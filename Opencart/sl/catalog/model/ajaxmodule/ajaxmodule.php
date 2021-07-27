<?php
class ModelAjaxModuleAjaxModule extends Model {
	
	public function getModule($module_name, $module_code) { 
		
		$name = $module_name;
		$code = $module_code;
		
		if(!empty($code)) {
			$query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'module WHERE name = "' . $name . '" AND code="'. $code .'"');
		} else {
			$query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'module WHERE name = "' . $name . '"');
		}
		
		if ($query->row) { 
			$module_setting = array(
				"code" => $query->row['code'],
				"setting_info" => json_decode($query->row['setting'], true)
			);
		} else {
			$module_setting = array();
		}
		return $module_setting;
	}

}	
?>