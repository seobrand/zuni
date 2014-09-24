<?php 
class Fundraiser extends AppModel {
	var $name="FrontUser";
	var $validate = array('email'=>array(
									   'email-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please enter email.',
											 'last'=>true
											 ),
										'email-2'=> array(
											 'rule' => 'email',
											 'message' => 'Please enter valid email.',
											 'last'=>true
										 ),
										'email-3'=> array(
											 'rule' => array('unique_email'),
											 'message' => 'This email already exist.'
										 )
									 ),
					 
					'password'=>array(
									   'password-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please enter password.',
											 'last'=>true
											 ),
										'password-2'=> array(
											 'rule' => array('minLength', 6),
											 'message' => 'Please enter atleast 6 characters long password.'
										 )
									 ),
					 
					'cpassword'=>array(
									   'cpassword-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please enter confirm password.',
											 'last'=>true
											 ),
										'cpassword-2'=> array(
											 'rule' => 'passwordMatch',
											 'message' => 'Password and confirm password does not match.'
										 )
									 )
					 );
			function unique_email() {
				if(isset($this->data['Fundraiser']['email']) && $this->data['Fundraiser']['email']!='') {
					
						App::import('model','FrontUser');
						$this->FrontUser = new FrontUser();					
						$email_exist = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email="'.$this->data['Fundraiser']['email'].'" AND (FrontUser.user_type="customer" OR FrontUser.user_type="parent")')));
					if(!empty($email_exist)) {
						return false;
					}
					return true;					
				}
				return true;
			}
			function passwordMatch()	{
				if(isset($this->data['Fundraiser']['password']) && $this->data['Fundraiser']['password']!='' && isset($this->data['Fundraiser']['cpassword']) && $this->data['Fundraiser']['cpassword']!='')
				{
					if($this->data['Fundraiser']['password']==$this->data['Fundraiser']['cpassword'])
					{
						return true;
					}
					return false;
				}
			}
}
?>