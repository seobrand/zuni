<?php
class CategoriesSubcategory extends AppModel {
	var $name="CategoriesSubcategory";
	var $belongsTo = array('Category','Subcategory');
	var $hasMany = array('CountiesCategoriesSubcategory');
}
?>