<?php

class ModelExtensionModuleWarehouseRules extends Model {
    
    public function addRule($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "warehouse_stock_rules SET name = '" . $this->db->escape($data['name']) . "', product_type = '".$data['product_type']."',warehouse_id = '" . (int)$data['warehouse_id'] . "', rule_type = '" . $data['rule_type']. "', priority = '" . (int)$data['priority']. "'");
        $rule_id = $this->db->getLastId();
        foreach ($data['csas'] as $csa_id) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "warehouse_stock_rules_csa SET rule_id = '" . $rule_id . "', csa_id = '". (int)$csa_id ."'");
        }
    }

    public function editRule($data, $rule_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "warehouse_stock_rules SET name = '" . $this->db->escape($data['name']) . "', product_type = '".$data['product_type']."',warehouse_id = '" . (int)$data['warehouse_id'] . "', rule_type = '" . $data['rule_type']. "', priority = '" . (int)$data['priority']. "' WHERE rule_id = '" . (int)$rule_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "warehouse_stock_rules_csa WHERE rule_id = '" . (int)$rule_id . "'");
        foreach ($data['csas'] as $csa_id) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "warehouse_stock_rules_csa SET rule_id = '" . $rule_id . "', csa_id = '". (int)$csa_id ."'");
        }
    }

    public function getRule($rule_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "warehouse_stock_rules WHERE rule_id = '" . (int) $rule_id . "'");
		return $query->row;
    }

    public function getRules() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "warehouse_stock_rules ORDER BY rule_id asc");
		return $query->rows;
    }

    public function getRuleCsas($rule_id) {
        $query = $this->db->query("SELECT csa_id FROM " . DB_PREFIX . "warehouse_stock_rules_csa WHERE rule_id = {$rule_id}");
		return $query->rows;
    }

    public function deleteRules() {
        $this->db->query("TRUNCATE table " . DB_PREFIX . "warehouse_stock_rules");
        $this->db->query("TRUNCATE table " . DB_PREFIX . "warehouse_stock_rules_csa");
    }

    public function deleteRule($rule_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "warehouse_stock_rules WHERE rule_id = '" . (int) $rule_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "warehouse_stock_rules_csa WHERE rule_id = '" . (int) $rule_id . "'");
    }
}