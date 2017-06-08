<?php

include_once("kraken_api_handler.php");

$output = array();
$data = $_POST['data'];
$urlList = explode(",", $data);
for ($i = 0; $i < sizeof($urlList); $i++) { 
	$source = trim($urlList[$i]);
	$result = kraken_request_url($source);
	//$output[$i] = $result;
	if(!$result["success"])
	 	$output[$i] = array('type' => 'failure', 'src' => $source, 'error' => $result["message"]);
	else
	 	$output[$i] = array('type' => 'success', 'src' => $source, 'dest' => $result["kraked_url"]);
}

echo json_encode($output);

?>