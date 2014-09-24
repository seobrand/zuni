popu = jQuery.noConflict();
popu(document).ready(function() {

	//grab all the anchor tag with rel set to shareit
	popu('a[rel=shareit1], #shareit-box2').mouseenter(function() {		
		
		//get the height, top and calculate the left value for the sharebox
		var height = popu(this).height();
		var top = popu(this).offset().top;
		
		//get the left and find the center value
		var left = popu(this).offset().left + (popu(this).width() /2) - (popu('#shareit-box2').width() / 2);		
		
		//grab the href value and explode the bar symbol to grab the url and title
		//the content should be in this format url|title
		var value = popu(this).attr('href').split('|');
		
		//assign the value to variables and encode it to url friendly
		var field = value[0];
		var url = encodeURIComponent(value[0]);
		var title = encodeURIComponent(value[1]);
		
		//assign the height for the header, so that the link is cover
		popu('#shareit-header').height(height);
		
		//display the box
		popu('#shareit-box2').show();
		
		//set the position, the box should appear under the link and centered
		popu('#shareit-box2').css({'top':top, 'left':left});
		
		//assign the url to the textfield
		popu('#shareit-field').val(field);
		
		//make the bookmark media open in new tab/window
		popu('a.shareit-sm').attr('target','_blank');
		
		 
		
	});

	//onmouse out hide the shareit box
	popu('#shareit-box2').mouseleave(function () {
		popu('#shareit-field').val('');
		popu(this).hide();
	});
	
});

popu(document).ready(function() {

	//grab all the anchor tag with rel set to shareit
	popu('a[rel=shareit2], #shareit-box3').mouseenter(function() {		
		
		//get the height, top and calculate the left value for the sharebox
		var height = popu(this).height();
		var top = popu(this).offset().top;
		
		//get the left and find the center value
		var left = popu(this).offset().left + (popu(this).width() /2) - (popu('#shareit-box3').width() / 2);		
		
		//grab the href value and explode the bar symbol to grab the url and title
		//the content should be in this format url|title
		var value = popu(this).attr('href').split('|');
		
		//assign the value to variables and encode it to url friendly
		var field = value[0];
		var url = encodeURIComponent(value[0]);
		var title = encodeURIComponent(value[1]);
		
		//assign the height for the header, so that the link is cover
		popu('#shareit-header').height(height);
		
		//display the box
		popu('#shareit-box3').show();
		
		//set the position, the box should appear under the link and centered
		popu('#shareit-box3').css({'top':top, 'left':left});
		
		//assign the url to the textfield
		popu('#shareit-field').val(field);
		
		//make the bookmark media open in new tab/window
		popu('a.shareit-sm').attr('target','_blank');
		
		 
		
	});

	//onmouse out hide the shareit box
	popu('#shareit-box3').mouseleave(function () {
		popu('#shareit-field').val('');
		popu(this).hide();
	});
	
 
	
});


popu(document).ready(function() {

	//grab all the anchor tag with rel set to shareit
	popu('a[rel=shareit3], #shareit-box4').mouseenter(function() {		
		
		//get the height, top and calculate the left value for the sharebox
		var height = popu(this).height();
		var top = popu(this).offset().top;
		
		//get the left and find the center value
		var left = popu(this).offset().left + (popu(this).width() /2) - (popu('#shareit-box4').width() / 2);		
		
		//grab the href value and explode the bar symbol to grab the url and title
		//the content should be in this format url|title
		var value = popu(this).attr('href').split('|');
		
		//assign the value to variables and encode it to url friendly
		var field = value[0];
		var url = encodeURIComponent(value[0]);
		var title = encodeURIComponent(value[1]);
		
		//assign the height for the header, so that the link is cover
		popu('#shareit-header').height(height);
		
		//display the box
		popu('#shareit-box4').show();
		
		//set the position, the box should appear under the link and centered
		popu('#shareit-box4').css({'top':top, 'left':left});
		
		//assign the url to the textfield
		popu('#shareit-field').val(field);
		
		//make the bookmark media open in new tab/window
		popu('a.shareit-sm').attr('target','_blank');
		
		 
		
	});

	//onmouse out hide the shareit box
	popu('#shareit-box4').mouseleave(function () {
		popu('#shareit-field').val('');
		popu(this).hide();
	});
	
 
	
});

