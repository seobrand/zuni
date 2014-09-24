<?php 
/*
   Coder: Keshav Sharma
   Date : 24 Nov 2011
*/ 
class ReferredFriendsController extends AppController {
      var $name = 'ReferredFriends';
	  var $helpers = array('Html','Form','User','Javascript','Text','Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
 	  var $components = array('Auth','common','Session','Cookie','RequestHandler','Email','emailhtml');

	 #this function call by default when a controller is called
	 function index()
	 {
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;	
		if($this->Session->check('Auth.Admin'))
		{
		   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   $this->set('common',$this->common);
		  //$condition[] = 'FrontUser.user_type!="parent"';
		  $condition[] = '';
		   $this->set('name','Name');
		   $this->set('county_id','');
		   $this->set('status','');		   
	       $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('ReferredFriend.id' => 'desc'));

		if((isset($this->data['ReferredFriend']['name']) && $this->data['ReferredFriend']['name'] !='Name') ||  (isset($this->params['named']['name']) && $this->params['named']['name'] !='Name'))
		
		 {
		if(isset($this->params['named']['name']))
		{
		    $condition[] = 'ReferredFriend.name LIKE "%' . str_replace("%20"," ",$this->params['named']['name']). '%"';
		}
		else
		{
		 	$condition[] = 'ReferredFriend.name LIKE "%' .$this->data['ReferredFriend']['name']. '%"';
		 }
		(empty($this->params['named'])) ? $this->set('name', $this->data['ReferredFriend']['name']) :$this->set('name', $this->params['named']['name']) ; 
		 } 
				 
	if($this->data['ReferredFriend']['county_id']!='' ||  isset($this->params['named']['county_id'] )) 
	{
		  if(isset($this->params['named']['county_id']))
		  {
			 $condition[] = 'ReferredFriend.county_id = '.$this->params['named']['county_id'];
		  }
		  else
		  {
			  $condition[] = 'ReferredFriend.county_id = '.$this->data['ReferredFriend']['county_id'];
		  }
					  
		 (empty($this->params['named'])) ? $this->set('county_id', $this->data['ReferredFriend']['county_id']) :$this->set('county_id', $this->params['named']['county_id']) ; 
	}
				 

	 if((isset($this->data['ReferredFriend']['status']) && $this->data['ReferredFriend']['status']!='') || (isset($this->params['named']['status']) && $this->params['named']['status']!='')) 
	 {
		  if(isset($this->params['named']['status']))
		  {
			 $condition[] = 'ReferredFriend.status = "'.$this->params['named']['status'].'"';
		  }
		  else
		  {
			 $condition[] = 'ReferredFriend.status = "'.$this->data['ReferredFriend']['status'].'"';
		  }
					   
	(empty($this->params['named'])) ? $this->set('status', $this->data['ReferredFriend']['status']) :$this->set('status', $this->params['named']['status']) ; 
	}
				 
				$data = $this->paginate('ReferredFriend', $condition);
		        $this->set('ReferredFriends', $data); 
 
			}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}

	 }
	 
	 function suspicious()
	 {
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;	
		if($this->Session->check('Auth.Admin'))
		{
		   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   $this->set('common',$this->common);
		  //$condition[] = 'FrontUser.user_type!="parent"';
		  $condition[] = 'ReferredFriend.refer_ip=ReferredFriend.refered_ip';
		   $this->set('name','Name');
		   $this->set('county_id','');
		   $this->set('status','');		   
	       $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('ReferredFriend.id' => 'desc'));

		if((isset($this->data['ReferredFriend']['name']) && $this->data['ReferredFriend']['name'] !='Name') ||  (isset($this->params['named']['name']) && $this->params['named']['name'] !='Name'))
		
		 {
		if(isset($this->params['named']['name']))
		{
		    $condition[] = 'ReferredFriend.name LIKE "%' . str_replace("%20"," ",$this->params['named']['name']). '%"';
		}
		else
		{
		 	$condition[] = 'ReferredFriend.name LIKE "%' .$this->data['ReferredFriend']['name']. '%"';
		 }
		(empty($this->params['named'])) ? $this->set('name', $this->data['ReferredFriend']['name']) :$this->set('name', $this->params['named']['name']) ; 
		 } 
				 
	if($this->data['ReferredFriend']['county_id']!='' ||  isset($this->params['named']['county_id'] )) 
	{
		  if(isset($this->params['named']['county_id']))
		  {
			 $condition[] = 'ReferredFriend.county_id = '.$this->params['named']['county_id'];
		  }
		  else
		  {
			  $condition[] = 'ReferredFriend.county_id = '.$this->data['ReferredFriend']['county_id'];
		  }
					  
		 (empty($this->params['named'])) ? $this->set('county_id', $this->data['ReferredFriend']['county_id']) :$this->set('county_id', $this->params['named']['county_id']) ; 
	}
				 
				$data = $this->paginate('ReferredFriend', $condition);
		        $this->set('ReferredFriends', $data); 
 
			}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}

	 }
	 
	 
	function view($id=null){
		$this->ReferredFriend->id = $id;
		$this->set('data',$this->ReferredFriend->read());
	}
	  
	  function delete($id=null){ 
	  	 $this->ReferredFriend->delete($id);
		 $this->Session->setFlash('Referred User has been deleted.');
		 $this->redirect(array('action'=>'index'));
	  }
	  
	 function nonCdelete($id=null){  	
	  	 $this->ReferredFriend->delete($id);
		 $this->Session->setFlash('Referred User has been deleted.');
		 $this->redirect(array('action'=>'nonConfirmEmail'));
	  }
	 function Condelete($id=null){	
	  	 $this->ReferredFriend->delete($id);
		 $this->Session->setFlash('Referred User has been deleted.');
		 $this->redirect(array('action'=>'confirmEmail'));
	  }  
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocomplete($string='') {
			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			App::import('model', 'ReferredFriend');
			$this->ReferredFriend = new ReferredFriend;
			$name = $this->ReferredFriend->query("SELECT ReferredFriend.name FROM referred_friends AS ReferredFriend WHERE ReferredFriend.name LIKE '$string%'");
			foreach($name as $name) {
				$arr[] = $name['ReferredFriend']['name'];
			}
			echo json_encode($arr);
			}
	}
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function nonConfirmEmail($string='') {
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;	
		if($this->Session->check('Auth.Admin'))
		{
		   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   $this->set('SchoolList',$this->common->getAllSchool()); //  List counties
		   $this->set('ChildList',$this->common->getAllChild()); //  List counties
		   
		   $this->set('common',$this->common);
		   $condition[] = 'FrontUser.user_type="parent" AND ReferredFriend.status="no"';
		   $this->set('name','Name');
		   $this->set('county_id','');
		   $this->set('school_id','');
		   $this->set('child_id','');  
	       $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('ReferredFriend.id' => 'desc'));

		if((isset($this->data['ReferredFriend']['name']) && $this->data['ReferredFriend']['name'] !='Name') ||  (isset($this->params['named']['name']) && $this->params['named']['name'] !='Name'))
		
		 {
		if(isset($this->params['named']['name']))
		{
		    $condition[] = 'ReferredFriend.name LIKE "%' . str_replace("%20"," ",$this->params['named']['name']). '%"';
		}
		else
		{
		 	$condition[] = 'ReferredFriend.name LIKE "%' .$this->data['ReferredFriend']['name']. '%"';
		 }
		(empty($this->params['named'])) ? $this->set('name', $this->data['ReferredFriend']['name']) :$this->set('name', $this->params['named']['name']) ; 
		 } 
				 
	if($this->data['ReferredFriend']['county_id']!='' ||  isset($this->params['named']['county_id'] )) 
	{
		  if(isset($this->params['named']['county_id']))
		  {
			 $condition[] = 'ReferredFriend.county_id = '.$this->params['named']['county_id'];
		  }
		  else
		  {
			  $condition[] = 'ReferredFriend.county_id = '.$this->data['ReferredFriend']['county_id'];
		  }
					  
		 (empty($this->params['named'])) ? $this->set('county_id', $this->data['ReferredFriend']['county_id']) :$this->set('county_id', $this->params['named']['county_id']);
	}				 

	 if((isset($this->data['ReferredFriend']['school_id']) && $this->data['ReferredFriend']['school_id']!='') || (isset($this->params['named']['school_id']) && $this->params['named']['school_id']!='')) 
	 {
		  if(isset($this->params['named']['school_id']))
		  {
			 $condition[] = 'ReferredFriend.school_id = "'.$this->params['named']['school_id'].'"';
		  }
		  else
		  {
			 $condition[] = 'ReferredFriend.school_id = "'.$this->data['ReferredFriend']['school_id'].'"';
		  }
(empty($this->params['named'])) ? $this->set('school_id', $this->data['ReferredFriend']['school_id']) :$this->set('school_id', $this->params['named']['school_id']);
	}
	 if((isset($this->data['ReferredFriend']['child_id']) && $this->data['ReferredFriend']['child_id']!='') || (isset($this->params['named']['child_id']) && $this->params['named']['child_id']!=''))
	 {
		  if(isset($this->params['named']['child_id']))
		  {
			 $condition[] = 'ReferredFriend.kid_id = "'.$this->params['named']['child_id'].'"';
		  }
		  else
		  {
			 $condition[] = 'ReferredFriend.kid_id = "'.$this->data['ReferredFriend']['child_id'].'"';
		  }
	(empty($this->params['named'])) ? $this->set('child_id', $this->data['ReferredFriend']['child_id']) :$this->set('child_id', $this->params['named']['child_id']); 
	}
				$data = $this->paginate('ReferredFriend', $condition);
		        $this->set('ReferredFriends', $data);
			}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}
	 }
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function confirmEmail($string='') {
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;
		if($this->Session->check('Auth.Admin'))
		{
		   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   $this->set('SchoolList',$this->common->getAllSchool());
		   $this->set('ChildList',$this->common->getAllChild());
		   $this->set('TeacherList',$this->common->getAllTeacher());
		   $this->set('common',$this->common);
		    $condition[] = 'FrontUser.user_type="parent" AND ReferredFriend.status="yes"';
		   $this->set('name','Name');
		   $this->set('county_id','');
		   $this->set('school_id','');
		   $this->set('child_id','');
		   $this->set('teacher','');
	       $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('ReferredFriend.id' => 'desc'));
		if((isset($this->data['ReferredFriend']['name']) && $this->data['ReferredFriend']['name'] !='Name') ||  (isset($this->params['named']['name']) && $this->params['named']['name'] !='Name'))		
		 {
		if(isset($this->params['named']['name']))
		{
		    $condition[] = 'ReferredFriend.name LIKE "%' . str_replace("%20"," ",$this->params['named']['name']). '%"';
		}
		else
		{
		 	$condition[] = 'ReferredFriend.name LIKE "%' .$this->data['ReferredFriend']['name']. '%"';
		 }
		(empty($this->params['named'])) ? $this->set('name', $this->data['ReferredFriend']['name']) :$this->set('name', $this->params['named']['name']) ;
		 }
	if($this->data['ReferredFriend']['county_id']!='' ||  isset($this->params['named']['county_id'] )) 
	{
		  if(isset($this->params['named']['county_id']))
		  {
			 $condition[] = 'ReferredFriend.county_id = '.$this->params['named']['county_id'];
		  }
		  else
		  {
			  $condition[] = 'ReferredFriend.county_id = '.$this->data['ReferredFriend']['county_id'];
		  }
		 (empty($this->params['named'])) ? $this->set('county_id', $this->data['ReferredFriend']['county_id']) :$this->set('county_id', $this->params['named']['county_id']);
	}
	 if((isset($this->data['ReferredFriend']['school_id']) && $this->data['ReferredFriend']['school_id']!='') || (isset($this->params['named']['school_id']) && $this->params['named']['school_id']!='')) 
	 {
		  if(isset($this->params['named']['school_id']))
		  {
			 $condition[] = 'ReferredFriend.school_id = "'.$this->params['named']['school_id'].'"';
		  }
		  else
		  {
			 $condition[] = 'ReferredFriend.school_id = "'.$this->data['ReferredFriend']['school_id'].'"';
		  }
	(empty($this->params['named'])) ? $this->set('school_id', $this->data['ReferredFriend']['school_id']) :$this->set('school_id', $this->params['named']['school_id']) ;
	}
	 if((isset($this->data['ReferredFriend']['child_id']) && $this->data['ReferredFriend']['child_id']!='') || (isset($this->params['named']['child_id']) && $this->params['named']['child_id']!=''))
	 {
		  if(isset($this->params['named']['child_id']))
		  {
			 $condition[] = 'ReferredFriend.kid_id = "'.$this->params['named']['child_id'].'"';
		  }
		  else
		  {
			 $condition[] = 'ReferredFriend.kid_id = "'.$this->data['ReferredFriend']['child_id'].'"';
		  }
	(empty($this->params['named'])) ? $this->set('child_id', $this->data['ReferredFriend']['child_id']) :$this->set('child_id', $this->params['named']['child_id']) ; 
	}
	 if((isset($this->data['ReferredFriend']['teacher']) && $this->data['ReferredFriend']['teacher']!='') || (isset($this->params['named']['teacher']) && $this->params['named']['teacher']!=''))
	 {
		  if(isset($this->params['named']['teacher']))
		  {
			 $condition[] = 'FrontUser.teacher = "'.$this->params['named']['teacher'].'"';
		  }
		  else
		  {
			 $condition[] = 'FrontUser.teacher = "'.$this->data['ReferredFriend']['teacher'].'"';
		  }
	(empty($this->params['named'])) ? $this->set('teacher', $this->data['ReferredFriend']['teacher']) :$this->set('teacher', $this->params['named']['teacher']);
	}
				$data = $this->paginate('ReferredFriend', $condition);
		        $this->set('ReferredFriends', $data);
			}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}
	 }
