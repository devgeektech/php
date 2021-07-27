<?php
namespace Cart;
class Customer {
	private $customer_id;
	private $firstname;
	private $lastname;
	private $customer_group_id;
	private $email;
	private $telephone;
	private $newsletter;
	private $address_id;


                private $harvest_id;
                private $warehouse_id;
            
	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

                $this->log = $registry->get('log');
            

		if (isset($this->session->data['customer_id'])) {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND status = '1'");

			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				
                if(empty($this->session->data['customer_group_id'])) {
                    $this->customer_group_id = $customer_query->row['customer_group_id'];
                } elseif(!empty($this->session->data['customer_group_id'])) {
                    $this->customer_group_id = $this->session->data['customer_group_id'];
                }
                
                //find Warehouse(CSA/Warhouse) of logged in Customer from session customer_group_id
                $this->warehouse_id = $this->getWarehouseByGroupId($this->customer_group_id);
            
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->newsletter = $customer_query->row['newsletter'];
				$this->address_id = $customer_query->row['address_id'];

                if(empty($this->session->data['harvest_id'])) {
                    $harvest_query = $this->db->query("SELECT harvest_id FROM " . DB_PREFIX . "harvests WHERE status = '1' ORDER BY date_added DESC LIMIT 1");
                    if ($harvest_query->num_rows) {
                        $this->harvest_id = $harvest_query->row['harvest_id'];
                    }
                } elseif(!empty($this->session->data['harvest_id'])) {
                    $this->harvest_id = $this->session->data['harvest_id'];
                }
            

				$this->db->query("UPDATE " . DB_PREFIX . "customer SET language_id = '" . (int)$this->config->get('config_language_id') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$this->session->data['customer_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
				}
			} else {
				$this->logout();
			}
		}
	}

  public function login($email, $password, $override = false) {
		if ($override) {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1'");
		} else {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");
		}

		if ($customer_query->num_rows) {
			$this->session->data['customer_id'] = $customer_query->row['customer_id'];

                $harvest_query = $this->db->query("SELECT harvest_id FROM " . DB_PREFIX . "harvests WHERE status = '1' ORDER BY date_added DESC LIMIT 1");
                if ($harvest_query->num_rows) {
                    $this->harvest_id = $harvest_query->row['harvest_id'];
                }
            

			$this->customer_id = $customer_query->row['customer_id'];
			$this->firstname = $customer_query->row['firstname'];
			$this->lastname = $customer_query->row['lastname'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->newsletter = $customer_query->row['newsletter'];
			$this->address_id = $customer_query->row['address_id'];
		

                //this logic call will remove Cart items from the logged in customer if they dont belong to current season session.
                $sql= "DELETE c FROM " . DB_PREFIX . "cart c LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = c.product_id) WHERE c.customer_id = '".$this->customer_id."' AND (p.harvest_id !=0 AND p.harvest_id != '".$this->harvest_id."')
                    ";
                
                $this->db->query($sql);                    
            
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET language_id = '" . (int)$this->config->get('config_language_id') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		unset($this->session->data['customer_id']);

                if(!empty($this->session->data['customer_group_id'])) {
                    unset($this->session->data['customer_group_id']);
                }
                if(!empty($this->session->data['harvest_id'])) {
                    unset($this->session->data['harvest_id']);
                }
                $this->harvest_id = '';
            

		$this->customer_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->customer_group_id = '';
		$this->email = '';
		$this->telephone = '';
		$this->newsletter = '';
		$this->address_id = '';
	}

	public function isLogged() {
		return $this->customer_id;
	}

	public function getId() {
		return $this->customer_id;
	}


                public function harvestId() {
                    // if harvest id NULL -- in case of logged out customers get default harvest
					if ($this->harvest_id == 0) {
						$q = $this->db->query("SELECT harvest_id FROM " . DB_PREFIX . "harvests where status = 1");
						if (!empty($q->row)) {
							return $q->row['harvest_id'];
						}
					}
                    return $this->harvest_id;
                }

                /*when login customer from admin and then we wanted to change harvest season. Set it in session  **/
                public function setHarvestId($harvest_id) {
                    // if harvest id NULL -- in case of logged out customers get default harvest
					if ($harvest_id == 0) {
						$q = $this->db->query("SELECT harvest_id FROM " . DB_PREFIX . "harvests where status = 1");
						if (!empty($q->row)) {
							$harvest_id = $q->row['harvest_id'];
						}
					}
                    $this->session->data['harvest_id'] = $harvest_id;
                    return $this->harvest_id = $harvest_id;
                }
                
                /*when login customer from admin and then we wanted to change customer group. Set it in session  **/
                public function setCustomerGroupId($customer_group_id) {
                    $this->session->data['customer_group_id'] = $customer_group_id;
                    return $this->customer_group_id = $customer_group_id;
                }
                
                public function getWarehouseByGroupId($customer_group_id) {
                    $sql = "SELECT * FROM " . DB_PREFIX . "warehouse_to_customergroup WHERE customer_group_id = '". (int)$customer_group_id."' ";
                    $q = $this->db->query($sql);
                    return ($q->num_rows) ? $q->row['warehouse_id'] : 0;
                }
                
                public function getWarehouseId() {
                    return $this->warehouse_id;
                }
            
	public function getFirstName() {
		return $this->firstname;
	}

	public function getLastName() {
		return $this->lastname;
	}

	public function getGroupId() {
		return $this->customer_group_id;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getTelephone() {
		return $this->telephone;
	}

	public function getNewsletter() {
		return $this->newsletter;
	}

	public function getAddressId() {
		return $this->address_id;
	}

	public function getBalance() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$this->customer_id . "'");

		return $query->row['total'];
	}

	public function getRewardPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");

		return $query->row['total'];
	}
}
