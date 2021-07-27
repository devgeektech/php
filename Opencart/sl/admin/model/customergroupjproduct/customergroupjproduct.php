<?php
class ModelCustomerGroupJProductCustomergroupJProduct extends Model {
	public function addProductCustomergroup($data) {

		// don't do anything, if customer group ids not found
		if (!isset($data['customer_group_ids'])) {
			$data['customer_group_ids'] = array();
		}

		/*if (empty($data['customer_group_ids'])) {
			return;
		}*/

		if (isset($data['all_products']) && $data['all_products'] == 1) {
			$data['products'] = array();
			$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product");

			// delete records
			$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_customer_group");

			foreach ($data['customer_group_ids'] as $customer_group_id) {
				foreach ($query->rows as $row) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_customer_group SET product_id = '" . (int)$row['product_id'] . "', customer_group_id = '" . (int)$customer_group_id . "'");
				}
			}
		}

		if (isset($data['all_categories']) && $data['all_categories'] == 1) {
			$data['categories'] = array();
			$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category");

			// delete records
			$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "category_customer_group");

			foreach ($data['customer_group_ids'] as $customer_group_id) {
				foreach ($query->rows as $row) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_customer_group SET category_id = '" . (int)$row['category_id'] . "', customer_group_id = '" . (int)$customer_group_id . "'");
				}
			}
		}

		if (!empty($data['categories']) && is_array($data['categories'])) {
			// delete records
			$this->db->query("DELETE FROM " . DB_PREFIX . "category_customer_group WHERE category_id IN ('". implode("','", $data['categories']) ."')");

			foreach ($data['customer_group_ids'] as $customer_group_id) {
				foreach ($data['categories'] as $category_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_customer_group SET category_id = '" . (int)$category_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
				}
			}
		}

		if (!empty($data['products']) && is_array($data['products'])) {
			// delete records
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_customer_group WHERE product_id in ('". implode("','", $data['products']) ."')");

			foreach ($data['customer_group_ids'] as $customer_group_id) {
				foreach ($data['products'] as $product_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_customer_group SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
				}
			}
		}
	}

	public function CreateGroupProductTable() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_customer_group` ( `product_id` int(11) NOT NULL,  `customer_group_id` int(11) NOT NULL, PRIMARY KEY (`product_id`,`customer_group_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "category_customer_group` ( `category_id` int(11) NOT NULL,  `customer_group_id` int(11) NOT NULL, PRIMARY KEY (`category_id`,`customer_group_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	}
}