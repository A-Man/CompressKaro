<?php

include_once("kraken_api_handler.php");
include_once("../lib/PHPExcel_1.8.0/Classes/PHPExcel/IOFactory.php");


$excel_file = "";
$target_dir = "../uploads/";
$target_file = $target_dir . time() . "_" . basename($_FILES["file"]["name"]);
if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    echo json_encode(array("error" => true, "msg" => "Upload error"));
    die();
} else {
    $excel_file = realpath($target_file);
}

if (strlen($excel_file)) {
    $objPHPExcel = null;
    try {
        $inputFileType = PHPExcel_IOFactory::identify($excel_file);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($excel_file);
    } catch(Exception $e) {
        die(json_encode(array("error" => true, "msg" =>'Error loading file "'.pathinfo($excel_file,PATHINFO_BASENAME).'": '.$e->getMessage())));
    }

    $data = array();
    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
        $data[$worksheet->getTitle()] = $worksheet->toArray();
    }

    $output = array();
    
    foreach ($data as $worksheet => $content) {
        $output[$worksheet] = array();
        foreach ($content as $key => $url) {
            $url = strval($url[0]);
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $result = kraken_request_url($url);
                if(!$result["success"])
                    $output[$worksheet][$key] = array('type' => 'failure', 'src' => $url, 'error' => $result["message"]);
                else
                    $output[$worksheet][$key] = array('type' => 'success', 'src' => $url, 'dest' => $result["kraked_url"]);
            }
        }
    }
    echo json_encode($output);
} else {
    return json_encode(array("type" => "failure", "file_name" => $excel_file, "error" => "Failed to upload file."));;
}

?>