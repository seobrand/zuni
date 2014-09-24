<?php 
/*
   Coder: Abhimanyu
   Date  : 13 Aug 2010
*/ 
class BannersController extends AppController { 
	  var $name = 'Banners';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('Auth','common','Cookie','RequestHandler','Session');  //component to check authentication . this component file is exists in app/controllers/components
	  
      /*    destroy all current sessions for a perticular SuperAdmins
	       and redirect to login page automatically
	 */
	    function logout() {
   		         $this->redirect($this->Auth->logout());
        }


     // index page of state for listing
	   function index(){  
             //variable for display number of state name per page	
	            $condition='';
				 $this->set('CountyList',$this->common->getAllCounty());
			 	 $this->set('countySearch', 'County');
				 $this->set('title', 'Banner Title'); 
				 $this->set('banner_size', 'Advertisement Type'); 

				$cond = array();
				
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'Banner.title' => 'asc' ));
				
				if((!empty($this->data['banners']['title']) && $this->data['banners']['title']!='Banner Title')){
			       $cond['Banner.title LIKE'] = '%' . $this->data['banners']['title'] . '%';
			      (empty($this->params['named'])) ? $this->set('title', $this->data['banners']['title']) :$this->set('title', $this->data['named']['title']) ; 
				 }
				
				if((!empty($this->data['banners']['countySearch']) && $this->data['banners']['countySearch']!='County')){
			       $cond['Banner.county_id'] =  $this->data['banners']['countySearch'];
			      (empty($this->params['named'])) ? $this->set('countySearch', $this->data['banners']['countySearch']) :$this->set('countySearch', $this->data['named']['countySearch']) ; 
				 }
				 
				if($this->data['banners']['banner_size']!='') {
			       $cond['Banner.banner_size'] =  $this->data['banners']['banner_size'];
			      (empty($this->params['named'])) ? $this->set('banner_size', $this->data['banners']['banner_size']) :$this->set('banner_size', $this->data['named']['banner_size']) ; 
			    }
				
			    if($this->data['banners']['publish']!="" ) {
				
				    $cond['Banner.publish LIKE'] = '%' . $this->data['banners']['publish'] . '%';
			        (empty($this->params['named'])) ? $this->set('publish', $this->data['banners']['publish']) :$this->set('publish', $this->data['named']['publish']) ;
		        } 
				
				if(!empty($this->params['named'])){
				     if(isset($this->params['named']['title'] )){
					   $cond['Banner.title LIKE'] = '%' . $this->params['named']['title'] . '%';
					   $this->set('title', $this->params['named']['title']);
					 }
				     if(isset($this->params['named']['countySearch'] )){
					   $cond['Banner.county_id'] = $this->params['named']['countySearch'] ;
					   $this->set('countySearch', $this->params['named']['countySearch']);
					 }
					 
				     if(isset($this->params['named']['banner_size'] )){
					   $cond['Banner.banner_size'] = $this->params['named']['banner_size'];
					   $this->set('banner_size', $this->params['named']['banner_size']);
					 }
					 if(isset($this->params['named']['publish'] )){
					   $cond['Banner.publish LIKE'] = '%' . $this->params['named']['publish'] . '%';
					   $this->set('publish', $this->params['named']['publish']);
					 }
				}
				
				 //If condition array is greater then 1 then combine by AND tag
			   if(is_array($condition) && count($condition) > 1) {
			 	   $condition['AND'] = $cond;
			   } else {
			       $condition  = $cond;
			    }

			  $data = $this->paginate('Banner', $condition);
		      $this->set('banners', $data); 
	   }
	   
	   
	   // ajax category selection
	function selectedCatList(){
	
		if(isset($this->data['Banner']['advertiser_profile_id'])&& $this->data['Banner']['advertiser_profile_id'] !=''){
		$adv_id=$this->data['Banner']['advertiser_profile_id'];
		}
		elseif(isset($this->params['pass'][0]))
		{
		$adv_id=$this->params['pass'][0];
		}
		else
		{
		$adv_id = '';
		}
		$this->set('a_id',$adv_id);
		
	if(isset($this->params['pass'][1]))
		$this->set('cat_select',$this->params['pass'][1]);
	else
		$this->set('cat_select','');

	if(isset($this->params['pass'][2]))
		$this->set('pcat_select',$this->params['pass'][2]);
	else
		$this->set('pcat_select','');	
	}	
	
	//add new banner	  
	    function addNewBanner(){  	
		
		/*------------------validation for redirect 2 mastersheet if it is initiated from master sheet-----------------*/
		  if((strpos($this->referer(),'masterSheet')!=false)) {
		  	$this->Session->write('reff',$this->referer());
		  }
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		   }
		  
		  
		/*----------------------------------------------------------------------------------------------------------*/
					//$this->set('AdvertiserProfiles', $this->common->getAllAdvertiserProfile());
					$this->set('AdvertiserProfiles', $this->common->getAdvertiserProfilesForBanner());					
	              if(isset($this->data)){
				 			 //pr($this->data);
	    	              $this->Banner->set($this->data['Banner']);
				   
			               /*if (empty($this->data)){
                          		   $this->data = $this->Banner->find(array('Banner.id' => $id));
                             } */
							 
			                       if ($this->Banner->validates()) {										  
										  $imageName='';
										  if($this->data['Banner']['logo']['name']!=''){
										  $this->data['Banner']['logo']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','_',$this->data['Banner']['logo']['name']);
											
											@chmod(APP.'webroot/img/banner',0777);
											
											$docDestination = APP.'webroot/img/banner/'.$this->data['Banner']['logo']['name']; 
											move_uploaded_file($this->data['Banner']['logo']['tmp_name'], $docDestination);
											 
											
											 $this->data['Banner']['image']=$this->data['Banner']['logo']['name']; 
										  }									  
										 
										  if(!empty($this->data['Banner']['date-11-mm'])){
										  $publishStartDate = mktime(date("h"), date("i"), date("s"), date($this->data['Banner']['date-11-mm']), date($this->data['Banner']['date-11-dd']), date($this->data['Banner']['date-11']));
										  } else {
										     $publishStartDate = "";
										  }
										  if(!empty($this->data['Banner']['date-1-mm'])){
										  $publishEndDate = mktime(date("h"), date("i"), date("s"), date($this->data['Banner']['date-1-mm']), date($this->data['Banner']['date-1-dd']), date($this->data['Banner']['date-1']));
										  } else {
										     $publishEndDate = "";
										  }
										  
										  
										 
								$this->data['Banner']['publish_date']=$publishStartDate;
								$this->data['Banner']['publish_enddate']=$publishEndDate;
								  
								  ///------------Get advertiser county, state and country-------
								  
								  App::import('model','AdvertiserProfile');
								  $this->AdvertiserProfile = new AdvertiserProfile();
								  
								  $advDetails=$this->AdvertiserProfile->find('first',array('fields'=>array('county','state','country'),'recursive' => -1,'conditions' => array('AdvertiserProfile.id'=>$this->data['Banner']['advertiser_profile_id'])));
								  
								
								$this->data['Banner']['county_id']=$advDetails['AdvertiserProfile']['county'];
								  
								$this->data['Banner']['state_id']=$advDetails['AdvertiserProfile']['state'];  
								
								$this->data['Banner']['country_id']=$advDetails['AdvertiserProfile']['country'];  
								
								  //----------------------------------------------------------
										  

										
										  if(isset($this->data['Banner']['category_id'])){
											  
											  $subCategoryDet  = explode("-",$this->data['Banner']['category_id']);
											  $parCategory  =$subCategoryDet[0];
											  $subCategory  = $subCategoryDet[1];
											  
											  $this->data['Banner']['category_id']      =  $parCategory;
											  $this->data['Banner']['subcategory_id']   =  $subCategory;
											}else{
											  $this->data['Banner']['category_id']      =  '';
											   $this->data['Banner']['subcategory_id']  = '';
											}
											
										

										  $this->Banner->save($this->data);
										  
									      $this->Session->setFlash('Your data has been submitted successfully.');  
										  
										if(isset($this->data['Banner']['prvs_link']) && (strpos($this->data['Banner']['prvs_link'],'masterSheet')!=false)) {
												$this->Session->delete('reff');
												$ad_id = explode('/',$this->data['Banner']['prvs_link']);			
												$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
										}else {										
											$this->redirect(array('action' => "index"));
										}	
										  
                                      }else{  

									      /*setting error message if validation fails*/
									      $errors = $this->Banner->invalidFields();
									      $this->Session->setFlash(implode('<br>', $errors));  
									       //$this->redirect(array('action' => "userGroup", 'message'=>'error'));
						             }

						             
				            }
	              }
        
	   
	   // show data in edit banner form
	   function bannerEditDetail($id=null){
		/*------------------validation for redirect 2 mastersheet if it is initiated from master sheet-----------------*/
		  if((strpos($this->referer(),'masterSheet')!=false)) {
		  	$this->Session->write('reff',$this->referer());
		  }
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		   }
		   
		   if(isset($this->data['Banner']['id']) && $this->data['Banner']['id']!='')
		   {
		   		$id=$this->data['Banner']['id'];
		   }
		   
		   
		   $data=$this->Banner->find('first',array('recursive' => -1,'conditions' => array('Banner.id'=>$id)));
		     
		    $this->set('data',$data);
			
			
			
		   //pr($this->Banner->find('first',array('recursive' => -1,'conditions' => array('Banner.id'=>$id))));
		/*----------------------------------------------------------------------------------------------------------*/		
				  
					
					$this->set('AdvertiserProfiles', $this->common->getAllAdvertiserProfile());
					
	              if(isset($this->data)){
				 	
	    	              $this->Banner->set($this->data['Banner']);
							 
			                       if($this->Banner->validates()) {	
								    
									$this->data['Banner']['id']=$this->data['Banner']['id'];
																		  
										  $imageName='';
										  if($this->data['Banner']['logo']['name']!=''){
										  $this->data['Banner']['logo']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','_',$this->data['Banner']['logo']['name']);
											
											@chmod(APP.'webroot/img/banner',0777);
											
											$docDestination = APP.'webroot/img/banner/'.$this->data['Banner']['logo']['name'];
											 
											$docDestinationDel = APP.'webroot/img/banner/'.$this->data['Banner']['hidden_photo']; 
											
											move_uploaded_file($this->data['Banner']['logo']['tmp_name'], $docDestination);
											
											$this->data['Banner']['image']=$this->data['Banner']['logo']['name']; 
											
											unlink($docDestinationDel);											
											 
										  }	
										  else
										  {
										  	$this->data['Banner']['image']=$this->data['Banner']['hidden_photo']; 
										  }								  
										 
										  if(!empty($this->data['Banner']['date-11-mm'])){
										  $publishStartDate = mktime(date("h"), date("i"), date("s"), date($this->data['Banner']['date-11-mm']), date($this->data['Banner']['date-11-dd']), date($this->data['Banner']['date-11']));
										  } else {
										     $publishStartDate = "";
										  }
										  if(!empty($this->data['Banner']['date-1-mm'])){
										  $publishEndDate = mktime(date("h"), date("i"), date("s"), date($this->data['Banner']['date-1-mm']), date($this->data['Banner']['date-1-dd']), date($this->data['Banner']['date-1']));
										  } else {
										     $publishEndDate = "";
										  }
										  
										  
												 
								$this->data['Banner']['publish_date']=$publishStartDate;
								$this->data['Banner']['publish_enddate']=$publishEndDate;
								  
								  								  

										
										  if($this->data['Banner']['category_id']!=''){
											  
											  $subCategoryDet  = explode("-",$this->data['Banner']['category_id']);
											  $parCategory  =$subCategoryDet[0];
											  $subCategory  = $subCategoryDet[1];
											  
											  $this->data['Banner']['category_id']      =  $parCategory;
											  $this->data['Banner']['subcategory_id']   =  $subCategory;
											}else{
											  $this->data['Banner']['category_id']      =  '';
											   $this->data['Banner']['subcategory_id']  = '';
											}
											
											
										
										  $this->Banner->save($this->data);
										  
									      $this->Session->setFlash('Your data has been submitted successfully.');  
										  
										if(isset($this->data['Banner']['prvs_link']) && (strpos($this->data['Banner']['prvs_link'],'masterSheet')!=false)) {
												$this->Session->delete('reff');
												$ad_id = explode('/',$this->data['Banner']['prvs_link']);			
												$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
										}else {										
											$this->redirect(array('action' => "index"));
										}	
										  
                                      }else{  

									      /*setting error message if validation fails*/
									      $errors = $this->Banner->invalidFields();
									      $this->Session->setFlash(implode('<br>', $errors));  
									       //$this->redirect(array('action' => "userGroup", 'message'=>'error'));
						             }

						             
				            }
	   
	   }
		
		
		
		//edit banner data
	   function bannerEdit($id=null){
	         $this->Banner->set($this->data['banners']);	

			 if ($this->Banner->validates()) {

										//making data array so we can pass in save mathod
										$saveArray = array();
							              $bannerId  					    =  $this->data['banners']['id'];
							              $saveArray['Banner']['id']        =  $bannerId;
										  
										  
			                              $bannerDetailArr 				    =  $this->Banner->find("id = $bannerId");
									      $saveArray['Banner']              =  $this->data['banners'];
										  if(!empty($this->data['banners']['image']['name'])){
										     $parameterArray = array();
											 $parameterArray['image'] = str_replace(' ','-',$this->data['banners']['image']);										 
										     $fileOK = $this->common->uploadFiles('img/banner', $parameterArray);
											 $imageName = $fileOK['urls'][0];
											 if($bannerDetailArr['Banner']['image']!="")
											 unlink(WWW_ROOT.''.$bannerDetailArr['Banner']['image']);
										  } else {										  
										     $imageName = $bannerDetailArr['Banner']['image'];											 
										  }
										  $saveArray['Banner']['image']=$imageName;
										  
										  if(!empty($saveArray['Banner']['date-11-mm'])){
										  $publishStartDate = mktime(date("h"), date("i"), date("s"), date($saveArray['Banner']['date-11-mm']), date($saveArray['Banner']['date-11-dd']), date($saveArray['Banner']['date-11']));
										  } else {
										     $publishStartDate = "";
										  }
										  
										  if(!empty($saveArray['Banner']['date-1-mm'])){
										  $publishEndDate = mktime(date("h"), date("i"), date("s"), date($saveArray['Banner']['date-1-mm']), date($saveArray['Banner']['date-1-dd']), date($saveArray['Banner']['date-1']));
										  } else {
										     $publishEndDate = "";
										  }
										  
										  if($publishEndDate!='' and $publishStartDate!=''){
										  		if($publishEndDate < $publishStartDate){
													$this->Session->setFlash('Banner Publish end date should be equal to or greater than start date.');  
													$this->redirect(array('action' => "bannerEditDetail/".$bannerId));
												}
										  }
										  
										  
										  $saveArray['Banner']['publish_date']     =  $publishStartDate;
										  $saveArray['Banner']['publish_enddate']  =  $publishEndDate;

										  if($this->data['banners']['category_id'][0]!=''){
											  $subCategory   = ",";
											  $subCategory  .= implode(",",$this->data['banners']['category_id']);
											  $subCategory  .= ",";
											  $saveArray['Banner']['category_id']        =  $subCategory;
										  }else{
										  	  $saveArray['Banner']['category_id']        =  '';
										  }
										  
										$this->Banner->save($saveArray);
										$this->Session->setFlash('Your data has been updated successfully.'); 
										
										if(isset($this->data['banners']['prvs_link']) && (strpos($this->data['banners']['prvs_link'],'masterSheet')!=false)) {
												$this->Session->delete('reff');
												$ad_id = explode('/',$this->data['banners']['prvs_link']);			
												$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
										}else {										
											$this->redirect(array('action' => "index"));
										}	
						 
			  } else{  

							/*setting error message if validation fails*/
							$errors = $this->Banner->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "bannerEditDetail/".$this->data['banners']['id'])); 
							
			  }
	    }
		
		 //delete category data in database
	   function bannerDelete($id) {
			  $result = $this->Banner->query("SELECT * FROM banners where category_id = $id");
			
			     $bannerDetailArr 	= $this->Banner->find("id = $id");
				 
				 if($bannerDetailArr['Banner']['image']!=""){				 
				        unlink(WWW_ROOT.'img/banner/'.$bannerDetailArr['Banner']['image']);						
				 }								
			     $this->Banner->delete($id);				 
			     $this->Session->setFlash('The Banner detail with id: '.$id.' has been deleted.');
	   	  	 if((strpos($this->referer(),'masterSheet')!=false)) {
					$ad_id = explode('/',$this->referer());			
					 $this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
				}else {
			     $this->redirect(array('action'=>'index'));			  
			  }			
	   }
	   //Set css for different color options

	/*----------------------To maintain reporting on front end--------------------------------------------------------*/ 		
	function reportBanner($adv_id='',$state='',$county='') {
				   $this->autoRender = false;
				   $st_id='';
				   $county_id='';
				   $condi_report='';
				   
				   $st_id=$this->common->getIdfromPageUrl('State',$state);
				   $county_id=$this->common->getIdfromPageUrl('County',$county);
				   App::import('model','InnerReport');
				   $this->InnerReport=new InnerReport();
				   $timestamp=$this->common->getTimeStampReport();
				   $st_id=$st_id['State']['id'];  
				   $county_id=$county_id['County']['id'];
				   $condi_report['InnerReport.state']=$st_id;
				   $condi_report['InnerReport.county']=$county_id;
				   $condi_report['InnerReport.date']=$timestamp;
				   $condi_report['InnerReport.type']='banner';
				   $condi_report['InnerReport.advertiser_id']=$adv_id;
				   $exist_rec=$this->InnerReport->find('first',array('conditions'=>$condi_report));
				  
				   if(empty($exist_rec))
				   {
					   $reportArray=array();
					   $reportArray['InnerReport']['state']=$st_id;
					   $reportArray['InnerReport']['county']=$county_id;
					   $reportArray['InnerReport']['date']=$timestamp;
					   $reportArray['InnerReport']['type']='banner';
					   $reportArray['InnerReport']['advertiser_id']=$adv_id;
					   $reportArray['InnerReport']['no_of_hit']=1;
					   $this->InnerReport->save($reportArray);
				   }
				   else
				   {
					   $reportArray=array();
					   $reportArray['InnerReport']['id']=$exist_rec['InnerReport']['id'];
					   $reportArray['InnerReport']['no_of_hit']=$exist_rec['InnerReport']['no_of_hit']+1;
					   $reportArray['InnerReport']['state']=$st_id;
					   $reportArray['InnerReport']['county']=$county_id;
					   $reportArray['InnerReport']['date']=$timestamp;
					   $reportArray['InnerReport']['advertiser_id']=$adv_id;
					   $reportArray['InnerReport']['type']='banner';
					   $this->InnerReport->save($reportArray);
				   }
	}	
	function check_home($id) {
		$this->autoRender = false;
		echo $this->common->bannerPerm($id);
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
	   	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );
			$this->Auth->allow('reportBanner');
		$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
   	}	 
	/* This function is setting all info about current SuperAdmins in 
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