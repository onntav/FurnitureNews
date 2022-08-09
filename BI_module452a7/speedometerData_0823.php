<?php
	include "../db/pdo_new.php";
	session_start();
	error_reporting(1);
   
	//$catConfigXML = simplexml_load_file('../cat-config.xml');

	$actualNumOfVisits = 0;
	$totalNumberOfPageVisits = 0;
	$averagePageLength = 0;

	
	$ipAddress = $_POST['IpAddress'];
	//echo $ipAddress;
	$biRepo = new BIRepository();
	$actualNumOfVisits = $biRepo->getTotalCatalogVisit();
	$totalNumberOfPageVisits = $biRepo->getTotalPageVisitsPerIP($ipAddress);
	
	$json_result = '{"numberOfCatalogVisits": '. $actualNumOfVisits .
                  ' ,"totalNumberOfPageVisits": ' . $totalNumberOfPageVisits . '}';
   
   print_r($json_result);
?>
