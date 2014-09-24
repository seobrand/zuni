<?php 
	class link extends AppModel { 
	        var $name = 'Link';
		
			//Validation for link
				 var $validate =  array(
				                 'title' => array(
        						 'rule' => 'notEmpty',
        						 'message' => 'Please enter the title.'),
								 'city_id' => array(
        						 'rule' => 'notEmpty',
        						 'message' => 'Please select city of the link.'),
								 'county_id' => array(
        						 'rule' => 'notEmpty',
        						 'message' => 'Please select county of the link.'),
								 'state_id' => array(
        						 'rule' => 'notEmpty',
        						 'message' => 'Please select state of the link.')
				 );
		function linkEditDetail($id=null){
		
			   $this->id = $id;
			   $Link = $this->read();
			   return $Link;
			   
	      }	
		 
		   
	} 
?>