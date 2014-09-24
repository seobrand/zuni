// JavaScript Document
function boxBlank(id,value,defaultValue){
    if(value==defaultValue){
 	    document.getElementById(id).value='';
        document.getElementById(id).focus();
	}
   
}

function getPopup(form){
	if(form=='city'){
		document.getElementById('formCity').style.display = "block";
		document.getElementById('formCounty').style.display = "none";
	}
	if(form=='county'){
		document.getElementById('formCity').style.display = "none";
		document.getElementById('formCounty').style.display = "block";
	}
}

function GetLink(data)
{
		document.getElementById('linkpop').style.display = "block";
		document.getElementById('linkpop').innerHTML = data;
}