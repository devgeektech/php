<?php class ModelExtensionModuleWarehouse extends Model{public function saveForCronJob($order_id){$this->removeFromCrobJobs($order_id);$this->db->query("INSERT INTO `".DB_PREFIX."warehouse_cronjobs` SET `order_id` = '".(int)$order_id."'");}public function getCronJobs(){$query=$this->db->query("SELECT * FROM `".DB_PREFIX."warehouse_cronjobs`");return $query->rows;}public function removeFromCrobJobs($order_id){$this->db->query("DELETE FROM `".DB_PREFIX."warehouse_cronjobs` WHERE order_id = '".(int)$order_id."'");}public function removeFromOrderStockProduct($order_id){$this->db->query("DELETE FROM `".DB_PREFIX."warehouse_order_product` WHERE order_id = '".(int)$order_id."'");}public function reduceStock(){$orders=$this->getCronJobs();foreach($orders as $key=>$value){$this->reduceStockOrder($value['order_id']);}}public function reduceStockOrder($order_id){$this->load->model("account/order");$value['order_id']=$order_id;$data['order_id']=$value['order_id'];$this->reStockWarehouse($order_id);$order_info=$this->getOrderInfo($value['order_id']);$warehousesorting=$this->warehouseSorting();if($order_info){$data['product']=array();$products=$this->model_account_order->getOrderProducts($value['order_id']);foreach($products as $product){if($this->checkProductSubtract($product['product_id'])){$stock_available_warehouse=$this->getGroupsById($product['product_id']);$warehouseForOrderAddProduct=$this->checkForOrderAddProduct($product['order_product_id'],$value['order_id']);if($warehouseForOrderAddProduct){$getallwarehouse=$this->getWarehouseBySortOrder();if(isset($getallwarehouse[$warehouseForOrderAddProduct])){$getallwarehouse[$warehouseForOrderAddProduct]=$product['quantity'];}$warehouse=$getallwarehouse;$this->log->write("Order product warehouse_id exists");}else{$warehouse=$this->getWarehouse($stock_available_warehouse,$warehousesorting,$product['quantity'],$order_info);}$data['product'][]=array('product_id'=>$product['product_id'],'order_product_id'=>$product['order_product_id'],'order_option_id'=>0,'product_option_value_id'=>0,'name'=>$product['name'],'warehouse'=>$warehouse);}$options=$this->model_account_order->getOrderOptions($value['order_id'],$product['order_product_id']);foreach($options as $option){if($this->checkProductOptionSubtract($option['product_option_value_id'])){if($option['type']!='file'){$name=$option['name'].": ".$option['value'];}else{$name="";$upload_info=$this->model_tool_upload->getUploadByCode($option['value']);if($upload_info){$name=$option['name'].": ".$upload_info['value'];}}$stock_available_warehouse=$this->getOptionsGroupsById($option['product_option_value_id']);if(isset($warehouseForOrderAddProduct)&&isset($getallwarehouse)){$warehouse=$getallwarehouse;}else{$warehouse=$this->getWarehouse($stock_available_warehouse,$warehousesorting,$product['quantity'],$order_info);}$data['product'][]=array('order_product_id'=>$product['order_product_id'],'product_id'=>$product['product_id'],'order_option_id'=>$option['order_option_id'],'name'=>$product['name']." > ".$name,'product_option_value_id'=>$option['product_option_value_id'],'warehouse'=>$warehouse,'quantity'=>$product['quantity']);}}}$this->saveOrderStockWarehouse($data);$this->removeFromCrobJobs($value['order_id']);}}public function removeOldCronJobs(){$this->db->query("DELETE FROM `".DB_PREFIX."warehouse_cronjobs` wc LEFT JOIN `".DB_PREFIX."order` o ON (wc.order_id = o.order_id) WHERE (o.order_status_id = 0 OR o.order_status_id = '') AND DATE(o.date_added) < DATE(DATE_SUB(NOW(), INTERVAL 10 day)) ");}public function saveOrderStockWarehouse($data=array()){$order_id=$data['order_id'];foreach($data['product']as $key=>$value){foreach($value['warehouse']as $warehouse_id=>$qty){if($qty){$this->db->query("INSERT INTO ".DB_PREFIX."warehouse_transaction SET order_id = '".(int)$order_id."', name = '".$this->db->escape($value['name'])."', order_product_id = '".(int)$value['order_product_id']."', order_option_id = '".(int)$value['order_option_id']."', product_option_value_id = '".(int)$value['product_option_value_id']."', warehouse_id = '".(int)$warehouse_id."', product_id = '".(int)$value['product_id']."', qty = '".(int)$qty."', date_added = NOW() ");}}if($value['order_option_id']){$this->updateStockOptionWarehouse($value['product_id'],$value['product_option_value_id'],$value['warehouse']);}else{$this->updateStockProductWarehouse($value['product_id'],$value['warehouse']);}}}public function checkProductSubtract($product_id){$query=$this->db->query("SELECT subtract FROM ".DB_PREFIX."product WHERE product_id = '".(int)$product_id."' AND subtract = '1'");if($query->num_rows){return 1;}else{return 0;}}public function checkProductOptionSubtract($product_option_value_id){$query=$this->db->query("SELECT subtract FROM ".DB_PREFIX."product_option_value WHERE product_option_value_id = '".(int)$product_option_value_id."' AND subtract = '1'");if($query->num_rows){return 1;}else{return 0;}}public function updateStockProductWarehouse($product_id,$warehouses){foreach($warehouses as $warehouse_id=>$qty){$query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_to_warehouse WHERE product_id = '".(int)$product_id."' AND warehouse_id = '".(int)$warehouse_id."'");if($query->num_rows){$this->db->query("UPDATE ".DB_PREFIX."product_to_warehouse SET qty = qty - '".(int)$qty."' WHERE product_id = '".(int)$product_id."' AND warehouse_id = '".(int)$warehouse_id."'");}else{$this->db->query("INSERT INTO ".DB_PREFIX."product_to_warehouse SET product_id = '".(int)$product_id."', warehouse_id = '".(int)$warehouse_id."', qty = qty - '".(int)$qty."'");}}}public function reStockWarehouse($order_id){$query=$this->db->query("SELECT * FROM ".DB_PREFIX."warehouse_transaction WHERE order_id = '".(int)$order_id."'");foreach($query->rows as $key=>$value){if($value['product_option_value_id']){$this->db->query("UPDATE ".DB_PREFIX."product_option_to_warehouse SET  qty = qty + '".(int)$value['qty']."' WHERE product_option_value_id = '".(int)$value['product_option_value_id']."' AND warehouse_id = '".(int)$value['warehouse_id']."' AND product_id = '".(int)$value['product_id']."'");}else{$this->db->query("UPDATE ".DB_PREFIX."product_to_warehouse SET  qty = qty + '".(int)$value['qty']."' WHERE product_id = '".(int)$value['product_id']."' AND warehouse_id = '".(int)$value['warehouse_id']."'");}}$this->db->query("DELETE FROM ".DB_PREFIX."warehouse_transaction WHERE order_id = '".(int)$order_id."'");}public function updateStockOptionWarehouse($product_id,$product_option_value_id,$warehouses){foreach($warehouses as $warehouse_id=>$qty){$query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_option_to_warehouse WHERE product_id = '".(int)$product_id."' AND  product_option_value_id = '".(int)$product_option_value_id."' AND warehouse_id = '".(int)$warehouse_id."'");if($query->num_rows){$this->db->query("UPDATE ".DB_PREFIX."product_option_to_warehouse SET qty = qty - '".(int)$qty."' WHERE product_id = '".(int)$product_id."' AND  product_option_value_id = '".(int)$product_option_value_id."' AND warehouse_id = '".(int)$warehouse_id."'");}else{$this->db->query("INSERT INTO ".DB_PREFIX."product_option_to_warehouse SET product_id = '".(int)$product_id."', warehouse_id = '".(int)$warehouse_id."', qty = qty - '".(int)$qty."', product_option_value_id = '".(int)$product_option_value_id."'");}}}public function getGroupsById($product_id){$product_majorcity_data=array();$query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_to_warehouse WHERE product_id = '".(int)$product_id."'");foreach($query->rows as $key=>$value){$product_majorcity_data[$value['warehouse_id']]=$value['qty'];}return $product_majorcity_data;}public function getwarehouses($data=array()){$sql="SELECT * FROM ".DB_PREFIX."warehouse";if(!empty($data['filter_name'])){$sql.=" WHERE name LIKE '".$this->db->escape($data['filter_name'])."%'";}$sort_data=array('name','sort_order');if(isset($data['sort'])&&in_array($data['sort'],$sort_data)){$sql.=" ORDER BY ".$data['sort'];}else{$sql.=" ORDER BY sort_order";}if(isset($data['order'])&&($data['order']=='DESC')){$sql.=" DESC";}else{$sql.=" ASC";}if(isset($data['start'])||isset($data['limit'])){if($data['start']<0){$data['start']=0;}if($data['limit']<1){$data['limit']=20;}$sql.=" LIMIT ".(int)$data['start'].",".(int)$data['limit'];}$query=$this->db->query($sql);return $query->rows;}public function getWarehouse($warehouses,$warehousesorting,$qty_ordered,$order_info){$quantity_left_to_deduct=$qty_ordered;$returnwarehouse=array();foreach($warehousesorting as $key=>$value){$warehousetoconsider=array();switch($key){case 'state':$warehousetoconsider=$this->getWarehouseByState($order_info['shipping_zone_id']);break;case 'sort_order':$warehousetoconsider=$this->getWarehouseBySortOrder();break;default:$warehousetoconsider=$this->getWarehouseBySortOrder();break;}$returnwarehouse=$warehousetoconsider;foreach($warehousetoconsider as $warehouse_id=>$value){if(!$returnwarehouse[$warehouse_id]){if($this->config->get('module_warehouse_negativestock')){$returnwarehouse[$warehouse_id]=$quantity_left_to_deduct;break 2;}else{if(isset($warehouses[$warehouse_id])){$quantity_available=$warehouses[$warehouse_id];if($quantity_available>=$quantity_left_to_deduct){$returnwarehouse[$warehouse_id]=$quantity_left_to_deduct;break 2;}else{$returnwarehouse[$warehouse_id]=$quantity_available;$quantity_left_to_deduct-=$quantity_available;if($quantity_left_to_deduct<=0){break 2;}}}}}}break;}return $returnwarehouse;}public function getWarehouseByState($zone_id){$returnarray=array();$query=$this->db->query("SELECT DISTINCT warehouse_id FROM ".DB_PREFIX."warehouse WHERE (zone_id = '".(int)$zone_id."' OR ".(int)$zone_id." IN (zoneids) ) ORDER BY sort_order ASC");if($query->num_rows){foreach($query->rows as $key=>$value){$returnarray[$value['warehouse_id']]=0;}}$query=$this->db->query("SELECT DISTINCT warehouse_id FROM ".DB_PREFIX."warehouse WHERE zone_id != '".(int)$zone_id."' ORDER BY sort_order ASC");if($query->num_rows){foreach($query->rows as $key=>$value){if(!isset($returnarray[$value['warehouse_id']])){$returnarray[$value['warehouse_id']]=0;}}}return $returnarray;}public function getWarehouseBySortOrder(){$returnarray=array();$query=$this->db->query("SELECT DISTINCT warehouse_id FROM ".DB_PREFIX."warehouse ORDER BY sort_order ASC");if($query->num_rows){foreach($query->rows as $key=>$value){$returnarray[$value['warehouse_id']]=0;}}return $returnarray;}public function checkForOrderAddProduct($order_product_id,$order_id){$query=$this->db->query("SELECT * FROM ".DB_PREFIX."warehouse_order_product WHERE order_product_id = '".(int)$order_product_id."' AND order_id = '".(int)$order_id."'");if($query->num_rows&&$query->row['warehouse_id']){return $query->row['warehouse_id'];}else{return 0;}}public function getOptionsGroupsById($product_option_value_id){$product_majorcity_data=array();$query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_option_to_warehouse WHERE product_option_value_id = '".(int)$product_option_value_id."'");foreach($query->rows as $key=>$value){$product_majorcity_data[$value['warehouse_id']]=$value['qty'];}return $product_majorcity_data;}public function warehouseSorting(){$warehouse_sortorder=$this->config->get('module_warehouse_sortorder');asort($warehouse_sortorder);return $warehouse_sortorder;}public function getZoneIdByAddressId($address_id){$query=$this->db->query("SELECT zone_id FROM ".DB_PREFIX."address WHERE address_id = '".(int)$address_id."'");if($query->num_rows){return $query->row['zone_id'];}else{return 0;}}public function checkStock($zone_id=0){$warehouseid=0;$warehousesort=$this->warehouseSorting();if(!empty($warehousesort)){reset($warehousesort);$firstkey=key($warehousesort);switch($firstkey){case 'state':$warehousetoconsider=$this->model_extension_module_warehouse->getWarehouseByState($zone_id);break;case 'sort_order':$warehousetoconsider=$this->model_extension_module_warehouse->getWarehouseBySortOrder();break;default:$warehousetoconsider=$this->model_extension_module_warehouse->getWarehouseBySortOrder();break;}reset($warehousetoconsider);$warehouseid=key($warehousetoconsider);}if($warehouseid){return $this->checkProductStockByWareHouseId($warehouseid);}else{return 0;}}public function checkProductStockByWareHouseId($warehouseid){$returnarray=array();foreach($this->cart->getProducts()as $key=>$value){$productquantity=$this->getWarehouseProductStock($warehouseid,$value['product_id']);if($productquantity<=0){$returnarray[$value['cart_id']]['name']=$value['name'];$returnarray[$value['cart_id']]['qty']=0;continue;}elseif($productquantity<$value['quantity']){$returnarray[$value['cart_id']]['name']=$value['name'];$returnarray[$value['cart_id']]['qty']=$productquantity;continue;}if(isset($value['option'])&&!empty($value['option'])){foreach($value['option']as $option){$productoptionquantity=$this->getWarehouseProductOptionStock($warehouseid,$option['product_option_value_id']);if($productoptionquantity<=0){$returnarray[$value['cart_id']]['name']=$value['name'].": ".$option['name'].": ".$option['value'];$returnarray[$value['cart_id']]['qty']=0;continue;}elseif($productoptionquantity<$value['quantity']){$returnarray[$value['cart_id']]['name']=$value['name'].": ".$option['name'].": ".$option['value'];$returnarray[$value['cart_id']]['qty']=$productoptionquantity;continue;}}}}if(!empty($returnarray)){return $returnarray;}else{return 0;}}public function getWarehouseProductStock($warehouse_id,$product_id){$query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_to_warehouse WHERE product_id = '".(int)$product_id."' AND warehouse_id = '".(int)$warehouse_id."'");if($query->num_rows){return $query->row['qty'];}else{return 0;}}public function getWarehouseProductOptionStock($warehouse_id,$product_option_value_id){$query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_option_to_warehouse WHERE product_option_value_id = '".(int)$product_option_value_id."' AND warehouse_id = '".(int)$warehouse_id."'");if($query->num_rows){return $query->row['qty'];}else{return 0;}}public function getOrderInfo($order_id){$order_query=$this->db->query("SELECT * FROM `".DB_PREFIX."order` WHERE order_id = '".(int)$order_id."' AND order_status_id > '0'");if($order_query->num_rows){if(in_array($order_query->row['order_status_id'],$this->config->get('module_warehouse_order_status'))){if(!$order_query->row['shipping_zone_id']){$order_query->row['shipping_zone_id']=$order_query->row['payment_zone_id'];$order_query->row['shipping_country_id']=$order_query->row['payment_country_id'];$order_query->row['shipping_postcode']=$order_query->row['payment_postcode'];}return array('order_id'=>$order_query->row['order_id'],'order_status_id'=>$order_query->row['order_status_id'],'shipping_zone_id'=>$order_query->row['shipping_zone_id'],'shipping_country_id'=>$order_query->row['shipping_country_id'],'shipping_postcode'=>$order_query->row['shipping_postcode']);}}return false;}}