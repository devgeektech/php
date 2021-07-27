<?php
/*
* country always united state [code => 223]
* Arguments can be provided
* - customers
* - manual_product_creation
* - marketplace
* - products
* - shares
* - orders
* - orderitems
* - orderpayments
* - order_transactions
* - update_parent_products_stocks
* - customers, manual_product_creation, marketplace, products, shares, orders, orderitems, orderpayments, order_transactions, update_parent_products_stocks
*/
ini_set('memory_limit', '-1');

$export = new export($argv);
if (!$export->_valid_export_type()) {
    $export->_error();
    exit;
}
$cf_db = array(
    "DB_HOST" => "opencartdev.work",
    "DB_USER" => "opencart_weis",
    "DB_PASS" => "!$&O2g0V2*83",
    "DB_DB" => "opencart_stonemysql"
);
$ci_db = array(
    "DB_HOST" => "wwdevserver.info",
    "DB_USER" => "wwdev_stoneledweis",
    "DB_PASS" => "UXr7=pfDmr[M",
    "DB_DB" => "wwdev_stoneledcoop"
);
if ($export->connect_cf_db($cf_db) && $export->connect_ci_db($ci_db)) {
    $export_type_args = explode(",", $export->argv[1]);
    foreach ($export_type_args as $export_type) {
        switch ($export_type) {
            case 'customers':
                $export->insert_customer_cf_to_ci();
                break;
            case 'manual_product_creation':
                $export->manual_product_creation();
                break;
            case 'marketplace':
                $export->insert_products_marketplace_cf_to_ci();
                break;
            case 'products':
                $export->insert_products_cf_to_ci();
                break;
            case 'orders':
                $export->insert_orders_cf_to_ci();
                break;
            case 'orderitems':
                $export->insert_orderitems_cf_to_ci();
                break;
            case 'shares':
                $export->insert_products_share_cf_to_ci();
                break;
            case 'orderpayments':
                $export->insert_orders_payment_cf_to_ci();
                break;
            case 'order_transactions':
                $export->insert_orders_transactions_cf_to_ci();
                break;
            case 'update_parent_products_stocks':
                $export->update_parent_products_stocks();
                break;
            default:
                $export->_error();
                break;
        }
    }
}
exit();

class export
{
    var $argv = array(),
        $support_export = array("customers", "marketplace", "products", "shares", "orders", "orderitems", "orderpayments", "order_transactions", "update_parent_products_stocks", "manual_product_creation"),
        $time_stemp,
        $handle,
        $log_file,
        $db,
        //$limit = "LIMIT 1";
        $limit = "";

    function __construct($argv)
    {
        $this->argv = $argv;
        $this->time_stemp = date("Dd-M-Y-H-i-s");
    }

    function _error($msg = '')
    {
        if ($msg == '') {
            $msg = "Please provide which records to export.";
            $msg .= "\n- " . implode("\n- ", $this->support_export);
        }
        echo $msg;
        return;
    }

    function _valid_export_type()
    {
        $return = true;
        if (!isset($this->argv[1])) {
            $return = false;
        } else {
            //support multiple records export csv
            $export_type_args = explode(",", $this->argv[1]);
            foreach ($export_type_args as $export_type_arg) {
                if (!in_array($export_type_arg, $this->support_export)) {
                    $return = false;
                    break;
                }
            }
        }
        return $return;
    }

