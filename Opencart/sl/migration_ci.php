<?php

ini_set('memory_limit', '-1');

class Migration
{

    private $DB_HOST = 'localhost';
    private $DB_NAME = 'ci_stone_new';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_PREFIX = 'ww_';
    private $source_domain = 'https://stoneledge.wwdevserver.info';
    private $dest_domain = 'https://stoneledge.opencartdev.work';

    public function __construct()
    {
        $this->conn = mysqli_connect($this->DB_HOST, $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_NAME) or die('failed to connect');
    }

    public function setExports($exports)
    {
        $this->exports = $exports;
    }

    public function start()
    {
        if (empty($this->exports)) {
            die('Please set export options to start migration.');
        }
        foreach ($this->exports as $export) {
            if (method_exists($this, $export)) {
                $this->$export();
                print "\n----------------------------------\n";
            } else {
                throw new Exception("${export} option is not valid.");
            }
        }
    }

    protected function getCustomerGroup($customer_data)
    {
        $customer_groups = [
            0 => 1, // 0 to default customer group
            1 => 2,
            2 => 30,
            3 => 6,
            6 => 4,
            7 => 24,
            9 => 22,
            10 => 31,
            11 => 29,
            12 => 5,
            13 => 27,
            14 => 7,
            15 => 3,
            16 => 8,
            17 => 21,
            19 => 19,
            20 => 25,
            21 => 26,
            24 => 12,
            25 => 1, // test vendor customer group to 1
            26 => 11,
            27 => 20,
            28 => 23,
            29 => 28,
            30 => 9,
            31 => 32,
            32 => 15,
            33 => 10,
            34 => 14,
            35 => 17,
            36 => 16,
            37 => 33,
            38 => 13,
            39 => 18
        ];

        return $customer_groups[$customer_data['vendor_id']] ?? 1;
    }

    protected function getHarvest($row)
    {
        $harvest = [
            8 => 1,
            9 => 2,
            10 => 3,
            11 => 4,
            12 => 5,
            13 => 6,
            14 => 7,
            15 => 8,
            16 => 9,
        ];

        return $harvest[$row['harvest_id']] ?? 1;
    }

    protected function getCSA($row)
    {
        $csas = [
            1 => 1,
            2 => 31,
            3 => 5,
            6 => 3,
            7 => 24,
            9 => 22,
            10 => 32,
            11 => 30,
            12 => 4,
            13 => 28,
            14 => 6,
            15 => 2,
            16 => 7,
            17 => 21,
            19 => 19,
            20 => 26,
            21 => 27,
            24 => 11,
            25 => 0,
            26 => 10,
            27 => 20,
            28 => 23,
            29 => 29,
            30 => 8,
            31 => 33,
            32 => 15,
            33 => 9,
            34 => 14,
            35 => 17,
            36 => 16,
            37 => 34,
            38 => 12,
            39 => 18,
        ];
        return $csas[$row['vendor_id']] ?? 0;
    }

    protected function getZone($customer_data, $name = false)
    {
        $state = strtolower($customer_data['state']);
        if ($state == 'sel' && $customer_data['city'] == 'new york') {
            $state = 'ny';
        } elseif ($state == 'sel') {
            $state = 'ct';
        }
        if ($state == 'tan' || $state == 'nu' || $state == 'new' || $state == 'n.y' || $state == 'sta' || $state == 'my' || $state == 'nyc' || $state == 'man' || $state == 'bro' || $state == 'mas' || $state == 'ple' || $state == 'yew') {
            $state = 'ny';
        }
        if ($state == 'con' || $state == 'ct,' || $state == 'ct.' || $state == 'cr') {
            $state = 'ct';
        }
        if ($state == 'pen') {
            $state = 'pa';
        }

        $zone_ids = [
            'al' => 3613,
            'ak' => 3614,
            'as' => 3615,
            'az' => 3616,
            'ar' => 3617,
            'aa' => 3619,
            'ae' => 3621,
            'ap' => 3623,
            'ca' => 3624,
            'co' => 3625,
            'ct' => 3626,
            'de' => 3627,
            'dc' => 3628,
            'fm' => 3629,
            'fl' => 3630,
            'ga' => 3631,
            'gu' => 3632,
            'hi' => 3633,
            'id' => 3634,
            'il' => 3635,
            'in' => 3636,
            'ia' => 3637,
            'ks' => 3638,
            'ky' => 3639,
            'la' => 3640,
            'me' => 3641,
            'mh' => 3642,
            'md' => 3643,
            'ma' => 3644,
            'mi' => 3645,
            'mn' => 3646,
            'ms' => 3647,
            'mo' => 3648,
            'mt' => 3649,
            'ne' => 3650,
            'nv' => 3651,
            'nh' => 3652,
            'nj' => 3653,
            'nm' => 3654,
            'ny' => 3655,
            'nc' => 3656,
            'nd' => 3657,
            'mp' => 3658,
            'oh' => 3659,
            'ok' => 3660,
            'or' => 3661,
            'pw' => 3662,
            'pa' => 3663,
            'pr' => 3664,
            'ri' => 3665,
            'sc' => 3666,
            'sd' => 3667,
            'tn' => 3668,
            'tx' => 3669,
            'ut' => 3670,
            'vt' => 3671,
            'vi' => 3672,
            'va' => 3673,
            'wa' => 3674,
            'wv' => 3675,
            'wi' => 3676,
            'wy' => 3677
        ];

        if ($name) {
            isset($zone_ids[$state]) ? strtoupper($state) : '';
        }
        return isset($zone_ids[$state]) ? $zone_ids[$state] : '';
    }

    protected function getOrderStatus($row)
    {
        $order_status = [
            'completed' => 5,
            'deleted' => 14,
            'cancel' => 7,
            'refund' => 11,
            'pending_payment' => 1,
            'incomplete' => 2,
            'pending_balance' => 1,
        ];
        return $order_status[$row['status']] ?? 1;
    }

    public function getHarvestYear($harvest_id) 
    {
        $q = $this->query("SELECT DATE_FORMAT(date_to, '%Y') as date FROM {$this->DB_PREFIX}harvests WHERE id = {$harvest_id}");
        if ($this->affectedRows() > 0) {
            return $this->fetch($q)['date'];
        }
        return 0;
    }

    protected function query($query)
    {
        return mysqli_query($this->conn, $query);
    }

    protected function affectedRows()
    {
        return mysqli_affected_rows($this->conn);
    }

    protected function fetch($query)
    {
        return mysqli_fetch_array($query);
    }

  /*   public function getPartnerByEmail($partner_email)
    {
        if ($partner_email) {
            $partner_q = $this->query("SELECT * FROM {$this->DB_PREFIX}contacts WHERE email LIKE '%{$partner_email}%'");
            $partner_data = $this->fetch($partner_q);
            $partner_id = $partner_data['id'];
        }
        return $partner_id ?? 0;
    } */

