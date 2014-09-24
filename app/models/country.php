<?php 
	class Country extends AppModel { 
	        var $name = 'Country';
		
			//Validation for Country
			var $validate =  array(
				 'countryname'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please insert country name.')
				 );
				 
		function countryEditDetail($id=null){
		
			   $this->id = $id;
			   $Country = $this->read();
			   return $Country;
			   
	      }	
		 
		   
	} 
?>