// JavaScript Document
/* ---------------------------- */
/* XMLHTTPRequest Enable */
/* ---------------------------- */
function createObject(){
	var request_type;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		request_type = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		request_type = new XMLHttpRequest();
	}
	return request_type;
}

var http = createObject();

var nocache = 0;
function updateRecord() {
	//var amount = document.getElementById('amount').value;
	// Set te random number to add to URL request
	nocache = Math.random();
	// Pass the login variables like URL variable
	http.open('get', 'udtdata.php?btnCheckOut=1&nocache = '+nocache);
	http.onreadystatechange = getResponse;
	http.send(null);
}

function getResponse() {
	if(http.readyState == 4){ 
		var response = http.responseText;
		var pageData = response.split("||&&");
		if(pageData[0] == 'OK'){
			document.getElementById('invoice').value = pageData[1];
			document.frm.submit();
		}
		else{
			//alert("There is a problem in submitting the form. Please try again later!");
			alert(response);
		}
	}
}

// END :: Right Column Fares Section
