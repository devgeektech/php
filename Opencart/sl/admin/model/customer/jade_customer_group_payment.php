<?php
class ModelCustomerJadeCustomerGroupPayment extends Model {
	public function CreateTableCustomerGroupPayment() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "jade_customer_group_payment` (`customer_group_id` int(11) NOT NULL, `payment_code` varchar(100) NOT NULL, PRIMARY KEY (`customer_group_id`,`payment_code`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	}

	public function getCustomerGroupPaymentMethod($customer_group_id) {
		$customer_group_payment_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "jade_customer_group_payment WHERE customer_group_id = '" . (int)$customer_group_id . "'");

		foreach ($query->rows as $result) {
			$customer_group_payment_data[] = $result['payment_code'];
		}

		return $customer_group_payment_data;
	}
}