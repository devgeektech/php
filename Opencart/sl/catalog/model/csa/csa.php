<?php

class ModelCsaCsa extends Model {

    public function getCSA($csa_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "csa WHERE csa_id = '" . (int) $csa_id . "'");

        return $query->row;
    }
    public function getAllCSA( $filters = array() ) {
        $sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "csa";
        
        $implode = [];

        if (count($filters) > 0) {
            $sql .= ' WHERE ';

            if (isset($filters['filter_status'])) {
                $implode[] = " status = '{$filters['filter_status']}'";
            }

            if (isset($filters['filter_visible'])) {
                $implode[] = " display = '{$filters['filter_visible']}'";
            }

            if (isset($filters['filter_registration'])) {
                $implode[] = " registration = '{$filters['filter_registration']}'";
            }

            $sql .= implode( ' AND ', $implode);
        }

        $sql .= ' ORDER BY csaname ASC';
       
		$query = $this->db->query($sql);
		return $query->rows;

	}

    public function getCSAByName($csa_name) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "csa WHERE csaname = '" .$csa_name . "'");

        return $query->row;
    }
}
