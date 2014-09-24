<?php 
/*
   Programmer : Manoj Pandit (Ishop Soldier)
   Date  	  : 21 March 2012
*/  
class FundraisersController extends AppController { 
	  var $name = 'Fundraisers';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax');  
	  var $layout = ''; //variable for admin layout
	  var $components = array('Auth','common','Cookie','RequestHandler','Email','Session','emailhtml');  //component to check authentication . this component file is exists in app/controllers/components
	  
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	    
		function index()
		{
		if ($this->Session->read('Auth.Parent.id')) {
			$this->Session->delete('Auth.Parent');
		}
		//delete existing fundraiser session
		if($this->Session->read('fundraiser'))
			$this->Session->delete('fundraiser');
		
		 $this->layout = 'fundraiser';
				
				$this->set('title_for_layout','Fundraiser Registration : Step#1');
				if(isset($this->data))
				{
					$this->Fundraiser->set($this->data['Fundraiser']);
					if($this->Fundraiser->validates())
					{
						$savearr='';
						$savearr['FrontUser']['realpassword'] = $this->data['Fundraiser']['password'];
						$savearr['FrontUser']['password'] = $this->Auth->password($this->data['Fundraiser']['password']);
						$savearr['FrontUser']['email'] = $this->data['Fundraiser']['email'];						
						$this->Session->write('fundraiser.step1',$savearr['FrontUser']);
						$this->redirect(array('controller'=>'fundraisers','action'=>'visitorFirst'));						 
					}
					else
					{
						$errors = $this->Fundraiser->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));
						return false;
					}
				}
				
        }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	    function visitorFirst()
		{
		if ($this->Session->read('Auth.Parent.id')) {
			$this->Session->delete('Auth.Parent');
		}
		   if($this->Session->read('fundraiser.step1'))
		   {
		   $this->set('title_for_layout','Fundraiser Registration : Step#2');
		   $this->set('StatesList',$this->common->getAllState());  //  List states
		   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   
		   $this->layout = 'fundraiser';

				if(isset($this->data))
				{
					$emailArray='';					
					$existEmail='';					
					$notExistEmail='';					
					$this->loadModel('ReferredFriend');					
					$emailArray=explode(',',$this->data['Fundraiser']['allemails']);
					foreach($emailArray as $emailArray1)
					{
						$exist='';						
						$exist=$this->ReferredFriend->find('first',array('conditions'=>array('ReferredFriend.email'=>$emailArray1)));						
						if(empty($exist))
						{
							$notExistEmail.=$emailArray1.'<br>';
						}
						else
						{
							$existEmail.=$emailArray1.'<br>';
						}						
					}					
					$this->Session->write('fundraiser.step2',$this->data['fundraisers']);					
					$this->Session->write('fundraiser.step2.notExist',$notExistEmail);					
					$this->Session->write('fundraiser.step2.msg',$this->data['Fundraiser']['message']);					
					if($existEmail=='')/**/
					{
						$this->redirect(array('controller'=>'fundraisers','action'=>'visitorThird'));
					}
					else
					{
						$this->Session->write('fundraiser.step2.exist',$existEmail);
						$this->redirect(array('controller'=>'fundraisers','action'=>'visitorSecond'));
					}
				}
			}
			else
			{
				$this->redirect(array('controller'=>'fundraisers','action'=>'index'));
			}	
        }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	    function visitorSecond()
		{
		if ($this->Session->read('Auth.Parent.id')) {
			$this->Session->delete('Auth.Parent');
		}
		   if($this->Session->read('fundraiser.step1') && $this->Session->read('fundraiser.step2'))
		   {
			   $this->layout = 'fundraiser';
			   
			   $this->set('title_for_layout','Fundraiser Registration : Step#3');
	
				if(isset($this->data))
				{	
					$this->redirect(array('controller'=>'fundraisers','action'=>'visitorThird'));
				}
			}
			else
			{
				$this->redirect(array('controller'=>'fundraisers','action'=>'index'));
			}				
        }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	    function visitorThird()
		{
		if ($this->Session->read('Auth.Parent.id')) {
			$this->Session->delete('Auth.Parent');
		}
		   if($this->Session->read('fundraiser.step1') && $this->Session->read('fundraiser.step2'))
		   {
			   $this->set('title_for_layout','Fundraiser Registration : Final Step');
			   $this->set('catList',$this->common->getAllCategoryFundraiser());	
			   $this->layout = 'fundraiser';
				
				if(isset($this->data))
				{	
					
					$strCat=implode(',',array_filter($this->data['Fundraiser']));
					
					$this->loadModel('FrontUser');
					
					$saveFrontUserArray='';
					
					$saveRefferedParentArray='';
					
					$saveRefferedChildArray='';
					
					$saveNewsletterUserArray='';
					
					$lastFrontUser=0;
					
					if($this->Session->read('fundraiser.step2.parentlname')=='')
					{
					$saveFrontUserArray['FrontUser']['name']=$this->Session->read('fundraiser.step2.parentfname');
					$saveNewsletterUserArray['NewsletterUser']['name']=$this->Session->read('fundraiser.step2.parentfname');
					}
					else
					{
					$saveFrontUserArray['FrontUser']['name']=$this->Session->read('fundraiser.step2.parentfname').' '.$this->Session->read('fundraiser.step2.parentlname');
					$saveNewsletterUserArray['NewsletterUser']['name']=$this->Session->read('fundraiser.step2.parentfname').' '.$this->Session->read('fundraiser.step2.parentlname');
					}
					
					/*-----------save newsletter array----------------*/
					$this->loadModel('NewsletterUser');
					
					$saveNewsletterUserArray['NewsletterUser']['email']=$this->Session->read('fundraiser.step1.email');
					$saveNewsletterUserArray['NewsletterUser']['user_id']=0;
					$saveNewsletterUserArray['NewsletterUser']['category_id']=$strCat;
					$saveNewsletterUserArray['NewsletterUser']['county_id']=$this->Session->read('fundraiser.step2.county');
					
					$this->NewsletterUser->save($saveNewsletterUserArray);
					
					/*-----------------------------------------------*/
						
					$saveFrontUserArray['FrontUser']['email']=$this->Session->read('fundraiser.step1.email');
					$saveFrontUserArray['FrontUser']['password']=$this->Session->read('fundraiser.step1.password');
					$saveFrontUserArray['FrontUser']['realpassword']=$this->Session->read('fundraiser.step1.realpassword');
					$saveFrontUserArray['FrontUser']['county_id']=$this->Session->read('fundraiser.step2.county');
					$saveFrontUserArray['FrontUser']['user_type']='parent';
					$saveFrontUserArray['FrontUser']['categories']=$strCat;
					$saveFrontUserArray['FrontUser']['school_id']=$this->Session->read('fundraiser.step2.school');
					$saveFrontUserArray['FrontUser']['grade']=$this->Session->read('fundraiser.step2.grade');
					$saveFrontUserArray['FrontUser']['teacher']=$this->Session->read('fundraiser.step2.teacher');
					$saveFrontUserArray['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
					
					
					$this->FrontUser->save($saveFrontUserArray);
					
					$lastFrontUser=$this->FrontUser->getlastinsertid();
					
					$this->loadModel('Kid');
					
					if($this->Session->read('fundraiser.step2.childlname')=='')
					{
					$saveRefferedChildArray['Kid']['child_name']=$this->Session->read('fundraiser.step2.childfname');
					}
					else
					{
					$saveRefferedChildArray['Kid']['child_name']=$this->Session->read('fundraiser.step2.childfname').' '.$this->Session->read('fundraiser.step2.childlname');
					}
										
					$saveRefferedChildArray['Kid']['front_user_id']=$lastFrontUser;
					
					$saveRefferedChildArray['Kid']['school_id']=$this->Session->read('fundraiser.step2.school');
	
					$this->Kid->save($saveRefferedChildArray);
					
					$lastKid=$this->Kid->getlastinsertid();				
					
					if($this->Session->read('fundraiser.step2.notExist')!='')
					{
						//$this->loadModel('RefferedParent');
						$this->loadModel('ReferredFriend');
						$emailArr='';
						$emailArr=array_filter(explode('<br>',$this->Session->read('fundraiser.step2.notExist')));
						$this->loadModel('Setting');
						$mail_data  = $this->Setting->find('first',array('fields'=>array('Setting.send_to_friend_subject','Setting.send_to_frient_body')));
						$place_marks = array('[friend_name]','[link]','[message]','[from]');
						$state_id = $this->common->getStateByCountyId($this->Session->read('fundraiser.step2.county'));
						$county_url = $this->common->getCountyUrl($this->Session->read('fundraiser.step2.county'));
						$state_url = $this->common->getStateUrls($state_id);						
						//$url 	= FULL_BASE_URL.router::url('/',false).'state/'.$state_url.'/'.$county_url;		
						$url = '<a href="'.FULL_BASE_URL.router::url('/',false).'state/'.$state_url.'/'.$county_url.'" target="_blank">'.FULL_BASE_URL.router::url('/',false).'state/'.$state_url.'/'.$county_url.'</a>';
										
							foreach($emailArr as $emailArr1)
							{
								$saveRefferedParentArray['ReferredFriend']['id']='';								
								$saveRefferedParentArray['ReferredFriend']['email'] = $emailArr1;;
								$saveRefferedParentArray['ReferredFriend']['front_user_id'] = $lastFrontUser;
								$saveRefferedParentArray['ReferredFriend']['county_id'] = $this->Session->read('fundraiser.step2.county');
								$saveRefferedParentArray['ReferredFriend']['school_id'] = $this->Session->read('fundraiser.step2.school');
								$saveRefferedParentArray['ReferredFriend']['kid_id']  =  $lastKid;
								$saveRefferedParentArray['ReferredFriend']['refer_ip']  =  $_SERVER['REMOTE_ADDR'];
								$saveRefferedParentArray['ReferredFriend']['state_id']  =  $state_id;
								$saveRefferedParentArray['ReferredFriend']['refered_date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
								
								$this->ReferredFriend->save($saveRefferedParentArray);								
								
								$place_words = array('Friend',$url,$this->Session->read('fundraiser.step2.msg'),$saveFrontUserArray['FrontUser']['name']);
								
								$subject = str_replace($place_marks,$place_words,$mail_data['Setting']['send_to_friend_subject']);
								
								$msg = str_replace($place_marks,$place_words,$mail_data['Setting']['send_to_frient_body']);
																			
												$this->Email->to 		= $emailArr1;
												$this->Email->subject 	= strip_tags($subject);
												$this->Email->replyTo 	= $this->common->getReturnEmail();
												$this->Email->from 		= $saveFrontUserArray['FrontUser']['email'];
												$this->Email->sendAs 	= 'html';
												//Set the body of the mail as we send it.			
												//seperate line in the message body.
												$this->body = '';				
												$this->body = $this->emailhtml->email_header($this->Session->read('fundraiser.step2.county'));
												$this->body .=$msg;
												$this->body .= $this->emailhtml->email_footer($this->Session->read('fundraiser.step2.county'));										
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
																								
			///////////////////////////sent mail insert to sent box ///////////////////
				$this->common->sentMailLog($saveFrontUserArray['FrontUser']['email'],$emailArr1,strip_tags($subject),$this->body,"referred_friend");
			/////////////////////////////////////////////////////////////////////////
			
							}						
					}					
			//------------------------------------------ Welcome Email -----------------------------------//
					$arrayTags = array("[consumer_name]","[url]");					
					$arrayReplace = array($saveNewsletterUserArray['NewsletterUser']['name'],$url);					
					//get Mail format 					
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.new_consumer_subject','Setting.new_consumer_body','Setting.newsletter_from_email')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_subject']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_body']);					
					//ADMINMAIL id
					$this->body = '';				
					$this->body = $this->emailhtml->email_header($this->Session->read('fundraiser.step2.county'));
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer($this->Session->read('fundraiser.step2.county'));
												
												
					$this->Email->to 		= $saveFrontUserArray['FrontUser']['email'];
					$this->Email->subject 	= strip_tags($subject);
					$this->Email->replyTo 	= $this->common->getReturnEmail();
					$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
					$this->Email->sendAs 	= 'html';
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
																								
			///////////////////////////sent mail insert to sent box ///////////////////
				$this->common->sentMailLog($this->common->getSalesEmail(),$saveFrontUserArray['FrontUser']['email'],strip_tags($subject),$this->body,"new_consumer_registration");
			/////////////////////////////////////////////////////////////////////////
								
					
					$this->Session->delete('fundraiser');
					
					$this->Session->setFlash('You are successfully registered as Fundraiser.');
					
					$this->redirect(array('controller'=>'fundraisers','action'=>'index'));					
				}
			}
			else
			{
				$this->redirect(array('controller'=>'fundraisers','action'=>'index'));			
			}		   
        }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	    function selectedCounty($state='',$county='')
		{
			if(isset($this->data['fundraisers']['state']) && $this->data['fundraisers']['state'] !=''){
				$state_id=$this->data['fundraisers']['state'];
				$selCounty = $this->common->getAllCountyByState($state_id);
			}
			elseif(isset($this->params['pass'][0]) && $this->params['pass'][0]!='')
			{
				$state_id=$this->params['pass'][0];				
				$selCounty = $this->common->getAllCountyByState($state_id);				
			}
			else
			{
				$state_id = '';
				$selCounty = '';
			}
			$this->set('selCounty',$selCounty);
			$this->set('county',$county);
		}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	    function selectedSchool()
		{
			if(isset($this->data['fundraisers']['county']) && $this->data['fundraisers']['county'] !=''){
				$county_id=$this->data['fundraisers']['county'];
				$selSchool = $this->common->getAllSchoolByCounty($county_id);
			}
			elseif(isset($this->params['pass'][0]) && $this->params['pass'][0]!='')
			{
				$county_id=$this->params['pass'][0];
				$selSchool = $this->common->getAllSchoolByCounty($county_id);
			}
			else
			{				
				$selSchool = '';
				$county_id='';
			}
			$this->set('selSchool',$selSchool);	
			$this->set('county_id',$county_id);
		}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	    function refer()
		{
		if($this->Session->read('Auth.Parent.id')) {
			$allemails = '';
			$message = '';
		   	$this->set('title_for_layout','Fundraiser Login : Refer');   
		   	$this->layout = 'fundraiser';
				if(isset($this->data))
				{
					$error = '';
					$allemails = $this->data['fundraisers']['allemails'];
					$message = $this->data['fundraisers']['message'];
					
					if($allemails=='') {
						$error[] = 'Please enter your Friend\'s email id.';
					}
					if($message=='') {
						$error[] = 'Please enter a Message.';
					}
					if(is_array($error)) {
						$this->Session->setFlash(implode('<br />',$error));
					} else {
								$user_id = $this->Session->read('Auth.Parent.id');
								$user_email = $this->Session->read('Auth.Parent.email');
								$user_name = $this->Session->read('Auth.Parent.name');
								$school = $this->Session->read('Auth.Parent.school_id');
								$child = $this->common->getChildId($user_id);
								//$this->loadModel('RefferedParent');
								$this->loadModel('ReferredFriend');
								$emailArr='';
								$emailArr=array_filter(explode(',',$allemails));
								$this->loadModel('Setting');
								$mail_data  = $this->Setting->find('first',array('fields'=>array('Setting.send_to_friend_subject','Setting.send_to_frient_body')));
								$place_marks = array('[friend_name]','[link]','[message]','[from]');
								$county_url = $this->common->getCountyUrl($this->Session->read('Auth.Parent.county_id'));
								$state_id = $this->common->getStateByCountyId($this->Session->read('Auth.Parent.county_id'));
								$state_url = $this->common->getStateUrls($state_id);				
								//$url 	= FULL_BASE_URL.router::url('/',false).'state/'.$state_url.'/'.$county_url;		
								$url = '<a href="'.FULL_BASE_URL.router::url('/',false).'state/'.$state_url.'/'.$county_url.'" target="_blank">'.FULL_BASE_URL.router::url('/',false).'state/'.$state_url.'/'.$county_url.'</a>';
												
									foreach($emailArr as $emailArr1)
									{													
										$saveRefferedParentArray['ReferredFriend']['id']='';								
										$saveRefferedParentArray['ReferredFriend']['email'] = $emailArr1;
										$saveRefferedParentArray['ReferredFriend']['front_user_id'] = $user_id;
										$saveRefferedParentArray['ReferredFriend']['county_id'] = $this->Session->read('Auth.Parent.county_id');
										$saveRefferedParentArray['ReferredFriend']['school_id'] = $school;
										$saveRefferedParentArray['ReferredFriend']['kid_id']  =  $child;
										$saveRefferedParentArray['ReferredFriend']['refer_ip']  = $_SERVER['REMOTE_ADDR'];
										$saveRefferedParentArray['ReferredFriend']['state_id']  =  $state_id;
										$saveRefferedParentArray['ReferredFriend']['refered_date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
										
										$this->ReferredFriend->save($saveRefferedParentArray);								
										
										$place_words = array('Friend',$url,$message,$user_name);
										
										$subject = str_replace($place_marks,$place_words,$mail_data['Setting']['send_to_friend_subject']);
										
										$msg = str_replace($place_marks,$place_words,$mail_data['Setting']['send_to_frient_body']);
																					
														$this->Email->to 		= $emailArr1;
														$this->Email->subject 	= strip_tags($subject);
														$this->Email->replyTo 	= $this->common->getReturnEmail();
														$this->Email->from 		= $user_email;
														$this->Email->sendAs 	= 'html';
														//Set the body of the mail as we send it.			
														//seperate line in the message body.
														$this->body = '';				
														$this->body = $this->emailhtml->email_header($this->Session->read('Auth.Parent.county_id'));
														$this->body .=$msg;
														$this->body .= $this->emailhtml->email_footer($this->Session->read('Auth.Parent.county_id'));
					
														
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
																								
			///////////////////////////sent mail insert to sent box ///////////////////
				$this->common->sentMailLog($user_email,$emailArr1,strip_tags($subject),$this->body,"referred_friend");
			/////////////////////////////////////////////////////////////////////////
								
			
									}
									$this->Session->setFlash('<span style="color:green;font-size:13px;font-weight:bold;">Referral Process has been finished successfully.</span>');
									$this->redirect(FULL_BASE_URL.router::url('/',false).'fundraisers');
					}
				}
				$this->set('allemails',$allemails);
				$this->set('message',$message);
				} else {
				$this->redirect(FULL_BASE_URL.router::url('/',false).'fundraisers');
				}
       }
//-------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username',
            'password' => 'password'
            );
			$this->Auth->allow('index','visitorFirst','visitorSecond','visitorThird','selectedCounty','selectedSchool','refer');
   	}	 
//---------------This function is setting all info about current SuperAdmins in currentAdmin array so we can use it anywhere lie name id etc.----------------//

	 function beforeRender(){
		    $this->set('currentAdmin', $this->Auth->user());
			$this->set('cssName',$this->Cookie->read('css_name'));
			$this->set('groupDetail',$this->common->adminDetails());
			$this->set('common',$this->common);
			//$this->Ssl->force();
	  }

} 