<?php
   include "../db/pdo_new.php";
   session_start();
   //error_reporting(1);
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>BI Management</title>
    <meta name="viewport" content="width=device-width">
	<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../css/chart-style.css" rel="stylesheet" type="text/css">
	<script src="../js/justgage-core.js"></script>
    <script src="../js/justgage.js"></script>
	<script
	  src="https://code.jquery.com/jquery-3.1.1.js"
	  integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
	  crossorigin="anonymous"></script>
	<script src="jquery-ui.js"></script>
	<link href="jquery-ui.min.css" rel="stylesheet" type="text/css" />
</head>

<style type="text/css">
	body {
		font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
		margin: 0; 
		padding: 0;
		text-align: center; 
	}
	
	.gauge{
		left: -20px;
	}
	
	svg{
		width:110%;
	}

</style>

<body>
	<?php
	    //ini_set('display_errors', 1);
		$xml = simplexml_load_file('BI-management-config.xml');
		$pageTitle = $xml->pagetitle;
	?>
	<div class="title" style="text-align:center;">
		<h1><?php echo $pageTitle; ?></h1>
	</div>
	<?php
		if(count($xml->catalogues->catalogue) > 0)
		{
			$catConfigXML = simplexml_load_file('../cat-config.xml');
			$numberOfVisits = $catConfigXML['NumberOfVisits'] or '100';
			
			echo '<center><div style="width:95%; text-align:center; margin: auto;">';
			echo 	'<table style="width: 100%; margin: auto;">';
			echo 		'<tbody>';
			for ($x = 0; $x <= count($xml->catalogues->catalogue) - 1; $x++) {
				$catImage = $xml->catalogues->catalogue[$x]->image;
				$name = $xml->catalogues->catalogue[$x]->name;
				$link = $xml->catalogues->catalogue[$x]->link;
				$biLink = $xml->catalogues->catalogue[$x]->bilink;
				$logo = $xml->catalogues->catalogue[$x]->logo;
				$dbname = $xml->catalogues->catalogue[$x]->db_name;
				renderCatalog($catImage, $name, $link, $biLink, $logo, $dbname, $x+1, $numberOfVisits);
			} 
			
			echo 	'</table>';
			echo '</div></center>';
		}
		else
		{
			echo '<div style="text-align:center; padding-top: 20px;font-weight:bold;>No catalog available. Ensure that you have setup catalogue details in the BI Management config file. Please contact administrator.</div>';
		}
		
		function renderCatalog($catImage, $name, $link, $biLink, $logo, $dbname, $guageCtr, $numberOfVisits)
		{
			$biRepo = new BIRepository();
			$actualNumOfVisits = $biRepo->getTotalCatalogVisit($dbname);
				
			echo
			'<script>'.
				'var numberOfVisits'. $guageCtr .' = ' . $numberOfVisits . ';' .
				'var actualNumOfVisits'. $guageCtr .' = ' . $actualNumOfVisits . ';' .
				'document.addEventListener("DOMContentLoaded", function(event) {
					var gg'. $guageCtr . ' = new JustGage({
						id: "gg'. $guageCtr . '",
						value: actualNumOfVisits'. $guageCtr .',
						min: 0,
						max: numberOfVisits'. $guageCtr .',
						gaugeWidthScale: 0.6,
						counter: true,
						formatNumber: true
					});
				});
			</script>';
			
			echo '<tr style="height: 250px;">';
			
			echo 
			'<td width="30%"><div style="text-align:center;">'.
				'<img src="' . $catImage . '" alt="' . $name . '" style="width:100px;height:100px;">'.
			'</div></td>';
			
			echo 
			'<td width="30%" style="text-align:center;">'.
				'<div style="width:260px;">'.
					'<div style="text-align:center; padding-top: 5px;font-weight:bold; padding-bottom: 20px;">'. $name .'</div>'.
					'<div style="width: 100%;">'.
						'<div style="text-align:center;float:left;">'.
							'<a href="'. $link .'" target="_blank">' .
								'<input type="button" value="Show Catalog" style="border-radius:5px;padding-left:10px; padding-right:10px;"></input>' .
							'</a>'.
						'</div>'.
						'<div style="text-align:center;float:right;">'.
							'<a href="'. $biLink .'" target="_blank">' .
								'<input type="button" value="Show Catalog BI" style="border-radius:5px;padding-left:10px; padding-right:10px;"></input>'.
							'</a>' .
						'</div>'.
					'</div>'.
				'</div>'.
			'</td>';
			
			echo 
			'<td width="30%"><div style="text-align:center;">'.
				'<div id="speedometerContainer" class="gauge-container col-md-12 col-sx-12">'.
					'<div id="gg'. $guageCtr . '" class="gauge col-md-12 col-sx-12"></div>'.
				'</div>'.
			'</div></td>';
			echo '</tr>';
		}
		
	?>
	<!--<div class="title" style="text-align:center; padding-top: 40px;">
		<input type='button' click="location.reload(true);" value="Reload Results"></input>
	</div>-->
</body>

</html>