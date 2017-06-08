<?php

include_once("kraken_api_handler.php");

$output = array();
$path = $_FILES['file']['name'];
$ext = pathinfo($path, PATHINFO_EXTENSION);
$dst = "";
$checkMime = getimagesize($_FILES["file"]["tmp_name"]);
$checkSize = ($_FILES['file']['size'] <= API_FILE_SIZE_LIMIT);
$uploadOk = ($checkMime !== false) && $checkSize ? 1 : 0;

if($uploadOk) {
    $target_dir = "../uploads/";
	$target_file = $target_dir . time() . '_' . basename($_FILES["file"]["name"]);
	if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo json_encode(array("error" => true, "msg" => "Upload error"));
		die();
	} else {
		$dst = realpath($target_file);
	}
}
if (strlen($dst)) {
    $result = kraken_request_file($dst);
    if(!$result["success"])
	 	$output = array('type' => 'failure', 'file_name' => $_FILES["file"]["name"], 'error' => $result["message"]);
	else
	 	$output = array('type' => 'success', 'file_name' => $result['file_name'], 'dest' => $result["kraked_url"]);
} else {
    $output = array('type' => 'failure', 'file_name' => $_FILES["file"]["name"], 'error' => "Failed to upload file");
}

echo json_encode($output);




?>