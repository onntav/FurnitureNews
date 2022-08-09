<?php
   session_start();
   //error_reporting(1);
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>BI Management</title>
    <meta name="viewport" content="width=device-width">
	<link href="./css/styles.css" rel="stylesheet" type="text/css" />
    <style>
	</style>
</head>

<style type="text/css">
	body {
		font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
		margin: 0; 
		padding: 0;
		text-align: center; 
	}
	
	tr {
		border-bottom: 1px solid black;
	}

</style>

<body>
	<?php
	    //ini_set('display_errors', 1);
		$xml = simplexml_load_file('BI-management-config.xml');
		
		$pageTitle = $xml->pagetitle;
		//print_r($xml);
		//echo $xml->pagetitle;
	?>
	<div class="title" style="text-align:center;">
		<h1><?php echo $pageTitle; ?></h1>
	</div>
	<?php
		if(count($xml->catalogues->catalogue) > 0)
		{
			echo '<center><div style="width:95%; text-align:center; margin: auto;">';
			echo 	'<table style="width: 100%; margin: auto;">';
			/*echo		'<thead>';
			echo 			'<tr>'.
								'<th>Catalogue Name</th>'.
								'<th>Catalogue Link</th>'.
								'<th>BI Link</th>'.
								'<th>Business Intelligence</th>'.
							'</tr>'.
						'</thead>';*/
			echo 		'<tbody>';
			for ($x = 0; $x <= count($xml->catalogues->catalogue) - 1; $x++) {
				//echo $xml->catalogues->catalogue[$x]->name . "<br>";
				//echo $xml->catalogues->catalogue[$x]->link . "<br>";
				//echo $xml->catalogues->catalogue[$x]->bilink . "<br>";	
				//echo $xml->catalogues->catalogue[$x]->logo . "<br>";
				
				$catImage = $xml->catalogues->catalogue[$x]->image;
				$name = $xml->catalogues->catalogue[$x]->name;
				$link = $xml->catalogues->catalogue[$x]->link;
				$biLink = $xml->catalogues->catalogue[$x]->bilink;
				$logo = $xml->catalogues->catalogue[$x]->logo;
				
				renderCatalog($catImage, $name, $link, $biLink, $logo);
			} 
			
			echo 	'</table>';
			echo '</div></center>';
		}
		else
		{
			echo '<div style="text-align:center; padding-top: 20px;font-weight:bold;>No catalog available. Ensure that you have setup catalogue details in the BI Management config file. Please contact administrator.</div>';
		}
		
		function renderCatalog($catImage, $name, $link, $biLink, $logo)
		{
			echo '<tr style="height: 250px;border-bottom: 1px solid black;">';
			
			echo 
			'<td width="40%"><div style="text-align:center;">'.
				'<img src="' . $catImage . '" alt="' . $name . '" style="width:100px;height:100px;">'.
			'</div></td>';
			
			//echo '<td><div style="text-align:center; padding-top: 20px;font-weight:bold;">'. $name .'</div></td>';
			
			echo 
			'<td width="30%" style="text-align:center;">'.
				'<div style="width:240px;">'.
					'<div style="text-align:center; padding-top: 20px;font-weight:bold;">'. $name .'</div>'.
					'<div style="width: 100%;">'.
						'<div style="text-align:center;float:left;">'.
							'<a href="'. $link .'">' .
								'<input type="button" value="Show Catalog" style="border-radius:5px;padding-left:10px; padding-right:10px;"></input>' .
							'</a>'.
						'</div>'.
						'<div style="text-align:center;float:right;">'.
							'<a href="'. $biLink .'">' .
								'<input type="button" value="Show Catalog BI" style="border-radius:5px;padding-left:10px; padding-right:10px;"></input>'.
							'</a>' .
						'</div>'.
					'</div>'.
				'</div>'.
			'</td>';
			
			/*echo 
			'<td>'.
				'<div style="text-align:center;">'.
					'<a href="'. $biLink .'">' .
						'<input type="button" value="Show Catalog BI" style="border-radius:5px;padding-left:10px; padding-right:10px;"></input>'.
					'</a>' .
				'</div>'.
			'</td>';*/
			
			echo 
			'<td width="30%"><div style="text-align:center;">'.
				'<img src="' . $logo . '" alt="' . $name . '" style="width:154px;height:100px;">'.
			'</div></td>';
			echo '</tr>';
		}
		
	?>
	<!--<div class="title" style="text-align:center; padding-top: 40px;">
		<input type='button' click="location.reload(true);" value="Reload Results"></input>
	</div>-->
</body>

</html>