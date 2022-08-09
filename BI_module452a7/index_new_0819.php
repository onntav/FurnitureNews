<?php
   include "../db/pdo_new.php";
   session_start();
   //error_reporting(1);
   
   $catConfigXML = null;
	//print_r($catConfigXML);
	$pageCnt = 0;
	$pageCnt = 0;
	$numberOfVisits = 100;
	$lengthOfVisit = 100;
	$lengthOfVisit = 100;
	$numberOfMostVisitedPagesToShow = 0;
	$displayPerUserBI = 1;
	$groupPageVisitsByIpAddress = 0;
	
	$actualNumOfVisits = 0;
	$totalNumberOfPageVisits = 0;
	$averagePageLength = 0;
	
	$biRepo = new BIRepository();
	$actualNumOfVisits = 0;
	$totalNumberOfPageVisits = 0;
	$mostVisitedPages = 0;
	//print_r($mostVisitedPages);
	
	$amChartDataSource = '';
	$totalPages = 0;
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Business Intelligence</title>
    <meta name="viewport" content="width=device-width">
    <style>
	body {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		padding-left: 2%;
		padding-right: 2%;
	}
    .container {
        width: 100%;
        margin: 0 auto;
        text-align: center;
    }

    .gauge {
        width: 300px;
        height: 300px;
    }

    a:link.button,
    a:active.button,
    a:visited.button,
    a:hover.button {
        margin: 30px 5px 0 2px;
        padding: 7px 13px;
    }
	
	.gauge-container{
		
	}
	
	
	.title{
		text-align: center;
		padding-bottom: 50px;
	}
	
	.center{
		text-align: center;
		padding-top: 0px;
		padding-left: 25px;
		margin-top: 0px;
		top: -250px;
		position: relative;
		z-index: 10000;
	}
	
	.most-visited-pages{
		text-align: center;
		padding-top: 0px;
		padding-left: 25px;
		margin-top: 0px;
		top: -425px;
		position: relative;
		z-index: 10000;
	}
	
	.tab-content {
		border-left: 1px solid #ddd;
		border-right: 1px solid #ddd;
		padding: 10px;
	}

	.nav-tabs {
		margin-bottom: 0;
	}
	
    </style>
	<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../css/chart-style.css" rel="stylesheet" type="text/css">
	<script src="../js/justgage-core.js"></script>
    <script src="../js/justgage.js"></script>
	<script src="../js/amcharts.js" type="text/javascript"></script>
	<script src="../js/serial.js" type="text/javascript"></script>
	<script
	  src="https://code.jquery.com/jquery-3.1.1.js"
	  integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
	  crossorigin="anonymous"></script>
	<script src="jquery-ui.js"></script>
	<script src="datatables.min.js"></script>
	<link href="jquery-ui.min.css" rel="stylesheet" type="text/css" />
	<link href="jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<?php
	    //ini_set('display_errors', 1);
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
		
		$biRepo = new BIRepository();
		$actualNumOfVisits = $biRepo->getTotalCatalogVisit();
		$totalNumberOfPageVisits = $biRepo->getTotalPageVisits();
		$mostVisitedPages = $biRepo->getMostVisitPages($numberOfMostVisitedPagesToShow);
		//print_r($mostVisitedPages);
		
		$amChartDataSource = 'var chartData = [';
		$totalPages = count($mostVisitedPages);
		$cnt = 0;
		foreach ($mostVisitedPages as $page)
		{
			$cnt++;
			$amChartDataSource .= '{"catalog": "Page ' . $page['PageNumber'].'",';
			$amChartDataSource .= '"visits": ' . $page['VisitCount'] . '}';
			if($cnt == $totalPages)
			{
				$amChartDataSource .= '];';
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
		
		//echo $amChartDataSource;
		
		$averagePageLength = ceil($totalNumberOfPageVisits / ($actualNumOfVisits > 0 ? $actualNumOfVisits : 1));
	?>
	<div class="title">
		<h2>Business Intelligence system</h2>
	</div>
	
	<!-- Tabs -->
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">BI Main</a></li>
			<?php if($displayPerUserBI == 1){ 
				echo '<li><a href="#tabs-2">Per User BI</a></li>';
			} ?>
		</ul>
		<div id="tabs-1">
			<div id="speedometerContainer" class="gauge-container col-md-12 col-sx-12">
				<div id="gg1" class="gauge col-md-6 col-sx-6">
				<h3 class="center">Number of Visits<h3>
				</div>
				<div id="gg2" class="gauge col-md-6 col-sx-6">
				<h3 class="center">Ave pages visited</h3>
				</div>
				<div style="margin-top: 50px; width:100%; margin-left: auto; margin-right: auto;">
					<div id="chartContainer" class="gauge-container col-md-12 col-sx-12" style="margin-top: 100px;">
						<div id="chartdiv" style="width: 70%; margin-left: auto; margin-right: auto; height: 400px;"></div>
						<h3 class="most-visited-pages">Most visited pages<h3>
					</div>
				</div>
			</div>
			
			<script>
				var numberOfVisits = <?php echo $numberOfVisits; ?>;
				var lengthOfVisit = <?php echo $lengthOfVisit; ?>;
				var actualNumOfVisits = <?php echo $actualNumOfVisits; ?>;
				var averagePageLength = <?php echo $averagePageLength ?>;
				
				document.addEventListener("DOMContentLoaded", function(event) {
					var gg1 = new JustGage({
						id: "gg1",
						value: actualNumOfVisits,
						min: 0,
						max: numberOfVisits,
						gaugeWidthScale: 0.6,
						counter: true,
						formatNumber: true
					});
					
					var gg2 = new JustGage({
						id: "gg2",
						value: averagePageLength,
						min: 0,
						max: lengthOfVisit,
						gaugeWidthScale: 0.6,
						counter: true,
						formatNumber: true
					});
					
					/* var gg3 = new JustGage({
						id: "gg3",
						value: actualNumOfVisits,
						min: 0,
						max: numberOfVisits,
						gaugeWidthScale: 0.6,
						counter: true,
						formatNumber: true
					});
					
					var gg4 = new JustGage({
						id: "gg4",
						value: averagePageLength,
						min: 0,
						max: lengthOfVisit,
						gaugeWidthScale: 0.6,
						counter: true,
						formatNumber: true
					}); */
				});
			</script>
			
			<script>
				var chart;
				var chartForPopup;
				var chartDataForPopup;
				<?php echo $amChartDataSource; ?>

				AmCharts.ready(function () {
					// SERIAL CHART
					chart = new AmCharts.AmSerialChart();
					chart.dataProvider = chartData;
					chart.categoryField = "catalog";
					chart.startDuration = 1;

					// AXES
					// category
					var categoryAxis = chart.categoryAxis;
					categoryAxis.labelRotation = 90;
					categoryAxis.gridPosition = "start";

					// value
					// in case you don't want to change default settings of value axis,
					// you don't need to create it, as one value axis is created automatically.

					// GRAPH
					var graph = new AmCharts.AmGraph();
					graph.valueField = "visits";
					graph.balloonText = "[[category]]: <b>[[value]]</b>";
					graph.type = "column";
					graph.lineAlpha = 0;
					graph.fillAlphas = 0.8;
					chart.addGraph(graph);

					// CURSOR
					var chartCursor = new AmCharts.ChartCursor();
					chartCursor.cursorAlpha = 0;
					chartCursor.zoomable = false;
					chartCursor.categoryBalloonEnabled = false;
					chart.addChartCursor(chartCursor);

					chart.creditsPosition = "top-right";

					chart.write("chartdiv");
				});
				
				$( document ).ready(function() {
					//$( "#tabs" ).tabs();
					var table = null;
					$('#speedometers').hide();
					
					 $( "#tabs" ).tabs({
						beforeActivate: function( event, ui ) {
							var tabIndex = ui.newTab.index();
							if(tabIndex == 0)
							{
								document.addEventListener("DOMContentLoaded", function(event) {
									var gg1 = new JustGage({
										id: "gg1",
										value: actualNumOfVisits,
										min: 0,
										max: numberOfVisits,
										gaugeWidthScale: 0.6,
										counter: true,
										formatNumber: true
									});
									
									var gg2 = new JustGage({
										id: "gg2",
										value: averagePageLength,
										min: 0,
										max: lengthOfVisit,
										gaugeWidthScale: 0.6,
										counter: true,
										formatNumber: true
									});
									
									/* var gg3 = new JustGage({
										id: "gg3",
										value: actualNumOfVisits,
										min: 0,
										max: numberOfVisits,
										gaugeWidthScale: 0.6,
										counter: true,
										formatNumber: true
									});
									
									var gg4 = new JustGage({
										id: "gg4",
										value: averagePageLength,
										min: 0,
										max: lengthOfVisit,
										gaugeWidthScale: 0.6,
										counter: true,
										formatNumber: true
									}); */
								});
								
								AmCharts.ready(function () {
									// SERIAL CHART
									chart = new AmCharts.AmSerialChart();
									chart.dataProvider = chartData;
									chart.categoryField = "catalog";
									chart.startDuration = 1;

									// AXES
									// category
									var categoryAxis = chart.categoryAxis;
									categoryAxis.labelRotation = 90;
									categoryAxis.gridPosition = "start";

									// value
									// in case you don't want to change default settings of value axis,
									// you don't need to create it, as one value axis is created automatically.

									// GRAPH
									var graph = new AmCharts.AmGraph();
									graph.valueField = "visits";
									graph.balloonText = "[[category]]: <b>[[value]]</b>";
									graph.type = "column";
									graph.lineAlpha = 0;
									graph.fillAlphas = 0.8;
									chart.addGraph(graph);

									// CURSOR
									var chartCursor = new AmCharts.ChartCursor();
									chartCursor.cursorAlpha = 0;
									chartCursor.zoomable = false;
									chartCursor.categoryBalloonEnabled = false;
									chart.addChartCursor(chartCursor);

									chart.creditsPosition = "top-right";
								});
								
								chart.write("chartdiv");
							}
							else if(tabIndex === 1)
							{
								if(!table)
								{	
									table = $('#tblPerUserBI').DataTable( {
										processing: true,
										serverSide: true,
										ajax: {
											url: 'biData.php',
											type: 'POST'
										},
										"columnDefs": [ {
											"targets": -1,
											"data": null,
											"defaultContent": "<button>Details</button>"
										}]
									} );
								 
									/* $('#tblPerUserBI tbody').on( 'click', 'button', function () {
										//var data = table.row( $(this).parents('tr') ).data();
										$('#speedometers').show();
										$('#speedometers').dialog({width:1024, height:$("#tabs-1").height(), modal: true});
										$('#speedometers').css("width", "100%");
										$('.ui-dialog-title').html("Per Customer Details");
										$('#gg3 svg').css("overflow", "visible");
										$('#gg4 svg').css("overflow", "visible");
									}); */
							
							
									$('#tblPerUserBI tbody').on( 'click', 'button', function () {
										//var data = table.row( $(this).parents('tr') ).data();
										var ipAddress = $(this).parents('tr').find("td:eq(1)").text();
										//alert("test");
										$.ajax({
											type: 'POST',
											url: 'speedometerData.php',
											data: ({IpAddress: ipAddress}),
											//contentType: "application/json; charset=utf-8",
											//dataType: "json",
											success: function(data) {
												//var result = data;
												var result = JSON.parse(data);
												//alert("numberOfCatalogVisits: " + result.numberOfCatalogVisits);
												//alert("totalNumberOfPageVisits: " + result.totalNumberOfPageVisits);
												
												$('#gg3 svg').remove();
												$('#gg4 svg').remove();
												
												var gg3 = new JustGage({
													id: "gg3",
													value: result.numberOfCatalogVisits,
													min: 0,
													max: numberOfVisits,
													gaugeWidthScale: 0.6,
													counter: true,
													formatNumber: true
												});
												
												var gg4 = new JustGage({
													id: "gg4",
													value: result.totalNumberOfPageVisits,
													min: 0,
													max: lengthOfVisit,
													gaugeWidthScale: 0.6,
													counter: true,
													formatNumber: true
												});
												
												// get the graph data
												$.ajax({
													type: 'POST',
													url: 'graphData.php',
													data: ({IpAddress: ipAddress}),
													//contentType: "application/json; charset=utf-8",
													//dataType: "json",
													success: function(data) {
														var result = data;
														//var result = JSON.parse(data);
														
														//alert(result);
														if(result != null && result.length > 0){
															chartDataForPopup = eval(result);
															refreshGraph(chartDataForPopup);
															chart.write("chartdiv2");
														}
													}
												});
											}
										});
			
										$('#speedometers').show();
										$('#speedometers').dialog({width:1024, height:$("#tabs-1").height(), modal: true});
										$('#speedometers').css("width", "100%");
										$('.ui-dialog-title').html("Per Customer Details");
										$('#gg3 svg').css("overflow", "visible");
										$('#gg4 svg').css("overflow", "visible");	
											centerSpeedometers();
										});
							
									/* table = $('#tblPerUserBI').DataTable( {
										processing: true,
										serverSide: true,
										ajax: {
											url: 'biData_new.php',
											type: 'POST'
										},
										"columnDefs": [ {
											"targets": -1,
											"data": null,
											"defaultContent": "<button>Details</button>"
										}]
									} );
								 
									$('#tblPerUserBI tbody').on( 'click', 'button', function () {
										//var data = table.row( $(this).parents('tr') ).data();
										var ipAddress = $(this).parents('tr').find("td:eq(1)").text();
										
										$.ajax({
											type: 'POST',
											url: 'biDataPerIP.php',
											data: ({IpAddress: ipAddress}),
											success: function() {
												alert("AJAX call succedded.");
											}
										});
			
										$('#speedometers').show();
										$('#speedometers').dialog({width:1024, height:$("#tabs-1").height(), modal: true});
										$('#speedometers').css("width", "100%");
										$('.ui-dialog-title').html("Per Customer Details");
										$('#gg3 svg').css("overflow", "visible");
										$('#gg4 svg').css("overflow", "visible");
									
											
											centerSpeedometers();
										});
										
										centerSpeedometers();
									}); */
								}
								
								chart.write("chartdiv2");
							}
						}
					 });
					
					resizeMainTabHeight();
					
					centerSpeedometers();
					
					$( window ).resize(function() {
						resizeMainTabHeight();
						centerSpeedometers();
					});
				});
				
				function refreshGraph(chartDataSource){
					// SERIAL CHART
					chart = new AmCharts.AmSerialChart();
					chart.dataProvider = chartDataSource;
					chart.categoryField = "catalog";
					chart.startDuration = 1;

					// AXES
					// category
					var categoryAxis = chart.categoryAxis;
					categoryAxis.labelRotation = 90;
					categoryAxis.gridPosition = "start";

					// value
					// in case you don't want to change default settings of value axis,
					// you don't need to create it, as one value axis is created automatically.

					// GRAPH
					var graph = new AmCharts.AmGraph();
					graph.valueField = "visits";
					graph.balloonText = "[[category]]: <b>[[value]]</b>";
					graph.type = "column";
					graph.lineAlpha = 0;
					graph.fillAlphas = 0.8;
					chart.addGraph(graph);

					// CURSOR
					var chartCursor = new AmCharts.ChartCursor();
					chartCursor.cursorAlpha = 0;
					chartCursor.zoomable = false;
					chartCursor.categoryBalloonEnabled = false;
					chart.addChartCursor(chartCursor);

					chart.creditsPosition = "top-right";
				}
				
				function centerSpeedometers(){
					if($(window).width() < 800)
					{
						var containerWidth = $("#speedometers div#gg3").width();
						var ggWidth = $("#speedometers div#gg3 svg").width();
						var gg3LabelWidth = $("#gg3Label").width();
						var gg4LabelWidth = $("#gg4Label").width();
						
						var ggLeft = (containerWidth + ggWidth) / 2; 	
						var gg3LabelLeft = ((containerWidth + gg3LabelWidth) / 2) + 10; 	
						var gg4LabelLeft = ((containerWidth + gg4LabelWidth) / 2) + 10; 	
						
						$("div#gg3 svg").css({left:ggLeft});
						$("div#gg4 svg").css({left:ggLeft});
						
						$("div#gg3 svg").css({top:'30px'});
						$("div#gg4 svg").css({top:'30px'});
						
						$("#gg3Label").css({left:gg3LabelLeft});
						$("#gg4Label").css({left:gg4LabelLeft});
					}
					else{
						$('#gg3 svg').css("left", "-70");
						$('#gg4 svg').css("left", "-70");
					}
				}
				
				var speedometerHeight = $("#speedometerContainer").height();
				
				function resizeMainTabHeight(){
					var newSpeedometerHeight = $("#speedometerContainer").height();
					var mainTabHeight = $("#speedometerContainer").height();
					if(mainTabHeight < 200){
						mainTabHeight = speedometerHeight;
					}
					
					$("#tabs-1").css({
						height: mainTabHeight
					});
				}
			</script>
		</div>
		<?php
		if($displayPerUserBI == 1){ 
			echo '<div id="tabs-2">
				<table id="tblPerUserBI" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Visit Date/Time</th>
						<th>IP Address</th>';
						
						
							if($groupPageVisitsByIpAddress == 0)
							{
								echo "<th>Page #</th>";
								echo "<th>Visit Count</th>";
							}	
							else 
							{
								echo "<th>Pages Count</th>";	
							}
					
				echo       '<th>Action</th>
						</tr>
					</thead>
				</table>
				<div id="speedometers" class="gauge-container col-md-12 col-sx-12">
					<div id="gg3" class="gauge col-md-6 col-sx-6">
					<h3 id="gg3Label" class="center">Number of Visits<h3>
					</div>
					<div id="gg4" class="gauge col-md-6 col-sx-6">
					<h3 id="gg4Label" class="center">Average no of pages</h3>
					</div>
					<div style="margin-top: 50px; width:100%; margin-left: auto; margin-right: auto;">
						<div class="gauge-container col-md-12 col-sx-12" style="margin-top: 50px;">
							<div id="chartdiv2" style="width: 70%; margin-left: auto; margin-right: auto; height: 400px;"></div>
							<h3 class="most-visited-pages">Most visited pages<h3>
						</div>
					</div>
				</div>
			</div>';
		}
		?>
	</div>
</body>

</html>