
function createRequest() {
	try {
		request = new XMLHttpRequest();
	} catch (tryMS) {
		try {
			request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (otherMS) {
			try {
				request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (failed) {
				request = null;
			}
		}
	}
	return request;
}


function processAjax(url,tar) {
	
	request = createRequest();
	if(request == null) {
		alert("Unable to Create Request");
		return;
	}
	var nocache = new Date();
	// Add the nocache to the url to make sure it gets updated page...
	//url = url + '?stopIEcache='+nocache;
	//url = url + '?stopIEcache='+nocache;
	request.open("GET",url,true);
	
	request.onreadystatechange = function() {stateChanged(tar)};
	request.send(null);
}


function stateChanged(field) {
	if(request.readyState == 4) {
		if(request.status == 200) {
			detailDiv = document.getElementById(field);
			detailDiv.innerHTML = request.responseText;			
		}
	} //else {
      //      detailDiv = document.getElementById(field);
	 //		detailDiv.innerHTML = '<img src="images/loading.gif">Loading...';
    //}
}