//---------------------------------destroy all current sessions for a perticular SuperAdmins and redirect to login page automatically------------------------------//
	function addreferral() {
		if(isset($this->data)) {
			if($this->data['ReferredFriend']['refer_file']['error']!=0){
				$this->Session->setFlash('Please upload a file.');
			}
			else if($this->data['ReferredFriend']['refer_file']['type']!='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && $this->data['ReferredFriend']['refer_file']['type']!='application/vnd.ms-excel') {
				$this->Session->setFlash('Please upload .xls file or .xlsx file.');
			}
			else if($this->data['ReferredFriend']['refer_file']['error']==0) {
				$docDestination = APP.'webroot/fundraiser/'.$this->data['ReferredFriend']['refer_file']['name'];
				@chmod(APP.'webroot/fundraiser',0777);
				move_uploaded_file($this->data['ReferredFriend']['refer_file']['tmp_name'], $docDestination) or die($docDestination);				
				
				//---------------------------------------------------------------------------------------------//
				if($this->data['ReferredFriend']['refer_file']['type']=='application/vnd.ms-excel') {
					require_once APP.'webroot/simplexls.class.php';
					$xls = new Spreadsheet_Excel_Reader($docDestination);
					for ($row=2;$row<=$xls->rowcount();$row++) {
						$parent = '';
						for ($col=1;$col<=$xls->colcount();$col++) {
									if($col==1) {
										$parent = $this->common->getParentDetails(trim($xls->val($row,$col)));
									} else {
									$check_refer = $this->common->checkReferral($xls->val($row,$col));
									if(empty($check_refer)) {
										if(is_array($parent) && !empty($parent) && trim($xls->val($row,$col))!='') {
											$kid = $this->common->getKid($parent['FrontUser']['id']);
											$savearr = '';
											$savearr['ReferredFriend']['id'] = '';
											$savearr['ReferredFriend']['email'] = $xls->val($row,$col);
											$savearr['ReferredFriend']['front_user_id'] = $parent['FrontUser']['id'];
											$savearr['ReferredFriend']['county_id'] = $parent['FrontUser']['county_id'];
											if(!empty($kid)) {
												$savearr['ReferredFriend']['kid_id'] = $kid['Kid']['id'];
												$savearr['ReferredFriend']['school_id'] = $kid['Kid']['school_id'];;
											}
											$savearr['ReferredFriend']['state_id'] = $this->common->getStateByCountyId($parent['FrontUser']['county_id']);
											$savearr['ReferredFriend']['status'] = 'no';
											$savearr['ReferredFriend']['refer_ip'] = $_SERVER['REMOTE_ADDR'];
											$savearr['ReferredFriend']['refered_date'] = mktime(0,0,0,date('m'),date('d'),date('Y'));
											$this->ReferredFriend->save($savearr);
										}
									}
								}	
						}
					}
				} else if($this->data['ReferredFriend']['refer_file']['type']=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
					require_once APP.'webroot/simplexlsx.class.php';
					$xlsx = new SimpleXLSX($docDestination);
					list($cols,) = $xlsx->dimension();
					$check = 1;
					foreach( $xlsx->rows() as $k => $r) {
							if($check!=1) {
								$parent = '';
								for( $i = 0; $i < $cols; $i++) {
									if(isset($r[$i]) && $r[$i]!='') {										
												if($i==0) {
													$parent = $this->common->getParentDetails(trim($r[$i]));											
												} else {
												$check_refer = $this->common->checkReferral($r[$i]);
												if(empty($check_refer)) {	
													if(is_array($parent) && !empty($parent)) {
														$kid = $this->common->getKid($parent['FrontUser']['id']);
														$savearr = '';
														$savearr['ReferredFriend']['id'] = '';
														$savearr['ReferredFriend']['email'] = $r[$i];
														$savearr['ReferredFriend']['front_user_id'] = $parent['FrontUser']['id'];
														$savearr['ReferredFriend']['county_id'] = $parent['FrontUser']['county_id'];
														if(!empty($kid)) {
															$savearr['ReferredFriend']['kid_id'] = $kid['Kid']['id'];
															$savearr['ReferredFriend']['school_id'] = $kid['Kid']['school_id'];;
														}
														$savearr['ReferredFriend']['state_id'] = $this->common->getStateByCountyId($parent['FrontUser']['county_id']);
														$savearr['ReferredFriend']['status'] = 'no';
														$savearr['ReferredFriend']['refer_ip'] = $_SERVER['REMOTE_ADDR'];
														$savearr['ReferredFriend']['refered_date'] = mktime(0,0,0,date('m'),date('d'),date('Y'));
														$this->ReferredFriend->save($savearr);
												}
											}
										}	
									}
								}
							}
						$check++;
					}
				}
				//---------------------------------------------------------------------------------------------//
				unlink($docDestination);
				$this->Session->setFlash('Excel sheet has been uploaded successfully.');
				$this->redirect(array('action'=>'nonConfirmEmail'));
			}
		}
	}
	
