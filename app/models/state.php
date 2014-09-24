<?php 
	class State extends AppModel { 
	        var $name = 'State';
		
			//Validation for state
				 var $validate =  array(
								 'statename'=>array(
									   'statename-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please insert state name.'
											 ),
										'statename-2'=> array(
										 'rule' => 'isUnique',
										 'message' => 'State alerady exist.'
										 )
									)
				 );
				 
				 
		function stateEditDetail($id=null){
			   $this->id = $id;
			   $State = $this->read();
			   return $State;
			   
	      }	
		 
		   
	} 
?>