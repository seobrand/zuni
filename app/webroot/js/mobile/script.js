$(document).ready(function(){

// tabs
$(".tab1").click(function(){
	$(".freebie_tab").slideDown(400);
	$(".bigdeal_tab").slideUp(400);
	$(".contest_tab").slideUp(400);		
});
	
$(".tab2").click(function(){
	$(".freebie_tab").slideUp(400);
	$(".bigdeal_tab").slideDown(400);
	$(".contest_tab").slideUp(400);
});
	
$(".tab3").click(function(){
	$(".freebie_tab").slideUp(400);
	$(".bigdeal_tab").slideUp(400);
	$(".contest_tab").slideDown(400);
});


// form 
$('input, textarea').each(function(){    
	var default_value = $(this).val();        
	$(this).focus(function() {
		if($(this).val() == default_value)
		{
			 $(this).val("");
		}
	}).blur(function(){
		if($(this).val().length == 0) /*Small update*/
		{
			$(this).val(default_value);
		}
	});
});



//safari
var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;

 if (isSafari) {
   $(".selectmenu3").addClass("safariInput1");
   $(".selectmenu4").addClass("safariInput1");
   }


//
/*$(window).resize(function(){
	if(window.innerHeight > window.innerWidth){
    //alert("Portrait");
	$(".tabs_outer ul").css({"width":window.innerWidth});
	$(".tabs_outer li").css({"width":window.innerWidth});
	alert(window.innerWidth);
}

else{
	//alert("Landscape");
	$(".tabs_outer ul").css({"width":window.innerWidth});
	$(".tabs_outer li").css({"width":window.innerWidth});
	alert(window.innerWidth);
}

	});*/


/*$(window).resize(function(){
						  
						   
 	
 
	 
});*/

var windowWidth = $(window).width();

if(windowWidth > 500){
	$(".nav_inner li").css({"width":"25%"});
	}




var metas = document.getElementsByTagName('meta');
var i;
if (navigator.userAgent.match(/iPhone/i)) {
	for (i=0; i<metas.length; i++) {
		if (metas[i].name == "viewport") {
			metas[i].content = "width=device-width, minimum-scale=1.0, maximum-scale=1.0";
		}
	}
	document.getElementsByTagName('body')[0].addEventListener("gesturestart", gestureStart, false);
}
function gestureStart() {
	for (i=0; i<metas.length; i++) {
		if (metas[i].name == "viewport") {
			metas[i].content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6";
		}
	}
}


/////////////

$(window).resize( function() {
		if ($(window).width() > 320 ) {
			$(".portraitClass").css({"display":"none"});
			$(".landscapeClass").css({"display":"block"});
			
		 
		} else {
			
			$(".portraitClass").css({"display":"block"});
			$(".landscapeClass").css({"display":"none"});
		
		
		}
		  
	   
});
	

		if ($(window).width() > 320 ) {
			$(".portraitClass").css({"display":"none"});
			$(".landscapeClass").css({"display":"block"});
			
		 
		} else {
			
			$(".portraitClass").css({"display":"block"});
			$(".landscapeClass").css({"display":"none"});
		
		
		}



//////////////


 });