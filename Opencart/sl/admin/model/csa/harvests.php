<?php

class ModelCsaHarvests extends Model {

    public function addHarvest($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "harvests SET status = '" . (int) $data['status'] . "',harvest_title = '" . $this->db->escape($data['harvest_title']) . "', harvest_display_title = '" . $this->db->escape($data['harvest_display_title']) . "', start_date = '" . $data['start_date'] . "', end_date = '" . $data['end_date'] . "', deliveries = '" . $this->db->escape($data['deliveries']) . "',short_description = '" . $this->db->escape($data['short_description']) . "', marketplace_start_date = '" . $data['marketplace_start_date'] . "', marketplace_end_date = '" . $data['marketplace_end_date'] . "', date_added = NOW()");

        $harvest_id = $this->db->getLastId();

        return $harvest_id;
    }

    public function editHarvest($harvest_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "harvests SET status = '" . (int) $data['status'] . "',harvest_title = '" . $this->db->escape($data['harvest_title']) . "', harvest_display_title = '" . $this->db->escape($data['harvest_display_title']) . "', start_date = '" . $data['start_date'] . "', end_date = '" . $data['end_date'] . "', deliveries = '" . $this->db->escape($data['deliveries']) . "',short_description = '" . $this->db->escape($data['short_description']) . "', marketplace_start_date = '" . $data['marketplace_start_date'] . "', marketplace_end_date = '" . $data['marketplace_end_date'] . "', date_modified = NOW() WHERE harvest_id = '" . (int) $harvest_id . "'");
    }

    public function deleteHarvest($harvest_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "harvests WHERE harvest_id = '" . (int) $harvest_id . "'");
    }

    public function getHarvest($harvest_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "harvests WHERE harvest_id = '" . (int) $harvest_id . "'");

        return $query->row;
    }

    public function getHarvestList($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "harvests";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = " harvest_title LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $implode[] = " status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_start_date'])) {
             $implode[] = " DATE(start_date) >= '" . $this->db->escape($data['filter_start_date']) . "'";
        }

        if (!empty($data['filter_end_date'])) {
            $implode[] = " DATE(end_date) <= '" . $this->db->escape($data['filter_end_date']) . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'harvest_title',
            'status',
            'start_date',
            'end_date'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY harvest_title";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalHarvest($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "harvests";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "harvest_title LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $implode[] = "status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
    
    public function getCurrentActiveHarvest() {
        $sql = "SELECT * FROM " . DB_PREFIX . "harvests WHERE status = '1' ORDER BY start_date DESC LIMIT 1";
        
        $query = $this->db->query($sql);
        return $query->row;
    }

}
