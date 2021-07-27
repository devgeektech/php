<?php
class ModelExtensionTotalTotal extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/total');


                $customer_group_id = $this->customer->getGroupId();                
                $query = $this->db->query("SELECT checkout_fixed_fee,checkout_fee_message FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
                if ($query->num_rows && $total['total'] > 0) {
                    if($query->row['checkout_fee_message'] != '') {
                        $total['totals'][] = array(
                            'code'       => 'custom_checkout_fee',
                            'title'      => $query->row['checkout_fee_message'],
                            'value'      => $query->row['checkout_fixed_fee'],
                            'sort_order' => 2
                        );
                        $total['total'] += $query->row['checkout_fixed_fee'];
                    }
                }
            
		$total['totals'][] = array(
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'value'      => max(0, $total['total']),
			'sort_order' => $this->config->get('total_total_sort_order')
		);
	}
}