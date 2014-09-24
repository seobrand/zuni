<?php
/*
   Coder: Surbhit
*/
class AdvertiserProfilesController extends AppController{
 var $name = 'AdvertiserProfiles';
 var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax');
 var $components = array('Auth','common','Session','Cookie','RequestHandler','Email','emailhtml','smtpmail','cronhtml','offerhtml');  //component to check authentication . this component file is exists in app/controllers/components
 var $layout = 'admin'; //this is the layout for admin panel
 var $model = 'AdvertiserOrder';
	 #this function call by default when a controller is called
	 function index()
	 {
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;
		if($this->Session->check('Auth.Admin'))
		{
		   $this->set('StatesList',$this->common->getAllState());  //  List states
		   $this->set('CitiesList',$this->common->getAllCity());   //  List cities
		   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   $this->set('CountriesList',$this->common->getAllCountry()); //  List countries
		   $this->set('Packages', $this->common->getOnlyPackage());
		   $this->set('common',$this->common);
		   $this->set('SelsePersons',$this->common->getAllSelsePerson(5));
		   $condition='';
		   $cond ='';
		   $this->set('company_name','Company Name');
		   $this->set('city','');
		   $this->set('state','');
		   $this->set('county','');
		   $this->set('category','');
		   $this->set('package_id','');
		   $this->set('salse_id','');
		   $this->set('publish','');
		   $cond1 = '';
		   $cond2 = '';
		   $id = '';
	       $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('AdvertiserProfile.id' => 'desc'),'contain' => array('AdvertiserCategory'=>array('CategoriesSubcategory'=>array('Category.categoryname','Category.publish'))));
				
		if((isset($this->data['AdvertiserProfile']['company_name']) && $this->data['AdvertiserProfile']['company_name'] !='Company Name') ||  (isset($this->params['named']['company_name']) && $this->params['named']['company_name'] !='Company Name'))
		 {
		if(isset($this->params['named']['company_name']))
		{
		    $cond[] = 'AdvertiserProfile.company_name LIKE "%' . str_replace("%20"," ",$this->params['named']['company_name']). '%"';
		}
		else
		{
		 	$cond[] = 'AdvertiserProfile.company_name LIKE "%' .$this->data['AdvertiserProfile']['company_name']. '%"';
		 }
		(empty($this->params['named'])) ? $this->set('company_name', $this->data['AdvertiserProfile']['company_name']) :$this->set('company_name', $this->params['named']['company_name']) ; 
		 }
		if($this->data['AdvertiserProfile']['city']!='' ||  isset($this->params['named']['city'])) 
		{
		   if(isset($this->params['named']['city']))
		   {
		     $cond[] = $this->params['named']['city'];
		   }
		   else
		   {
				 $cond[] = 'AdvertiserProfile.city = '.$this->data['AdvertiserProfile']['city'];
		   }
				   
		  (empty($this->params['named'])) ? $this->set('city', $this->data['AdvertiserProfile']['city']) :$this->set('city', $this->params['named']['city']) ; 
	   }
				 
		if($this->data['AdvertiserProfile']['state']!='' ||  isset($this->params['named']['state'])) 
		{ 
		   if(isset($this->params['named']['state']))
		   {
			   $cond[] = 'AdvertiserProfile.state = '.$this->params['named']['state'];
		   }
		   else
		   {
			 $cond[] = 'AdvertiserProfile.state = '.$this->data['AdvertiserProfile']['state'];
		   }
		 (empty($this->params['named'])) ? $this->set('state', $this->data['AdvertiserProfile']['state']) :$this->set('state', $this->params['named']['state']) ; 
	   }
				 
	if($this->data['AdvertiserProfile']['county']!='' ||  isset($this->params['named']['county'] )) 
	{
		  if(isset($this->params['named']['county']))
		  {
			 $cond[] = 'AdvertiserProfile.county = '.$this->params['named']['county'];
		  }
		  else
		  {
			  $cond[] = 'AdvertiserProfile.county = '.$this->data['AdvertiserProfile']['county'];
		  }
		  
		 (empty($this->params['named'])) ? $this->set('county', $this->data['AdvertiserProfile']['county']) :$this->set('county', $this->params['named']['county']) ; 
	}
	
	 if((isset($this->data['AdvertiserProfile']['category']) && $this->data['AdvertiserProfile']['category']!='') || (isset($this->params['named']['category']) && $this->params['named']['category']!=''))
	 {
	 	  $cat = '';
		  if(isset($this->params['named']['category']))
		  {
		  	$cat = $this->params['named']['category'];
		  }
		  else
		  {
		  	$cat = $this->data['AdvertiserProfile']['category'];
			 
		  }
		  
		  if($cat) {
		  
		  			$this->loadModel('AdvertiserCategory');
					$cats_pair = explode('-',$cat);
					
					$data = $this->AdvertiserCategory->find('all',array('fields'=>'AdvertiserCategory.advertiser_profile_id','conditions'=>array('CategoriesSubcategory.category_id'=>$cats_pair[0],'CategoriesSubcategory.subcategory_id'=>$cats_pair[1])));
					$profile = '';
					if(!empty($data)) {
						foreach($data as $data) {
							$profile[] = $data['AdvertiserCategory']['advertiser_profile_id'];
						}
					}
					if(is_array($profile)) {
						$profile_ids = implode(',',array_values(array_filter($profile)));
					} else {
						$profile_ids = 0;
					}
					$cond[] = 'AdvertiserProfile.id IN ('.$profile_ids.')';
		  }
		  
	(empty($this->params['named'])) ? $this->set('category', $this->data['AdvertiserProfile']['category']) :$this->set('category', $this->params['named']['category']) ; 
	}	
	 if((isset($this->data['AdvertiserProfile']['publish']) && $this->data['AdvertiserProfile']['publish']!='') || (isset($this->params['named']['publish']) && $this->params['named']['publish']!='')) 
	 {
		  if(isset($this->params['named']['publish']))
		  {
			 $cond[] = 'AdvertiserProfile.publish = "'.$this->params['named']['publish'].'"';
		  }
		  else
		  {
			 $cond[] = 'AdvertiserProfile.publish = "'.$this->data['AdvertiserProfile']['publish'].'"';
		  }
					   
	(empty($this->params['named'])) ? $this->set('publish', $this->data['AdvertiserProfile']['publish']) :$this->set('publish', $this->params['named']['publish']) ; 
	}
	 if((isset($this->data['AdvertiserProfile']['package_id']) && $this->data['AdvertiserProfile']['package_id']!='') || (isset($this->params['named']['package_id']) && $this->params['named']['package_id']!='')) 
	 {
		  if(isset($this->params['named']['package_id']))
		  {
			 $cond[] = 'AdvertiserOrder.package_id = '.$this->params['named']['package_id'];
		  }
		  else
		  {
			 $cond[] = 'AdvertiserOrder.package_id = '.$this->data['AdvertiserProfile']['package_id'];
		  }
					   
	(empty($this->params['named'])) ? $this->set('package_id', $this->data['AdvertiserProfile']['package_id']) :$this->set('package_id', $this->params['named']['package_id']) ; 
	}	
	 if((isset($this->data['AdvertiserProfile']['salse_id']) && $this->data['AdvertiserProfile']['salse_id']!='') || (isset($this->params['named']['salse_id']) && $this->params['named']['salse_id']!='')) 
	 {
		  if(isset($this->params['named']['salse_id']))
		  {
			 $cond[] = 'AdvertiserOrder.salesperson = '.$this->params['named']['salse_id'];
		  }
		  else
		  {
			 $cond[] = 'AdvertiserOrder.salesperson = '.$this->data['AdvertiserProfile']['salse_id'];
		  }
					   
	(empty($this->params['named'])) ? $this->set('salse_id', $this->data['AdvertiserProfile']['salse_id']) :$this->set('salse_id', $this->params['named']['salse_id']) ; 
	}
			if(is_array($cond)){ 
					$cond2 = 'AND '.implode(' AND ',$cond);
					} else {
					$cond2 = '';
		    }
				$join = $this->AdvertiserProfile->query("SELECT AdvertiserOrder.*, AdvertiserProfile.* FROM advertiser_orders AS AdvertiserOrder, advertiser_profiles AS AdvertiserProfile WHERE AdvertiserProfile.order_id = AdvertiserOrder.id AND AdvertiserOrder.save_later<>1 ".$cond2);
				foreach($join as $join) {
					$id[] = $join['AdvertiserOrder']['id'];
				}
				if(is_array($id)) {
					$id_in = '('.implode(',',$id).')';	
				} else {
					$id_in = "('0')";
				}
				$cond1[] = 'AdvertiserProfile.order_id IN '.$id_in;
				$data = $this->paginate('AdvertiserProfile', $cond1);
		        $this->set('AdvertiserProfiles', $data);
			}
		else{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}
		}
/*--------------------------- it is used to autocomplete the search box -----------------------------------------------------*/
	function autocomplete($string='') {
			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			$name = $this->AdvertiserProfile->query("SELECT AdvertiserProfile.company_name FROM advertiser_profiles AS AdvertiserProfile WHERE AdvertiserProfile.company_name LIKE '$string%' AND AdvertiserProfile.publish='yes' AND AdvertiserProfile.county=".$this->Session->read('county_data.id')." order by company_name");
			foreach($name as $name) {
				$arr[] = $name['AdvertiserProfile']['company_name'];
			}
			echo json_encode($arr);
		}
	}
