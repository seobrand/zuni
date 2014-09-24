<?php 
	class Image extends AppModel { 
	        var $name = 'Image';
		
			//Validation for Country
			var $validate =  array(
				 'title'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please insert image title.')				
				 );
				 
		function imageEditDetail($id=null){
			   $this->id = $id;
			   $Image = $this->read();
			   return $Image;
	      }	
		 
	} 
?>