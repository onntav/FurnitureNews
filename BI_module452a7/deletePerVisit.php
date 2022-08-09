<?php
	include "../db/pdo.php";
	session_start();
	error_reporting(1);
   
	$biRepo = new BIRepository();
	
	$visitId = $_POST['VisitId'];
	
	$result = $biRepo->deletePerVisit($visitId);
   
   print_r($result);
?>
