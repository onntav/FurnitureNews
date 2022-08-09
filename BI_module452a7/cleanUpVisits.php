<?php
	include "../db/pdo_new.php";
	session_start();
	error_reporting(1);
   
	$biRepo = new BIRepository();
	$result = $biRepo->deleteAllVisits();
   
   print_r($result);
?>
