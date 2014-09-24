<?php 
class FrontUser extends AppModel {
	var $name="FrontUser";
	var $belongsTo = array('AdvertiserProfile');
	var $hasMany  = array('ReferredFriend','ReferredBusiness');
}	
?>