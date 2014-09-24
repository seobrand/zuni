<?php 
	class TopTenBusiness extends AppModel { 
	        var $name = 'TopTenBusiness';
			
			var $belongsTo = 'AdvertiserProfile';
			
			/*var $validate = array(
			'order'=> array(
			'rule'=>'numeric',
			'message'=>'order must be a number'
			)
			);*/

	} 
?>