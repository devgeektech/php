<?php
class ModelExtensionModuleDBlogModuleDate extends Model {

    public function getDates()
    {
        $query = $this->db->query("SELECT Year(`date_published`) as year, Month(`date_published`) as month, count(*) as total FROM `".DB_PREFIX."bm_post` bp LEFT JOIN ".DB_PREFIX."bm_post_to_store bps  ON (bp.post_id = bps.post_id) WHERE bps.store_id =".(int)$this->config->get('config_store_id')." GROUP BY Year(`date_published`), Month(`date_published`) ");
        return $query->rows;
    }

}