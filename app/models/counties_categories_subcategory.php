<?php
class CountiesCategoriesSubcategory extends AppModel {
	var $name="CountiesCategoriesSubcategory";
	var $belongsTo = array('County','CategoriesSubcategory');
}
?>