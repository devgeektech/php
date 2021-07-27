<?php

class ModelCsaCsa extends Model {

    public function addCSA($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "csa SET display = '" . $this->db->escape($data['display']) . "', registration = '" . $this->db->escape($data['registration']) . "', csaname = '" . $this->db->escape($data['csaname']) . "', description = '" . $this->db->escape($data['description']) . "', membership_requirements = '" . $this->db->escape($data['membership_requirements']) . "', pickup_address = '" . $this->db->escape($data['pickup_address']) . "', latitude = '" . $this->db->escape($data['latitude']) . "', longitude = '" . $this->db->escape($data['longitude']) . "', operating_hours = '" . $this->db->escape($data['operating_hours']) . "', delivery_day = '" . $this->db->escape($data['delivery_day']) . "', csa_admin_fee = '" . (float)$data['csa_admin_fee'] . "', csa_email = '" . $this->db->escape($data['csa_email']) . "', csa_phone = '" . $this->db->escape($data['csa_phone']) . "', website = '" . $this->db->escape($data['website']) . "',brochure_link = '" . $this->db->escape($data['brochure_link']) . "', csa_image_type = '" . (isset($data['csa_image_type']) ? $data['csa_image_type'] : 0) . "',csa_image = '" . $this->db->escape($data['csa_image']) . "',order_notification_email = '" . $this->db->escape($data['order_notification_email']) . "',volunteering_required = '" . (isset($data['volunteering_required']) ? $data['volunteering_required'] : 0) . "',checkout_volunteer_messages = '" . $this->db->escape($data['checkout_volunteer_messages']) . "',allow_share_partners = '" . (isset($data['allow_share_partners']) ? $data['allow_share_partners'] : 0) . "',customer_group_id = '" . (int) $data['customer_group_id'] . "',warehouse_id = '" . (int) $data['warehouse_id'] . "',status = '" . (int) $data['status'] . "', date_added = NOW()");

        $csa_id = $this->db->getLastId();
        