/*------------------------------------------------------------------------------------------------------------------------*/
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
//-------------------- Add & edit advertiser profile ----------------------//
   function addNewAdvertiserProfile()
	 {
	            $this->set('StatesList',$this->common->getAllState());  //  List states
				$this->set('CitiesList',$this->common->getAllCity());   //  List cities
				$this->set('CountyList',$this->common->getAllCounty()); //  List counties
				$this->set('CountriesList',$this->common->getAllCountry()); //  List countries
				$this->set('categoryList',$this->common->getAllCategory()); //  List categories
				//$this->set('Packages', $this->common->getAllPackage(1));
				$this->set('Packages', $this->common->getOnlyPackage());
				 $this->loadModel('User');
				 $this->set('salesperson', $this->User->returnUsersSales());
				 if($this->Session->read('reff')) {
		   				$this->set('reff',$this->Session->read('reff'));
		   		} else {
		   				$this->set('reff',$this->referer());
		   		}
				$county_id = '';
				if(isset($this->data)) {
					$county_id = $this->data['AdvertiserProfile']['county'];
				}
				$this->set('county_id',$county_id);
							
							
				//$this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
				if(isset($this->data))
				{
				
				//pr($this->data);exit;
				
				$this->AdvertiserProfile->set($this->data);
				  if($this->AdvertiserProfile->validates()){
						//this is for new record
						App::import('model', 'AdvertiserOrder');
						$this->AdvertiserOrder = new AdvertiserOrder;
						$saveArray = array();
						$saveArray['AdvertiserOrder']['package_id']   		=  $this->data['AdvertiserProfile']['package_id'];
						$saveArray['AdvertiserOrder']['payment_status']   	=  'pending';
						$saveArray['AdvertiserOrder']['order_status']   	=  'pending';
						
						  if(isset($this->data['AdvertiserProfile']['salesperson'])) {
							$saveArray['AdvertiserOrder']['salesperson']	= $this->data['AdvertiserProfile']['salesperson'];
						  } else {
							$saveArray['AdvertiserOrder']['salesperson']	= $this->Session->read('Auth.Admin.id');
						  }
						  		  
						$saveArray['AdvertiserOrder']['order_detail']  		= '';
						$saveArray['AdvertiserOrder']['user_group_id']     	= $this->Session->read('Auth.Admin.user_group_id');
						$this->AdvertiserOrder->save($saveArray);
						$this->data['AdvertiserProfile']['order_id']		=	$this->AdvertiserOrder->getlastinsertid();
						$this->data['AdvertiserProfile']['publish'] 		= 'no';
						$this->data['AdvertiserProfile']['modifier']  		= $this->Session->read('Auth.Admin.id');
						
						if(isset($this->data['AdvertiserProfile']['salesperson'])) {
							$this->data['AdvertiserProfile']['creator']	= $this->data['AdvertiserProfile']['salesperson'];
						  } else {
							$this->data['AdvertiserProfile']['creator']	= $this->Session->read('Auth.Admin.id');
						  }
						date_default_timezone_set('US/Eastern');
						$this->data['AdvertiserProfile']['contract_date']  	= strtotime($this->data['AdvertiserProfile']['contract_date']);
						//$this->data['AdvertiserProfile']['contract_expiry_date']  	= strtotime($this->data['AdvertiserProfile']['contract_expiry_date']);						
		//after getting last inserted id for advertiser table we are inserting in work order table
					  App::import('model', 'WorkOrder');
					  $this->WorkOrder = new WorkOrder;
					  $saveWorkArray = array();
					  
					  $new_order_id=$this->AdvertiserOrder->getlastinsertid();
					  
					  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $new_order_id;
					  $saveWorkArray['WorkOrder']['read_status']   				=  0;
					  $saveWorkArray['WorkOrder']['subject']   					=  'New order placed in your account';
					  $saveWorkArray['WorkOrder']['message']   					=  'A new order has been placed recently by admin team under your reference.Order detail is below:';
					  $saveWorkArray['WorkOrder']['type']   					=  'orderplaced';
					  
					  if(isset($this->data['AdvertiserProfile']['salesperson'])) {
							$saveWorkArray['WorkOrder']['sent_to']	= $this->data['AdvertiserProfile']['salesperson'];
						  } else {
							$saveWorkArray['WorkOrder']['sent_to']	= $this->Session->read('Auth.Admin.id');
						  }
						  
					  $saveWorkArray['WorkOrder']['sent_to_group']   			=  5;
					  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
					  $saveWorkArray['WorkOrder']['bottom_line']				=  '';
					  date_default_timezone_set('US/Eastern');
					  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
					  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
					  
					  if(isset($this->data['AdvertiserProfile']['salesperson'])) {
						$saveWorkArray['WorkOrder']['salseperson_id']	= $this->data['AdvertiserProfile']['salesperson'];
					  } else {
						$saveWorkArray['WorkOrder']['salseperson_id']	= $this->Session->read('Auth.Admin.id');
					  }
						  
					  $this->WorkOrder->save($saveWorkArray);
					$this->data['AdvertiserProfile']['show_at_home']		=	$this->data['AdvertiserProfile']['show_at_home'];
					$this->data['AdvertiserProfile']['show_at_category']	=	$this->data['AdvertiserProfile']['show_at_category'];
					$this->data['AdvertiserProfile']['show_at_dailydeals']	=	$this->data['AdvertiserProfile']['show_at_dailydeals'];
					
			//--------------------------------Lat-Long---------------------------------------//		
						if(trim($this->data['AdvertiserProfile']['long'])=='' || trim($this->data['AdvertiserProfile']['lat'])=='') {
							$latlong = $this->common->latLong('United+States+'.$this->common->getStateName($this->data['AdvertiserProfile']['state']).'+'.$this->common->getCountyName($this->data['AdvertiserProfile']['county']).'+'.$this->common->getCityName($this->data['AdvertiserProfile']['city']).'+'.trim($this->data['AdvertiserProfile']['address']));
						}		
						if(trim($this->data['AdvertiserProfile']['long'])=='') {
							$this->data['AdvertiserProfile']['long'] = $latlong['long'];
						}
						
						if(trim($this->data['AdvertiserProfile']['lat'])=='') {
							$this->data['AdvertiserProfile']['lat'] = $latlong['lat'];
						}
			/*-------------------------------------LOGO-------------------------------------*/					
					if($this->data['AdvertiserProfile']['logo']['name']!=""){
					
					$type = $this->data['AdvertiserProfile']['logo']['type'];
					if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif"){					                         
						if(isset($imageOld[0]['advertiser_profiles']['logo']) and file_exists(APP.'webroot/img/logo/'.$imageOld[0]['advertiser_profiles']['logo'])){
						  unlink(APP.'webroot/img/logo/'.$imageOld[0]['advertiser_profiles']['logo']);
						}						
						$this->data['AdvertiserProfile']['logo']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['AdvertiserProfile']['logo']['name']);
						$docDestination = APP.'webroot/img/logo/'.$this->data['AdvertiserProfile']['logo']['name']; 
						@chmod(APP.'webroot/img/logo',0777);
						move_uploaded_file($this->data['AdvertiserProfile']['logo']['tmp_name'], $docDestination) or die($docDestination);
						$this->data['AdvertiserProfile']['logo'] = $this->data['AdvertiserProfile']['logo']['name'];
					}	
				}
				/*--------------------------------Main Image------------------------------------------*/		
				if(isset($this->data['AdvertiserProfile']['main_image_type']) && $this->data['AdvertiserProfile']['main_image_type']==1 && isset($this->data['AdvertiserProfile']['main_image']['name']) && $this->data['AdvertiserProfile']['main_image']['name']!=""){
					$type = explode(".",$this->data['AdvertiserProfile']['main_image']['name']);
					                         					
						$this->data['AdvertiserProfile']['main_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['AdvertiserProfile']['main_image']['name']);
						$docDestination = APP.'webroot/img/logo/main_image/'.$this->data['AdvertiserProfile']['main_image']['name']; 
						@chmod(APP.'webroot/img/logo/main_image',0777);
						move_uploaded_file($this->data['AdvertiserProfile']['main_image']['tmp_name'], $docDestination) or die($docDestination);
						$this->data['AdvertiserProfile']['main_image'] = $this->data['AdvertiserProfile']['main_image']['name'];
				}elseif(isset($this->data['AdvertiserProfile']['main_image_type']) && $this->data['AdvertiserProfile']['main_image_type']==0 && isset($this->data['AdvertiserProfile']['main_image']) && $this->data['AdvertiserProfile']['main_image']!="")
				{
					@chmod(APP.'webroot/img/logo/main_image',0777);
					$new_pic_name=time().'_'.$this->data['AdvertiserProfile']['main_image'];
					@copy(APP.'webroot/img/photo_gallery/'.$this->data['AdvertiserProfile']['main_image'],WWW_ROOT.'img/logo/main_image/'.$new_pic_name);
					$this->data['AdvertiserProfile']['main_image'] = $new_pic_name;
				}
				
			/*-------------------------------------Offer Image-------------------------------------*/					
					if($this->data['AdvertiserProfile']['offer_image']['name']!=""){
					
					$type = $this->data['AdvertiserProfile']['offer_image']['type'];
					if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif"){					                         
						if(isset($imageOld[0]['advertiser_profiles']['offer_image']) and file_exists(APP.'webroot/img/offer/soffers/'.$imageOld[0]['advertiser_profiles']['offer_image'])){
						  unlink(APP.'webroot/img/offer/soffers/'.$imageOld[0]['advertiser_profiles']['offer_image']);
						}						
						$this->data['AdvertiserProfile']['offer_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['AdvertiserProfile']['offer_image']['name']);
						$docDestination = APP.'webroot/img/offer/soffers/'.$this->data['AdvertiserProfile']['offer_image']['name']; 
						@chmod(APP.'webroot/img/offer/soffers',0777);
						move_uploaded_file($this->data['AdvertiserProfile']['offer_image']['tmp_name'], $docDestination) or die($docDestination);
						$this->data['AdvertiserProfile']['offer_image'] = $this->data['AdvertiserProfile']['offer_image']['name'];
					}	
				}
				
					$this->AdvertiserProfile->saveAll($this->data);
					$advertiser_id_is = $this->AdvertiserProfile->getlastinsertid();
					/*------------to set the multiple category and subcategory------------------*/
					$this->loadModel('AdvertiserCategory');
					foreach($this->data['AdvertiserProfile']['subcategory'] as $pair) {
						$break = explode('-',$pair);
						$catSubcat = $this->common->returnCatSubcatId($break[0],$break[1]);
						if($catSubcat) {
							$save = '';
							$save['AdvertiserCategory']['id'] = '';
							$save['AdvertiserCategory']['advertiser_profile_id'] = $advertiser_id_is;
							$save['AdvertiserCategory']['categories_subcategory_id'] = $catSubcat;
							$this->AdvertiserCategory->save($save,false);
						}
					}
					 $this->loadModel('FrontUser');
					 $password = $this->common->randomPassword(8);
					 $arr['FrontUser']['password'] = $this->Auth->password($password);
					 $arr['FrontUser']['realpassword'] = $password;
					 $arr['FrontUser']['name'] 		= $this->data['AdvertiserProfile']['name'];
					 $arr['FrontUser']['zip'] 	  	= $this->data['AdvertiserProfile']['zip'];
					 $arr['FrontUser']['email'] 	= $this->data['AdvertiserProfile']['email'];
					 $arr['FrontUser']['status'] 	= $this->data['AdvertiserProfile']['publish'];
					 $arr['FrontUser']['state_id'] = $this->data['AdvertiserProfile']['state'];
					 $arr['FrontUser']['county_id'] = $this->data['AdvertiserProfile']['county'];
					 $arr['FrontUser']['address'] 	= $this->data['AdvertiserProfile']['address'];
					 $arr['FrontUser']['city_id'] 	= $this->data['AdvertiserProfile']['city'];
					 
					 $arr['FrontUser']['advertiser_profile_id'] = $advertiser_id_is;
					 $arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
					 $this->FrontUser->save($arr);
					 
					 
					 //----------save the instance of order, when new order is placed (Start)------//
						App::import('model', 'OrderInstance');
						$this->OrderInstance = new OrderInstance;
						$saveInstanceArray = array();
						$saveInstanceArray['OrderInstance']['advertiser_order_id']   	=  $new_order_id;
						$saveInstanceArray['OrderInstance']['advertiser_profile_id']   	=  $advertiser_id_is;
						$saveInstanceArray['OrderInstance']['package_id']   		=  $this->data['AdvertiserProfile']['package_id'];
						$saveInstanceArray['OrderInstance']['insert_status']   		=  1;
					 	$this->OrderInstance->save($saveInstanceArray);
					 //----------save the instance of order, when new order is placed (End)------//
					 
					// $this->sendUsernamePassword($this->data['AdvertiserProfile']['email'],$password);
						/*----------welcome-mail code for advertiser registration-------------*/
					// Here we are sending email to advertiser for notification that his/he order has been placed at ishop.com
											    App::import('model', 'Setting');
	    										$this->Setting = new Setting;
												$emailArray = $this->Setting->getAdvertiserEmailData();
												$this->Email->sendAs = 'html';
												$this->Email->to = $this->data['AdvertiserProfile']['email'];
												$this->Email->subject = $emailArray[0]['settings']['new_advertiser_subject'];
												$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
												$package_name =   $this->common->getAllPackage(2);
												$package_price =   $this->common->getAllPackage(3);
												$bodyData = $this->Setting->replaceUserMarkers($emailArray[0]['settings']['new_advertiser_body'],$this->data['AdvertiserProfile']['name'],$package_name[$this->data['AdvertiserProfile']['package_id']],$this->data['AdvertiserProfile']['company_name'],$package_price[$this->data['AdvertiserProfile']['package_id']],$this->data['AdvertiserProfile']['order_id'],$password,'');
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = $this->emailhtml->email_header($this->data['AdvertiserProfile']['county']);
												$this->body .=$bodyData;
												$this->body .= $this->emailhtml->email_footer($this->data['AdvertiserProfile']['county']);
																	
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$this->data['AdvertiserProfile']['email'],strip_tags($emailArray[0]['settings']['new_advertiser_subject']),$this->body,"new_advertiser_registration");
					/////////////////////////////////////////////////////////////////////////
	
				//--------------------------------------------------------------
					$this->loadModel('Setting');
					$setvale = $this->Setting->find('first',array('fields'=>array('refer_business_bucks')));
					$bucksprice = $setvale['Setting']['refer_business_bucks'];
					//bucks management
					$this->loadModel('ReferredBusiness');
					
					$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.phone'=>$this->data['AdvertiserProfile']['phoneno'],'ReferredBusiness.status'=>'no')));
												
					if($this->data['AdvertiserProfile']['phoneno2']!='' && empty($checkRefer)) {
						$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.phone'=>$this->data['AdvertiserProfile']['phoneno2'],'ReferredBusiness.status'=>'no')));
					}
					
					if(is_array($checkRefer) && !empty($checkRefer)) {				
						$savearr['ReferredBusiness']['id'] = $checkRefer['ReferredBusiness']['id'];
						$savearr['ReferredBusiness']['status'] = 'yes';
						$savearr['ReferredBusiness']['bucks'] = $bucksprice;
						$savearr['ReferredBusiness']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
						$savearr['ReferredBusiness']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
						$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
						$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;	
						$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
						$this->ReferredBusiness->save($savearr);
						$this->FrontUser->save($savearr);
						$this->loadModel('Buck');
						$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$this->data['AdvertiserProfile']['county'],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
						if(is_array($checkBuck) && count($checkBuck)) {
							$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
							$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
						} else {
							$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
							$saveBuck['Buck']['county_id'] = $this->data['AdvertiserProfile']['county'];
							$saveBuck['Buck']['bucks'] = $bucksprice;
							$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
						}
						$this->Buck->save($saveBuck);
					}
			/*------------------------------------------------------------*/
						if(isset($this->data['AdvertiserProfile']['referrer']) && strpos($this->data['AdvertiserProfile']['referrer'],'advertiser_profiles')==false)
						  		$this->redirect($this->data['AdvertiserProfile']['referrer']);
							   $this->Session->setFlash('Your data has been submitted successfully.');
						
					 if(isset($this->data['AdvertiserProfile']['prvs_link']) && (strpos($this->data['AdvertiserProfile']['prvs_link'],'masterSheet')!=false)) {
					 		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['AdvertiserProfile']['prvs_link']);
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
					} else {
						$this->redirect(array('action' => 'index'));
					}
			   } else {
					$errors = $this->AdvertiserProfile->invalidFields();
					$this->Session->setFlash(implode('<br>', $errors));
			   }
			}
		}
	 		/*
			Function to Delete complete advertiser profile like saving offers ,vip offers, images , video , order record
			from advertiser order and advertiser detail in advertiser profiles table
			*/

		function advertiserProfileDelete($id) {
				$this->AdvertiserProfile->id = $id;
				$orderId = $this->AdvertiserProfile->query("SELECT order_id FROM advertiser_profiles WHERE id='".$id."'");
if(isset($orderId[0]['advertiser_profiles']['order_id'])) {
				$workOrderId = $this->AdvertiserProfile->query("SELECT id FROM work_orders WHERE advertiser_order_id = ".$orderId[0]['advertiser_profiles']['order_id']);		
				}	
			   $this->AdvertiserProfile->id = $id;
			   $this->loadModel('Image');
			   $this->loadModel('Video');
			   $this->loadModel('Offer');
			   $this->loadModel('AdvertiserOrder');
			   $this->loadModel('WorkOrder');
			   $this->loadModel('DailyDeal');
			   $this->loadModel('DailyDiscount');
			   $this->loadModel('SavingOffer');
			   $this->loadModel('TopTenBusiness');
			   $this->loadModel('VipOffer');
			   $this->loadModel('Voucher');
			   $this->loadModel('FrontUser');
			   $this->loadModel('OrderInstance');
			   $this->loadModel('ImageLibrary');
			   $this->loadModel('AdvertiserCategory');
			   if(isset($orderId[0]['advertiser_profiles']['order_id'])) {
			  		$this->WorkOrder->deleteAll(array('WorkOrder.advertiser_order_id'=>$orderId[0]['advertiser_profiles']['order_id']));
			   }
			   //$imageOfferOld = $this->Offer->query("SELECT id FROM offers WHERE advertiser_profile_id =".$id.";"); 
			   $imageGalleryOld = $this->Image->query("SELECT id, image_thumb,image_big FROM images WHERE advertiser_profile_id =".$id.";");
			   $videoOld = $this->Video->query("SELECT id, file_name FROM videos WHERE advertiser_profile_id =".$id.";");
		   
			    if(count($imageGalleryOld) > 0 ){
				   for($j=0; $j < count($imageGalleryOld) ;$j++){
					unlink(APP.'webroot/img/gallery/'.$imageGalleryOld[$j]['images']['image_thumb']);
					unlink(APP.'webroot/img/gallery/'.$imageGalleryOld[$j]['images']['image_big']);
					$this->Image->delete($imageGalleryOld[$j]['images']['id']); //---deleting gallery image entry for this advertiser	
				   }
			   }
			   if(isset($videoOld[0]['videos']['file_name']) && $videoOld[0]['videos']['file_name'] !=''){
			    	unlink(APP.'webroot/img/video/'.$videoOld[0]['videos']['file_name']);
			   }
			   if(isset($videoOld[0]['videos']['id'])) {
			   		$this->Video->delete($videoOld[0]['videos']['id']);
			   }//---deleting video entry for this advertiser
			   if(isset($orderId[0]['advertiser_profiles']['order_id'])) {
			   		$this->AdvertiserOrder->delete($orderId[0]['advertiser_profiles']['order_id']);
			   }
			   $this->SavingOffer->deleteAll(array('SavingOffer.advertiser_profile_id'=>$id));
			   $this->AdvertiserCategory->deleteAll(array('AdvertiserCategory.advertiser_profile_id'=>$id));
			   $this->TopTenBusiness->deleteAll(array('TopTenBusiness.advertiser_profile_id'=>$id));
			   $this->DailyDiscount->deleteAll(array('DailyDiscount.advertiser_profile_id'=>$id));
			   $this->DailyDeal->deleteAll(array('DailyDeal.advertiser_profile_id'=>$id));
			   $this->VipOffer->deleteAll(array('VipOffer.advertiser_profile_id'=>$id));
			   $this->Voucher->deleteAll(array('Voucher.advertiser_profile_id'=>$id));
			   $this->FrontUser->deleteAll(array('FrontUser.advertiser_profile_id'=>$id));
			   $this->ImageLibrary->deleteAll(array('ImageLibrary.advertiser_profile_id'=>$id));
			   $this->OrderInstance->deleteAll(array('OrderInstance.advertiser_profile_id'=>$id));
			   $this->AdvertiserProfile->delete($id);
			   $this->Session->setFlash('The Advertiser Profile with id: '.$id.' has been deleted.');
			   $this->redirect(array('action'=>'index'));
		}
		function getAdvertiserPublishStatus($id=null)
		{
			$this->AdvertiserProfile->id=$id;
			return $this->AdvertiserProfile->field('publish');
		}
			
		function advertiserProfileEditDetail($id=null)
		{
			   $this->set('StatesList',$this->common->getAllState());  //  List states
			   $this->set('CitiesList',$this->common->getAllCity());   //  List cities
			   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
			   $this->set('CountriesList',$this->common->getAllCountry()); //  List countries
			   $this->set('categoryList',$this->common->getAllCategory()); //  List categories
			   $this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
			   $data = $this->AdvertiserProfile->find('first',array('conditions'=>array('AdvertiserProfile.id'=>$id),'contain' => array('AdvertiserCategory'=>array('CategoriesSubcategory'=>array('Category.id','Subcategory.id')))));
		       $this->set('AdvertiserProfile',$data);
			   if((strpos($this->referer(),'masterSheet')!=false)) {
		  				$this->Session->write('reff',$this->referer());
		  		}
				if((strpos($this->referer(),'/edit/')!=false)) {
		  				$this->Session->write('reffChange',$this->referer());
		  		}
		  		if($this->Session->read('reff')) {
		   				$this->set('reff',$this->Session->read('reff'));
		   		} else {
		   				$this->set('reff',$this->referer());
		   		}
				
				if($this->Session->read('reffChange')) {
		   				$this->set('reffChange',$this->Session->read('reffChange'));
		   		} else {
		   				$this->set('reffChange',$this->referer());
		   		}
				$county_id = '';
				if(isset($this->data)) {
					$county_id = $this->data['AdvertiserProfile']['county'];
				}else {
					$county_id = $this->common->getCompanyCounty($id);
				}
				$this->set('county_id',$county_id);
			   //echo $_SERVER['HTTP_REFERER'];
			   $this->set('referrer',$_SERVER['HTTP_REFERER']);
			   App::import('model', 'AdvertiserOrder');
			   $this->AdvertiserOrder = new AdvertiserOrder;
			   $this->set('PackageId', $this->AdvertiserOrder->packageDetail($id));
			   $this->set('salesPersonId', $this->common->salesIdForAdvertiser($id));
			   $this->loadModel('User');
			   $this->set('salesperson', $this->User->returnUsersSales());
				if(isset($this->data))
				{
				
		  $this->AdvertiserProfile->set($this->data);
				  if($this->AdvertiserProfile->validates()){ 
					$this->data['AdvertiserProfile']['page_url'] = $this->common->makeAlias(trim($this->data['AdvertiserProfile']['company_name']));
					if(isset($this->data['AdvertiserProfile']['salesperson']) && $this->data['AdvertiserProfile']['salesperson']==''){
					     $this->Session->setFlash('Please select salesperson.'); 
						  return false;
					}
					 
					if(empty($this->data['AdvertiserProfile']['subcategory'][0])){
						$this->Session->setFlash('Please select atleast one subcategory');
						return false;
					}
				  	if(isset($this->data['AdvertiserOrder']['id']) and $this->data['AdvertiserOrder']['id']!=''){
					
					  if(isset($this->data['AdvertiserProfile']['salesperson'])) {
					  	$user = $this->common->getadminemailbyid($this->data['AdvertiserProfile']['salesperson']);
					  } else {
					  	$user = $this->common->getadminemailbyid($this->Session->read('Auth.Admin.id'));
					  }
					  
						//this is for edit
						 $this->loadModel('AdvertiserOrder');
						
						  if(isset($this->data['AdvertiserProfile']['salesperson'])) {
							$salesperson = $this->data['AdvertiserProfile']['salesperson'];
						  } else {
							$salesperson = $this->Session->read('Auth.Admin.id');
						  }
					  
					  
						$this->AdvertiserOrder->query("update advertiser_orders set salesperson ='".$salesperson."' where id='".$this->data['AdvertiserOrder']['id']."'");
						 $this->data['AdvertiserProfile']['order_id']			=	$this->data['AdvertiserOrder']['id'];
					
					  App::import('model', 'WorkOrder');
					  $this->WorkOrder = new WorkOrder;
					  $saveWorkArray = array();
					  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $this->data['AdvertiserOrder']['id'];
					  $saveWorkArray['WorkOrder']['read_status']   				=  0;
					  $saveWorkArray['WorkOrder']['subject']   					=  'Update to Advertiser Profile';
					  $saveWorkArray['WorkOrder']['message']   					=  'Following Advertiser order details has been updated by '.$this->common->groupName($this->Session->read('Auth.Admin.user_group_id')).' team (Name : '.$this->Session->read('Auth.Admin.name').').';
					  $saveWorkArray['WorkOrder']['type']   					=  'orderupdated';
					  
					  if(isset($this->data['AdvertiserProfile']['salesperson'])) {
							$saveWorkArray['WorkOrder']['sent_to'] = $this->data['AdvertiserProfile']['salesperson'];
						  } else {
							$saveWorkArray['WorkOrder']['sent_to'] = $this->Session->read('Auth.Admin.id');
						  }
					  $saveWorkArray['WorkOrder']['sent_to_group']   			=  5;
					  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
					  $saveWorkArray['WorkOrder']['bottom_line']				=  '';
					  $saveWorkArray['WorkOrder']['salseperson_id'] 			=  0;
					  date_default_timezone_set('US/Eastern');
					  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
					  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
					  
					  if(isset($this->data['AdvertiserProfile']['salesperson'])) {
							 $saveWorkArray['WorkOrder']['salseperson_id'] = $this->data['AdvertiserProfile']['salesperson'];
						  } else {
							 $saveWorkArray['WorkOrder']['salseperson_id'] = $this->Session->read('Auth.Admin.id');
						  }
					  $this->WorkOrder->save($saveWorkArray);
					}
					$this->data['AdvertiserProfile']['modifier']  			= $this->Session->read('Auth.Admin.id');
					date_default_timezone_set('US/Eastern');
					$this->data['AdvertiserProfile']['contract_date']  		= strtotime($this->data['AdvertiserProfile']['contract_date']);
					
					/*------------to set the multiple category and subcategory------------------*/
					$this->loadModel('AdvertiserCategory');
					$id = $this->data['AdvertiserProfile']['id'];
					$this->AdvertiserCategory->deleteAll(array('AdvertiserCategory.advertiser_profile_id'=>$id));
					foreach($this->data['AdvertiserProfile']['subcategory'] as $pair) {
						$break = '';
						$break = explode('-',$pair);
						$catSubcat = $this->common->returnCatSubcatId($break[0],$break[1]);
						if($catSubcat) {
							$save = '';
							$save['AdvertiserCategory']['id'] = '';
							$save['AdvertiserCategory']['advertiser_profile_id'] = $id;
							$save['AdvertiserCategory']['categories_subcategory_id'] = $catSubcat;
							$this->AdvertiserCategory->save($save,false);
						}
					}
					//--------------------------------Lat-Long---------------------------------------//		
						if(trim($this->data['AdvertiserProfile']['long'])=='' || trim($this->data['AdvertiserProfile']['lat'])=='') {
							$latlong = $this->common->latLong('United+States+'.$this->common->getStateName($this->data['AdvertiserProfile']['state']).'+'.$this->common->getCountyName($this->data['AdvertiserProfile']['county']).'+'.$this->common->getCityName($this->data['AdvertiserProfile']['city']).'+'.trim($this->data['AdvertiserProfile']['address']));
						}		
						if(trim($this->data['AdvertiserProfile']['long'])=='') {
							$this->data['AdvertiserProfile']['long'] = $latlong['long'];
						}
						
						if(trim($this->data['AdvertiserProfile']['lat'])=='') {
							$this->data['AdvertiserProfile']['lat'] = $latlong['lat'];
						}
					/*----------------------------------LOGO----------------------------------------*/				
					
					if(isset($this->data['AdvertiserProfile']['id'])){
						$imageOld = $this->AdvertiserProfile->query("SELECT logo FROM advertiser_profiles WHERE id =".$this->data['AdvertiserProfile']['id'].";");
					}
					
					
					if($this->data['AdvertiserProfile']['logo']['name']!=""){
					$type = $this->data['AdvertiserProfile']['logo']['type'];
					if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif"){
					                         
						if(isset($imageOld[0]['advertiser_profiles']['logo']) and file_exists(APP.'webroot/img/logo/'.$imageOld[0]['advertiser_profiles']['logo'])){
						  unlink(APP.'webroot/img/logo/'.$imageOld[0]['advertiser_profiles']['logo']);
						}
						

						$this->data['AdvertiserProfile']['logo']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['AdvertiserProfile']['logo']['name']);

						$docDestination = APP.'webroot/img/logo/'.$this->data['AdvertiserProfile']['logo']['name']; 
						@chmod(APP.'webroot/img/logo',0777);
						move_uploaded_file($this->data['AdvertiserProfile']['logo']['tmp_name'], $docDestination) or die($docDestination);
						$this->data['AdvertiserProfile']['logo'] = $this->data['AdvertiserProfile']['logo']['name'];						
					}
				}else{
					$this->data['AdvertiserProfile']['logo'] = $this->data['AdvertiserProfile']['old_logo'];		
				}
				
				
				/*--------------------------------Main Image start------------------------------------------*/
				
				if(isset($this->data['AdvertiserProfile']['main_image_type']) && $this->data['AdvertiserProfile']['main_image_type']==1 && isset($this->data['AdvertiserProfile']['main_image_upload']['name']) && $this->data['AdvertiserProfile']['main_image_upload']['name']!=""){
					$type = explode(".",$this->data['AdvertiserProfile']['main_image_upload']['name']);
						$old_main_mage=$this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.main_image','AdvertiserProfile.main_image_type'),'conditions'=>array('AdvertiserProfile.id'=>$this->data['AdvertiserProfile']['id'])));
						
						if(isset($old_main_mage['AdvertiserProfile']['main_image']) && $old_main_mage['AdvertiserProfile']['main_image']!='' && file_exists(APP.'webroot/img/logo/main_image/'.$old_main_mage['AdvertiserProfile']['main_image']))
						{
							 @chmod(APP.'webroot/img/logo/main_image',0777);
							 @unlink(APP.'webroot/img/logo/main_image/'.$old_main_mage['AdvertiserProfile']['main_image']);
						}
						    					
						$this->data['AdvertiserProfile']['main_image_upload']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['AdvertiserProfile']['main_image_upload']['name']);
						$docDestination = APP.'webroot/img/logo/main_image/'.$this->data['AdvertiserProfile']['main_image_upload']['name']; 
						@chmod(APP.'webroot/img/logo/main_image',0777);
						move_uploaded_file($this->data['AdvertiserProfile']['main_image_upload']['tmp_name'], $docDestination) or die($docDestination);
						$this->data['AdvertiserProfile']['main_image'] = $this->data['AdvertiserProfile']['main_image_upload']['name'];
				}elseif(isset($this->data['AdvertiserProfile']['main_image_type']) && $this->data['AdvertiserProfile']['main_image_type']==0 && isset($this->data['AdvertiserProfile']['main_image']) && $this->data['AdvertiserProfile']['main_image']!="")
				{
						@chmod(APP.'webroot/img/logo/main_image',0777);
					
						$old_main_mage=$this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.main_image','AdvertiserProfile.main_image_type'),'conditions'=>array('AdvertiserProfile.id'=>$this->data['AdvertiserProfile']['id'])));
						
						 if($old_main_mage['AdvertiserProfile']['main_image']!=$this->data['AdvertiserProfile']['main_image'])
						 {
							 @unlink(APP.'webroot/img/logo/main_image/'.$old_main_mage['AdvertiserProfile']['main_image']);
							 $new_pic_name=time().'_'.$this->data['AdvertiserProfile']['main_image'];
							 @copy(APP.'webroot/img/photo_gallery/'.$this->data['AdvertiserProfile']['main_image'],WWW_ROOT.'img/logo/main_image/'.$new_pic_name);
							 $this->data['AdvertiserProfile']['main_image']=$new_pic_name;
						 }else{
						 	$this->data['AdvertiserProfile']['main_image']=$this->data['AdvertiserProfile']['old_main_image'];
						 }
						 
				}else{
					$this->data['AdvertiserProfile']['main_image']=$this->data['AdvertiserProfile']['old_main_image'];
				}
				
				/*--------------------------------Main Image end------------------------------------------*/
				

					/*----------------------------------Offer Image----------------------------------------*/				
					
					if(isset($this->data['AdvertiserProfile']['id'])){
						$offerImageOld = $this->AdvertiserProfile->query("SELECT offer_image FROM advertiser_profiles WHERE id =".$this->data['AdvertiserProfile']['id'].";");
					}
					
					
					if($this->data['AdvertiserProfile']['offer_image']['name']!=""){
					$type = $this->data['AdvertiserProfile']['offer_image']['type'];
					if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif"){
					                         
						if(isset($imageOld[0]['advertiser_profiles']['offer_image']) and file_exists(APP.'webroot/img/offer/soffers/'.$offerImageOld[0]['advertiser_profiles']['offer_image'])){
						  unlink(APP.'webroot/img/offer/soffers/'.$offerImageOld[0]['advertiser_profiles']['offer_image']);
						}
						

						$this->data['AdvertiserProfile']['offer_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['AdvertiserProfile']['offer_image']['name']);

						$docDestination = APP.'webroot/img/offer/soffers/'.$this->data['AdvertiserProfile']['offer_image']['name']; 
						@chmod(APP.'webroot/img/offer/soffers',0777);
						move_uploaded_file($this->data['AdvertiserProfile']['offer_image']['tmp_name'], $docDestination) or die($docDestination);
						$this->data['AdvertiserProfile']['offer_image'] = $this->data['AdvertiserProfile']['offer_image']['name'];						
					}
				}else{
					$this->data['AdvertiserProfile']['offer_image'] = $this->data['AdvertiserProfile']['old_offer_image'];		
				}


					$this->data['TopTenBusiness']['city']        = $this->data['AdvertiserProfile']['city'];
					$this->data['TopTenBusiness']['county']        = $this->data['AdvertiserProfile']['county'];
					$this->data['TopTenBusiness']['state']        = $this->data['AdvertiserProfile']['state'];
					$this->data['TopTenBusiness']['category']    = '';//$this->data['AdvertiserProfile']['category'];
					$this->data['TopTenBusiness']['subcategory'] = '';//$this->data['AdvertiserProfile']['subcategory'];
					if($this->data['TopTenBusiness']['publish'] =='yes' && $this->data['AdvertiserProfile']['publish'] =='yes'){
					    $this->data['TopTenBusiness']['publish'] ='yes';
					    $this->data['TopTenBusiness']['status'] ='enable';
					}else{
					   
					   $this->data['TopTenBusiness']['publish'] ='no';
					    $this->data['TopTenBusiness']['status'] ='disable';
					
					}
				
					
					if(isset($this->data['AdvertiserProfile']['id']) && $this->data['AdvertiserProfile']['id'] !='' ){
						$this->data['AdvertiserProfile']['id'] = $this->data['AdvertiserProfile']['id'];
					}
					
					if(isset($this->data['TopTenBusiness']['id']) && $this->data['TopTenBusiness']['id'] !='' ){
						$this->data['TopTenBusiness']['id'] = $this->data['TopTenBusiness']['id'];
					}
					
					
					  if(isset($this->data['AdvertiserProfile']['salesperson'])) {
						 $this->data['AdvertiserProfile']['creator'] = $this->data['AdvertiserProfile']['salesperson'];
					  } else {
						 $this->data['AdvertiserProfile']['creator'] = $this->Session->read('Auth.Admin.id');
					  }
						  
					
					if($this->common->groupName($this->Session->read('Auth.Admin.user_group_id'))!='Salesperson')
					{
						$advProStatus=$this->getAdvertiserPublishStatus($id);
						if(isset($advProStatus) && $advProStatus!='' && $advProStatus=='no' && isset($this->data['AdvertiserProfile']['publish']) && $this->data['AdvertiserProfile']['publish']!='' && $this->data['AdvertiserProfile']['publish']=='yes')
						{
						  App::import('model', 'WorkOrder');
						  $this->WorkOrder = new WorkOrder;
						  
						  $savePublishWorkArray = array();	  
						  $savePublishWorkArray['WorkOrder']['advertiser_order_id']   		=  $this->data['AdvertiserOrder']['id'];
						  $savePublishWorkArray['WorkOrder']['read_status']   				=  0;
						  $savePublishWorkArray['WorkOrder']['subject']   					=  'Advertiser Profile published recently';
						  $savePublishWorkArray['WorkOrder']['message']   					=  'Advertiser profile has been published by '.$this->common->groupName($this->Session->read('Auth.Admin.user_group_id')).' team (Name : '.$this->Session->read('Auth.Admin.name').').';
						  $savePublishWorkArray['WorkOrder']['type']   					=  'profilepublish';
						
						  $savePublishWorkArray['WorkOrder']['sent_to'] = $this->Session->read('Auth.Admin.id');
						  $savePublishWorkArray['WorkOrder']['sent_to_group']   			=  5;
						  $savePublishWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
						  $savePublishWorkArray['WorkOrder']['bottom_line']				=  '';
						  $savePublishWorkArray['WorkOrder']['salseperson_id'] 			=  $this->data['AdvertiserProfile']['creator'];
						  date_default_timezone_set('US/Eastern');
						  $savePublishWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
						  $savePublishWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));	
						  $this->WorkOrder->save($savePublishWorkArray);			   
						}
					}	  
					$this->AdvertiserProfile->saveAll($this->data);
					
					$this->AdvertiserProfile->query("UPDATE saving_offers SET advertiser_status='".$this->data['AdvertiserProfile']['publish']."' WHERE advertiser_profile_id=".$this->data['AdvertiserProfile']['id']);
					
					
						 $this->loadModel('FrontUser');
						 $frontUser = $this->FrontUser->find('first',array('fields'=>array('FrontUser.id'),'conditions'=>array('FrontUser.advertiser_profile_id'=>$this->data['AdvertiserProfile']['id'])));
						 /*if(!isset($frontUser['FrontUser']['id'])) {
						 	 $password = $this->common->randomPassword(8);
							 $arr['FrontUser']['password'] = $this->Auth->password($password);
							 $this->sendUsernamePassword($this->data['AdvertiserProfile']['email'],$password);
						 }*/
						 $arr['FrontUser']['county_id']	= $this->data['AdvertiserProfile']['county'];
						 $arr['FrontUser']['state_id'] = $this->data['AdvertiserProfile']['state'];
						 $arr['FrontUser']['id']  = $frontUser['FrontUser']['id'];			 
						 $arr['FrontUser']['name'] = $this->data['AdvertiserProfile']['name'];
						 $arr['FrontUser']['email']  = $this->data['AdvertiserProfile']['email'];		
						 $arr['FrontUser']['status'] = $this->data['AdvertiserProfile']['publish'];
						 if($arr['FrontUser']['status']!='no') {
						 	$arr['FrontUser']['status'] = 'yes';
						 }
						 $arr['FrontUser']['advertiser_profile_id'] = $this->data['AdvertiserProfile']['id'];						 
						 $arr['FrontUser']['terms_condition'] = 1;
						 $arr['FrontUser']['receive_email'] = 1;
						 $arr['FrontUser']['zip'] = $this->data['AdvertiserProfile']['zip'];
						 $arr['FrontUser']['address'] 	  = $this->data['AdvertiserProfile']['address'];	
					 	 $arr['FrontUser']['city_id'] 	  = $this->data['AdvertiserProfile']['city'];
						 $arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
						 $this->FrontUser->save($arr);
											 
						if(isset($this->data['AdvertiserProfile']['referrer']) && strpos($this->data['AdvertiserProfile']['referrer'],'advertiser_profiles')==false)
						  		$this->redirect($this->data['AdvertiserProfile']['referrer']); 
								
							   $this->Session->setFlash('Your data has been updated successfully.');						
					 if(isset($this->data['AdvertiserProfile']['prvs_link']) && (strpos($this->data['AdvertiserProfile']['prvs_link'],'masterSheet')!=false)) {
					 		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['AdvertiserProfile']['prvs_link']);		
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
							} else if(isset($this->data['AdvertiserProfile']['reffChange']) && (strpos($this->data['AdvertiserProfile']['reffChange'],'/edit/')!=false)) {
								$ad_id = explode('/',$this->data['AdvertiserProfile']['reffChange']);
					 			$this->Session->delete('reffChange');
								$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/edit/'.$ad_id[3]);
					}else {
						   $this->redirect(array('action' => 'index'));
					}
						   
						   
                   }else{
				   
						$errors = $this->AdvertiserProfile->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
				   
				   }
					   
				}
		}

	function publishCat($cats){
		$this->loadModel('Category');
		$this->loadModel('Subcategory');
		foreach($cats as $cats) {
			$pair = '';
			$pair = explode('-',$cats);
			$savecat = '';
			$savecat['Category']['id'] = $pair[0];
			$savecat['Category']['publish'] = 'yes';
			$this->Category->save($savecat,false);
			
			$savesubcat = '';
			$savesubcat['Subcategory']['id'] = $pair[1];
			$savesubcat['Subcategory']['publish'] = 'yes';
			$this->Subcategory->save($savesubcat,false);
		}
	}	
	
	function subCatList(){
	
	//pr($this->data);
	$this->loadModel('Subcategory');
	$category_id ='';
	$sub_category_id ='';
	$subcategoryName ='';
	//pr($this->params);
	
	if(isset($this->params['pass']) && count($this->params['pass'])){
		$category_id =  $this->params['pass'][0];
		$sub_category_id =  $this->params['pass'][1]; 
		$subcategoryName = $this->params['pass'][1];
		
		
		if(isset($this->data['AdvertiserProfile']['category']) && $this->params['pass'][0] == $this->data['AdvertiserProfile']['category'] ){ 
		$category_id =  $this->params['pass'][0];
		$subcategoryName = $this->params['pass'][1];
		}else{
		$category_id =  $this->params['pass'][0]; 
		$subcategoryName = $this->params['pass'][1];
		}
	}else{
		 $category_id =  $this->data['AdvertiserProfile']['category'];
		 //$subcategoryName = '';
	}

	$subCategoryList  = $this->Subcategory->find('list', array('fields' => array('id', 'categoryname'),'order' => 'Subcategory.categoryname ASC','recursive' => -1,'conditions' => array( "AND" => array ('Subcategory.category_id LIKE' => '%,'.$category_id.',%',
	                   "Subcategory.publish " => 'yes')))); 
					   
	$this->set('subCategoryList',$subCategoryList); //  List Subcategories	
	$this->set('subcategoryName',$subcategoryName);
	}
	/************************** Function to find out all pending order ******************************/     
	  function pendingAdvertiserOrder() {
	   	$cond1 = '';
	   	$cond = '';
		$id = '';
	    $this->set('title_for_layout', 'Advertiser Pending Order Management');
		/* Sets all categories to view */
		$this->set('Categorys',$this->common->getAllCategory());
		/* Sets all counties to view */
		$this->set('Countys',$this->common->getAllCounty());
		$this->set('Packages', $this->common->getOnlyPackage());
		$this->set('search_text','Title');
		$this->set('category', 'Category');
		$this->set('county', 'County');
		$this->set('advertiser_search', '');
		$this->set('s_date', '');
		$this->set('e_date', '');
		$this->set('package_id', '');
		$this->set('AdvertiserProfiles', $this->common->getAllAdvertiserProfileForOrderListing());
		 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
	 // if only County is set	 
		 if($this->data['AdvertiserProfile']['county']!='' ||  isset($this->params['named']['county'])) 
		{
		  if(isset($this->params['named']['county']))  {
		  		$cond[] = 'AdvertiserProfile.county ='.$this->params['named']['county']; 
		  } else {
		  		$cond[] = 'AdvertiserProfile.county ='.$this->data['AdvertiserProfile']['county'];
		  }					  
		 (empty($this->params['named'])) ? $this->set('county', $this->data['AdvertiserProfile']['county']) :$this->set('county', $this->params['named']['county']) ; 
		}
		
		// if only Category is set	 				 
	 if((isset($this->data['AdvertiserProfile']['category']) && $this->data['AdvertiserProfile']['category']!='') || (isset($this->params['named']['category']) && $this->params['named']['category']!='')) 
	 {
		  $cat = '';
		  if(isset($this->params['named']['category']))
		  {
		  	$cat = $this->params['named']['category'];
		  }
		  else
		  {
		  	$cat = $this->data['AdvertiserProfile']['category'];
			 
		  }
		  
		  if($cat) {
		  
		  			$this->loadModel('AdvertiserCategory');
					
					$data = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT AdvertiserCategory.advertiser_profile_id','conditions'=>array('CategoriesSubcategory.category_id'=>$cat)));
					$profile = '';
					if(!empty($data)) {
						foreach($data as $data) {
							$profile[] = $data['AdvertiserCategory']['advertiser_profile_id'];
						}
					}
					if(is_array($profile)) {
						$profile_ids = implode(',',array_values(array_filter($profile)));
					} else {
						$profile_ids = 0;
					}
					$cond[] = 'AdvertiserProfile.id IN ('.$profile_ids.')';
		  }
		  
		  
		  				   
	(empty($this->params['named'])) ? $this->set('category', $this->data['AdvertiserProfile']['category']) :$this->set('category', $this->params['named']['category']) ;
	}
		
	// if only Package is set 				 
	if((isset($this->data['AdvertiserProfile']['package_id']) && $this->data['AdvertiserProfile']['package_id']!='') || (isset($this->params['named']['package_id']) && $this->params['named']['package_id']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['package_id'])) {
		  		$cond[] = "AdvertiserOrder.package_id = ".$this->data['AdvertiserProfile']['package_id'];
		  } else {
		  		$cond[] = "AdvertiserOrder.package_id = ".$this->params['named']['package_id'];
		  }					   
			(isset($this->data['AdvertiserProfile']['package_id'])) ? $this->set('package_id', $this->data['AdvertiserProfile']['package_id']) :$this->set('package_id', $this->params['named']['package_id']) ; 
	}
	// if only start date is set 				 
	if((isset($this->data['AdvertiserProfile']['s_date']) && $this->data['AdvertiserProfile']['s_date']!='') || (isset($this->params['named']['s_date']) && $this->params['named']['s_date']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['s_date'])) {
				$cond[] =  'AdvertiserOrder.created >='.strtotime($this->data['AdvertiserProfile']['s_date']);
		  } else {
				$cond[] =  'AdvertiserOrder.created >='.strtotime($this->params['named']['s_date']);
		  }					   
			(isset($this->data['AdvertiserProfile']['s_date'])) ? $this->set('s_date', $this->data['AdvertiserProfile']['s_date']) :$this->set('s_date',$this->params['named']['s_date']) ; 
	}
	// if only end date is set 				 
	if((isset($this->data['AdvertiserProfile']['e_date']) && $this->data['AdvertiserProfile']['e_date']!='') || (isset($this->params['named']['e_date']) && $this->params['named']['e_date']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['e_date'])) {
				$cond[] =  'AdvertiserOrder.created <='.strtotime($this->data['AdvertiserProfile']['e_date']);
		  } else {
		  		$cond[] =  'AdvertiserOrder.created <='.strtotime($this->params['named']['e_date']);
		  }					   
			(isset($this->data['AdvertiserProfile']['e_date'])) ? $this->set('e_date', $this->data['AdvertiserProfile']['e_date']) :$this->set('e_date',$this->params['named']['e_date']) ; 
	}
	// if only advertiser is set 				 
	if((isset($this->data['AdvertiserProfile']['advertiser_search']) && $this->data['AdvertiserProfile']['advertiser_search']!='') || (isset($this->params['named']['advertiser_search']) && $this->params['named']['advertiser_search']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['advertiser_search'])) {
		  		$cond[] = "AdvertiserProfile.id  = ".$this->data['AdvertiserProfile']['advertiser_search'];
		  } else {
		  		$cond[] = "AdvertiserProfile.id = ".$this->params['named']['advertiser_search'];
		  }					   
			(isset($this->data['AdvertiserProfile']['advertiser_search'])) ? $this->set('advertiser_search', $this->data['AdvertiserProfile']['advertiser_search']) :$this->set('advertiser_search',$this->params['named']['advertiser_search']) ; 
	}			

			
	if(is_array($cond)){
		$cond2 = 'AND '.implode(' AND ',$cond);
	} else {
		$cond2 = '';
	}
			$join = $this->AdvertiserProfile->query("SELECT AdvertiserOrder.*, AdvertiserProfile.* FROM advertiser_orders AS AdvertiserOrder, advertiser_profiles AS AdvertiserProfile WHERE AdvertiserProfile.order_id = AdvertiserOrder.id AND AdvertiserOrder.proof_status = 'pending' ".$cond2);
		$this->set('join',$join);
		foreach($join as $join) {
			$id[] = $join['AdvertiserOrder']['id'];
		}
		if(is_array($id)) {
		$id_in = '('.implode(',',$id).')';	
		} else {
		$id_in = "('0')";
		}
		$cond1[]	='AdvertiserProfile.order_id IN '.$id_in.' AND AdvertiserProfile.order_id<>0';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'fields'=>array('AdvertiserProfile.*'),'conditions'=>array($cond1));
		/*----It sets data to view by specified condition----*/
		 		$data = $this->paginate('AdvertiserProfile',$cond1);
		    	$this->set('AdvertiserProfile', $data); 			
	   }
	   
	/************************** Function to find out all pending order ******************************/     
	  function rejectedAdvertiserOrder() {
	   	$cond1 = '';
	   	$cond = '';
		$id = '';
	    $this->set('title_for_layout', 'Advertiser Rejected Order Management');		
		/* Sets all categories to view */
		$this->set('Categorys',$this->common->getAllCategory());		
		/* Sets all counties to view */
		$this->set('Countys',$this->common->getAllCounty());
		$this->set('Packages', $this->common->getOnlyPackage());
		$this->set('search_text','Title');
		$this->set('category', 'Category');
		$this->set('county', 'County');
		$this->set('advertiser_search', '');
		$this->set('s_date', '');
		$this->set('e_date', '');
		$this->set('package_id', '');		
		$this->set('AdvertiserProfiles', $this->common->getAllAdvertiserProfileForOrderListing());		
		 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
	// if only County is set	 
		 if($this->data['AdvertiserProfile']['county']!='' ||  isset($this->params['named']['county'])) 
		{
		  if(isset($this->params['named']['county'])) {
		  		$cond[] = 'AdvertiserProfile.county ='.$this->params['named']['county'];
		  } else {
		  		$cond[] = 'AdvertiserProfile.county ='.$this->data['AdvertiserProfile']['county'];
		  }
		 (empty($this->params['named'])) ? $this->set('county', $this->data['AdvertiserProfile']['county']) :$this->set('county', $this->params['named']['county']) ; 
		}
	// if only Category is set	 				 
	 		if((isset($this->data['AdvertiserProfile']['category']) && $this->data['AdvertiserProfile']['category']!='') || (isset($this->params['named']['category']) && $this->params['named']['category']!='')) 
	 {
		  $cat = '';
		  if(isset($this->params['named']['category']))
		  {
		  	$cat = $this->params['named']['category'];
		  }
		  else
		  {
		  	$cat = $this->data['AdvertiserProfile']['category'];
			 
		  }
		  
		  if($cat) {
		  
		  			$this->loadModel('AdvertiserCategory');
					
					$data = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT AdvertiserCategory.advertiser_profile_id','conditions'=>array('CategoriesSubcategory.category_id'=>$cat)));
					$profile = '';
					if(!empty($data)) {
						foreach($data as $data) {
							$profile[] = $data['AdvertiserCategory']['advertiser_profile_id'];
						}
					}
					if(is_array($profile)) {
						$profile_ids = implode(',',array_values(array_filter($profile)));
					} else {
						$profile_ids = 0;
					}
					$cond[] = 'AdvertiserProfile.id IN ('.$profile_ids.')';
		  }
		  
		  
		  				   
	(empty($this->params['named'])) ? $this->set('category', $this->data['AdvertiserProfile']['category']) :$this->set('category', $this->params['named']['category']) ;
	}		
	// if only package is set	 				 
	if((isset($this->data['AdvertiserProfile']['package_id']) && $this->data['AdvertiserProfile']['package_id']!='') || (isset($this->params['named']['package_id']) && $this->params['named']['package_id']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['package_id'])) {
		  		$cond[] = "AdvertiserOrder.package_id = ".$this->data['AdvertiserProfile']['package_id'];
		  } else {
		  		$cond[] = "AdvertiserOrder.package_id = ".$this->params['named']['package_id'];
		  }					   
			(isset($this->data['AdvertiserProfile']['package_id'])) ? $this->set('package_id', $this->data['AdvertiserProfile']['package_id']) :$this->set('package_id', $this->params['named']['package_id']) ; 
	}
	// if only start date is set 				 
	if((isset($this->data['AdvertiserProfile']['s_date']) && $this->data['AdvertiserProfile']['s_date']!='') || (isset($this->params['named']['s_date']) && $this->params['named']['s_date']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['s_date'])) {
				$cond[] =  'AdvertiserOrder.created >='.strtotime($this->data['AdvertiserProfile']['s_date']);
		  } else {
				$cond[] =  'AdvertiserOrder.created >='.strtotime($this->params['named']['s_date']);
		  }					   
			(isset($this->data['AdvertiserProfile']['s_date'])) ? $this->set('s_date', $this->data['AdvertiserProfile']['s_date']) :$this->set('s_date',$this->params['named']['s_date']) ; 
	}
	// if only end date is set 				 
	if((isset($this->data['AdvertiserProfile']['e_date']) && $this->data['AdvertiserProfile']['e_date']!='') || (isset($this->params['named']['e_date']) && $this->params['named']['e_date']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['e_date'])) {
				$cond[] =  'AdvertiserOrder.created <='.strtotime($this->data['AdvertiserProfile']['e_date']);
		  } else {
		  		$cond[] =  'AdvertiserOrder.created <='.strtotime($this->params['named']['e_date']);
		  }					   
			(isset($this->data['AdvertiserProfile']['e_date'])) ? $this->set('e_date', $this->data['AdvertiserProfile']['e_date']) :$this->set('e_date',$this->params['named']['e_date']) ; 
	}
	// if only advertiser is set 				 
	if((isset($this->data['AdvertiserProfile']['advertiser_search']) && $this->data['AdvertiserProfile']['advertiser_search']!='') || (isset($this->params['named']['advertiser_search']) && $this->params['named']['advertiser_search']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['advertiser_search'])) {
		  		$cond[] = "AdvertiserProfile.id  = ".$this->data['AdvertiserProfile']['advertiser_search'];
		  } else {
		  		$cond[] = "AdvertiserProfile.id = ".$this->params['named']['advertiser_search'];
		  }					   
			(isset($this->data['AdvertiserProfile']['advertiser_search'])) ? $this->set('advertiser_search', $this->data['AdvertiserProfile']['advertiser_search']) :$this->set('advertiser_search',$this->params['named']['advertiser_search']) ; 
	}

	if(is_array($cond)){ 
		$cond2 = 'AND '.implode(' AND ',$cond);
		} else {
		$cond2 = '';
		}	
		
		$join = $this->AdvertiserProfile->query("SELECT AdvertiserOrder.*, AdvertiserProfile.* FROM advertiser_orders AS AdvertiserOrder, advertiser_profiles AS AdvertiserProfile WHERE AdvertiserProfile.order_id = AdvertiserOrder.id AND AdvertiserOrder.proof_status = 'rejected' ".$cond2);		
		
		$this->set('join',$join);
		foreach($join as $join) {
			$id[] = $join['AdvertiserOrder']['id'];
		}
		if(is_array($id)) {
		$id_in = '('.implode(',',$id).')';	
		} else {
		$id_in = "('0')";
		}
		$cond1[]	='AdvertiserProfile.order_id IN '.$id_in.' AND AdvertiserProfile.order_id<>0';
		//pr($cond1);
		$this->paginate = array('limit' => PER_PAGE_RECORD,'fields'=>array('AdvertiserProfile.*'),'conditions'=>array($cond1));
		/*----------------------------------It sets data to view by specified condition--------------------------------------------------------*/
		 		$data = $this->paginate('AdvertiserProfile',$cond1);
		    	$this->set('AdvertiserProfile', $data);			
	   }
	/************************** Function to find out all pending order ******************************/
	  function approvedAdvertiserOrder() {
	   $cond1 = '';
	   	$cond = '';
		$id = '';
	    $this->set('title_for_layout', 'Advertiser Rejected Order Management');		
		$this->set('Categorys',$this->common->getAllCategory());		
		$this->set('Countys',$this->common->getAllCounty());
		$this->set('Packages', $this->common->getOnlyPackage());
		$this->set('search_text','Title');
		$this->set('category', 'Category');
		$this->set('county', 'County');
		$this->set('s_date', '');
		$this->set('e_date', '');
		$this->set('package_id', '');
		//$this->set('Advertiser',$this->common->getAllAdvdertiser());
		 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
	 // if only County is set	 
		 if($this->data['AdvertiserProfile']['county']!='' ||  isset($this->params['named']['county'])) 
		{
		  if(isset($this->params['named']['county'])) {
		  		$cond[] = 'AdvertiserProfile.county ='.$this->params['named']['county'];
		  }  else  {
		  		$cond[] = 'AdvertiserProfile.county ='.$this->data['AdvertiserProfile']['county'];
		  }					  
		 (empty($this->params['named'])) ? $this->set('county', $this->data['AdvertiserProfile']['county']) :$this->set('county', $this->params['named']['county']) ; 
		}
	// if only Category is set	 				 
	 	if((isset($this->data['AdvertiserProfile']['category']) && $this->data['AdvertiserProfile']['category']!='') || (isset($this->params['named']['category']) && $this->params['named']['category']!='')) 
	 {
		  $cat = '';
		  if(isset($this->params['named']['category']))
		  {
		  	$cat = $this->params['named']['category'];
		  }
		  else
		  {
		  	$cat = $this->data['AdvertiserProfile']['category'];
			 
		  }
		  
		  if($cat) {
		  
		  			$this->loadModel('AdvertiserCategory');
					
					$data = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT AdvertiserCategory.advertiser_profile_id','conditions'=>array('CategoriesSubcategory.category_id'=>$cat)));
					$profile = '';
					if(!empty($data)) {
						foreach($data as $data) {
							$profile[] = $data['AdvertiserCategory']['advertiser_profile_id'];
						}
					}
					if(is_array($profile)) {
						$profile_ids = implode(',',array_values(array_filter($profile)));
					} else {
						$profile_ids = 0;
					}
					$cond[] = 'AdvertiserProfile.id IN ('.$profile_ids.')';
		  }
		  
		  
		  				   
	(empty($this->params['named'])) ? $this->set('category', $this->data['AdvertiserProfile']['category']) :$this->set('category', $this->params['named']['category']) ;
	}		
	// if only Package is set		 				 
		if((isset($this->data['AdvertiserProfile']['package_id']) && $this->data['AdvertiserProfile']['package_id']!='') || (isset($this->params['named']['package_id']) && $this->params['named']['package_id']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['package_id'])) {
		  		$cond[] = "AdvertiserOrder.package_id = ".$this->data['AdvertiserProfile']['package_id'];
		  } else {
		  		$cond[] = "AdvertiserOrder.package_id = ".$this->params['named']['package_id'];
		  }					   
			(isset($this->data['AdvertiserProfile']['package_id'])) ? $this->set('package_id', $this->data['AdvertiserProfile']['package_id']) :$this->set('package_id', $this->params['named']['package_id']) ; 
	}
	// if only start date is set 				 
	if((isset($this->data['AdvertiserProfile']['s_date']) && $this->data['AdvertiserProfile']['s_date']!='') || (isset($this->params['named']['s_date']) && $this->params['named']['s_date']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['s_date'])) {
				$cond[] =  'AdvertiserOrder.created >='.strtotime($this->data['AdvertiserProfile']['s_date']);
		  } else {
				$cond[] =  'AdvertiserOrder.created >='.strtotime($this->params['named']['s_date']);
		  }					   
			(isset($this->data['AdvertiserProfile']['s_date'])) ? $this->set('s_date', $this->data['AdvertiserProfile']['s_date']) :$this->set('s_date',$this->params['named']['s_date']) ; 
	}
	// if only end date is set 				 
	if((isset($this->data['AdvertiserProfile']['e_date']) && $this->data['AdvertiserProfile']['e_date']!='') || (isset($this->params['named']['e_date']) && $this->params['named']['e_date']!='')) 
	 {
		  if(isset($this->data['AdvertiserProfile']['e_date'])) {
				$cond[] =  'AdvertiserOrder.created <='.strtotime($this->data['AdvertiserProfile']['e_date']);
		  } else {
		  		$cond[] =  'AdvertiserOrder.created <='.strtotime($this->params['named']['e_date']);
		  }					   
			(isset($this->data['AdvertiserProfile']['e_date'])) ? $this->set('e_date', $this->data['AdvertiserProfile']['e_date']) :$this->set('e_date',$this->params['named']['e_date']) ; 
	}
	
	if(is_array($cond)){ 
		$cond2 = 'AND '.implode(' AND ',$cond);
		} else {
		$cond2 = '';
		}	
		$join = $this->AdvertiserProfile->query("SELECT AdvertiserOrder.*, AdvertiserProfile.* FROM advertiser_orders AS AdvertiserOrder, advertiser_profiles AS AdvertiserProfile WHERE AdvertiserProfile.order_id = AdvertiserOrder.id AND AdvertiserOrder.proof_status = 'approved' ".$cond2);
		$this->set('join',$join);
		foreach($join as $join) {
			$id[] = $join['AdvertiserOrder']['id'];
		}
		if(is_array($id)) {
		$id_in = '('.implode(',',$id).')';	
		} else {
		$id_in = "('0')";
		}
		$cond1[]	='AdvertiserProfile.order_id IN '.$id_in.' AND AdvertiserProfile.order_id<>0';
		//pr($cond1);
		$this->paginate = array('limit' => PER_PAGE_RECORD,'fields'=>array('AdvertiserProfile.*'),'conditions'=>array($cond1));
		/*----------------------------------It sets data to view by specified condition--------------------------------------------------------*/
		 		$data = $this->paginate('AdvertiserProfile',$cond1);
		    	$this->set('AdvertiserProfile', $data);			
	   }
	   
