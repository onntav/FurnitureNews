<?php
   include "db/pdo.php";
   //include "db/CatalogVisit.php";
   //include "db/PageVisit.php";
   session_start();
   //error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Catalogue</title>
	
	<link href="./css/redsqr.css" rel="stylesheet" type="text/css" />
	<link href="./css/styles.css" rel="stylesheet" type="text/css" />
	<link href="./css/colorbox.css" rel="stylesheet" type="text/css" />
	<link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <script type='text/javascript' src="./js/jquery-1.9.1.min.js"></script> 	
    <script type='text/javascript' src="./js/dragresize.js"></script>   
	<script type='text/javascript' src="./js/jquery.colorbox.js"></script>
	<script type='text/javascript' src="./js/redsqr.js"></script>
	<script type='text/javascript' src="./js/fullmode.js"></script>
	<script type='text/javascript' src="./js/jquery.popitup.js"></script>
    <script type='text/javascript' src="./js/jquery-ui.js"></script>
	<script type='text/javascript' src="./js/jquery.touchwipe.js"></script>
</head>

<style type="text/css">
	#crop {
		width: 100px;
		height: 100px;
		overflow: hidden;
	}

	.popit-wrapper{
		display: none;
		border: 1px solid #ccc;
		background: #fff;
		border-radius: 5px;
		box-shadow: 0px 0px 6px 2px #ccc;
	}
	
	.popit-content{
		padding: 20px;
	}
	
	.popit-header{
		border-bottom: 1px solid #ccc;
	}
	
	.popit-body{
		padding: 20px 0;
	}
	
	.popitup-overlay{
		background: #000;
		position: fixed;
		z-index: 9999;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		opacity: 0.5;
	}
	
	.margin-top-40{
		margin-top: 40px;
	}
	
	.list-group-item{
		border: 0;
	}
	
	.edit-box{
		position:absolute;
		background-color:#2C353F;
		color:#E4E8EC;
		font-family:Arial, Helvetica, sans-serif;
		top:0px;
		margin-left: auto;
		margin-right: auto;
		left: 0;
		right: 0;
	}
	
	.cat-container{
		position:absolute;
		width:1220px;
		height:700px;
		margin-left: auto;
		margin-right: auto;
		margin-top: 10px;
		left: 0;
		right: 0;
	}
	
	.cat-container-iphone{
		position:absolute;
		width:50% !important;
		height:700px;
		margin-left: auto;
		margin-right: auto;
		left: 0;
		right: 0;
	}
	
	.main-container{
		position:relative;
		width:1200px;
		height:700px;
		margin-left: auto;
		margin-right: auto;
		left: 0;
		right: 0;
	}
	
	.main-container-iphone{
		position:relative;
		width:50% !important;
		height:700px;
		margin-left: auto;
		margin-right: auto;
		left: 0;
		right: 0;
	}
	
	
	.catalog-overlay{
		position: absolute;
		width: 130%;
		height: 700px;
		margin-left: auto;
		margin-right: auto;
		left: 0;
		top: 39;
		background-color: green;
		z-index: 99999;
	}
	
	.ruler-pages{
		width:35%;height:45px;
		position:absolute; 
		top:-90;
		margin-left: auto; 
		margin-right: auto; 
		border: 1px solid #a1a1a1;
		left: 0; 
		right: 0; 
		text-align: center;
		background-color:#D2D2D2;
		margin-top:10px; 
		padding-top: 10px;
		border-radius: 10px;
	}
	
	.red-square-options{
		width:35%;height:45px;
		position:relative; 
		margin-left: auto; 
		margin-right: auto; 
		left: 0; 
		right: 0; 
		text-align: center;
		margin-top:0px; 
		padding-top: 0px;
	}
	
	.bordered{
		border: 1px solid #DBDBDB;
	}
    
    .highlight-left{
        border-left-width: 3px;
        border-right-width: 1px;
        border-top-width: 3px;
        border-bottom-width: 3px;
        border-style: solid;
        border-color: #508C3C;
        display:block;
    }
    
    .highlight-right{
        border-left-width: 1px;
        border-right-width: 3px;
        border-top-width: 3px;
        border-bottom-width: 3px;
        border-style: solid;
        border-color: #508C3C;
        display:block;
        padding-left: -2px;
        margin-left: -2px;
    }
	
	.bordered-light{
		border: 1px solid #EDEDED;
        clear:both;
	}
    
    .pageGroup{
        margin: 2px;
        clear: both;
    }
    
    .advLoadingPageLeft{
        margin-top: 2px;
        margin-right: 0px;
        margin-bottom: 2px;
        margin-left: 2px;
        border-left-width: 1px;
        border-right-width: 0px;
        border-top-width: 1px;
        border-bottom-width: 1px;
        border-style: solid;
        border-color: #DBDBDB;
        display:inline-flex;
    }
    
    .advLoadingPageRight{
        margin-top: 2px;
        margin-right: 6px;
        margin-bottom: 2px;
        margin-left: 0px;
        border-left-width: 1px;
        border-right-width: 1px;
        border-top-width: 1px;
        border-bottom-width: 1px;
        border-style: solid;
        border-color: #DBDBDB;
        display:inline-flex;
    }
	
	.page-dimension-default{
		float:left;
		position: relative;
		width: 600px;
	}
	
	.page-dimension-iphone{
		float:left;
		position: relative;
		width: 50%;
	}
	
</style>

