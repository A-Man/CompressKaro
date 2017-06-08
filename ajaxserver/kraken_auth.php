<?php 

define('API_KEY', "0ff070417c8a525438eb779abb926abc");
define('API_SECRET', "f39a82111127a0726c7f1298474b54a2e356016c");
define('API_URL_ENDPOINT', 'https://api.kraken.io/v1/url');
define('API_UPLOAD_ENDPOINT', 'https://api.kraken.io/v1/upload');
define('API_FILE_SIZE_LIMIT', 33554432);
set_time_limit(300); // set operation limit for php to ensure script doesn't timeout before all urls have been compressed

function get_kraken_auth() {
    return array("api_key" => API_KEY, "api_secret" => API_SECRET);
}


?>