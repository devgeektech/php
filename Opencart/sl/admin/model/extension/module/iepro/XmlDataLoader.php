<?php
    class XmlDataLoader extends IeProProfileObject {
        private $xml_array;

        public static function get_xml_data( $controller) {
            $xml_data = new XmlDataLoader( $controller);
            $xml_data->load();

            return $xml_data->get_data();
        }

        public function load() {
            switch ($this->profile_manager->get_file_origin()) {
                case 'manual':
                    $contents = $this->get_file_contents();
                    break;

                case 'url':
                    $contents = $this->get_url_contents();
                    break;

                case 'ftp':
                    $contents = $this->get_ftp_contents();
                    break;
            }

            $model = $this->model_loader->load_file_model( 'ie_pro_file_xml');

            $this->xml_array = $model->get_xml2array_object()->createArray( $contents);
        }

        public function get_data() {
            return new XmlData( $this->controller, $this->xml_array);
        }

        private function get_file_contents() {
            if (!$this->parameters->has_file_upload()) {
               throw new \Exception( $this->language->get( 'profile_import_categories_xml_file_upload_expected'));
            }

            $file = $this->parameters->file( 'file');

            return file_get_contents( $file['tmp_name']);
        }

        private function get_url_contents() {
            return file_get_contents(htmlspecialchars_decode($this->parameters->get( 'import_xls_url')));
        }

        private function get_ftp_contents() {
            $file = $this->parameters->get_strict(
                'import_xls_ftp_file',
                $this->language->get('progress_export_ftp_empty_filename')
            );

            $filename = "{$file}.{$this->file_type}";

            $ftp_path = rtrim( $this->parameters->get( 'import_xls_ftp_path'));
            $final_path = "{$ftp_path}{$filename}";

            $connection = $this->open_ftp_connection();

            ftp_get( $connection, $this->controller->file_tmp_path, $final_path, FTP_BINARY);
            ftp_close( $connection);

            return file_get_contents( $this->file_tmp_path);
        }

        private function open_ftp_connection() {
            $server = $this->parameters->get( 'import_xls_ftp_host');
            $username = $this->parameters->get( 'import_xls_ftp_username');
            $password = $this->parameters->get( 'import_xls_ftp_password');
            $port = $this->parameters->get( 'import_xls_ftp_port', 21);

            $connection = ftp_connect( $server, $port);

            if (!$connection) {
                throw new \Exception( $this->language->get( 'progress_export_ftp_error_connection'));
            }

            $login = ftp_login( $connection, $username, $password);

            if (!$login) {
                throw new \Exception( $this->language->get( 'progress_export_ftp_error_login'));
            }

            $passive_mode = $this->parameters->get( 'import_xls_ftp_passive_mode', null) !== null;

            if ($passive_mode) {
                ftp_pasv( $connection, true);
            }

            return $connection;
        }
    }
