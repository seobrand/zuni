<?php 
	class Order extends AppModel { 
	        var $name = 'Order';
			var $belongsTo = array('FrontUser','Voucher');		 
	} 
?>