<?php 
class ReferredBusiness extends AppModel {
	var $name="ReferredBusiness";
	var $belongsTo = array('FrontUser','City','County','State');
}
?>