	$(document).ready( function() {
	
		// When site loaded, load the Popupbox First
		//loadPopupBox();
	
		$('#popupBoxClose').click( function() {			
			unloadPopupBox();
		});
		
		$('#container').click( function() {
			unloadPopupBox();
		});

		function unloadPopupBox() {	// TO Unload the Popupbox
			$('#popup_box').fadeOut("slow");
			//$("#container").css({ // this is just for style		
			//	"opacity": "1"  
			//}); 
		}	
		
		function loadPopupBox() {	// To Load the Popupbox
		alert('test');
		//	$('#popup_box').fadeIn("slow");
			
			
			//var myScrollTo = 500;
			//window.scrollTo(0, myScrollTo);
			//$("#container").css({ // this is just for style
			//	"opacity": "0.3"  
			//}); 		
		}
		/**********************************************************/
		
	});