<?php
	//ini_set('display_errors',1);
	// Load cat-config.xml file and get parameters from there
	$viewOnEditMode = 'false';
	$isDraggable = 'true';
	$catConfigXML = simplexml_load_file('cat-config.xml') or createXmlCatConfig();
	//print_r($catConfigXML);
	$editMode = 0;
	$pageCnt = 0;
	$pageCnt = $catConfigXML["pageCount"];
	$editMode = $catConfigXML["editMode"];
	$offRedsqr = $catConfigXML["offRedsqr"];
	$isDraggable = $catConfigXML["draggable"] or 'true';
	$allowZoom = $catConfigXML["allowZoom"] or 'true';
	
	$biRepo = new BIRepository();
	$catVisitId = isset($_SESSION['CatVisitId']) ? $_SESSION['CatVisitId'] : 0;
  
	$_IsButtonClicked = false;
  
	$isEdit = $editMode;
	$nav = (isset($_GET['nav']) && $_GET['nav'] != '') ? $_GET['nav'] : $nav = '';	  
	$customerId = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : $customerId = '';	  
	$numOfVisitsStr = "NumberOfVisits";
	
	$isCustomerValid = false;
	
	if(strlen($customerId))
		$isCustomerValid = $biRepo->isValidCustomerId($customerId);
	
	/* this will reset the page_no = 1 */
	if($nav == '')
	{
		if ($isEdit == 0) 
		{
			$_SESSION['page_no'] = 1;
		}
		//echo $_SERVER['SERVER_ADDR'];
		if(($_GET["pageNumber"] == 1) && (!$_GET["nav"]))
		{
			$catalogVisit = new CatalogVisit;
			$catalogVisit->IPAddress =  getenv('HTTP_CLIENT_IP')?:
										getenv('HTTP_X_FORWARDED_FOR')?:
										getenv('HTTP_X_FORWARDED')?:
										getenv('HTTP_FORWARDED_FOR')?:
										getenv('HTTP_FORWARDED')?:
										getenv('REMOTE_ADDR');
			
			if(isCustomerValid)
				$catalogVisit->CustomerId = $customerId;
			
			$bis = $biRepo->insertCatalogVisit($catalogVisit);
			$catVisitId = $bis;
			$_SESSION['CatVisitId'] = $catVisitId;
			incrementPageVisitCounter($pageno_, $pageCnt, $biRepo, $_SESSION['CatVisitId']);
		}
	}
	
	function incrementPageVisitCounter($pageNumber, $pageCnt, $connection, $catVisitId)
	{
		if($pageNumber != null)
		{
			$pageVisit = new PageVisit;
			$pageVisit->CatVisitId = $catVisitId;
			$pageVisit->PageNumber = $pageNumber;
			$bis = $connection->insertPageVisit($pageVisit);
			
			$partnerPage = $pageNumber;
			
			if($pageNumber % 2 == 0)
			{
				$partnerPage = $pageNumber - 1;
			}
			else
			{
				$partnerPage = $pageNumber + 1;
			}
			
			$pageVisit = new PageVisit;
			$pageVisit->CatVisitId = $catVisitId;
			$pageVisit->PageNumber = $partnerPage;
			$bis = $connection->insertPageVisit($pageVisit);
		}
	}
	
	function getBIDataLineString($varName, $value)
	{
		return $varName . "=" . $value . PHP_EOL;
	}
	
	function getPageData($lineText)
	{
		return substr($lineText, strpos($lineText,"=") + 1);
	}
	
	function getBIData($file, $varName)
	{
		while(!feof($file)) 
		{
			$lineText = fgets($file);
			$varPosition = strpos($lineText, $varName);
			
			if ($varPosition > -1)
			{
				$value = substr($lineText, strpos($lineText,"=") + 1);
				return $value;
			}
		}
	}
	
	function getPageNumber($fileLineContent) 
	{
		if($fileLineContent != null && $fileLineContent != '')
		{
			//echo "<br\> file content: " . $fileLineContent;
			if(strpos($fileLineContent, "Page") > -1)
			{
				$pNum = 0;
				$pNum = substr($fileLineContent, 4, strpos($fileLineContent, "=") - 4);
				if($pNum != null && $pNum != '')
				{
					$pNum = (int)$pNum;
				}
				
				return $pNum;
			}
			else
			{
				return 0;
			}
		}
	}
  
	$view_mode = 'imgOpa pageGroup';   
	$edit_mode = 'drsElement drsMoveHandle';	

	if($nav == 'left')
	{
		$pageno_ = $_SESSION['page_no'] - 2;

		$_SESSION['page_no'] = $pageno_;
		$pageno_ = $_SESSION['page_no'];
	}
	else
	{
		if (isset($_SESSION['page_no']))	
		{
			$pageno_ = $_SESSION['page_no'];
		}
	}

	if($nav == 'right')
	{
		$pageno_ = $_SESSION['page_no'] + 2; 
		
		$_SESSION['page_no'] = $pageno_;  
		$pageno_ = $_SESSION['page_no'];
	}
	else
	{
		if (isset($_SESSION['page_no']))	
		   $pageno_ = $_SESSION['page_no'];
	}
		 
	if($nav == "" && isset($_GET["pageNumber"]))
	{
	   if($_GET["pageNumber"] == $pageCnt)
	   {
			$pageno_ = $_GET["pageNumber"] - 1; 
	   }
	   else
	   {
		   $pageno_ = $_GET["pageNumber"] % 2 == 0 ? $_GET["pageNumber"] - 1 : $_GET["pageNumber"]; 
	   }
	   $_SESSION['page_no'] = $pageno_;
	}
	   
	if($nav == "first")
	{
		$_SESSION['page_no'] = 1;
		$pageno_ = $_SESSION['page_no']; 
	}
	   
	if($nav == "last"){
		$_SESSION['page_no'] = $pageCnt -1;
		$pageno_ = $_SESSION['page_no']; 
	}
	   
	if($pageno_ > $pageCnt)
	{
		unset( $_SESSION['page_no']);
		$_SESSION['page_no'] = 1;
		$pageno_ = $_SESSION['page_no'];
	} 
	else if ($pageno_ < 1)
	{
		$_SESSION['page_no'] = $pageno_ + $pageCnt; //to offset -1 set to 3
		$pageno_ = $_SESSION['page_no'];
	}
	
	if(isset($_SESSION['CatVisitId']) && $_SESSION['CatVisitId'] != null)
	{
		incrementPageVisitCounter($pageno_, $pageCnt, $biRepo, $_SESSION['CatVisitId']);
	}
   
	if ($isEdit == 1) 
	{ 
		$_SESSION['page_no_restore'] = $pageno_;
	}
