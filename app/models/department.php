<?php 
	class Department extends AppModel { 
	        var $name = 'Department';
		
			//Validation for contact
			var $validate =  array(
				 'name'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please enter department name.'
								)				  
								
				 );	   
	} 
?>