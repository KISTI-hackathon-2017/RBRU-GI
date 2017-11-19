<?php
include("myconfig.php");
include("mylib.php");

// Get service
$_GET_lower = array_change_key_case($_GET);
$service = $_GET_lower['service']; // show be WFS
$version = $_GET_lower['version']; // show be WFS
$request = strtolower($_GET_lower['request']); // show be WFS

// Save the request
$fs = fopen("log.txt", 'a') or die("Unable to open file!");
	fwrite($fs, $_SERVER['QUERY_STRING']);
	fwrite($fs, "\n");
fclose($fs);


header("Content-type: text/xml; charset=utf-8'");

if($request == 'Exceptions') {
}elseif($request == 'getcapabilities') {
	include("getcapabilities.php");
}elseif($request == 'describefeaturetype') {
	include("describefeaturetype.php");
}elseif($request == 'getfeature') {
	include("getfeature.php");
}elseif($request == 'describestoredqueries') {
	include("describestoredqueries.php");
}else{
	include("getcapabilities.php");
}
?>