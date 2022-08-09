	      var lastSelectedRedSqr = null;
		  var div = null;
		  
		  function destroyEditCloneDiv(divTag) {
           div = document.getElementById(divTag);	   
		   lastSelectedRedSqr = div;
           var countCheckbox = 0; 

			if (enableCloneRedsqr() == true) {
			   countCheckbox++;
			}		   

			if (enableEditRedsqr() == true) {
			   countCheckbox++;
			} 
		    
			if (enableDestroyRedsqr() == true) {
			   countCheckbox++;
			}

			if (countCheckbox == 2 || countCheckbox == 3) {
			   alert('Please select one option at a time!');
			   return false;
			}			
			
			if (enableDestroyRedsqr() == true) {
				if (confirm('Continue to delete Redsqr ?')) {		     		     
				   div.parentNode.removeChild(div);             
			   } else {
				 return false;
			   }
			}

			if (enableEditRedsqr() == true) {
				lastSelectedRedSqr = div;
				if (confirm('Continue to edit and apply the product link on Redsqr ?')) {
				   var productLink = document.getElementById("productLink");
				   // var redSqrLink = $(div).find("p.url").html();
				   // var arrURL = [];
				   var cleanUrl = getRedSquareLink(div);
				   
				   // //alert(redSqrLink);
				   // if(redSqrLink != null && typeof(redSqrLink) != 'undefined')
				   // {
					   // cleanUrl = getCleanUrl(redSqrLink);
				   // }
				   
				   $(productLink).val(cleanUrl);
				   div.setAttribute("align", "center");
				   div.style.color="#FFFFFF";				   
				   div.title = 'link="'+ cleanUrl +'"';
				   lastSelectedRedSqr = div;
			   } else {
				 return false;
			   }
			}

			if (enableCloneRedsqr() == true) {
				if (confirm('Continue to clone Redsqr ?')) {		     		     
				   var container = document.getElementById('divContainer');
				   //alert(container);
				   var clone = document.getElementById(div.id).cloneNode(true);;
				   //alert(clone);
				   var tmpId = new Date().getTime();
				   clone.id = tmpId;
				   container.appendChild(clone);				   
			   } else {
				 return false;
			   }			
			}
		}  
		
		function getRedSquareLink(redSq){
		   var redSqrLink = $(redSq).find("p.url").html();
		   var arrURL = [];
		   var cleanUrl = "";
		   
		   //alert(redSqrLink);
		   if(redSqrLink != null && typeof(redSqrLink) != 'undefined')
		   {
			   cleanUrl = getCleanUrl(redSqrLink);
		   }
		   return cleanUrl;
		}
		
		function getInternalLinkPageNumber(link){
			return getParameterByName('pageNumber', link);
		}
		
		function getParameterByName(name, url) {
			if (!url) {
			  url = window.location.href;
			}
			debugger;
			name = name.replace(/[\[\]]/g, "\\$&");
			var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
			regex.ignoreCase = true;
			url = url.replace(/&amp;/g, '&');
			var results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			
			return decodeURIComponent(results[2].replace(/\+/g, " "));
		}
		
		function redirectPage(divTag) {
           div = document.getElementById(divTag);	
		   var link = $(div).find('.url').html();
		   var url = getCleanUrl(link);
		   
		   url = url.replace(/&amp;/g, '&');
		   
		   if(url.indexOf('pageNumber=') != -1){
			   // just flip the cat page
			   
			   window.location.replace(url);
		   }else{
			   // redirect to a new tab
			   window.open(url, '_blank');
		   }
		}
		  
		  function enableDestroyRedsqr() {
		  var isChecked = false;
            if(!(document.getElementById("destroyRedsqr").checked)) {                   
               isChecked = false;
            } else {   
               isChecked = true;		   
            }	
		    return isChecked;
		  } 

		  function enableEditRedsqr() {
		  var isChecked = false;
            if(!(document.getElementById("editRedsqr").checked)) {                   
               isChecked = false;
            } else {   
               isChecked = true;		   
            }	
		    return isChecked;
		  } 	

		  function enableCloneRedsqr() {
		  var isChecked = false;
            if(!(document.getElementById("cloneRedsqr").checked)) {                   
               isChecked = false;
            } else {   
               isChecked = true;		   
            }	
		    return isChecked;
		  }		  
		
		function getXyLocation(event){		
		   	var elmY = event.pageY-document.getElementById("maxIcon").offsetTop;
			document.frmEditor.yAxis.value = (elmY * 1.7);	
		}
		
		function getXyLocationOverload(event, divObj){	
			//alert(divObj.id);
		   var redSqrMaxIconId = "maxIcon" + divObj.id;	
		   var elmY = event.pageY-document.getElementById(redSqrMaxIconId).offsetTop;
		   alert(redSqrMaxIconId);
		   document.frmEditor.yAxis.value = (elmY * 1.7);
		   //document.frmEditor.yAxis.value = elmY;
		   //alert(elmY);
		
		//pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("maxIcon").offsetTop;
		//document.frmEditor.yAxis.value = (pos_y * 1.7);
		}	
		
		function syncURLValue(){
			var productLink = document.getElementById("productLink");
			setSelectedRedSquareTitle(productLink.value);
		}
		
		function setSelectedRedSquareTitle(url){
			$(div).find('.url').html('link="' + url +'"');
			//div.title = 'link="' + url +'"';
		}
		
		function getCleanUrl(urlTitle){
			var arrURL = [];
			var cleanUrl = "";
			
			arrURL = urlTitle.split('"');
			cleanUrl = arrURL.length > 1 ? arrURL[1] : "";
			
			return cleanUrl;
		}
		
		function hasExistingRedSquares(){
		   var container = document.getElementById('divContainer');
		   return $(container).find('div.drsElement.drsMoveHandle').length > 0;
		}
		
		function addNewClonedRedSquare(){
			var container = document.getElementById('divContainer');
			var div = $(container).find('.drsElement.drsMoveHandle').last();
			
			if(div != null && typeof div != 'undefined' && div.length > 0){
				//var clone = $(div).clone(true);
				
			   var clone = document.getElementById(div[0].id).cloneNode(true);;
			   
			   clone
			   var tmpId = new Date().getTime();
			   clone.id = tmpId;
			   container.appendChild(clone);
			}
		}