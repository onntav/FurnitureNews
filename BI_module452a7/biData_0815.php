<?php
   include "../db/pdo.php";
   session_start();
   error_reporting(1);
   
   
   //debug_to_console("draw: " . $_POST[draw]);
   //debug_to_console("length: " . $_POST[length]);
   //echo "Page #:" . print_r($_POST[draw]);
   //echo "Page Size: " . print_r($_POST[length]);
   //echo "Order: " . $_POST[order][0][column];
   //echo "Order dir: " . $_POST[order][0][dir];
   //echo "Search value: " . $_POST[search][value];
   
   //print_r($_POST);
   
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
   
   //echo "Search value: " . $searchVal . "\n\r";
   
   $biRepo = new BIRepository();
   $mostVisitedPages = $biRepo->getCatalogVisitsSummary($_POST[start], $_POST[length], $sortCriteria, $searchVal);
   $countMostVisitedPages = count($biRepo->getAllCatalogVisitsSummary($_POST[start], $_POST[length], $sortCriteria, $searchVal));
   $newArrayResult = array();
		
   for($i=0; $i < count($mostVisitedPages); $i++)
   {
		$pageVisit = $mostVisitedPages[$i];
		$array = array($pageVisit['VisitDateTime'], $pageVisit['IpAddress'], $pageVisit['PageNumber'], $pageVisit['VisitCount']);
		array_push($newArrayResult, $array);
   }
   
   //$recordsFiltered = "recordsFiltered:".count($newArrayResult);
   //array_push($newArrayResult, $recordsFiltered);
   //array_push($newArrayResult, $_POST[length]);

   //print_r(json_encode(array_values($newArrayResult)));
   $json_result = stripslashes(json_encode(array_values($newArrayResult)));
   $json_result = '{"recordsFiltered": '.count($newArrayResult) .
				  ' ,"recordsTotal": '. $countMostVisitedPages .
				  ' ,"draw": '. $_POST[draw] .
				  ' ,"iTotalDisplayRecords": ' . $countMostVisitedPages .
                  ' ,"data": ' . $json_result . '}';
				  
	//$json_result = '{"data": ' . $json_result . '}';
   
   print_r($json_result);
?>
