<?php 
class FrontCategory extends AppModel { 
	        var $name = 'FrontCategory';
			//Validation for Front Category
			var $validate =  array(
				'title'=>array(
								'rule1'=>array(
									'rule'=>'notEmpty',
									'message'=>'Please enter the Title'								
								),
								'rule2'=>array(
									'rule' => 'isUnique',
									'message' => 'Title already in use, Please try another.',
									'on' => 'create'
								)
					),
				'details'=>array(
									'rule'=>'notEmpty',
									'message'=>'Please enter the Front Page Line'								
								)		
			);

}
?>