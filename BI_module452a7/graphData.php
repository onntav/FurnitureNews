<?php
	include "../db/pdo.php";
	session_start();
	error_reporting(1);
   
    $catConfigXML = simplexml_load_file('../cat-config.xml');
	$numberOfMostVisitedPagesToShow = $catConfigXML['NumberOfMostVisitedPagesToShow'] or 0;
	//print_r($catConfigXML);
	$ipAddress = $_POST['IpAddress'];
	$visitId = $_POST['VisitId'];
	$customerId = $_POST['CustomerId'];
	
	//echo $ipAddress;
	$biRepo = new BIRepository();
	//$totalNumberOfPageVisits = $biRepo->getTotalPageVisitsPerIP($ipAddress);
	//$numberOfMostVisitedPagesToShow = $catConfigXML['NumberOfMostVisitedPagesToShow'] or 0;
	
	if($customerId != null && $customerId > 0)
	{
		$mostVisitedPages = $biRepo->getMostVisitPagesPerCustomerId($numberOfMostVisitedPagesToShow, $customerId, $visitId);
	}
	else
	{
		$mostVisitedPages = $biRepo->getMostVisitPagesPerIP($numberOfMostVisitedPagesToShow, $ipAddress, $visitId);
	}
	
	//print_r($mostVisitedPages);
	
	$amChartDataSource = '[';
	$totalPages = count($mostVisitedPages);
	$cnt = 0;
	foreach ($mostVisitedPages as $page)
	{
		$cnt++;
		$amChartDataSource .= '{"catalog": "Page ' . $page['PageNumber'].'",';
		$amChartDataSource .= '"visits": ' . $page['VisitCount'] . '}';
		
		
		if($cnt == $totalPages)
		{
			$amChartDataSource .= ']';
		}
		else
		{
			$amChartDataSource .= ',';
		}
	}
	
	if($totalPages == 0)
	{
		$amChartDataSource = '';
	}

	//$totalPages = count($mostVisitedPages);
	//$json_result = '{"numberOfCatalogVisits": '. $actualNumOfVisits .
    //              ' ,"totalNumberOfPageVisits": ' . $totalNumberOfPageVisits . '}';
	//$json_result = json_encode($amChartDataSource);
   
   print_r($amChartDataSource);
?>
