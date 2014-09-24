<?php 
/*
   Coder: Surbhit
*/ 

class UsersController extends AppController{

 var $name = 'Users'; 
 var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator'); 
 var $components = array('Auth','common','Session','Cookie','Email','emailhtml');
 var $layout = 'admin'; //this is the layout for admin panel 


	 #this function call by default when a controller is called

	 function index() {          

   		    App::import('model', 'Admin');

		    $this->Admin = new Admin;	

	 		if($this->Session->check('Auth.Admin')){

			    $this->redirect(array('action' => "user"));

			}else{

				$this->Session->setFlash('You are not authorized to access this location.');

				$this->redirect(array('action' => "login"));

			}



	 }

	 

	 function setCss($id)

	 {

			$this->Cookie->delete('css_name');

			if($this->params['pass'][0]=='0'){

			   $this->Cookie->write('css_name','theme',false);

			   $this->redirect(array('action' => $this->params['pass'][1]));

			}else{

			   $this->Cookie->write('css_name','theme'.$this->params['pass'][0],false);

			   $this->redirect(array('action' => $this->params['pass'][1]));

		    }

			



	 }

	 //----------------------------Listing of User group---------------------------------------------------------- 

	 function user()
	 {
	     if(isset($this->params['named']['message']))

		   {

			  if($this->params['named']['message']=='success')

			  {

				 $this->set('success','success');

			  }else{

			   $this->set('error','error');

			  }

		   }

		   

		   $this->set('name', 'name'); 

		   $this->set('user_group_id', '');

		   $this->set('active', ''); 

		   

		   $this->set('city', ''); 

		   $this->set('state', ''); 

		   $this->set('county', ''); 

		   $this->set('name1', ''); 

		   

		 $this->set('StatesList',$this->common->getAllState());  //  List states
		 $this->set('CitiesList',$this->common->getAllCity());   //  List cities

		 $this->set('CountyList',$this->common->getAllCounty()); //  List counties

		 $this->set('UserGroupList',$this->common->getAllUserGroup()); //  List usergroups

		 

		      $condition='';

		 

			  $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('User.id' => 'asc'));

			 #setting diff condition in paginate function according to search criteria

			 if((!empty($this->data['Users']['name']) && $this->data['Users']['name']!='name') && $this->data['Users']['user_group_id'] == "" && $this->data['Users']['active'] == "")

			 {

				 $this->set('name', $this->data['Users']['name']); 

				 $condition =   array('User.name LIKE' => '%' . $this->data['Users']['name'] . '%');   

						  

			 }

			 if(($this->data['Users']['name'] == "" || $this->data['Users']['name']=='name') && $this->data['Users']['user_group_id'] != "" && $this->data['Users']['active'] == "")

			 {

				 $this->set('user_group_id', $this->data['Users']['user_group_id']); 

				 $condition =   array('User.user_group_id' => $this->data['Users']['user_group_id'] );   

						  

			 }

			 if(($this->data['Users']['name'] == "" || $this->data['Users']['name']=='name') && $this->data['Users']['user_group_id'] == "" && $this->data['Users']['active'] != "")

			 {

				 $this->set('active', $this->data['Users']['active']); 

				 $condition =   array('User.active '  => $this->data['Users']['active'] );   

						  

			 }

			 

			if((!empty($this->data['Users']['name'] ) && $this->data['Users']['name']!='name') && $this->data['Users']['active'] == "" && $this->data['Users']['user_group_id'] != "")

			 {

				 $this->set('name', $this->data['Users']['name']); 

				 $this->set('user_group_id', $this->data['Users']['user_group_id']); 

				 $condition = 	array (	'AND' => array ('User.name LIKE' => '%' . $this->data['Users']['name'] . '%', 'User.user_group_id' =>$this->data['Users']['user_group_id'] ));  

						  

			 } 

			 

			if((!empty($this->data['Users']['name'] ) && $this->data['Users']['name']!='name') && $this->data['Users']['active'] != "" && $this->data['Users']['user_group_id'] == "")

			 {

				 $this->set('name', $this->data['Users']['name']); 

				 $this->set('active', $this->data['Users']['active']); 

				 $condition = 	array (	'AND' => array ('User.name LIKE' => '%' . $this->data['Users']['name'] . '%', 'User.active' =>$this->data['Users']['active'] ));  

						  

			 } 



