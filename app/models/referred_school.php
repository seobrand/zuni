<?php 
class ReferredSchool extends AppModel {
	var $name="ReferredSchools";
	var $belongsTo = array('FrontUser','School','DailyDiscount');
}
?>