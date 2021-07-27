<?php
class ModelExtensionModuleDVisualDesignerModule extends Model {
    public function getModule($module_id) {
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$module_id . "'");

        if ($query->row) {
            if(VERSION>='2.1.0.0') {
                 return json_decode($query->row['setting'], true);
            }
            else {
                 return unserialize($query->row['setting']);
            }
           
        } else {
            return array();
        }
    }

    public function editModule($module_id, $data) {
        if(VERSION>='2.1.0.0') {
          $this->db->query("UPDATE `" . DB_PREFIX . "module` SET `setting` = '" . $this->db->escape(json_encode($data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");  
        }
        else {
            $this->db->query("UPDATE `" . DB_PREFIX . "module` SET `setting` = '" . $this->db->escape(serialize($data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
        }  
    }
}