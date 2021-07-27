<?php
    require_once DIR_SYSTEM . 'library/google_spreadsheets/apiclient/vendor/autoload.php';
    require_once DIR_SYSTEM . 'library/google_spreadsheets/php-google_spreadsheet-client/vendor/autoload.php';
    use Google\Spreadsheet\DefaultServiceRequest;
    use Google\Spreadsheet\ServiceRequestFactory;

    class ModelExtensionModuleIeProFileSpreadsheet extends ModelExtensionModuleIeProFile {
        public function __construct($registry){
            parent::__construct($registry);
        }

        function create_file() {
            $GoogleAccessToken = false;
            if(file_exists($this->google_spreadsheet_json_file_path)) {
                putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $this->google_spreadsheet_json_file_path);
                $client = new Google_Client;
                $client->useApplicationDefaultCredentials();

                $client->setApplicationName("Opencart - Export/Import PRO");
                $client->setScopes(['https://www.googleapis.com/auth/drive', 'https://spreadsheets.google.com/feeds']);

                if ($client->isAccessTokenExpired()) {
                    $client->refreshTokenWithAssertion();
                }

                $GoogleAccessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
            }
            $this->GoogleAccessToken = $GoogleAccessToken;
            if(!$GoogleAccessToken)
                $this->exception($this->language->get('google_spreadsheet_error_token'));

            $filename = $this->profile['import_xls_spreadsheet_name'];

            if(empty($filename))
                $this->exception($this->language->get('google_spreadsheet_error_empty_filename'));

            $this->filename = $filename;
        }

        function insert_columns($columns) {}

        function insert_data($columns, $elements) {
            $elements_to_insert = count($elements);
            $sheet_name = $this->language->get('xlsx_sheet_name_'.$this->profile['import_xls_i_want']).'-'.date('Y-m-d-His');

            foreach ($columns as $key2 => $col) {
                $final_column_names[] = $col['custom_name'];
            }

            $final_elements = array();
            foreach ($elements as $element_id => $element) {
                $temp = array();
                foreach ($columns as $col_name => $col_info) {
                    $custom_name = $col_info['custom_name'];
                    $temp[] = array_key_exists($custom_name, $element) ? str_replace(array("\r", "\n", '/\s+/g', '/\t+/'), '', $element[$custom_name]) : '';
                }
                $final_elements[] = $temp;
            }

            $message = $this->language->get('google_spreadsheet_sending_data');
            $this->update_process($message);

            $serviceRequest = new DefaultServiceRequest($this->GoogleAccessToken);
            ServiceRequestFactory::setInstance($serviceRequest);

            $spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
            $spreadsheetFeed = $spreadsheetService->getSpreadsheetFeed();

            $spreadsheet = $spreadsheetFeed->getByTitle($this->filename);
            $spreadsheet->addWorksheet($sheet_name, count($final_column_names)+count($final_elements), count($final_column_names));

            $worksheetFeed = $spreadsheet->getWorksheetFeed();
            $worksheet = $worksheetFeed->getByTitle($sheet_name);

            $cellFeed = $worksheet->getCellFeed();

            $batchRequest = new Google\Spreadsheet\Batch\BatchRequest();
                foreach ($final_column_names as $key => $value) {
                    $batchRequest->addEntry($cellFeed->createCell(1, ($key+1), $value));
                }
                foreach ($final_elements as $number_row => $element) {
                    foreach ($element as $number_column => $data) {
                        if(is_numeric($data) && strlen(substr(strrchr($data, "."), 1)) > 2)
                            $data = number_format($data, 2);
                        elseif(in_array($data, array('+', '-', '*', '=', '%')))
                            $data = '~' . $data;

                        $batchRequest->addEntry($cellFeed->createCell(($number_row+2), ($number_column+1), $data));
                    }
                }
            $batchResponse = $cellFeed->insertBatch($batchRequest);

            $worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
            $worksheet = $worksheets[0];
            $worksheet->delete();
        }

        public function get_data() {
            $this->create_file();

            $serviceRequest = new DefaultServiceRequest($this->GoogleAccessToken);
            ServiceRequestFactory::setInstance($serviceRequest);

            $spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
            $spreadsheetFeed = $spreadsheetService->getSpreadsheetFeed();
            //$spreadsheet = $spreadsheetFeed->getEntries();
            $spreadsheet = $spreadsheetFeed->getByTitle($this->filename);

            $worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
            $worksheet = $worksheets[0];

            $listFeed = $worksheet->getListFeed();

            $final_xls_data = array('columns' => array(), 'data' => array());

            foreach ($listFeed->getEntries() as $key => $entry) {
                $values = $entry->getValues();
                //<editor-fold desc="Put columns">
                    if($key == 0) {
                        foreach ($values as $col_name => $val) {
                            $final_xls_data['columns'][] = $col_name;
                        }
                    }
                //</editor-fold>
                $values = array_values($values);
                foreach ($values as $keyval => $val) {
                    if(!empty($val) && is_string($val) && $val[0] == '~')
                        $values[$keyval] = substr($val, 1);
                    elseif(is_numeric(str_replace(array(',','.'), '', $val))) {
                        $values[$keyval] = str_replace(',', '', $val);
                    }
                }
                $final_xls_data['data'][] = $values;
            }

            foreach ($final_xls_data['columns'] as $key => $col_name) {
                foreach ($this->columns as $key_column => $col_data) {
                    $col_real_name = $col_data['custom_name'];
                    $col_formatted = $this->format_column_name($col_real_name);
                    if($col_formatted == $col_name)
                        $final_xls_data['columns'][$key] = $col_real_name;
                }
            }

            return $final_xls_data;
        }

        public function format_column_name($name) {
            $name = strtolower($name);
            $name = str_replace(array('*', ' ', '(', ')', '_'), '', $name);
            return $name;
        }
    }
?>