<?php
class ModelExtensionDVisualDesignerModuleModule extends Model {

    public function getInstalled($type)
    {
        $extension_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY code");

        foreach ($query->rows as $result) {
            $extension_data[] = $result['code'];
        }

        return $extension_data;
    }

    public function getModulesByCode($code)
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

        return $query->rows;
    }

    public function getModule($module_id)
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");

        if (VERSION >= '2.1.0.0') {
            if ($query->row) {
                return json_decode($query->row['setting'], true);
            } else {
                return array();
            }
        } else {
            if ($query->row) {
                return unserialize($query->row['setting']);
            } else {
                return array();
            }
        }
    }

}