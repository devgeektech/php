<?php

class ModelExtensionDVisualDesignerModuleFile extends Model
{
    public function addDownload($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "download 
            SET filename = '" . $this->db->escape($data['filename']) . "', 
            mask = '" . $this->db->escape($data['mask']) . "', 
            date_added = NOW()");

        $download_id = $this->db->getLastId();

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "download_description 
                SET download_id = '" . (int)$download_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($data['filename']) . "'");
        }

        return $download_id;
    }

    public function getDownload($download_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE d.download_id = '" . (int)$download_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }
}