popu(document).ready(function() {

	//grab all the anchor tag with rel set to shareit
	popu('a[rel=shareit4], #shareit-box5').mouseenter(function() {		
		
		//get the height, top and calculate the left value for the sharebox
		var height = popu(this).height();
		var top = popu(this).offset().top;
		
		//get the left and find the center value
		var left = popu(this).offset().left + (popu(this).width() /2) - (popu('#shareit-box5').width() / 2);		
		
		//grab the href value and explode the bar symbol to grab the url and title
		//the content should be in this format url|title
		var value = popu(this).attr('href').split('|');
		
		//assign the value to variables and encode it to url friendly
		var field = value[0];
		var url = encodeURIComponent(value[0]);
		var title = encodeURIComponent(value[1]);
		
		//assign the height for the header, so that the link is cover
		popu('#shareit-header').height(height);
		
		//display the box
		popu('#shareit-box5').show();
		
		//set the position, the box should appear under the link and centered
		popu('#shareit-box5').css({'top':top, 'left':left});
		
		//assign the url to the textfield
		popu('#shareit-field').val(field);
		
		//make the bookmark media open in new tab/window
		popu('a.shareit-sm').attr('target','_blank');
		
		 
		
	});

	//onmouse out hide the shareit box
	popu('#shareit-box5').mouseleave(function () {
		popu('#shareit-field').val('');
		popu(this).hide();
	});
	
 
	
});

popu(document).ready(function() {

	//grab all the anchor tag with rel set to shareit
	popu('a[rel=shareit5], #shareit-box6').mouseenter(function() {		
		
		//get the height, top and calculate the left value for the sharebox
		var height = popu(this).height();
		var top = popu(this).offset().top;
		
		//get the left and find the center value
		var left = popu(this).offset().left + (popu(this).width() /2) - (popu('#shareit-box6').width() / 2);		
		
		//grab the href value and explode the bar symbol to grab the url and title
		//the content should be in this format url|title
		var value = popu(this).attr('href').split('|');
		
		//assign the value to variables and encode it to url friendly
		var field = value[0];
		var url = encodeURIComponent(value[0]);
		var title = encodeURIComponent(value[1]);
		
		//assign the height for the header, so that the link is cover
		popu('#shareit-header').height(height);
		
		//display the box
		popu('#shareit-box6').show();
		
		//set the position, the box should appear under the link and centered
		popu('#shareit-box6').css({'top':top, 'left':left});
		
		//assign the url to the textfield
		popu('#shareit-field').val(field);
		
		//make the bookmark media open in new tab/window
		popu('a.shareit-sm').attr('target','_blank');
		
		 
		
	});

	//onmouse out hide the shareit box
	popu('#shareit-box6').mouseleave(function () {
		popu('#shareit-field').val('');
		popu(this).hide();
	});
	
 
	
});


popu(document).ready(function() {

	//grab all the anchor tag with rel set to shareit
	popu('a[rel=shareit6], #shareit-box7').mouseenter(function() {		
		
		//get the height, top and calculate the left value for the sharebox
		var height = popu(this).height();
		var top = popu(this).offset().top;
		
		//get the left and find the center value
		var left = popu(this).offset().left + (popu(this).width() /2) - (popu('#shareit-box7').width() / 2);		
		
		//grab the href value and explode the bar symbol to grab the url and title
		//the content should be in this format url|title
		var value = popu(this).attr('href').split('|');
		
		//assign the value to variables and encode it to url friendly
		var field = value[0];
		var url = encodeURIComponent(value[0]);
		var title = encodeURIComponent(value[1]);
		
		//assign the height for the header, so that the link is cover
		popu('#shareit-header').height(height);
		
		//display the box
		popu('#shareit-box7').show();
		
		//set the position, the box should appear under the link and centered
		popu('#shareit-box7').css({'top':top, 'left':left});
		
		//assign the url to the textfield
		popu('#shareit-field').val(field);
		
		//make the bookmark media open in new tab/window
		popu('a.shareit-sm').attr('target','_blank');
		
		 
		
	});

	//onmouse out hide the shareit box
	popu('#shareit-box7').mouseleave(function () {
		popu('#shareit-field').val('');
		popu(this).hide();
	});
	
 
	
});

popu(document).ready(function() {

	//grab all the anchor tag with rel set to shareit
	popu('a[rel=shareit7], #shareit-box8').mouseenter(function() {		
		
		//get the height, top and calculate the left value for the sharebox
		var height = popu(this).height();
		var top = popu(this).offset().top;
		
		//get the left and find the center value
		var left = popu(this).offset().left + (popu(this).width() /2) - (popu('#shareit-box8').width() / 2);		
		
		//grab the href value and explode the bar symbol to grab the url and title
		//the content should be in this format url|title
		var value = popu(this).attr('href').split('|');
		
		//assign the value to variables and encode it to url friendly
		var field = value[0];
		var url = encodeURIComponent(value[0]);
		var title = encodeURIComponent(value[1]);
		
		//assign the height for the header, so that the link is cover
		popu('#shareit-header').height(height);
		
		//display the box
		popu('#shareit-box8').show();
		
		//set the position, the box should appear under the link and centered
		popu('#shareit-box8').css({'top':top, 'left':left});
		
		//assign the url to the textfield
		popu('#shareit-field').val(field);
		
		//make the bookmark media open in new tab/window
		popu('a.shareit-sm').attr('target','_blank');
		
		 
		
	});

	//onmouse out hide the shareit box
	popu('#shareit-box8').mouseleave(function () {
		popu('#shareit-field').val('');
		popu(this).hide();
	});
	
 
	
});