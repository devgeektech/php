<?php

class ModelExtensionModulesimpleinstagramwidget extends Model
{

    private function getModule($module_id)
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int) $module_id . "'");
        if ($query->row) {
            return json_decode($query->row['setting'], true);
        } else {
            return array();
        }
    }

    public function editModule($module_id, $data)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "' WHERE `module_id` = '" . (int) $module_id . "'");
    }

    public function refreshToken($module_id, $access_token)
    {
        $module_info = $this->getModule($module_id);
        if ($module_info) {
            $module_info['access_token'] = $access_token;
            $this->editModule($module_id, $module_info);
        }
        return true;
    }
}