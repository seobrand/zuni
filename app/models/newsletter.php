<?php 
         class Newsletter extends AppModel { 
	        var $name = 'Newsletter';
			
			 //Validation for Newsletter
				 var $validate =  array(
								 'title' => array(
											 'rule' => 'notEmpty',
											 'message' => 'Please Insert Title.'
										 ),
								'county' => array(
											 'rule' => 'notEmpty',
											 'message' => 'Please Select County.'
										 ),
							   'category' => array(
											 'rule' => 'notEmpty',
											 'message' => 'Please Select Category.'
										 ),
								'subject' => array(
											 'rule' => 'notEmpty',
											 'message' => 'Please Insert Email subject.'
										 ),		 
								'email_content' => array(
											 'rule' => 'notEmpty',
											 'message' => 'Please Insert Email content.'
										 )
				 );
		    function editNewsletter($id=null){
			    $this->id = $id;
			    $Newsletter = $this->read();
			    return $Newsletter;
			   
	       }
	}
?>