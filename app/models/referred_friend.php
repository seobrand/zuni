<?php 
class ReferredFriend extends AppModel {
	var $name="ReferredFriend";
	var $belongsTo = array('FrontUser','County','State','Kid','School');
}	
?>