?>


<script type="text/javascript">
    var imgDragged = "";
	var zooming = false;
	var pageN = 0;
	var pageCount = 0;
	var initialLoadPixelRatio;
	var initialDimension;
	var isEditMode = 0;
	
	$(document).on("contextmenu",function(e){
		if( e.button == 2 ) {
			e.preventDefault();
		}
		return true;
	});
	
	var inactivityTime = function () {
		var t;
		window.onload = resetTimer;
		
		// DOM Events
		document.onmousemove = resetTimer;
		document.onkeypress = resetTimer;
		document.onmousedown = resetTimer; // touchscreen presses
		document.ontouchstart = resetTimer;
		document.onclick = resetTimer;     // touchpad clicks
		document.onscroll = resetTimer;    // scrolling with arrow keys
		document.onkeypress = resetTimer;

		function logout() {
			location.href = 'default.php';
		}

		function resetTimer() {
			clearTimeout(t);
			t = setTimeout(logout, 600000 ); // 10 mins
			// 1000 milisec = 1 sec
		}
	};

	$(document).ready(function(){
		initialDimension = getPageDimension();
		//inactivityTime();
		var myElement = document.getElementById('#leftPage');
		pageCount = parseInt('<?php echo $pageCnt; ?>');
		isEditMode = parseInt('<?php echo $isEdit; ?>')

		pageN = parseInt(getUrlParameter("pageNumber"));
		
		$("#leftPage").touchwipe({
			wipeRight: handleSwipeRight,
			min_move_x: 20,
			min_move_y: 20,
			preventDefaultEvents: false
		});
		
		$("#rightPage").touchwipe({
			wipeLeft: handleSwipeLeft,
			min_move_x: 20,
			min_move_y: 20,
			preventDefaultEvents: false
		});
		
		$(window).on('touchstart', function(ev) {
			if(ev.touches != null && ev.touches.length > 1){
				zooming = true;		
			}
		});
		
		$('#redSqrAddMode').click(function(e){
			var page = $('#pageNumber').val();
			if(hasExistingRedSquares()){
				addNewClonedRedSquare();
			}else{
				window.location.href = "default.php?nav=&newRedSq=1&pageNumber=" + page;
			}
		});
		
		// $('#redSqrEditMode').click(function(e){
			// var page = $('#pageNumber').val();
			// window.location.href = "default.php?nav=&emode=" + isEditMode + "&pageNumber=" + page;
		// });
		
		$(window).on("touchend", function(ev) {
			zooming = false;	
		});
		
		
        
        $('#leftPageContainer').css('height', $('#leftPage').height());
        $('#rightPageContainer').css('height', $('#rightPage').height());
        
		if(window.location.href.indexOf("pageNumber") == -1){
			if(window.location.href.indexOf("?") == -1){
				window.location.href = window.location.href + "?pageNumber=1";
			}else{
				window.location.href = window.location.href + "&pageNumber=1";
			}
		}
        
        $(document).on('touchstart', '.advance-loading', function(e) {
            window.location.href = $($(this).parent()).attr("href");
        });
        
        $(document).on('touchstart', '.navigation-button', function(e) {
            window.location.href = $($(this).parent()).attr("href");
        });
		
		
		//Examples of how to assign the Colorbox event to elements
		
		$('#pages').click(function(){
			$('.popit-wrapper.page-search').popitup({
			  autoClose: true,
			  overlayColor   :  "#000",
			  overlayOpacity :  "0.5",
			  fixedModal     :  false
			});
            
            $('.popit-wrapper.page-search').css('top',$('.ruler-pages').position().top - 29);
		});
		
		$('#btnRedSqLink').click(function(){
			$('.popit-wrapper.add-link').popitup({
			  autoClose: true,
			  overlayColor   :  "#000",
			  overlayOpacity :  "0.5",
			  fixedModal     :  false
			});
            
			debugger;
			if(lastSelectedRedSqr != null && typeof (lastSelectedRedSqr) != 'undefined'){
				var redSqLink = getRedSquareLink(lastSelectedRedSqr);
				var pageNumber = getInternalLinkPageNumber(redSqLink);
				if(pageNumber != null && pageNumber.length > 0){
					pageNumber = parseInt(pageNumber);
					$('#rdSqPageNumber').val(pageNumber);
				}else{
					$('#rdSqPageNumber').val('');
				}
			}
			
            $('.popit-wrapper.add-link').css('top',$('.ruler-pages').position().top - 85);
		});
		
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){ 			
			return false;
		});	
		
		$("#cboxPhoto").click(function() {
			alert('unload...');
		} );			
		
		$("#pageNumber").keyup(function(e){
			e.preventDefault();
			if(e.keyCode == 13)
			{
				gotoPage();
			}
		});
	});	
	
	function autoResizeDiv()
	{
		$('body').width = window.innerWidth +'px';
	}
	
	function isZoomed(){
		
		//if(/iPhone/i.test(navigator.userAgent) ) {
			//return false;
		//}

		// get current dimension
		var currentDim = getPageDimension();
		
		var zoomed = initialDimension.Width != currentDim.Width || initialDimension.Height != currentDim.Height;
		return zoomed;
	}	
	
	function getScreenPixelRatio(){
		return (window.outerWidth - 8) / window.innerWidth;
	}
	
	function getPageDimension(){
		var ratio = window.devicePixelRatio || 1;
		var dimension = {Width: window.innerWidth * ratio, Height: window.innerWidth * ratio};
		return dimension;
	}
	
	function handleSwipeLeft(e){
		if(!isZoomed()){ 
			var newPg = pageN;
			
			if(pageN % 2 == 0){
				newPg = pageN + 1;
			}else{
				newPg = pageN + 2;
			}
			
			if(newPg > pageCount){
				newPg = 1;
			}
			
			gotoPage(newPg);
		}
		
		zooming = false;
	}
	
	function handleSwipeRight(e){	
		if(!isZoomed()){ 
			var newPg = pageN;
			
			if(pageN % 2 == 0){
				newPg = pageN - 2;
			}else{
				newPg = pageN - 1;
			}
			
			if(newPg == 0){
				newPg = pageCount;
			}
			
			gotoPage(newPg);
		}
		
		zooming = false;
	}
	
	function isPage100Percent(){
		alert("window width:" + $(window).width());
		alert("screen width:" + screen.width);
		return $(window).width() == screen.width;
	}
	
	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
	};

	function gotoPage(pageNmbr){
		
		if(pageNmbr === undefined){
			pageNmbr = null;
		}
		
		var pageNum = 0;
		
		if(pageNmbr != null && typeof pageNmbr != "undefined"){
			pageNum = pageNmbr;			
		}else
			pageNum = $('#pageNumber').val();{
		}

		var maxPages = $('#pageNumber').attr("max");
		
		if(pageNum < 1){
			alert("Page number must be greater than zero.");
			return;
		}
		
		if(parseInt(pageNum) > parseInt(maxPages)){
			alert("Page number must not be greater than " + maxPages + ".");
			return;
		}
		
		window.location.href = 'default.php?nav=&pageNumber=' + pageNum + '&emode=' + isEditMode;
	}
	
	function addLinkToRedSq(pageNmbr){
		if(pageNmbr === undefined){
			pageNmbr = null;
		}
		
		var pageNum = 0;
		
		if(pageNmbr != null && typeof pageNmbr != "undefined"){
			pageNum = pageNmbr;			
		}else
			pageNum = $('#rdSqPageNumber').val();{
		}

		var maxPages = $('#rdSqPageNumber').attr("max");
		
		if(pageNum < 1){
			alert("Page number must be greater than zero.");
			return;
		}
		
		if(parseInt(pageNum) > parseInt(maxPages)){
			alert("Page number must not be greater than " + maxPages + ".");
			return;
		}
		
		setSelectedRedSquareTitle('default.php?nav=&pageNumber=' + pageNum);
	}
	
	function handlePageClick(event){
		if(event.target.parentElement.href != null && typeof event.target.parentElement.href != 'undefined' 
			&& event.target.parentElement.href.indexOf("default.php") >= 0){
			event.preventDefault();
			event.stopImmediatePropagation();
			return false;
		}
	}
	
	$(function() {
		$('.imgOpa').each(function() {
			$(this).hover(
				function() {
					$(this).stop().animate({ opacity: 0.6 }, 200);  //1.0
				},
			   function() {
				   $(this).stop().animate({ opacity: 0.01 }, 800);
			   })
			});
	});
	
	
	//<![CDATA[

	// Using DragResize is simple!
	// You first declare a new DragResize() object, passing its own name and an object
	// whose keys constitute optional parameters/settings:

	var dragresize = new DragResize('dragresize', { minWidth: 10, minHeight: 10, minLeft: 0, minTop: 10, maxLeft: 1600, maxTop: 1024 });

	// Optional settings/properties of the DragResize object are:
	//  enabled: Toggle whether the object is active.
	//  handles[]: An array of drag handles to use (see the .JS file).
	//  minWidth, minHeight: Minimum size to which elements are resized (in pixels).
	//  minLeft, maxLeft, minTop, maxTop: Bounding box (in pixels).

	// Next, you must define two functions, isElement and isHandle. These are passed
	// a given DOM element, and must "return true" if the element in question is a
	// draggable element or draggable handle. Here, I'm checking for the CSS classname
	// of the elements, but you have have any combination of conditions you like:

	dragresize.isElement = function(elm)
	{
		if (elm.className && elm.className.indexOf('drsElement') > -1) return true;
	};
	
	dragresize.isHandle = function(elm)
	{
		if (elm.className && elm.className.indexOf('drsMoveHandle') > -1) return true;
	};

	// You can define optional functions that are called as elements are dragged/resized.
	// Some are passed true if the source event was a resize, or false if it's a drag.
	// The focus/blur events are called as handles are added/removed from an object,
	// and the others are called as users drag, move and release the object's handles.
	// You might use these to examine the properties of the DragResize object to sync
	// other page elements, etc.

	dragresize.ondragfocus = function() { };
	dragresize.ondragstart = function(isResize) { };
	dragresize.ondragmove = function(isResize) { };
	dragresize.ondragend = function(isResize) { };
	dragresize.ondragblur = function() { };

	// Finally, you must apply() your DragResize object to a DOM node; all children of this
	// node will then be made draggable. Here, I'm applying to the entire document.
	dragresize.apply(document);
	//]]>
	
	function getDivCoords()
	{
		var divTag = document.getElementsByClassName("drsElement");

		var elmX = 0;
		var elmY = 0;
		var elmW = 0;
		var elmH = 0;
		var coords = "";
		var i = 0;
		
		for(i=0;i<divTag.length;i++) {
			var url = getCleanUrl($(divTag[i]).find('.url').html());
			elmX = divTag[i].style.left;
			elmY = divTag[i].style.top;
			elmW = divTag[i].offsetWidth;
			elmH = divTag[i].offsetHeight;	
			
			if(parseInt(divTag[i].id)){
				coords += '&lt;btn ' + 'link=&quot;' + url + '&quot;' + ' seq=&quot;'+ divTag[i].id + '&quot;' + ' x=&quot;' + 
				elmX +'&quot; y=&quot;' + elmY + '&quot; w=&quot;' + 
				elmW +'px&quot; h=&quot;' + elmH + 'px&quot; ' + '/&gt;' + '\n';
	  
			}
		}

		document.getElementById("xmlData").value = coords;
		document.getElementById("pageUrl").value = url;
		return false;
	}

	function allowDrop(event) {
		event.preventDefault();
	}    
    
	function drag(event) {
        if(<?php echo $allowZoom ?>){
		    imgDragged = $(event.target).attr("src");
        }else{
            imgDragged = $(event.target).children()[0].attr("src");
        }
	}
	
	function drop(event, navLeftRight, isDraggable) {
		event.preventDefault();
        
		if (isDraggable == 'true') {
            var dirArray = null;
            
            var leftImgSrcArray = $('#leftPage').attr("src").split('/');
            var rightImgSrcArray = $('#rightPage').attr("src").split('/');
            
            var draggedDirArray = imgDragged.split('/');
            var draggedPageNum = getPageNumber(draggedDirArray);
            
            if(navLeftRight == 'right'){
                
                if(draggedPageNum == getPageNumber(rightImgSrcArray))
                    return false;
                    
                dirArray = leftImgSrcArray;    
            }else{
                
                if(draggedPageNum == getPageNumber(leftImgSrcArray))
                    return false;
                    
                dirArray = rightImgSrcArray;
            }
            
            var pageNumberDragged = getPageNumber(dirArray);
			
            if(pageNumberDragged % 2 == 0){
                // right page is being dragged
                if(navLeftRight == 'left'){
                    window.location = 'default.php?nav=right&emode=' + isEditMode + '&pageNumber=' + (pageNumberDragged + 1);        
                }
            } else {
                // left page is being dragged
                if(navLeftRight == 'right'){
                    window.location = 'default.php?nav=left&emode=' + isEditMode + '&pageNumber=' + ((pageNumberDragged - 2 < 1) ? <?php echo($pageCnt) ?> : (pageNumberDragged - 2));
                }
            }
			
		} else {
			alert('Drag and drop navigation is not available. Please set draggable to true and try again. Thank you.');
		}
	}
    
    function getPageNumber(dirArray){
        var pageImg = dirArray[dirArray.length -1];
        var pageNumberDraggedFileName = pageImg.split('.')[0];
        var pageNumberDragged = parseInt(pageNumberDraggedFileName.split('i')[1]);
        return pageNumberDragged;
    }
	
