<?php

class ModelExtensionModuleCustomBlock extends Model
{
    public function getPosts($data = array())
    {
        $sql = "SELECT p.post_id AS post_id, p.image AS image,
        p.`status` AS `status`, p.date_added AS `date_added`, p.date_modified AS `date_modified`, p.date_published AS `date_published`,
        pd.language_id AS language_id, pd.title AS title, pd.tag AS tag
        FROM " . DB_PREFIX . "bm_post p
        LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (p.post_id = pd.post_id)
        LEFT JOIN " . DB_PREFIX . "bm_post_to_category p2c ON (p.post_id = p2c.post_id)
        LEFT JOIN " . DB_PREFIX . "bm_post_to_product p2p ON (p.post_id = p2p.post_id)
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sql .= " AND p.type = '" . (int)$data['type'] . "'";
		
		if (!empty($data['filter_title'])) {
            $sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
        }
		
        
        

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
        }

        if (isset($data['filter_tag']) && !is_null($data['filter_tag'])) {
            $sql .= " AND pd.tag  LIKE '" . $this->db->escape($data['filter_tag']) . "%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(p.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_date_published'])) {
            $sql .= " AND DATE(p.date_published) = DATE('" . $this->db->escape($data['filter_date_published']) . "')";
        }

        $sql .= " GROUP BY p.post_id";

        $sort_data = array(
            'pd.title',
            'p.status',
            'pd.tag',
            'category',
            'p.date_added',
            'p.date_modified',
            'p.date_published'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pd.title";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if (!isset($data['start']) || $data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
		//echo $sql;die();
        $query = $this->db->query($sql);
        return $query->rows;
    }

}
