<?php
class ModelExtensionCustomerGroupPrice extends Model {
	public function install() {
$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."product_customergroup_optionvalue` (
  `product_id` int(11) NOT NULL,
  `product_option_id` int(11) NOT NULL,
  `product_option_value_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `option_value_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."product_customergroup_price` (
  `product_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
	}
	public function uninstall() {
	$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."product_customergroup_optionvalue`");
	$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."product_customergroup_price`");
	}

  public function getProductOptionGroup($product_option_value_id) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_customergroup_optionvalue WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
    return $query->rows;
  }

  public function getCustomerGroupByName($name) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE name = '" . $name . "'");

    return $query->row;
  }

  public function updateProduct($data) {
    $group_info = $this->getCustomerGroupByName($data['group_name']);
	
    $this->db->query("delete from " . DB_PREFIX . "product_customergroup_price where product_id='".$data['product_id']."' AND customer_group_id = '".$group_info['customer_group_id']."' ");
	$this->db->query("insert  " . DB_PREFIX . "product_customergroup_price set  price='".(float)$data['price']."' , product_id='".$data['product_id']."' , customer_group_id = '".$group_info['customer_group_id']."' ");
  }
  
  public function updateCustomerProOpt($data) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN  ".DB_PREFIX."option_value_description ovp ON (pov.option_value_id = ovp.option_value_id) WHERE ovp.name = '" . $data['option_value'] . "'AND pov.product_id='".$data['product_id']."'");

    if (isset($query->row['product_option_value_id'])) {
      $product_option_value_id = $query->row['product_option_value_id'];
      $option_id = $query->row['option_id'];
      $product_option_id = $query->row['product_option_id'];
      $option_value_id = $query->row['option_value_id'];
    }else{
      $product_option_value_id = 0;
      $option_id = 0;
      $product_option_id = 0;
      $option_value_id = 0;
    }
    $group_info = $this->getCustomerGroupByName($data['group_name']);

    $query2 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_customergroup_optionvalue where product_option_value_id='".$product_option_value_id."' AND customer_group_id='".$group_info['customer_group_id']."'AND product_id='".$data['product_id']."'");

    if (isset($query2->row['product_option_value_id'])) {
      $this->db->query("UPDATE " . DB_PREFIX . "product_customergroup_optionvalue set  price='".$data['group_price']."' WHERE product_option_value_id='".$product_option_value_id."' AND customer_group_id='".$group_info['customer_group_id']."'AND product_id='".$data['product_id']."'");
    }else{
      $this->db->query("INSERT INTO " . DB_PREFIX . "product_customergroup_optionvalue set price='".$data['group_price']."',product_id='".$data['product_id']."',product_option_value_id='".$product_option_value_id."',option_id='".$option_id."',option_value_id='".$option_value_id."',product_option_id='".$product_option_id."',customer_group_id='".$group_info['customer_group_id']."'");
    }
  }

  public function getproductbymodel($model) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product where model='".$model."'");
    if($query->row) {
      return $query->row['product_id'];
    }
  }

  public function getproductbysku($sku) {

    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product where sku='".$sku."'");
    if($query->row) {
      return $query->row['product_id'];
    }
  }

  /*public function updateCustomerSpecialPrice($data) {
    $group_info = $this->getCustomerGroupByName($data['group_name']);
    //print_r($data);die();

    $this->db->query("update " . DB_PREFIX . "product_special set  price='".$data['special_price']."' WHERE customer_group_id='".$group_info['customer_group_id']."'AND product_id='".$data['product_id']."'");
  }*/
}
