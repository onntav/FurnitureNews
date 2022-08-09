<?php
   include "../db/pdo.php";
   session_start();
   //error_reporting(1);
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
	
    </style>
	<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../css/chart-style.css" rel="stylesheet" type="text/css">
	<script src="../js/justgage-core.js"></script>
    <script src="../js/justgage.js"></script>
	<script src="../js/amcharts.js" type="text/javascript"></script>
	<script src="../js/serial.js" type="text/javascript"></script>
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
			$amChartDataSource .= '{"country": "Page ' . $page['PageNumber'].'",';
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
		
		//echo $amChartDataSource;
		
		$averagePageLength = ceil($totalNumberOfPageVisits / ($actualNumOfVisits > 0 ? $actualNumOfVisits : 1));
	?>
	<div class="title">
		<h2>Business Intelligence system</h2>
	</div>
    <div class="gauge-container col-md-12 col-sx-12">
        <div id="gg1" class="gauge col-md-6 col-sx-6">
		<h3 class="center">Number of Visits<h3>
		</div>
		<div id="gg2" class="gauge col-md-6 col-sx-6">
		<h3 class="center">Average no. of pages visited</h3>
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
    });
    </script>
	
	<script>
            var chart;
			<?php echo $amChartDataSource; ?>
            // var chartData = [
                // {
                    // "country": "Page 1",
                    // "visits": 4025
                // },
                // {
                    // "country": "Page 2",
                    // "visits": 3000
                // },
                // {
                    // "country": "Page 3",
                    // "visits": 2000
                // },
                // {
                    // "country": "Page 10",
                    // "visits": 1500
                // },
                // {
                    // "country": "Page 20",
                    // "visits": 1122
                // },
                // {
                    // "country": "Page 50",
                    // "visits": 1114
                // },
                // {
                    // "country": "Page 52",
                    // "visits": 984
                // },
                // {
                    // "country": "Page 100",
                    // "visits": 711
                // },
                // {
                    // "country": "Page 200",
                    // "visits": 386
                // },
                // {
                    // "country": "Page 350",
                    // "visits": 384
                // },
                // {
                    // "country": "Page 351",
                    // "visits": 338
                // },
                // {
                    // "country": "Page 356",
                    // "visits": 328
                // }
            // ];


            AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "country";
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
        </script>
		<div style="margin-top: 50px; width:100%; margin-left: auto; margin-right: auto;">
			<div class="gauge-container col-md-12 col-sx-12" style="margin-top: 100px;">
				<div id="chartdiv" style="width: 70%; margin-left: auto; margin-right: auto; height: 400px;"></div>
				<h3 class="most-visited-pages">Most visited pages<h3>
			</div>
		</div>
</body>

</html>

