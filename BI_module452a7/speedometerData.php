<?php
	include "../db/pdo.php";
	session_start();
	error_reporting(1);
   
	//$catConfigXML = simplexml_load_file('../cat-config.xml');

	$actualNumOfVisits = 0;
	$totalNumberOfPageVisits = 0;
	$averagePageLength = 0;

	
	$ipAddress = $_POST['IpAddress'];
	$visitId = $_POST['VisitId'];
	$customerId = $_POST['CustomerId'];
	
	//echo $ipAddress;
	//echo $visitId;
	//echo $customerId;
	$biRepo = new BIRepository();
	
	if($customerId != null && $customerId > 0)
	{
		$actualNumOfVisits = $biRepo->getTotalCatalogVisitPerCustomerId($customerId);
		$totalNumberOfPageVisits = $biRepo->getTotalPageVisitsForCatVisit($visitId);
	}
	else
	{
		$actualNumOfVisits = $biRepo->getTotalCatalogVisitPerIp($ipAddress);
		$totalNumberOfPageVisits = $biRepo->getTotalPageVisitsForCatVisit($visitId);
	}
	
	$json_result = '{"numberOfCatalogVisits": '. $actualNumOfVisits .
                 ' ,"totalNumberOfPageVisits": ' . $totalNumberOfPageVisits . '}';
   
   print_r($json_result);
?>
