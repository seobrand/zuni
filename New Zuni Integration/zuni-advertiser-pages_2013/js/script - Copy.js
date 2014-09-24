$(document).ready(function(){

//chrome
var isChrome = window.chrome;
if(isChrome) {
   $(".textgradient").css({'top':'62px'});
   $(".selectmenu").css({'padding-right':'20px'});
   $(".inputOuter2 input").css({'padding-top':'3px'});
    
}

var isSafari = window.safari;
if(isSafari) {
   $(".textgradient").css({'top':'62px'});
}

//safari
var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;

 if (isSafari) {
   $(".textgradient").css({'top':'62px'});
  }



 

	
$(".bottomlink01").click(function() {
	 
	 $.cookie($(".joinZuni").animate({
		height:'0px',
		opacity:0,			
}, 2000));
 

	 
	 
		
setInterval(function(){
	$(".joinZuni").css({'display':'none'});
	$(".copyright").css({'padding-bottom':'0px'});
}, 900);
		
	});









//Daily Discount
$("#bigDeal").click(function(){	
	$(".todayDealBox").toggle();
	$(".freeDealBox").hide();
	$(".contestBox").hide();
	overlay_toggle('bigDeal');
 });
 
//TODAY'S FREEBIE
$("#freeDeal").click(function(){	
	$(".todayDealBox").hide();
	$(".contestBox").hide();
	$(".freeDealBox").toggle();
	overlay_toggle('freeDeal');
 });
 
//contest
$("#contestTab").click(function(){	
	$(".todayDealBox").hide();
	$(".freeDealBox").hide();
	$(".contestBox").toggle();
	overlay_toggle('contestTab');
 });
 
 
function overlay_toggle(contest){
	 if(contest == 'bigDeal'){
		 if($(".todayDealBox").css('display').toString() == 'block'){
		 	$(".overlay").show();
		 }else{
			$(".overlay").hide();
		 }	 
	 }
	 
	 
	 if(contest == 'freeDeal'){
		 if($(".freeDealBox").css('display').toString() == 'block'){
		 	$(".overlay").show();
		 }else{
			$(".overlay").hide();
		 }	 
	 }
	 
	 
	 if(contest == 'contestTab'){
		 if($(".contestBox").css('display').toString() == 'block'){
		 	$(".overlay").show();
		 }else{
			$(".overlay").hide();
		 }	 
	 }
 
 }
 

//$(document.body).append("<div class='overlay'></div>");
$(".overlay").hide();
$("#dropdown1, .dropdownBox1").hover(function(){
	$(".top_right").css({'z-index':'500','position':'relative'});
	$(".overlay").show();		
},
function(){
	$(".overlay").hide();
	$(".top_right").css({'z-index':'300','position':'relative'});
});

$(".dropdownBox1").hover(function(){
	$("#dropdown1").css({opacity:'.5'});
			
},
function(){
	$("#dropdown1").css({opacity:'1'});
});


//redeem

$(".redeemTab").hover(function(){
	$(".redeemdropdown").show();		
},
function(){
	$(".redeemdropdown").hide();
});




//browse cities and countries
$(function() {
    $('.browseCitiesBg_indent > ul > li').hover(function(){
        $(this).children('ul').slideDown();
        },
    function(){
        $(this).children('ul').hide();
    });    
});

//categories box border radius
$(".saving_offers ul > li:nth-child(1) .border_radius2").attr('class','border_radius1');
$(".saving_offers ul > li:nth-child(2) .border_radius2").attr('class','border_radius1');
$(".saving_offers ul > li:nth-child(3) .border_radius2").attr('class','border_radius1');
$(".saving_offers ul > li:nth-child(4) .border_radius2").attr('class','border_radius1');
$(".saving_offers ul > li:nth-child(5) .border_radius2").attr('class','border_radius2');
$(".saving_offers ul > li:nth-child(6) .border_radius2").attr('class','border_radius2');
$(".saving_offers ul > li:nth-child(7) .border_radius2").attr('class','border_radius2');
$(".saving_offers ul > li:nth-child(8) .border_radius2").attr('class','border_radius2');

// form input

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

$(".discountInput1").focus(function(){
	$(this).parent().css({'background-position':'0 -43px'});
 	
}).blur(function(){
	$(this).parent().css({'background-position':'0 0'});
 });


$(".contactInput1").focus(function(){
	$(this).parent().css({'background-position':'0 -48px'});
 	
}).blur(function(){
	$(this).parent().css({'background-position':'0 0'});
 });


$(".contacttextarea1").focus(function(){
	$(this).parent().css({'background-position':'0 -148px'});
 	
}).blur(function(){
	$(this).parent().css({'background-position':'0 0'});
 });


$(".refferinput1").focus(function(){
	$(this).parent().css({'background-position':'0 -43px'});
 	
}).blur(function(){
	$(this).parent().css({'background-position':'0 0'});
 });


$(".reffertextarea1").focus(function(){
	$(this).parent().css({'background-position':'0 -103px'});
 	
}).blur(function(){
	$(this).parent().css({'background-position':'0 0'});
 });

$(".contacttextare1").focus(function(){
	$(this).parent().css({'background-position':'0 -143px'});
 	
}).blur(function(){
	$(this).parent().css({'background-position':'0 0'});
 });


$("#close1").click(function(){
		$("#freeDealBox").css({'display':'none'});
		$(".overlay").css({'display':'none'});								
});


$("#close2").click(function(){
		$("#freeDiskBox").css({'display':'none'});
		$(".overlay").css({'display':'none'});								
});

$("#close3").click(function(){
		$(".contestBox").css({'display':'none'});
		$(".overlay").css({'display':'none'});								
});

});	

 