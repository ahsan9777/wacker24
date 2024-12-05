var popUpWin=0;
function popUpWindow(URLStr, left, top, width, height)
{
  if(popUpWin)
  {
    if(!popUpWin.closed) popUpWin.close();
  }
  popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menub ar=no,scrollbar=auto,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}

function ValidName(FormName,ElemName){
	var digits="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz- ";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}
function ValidUserName(FormName,ElemName){
	var digits="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_. ";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}
function validAdress(FormName,ElemName){
	var digits="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@_.-,'/\ ";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}
function validNumbers(FormName,ElemName){
	var digits="0123456789";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}


function validFloats(FormName,ElemName){
	var digits="0123456789.";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}



function ValidPhone(FormName,ElemName){
	var digits="0123456789-+";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}
function validNumbersTime(FormName,ElemName){
	var digits="0123456789:";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}
function validDigitsWithSC(FormName,ElemName){
	var digits="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@_.-#,:;/\() ";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}
function IsValidTime(FormName,ElemName){
	
	if (validNumbersTime(FormName,ElemName)==false){
		return (false);
	}

	var Temp     = document.forms[FormName].elements[ElemName];
	var AtSym    = Temp.value.indexOf(':')
	var Space    = Temp.value.indexOf(' ')
	var Period   = Temp.value.lastIndexOf(':')
	var Length   = Temp.value.length - 1   // Array is from 0 to length-1

	if ((AtSym < 2) || (AtSym > 2) || (Period == Length ) || (Space  != -1)) {  
		return (false);
	}
	
	return (true);
}
function IsValidTimeFormat(FormName,ElemName){
	
	var Temp     = document.forms[FormName].elements[ElemName];
	var x 		 = Temp.value.split(":");

	if ((parseInt(x[0]) > 23) || (parseInt(x[1]) > 59)){  
		return (false);
	}

	return (true);
}


function EmailCheck(eAddr) 
{ 
   var goodAddr = false;
   var ndxAt = ndxDot = 0;

   ndxAt  = eAddr.indexOf("@");
   ndxDot = eAddr.indexOf(".");

   if ( (ndxDot < 0) || (ndxAt < 0) )
      alert("Invalid Email address. !");  
   else if (ndxDot < ndxAt)
      alert("You entered email address incorrectly.\nThe '.' should come after the '@'.\n\nThe format is 'you@dom.suf'. !");
   else if ( (ndxDot - 2) <= ndxAt)
      alert("The domain name is missing.\n\nThe format is 'you@dom.suf'. !");
   else if ( eAddr.length <= (ndxDot + 3) )
      alert("There must be three characters after the 'dot'.\n\nThe format is 'you@dom.suf'. !");
   else
      goodAddr = true;
   return (goodAddr);                       
} 


function ValidEmail(FormName,ElemName)
	{
	
	if (validDigits(FormName,ElemName)==false){
		return (false);
	}
	var EmailOk  = true
	var Temp     = document.forms[FormName].elements[ElemName]
	var AtSym    = Temp.value.indexOf('@')
	var Period   = Temp.value.lastIndexOf('.')
	var Space    = Temp.value.indexOf(' ')
	var Dot      = Temp.value.indexOf('.')
	var Length   = Temp.value.length - 1   // Array is from 0 to length-1

	if ((AtSym < 1) ||                     // '@' cannot be in first position
	    (Period <= AtSym+1) ||             // Must be atleast one valid char btwn '@' and '.'
	    (Period == Length ) ||             // Must be atleast one valid char after '.'
	    (Space  != -1)      ||              // No empty spaces permitted
	    (Dot ==0 )          ||            // No Dot on first position permitted
	    (Dot+1 ==AtSym ))                      // No Dot on first position permitted
	   {  
	      EmailOk = false
	   }
	return EmailOk
}


function validDigits(FormName,ElemName){
	var digits="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@_.-";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1); // used in valid email function
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}

function DateDiff(FormName, Date1, Date2){
	var diff, totalTime1, totalTime2;
	var time1 = document.forms[FormName].elements[Date1].value.split(":");
	var time2 = document.forms[FormName].elements[Date2].value.split(":");
	totalTime1 = time1[0] * 60 + time1[1];
	totalTime2 = time2[0] * 60 + time2[1];
	diff = totalTime2 - totalTime1;
	if(diff <= 0){
		return (false);
	}
return true;
}
function Checkbox(TheForm, Field){
	var obj = document.forms[TheForm].elements[Field];
	var res = false;
	if(obj.length > 0){
		for(var i=0; i < obj.length; i++){
			if(obj[i].checked == true){
				res = true;
			}
		}
	}
	else{
		if(obj.checked == true){
				res = true;
		}
	}
return (res);
}

function CheckboxCount(TheForm, Field){
	var obj = document.forms[TheForm].elements[Field];
	var res = 0;
	if(obj.length > 0){
		for(var i=0; i < obj.length; i++){
			if(obj[i].checked == true){
				res++;
			}
		}
	}
	else{
		if(obj.checked == true){
				res++;
		}
	}
return (res);
}

function checkAll_2(TheForm, Field){
	var obj = document.forms[TheForm].elements[Field];
	if(document.forms[TheForm].chkAll.checked == true){
		if(obj.length > 0){
			for(var i=0; i < obj.length; i++){
				obj[i].checked = true;
			}
		}
		else{
			obj.checked = true;
		}
	}
	else{
		if(obj.length > 0){
			for(var i=0; i < obj.length; i++){
				obj[i].checked = false;
			}
		}
		else{
			obj.checked = false;
		}
	}
}




