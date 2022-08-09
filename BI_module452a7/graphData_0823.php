<?php
	include "../db/pdo_new.php";
	session_start();
	error_reporting(1);
   
    $catConfigXML = simplexml_load_file('../cat-config.xml');
	$ipAddress = $_POST['IpAddress'];
	$visitId = $_POST['VisitId'];
	
	//echo $ipAddress;
	$biRepo = new BIRepository();
	//$totalNumberOfPageVisits = $biRepo->getTotalPageVisitsPerIP($ipAddress);
	$numberOfMostVisitedPagesToShow = $catConfigXML['NumberOfMostVisitedPagesToShow'] or 0;
	
	$mostVisitedPages = $biRepo->getMostVisitPagesPerIP($numberOfMostVisitedPagesToShow, $ipAddress, $visitId);
	
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
