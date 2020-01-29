<?php
/*
 * File: process.php
 * Description: Main file which is run to extract data from json provided[Step1], store it in table [Step2] and create summary CSV of it [Step3]
 * Version 1.1
 */


# Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'connection.php';
include 'functions.php';


// get connection from connection.php
$DBH= dbConnection();


// create database by executing script.sql
try {
$query = file_get_contents("script.sql");
$stmt = $DBH->prepare($query);
if ($stmt->execute())
     echo "Successfully database created <br><br>";
else 
     echo "Failed to create database<br>";

}catch(Exception $e) {

	echo $e;
}

// [---------- MAIN EXECUTION: start here ------------------------]

// [Step1: extract json ] ##############################
	$url = "https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl";
	$jsondata = extractJsonData($url);


// [Step2: process orders data ] #######################
	processOrdersData($jsondata);


// [Step3: create output file ] #######################
	createOutputSummary();

// [---------- MAIN EXECUTION: end here  ------------------------]



?> 