/************************** function to confirm to delete advertiser profile ******************************/
	function confirmDelete($advertisor_id) {
		$this->id = $advertisor_id;
		$this->set('advertisor_id',$advertisor_id);
		$company_name = $this->AdvertiserProfile->field('AdvertiserProfile.company_name');
		$this->set('company_name',$company_name);
	}
/************************** function to use in daily discount add with ajax call ******************************/   
	function logo() {
	if(isset($this->data['DailyDiscount']['advertiser_profile_id']) || isset($this->params['pass'][0])) {
	$aid = (isset($this->data['DailyDiscount']['advertiser_profile_id'])) ? $this->data['DailyDiscount']['advertiser_profile_id'] : $this->params['pass'][0];
			$AdvertiserProfile = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.logo'),'conditions'=>array('AdvertiserProfile.id'=>$aid)));
			$logo = ($AdvertiserProfile['AdvertiserProfile']['logo']!='')?$AdvertiserProfile['AdvertiserProfile']['logo']:'';
			$this->set('logo',$logo);
		}
	}
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocompleteMastersheet($string='') {
			$this->autoRender = false;
			if($string!=''){
				$arr = '';
				$name = $this->AdvertiserProfile->query("SELECT AdvertiserProfile.company_name FROM advertiser_profiles AS AdvertiserProfile WHERE AdvertiserProfile.company_name LIKE '$string%'");
			foreach($name as $name) {
				$arr[] = $name['AdvertiserProfile']['company_name'];
			}
				echo json_encode($arr);
			}
	}
