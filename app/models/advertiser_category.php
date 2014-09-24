<?php
class AdvertiserCategory extends AppModel {
	var $name="AdvertiserCategory";
	var $belongsTo = array('CategoriesSubcategory','AdvertiserProfile');
	var $actsAs = array('Containable');
}
?>