<?php 
/*
   Coder: Vijender
   Date  : 01 Dec 2010
*/ 

class SettingsController extends AppController {
	  var $name = 'Settings';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('Auth','common','Cookie','Session');  //component to check authentication . this component file is exists in app/controllers/components
	  
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
				
				if(isset($this->params['named']['message']))
		           {
			          if($this->params['named']['message']=='success')
			          {
				        $this->set('success','success');
			          }else{
			            $this->set('error','error');
			          }
		          }
				
	            $this->set('search_text', 'header logo'); 
				
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'Setting.id' => 'asc' ));
			    if((!empty($this->data['settings']['search_text'] ))&&($this->data['settings']['search_text']!="header logo"))  {
                           $this->set('search_text', $this->data['settings']['search_text']); 
				           $condition =   array('Setting.h_logo LIKE' => '%' . $this->data['settings']['search_text'] . '%');
						   
		        } 
	         /*  if((isset($this->params['named']['search_text']))&&($this->params['named']['search_text']!="category name")){
                          $this->set('search_text', $this->params['named']['search_text']);
				          $condition =   array('Category.categoryname LIKE' => '%' . $this->params['named']['search_text'] . '%');
						  
		        } */				
			  $data = $this->paginate('Setting', $condition);
		      $this->set('settings', $data);
	   }
	   
	    // adding new Setting in database
	  
	    function addNewSetting(){  	
	              if(isset($this->data)){
			  
	    	               $this->Setting->set($this->data['settings']);
				   
			               if (empty($this->data)){
                          		   $this->data = $this->Setting->find(array('Setting.id' => $id));
                             }
			               if($this->data['settings']!=''){

					                 if ($this->Setting->validates()) {
									      //making data array so we can pass in save mathod
									      $saveArray 							 	= array();
									      $saveArray['Setting']['w_link'] 			=  $this->data['settings']['w_link'];
										  $saveArray['Setting']['f_link']      		=  $this->data['settings']['f_link'];
										 										 
										  if(!empty($this->data['settings']['h_logo']['name'])){
										  
										     $fileOK = $this->common->uploadFiles('img/setting', $this->data['settings']);
											 $imageName = $fileOK['urls'][0];
											 
										  } else {
										  
										     $imageName = "";
											 
										  }
										  
										  
										  $saveArray['Setting']['h_logo']        =  $imageName;
										  
									      $this->Setting->save($saveArray);
									      $this->Session->setFlash('Your data has been submitted successfully.');  
									      $this->redirect(array('action' => "index"  , 'message'=>'success'));
                                          //unlink($uploadpath.$largeImage);
						             } else {  

									      /*setting error message if validation fails*/
									       $errors = $this->Category->invalidFields();
									       $this->Session->setFlash(implode('<br>', $errors));  
									       //$this->redirect(array('action' => "userGroup", 'message'=>'error'));
						             }
				            }
	              }
        
	     }
		 
		 // show data in edit Setting form
	  	  function settingEditDetail($id=null)
		  {
  
		        //pr($this->common->getMasterPasswordForUser());
				  $this->set('Setting',$this->Setting->settingEditDetail($id));
	      }
		  
		  //edit state data
	   function settingEdit($id=null){
	  
	         $this->Setting->set($this->data['settings']);	
        
			 if ($this->Setting->validates()) {
/*			 pr($this->data);
			 exit;*/
							//making data array so we can pass in save mathod
							$ii = 0;
							$saveArray = array();
							$settingId = $this->data['settings']['id'];
							$saveArray['Setting']['id']= $settingId;
			                $settingDetailArr = $this->Setting->find("id = $settingId");
							
							#here we are checking if admin has filled both upload video filled
							#and youtube url filed then we are giving him erro message.
							if(!empty($this->data['settings']['how_isho_work_video']['name']) and !empty($this->data['settings']['how_ishop_work_youtube'])){
										$this->Session->setFlash('Please either upload video OR give youtube url for how Zuni works.');  
										$this->redirect(array('action' => "settingEditDetail/".$this->data['settings']['id']));
							}
							if(!empty($this->data['settings']['what_is_dailydouble_video']['name']) and !empty($this->data['settings']['what_is_dailydouble_youtube'])){
										$this->Session->setFlash('Please either upload video OR give youtube url for what is daily double.');  
										$this->redirect(array('action' => "settingEditDetail/".$this->data['settings']['id']));
							}
							if(!empty($this->data['settings']['ishop_bucks_video']['name']) and !empty($this->data['settings']['ishop_bucks_youtube'])){
										$this->Session->setFlash('Please either upload video OR give youtube url for Zuni bucks.');  
										$this->redirect(array('action' => "settingEditDetail/".$this->data['settings']['id']));
							}
							if(!empty($this->data['settings']['how_to_join_video']['name']) and !empty($this->data['settings']['how_to_join_youtube'])){
										$this->Session->setFlash('Please either upload video OR give youtube url for how to join Zuni.');  
										$this->redirect(array('action' => "settingEditDetail/".$this->data['settings']['id']));
							}							
							//uploading 4 videos so we are setting some configuration for upload time and upload size
							set_time_limit(0);
							ini_set("session.gc_maxlifetime","10800");
				/************************below we are uploading 4 videos one by one . we are allowing only .flv and .wmv files*********/
							if(!empty($this->data['settings']['how_isho_work_video']['name']) and $this->data['settings']['how_ishop_work_youtube']==''){
								$ii++;
								$fileArr  = explode(".",$this->data['settings']['how_isho_work_video']['name']);
								$currentTimestamp = $this->common->getTimeStamp().$ii;
								if(strtolower($fileArr[1]) == 'flv' || strtolower($fileArr[1])=='wmv'){
									$uploadpath =WWW_ROOT."videos/";
									$videoFileName = $currentTimestamp."_".$this->data['settings']['how_isho_work_video']['name'];
									$vidDestination = $uploadpath.$videoFileName;
									move_uploaded_file($this->data['settings']['how_isho_work_video']['tmp_name'], $vidDestination);
									if($settingDetailArr['Setting']['how_isho_work_video']!=""){
										unlink($uploadpath.$settingDetailArr['Setting']['how_isho_work_video']);
									}
									$saveArray['Setting']['how_isho_work_video'] = $videoFileName;
								}else{
										$this->Session->setFlash('Please upload only flv or wmv files.');  
										$this->redirect(array('action' => "settingEditDetail/".$this->data['settings']['id']));
								}
											 
						   } else {
									$saveArray['Setting']['how_isho_work_video'] 	= $settingDetailArr['Setting']['how_isho_work_video'];
									$saveArray['Setting']['how_ishop_work_youtube'] = $this->data['settings']['how_ishop_work_youtube'];
						  
						  }
						  // for default image of zuni video
							if(!empty($this->data['settings']['how_ishop_work_img']['name'])){
								$ii++;
								
								$currentTimestamp = $this->common->getTimeStamp().$ii;
								
									$uploadpathimg =WWW_ROOT."img/videos/";
									$imgFileName = $currentTimestamp."_".$this->data['settings']['how_ishop_work_img']['name'];
									$imgDestination = $uploadpathimg.$imgFileName;
									move_uploaded_file($this->data['settings']['how_ishop_work_img']['tmp_name'], $imgDestination);
									if($settingDetailArr['Setting']['how_ishop_work_img']!=""){
										unlink($uploadpathimg.$settingDetailArr['Setting']['how_ishop_work_img']);
									}
									$saveArray['Setting']['how_ishop_work_img'] = $imgFileName;
											 
						   } else {
									$saveArray['Setting']['how_ishop_work_img'] 	= $settingDetailArr['Setting']['how_ishop_work_img'];
						  
						  }						  
						  
						  
				/************************************************************************************************************************************/ 
						if(!empty($this->data['settings']['what_is_dailydouble_video']['name']) and $this->data['settings']['what_is_dailydouble_youtube']==''){
								$ii++;
								$fileArr  = explode(".",$this->data['settings']['what_is_dailydouble_video']['name']);
								$currentTimestamp = $this->common->getTimeStamp().$ii;
								if(strtolower($fileArr[1]) == 'flv' || strtolower($fileArr[1])=='wmv'){
									$uploadpath =WWW_ROOT."videos/";
									$videoFileName = $currentTimestamp."_".$this->data['settings']['what_is_dailydouble_video']['name'];
									$vidDestination = $uploadpath.$videoFileName;
									@chmod(APP.'webroot/videos',0777);
									move_uploaded_file($this->data['settings']['what_is_dailydouble_video']['tmp_name'], $vidDestination);
									if($settingDetailArr['Setting']['what_is_dailydouble_video']!=""){
										unlink($uploadpath.$settingDetailArr['Setting']['what_is_dailydouble_video']);
									}
									$saveArray['Setting']['what_is_dailydouble_video'] = $videoFileName;
								}else{
										$this->Session->setFlash('Please upload only flv or wmv files.');  
										$this->redirect(array('action' => "settingEditDetail/".$this->data['settings']['id']));
								}
											 
						   } else {
									$saveArray['Setting']['what_is_dailydouble_video'] = $settingDetailArr['Setting']['what_is_dailydouble_video'];
									$saveArray['Setting']['what_is_dailydouble_youtube'] = $this->data['settings']['what_is_dailydouble_youtube'];
						  
						  }
						  
						  // for default image of zuni video
							if(!empty($this->data['settings']['what_is_dailydouble_img']['name'])){
								$ii++;
								
								$currentTimestamp = $this->common->getTimeStamp().$ii;
								
									$uploadpathimg =WWW_ROOT."img/videos/";
									$imgFileName = $currentTimestamp."_".$this->data['settings']['what_is_dailydouble_img']['name'];
									$imgDestination = $uploadpathimg.$imgFileName;
									move_uploaded_file($this->data['settings']['what_is_dailydouble_img']['tmp_name'], $imgDestination);
									if($settingDetailArr['Setting']['what_is_dailydouble_img']!=""){
										unlink($uploadpathimg.$settingDetailArr['Setting']['what_is_dailydouble_img']);
									}
									$saveArray['Setting']['what_is_dailydouble_img'] = $imgFileName;
											 
						   } else {
									$saveArray['Setting']['what_is_dailydouble_img'] 	= $settingDetailArr['Setting']['what_is_dailydouble_img'];
						  
						  }	
						  
				/************************************************************************************************************************************/ 
						  
						if(!empty($this->data['settings']['ishop_bucks_video']['name']) and $this->data['settings']['ishop_bucks_youtube']==''){
								$ii++;
								$fileArr  = explode(".",$this->data['settings']['ishop_bucks_video']['name']);
								$currentTimestamp = $this->common->getTimeStamp().$ii;
								if(strtolower($fileArr[1]) == 'flv' || strtolower($fileArr[1])=='wmv'){
									$uploadpath =WWW_ROOT."videos/";
									$videoFileName = $currentTimestamp."_".$this->data['settings']['ishop_bucks_video']['name'];
									$vidDestination = $uploadpath.$videoFileName;
									move_uploaded_file($this->data['settings']['ishop_bucks_video']['tmp_name'], $vidDestination);
									if($settingDetailArr['Setting']['ishop_bucks_video']!=""){
										unlink($uploadpath.$settingDetailArr['Setting']['ishop_bucks_video']);
									}
									$saveArray['Setting']['ishop_bucks_video'] = $videoFileName;
								}else{
										$this->Session->setFlash('Please upload only flv or wmv files.');  
										$this->redirect(array('action' => "settingEditDetail/".$this->data['settings']['id']));
								}
											 
						   } else {
									$saveArray['Setting']['ishop_bucks_video'] = $settingDetailArr['Setting']['ishop_bucks_video'];
									$saveArray['Setting']['ishop_bucks_youtube'] = $this->data['settings']['ishop_bucks_youtube'];
						  
						  }

						  // for default image of zuni video
							if(!empty($this->data['settings']['ishop_bucks_img']['name'])){
								$ii++;
								
								$currentTimestamp = $this->common->getTimeStamp().$ii;
								
									$uploadpathimg =WWW_ROOT."img/videos/";
									$imgFileName = $currentTimestamp."_".$this->data['settings']['ishop_bucks_img']['name'];
									$imgDestination = $uploadpathimg.$imgFileName;
									move_uploaded_file($this->data['settings']['ishop_bucks_img']['tmp_name'], $imgDestination);
									if($settingDetailArr['Setting']['ishop_bucks_img']!=""){
										unlink($uploadpathimg.$settingDetailArr['Setting']['ishop_bucks_img']);
									}
									$saveArray['Setting']['ishop_bucks_img'] = $imgFileName;
											 
						   } else {
									$saveArray['Setting']['ishop_bucks_img'] 	= $settingDetailArr['Setting']['ishop_bucks_img'];
						  
						  }	

				/************************************************************************************************************************************/ 
						  
						if(!empty($this->data['settings']['how_to_join_video']['name']) and $this->data['settings']['how_to_join_youtube']==''){
								$ii++;
								$fileArr  = explode(".",$this->data['settings']['how_to_join_video']['name']);
								$currentTimestamp = $this->common->getTimeStamp().$ii;
								if(strtolower($fileArr[1]) == 'flv' || strtolower($fileArr[1])=='wmv'){
									$uploadpath =WWW_ROOT."videos/";
									$videoFileName = $currentTimestamp."_".$this->data['settings']['how_to_join_video']['name'];
									$vidDestination = $uploadpath.$videoFileName;
									move_uploaded_file($this->data['settings']['how_to_join_video']['tmp_name'], $vidDestination);
									if($settingDetailArr['Setting']['how_to_join_video']!=""){
										unlink($uploadpath.$settingDetailArr['Setting']['how_to_join_video']);
									}
									$saveArray['Setting']['how_to_join_video'] = $videoFileName;
								}else{
										$this->Session->setFlash('Please upload only flv or wmv files.');  
										$this->redirect(array('action' => "settingEditDetail/".$this->data['settings']['id']));
								}
											 
						   } else {
									$saveArray['Setting']['how_to_join_video'] = $settingDetailArr['Setting']['how_to_join_video'];
									$saveArray['Setting']['how_to_join_youtube'] = $this->data['settings']['how_to_join_youtube'];
						  
						  }

						  // for default image of zuni video
							if(!empty($this->data['settings']['how_to_join_img']['name'])){
								$ii++;
								
								$currentTimestamp = $this->common->getTimeStamp().$ii;
								
									$uploadpathimg =WWW_ROOT."img/videos/";
									$imgFileName = $currentTimestamp."_".$this->data['settings']['how_to_join_img']['name'];
									$imgDestination = $uploadpathimg.$imgFileName;
									move_uploaded_file($this->data['settings']['how_to_join_img']['tmp_name'], $imgDestination);
									if($settingDetailArr['Setting']['how_to_join_img']!=""){
										unlink($uploadpathimg.$settingDetailArr['Setting']['how_to_join_img']);
									}
									$saveArray['Setting']['how_to_join_img'] = $imgFileName;
						   } else {
									$saveArray['Setting']['how_to_join_img'] 	= $settingDetailArr['Setting']['how_to_join_img'];
						  }
				/************************************************************************************************************************************/ 
							if(empty($this->data['settings']['how_isho_work_video']['name']) and $this->data['settings']['how_ishop_work_youtube']!=''){

									$saveArray['Setting']['how_isho_work_video'] 	= '';
									$saveArray['Setting']['how_ishop_work_youtube'] = $this->data['settings']['how_ishop_work_youtube'];
						   } 
				
				/************************************************************************************************************************************/ 
				
							if(empty($this->data['settings']['what_is_dailydouble_video']['name']) and $this->data['settings']['what_is_dailydouble_youtube']!=''){

									$saveArray['Setting']['what_is_dailydouble_video'] 	= '';
									$saveArray['Setting']['what_is_dailydouble_youtube'] = $this->data['settings']['what_is_dailydouble_youtube'];
						   } 
				
				/************************************************************************************************************************************/ 
							if(empty($this->data['settings']['ishop_bucks_video']['name']) and $this->data['settings']['ishop_bucks_youtube']!=''){

									$saveArray['Setting']['ishop_bucks_video'] 	= '';
									$saveArray['Setting']['ishop_bucks_youtube'] = $this->data['settings']['ishop_bucks_youtube'];
						   } 
				
				/************************************************************************************************************************************/ 
							if(empty($this->data['settings']['how_to_join_video']['name']) and $this->data['settings']['how_to_join_youtube']!=''){

									$saveArray['Setting']['how_to_join_video'] 	= '';
									$saveArray['Setting']['how_to_join_youtube'] = $this->data['settings']['how_to_join_youtube'];
						   } 
				
				/************************************************************************************************************************************/
					if($this->data['settings']['header_img']['name']!='')
					{
							$type = explode(".",$this->data['settings']['header_img']['name']);							
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{      
								if(isset($this->data['settings']['default_header']) && $this->data['settings']['default_header']!='') {					
									unlink(APP.'webroot/img/header/'.$this->data['settings']['default_header']);    
								}								               
								$this->data['settings']['header_img']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['settings']['header_img']['name']);
								$docDestination = APP.'webroot/img/header/'.$this->data['settings']['header_img']['name'];								
								@chmod(APP.'webroot/img/header',0777);								
								move_uploaded_file($this->data['settings']['header_img']['tmp_name'], $docDestination) or die($docDestination);								
								$saveArray['Setting']['header_image'] = $this->data['settings']['header_img']['name'];								
							}	
					}
				/************************************************************************************************************************************/
					if($this->data['settings']['default_discount']['name']!='')	
					{
							$type = explode(".",$this->data['settings']['default_discount']['name']);							
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{      
								if(file_exists(WWW_ROOT.'/img/default/default_discount.jpg')) {	
									unlink(APP.'webroot/img/default/default_discount.jpg');    
								}
								$docDestination = APP.'webroot/img/default/default_discount.jpg';
								move_uploaded_file($this->data['settings']['default_discount']['tmp_name'], $docDestination) or die($docDestination);
							}	
					}
/************************************************************************************************************************************/
					if($this->data['settings']['default_deal']['name']!='')	
					{
							$type = explode(".",$this->data['settings']['default_deal']['name']);							
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{      
								if(file_exists(WWW_ROOT.'/img/default/default_deal.jpg')) {	
									unlink(APP.'webroot/img/default/default_deal.jpg');    
								}
								$docDestination = APP.'webroot/img/default/default_deal.jpg';
								move_uploaded_file($this->data['settings']['default_deal']['tmp_name'], $docDestination) or die($docDestination);
							}	
					}
/************************************************************************************************************************************/
					if($this->data['settings']['default_contest']['name']!='')	
					{
							$type = explode(".",$this->data['settings']['default_contest']['name']);							
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{      
								if(file_exists(WWW_ROOT.'/img/default/default_contest.jpg')) {
									unlink(APP.'webroot/img/default/default_contest.jpg');    
								}
								$docDestination = APP.'webroot/img/default/default_contest.jpg';
								move_uploaded_file($this->data['settings']['default_contest']['tmp_name'], $docDestination) or die($docDestination);
							}	
					}
				/************************************************************************************************************************************/
					if($this->data['settings']['discount_fb_image']['name']!='')
					{					
							$type = explode(".",$this->data['settings']['discount_fb_image']['name']);
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{
								if(file_exists(WWW_ROOT.'/img/fb/discount.jpg')) {
									unlink(APP.'webroot/img/fb/discount.jpg');    
								}
								$docDestination = APP.'webroot/img/fb/discount.jpg';
								move_uploaded_file($this->data['settings']['discount_fb_image']['tmp_name'], $docDestination) or die($docDestination);
							}	
					}																							
				/************************************************************************************************************************************/							

				/************************************************************************************************************************************/
					if($this->data['settings']['home_page_footer_image']['name']!='')
					{
							$type = explode(".",$this->data['settings']['home_page_footer_image']['name']);
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{
								if(file_exists(WWW_ROOT.'/img/header/my_home_page_footer.jpg')) {
									unlink(APP.'webroot/img/header/my_home_page_footer.jpg');    
								}
								$this->data['settings']['home_page_footer_image']['name']='my_home_page_footer.jpg';
								$docDestination = APP.'webroot/img/header/my_home_page_footer.jpg';
								@chmod(APP.'webroot/img/header',0777);
								move_uploaded_file($this->data['settings']['home_page_footer_image']['tmp_name'], $docDestination) or die($docDestination);
							}	
							
					}
					$saveArray['Setting']['home_page_footer_image']	= 'my_home_page_footer.jpg';																				
				/************************************************************************************************************************************/	
							$saveArray['Setting']['newsletter_from_email'] 	= $this->data['settings']['newsletter_from_email'];
							$saveArray['Setting']['newsletter_bottom'] 		= $this->data['settings']['newsletter_bottom'];
							$saveArray['Setting']['refer_friend_bucks'] 	= $this->data['settings']['refer_friend_bucks'];
							$saveArray['Setting']['refer_business_bucks']	= $this->data['settings']['refer_business_bucks'];
							$saveArray['Setting']['facebook_url'] 			= $this->data['settings']['facebook_url'];
							$saveArray['Setting']['twitter_url']			= $this->data['settings']['twitter_url'];
							$saveArray['Setting']['sales_email']			= $this->data['settings']['sales_email'];
							$saveArray['Setting']['admin_email']			= $this->data['settings']['admin_email'];
							$saveArray['Setting']['google_map_key']			= $this->data['settings']['google_map_key'];
							$saveArray['Setting']['exchange_rate']			= $this->data['settings']['exchange_rate'];
							$saveArray['Setting']['send_to_friend_subject']	= $this->data['settings']['send_to_friend_subject'];
							$saveArray['Setting']['send_to_frient_body']	= $this->data['settings']['send_to_frient_body'];
							$saveArray['Setting']['new_advertiser_subject']	= $this->data['settings']['new_advertiser_subject'];
							$saveArray['Setting']['new_advertiser_body']	= $this->data['settings']['new_advertiser_body'];
							$saveArray['Setting']['new_consumer_subject']	= $this->data['settings']['new_consumer_subject'];
							$saveArray['Setting']['new_consumer_body']		= $this->data['settings']['new_consumer_body'];
							$saveArray['Setting']['new_business_subject']	= $this->data['settings']['new_business_subject'];
							$saveArray['Setting']['new_business_body']		= $this->data['settings']['new_business_body'];
							$saveArray['Setting']['new_sent_proof_subject']	= $this->data['settings']['new_sent_proof_subject'];
							$saveArray['Setting']['new_sent_proof_body']	= $this->data['settings']['new_sent_proof_body'];
							$saveArray['Setting']['waiting_gift']			= $this->data['settings']['waiting_gift'];
							$saveArray['Setting']['news_unsub_sub']			= $this->data['settings']['news_unsub_sub'];
							$saveArray['Setting']['news_unsub_cont']		= $this->data['settings']['news_unsub_cont'];
							
							$saveArray['Setting']['home_page_footer_text']	= $this->data['settings']['home_page_footer_text'];
							$saveArray['Setting']['home_page_footer_box_status']	= $this->data['settings']['home_page_footer_box_status'];
							
							//$saveArray['Setting']['site_down']				= $this->data['settings']['site_down'];
							$saveArray['Setting']['consumer_spend_heading']	= $this->data['settings']['consumer_spend_heading'];
							$root_url = FULL_BASE_URL.router::url('/',false).'img/';
							$saveArray['Setting']['admin_consumer_body'] = str_replace('../../img/',$root_url,$this->data['settings']['admin_consumer_body']);
							$saveArray['Setting']['referral_body'] = str_replace('../../img/',$root_url,$this->data['settings']['referral_body']);
							
							$saveArray['Setting']['zuni_care_subject'] = str_replace('../../img/',$root_url,$this->data['settings']['zuni_care_subject']);
							$saveArray['Setting']['zuni_care_body'] = str_replace('../../img/',$root_url,$this->data['settings']['zuni_care_body']);
							
							$saveArray['Setting']['meta_keyword']			= $this->data['settings']['meta_keyword'];
							$saveArray['Setting']['meta_description']		= $this->data['settings']['meta_description'];
							
							$saveArray['Setting']['cat_meta_title']				= $this->data['settings']['cat_meta_title'];
							$saveArray['Setting']['cat_meta_keyword']			= $this->data['settings']['cat_meta_keyword'];
							$saveArray['Setting']['cat_meta_description']		= $this->data['settings']['cat_meta_description'];
							
							$saveArray['Setting']['catcity_meta_title']				= $this->data['settings']['catcity_meta_title'];
							$saveArray['Setting']['catcity_meta_keyword']			= $this->data['settings']['catcity_meta_keyword'];
							$saveArray['Setting']['catcity_meta_description']		= $this->data['settings']['catcity_meta_description'];
							
							$saveArray['Setting']['merchant_meta_title']			= $this->data['settings']['merchant_meta_title'];
							$saveArray['Setting']['merchant_meta_keyword']			= $this->data['settings']['merchant_meta_keyword'];
							$saveArray['Setting']['merchant_meta_description']		= $this->data['settings']['merchant_meta_description'];
							
							$saveArray['Setting']['password_subject']		= $this->data['settings']['password_subject'];
							$saveArray['Setting']['password_body']			= $this->data['settings']['password_body'];
							
							$saveArray['Setting']['contract_subject']		= $this->data['settings']['contract_subject'];
							$saveArray['Setting']['contract_body']			= $this->data['settings']['contract_body'];
							
							$saveArray['Setting']['discount_link_subject']	= $this->data['settings']['discount_link_subject'];
							$saveArray['Setting']['discount_link_body']		= $this->data['settings']['discount_link_body'];
							
							$saveArray['Setting']['member_subject']		= $this->data['settings']['member_subject'];
							$saveArray['Setting']['member_body']		= $this->data['settings']['member_body'];
							$saveArray['Setting']['contract_duration']	= $this->data['settings']['contract_duration'];
							
							$saveArray['Setting']['offer_title']	= $this->data['settings']['offer_title'];
							$saveArray['Setting']['offer_content']	= $this->data['settings']['offer_content'];
							$saveArray['Setting']['offer_email_footer_text']	= $this->data['settings']['offer_email_footer_text'];
							if(trim($this->data['settings']['master_password'])!='') {
								$saveArray['Setting']['master_pswd']	= $this->data['settings']['master_password'];
							}
							
							$this->Setting->save($saveArray);
							
							/*if(trim($this->data['settings']['master_password'])!='') {
								$this->loadModel('FrontUser');
								$this->FrontUser->query("UPDATE front_users SET master_password='".trim($this->data['settings']['master_password'])."' WHERE user_type='advertiser'");
							}*/
							
							$this->Session->setFlash('Your data has been updated successfully.');
							$this->redirect(array('action' => "settingEditDetail/".$this->data['settings']['id']));
				  } else {
								/*setting error message if validation fails*/
								$errors = $this->Category->invalidFields();	
								$this->Session->setFlash(implode('<br>', $errors));  
								$this->redirect(array('action' => "settingEditDetail/".$this->data['settings']['id']));							
				  }			  
	    }	
    /*
	    this function is checking username and pasword in database
	    and if true then redirect to home page
	*/
	   function beforeFilter() { 
	 
             $this->Auth->fields = array(
             'username' => 'username', 
             'password' => 'password'
            );
			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');			
   	    }
	 function paging_record($val) {
	 		$this->autoRender = false;
	 		$myFile = 'settings.php';
			$fh = fopen($myFile, 'w') or die("can't open file");
	 		$stringData = '<?php ';			
			$stringData .= "define('PER_PAGE_RECORD', '".$val."');";
			$stringData .= '?>';
			fwrite($fh, $stringData);
			fclose($fh);
	 }
	 //----------------------------------//
	 function imageSize() {	 	
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
/*------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	//Set css for different color options
    function setCss($id)  {
			$this->Cookie->delete('css_name');
			if($this->params['pass'][0]=='0'){
			   $this->Cookie->write('css_name','theme',false);
			   $this->redirect(array('action' => $this->params['pass'][1]));
			}else{
			   $this->Cookie->write('css_name','theme'.$this->params['pass'][0],false);
			   $this->redirect(array('action' => $this->params['pass'][1]));
		    }
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
?>