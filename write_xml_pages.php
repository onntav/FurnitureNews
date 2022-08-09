<?php

$page = $_POST['pageNo'];
//$pageUrl = $_POST['pageUrl'];
$page_ = $_POST['pageNo']+1; 
$xmlData = $_POST['xmlData'];
$addNewRedsqr = $_POST['addNewRedsqr'];
if ($addNewRedsqr != '') {
  //$xmlData .= $addNewRedsqr;
} 

writeXmlPages_eb($page."_".$page_, $xmlData, $page);

  function writeXmlPages_eb($xmlPageName, $xmlContent, $page) {
       //echo "writing xml pages..." . $xmlPageName;
        $urlDoc = "page";
        $fileName = "pages".$xmlPageName;         
	   
        $xmlBeg = "<?xml version='1.0' encoding='utf-8'?>"; 

        $rootELementStart = "<$urlDoc>";

        $rootElementEnd = "</$urlDoc>";

        $xml_document= $xmlBeg; 

        $xml_document .= $rootELementStart;
		
		/* pages */
		//$xml_document .=  "<btn id='$id'/>";	
		
        $xml_document .= stripslashes(normalizedXMLTags($xmlContent));
		$xml_document = str_replace('&', '&amp;', $xml_document);
		
        $xml_document .= $rootElementEnd;
        
		//file_put_contents("myxmlfile.xml", $xml_document); //advance writing of xml file or etc...

        //$path_dir = "./xml_pages_eb/";
		//$path_dir = "xml_pages_eb/";

        $path_dir = $fileName.".xml";

		/* Data in Variables ready to be written to an XML file */
        
		$fp = fopen($path_dir,'w');

        //$write = fwrite($fp,$xml_document);
		if (fwrite($fp,$xml_document)) echo "writing=".$xmlPageName;
		else echo "writing=Error";
      
	    fclose($fp); 
	/* Loading the created XML file to check contents */

	$sites = simplexml_load_file("$path_dir");

	//print_r($sites);
	// return "writing=Done.";

	return header("Location: default.php?nav=&emode=1&pageNumber=".$page);
   }
   
   function normalizedXMLTags($xml){
   	
	  $xml = str_replace ( '&amp;', '&', $xml );
	  $xml = str_replace ( '&#039;', '\'', $xml );
	  $xml = str_replace ( '&quot;', '"', $xml );
	  $xml = str_replace ( '&lt;', '<', $xml );
	  $xml = str_replace ( '&gt;', '>', $xml );
   	  return $xml;
   }
   
   
   
?>
