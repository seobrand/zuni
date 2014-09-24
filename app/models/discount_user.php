<?php 
class DiscountUser extends AppModel {
	var $name="DiscountUser";
	var $belongsTo = array('FrontUser','DailyDiscount','AdvertiserProfile');
}	
?>