/*
-------------------------------------------------------------------
						Previous checks
-------------------------------------------------------------------
*/

function validDigitsWithSpace(FormName,ElemName){
	var digits="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@_.-!?:, ";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}

function validDigits2(FormName,ElemName){
	var digits="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz@_.-!?:, ";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}


function IsFoneValid(FormName,ElemName){
	var digits="0123456789.-";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}


/*
function validDate(FormName,ElemName){
	var digits="0123456789/";
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(digits.indexOf(temp)==-1){
			return (false);
		}
	}
	return (true);
}


function IsDateValid(FormName,ElemName)
	{
	
	if (validDate(FormName,ElemName)==false){
		return (false);
	}
	var BDateOk  = true
	var Temp     = document.forms[FormName].elements[ElemName]
	var AtSym    = Temp.value.indexOf('/')
	var Period   = Temp.value.lastIndexOf('/')
	var Space    = Temp.value.indexOf(' ')
	var Length   = Temp.value.length - 1   // Array is from 0 to length-1

	if ((AtSym < 2) ||                     // '/' cannot be in first position
	    (Period <= AtSym+2) ||             // Must be atleast one valid char btwn '@' and '.'
	    (Period == Length ) ||             // Must be atleast one valid char after '.'
	    (Space  != -1))                    // No empty spaces permitted
	   {  
	      BDateOk = false
	   }
	return BDateOk
}


*/





function CheckRadio(FormName,ElemName){
	var ret;
	ret = false;
	var obj = document.forms[FormName].elements[ElemName];
	for(i=0; i<obj.length; i++){
		if(obj[i].checked == true){
			ret = true;
		}
	}
	return (ret);
}

/*function for checking blank fields*/
function IsBlank(FormName,ElemName){
	
	var frmElement=document.forms[FormName].elements[ElemName];
	var temp;
	var countSpace=0;
	for(var i=0;i<frmElement.value.length;i++){
		temp=frmElement.value.substring(i,i+1);
		if(temp.indexOf(" ")!=-1){
			countSpace++;
		}
	}
	if (countSpace==frmElement.value.length)
		return (false);
	
	return (true);
}

/*function for checking Email address*/
function IsEmailValid(FormName,ElemName)
	{
	
	if (validDigits(FormName,ElemName)==false){
		return (false);
	}
	var EmailOk  = true
	var Temp     = document.forms[FormName].elements[ElemName]
	var AtSym    = Temp.value.indexOf('@')
	var Period   = Temp.value.lastIndexOf('.')
	var Space    = Temp.value.indexOf(' ')
	var Dot      = Temp.value.indexOf('.')
	var Length   = Temp.value.length - 1   // Array is from 0 to length-1

	if ((AtSym < 1) ||                     // '@' cannot be in first position
	    (Period <= AtSym+1) ||             // Must be atleast one valid char btwn '@' and '.'
	    (Period == Length ) ||             // Must be atleast one valid char after '.'
	    (Space  != -1)      ||              // No empty spaces permitted
	    (Dot ==0 )          ||            // No Dot on first position permitted
	    (Dot+1 ==AtSym ))                      // No Dot on first position permitted
	   {  
	      EmailOk = false
	   }
	return EmailOk
}

function check(){
     var obj
     obj = document.forms['frmRegister'].elements['cboprice[]'];
     var i
     var cnt
     cnt = 0
     for(i=0; i < obj.options.length; i++){
         if(obj.options[i].selected){
               cnt = cnt + 1
          }
     }
	 if(cnt < 1){
		  return false
     }
     return true
}

function highlight(checkbox) {
   if (document.getElementById) {
      var tr = eval("document.getElementById(\"TR" + checkbox.value + "\")");
   } else {
      return;
   }
   if (tr.style) {
      if (checkbox.checked) {
         tr.style.backgroundColor = "#D7D7D7";
      } else {
         tr.style.backgroundColor = "";
      }
   }
}

function submitForm(TheForm){
	document.forms[TheForm].submit();
}

function SubmitForm2(TheForm, Url){
	document.forms[TheForm].action = Url;
	document.forms[TheForm].submit();
}

function chkSignup(){
	if (document.nletter.txtsignup.value=="" || IsBlank("nletter","txtsignup")==false) {
		alert("Email Required!");
		document.nletter.txtsignup.focus();
		return (false);
	}
	else{
		var ChkEmail = ValidEmail("nletter","txtsignup");
		if (ChkEmail==false) {
			alert("Email format is not correct!");
			document.nletter.txtsignup.focus();
			return (false);
		}
		else{
			document.nletter.submit();
		}
	}
}


function disableRightClick(e) {
	var message = "Right click disabled";
	if(!document.rightClickDisabled){ // initialize 
		if(document.layers) {
			document.captureEvents(Event.MOUSEDOWN);
			document.onmousedown = disableRightClick;
		}
		else document.oncontextmenu = disableRightClick;
		return document.rightClickDisabled = true;
	}
	if(document.layers || (document.getElementById && !document.all)){
		if (e.which==2||e.which==3){
			alert(message);
			return false;
		}
	}
	else{
		alert(message);
		return false;
	}
}

function toggleCheckbox(id) {
    document.getElementById(id).checked = !document.getElementById(id).checked;
}

function checkRequired(){
	if (frm1.txtcat.value=="" || IsBlank("frm1","txtcat")==false){
		alert("Category Name Required!");
		frm1.txtcat.focus();
		return (false);
	}
	return (true);
}	

function chkRequired(TheForm)	{
	if (Checkbox("frm", 'chkstatus[]') == false){
		alert("You must check atleast one checkbox");
		return (false);
	}	
	return (true);
}

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

