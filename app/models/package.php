<?php 
	class Package extends AppModel { 
	        var $name = 'Package';
		
			//Validation for package
				 var $validate =  array(
								 'name'=>array(
									   'name-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please insert package name.'
											 ),
										'name-2'=> array(
										 'rule' => 'isUnique',
										 'message' => 'Package name alerady exist.'
										 )
									),
							'setup_price' => array(
                                     'rule' => 'numeric',
									 'message' => 'Please enter valid setup price.'
                                    ),
							'monthly_price' => array(
                                     'rule' => 'numeric',
									 'message' => 'Please enter monthly price.'
                                    )				
				 );
		function packageEditDetail($id=null){
		
			   $this->id = $id;
			   $Package = $this->read();
			   return $Package;
			   
	      }	
		 
		   
	} 
?>