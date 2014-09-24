<?php 
	class ContactLead extends AppModel { 
	        var $name = 'ContactLead';
			var $useTable = 'contacts';
		
			//Validation for contact
			var $validate =  array(
				 'name'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please enter name.'
								),
				'email' => array(
							  'emailRule-1'=> array(
									'rule' => 'notEmpty',
									'message' => 'Please enter email.',
									'last'=>true
									),
							  'emailRule-2'=>array(
									'rule' => 'email',
									'message' => 'Please enter a valid email.'
									)
							  ),
				 'state'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please select state.'
								),
				/*'phone'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please enter phone no.'
								),*/
				 'county'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please select county.'
								),
				'department'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please select Department.'
								)							  
								
				 );
	} 
?>