        if (isset($data['delivery_date'])) {
            foreach ($data['delivery_date'] as $delivery_date) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "csa_delivery_dates SET csa_id = '" . (int)$csa_id . "', weeks = '" . $delivery_date['weeks'] . "', odd_even_week = '" . $this->db->escape($delivery_date['odd_even_week']) . "', note = '" . $this->db->escape($delivery_date['note']) . "', delivery_date = '" . $this->db->escape($delivery_date['delivery_date']) . "',beginning_of = '" . $delivery_date['beginning_of'] . "'");
            }
        }

        return $csa_id;
    }

    public function editCSA($csa_id, $data) {

        $this->db->query("UPDATE " . DB_PREFIX . "csa SET display = '" . $this->db->escape($data['display']) . "', registration = '" . $this->db->escape($data['registration']) . "', csaname = '" . $this->db->escape($data['csaname']) . "', description = '" . $this->db->escape($data['description']) . "',  membership_requirements = '" . $this->db->escape($data['membership_requirements']) . "', pickup_address = '" . $this->db->escape($data['pickup_address']) . "', latitude = '" . $this->db->escape($data['latitude']) . "', longitude = '" . $this->db->escape($data['longitude']) . "', operating_hours = '" . $this->db->escape($data['operating_hours']) . "', delivery_day = '" . $this->db->escape($data['delivery_day']) . "', csa_admin_fee = '" . (float)$data['csa_admin_fee'] . "', csa_email = '" . $this->db->escape($data['csa_email']) . "', csa_phone = '" . $this->db->escape($data['csa_phone']) . "', website = '" . $this->db->escape($data['website']) . "',brochure_link = '" . $this->db->escape($data['brochure_link']) . "', csa_image_type = '" . (isset($data['csa_image_type']) ? $data['csa_image_type'] : 0) . "',csa_image = '" . $this->db->escape($data['csa_image']) . "',order_notification_email = '" . $this->db->escape($data['order_notification_email']) . "',volunteering_required = '" . (isset($data['volunteering_required']) ? $data['volunteering_required'] : 0) . "',checkout_volunteer_messages = '" . $this->db->escape($data['checkout_volunteer_messages']) . "',allow_share_partners = '" . (isset($data['allow_share_partners']) ? $data['allow_share_partners'] : 0) . "',customer_group_id = '" . (int) $data['customer_group_id'] . "',warehouse_id = '" . (int) $data['warehouse_id'] . "',status = '" . (int) $data['status'] . "', date_modified = NOW() WHERE csa_id='" . (int) $csa_id . "'");
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "csa_delivery_dates WHERE csa_id = '" . (int)$csa_id . "'");
        if (isset($data['delivery_date'])) {
            foreach ($data['delivery_date'] as $delivery_date) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "csa_delivery_dates SET csa_id = '" . (int)$csa_id . "', weeks = '" . $delivery_date['weeks'] . "', odd_even_week = '" . $this->db->escape($delivery_date['odd_even_week']) . "', note = '" . $this->db->escape($delivery_date['note']) . "', delivery_date = '" . $this->db->escape($delivery_date['delivery_date']) . "',beginning_of = '" . $delivery_date['beginning_of'] . "'");
            }
        }
        
    }

    public function deleteCSA($csa_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "csa WHERE csa_id = '" . (int) $csa_id . "'");
    }
    
    public function getShareProducts($harvest_id) {
        $sql = "SELECT p.product_id,pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.harvest_id = '" . $harvest_id . "' ORDER BY pd.name ASC";
        $query = $this->db->query($sql);
	return $query->rows;
        
    }
    
     public function getdeliveryDateByHarvestId($delivery_day,$harvest_details) {
        $delivery_dates = array();
        $harvest_id = $harvest_details['harvest_id']; 
        $start_date = new DateTime($harvest_details['start_date']);
        $end_date = new DateTime($harvest_details['end_date']);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start_date, $interval, $end_date);
        $odd_even_week = 'odd';
        
        $delivery_day = isset($delivery_day) ? $delivery_day: 1;
        $csa_selection_day = '';
        
        switch($delivery_day){
            case 1:
                $csa_selection_day = 'Mon';
                break;
            case 2:
                $csa_selection_day = 'Tue';
                break;
            case 3:
                $csa_selection_day = 'Wed';
                break;
            case 4:
                $csa_selection_day = 'Thu';
                break;
            case 5:
                $csa_selection_day = 'Fri';
                break;
            case 6:
                $csa_selection_day = 'Sat';
                break;
            case 7:
                $csa_selection_day = 'Sun';
                break;
            default:
                $csa_selection_day = 'Mon';
        }
        $count = 1;
        foreach ($period as $dt) {
            $day = $dt->format("D");
            if($day == $csa_selection_day) {
                $date = $dt->format("Y-m-d");
                $delivery_dates[] = array(                        
                    'weeks'            => 'week #'.$count,
                    'delivery_date'    => $date,
                    'odd_even_week'    => $odd_even_week,
                    'beginning_of'     => 0,
                    'note'             => '',
                );
                
                if($odd_even_week == 'odd'){
                    $odd_even_week = 'even';
                } else {
                    $odd_even_week = 'odd';
                }
                
                $count++;
            }
        }
        return $delivery_dates;
    }
    
   

    public function getCSA($csa_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "csa WHERE csa_id = '" . (int) $csa_id . "'");

        return $query->row;
    }

    public function getCSAList($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "csa";


        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = " csaname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = " csa_email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_visible'])) {
            $implode[] = " display = '" . (int) $this->db->escape($data['filter_visible']) . "'";
        }

        if (!empty($data['filter_registration'])) {
            $implode[] = " registration = '" . (int) $this->db->escape($data['filter_registration']) . "'";
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $implode[] = " status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'csaname',
            'display',
            'registration',
            'csa_email',
            'status',
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY csaname";
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

    public function getTotalCSA($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "csa";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "csaname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "csa_email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }
        
        if (!empty($data['filter_visible'])) {
            $implode[] = " display = '" . (int) $this->db->escape($data['filter_visible']) . "'";
        }

        if (!empty($data['filter_registration'])) {
            $implode[] = " registration = '" . (int) $this->db->escape($data['filter_registration']) . "'";
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
    public function getCSADeliveryDates($csa_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "csa_delivery_dates WHERE csa_id = '" . (int) $csa_id . "'");
        return $query->rows;
    }
}
