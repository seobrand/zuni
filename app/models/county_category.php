<?php
class CountyCategory extends AppModel {
	var $name="CountyCategory";
	var $belongsTo = array('Category','County');
}
?>