/*------------------------------------------------------------------------------------------------------------------------*/ 	
#this function call by default when a controller is called
	 function mastersheetListing() 
	 {
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;	
		if($this->Session->check('Auth.Admin'))
		{
		   $this->set('StatesList',$this->common->getAllState());  //  List states
		   $this->set('CitiesList',$this->common->getAllCity());   //  List cities
		   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   $this->set('CountriesList',$this->common->getAllCountry()); //  List countries
		   $this->set('Packages', $this->common->getOnlyPackage());
		   $this->set('common',$this->common);
		   $this->set('SelsePersons',$this->common->getAllSelsePerson(5));
		   $condition='';
		   $cond ='';
		   $this->set('company_name','Company Name');
		   $this->set('city','');
		   $this->set('state','');
		   $this->set('county','');
		   $this->set('category','');
		   $this->set('package_id','');
		   $this->set('salse_id','');
		   $this->set('publish','');		   
		   	$cond1 = '';
			$cond2 = '';
			$id = '';
	       $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('AdvertiserProfile.id' => 'desc'),'contain' => array('AdvertiserCategory'=>array('CategoriesSubcategory'=>array('Category.categoryname','Category.publish'))));

		if(($this->data['AdvertiserProfile']['company_name'] && $this->data['AdvertiserProfile']['company_name'] !='Company Name') ||  (isset($this->params['named']['company_name']) && $this->params['named']['company_name'] !='Company Name'))
		
		 {
		if(isset($this->params['named']['company_name']))
		{
		    $cond[] = 'AdvertiserProfile.company_name LIKE "%' . str_replace("%20"," ",$this->params['named']['company_name']). '%"';
		}
		else
		{
		 $cond[] = 'AdvertiserProfile.company_name LIKE "%' .$this->data['AdvertiserProfile']['company_name']. '%"';
		 }
		(empty($this->params['named'])) ? $this->set('company_name', $this->data['AdvertiserProfile']['company_name']) :$this->set('company_name', $this->params['named']['company_name']) ; 
		 }
		if($this->data['AdvertiserProfile']['city']!='' ||  isset($this->params['named']['city'])) 
		{
		   if(isset($this->params['named']['city']))
		   {
		     $cond[] = $this->params['named']['city'];
		   }
		   else
		   {
				 $cond[] = 'AdvertiserProfile.city = '.$this->data['AdvertiserProfile']['city'];
		   }
				   
		  (empty($this->params['named'])) ? $this->set('city', $this->data['AdvertiserProfile']['city']) :$this->set('city', $this->params['named']['city']) ; 
	   }
				 
		if($this->data['AdvertiserProfile']['state']!='' ||  isset($this->params['named']['state'])) 
		{ 
		   if(isset($this->params['named']['state']))
		   {
			   $cond[] = 'AdvertiserProfile.state = '.$this->params['named']['state'];
		   }
		   else
		   {
			 $cond[] = 'AdvertiserProfile.state = '.$this->data['AdvertiserProfile']['state'];
		   }
		 (empty($this->params['named'])) ? $this->set('state', $this->data['AdvertiserProfile']['state']) :$this->set('state', $this->params['named']['state']) ; 
	   }
				 
	if($this->data['AdvertiserProfile']['county']!='' ||  isset($this->params['named']['county'] )) 
	{
		  if(isset($this->params['named']['county']))
		  {
			 $cond[] = 'AdvertiserProfile.county = '.$this->params['named']['county'];
		  }
		  else
		  {
			  $cond[] = 'AdvertiserProfile.county = '.$this->data['AdvertiserProfile']['county'];
		  }
					  
		 (empty($this->params['named'])) ? $this->set('county', $this->data['AdvertiserProfile']['county']) :$this->set('county', $this->params['named']['county']) ; 
	}
				 
	 if((isset($this->data['AdvertiserProfile']['category']) && $this->data['AdvertiserProfile']['category']!='') || (isset($this->params['named']['category']) && $this->params['named']['category']!='')) 
	 {
		  $cat = '';
		  if(isset($this->params['named']['category']))
		  {
		  	$cat = $this->params['named']['category'];
		  }
		  else
		  {
		  	$cat = $this->data['AdvertiserProfile']['category'];
			 
		  }
		  
		  if($cat) {
		  
		  			$this->loadModel('AdvertiserCategory');
					$cats_pair = explode('-',$cat);
					
					$data = $this->AdvertiserCategory->find('all',array('fields'=>'AdvertiserCategory.advertiser_profile_id','conditions'=>array('CategoriesSubcategory.category_id'=>$cats_pair[0],'CategoriesSubcategory.subcategory_id'=>$cats_pair[1])));
					$profile = '';
					if(!empty($data)) {
						foreach($data as $data) {
							$profile[] = $data['AdvertiserCategory']['advertiser_profile_id'];
						}
					}
					if(is_array($profile)) {
						$profile_ids = implode(',',array_values(array_filter($profile)));
					} else {
						$profile_ids = 0;
					}
					$cond[] = 'AdvertiserProfile.id IN ('.$profile_ids.')';
		  }
					   
	(empty($this->params['named'])) ? $this->set('category', $this->data['AdvertiserProfile']['category']) :$this->set('category', $this->params['named']['category']) ; 
	}	
	 if((isset($this->data['AdvertiserProfile']['publish']) && $this->data['AdvertiserProfile']['publish']!='') || (isset($this->params['named']['publish']) && $this->params['named']['publish']!='')) 
	 {
		  if(isset($this->params['named']['publish']))
		  {
			 $cond[] = 'AdvertiserProfile.publish = "'.$this->params['named']['publish'].'"';
		  }
		  else
		  {
			 $cond[] = 'AdvertiserProfile.publish = "'.$this->data['AdvertiserProfile']['publish'].'"';
		  }
					   
	(empty($this->params['named'])) ? $this->set('publish', $this->data['AdvertiserProfile']['publish']) :$this->set('publish', $this->params['named']['publish']) ; 
	}
	 if((isset($this->data['AdvertiserProfile']['package_id']) && $this->data['AdvertiserProfile']['package_id']!='') || (isset($this->params['named']['package_id']) && $this->params['named']['package_id']!='')) 
	 {
		  if(isset($this->params['named']['package_id']))
		  {
			 $cond[] = 'AdvertiserOrder.package_id = '.$this->params['named']['package_id'];
		  }
		  else
		  {
			 $cond[] = 'AdvertiserOrder.package_id = '.$this->data['AdvertiserProfile']['package_id'];
		  }
					   
	(empty($this->params['named'])) ? $this->set('package_id', $this->data['AdvertiserProfile']['package_id']) :$this->set('package_id', $this->params['named']['package_id']) ; 
	}	
	 if((isset($this->data['AdvertiserProfile']['salse_id']) && $this->data['AdvertiserProfile']['salse_id']!='') || (isset($this->params['named']['salse_id']) && $this->params['named']['salse_id']!='')) 
	 {
		  if(isset($this->params['named']['salse_id']))
		  {
			 $cond[] = 'AdvertiserOrder.salesperson = '.$this->params['named']['salse_id'];
		  }
		  else
		  {
			 $cond[] = 'AdvertiserOrder.salesperson = '.$this->data['AdvertiserProfile']['salse_id'];
		  }
					   
	(empty($this->params['named'])) ? $this->set('salse_id', $this->data['AdvertiserProfile']['salse_id']) :$this->set('salse_id', $this->params['named']['salse_id']) ; 
	}	 
			if(is_array($cond)){ 
					$cond2 = 'AND '.implode(' AND ',$cond);
					} else {
					$cond2 = '';
		    }
				$join = $this->AdvertiserProfile->query("SELECT AdvertiserOrder.*, AdvertiserProfile.* FROM advertiser_orders AS AdvertiserOrder, advertiser_profiles AS AdvertiserProfile WHERE AdvertiserProfile.order_id = AdvertiserOrder.id AND AdvertiserOrder.save_later<>1 ".$cond2);
				foreach($join as $join) {
					$id[] = $join['AdvertiserOrder']['id'];
				}
				if(is_array($id)) {
				$id_in = '('.implode(',',$id).')';	
				} else {
				$id_in = "('0')";
				}
				$cond1[] = 'AdvertiserProfile.order_id IN '.$id_in.' AND AdvertiserProfile.represent_call="no"';
						 
				$data = $this->paginate('AdvertiserProfile', $cond1);
		        $this->set('AdvertiserProfiles', $data); 
 
			}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}

	 }
/************************** function to use in master sheet section ******************************/ 
	function masterSheet($id) {
		if(!$id) {
			$this->redirect(array('action'=>'mastersheetListing'));
		} else {
			$this->set('title_for_layout', 'Master Sheet of Advertiser Profile');
			$this->AdvertiserProfile->id = $id;
			$data = $this->AdvertiserProfile->read();
			$this->set('data',$data);
			$this->set('categoryList',$this->common->getAllCategory()); //  List categories
			$this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
		}	
	}
/************************** function to use in master sheet section ******************************/ 
	function send_proof($id=1) {
		if(isset($this->data['AdvertiserProfile']['id'])) {
			$this->AdvertiserProfile->id = $this->data['AdvertiserProfile']['id'];
		} else {
			$this->AdvertiserProfile->id = base64_decode($id);
		}
		$this->set('title_for_layout', 'All Proofs of Advertiser Profile');
		
		$data = $this->AdvertiserProfile->read();
		
		$this->set('data',$data);
		$order = $this->common->getorderdetail($data['AdvertiserProfile']['order_id']);
		$this->set('order',$order);
		$this->set('categoryList',$this->common->getAllCategory()); //  List categories
		$this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
		
	/**************************************************** send proof to advertiser *******************************************************************/
		if(isset($this->data['AdvertiserProfile']['step']) && $this->data['AdvertiserProfile']['step'] == 'send_proof') {
				$savearray = array('id'=>$this->data['AdvertiserProfile']['order_id'],'order_status'=>'pending','proof_status'=>'pending','proof_send_date'=>time(),'proof_reject_date'=>'');
				$this->loadModel('AdvertiserOrder');
				$this->AdvertiserOrder->save($savearray);
		// email to advertiser
				$this->sent_proof_email($data['AdvertiserProfile']['id'],$data['AdvertiserProfile']['email'],$data['AdvertiserProfile']['name']);
				
		// message to related salseperson
				$msg = "Following Advertiser's proof details has been sent by admin team.";
				$type = 'proofsent';
				$to = $order['AdvertiserOrder']['salesperson'];
				$form =  $this->Session->read('Auth.Admin.user_group_id');
				$b_line = 'Please follow below url:<br /><br /><a href="'.FULL_BASE_URL.Router::url("/",false).'advertiser_profiles/send_proof/'.base64_encode($this->data['AdvertiserProfile']['id']).'" style="text-decoration:underline;" target="_blank">'.FULL_BASE_URL.Router::url('/',false).'advertiser_profiles/send_proof/'.base64_encode($this->data['AdvertiserProfile']['id']).'</a>';
				
				$this->proof_message($this->data['AdvertiserProfile']['order_id'],'Proof sent by admin',$msg,$type,$to,5,$form,$b_line,$data['AdvertiserProfile']['creator']);
				$this->Session->setFlash('Proof has been sent to advertiser successfully.');
				$this->redirect(array('controller'=>'advertiser_profiles','action'=>'send_proof',base64_encode($this->data['AdvertiserProfile']['id'])));
			}
	/**************************************************** reject proof by advertiser *******************************************************************/
		if(isset($this->data['AdvertiserProfile']['step']) && $this->data['AdvertiserProfile']['step'] == 'reject_proof') {
				$savearray = array('id'=>$this->data['AdvertiserProfile']['order_id'],'order_status'=>'rejected','proof_status'=>'rejected','proof_send_date'=>'','proof_reject_date'=>time(),'reject_reason'=>$this->data['AdvertiserProfile']['reason']);
				$this->loadModel('AdvertiserOrder');
				$this->AdvertiserOrder->save($savearray);
		// Email to advertiser
				$this->reject_proof_email($data['AdvertiserProfile']['email'],$data['AdvertiserProfile']['name']);
		// message to Admin by advertiser
				$msg = "Following Advertiser has rejected the proof.";
				$type = 'proofreject';
				$to = 0;
				$form =  'Advertiser';
				$b_line = 'Please follow below url:<br /><br /><a href="'.FULL_BASE_URL.Router::url("/",false).'advertiser_profiles/send_proof/'.base64_encode($this->data['AdvertiserProfile']['id']).'" style="text-decoration:underline;" target="_blank">'.FULL_BASE_URL.Router::url('/',false).'advertiser_profiles/send_proof/'.base64_encode($this->data['AdvertiserProfile']['id']).'</a>';
				
				$this->proof_message($this->data['AdvertiserProfile']['order_id'],'Proof rejected by Advertiser',$msg,$type,$to,1,$form,$b_line,$data['AdvertiserProfile']['creator']);
				
				$this->Session->setFlash('Proof has been rejected.');
				$this->redirect(array('controller'=>'advertiser_profiles','action'=>'send_proof',base64_encode($this->data['AdvertiserProfile']['id'])));
			}
	/**************************************************** accept proof by advertiser *******************************************************************/
		if(isset($this->data['AdvertiserProfile']['step']) && $this->data['AdvertiserProfile']['step'] == 'accept_proof') {
				$savearray = array('id'=>$this->data['AdvertiserProfile']['order_id'],'order_status'=>'approved','proof_status'=>'approved','proof_reject_date'=>'','proof_accept_date'=>time());
				$this->loadModel('AdvertiserOrder');
				$this->AdvertiserOrder->save($savearray);
		// message to Admin by advertiser
				$msg = "Following Advertiser has accepted the proof.";
				$type = 'proofaccept';
				$to = 0;
				$form =  'Advertiser';
				$b_line = 'Please follow below url:<br /><br /><a href="'.FULL_BASE_URL.Router::url("/",false).'advertiser_profiles/send_proof/'.base64_encode($this->data['AdvertiserProfile']['id']).'" style="text-decoration:underline;" target="_blank">'.FULL_BASE_URL.Router::url('/',false).'advertiser_profiles/send_proof/'.base64_encode($this->data['AdvertiserProfile']['id']).'</a>';

				$this->proof_message($this->data['AdvertiserProfile']['order_id'],'Proof accepted by Advertiser',$msg,$type,$to,1,$form,$b_line,$data['AdvertiserProfile']['creator']);
				
				$this->loadModel('FrontUser');
				$userid = $this->FrontUser->find('first',array('fields'=>array('FrontUser.id'),'conditions'=>array('FrontUser.advertiser_profile_id'=>$this->data['AdvertiserProfile']['id'])));
				$this->AdvertiserProfile->id = $this->data['AdvertiserProfile']['id'];
				$this->Session->setFlash('Proof has been accepted successfully.');
				$this->redirect(array('controller'=>'advertiser_profiles','action'=>'send_proof',base64_encode($this->data['AdvertiserProfile']['id'])));
			}
		
	}	
	//message on proof 
	function proof_message($order_id,$sub,$msg,$type,$to,$to_grp,$form,$b_line,$salse_id) {
					  $this->loadModel('WorkOrder');
					  $saveWorkArray = array();
					  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $order_id;
					  $saveWorkArray['WorkOrder']['read_status']   				=  0;
					  $saveWorkArray['WorkOrder']['subject']   					=  $sub;
					  $saveWorkArray['WorkOrder']['message']   					=  $msg;
					  $saveWorkArray['WorkOrder']['type']   					=  $type;
					  $saveWorkArray['WorkOrder']['sent_to']   					=  $to;
					  $saveWorkArray['WorkOrder']['sent_to_group']   			=  $to_grp;
					  $saveWorkArray['WorkOrder']['from_group']   				=  $form;
					  $saveWorkArray['WorkOrder']['bottom_line']				=  $b_line;
					  $saveWorkArray['WorkOrder']['salseperson_id'] 			=  $salse_id;
					  date_default_timezone_set('US/Eastern');
					  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
					  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
					 $this->WorkOrder->save($saveWorkArray);
	}
	function proof_messages($order_id,$sub1,$msg1,$type1,$to1,$to_grp1,$form1,$b_line1,$salse_id1) {
					 $this->loadModel('WorkOrder');
					  $saveWorkArray1 = array();
					  $saveWorkArray1['WorkOrder']['id']							= $this->WorkOrder->id+1;
					  $saveWorkArray1['WorkOrder']['advertiser_order_id'] 	 		=  $order_id;
					  $saveWorkArray1['WorkOrder']['read_status']   				=  0;
					  $saveWorkArray1['WorkOrder']['subject']   					=  $sub1;
					  $saveWorkArray1['WorkOrder']['message']   					=  $msg1;
					  $saveWorkArray1['WorkOrder']['type']   						=  $type1;
					  $saveWorkArray1['WorkOrder']['sent_to']   					=  $to1;
					  $saveWorkArray1['WorkOrder']['sent_to_group']   				=  $to_grp1;
					  $saveWorkArray1['WorkOrder']['from_group']   					=  $form1;
					  $saveWorkArray1['WorkOrder']['bottom_line']					=  $b_line1;
					  $saveWorkArray1['WorkOrder']['salseperson_id'] 				=  $salse_id1;
					  date_default_timezone_set('US/Eastern');
					  $saveWorkArray1['WorkOrder']['created_date']   			    =  date(DATE_FORMAT.' h:i:s A');
					  $saveWorkArray1['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
					  $this->WorkOrder->save($saveWorkArray1);
	}
/***************************************** Email to advertiser when admin send proof ********************************************/
	function sent_proof_email($id,$email,$name) {
	
		/*------------Manage Email Template---------------*/
			App::import('model', 'Setting');
			$this->Setting = new Setting;
			$emailArray = $this->Setting->getSentProofEmailData();
			$subject 		= $emailArray[0]['settings']['new_sent_proof_subject'];
			$link			= '<a href="'.FULL_BASE_URL.Router::url("/",false).'advertiser_profiles/send_proof/'.base64_encode($id).'" style="text-decoration:underline;" target="_blank">'.FULL_BASE_URL.Router::url('/',false).'advertiser_profiles/send_proof/'.base64_encode($id).'</a>';
			$bodyText 		= $this->Setting->replaceMarkersSentProof($emailArray[0]['settings']['new_sent_proof_body'],$name,$link);
/*-----------------------------------------------*/
			//ADMINMAIL id
			$this->Email->to 		= $email;
			$this->Email->subject 	= strip_tags($subject);
			$this->Email->replyTo 	= $this->common->getReturnEmail();
			$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
			$this->Email->sendAs 	= 'html';
			//Set the body of the mail as we send it.			
			//seperate line in the message body.			
			
			$this->body = '';					
			$this->body = $this->emailhtml->email_header();
			$this->body .=$bodyText;
			$this->body .= $this->emailhtml->email_footer();
												
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"send_proof");
					/////////////////////////////////////////////////////////////////////////
	
		}	
/***************************************** Email to advertiser when reject proof ********************************************/	
	function reject_proof_email($email,$name) {
		
			$arrayTags = array("[name]","[email]");
			$arrayReplace = array($name,$email);
			
			//get Mail format 
			$this->loadModel('Setting');
			$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.reject_proof_subject','Setting.reject_proof_body','Setting.sales_email')));
			$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['reject_proof_subject']);
			$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['reject_proof_body']);
			
			$this->body = '';
			$this->body = $this->emailhtml->email_header();
			$this->body .=$bodyText;
			$this->body .= $this->emailhtml->email_footer();
			
			//ADMINMAIL id
			$this->Email->to 		= $email;
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
				$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"reject_proof");
			/////////////////////////////////////////////////////////////////////////								
	}
/***************************************** Email to advertiser when reject proof ********************************************/
function update_saving_offer($id) {
	$this->autoRender = false;
	$this->loadModel('savingOffer');
	$data = '';
	
		foreach($this->data['main'] as $key=>$value) {
			$a = explode('_',$key);
			$data['savingOffer']['id']						=	$a[1];
			$data['savingOffer']['current_saving_offer']	=	$value;
			$this->savingOffer->save($data);
		}
		$data1 = '';
		foreach($this->data['AdvertiserProfile'] as $key=>$value) {
			$b = explode('_',$key);
			if(isset($b[1])) {
				$data1['savingOffer']['id']						=	$b[1];
				$data1['savingOffer']['other_saving_offer']		=	$value;
				$this->savingOffer->save($data1);
			}
		}
			$myAdvertiserInfo=$this->common->getAdvertiserdetailswithOrder($id);
			//------------------------inbox notification------------//								
			  App::import('model', 'WorkOrder');
			  $this->WorkOrder = new WorkOrder;
			  $saveWorkArray = '';
			  $saveWorkArray['WorkOrder']['id']   						=  '';
			  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $myAdvertiserInfo['order_id'];
			  $saveWorkArray['WorkOrder']['read_status']   				=  0;
			  $saveWorkArray['WorkOrder']['subject']   					=  'Saving Offer Update';
			  $saveWorkArray['WorkOrder']['message']   					=  'A saving offer has been updated recently. details are below:';
			  $saveWorkArray['WorkOrder']['type']   					=  'savingworkorderupdate';
			  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
			  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
			  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
			  $saveWorkArray['WorkOrder']['bottom_line']   				=  '';
			  $saveWorkArray['WorkOrder']['salseperson_id'] 			=  $myAdvertiserInfo['creator'];
			  date_default_timezone_set('US/Eastern');
			  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
			  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
			  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can check all other offers for this advertiser in Advertiser profiles section and pulish them. Please follow below url:<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'saving_offers/index/'.$myAdvertiserInfo['id'].'" style="text-decoration:underline;" target="_blank">Saving offers Listing</a>';
			  $this->WorkOrder->save($saveWorkArray);	


		$msg = 'Saving offers updated successfully.';
		$filling = 'success';
		$data2 = '';
		if(!empty($this->data['zuni_care'])){
			foreach($this->data['zuni_care'] as $key=>$value) {
				if($value==0) {
					$data2['savingOffer']['id']						=	$key;
					$data2['savingOffer']['zuni_care']				=	$value;
					$this->savingOffer->save($data2);
				} else if($value==1) {
				
					$check = $this->common->checkSavingOffer($key);
					$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
					if($check['offer_expiry_date']<$today) {
					
						$filling = 'error';
						$msg = 'You can\'t choose an expired offer as zuni care.';
						
					} else if($check['status']=='no') {
					
						$filling = 'error';
						$msg = 'You can\'t choose an un-published offer as zuni care.';
						
					} else {
					
						$zuni_care = $this->common->checkZuniCare($key,$this->data['AdvertiserProfile']['county']);
						if(empty($zuni_care)) {
							$data2['savingOffer']['id']						=	$key;
							$data2['savingOffer']['zuni_care']				=	$value;
							$this->savingOffer->save($data2);
						} else {
							$filling = 'error';
							$msg = 'Zuni care offer is still running by '.$zuni_care['AdvertiserProfile']['company_name'].' from : '.date(DATE_FORMAT,$zuni_care['SavingOffer']['offer_start_date']).' to '.date(DATE_FORMAT,$zuni_care['SavingOffer']['offer_expiry_date']).'.';
						}
					}
				}
			}
		}
		$this->Session->setFlash($msg);
		$this->redirect($this->referer().'/type:'.$filling);
	}