    function connect_cf_db($db_variables)
    {
        $mysqli = new mysqli($db_variables['DB_HOST'], $db_variables['DB_USER'], $db_variables['DB_PASS'], $db_variables['DB_DB']);
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            return false;
        }
        $this->cfdb = $mysqli;
        return true;
    }

    function connect_ci_db($db_variables)
    {
        $mysqli = new mysqli($db_variables['DB_HOST'], $db_variables['DB_USER'], $db_variables['DB_PASS'], $db_variables['DB_DB']);
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            return false;
        }
        $this->cidb = $mysqli;
        return true;
    }

    function savelog($status_msg)
    {
        if (fwrite($this->log_file, $status_msg)) {
            return true;
        }
        return false;
    }

    function openLogFile($file_prefix)
    {
        $log_name = $file_prefix . "-log-" . $this->time_stemp . ".txt";
        if (file_exists($log_name)) {
            $this->log_file = fopen($log_name, "a");
        } else {
            $this->log_file = fopen($log_name, "w");
        }
        if ($this->log_file) {
            return true;
        }
        return false;
    }

    function _closelog()
    {
        if (fclose($this->log_file)) {
            return true;
        }
        return false;
    }

    function progress($export_type, $done, $total, $size = 30)
    {
        static $start_time;
        // if we go over our bound, just ignore it
        if ($done > $total) return;
        if (empty($start_time)) $start_time = time();
        $now = time();
        $perc = (float)($done / $total);
        $bar = floor($perc * $size);
        $status_bar = "\r$export_type [";
        $status_bar .= str_repeat("=", $bar);
        if ($bar < $size) {
            $status_bar .= ">";
            $status_bar .= str_repeat(" ", $size - $bar);
        } else {
            $status_bar .= "=";
        }
        $disp = number_format($perc * 100, 0);
        $status_bar .= "] $disp%  $done/$total";
        $rate = ($now - $start_time) / $done;
        $left = $total - $done;
        $eta = round($rate * $left, 2);
        $elapsed = $now - $start_time;
        $status_bar .= " remaining: " . number_format($eta) . " sec.  elapsed: " . number_format($elapsed) . " sec.";
        echo "$status_bar  ";
        flush();
        // when done, send a newline
        if ($done == $total) {
            echo "\n";
        }
    }

    function escape($value)
    {
        // $db_object->conn_id->set_charset("utf8");
        // $db_object->conn_id->query("SET SQL_MODE = ''");

        return str_replace("'", "\'", $value);
    }

    function _validat_harvest($cf_harvest)
    {
        $query = "SELECT * FROM `ww_harvests` WHERE `name` LIKE '%" . $cf_harvest['title'] . "%' OR `display_name` LIKE '%" . $cf_harvest['displayTitle'] . "%' OR `marketplace_display_name` LIKE '%" . $cf_harvest['marketplaceTitle'] . "%'";
        $records = $this->cidb->query($query);
        $records = $records->fetch_object();
        $harvest_id = '';
        if (isset($records->id)) {
            $harvest_id = $records->id;
        }
        return $harvest_id;
    }

    function insert_new_harvest()
    {
        $query = "SELECT * FROM `dbo.harvests`";
        $records = $this->cfdb->query($query);
        $records = $records->fetch_all(MYSQLI_ASSOC);
        $harvest_id = '';
        $harvest_insert_query = "INSERT INTO `ww_harvests` (`id`, `name`, `display_name`, `marketplace_display_name`, `description`, `date_from`, `date_to`, `deliveries`, `active`, `visible`, `disallowed_products`) VALUES(__VALUES__)";
        foreach ($records as $record) {
            $harvest_id = $this->_validat_harvest($record);
            if ($harvest_id == '') {
                $harvest_values = [];
                $harvest_values[] = $record['ID'];
                $harvest_values[] = $this->escape($record['title']);
                $harvest_values[] = $this->escape($record['displayTitle']);
                $harvest_values[] = $this->escape($record['marketplaceTitle']);
                $harvest_values[] = $this->escape($record['description']);
                $harvest_values[] = $record['startDate'];
                $harvest_values[] = $record['endDate'];
                $harvest_values[] = $record['deliveries'];
                $harvest_values[] = $record['display'];
                $harvest_values[] = $record['display'];
                $harvest_values[] = $record['productIDs'];
                $ww_harvest_values = "'" . implode("','", $harvest_values) . "'";
                $ww_harvest_values = str_replace("__VALUES__", $ww_harvest_values, $harvest_insert_query);
                $this->cidb->query($ww_harvest_values);
                $harvest_id = mysqli_insert_id($this->cidb);
            }
        }
        return $harvest_id;
    }

    function insert_products_share_options($ci_product_id, $cf_product)
    {
        $query = "SELECT * FROM `dbo.shareOptions` WHERE `shareID` = '" . $cf_product['ID'] . "'";
        $records = $this->cfdb->query($query);
        $records = $records->fetch_all(MYSQLI_ASSOC);
        $share_insert_query = "INSERT INTO `ww_product_variations` (`id`,`product_id`, `vendor_id`, `master_id`, `name`, `description`, `price_change`, `amount`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `amount_type`, `stock_reduction`, `available_online`, `active`, `visible`, `display_all_vendors`, `start_delivery`, `end_delivery`, `product_image`, `product_image_type`, `coldfusion_share_id`) VALUES(___VALUES___);";

        foreach ($records as $cf_share_option) {
            $cf_share_option['stockReduction'] = $cf_share_option['stockReduction'] == 2 ? 'half_share' : 'one_share';
            $pimage = "https://www.stoneledge.farm/img/marketplace/" . $cf_share_option['image'];
            $price_change = ($cf_share_option['price'] != '' || $cf_share_option['price'] > 0) ? "new_price" : "none";
            $amount_type = ($cf_share_option['price'] != '' || $cf_share_option['price'] > 0) ? "fixed" : 0;
            $available_online = ($cf_share_option['display'] == 1) ? "1" : "0";
            $active = ($cf_share_option['display'] == 1) ? "1" : "0";
            $visible = ($cf_share_option['display'] == 1) ? "1" : "0";
            $startDelivery = ($cf_share_option['startDelivery'] == '') ? "0" : "1";
            $endDelivery = ($cf_share_option['endDelivery'] == '') ? "0" : "1";

            $cfquery = "SELECT * FROM `ww_product_variations` where `coldfusion_share_id` =" . $cf_share_option["ID"];
            $checkrecord = $this->cidb->query($cfquery);
            $checkrecord = $checkrecord->fetch_all(MYSQLI_ASSOC);
            $check_count = count($checkrecord);
            if ($check_count > 0) {
                $ww_share_option_update = "update `ww_product_variations` set `name`='" . $this->escape($cf_share_option['title']) . "',`description`='" . $this->escape($cf_share_option['description']) . "',`price_change`='" . $price_change . "',`amount`='" . $cf_share_option['price'] . "',`initial_stock`='" . $cf_share_option['stock'] . "',`available_stock`='" . $cf_share_option['stock'] . "',`amount_type`='" . $amount_type . "',`stock_reduction`='" . $cf_share_option['stockReduction'] . "',`available_online`='" . $available_online . "',`active`='" . $active . "',`visible`='" . $visible . "',`start_delivery`='" . $startDelivery . "',`end_delivery`='" . $endDelivery . "',`product_image`='" . $pimage . "',`product_image_type`='link' where `coldfusion_share_id` ='" . $cf_share_option["ID"] . "'";

                if ($this->cidb->query($ww_share_option_update)) {
                    $status_msg = "Share options update >> coldfusion_share_id ='" . $cf_share_option['ID'] . "' >> Success .";
                } else {
                    $status_msg = "Share options update >> coldfusion_share_id ='" . $cf_share_option['ID'] . "'  >> FAIL >> Error >> " . $this->cidb->error;
                }

                $this->savelog("\n- " . $status_msg);
            } else {
                /************************************* Insert Master option starts *****************************************************************/

                $queryp = "SELECT * FROM `ww_product_variations` ORDER BY `ww_product_variations`.`id` DESC limit 1 ";
                $lastproduct = $this->cidb->query($queryp);
                $lastproduct = $lastproduct->fetch_array(MYSQLI_ASSOC);
                $new_product_id = $lastproduct['id'] + 1;

                $share_master_option = [];
                $share_master_option[] = $new_product_id;//id
                $share_master_option[] = $ci_product_id; //product_id
                $share_master_option[] = 0; //vendor_id
                $share_master_option[] = 0; //master_id --> This need to identified
                $share_master_option[] = $this->escape($cf_share_option['title']); //name
                $share_master_option[] = $this->escape($cf_share_option['description']); //description
                $share_master_option[] = ($cf_share_option['price'] != '' || $cf_share_option['price'] > 0) ? "new_price" : "none"; //price_change
                $share_master_option[] = $cf_share_option['price']; //amount
                $share_master_option[] = $cf_share_option['stock']; //initial_stock
                $share_master_option[] = 0; //initial_items
                $share_master_option[] = $cf_share_option['stock']; //available_stock
                $share_master_option[] = 0; //available_items
                $share_master_option[] = 0; //stock_sold
                $share_master_option[] = 0; //items_sold
                $share_master_option[] = ($cf_share_option['price'] != '' || $cf_share_option['price'] > 0) ? "fixed" : 0; //amount_type
                $share_master_option[] = $cf_share_option['stockReduction']; //stock_reduction
                $share_master_option[] = ($cf_share_option['display'] == 1) ? "1" : "0"; //available_online
                $share_master_option[] = ($cf_share_option['display'] == 1) ? "1" : "0"; //active
                $share_master_option[] = ($cf_share_option['display'] == 1) ? "1" : "0"; //visible
                $share_master_option[] = 1; //display_all_vendors
                $share_master_option[] = ($cf_share_option['startDelivery'] == '') ? "0" : "1"; //start_delivery
                $share_master_option[] = ($cf_share_option['endDelivery'] == '') ? "0" : "1"; //end_delivery
                $share_master_option[] = $pimage; //product_image
                $share_master_option[] = 'link'; //product_image_type
                $share_master_option[] = $cf_share_option['ID']; //coldfusion_share_id
                //csaIDs :: Need to identify
                $share_master_product_details_values = "'" . implode("','", $share_master_option) . "'";
                $share_master_product_details_values = str_replace("___VALUES___", $share_master_product_details_values, $share_insert_query);
                if ($this->cidb->query($share_master_product_details_values)) {
                    /************************************* Insert Master option ends *****************************************************************/

                    $option_array = explode(':', $cf_share_option['csaIDs']);
                    $option_array22 = array_filter($option_array);

                    foreach ($option_array22 as $cf_csa_id) {

                        if ($cf_csa_id != 22 && $cf_csa_id != 29) {
                            $vendordata = $this->get_ci_vendorid_by_name($cf_csa_id);
                            $vendor_id = isset($vendordata['id']) ? $vendordata['id'] : 0;
                        } else {
                            $vendor_id = ($cf_csa_id == 22) ? 14 : 0;
                        }

                        $querymasterp = "SELECT * FROM `ww_product_variations` where product_id = '" . $ci_product_id . "' && vendor_id = 0 && master_id= 0 ";
                        $masterproduct = $this->cidb->query($querymasterp);
                        $masterproduct = $masterproduct->fetch_array(MYSQLI_ASSOC);
                        $master_product_id = $masterproduct['id'];

                        $queryp = "SELECT * FROM `ww_product_variations` ORDER BY `ww_product_variations`.`id` DESC limit 1 ";
                        $lastproduct = $this->cidb->query($queryp);
                        $lastproduct = $lastproduct->fetch_array(MYSQLI_ASSOC);
                        $new_product_id = $lastproduct['id'] + 1;

                        $share_option = [];
                        $share_option[] = $new_product_id;//id
                        $share_option[] = $ci_product_id; //product_id
                        $share_option[] = $vendor_id; //vendor_id
                        $share_option[] = $master_product_id; //master_id
                        $share_option[] = $this->escape($cf_share_option['title']); //name
                        $share_option[] = $this->escape($cf_share_option['description']); //description
                        $share_option[] = ($cf_share_option['price'] != '' || $cf_share_option['price'] > 0) ? "new_price" : "none"; //price_change
                        $share_option[] = $cf_share_option['price']; //amount
                        $share_option[] = $cf_share_option['stock']; //initial_stock
                        $share_option[] = 0; //initial_items
                        $share_option[] = $cf_share_option['stock']; //available_stock
                        $share_option[] = 0; //available_items
                        $share_option[] = 0; //stock_sold
                        $share_option[] = 0; //items_sold
                        $share_option[] = ($cf_share_option['price'] != '' || $cf_share_option['price'] > 0) ? "fixed" : 0; //amount_type
                        $share_option[] = $cf_share_option['stockReduction']; //stock_reduction
                        $share_option[] = ($cf_share_option['display'] == 1) ? "1" : "0"; //available_online
                        $share_option[] = ($cf_share_option['display'] == 1) ? "1" : "0"; //active
                        $share_option[] = ($cf_share_option['display'] == 1) ? "1" : "0"; //visible
                        $share_option[] = 1; //display_all_vendors
                        $share_option[] = ($cf_share_option['startDelivery'] == '') ? "0" : "1"; //start_delivery
                        $share_option[] = ($cf_share_option['endDelivery'] == '') ? "0" : "1"; //end_delivery
                        $share_option[] = "https://www.stoneledge.farm/img/marketplace/" . $cf_share_option['image']; //product_image
                        $share_option[] = ''; //product_image_type
                        $share_option[] = $cf_share_option['ID']; //coldfusion_share_id
                        //csaIDs :: Need to identify
                        $product_details_values = "'" . implode("','", $share_option) . "'";
                        $product_details_values = str_replace("___VALUES___", $product_details_values, $share_insert_query);
                        if ($this->cidb->query($product_details_values)) {
                            $this->savelog("\n- " . "Share Option Add >> vendor Id = " . $vendor_id . " >> Master Id = " . $master_product_id . ">> CF Option ID = " . $cf_share_option['ID'] . ">> CI Product Id = " . $ci_product_id . " >> CF Product Id = " . $cf_product['ID'] . " >> Success .");
                        } else {
                            $this->savelog("\n- " . "Share Option Add >> vendor Id = " . $vendor_id . " >> Master Id = " . $master_product_id . " >> CF Option ID = " . $cf_share_option['ID'] . ">> CI Product Id = " . $ci_product_id . " >> CF Product Id = " . $cf_product['ID'] . ">> Error " . $this->cidb->error);
                        }
                    }
                } else {
                    $this->savelog("\n- " . "Share Option Add >> CF Option ID = " . $cf_share_option['ID'] . ">> CI Product Id =" . $ci_product_id . " >> CF Product Id = " . $cf_product['ID'] . ">> Error " . $this->cidb->error);
                }
            }
        }
        return true;
    }

    function insert_products_cf_to_ci()
    {
        $this->openLogFile("products");
        $query = "SELECT * FROM `product_variation_mapping` ORDER BY `id` " . $this->limit;

        $records = $this->cidb->query($query);
        $records = $records->fetch_all(MYSQLI_ASSOC);
        $total_records = count($records);
        $processed = 0;

        foreach ($records as $product) {
            $query = "SELECT * FROM `dbo.productOptions` where `ID` =" . $product['coldfusion_product_variation_id'];
            $record = $this->cfdb->query($query);
            $prd = $record->fetch_array(MYSQLI_ASSOC);
            if ($product['ci_product_variation_id'] == "not_found") {
                $product_insert_query = "INSERT INTO `ww_product_variations` (`id`,`product_id`, `vendor_id`, `master_id`,`name`, `description`, `price_change`, `amount`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `amount_type`,`stock_reduction`, `available_online`, `active`,`visible`, `display_all_vendors`, `start_delivery`,`end_delivery`, `product_image`,`product_image_type`) VALUES(___VALUES___);";

                $stock_reduction = ($prd['trackInventory'] == 1) ? "one_share" : "";
                $queryp = "SELECT * FROM `ww_product_variations` ORDER BY `ww_product_variations`.`id` DESC limit 1 ";
                $lastproduct = $this->cidb->query($queryp);
                $lastproduct = $lastproduct->fetch_array(MYSQLI_ASSOC);
                $new_product_id = $lastproduct['id'] + 1;
                if (strpos($product['ci_product_id'], "manually_crated") !== false) {
                    $parentdata = explode("=", $product['ci_product_id']);
                    $parentid = $parentdata[1];
                } else {
                    $parentid = $product['ci_product_id'];
                }

                $product_details = [];
                $product_details[] = $new_product_id; // order id
                $product_details[] = $parentid;//product_id (marketplaceid)
                $product_details[] = '0'; //vendor_id --> This need to identified
                $product_details[] = '0'; //master_id --> This need to identified
                $product_details[] = $this->escape($prd['title']); //name
                $product_details[] = $this->escape($prd['description']); //description
                $product_details[] = 'new_price'; //price_change
                $product_details[] = $prd['price']; // amount
                $product_details[] = "0"; //initial_stock
                $product_details[] = "0"; //initial_items
                $product_details[] = $prd['stock']; //available_stock
                $product_details[] = "0"; //available_items
                $product_details[] = "0"; //stock_sold
                $product_details[] = "0"; //items_sold
                $product_details[] = "fixed"; //amount_type
                $product_details[] = $stock_reduction; //stock_reduction
                $product_details[] = ($prd['display'] == 1) ? "1" : "0"; //available_online
                $product_details[] = ($prd['display'] == 1) ? "1" : "0"; //active
                $product_details[] = ($prd['display'] == 1) ? "1" : "0"; //visible
                $product_details[] = "0"; //display_all_vendors
                $product_details[] = "0"; //start_delivery
                $product_details[] = "0"; //end_delivery
                $product_details[] = "https://www.stoneledge.farm/img/marketplace/" . $prd['image']; //product_image
                $product_details[] = ""; //product_image_type

                $product_details_values = "'" . implode("','", $product_details) . "'";
                $product_details_values = str_replace("___VALUES___", $product_details_values, $product_insert_query);

                if ($this->cidb->query($product_details_values)) {
                    $product_variation_mapping_update = "update product_variation_mapping set `ci_product_variation_id`='" . $new_product_id . "' where `coldfusion_product_variation_id` ='" . $product['coldfusion_product_variation_id'] . "'";
                    $this->cidb->query($product_variation_mapping_update);

                    $status_msg = "Product variation '" . $prd['ID'] . "' inserted, new variation id '" . $new_product_id . "'>> Success";
                } else {
                    $status_msg = "Product variation  '" . $prd['ID'] . "'  >> FAIL >> Error >> " . $this->cidb->error;
                }
            } else {
                $name = isset($prd['title']) ? $this->escape($prd['title']) : "";
                $description = isset($prd['description']) ? $this->escape($prd['description']) : "";
                $amount = $prd['price'];
                $available_stock = $prd['stock'];
                $stock_reduction = ($prd['trackInventory'] == 1) ? "one_share" : "";
                $available_online = ($prd['display'] == 1) ? "1" : "0";
                $active = ($prd['display'] == 1) ? "1" : "0";
                $visible = ($prd['display'] == 1) ? "1" : "0";
                $product_image = "https://www.stoneledge.farm/img/marketplace/" . $prd['image'];

                $product_variation_values_update = "update `ww_product_variations` set `name`='" . $name . "',`description`='" . $description . "',`price_change`='new_price',`amount`='" . $amount . "',`available_stock`='" . $available_stock . "',`amount_type`='fixed',`stock_reduction`='" . $stock_reduction . "',`available_online`='" . $available_online . "',`active`='" . $active . "',`visible`='" . $visible . "',`product_image`='" . $product_image . "' where id='" . $product['ci_product_variation_id'] . "'";

                if ($this->cidb->query($product_variation_values_update)) {
                    $status_msg = "Updated product variation CF id'" . $prd['ID'] . "'CI id '" . $product['ci_product_variation_id'] . "' >> Success";
                } else {
                    $status_msg = "Update product variation  CF id'" . $prd['ID'] . "'CI id '" . $product['ci_product_variation_id'] . "' >> FAIL >> Error >> " . $this->cidb->error;
                }
            }

            $this->savelog("\n- " . $status_msg);
            $processed++;
            $this->progress("Product variations >>", $processed, $total_records);
        }
        $this->_closelog();
    }

    function insert_products_share_cf_to_ci()
    {
        $this->openLogFile('shares');
        //first insert new harvests
        $harvest_id = $this->insert_new_harvest();
        if ($harvest_id == '') {
            die("can not insety harvest in CI database:: " . __FILE__ . ' :: ' . __LINE__);
        }
        $query = "SELECT * FROM `dbo.share` ORDER BY `ID` " . $this->limit;
        $records = $this->cfdb->query($query);
        $records = $records->fetch_all(MYSQLI_ASSOC);
        $total_records = count($records);
        $processed = 0;
        $product_insert_query = "INSERT INTO `ww_products` (`id`,`vendor_id`, `master_id`, `category_id`, `name`, `description`, `product_image`, `default_price`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `allowed_product_variations`, `allow_product_partners`, `num_sales_allow_comp`, `mandatory_product`, `suggested_product`, `display_order`, `is_featured`, `active`, `visible`, `product_type`, `is_stock_csa_level`, `is_unlimited_stock`, `display_all_vendors`, `harvest_id`, `start_delivery`, `end_delivery`, `product_image_type`, `archive`,`model`,`coldfusion_id`) VALUES(___VALUES___);";
        foreach ($records as $prd_share) {
            $pimage = "https://www.stoneledge.farm/img/shares/" . $this->escape($prd_share['image']);

            $ciquery = "SELECT * FROM `ww_products` where `product_type`='share' && `coldfusion_id` ='" . $prd_share["ID"] . "'";
            $checkrecord = $this->cidb->query($ciquery);
            $checkrecord = $checkrecord->fetch_all(MYSQLI_ASSOC);
            $check_count = count($checkrecord);
            if ($check_count > 0) {
                $is_featured = ($prd_share['feature'] == 1) ? "1" : "0";
                $active = ($prd_share["display"] == 1) ? "1" : "0";
                $visible = ($prd_share["display"] == 1) ? "1" : "0";

                $ww_share_products_update = "update ww_products set `name`='" . $this->escape($prd_share['title']) . "',`description`='" . $this->escape($prd_share['htmlList']) . "',`product_image`='" . $pimage . "',`default_price`='" . $prd_share['defaultPrice'] . "',`initial_stock`='" . $prd_share["initialStock"] . "',`available_stock`='" . $prd_share["initialStock"] . "',`allow_product_partners`='" . $prd_share['allowSharePartners'] . "',`num_sales_allow_comp`='" . $prd_share['compSharesCnt'] . "',`is_featured`='" . $is_featured . "',`active`='" . $active . "',`visible`='" . $visible . "',`harvest_id`='" . $harvest_id . "',`start_delivery`='" . $prd_share['startDelivery'] . "',`end_delivery`='" . $prd_share['endDelivery'] . "',`model`='" . $this->escape($prd_share['title']) . "' where `product_type`='share' && `coldfusion_id` ='" . $prd_share["ID"] . "'";

                if ($this->cidb->query($ww_share_products_update)) {
                    $status_msg = "Share products >> coldfusion_id ='" . $prd_share['ID'] . "' >> Success >> Updated";
                } else {
                    $status_msg = "Share products >> coldfusion_id ='" . $prd_share['ID'] . "'  >> FAIL >> Error >> " . $this->cidb->error;
                }

                $this->savelog("\n- " . $status_msg);

                $ciquerymaster = "SELECT * FROM `ww_products` where `master_id`=0 && `vendor_id`=0 && `product_type`='share' && `coldfusion_id` ='" . $prd_share["ID"] . "'";
                $checkrecordmaster = $this->cidb->query($ciquerymaster);
                $checkrecordmaster = $checkrecordmaster->fetch_array(MYSQLI_ASSOC);
                if ($checkrecordmaster != "") {
                    $product_ci_id = $checkrecordmaster['id'];
                    $insert_options_status = $this->insert_products_share_options($product_ci_id, $prd_share);
                }
            } else {
                $queryp = "SELECT * FROM `ww_products` ORDER BY `ww_products`.`id` DESC limit 1 ";
                $lastproduct = $this->cidb->query($queryp);
                $lastproduct = $lastproduct->fetch_array(MYSQLI_ASSOC);
                $new_product_id = $lastproduct['id'] + 1;

                $product_share_master = [];
                $product_share_master[] = $new_product_id;//product id
                $product_share_master[] = '0'; //vendor_id --> This need to identified
                $product_share_master[] = '0'; //master_id --> This need to identified
                $product_share_master[] = '0'; //category_id --> This need to identified
                $product_share_master[] = $this->escape($prd_share['title']); //name
                $product_share_master[] = $this->escape($prd_share['htmlList']); //description
                $product_share_master[] = $pimage; //product_image
                $product_share_master[] = $prd_share['defaultPrice']; //default_price
                $product_share_master[] = $prd_share["initialStock"]; //initial_stock
                $product_share_master[] = "0"; //initial_items
                $product_share_master[] = $prd_share["initialStock"]; //available_stock
                $product_share_master[] = "0"; //available_items
                $product_share_master[] = "0.00"; //stock_sold
                $product_share_master[] = "0"; //items_sold
                $product_share_master[] = ""; //allowed_product_variations
                $product_share_master[] = $prd_share['allowSharePartners']; //allow_product_partners
                $product_share_master[] = $prd_share['compSharesCnt']; //num_sales_allow_comp
                $product_share_master[] = "0"; //mandatory_product
                $product_share_master[] = "0"; //suggested_product
                $product_share_master[] = "0"; //display_order
                $product_share_master[] = ($prd_share['feature'] == 1) ? "1" : "0"; //is_featured
                $product_share_master[] = ($prd_share["display"] == 1) ? "1" : "0"; //active
                $product_share_master[] = ($prd_share["display"] == 1) ? "1" : "0"; //visible
                $product_share_master[] = "share"; //product_type
                $product_share_master[] = '1'; //is_stock_csa_level
                $product_share_master[] = '0'; //is_unlimited_stock
                $product_share_master[] = '0'; //display_all_vendors
                $product_share_master[] = $harvest_id; //harvest_id
                $product_share_master[] = $prd_share['startDelivery']; //start_delivery
                $product_share_master[] = $prd_share['endDelivery']; //end_delivery
                $product_share_master[] = ''; //product_image_type
                $product_share_master[] = 0; //archive
                $product_share_master[] = $this->escape($prd_share['title']); //model
                $product_share_master[] = $prd_share['ID']; //coldfusion_id
                $product_share_master_values = "'" . implode("','", $product_share_master) . "'";
                $product_share_master_values = str_replace("___VALUES___", $product_share_master_values, $product_insert_query);

                if ($this->cidb->query($product_share_master_values)) {
                    /********************************** Assign vendor to master share start *********************************/

                    $querycsa = "SELECT GROUP_CONCAT(csaIDs) as csaids FROM `dbo.shareOptions` WHERE shareID='" . $prd_share["ID"] . "'";
                    $productcsas = $this->cfdb->query($querycsa);
                    $productcsas = $productcsas->fetch_array(MYSQLI_ASSOC);
                    $productcsas = str_replace(",", "", $productcsas['csaids']);

                    $option_array = explode(':', $productcsas);
                    $option_array22 = array_unique($option_array);
                    $option_array22 = array_filter($option_array22);

                    foreach ($option_array22 as $cf_csa_id) {
                        if ($cf_csa_id != 22 && $cf_csa_id != 29) {
                            $vendordata = $this->get_ci_vendorid_by_name($cf_csa_id);
                            $vendor_id = isset($vendordata['id']) ? $vendordata['id'] : 0;
                        } else {
                            $vendor_id = ($cf_csa_id == 22) ? 14 : 0;
                        }

                        $querylastp = "SELECT * FROM `ww_products` ORDER BY `ww_products`.`id` DESC limit 1 ";
                        $productlast = $this->cidb->query($querylastp);
                        $productlast = $productlast->fetch_array(MYSQLI_ASSOC);
                        $next_product_id = $productlast['id'] + 1;

                        $product_details = [];
                        $product_details[] = $next_product_id;//product id
                        $product_details[] = $vendor_id; //vendor_id
                        $product_details[] = $new_product_id; //master_id
                        $product_details[] = '0'; //category_id --> This need to identified
                        $product_details[] = $this->escape($prd_share['title']); //name
                        $product_details[] = $this->escape($prd_share['htmlList']); //description
                        $product_details[] = "https://www.stoneledge.farm/img/shares/" . $this->escape($prd_share['image']); //product_image
                        $product_details[] = $prd_share['defaultPrice']; //default_price
                        $product_details[] = $prd_share["initialStock"]; //initial_stock
                        $product_details[] = "0"; //initial_items
                        $product_details[] = $prd_share["initialStock"]; //available_stock
                        $product_details[] = "0"; //available_items
                        $product_details[] = "0.00"; //stock_sold
                        $product_details[] = "0"; //items_sold
                        $product_details[] = ""; //allowed_product_variations
                        $product_details[] = $prd_share['allowSharePartners']; //allow_product_partners
                        $product_details[] = $prd_share['compSharesCnt']; //num_sales_allow_comp
                        $product_details[] = "0"; //mandatory_product
                        $product_details[] = "0"; //suggested_product
                        $product_details[] = "0"; //display_order
                        $product_details[] = ($prd_share['feature'] == 1) ? "1" : "0"; //is_featured
                        $product_details[] = ($prd_share["display"] == 1) ? "1" : "0"; //active
                        $product_details[] = ($prd_share["display"] == 1) ? "1" : "0"; //visible
                        $product_details[] = "share"; //product_type
                        $product_details[] = '1'; //is_stock_csa_level
                        $product_details[] = '0'; //is_unlimited_stock
                        $product_details[] = '0'; //display_all_vendors
                        $product_details[] = $harvest_id; //harvest_id
                        $product_details[] = $prd_share['startDelivery']; //start_delivery
                        $product_details[] = $prd_share['endDelivery']; //end_delivery
                        $product_details[] = ''; //product_image_type
                        $product_details[] = 0; //archive
                        $product_details[] = $this->escape($prd_share['title']); //name
                        $product_details[] = $prd_share['ID']; //coldfusion_id
                        $product_details_values = "'" . implode("','", $product_details) . "'";
                        $product_details_values = str_replace("___VALUES___", $product_details_values, $product_insert_query);

                        if ($this->cidb->query($product_details_values)) {
                            $status_msg = "Share product >> Add >> Product id ='" . $next_product_id . "' master id = '" . $new_product_id . "', venor id ='" . $vendor_id . "' >> Success . ";
                            $this->savelog("\n- " . $status_msg);
                        } else {
                            $status_msg = "Share product Add '" . $next_product_id . "' master id = '" . $new_product_id . "', venor id ='" . $vendor_id . "' >> FAIL >> Error >> " . $this->cidb->error;
                            $this->savelog("\n- " . $status_msg);
                        }
                    }

                    /********************************** Assign vendor to master share end ***********************************/

                    $product_ci_id = $new_product_id;
                    $insert_options_status = $this->insert_products_share_options($product_ci_id, $prd_share);
                } else {
                    $status_msg = "Share product Add >> '" . $this->escape($prd_share['title']) . "' >> FAIL >> Error >> " . $this->cidb->error;
                    $this->savelog("\n- " . $status_msg);
                }
            }
            $processed++;
            $this->progress("Product [Shares] >>", $processed, $total_records);
        }
    }

    function insert_products_marketplace_cf_to_ci()
    {
        $this->openLogFile("marketplace");
        //first insert new harvests
        $harvest_id = $this->insert_new_harvest();
        if ($harvest_id == '') {
            die("can not insety harvest in CI database:: " . __FILE__ . ' :: ' . __LINE__);
        }

        $query = "SELECT * FROM `cf_ci_product_mapping` ORDER BY `id` " . $this->limit;
        $records = $this->cidb->query($query);
        $records = $records->fetch_all(MYSQLI_ASSOC);
        $total_records = count($records);
        $processed = 0;

        foreach ($records as $prd) {

            if ($prd['ci_product_id'] == 'not_found') {
                $query = "SELECT * FROM `dbo.products` where `ID` =" . $prd['coldfusion_product_id'];
                $record = $this->cfdb->query($query);
                $prd_marketplace = $record->fetch_array(MYSQLI_ASSOC);
                //$total_records = count($records);
                //$processed = 0;
                $product_insert_query = "INSERT INTO `ww_products` (`id`,`vendor_id`, `master_id`, `category_id`, `name`, `description`, `product_image`, `default_price`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `allowed_product_variations`, `allow_product_partners`, `num_sales_allow_comp`, `mandatory_product`, `suggested_product`, `display_order`, `is_featured`, `active`, `visible`, `product_type`, `is_stock_csa_level`, `is_unlimited_stock`, `display_all_vendors`, `harvest_id`, `start_delivery`, `end_delivery`, `product_image_type`, `archive`,`model`) VALUES(___VALUES___);";

                $queryp = "SELECT * FROM `ww_products` ORDER BY `ww_products`.`id` DESC limit 1 ";
                $lastproduct = $this->cidb->query($queryp);
                $lastproduct = $lastproduct->fetch_array(MYSQLI_ASSOC);
                $new_product_id = $lastproduct['id'] + 1;


                $descriptiondata = $this->get_cf_product_description($prd_marketplace['categoryID']);
                $pro_desc = isset($descriptiondata['htmlList']) ? $this->escape($descriptiondata['htmlList']) : "";

                $product_details = [];
                $product_details[] = $new_product_id;//product id
                $product_details[] = '0'; //vendor_id
                $product_details[] = '0'; //master_id
                $product_details[] = '0'; //category_id
                $product_details[] = $this->escape($prd_marketplace['title']); //name
                $product_details[] = $pro_desc; //description
                $product_details[] = "https://www.stoneledge.farm/img/marketplace/" . $this->escape($prd_marketplace['image']); //product_image
                $product_details[] = "0.00"; //default_price
                $product_details[] = "0.00"; //initial_stock
                $product_details[] = "0"; //initial_items
                $product_details[] = "0.00"; //available_stock
                $product_details[] = "0"; //available_items
                $product_details[] = "0.00"; //stock_sold
                $product_details[] = "0"; //items_sold
                $product_details[] = ""; //allowed_product_variations
                $product_details[] = "0"; //allow_product_partners
                $product_details[] = "0"; //num_sales_allow_comp
                $product_details[] = "0"; //mandatory_product
                $product_details[] = "0"; //suggested_product
                $product_details[] = "0"; //display_order
                $product_details[] = ($prd_marketplace['feature'] == 1) ? "1" : "0"; //is_featured
                $product_details[] = ($prd_marketplace["display"] == 1) ? "1" : "0"; //active
                $product_details[] = ($prd_marketplace["display"] == 1) ? "1" : "0"; //visible
                $product_details[] = "product"; //product_type
                $product_details[] = '0'; //is_stock_csa_level
                $product_details[] = '0'; //is_unlimited_stock
                $product_details[] = '1'; //display_all_vendors
                $product_details[] = $harvest_id; //harvest_id
                $product_details[] = 0; //start_delivery
                $product_details[] = 0; //end_delivery
                $product_details[] = ''; //product_image_type
                $product_details[] = 0; //archive
                $product_details[] = $this->escape($prd_marketplace['title']); //model

                $product_details_values = "'" . implode("','", $product_details) . "'";
                $product_details_values = str_replace("___VALUES___", $product_details_values, $product_insert_query);
                if ($this->cidb->query($product_details_values)) {
                    $status_msg = "Marketplace product '" . $prd_marketplace['title'] . "' inserted >> Success";

                    $product_variation_mapping_update = "update product_variation_mapping set `ci_product_id`='" . $new_product_id . "' where `coldfusion_product_id` ='" . $prd['coldfusion_product_id'] . "'";
                    $this->cidb->query($product_variation_mapping_update);

                    $product_mapping_update = "update cf_ci_product_mapping set `ci_product_id`='" . $new_product_id . "' where `coldfusion_product_id` ='" . $prd['coldfusion_product_id'] . "'";
                    $this->cidb->query($product_mapping_update);

                } else {
                    $status_msg = "Marketplace product '" . $prd_marketplace['title'] . "' inserted >> FAIL >> Error >> " . $this->cidb->error;
                }
                $this->savelog("\n- " . $status_msg);
                $processed++;
                $this->progress("Product [Marketplace] >>", $processed, $total_records);

            } else {
                $status_msg = "Marketplace product  '" . $prd['coldfusion_product_id'] . "' Already exist having ci id '" . $prd['ci_product_id'] . "'. >> Success";
                $this->savelog("\n- " . $status_msg);
                $processed++;
                $this->progress("Product [Marketplace] >>", $processed, $total_records);
            }

        }
        $this->_closelog();
    }

    function insert_customer_cf_to_ci()
    {
        $this->openLogFile("customers");
        $query = "SELECT * FROM `dbo.accounts` ORDER BY `ID` " . $this->limit;
        $records = $this->cfdb->query($query);
        $records = $records->fetch_all(MYSQLI_ASSOC);
        $ww_users_insert = "INSERT INTO `ww_users` (`id`,`contact_id`, `user_name`, `password`, `role`, `banned`) VALUES(___VALUES___);";
        $ww_contacts_insert = "INSERT INTO `ww_contacts` (`id`,`vendor_id`, `manages_vendor_id`, `contact_status`, `first_name`, `last_name`, `streetname1`, `streetname2`, `city`, `state`, `zip`, `email`, `phone`, `sms_phone`, `sms_carrier`, `partner_name`, `partner_email`, `partner_phone`, `notes`, `recieve_newsletter`, `volunteer`, `allowed_payment_methods`,`modified_date`,`created_date`) VALUES(___VALUES___);";
        $total_records = count($records);
        $processed = 0;
        foreach ($records as $record) {
            $vendordata = "";
            $vendorManagerdata = "";
            $new_contact_id = "";
            $new_user_id = "";
            $c_status = "";

            if ($record['csaMemberID'] != 22 && $record['csaMemberID'] != 29) {
                $vendordata = $this->get_ci_vendorid_by_name($record['csaMemberID']);
                $vendor_id = isset($vendordata['id']) ? $vendordata['id'] : 0;
            } else {
                $vendor_id = ($record['csaMemberID'] == 22) ? 14 : 0;
            }

            if ($record['csaManagerID'] != 22 && $record['csaManagerID'] != 29) {
                $vendorManagerdata = $this->get_ci_vendorid_by_name($record['csaManagerID']);
                $vendor_manager_id = isset($vendorManagerdata['id']) ? $vendorManagerdata['id'] : 0;
            } else {
                $vendor_manager_id = ($record['csaManagerID'] == 22) ? 14 : 0;
            }

            if ($record['status'] == 1) {
                $c_status = "active";
            } else if ($record['status'] == 0 || $record['status'] == 3) {
                $c_status = "waiting_list";
            } else {
                $c_status = "archived";
            }

            $postdate = ($record['postDate'] == "1900-01-01 00:00:00") ? $record['postDateOLD'] : $record['postDate'];
            $postdate = ($postdate != "") ? $postdate : date("Y-m-d H:i:s");

            $partnerdata = $this->get_cf_partner_details($record['ID']);

            if (isset($partnerdata['name'])) {
                $partner_name = $partnerdata['name'];
                $partner_email = $partnerdata['email'];
                $partner_phone = $partnerdata['phone'];
            } else {
                $partner_name = "";
                $partner_email = "";
                $partner_phone = "";
            }

            $zip = (is_numeric($record['zip'])) ? $record['zip'] : 0;

            $ciquery = "SELECT * FROM `ww_contacts` where `email` ='" . $record['email'] . "'";
            $checkrecord = $this->cidb->query($ciquery);
            $checkrecord = $checkrecord->fetch_array(MYSQLI_ASSOC);
            if ($checkrecord != "") {
                $updated = 0;
                $partnerdetails = $this->get_cf_partner_details($record['ID']);
                $volunteer = ($record['accessLevel'] == 0) ? "1" : "0";

                $ww_contacts_update = "update ww_contacts set `vendor_id`='" . $vendor_id . "',`manages_vendor_id`='" . $vendor_manager_id . "',`contact_status`='" . $c_status . "',`first_name`='" . $this->escape($record['firstName']) . "',`last_name`='" . $this->escape($record['lastName']) . "',`streetname1`='" . $this->escape($record['address1']) . "',`streetname2`='" . $this->escape($record['address2']) . "',`city`='" . $record['city'] . "',`state`='" . $record['state'] . "',`zip`='" . $zip . "',`phone`='" . $record['phone'] . "',`sms_phone`='',`sms_carrier`='',`partner_name`='" . $partner_name . "',`partner_email`='" . $partner_email . "',`partner_phone`='" . $partner_phone . "',`notes`='" . $this->escape($record['notes']) . "',`recieve_newsletter`='" . $record['newsletter'] . "',`volunteer`='" . $volunteer . "' where `email` ='" . $record['email'] . "'";
                if ($this->cidb->query($ww_contacts_update)) {
                    $role = ($record['csaManagerID'] != 0) ? '3' : '4'; //role
                    $banned = ($record['allowAccess'] == 0 && $record['status'] == 0) ? "1" : "0"; //banned
                    $ww_users_update = "update ww_users set `role`='" . $role . "',`banned`='" . $banned . "' where `user_name` ='" . $record['email'] . "'";
                    if ($this->cidb->query($ww_users_update)) {
                        $this->savelog("\n- " . $record['email'] . "- customer already exist, updated.");
                    } else {
                        $this->savelog("\n- " . $record['email'] . " >> ww_users Update >> FAIL >> Error >> " . $this->cidb->error);
                    }
                } else {
                    $this->savelog("\n- " . $record['email'] . " >> ww_contacts Update >> FAIL >> Error >> " . $this->cidb->error);
                }

                if (!empty($record['notes'])) {
                    $contact_id = $checkrecord['id'];
                    $user_query = "SELECT `id` FROM `ww_users` where `user_name` ='" . $record['email'] . "'";
                    $user = $this->cidb->query($user_query);
                    $user = $user->fetch_array(MYSQLI_ASSOC);
                    $user_id = $user['id'];

                    $ww_comments_delete_query = "DELETE FROM `ww_comments` WHERE comment_for_id = '" . $contact_id . "' AND user_id = '" . $user_id . "' AND comment LIKE '%" . $this->escape($record['notes']) . "%'";
                    $this->cidb->query($ww_comments_delete_query);

                    $ww_comments_values = array();
                    $ww_comments_values[] = 'contacts';
                    $ww_comments_values[] = $checkrecord['id'];
                    $ww_comments_values[] = $user['id'];
                    $ww_comments_values[] = $this->escape($record['notes']);
                    $ww_comments_values[] = $record['postDateOLD'];
                    $ww_comments_values[] = 1;
                    $ww_comments_values[] = $record['notePrivacy'] == 1 ? 0 : 1;
                    $ww_contacts_values = "'" . implode("','", $ww_comments_values) . "'";
                    $ww_comments_query = "INSERT INTO `ww_comments` (`comment_for`, `comment_for_id`, `user_id`, `comment`, `date`, `status`, `is_public`) VALUES($ww_contacts_values);";
                    $this->cidb->query($ww_comments_query);
                }

                $processed++;
                $this->progress("Customers >>", $processed, $total_records);
                continue;
            }

            $queryc = "SELECT * FROM `ww_contacts` ORDER BY `ww_contacts`.`id` DESC limit 1 ";
            $lastcustomer = $this->cidb->query($queryc);
            $lastcustomer = $lastcustomer->fetch_array(MYSQLI_ASSOC);
            $new_contact_id = $lastcustomer['id'] + 1;

            $status_msg = '';
            $ww_contacts = [];
            $ww_contacts[] = $new_contact_id; //id
            $ww_contacts[] = $vendor_id; //vendor_id
            $ww_contacts[] = $vendor_manager_id; //manages_vendor_id
            $ww_contacts[] = $c_status;
            $ww_contacts[] = $this->escape($record['firstName']); //First name
            $ww_contacts[] = $this->escape($record['lastName']); //last name
            $ww_contacts[] = $record['address1']; //streetname1
            $ww_contacts[] = $record['address2']; //streetname21
            $ww_contacts[] = $record['city']; //city
            $ww_contacts[] = $record['state']; //state
            $ww_contacts[] = $zip; //zip
            $ww_contacts[] = $record['email']; //email
            $ww_contacts[] = $record['phone']; //phone
            $ww_contacts[] = ''; //sms_phone
            $ww_contacts[] = ''; //sms_carrier
            $ww_contacts[] = $partner_name; //partner_name
            $ww_contacts[] = $partner_email; //partner_email
            $ww_contacts[] = $partner_phone; //partner_phone
            $ww_contacts[] = $this->escape($record['notes']); //notes
            $ww_contacts[] = $record['newsletter']; //recieve_newsletter
            $ww_contacts[] = ($record['accessLevel'] == 0) ? "1" : "0";//volunteer
            $ww_contacts[] = "b:0;"; //allowed_payment_methods
            $ww_contacts[] = $postdate; //modified_date
            $ww_contacts[] = $postdate; //created_date
            $ww_contacts_values = "'" . implode("','", $ww_contacts) . "'";
            $ww_contacts_values = str_replace("___VALUES___", $ww_contacts_values, $ww_contacts_insert);
            if ($this->cidb->query($ww_contacts_values)) {
                $ciuserquery = "SELECT * FROM `ww_users` where `user_name` ='" . $record['email'] . "'";
                $checkuserrecord = $this->cidb->query($ciuserquery);
                $checkuserrecord = $checkuserrecord->fetch_array(MYSQLI_ASSOC);
                if ($checkuserrecord != "") {
                    $role = ($record['csaManagerID'] != 0) ? '3' : '4'; //role
                    $banned = ($record['allowAccess'] == 0 && $record['status'] == 0) ? "1" : "0"; //banned
                    $ww_users_update = "update ww_users set `role`='" . $role . "',`banned`='" . $banned . "' where `user_name` ='" . $record['email'] . "'";
                    if ($this->cidb->query($ww_users_update)) {
                        $status_msg = $record['email'] . ' >> - customer already exist, updated.';
                    } else {
                        $status_msg = $record['email'] . ' >> ww_users Update >> FAIL >> Error >> ' . $this->cidb->error;
                    }
                } else {
                    $queryu = "SELECT * FROM `ww_users` ORDER BY `ww_users`.`id` DESC limit 1";
                    $lastuser = $this->cidb->query($queryu);
                    $lastuser = $lastuser->fetch_array(MYSQLI_ASSOC);
                    $new_user_id = $lastuser['id'] + 1;

                    $ww_users = [];
                    $ww_users[] = $new_user_id;//id
                    $ww_users[] = $new_contact_id; //contact id
                    $ww_users[] = $record['email']; //user name
                    $ww_users[] = ''; //password
                    $ww_users[] = ($record['csaManagerID'] != 0) ? '3' : '4'; //role
                    $ww_users[] = ($record['allowAccess'] == 0 && $record['status'] == 0) ? "1" : "0"; //banned
                    $ww_users_values = "'" . implode("','", $ww_users) . "'";
                    $ww_users_values = str_replace("___VALUES___", $ww_users_values, $ww_users_insert);
                    if ($this->cidb->query($ww_users_values)) {
                        $status_msg = $record['email'] . ' Added >> Status >> Success';
                    } else {
                        $status_msg = $record['email'] . ' >> ww_users Status >> FAIL >> Error >> ' . $this->cidb->error;
                    }
                }

                if (!empty($record['notes'])) {
                    $user_id = !empty($checkuserrecord) && !empty($checkuserrecord ['id']) ?  $checkuserrecord ['id'] : $new_user_id;

                    $ww_comments_values = array();
                    $ww_comments_values[] = 'contacts';
                    $ww_comments_values[] = $new_contact_id;
                    $ww_comments_values[] = $user_id;
                    $ww_comments_values[] = $this->escape($record['notes']);
                    $ww_comments_values[] = $record['postDateOLD'];
                    $ww_comments_values[] = 1;
                    $ww_comments_values[] = $record['notePrivacy'] == 1 ? 0 : 1;
                    $ww_contacts_values = "'" . implode("','", $ww_comments_values) . "'";
                    $ww_comments = "INSERT INTO `ww_comments` (`comment_for`, `comment_for_id`, `user_id`, `comment`, `date`, `status`, `is_public`) VALUES($ww_contacts_values);";
                    $this->cidb->query($ww_comments_query);
                }
            } else {
                $status_msg = $record['email'] . ' >> ww_contacts Status >> FAIL >> Error >> ' . $this->cidb->error;
            }
            $this->savelog("\n- " . $status_msg);
            $processed++;
            $this->progress("Customers >>", $processed, $total_records);
        }
        $this->_closelog();
    }

    function insert_orders_cf_to_ci()
    {
        $this->openLogFile("orders");
        //first insert new harvests
        $harvest_id = $this->insert_new_harvest();
        if ($harvest_id == '') {
            die("can not insety harvest in CI database:: " . __FILE__ . ' :: ' . __LINE__);
        }

        $query = "SELECT * FROM `dbo.orderIDs` ORDER BY `ID` " . $this->limit;
        //$query = "SELECT * FROM `dbo.orderids` ORDER BY `ID` = 5195";
        $records = $this->cfdb->query($query);
        $records = $records->fetch_all(MYSQLI_ASSOC);
        $total_records = count($records);
        $processed = 0;
        $order_insert_query = "INSERT INTO `ww_orders` (`id`,`order_token`, `harvest_id`, `staff_id`,`contact_id`, `user_id`, `vendor_id`, `status`, `total_charge`, `tax_exempt`, `paid_to_date`, `online_order`, `email`, `cc_last_four`, `cc_last_four_search`, `created_date`, `modified_date`, `customer_ip`,`share_pay_mode`,`coldfusion_order_id`) VALUES(___VALUES___);";
        foreach ($records as $order) {

            $cdata = $this->get_customer_by_id($order['accountID']);
            if ($cdata != "") {
                $ci_cdata = $this->get_ci_customer_by_email($cdata['email']);
                if ($ci_cdata != "") {
                    $contact_id = $ci_cdata['id'];
                    $ci_user_data = $this->get_ci_user_by_contact_id($contact_id);
                    $user_id = ($ci_user_data != "") ? $ci_user_data['id'] : "0";
                } else {
                    $contact_id = "0";//dummy contact id
                    $user_id = "0";//dummy contact id
                }
            } else {
                $contact_id = "0";//dummy contact id
                $user_id = "0";//dummy contact id
            }
            // 22 and 29 vendors not exists in ci table
            if ($order['csaID'] != 22 && $order['csaID'] != 29) {
                $vendordata = $this->get_ci_vendorid_by_name($order['csaID']);
                $vendor_id = isset($vendordata['id']) ? $vendordata['id'] : 0;
            } else {
                $vendor_id = ($order['csaID'] == 22) ? 14 : 0;
            }


            $total_pay = $this->get_cf_order_total_pay_by_order($order["ID"], $order['grandTotal']);
            $order_status = ($total_pay < $order['grandTotal']) ? "pending" : "completed";

            $cfquery = "SELECT * FROM `ww_orders` where `coldfusion_order_id` =" . $order["ID"];
            $checkrecord = $this->cidb->query($cfquery);
            $checkrecord = $checkrecord->fetch_all(MYSQLI_ASSOC);
            $check_count = count($checkrecord);
            if ($check_count > 0) {
                $ww_orders_update = "update ww_orders set `harvest_id`='" . $harvest_id . "',`contact_id`='" . $contact_id . "',`user_id`='" . $user_id . "',`vendor_id`='" . $vendor_id . "',`status`='" . $order_status . "',`total_charge`='" . $order['grandTotal'] . "',`paid_to_date`='" . $total_pay . "',`online_order`='1',`email`='" . $order['billingEmail'] . "',`share_pay_mode`='" . $order['sharePmtType'] . "' where `coldfusion_order_id` ='" . $order["ID"] . "'";

                if ($this->cidb->query($ww_orders_update)) {
                    $status_msg = "Order '" . $order['ID'] . "' >> Success >> Updated";
                } else {
                    $status_msg = "Order Update '" . $order['ID'] . "'  >> FAIL >> Error >> " . $this->cidb->error;
                }
            } else {
                $query = "SELECT * FROM `ww_orders` ORDER BY `ww_orders`.`id` DESC limit 1 ";
                $lastorder = $this->cidb->query($query);
                $lastorder = $lastorder->fetch_array(MYSQLI_ASSOC);
                $new_order_id = $lastorder['id'] + 1;

                $order_details = [];
                $order_details[] = $new_order_id; // order id
                $order_details[] = '0000000000000';//dummy order_token
                $order_details[] = $harvest_id;//harvest id
                $order_details[] = '0';//staff id
                $order_details[] = $contact_id;
                $order_details[] = $user_id;
                $order_details[] = $vendor_id; //vendor_id
                $order_details[] = $order_status; // dummmy status
                $order_details[] = $order['grandTotal']; //total_charge
                $order_details[] = "0"; //tax_exempt
                $order_details[] = $total_pay; //paid_to_date
                $order_details[] = "1"; //online_order
                $order_details[] = $order['billingEmail']; //email
                $order_details[] = ""; //cc_last_four
                $order_details[] = ""; //cc_last_four_search
                $order_details[] = $order['orderDate']; //created_date
                $order_details[] = $order['orderDate']; //modified_date
                $order_details[] = ""; //customer_ip
                $order_details[] = $order['sharePmtType']; //share_pay_mode
                $order_details[] = $order['ID'];//coldfusion_order_id

                $order_details_values = "'" . implode("','", $order_details) . "'";
                $order_details_values = str_replace("___VALUES___", $order_details_values, $order_insert_query);

                if ($this->cidb->query($order_details_values)) {
                    $status_msg = "Order Add >> Cf order id ='" . $order['ID'] . "' >> new order id ='" . $new_order_id . "'>> Success";
                } else {
                    $status_msg = "Order Add >> Cf order id ='" . $order['ID'] . "'  >> FAIL >> Error >> " . $this->cidb->error;
                }
            }
            $this->savelog("\n- " . $status_msg);
            $processed++;
            $this->progress("Order >>", $processed, $total_records);
        }
        $this->_closelog();
    }

    function insert_orderitems_cf_to_ci()
    {
        $this->openLogFile("orderitems");
        $query = "SELECT o.*, oid.sharePartnerID FROM `dbo.orders` o LEFT JOIN `dbo.orderIDs` oid ON oid.ID = o.orderID ORDER BY o.`ID` " . $this->limit;
        $records = $this->cfdb->query($query);
        $records = $records->fetch_all(MYSQLI_ASSOC);
        $total_records = count($records);
        $processed = 0;
        $order_items_insert_query = "INSERT INTO `ww_order_to_products` (`id`,`order_id`, `product_id`, `variation_id`,`product_name`, `product_quantity`, `product_price`, `product_stock`, `share_partner`, `flag`,`coldfusion_order_item_id`) VALUES(___VALUES___);";
        foreach ($records as $order) {

            $ci_pdata = $this->get_ci_product_variation_by_cf_id($order['productID'], $order['productType']);
            $product_variation_id = isset($ci_pdata['id']) ? $ci_pdata['id'] : 0;

            $ci_odata = $this->get_ci_order_by_cf_id($order['orderID']);
            $order_id = isset($ci_odata['id']) ? $ci_odata['id'] : 0;

            $product_id = $this->get_ci_pro_id($order["productID"], $order['productType']);

            $p_contact_id = "0";
            if (!empty($order['sharePartnerID'])) {
                $pdata = $this->get_customer_by_id($order['sharePartnerID']);
                if ($pdata != "") {
                    $ci_pdata = $this->get_ci_customer_by_email($pdata['email']);
                    $p_contact_id = ($ci_pdata != "") ? $ci_pdata['id'] : 0;
                }
            }

            $cfquery = "SELECT * FROM `ww_order_to_products` where `coldfusion_order_item_id` =" . $order["ID"];
            $checkrecord = $this->cidb->query($cfquery);
            $checkrecord = $checkrecord->fetch_all(MYSQLI_ASSOC);
            $check_count = count($checkrecord);
            if ($check_count > 0) {
                $ww_order_to_products_update = "update ww_order_to_products set `order_id`='" . $order_id . "',`product_id`='" . $product_id . "',`variation_id`='" . $product_variation_id . "',`product_name`='" . $this->escape($order['productTitle']) . "',`product_quantity`='" . $order['productQty'] . "',`product_price`='" . $order['productCost'] . "',`product_stock`='" . $order['inventoryCnt'] . "',`share_partner`='" . $p_contact_id . "',`flag`='0' where `coldfusion_order_item_id` ='" . $order["ID"] . "'";
                if ($this->cidb->query($ww_order_to_products_update)) {
                    $status_msg = "Order item '" . $order['ID'] . "' >> Success >> Updated";
                } else {
                    $status_msg = "Order item Update '" . $order['ID'] . "'  >> FAIL >> Error >> " . $this->cidb->error;
                }
            } else {
                $query = "SELECT * FROM `ww_order_to_products` ORDER BY `ww_order_to_products`.`id` DESC limit 1 ";
                $lastorderitem = $this->cidb->query($query);
                $lastorderitem = $lastorderitem->fetch_array(MYSQLI_ASSOC);
                $new_order_item_id = $lastorderitem['id'] + 1;

                $order_item_details = [];
                $order_item_details[] = $new_order_item_id;//id
                $order_item_details[] = $order_id; // order_id
                $order_item_details[] = $product_id;//product_id
                $order_item_details[] = $product_variation_id;//variation_id
                $order_item_details[] = $this->escape($order['productTitle']);//product_name
                $order_item_details[] = $order['productQty'];//product_quantity
                $order_item_details[] = $order['productCost'];//product_price
                $order_item_details[] = $order['inventoryCnt']; //product_stock
                $order_item_details[] = $p_contact_id; // share_partner
                $order_item_details[] = '0'; //flag
                $order_item_details[] = $order['ID']; //coldfusion_product_variation_id

                $order_item_details_values = "'" . implode("','", $order_item_details) . "'";
                $order_item_details_values = str_replace("___VALUES___", $order_item_details_values, $order_items_insert_query);
                if ($this->cidb->query($order_item_details_values)) {
                    $status_msg = "Order item Add >>'" . $order['ID'] . "' >> new order item id ='" . $new_order_item_id . "'>> Success";
                } else {
                    $status_msg = "Order item Add >>'" . $order['ID'] . "' >> FAIL >> Error >> " . $this->cidb->error;
                }
            }
            $this->savelog("\n- " . $status_msg);
            $processed++;
            $this->progress("Order items >>", $processed, $total_records);
        }
        $this->_closelog();
    }

    function insert_orders_payment_cf_to_ci()
    {
        $this->openLogFile("order_payments");
        $query = "SELECT * FROM `dbo.orderPmtSchedule` ORDER BY `ID` " . $this->limit;
        $records = $this->cfdb->query($query);
        $records = $records->fetch_all(MYSQLI_ASSOC);
        $total_records = count($records);
        $processed = 0;
        $order_pay_schedule_insert_query = "INSERT INTO `ww_order_scheduled_payments` (`id`,`order_id`, `pay_dates`, `payment_profile_id`,`status`,`coldfusion_schedule_pay_id`) VALUES(___VALUES___);";
        foreach ($records as $order) {
            $ci_odata = $this->get_ci_order_by_cf_id($order['orderID']);
            $order_id = isset($ci_odata['id']) ? $ci_odata['id'] : 0;

            $cfquery = "SELECT * FROM `ww_order_scheduled_payments` where coldfusion_schedule_pay_id =" . $order["ID"];
            $checkrecord = $this->cidb->query($cfquery);
            $checkrecord = $checkrecord->fetch_all(MYSQLI_ASSOC);
            $check_count = count($checkrecord);
            if ($check_count > 0) {
                $status = ($order['status'] == 1) ? "pending" : "failed";

                $ww_order_scheduled_payments_update = "update ww_order_scheduled_payments set `order_id`='" . $order_id . "',`pay_dates`='" . $order['pmtDueDate'] . "',`payment_profile_id`='0',`status`='" . $status . "' where `coldfusion_schedule_pay_id` ='" . $order["ID"] . "'";

                if ($this->cidb->query($ww_order_scheduled_payments_update)) {
                    $status_msg = "payment schedule '" . $order['ID'] . "' >> Success >> Updated";
                } else {
                    $status_msg = "payment schedule Update '" . $order['ID'] . "'  >> FAIL >> Error >> " . $this->cidb->error;
                }
            } else {
                $query = "SELECT * FROM `ww_order_scheduled_payments` ORDER BY `ww_order_scheduled_payments`.`id` DESC limit 1 ";
                $lastorderpayment = $this->cidb->query($query);
                $lastorderpayment = $lastorderpayment->fetch_array(MYSQLI_ASSOC);
                $new_order_pay_schedule_id = $lastorderpayment['id'] + 1;

                $order_paymnts_details = [];
                $order_paymnts_details[] = $new_order_pay_schedule_id;//id
                $order_paymnts_details[] = $order_id; // order_id
                $order_paymnts_details[] = $order['pmtDueDate'];//pay_dates
                $order_paymnts_details[] = '0';//payment_profile_id
                $order_paymnts_details[] = ($order['status'] == 1) ? "pending" : "failed";//status
                $order_paymnts_details[] = $order['ID']; //coldfusion_schedule_pay_id


                $order_paymnts_details_values = "'" . implode("','", $order_paymnts_details) . "'";
                $order_paymnts_details_values = str_replace("___VALUES___", $order_paymnts_details_values, $order_pay_schedule_insert_query);
                if ($this->cidb->query($order_paymnts_details_values)) {
                    $status_msg = "Order payment schedule Add >> CF id  ='" . $order['ID'] . "' >> new order item id ='" . $new_order_pay_schedule_id . "'>> Success";
                } else {
                    $status_msg = "Order payment schedule Add >> CF id ='" . $order['ID'] . "'  >> FAIL >> Error >> " . $this->cidb->error;
                }
            }
            $this->savelog("\n- " . $status_msg);
            $processed++;
            $this->progress("Order payment schedule >>", $processed, $total_records);
        }
        $this->_closelog();
    }

    function insert_orders_transactions_cf_to_ci()
    {
        $this->openLogFile("order_transactions");
        $status_msg = "";
        $query = "SELECT * FROM `dbo.orderPmts` ORDER BY `ID` " . $this->limit;
        //$query = "SELECT * FROM `dbo.orderpmts` where `ID` =1971" ;

        $records = $this->cfdb->query($query);
        $records = $records->fetch_all(MYSQLI_ASSOC);
        $total_records = count($records);
        $processed = 0;
        $order_transaction_insert_query = "INSERT INTO `ww_transactions` (`id`,`order_id`, `contact_id`,`transaction_id`, `payment_by`,`transaction_amount`,`note`,`payment_type`,`payment_gateway`,`transaction_date`,`created_date`,`modified_date`,`coldfusion_transaction_id`) VALUES(___VALUES___);";
        foreach ($records as $order) {

            $ci_odata = $this->get_ci_order_by_cf_id($order['orderID']);
            $order_id = isset($ci_odata['id']) ? $ci_odata['id'] : 0;

            $cdata = $this->get_customer_by_id($order['accountID']);
            if ($cdata != "") {
                $ci_cdata = $this->get_ci_customer_by_email($cdata['email']);
                $contact_id = ($ci_cdata != "") ? $ci_cdata['id'] : 0;
            } else {
                $contact_id = "0";
            }

            if ($order['pmtType'] == 1) {
                $payment_type = "Credit Card Full Payment Plan";
                $payment_gateway = "authorize_net";
            } elseif ($order['pmtType'] == 2) {
                $payment_type = "Check Full Payment Plan";
                $payment_gateway = "checkfullpay";
            } elseif ($order['pmtType'] == 3) {
                $payment_type = "Credit Card Four Payment Plan";
                $payment_gateway = "authorizenet4pay";
            } elseif ($order['pmtType'] == 4) {
                $payment_type = "Check Four Payment Plan";
                $payment_gateway = "check4pay";
            } else {
                $payment_type = "";
                $payment_gateway = "undefined";
            }

            $cfquery = "SELECT * FROM `ww_transactions` where `coldfusion_transaction_id` =" . $order["ID"];
            $checkrecord = $this->cidb->query($cfquery);
            $checkrecord = $checkrecord->fetch_all(MYSQLI_ASSOC);
            $check_count = count($checkrecord);
            if ($check_count > 0) {
                $ww_transactions_update = "update `ww_transactions` set `order_id`='" . $order_id . "',`contact_id`='" . $contact_id . "',`transaction_id`='" . $order["transactionID"] . "',`payment_by`='" . $contact_id . "',`transaction_amount`='" . str_replace('$', '', $order['amount']) . "',`note`='" . $this->escape($order['pmtNote']) . "',`payment_type`='" . $payment_type . "',`payment_gateway`='" . $payment_gateway . "' where `coldfusion_transaction_id` ='" . $order["ID"] . "'";

                if ($this->cidb->query($ww_transactions_update)) {
                    $status_msg = "Order transaction Update >> CF id = '" . $order['ID'] . "' >> Success .";
                } else {
                    $status_msg = "Order transaction Update >> CF id = '" . $order['ID'] . "'  >> FAIL >> Error >> " . $this->cidb->error;
                }
            } else {

                $query = "SELECT * FROM `ww_transactions` ORDER BY `ww_transactions`.`id` DESC limit 1 ";
                $lasttransaction = $this->cidb->query($query);
                $lasttransaction = $lasttransaction->fetch_array(MYSQLI_ASSOC);
                $new_order_transaction_id = $lasttransaction['id'] + 1;

                $common_date = $order['pmtDate'];
                $order_transaction_details = [];
                $order_transaction_details[] = $new_order_transaction_id;//id
                $order_transaction_details[] = $order_id; // order_id
                $order_transaction_details[] = $contact_id;//contact_id
                $order_transaction_details[] = $order["transactionID"];//transaction_id
                $order_transaction_details[] = $contact_id; // payment_by
                $order_transaction_details[] = str_replace('$', '', $order['amount']);//transaction_amount
                $order_transaction_details[] = $this->escape($order['pmtNote']);//note
                $order_transaction_details[] = $payment_type; // payment_type
                $order_transaction_details[] = $payment_gateway;//payment_gateway
                $order_transaction_details[] = $common_date;//transaction_date
                $order_transaction_details[] = $common_date;//created_date
                $order_transaction_details[] = $common_date; //modified_date
                $order_transaction_details[] = $order['ID']; //modified_date

                $order_transaction_details_values = "'" . implode("','", $order_transaction_details) . "'";
                $order_transaction_details_values = str_replace("___VALUES___", $order_transaction_details_values, $order_transaction_insert_query);
                if ($this->cidb->query($order_transaction_details_values)) {
                    $status_msg = "Order transaction id Add >> CF id ='" . $order['ID'] . "' >> new order transaction id ='" . $new_order_transaction_id . "'>> Success";
                } else {
                    $status_msg = "Order transaction id Add >> CF id ='" . $order['ID'] . "'  >> FAIL >> Error >> " . $this->cidb->error;
                }
            }
            $this->savelog("\n- " . $status_msg);
            $processed++;
            $this->progress("Order transaction >>", $processed, $total_records);
        }
        $this->_closelog();
    }

    function update_parent_products_stocks()
    {
        $this->openLogFile("update_stocks");
        $cfquery = "SELECT * FROM `product_variation_mapping` ORDER BY `id` ASC";
        $map_records = $this->cidb->query($cfquery);
        $map_records = $map_records->fetch_all(MYSQLI_ASSOC);
        foreach ($map_records as $data) {
            if (strpos($data['ci_product_id'], "manually_crated") !== false) {
                $ci_product_data = explode("=", $data['ci_product_id']);
                $ci_product_id = $ci_product_data[1];
            } else {
                $ci_product_id = $data['ci_product_id'];
            }
            $ci_prd_ids[] = $ci_product_id;
        }

        $ci_prd_ids = array_unique($ci_prd_ids);
        $total_records = count($ci_prd_ids);
        $processed = 0;
        foreach ($ci_prd_ids as $pid) {
            $ini_stock = 0;
            $ini_items = 0;
            $available_stock = 0;
            $available_items = 0;
            $query = "SELECT * FROM `ww_product_variations` where `product_id` ='" . $pid . "'";
            $precords = $this->cidb->query($query);
            $precords = $precords->fetch_all(MYSQLI_ASSOC);
            foreach ($precords as $product) {

                $ini_stock += $product['initial_stock'];
                $ini_items += $product['initial_items'];
                $available_stock += $product['available_stock'];
                $available_items += $product['available_items'];

            }
            $ww_product_update = "update ww_products set `initial_stock`='" . $ini_stock . "',`initial_items`='" . $ini_items . "',`available_stock`='" . $available_stock . "',`available_items`='" . $available_items . "' where `id` ='" . $pid . "'";

            if ($this->cidb->query($ww_product_update)) {
                $status_msg = "CI Product'" . $pid . "' stock updated  >> Success";
            } else {
                $status_msg = "CI Product'" . $pid . "' stock update  >> FAIL >> Error >> " . $this->cidb->error;
            }
            $this->savelog("\n- " . $status_msg);
            $processed++;
            $this->progress("Update parent product stocks >>", $processed, $total_records);

        }
        $this->_closelog();
    }

    function manual_product_creation()
    {
        $queryp = "SELECT * FROM `ww_products` ORDER BY `ww_products`.`id` DESC limit 1 ";
        $lastproduct = $this->cidb->query($queryp);
        $lastproduct = $lastproduct->fetch_array(MYSQLI_ASSOC);
        $new_product_id = $lastproduct['id'] + 1;
        $ww_product_1 = "INSERT INTO `ww_products` (`id`, `vendor_id`, `master_id`, `category_id`, `name`, `description`, `product_image`, `default_price`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `allowed_product_variations`, `allow_product_partners`, `num_sales_allow_comp`, `mandatory_product`, `suggested_product`, `display_order`, `is_featured`, `active`, `visible`, `product_type`, `is_stock_csa_level`, `is_unlimited_stock`, `display_all_vendors`, `harvest_id`, `start_delivery`, `end_delivery`, `product_image_type`, `archive`, `model`, `coldfusion_id`) VALUES ('" . $new_product_id . "', '0', '0', '21', 'Hudson Valley Good Ol  Apple', '<p>GOOD OL&#39; APPLE</p><p>375 ml bottle</p><p>Unaged. Unprocessed. Unadulterated.</p><p>Pure apple cider vinegar, with no frills or bells or whistles. Just the good stuff.</p><p>Delightful for cooking, saut&eacute;ing, marinading- even drinking straight every morning.</p><p><br></p>\r\n', 'https://www.stoneledge.farm/img/marketplace/Good-Ol-Apple-20523102048616.png', '10.00', '0.00', '0', '26.00', '0', '0.00', '0', '', '0', '0', '0', '0', '0', '0', '1', '1', 'product', '0', '0', '0', '0', '0', '0', 'browse', '0', 'Hudson Valley Good Ol  Apple', NULL)";
        $this->cidb->query($ww_product_1);
        $product_id_14_122 = "manually_crated=" . $new_product_id;
        $ww_product_1_update = "update product_variation_mapping set `ci_product_id`='" . $product_id_14_122 . "' where `coldfusion_product_id` =14 && `coldfusion_product_variation_id` =122";
        $this->cidb->query($ww_product_1_update);

        $new_product_id2 = $new_product_id + 1;
        $ww_product_2 = "
INSERT INTO `ww_products` (`id`, `vendor_id`, `master_id`, `category_id`, `name`, `description`, `product_image`, `default_price`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `allowed_product_variations`, `allow_product_partners`, `num_sales_allow_comp`, `mandatory_product`, `suggested_product`, `display_order`, `is_featured`, `active`, `visible`, `product_type`, `is_stock_csa_level`, `is_unlimited_stock`, `display_all_vendors`, `harvest_id`, `start_delivery`, `end_delivery`, `product_image_type`, `archive`, `model`, `coldfusion_id`) VALUES ('" . $new_product_id2 . "', '0', '0', '21', 'Hany s Harvest Wilder Dandelion Burdock Vinegar', '<p>&quot;Our Wilder Earth Cider adds the amazing burdock and dandelion roots, which have been used in traditional teas for centuries, to raw apple cider vinegar infused with ginger, horseradish and turmeric roots. The taste is earthy and pleasantly bitter. &quot; INGREDIENTS:Organic Raw Unfiltered Apple Cider Vinegar, Raw Honey, Organic Lemon juice, Organic Ginger root, Horseradish root, Organic Turmeric root, Dandelion root, Burdock root &amp; Black Pepper. 8oz bottle</p>
', 'https://www.stoneledge.farm/img/marketplace/Dandelion-Burdock-20747946243.jpg', '12.00', '0.00', '0', '12.00', '0', '0.00', '0', '', '0', '0', '0', '0', '0', '0', '1', '1', 'product', '0', '0', '0', '0', '0', '0', 'browse', '0', 'Hany s Harvest Wilder Dandelion Burdock Vinegar', NULL)";
        $this->cidb->query($ww_product_2);
        $product_id_14_140 = "manually_crated=" . $new_product_id2;
        $ww_product_2_update = "update product_variation_mapping set `ci_product_id`='" . $product_id_14_140 . "' where `coldfusion_product_id` =14 && `coldfusion_product_variation_id` =140";
        $this->cidb->query($ww_product_2_update);

        $new_product_id3 = $new_product_id2 + 1;
        $ww_product_3 = "INSERT INTO `ww_products` (`id`, `vendor_id`, `master_id`, `category_id`, `name`, `description`, `product_image`, `default_price`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `allowed_product_variations`, `allow_product_partners`, `num_sales_allow_comp`, `mandatory_product`, `suggested_product`, `display_order`, `is_featured`, `active`, `visible`, `product_type`, `is_stock_csa_level`, `is_unlimited_stock`, `display_all_vendors`, `harvest_id`, `start_delivery`, `end_delivery`, `product_image_type`, `archive`, `model`, `coldfusion_id`) VALUES ('" . $new_product_id3 . "', '0', '0', '21', 'Hany s Harvest Beetroot Cinnamon Beet Vinegar', '<p>&quot;Ceylon Cinnamon and whole, organic beets complete our signature composition of turmeric, ginger and horseradish for an assertively warming bounty of goodness.&quot;</p><p>INGREDIENTS:Organic Raw unfiltered Apple Cider Vinegar, Organic Beets, Organic Lemon juice, Organic Ginger root, Horseradish root, Organic Turmeric root, Ceylon Cinnamon &amp; Black Pepper.</p><p>8oz. bottle</p>', 'https://www.stoneledge.farm/img/marketplace/BeetRoot-Vinegar-207471314910.jpg', '12.00', '0.00', '0', '12.00', '0', '0.00', '0', '', '0', '0', '0', '0', '0', '0', '1', '1', 'product', '0', '0', '0', '0', '0', '0', 'browse', '0', 'Hany s Harvest Beetroot Cinnamon Beet Vinegar', NULL)";
        $this->cidb->query($ww_product_3);
        $product_id_14_141 = "manually_crated=" . $new_product_id3;
        $ww_product_3_update = "update product_variation_mapping set `ci_product_id`='" . $product_id_14_141 . "' where `coldfusion_product_id` =14 && `coldfusion_product_variation_id` =141";
        $this->cidb->query($ww_product_3_update);

        $ci_p_id_14 = "752-756-755-753-754-" . $new_product_id . "-" . $new_product_id2 . "-" . $new_product_id3;
        $update_14 = "update cf_ci_product_mapping set `ci_product_id`='" . $ci_p_id_14 . "' where `coldfusion_product_id` =14";
        $this->cidb->query($update_14);


        $new_product_id4 = $new_product_id3 + 1;
        $ww_product_4 = "INSERT INTO `ww_products` (`id`, `vendor_id`, `master_id`, `category_id`, `name`, `description`, `product_image`, `default_price`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `allowed_product_variations`, `allow_product_partners`, `num_sales_allow_comp`, `mandatory_product`, `suggested_product`, `display_order`, `is_featured`, `active`, `visible`, `product_type`, `is_stock_csa_level`, `is_unlimited_stock`, `display_all_vendors`, `harvest_id`, `start_delivery`, `end_delivery`, `product_image_type`, `archive`, `model`, `coldfusion_id`) VALUES ('" . $new_product_id4 . "', '0', '0', '1', 'Dry Bean Soup Mix', '<p>24 oz. (1.5 pound) bag, Certified Organic Mix of NYS grown Dry Beans.</p><p>The Dry Beans are an Agricultural commodity, please make sure to pick through the beans, discarding any discolored or shriveled beans or any foreign matter.</p>', 'https://www.stoneledge.farm/img/marketplace/Soup-Mix-191024101741987.jpeg', '7.65', '0.00', '0', '20.00', '0', '0.00', '0', '', '0', '0', '0', '0', '0', '0', '1', '1', 'product', '0', '0', '0', '0', '0', '0', 'browse', '0', 'Dry Bean Soup Mix', NULL)";
        $this->cidb->query($ww_product_4);
        $product_id_17_71 = "manually_crated=" . $new_product_id4;
        $ww_product_4_update = "update product_variation_mapping set `ci_product_id`='" . $product_id_17_71 . "' where `coldfusion_product_id` =17 && `coldfusion_product_variation_id` =71";
        $this->cidb->query($ww_product_4_update);

        $new_product_id5 = $new_product_id4 + 1;
        $ww_product_5 = "INSERT INTO `ww_products` (`id`, `vendor_id`, `master_id`, `category_id`, `name`, `description`, `product_image`, `default_price`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `allowed_product_variations`, `allow_product_partners`, `num_sales_allow_comp`, `mandatory_product`, `suggested_product`, `display_order`, `is_featured`, `active`, `visible`, `product_type`, `is_stock_csa_level`, `is_unlimited_stock`, `display_all_vendors`, `harvest_id`, `start_delivery`, `end_delivery`, `product_image_type`, `archive`, `model`, `coldfusion_id`) VALUES ('" . $new_product_id5 . "', '0', '0', '1', 'Cranberry Beans', '<p>24 oz. (1.5 pound) bag, Certified Organic Cranberry Beans. NYS grown Dry Beans.</p><p>The Dry Beans are an Agricultural commodity, please make sure to pick through the beans, discarding any discolored or shriveled beans or any foreign matter.</p>', 'https://www.stoneledge.farm/img/marketplace/Cranberry-Beans-20512152088.jpg', '7.65', '0.00', '0', '17.00', '0', '0.00', '0', '', '0', '0', '0', '0', '0', '0', '1', '1', 'product', '0', '0', '0', '0', '0', '0', 'browse', '0', 'Cranberry Beans', NULL)";
        $this->cidb->query($ww_product_5);
        $product_id_17_101 = "manually_crated=" . $new_product_id5;
        $ww_product_5_update = "update product_variation_mapping set `ci_product_id`='" . $product_id_17_101 . "' where `coldfusion_product_id` =17 && `coldfusion_product_variation_id` =101";
        $this->cidb->query($ww_product_5_update);

        $ci_p_id_17 = "570-569-803-159-571-" . $new_product_id4 . "-" . $new_product_id5;
        $update_17 = "update cf_ci_product_mapping set `ci_product_id`='" . $ci_p_id_17 . "' where `coldfusion_product_id` =17";
        $this->cidb->query($update_17);

        $new_product_id6 = $new_product_id5 + 1;
        $ww_product_6 = "INSERT INTO `ww_products` (`id`, `vendor_id`, `master_id`, `category_id`, `name`, `description`, `product_image`, `default_price`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `allowed_product_variations`, `allow_product_partners`, `num_sales_allow_comp`, `mandatory_product`, `suggested_product`, `display_order`, `is_featured`, `active`, `visible`, `product_type`, `is_stock_csa_level`, `is_unlimited_stock`, `display_all_vendors`, `harvest_id`, `start_delivery`, `end_delivery`, `product_image_type`, `archive`, `model`, `coldfusion_id`) VALUES ('" . $new_product_id6 . "', '0', '0', '20', 'Merino Yarn', '<p>Pure Merino Wool Yarn from our Stoneledge Farm flock. &nbsp;Locally spun.</p><p>100% Stoneledge Farm Merino wool from our flock. &nbsp;locally spun.Sport Weight Knitting Yarn &amp; Wool. Sport weight yarn, sits between fingering weight and DK yarn. It creates a beautiful lightweight fabric that is ideal for garments with colorwork, and adorable babywear, cozy socks, sweaters with great ease and drape and warm shawls and scarves. Sport weight yarns are recommended for projects with a knitting gauge of 5-6 st</p>', 'https://www.stoneledge.farm/img/marketplace/White-merino-yarn-1992410128505.jpg', '18.95', '0.00', '0', '15.00', '0', '0.00', '0', '', '0', '0', '0', '0', '0', '0', '1', '1', 'product', '0', '0', '0', '0', '0', '0', 'browse', '0', 'Merino Yarn', NULL)";
        $this->cidb->query($ww_product_6);
        $product_id_11_24 = "manually_crated=" . $new_product_id6;
        $ww_product_6_update = "update product_variation_mapping set `ci_product_id`='" . $product_id_11_24 . "' where `coldfusion_product_id` =11 && `coldfusion_product_variation_id` =24";
        $this->cidb->query($ww_product_6_update);

        $new_product_id7 = $new_product_id6 + 1;
        $ww_product_7 = "INSERT INTO `ww_products` (`id`, `vendor_id`, `master_id`, `category_id`, `name`, `description`, `product_image`, `default_price`, `initial_stock`, `initial_items`, `available_stock`, `available_items`, `stock_sold`, `items_sold`, `allowed_product_variations`, `allow_product_partners`, `num_sales_allow_comp`, `mandatory_product`, `suggested_product`, `display_order`, `is_featured`, `active`, `visible`, `product_type`, `is_stock_csa_level`, `is_unlimited_stock`, `display_all_vendors`, `harvest_id`, `start_delivery`, `end_delivery`, `product_image_type`, `archive`, `model`, `coldfusion_id`) VALUES ('" . $new_product_id7 . "', '0', '0', '20', 'Smoky Brown Merino Wool Yarn', '<p>100% Stoneledge Farm wool from our own flock, locally spun. Sport Weight Knitting Yarn &amp; Wool. Sport weight yarn, sits between fingering weight and DK yarn. It creates a beautiful lightweight fabric that is ideal for garments with colorwork, and adorable babywear, cozy socks, sweaters with great ease and drape and warm shawls and scarves. Sport weight yarns are recommended for projects with a knitting gauge of 5-6 stitches per inch.Yarn Type: &nbsp;Sport WeightYardage: &nbsp;260 yards/skein,</p>', 'https://www.stoneledge.farm/img/marketplace/Natural-colored-yarn-skein-1992410227161.jpg', '18.95', '0.00', '0', '10.00', '0', '0.00', '0', '', '0', '0', '0', '0', '0', '0', '1', '1', 'product', '0', '0', '0', '0', '0', '0', 'browse', '0', 'Smoky Brown Merino Wool Yarn', NULL)";
        $this->cidb->query($ww_product_7);
        $product_id_11_25 = "manually_crated=" . $new_product_id7;
        $ww_product_5_update = "update product_variation_mapping set `ci_product_id`='" . $product_id_11_25 . "' where `coldfusion_product_id` =11 && `coldfusion_product_variation_id` =25";
        $this->cidb->query($ww_product_5_update);

        $ci_p_id_11 = $new_product_id6 . "-" . $new_product_id7;
        $update_11 = "update cf_ci_product_mapping set `ci_product_id`='" . $ci_p_id_11 . "' where `coldfusion_product_id` =11";
        $this->cidb->query($update_11);
        echo "completed";
    }

    function get_customer_by_id($acc_id)
    {
        $query = "SELECT * FROM `dbo.accounts` where `ID` = " . $acc_id;
        $record = $this->cfdb->query($query);
        $results = $record->fetch_array(MYSQLI_ASSOC);
        return $results;
    }

    function get_ci_customer_by_email($email)
    {
        $query = "SELECT * FROM `ww_contacts` where `email` = '" . $email . "'";
        $record = $this->cidb->query($query);
        $results = $record->fetch_array(MYSQLI_ASSOC);
        return $results;
    }

    function get_ci_user_by_contact_id($contact_id)
    {
        $query = "SELECT * FROM `ww_users` where `contact_id` = " . $contact_id;
        $record = $this->cidb->query($query);
        $results = $record->fetch_array(MYSQLI_ASSOC);
        return $results;
    }

    function get_cf_parent_product_id($pro_id, $pro_type)
    {
        if ($pro_type == "Marketplace" || $pro_type == 'product') {
            $query = "SELECT * FROM `ww_products` where `product_type` = 'product'  && `coldfusion_id` = " . $pro_id;
            $record = $this->cidb->query($query);
            $results = $record->fetch_array(MYSQLI_ASSOC);
        } else {
            $query = "SELECT * FROM `ww_products` where `product_type` = 'share'  && `coldfusion_id` = " . $pro_id;
            $record = $this->cidb->query($query);
            $results = $record->fetch_array(MYSQLI_ASSOC);
        }

        return $results;
    }

    function get_ci_vendorid_by_name($cf_csa_id)
    {
        $query = "SELECT * FROM `dbo.CSAs` where `ID` = '" . $cf_csa_id . "'";
        $record = $this->cfdb->query($query);
        $results = $record->fetch_array(MYSQLI_ASSOC);
        if ($results != "") {
            $query2 = "SELECT * FROM `ww_vendors` where `name` = '" . $results['title'] . "'";
            $record2 = $this->cidb->query($query2);
            $results = $record2->fetch_array(MYSQLI_ASSOC);
        }

        return $results;
    }

    function get_ci_product_variation_by_cf_id($cf_pro_id, $pro_type)
    {
        if ($pro_type == "Marketplace" || $pro_type == 'product') {
            $query = "SELECT * FROM `ww_product_variations` where `coldfusion_product_id` = " . $cf_pro_id;
            $record = $this->cidb->query($query);
            $results = $record->fetch_array(MYSQLI_ASSOC);
        } else {
            $query = "SELECT * FROM `ww_product_variations` where `coldfusion_share_id` = " . $cf_pro_id;
            $record = $this->cidb->query($query);
            $results = $record->fetch_array(MYSQLI_ASSOC);
        }
        return $results;
    }

    function get_ci_order_by_cf_id($cf_orderid)
    {
        $query = "SELECT * FROM `ww_orders` where `coldfusion_order_id` = " . $cf_orderid;
        $record = $this->cidb->query($query);
        $results = $record->fetch_array(MYSQLI_ASSOC);
        return $results;
    }

    function get_ci_pro_id($id, $pro_type)
    {
        if ($pro_type == 'Marketplace') {
            $query = "SELECT * FROM `ww_product_variations` where `coldfusion_product_id` = " . $id;
            $record = $this->cidb->query($query);
            $results = $record->fetch_array(MYSQLI_ASSOC);
            $pid = isset($results['product_id']) ? $results['product_id'] : "0";
        } else {
            $query = "SELECT * FROM `ww_product_variations` where `coldfusion_share_id` = " . $id;
            $record = $this->cidb->query($query);
            $results = $record->fetch_array(MYSQLI_ASSOC);
            $pid = isset($results['product_id']) ? $results['product_id'] : "0";
        }

        return $pid;
    }

    function get_cf_partner_details($c_id)
    {
        $query = "SELECT * FROM `dbo.sharePartners` where `memberID` = " . $c_id;
        $record = $this->cfdb->query($query);
        $results = $record->fetch_array(MYSQLI_ASSOC);
        $partnerdata = array();
        if ($results != "") {
            $partnerID = $results['partnerID'];
            $partnerdetails = $this->get_customer_by_id($partnerID);

            if ($partnerdetails != "") {
                if (isset($partnerdetails['firstName']) && isset($partnerdetails['lastName'])) {
                    $partner_name = $this->escape($partnerdetails['firstName']) . " " . $this->escape($partnerdetails['lastName']);
                } else if (isset($partnerdetails['firstName'])) {
                    $partner_name = $this->escape($partnerdetails['firstName']);
                } else {
                    $partner_name = "";
                }

                $partner_email = isset($partnerdetails['email']) ? $partnerdetails['email'] : "";
                $partner_phone = isset($partnerdetails['phone']) ? $partnerdetails['phone'] : "";
                $partnerdata['name'] = $partner_name;
                $partnerdata['email'] = $partner_email;
                $partnerdata['phone'] = $partner_phone;
            }
        } else {
            $query = "SELECT * FROM `dbo.sharePartners` where `partnerID` = '" . $c_id . "'";
            $record = $this->cfdb->query($query);
            $results = $record->fetch_array(MYSQLI_ASSOC);
            if ($results != "") {
                $partnerID = $results['memberID'];
                $partnerdetails = $this->get_customer_by_id($partnerID);
                if ($partnerdetails != "") {
                    if (isset($partnerdetails['firstName']) && isset($partnerdetails['lastName'])) {
                        $partner_name = $this->escape($partnerdetails['firstName']) . " " . $this->escape($partnerdetails['lastName']);
                    } else if (isset($partnerdetails['firstName'])) {
                        $partner_name = $this->escape($partnerdetails['firstName']);
                    } else {
                        $partner_name = "";
                    }

                    $partner_email = isset($partnerdetails['email']) ? $partnerdetails['email'] : "";
                    $partner_phone = isset($partnerdetails['phone']) ? $partnerdetails['phone'] : "";
                    $partnerdata['name'] = $partner_name;
                    $partnerdata['email'] = $partner_email;
                    $partnerdata['phone'] = $partner_phone;
                }
            }
        }
        return $partnerdata;
    }

    function get_cf_product_description($id)
    {
        $query = "SELECT * FROM `dbo.marketplace` where `ID` = " . $id;
        $record = $this->cfdb->query($query);
        $results = $record->fetch_array(MYSQLI_ASSOC);
        return $results;
    }

    function get_cf_order_total_pay_by_order($order_id, $order_amount)
    {
        $query = "SELECT * FROM `dbo.orderPmts` where `orderID` = " . $order_id;
        $record = $this->cfdb->query($query);
        $results = $record->fetch_all(MYSQLI_ASSOC);
        $total_pay = 0;

        foreach ($results as $result) {
            $total_pay = (float)$total_pay + (float)$result['amount'];
        }
        return $total_pay;
    }
}