			 if(($this->data['Users']['name'] == "" || $this->data['Users']['name']=='name') && $this->data['Users']['user_group_id'] != "" && $this->data['Users']['active'] != "")

			 {

				 $this->set('active', $this->data['Users']['active']); 

				 $this->set('user_group_id', $this->data['Users']['user_group_id']); 

				 $condition = 	array (	'AND' => array ('User.user_group_id' => $this->data['Users']['user_group_id'], 'User.active' =>$this->data['Users']['active'] ));

						  

			 }	



			if((!empty($this->data['Users']['name'] ) && $this->data['Users']['name']!='name') && $this->data['Users']['active'] != "" && $this->data['Users']['user_group_id'] != "")

			 {

				 $this->set('name', $this->data['Users']['name']); 

				  $this->set('user_group_id', $this->data['Users']['user_group_id']);

				 $this->set('active', $this->data['Users']['active']); 

				 $condition = 	array (	'AND' => array ('User.name LIKE' => '%' . $this->data['Users']['name'] . '%', 'User.user_group_id' => $this->data['Users']['user_group_id'],'User.active' =>$this->data['Users']['active'] ));  

						  

			 } 	

			 

			 

			 

			//----------------------------------At the time of sorting Filteration on basis of these fields------------------------------

			 if(!empty($this->params['named'])){

			 

					 if((isset($this->params['named']['name'] ) && $this->params['named']['name']!='name') && !isset($this->params['named']['user_group_id']) && !isset($this->params['named']['active']))

					 {

						 $this->set('name', $this->params['named']['name']); 

				         $condition =   array('User.name LIKE' => '%' . $this->params['named']['name'] . '%');    

								  

					 }

					 

					if((!isset($this->params['named']['name'])|| $this->params['named']['name']=='name') && isset($this->params['named']['user_group_id']) && !isset($this->params['named']['active']))

					 {

						 $this->set('user_group_id', $this->params['named']['user_group_id']); 

				         $condition =   array('User.user_group_id '  => $this->params['named']['user_group_id'] );    

								  

					 }

					 

					 if((!isset($this->params['named']['name'])|| $this->params['named']['name']=='name') && !isset($this->params['named']['user_group_id']) && isset($this->params['named']['active']))

					 {

						 $this->set('active', $this->params['named']['active']); 

				         $condition =   array('User.active ' => $this->params['named']['active'] );    

								  

					 } 

					 

					 

					 if((isset($this->params['named']['name'] ) && $this->params['named']['name']!='name') && isset($this->params['named']['user_group_id']) && !isset($this->params['named']['active']))

					 {

						 $this->set('name', $this->params['named']['name']); 

				         $this->set('user_group_id', $this->params['named']['user_group_id']); 

				         $condition = 	array (	'AND' => array ('User.name LIKE' => '%' . $this->params['named']['name'] . '%', 'User.user_group_id' =>$this->params['named']['user_group_id'] ));  

								  

					 }  

					 

					 if((isset($this->params['named']['name'] ) && $this->params['named']['name']!='name') && !isset($this->params['named']['user_group_id']) && isset($this->params['named']['active']))

					 {

						 $this->set('active', $this->params['named']['active']); 

				          $this->set('name', $this->params['named']['name']); 

				         $condition = 	array (	'AND' => array ('User.name LIKE' => '%' . $this->params['named']['name'] . '%', 'User.active' =>$this->params['named']['active'] ));

								  

					 } 

					 if((!isset($this->params['named']['name'] )) && isset($this->params['named']['user_group_id']) && isset($this->params['named']['active']))

					 {

						 $this->set('active', $this->params['named']['active']); 

				         $this->set('user_group_id', $this->params['named']['user_group_id']); 

				         $condition = 	array (	'AND' => array ('User.user_group_id' => $this->params['named']['user_group_id'], 'User.active' =>$this->params['named']['active'] ));

								  

					 } 	

					 if((isset($this->params['named']['name'] ) && $this->params['named']['name']=='name') && isset($this->params['named']['user_group_id']) && isset($this->params['named']['active']))

					 {

						 $this->set('name', $this->params['named']['name']); 

				         $this->set('user_group_id', $this->params['named']['user_group_id']);

				         $this->set('active', $this->params['named']['active']); 

				         $condition = 	array (	'AND' => array ('User.name LIKE' => '%' . $this->params['named']['name'] . '%', 'User.user_group_id' => $this->params['named']['user_group_id'],'User.active' =>$this->params['named']['active'] )); 

								  

					 } 					 				 

			}		 		 

			 



		 

