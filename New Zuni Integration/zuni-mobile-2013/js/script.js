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




});