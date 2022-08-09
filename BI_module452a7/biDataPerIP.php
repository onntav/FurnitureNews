<?php
	include "../db/pdo.php";
	session_start();
	error_reporting(1);
   
	$catConfigXML = simplexml_load_file('../cat-config.xml');
	
	//print_r($catConfigXML);
	$pageCnt = 0;
	$pageCnt = $catConfigXML["pageCount"];
	$numberOfVisits = $catConfigXML['NumberOfVisits'] or '100';
	$lengthOfVisit = $catConfigXML['LengthOfVisit'] or '100';
	$lengthOfVisit = $catConfigXML['LengthOfVisit'] or '100';
	$numberOfMostVisitedPagesToShow = $catConfigXML['NumberOfMostVisitedPagesToShow'] or 0;
	$displayPerUserBI = $catConfigXML['DisplayPerUserBI'] or 1;
	$groupPageVisitsByIpAddress = $catConfigXML['GroupPageVisitsByIpAddress'] or 0;

	$actualNumOfVisits = 0;
	$totalNumberOfPageVisits = 0;
	$averagePageLength = 0;

	
	$ipAddress = $_POST['IpAddress'];
	$visitId = $_POST['VisitId'];
	$customerId = $_POST['CustomerId'];
	
	//echo $ipAddress;
	$biRepo = new BIRepository();
	$actualNumOfVisits = $biRepo->getTotalCatalogVisit();
	$totalNumberOfPageVisits = 0;
	
	if($customerId != null && $customerId > 0)
	{
		$totalNumberOfPageVisits = $biRepo->getTotalPageVisitsPerCustomerId($customerId, $visitId);
	}
	else
	{
		$totalNumberOfPageVisits = $biRepo->getTotalPageVisitsPerIP($ipAddress, $visitId);
	}
	
	$averagePageLength = ceil($totalNumberOfPageVisits / ($actualNumOfVisits > 0 ? $actualNumOfVisits : 1));
	
	$json_result = '{"numberOfCatalogVisits": '. $actualNumOfVisits .
                  ' ,"totalNumberOfPageVisits": ' . $averagePageLength . '}';
				  
	//$json_result = json_encode($json_result);
   
   print_r($json_result);
?>
