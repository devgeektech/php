<?php
class ModelExtensionModuleWarehouse extends Model {
	public function addwarehouse($data) {

		if(empty($data['states'])) {
			$data['states'] = array();
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "warehouse SET name = '" . $this->db->escape($data['name']) . "', zone_id = '".(int)$data['zone_id']."', country_id = '".(int)$data['country_id']."', image = '" . $this->db->escape($data['image']) . "', comment = '" . $this->db->escape($data['comment']) . "',geolocation = '" . $this->db->escape($data['geolocation']) . "', contactperson_image = '" . $this->db->escape($data['contactperson_image']) . "',contactperson_name = '" . $this->db->escape($data['contactperson_name']) . "',contactperson_mobile = '" . $this->db->escape($data['contactperson_mobile']) . "',contactperson_phone = '" . $this->db->escape($data['contactperson_phone']) . "', zoneids = '".$this->db->escape(implode(",", $data['states']))."', sort_order = '".(int)$data['sort_order']."'");

		$warehouse_id = $this->db->getLastId();

		// Product
		$query = $this->db->query("SELECT DISTINCT product_id FROM " . DB_PREFIX . "product");

		foreach ($query->rows as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_warehouse SET product_id = '" . (int)$product['product_id'] . "', warehouse_id = '" . (int)$warehouse_id . "', qty = 0");
		}

		//product option
		$query = $this->db->query("SELECT product_option_value_id,product_id FROM " . DB_PREFIX . "product_option_value");

		foreach ($query->rows as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_to_warehouse SET product_id = '" . (int)$product['product_id'] . "', warehouse_id = '" . (int)$warehouse_id . "', qty = 0, product_option_value_id = '" . (int)$product['product_option_value_id'] . "'");
		}
	}

	public function editwarehouse($warehouse_id, $data) {

		if(empty($data['states'])) {
			$data['states'] = array();
		}

		$this->db->query("UPDATE " . DB_PREFIX . "warehouse SET name = '" . $this->db->escape($data['name']) . "', zone_id = '".(int)$data['zone_id']."', country_id = '".(int)$data['country_id']."', image = '" . $this->db->escape($data['image']) . "', comment = '" . $this->db->escape($data['comment']) . "', geolocation = '" . $this->db->escape($data['geolocation']) . "', contactperson_image = '" . $this->db->escape($data['contactperson_image']) . "',contactperson_name = '" . $this->db->escape($data['contactperson_name']) . "',contactperson_mobile = '" . $this->db->escape($data['contactperson_mobile']) . "',contactperson_phone = '" . $this->db->escape($data['contactperson_phone']) . "', zoneids = '".$this->db->escape(implode(",", $data['states']))."', sort_order = '".(int)$data['sort_order']."' WHERE warehouse_id = '" . (int)$warehouse_id . "'");
	}

