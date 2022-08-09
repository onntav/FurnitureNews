<?php
   include "../db/pdo.php";
   session_start();
   error_reporting(1);
   
   $sortColumnNum = $_POST[order][0][column];
   $sortColumn = '';
   
   switch($sortColumnNum)
   {
	    case "0":
			$sortColumn = "VisitDateTime";
			break;
		case "1":
			$sortColumn = "PageNumber";
			break;
		case "2":
			$sortColumn = "VisitCount";
			break;
   }
   
   $sortDir = $_POST[order][0][dir];
   $sortCriteria = ($sortColumnNum == "0") ? "" : $sortColumn . " " . $sortDir . " "; // retain the normal sorting when first column is selected
   $searchVal = $_POST[search][value];
   
   $catConfigXML = simplexml_load_file('../cat-config.xml');
	 $groupPageVisitsByIpAddress = $catConfigXML['GroupPageVisitsByIpAddress'] or 0;
  
   $biRepo = new BIRepository();
   $mostVisitedPages = $biRepo->getCatalogVisitsSummary($_POST[start], $_POST[length], $sortCriteria, $searchVal, $groupPageVisitsByIpAddress);
   $countMostVisitedPages = count($biRepo->getAllCatalogVisitsSummary($_POST[start], $_POST[length], $sortCriteria, $searchVal, $groupPageVisitsByIpAddress));
   $newArrayResult = array();
		
   for($i=0; $i < count($mostVisitedPages); $i++)
   {
		$pageVisit = $mostVisitedPages[$i];
    if($groupPageVisitsByIpAddress == 0)
    {
        $array = array($pageVisit['VisitDateTime'], $pageVisit['IpAddress'], $pageVisit['PageNumber'], $pageVisit['VisitCount'], $pageVisit['Id'], $pageVisit['CustomerId'] == 0 ? '' : $pageVisit['CustomerId']);
		    array_push($newArrayResult, $array);
    }
    else
    {
        $array = array($pageVisit['VisitDateTime'], $pageVisit['IpAddress'], $pageVisit['VisitCount'], $pageVisit['Id'], $pageVisit['CustomerId'] == 0 ? '' : $pageVisit['CustomerId']);
		    array_push($newArrayResult, $array);  
    }
   }

   $json_result = stripslashes(json_encode(array_values($newArrayResult)));
   $json_result = '{"recordsFiltered": '.count($newArrayResult) .
				  ' ,"recordsTotal": '. $countMostVisitedPages .
				  ' ,"draw": '. $_POST[draw] .
				  ' ,"iTotalDisplayRecords": ' . $countMostVisitedPages .
                  ' ,"data": ' . $json_result . '}';
   
   print_r($json_result);
?>