/*--------------------------------------------------- it is used to autocomplete the search box -----------------------------------------------------*/	 
	function sendreferral() {
			
			$this->loadModel('FrontUser');
			$p_user_id = $this->FrontUser->find('all',array('fields'=>'id','conditions'=>array('FrontUser.user_type'=>'parent'),'recursive'=>-1));
			foreach($p_user_id as $p_user_id) {
				$p_user_ids[] = $p_user_id['FrontUser']['id'];
			}			
			$User=$this->ReferredFriend->find('list', array('fields' => array('email', 'name'),'conditions'=>array('ReferredFriend.status'=>'no','ReferredFriend.front_user_id IN ('.implode(',',$p_user_ids).')'),'order' => 'ReferredFriend.name ASC','recursive'=>-1));
			$this->set('Users',$User);
			if(isset($this->data))
		  	{
					
						$msgError = '';
						/*pr($this->data);*/
		  				if($this->data['ReferredFriend']['massmail']!=1 && empty($this->data['ReferredFriend']['user']) && isset($this->data['ReferredFriend']['want_test']) && $this->data['ReferredFriend']['want_test'] == 0)
						{
							$msgError='Please Select User.';
						}
						else if($this->data['ReferredFriend']['massmail']!=1 && isset($this->data['ReferredFriend']['user'][0]) && $this->data['ReferredFriend']['user'][0]=='' && isset($this->data['ReferredFriend']['want_test']) && $this->data['ReferredFriend']['want_test'] == 0)
						{
							$msgError='Please Select User.';
						}
						
																	
						if($msgError=='')
						{
//-------------------------------------------------------------------------------------------------------------------------------------------------------------//												
							if($this->data['ReferredFriend']['want_test']==1 && $this->data['ReferredFriend']['test_email']!='') {							
								$emails[0] = $this->data['ReferredFriend']['test_email'];
							}
													
							else if($this->data['ReferredFriend']['massmail'] == 0)	// Single mail section
								{
									$emails = $this->data['ReferredFriend']['user'];
								}
								else	// Massmail section
								{
								$this->loadModel('FrontUser');
								$p_user_id = $this->FrontUser->find('all',array('fields'=>'id','conditions'=>array('FrontUser.user_type'=>'parent'),'recursive'=>-1));
								foreach($p_user_id as $p_user_id) {
									$p_user_ids[] = $p_user_id['FrontUser']['id'];
								}
								$condi = array('ReferredFriend.status'=>'no','ReferredFriend.front_user_id IN ('.implode(',',$p_user_ids).')');																
								$emails=$this->ReferredFriend->find('list', array('fields' => array('email'),'conditions'=>$condi,'order'=>'ReferredFriend.name ASC','recursive' => -1));
								}
								$this->loadModel('Setting');
								$mail_data  = $this->Setting->find('first',array('fields'=>array('Setting.referral_subject','Setting.referral_body')));								
								$place_marks = array('[PARENT_NAME]','[PARENT_FIRST_NAME]','[URL]','[FRIEND]','[COUNTY]');
							$url = '<a href="'.FULL_BASE_URL.router::url('/',false).'referral" target="_blank">'.FULL_BASE_URL.router::url('/',false).'referral</a>';
									$email_ids = array_chunk($emails, EMAIL_LIST);
									/*	$email_ids = '';
										$email_ids[] = array('keshav@planetwebsolution.com','manoj@planetwebsolution.com');
									*//*pr($email_ids);
										exit;*/
									set_time_limit(0);
									foreach($email_ids as $email_id) {						
											foreach($email_id as $email) {
											$parent_data = $this->common->getParent($email);
											if(isset($parent_data['name']) && $parent_data['name']!='') {
												$parent_name = $parent_data['name'];
											} else {
												$parent_name = 'Rickey Jackson';
											}
											
											if(isset($parent_data['first_name']) && $parent_data['first_name']!='') {
												$parent_fname= $parent_data['first_name'];
											} else {
												$parent_fname = 'Rickey';
											}
											
											if(isset($parent_data['friend']) && $parent_data['friend']!='') {
												$friend	= $parent_data['friend'];
											} else {
												$friend = 'Peter';
											}
											
											if(isset($parent_data['county']) && $parent_data['county']!='') {
												$county_name	= $parent_data['county'];
											} else {
												$county_name 	= 'Marion';
											}
																						
											$place_words = array($parent_name,$parent_fname,$url,$friend,$county_name);		
											
											$subject = str_replace($place_marks,$place_words,$mail_data['Setting']['referral_subject']);
											$msg = str_replace($place_marks,$place_words,$mail_data['Setting']['referral_body']);						
											$from=$this->common->getNewsLetterEmail();
												$this->Email->to 		= $email;
												$this->Email->subject 	= strip_tags($subject);
												$this->Email->replyTo 	= $this->common->getReturnEmail();
												$this->Email->from 		= $from;
												$this->Email->sendAs 	= 'html';
												//Set the body of the mail as we send it.			
												//seperate line in the message body.
												$this->body = '';				
												$this->body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Zuni</title></head><body>';
												$this->body .=$msg;
												$this->body .= '</body></html>';										
									/* SMTP Options */
										   $this->Email->smtpOptions = array(
												'port'=>'25', 
												'timeout'=>'30',
												'host' =>SMTP_HOST_NAME,
												'username'=>SMTP_USERNAME,
												'password'=>SMTP_PASSWORD
											);										
												/* Set delivery method */
												$this->Email->delivery = 'smtp';										
												/* Do not pass any args to send() */
												$this->Email->send($this->body);		
																						
											}
											//sleep(2);							
										}
										
										$this->Session->setFlash('Referral Email has been Sent Successfully!!');
										$this->redirect(array('action' => 'nonConfirmEmail'));
//-------------------------------------------------------------------------------------------------------------------------------------------------------------//					
					}
						else
						{
						$this->Session->setFlash($msgError);
						return false;	
						}	
	
				 }
	}
