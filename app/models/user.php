<?php 
	class User extends AppModel { 
	        var $name = 'User';
			 
			 var $belongsTo = 'UserGroup';
			//Validation for users
		var $validate =  array(
				'name' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please enter full name.'),
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
				'username' => array(
							  'username-1'=> array(
									'rule' => 'notEmpty',
									'message' => 'Please enter username.'
									),
							  'username-2'=>array(
									'rule' => 'isUnique',
									'message' => 'Username already in use, Please try another.'
									)		
							  ),			  
				'password' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please insert password.'),
				
				'city' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please enter city.'),
				'county' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please select county.'),
				'state' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please select state.'),
				'user_group_id' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please select Access Level.')
							
				 );	
		
		function userEditDetail($id=null){
			$this->id = $id;
			$User = $this->read();
			return $User;
	      }	
		  
		function returnUsersSales(){
	
			$allUsers = $this->find('list',array('fields'=>array('User.id','User.name'),'conditions'=>array('User.active'=>'yes','User.user_group_id'=>'5')));
			return $allUsers;
	
		}
		
		function returnSalesCommission($salesPersonId){
	
			$salesCommission = $this->query("select commission from users where id='".$salesPersonId."'");
			return $salesCommission[0]['users']['commission'];
	
		}
} 
?>