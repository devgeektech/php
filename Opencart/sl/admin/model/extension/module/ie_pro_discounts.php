<?php
    class ModelExtensionModuleIeProDiscounts extends ModelExtensionModuleIePro {
        public function __construct($registry)
        {
            parent::__construct($registry);
            $this->cat_name = 'discounts';
        }

        public function set_model_tables_and_fields($special_tables = array(), $special_tables_description = array(), $delete_tables = array()) {
            $this->main_table = 'product_discount';
            $this->main_field = 'product_discount_id';
            parent::set_model_tables_and_fields($special_tables, $special_tables_description, $delete_tables);
        }

        public function get_columns($configuration = array()) {
            $columns = parent::get_columns($configuration);
            return $columns;
        }

        function get_columns_formatted($multilanguage) {
            $columns = array(
                'Product discount id' => array('hidden_fields' => array('table' => 'product_discount', 'field' => 'product_discount_id')),
                'Product id' => array('hidden_fields' => array('table' => 'product_discount', 'field' => 'product_id'), 'product_id_identificator' => 'product_id'),
                'Customer group id' => array('hidden_fields' => array('table' => 'product_discount', 'field' => 'customer_group_id')),
                'Quantity' => array('hidden_fields' => array('table' => 'product_discount', 'field' => 'quantity')),
                'Priority' => array('hidden_fields' => array('table' => 'product_discount', 'field' => 'priority')),
                'Price' => array('hidden_fields' => array('table' => 'product_discount', 'field' => 'price')),
                'Date_start' => array('hidden_fields' => array('table' => 'product_discount', 'field' => 'date_start')),
                'Date_end' => array('hidden_fields' => array('table' => 'product_discount', 'field' => 'date_end')),
                'Deleted' => array('hidden_fields' => array('table' => 'empty_columns', 'field' => 'delete', 'is_boolean' => true)),
            );

            $columns = parent::put_type_to_columns_formatted($columns, $multilanguage);

            return $columns;
        }
    }
?>