// Function to save Customer registration data on first choice
	function saveCustomerdata($data) {
				$this->autoRender = false;
				$alldata = explode('|',$data);
				$this->loadModel('FrontUser');
				$arr['FrontUser']['name'] = $alldata[0].' '.$alldata[1];
				$arr['FrontUser']['zip'] = $alldata[2];
				$arr['FrontUser']['password']  = $this->Auth->password($alldata[4]);
				$arr['FrontUser']['realpassword']  = $alldata[4];
				$arr['FrontUser']['email']  = $alldata[3];
				$arr['FrontUser']['county_id']  =$this->Session->read('county_data.id');
				$arr['FrontUser']['user_type']  = 'customer';
				$arr['FrontUser']['terms_condition'] = $alldata[5];
				$arr['FrontUser']['receive_email'] 	  = $alldata[6];
				$arr['FrontUser']['type']			= 	'Front End';
				$arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
				//$arr['FrontUser']['address'] 	  = $alldata[7];
				//$arr['FrontUser']['city_id'] 	  = $alldata[8];
				$this->FrontUser->save($arr);
				
				$this->loadModel('NewsletterUser');			
				$arr['NewsletterUser']['name'] = $alldata[0].' '.$alldata[1];
				$arr['NewsletterUser']['email'] = $alldata[3];
				$arr['NewsletterUser']['zipcode'] = $alldata[2];
				$arr['NewsletterUser']['user_id'] = $this->FrontUser->getLastInsertId();
				$arr['NewsletterUser']['category_id'] = $alldata[9];
				$arr['NewsletterUser']['county_id'] = $this->Session->read('county_data.id');
				$this->NewsletterUser->save($arr);
				
				$this->loadModel('Setting');
				$setvale = $this->Setting->find('first',array('fields'=>array('refer_friend_bucks')));
				$bucksprice = $setvale['Setting']['refer_friend_bucks'];
				$this->loadModel('ReferredFriend');
				$checkRefer = $this->ReferredFriend->find('first',array('conditions'=>array('ReferredFriend.email'=>$alldata[3],'ReferredFriend.status'=>'no')));
				if(is_array($checkRefer)) {
					$savearr1 = '';
					$savearr = '';
					$savearr['ReferredFriend']['id'] = $checkRefer['ReferredFriend']['id'];
					$savearr['ReferredFriend']['status'] = 'yes';
					$savearr['ReferredFriend']['bucks'] = $bucksprice;
					$savearr['ReferredFriend']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
					$savearr['ReferredFriend']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$savearr1['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
					$savearr1['FrontUser']['total_bucks'] = (int)$checkRefer['FrontUser']['total_bucks']+(int)$bucksprice;	
					$savearr1['FrontUser']['unique_id']	=	$this->common->randomPassword(10);				
					$this->ReferredFriend->save($savearr);
					$this->FrontUser->save($savearr1);
					
					$this->loadModel('Buck');
					$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$this->Session->read('county_data.id'),'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
					if(is_array($checkBuck) && count($checkBuck)) {
						$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
						$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
					} else {
						$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
						$saveBuck['Buck']['county_id'] = $this->Session->read('county_data.id');
						$saveBuck['Buck']['bucks'] = $bucksprice;
						$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					}
					$this->Buck->save($saveBuck);
				}
				
				
		//------------------------------------------ Welcome Email -----------------------------------//
					$arrayTags = array("[consumer_name]","[url]");
					$county_id = $this->Session->read('county_data.id');
					$full_url = FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrl($county_id).'/'.$this->common->getCountyUrl($county_id);
					$url = '<a href="'.$full_url.'" target="_blank">'.$full_url.'</a>';
					$arrayReplace = array($alldata[0],$url);
					//get Mail format
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.new_consumer_subject','Setting.new_consumer_body','Setting.newsletter_from_email')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_subject']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_body']);
					//ADMINMAIL id					
					
					$this->body = '';					
					$this->body = $this->emailhtml->email_header($this->Session->read('county_data.id'));
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer($this->Session->read('county_data.id'));				
				
					$this->Email->to 		= $alldata[3];
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
					if($this->Email->send($this->body)) {echo 'yes';
					
				///////////////////////////sent mail insert to sent box ///////////////////
					$this->common->sentMailLog($this->common->getSalesEmail(),$alldata[3],strip_tags($subject),$this->body,"new_consumer_registration");
				/////////////////////////////////////////////////////////////////////////
									
					} else {echo 'no';}
		//------------------------------------------ end ---------------------------------------//
	}
// Function to save Customer registration data on first choice
	function saveContestCustomerdata($data) {
$this->loadModel('FrontUser');
			$alldata = explode('|',$data);
				$date = date('Y-m-d h:i:s');
				
				$this->loadModel('Consumer');
				$savearr['Consumer']['name'] 			= 	$alldata[1];
				$savearr['Consumer']['email'] 			= 	$alldata[2];
				$savearr['Consumer']['zip'] 			= 	$alldata[3];
				$savearr['Consumer']['county_id']		= 	$alldata[4];
				$savearr['Consumer']['state_id']		= 	$this->common->getStateByCountyId($alldata[4]);
				$savearr['Consumer']['user_type']		= 	'customer';
				$savearr['Consumer']['status']			= 	'yes';
				$savearr['Consumer']['type']			= 	'Front End';
				$savearr['Consumer']['realpassword']	=	$alldata[5];
				$savearr['Consumer']['password']		= 	$this->Auth->password($alldata[5]);
				$savearr['Consumer']['terms_condition']	= 1;
				$savearr['Consumer']['unique_id']=$this->common->randomPassword(10);					
				$this->Consumer->save($savearr);	
				$frontCust = $this->Consumer->getLastInsertId();
				
				$this->loadModel('NewsletterUser');		
				$arr['NewsletterUser']['name'] = $alldata[1];
				$arr['NewsletterUser']['email'] = $alldata[2];
				$arr['NewsletterUser']['zipcode'] = $alldata[3];
				$arr['NewsletterUser']['user_id'] = $frontCust;
				$arr['NewsletterUser']['category_id'] = $alldata[0];
				$arr['NewsletterUser']['all_cats'] = $alldata[6];
				$arr['NewsletterUser']['county_id'] = $alldata[4];				
				$this->NewsletterUser->save($arr);
				
				
				$dbuser_info = $this->Consumer->find('first',array('conditions'=>array('Consumer.id'=>$frontCust)));
				$this->Session->write('Auth.FrontConsumer',$dbuser_info['Consumer']);
				
				
				
				$this->loadModel('Setting');
				$setvale = $this->Setting->find('first',array('fields'=>array('refer_friend_bucks')));
				$bucksprice = $setvale['Setting']['refer_friend_bucks'];
				$this->loadModel('ReferredFriend');
				$checkRefer = $this->ReferredFriend->find('first',array('conditions'=>array('ReferredFriend.email'=>$alldata[2],'ReferredFriend.status'=>'no')));
				if(is_array($checkRefer)) {					
					$savearr['ReferredFriend']['id'] = $checkRefer['ReferredFriend']['id'];
					$savearr['ReferredFriend']['status'] = 'yes';
					$savearr['ReferredFriend']['bucks'] = $bucksprice;
					$savearr['ReferredFriend']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
					$savearr['ReferredFriend']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
					$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;		
					$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);			
					$this->ReferredFriend->save($savearr);
					$this->FrontUser->save($savearr);
					
					$this->loadModel('Buck');
					$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$alldata[4],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
					if(is_array($checkBuck) && count($checkBuck)) {
						$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
						$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
					} else {
						$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
						$saveBuck['Buck']['county_id'] = $alldata[4];
						$saveBuck['Buck']['bucks'] = $bucksprice;
						$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					}
					$this->Buck->save($saveBuck);
			}
			
					//------------------------------------------ Welcome Email -----------------------------------//
					$arrayTags = array("[consumer_name]","[url]");
					$full_url = FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrl($alldata[4]).'/'.$this->common->getCountyUrl($alldata[4]);
					$url = '<a href="'.$full_url.'" target="_blank">'.$full_url.'</a>';
					
					$arrayReplace = array($alldata[1],$url);
					//get Mail format 
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.new_consumer_subject','Setting.new_consumer_body','Setting.newsletter_from_email')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_subject']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_body']);					
					//ADMINMAIL id					
					
					$this->body = '';					
					$this->body = $this->emailhtml->email_header($alldata[4]);
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer($alldata[4]);				
				
					$this->Email->to 		= $alldata[2];
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$alldata[2],strip_tags($subject),$this->body,"new_consumer_registration");
					/////////////////////////////////////////////////////////////////////////
					
		//------------------------------------------ end ---------------------------------------//	
			$this->layout=false;
			$token = 0;		
			$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.id'=>$frontCust)));
			if(!empty($dbuser_info)) {
				$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);	
				//Today Contest 
				$contest_timstmp = mktime(0,0,0,date('m'),date('d'),date('Y'));
				$this->loadModel('Contest');
				$today_contest = $this->Contest->find('first',array('conditions'=>array('Contest.county_id'=>$alldata[4],'Contest.s_date <='.$contest_timstmp,'Contest.e_date >='.$contest_timstmp,'Contest.status'=>'yes')));
				$this->set('today_contest',$today_contest);
				
				$this->loadModel('ContestUser');
			$winner_list = $this->ContestUser->find('all',array('fields'=>array('FrontUser.name','ContestUser.created','Contest.prize','Contest.e_date'),'conditions'=>array('Contest.county_id'=>$alldata[4],'ContestUser.winner'=>1),'order'=>array('ContestUser.id'=>'DESC'),'limit'=>4));
			$this->set('winner_list',$winner_list);
			
						
				$token = 1;
			}
			else{
				$token = 0;
			}
			$this->set('token',$token);
	}
// Function to save Customer registration data on first choice
	function saveDealCustomerdata($data,$deal) {
$this->loadModel('FrontUser');
			$alldata = explode('|',$data);
				$date = date('Y-m-d h:i:s');
				
				
				$this->loadModel('Consumer');
				$savearr['Consumer']['name'] 			= 	$alldata[1];
				$savearr['Consumer']['email'] 			= 	$alldata[2];
				$savearr['Consumer']['zip'] 			= 	$alldata[3];
				$savearr['Consumer']['county_id']		= 	$alldata[4];
				$savearr['Consumer']['state_id']		= 	$this->common->getStateByCountyId($alldata[4]);
				$savearr['Consumer']['user_type']		= 	'customer';
				$savearr['Consumer']['status']			= 	'yes';
				$savearr['Consumer']['type']			= 	'Front End';
				$savearr['Consumer']['realpassword']	=	$alldata[5];
				$savearr['Consumer']['password']		= 	$this->Auth->password($alldata[5]);
				$savearr['Consumer']['terms_condition']	= 1;
				$savearr['Consumer']['unique_id']=$this->common->randomPassword(10);					
				$this->Consumer->save($savearr);	
				$frontCust = $this->Consumer->getLastInsertId();
				
				$this->loadModel('NewsletterUser');		
				$arr['NewsletterUser']['name'] = $alldata[1];
				$arr['NewsletterUser']['email'] = $alldata[2];
				$arr['NewsletterUser']['zipcode'] = $alldata[3];
				$arr['NewsletterUser']['user_id'] = $frontCust;
				$arr['NewsletterUser']['category_id'] = $alldata[0];
				$arr['NewsletterUser']['all_cats'] = $alldata[6];
				$arr['NewsletterUser']['county_id'] = $alldata[4];				
				$this->NewsletterUser->save($arr);
				
				
				$dbuser_info = $this->Consumer->find('first',array('conditions'=>array('Consumer.id'=>$frontCust)));
				$this->Session->write('Auth.FrontConsumer',$dbuser_info['Consumer']);
				
				
				$this->loadModel('Setting');
				$setvale = $this->Setting->find('first',array('fields'=>array('refer_friend_bucks')));
				$bucksprice = $setvale['Setting']['refer_friend_bucks'];
				$this->loadModel('ReferredFriend');
				$checkRefer = $this->ReferredFriend->find('first',array('conditions'=>array('ReferredFriend.email'=>$alldata[2],'ReferredFriend.status'=>'no')));
				if(is_array($checkRefer)) {					
					$savearr['ReferredFriend']['id'] = $checkRefer['ReferredFriend']['id'];
					$savearr['ReferredFriend']['status'] = 'yes';
					$savearr['ReferredFriend']['bucks'] = $bucksprice;
					$savearr['ReferredFriend']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
					$savearr['ReferredFriend']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
					$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;		
					$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);			
					$this->ReferredFriend->save($savearr);
					$this->FrontUser->save($savearr);
					
					$this->loadModel('Buck');
					$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$alldata[4],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
					if(is_array($checkBuck) && count($checkBuck)) {
						$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
						$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
					} else {
						$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
						$saveBuck['Buck']['county_id'] = $alldata[4];
						$saveBuck['Buck']['bucks'] = $bucksprice;
						$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					}
					$this->Buck->save($saveBuck);
			}
			
					//------------------------------------------ Welcome Email -----------------------------------//
					$arrayTags = array("[consumer_name]","[url]");
					$full_url = FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrl($alldata[4]).'/'.$this->common->getCountyUrl($alldata[4]);
					$url = '<a href="'.$full_url.'" target="_blank">'.$full_url.'</a>';
					$arrayReplace = array($alldata[1],$url);
					//get Mail format 
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.new_consumer_subject','Setting.new_consumer_body','Setting.newsletter_from_email')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_subject']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_body']);					
					//ADMINMAIL id					
					
					$this->body = '';					
					$this->body = $this->emailhtml->email_header($alldata[4]);
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer($alldata[4]);				
				
					$this->Email->to 		= $alldata[2];
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$alldata[2],strip_tags($subject),$this->body,"new_consumer_registration");
					/////////////////////////////////////////////////////////////////////////
					
		//------------------------------------------ end ---------------------------------------//
		$this->layout=false;
		$token = 0;
		$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.id'=>$frontCust)));
		if(!empty($dbuser_info)) {	
			$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);	
			//Today Deal 
			$this->loadModel('DailyDeal');			
			$daily_deal = $this->DailyDeal->find('first',array('conditions'=>array("DailyDeal.id=$deal")));
			$this->set('daily_deal',$daily_deal);			
			$token = 1;
		}
		else{
			$token = 0;
		}
		$this->set('token',$token);		
	}		
// Function to save Customer registration data on first choice
	function saveDiskCustomerdata($data) {
			$this->loadModel('FrontUser');
			$alldata = explode('|',$data);
				$date = date('Y-m-d h:i:s');
							
				$this->loadModel('Consumer');
				$savearr['Consumer']['name'] 			= 	$alldata[1];
				$savearr['Consumer']['email'] 			= 	$alldata[2];
				$savearr['Consumer']['zip'] 			= 	$alldata[3];
				$savearr['Consumer']['county_id']		= 	$alldata[4];
				$savearr['Consumer']['state_id']		= 	$this->common->getStateByCountyId($alldata[4]);
				$savearr['Consumer']['user_type']		= 	'customer';
				$savearr['Consumer']['status']			= 	'yes';
				$savearr['Consumer']['type']			= 	'Front End';
				$savearr['Consumer']['realpassword']	=	$alldata[5];
				$savearr['Consumer']['password']		= 	$this->Auth->password($alldata[5]);
				$savearr['Consumer']['terms_condition']	= 1;
				$savearr['Consumer']['unique_id']=$this->common->randomPassword(10);					
				$this->Consumer->save($savearr);
				$frontCust = $this->Consumer->getLastInsertId();
				
				$this->loadModel('NewsletterUser');
				$arr['NewsletterUser']['name'] = $alldata[1];
				$arr['NewsletterUser']['email'] = $alldata[2];
				$arr['NewsletterUser']['zipcode'] = $alldata[3];
				$arr['NewsletterUser']['user_id'] = $frontCust;
				$arr['NewsletterUser']['category_id'] = $alldata[0];
				$arr['NewsletterUser']['all_cats'] = $alldata[6];
				$arr['NewsletterUser']['county_id'] = $alldata[4];
				$this->NewsletterUser->save($arr);
				
				$dbuser_info = $this->Consumer->find('first',array('conditions'=>array('Consumer.id'=>$frontCust)));
				$this->Session->write('Auth.FrontConsumer',$dbuser_info['Consumer']);
				
				
				$this->loadModel('Setting');
				$setvale = $this->Setting->find('first',array('fields'=>array('refer_friend_bucks')));
				$bucksprice = $setvale['Setting']['refer_friend_bucks'];
				$this->loadModel('ReferredFriend');
				$checkRefer = $this->ReferredFriend->find('first',array('conditions'=>array('ReferredFriend.email'=>$alldata[2],'ReferredFriend.status'=>'no')));
				if(is_array($checkRefer)) {					
					$savearr['ReferredFriend']['id'] = $checkRefer['ReferredFriend']['id'];
					$savearr['ReferredFriend']['status'] = 'yes';
					$savearr['ReferredFriend']['bucks'] = $bucksprice;
					$savearr['ReferredFriend']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
					$savearr['ReferredFriend']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
					$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;		
					$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);			
					$this->ReferredFriend->save($savearr);
					$this->FrontUser->save($savearr);					
					$this->loadModel('Buck');
					$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$alldata[4],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
					if(is_array($checkBuck) && count($checkBuck)) {
						$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
						$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
					} else {
						$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
						$saveBuck['Buck']['county_id'] = $alldata[4];
						$saveBuck['Buck']['bucks'] = $bucksprice;
						$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					}
					$this->Buck->save($saveBuck);
			}
			
					//------------------------------------------ Welcome Email -----------------------------------//
					$arrayTags = array("[consumer_name]","[url]");
					$full_url = FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrl($alldata[4]).'/'.$this->common->getCountyUrl($alldata[4]);
					$url = '<a href="'.$full_url.'" target="_blank">'.$full_url.'</a>';
					$arrayReplace = array($alldata[1],$url);
					//get Mail format 
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.new_consumer_subject','Setting.new_consumer_body','Setting.newsletter_from_email')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_subject']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_body']);					
					//ADMINMAIL id					
					
					$this->body = '';					
					$this->body = $this->emailhtml->email_header($alldata[4]);
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer($alldata[4]);				
				
					$this->Email->to 		= $alldata[2];
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$alldata[2],strip_tags($subject),$this->body,"new_consumer_registration");
					/////////////////////////////////////////////////////////////////////////
					
					echo $savearr['Consumer']['unique_id'];					
		//------------------------------------------ end ---------------------------------------//
		$this->autoRender=false;
		$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.id'=>$frontCust)));
		if(!empty($dbuser_info)) {	
			$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);			
		}	
	}		
// Function to save Customer registration data on first choice	
	function saveReferdata($data) {		
	$this->autoRender = false;
			$alldata = explode('|',$data);
				$this->loadModel('FrontUser');
				$arr['FrontUser']['name'] = $alldata[1];
				$arr['FrontUser']['zip'] = $alldata[3];
				$arr['FrontUser']['password']  = $this->Auth->password($alldata[5]);
				$arr['FrontUser']['email']  = $alldata[2];
				$county_id = $this->common->getReferCounty($alldata[2]);
				$arr['FrontUser']['county_id']  = $county_id;			
				$arr['FrontUser']['user_type']  = 'customer';
				$arr['FrontUser']['terms_condition'] = 1;
				$arr['FrontUser']['receive_email'] 	  = 1;
				$arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
				$this->FrontUser->save($arr);
				$frontCust = $this->FrontUser->getLastInsertId();	
							
				$this->loadModel('NewsletterUser');
				$arrs['NewsletterUser']['name'] = $alldata[1];
				$arrs['NewsletterUser']['email']  = $alldata[2];
				$arr['NewsletterUser']['user_id'] = $frontCust;
				$arrs['NewsletterUser']['status']  = 'yes';
				$arrs['NewsletterUser']['zipcode'] = $alldata[3];
				$arrs['NewsletterUser']['category_id'] = $alldata[0];
				$arrs['NewsletterUser']['county_id']  = $county_id;
				$this->NewsletterUser->save($arrs);
				
				$this->loadModel('Setting');
				$setvale = $this->Setting->find('first',array('fields'=>array('refer_friend_bucks')));
				$bucksprice = $setvale['Setting']['refer_friend_bucks'];
				$this->loadModel('ReferredFriend');
				$checkRefer = $this->ReferredFriend->find('first',array('conditions'=>array('ReferredFriend.email'=>$alldata[2],'ReferredFriend.status'=>'no')));
				if(is_array($checkRefer)) {
					$savearr['ReferredFriend']['id'] = $checkRefer['ReferredFriend']['id'];
					$savearr['ReferredFriend']['status'] = 'yes';
					$savearr['ReferredFriend']['bucks'] = $bucksprice;
					$savearr['ReferredFriend']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
					$savearr['ReferredFriend']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
					$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;
					$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
					$this->ReferredFriend->save($savearr);
					$this->FrontUser->save($savearr);
					
					$this->loadModel('Buck');
					$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$county_id,'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
					if(is_array($checkBuck) && count($checkBuck)) {
						$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
						$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
					} else {
						$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
						$saveBuck['Buck']['county_id'] = $this->Session->read('county_data.id');
						$saveBuck['Buck']['bucks'] = $bucksprice;
						$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					}
					$this->Buck->save($saveBuck);
				}
				
				$bodyText="Hi $alldata[1],<br>You are Successfully subscribed to receive e-mails for Zuni deals and promotions.<br><br>Thanks<br>Zuni Team";			
				$this->Email->to 		= $alldata[2];				
				$this->Email->subject 	= 'Zuni Newsletter Subscription for Promotions and Deals';				
				$this->Email->replyTo 	= $this->common->getReturnEmail();
				$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
				$this->Email->sendAs 	= 'html';				
				//Set the body of the mail as we send it.		
				//seperate line in the message body.				
				
				
				$this->body = '';					
				$this->body = $this->emailhtml->email_header($this->Session->read('county_data.id'));
				$this->body .=$bodyText;
				$this->body .= $this->emailhtml->email_footer($this->Session->read('county_data.id'));
					
							
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
				$this->common->sentMailLog($this->common->getSalesEmail(),$alldata[2],"Zuni Newsletter Subscription for Promotions and Deals",$this->body,"newsletter_subscription");
			/////////////////////////////////////////////////////////////////////////
						
		}			
