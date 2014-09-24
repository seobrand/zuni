<?php 
	class SignupEmail extends AppModel { 
	        var $name = 'SignupEmail';
		
			//Validation for Country
			var $validate =  array(
				 'first_name'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please insert first name.'),
				 'last_name'=>array('rule' => 'Numeric',
        		 				'message' => 'Please insert last name.'),
				 'email' => array(
							  'emailRule-1'=> array(
									'rule' => 'email',
									'message' => 'Please insert valid email-id.'
									),
							  'emailRule-2'=>array(
									'rule' => 'isUnique',
									'message' => 'Email id already in use, Please try another.'
									)
							  ),
				 );
				 
		function signupEmailEditDetail($id=null){
			   $this->id = $id;
			   $SignupEmail = $this->read();
			   return $SignupEmail;
	      }	
		 
	} 
?>