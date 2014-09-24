<?php 
/*
   Coder: Abhimanyu
   Date  : 08 Dec 2010
*/ 

class VipOffersController extends AppController { 
      var $name = 'VipOffers';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('common','Cookie','Auth','Email','RequestHandler','emailhtml','Session');
	  
	  function index()
	  {
			$this->set('currentAdmin', $this->Auth->user());
			$this->set('adv_profile_id', $this->params['pass'][0]);
			$adverName = $this->VipOffer->query("SELECT id,name,company_name FROM advertiser_profiles WHERE id ='".$this->params['pass'][0]."'");
			$this->set('adverName', $adverName[0]['advertiser_profiles']['name']);
			$this->set('adverCompany', $adverName[0]['advertiser_profiles']['company_name']);
			$this->set('adverId', $adverName[0]['advertiser_profiles']['id']);
			$condition =array('VipOffer.advertiser_profile_id '=>$this->params['pass'][0]);
			$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('VipOffer.id' => 'asc'));
			$data = $this->paginate('VipOffer', $condition);
			$this->set('totalVipOffers', count($data));
		    $this->set('offers', $data);
   		    $this->set('advertiser_id',$this->params['pass'][0]);//for element use only
	  }
	  
	 /*----------------------------------------this function set all data to view(searching and sorting)-------------------------------------*/
	 
	 
	 function vipOfferCommon()
	  {	
	  		$this->set('title_for_layout','Vip Offer Common');
			$this->set('search_text','Title');
			$this->set('s_date','');
			$this->set('e_date','');
			$this->set('category', 'Category');
			$this->set('advertiser_profile_id', 'Advertiser');
			$this->set('Categorys',$this->common->getAllCategory()); //list categories
			$this->set('categoryList',$this->common->getAllCategory()); //list categories
			$this->set('advertiserList',$this->common->getAllAdvertiserProfile()); //  List advertisers
			$this->set('companyList',$this->common->getAllCompanyName()); //  List company name
			#Getting detail of current logged in user
			$this->set('currentAdmin', $this->Auth->user());
			#making condition to filter data and getting data with pagging and assigning returning data array in a variable
			$cond='';
			$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('VipOffer.id' => 'asc'));
//if advertiser is set					
		if((isset($this->data['vip_offers']['advertiser_profile_id']) and $this->data['vip_offers']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['vip_offers']['advertiser_profile_id']) and $this->data['vip_offers']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['vip_offers']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'] ;
			}else{
			  
			  $advertiser_profile_id ="";
			}
			
			$this->set('advertiser_profile_id',$advertiser_profile_id); 
		}
		
