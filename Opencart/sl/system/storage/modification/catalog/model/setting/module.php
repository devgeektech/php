<?php
class ModelSettingModule extends Model {

            public function getModuleByName($module_name, $code) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE name = '" . $module_name . "' and code = '" . $code . "'");
                if ($query->row) {
                    return json_decode($query->row['setting'], true);
                } else {
                    return array();	
                }
            }
            
	public function getModule($module_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$module_id . "'");
		
		if ($query->row) {
			return json_decode($query->row['setting'], true);
		} else {
			return array();	
		}
	}		
}