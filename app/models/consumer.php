<?php 
class Consumer extends AppModel {
	var $name="FrontUser";
	var $belongsTo = array('AdvertiserProfile');
	var $hasMany  = array('ReferredFriend','ReferredBusiness');
	
	var $validate =  array(
				'first_name' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please enter your First name.'),			
				'last_name' => array(
				'rule1'=>array(
					'rule' => 'notEmpty',
					'message' => 'Please enter your Last name.')			
				),
				'email' => array(
								'emailRule-1'=>array(
									'rule' => 'email',
									'message' => 'Please enter valid Email.',
									'last'=>true
									),
							    'emailRule-2'=>array(
									'rule' => 'isUnique',
									'message' => 'Email id already in use, Please try another.',
									'on'=>'create'
								)
					),				
				'state_id' => array(
								'rule' => 'notEmpty',
								'message' => 'Please select State.'),
				'county_id' => array(
								'rule' => 'notEmpty',
								'message' => 'Please select County.'),	
				'total_bucks' => array(
								'rule' => 'numeric',
								'message' => 'Please enter vaild Bucks.'),																	  
				'm_password' => array(
								'mpassword_1' => array(
									'rule' => array('checkPassword'),
									'message' => 'Please enter Password.',
									),
								'mpassword-2'=>array(
									'rule' => array('minlength'),
									'message' => 'Password should contail atleast 6 characters.',
									)
								),	
				'c_password' => array(
								'password-1' => array(
									'rule' => array('checkcPassword'),
									'message' => 'Please enter Confirm Password.',
									),
							    'password-2'=>array(
									'rule' => array('compare'),
									'message' => 'Password and Confirm password do not match.',
									)								
							  )	  
				
				 );
	function minlength() {
		if($this->data['Consumer']['m_password']!='' && strlen($this->data['Consumer']['m_password'])<6) {
			return false;
		}
		return true;	
	}
	function checkPassword() {
		if((!isset($this->data['Consumer']['id']) && $this->data['Consumer']['m_password']=='')) {
			return false;
		}
		return true;
	}
	function checkcPassword() {
		if((!isset($this->data['Consumer']['id']) && $this->data['Consumer']['c_password']=='') || (isset($this->data['Consumer']['id']) && $this->data['Consumer']['m_password']!='' && $this->data['Consumer']['c_password']=='')) {
			return false;
		}
		return true;
	}	
	function compare() {
		if($this->data['Consumer']['m_password']!='' && $this->data['Consumer']['c_password']!='' && $this->data['Consumer']['m_password']!=$this->data['Consumer']['c_password']) {
		return false;
		}
		return true;
	}
}	
?>