    public function customers()
    {
        // SELECT * from ww_users AS u INNER JOIN ww_contacts AS c on u.contact_id = c.id
        $query = $this->query("SELECT * from {$this->DB_PREFIX}users AS u INNER JOIN {$this->DB_PREFIX}contacts AS c on u.contact_id = c.id");
        // customer csv headers
        $customer_handle = fopen('customers.csv', 'w') or die('failed to open a file') or die('Failed to open file.');
        $customer_headers = ['Customer ID', 'Group id', 'Store id', 'Address id', 'Language id', 'First name', 'Last name', 'Email', 'Telephone', 'Fax', 'Custom field', 'Password', 'Salt', 'Newsletter', 'Safe', 'Cart', 'Wish list', 'IP', 'Token', 'Code', 'Status', 'Date added', 'CSA id', 'Manager CSA id'];
        fputcsv($customer_handle, $customer_headers, ',');

        $address_handle = fopen('addresses.csv', 'w') or die('failed to open a file') or die('Failed to open file.');
        $address_headers = ['Address id', 'Customer id', 'First name', 'Last name', 'Company', 'Address 1', 'Address 2', 'Postcode', 'City', 'Zone id', 'Country id'];
        fputcsv($address_handle, $address_headers, ',');

        if ($this->affectedRows() > 0) {
            print "Customer export started.\n";
            $count = 0;
            while ($row = $this->fetch($query)) {
                $count++;
                $row['address_id'] = $count;

                $address_insert_data = [
                    $row['address_id'],
                    $row['contact_id'],
                    $row['first_name'],
                    $row['last_name'],
                    '', // company
                    $row['streetname1'], // address 1
                    $row['streetname2'], // address 2
                    $row['zip'],
                    $row['city'],
                    $this->getZone($row),
                    223, // united states
                ];

                fputcsv($address_handle, $address_insert_data, ',');

                $status = ($row['contact_status'] == 'active' || $row['contact_status'] == '	
                waiting_list') ? 1 : 0;

                $customer_insert_data = [
                    $row['contact_id'],
                    $this->getCustomerGroup($row),
                    $row['store_id'] ?? 0,
                    $row['address_id'],
                    1, // language_id
                    $row['first_name'],
                    $row['last_name'],
                    $row['user_name'],
                    $row['phone'],
                    '', // fax
                    '', // custom_field
                    '', // Password - don't migrate
                    '', // salt
                    $row['recieve_newsletter'],
                    0, // safe
                    '', // cart
                    '', // wish_list
                    '', // ip
                    '', // token
                    '', // code
                    $status,
                    $row['created_date'],
                    $this->getCSA($row),
                    ($row['manages_vendor_id'] != 0) ? $this->getCSA($row) : 0,
                ];
                fputcsv($customer_handle, $customer_insert_data);
                print "${count} customer(s) exported\n";
            }
            print "Customer export ended.\n";
        }

        fclose($address_handle);
        fclose($customer_handle);
    }

    public function partners()
    {
        $partners_handle = fopen('partners.csv', 'w') or die('Failed to open file.');
        // $partner_headers = ['id', 'customer_id', 'partner_id', 'status'];
        // fputcsv($partners_handle, $partner_headers);

        $query = $this->query("SELECT * FROM {$this->DB_PREFIX}contact_to_friends");
        if ($this->affectedRows() > 0) {
            print "Partners export started.\n";
            $count = 0;
            while ($row = $this->fetch($query)) {
            
                $partner_insert_data = [
                    $row['id'],
                    $row['contact_id'],
                    $row['friend_id'],
                    $row['status']
                ];

                fputcsv($partners_handle, $partner_insert_data);
                $count++;
                print "${count} partner(s) exported\n";
            }
            print "partners export ended.\n";
        }
        fclose($partners_handle);
    }

    public function categories()
    {
        $category_handle = fopen('categories.csv', 'w') or die('Failed to open file.');
        $category_headers = ['Category id', 'Parent id', 'Name', 'Description', 'Meta title', 'Meta description', 'Meta keywords', 'SEO url', 'Image', 'Top', 'Columns', 'Sort order', 'Status', 'Stores', 'Filters', 'Layout'];
        fputcsv($category_handle, $category_headers);
        $query = $this->query("SELECT * FROM {$this->DB_PREFIX}categories");
        if ($this->affectedRows() > 0) {
            print "Category export started.\n";
            $count = 0;
            while ($row = $this->fetch($query)) {
                $count++;
                $category_insert_data = [
                    $row['id'],
                    $row['parent_id'],
                    $row['name'],
                    $row['description'],
                    $row['name'], // meta title
                    $row['description'], // meta description
                    $row['keywords'] ?? '',
                    '', // seo url
                    ($row['category_image'] != '') ?  (strpos($row['category_image'], 'http://') === FALSE && strpos($row['category_image'], 'https://') === FALSE ? $this->source_domain . $row['category_image'] : $row['category_image']) : '',
                    0, // top
                    1, // columns
                    $row['display_order'],
                    $row['visible'],
                    0, // stores
                    '', // filters
                    '', // layout
                ];
                fputcsv($category_handle, $category_insert_data);
                print "${count} categories exported\n";
            }
            print "Category export ended.\n";
        }
        fclose($category_handle);
    }

    public function options()
    {
        $options_handle = fopen('options.csv', 'w') or die('Failed to open file.');
        $option_headers = ['Option id', 'Name', 'Option type', 'Sort order'];
        fputcsv($options_handle, $option_headers);

        $this->query("UPDATE {$this->DB_PREFIX}product_variations set name=trim(name)");
        $this->query("UPDATE {$this->DB_PREFIX}product_variations set name=trim(TRAILING '\n' FROM name)");
        $this->query("UPDATE {$this->DB_PREFIX}product_variations set name=trim(TRAILING '\r' FROM name)");

        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}product_variations LIKE 'option_id'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}product_variations ADD option_id INT NOT NULL AFTER product_image_type");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}product_variations SET option_id=0");
        }
        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}product_variations LIKE 'used_option'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}prsoduct_variations ADD used_option INT NOT NULL DEFAULT 0 AFTER product_image_type");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}product_variations SET used_option=0");
        }

        $query = $this->query("SELECT * FROM {$this->DB_PREFIX}products WHERE master_id = 0 AND vendor_id = 0 ORDER BY id DESC");
        if ($this->affectedRows() > 0) {
            print "Options export started.\n";
            $count = 0;
            $already_migrated = array();
            while ($row = $this->fetch($query)) {
                $q = $this->query("SELECT * FROM {$this->DB_PREFIX}product_variations WHERE product_id = '{$row['id']}' AND vendor_id = 0 and master_id = 0 ORDER BY product_id DESC");
                $name = '';
                $saved_id = 0;
                $ids = [];
                if ($this->affectedRows() > 0) {
                    // check for all names if need to be migrated
                    while ($r = $this->fetch($q)) {
                        if ($name == '') {
                            $name = trim($r['name']);
                        }
                        $n = trim($r['name']);
                        $ids[] = $r['id'];
                        foreach ($already_migrated as $migrated) {
                            if (isset($migrated[$n]) && $saved_id == 0) {
                                $saved_id = $migrated[$n];
                            }
                        }
                    }

                    if ($saved_id === 0) {
                        $count++;
                        $option_insert_data = [
                            $count, // option id
                            $name,
                            'select', // option type
                            '', // sort order,
                        ];

                        fputcsv($options_handle, $option_insert_data);
                        print "${count} option(s) exported\n";
                        // push into already migrated array
                        array_push($already_migrated, [$name => $count]);
                        $this->query("UPDATE {$this->DB_PREFIX}product_variations SET used_option = 1 WHERE id = '{$ids[0]}'");
                        $saved_id = $count;
                    }

                    foreach ($ids as $id) {
                        // update current row option id
                        $this->query("UPDATE {$this->DB_PREFIX}product_variations SET option_id='{$saved_id}' WHERE id = '{$id}'");
                    }
                }
            }
            print "Options export ended.\n";
        }
        fclose($options_handle);
    }

    public function option_values()
    {
        $option_value_handle = fopen('option_values.csv', 'w') or die('Failed to open file.');
        $option_value_headers = ['Option value id', 'Option id', 'Name', 'Option image', 'Sort order'];
        fputcsv($option_value_handle, $option_value_headers);
        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}product_variations LIKE 'option_value_id'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}product_variations ADD option_value_id INT NOT NULL AFTER product_image_type");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}product_variations SET option_value_id=0");
        }
        $query = $this->query("SELECT * FROM {$this->DB_PREFIX}product_variations WHERE master_id = 0 and vendor_id = 0 and used_option = 1 order by option_id ASC");

        if ($this->affectedRows() > 0) {
            print "Option values export started.\n";
            $count = 0;
            while ($row = $this->fetch($query)) {
                $options_q = $this->query("SELECT * FROM {$this->DB_PREFIX}product_variations WHERE master_id = 0 AND option_id = '{$row['option_id']}'"); // it will return all the options but we need unique ones
                $unique_options = array();
                if ($this->affectedRows() > 0) {
                    while ($optionRow = $this->fetch($options_q)) {
                        // select all the unique names
                        $option_value_name = trim($optionRow['name']);
                        if (!in_array($option_value_name, $unique_options)) {
                            $unique_options[] = $option_value_name;
                        }
                    }
                }

                // loop over all options and update their option value id and export in csv
                foreach ($unique_options as $option_name) {
                    $count++;
                    $option_name = str_replace("'", "\'", $option_name);
                    $q = $this->query("SELECT * FROM {$this->DB_PREFIX}product_variations WHERE master_id = 0 AND option_id = '{$row['option_id']}' AND name='{$option_name}'");

                    // check any of the row has image
                    $image = '';
                    while ($r = $this->fetch($q)) {
                        if ($r['product_image'] != '') {
                            $image = $r['product_image'];
                            break;
                        }
                    }

                    $option_value_insert_data = [
                        $count, // option value id
                        $row['option_id'], // option id
                        str_replace("\'", "'", $option_name),
                        ($image != '') ? ((strpos($image, 'http://') === false && strpos($image, 'https://') === false) ? $this->source_domain . $image : $image) : '',
                        '', // sort order
                    ];
                    $this->query("UPDATE {$this->DB_PREFIX}product_variations set option_value_id='{$count}' WHERE option_id={$row['option_id']} AND name='{$option_name}'");
                    fputcsv($option_value_handle, $option_value_insert_data);
                    print "${count} option value(s) exported\n";
                }
            }
            print "Option values export ended.\n";
        }
        fclose($option_value_handle);
    }

    public function products()
    {
        $products_handle = fopen('products.csv', 'w') or die('Failed to open file.');
        $product_headers = ['Product ID', 'Cat. 1', 'Model', 'Name', 'Description', 'Meta description', 'Meta title', 'Meta keywords', 'SEO url 0', 'Tags', 'SKU', 'EAN', 'UPC', 'JAN', 'MPN', 'ISBN', 'Minimum', 'Subtract', 'Out stock status', 'Price', 'Tax class', 'Quantity', 'Main image', 'Manufacturer', 'Points', 'Points 2199 Holland Ave. Bronx CSA', 'Points Anderson Avenue CSA', 'Points Carnegie Hill CSA', 'Points Chelsea CSA', 'Points City Island CSA', 'Points Darien CSA', 'Points Default', 'Points East 88th Street CSA', 'Points Gerard Avenue CSA', 'Points Greene County CSA', 'Points Hastings CSA', 'Points Jan Hus CSA', 'Points Lenox Hill Neighborhood House', 'Points Lexington Ave CSA', 'Points Locust Point CSA', 'Points Mt. Sinai Farm Share', 'Points Mt. Sinai Farm Share Union Sq', 'Points New Rochelle Synagogue CSA', 'Points Parkchester CSA', 'Points PS 11', 'Points PS 83 CSA', 'Points Publicis Employee CSA', 'Points Ridgefield CSA at St. Andrews', 'Points Rye/ Soundshore CSA', 'Points Scarsdale Synagogue CSA', 'Points South Salem CSA', 'Points Southport /Fairfield CSA', 'Points Stamford JCC CSA', 'Points Upper Westchester CSA', 'Points West Village CSA', 'Points White Plains CSA', 'Points Wilton/Norwalk CSA', 'Points Yorkville CSA', 'Weight class', 'Weight', 'Length class', 'Length', 'Width', 'Height', 'Option', 'Option required', 'Option type', 'Option value', 'Option value sort order', 'Option subtract', 'Option image', 'Option quantity', 'Option price prefix', 'Option price', 'Option points prefix', 'Option points', 'Option weight prefix', 'Option weight', 'Products related', 'Date available', 'Date added', 'Date modified', 'Requires shipping', 'Location', 'Sort order', 'Store', 'Status', 'Product Type', 'Harvest Id'];
        fputcsv($products_handle, $product_headers);

        $this->query("UPDATE {$this->DB_PREFIX}product set name=trim(name)");
        $this->query("UPDATE {$this->DB_PREFIX}product set name=trim(TRAILING '\n' FROM name)");
        $this->query("UPDATE {$this->DB_PREFIX}product set name=trim(TRAILING '\r' FROM name)");

        $query = $this->query("SELECT * FROM {$this->DB_PREFIX}products where master_id = 0");
  
        if ($this->affectedRows() > 0) {
            // update the product model in column too
            $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}products LIKE 'model'");
            if ($this->affectedRows() <= 0) {
                $this->query("ALTER TABLE {$this->DB_PREFIX}products ADD model VARCHAR(255) NOT NULL AFTER archive");
            }
            print "Products export started.\n";
            $count = 0;
            $models = array();
            while ($row = $this->fetch($query)) {
                if (!in_array($row['name'], $models)) {
                    $harvest_year = '';
                    if ($row['product_type'] == 'share') {
                        $harvest_year = '-' . $this->getHarvestYear($row['harvest_id']);
                    }
                    $model = trim(
                        str_replace(
                            "--", 
                            "", 
                            str_replace(
                                array("'", ",", " "),
                                array("", "", "-"),
                                strtolower($row['name']) . $harvest_year
                            )
                        )
                        , "-");
                } else {
                    $found = false;
                    $i = 1;
                    while ($found == false) {
                        if (!in_array($row['name'] . $i, $models)) {
                            $model = $row['name'] . $i;
                            $found = true;
                        } else {
                            $i++;
                        }
                    }
                }

                // product type
                $product_type = 0;
                if ($row['mandatory_product'] == 1) {
                    $product_type = 3; // mandatory share
                } else if ($row['suggested_product'] == 1) {
                    $product_type = 4; // suggested share
                } else if ($row['product_type'] == 'share') {
                    $product_type = 2; // normal share
                } else if ($row['product_type'] == 'product') {
                    $product_type = 1; // marketplace product
                }
                if ($product_type == '2' || $product_type == '3' || $product_type == '4') {
                    $category_id = 95; // share category
                } else {
                    $category_id = $row['category_id'];
                }
                $count++;
                array_push($models, $model);
                $product_insert_data = [
                    $row['id'],
                    $category_id,
                    $model, // Model
                    $row['name'],
                    $row['description'],
                    '', // meta description
                    $row['name'], // meta title
                    '', // Meta keywords
                    '', // seo url
                    '', // Tags
                    '', // SKU
                    '', // EAN
                    '', // UPC
                    '', // JAN
                    '', // MPN
                    '', // ISBN
                    1, // Minimum
                    1, // Subtract stock
                    5, // Out stock status
                    $row['default_price'],
                    0, // Tax class 0 - None
                    ($row['initial_stock'] == 0) ? $row['available_stock'] : $row['initial_stock'], // Quantity
                    ($row['product_image'] != '') ?  (strpos($row['product_image'], 'http://') === FALSE && strpos($row['product_image'], 'https://') === FALSE ? $this->source_domain . $row['product_image'] : $row['product_image']) : '',
                    '', // Manufacturer
                    '', // Points
                    '', // Points 2199 Holland Ave. Bronx CSA
                    '', // Points Anderson Avenue CSA
                    '', // Points Carnegie Hill CSA
                    '', // Points Chelsea CSA
                    '', // Points City Island CSA
                    '', // Points Darien CSA
                    '', // Points Default
                    '', // Points East 88th Street CSA
                    '', // Points Gerard Avenue CSA
                    '', // Points Greene County CSA
                    '', // Points Hastings CSA
                    '', // Points Jan Hus CSA
                    '', // Points Lenox Hill Neighborhood House
                    '', // Points Lexington Ave CSA
                    '', // Points Locust Point CSA
                    '', // Points Mt. Sinai Farm Share
                    '', // Points Mt. Sinai Farm Share Union Sq
                    '', // Points New Rochelle Synagogue CSA
                    '', // Points Parkchester CSA
                    '', // Points PS 11
                    '', // Points PS 83 CSA
                    '', // Points Publicis Employee CSA
                    '', // Points Ridgefield CSA at St. Andrews
                    '', // Points Rye/ Soundshore CSA
                    '', // Points Scarsdale Synagogue CSA
                    '', // Points South Salem CSA
                    '', // Points Southport /Fairfield CSA
                    '', // Points Stamford JCC CSA
                    '', // Points Upper Westchester CSA
                    '', // Points West Village CSA
                    '', // Points White Plains CSA
                    '', // Points Wilton/Norwalk CSA
                    '', // Points Yorkville CSA
                    '', // Weight class
                    '', // Weight
                    '', // Length class
                    '', // Length
                    '', // Width
                    '', // Height
                    '', // Option
                    '', // Option required
                    '', // Option type
                    '', // Option value
                    '', // Option value sort order
                    '', // Option subtract
                    '', // Option image
                    '', // Option quantity
                    '', // Option price prefix
                    '', // Option price
                    '', // Option points prefix
                    '', // Option points
                    '', // Option weight prefix
                    '', // Option weight
                    '', // Products related
                    '', // Date available
                    '', // Date added
                    '', // Date modified
                    0, // Requires shipping
                    '', // Location
                    $row['display_order'],
                    0, // Store
                    $row['visible'], // Status
                    $product_type,
                    $row['harvest_id'] != 0 ? $this->getHarvest($row) : 0, // harvest id
                ];
                fputcsv($products_handle, $product_insert_data);
                $this->query("UPDATE {$this->DB_PREFIX}products set model='{$model}' WHERE id='{$row['id']}'");
                print "${count} product(s) exported\n";
            }
            print "Products export ended.\n";
            fclose($products_handle);
        }
    }

    public function products_option_values()
    {
        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}product_variations LIKE 'product_option_value_id'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}product_variations ADD product_option_value_id INT NOT NULL AFTER product_image_type");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}product_variations SET product_option_value_id=0");
        }

        $handle = fopen('products_option_values.csv', 'w') or die('Failed to open file.');

        $products_option_values_headers = ['Product option value id', 'Product id', 'Option id', 'Option value id', 'Quantity', 'Subtract', 'Price', 'Price prefix', 'Points', 'Points prefix', 'Weight', 'Weight prefix'];
        fputcsv($handle, $products_option_values_headers);
        // SELECT *, pv.id as variation_id from ww_products p INNER JOIN ww_product_variations pv ON pv.product_id = p.id WHERE p.master_id = 0 and pv.master_id = 0 and p.id=669  order by pv.product_id asc
        $query = $this->query("SELECT *, pv.id as variation_id from {$this->DB_PREFIX}products p INNER JOIN {$this->DB_PREFIX}product_variations pv ON pv.product_id = p.id WHERE p.master_id = 0 and pv.master_id = 0 order by pv.product_id asc");
        if ($this->affectedRows() > 0) {
            $count = 0;
            print "Products options values export started.\n";
            while ($row = $this->fetch($query)) {
                $count++;
                if ($row['default_price'] == $row['amount'] || $row['amount'] == 0) {
                    $price_prefix = '+';
                    $price = '0';
                } else if ($row['default_price']  > $row['amount']) {
                    $price_prefix = '-';
                    $price = $row['default_price'] - $row['amount'];
                } else {
                    $price_prefix = '+';
                    $price = $row['amount'] - $row['default_price'];
                }

                $products_option_values_data = array(
                    $count, // Product option value id
                    $row['product_id'], // Product id
                    $row['option_id'],
                    $row['option_value_id'],
                    ($row['initial_stock'] == 0) ? $row['available_stock'] : $row['initial_stock'], // Quantity
                    '1', // Subtract Stock
                    number_format($price, 2),
                    $price_prefix,
                    '', // Points
                    '', // Points prefix
                    '', // Weight
                    '' // Weight prefix
                );
                fputcsv($handle, $products_option_values_data);
                $this->query("UPDATE {$this->DB_PREFIX}product_variations SET product_option_value_id={$count} WHERE id = {$row['variation_id']}");
                print "${count} product options value(s) exported\n";
            }
            print "Product options values export ended.\n";
        }
        fclose($handle);
    }

    public function product_customer_group_price()
    {
        $handle = fopen('product_customer_group_price.csv', 'w') or die('Failed to open file.');

        $query = $this->query("SELECT * from {$this->DB_PREFIX}products where master_id = 0");
        if ($this->affectedRows() > 0) {
            $count = 0;
            print "Product customer group price export started.\n";
            while ($row = $this->fetch($query)) {
                $count++;
                $product_id = $row['id'];
                $q = $this->query("SELECT * from {$this->DB_PREFIX}products where master_id = {$row['id']}");
                if ($this->affectedRows() > 0) {
                    while ($r = $this->fetch($q)) {
                        $product_customer_group_price_data = array(
                            $product_id,
                            $this->getCustomerGroup($r),
                            $r['default_price']
                        );
                        fputcsv($handle, $product_customer_group_price_data);
                    }
                }
                print "${count} product customer group price exported\n";
            }
            print "Product customer group price export ended.\n";
        }
        fclose($handle);
    }

    public function product_customergroup_optionvalue()
    {
        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}product_variations LIKE 'product_option_id'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}product_variations ADD product_option_id INT NOT NULL AFTER product_image_type");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}product_variations SET product_option_id=0");
        }
        // update product option id first
        $query = $this->query("SELECT pv.product_id, pv.option_id, pv.id as id from ww_products p INNER JOIN ww_product_variations pv ON pv.product_id = p.id WHERE p.master_id = 0 and pv.master_id = 0 GROUP by pv.product_id, pv.option_id order by pv.product_id asc");
        if ($this->affectedRows() > 0) {
            $count = 1;
            while ($row = $this->fetch($query)) {
                $this->query("UPDATE {$this->DB_PREFIX}product_variations set product_option_id={$count} where option_id={$row['option_id']} and product_id={$row['product_id']}");
                $count++;
            }
        }

        $handle = fopen('product_customer_group_option_value.csv', 'w') or die('Failed to open file.');
        $query = $this->query("SELECT pv.id as variation_id, pv.* from ww_products p INNER JOIN ww_product_variations pv ON pv.product_id = p.id WHERE p.master_id = 0 and pv.master_id = 0");
        if ($this->affectedRows() > 0) {
            $count = 0;
            print "Product customer group option value export started.\n";
            while ($row = $this->fetch($query)) {
                $count++;

                $q = $this->query("SELECT * from {$this->DB_PREFIX}products p INNER JOIN {$this->DB_PREFIX}product_variations pv ON pv.product_id = p.id where pv.master_id = {$row['variation_id']}");
                if ($this->affectedRows() > 0) {
                    while ($r = $this->fetch($q)) {

                        if ($r['default_price'] == $r['amount'] || $r['amount'] == 0) {
                            $price = '0';
                        } else if ($r['default_price']  > $r['amount']) {
                            $price = $r['default_price'] - $r['amount'];
                        } else {
                            $price = $r['amount'] - $r['default_price'];
                        }

                        $product_customer_group_price_data = array(
                            $r['product_id'], // 'product_id',
                            $row['product_option_id'], // 'product_option_id',
                            $row['product_option_value_id'], // 'product_option_value_id',
                            $row['option_id'], // 'option_id',
                            $row['option_value_id'], // 'option_value_id',
                            $this->getCustomerGroup($r), // 'customer_group_id',
                            $price // 'price',
                        );
                        fputcsv($handle, $product_customer_group_price_data);
                    }
                }
                print "${count} product customer group price exported\n";
            }
            print "Options export ended.\n";
        }
        fclose($handle);
    }

    public function product_customer_group()
    {
        $product_customer_handle = fopen('product_customer_group.csv', 'w') or die('Failed to open file.');
         
        $query = $this->query("SELECT * FROM {$this->DB_PREFIX}products WHERE master_id != 0 AND vendor_id != 0");
        if ($this->affectedRows() > 0) {
            print "Product customer group export started.\n";
            $count = 0;

            while ($row = $this->fetch($query)) {
                $count++;
                $product_customer_insert_data = array(
                    $row['master_id'], // product id
                    $this->getCustomerGroup($row),
                );
                fputcsv($product_customer_handle, $product_customer_insert_data);
                print "${count} product customer(s) group exported\n";
            }
            print "Product customer group export ended.\n";
        }
        fclose($product_customer_handle);
    }

    // create recurring profiles
    public function recurring()
    {
        $recurring_handle = fopen('recurring.csv', 'w') or die('Failed to open file.');
        $recurring_desc_handle = fopen('recurring_desc.csv', 'w') or die('Failed to open file.');
        $product_recurring_handle = fopen('product_recurring.csv', 'w') or die('Failed to open file.');

        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}products LIKE 'recurring_id'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}products ADD recurring_id INT NOT NULL AFTER product_image_type");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}products SET recurring_id = 0");
        }

        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}products LIKE 'recurring_name3'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}products ADD recurring_name3 varchar(100) NULL AFTER product_image_type");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}products SET recurring_name3 = ''");
        }

        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}products LIKE 'recurring_name4'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}products ADD recurring_name4 varchar(100) NULL AFTER product_image_type");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}products SET recurring_name4 = ''");
        }

        $query = $this->query("SELECT * FROM {$this->DB_PREFIX}products WHERE product_type = 'share' AND master_id = 0");
        if ($this->affectedRows() > 0) {
            print "Recurring export started.\n";
            $count = 0;
            $already_done = array();
            while ($row = $this->fetch($query)) {
                $durations = [3, 4];
                foreach ($durations as $duration) {
                    $price = $row['default_price'];
                    $price = number_format($price / $duration, 2);
                    $name = $duration . " Pay - {$row['name']} (\${$price} Per Month)";
                    $found = false;
                    foreach ($already_done as $recurring) {
                        if (isset($recurring[$name])) {
                            $found = $recurring;
                        }
                    }

                    if ($found != false) {
                        $recurring_id = $found[$name];
                    } else {
                        $count++;
                        $recurring_id = $count;

                        $recurring_insert_data = [
                            $recurring_id, // recurring_id,
                            $price,
                            'month', // frequency
                            $duration,
                            '1', // cycle,
                            0, // trial_status,
                            0, // trial_price,
                            'day', // trial_frequency,
                            0, // trial_duration,
                            1, // trial_cycle,
                            1, // status,
                            0 // sort_order
                        ];

                        $recurring_desc_insert_data = [
                            $recurring_id, // 'recurring_id',
                            1, // language_id,
                            $name // recurring_name
                        ];

                        fputcsv($recurring_handle, $recurring_insert_data);
                        fputcsv($recurring_desc_handle, $recurring_desc_insert_data);
                        array_push($already_done, array($name => $count));
                    }


                    $oc_customer_groups = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33];

                    // assign recurring option to product with all customer groups
                    foreach ($oc_customer_groups as $customer_group) {
                        $product_recurring_insert_data = [
                            $row['id'],
                            $recurring_id,  // recurring_id
                            $customer_group
                        ];
                        fputcsv($product_recurring_handle, $product_recurring_insert_data);
                    }

                    print "${count} recurring exported\n";

                    $name = str_replace("'", "\'", $name);
                    if ($duration == 3) {
                        $this->query("UPDATE {$this->DB_PREFIX}products set recurring_id='{$recurring_id}' WHERE id='{$row['id']}'");
                        $this->query("UPDATE {$this->DB_PREFIX}products set recurring_name3='{$name}' WHERE id='{$row['id']}'");
                    } else {
                        $this->query("UPDATE {$this->DB_PREFIX}products set recurring_name4='{$name}' WHERE id='{$row['id']}'");
                    }
                }
            }
            print "Recurring export ended.\n";
        }
        fclose($recurring_handle);
    }

    public function orders()
    {
        $orders_handle = fopen('orders.csv', 'w') or die('Failed to open file.');

        $order_headers = ['Order id', 'Invoice no', 'Invoice prefix', 'Store id', 'Store name', 'Store url', 'Customer id', 'Customer group id', 'Firstname', 'Lastname', 'Email', 'Telephone', 'Fax', 'Custom field', 'Payment firstname', 'Payment lastname', 'Payment company', 'Payment address 1', 'Payment address 2', 'Payment city', 'Payment postcode', 'Payment country', 'Payment country id', 'Payment zone', 'Payment zone id', 'Payment address format', 'Payment custom field', 'Payment method', 'Payment code', 'Shipping firstname', 'Shipping lastname', 'Shipping company', 'Shipping address 1', 'Shipping address 2', 'Shipping city', 'Shipping postcode', 'Shipping country', 'Shipping country id', 'Shipping zone', 'Shipping zone id', 'Shipping address format', 'Shipping custom field', 'Shipping method', 'Shipping code', 'Comment', 'Total', 'Order status id', 'Affiliate id', 'Commission', 'Marketing id', 'Tracking', 'Language id', 'Currency id', 'Currency code', 'Currency value', 'Ip', 'Forwarded ip', 'User agent', 'Accept language', 'Date added', 'Date modified', 'Harvest id'];

        fputcsv($orders_handle, $order_headers);
        /* SELECT *, o.id order_id, c.vendor_id customer_vendor_id, o.created_date order_created_date, o.modified_date order_modified_date FROM ww_orders o INNER JOIN ww_contacts c ON o.contact_id = c.id */
        $query = $this->query("SELECT o.*, c.*, o.id order_id, c.vendor_id customer_vendor_id, o.created_date order_created_date, o.modified_date order_modified_date FROM {$this->DB_PREFIX}orders o INNER JOIN {$this->DB_PREFIX}contacts c ON o.contact_id = c.id INNER JOIN {$this->DB_PREFIX}order_to_products op ON o.id = op.order_id GROUP by op.order_id");

        if ($this->affectedRows() > 0) {
            print "Orders export started.\n";
            $count = 0;
            while ($row = $this->fetch($query)) {
                $count++;

                $comments_q = $this->query("SELECT * FROM {$this->DB_PREFIX}comments WHERE comment_for = 'orders' AND comment_for_id = '{$row['order_id']}'");
                $comment = '';
                if ($this->affectedRows() > 0) {
                    while ($comment_row = $this->fetch($comments_q)) {
                        $comment .= $comment_row['comment'] . "\r\n";
                    }
                }

                // get payment method
                $payment_method = '';

                $q = $this->query("SELECT * from {$this->DB_PREFIX}transactions WHERE order_id = '{$row['order_id']}'");
                if ($this->affectedRows() > 0) {
                    $r = $this->fetch($q);
                    $payment_method = $r['payment_gateway'];
                }

                $date = getdate(strtotime($row['order_created_date']));

                $order_insert_data = [
                    $row['order_id'],
                    0, // invoice no
                    "INV-{$date['year']}-00",
                    '0', // store_id
                    'Stoneledge Farm',
                    $this->dest_domain . '/', // store url
                    $row['contact_id'],
                    $this->getCustomerGroup($row),
                    $row['first_name'],
                    $row['last_name'],
                    $row['email'],
                    $row['phone'],
                    '', // fax
                    '', // Custom field
                    $row['first_name'], // Payment firstname
                    $row['last_name'], // Payment lastname
                    '', // Payment company
                    $row['streetname1'], // Payment address 1
                    $row['streetname1'], // Payment address 2
                    $row['city'], // Payment city
                    $row['zip'], // Payment postcode
                    'United States', // payment country
                    '223', // payment country id
                    $this->getZone($row, true),  // 'Payment zone'
                    $this->getZone($row), // 'Payment zone id'
                    '{firstname} {lastname}
                    {company}
                    {address_1}
                    {address_2}
                    {city}, {zone} {postcode}
                    {country}', // payment address format
                    '[]', // 'Payment custom field'
                    $payment_method,  // 'Payment method'
                    $payment_method, // 'Payment code'
                    $row['first_name'], // Shipping firstname
                    $row['last_name'], // Shipping lastname
                    '', // Shipping company
                    $row['streetname1'], // Shipping address 1
                    $row['streetname1'], // Shipping address 2
                    $row['city'], // Shipping city
                    $row['zip'], // Shipping postcode
                    'United States', // Shipping country
                    '223', // Shipping country id
                    $this->getZone($row, true),  // 'Shipping zone'
                    $this->getZone($row), // 'Shipping zone id'
                    '{firstname} {lastname}
                    {company}
                    {address_1}
                    {address_2}
                    {city}, {zone} {postcode}
                    {country}', // shipping address format
                    '[]',
                    '', // Shipping method
                    '', // Shipping Code
                    $comment,
                    $row['total_charge'], // Total
                    $this->getOrderStatus($row),
                    '0', // Affiliate id
                    '0', // Commission
                    '0', // Marketing id
                    '', // Tracking
                    '1', // Language id
                    '2', // Currency id 
                    'USD', // Currency code
                    '1', // Currency value
                    $row['customer_ip'],
                    '', // Forwarded ip
                    '', // User agent
                    '', // Accept language
                    $row['order_created_date'],
                    $row['order_modified_date'],
                    $this->getHarvest($row), // Harvest id
                ];
                fputcsv($orders_handle, $order_insert_data);
                print "${count} order(s) exported\n";
            }
            print "Orders export ended.\n";
        }
        fclose($orders_handle);
    }

    public function order_products()
    {
        $order_products_handle = fopen('order_products.csv', 'w') or die('Failed to open file.');
        $order_products_headers = ['Order product id', 'Order id', 'Product id', 'Name', 'Model', 'Quantity', 'Price', 'Total', 'Tax', 'Reward'];
        fputcsv($order_products_handle, $order_products_headers);

        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}order_to_products LIKE 'order_product_id'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}order_to_products ADD order_product_id INT NOT NULL AFTER share_partner");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}order_to_products SET order_product_id=0");
        }
        // SELECT *, op.id as order_product_id FROM ww_order_to_products op INNER JOIN ww_products p ON p.id=op.product_id order by op.id ASC
        $query = $this->query("SELECT *, op.id as order_product_id FROM {$this->DB_PREFIX}order_to_products op INNER JOIN {$this->DB_PREFIX}products p ON p.id=op.product_id order by op.id ASC");
        if ($this->affectedRows() > 0) {
            print "Order products export started.\n";
            $count = 0;
            while ($row = $this->fetch($query)) {
                $count++;
                $order_products_insert_data = [
                    $count, // Order product id
                    $row['order_id'],
                    $row['product_id'],
                    $row['name'],
                    $row['model'],
                    $row['product_quantity'],
                    $row['product_price'], // unit price
                    round($row['product_quantity'] * $row['product_price'], 2), // total price
                    0.0000, // tax
                    0 // reward
                ];
                fputcsv($order_products_handle, $order_products_insert_data);
                $this->query("UPDATE {$this->DB_PREFIX}order_to_products SET order_product_id='{$count}' WHERE id={$row['order_product_id']}");
                print "${count} order product(s) exported\n";
            }
            print "Order products export ended.\n";
        }
        fclose($order_products_handle);
    }

    public function order_option()
    {
        $order_options_handle = fopen('order_option.csv', 'w') or die('Failed to open file.');

        $query = $this->query("SELECT * FROM {$this->DB_PREFIX}order_to_products op INNER JOIN {$this->DB_PREFIX}product_variations pv ON pv.id=op.variation_id order by op.id ASC");

        if ($this->affectedRows() > 0) {
            print "Order options export started.\n";
            $count = 0;
            while ($row = $this->fetch($query)) {
                $count++;
                // get option value based on product id will be first row in group
                $option_q = $this->query("select * from ww_product_variations where product_id = '{$row['product_id']}' group by product_id");
                $option_name = '';
                if ($this->affectedRows() > 0) {
                    $option_data = $this->fetch($option_q);
                    $option_name = $option_data['name'];
                }
                $order_options_insert_data = array(
                    $count, // order_option_id
                    $row['order_id'],
                    $row['order_product_id'], // order_product_id
                    $row['option_id'],
                    $row['option_value_id'],
                    $option_name,
                    $row['name'], // option value
                    'select'
                );
                fputcsv($order_options_handle, $order_options_insert_data);
                print "${count} order option(s) exported\n";
            }
            print "Order options export ended.\n";
        }
        fclose($order_options_handle);
    }

    public function order_total()
    {
        $order_total_handle = fopen('order_total.csv', 'w') or die('Failed to open file.');
        $order_total_headers = ['Order total id', 'Order id', 'Code', 'Title', 'Value', 'Sort order',];
        fputcsv($order_total_handle, $order_total_headers);
        $query = $this->query("SELECT o.* FROM {$this->DB_PREFIX}orders o INNER JOIN {$this->DB_PREFIX}contacts c ON o.contact_id = c.id INNER JOIN {$this->DB_PREFIX}order_to_products op ON o.id = op.order_id GROUP by op.order_id");
        if ($this->affectedRows() > 0) {
            print "Order total export started.\n";
            $count = 0;
            $order_totals = [
                'sub_total' => array(
                    'title' => 'Sub-Total',
                    'order' => 1,
                ),
                'total' => array(
                    'title' => 'Total',
                    'order' => 9,
                )
            ];
            while ($row = $this->fetch($query)) {
                foreach ($order_totals as $key => $order_total) {
                    $count++;
                    $order_total_insert_data = array(
                        $count, // 'Order total id'
                        $row['id'], // Order id
                        $key, // 'Code',
                        $order_total['title'], // 'Title',
                        $row['total_charge'], // 'Value',
                        $order_total['order'], // sort order
                    );
                    fputcsv($order_total_handle, $order_total_insert_data);
                    print "${count} order total(s) exported\n";
                }
            }
            print "Order total export ended.\n";
        }
        fclose($order_total_handle);
    }

    protected function set_order_transactions_cols()
    {
        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}transactions LIKE 'pending_amount'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}transactions ADD pending_amount VARCHAR(255) NOT NULL AFTER payment_gateway");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}transactions SET pending_amount=transaction_amount");
        }

        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}order_to_products LIKE 'pending_amount'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}order_to_products ADD pending_amount VARCHAR(255) NOT NULL AFTER flag");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}order_to_products op INNER JOIN  {$this->DB_PREFIX}products p ON p.id = op.product_id SET op.pending_amount=op.product_price WHERE p.product_type='share'");
        }

        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}transactions LIKE 'completed'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}transactions ADD completed INT NOT NULL DEFAULT 0 AFTER payment_gateway");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}transactions set completed = 0");
        }

        $this->query("SHOW COLUMNS FROM {$this->DB_PREFIX}order_to_products LIKE 'order_recurring_id'");
        if ($this->affectedRows() <= 0) {
            $this->query("ALTER TABLE {$this->DB_PREFIX}order_to_products ADD order_recurring_id INT NOT NULL DEFAULT 0 AFTER share_partner");
        } else {
            $this->query("UPDATE {$this->DB_PREFIX}order_to_products SET order_recurring_id = 0");
        }
    }

    public function order_transactions()
    {
        $order_recurring_handle = fopen('order_recurring.csv', 'w') or die('Failed to open file.');
        $order_recurring_trans_handle = fopen('order_recurring_transaction.csv', 'w') or die('Failed to open file.');

        $order_recurring_headers = ['order_recurring_id', 'order_id', 'reference', 'product_id', 'product_name', 'product_quantity', 'recurring_id', 'recurring_name', 'recurring_description', 'recurring_frequency', 'recurring_cycle', 'recurring_duration', 'recurring_price', 'trial', 'trial_frequency', 'trial_cycle', 'trial_duration', 'trial_price', 'status', 'date_added'];

        $order_recurring_trans_headers = ['order_recurring_transaction_id', 'order_recurring_id', 'reference', 'type', 'amount', 'date_added'];

        fputcsv($order_recurring_handle, $order_recurring_headers);
        fputcsv($order_recurring_trans_handle, $order_recurring_trans_headers);

        $this->set_order_transactions_cols(); // helper cols
        // SELECT o.* FROM ww_orders o INNER JOIN ww_contacts c ON o.contact_id = c.id INNER JOIN ww_order_to_products op ON o.id = op.order_id WHERE o.status != 'incomplete' GROUP BY op.order_id
        $query = $this->query("SELECT o.*, o.id as order_id FROM {$this->DB_PREFIX}orders o INNER JOIN {$this->DB_PREFIX}contacts c ON o.contact_id = c.id INNER JOIN {$this->DB_PREFIX}order_to_products op ON o.id = op.order_id WHERE o.status != 'incomplete' GROUP BY op.order_id");

        if ($this->affectedRows() > 0) {
            print "Order transaction export started.\n";
            $count = 0;
            $trans_count = 0;
            while ($row = $this->fetch($query)) {

                $all_transactions_done = false;
                $exported_order_product = array();
                while (!$all_transactions_done) {

                    $prod_q = $this->query("SELECT *, op.id as order_product_id FROM {$this->DB_PREFIX}order_to_products op INNER JOIN {$this->DB_PREFIX}products p ON p.id = op.product_id  WHERE order_id = '{$row['id']}' AND p.product_type='share'");

                    while (($prod_row = $this->fetch($prod_q)) && !$all_transactions_done) {

                       // check if there is pending amount for the product
                        if ($prod_row['pending_amount'] == 0) {
                            continue;
                        }

                        $product_pending_amount = $prod_row['pending_amount'];

                        $trans_q = $this->query("SELECT * FROM {$this->DB_PREFIX}transactions WHERE order_id = '{$row['id']}' AND payment_gateway IN ('check3pay', 'authorizenet3pay', 'check4pay', 'authorizenet4pay', 'partial_payment') AND transaction_amount > 0 and completed = 0");

                        $total_transactions = $this->affectedRows(); // to identify payment method

                        // if there's no transactions left transactions done true move to next order
                        if ($total_transactions == 0) {
                            $all_transactions_done = true;
                            continue;
                        }

                        if (!in_array($row['order_id'] . '_' . $prod_row['id'], $exported_order_product)) {
                            $count++;
                            $order_recurring_id = $count;
                        } else {
                            $order_recurring_id = $prod_row['order_recurring_id'];
                        }

                        $payment_method = '';
                        while ($trans_row = $this->fetch($trans_q)) {
                            if ($payment_method == '') {
                                if ($trans_row['payment_gateway'] != '') {
                                    $payment_method = $trans_row['payment_gateway'];
                                } else {
                                    if ($trans_row['payment_type'] == 'check' || $row['cc_last_four'] == '') {
                                        $payment_method = ($total_transactions > 3) ? 'check4pay' : 'check3pay';
                                    } else {
                                        $payment_method = ($total_transactions > 3) ? 'authorizenet4pay' : 'authorizenet3pay';
                                    }
                                }
                            }

                            $duration = ($payment_method == 'check3pay' || $payment_method == 'authorizenet3pay') ? 3 : 4;

                            // check if product pending amount is greater than transaction pay whole transaction
                            // else pay needed amount for current product
                            if ( $product_pending_amount > $trans_row['transaction_amount']) {
                                $trans_amount = $trans_row['transaction_amount'];
                                $product_pending_amount =  $product_pending_amount - $trans_row['transaction_amount'];
                                $transaction_pending_amount = 0;
                            } else if ($product_pending_amount < $trans_row['transaction_amount']) {
                                $trans_amount = $product_pending_amount;
                                $transaction_pending_amount =  $trans_row['transaction_amount'] - $product_pending_amount;
                                $product_pending_amount = 0;
                            } else {
                                $trans_amount = $product_pending_amount;
                                $product_pending_amount = $transaction_pending_amount = 0;
                            }

                            if ($trans_amount == 0) {
                                continue;
                            }
                            $trans_count++;
                            $order_recurring_trans_insert_data = [
                                $trans_count, // 'order_recurring_transaction_id',
                                $order_recurring_id, // 'order_recurring_id',
                                '', // reference
                                1, // 'type', admin/model/sale/recurring.php:86, catalog/language/en-gb/account/recurring.php
                                $trans_amount, // 'amount',
                                $trans_row['transaction_date'], // 'date_added'
                            ];

                            fputcsv($order_recurring_trans_handle, $order_recurring_trans_insert_data);
                            $this->query("UPDATE {$this->DB_PREFIX}transactions set pending_amount = {$transaction_pending_amount} where id = '{$trans_row['id']}'");
                            $this->query("UPDATE {$this->DB_PREFIX}order_to_products set pending_amount = {$product_pending_amount} where id = '{$prod_row['order_product_id']}'");
                        } // while loop transaction row

                        if (!in_array($row['order_id'] . '_' . $prod_row['id'], $exported_order_product)) {

                            if ($payment_method == 'check3pay' || $payment_method == 'authorizenet3pay') {
                                $recurring_id = $prod_row['recurring_id'];
                                $duration = 3;
                            } else {
                                $recurring_id = $prod_row['recurring_id'] + 1;
                                $duration = 4;
                            }
                            
                            $name = $prod_row["recurring_name{$duration}"];
                            $order_recurring_insert_data = [
                                $order_recurring_id, // 'order_recurring_id',
                                $row['id'], // 'order_id',
                                '', // 'reference',
                                $prod_row['id'], // 'product_id',
                                $prod_row['name'], // 'product_name',
                                $prod_row['product_quantity'], // 'product_quantity',
                                $recurring_id,
                                $name, // 'recurring_name',
                                $name, // 'recurring_description',
                                'month', // 'recurring_frequency',
                                1, // 'recurring_cycle',
                                $duration, // 'recurring_duration',
                                number_format($prod_row['product_price'] / $duration, 2),
                                // $trans_amount, //'recurring_price',
                                0, // 'trial',
                                'month', // 'trial_frequency',
                                1, // 'trial_cycle',
                                0, // 'trial_duration',
                                0, // 'trial_price',
                                1, // 'status',
                                $row['created_date'], // 'date_added'
                            ];
                            fputcsv($order_recurring_handle, $order_recurring_insert_data);
                            print "${count} order transaction(s) exported\n";
                            $exported_order_product[] = $row['order_id'] . '_' . $prod_row['id'];
                            $this->query("UPDATE {$this->DB_PREFIX}order_to_products set order_recurring_id = '{$count}' WHERE product_id={$prod_row['product_id']}");
                        }
                    } // while prod row

                    if (!$all_transactions_done) {
                        // check if transaction amount in still pending
                        $trans_check = $this->query("SELECT sum(pending_amount) as amount FROM {$this->DB_PREFIX}transactions WHERE order_id = '{$row['id']}' AND payment_gateway IN ('check3pay', 'authorizenet3pay', 'check4pay', 'authorizenet4pay', 'partial_payment') AND transaction_amount != 0 and completed = 0 GROUP BY order_id");

                        if ($this->affectedRows() > 0) {
                            $r = $this->fetch($trans_check);
                            if ((int) $r['amount'] <= 0) {
                                $all_transactions_done = true;
                            }
                        }

                        $prod_check = $this->query("SELECT sum(pending_amount) as amount FROM {$this->DB_PREFIX}order_to_products WHERE order_id = '{$row['id']}'");
                        if ($this->affectedRows() > 0) {
                            $r = $this->fetch($prod_check);
                            if ((int) $r['amount'] == 0) {
                                $all_transactions_done = true;
                            }
                        }
                    }
                }
            }
            print "Order transaction export ended.\n";
        }
        fclose($order_recurring_handle);
    }
}

$export_types =  array('customers', 'partners', 'categories', 'options', 'option_values', 'products', 'products_option_values', 'recurring', 'orders', 'order_products', 'order_option', 'order_total', 'order_transactions', 'product_customer_group', 'product_customer_group_price', 'product_customergroup_optionvalue');

$exports = array();

if ($argc > 1) {
    for ($i = 1; $i < $argc; ++$i) {
        $arg = str_replace('--', '', $argv[$i]);
        if (in_array($arg, $export_types)) {
            array_push($exports, $arg);
        } else {
            throw new Exception("Invalid option - ${arg}\nValid options are - customers, partners, categories, options, option_values, products, products_option_values, orders, order_products, order_option, order_total, order_transactions, product_customer_group, product_customer_group_price, product_customergroup_optionvalue");
        }
    }
} else {
    $exports = $export_types; // export all
}

$migration = new Migration();
// partners, order option, product_customer_group, recurring profiles, order_transactions needs to be imported in phpmyadmin
$migration->setExports($exports);
$migration->start();