</script>
	

<body oncontextmenu="return false">
	<form id="frmEditor" name="frmEditor" action="write_xml_pages.php" method="post" onsubmit="getDivCoords();" enctype="multipart/form-data">
		<input name="yAxis" size="6" style="font-size: 1px;visibility: hidden;" />
		<input name="xmlData" id="xmlData" style="font-size: 1px;visibility: hidden;" />
		<input name="pageNo" id="pageNo" value="<?php echo $pageno_; ?>" style="font-size: 1px;visibility: hidden;" />
		<input name="pageUrl" id="pageUrl" style="font-size: 1px;visibility: hidden;" />
		<input name="addNewRedsqr" id="addNewRedsqr" value="<btn page='0' id='0' link='http://productlink/default'/>" style="font-size: 1px;visibility: hidden;" />
		 <?php
			if ($editMode == 1) {
		 ?>
				 <div class="edit-box">
					<input name="btnGenerate" type="submit" id="btnGenerate" value="Save Changes" title="Save Changes" onclick="return confirm('Continue to ' + this.value +'?');"/>
					<a id="redSqrAddMode" href="#" style="color:#FFD47F;"><img id="addRs" src="./images/add.jpg" style="border:none;height:12px;" />New Red Sq</a>&nbsp;&nbsp;
					<!--<a id="redSqrEditMode" href="#" style="color:#FFD47F;"><img id="editRs" src="./images/edit.jpg" style="border:none;height:12px;" />Edit Mode</a>&nbsp;&nbsp;-->
					<input type="checkbox" id="cloneRedsqr" name="cloneRedsqr" onclick="javascript:enableCloneRedsqr();" >Clone redSqr's	
					<input type="checkbox" id="editRedsqr" name="editRedsqr" onclick="javascript:enableEditRedsqr();" >Edit redSqr's	
					<input type="checkbox" id="destroyRedsqr" name="destroyRedsqr" onclick="javascript:enableDestroyRedsqr();" >Delete redSqr's
					&nbsp;&nbsp;| Enter product link: <input type="text" id="productLink" value="http://domain-name/product-id" onkeyup="javascript:syncURLValue()" size="98" />	   
				 </div>
		<?php
			}
		?> 
		
		<div id="cat-overlay" class="catalog-overlay" style="display:none;"></div>
		
		<div id="divContainer" class="cat-container">
			<div id="main" class="main-container">
				<!-- navigate left page-->
				<?php
					$pageLeft = './pages/pHi'.$pageno_.'.jpg';
				?>
				
				<!-- LEFT PAGE of the CAT -->
				<div id="leftPageContainer" class="page-dimension-default" onclick="getXyLocation(event);" 
                    ondrop="drop(event, 'left', '<?php echo $isDraggable; ?>')" ondragover="allowDrop(event)" ondragstart="drag(event)">
					<a class="pageGroup" onclick="handlePageClick(event)" href="<?php if($allowZoom =='true'){ echo $pageLeft; ?> <?php } else { echo '#';} ?>" >	
						<img id="leftPage" class="bordered-light" src="<?php echo $pageLeft; ?>" style="width: 600px;height: 700px;" />
					</a>
					<div id="nav_first_page" style="position:relative; float: left;top:-420px;z-index:2020" 
						ondrop="drop(event, 'left', '<?php echo $isDraggable; ?>')" ondragover="allowDrop(event)" ondragstart="return false;">
						<a href="default.php?nav=<?php echo 'first&emode='. $isEdit . '&pageNumber=1'?>">
						<img id="ImagePrev" class="navigation-button" src="./images/iv_left_start_arrow.png" align="left" style="border:none;padding-right:5px;" /></a>
					</div>
					<div id="nav_left" style="position:relative; float: left;top:-420px;z-index:2020" 
						ondrop="drop(event, 'left', '<?php echo $isDraggable; ?>')" ondragover="allowDrop(event)" ondragstart="return false;"> <!-- left:-60px; -->
						<a href="default.php?nav=<?php echo 'left&emode='. $isEdit . '&pageNumber='?><?php if($pageno_ <=1 ){echo $pageCnt; ?><?php }else{echo $pageno_- 2;} ?>">
						<img id="Image1" class="navigation-button" src="./images/iv_left_next_arrow.png" align="left" style="border:none;" /></a>
					</div> 
				 
					<input id="xy" type="text" value="testing" style="width: 200px;visibility: hidden;" />
				 </div>
			 
				<!-- RIGHT PAGE of the CAT -->
				<?php
					$pageRight = './pages/pHi'.($pageno_ + 1).'.jpg';
				?>	 
				<div id="rightPageContainer" class="page-dimension-default" onclick="getXyLocation(event);" 
                    ondrop="drop(event, 'right', '<?php echo $isDraggable; ?>')" ondragover="allowDrop(event)" ondragstart="drag(event)">
					<a class="pageGroup" onclick="handlePageClick(event)" href="<?php if($allowZoom =='true'){ echo $pageRight; ?> <?php } else { echo '#';} ?>" >	
						<img id="rightPage" class="bordered-light" src="<?php echo $pageRight; ?>" style="width: 600px;height: 700px;" />
					</a>	
					<div id="nav_last_page" style="position:relative; float: right;top:-420px;z-index:2020" 
						ondrop="drop(event, 'right', '<?php echo $isDraggable; ?>')" ondragover="allowDrop(event)" ondragstart="return false;" >
						<!-- left:60px; -->
						<a href="default.php?nav=<?php echo 'last&emode='. $isEdit . '&pageNumber=' . $pageCnt ?>">
						<img id="Image2" class="navigation-button" src="./images/iv_right_end_arrow.png" align="right" style="border:none;" /></a>
					</div>
					<div id="nav_right" style="position:relative; float: right;top:-420px;z-index:2020" 
						ondrop="drop(event, 'right', '<?php echo $isDraggable; ?>')" ondragover="allowDrop(event)" ondragstart="return false;"> <!-- left:60px; -->
						<a href="default.php?nav=<?php echo 'right&emode='. $isEdit . '&pageNumber='?><?php if($pageno_ >= $pageCnt){echo '1'; ?><?php }else{echo $pageno_+ 2;} ?>">
						<img id="Image2" class="navigation-button" src="./images/iv_right_next_arrow.png" align="right" style="border:none;padding-right:5px;" /></a>
					</div>
				</div>	
			</div>
			<br/>
			
			<?php
				if($isEdit == 1)
				{
			?>
					<div class="red-square-options">
						<input id="btnRedSqLink" type="button" value="Add Internal Link" title="Add internal link"/>
					</div>
					<div class="popit-wrapper add-link">
						<div class="popit-content">
							<text>Page&nbsp;</text>
							<input id="rdSqPageNumber" type="number" style="width:60px;" 
									onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
									min="1" max="<?php echo $pageCnt ?>" 
									value="<?php echo $_GET["pageNumber"] ?>" autofocus="autofocus"/>
							<input id="btnAddLink" type="button" value="Apply" title="Add link" onclick="addLinkToRedSq()"/>
						</div>
					</div>
			<?php
				}
			?>
			
			<div class="ruler-pages">
				<input id="pages" type="text" style="height:20px; text-align:center;" readonly 
					value="<?php echo $pageno_ ?> - <?php echo $pageno_ + 1 ?>   |  <?php echo $pageCnt + 1 ?>" />
			</div>
			
			<div class="popit-wrapper page-search">
				<div class="popit-content">
					<input id="pageNumber" type="number" style="width:60px;" 
							onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
							min="1" max="<?php echo $pageCnt ?>" 
							value="<?php echo $_GET["pageNumber"] ?>" />
					<input id="btnGo" type="button" value="Go" title="Go to page" onclick="gotoPage()"/>
				</div>
			</div>
			
			<!-- ADVANCED LOADING PAGES -->
			<div style="float:right;width:1220px;display:inline-flex;padding-left:11px; margin-top: 80px;">
				<?php
					// get the navigation type first, left, right, last
					$nav = (isset($_GET['nav']) && $_GET['nav'] != '') ? $_GET['nav'] : $nav = '';	  
			
					// get all the settings for advance loading
                    $backwardAdvanceLoadingPages = intval($catConfigXML['BackwardAdvanceLoadingPages']);
                    $forwardAdvanceLoadingPages = intval($catConfigXML['ForwardAdvanceLoadingPages']);
                    $highlightCurrentlyDisplayedPages = intval($catConfigXML['HighlightCurrentlyDisplayedPages']);
					
					// render the advance loading section
                    renderAdvanceLoadingPages($pageno_, $pageCnt, $backwardAdvanceLoadingPages, $forwardAdvanceLoadingPages, $highlightCurrentlyDisplayedPages);
                    
                    // display advance loading pages
                    function renderAdvanceLoadingPages($pageno_, $pageCnt, $backwardAdvanceLoadingPages, $forwardAdvanceLoadingPages, $highlightCurrentlyDisplayedPages)
                    {
                         // render backward advance loading pages
                         // -------------------------------------
                         $startPage = $pageno_ - (($pageno_ % 2 == 0) ? 2 : 1);
                         $backwardAdvLoadingPages = intval($pageno_ - $backwardAdvanceLoadingPages);
                         $i = $startPage;
                         
                         if($backwardAdvLoadingPages >= 1)
                         {
                            for ($i=$backwardAdvLoadingPages;$i < $pageno_;$i++)
                            {
                                displayAdvanceLoadingPage($i);
                            }    
                         }   
                         else
                         {
                            // Define two variables to split the possible pages to be loaded in advance
                            $advanceLoadingPagesSplit1 = 0;
                            $advanceLoadingPagesSplit2 = 0;
                            
                            // Take note that there are special cases like the value of $advanceLoadingPages that goes beyond the total number of pages of the catalog
                            // and in this case, there are no longer pages to be displayed so we need to split them as described below:
                            // Example:
                            // If you have a page count of 48 and the catalogue is currently displaying page 40 and the advanceLoadingPages being set in the config is 10.
                            // Computations would be:
                            // $advanceLoadingPages = 40 + 10    which will become 50 and 50 is already greater than 48, the pagecount value
                            // in this case we need to split the pages into two different scenarios and get the following two group of pages as follows:
                            // Pages 41, 42, 43, 44, 45, 46, 47, 48  and then followed by
                            // Pages 1, 2.  Take note that the next page of page 48 is Page 1.
                            
                            $advanceLoadingPagesSplit1 = 1;
                            $advanceLoadingPagesSplit2 = intval($backwardAdvLoadingPages + $pageCnt);
                            
                            // Display second group of pages
                            if($advanceLoadingPagesSplit2 > 0)
                            {
                                $j = intval($advanceLoadingPagesSplit2);
                                for (;$j<=intval($pageCnt);$j++)
                                {
                                    displayAdvanceLoadingPage($j);
                                }	
                            }
                            
                            // Display first group of pages
                            for ($i = 1;$i<$pageno_;$i++)
                            {
                                displayAdvanceLoadingPage($i);
                            }	
                         }                      
                         
                         // render currently displayed pages
                         // -------------------------------------
                         displayAdvanceLoadingPage($pageno_, true);
                         displayAdvanceLoadingPage($pageno_ + 1, true);
                         
                         // render forward advance loading pages
                         // -------------------------------------
                         $startPage = $pageno_ + (($pageno_ % 2 == 0) ? 1 : 2);
                         $i = $startPage;
						 // compute the highest page number based on what is placed in the config.
						 // this will determine the last page to be loaded
						 $forwardAdvLoadingPages = $startPage + $forwardAdvanceLoadingPages - 1;
                         
						 // Define two variables to split the possible pages to be loaded in advance
						 $advanceLoadingPagesSplit1 = 0;
						 $advanceLoadingPagesSplit2 = 0;
						 
						 // Take note that there are special cases like the value of $advanceLoadingPages that goes beyond the total number of pages of the catalog
						 // and in this case, there are no longer pages to be displayed so we need to split them as described below:
						 // Example:
						 // If you have a page count of 48 and the catalogue is currently displaying page 40 and the advanceLoadingPages being set in the config is 10.
						 // Computations would be:
						 // $advanceLoadingPages = 40 + 10    which will become 50 and 50 is already greater than 48, the pagecount value
						 // in this case we need to split the pages into two different scenarios and get the following two group of pages as follows:
						 // Pages 41, 42, 43, 44, 45, 46, 47, 48  and then followed by
						 // Pages 1, 2.  Take note that the next page of page 48 is Page 1.
						 if($forwardAdvLoadingPages <= $pageCnt)
						 {
							$advanceLoadingPagesSplit1 = $forwardAdvLoadingPages;
						 }
						 else
						 {
							$advanceLoadingPagesSplit1 = $pageCnt;
							$advanceLoadingPagesSplit2 = $forwardAdvLoadingPages - $pageCnt;
						 }
						 
						// Display first group of pages
						for (;$i<=$advanceLoadingPagesSplit1;$i++)
						{
							displayAdvanceLoadingPage($i);
						}	
						 
						 // Display second group of pages
						if($advanceLoadingPagesSplit2 > 0)
						{
							$j = 1;
                            
							for (;$j<=$advanceLoadingPagesSplit2;$j++)
							{
								displayAdvanceLoadingPage($j);
							}	
						}
                    }
					 
					// This function displays the specific image for the specified page number
					function displayAdvanceLoadingPage($pageNumber, $isCurrentPage = false) {
						$img = './pages/pHi'.$pageNumber.'.jpg';
                        $href = './default.php?pageNumber=' . $pageNumber;
						 ?>
						 <a class="<?php if($pageNumber % 2 == 1) { echo "advLoadingPageLeft"; } else { echo "advLoadingPageRight"; } ?>" href="<?php echo $href; ?>" >
							<img ondragstart="return false;" class="advance-loading <?php if($isCurrentPage) { if($pageNumber % 2 == 1) { echo "highlight-left"; } else { echo "highlight-right"; }} else { echo ""; } ?>" src="<?php echo $img; ?>" style="width: 60px;height: 70px; "/>
						 </a>  
						 <?php
					}
                
				 ?>
			</div>

			<?php
				unset($i);
				unset($j);
				
				$divId = 0;
				$pageNoLeft = $pageno_;
				$pageNoRight = $pageno_ + 1;
				
				$page = 'pages'.$pageNoLeft.'_'.$pageNoRight.'.xml';
				
				//$pages = simplexml_load_file($page);
				
				if($isEdit)
				{
					if(isset($_GET["newRedSq"]))
					{
						if($_GET["newRedSq"])
						{
							$pages = createDefaultXmlPages($pageNoLeft.'_'.$pageNoRight); //die("Error: Cannot create object from XML file ".$page);
							$pages = simplexml_load_file($page);
						}
					}
					else
					{
						$pages = simplexml_load_file($page) or createDefaultXmlPagesWithNoRedSq($pageNoLeft.'_'.$pageNoRight);
					}
				}
				else
				{
					$pages = simplexml_load_file($page) or createDefaultXmlPagesWithNoRedSq($pageNoLeft.'_'.$pageNoRight);
				}
				
				if ($offRedsqr != 1) 
				{
					foreach ($pages as $pagesinfo)
					{
							
						$page=$pagesinfo['page'];
						$link=$pagesinfo['link'];
						$coordinates ='';
						
						if($pagesinfo['w'] == "" || $pagesinfo['w']==NULL)
						{
							$pagesinfo['w'] = '256px';
						}
									
						if($pagesinfo['h'] == "" || $pagesinfo['h']==NULL)
						{
							$pagesinfo['h'] = '256px';
						}
						
						if($pagesinfo['x'] == "" || $pagesinfo['x']==NULL)
						{
							$pagesinfo['x'] = '320px';			
						}
							
						if($pagesinfo['y'] == "" || $pagesinfo['y']==NULL)
						{
							$pagesinfo['y'] = '320px';
						}
						
						$coordinates='width:'.$pagesinfo['w'] .';height:'.$pagesinfo['h'] .';left:'. $pagesinfo['x'] .';top:'.$pagesinfo['y'].';';
					
						$url = 'link=&quot;'.$link.'&quot;';
						$divId++;	    
			?>

						<div id="<?php echo $divId;?>" class="<?php if($isEdit == 1){echo $edit_mode;} else { echo $view_mode;} ?>" title="<?php if($isEdit == 1) echo $url; else echo ""; ?>" onclick="<?php if($isEdit == 1) echo 'javascript:destroyEditCloneDiv(this.id)'; else echo 'javascript:redirectPage(this.id)'; ?>"
							  style="position:float;background-color:#CC3333;border:solid 0px #F8CB7F;z-index:2000;<?php echo $coordinates;?>"
							  href="<?php if($pagesinfo['x'] < 600) { echo $pageLeft; } else { echo $pageRight; } ?>">		  
								
							<?php
								if($isEdit == 1){
								   echo '<p align="center" class="url" style="font-family:Times New Roman,Georgia,Serif;color:#FFFFFF;">'.$url.'</p>';
								}else{
									echo '<p align="center" class="url" style="font-family:Times New Roman,Georgia,Serif;color:#FFFFFF;display:none;">'.$url.'</p>';
								} 
							?>  								
						</div>
						
				<?php
					}
				}
			?>			
		
		</div>
    </form>