//-------------------------------- Function to save advertiser registration data on second choice ---------------------------------------------//
function savealldata($string1='',$show_at_home=0,$automatic='',$Countyid='',$card_address='',$card_city='',$card_state='',$card_zip='') {
			$string1 = str_replace('qqqqq',':',$string1);
			$automatic = str_replace('qqqqq',':',$automatic);
			
			if($show_at_home) {
				$home_price = 50;
			} else {
				$home_price = 0;
			}
			$this->autoRender = false;				
			$alldata = explode('|',$string1);
			/*pr($alldata);
			exit;*/
			$this->loadModel('Package');			
            $p_price = $this->Package->find('first',array('fields'=>array('Package.setup_price','Package.monthly_price'),'conditions'=>array('Package.id'=>$alldata[0])));
			$total_price = ($p_price['Package']['setup_price']+$p_price['Package']['monthly_price']);
            //save user inputted city
			
            $this->loadModel('City');
			
			$cityResult=$this->City->find('first',array('fields'=>array('City.id'),'conditions'=>"City.cityname LIKE '".$alldata[5]."' AND City.county_id=".$alldata[4]));
			if(!empty($cityResult))
			{
				$alldata[5] = $cityResult['City']['id'];
			}
			else
			{			
					$cityname	=ucwords(strtolower($alldata[5]));
					$page_url	=$this->common->makeAlias(trim($alldata[5]));
					$state_id	=$alldata[6];
					$county_id	=$alldata[4];
					$this->City->query("INSERT into cities (cityname, page_url, state_id, county_id) values('".$cityname."', '".$page_url."', $state_id, $county_id)");
					$city_id = $this->City->query("SELECT id FROM cities WHERE cityname ='".$cityname."' AND county_id=$county_id");
					$alldata[5] = $city_id[0]['cities']['id'];				
							
			}            
			//////////////////// Payment Gateway //////////////////////////			
			ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/Users/keithpalmerjr/Projects/QuickBooks/');
			//error_reporting(E_ALL | E_STRICT);
			//ini_set('display_errors', true);
			require_once (APP.'webroot/quickbooks/QuickBooks.php');
			$dsn = null;
			$path_to_private_key_and_certificate = APP.'webroot/quickbooks/docs/intuit.pem';
			$application_login = INTUIT_APP_LOGIN;
			$connection_ticket = INTUIT_TICKET;
			//$connection_ticket = 'TGT-214-ZfVkTPfjiZZYFg83gYA6Hw';
			$MS = new QuickBooks_MerchantService(
			$dsn,
			$path_to_private_key_and_certificate,
			$application_login,
			$connection_ticket);
			//$MS->useTestEnvironment(true);
			$MS->useLiveEnvironment(true);
			$name = $alldata[14];
			//$number = '4427322513320494';
			$exdate = explode('-',$alldata[16]);
			$number = $alldata[13];
			$expyear =	$exdate[1];
			$expmonth = $exdate[0];
			$address = $card_address.', '.$card_city.', '.$card_state;
			$postalcode = $card_zip;
			$cvv = $alldata[15];
			$amount = number_format($total_price+$home_price,1);
			//$amount = 0.1;
			$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
			if ($Transaction = $MS->charge($Card, $amount))
			{
				$trans_result = $Transaction->toArray();
				$CreditCardTransID = $trans_result['CreditCardTransID'];
				$ClientTransID = $trans_result['ClientTransID'];
				$TStamp = $trans_result['TxnAuthorizationStamp'];
				/*$trans_result = '1';
				$CreditCardTransID = '1';
				$ClientTransID = '1';
				$TStamp = '1';*/
				///////////////////////////////////// Saving data //////////////////////////////////////
			$date = date('Y-m-d h:i:s');
			$this->loadModel('AdvertiserOrder');
			$this->AdvertiserOrder->save(array('package_id'=>$alldata[0],'payment_status'=>'approved','order_status'=>'pending'));
			$order_id = $this->AdvertiserOrder->getLastInsertId();
			$card_expiry = explode('-',$alldata[16]);
			
			$subcats1 = $alldata[1];
			$subcats2 = array_filter(explode(',',$subcats1));
			$subcats3 = implode(',',$subcats2);
			
			/*$this->AdvertiserProfile->query("INSERT INTO advertiser_profiles (name,address,city,county,state,email,phoneno,zip,company_name,publish,category,website,credit_name,credit_number,security_no,card_type,card_exp_month,card_exp_year,order_id,show_at_home,created,modified,transaction_id,transaction_client_id,transaction_date,address2) VALUES ('".$alldata[9]."' ,'".$alldata[3]."' ,'".$alldata[5]."' ,'".$alldata[4]."' ,'".$alldata[6]."' ,'".$alldata[10]."' ,'".$alldata[8]."' ,'".$alldata[7]."' ,'".$alldata[2]."' ,'yes' ,'".$alldata[1]."' ,'".$alldata[11]."' ,'".$alldata[14]."' ,'".$alldata[13]."' ,'".$alldata[15]."' ,'".$alldata[12]."' ,'".$card_expiry[0]."' ,'".$card_expiry[1]."' ,'".$order_id."' ,'".$show_at_home."' ,'".$date."' ,'".$date."' ,'".$CreditCardTransID."' ,'".$ClientTransID."' ,'".$TStamp."' ,'".$alldata[21]."')");*/
			
			$this->AdvertiserProfile->query("INSERT INTO advertiser_profiles (name,address,city,county,state,email,phoneno,zip,company_name,publish,category,website,order_id,show_at_home,created,modified,transaction_id,transaction_client_id,transaction_date,address2) VALUES ('".$alldata[9]."' ,'".$alldata[3]."' ,'".$alldata[5]."' ,'".$alldata[4]."' ,'".$alldata[6]."' ,'".$alldata[10]."' ,'".$alldata[8]."' ,'".$alldata[7]."' ,'".$alldata[2]."' ,'yes' ,'".$alldata[1]."' ,'".$alldata[11]."' ,'".$order_id."' ,'".$show_at_home."' ,'".$date."' ,'".$date."' ,'".$CreditCardTransID."' ,'".$ClientTransID."' ,'".$TStamp."' ,'".$alldata[21]."')");
			
		$ad_id = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.id'),'conditions'=>array('AdvertiserProfile.order_id'=>$order_id)));			
			$advertiser_id = $ad_id['AdvertiserProfile']['id'];
			
			//----------save the instance of order, when new order is placed (Start)------//
			App::import('model', 'OrderInstance');
			$this->OrderInstance = new OrderInstance;
			$saveInstanceArray = array();
			$saveInstanceArray['OrderInstance']['advertiser_order_id']   =  $order_id;
			$saveInstanceArray['OrderInstance']['advertiser_profile_id']  =  $advertiser_id;
			$saveInstanceArray['OrderInstance']['package_id']   	=  $alldata[0];
			$saveInstanceArray['OrderInstance']['insert_status']   	=  5;
			$this->OrderInstance->save($saveInstanceArray,false);
			//----------save the instance of order, when new order is placed (End)------//
			
			
			$this->loadModel('SavingOffer');
			$saving = explode('=',$alldata[17]);
			for($q=0;$q<count($saving)-1;$q++) {
				$pieces = explode('$',$saving[$q]);
					if($pieces[2]==1) {
						$offer_start = strtotime(date('d-m-Y'));
						$offer_expire = strtotime($pieces[3]);
					} else {
						$offer_start = '';
						$offer_expire = '';
					}		
				$this->SavingOffer->query("INSERT INTO saving_offers (title,advertiser_profile_id,advertiser_county_id,status,created,modified,offer_start_date,offer_expiry_date,no_valid_other_offer,no_transferable) VALUES ('".$pieces[0]."' ,'".$advertiser_id."' ,'".$Countyid."' ,'yes' ,'".$date."' ,'".$date."' ,'".$offer_start."' ,'".$offer_expire."' ,'".$alldata[18]."' ,'".$alldata[19]."')");
		}		
				$this->loadModel('FrontUser');
				$password = $this->common->randomPassword(8);
				//$arr['FrontUser']['username'] = $this->common->randomPassword(8);
				$arr['FrontUser']['name'] = $alldata[9];
				$arr['FrontUser']['password'] = $this->Auth->password($password);
				$arr['FrontUser']['realpassword'] = $password;
				$arr['FrontUser']['email'] 	  = $alldata[10];
				$arr['FrontUser']['advertiser_profile_id'] = $advertiser_id;
				$arr['FrontUser']['county_id']  = $this->Session->read('county_data.id');
				$arr['FrontUser']['status'] 	= 'yes';
				$arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
				$this->FrontUser->save($arr,false);
				//$this->sendUsernamePassword($alldata[10],$password);
	//---------------------------------------------------------------------------------------------------------------------------//
				$this->loadModel('Setting');
				$emailArray = $this->Setting->getAdvertiserEmailData();
				$this->Email->sendAs = 'html';
				$this->Email->to = $alldata[10];
				$this->Email->subject = $emailArray[0]['settings']['new_advertiser_subject'];
				$this->Email->replyTo = $this->common->getReturnEmail();
				$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
				$package_name =   $this->common->getAllPackage(2);
				$package_price =   $this->common->getAllPackage(3);
				$bodyData = $this->Setting->replaceUserMarkers($emailArray[0]['settings']['new_advertiser_body'],$alldata[9],$package_name[$alldata[0]],$alldata[2],$package_price[$alldata[0]],$order_id,$password,'');
				
				$this->body = $this->emailhtml->email_header($this->Session->read('county_data.id'));
				$this->body .= $bodyData;
				$this->body .= $this->emailhtml->email_footer($this->Session->read('county_data.id'));
				
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$alldata[10],strip_tags($emailArray[0]['settings']['new_advertiser_subject']),$this->body,"new_advertiser_registration");
					/////////////////////////////////////////////////////////////////////////
					

	//--------------------------------------------------------------------------------------------------------------------------//
				$setvale = $this->Setting->find('first',array('fields'=>array('refer_business_bucks')));
				$bucksprice = $setvale['Setting']['refer_business_bucks'];
				
			$this->loadModel('ReferredBusiness');
			$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.email'=>$alldata[10],'ReferredBusiness.status'=>'no')));
				if(is_array($checkRefer)) {
					$savearr['ReferredBusiness']['id'] = $checkRefer['ReferredBusiness']['id'];
					$savearr['ReferredBusiness']['status'] = 'yes';
					$savearr['ReferredBusiness']['bucks'] = $bucksprice;
					$savearr['ReferredBusiness']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
					$savearr['ReferredBusiness']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
					$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;		
					$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
					$this->ReferredBusiness->save($savearr,false);
					$this->FrontUser->save($savearr,false);
					
					$this->loadModel('Buck');
					$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$this->Session->read('county_data.id'),'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
					if(is_array($checkBuck) && count($checkBuck)) {
						$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
						$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
					} else {
						$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
						$saveBuck['Buck']['county_id'] = $this->Session->read('county_data.id');
						$saveBuck['Buck']['bucks'] = $bucksprice;
						$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					}
					$this->Buck->save($saveBuck,false);
				}
			  App::import('model', 'WorkOrder');
			  $this->WorkOrder = new WorkOrder;
			  $saveWorkArray = array();
			  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $order_id;
			  $saveWorkArray['WorkOrder']['read_status']   				=  0;
			  $saveWorkArray['WorkOrder']['subject']   					=  'New work order Generated';
			  $saveWorkArray['WorkOrder']['message']					=	'A new work order has been placed recently. Order detail is below:';
			  $saveWorkArray['WorkOrder']['type']   					=  'workorder';
			  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
			  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
			  $saveWorkArray['WorkOrder']['from_group']   				=  1;
			  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can go further and add other details about this advertiser in advertiser profiles section like saving offers , vip offers etc.';
			  $saveWorkArray['WorkOrder']['salseperson_id'] 			=  0;
			  date_default_timezone_set('US/Eastern');
		  	  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
			  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
			  $this->WorkOrder->save($saveWorkArray,false);
			////////////////////////////////////////////////////////////////
				echo 'success';
			} else {
				echo $MS->errorMessage();
			}
	}
//-------------------------------------------------------------------------------------------------------------------------------------//	
	function savepassword($ad_id='',$old_pass='',$new_paa='') {
		$this->autoRender = false;
		$this->loadModel('FrontUser');
		$user_details = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.id'=>$ad_id,'FrontUser.password'=>$this->Auth->password($old_pass))));
		if(!empty($user_details)) {
			$this->data['FrontUser']['id'] 				= 	$ad_id;
			$this->data['FrontUser']['password']		=	$this->Auth->password($new_paa);
			$this->data['FrontUser']['realpassword']	=	$new_paa;
			$this->data['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
			$this->FrontUser->save($this->data);
			//echo $this->Auth->password($new_paa);
		} else {
			echo 'fail';
		}
	}
	function sendUsernamePassword($email,$password) {
		$subject 		= 'Welcome to Zuni'; 
		$bodyText 		= 'Thanks for register in Zuni.<br />
							Your login Password is :<br />
							Password : '.$password.'<br />
							<br />Thanks<br />Zuni Admin Team';
							
							
		$this->body = '';					
		$this->body = $this->emailhtml->email_header();
		$this->body .= $bodyText;
		$this->body .= $this->emailhtml->email_footer();
				
									
		//ADMINMAIL id
		$this->Email->to 		= $email;
		$this->Email->subject 	= strip_tags($subject);
		$this->Email->replyTo 	= $this->common->getReturnEmail();
		$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
		$this->Email->sendAs 	= 'html';
		//Set the body of the mail as we send it.			
		//seperate line in the message body.
		
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
			$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"send_username_and_password");
			/////////////////////////////////////////////////////////////////////////

	}
	function sendPasswordtoAdvertiser($email,$password,$adv_name) {

	/*------------Manage Email Template---------------*/
	 	App::import('model', 'Setting');
	    $this->Setting = new Setting;	
		$emailArray = $this->Setting->getAcceptProofEmailData();
		$subject 		= $emailArray[0]['settings']['new_accept_proof_subject'];  
		$bodyText 		= $this->Setting->replaceMarkersAcceptProof($emailArray[0]['settings']['new_accept_proof_body'],$adv_name,$password);						   
	/*-----------------------------------------------*/												
		//ADMINMAIL id
		$this->Email->to 		= $email;
		$this->Email->subject 	= strip_tags($subject);
		$this->Email->replyTo 	= $this->common->getReturnEmail();
		$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
		$this->Email->sendAs 	= 'html';
		//Set the body of the mail as we send it.			
		//seperate line in the message body.
		$this->body = '';					
		$this->body = $this->emailhtml->email_header();
		$this->body .= $bodyText;
		$this->body .= $this->emailhtml->email_footer();
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
			$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"send_password_to_advertiser");
			/////////////////////////////////////////////////////////////////////////

	}	
	function emailvalidate($email='',$county='',$state='') {
		$this->autoRender = false;
		$state_id = $this->common->getStateIdByUrl($state);
		$this->loadModel('County');
		$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$county,'County.state_id'=>$state_id)));
		
		$this->loadModel('FrontUser');
		$totalcount = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.email'=>$email,'FrontUser.county_id'=>$county_id['County']['id'],'FrontUser.user_type'=>'advertiser')));
		if($totalcount>0) {
			echo 'Email address is already in use.';
		}
	}
	function Useremailvalidate($email='',$county='',$state='') {
		$this->autoRender = false;
		$state_id = $this->common->getStateIdByUrl($state);
		$this->loadModel('County');
		$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$county,'County.state_id'=>$state_id)));
		
		$this->loadModel('FrontUser');
		$totalcount = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.email'=>$email,'FrontUser.county_id'=>$county_id['County']['id'],'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")')));
		if($totalcount>0) {
			echo 'Error';
		}
	}
	function Consumeremailvalidate($email='',$id='',$pass='') {
		$this->autoRender = false;
		$this->loadModel('FrontUser');
		$totalcount = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.email'=>$email,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.id<>'.$id)));
		$totalpass = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.realpassword'=>$pass,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.id'=>$id)));
		if($totalcount>0) {
			echo 'Error';
		}
		if($totalpass==0) {
			echo 'pass';
		}
	}
	// for use in mobile version
	function ConsumeremailvalidateMobile($email='',$id='',$pass='') {
		$this->autoRender = false;
		$this->layout = '';
		$this->loadModel('FrontUser');
		$totalcount = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$email,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.id<>'.$id)));
		
		$totalpass = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.id'=>$id,'FrontUser.realpassword'=>$pass,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")')));
		if(empty($totalpass)) {
			echo 'pass';
		}
		if(empty($totalcount)) {
			echo 'Error';
		}
		
	}
	
//	Function to check for existing email for newsletter
	function validEmailNewsletter($email,$county='',$state='')
	{
		$this->autoRender = false;
		$state_id = $this->common->getStateIdByUrl($state);
		$this->loadModel('County');
		$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$county,'County.state_id'=>$state_id)));
		
		$this->loadModel('NewsletterUser');
		$emailExist = $this->NewsletterUser->find('first',array('conditions'=>array('NewsletterUser.email'=>$email,'NewsletterUser.county_id'=>$county_id['County']['id'])));	
		if(is_array($emailExist))
		{
			echo 'emailexist';
		}
	}	
// Function to save advertiser registration data on first choice
	function saveNewslaterdata($data) {

			$this->autoRender = false;
			$alldata = explode('|',$data);
				$date = date('Y-m-d h:i:s');
				
				$this->loadModel('FrontUser');
				$this->loadModel('Consumer');
				$savearr['Consumer']['name'] 			= 	$alldata[1];
				$savearr['Consumer']['email'] 			= 	$alldata[2];
				$savearr['Consumer']['zip'] 			= 	$alldata[3];
				$savearr['Consumer']['county_id']		= 	$alldata[4];
				$savearr['Consumer']['state_id']		= 	$this->common->getStateByCountyId($alldata[4]);
				$savearr['Consumer']['user_type']		= 	'customer';
				$savearr['Consumer']['type']			= 	'Front End';
				$savearr['Consumer']['status']			= 	'yes';
				$savearr['Consumer']['realpassword']	=	$alldata[5];
				$savearr['Consumer']['password']		= 	$this->Auth->password($alldata[5]);
				$savearr['Consumer']['terms_condition']	= 1;
				$savearr['Consumer']['unique_id']=$this->common->randomPassword(10);					
				$this->Consumer->save($savearr);
				$consumer_id = $this->Consumer->getlastinsertid();
				
				$this->loadModel('NewsletterUser');		
				$arr['NewsletterUser']['name'] = $alldata[1];
				$arr['NewsletterUser']['email'] = $alldata[2];
				$arr['NewsletterUser']['zipcode'] = $alldata[3];
				$arr['NewsletterUser']['user_id'] = $consumer_id;
				$arr['NewsletterUser']['category_id'] = $alldata[0];
				$arr['NewsletterUser']['all_cats'] = $alldata[6];
				$arr['NewsletterUser']['county_id'] = $alldata[4];				
				$this->NewsletterUser->save($arr);
				
				
				
				$dbuser_info = $this->Consumer->find('first',array('conditions'=>array('Consumer.id'=>$consumer_id)));
				$this->Session->write('Auth.FrontConsumer',$dbuser_info['Consumer']);
				
				
				$this->loadModel('Setting');
				$setvale = $this->Setting->find('first',array('fields'=>array('refer_friend_bucks')));
				$bucksprice = $setvale['Setting']['refer_friend_bucks'];
				$this->loadModel('ReferredFriend');
				$checkRefer = $this->ReferredFriend->find('first',array('conditions'=>array('ReferredFriend.email'=>$alldata[2],'ReferredFriend.status'=>'no')));
				if(is_array($checkRefer)) {					
					$savearr['ReferredFriend']['id'] = $checkRefer['ReferredFriend']['id'];
					$savearr['ReferredFriend']['status'] = 'yes';
					$savearr['ReferredFriend']['bucks'] = $bucksprice;
					$savearr['ReferredFriend']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
					$savearr['ReferredFriend']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
					$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;		
					$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);			
					$this->ReferredFriend->save($savearr);
					$this->FrontUser->save($savearr);
					
					$this->loadModel('Buck');
					$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$alldata[4],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
					if(is_array($checkBuck) && count($checkBuck)) {
						$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
						$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
					} else {
						$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
						$saveBuck['Buck']['county_id'] = $alldata[4];
						$saveBuck['Buck']['bucks'] = $bucksprice;
						$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					}
					$this->Buck->save($saveBuck);
			}				
				//------------------------------------------ Welcome Email -----------------------------------//
					$arrayTags = array("[consumer_name]","[url]");
					$full_url = FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrl($alldata[4]).'/'.$this->common->getCountyUrl($alldata[4]);
					$url = '<a href="'.$full_url.'" target="_blank">'.$full_url.'</a>';
					$arrayReplace = array($alldata[1],$url);
					//get Mail format 
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.new_consumer_subject','Setting.new_consumer_body','Setting.newsletter_from_email')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_subject']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_body']);					
					//ADMINMAIL id					
					
					$this->body = '';					
					$this->body = $this->emailhtml->email_header($alldata[4]);
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer($alldata[4]);				
					
					$this->Email->to 		= $alldata[2];
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
					$this->Email->delivery = 'smtp';
					$this->Email->send($this->body);
					
					///////////////////////////sent mail insert to sent box ///////////////////
						$this->common->sentMailLog($this->common->getSalesEmail(),$alldata[2],strip_tags($subject),$this->body,"new_consumer_registration");
					/////////////////////////////////////////////////////////////////////////
					
					$this->Session->setFlash('Your profile has been created successfully.');					
					echo 'success';exit;
					
//------------------------------------------ end ---------------------------------------//						
			/*require(WWW_ROOT.'smtp_class.php');	
			$SmtpServer="zuni.com";
			$SmtpPort="25"; //default
			$SmtpUser="noreply@zuni.com";
			$SmtpPass="taal789";
		
			//$from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
			$from_name = $this->common->getFromName();
			$from_address = $this->common->getSalesEmail();
			$return = $this->common->getReturnEmail();
			$from = $this->common->getSalesEmail();
			$to = $alldata[2];	//'keshavhello1@gmail.com';//
			$subject = strip_tags($subject);
			//echo htmlentities($this->body);
			$body = $this->body;
			/*$SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body);
			$SMTPChat = $SMTPMail->SendMail();*/

			/*$headers = "Reply-To: The Sender <$from>\r\n"; 
			$headers .= "Return-Path: The Sender <$from>\r\n"; 
			$headers .= "From: The Sender <$from>\r\n"; 
			$headers .= "Organization: $from_name\r\n";
			  $headers .= "MIME-Version: 1.0\r\n";
			  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			  $headers .= "X-Priority: 3\r\n";
			  $headers .= "X-Mailer: PHP". phpversion() ."\r\n";
			if(mail($to, $body, $subject, $headers)) {
			   echo "The email has been sent!";
			   } else {
			   echo "The email has failed!";
			   }*/
	//------------------------------------------ end ---------------------------------------//			
	}
	function updateStatus($id=''){
		$ad_id = explode('_',$id);
		$arr['AdvertiserProfile']['id'] = $ad_id[1];
		$arr['AdvertiserProfile']['represent_call'] = 'no';
		$this->AdvertiserProfile->save($arr);
	}
	
////// Master analytical report //////////////
	function masterAnalyticReport() {
			$this->set('advertiserList',$this->common->getAdvertiserProfileAll());
			if(isset($this->data)) {
			if($this->data['AdvertiserProfile']['advertiser_profile_id']=='') {
				$this->Session->setFlash('Please select advertiser.');
			} else {
				//pr($this->data);
				$this->set('option',$this->data['AdvertiserProfile']);
				$this->set('title_for_layout', 'Master analytic report of Advertisers');
				$this->AdvertiserProfile->id = $this->data['AdvertiserProfile']['advertiser_profile_id'];
				$data = $this->AdvertiserProfile->read();
				$this->set('data',$data);
				$this->set('categoryList',$this->common->getAllCategory()); //  List categories
				$this->set('subCategoryList',$this->common->getAllSubCategory());
				$this->render('/advertiser_profiles/master_report');
			}
		}
	}
