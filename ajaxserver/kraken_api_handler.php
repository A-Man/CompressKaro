<?php 

include_once 'kraken_auth.php';

function kraken_request_url($url) {
	$params = array();
	$params["auth"] = get_kraken_auth();
	$params["url"] = $url;
	$params["wait"] = true;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
	curl_setopt($curl, CURLOPT_URL, 'https://api.kraken.io/v1/url');
	// Force continue-100 from server
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.85 Safari/537.36");
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
	curl_setopt($curl, CURLOPT_FAILONERROR, 0);
	curl_setopt($curl, CURLOPT_CAINFO, __DIR__ . "/cacert.pem");
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	$response = json_decode(curl_exec($curl), true);
	if ($response === null) {
		$response = array (
			"success" => false,
			"error" => 'cURL Error: ' . curl_error($curl)
		);
	}
	curl_close($curl);
	return $response;
}

function kraken_request_file($file) {

    if (!file_exists($file)) {
        return array(
            "success" => false,
            "error" => 'File `' . $file . '` does not exist'
        );
    }

    $params = array();
	$params["auth"] = get_kraken_auth();
	$params["wait"] = true;
    $data = array();
    if (class_exists('CURLFile')) {
        $fileObject = new CURLFile($file);
    } else {
         $fileObject = '@' . $file;
    }
    $data["file"] = $fileObject;
    $data["data"] = json_encode($params);

    //return $data;    
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, API_UPLOAD_ENDPOINT);
	// Force continue-100 from server
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.85 Safari/537.36");
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_FAILONERROR, 0);
	curl_setopt($curl, CURLOPT_CAINFO, __DIR__ . "/cacert.pem");
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	$response = json_decode(curl_exec($curl), true);
	if ($response === null) {
		$response = array (
			"success" => false,
			"error" => 'cURL Error: ' . curl_error($curl)
		);
	}
    $response["info"] = curl_getinfo($curl);
	curl_close($curl);
	return $response;
}

?>