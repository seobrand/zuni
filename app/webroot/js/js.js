<!-- Hide JavaScript from Java-Impaired Browsers

function MM_findObj(n, d) 
{ //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

var insDiv   = MM_findObj('popupDiv');
var insFrame = MM_findObj('popupFrame');
var hdrDiv = MM_findObj('phdr');
var cntDiv = MM_findObj('pcnt');

//var regButDiv = MM_findObj('regButton');
//insDiv.style.visibility = 'hidden';

function loadPopUp(targ,w,h)
{
	document.getElementById('popupDiv').style.display='block';
	insFrame.src = targ;
	insFrame.height=h;
	insFrame.width=w;
	insDiv.style.width=w+"px";
	insDiv.style.visibility = 'visible';
	insDiv.style.zIndex = '3';
    if((navigator.platform=="MacPPC") && (navigator.appName == "Microsoft Internet Explorer"))
	{}
	else
		window.scroll(0,0);
}

function closeWindow(r)
{
	document.getElementById('popupDiv').style.display='none';
}



