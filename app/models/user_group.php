<?php 
	class UserGroup extends AppModel { 
	        var $name = 'UserGroup';
			
			//var $hasOne = 'User';
			  var $hasMany = array(
			 'User' => array(
			 'className' => 'User',
			 'dependent' => true
			 )
			 ); 
			
			var $validate =  array(
								 'group_name'=>array(
									   'groupnameRule-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please insert group name.'
											 ),
										'groupnameRule-2'=> array(
										 'rule' => 'isUnique',
										 'message' => 'User group alerady exist.'
										 )
									)
				 );
				
				 
		function groupEditDetail($id=null){
			$this->id = $id;
			$UserGroup = $this->read();
			return $UserGroup;
	      }	
	} 
?>