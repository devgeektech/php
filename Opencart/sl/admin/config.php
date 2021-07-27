<?php
// HTTP
define('HTTP_SERVER', 'http://localhost/sl/admin/');
define('HTTP_CATALOG', 'http://localhost/sl/');

// HTTPS
define('HTTPS_SERVER', 'http://localhost/sl/admin/');
define('HTTPS_CATALOG', 'http://localhost/sl/');

// DIR
define('DIR_APPLICATION', '/opt/lampp/htdocs/sl/admin/');
define('DIR_SYSTEM', '/opt/lampp/htdocs/sl/system/');
define('DIR_IMAGE', '/opt/lampp/htdocs/sl/image/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_CATALOG', '/opt/lampp/htdocs/sl/catalog/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'opencart_stoneledgeoc');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

// OpenCart API
define('OPENCART_SERVER', 'https://www.opencart.com/');