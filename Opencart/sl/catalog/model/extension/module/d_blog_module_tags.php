<?php
class ModelExtensionModuleDBlogModuleTags extends Model {

    public function getTags()
    {
        $query = $this->db->query("SELECT bp.tag FROM ".DB_PREFIX."bm_post_description bp LEFT JOIN ".DB_PREFIX."bm_post_to_store bps  ON (bp.post_id = bps.post_id) WHERE bps.store_id =".(int)$this->config->get('config_store_id')." ");

        $tags = array();
        foreach ($query->rows as $value) {

            $tag_split = preg_split("/,/", $value['tag']);
            foreach ($tag_split as $value) {
                $tags[] = trim($value);
            }
        }
        $tags = array_unique($tags);

        return $tags;
    }

    public function TotalPostsByTag($tag)
    {
        $query = $this->db->query("SELECT count(*) as total FROM ".DB_PREFIX."bm_post_description bp LEFT JOIN ".DB_PREFIX."bm_post_to_store bps  ON (bp.post_id = bps.post_id) WHERE bps.store_id =".(int)$this->config->get('config_store_id')." AND bp.tag LIKE '%" . $tag . "%'");
        return $query->row['total'];
    }

}
