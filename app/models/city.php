<?php 
	class City extends AppModel { 
	        var $name = 'City';
		
			//Validation for city
				 var $validate =  array(
								 'cityname'=>array(
									   'cityname-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please insert city name.'
											 ),
										'cityname-2'=> array(
										 'rule' => 'isUnique',
										 'message' => 'City already exists.'
										 ),
									 ),
							    'county_id' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select county of the city.'),
								'state_id' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select state of the city.')
				 );
			function cityEditDetail($id=null){
		
			    $this->id = $id;
				  
			    $City = $this->read();
			  
			    return $City;
	      }	 		 
	} 
?>