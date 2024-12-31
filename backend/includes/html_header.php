<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<title>Wacker 24 Backend Control Panel</title>
<link rel="stylesheet" href="./assets/style/styles.css">
<link rel="stylesheet" href="./assets/style/scrollbar.css">
<link rel="stylesheet" href="./assets/style/responsive.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script>
    function setAll(){
	if(frm.chkAll.checked == true){
		checkAll("frm", "chkstatus[]");
	}
	else{
		clearAll("frm", "chkstatus[]");
	}
}

function checkAll(TheForm, Field){
	var obj = document.forms[TheForm].elements[Field];
	if(obj.length > 0){
		for(var i=0; i < obj.length; i++){
			obj[i].checked = true;
		}
	}
	else{
		obj.checked = true;
	}
}

function clearAll(TheForm, Field){
	var obj = document.forms[TheForm].elements[Field];
	if(obj.length > 0){
		for(var i=0; i < obj.length; i++){
			obj[i].checked = false;
		}
	}
	else{
		obj.checked = false;
	}
}
</script>