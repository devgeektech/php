<?php
class ModelCustomerGroupJProductExport extends Model {
	public function getCategories($data = array()) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) ";

		if (isset($data['store_id']) && $data['store_id'] != '') {
			$sql .= " LEFT JOIN " . DB_PREFIX . "category_to_store c2s1 ON (cp.category_id = c2s1.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s2 ON (cp.category_id = c2s2.category_id) ";
		}

		$sql .= " WHERE c1.category_id>0 AND c2.category_id>0";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['store_id']) && $data['store_id'] != '') {
			$sql .= " AND c2s1.store_id = '" . (int)$data['store_id'] . "' AND c2s2.store_id = '" . (int)$data['store_id'] . "'";
		}

		if (isset($data['language_id']) && $data['language_id'] != '') {
			$sql .= " AND cd1.language_id = '" . (int)$data['language_id'] . "' AND cd2.language_id = '" . (int)$data['language_id'] . "'";
		}

		if (!empty($data['category_ids']) && is_array($data['category_ids'])) {
			$sql .= " AND cp.category_id IN ('" . implode("','", array_unique($data['category_ids'])) . "')";
		}

		if (isset($data['status']) && $data['status'] != '') {
			$sql .= " AND c1.status = '" . (int)$data['status'] . "' AND c2.status = '" . (int)$data['status'] . "'";
		}

		$sql .= " GROUP BY cp.category_id";

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

		if (isset($data['start']) && $data['start'] != '') {
			$sql .= " LIMIT ". (int)$data['start'] ."";
			if (isset($data['limit']) && $data['limit'] != '') {
				$sql .= " , ". (int)$data['limit'] ."";
			}
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}


	public function getCategoryCustomerGroups($category_id) {
		return $this->db->query("SELECT * FROM " . DB_PREFIX . "category_customer_group WHERE category_id ='". (int)$category_id ."'")->rows;
	}

	public function hasCategoryCustomerGroup($data) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_customer_group WHERE category_id='". (int)$data['category_id'] ."' AND customer_group_id='". (int)$data['customer_group_id'] ."'");
		return ($query->num_rows);
	}

	public function editCategoryCustomerGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "category_customer_group SET category_id='". (int)$data['category_id'] ."', customer_group_id='". (int)$data['customer_group_id'] ."'");
	}

	public function getProducts($data) {
		$sql = "SELECT p.product_id, pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		if (isset($data['store_id']) && $data['store_id'] != '') {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) ";
		}
		$sql .= " WHERE p.product_id > 0";
		if (isset($data['language_id']) && $data['language_id'] != '') {
			$sql .= " AND pd.language_id = '" . (int)$data['language_id'] . "'";
		}

		if (!empty($data['product_ids']) && is_array($data['product_ids'])) {
			$sql .= " AND p.product_id IN ('" . implode("','", array_unique($data['product_ids'])) . "')";
		}

		if (isset($data['store_id']) && $data['store_id'] != '') {
			$sql .= " AND p2s.store_id = '" . (int)$data['store_id'] . "'";
		}
		if (isset($data['status']) && $data['status'] != '') {
			$sql .= " AND p.status = '" . (int)$data['status'] . "'";
		}

		$sql .= " ORDER BY pd.name ASC";
		if (isset($data['start']) && $data['start'] != '') {
			$sql .= " LIMIT ". (int)$data['start'] ."";
			if (isset($data['limit']) && $data['limit'] != '') {
				$sql .= " , ". (int)$data['limit'] ."";
			}
		}
		// echo $sql;die;
		$query = $this->db->query($sql);

		return $query->rows;
	}


	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->row;
	}

	public function getProductCustomerGroups($product_id) {
		return $this->db->query("SELECT * FROM " . DB_PREFIX . "product_customer_group WHERE product_id ='". (int)$product_id ."'")->rows;
	}

	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductsByCategoryIds($category_ids) {
		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id IN ('" . implode("','", $category_ids) . "') ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.manufacturer_id = '" . (int)$manufacturer_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductsByManufacturerIds($manufacturer_ids) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.manufacturer_id IN ('" . implode("','", $manufacturer_ids) . "') ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function hasProductCustomerGroup($data) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_customer_group WHERE product_id='". (int)$data['product_id'] ."' AND customer_group_id='". (int)$data['customer_group_id'] ."'");
		return ($query->num_rows);
	}

	public function editProductCustomerGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_customer_group SET product_id='". (int)$data['product_id'] ."', customer_group_id='". (int)$data['customer_group_id'] ."'");
	}
}