<?php

include_once("kraken_api_handler.php");

$csv_file = $_FILES['file']['name'];
$ext = pathinfo($csv_file, PATHINFO_EXTENSION);


if (strtolower($ext) != "csv") {
    
} else {
    // limit csv file size?
	$target_dir = "../uploads/";
	$target_file = $target_dir . time() . basename($_FILES["file"]["name"]);
	if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo json_encode(array("type" => "failure", "file_name" => $csv_file, "error" => "Failed to upload file."));
		die();
	} else {
		$csv_file = $target_file;
	}

	$urlList = array_map('str_getcsv', file($csv_file))[0];
	for ($i = 0; $i < sizeof($urlList); $i++) { 
		$source = trim($urlList[$i]);
		$result = kraken_request_url($source);
		//$output[$i] = $result;
		if(!$result["success"])
			$output[$i] = array('type' => 'failure', 'src' => $source, 'error' => $result["message"]);
		else
			$output[$i] = array('type' => 'success', 'src' => $source, 'dest' => $result["kraked_url"]);
		sleep(1); // sleep for 1 second, because kraken api times out if connections are made too quickly
	}

	echo json_encode($output);

}

?>