</body> 

<?php
	unset($isEdit);
	unset($nav);
	unset($pageno);
	//unset($pageno_);
	
	function createDefaultXmlPages($xmlPageName) 
	{		
		$urlDoc = "page";
		$fileName = "pages".$xmlPageName;         
	   
		$xmlBeg = "<?xml version='1.0' encoding='utf-8'?>"; 

		$rootELementStart = "<$urlDoc>";
		
		$xmlContent = "<btn link='http://productlink/default'/>";

		$rootElementEnd = "</$urlDoc>";

		$xml_document= $xmlBeg; 

		$xml_document .= $rootELementStart;
		
		$xml_document .= stripslashes(normalizedXMLTags($xmlContent));
		
		$xml_document .= $rootElementEnd;     

		$path_dir = $fileName.".xml";

		/* Data in Variables ready to be written to an XML file */
		
		$fp = fopen($path_dir,'w');

		fwrite($fp,$xml_document);
		//if (fwrite($fp,$xml_document)) 
			//echo "writing=".$xmlPageName;
		//else 
			//echo "Problem creating page config.";
	  
		fclose($fp); 
		/* Loading the created XML file to check contents */

		$sites = simplexml_load_file("$path_dir");
	}
	
	function createDefaultXmlPagesWithNoRedSq($xmlPageName) 
	{		
		$urlDoc = "page";
		$fileName = "pages".$xmlPageName;         
	   
		$xmlBeg = "<?xml version='1.0' encoding='utf-8'?>"; 

		$rootELementStart = "<$urlDoc>";

		$rootElementEnd = "</$urlDoc>";

		$xml_document= $xmlBeg; 

		$xml_document .= $rootELementStart;
		
		//$xml_document .= stripslashes(normalizedXMLTags($xmlContent));
		
		$xml_document .= $rootElementEnd;     

		$path_dir = $fileName.".xml";

		/* Data in Variables ready to be written to an XML file */
		
		$fp = fopen($path_dir,'w');

		fwrite($fp,$xml_document);
		
		//if (fwrite($fp,$xml_document)) 
			//echo "writing=".$xmlPageName;
		//else 
			//echo "Problem creating page config.";
	  
		fclose($fp); 
		/* Loading the created XML file to check contents */

		$sites = simplexml_load_file("$path_dir");
	}
	   
	function createXmlCatConfig() 
	{
		$rootElement = "config";
		$fileName = "cat-config";         

		$xmlContent = "<property advanceLoadingPages='8'/>";
	   
		$xmlBeg = "<?xml version='1.0' encoding='utf-8'?>"; 

		$rootELementStart = "<$rootElement>";

		$rootElementEnd = "</$rootElement>";

		$xml_document= $xmlBeg; 

		$xml_document .= $rootELementStart;
		
		$xml_document .= stripslashes(normalizedXMLTags($xmlContent));
		
		$xml_document .= $rootElementEnd;       

		$path_dir = $fileName.".xml";

		/* Data in Variables ready to be written to an XML file */
		
		$fp = fopen($path_dir,'w');

		//if (fwrite($fp,$xml_document)) 
		//	echo "writing=".$fileName;
		//else 
		//	echo "writing=Error";
	  
		fclose($fp); 
		
		/* Loading the created XML file to check contents */

		$sites = simplexml_load_file("$path_dir");
	}
	
	function createBIDataFile($pageCnt)
	{
		$fileName = "BI_data";         

		$fileContent = "NumberOfVisits=1" . PHP_EOL;
		//echo "Page count: " . $pageCnt;
		for ($x = 1; $x <= $pageCnt; $x++) 
		{
			$fileContent = $fileContent . getBIDataLineString("Page" . $x, 0);
		} 

		$path_dir = "BI_module/" . $fileName.".txt";

		/* Data in Variables ready to be written to an XML file */
		
		$fp = fopen($path_dir,'w');
		
		fwrite($fp,$fileContent); 
	  
		fclose($fp); 
	}
	   
   function normalizedXMLTags($xml)
   {
	  $xml = str_replace ( '&amp;', '&', $xml );
	  $xml = str_replace ( '&#039;', '\'', $xml );
	  $xml = str_replace ( '&quot;', '"', $xml );
	  $xml = str_replace ( '&lt;', '<', $xml );
	  $xml = str_replace ( '&gt;', '>', $xml );
	  return $xml;
   }		
?>
</html>