//-------------------------------------------------------------------------------------------------------------------------------//	
	function mastersheet_password() {
		if(isset($this->data)) {
			$user_id = $this->data['AdvertiserProfile']['advertiser_id'];
			$this->loadModel('FrontUser');
			$id = $this->FrontUser->find('first',array('fields'=>array('FrontUser.id'),'conditions'=>array('FrontUser.advertiser_profile_id'=>$user_id)));
			if(!empty($id)) {
				$savearr = '';
				$savearr['FrontUser']['id'] = $id['FrontUser']['id'];
				$savearr['FrontUser']['password'] = $this->Auth->password($this->data['AdvertiserProfile']['password']);
				$savearr['FrontUser']['realpassword'] = $this->data['AdvertiserProfile']['password'];
				$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
				$this->FrontUser->save($savearr);
				$this->Session->setFlash('Password has been saved sccessfully.');
				$this->redirect($this->referer());		
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
					//$this->RegisteredEmail->find('first');
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
			/* Set delivery method */
			$this->Email->delivery = 'smtp';										
			/* Do not pass any args to send() */
			$this->Email->send($this->body);
								
			///////////////////////////sent mail insert to sent box ///////////////////
			$this->common->sentMailLog($this->common->getSalesEmail(),$email,"Registration Link",$this->body,"send_registration_link");
			/////////////////////////////////////////////////////////////////////////

				}
		}	
/************************** function to confirm to delete advertiser profile ******************************/
	function thanksPage($advertiser_id) {
		$this->id = $advertiser_id;
		$this->set('advertiser_id',$advertiser_id);
		$company_name = $this->AdvertiserProfile->field('AdvertiserProfile.company_name');
		$this->set('company_name',$company_name);
	}
/***********************************************************************************************************/
	function changeSheet() {
			$this->set('title_for_layout', 'Change Ordersheet');
			$this->set('StatesList',$this->common->getAllState());
			$this->set('CountyList',$this->common->getAllCounty());
			$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); // List advertisers
			$this->loadModel('Package');
			$spcl_pkg = $this->Package->find('all',array('fields'=>array('Package.id','Package.name','Package.package_detail'),'conditions'=>array('Package.status'=>'yes','Package.type'=>'special'),'order'=>'Package.id'));
			$this->set('spcl_pkg',$spcl_pkg);
			if(isset($this->data)) {
				$error = array();
				if($this->data['AdvertiserProfile']['state']=='') {
					$error[] = 'Please select a State.';
				}
				if($this->data['AdvertiserProfile']['county']=='') {
					$error[] = 'Please select a County.';
				}
				if($this->data['AdvertiserProfile']['city']=='') {
					$error[] = 'Please select a City.';
				}
				if($this->data['AdvertiserProfile']['advertiser_profile_id']=='') {
					$error[] = 'Please select an Advertiser.';
				}
				$check_service = 0;
				$spcl_pkg = '';
				$spcl_pkg_name = '';
				if($this->data['AdvertiserProfile']['all_pkg']!='') {
					$pkg_name = explode(',',$this->data['AdvertiserProfile']['all_pkg']);
					foreach($pkg_name as $pkg_name) {
						if($this->data['AdvertiserProfile'][$pkg_name]) {
							$check_service = 1;
							$spcl_pkg = 1;
							$spcl_pkg_name = $pkg_name;
							break;
						}
					}
				}
				if($this->data['AdvertiserProfile']['edit']==1 || $this->data['AdvertiserProfile']['saving']==1 || $this->data['AdvertiserProfile']['discount']==1 || $this->data['AdvertiserProfile']['deal']==1 || $this->data['AdvertiserProfile']['vip']==1 || $this->data['AdvertiserProfile']['package']==1) {
					$check_service = 1;
				}
				if(!$check_service) {
					$error[] = 'Please select a Service.';
				}
				$service = '';
				if($this->data['AdvertiserProfile']['edit']) {
					$service = 'edit';
				} else if($this->data['AdvertiserProfile']['saving']) {
					$service = 'saving';
				} else if($this->data['AdvertiserProfile']['discount']) {
					$service = 'discount';
				} else if($this->data['AdvertiserProfile']['deal']) {
					$service = 'deal';
				} else if($this->data['AdvertiserProfile']['vip']) {
					$service = 'vip';
				} else if($this->data['AdvertiserProfile']['package']) {
					$service = 'package';
				}
				$ad_id = $this->data['AdvertiserProfile']['advertiser_profile_id'];
				$final_pkg_id = 0;
				if($spcl_pkg_name!='') {
					$spcl_pkg_name_array = explode('_',$spcl_pkg_name);
					$final_pkg_id = $spcl_pkg_name_array[1];
				}
				if(!empty($error)) {
					$this->Session->setFlash(implode('<br />',$error));
				}else if($spcl_pkg_name!='' && (!$this->common->onlyHomePerm($ad_id))) {
					$this->Session->setFlash('This is not available in the advertisers current package. You must upgrade package to add this feature.');
				}else if($service=='saving' && (!$this->common->homeSavingPerm($ad_id) && !$this->common->categorySavingPerm($ad_id))){
					$this->Session->setFlash('This is not available in the advertisers current package. You must upgrade package to add this feature.');
				} else if($service=='vip' && !$this->common->vipPerm($ad_id)){
					$this->Session->setFlash('This is not available in the advertisers current package. You must upgrade package to add this feature.');
				} else if($service=='discount' && (!$this->common->homeDiscountPerm($ad_id) && !$this->common->categoryDiscountPerm($ad_id))){
					$this->Session->setFlash('This is not available in the advertisers current package. You must upgrade package to add this feature.');
				} else if($service=='deal' && (!$this->common->homeDealPerm($ad_id) && !$this->common->categoryDealPerm($ad_id))){
					$this->Session->setFlash('This is not available in the advertisers current package. You must upgrade package to add this feature.');
				} else if($service=='discount') {
					$this->redirect(array('controller' => 'daily_discounts','action' =>'addDailyDiscount',$this->data['AdvertiserProfile']['advertiser_profile_id']));
				} else if($service=='deal') {
					$this->redirect(array('controller' => 'daily_deals','action' =>'addDailyDeal',$this->data['AdvertiserProfile']['advertiser_profile_id']));
				} else if($service=='package') {
					$this->redirect(array('controller' => 'advertiser_profiles','action' =>'package',$this->data['AdvertiserProfile']['advertiser_profile_id'],$final_pkg_id));} else if($final_pkg_id) {
					$this->redirect(array('controller' => 'advertiser_profiles','action' =>'splpackage',$this->data['AdvertiserProfile']['advertiser_profile_id'],$final_pkg_id));} else {
					$this->redirect(array('controller' => 'advertiser_profiles','action' =>$service,$this->data['AdvertiserProfile']['advertiser_profile_id']));
				}
			}
		}
/************************** function to confirm to delete advertiser profile ******************************/
	function edit($id) {
			$this->set('title_for_layout', 'Basic details of Advertiser');
			$this->AdvertiserProfile->id = $id;
			$data = $this->AdvertiserProfile->read();
			$this->set('data',$data);
			$this->set('categoryList',$this->common->getAllCategory()); //  List categories
			$this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
	}
/************************** function to confirm to delete advertiser profile ******************************/
	function saving($id) {
			$this->set('title_for_layout', 'Saving Offers of Advertiser');
			$this->AdvertiserProfile->id = $id;
			$data = $this->AdvertiserProfile->read();
			$this->set('data',$data);
	}
/************************** function to confirm to delete advertiser profile ******************************/
	function vip($id) {
			$this->set('title_for_layout', 'VIP Offers of Advertiser');
			$this->AdvertiserProfile->id = $id;
			$data = $this->AdvertiserProfile->read();
			$this->set('data',$data);
			$this->set('categoryList',$this->common->getAllCategory()); //  List categories
	}
/************************** function to confirm to delete advertiser profile ******************************/
	function package($id,$spcl_pkg=0) {
		$this->set('title_for_layout', 'Upgrade Package');
		if($id) {
				
				$this->AdvertiserProfile->id = $id;
				$data = $this->AdvertiserProfile->read();
				$this->set('data',$data);
				$this->loadModel('AdvertiserOrder');
				$this->set('PackageId', $this->AdvertiserOrder->packageDetail($id));
				$pkg_arr = $this->common->getOnlyPackage();
				$this->set('Packages', $pkg_arr);
				$this->set('spcl_pkg', $spcl_pkg);
				if(isset($this->data)) {						
								$error = array();
								if($this->data['AdvertiserProfile']['package_id']=='') {
									$error[] = 'Please select a Package.';
								}
								if($this->data['AdvertiserProfile']['contract_date']=='') {
									$error[] = 'Please select a Contract Date.';
								}
								/*if($this->data['AdvertiserProfile']['contract_expiry_date']=='') {
									$error[] = 'Please select a Contract End Date.';
								}*/
								if(!empty($error)) {
									$this->Session->setFlash(implode('<br />',$error));
								} else {
								
												$savearr = '';
												$savearr['AdvertiserOrder']['id'] = $this->data['AdvertiserProfile']['order_id'];
												$savearr['AdvertiserOrder']['package_id'] = $this->data['AdvertiserProfile']['package_id'];
												$this->AdvertiserOrder->save($savearr);
												date_default_timezone_set('US/Eastern');
												$savedatearr = '';
												$savedatearr['AdvertiserProfile']['id'] = $id;
												$savedatearr['AdvertiserProfile']['contract_date'] = strtotime($this->data['AdvertiserProfile']['contract_date']);
												//$savedatearr['AdvertiserProfile']['contract_expiry_date'] = strtotime($this->data['AdvertiserProfile']['contract_expiry_date']);
												$savedatearr['AdvertiserProfile']['old_contract_date'] = $this->data['AdvertiserProfile']['old_contract_date'];
												$savedatearr['AdvertiserProfile']['modified'] = date('Y-m-d H:i:s');
												$this->AdvertiserProfile->save($savedatearr);
												
												//----------save the instance of order, when order(package upgrade) is updated (Start)------//
												if(isset($this->data['AdvertiserProfile']['package_id']) && $this->data['AdvertiserProfile']['package_id']!='')
												{
													App::import('model', 'OrderInstance');
													$this->OrderInstance = new OrderInstance;
													$saveInstanceArray = array();
													$saveInstanceArray['OrderInstance']['advertiser_order_id']   =  $this->data['AdvertiserProfile']['order_id'];
													$saveInstanceArray['OrderInstance']['advertiser_profile_id'] = $id;
													$saveInstanceArray['OrderInstance']['package_id']   	=  $this->data['AdvertiserProfile']['package_id'];
													$saveInstanceArray['OrderInstance']['insert_status']   		=  2;
													$this->OrderInstance->save($saveInstanceArray);
												}
												//----------save the instance of order, when order(package upgrade) is updated (End)------//
												
												
												App::import('model', 'WorkOrder');
												$this->WorkOrder = new WorkOrder;
												$saveWorkArray = '';
											  $saveWorkArray['WorkOrder']['id']   						=  '';
											  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $this->data['AdvertiserProfile']['order_id'];
											  $saveWorkArray['WorkOrder']['read_status']   				=  0;
											  $saveWorkArray['WorkOrder']['subject']   					=  'Contract Updated';
									  		  $saveWorkArray['WorkOrder']['message']   					=  'A Contract has been updated recently. details are below:';
											  $saveWorkArray['WorkOrder']['type']   					=  'Contract Updated';
											  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
											  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
											  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
											  $saveWorkArray['WorkOrder']['bottom_line']   				=  '';
											  $saveWorkArray['WorkOrder']['salseperson_id'] 			=  $this->data['AdvertiserProfile']['salesperson'];
											  date_default_timezone_set('US/Eastern');
											  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
											  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
											  $this->WorkOrder->save($saveWorkArray);
											  
											  //$oldPrice = $this->common->getPackageTotal($this->data['AdvertiserProfile']['old_package']);
											  //$newPrice = $this->common->getPackageTotal($this->data['AdvertiserProfile']['package_id']);
											  
//-------------------------------------------------Order Email------------------------------------------//
												$this->loadModel('Setting');
												$arrayTags = array("[advertiser_name]","[package_name]","[package_price]");
												/*pr($this->data);
												exit;*/
												$arrayReplace = array($this->common->getCompanyNameById($id),$pkg_arr[$this->data['AdvertiserProfile']['package_id']],$this->common->getMainPackagePrice($this->data['AdvertiserProfile']['package_id']));
												$emailArray = $this->Setting->find('first',array('fields'=>array('Setting.package_subject','Setting.package_content'),'conditions'=>array('Setting.id'=>1)));
												$subject 	= str_replace($arrayTags,$arrayReplace,$emailArray['Setting']['package_subject']);
												$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailArray['Setting']['package_content']);
			
			
												$this->Email->sendAs = 'html';
												$this->Email->to = $this->common->getCompanyEmail($id);
												$this->Email->subject = $subject;
												
												
												$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
												//$this->body = $bodyData;
												
												$this->body = '';
												//$this->body = $this->emailhtml->email_header();
												$this->body .=$bodyText;
												//$this->body .= $this->emailhtml->email_footer();
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$this->common->getCompanyEmail($id),strip_tags($subject),$this->body,"package_upgrade");
					/////////////////////////////////////////////////////////////////////////
								
							//---------------------------------------------------------------------------------------------------//															
														
														$this->Session->setFlash('Package has been updated successfully.');
														$this->redirect(array('controller' => 'advertiser_profiles','action' =>'changeSheet'));
													}
												}		
										} else {												
												$this->redirect(array('controller' => 'advertiser_profiles','action' =>'changeSheet'));
										}
	}
//----------------------- function to to upgrade special package ------------------------//
	function splpackage($id,$spcl_pkg=0) {
		if($id) {
				$this->set('title_for_layout', 'Upgrade Package');
				$this->AdvertiserProfile->id = $id;
				$data = $this->AdvertiserProfile->read();
				$this->set('data',$data);
				$this->loadModel('AdvertiserOrder');
				$this->set('PackageId', $this->AdvertiserOrder->packageDetail($id));
				$pkg_arr = $this->common->getspclPackage();
				$this->set('Packages', $pkg_arr);
				$this->set('currPackages', $this->common->getAllPackage(1));
				$this->set('spcl_pkg', $spcl_pkg);
				if(isset($this->data)) {
								$error = array();
								if($this->data['AdvertiserProfile']['package_id']=='') {
									$error[] = 'Please select a Package.';
								}
								if($this->data['AdvertiserProfile']['credit_name']=='') {
									$error[] = 'Please enter Name On Credit Card.';
								}
								if($this->data['AdvertiserProfile']['address']=='') {
									$error[] = 'Please enter Address.';
								}
								if($this->data['AdvertiserProfile']['city']=='') {
									$error[] = 'Please enter City.';
								}
								if($this->data['AdvertiserProfile']['state']=='') {
									$error[] = 'Please enter State.';
								}
								if($this->data['AdvertiserProfile']['zip']=='') {
									$error[] = 'Please enter Zip.';
								}
								if($this->data['AdvertiserProfile']['credit_number']=='') {
									$error[] = 'Please enter Credit Card Number.';
								}
								/*if($this->data['AdvertiserProfile']['cvv']=='') {
									$error[] = 'Please enter CVV .';
								}*/
								if($this->data['AdvertiserProfile']['card_exp_month']=='') {
									$error[] = 'Please select Card Expiry Month.';
								}
								if($this->data['AdvertiserProfile']['card_exp_year']=='') {
									$error[] = 'Please select Card Expiry Year.';
								}
								
								if(!empty($error)) {
									$this->Session->setFlash(implode('<br />',$error));
								} else {
								//----------------------------------------------------------------------------------------
									$this->loadModel('Package');
									$p_price = $this->Package->find('first',array('fields'=>array('Package.setup_price','Package.monthly_price'),'conditions'=>array('Package.id'=>$this->data['AdvertiserProfile']['package_id'])));
									$total_price = ($p_price['Package']['setup_price']+$p_price['Package']['monthly_price']);
									/*if($this->data['AdvertiserProfile']['monthly_fee']!='') {
										$total_price = $total_price+$this->data['AdvertiserProfile']['monthly_fee'];
									}*/
									
									//--------------------- Payment Gateway Start-----------------------------//
													$authNameArr=explode(' ',$this->data['AdvertiserProfile']['credit_name']);
													
													$auth_fname=$authNameArr[0];
													$auth_lname=$authNameArr[1];
														
													$final_exp_date=$this->data['AdvertiserProfile']['card_exp_month'].$this->data['AdvertiserProfile']['card_exp_year'];
													$amount = number_format($total_price,1);
													
													//$amount = 1.0;
													//----------------Auth.net start------------------//
													$post_url = "https://secure.authorize.net/gateway/transact.dll"; // for live account intergartion
													// $post_url = "https://test.authorize.net/gateway/transact.dll"; // for test account intergartion
								
													$post_values = array(
														
														// the API Login ID and Transaction Key must be replaced with valid values
														"x_login"			=> AUTHORIZE_APP_LOGIN,
														"x_tran_key"		=> AUTHORIZE_TRANSACTION_KEY,
													
														"x_version"			=> "3.1",
														"x_delim_data"		=> "TRUE",
														"x_delim_char"		=> "|",
														"x_relay_response"	=> "FALSE",
													
														"x_type"			=> "AUTH_CAPTURE",
														"x_method"			=> "CC",
														"x_card_num"		=> $this->data['AdvertiserProfile']['credit_number'],
														"x_exp_date"		=> $final_exp_date,
													
														"x_amount"			=> $amount,
														"x_description"		=> "",
													
														"x_first_name"		=> $auth_fname,
														"x_last_name"		=> $auth_lname,
														"x_address"			=> "",
														"x_state"			=> "",
														"x_zip"				=> ""
														// Additional fields can be added here as outlined in the AIM integration
														// guide at: http://developer.authorize.net
													);
													
													$post_string = "";
													foreach( $post_values as $key => $value )
														{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
													$post_string = rtrim( $post_string, "& " );
													
													$request = curl_init($post_url); // initiate curl object
													curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
													curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
													curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
													curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
													$post_response = curl_exec($request); // execute curl post and store results in $post_response
													// additional options may be required depending upon your server configuration
													// you can find documentation on curl options at http://www.php.net/curl_setopt
													curl_close ($request); // close curl object
												
													// This line takes the response and breaks it into an array using the specified delimiting character
													$response_array = explode($post_values["x_delim_char"],$post_response);
													
													
													//----------------Auth.net end------------------//			
													
													
													
													
													
									//--------------------- Payment Gateway End-----------------------------//				
													
																								
												
												if(isset($response_array[0]) && $response_array[0]!='' && $response_array[0]=='1')
													{
														$redirection_cntr = 'advertiser_profiles';
														$redirection_act = 'masterSheet';
														
														$saveWorkArray = '';
														
														if($this->data['AdvertiserProfile']['package_id']==$this->common->homeDiscountPkg()) {
															$spcl_pkg_id = $this->data['AdvertiserProfile']['package_id'];
															$redirection_cntr = 'daily_discounts';
															$redirection_act = 'addDailyDiscount';
															
														    $saveWorkArray['WorkOrder']['subject']  =  'Home Page Daily Discount Updated';
														    $saveWorkArray['WorkOrder']['message']  =  'Advertiser paid for the Add Home Page Daily Discount recently. details are below:';
														    $saveWorkArray['WorkOrder']['type']   	=  'addhomediscountupdate';
															
														} else if($this->data['AdvertiserProfile']['package_id']==$this->common->homeDealPkg()) {
															$spcl_pkg_id = $this->data['AdvertiserProfile']['package_id'];
															$redirection_cntr = 'daily_deals';
															$redirection_act = 'addDailyDeal';
															
														    $saveWorkArray['WorkOrder']['subject']   =  'Home Page Daily Deal Updated';
														    $saveWorkArray['WorkOrder']['message']   = 'Advertiser paid for the Add Home Page Daily Deal recently. details are below:';
														    $saveWorkArray['WorkOrder']['type']   	 =  'addhomedealupdate';
															
														} else if($this->data['AdvertiserProfile']['package_id']==$this->common->homeSavingPkg()) {
															$spcl_pkg_id = $this->data['AdvertiserProfile']['package_id'];
															$redirection_cntr = 'saving_offers';
															$redirection_act = 'addNewOffer';

														    $saveWorkArray['WorkOrder']['subject']   =  'Home Page Banner Updated';
														    $saveWorkArray['WorkOrder']['message']   =  'Advertiser paid for the Add Home Page Banner recently. details are below:';
														    $saveWorkArray['WorkOrder']['type']   	 =  'addhomebannerupdate';
																														
														} else {
															$spcl_pkg_id = 0;
														}
														if($spcl_pkg_id!=0) {												
															$spcl_pkg = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.spclpkg'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
															$full_spcl_pkg_id = str_replace(','.$spcl_pkg_id.',',',',$spcl_pkg['AdvertiserProfile']['spclpkg']);
															$full_spcl_pkg_id = $full_spcl_pkg_id.','.$spcl_pkg_id.',';
															$savearr = '';
															$savearr['AdvertiserProfile']['id'] = $id;
															$savearr['AdvertiserProfile']['spclpkg'] = $full_spcl_pkg_id;
															$savearr['AdvertiserProfile']['modified'] = date('Y-m-d H:i:s');
															$this->AdvertiserProfile->save($savearr);
															
								//------------------------inbox notification (Start)-----------//
																				
														  $myAdvertiserInfo=$this->common->getAdvertiserdetailswithOrder($id);
														  App::import('model', 'WorkOrder');
														  $this->WorkOrder = new WorkOrder;
														  $saveWorkArray['WorkOrder']['id']  =  '';
														  $saveWorkArray['WorkOrder']['advertiser_order_id']  =  $myAdvertiserInfo['order_id'];
														  $saveWorkArray['WorkOrder']['read_status']   				=  0;
														  $saveWorkArray['WorkOrder']['sent_to']   	  =  0;
														  $saveWorkArray['WorkOrder']['sent_to_group'] =  1;
														  $saveWorkArray['WorkOrder']['from_group']   =  $this->Session->read('Auth.Admin.user_group_id');
														  $saveWorkArray['WorkOrder']['bottom_line']  =  '';
														  $saveWorkArray['WorkOrder']['salseperson_id'] =  $myAdvertiserInfo['creator'];
														  date_default_timezone_set('US/Eastern');
														  $saveWorkArray['WorkOrder']['created_date']  =  date(DATE_FORMAT.' h:i:s A');
														  $saveWorkArray['WorkOrder']['created']       =  strtotime(date(DATE_FORMAT.' h:i:s A'));
														  $this->WorkOrder->save($saveWorkArray);						
														
								//------------------------inbox notification (End)-----------//		
								//------------------------create order instance (start)-----------//		
														App::import('model', 'OrderInstance');
														$this->OrderInstance = new OrderInstance;
														$saveInstanceArray = array();
														$saveInstanceArray['OrderInstance']['advertiser_order_id']   =  $myAdvertiserInfo['order_id'];
														$saveInstanceArray['OrderInstance']['advertiser_profile_id']  =  $id;
														$saveInstanceArray['OrderInstance']['package_id']   	=  $this->data['AdvertiserProfile']['package_id'];
														$saveInstanceArray['OrderInstance']['insert_status']   	=  4;
														$this->OrderInstance->save($saveInstanceArray);
								//------------------------create order instance (End)-----------//																		
														} else {														
															$savearr = '';
															$savearr['AdvertiserOrder']['id'] = $this->data['AdvertiserProfile']['order_id'];
															$savearr['AdvertiserOrder']['package_id'] = $this->data['AdvertiserProfile']['package_id'];
															$this->AdvertiserOrder->save($savearr);
															$savedatearr = '';
															$savedatearr['AdvertiserProfile']['id'] = $id;
															$savedatearr['AdvertiserProfile']['modified'] = date('Y-m-d H:i:s');
															$this->AdvertiserProfile->save($savedatearr);
														}
														
				
														
														
							//-------------------------------------------------Order Email------------------------------------------//
												$this->loadModel('Setting');
												$arrayTags = array("[advertiser_name]","[package_name]","[package_price]");
												$arrayReplace = array($this->common->getCompanyNameById($id),$pkg_arr[$this->data['AdvertiserProfile']['package_id']],$this->common->getPackagePrice($this->data['AdvertiserProfile']['package_id']));
												$emailArray = $this->Setting->find('first',array('fields'=>array('Setting.package_subject','Setting.package_content'),'conditions'=>array('Setting.id'=>1)));
												$subject 	= str_replace($arrayTags,$arrayReplace,$emailArray['Setting']['package_subject']);
												$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailArray['Setting']['package_content']);
			
			
												$this->Email->sendAs = 'html';
												$this->Email->to = $this->common->getCompanyEmail($id);
												$this->Email->subject = $subject;
												$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = $this->emailhtml->email_header();
												$this->body .=$bodyText;
												$this->body .= $this->emailhtml->email_footer();
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$this->common->getCompanyEmail($id),strip_tags($subject),$this->body,"special_package_upgrade");
					/////////////////////////////////////////////////////////////////////////
					
							//---------------------------------------------------------------------------------------------------//
													//$this->Session->setFlash('Package has been updated successfully.');
													$this->redirect(array('controller' => $redirection_cntr,'action' =>$redirection_act,$id));
													} else	{
															$this->Session->setFlash("Payment Gateway Error : ".$response_array[3]);
															return false;
														}
													}
												}
										} else {
												$this->redirect(array('controller' => 'advertiser_profiles','action' =>'changeSheet'));
										}
	}
	//---------old to upgrade special package for backup---------//
	function __splpackage__($id,$spcl_pkg=0) {
		if($id) {
				$this->set('title_for_layout', 'Upgrade Package');
				$this->AdvertiserProfile->id = $id;
				$data = $this->AdvertiserProfile->read();
				$this->set('data',$data);
				$this->loadModel('AdvertiserOrder');
				$this->set('PackageId', $this->AdvertiserOrder->packageDetail($id));
				$pkg_arr = $this->common->getspclPackage();
				$this->set('Packages', $pkg_arr);
				$this->set('currPackages', $this->common->getAllPackage(1));
				$this->set('spcl_pkg', $spcl_pkg);
				if(isset($this->data)) {
								$error = array();
								if($this->data['AdvertiserProfile']['package_id']=='') {
									$error[] = 'Please select a Package.';
								}
								if($this->data['AdvertiserProfile']['credit_name']=='') {
									$error[] = 'Please enter Name On Credit Card.';
								}
								if($this->data['AdvertiserProfile']['address']=='') {
									$error[] = 'Please enter Address.';
								}
								if($this->data['AdvertiserProfile']['city']=='') {
									$error[] = 'Please enter City.';
								}
								if($this->data['AdvertiserProfile']['state']=='') {
									$error[] = 'Please enter State.';
								}
								if($this->data['AdvertiserProfile']['zip']=='') {
									$error[] = 'Please enter Zip.';
								}
								if($this->data['AdvertiserProfile']['credit_number']=='') {
									$error[] = 'Please enter Credit Card Number.';
								}
								if($this->data['AdvertiserProfile']['cvv']=='') {
									$error[] = 'Please enter CVV .';
								}
								if($this->data['AdvertiserProfile']['card_exp_month']=='') {
									$error[] = 'Please select Card Expiry Month.';
								}
								if($this->data['AdvertiserProfile']['card_exp_year']=='') {
									$error[] = 'Please select Card Expiry Year.';
								}
								
								if(!empty($error)) {
									$this->Session->setFlash(implode('<br />',$error));
								} else {
								//----------------------------------------------------------------------------------------
									$this->loadModel('Package');
									$p_price = $this->Package->find('first',array('fields'=>array('Package.setup_price','Package.monthly_price'),'conditions'=>array('Package.id'=>$this->data['AdvertiserProfile']['package_id'])));
									$total_price = ($p_price['Package']['setup_price']+$p_price['Package']['monthly_price']);
									/*if($this->data['AdvertiserProfile']['monthly_fee']!='') {
										$total_price = $total_price+$this->data['AdvertiserProfile']['monthly_fee'];
									}*/
									//////////////////// Payment Gateway //////////////////////////
													ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/Users/keithpalmerjr/Projects/QuickBooks/');
													//error_reporting(E_ALL | E_STRICT);
													//ini_set('display_errors', true);
													require_once (APP.'webroot/quickbooks/QuickBooks.php');
													$dsn = null;
													$path_to_private_key_and_certificate = APP.'webroot/quickbooks/docs/intuit.pem';
													$application_login = INTUIT_APP_LOGIN;
													$connection_ticket = INTUIT_TICKET;
													//$connection_ticket = 'TGT-214-ZfVkTPfjiZZYFg83gYA6Hw';
													$MS = new QuickBooks_MerchantService(
													$dsn,
													$path_to_private_key_and_certificate,
													$application_login,
													$connection_ticket);
													//$MS->useTestEnvironment(true);
													$MS->useLiveEnvironment(true);
													$name = NULL;
													//$number = '4427322513320494';
													$number = $this->data['AdvertiserProfile']['credit_number'];
													$expyear =	$this->data['AdvertiserProfile']['card_exp_year'];
													$expmonth = $this->data['AdvertiserProfile']['card_exp_month'];
													$address = NULL;
													$postalcode = NULL;
													$cvv = $this->data['AdvertiserProfile']['cvv'];
													$amount = number_format($total_price,1);
													//$amount = 1.0;
												$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
												if ($Transaction = $MS->charge($Card, $amount))
													{
														$redirection_cntr = 'advertiser_profiles';
														$redirection_act = 'masterSheet';
														
														$saveWorkArray = '';
														
														if($this->data['AdvertiserProfile']['package_id']==$this->common->homeDiscountPkg()) {
															$spcl_pkg_id = $this->data['AdvertiserProfile']['package_id'];
															$redirection_cntr = 'daily_discounts';
															$redirection_act = 'addDailyDiscount';
															
														    $saveWorkArray['WorkOrder']['subject']  =  'Home Page Daily Discount Updated';
														    $saveWorkArray['WorkOrder']['message']  =  'Advertiser paid for the Add Home Page Daily Discount recently. details are below:';
														    $saveWorkArray['WorkOrder']['type']   	=  'addhomediscountupdate';
															
														} else if($this->data['AdvertiserProfile']['package_id']==$this->common->homeDealPkg()) {
															$spcl_pkg_id = $this->data['AdvertiserProfile']['package_id'];
															$redirection_cntr = 'daily_deals';
															$redirection_act = 'addDailyDeal';
															
														    $saveWorkArray['WorkOrder']['subject']   =  'Home Page Daily Deal Updated';
														    $saveWorkArray['WorkOrder']['message']   = 'Advertiser paid for the Add Home Page Daily Deal recently. details are below:';
														    $saveWorkArray['WorkOrder']['type']   	 =  'addhomedealupdate';
															
														} else if($this->data['AdvertiserProfile']['package_id']==$this->common->homeSavingPkg()) {
															$spcl_pkg_id = $this->data['AdvertiserProfile']['package_id'];
															$redirection_cntr = 'saving_offers';
															$redirection_act = 'addNewOffer';

														    $saveWorkArray['WorkOrder']['subject']   =  'Home Page Banner Updated';
														    $saveWorkArray['WorkOrder']['message']   =  'Advertiser paid for the Add Home Page Banner recently. details are below:';
														    $saveWorkArray['WorkOrder']['type']   	 =  'addhomebannerupdate';
																														
														} else {
															$spcl_pkg_id = 0;
														}
														if($spcl_pkg_id!=0) {												
															$spcl_pkg = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.spclpkg'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
															$full_spcl_pkg_id = str_replace(','.$spcl_pkg_id.',',',',$spcl_pkg['AdvertiserProfile']['spclpkg']);
															$full_spcl_pkg_id = $full_spcl_pkg_id.','.$spcl_pkg_id.',';
															$savearr = '';
															$savearr['AdvertiserProfile']['id'] = $id;
															$savearr['AdvertiserProfile']['spclpkg'] = $full_spcl_pkg_id;
															$savearr['AdvertiserProfile']['modified'] = date('Y-m-d H:i:s');
															$this->AdvertiserProfile->save($savearr);
															
								//------------------------inbox notification (Start)-----------//
																				
														  $myAdvertiserInfo=$this->common->getAdvertiserdetailswithOrder($id);
														  App::import('model', 'WorkOrder');
														  $this->WorkOrder = new WorkOrder;
														  $saveWorkArray['WorkOrder']['id']  =  '';
														  $saveWorkArray['WorkOrder']['advertiser_order_id']  =  $myAdvertiserInfo['order_id'];
														  $saveWorkArray['WorkOrder']['read_status']   				=  0;
														  $saveWorkArray['WorkOrder']['sent_to']   	  =  0;
														  $saveWorkArray['WorkOrder']['sent_to_group'] =  1;
														  $saveWorkArray['WorkOrder']['from_group']   =  $this->Session->read('Auth.Admin.user_group_id');
														  $saveWorkArray['WorkOrder']['bottom_line']  =  '';
														  $saveWorkArray['WorkOrder']['salseperson_id'] =  $myAdvertiserInfo['creator'];
														  date_default_timezone_set('US/Eastern');
														  $saveWorkArray['WorkOrder']['created_date']  =  date(DATE_FORMAT.' h:i:s A');
														  $saveWorkArray['WorkOrder']['created']       =  strtotime(date(DATE_FORMAT.' h:i:s A'));
														  $this->WorkOrder->save($saveWorkArray);						
														
								//------------------------inbox notification (End)-----------//		
								//------------------------create order instance (start)-----------//		
														App::import('model', 'OrderInstance');
														$this->OrderInstance = new OrderInstance;
														$saveInstanceArray = array();
														$saveInstanceArray['OrderInstance']['advertiser_order_id']   =  $myAdvertiserInfo['order_id'];
														$saveInstanceArray['OrderInstance']['advertiser_profile_id']  =  $id;
														$saveInstanceArray['OrderInstance']['package_id']   	=  $this->data['AdvertiserProfile']['package_id'];
														$saveInstanceArray['OrderInstance']['insert_status']   	=  4;
														$this->OrderInstance->save($saveInstanceArray);
								//------------------------create order instance (End)-----------//																		
														} else {														
															$savearr = '';
															$savearr['AdvertiserOrder']['id'] = $this->data['AdvertiserProfile']['order_id'];
															$savearr['AdvertiserOrder']['package_id'] = $this->data['AdvertiserProfile']['package_id'];
															$this->AdvertiserOrder->save($savearr);
															$savedatearr = '';
															$savedatearr['AdvertiserProfile']['id'] = $id;
															$savedatearr['AdvertiserProfile']['modified'] = date('Y-m-d H:i:s');
															$this->AdvertiserProfile->save($savedatearr);
														}
														
				
														
														
							//-------------------------------------------------Order Email------------------------------------------//
												$this->loadModel('Setting');
												$arrayTags = array("[advertiser_name]","[package_name]","[package_price]");
												$arrayReplace = array($this->common->getCompanyNameById($id),$pkg_arr[$this->data['AdvertiserProfile']['package_id']],$this->common->getPackagePrice($this->data['AdvertiserProfile']['package_id']));
												$emailArray = $this->Setting->find('first',array('fields'=>array('Setting.package_subject','Setting.package_content'),'conditions'=>array('Setting.id'=>1)));
												$subject 	= str_replace($arrayTags,$arrayReplace,$emailArray['Setting']['package_subject']);
												$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailArray['Setting']['package_content']);
			
			
												$this->Email->sendAs = 'html';
												$this->Email->to = $this->common->getCompanyEmail($id);
												$this->Email->subject = $subject;
												$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = $this->emailhtml->email_header();
												$this->body .=$bodyText;
												$this->body .= $this->emailhtml->email_footer();
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$this->common->getCompanyEmail($id),strip_tags($subject),$this->body,"special_package_upgrade");
					/////////////////////////////////////////////////////////////////////////
					
							//---------------------------------------------------------------------------------------------------//
													//$this->Session->setFlash('Package has been updated successfully.');
													$this->redirect(array('controller' => $redirection_cntr,'action' =>$redirection_act,$id));
													} else	{
															$this->Session->setFlash($MS->errorMessage());
															return false;
														}
													}
												}
										} else {
												$this->redirect(array('controller' => 'advertiser_profiles','action' =>'changeSheet'));
										}
	}

	function pkgmonthly($pkg_id) {
		$this->autoRender = false;
		echo $this->common->pkgMonthlyFee($pkg_id);
	}
	
	
	function signature() {
					if(isset($_POST['output']) && $_POST['output']!=''){

						$img = $this->sigJsonToImage($_POST['output']);

						// Save to file

						$fileName = time()."-signature.png";

						$filePath = WWW_ROOT."Signature/".$fileName;

						imagepng($img, $filePath);

						imagedestroy($img);						

					}else{ // if sign is Text

						$img = imagecreatetruecolor(400, 30);

						$bgColour = imagecolorallocate($img, 0xff, 0xff, 0xff);

						$penColour = imagecolorallocate($img, 0x14, 0x53, 0x94);

						imagefilledrectangle($img, 0, 0, 399, 29, $bgColour);

						$text = $this->data['AdvertiserProfile']['name'];

						$font = WWW_ROOT.'journal.ttf';

						imagettftext($img, 20, 0, 10, 20, $penColour, $font, $text);

						// Save to file

						$fileName = time()."-signature.png";

						$filePath = WWW_ROOT."Signature/".$fileName;

						imagepng($img, $filePath);
						
						imagedestroy($img);
					}
				

				}				
				
	function drawThickLine ($img, $startX, $startY, $endX, $endY, $colour, $thickness) {

		  $angle = (atan2(($startY - $endY), ($endX - $startX)));

		

		  $dist_x = $thickness * (sin($angle));

		  $dist_y = $thickness * (cos($angle));

		

		  $p1x = ceil(($startX + $dist_x));

		  $p1y = ceil(($startY + $dist_y));

		  $p2x = ceil(($endX + $dist_x));

		  $p2y = ceil(($endY + $dist_y));

		  $p3x = ceil(($endX - $dist_x));

		  $p3y = ceil(($endY - $dist_y));

		  $p4x = ceil(($startX - $dist_x));

		  $p4y = ceil(($startY - $dist_y));

		

		  $array = array(0=>$p1x, $p1y, $p2x, $p2y, $p3x, $p3y, $p4x, $p4y);

		  imagefilledpolygon($img, $array, (count($array)/2), $colour);

		}
		
	function sigJsonToImage ($json, $options = array()) {

		  $defaultOptions = array(

			'imageSize' => array(198, 55)

			,'bgColour' => array(0xff, 0xff, 0xff)

			,'penWidth' => 2

			,'penColour' => array(0x14, 0x53, 0x94)

			,'drawMultiplier'=> 12

		  );

		

		  $options = array_merge($defaultOptions, $options);

		

		  $img = imagecreatetruecolor($options['imageSize'][0] * $options['drawMultiplier'], $options['imageSize'][1] * $options['drawMultiplier']);

		

		  if ($options['bgColour'] == 'transparent') {

			imagesavealpha($img, true);

			$bg = imagecolorallocatealpha($img, 0, 0, 0, 127);

		  } else {

			$bg = imagecolorallocate($img, $options['bgColour'][0], $options['bgColour'][1], $options['bgColour'][2]);

		  }

		

		  $pen = imagecolorallocate($img, $options['penColour'][0], $options['penColour'][1], $options['penColour'][2]);

		  imagefill($img, 0, 0, $bg);

		

		  if (is_string($json))

			$json = json_decode(stripslashes($json));

		

		  foreach ($json as $v)

			$this->drawThickLine($img, $v->lx * $options['drawMultiplier'], $v->ly * $options['drawMultiplier'], $v->mx * $options['drawMultiplier'], $v->my * $options['drawMultiplier'], $pen, $options['penWidth'] * ($options['drawMultiplier'] / 2));

		

		  $imgDest = imagecreatetruecolor($options['imageSize'][0], $options['imageSize'][1]);

		

		  if ($options['bgColour'] == 'transparent') {

			imagealphablending($imgDest, false);

			imagesavealpha($imgDest, true);

		  }
		  imagecopyresampled($imgDest, $img, 0, 0, 0, 0, $options['imageSize'][0], $options['imageSize'][0], $options['imageSize'][0] * $options['drawMultiplier'], $options['imageSize'][0] * $options['drawMultiplier']);
		  
		  imagedestroy($img);
		  
		  return $imgDest;
		}
