<?php 
	class School extends AppModel { 
	        var $name = 'School';
		
			//Validation for School
				 var $validate =  array(
								 'schoolname'=>array(
									   'schoolname-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please enter school name.'
											 ),
										'schoolname-2'=> array(
										 'rule' => 'isUnique',
										 'message' => 'School alerady exist.',
										 'on'=>'create'
										 ),
									 ),
							    'city_id' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select city.'),
							    'county_id' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select county.'),
								'state_id' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select state.')
				 );
			function schoolEditDetail($id=null){
		
			    $this->id = $id;
				  
			    $School = $this->read();
			  
			    return $School;
	      }	 		 
	} 
?>