	public function deletewarehouse($warehouse_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "warehouse WHERE warehouse_id = " . (int)$warehouse_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_warehouse WHERE warehouse_id = " . (int)$warehouse_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_to_warehouse WHERE warehouse_id = " . (int)$warehouse_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "warehouse_order_product WHERE warehouse_id = " . (int)$warehouse_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "warehouse_to_customergroup WHERE warehouse_id = " . (int)$warehouse_id);
	}

	public function getwarehouse($warehouse_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "warehouse WHERE warehouse_id = '" . (int)$warehouse_id . "'");

		return $query->row;
	}

	public function  getGroupsById($product_id){
		$product_majorcity_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_warehouse WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $key => $value) {
			$product_majorcity_data[$value['warehouse_id']] =  $value['qty'];
		}

		
		return $product_majorcity_data;
	}

	public function  getQtyByWarehouseProducrId($product_id,$warehouse_id){

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_warehouse WHERE product_id = '" . (int)$product_id . "' AND warehouse_id = '" . (int)$warehouse_id . "'");
		
		if($query->num_rows) {
			return $query->row['qty'];
		} else {
			return 0;
		}
		
	}

	public function  getOptionsGroupsById($product_option_value_id){
		$product_majorcity_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_to_warehouse WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
		
		foreach ($query->rows as $key => $value) {
			$product_majorcity_data[$value['warehouse_id']] =  $value['qty'];
		}

		
		return $product_majorcity_data;
	}

	public function  getWarehouseCustomerGroup($customer_group_id){
		$customgroup_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "warehouse_to_customergroup WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		
		foreach ($query->rows as $key => $value) {
			$customgroup_data[] =  $value['warehouse_id'];
		}
		
		return $customgroup_data;
	}

	public function  getWarehouseTransactionById($order_id,$order_product_id,$product_id,$order_option_id = 0,$product_option_value_id = 0){
		$product_majorcity_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "warehouse_transaction WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "' AND product_id = '" . (int)$product_id . "' AND order_option_id = '" . (int)$order_option_id . "' AND product_option_value_id = '" . (int)$product_option_value_id . "'");
		
		foreach ($query->rows as $key => $value) {
			$product_majorcity_data[$value['warehouse_id']] =  $value['qty'];
		}
		
		return $product_majorcity_data;
	}

	public function getProductLocations($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_warehouse WHERE product_id = '" . (int)$product_id . "'");
		return $query->rows;
	}

	public function editToken($order_id,$token) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_warehouse WHERE product_id = '" . (int)$product_id . "'");
	}

	public function updateQty($product_id,$qty) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '".(int)$qty."' WHERE product_id = '".(int)$product_id."'");
	}

	public function updateOptionQty($product_id,$product_option_value_id,$qty) {
		$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = '".(int)$qty."' WHERE product_id = '".(int)$product_id."' AND product_option_value_id = '".(int)$product_option_value_id."'");
	}

	public function saveProductWarehouse($warehouse,$product_id) {
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_warehouse WHERE product_id = '" . (int)$product_id . "'");
		foreach ($warehouse as $key => $value) {
		  if(isset($key) && $key) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_warehouse SET product_id = '" . (int)$product_id . "', warehouse_id = '" . (int)$key . "',qty = '" . (int)$value['qty'] . "'");
			}
		}	
	}

	public function saveProductOptionWarehouse($warehouse,$product_id,$product_option_value_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_to_warehouse WHERE product_id = '".(int)$product_id."' AND product_option_value_id = '" . (int)$product_option_value_id . "'");
		foreach ($warehouse as $key => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_to_warehouse SET product_option_value_id = '" . (int)$product_option_value_id . "', warehouse_id = '" . (int)$key . "', product_id = '".(int)$product_id."', qty = '" . (int)$value['qty'] . "'");
		}
	}

	public function saveWarehouseCustomerGroup($customer_group_id,$data=array()) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "warehouse_to_customergroup WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		if (isset($data['cg_warehouse'])) {
			foreach ($data['cg_warehouse'] as $warehouse) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "warehouse_to_customergroup SET customer_group_id
				 = '" . (int)$customer_group_id . "', warehouse_id = '" . (int)$warehouse . "'");
			}
		}
	}

	public function saveOrderStockWarehouse($data = array()) {
		
		$order_id = $data['order_id'];
		
		//Restock
		$this->reStockWarehouse($order_id);

		foreach ($data['product'] as $key => $value) {

			//Inserting in transaction table
			foreach ($value['warehouse'] as $warehouse_id => $qty) {
				if($qty) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "warehouse_transaction SET order_id = '" . (int)$order_id . "', name = '".$this->db->escape($value['name'])."', order_product_id = '" . (int)$value['order_product_id'] . "', order_option_id = '" . (int)$value['order_option_id'] . "', product_option_value_id = '" . (int)$value['product_option_value_id'] . "', warehouse_id = '" . (int)$warehouse_id . "', product_id = '".(int)$value['product_id']."', qty = '" . (int)$qty . "', date_added = NOW() ");
				}
			}

			// Need to reduce stock from main table
			if($value['order_option_id']) {
				$this->updateStockOptionWarehouse($value['product_id'],$value['product_option_value_id'],$value['warehouse']);
			} else {
				$this->updateStockProductWarehouse($value['product_id'],$value['warehouse']);
			}

		}

		//Removing from Cron Jobs
		$this->removeFromCrobJobs($order_id);

		//Removing from Order Stock Product as admin has manually edited
		$this->removeFromOrderStockProduct($order_id);
	}

	public function removeFromCrobJobs($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "warehouse_cronjobs` WHERE order_id = '" . (int)$order_id . "'");
	}

	public function removeFromOrderStockProduct($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "warehouse_order_product` WHERE order_id = '" . (int)$order_id . "'");
	}

	public function updateStockProductWarehouse($product_id,$warehouses) {
		foreach ($warehouses as $warehouse_id => $qty) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_warehouse WHERE product_id = '" . (int)$product_id . "' AND warehouse_id = '".(int)$warehouse_id."'");
			if($query->num_rows) {
				$this->db->query("UPDATE " . DB_PREFIX . "product_to_warehouse SET qty = qty - '".(int)$qty."' WHERE product_id = '" . (int)$product_id . "' AND warehouse_id = '".(int)$warehouse_id."'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_warehouse SET product_id = '" . (int)$product_id . "', warehouse_id = '" . (int)$warehouse_id . "', qty = qty - '" . (int)$qty . "'");
			}
		}
	}

	public function reStockWarehouse($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "warehouse_transaction WHERE order_id = '" . (int)$order_id . "'");
		foreach ($query->rows as $key => $value) {
			if($value['product_option_value_id']) {
				$this->db->query("UPDATE " . DB_PREFIX . "product_option_to_warehouse SET  qty = qty + '" . (int)$value['qty'] . "' WHERE product_option_value_id = '" . (int)$value['product_option_value_id'] . "' AND warehouse_id = '" . (int)$value['warehouse_id'] . "' AND product_id = '".(int)$value['product_id']."'");
			} else {
				$this->db->query("UPDATE " . DB_PREFIX . "product_to_warehouse SET  qty = qty + '" . (int)$value['qty'] . "' WHERE product_id = '".(int)$value['product_id']."' AND warehouse_id = '" . (int)$value['warehouse_id'] . "'");
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "warehouse_transaction WHERE order_id = '" . (int)$order_id . "'");
	}

	public function updateStockOptionWarehouse($product_id,$product_option_value_id,$warehouses) {

		foreach ($warehouses as $warehouse_id => $qty) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_to_warehouse WHERE product_id = '" . (int)$product_id . "' AND  product_option_value_id = '" . (int)$product_option_value_id . "' AND warehouse_id = '".(int)$warehouse_id."'");
			if($query->num_rows) {
				$this->db->query("UPDATE " . DB_PREFIX . "product_option_to_warehouse SET qty = qty - '".(int)$qty."' WHERE product_id = '" . (int)$product_id . "' AND  product_option_value_id = '" . (int)$product_option_value_id . "' AND warehouse_id = '".(int)$warehouse_id."'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_to_warehouse SET product_id = '" . (int)$product_id . "', warehouse_id = '" . (int)$warehouse_id . "', qty = qty - '" . (int)$qty . "', product_option_value_id = '" . (int)$product_option_value_id . "'");
			}
		}
	}

	public function getwarehouses($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "warehouse";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalwarehouses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "warehouse");

		return $query->row['total'];
	}

	public function getZoneName($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '".(int)$zone_id."'");

		
		$zone_name = isset($query->row['name'])?$query->row['name']:'';
                return $zone_name;
            
	}

	public function getTransactions($data = array()) {
		$sql = "SELECT wt.warehouse_transaction_id,wt.order_id,w.name as warehouse,wt.name,wt.qty,wt.date_added FROM " . DB_PREFIX . "warehouse_transaction wt LEFT JOIN " . DB_PREFIX . "warehouse w ON (w.warehouse_id = wt.warehouse_id) WHERE 1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND wt.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_warehouse'])) {
			$sql .= " AND w.name LIKE '" . $this->db->escape($data['filter_warehouse']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND wt.order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND wt.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (!empty($data['filter_date'])) {
			$sql .= " AND DATE(wt.date_added) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		}

		$sql .= " GROUP BY wt.warehouse_transaction_id";

		$sort_data = array(
			'wt.name',
			'w.name',
			'wt.order_id',
			'wt.quantity',
			'wt.warehouse_id'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY wt.warehouse_transaction_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalTransactions($data = array()) {

		$sql = "SELECT COUNT(DISTINCT wt.warehouse_transaction_id) AS total FROM " . DB_PREFIX . "warehouse_transaction wt LEFT JOIN " . DB_PREFIX . "warehouse w ON (w.warehouse_id = wt.warehouse_id) WHERE 1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND wt.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_warehouse'])) {
			$sql .= " AND w.name LIKE '" . $this->db->escape($data['filter_warehouse']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND wt.order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND wt.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (!empty($data['filter_date'])) {
			$sql .= " AND DATE(wt.date_added) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		}

		$sql .= " GROUP BY wt.warehouse_transaction_id";

		$sort_data = array(
			'wt.name',
			'w.name',
			'wt.order_id',
			'wt.quantity',
			'wt.warehouse_id'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY wt.warehouse_transaction_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		$query = $this->db->query($sql);
		if($query->num_rows) {
			return $query->row['total'];
		}
		return 0;
	}

	public function getProductsForCsv($data = array()) {
			
		$query = array();

		$sql = "SELECT DISTINCT pt.product_id,p.name AS productname,p.product_id as id, pt.sku, pt.price, pt.product_type FROM " . DB_PREFIX . "product_description p  LEFT JOIN " . DB_PREFIX . "product pt ON (pt.product_id = p.product_id) ";

		if (isset($data['filter_category_id']) && $data['filter_category_id'] !== null) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (pt.product_id = p2c.product_id) ";
		}

		$sql .= " WHERE  p.language_id = '".(int)$this->config->get('config_language_id')."' ";


		if (isset($data['filter_category_id']) && $data['filter_category_id'] !== null) {
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}

		if (isset($data['filter_manufacturer_id']) && $data['filter_manufacturer_id'] !== null) {
			$sql .= " AND pt.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== null) {
			$sql .= " AND pt.status = '" . (int)$data['filter_status'] . "'";
		}


			// warehouse_export_customization
			if (isset($data['filter_product_type']) && $data['filter_product_type'] !== null) {
				$sql .= " AND pt.product_type IN (" . $data['filter_product_type'] . ")";
			}

			if (isset($data['filter_harvest_id']) && $data['filter_harvest_id'] !== null) {
				$sql .= " AND pt.harvest_id = '" . (int)$data['filter_harvest_id'] . "'";
			}
			// -- warehouse_export_customization
			
		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'p.name'

			// warehouse_export_customization
			,'pt.product_id',
			// -- warehouse_export_customization
			
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getProductEditView($data = array()) {
			
		$query = array();

		$sql = "SELECT DISTINCT pt.product_id,pt.subtract,pt.model,pt.sku,pt.upc,pt.image,p.name AS names, pt.quantity AS pquant,pt.price AS price, p.product_id as id, pt.stock_status_id as stock_id FROM " . DB_PREFIX . "product_description p  LEFT JOIN " . DB_PREFIX . "product pt ON (pt.product_id = p.product_id) ";

		if (isset($data['filter_category_id']) && $data['filter_category_id'] !== null) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (pt.product_id = p2c.product_id) ";
		}

		$sql .= " WHERE  p.language_id = '".(int)$this->config->get('config_language_id')."' ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND p.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}


		if (isset($data['filter_harvest_id']) && $data['filter_harvest_id'] !== '') {
                    if($data['filter_zero_harvest_id']) {
                        $sql .= " AND (pt.harvest_id = '" . (int)$data['filter_harvest_id'] . "' OR pt.harvest_id = '0' ) ";
                    } else {
                        $sql .= " AND pt.harvest_id = '" . (int)$data['filter_harvest_id'] . "'";
                    }
		}
                if (isset($data['filter_product_type']) && $data['filter_product_type'] !== '') {
                        if($data['filter_product_type'] == '0') {//For All Shares display normal shares, mandatory shares and suggested shares.
                            $sql .= " AND p.product_type IN (3,4) ";
                        } elseif($data['filter_product_type'] != '0' && $data['filter_product_type'] != '*') {
                            $sql .= " AND p.product_type = '" . (int)$data['filter_product_type'] . "' ";
                        }
                }
            
		if (!empty($data['filter_model'])) {
			$sql .= " AND pt.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_sku'])) {
			$sql .= " AND pt.sku LIKE '" . $this->db->escape($data['filter_sku']) . "%'";
		}
		
		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== null) {
			$sql .= " AND pt.quantity <= '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_category_id']) && $data['filter_category_id'] !== null) {
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}

		if (isset($data['filter_manufacturer_id']) && $data['filter_manufacturer_id'] !== null) {
			$sql .= " AND pt.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== null) {
			$sql .= " AND pt.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'p.name',
			'pt.quantity'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);
		
		return $query->rows;
	}


	public function getProductEditViewTotal($data = array()) {
			
		$query = array();

		$sql = "SELECT DISTINCT pt.product_id,pt.model,pt.sku,pt.upc,pt.image,p.name AS names, pt.quantity AS pquant, p.product_id as id, pt.stock_status_id as stock_id FROM " . DB_PREFIX . "product_description p  LEFT JOIN " . DB_PREFIX . "product pt ON (pt.product_id = p.product_id) ";

		if (isset($data['filter_category_id']) && $data['filter_category_id'] !== null) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (pt.product_id = p2c.product_id) ";
		}

		$sql .= " WHERE  p.language_id = '".(int)$this->config->get('config_language_id')."' ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND p.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}


		if (isset($data['filter_harvest_id']) && $data['filter_harvest_id'] !== '') {
                    if($data['filter_zero_harvest_id']) {
                        $sql .= " AND (pt.harvest_id = '" . (int)$data['filter_harvest_id'] . "' OR pt.harvest_id = '0' ) ";
                    } else {
                        $sql .= " AND pt.harvest_id = '" . (int)$data['filter_harvest_id'] . "'";
                    }
		}
                if (isset($data['filter_product_type']) && $data['filter_product_type'] !== '') {
                        if($data['filter_product_type'] == '0') {//For All Shares display normal shares, mandatory shares and suggested shares.
                            $sql .= " AND p.product_type IN (3,4) ";
                        } elseif($data['filter_product_type'] != '0' && $data['filter_product_type'] != '*') {
                            $sql .= " AND p.product_type = '" . (int)$data['filter_product_type'] . "' ";
                        }
                }
            
		if (!empty($data['filter_model'])) {
			$sql .= " AND pt.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_sku'])) {
			$sql .= " AND pt.sku LIKE '" . $this->db->escape($data['filter_sku']) . "%'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== null) {
			$sql .= " AND pt.quantity <= '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_category_id']) && $data['filter_category_id'] !== null) {
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}

		if (isset($data['filter_manufacturer_id']) && $data['filter_manufacturer_id'] !== null) {
			$sql .= " AND pt.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== null) {
			$sql .= " AND pt.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.product_id ";
		
		$query = $this->db->query($sql);
		
		return $query->num_rows;
	}

	public function getProductOptions($product_id,$data = array()) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY od.name ASC");
		
		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$sql = "SELECT *,ovd.name as optionname FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ovd.option_value_id = pov.option_value_id) ";

			$sql .= "WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			if (isset($data['filter_subtract']) && $data['filter_subtract'] !== null) {
				$sql .= " AND pov.subtract = '" . (int)$data['filter_subtract'] . "'";
			}

			$sql .= " ORDER BY ovd.name ASC";
			
			$product_option_value_query = $this->db->query($sql);
			
			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'optionname'         	  => $product_option_value['optionname'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function checkProductSubtract($product_id) {
		$query = $this->db->query("SELECT subtract FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "' AND subtract = '1'");
		if($query->num_rows) {
			return 1;
		} else {
			return 0;
		}
	}

	public function checkProductOptionSubtract($product_option_value_id) {
		$query = $this->db->query("SELECT subtract FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . (int)$product_option_value_id . "' AND subtract = '1'");
		if($query->num_rows) {
			return 1;
		} else {
			return 0;
		}
	}

	public function addWarehouseQtyProduct($details) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_warehouse WHERE product_id = '" . (int)$details['product_id'] . "' AND warehouse_id = '".(int)$details['warehouse_id']."'");
		if($query->num_rows) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_to_warehouse SET qty = qty + '".(int)$details['qty']."' WHERE product_id = '" . (int)$details['product_id'] . "' AND warehouse_id = '".(int)$details['warehouse_id']."'");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_warehouse SET product_id = '" . (int)$details['product_id'] . "', warehouse_id = '" . (int)$details['warehouse_id'] . "', qty = '" . (int)$details['qty'] . "'");
		}
	}

	public function addWarehouseQtyProductOption($details) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_to_warehouse WHERE product_id = '" . (int)$details['product_id'] . "' AND product_option_value_id = '" . (int)$details['optionid'] . "' AND warehouse_id = '".(int)$details['warehouse_id']."'");
		if($query->num_rows) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_option_to_warehouse SET qty = qty + '".(int)$details['qty']."' WHERE product_id = '" . (int)$details['product_id'] . "' AND product_option_value_id = '" . (int)$details['optionid'] . "' AND warehouse_id = '".(int)$details['warehouse_id']."'");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_to_warehouse SET product_id = '" . (int)$details['product_id'] . "', warehouse_id = '" . (int)$details['warehouse_id'] . "',product_option_value_id = '" . (int)$details['optionid'] . "', qty = '" . (int)$details['qty'] . "'");
		}
	}

	public function deleteAndAddWarehouseQtyProduct($details) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_warehouse WHERE product_id = '" . (int)$details['product_id'] . "' AND warehouse_id = '" . (int)$details['warehouse_id'] . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_warehouse SET product_id = '" . (int)$details['product_id'] . "', warehouse_id = '" . (int)$details['warehouse_id'] . "',qty = '" . (int)$details['qty'] . "'");
	}

	public function deleteAndAddWarehouseQtyProductOption($details) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_to_warehouse WHERE product_id = '" . (int)$details['product_id'] . "' AND product_option_value_id = '" . (int)$details['optionid'] . "' AND warehouse_id = '" . (int)$details['warehouse_id'] . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_to_warehouse SET product_id = '" . (int)$details['product_id'] . "', warehouse_id = '" . (int)$details['warehouse_id'] . "',product_option_value_id = '" . (int)$details['optionid'] . "', qty = '" . (int)$details['qty'] . "'");
	}

	public function bulkAddWarehouse($details,$sum_it,$main_qty) {
		if(isset($details['product_id']) && isset($details['warehouse_id'])) {
			if($sum_it) {
				if($details['optionid']) {
					$this->addWarehouseQtyProductOption($details);
				} else {
					$this->addWarehouseQtyProduct($details);
				}
			} else {
				if($details['optionid']) {
					$this->deleteAndAddWarehouseQtyProductOption($details);
				} else {
					$this->deleteAndAddWarehouseQtyProduct($details);
				}	
			}

			if($main_qty) {

				if($details['optionid']) {
					$query = $this->db->query("SELECT SUM(pow.qty) as totalqty FROM " . DB_PREFIX . "product_option_to_warehouse pow LEFT JOIN " . DB_PREFIX . "warehouse w ON (pow.warehouse_id = w.warehouse_id) WHERE pow.product_id = '" . (int)$details['product_id'] . "' AND product_option_value_id = '".(int)$details['optionid']."'");

					if($query->num_rows) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = '" . (int)$query->row['totalqty'] . "'  WHERE product_option_value_id = '" . (int)$details['optionid'] . "'");
					}
				} else {
					$query = $this->db->query("SELECT SUM(pw.qty) as totalqty FROM " . DB_PREFIX . "product_to_warehouse pw LEFT JOIN " . DB_PREFIX . "warehouse w ON (pw.warehouse_id = w.warehouse_id) WHERE pw.product_id = '" . (int)$details['product_id'] . "'");

					if($query->num_rows) {
						$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$query->row['totalqty'] . "'  WHERE product_id = '" . (int)$details['product_id'] . "'");
					}
				}	
			}
		}	
	}

	public function  getQtyByWarehouseProducrIdOptionID($product_id,$product_option_value_id,$warehouse_id){

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_to_warehouse WHERE product_id = '" . (int)$product_id . "' AND product_option_value_id = '" . (int)$product_option_value_id . "' AND warehouse_id = '" . (int)$warehouse_id . "'");
		
		if($query->num_rows) {
			return $query->row['qty'];
		} else {
			return 0;
		}
		
	}

	public function getManufacturers() {
		$retundata = array();
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "manufacturer m ORDER BY name ASC");
		foreach ($query->rows as $key => $value) {
			$retundata[] = array("manufacturer_id"=>$value['manufacturer_id'],'name'=>$value['name']);
		}
		return $retundata;
	}

	public function getStates($data = array()) {
		$sql = "SELECT zone_id,name FROM " . DB_PREFIX . "zone WHERE 1 = 1 ";

		if(!empty($data['filter_name'])) {
			$sql .= " AND (name LIKE '".$this->db->escape($data['filter_name'])."%' OR code = '".$this->db->escape($data['filter_name'])."') ";
		}

		if(!empty($data['filter_country_id'])) {
			$sql .= " AND country_id = '".(int)$data['filter_country_id']."' ";
		}

		$sql .= " ORDER BY name";

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);

		return $query->rows;
	}


			// warehouse_export_customization
			public function getCustomerGroupWarehouse($warehouse_id) {
				$customer_group = array();
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "warehouse_to_customergroup WHERE warehouse_id = '" . (int)$warehouse_id . "'");
				
				foreach ($query->rows as $key => $value) {
					$customer_group[] =  $value['customer_group_id'];
				}
				
				return $customer_group;
			}

			public function getCustomerGroupCsa($warehouse_id) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "csa WHERE warehouse_id = '" . (int)$warehouse_id . "'");
				return $query->row;
			}
			// -- warehouse_export_customization
			
	public function createTable() {
		//$this->db->query("DROP TABLE  `". DB_PREFIX ."warehouse`");
		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."warehouse'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "warehouse` (
				  `warehouse_id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) NOT NULL,
				  `zone_id` int(11) NOT NULL,
				  `country_id` int(11) NOT NULL,
				  `sort_order` int(11) NOT NULL,
				  `image` varchar(255) NOT NULL,
				  `comment` text NOT NULL,
				  PRIMARY KEY (`warehouse_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1";
            $this->db->query($sql);
        }

        $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "warehouse` LIKE  'zoneids'";
        $result = $this->db->query($sql)->num_rows;
        if(!$result) {
        	$this->db->query("ALTER TABLE `" . DB_PREFIX . "warehouse` ADD `zoneids` varchar(512) NOT NULL");
        }

        $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "warehouse` LIKE  'geolocation'";
        $result = $this->db->query($sql)->num_rows;
        if(!$result) {
        	$this->db->query("ALTER TABLE `" . DB_PREFIX . "warehouse` ADD `geolocation` varchar(1024) NOT NULL,ADD `contactperson_image` varchar(255) DEFAULT NULL,ADD  `contactperson_name` varchar(32) NOT NULL,ADD `contactperson_mobile` varchar(32) NOT NULL,ADD `contactperson_phone` varchar(32) NOT NULL");
        }
        
        //$this->db->query("DROP TABLE  `". DB_PREFIX ."warehouse_cronjobs`");
		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."warehouse_cronjobs'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "warehouse_cronjobs` (
				  `warehouse_cronjobs_id` int(11) NOT NULL AUTO_INCREMENT,
				  `order_id` int(11) NOT NULL,
				  PRIMARY KEY (`warehouse_cronjobs_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1";
            $this->db->query($sql);
        }

        //$this->db->query("DROP TABLE  `". DB_PREFIX ."product_to_warehouse`");
        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."product_to_warehouse'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_to_warehouse` (
				  `product_id` int(11) NOT NULL,
				  `warehouse_id` int(11) NOT NULL,
				  `qty` int(11) NOT NULL,
				  PRIMARY KEY (`product_id`,`warehouse_id`),
				  KEY `warehouse_id` (`warehouse_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
            $this->db->query($sql);
        }

        //$this->db->query("DROP TABLE  `". DB_PREFIX ."product_option_to_warehouse`");
        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."product_option_to_warehouse'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_option_to_warehouse` (
				  `product_option_value_id` int(11) NOT NULL,
				  `warehouse_id` int(11) NOT NULL,
				  `product_id` int(11) NOT NULL,
				  `qty` int(11) NOT NULL,
				  PRIMARY KEY (`product_option_value_id`,`warehouse_id`),
				  KEY `warehouse_id` (`warehouse_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
            $this->db->query($sql);
        }

        //$this->db->query("DROP TABLE  `". DB_PREFIX ."warehouse_transaction`");
        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."warehouse_transaction'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "warehouse_transaction` (
				  `warehouse_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
				  `order_id` int(11) NOT NULL,
				  `warehouse_id` int(11) NOT NULL,
				  `name` varchar(256) NOT NULL,
				  `order_product_id` int(11) NOT NULL,
				  `product_id` int(11) NOT NULL,
				  `order_option_id` int(11) NOT NULL,
				  `product_option_value_id` int(11) NOT NULL,
				  `qty` int(11) NOT NULL,
				  `date_added` datetime NOT NULL,
				  PRIMARY KEY (`warehouse_transaction_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
            $this->db->query($sql);
        }

        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."warehouse_order_product'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "warehouse_order_product` (
			  `warehouse_order_product_id` int(11) NOT NULL AUTO_INCREMENT,
			  `order_id` int(11) NOT NULL,
			  `order_product_id` int(11) NOT NULL,
			  `warehouse_id` int(11) NOT NULL,
			  PRIMARY KEY (`warehouse_order_product_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
			$this->db->query($sql);
        }

        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."warehouse_to_customergroup'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "warehouse_to_customergroup` (
			  `customer_group_id` int(11) NOT NULL,
  			  `warehouse_id` int(11) NOT NULL,
			  PRIMARY KEY (`customer_group_id`,`warehouse_id`),
  			  KEY `warehouse_id` (`warehouse_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
			$this->db->query($sql);
        }

	}
}