/*--------------------------------------------------- it is used to autocomplete the search box -----------------------------------------------------*/	 
	function sendreminder() {
			$this->loadModel('FrontUser');
			$p_user_id = $this->FrontUser->find('all',array('fields'=>'id','conditions'=>array('FrontUser.user_type'=>'parent'),'recursive'=>-1));
			foreach($p_user_id as $p_user_id) {
				$p_user_ids[] = $p_user_id['FrontUser']['id'];
			}			
			$User=$this->ReferredFriend->find('list', array('fields' => array('email', 'name'),'conditions'=>array('ReferredFriend.status'=>'no','ReferredFriend.front_user_id IN ('.implode(',',$p_user_ids).')'),'order' => 'ReferredFriend.name ASC','recursive'=>-1));
			$this->set('Users',$User);
			if(isset($this->data))
		  	{
					
						$msgError = '';
						/*pr($this->data);*/
		  				if($this->data['ReferredFriend']['massmail']!=1 && empty($this->data['ReferredFriend']['user']) && isset($this->data['ReferredFriend']['want_test']) && $this->data['ReferredFriend']['want_test'] == 0)
						{
							$msgError='Please Select User.';
						}
						else if($this->data['ReferredFriend']['massmail']!=1 && isset($this->data['ReferredFriend']['user'][0]) && $this->data['ReferredFriend']['user'][0]=='' && isset($this->data['ReferredFriend']['want_test']) && $this->data['ReferredFriend']['want_test'] == 0)
						{
							$msgError='Please Select User.';
						}
						if($msgError=='')
						{
//-------------------------------------------------------------------------------------------------------------------------------------------------------------//													
							if($this->data['ReferredFriend']['want_test']==1 && $this->data['ReferredFriend']['test_email']!='') {							
								$emails[0] = $this->data['ReferredFriend']['test_email'];
							}
													
							else if($this->data['ReferredFriend']['massmail'] == 0)	// Single mail section
								{
									$emails = $this->data['ReferredFriend']['user'];
								}
								else	// Massmail section
								{
								$this->loadModel('FrontUser');
								$p_user_id = $this->FrontUser->find('all',array('fields'=>'id','conditions'=>array('FrontUser.user_type'=>'parent'),'recursive'=>-1));
								foreach($p_user_id as $p_user_id) {
									$p_user_ids[] = $p_user_id['FrontUser']['id'];
								}
								$condi = array('ReferredFriend.status'=>'no','ReferredFriend.front_user_id IN ('.implode(',',$p_user_ids).')');									
								$emails=$this->ReferredFriend->find('list', array('fields' => array('email'),'conditions'=>$condi,'order'=>'ReferredFriend.name ASC','recursive' => -1));
								}
								$this->loadModel('Setting');
								$mail_data  = $this->Setting->find('first',array('fields'=>array('Setting.reminder_subject','Setting.reminder_body')));								
								$place_marks = array('[PARENT_NAME]','[PARENT_FIRST_NAME]','[URL]','[FRIEND]','[COUNTY]');
							$url = '<a href="'.FULL_BASE_URL.router::url('/',false).'referral" target="_blank">'.FULL_BASE_URL.router::url('/',false).'referral</a>';
									//pr($emails);
									//exit;
									set_time_limit(0);
									$email_ids = array_chunk($emails, EMAIL_LIST);
									foreach($email_ids as $email_id) {
											foreach($email_id as $email) {
											$parent_data = $this->common->getParent($email);											
											if(isset($parent_data['name']) && $parent_data['name']!='') {
												$parent_name = $parent_data['name'];
											} else {
												$parent_name = 'Rickey Jackson';
											}
											
											if(isset($parent_data['first_name']) && $parent_data['first_name']!='') {
												$parent_fname= $parent_data['first_name'];
											} else {
												$parent_fname = 'Rickey';
											}
											
											if(isset($parent_data['friend']) && $parent_data['friend']!='') {
												$friend	= $parent_data['friend'];
											} else {
												$friend = 'Peter';
											}
											
											if(isset($parent_data['county']) && $parent_data['county']!='') {
												$county_name	= $parent_data['county'];
											} else {
												$county_name 	= 'Marion';
											}
											
																				
											$place_words = array($parent_name,$parent_fname,$url,$friend,$county_name);
											$subject = str_replace($place_marks,$place_words,$mail_data['Setting']['reminder_subject']);
											$msg = str_replace($place_marks,$place_words,$mail_data['Setting']['reminder_body']);								
											$from=$this->common->getNewsLetterEmail();
												$this->Email->to 		= $email;
												$this->Email->subject 	= strip_tags($subject);
												$this->Email->replyTo 	= $this->common->getReturnEmail();
												$this->Email->from 		= $from;
												$this->Email->sendAs 	= 'html';
												//Set the body of the mail as we send it.			
												//seperate line in the message body.
												$this->body = '';				
												$this->body = $this->emailhtml->email_header();
												$this->body .=$msg;
												$this->body .= $this->emailhtml->email_footer();										
									/* SMTP Options */
										   $this->Email->smtpOptions = array(
												'port'=>'25', 
												'timeout'=>'30',
												'host' =>SMTP_HOST_NAME,
												'username'=>SMTP_USERNAME,
												'password'=>SMTP_PASSWORD
											);										
												/* Set delivery method */
												$this->Email->delivery = 'smtp';										
												/* Do not pass any args to send() */
												$this->Email->send($this->body);												
											}
											sleep(2);							
										}
										
										$this->Session->setFlash('Reminder Email has been Sent Successfully!!');
										$this->redirect(array('action' => 'nonConfirmEmail'));
//-------------------------------------------------------------------------------------------------------------------------------------------------------------//	
						}
						else
						{
						$this->Session->setFlash($msgError);
						return false;	
						}	
	
				 }
	}
	//---------------------------------------------------------------------------------------------------------------------------------//	
	function send_mail($email) {
			$this->autoRender = false;
			if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
					$this->loadModel('RegisteredEmail');
					$random = $this->common->randomPassword(8);
					$savearray['RegisteredEmail']['email'] = $email;
					$savearray['RegisteredEmail']['random'] = $random;
					$this->RegisteredEmail->save($savearray);				
					$url = FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/register/'.$random;
					$this->Email->sendAs = 'html';
					$this->Email->to = $email;
					$this->Email->subject = 'Registration Link';
					$this->Email->replyTo = $this->common->getReturnEmail();
					$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
					$this->body = '';
					$this->body = $this->emailhtml->email_header();	
					$this->body .="Dear Advertiser<br /><br />"; 
					$this->body .="Please click on given link for registration on Zuni.<br /><br />"; 
					$this->body .= $url.'<br /><br />';
					$this->body .="Thanks  <br /> Zuni Sales Team";
					$this->body .= $this->emailhtml->email_footer();
					$this->Email->smtpOptions = array(
				'port'=>'25', 
				'timeout'=>'30',
				'host' =>SMTP_HOST_NAME,
				'username'=>SMTP_USERNAME,
				'password'=>SMTP_PASSWORD
			);
			$this->Email->delivery = 'smtp';										
			$this->Email->send($this->body);
			
			///////////////////////////sent mail insert to sent box ///////////////////			

			$this->common->sentMailLog($this->common->getSalesEmail(),$email,"Registration Link",$this->body,"send_registration_link");
			////////////////////////////////////////////////////////////////////////////
			
				}
			}
/*--------------------------------------------------- it is used to autocomplete the search box -----------------------------------------------------*/	 
	function beforeFilter() {
			$this->Auth->fields = array(
				'username' => 'username',
				'password' => 'password'
				);
				$this->Auth->allow('send_mail');
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
}
?>