			  $data = $this->paginate('User', $condition);

		      $this->set('Users', $data); 

	 }


	 function  addNewUser()
	 {
	 		 
	   if(isset($this->data))
	   {
	    	$this->User->set($this->data['users']);
			if($this->data['users']!=''){
					if ($this->User->validates()) {
									
									if($this->Session->read('user_data')) {
										$this->Session->delete('user_data');
									}
									//making data array so we can pass in save mathod
									
									
									$this->loadModel('City');			
									$cityResult=$this->City->find('first',array('fields'=>array('City.id'),'conditions'=>"City.cityname LIKE '".$this->data['users']['city']."'"));
									if(!empty($cityResult)) {
										$city_id = $cityResult['City']['id'];
									} else {
										$saveCityData='';
										$saveCityData['City']['cityname']=ucwords(strtolower($this->data['users']['city']));
										$saveCityData['City']['page_url']=$this->common->makeAlias(trim($this->data['users']['city']));
										$saveCityData['City']['state_id']=$this->data['users']['state'];
										$saveCityData['City']['county_id']=$this->data['users']['county'];
										$this->City->save($saveCityData);
										$city_id = $this->City->getLastInsertId();
									}
									
									$saveArray = array();
									$saveArray['User']['name']     = $this->data['users']['name'];
									$saveArray['User']['email']    = $this->data['users']['email'];
									$saveArray['User']['username'] = $this->data['users']['username'];
									$saveArray['User']['password'] = $this->Auth->password($this->data['users']['password']);
									$saveArray['User']['commission'] = $this->data['users']['commission'];
									$saveArray['User']['cpassword'] = $this->Auth->password($this->data['users']['cpassword']);

									if($saveArray['User']['password'] != $saveArray['User']['cpassword'])
									{
									 $this->Session->setFlash('Password not match with confirm password.');  
									 $this->redirect(array('action' => "user",'message'=>'error'));
									}

									$saveArray['User']['address']  = $this->data['users']['address'];
									$saveArray['User']['city']     = $city_id;
									$saveArray['User']['county']   = $this->data['users']['county'];
									$saveArray['User']['state']    = $this->data['users']['state'];
									$saveArray['User']['zip']      = $this->data['users']['zip'];
									$saveArray['User']['phoneno']  = $this->data['users']['phoneno'];
									$saveArray['User']['user_group_id'] = $this->data['users']['user_group_id'];
									$saveArray['User']['active']   = $this->data['users']['active'];

									$this->User->save($saveArray);
								
								$url = FULL_BASE_URL.Router::url('/', false).'admins';
								$arrayTags = array("[name]","[username]","[password]","[link]");
								$arrayReplace = array($this->data['users']['name'],$this->data['users']['username'],$this->data['users']['password'],$url);
								
								//get Mail format
								$this->loadModel('Setting');
								$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.member_subject','Setting.member_body')));
								$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['member_subject']);
								$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['member_body']);
		
									// Here we are sending email to newly created user with login detail and login url
									$this->Email->sendAs = 'html';
									$this->Email->to = $this->data['users']['email'];
									$this->Email->subject = $subject;
									$this->Email->replyTo = $this->common->getReturnEmail();
									$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
									$this->body = '';				
									//$this->body = $this->emailhtml->email_header();
									$this->body .= $bodyText;
									//$this->body .= $this->emailhtml->email_footer();
									
									$this->Email->send($this->body);
					
							///////////////////////////sent mail insert to sent box ///////////////////
								$this->common->sentMailLog($this->common->getSalesEmail(),$this->data['users']['email'],strip_tags($subject),$this->body,"new_admin_user_registration");
							/////////////////////////////////////////////////////////////////////////
	
									$this->Session->setFlash('Your data has been submitted successfully.');  
									$this->redirect(array('action' => "user",'message'=>'success'));

						}else{  

									/*setting error message if validation fails*/
									$this->Session->write('user_data',$this->data['users']);
									$errors = $this->User->invalidFields();	
									$this->Session->setFlash(implode('<br>', $errors));  
									$this->redirect(array('action' => "user", 'message'=>'error'));

						}

				 }

	   		}

	 }

	 


	function userEditDetail($id=null){

	    

		$this->set('StatesList',$this->common->getAllState());  //  List states

		$this->set('CitiesList',$this->common->getAllCity());   //  List cities

		$this->set('CountyList',$this->common->getAllCounty()); //  List counties

		$this->set('UserGroupList',$this->common->getAllUserGroup()); //  List usergroups
		
		$currentAdmin = $this->Auth->user();
		
/////////////////////////////////////////////////////		
				
		$permissions=$this->common->adminDetails();
		

/////////////////////////////////////////////////////		
		
		if(($currentAdmin['Admin']['id']!=$id) and (!in_array(2,$permissions))) //redirect, if authorization failed
			
			$this->redirect(array('controller'=>'admins','action'=>'home'));
			
		$this->set('User',$this->User->userEditDetail($id));

	}

	

	/*------------------------------Function to Delete User------------------------------------*/

		function userDelete($id) {

			$this->User->id = $id;
			if($this->Session->read('Auth.Admin.id')==$id) {
				$this->Session->setFlash('You can\'t delete your own profile.');
				$this->redirect(array('action'=>'user','message'=>'error'));				
			}
		else {

			$this->User->delete($id);

			$this->Session->setFlash('The User with id: '.$id.' has been deleted.');

			$this->redirect(array('action'=>'user'));
		}
		}

		

	/*------------------------------Function to Edit Particular User------------------------------------*/	

	

	function editUser(){

	$currentAdmin = $this->Auth->user();

	if(isset($this->data))
	   {
	    	$this->User->set($this->data['users']);
			if($this->data['users']!=''){

					if ($this->User->validates(array('fieldList' => array('name','email','username','user_group_id','city','state','county')))) {

									//making data array so we can pass in save mathod
									$saveArray = array();
									$saveArray['User']['name']     = $this->data['users']['name'];
									$saveArray['User']['email']    = $this->data['users']['email'];
									$saveArray['User']['username'] = $this->data['users']['username'];
									
									if(trim($this->data['users']['password'])!=''){
										if($this->data['users']['password'] != $this->data['users']['cpassword'])
										{
										 $this->Session->setFlash('Password not match with confirm password.');  
										 $this->redirect(array('action' => "userEditDetail/".$this->data['users']['id'],'message'=>'error'));
										}
									}

									if(isset($this->data['users']['password']) && $this->data['users']['password']) {
								      $saveArray['User']['password'] = $this->Auth->password($this->data['users']['password']);
							        }
							        else {
								         $saveArray['User']['password'] = $this->data['users']['oldpassword'];
							         }
									
									$this->loadModel('City');			
									$cityResult=$this->City->find('first',array('fields'=>array('City.id'),'conditions'=>"City.cityname LIKE '".$this->data['users']['city']."'"));
									if(!empty($cityResult)) {
										$city_id = $cityResult['City']['id'];
									} else {
										$saveCityData='';
										$saveCityData['City']['cityname']=ucwords(strtolower($this->data['users']['city']));
										$saveCityData['City']['page_url']=$this->common->makeAlias(trim($this->data['users']['city']));
										$saveCityData['City']['state_id']=$this->data['users']['state'];
										$saveCityData['City']['county_id']=$this->data['users']['county'];
										$this->City->save($saveCityData);
										$city_id = $this->City->getLastInsertId();
									}
									$saveArray['User']['commission'] = $this->data['users']['commission'];
									$saveArray['User']['address']  = $this->data['users']['address'];
									$saveArray['User']['city']     = $city_id;
									$saveArray['User']['county']   = $this->data['users']['county'];
									$saveArray['User']['state']    = $this->data['users']['state'];
									$saveArray['User']['zip']      = $this->data['users']['zip'];
									$saveArray['User']['phoneno']  = $this->data['users']['phoneno'];
									$saveArray['User']['user_group_id'] = $this->data['users']['user_group_id'];
									$saveArray['User']['active']   = $this->data['users']['active'];

									$this->User->save($saveArray);
									$this->Session->setFlash('Your data has been updated successfully.'); 
									if($currentAdmin['Admin']['user_group_id']!=5){ 
										$this->redirect(array('action' => "user",'message'=>'success'));
									}else{
										$this->redirect(array('action' => "userEditDetail/".$this->data['users']['id']));
									}
						}else{  

									/*setting error message if validation fails*/
									$errors = $this->User->invalidFields();	
									$this->Session->setFlash(implode('<br>', $errors));  
									$this->redirect(array('action' => "userEditDetail/".$this->data['users']['id'], 'message'=>'error'));

						}

				 }

	   }


	}





   //----------------------------Listing of User group----------------------------------------------------------

	function userGroup()

	{

	   if(isset($this->params['named']['message']))

	   {

	      if($this->params['named']['message']=='success')

		  {

		     $this->set('success','success');

		  }else{

		   $this->set('error','error');

		  }

	   }

	          $condition='';

			  App::import('model','UserGroup'); // importing UserGroup (pages) model

		      $this->UserGroup = new UserGroup();

			  

			  $this->set('group_name', 'name'); 

			  $this->set('active', ''); 

			 

			  $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('UserGroup.id' => 'asc'));

			   #setting diff condition in paginate function according to search criteria

			 if((!empty($this->data['Users']['group_name']) && $this->data['Users']['group_name']!='name') && $this->data['Users']['active'] == "")

			 {

				 $this->set('group_name', $this->data['Users']['group_name']); 

				 $condition =   array('UserGroup.group_name LIKE' => '%' . $this->data['Users']['group_name'] . '%');   

						  

			 }

			 if(($this->data['Users']['group_name'] == "" || $this->data['Users']['group_name']=='name') && $this->data['Users']['active'] != "")

			 {

				 $this->set('active', $this->data['Users']['active']); 

				 $condition =   array('UserGroup.active'  => $this->data['Users']['active'] );   

						  

			 }

			 if((!empty($this->data['Users']['group_name'] ) && $this->data['Users']['group_name']!='name') && $this->data['Users']['active'] != "")

			 {

				 $this->set('group_name', $this->data['Users']['group_name']); 

				 $this->set('active', $this->data['Users']['active']); 

				 $condition = 	array (	'AND' => array ('UserGroup.group_name LIKE' => '%' . $this->data['Users']['group_name'] . '%', 'UserGroup.active' =>$this->data['Users']['active'] ));  

						  

			 } 

			 

			  //----------------------------------At the time of sorting Filteration on basis of these fields------------------------------

			 if(!empty($this->params['named'])){

			 

					 if((isset($this->params['named']['group_name'] ) && $this->params['named']['group_name']!='name') && !isset($this->params['named']['active']))

					 {

						 $this->set('group_name', $this->params['named']['group_name']); 

						 $condition =   array('UserGroup.group_name LIKE' => '%' . $this->params['named']['group_name'] . '%');   

								  

					 }

					 if((!isset($this->params['named']['group_name'])|| $this->params['named']['group_name']=='name') && isset($this->params['named']['active']))

					 {

						 $this->set('active', $this->params['named']['active']); 

						 $condition =   array('UserGroup.active ' => $this->params['named']['active']);   

								  

					 } 

					 if((isset($this->params['named']['group_name'] ) && $this->params['named']['group_name']!='name') && isset($this->params['named']['active']))

					 {

						 $this->set('group_name', $this->params['named']['group_name']); 

						 $this->set('active', $this->params['named']['active']);

						 $condition = 	array (	'AND' => array ('UserGroup.group_name LIKE' => '%' . $this->params['named']['title'] . '%', 'UserGroup.active' =>$this->params['named']['active'] ));    

								  

					 }  

			}

			  $data = $this->paginate('UserGroup', $condition);

		      $this->set('UserGroups', $data); 

	}

	

	/*------------------------------Function to Add  User Group------------------------------------*/

	

	function addUserGroup()

	{

	   App::import('model', 'UserGroup');

	   $this->UserGroup = new UserGroup;

	   

	   if(isset($this->data))

	   {

	    	$this->UserGroup->set($this->data['users']);

			if($this->data['users']!=''){



					if ($this->UserGroup->validates()) {

									//making data array so we can pass in save mathod

									$saveArray = array();

									$saveArray['UserGroup']['group_name'] = $this->data['users']['group_name'];

									$saveArray['UserGroup']['active'] = $this->data['users']['active'];
									if($this->data['users']['permissions']){
										$premissions =  $this->common->arrayToCsvString($this->data['users']['permissions']);
										$saveArray['UserGroup']['permissions'] = ','.$premissions.',';
									}else{
										$saveArray['UserGroup']['permissions'] = '';
									}

																									

									$this->UserGroup->save($saveArray);
									$this->Session->setFlash('Your data has been submitted successfully.');  
									$this->redirect(array('action' => "userGroup",'message'=>'success'));


						}else{

									/*setting error message if validation fails*/

									$errors = $this->UserGroup->invalidFields();	

									$this->Session->setFlash(implode('<br>', $errors));  

									$this->redirect(array('action' => "userGroup", 'message'=>'error'));

						}

				 }

	   }



	} 

	

		/*------------------------------Function to Delete Group------------------------------------*/

		function groupDelete($id) {

			App::import('model','UserGroup'); // importing UserGroup model

			$this->UserGroup = new UserGroup(); 

			$this->UserGroup->id = $id;

			       

			//$counts = $this->User->find('count', array('conditions' => array('User.user_group_id' =>$id)));
			if($id!=1) {

				$counts = $this->User->query("SELECT COUNT(*) as count FROM users WHERE user_group_id =".$id.";");
		
				if($counts[0][0]['count'] == 0){
		
					$this->UserGroup->delete($id);
		
					$this->Session->setFlash('The Group with id: '.$id.' has been deleted.');
	
				} else {
	
					$this->Session->setFlash('Group not deleted because The Group with id: '.$id.' has been assigned to some users.');
	
				}
				
			}
			$this->redirect(array('action'=>'UserGroup'));

		}

		

	/*------------------------------Function to Edit Particular Group------------------------------------*/	

	

	

	function groupEdit($id=null){



			App::import('model','UserGroup'); // importing UserGroup model

			$this->UserGroup = new UserGroup(); 

			$this->UserGroup->id = $id;



			$this->UserGroup->set($this->data['users']);	



			if ($this->UserGroup->validates()) {


							//making data array so we can pass in save mathod

							$saveArray = array();

							$saveArray['UserGroup']['group_name'] = $this->data['users']['group_name'];

							$saveArray['UserGroup']['active'] = $this->data['users']['active'];
							
							if($saveArray['UserGroup']['id']==1) {
								$saveArray['UserGroup']['active'] = 'yes';
							}

							if($this->data['users']['permissions']){
								$premissions =  $this->common->arrayToCsvString($this->data['users']['permissions']);

								$saveArray['UserGroup']['permissions'] = ','.$premissions.',';
							}else{
								$saveArray['UserGroup']['permissions'] = '';
							}

							$this->UserGroup->save($saveArray);

							$this->Session->setFlash('Your data has been updated successfully.');  

							$this->redirect(array('action' => "UserGroup"));



			} else{  



							/*setting error message if validation fails*/

							$errors = $this->UserGroup->invalidFields();	

							$this->Session->setFlash(implode('<br>', $errors));  

							$this->redirect(array('action' => "groupEditDetail/".$this->data['users']['id'])); 

			}



		}

		

		

		function groupEditDetail($id=null){



		App::import('model', 'UserGroup');

		$this->UserGroup = new UserGroup;

		$this->set('UserGroup',$this->UserGroup->groupEditDetail($id));

	}

	

   /*

    this function is checking username and password in database

	and if true then redirect to home page

	*/



	function beforeFilter() { 



        $this->Auth->fields = array(

            'username' => 'username', 

            'password' => 'password'

            );



			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');

   	}

	/* This function is setting all info about current Admins in 

	currentAdmin array so we can use it anywhere lie name id etc.

	*/

	function beforeRender(){

		$this->set('currentAdmin', $this->Auth->user());

		$this->set('cssName',$this->Cookie->read('css_name'));
		
        $this->set('groupDetail',$this->common->adminDetails());
		
		$this->set('common',$this->common);
		
		//$this->Ssl->force();

	} 

}//end class

?>