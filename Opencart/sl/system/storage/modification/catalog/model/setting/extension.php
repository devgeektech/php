<?php
class ModelSettingExtension extends Model {
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

          /* Jade Payment Method Customer Group Starts */
          if($type == 'payment') {

			$jade_customer_group_payment_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "jade_customer_group_payment jcp WHERE jcp.customer_group_id = '". (int)$this->config->get('config_customer_group_id') ."'");
			if($jade_customer_group_payment_query->row['total']) {

				$query = $this->db->query("SELECT ext.* FROM " . DB_PREFIX . "extension ext LEFT JOIN " . DB_PREFIX . "jade_customer_group_payment jcp ON (ext.code = jcp.payment_code) WHERE ext.type = '" . $this->db->escape($type) . "' AND jcp.customer_group_id = '". (int)$this->config->get('config_customer_group_id') ."'");
			}
		}
        /* Jade Payment Method Customer Group Ends */
				

		return $query->rows;
	}
}