/************************** Auto reminder mail to sales person before 15 days of contract date expire ******************************/
	function ContractReminder() {
		date_default_timezone_set('US/Eastern');
		$this->autoRender = false;
		
		$this->loadModel('Setting');
		$duration = $this->Setting->find('first',array('fields'=>array('Setting.contract_duration')));
		$days = (int)$duration['Setting']['contract_duration'];
		$maxtime = mktime(0,0,0,date('m'),date('d')+$days,date('Y'));
		
		$contract = $this->AdvertiserProfile->find('all',array('fields'=>array('AdvertiserProfile.company_name','AdvertiserProfile.contract_date','AdvertiserProfile.contract_expiry_date','AdvertiserProfile.creator'),'conditions'=>array('AdvertiserProfile.contract_expiry_date'=>$maxtime,'AdvertiserProfile.publish'=>'yes')));
		
		if(!empty($contract)) {
			foreach($contract as $contract) {
				$this->loadModel('User');
				$email = $this->User->find('first',array('fields'=>array('User.name','User.email'),'conditions'=>array('User.active'=>'yes','User.id'=>$contract['AdvertiserProfile']['creator'])));
				if(!empty($email)) {
							$email_id = $email['User']['email'];
							$range = date(DATE_FORMAT,$contract['AdvertiserProfile']['contract_date']).' - '.date(DATE_FORMAT,$contract['AdvertiserProfile']['contract_expiry_date']);
							$arrayTags = array("[salesperson]","[advertiser]","[contract_date]");
							$arrayReplace = array($email['User']['name'],$contract['AdvertiserProfile']['company_name'],$range);
							
							//get Mail format
							$this->loadModel('Setting');
							$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.contract_subject','Setting.contract_body')));
							$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['contract_subject']);
							$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['contract_body']);
							
							//ADMINMAIL id
							$this->Email->to 		= $email_id;
							$this->Email->subject 	= strip_tags($subject);
							$this->Email->replyTo 	= $this->common->getReturnEmail();
							$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
							$this->Email->sendAs 	= 'html';
							//Set the body of the mail as we send it.
							//seperate line in the message body.
							$this->body = '';				
							$this->body = $this->cronhtml->email_header();
							$this->body .=$bodyText;
							$this->body .= $this->cronhtml->email_footer();
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
							$this->Email->reset();
				}
			}
		}
	}
	function openvideo() {
		$this->layout = false;
		$detect_root = new Mobile_Detect();
		$device = ($detect_root->isMobile() ? ($detect_root->isTablet() ? 'tablet' : 'mobile') : 'computer');
		$this->set('device',$device);
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function offerEmail() {
		$this->set('title_for_layout', 'Advertiser Offer Email');
		$this->set('advertiserList',$this->common->getAdvertiserProfileAll());
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function offerHtml() {
		$this->set('title_for_layout', 'Advertiser Offer Email');
		if(isset($this->data)) {
			$advertisers = array_flip($this->data['AdvertiserProfile']['arr']);
			ksort($advertisers);
			$this->set('advertiser',$advertisers);
		} else {
			$this->redirect(array('action'=>'offerEmail'));
		}
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function test_offer_email() {
		$this->autoRender = false;
		if(isset($this->data)) {
				
				$advertiser = unserialize($this->data['AdvertiserProfile']['array_string']);
				$content = '';
				$content .= $this->offerhtml->email_header();
				$content .= $this->offerhtml->email_box();
				$content .= $this->offerhtml->email_content($advertiser);
				$content .= $this->offerhtml->email_footer();
				

				$this->Email->sendAs = 'html';
				$this->Email->to = $this->data['AdvertiserProfile']['email'];
				$this->Email->subject = $this->common->getOfferEmailSubject();//'Zuni Merchant Page / Everyday Savings Offers';
				$this->Email->replyTo = $this->common->getReturnEmail();
				$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
				
				$this->body = '';
				$this->body .= $content;
									
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
			$this->Session->setFlash('Test email sent successfully.');
			$this->redirect(array('action'=>'offerEmail'));
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function bulkOfferEmail() {
		$this->autoRender = false;
		if(isset($this->data)) {
				$advertiser = unserialize($this->data['AdvertiserProfile']['array_string']);
				$content = '';
				$content .= $this->offerhtml->email_header();
				$content .= $this->offerhtml->email_box();
				$footer  = $this->offerhtml->email_footer();
				set_time_limit(0);
				
				$succedd = '';
				$failed = '';
				$unique_string = $this->common->randomPassword(10);
				$this->loadModel('DiscountNewsletter');
				$users = $this->DiscountNewsletter->find('all',array('fields'=>array('id,email'),'conditions'=>array('status'=>'yes')));
				//$users = array(array('DiscountNewsletter'=>array('id'=>1,'email'=>'keshav@planetwebsolution.com')),array('DiscountNewsletter'=>array('id'=>2,'email'=>'manoj@planetwebsolution.com')),array('DiscountNewsletter'=>array('id'=>3,'email'=>'seobranddevelopers@gmail.com')));
				$email_ids = array_chunk($users, EMAIL_LIST);
				foreach($email_ids as $email_id) {
						foreach($email_id as $email) {
							$id = $email['DiscountNewsletter']['id'];
							$this->Email->sendAs = 'html';
							$this->Email->to = $email['DiscountNewsletter']['email'];
							$this->Email->subject = $this->common->getOfferEmailSubject(); //'Zuni Merchant Page / Everyday Savings Offers';
							$this->Email->replyTo = $this->common->getReturnEmail();
							$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
							
							//For URL tracking
							$tracking_string = '?unique='.$unique_string.'?'.base64_encode($id);
							$content_final = '';
							$content_final .= $this->offerhtml->email_content($advertiser,$tracking_string);
							
							// For email open tracking
							$content_final .= '<img src="'.FULL_BASE_URL.router::url('/',false).'offeremaillogs/saveEmailOpen?unique='.$unique_string.'?'.base64_encode($id).'" style="display:none;width:0" />';
							
							$content_final .= $footer;
							
							$this->body = '';
							$this->body .= $content;
							$this->body .= $content_final;
							
							$this->Email->smtpOptions = array(
								'port'=>'25',
								'timeout'=>'30',
								'host' =>SMTP_HOST_NAME,
								'username'=>SMTP_USERNAME,
								'password'=>SMTP_PASSWORD
							);
							$this->Email->delivery = 'smtp';
							if($this->Email->send($this->body)) {
								$succedd[]= $id;
							} else {
								$failed[]= $id;
							}
							$this->Email->reset();
						}
				}
	 //----------------------------------Save Email Log----------------------------------------//		
			$this->loadModel('Offeremaillog');
			$sent = ',';
			$notsent = ',';
			if(is_array($succedd)) {
				$sent = ','.implode(',',$succedd).',';
			}
			if(is_array($failed)) {
				$notsent = ','.implode(',',$failed).',';
			}
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$check_same = $this->common->check_same_date_offer($today);
			
			$save = '';
			if(isset($check_same['Offeremaillog']['id'])) {
				$save['Offeremaillog']['id'] = $check_same['Offeremaillog']['id'];
			}
				$save['Offeremaillog']['sending_date'] = $today;
				$save['Offeremaillog']['sent'] = $sent;
				$save['Offeremaillog']['notsent'] = $notsent;
				$save['Offeremaillog']['opened'] = '';
				$save['Offeremaillog']['email_opened'] = '';
				$save['Offeremaillog']['unique']=$unique_string;
				$save['Offeremaillog']['advrtisers']=$this->data['AdvertiserProfile']['array_string'];
				
			$this->Offeremaillog->save($save);
		//----------------------------------Save Email Log----------------------------------------//
	}
			$this->Session->setFlash('Emails sent successfully to all Subscribers.');
			$this->redirect(array('controller'=>'offeremaillogs','action'=>'index'));
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/	
	function intermediate() {
		
	}
/*------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function crop($pass='') {
	if($pass!='') {
		$break = explode('/',base64_decode($pass));
		if(count($break)==2) {
			$img = base64_decode($break[0]);
			$url = base64_decode($break[1]);
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				if(trim($_POST['w'])=='' || trim($_POST['h'])=='') {
					$this->Session->setFlash('Invalid dimensions for resizing.');
				} else {
				ini_set('memory_limit', '-1');
				$size = getimagesize(WWW_ROOT.'img/'.$img); 
					switch ($size['mime']) { 
					case "image/gif": 
						$src_image = imagecreatefromgif(WWW_ROOT.'img/'.$img);
						break; 
					case "image/jpeg":
						$src_image = imagecreatefromjpeg(WWW_ROOT.'img/'.$img);
						break;
					case "image/png":
						$src_image = imagecreatefrompng(WWW_ROOT.'img/'.$img);
						break;
					}
					
					$dst_x = 0;
					$dst_y = 0;
					$src_x = $_POST['x1']; // Crop Start X
					$src_y = $_POST['y1']; // Crop Srart Y
					$dst_w = (int)$_POST['w']; // Thumb width
					$dst_h = (int)$_POST['h']; // Thumb height
					$src_w = (int)$_POST['w'];//(int)($_POST['w']+$_POST['x2']);
					$src_h = (int)$_POST['h'];//(int)($_POST['h']+$_POST['y2']);
					
					$dst_image = imagecreatetruecolor($dst_w,$dst_h);
					imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
					
					switch ($size['mime']) {
					case "image/gif":
						imagegif($dst_image, WWW_ROOT.'img/'.$img);
						break;
					case "image/jpeg":
						imagejpeg($dst_image, WWW_ROOT.'img/'.$img);
						break;
					case "image/png":
						imagepng($dst_image, WWW_ROOT.'img/'.$img);
						break;
					}
					$this->Session->setFlash('Image has been resized successfully.');
					$this->redirect($url.'/type:success');
				}	
			}
			$this->set('img',$img);
			$this->set('url',$url);
		} else {
			$this->redirect($this->referer());
		}
	} else {
		$this->redirect($this->referer());
	}
}			
		// upadate query for copying main saving offer image to advetiser profile offer image
		function setOfferImageFromSavingOffer()
		{
			exit;
			$this->loadModel('SavingOffer');
			$all_save_offers=$this->SavingOffer->find('all',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.advertiser_profile_id !='=>0)));
			$up=0;
			foreach($all_save_offers as $all_save_offer)
			{
				$this->AdvertiserProfile->query("UPDATE advertiser_profiles SET offer_image='".$all_save_offer['SavingOffer']['offer_image_big']."' where id='".$all_save_offer['SavingOffer']['advertiser_profile_id']."'");
				$up++;
			}
			echo $up.' images added';
			exit;
		}
/************************************ Function to confirm to delete advertiser profile *********************************************/
	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username',
            'password' => 'password'
            );
			$this->Auth->allow('send_proof','savealldata','emailvalidate','Useremailvalidate','saveCustomerdata','savepassword','saveNewslaterdata','autocomplete','validEmailNewsletter','Consumeremailvalidate','ConsumeremailvalidateMobile','saveReferdata','send_mail','saveContestCustomerdata','pkgmonthly','saveDealCustomerdata','saveDiskCustomerdata','ContractReminder','openvideo');
			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
   	}
	/* This function is setting all info about current Admins in
	currentAdmin array so we can use it anywhere lie name id etc.
	*/
	function beforeRender() {
		$this->set('last_url',$this->referer());
		$this->set('currentAdmin', $this->Auth->user());
		$this->set('cssName',$this->Cookie->read('css_name'));
        $this->set('groupDetail',$this->common->adminDetails());
		$this->set('common',$this->common);
		$this->set('offerhtml',$this->offerhtml);
		//$this->Ssl->force();
	}
}//end class
?>