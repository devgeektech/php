<?php
class ControllerAccountAccount extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/account');
		$this->load->model('account/customer');
		$this->load->model('account/address');

		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
		if($customer_info){
			$data['customer_info'] = $customer_info;
			$address_id = $this->customer->getAddressId();
			$address = $this->model_account_address->getAddress($address_id); 
			//print_r($address);
			$data['customer_address'] = $address;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 
		
		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['address'] = $this->url->link('account/address', '', true);
		
		$data['credit_cards'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/extension/credit_card/*.php');
		
		foreach ($files as $file) {
			$code = basename($file, '.php');
			
			if ($this->config->get('payment_' . $code . '_status') && $this->config->get('payment_' . $code . '_card')) {
				$this->load->language('extension/credit_card/' . $code, 'extension');

				$data['credit_cards'][] = array(
					'name' => $this->language->get('extension')->get('heading_title'),
					'href' => $this->url->link('extension/credit_card/' . $code, '', true)
				);
			}
		}
		
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		
		if ($this->config->get('total_reward_status')) {
			$data['reward'] = $this->url->link('account/reward', '', true);
		} else {
			$data['reward'] = '';
		}		
		
		$data['return'] = $this->url->link('account/return', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['recurring'] = $this->url->link('account/recurring', '', true);
		
		$this->load->model('account/customer');
		

                $this->load->model('catalog/product');
                //check if admin is logged in as a customer 
                if (!empty($this->session->data['user_id'])) {
                    $currentCSADetails = $this->model_catalog_product->getCustomerCSAFromSession();
                    if(!empty($currentCSADetails)) {
                        $customer_info['csa_id'] = $currentCSADetails['csa_id'];
                    }
                }
                $csa_info = $this->model_catalog_product->getCSA($customer_info['csa_id']);
                $data['csa_info'] = '';
                if(!empty($csa_info)) {
                    $data['csa_info'] = $csa_info;
                    $data['operating_hours'] = html_entity_decode($csa_info['operating_hours'], ENT_QUOTES, 'UTF-8');
                    $data['pickup_address'] = html_entity_decode($csa_info['pickup_address'], ENT_QUOTES, 'UTF-8');
                }
             
		$affiliate_info = $this->model_account_customer->getAffiliate($this->customer->getId());
		
		if (!$affiliate_info) {	
			$data['affiliate'] = $this->url->link('account/affiliate/add', '', true);
		} else {
			$data['affiliate'] = $this->url->link('account/affiliate/edit', '', true);
		}
		
		if ($affiliate_info) {		
			$data['tracking'] = $this->url->link('account/tracking', '', true);
		} else {
			$data['tracking'] = '';
		}
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('account/account', $data));
	}


                public function change_csa() {
                    $this->document->setTitle('Change CSA');
                    $data['breadcrumbs'] = array();
                    $data['breadcrumbs'][] = array(
                            'text' => $this->language->get('text_home'),
                            'href' => $this->url->link('common/home')
                    );
                    $data['breadcrumbs'][] = array(
                            'text' => 'Change CSA',
                            'href' => $this->url->link('account/change_csa', '', true)
                    );
                    $this->load->model('catalog/product');
                    
                    if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
                        //check if admin is logged in as a customer 
                        if (!empty($this->session->data['user_id'])) {
                            $csaInfo = $this->model_catalog_product->getCSA($this->request->post['csa_id']);
                            if(!empty($csaInfo)) {
                                $csa_customer_group_id = $csaInfo['customer_group_id'];
                                $this->customer->setCustomerGroupId($csa_customer_group_id); 
                            } 
                        } else {
                            $this->model_catalog_product->updateCustomerCSA($this->request->post);
                        }
                    }
                    $data['csa_details'] = $this->model_catalog_product->getCSADetails();

                    $current_csa = $this->model_catalog_product->getCustomerCSA();
                    $data['current_csa'] = '';
                    if(!empty($current_csa)) {
                         $data['current_csa'] = $current_csa['csa_id'];
                    }
                
                    //check if admin is logged in as a customer 
                    if (!empty($this->session->data['user_id'])) {
                        $currentCSADetails = $this->model_catalog_product->getCustomerCSAFromSession();
                        if(!empty($currentCSADetails)) {
                            $data['current_csa'] = $currentCSADetails['csa_id'];
                        }
                    }
                    $data['column_left'] = $this->load->controller('common/column_left');
                    $data['column_right'] = $this->load->controller('common/column_right');
                    $data['content_top'] = $this->load->controller('common/content_top');
                    $data['content_bottom'] = $this->load->controller('common/content_bottom');
                    $data['footer'] = $this->load->controller('common/footer');
                    $data['header'] = $this->load->controller('common/header');

                    $this->response->setOutput($this->load->view('account/change_csa', $data));
                }

                public function get_csa_details() {
                        $json = array();
                        $this->load->model('catalog/product');
                        $csa_details = $this->model_catalog_product->getCSADetails($this->request->get['csa_id']);

                        if(!empty($csa_details)) {
                            $csa_detail = $csa_details[0];
                            $json = array(
                                'description' => html_entity_decode($csa_detail['description'], ENT_QUOTES, 'UTF-8'),
                                'pickup_address' => html_entity_decode($csa_detail['pickup_address'], ENT_QUOTES, 'UTF-8'),
                                'operating_hours' => html_entity_decode($csa_detail['description'], ENT_QUOTES, 'UTF-8'),
                                'csa_email' => $csa_detail['csa_email'],
                                'csa_phone' => $csa_detail['csa_phone'],
                                'website' => $csa_detail['website'],
                                'brochure_link' => $csa_detail['brochure_link'],
                                'checkout_volunteer_messages' => html_entity_decode($csa_detail['checkout_volunteer_messages'], ENT_QUOTES, 'UTF-8'),
                            );
                        }
                        $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode($json));;
                }
            
	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}