//if category is set					
		if((isset($this->data['vip_offers']['category']) and $this->data['vip_offers']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
			if((isset($this->data['vip_offers']['category']) and $this->data['vip_offers']['category'] != 0))
			{
			 $category = $this->data['vip_offers']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
//if status/publish is set					
		if((isset($this->data['vip_offers']['publish']) and $this->data['vip_offers']['publish'] != '')|| ( isset($this->params['named']['publish']) and $this->params['named']['publish'] !='')){
		
			if((isset($this->data['vip_offers']['publish']) and $this->data['vip_offers']['publish'] != ''))
			{
			 $publish = $this->data['vip_offers']['publish'] ;
			}
            else if( (isset($this->params['named']['publish'])) and $this->params['named']['publish'] !=''){
             $publish = $this->params['named']['publish'] ;
			}else{
			$publish = '';
			}
			$this->set('publish',$publish); 
		}
		
//if title is set					
		if((isset($this->data['vip_offers']['search_text']) and ($this->data['vip_offers']['search_text'] != '' and $this->data['vip_offers']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['vip_offers']['search_text']) and ($this->data['vip_offers']['search_text'] != '' and $this->data['vip_offers']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['vip_offers']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
//if start date is set							
		if((isset($this->data['vip_offers']['s_date']) and $this->data['vip_offers']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['vip_offers']['s_date']) and $this->data['vip_offers']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['vip_offers']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['vip_offers']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}
			else if( (isset($this->params['named']['s_date'])) and $this->params['named']['s_date'] !=''){
			 
			  $arrS_date = explode("-",$this->params['named']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			 
			 $s_date = $this->params['named']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}else{
			 $s_date ="";
			}
			
			$this->set('s_date',$s_date);
			
			$this->set('s_datetmsp',$s_datetmsp);
			
		
		}
		
//if Expiration date is set					
		if((isset($this->data['vip_offers']['e_date']) and $this->data['vip_offers']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['vip_offers']['e_date']) and $this->data['vip_offers']['e_date'] != ''))
			{
			
			  $arrE_date = explode("/",$this->data['vip_offers']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['vip_offers']['e_date'] ;
			  $e_date = str_replace("/","-",$e_date);
			  $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			}
			else if( (isset($this->params['named']['e_date'])) and $this->params['named']['e_date'] !=''){
			 
			  $arrE_date = explode("-",$this->params['named']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			 
			 $e_date = $this->params['named']['e_date'] ;
			 $e_date = str_replace("/","-",$e_date);
			 $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			}else{
			 $e_date ="";
			}
			
			$this->set('e_date',$e_date);
			$this->set('e_datetmsp',$e_datetmsp);
		
		}
		
	//filteration is done on the basis of these fields	
		if(isset($advertiser_profile_id) && $advertiser_profile_id !=''){
		 $cond['VipOffer.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($category) && $category !=''){
		 $cond['VipOffer.category LIKE '] = '%'.$category.'%';
		}
		if(isset($publish) && $publish !=''){
		 $cond['VipOffer.status'] = $publish;
		}
		if(isset($search_text) && $search_text !=''){
		 $cond['VipOffer.title LIKE'] = '%'.$search_text. '%';
		}
		
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['VipOffer.offer_start_date >='] = $s_datetmsp ;
		  $cond['VipOffer.offer_expiry_date <='] = $e_datetmsp ;
		}
		
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){
		
		   $cond['VipOffer.offer_start_date ='] = $s_datetmsp ;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['VipOffer.offer_expiry_date ='] = $e_datetmsp ;
		}

			
			$data = $this->paginate('VipOffer', $cond);
			#counting array length to get number of records to show or hide add new saving offer link
			#admin or sales person can insert max 5 saving offers after that link will be hide automatically.
			$this->set('totalVipOffers', count($data));
		    $this->set('vip_offers', $data);
			
   
	 
	  }
	 /*-------------------------------------------------------------------------------------------------------------------------------------*/	 
	 
	 
	  
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
	 /*-----------------------------Add/edit OFFER---------------------------------------*/
	 function addNewOffer($id=null)
	 {
	   $currentAdmin =  $this->Auth->user();
	   if(isset($this->params['pass'][0])){
	   		$this->set('adv_profile_id', $this->params['pass'][0]);
		}
	   if(isset($this->params['pass'][0])){
			$this->set('advertiser_id', $this->params['pass'][0]);	//for element use only
		}
if($this->Session->read('referer')!='/vip_offers/vipOfferCommon'){ 
			$this->Session->write('referer',$this->referer());
		}
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		   }	
		$this->set('refferer',$this->Session->read('referer'));
	  	//$this->set('advertiserList',$this->common->getAllAdvertiserProfile()); //  List Advertisers
	   $this->set('advertiserList',$this->common->getAdvertiserProfilesForVip()); //  List advertisers
	   
	   if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='')
	   $this->set('Categorys',$this->common->getAllCategoryVip($this->params['pass'][0]));// List the advertiser selected categories    
	   
	   if(isset($this->data))
	   {
			$this->VipOffer->set($this->data);
			if($this->data['VipOffer']['advertiser_profile_id']){
	   			//$this->set('adv_profile_id', $this->data['VipOffer']['advertiser_profile_id']);
	         }

				  if($this->VipOffer->validates()){ 
				   				   
					/*-------validation for subcategory-----------------*/
					/*	if($this->data['VipOffer']['subcategory'][0]==0)			
						{
							$this->Session->setFlash("Please select atleast one subactegory"); 
							return false;
						}*/		
					
					#Here we are find out the category and subcategory of advertiser
					App::import('model','AdvertiserProfile');
					$this->AdvertiserProfile = new AdvertiserProfile();
					$cat_subcat = $this->AdvertiserProfile->find('all',array('fields'=>array('county'),'conditions'=>array('AdvertiserProfile.id'=>$this->data['VipOffer']['advertiser_profile_id']))); 
					$this->data['VipOffer']['advertiser_county_id'] = $cat_subcat[0]['AdvertiserProfile']['county'];					
					//$this->data['VipOffer']['category'] = $this->data['vip_offers']['category'];
					$this->data['VipOffer']['subcategory'] = '';

					/*--------------------------------------------------------------------------*/	 					
					
					if(!empty($this->data['VipOffer']['offer_start_date']))
					{
						$s_date		= $this->data['VipOffer']['offer_start_date'];
						$start_date	= explode('/',$s_date);
						$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
					}
					else
					{
					 	$start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
					}

					if(!empty($this->data['VipOffer']['offer_expiry_date']))
					{
						$e_date		= $this->data['VipOffer']['offer_expiry_date'];
						$expiry_date	= explode('/',$e_date);
						$expiry_date = mktime(0,0,0,$expiry_date[0],$expiry_date[1],$expiry_date[2]);
					}
					else
					{
					 	$expiry_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
					}
					
					
						/***********--------------------------valid date validation ------------------------------------------------------*/
						
						if($expiry_date < $start_date )
						{
							$this->Session->setFlash('Offer End Date should be greater than or equal to Start Date');  
							return false;						
						}
						
						/*----------------------------------------------------------------------------------------------------------------*/					

			 		/*------------------------------------find booked start date for same county-------------------------------*/

					$county = $this->AdvertiserProfile->find('all',array('fields'=>array('county'),'conditions'=>array('AdvertiserProfile.id'=>$this->data['VipOffer']['advertiser_profile_id'])));
					
					if($start=$this->VipOffer->query("select title from vip_offers where '".$start_date."' between offer_start_date and offer_expiry_date and advertiser_county_id='".$county[0]['AdvertiserProfile']['county']."' and id != '".$id."' and id!=null"))
					{
							$this->Session->setFlash('This vip offer start date range is booked'); 
							return false;
					}							
					else
					{
							$this->data['VipOffer']['offer_start_date']= $start_date;
					}

			 		/*-------------------------------------------------------------------*/
					

			 		/*------------------------------------find booked expiration date for same county-------------------------------*/

					$county = $this->AdvertiserProfile->find('all',array('fields'=>array('county'),'conditions'=>array('AdvertiserProfile.id'=>$this->data['VipOffer']['advertiser_profile_id'])));
					
					if($start=$this->VipOffer->query("select title from vip_offers where '".$expiry_date."' between offer_start_date and offer_expiry_date and advertiser_county_id='".$county[0]['AdvertiserProfile']['county']."' and id != '".$id."' and id!=null"))
					{
							$this->Session->setFlash('This vip offer expiry date range is booked'); 
							return false;
					}							
					else
					{
							$this->data['VipOffer']['offer_expiry_date']= $expiry_date;
					
					}

			 		/*-------------------------------------------------------------------*/

					
					if(isset($this->data['VipOffer']['id']) and $this->data['VipOffer']['id']!=''){
						$this->data['VipOffer']['id'] =  $this->data['VipOffer']['id'];
					}
				
					if(isset($this->data['VipOffer']['id'])){
						$imageOld = $this->VipOffer->query("SELECT offer_image_small FROM vip_offers WHERE id ='".$this->data['VipOffer']['id']."'");
					}

					if(isset($this->data['VipOffer']['offer_image_small']['name']) and $this->data['VipOffer']['offer_image_small']['name']!=""){
					
					$type = $this->data['VipOffer']['offer_image_small']['type'];
					if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif"){
						if(isset($imageOld[0]['vip_offers']['offer_image_small'])){
						  @unlink(APP.'webroot/img/offer/voffers/'.$imageOld[0]['vip_offers']['offer_image_small']);
						}
						

						$this->data['VipOffer']['offer_image_small']['name'] = $this->common->getTimeStamp()."_".$this->data['VipOffer']['advertiser_profile_id']."_".str_replace(' ','-',$this->data['VipOffer']['offer_image_small']['name']);
						$docDestination = APP.'webroot/img/offer/voffers/'.$this->data['VipOffer']['offer_image_small']['name']; 
						@chmod(APP.'webroot/img/offer/voffers',0777);
						move_uploaded_file($this->data['VipOffer']['offer_image_small']['tmp_name'], $docDestination) or die($docDestination);
						$this->data['VipOffer']['offer_image_small'] = $this->data['VipOffer']['offer_image_small']['name'];
						
					}else{
						$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
					}	

				}else{   
				        if(isset($imageOld[0]['vip_offers']['offer_image_small'])){
						  	$this->data['VipOffer']['offer_image_small'] = $imageOld[0]['vip_offers']['offer_image_small'];
						  }else{
				        	 $this->data['VipOffer']['offer_image_small'] = '';
				          }
			
				  }
				if(isset($this->data['VipOffer']['advertiser_profile_id'])) {
					  	$this->data['VipOffer']['advertiser_profile_id'] = $this->data['VipOffer']['advertiser_profile_id'];
					  }
					 																			
					
					$this->VipOffer->saveAll($this->data);
					$VipOffer_id = $this->VipOffer->getLastInsertId();
					//aftre getting last inserted id for advertiser table we are inserting in work order table
						  App::import('model', 'WorkOrder');
						  $this->WorkOrder = new WorkOrder;
						  
						  $orderid = $this->common->getOrderId($this->data['VipOffer']['advertiser_profile_id']);
						  
					 $saveWorkArray = array();	  
					if(strpos($this->referer(),'offerEditDetail')) {
						  $saveWorkArray['WorkOrder']['subject']   					=  'Update to VIP Offer';
						  $saveWorkArray['WorkOrder']['message']   					=  'Update to VIP Offer for the following advertiser profile.';
						  $saveWorkArray['WorkOrder']['type']   					=  'VIP Offer Workorder Update';	
						   $VipOffer_id												=  $this->data['VipOffer']['id'];					
					}else{
						  $saveWorkArray['WorkOrder']['subject']   					=  'New VIP Offer';
						  $saveWorkArray['WorkOrder']['message']   					=  'New VIP Offer has been launched for the following advertiser profile.';
						  $saveWorkArray['WorkOrder']['type']   					=  'VIP Offer Workorder';	
						 
					}
						 
						  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $orderid['AdvertiserProfile']['order_id'];
						  $saveWorkArray['WorkOrder']['read_status']   				=  0;

						  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
						  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
						  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');						  
						  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can edit this VIP Offer or check all other VIP Offers for this advertiser in Advertiser profiles section and pulish them. Please follow below url:<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'vip_offers/offerEditDetail/'.$VipOffer_id.'/'.$this->data['VipOffer']['advertiser_profile_id'].'" style="text-decoration:underline;" target="_blank">Edit New VIP Offer</a><br /><br />OR<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'vip_offers/index/'.$this->data['VipOffer']['advertiser_profile_id'].'" style="text-decoration:underline;" target="_blank">VIP Offers Listing</a>';
		
						  date_default_timezone_set('US/Eastern');
						  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
						  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
						  $saveWorkArray['WorkOrder']['salseperson_id']   			=  $this->common->salesIdForAdvertiser($this->data['VipOffer']['advertiser_profile_id']);
						  $this->WorkOrder->save($saveWorkArray);						  
						  
						  
						 
					if(isset($this->data['VipOffer']['id'])){
					        $this->Session->setFlash('Vip Offer with id: '.$this->data['VipOffer']['id'].' has been updated successfully.');  
					 }else{
							$this->Session->setFlash('Vip Offer has been submitted successfully.');  
					}
					  if(isset($this->data['VipOffer']['prvs_link']) && (strpos($this->data['VipOffer']['prvs_link'],'masterSheet')!=false)) {
					  		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['VipOffer']['prvs_link']);			
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
							}else {	
														
							if(strpos($this->data['VipOffer']['refferer'],'vip_offers/vipOfferCommon')!==false){
						  		$this->redirect(array('action' => "vipOfferCommon")); 
						  	}
						  	else{
						  		$this->redirect(array('action' => "index/".$this->data['VipOffer']['advertiser_profile_id'])); 
						  	}
							}
							
							
                   		  }else{
							$errors = $this->VipOffer->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							if(isset($this->data['VipOffer']['id'])){
							$this->redirect(array('action' => "offerEditDetail/".$this->data['VipOffer']['id'].'/'.$this->data['VipOffer']['advertiser_profile_id'])); 
							}
				   		}			   
					}
	 			}	 
	 function offerEditDetail($id=null)
		{
	       $this->set('Offer',$this->VipOffer->offerEditDetail($id));
		  // pr($this->VipOffer->offerEditDetail($id));
		   			 //$this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
		  	$this->set('advertiserList',$this->common->getAllAdvertiserProfile()); //  List Advertisers
		if(isset($this->params['pass'][1]) || $this->data['VipOffer']['advertiser_profile_id']){
			$ad_id =  (isset($this->params['pass'][1])) ? $this->params['pass'][1] : $this->data['VipOffer']['advertiser_profile_id'];
	   		$this->set('advertiser_id', $ad_id);
		} //for shortcut purpose because advertiser id is set as this name in element

		if($this->Session->read('referer')!='/vip_offers/vipOfferCommon'){ 
			$this->Session->write('referer',$this->referer());	
		}		
		$this->set('refferer',$this->Session->read('referer'));
		  
		  if((strpos($this->referer(),'masterSheet')!=false)) {
		  	$this->Session->write('reff',$this->referer());
		  }
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		   }
 	   if(isset($this->params['pass'][1]) && $this->params['pass'][1]!='')
	   $this->set('Categorys',$this->common->getAllCategoryVip($this->params['pass'][1])); 
		}	 
	 /*------------------------------Function to Delete Offer------------------------------------*/
		function offerDelete($id,$pass=null,$chk=null) {

			$this->VipOffer->id = $id;			
			$imageOld = $this->VipOffer->query("SELECT offer_image_small FROM vip_offers WHERE id =".$id.";");
			if($imageOld[0]['vip_offers']['offer_image_small']!=''){
				@unlink(APP.'webroot/img/offer/voffers/'.$imageOld[0]['vip_offers']['offer_image_small']);
			}			
			$this->VipOffer->delete($id);
			$this->Session->setFlash('Vip Offer with id: '.$id.' has been deleted.');
			$this->redirect($this->referer());
		}
	function check_home($id) {
		$this->autoRender = false;
		echo $this->common->vipPerm($id);
	}		
//------------------------------------------------------------------------

function vipSavingOffer($id) {
	if($this->Session->read('Auth.FrontUser')) {
		$this->layout = false;
		$this->id = $id;
		$offer = $this->VipOffer->read();
		$this->set('offer',$offer);
	} else {
		$this->layout = false;
		$this->render('/errors/url_error');
	}
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
/*------------------------------------------------------------------------------------------------------------------------*/ 		
	function getAdvertiserCats($id=0) {
		$this->autoRender = false;
		echo $this->common->advertiserCatCombo($id,'VipOffer','category');
	}
/*------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	 /*
    	this function is applying images and link header and footer layout
	*/
	function beforeFilter() { 

        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );
			$this->Auth->allow('vipSavingOffer');
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