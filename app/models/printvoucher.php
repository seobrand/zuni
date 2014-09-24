<?php 
	class Printvoucher extends AppModel { 
	        var $name = 'Printvoucher';
			var $belongsTo = array('FrontUser','County','AdvertiserProfile','Voucher');		 
	} 
?>