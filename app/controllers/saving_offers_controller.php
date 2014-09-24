<?php 
/*
   Coder: anoop sharma
   Date  : 20 April 2011
*/ 
class SavingOffersController extends AppController {
      var $name = 'SavingOffers';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax');
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('common','Cookie','Auth','Email','RequestHandler','emailhtml','Session');
	  
	  function index()
	  {
			#Getting detail of current logged in user
			$this->set('currentAdmin', $this->Auth->user());
			#Getting advertiser id from url and then setting that value in a variable 'adv_profile_id'
			$this->set('adv_profile_id', $this->params['pass'][0]);
			#Getting id , name, comnay name of a perticualr advertiser and than setting these 3 values
			#in three different variables for use in ctp file
			$adverName = $this->SavingOffer->query("SELECT id,name,company_name FROM advertiser_profiles WHERE id ='".$this->params['pass'][0]."'");
			$this->set('adverName', $adverName[0]['advertiser_profiles']['name']);
			$this->set('adverCompany', $adverName[0]['advertiser_profiles']['company_name']);
			$this->set('adverId', $adverName[0]['advertiser_profiles']['id']);
			#making condition to filter data and getting data with pagging and assigning returning data array in a variable
			$condition =array('SavingOffer.advertiser_profile_id '=>$this->params['pass'][0],'SavingOffer.live'=>1);
			$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('SavingOffer.id' => 'DESC'));
			$data = $this->paginate('SavingOffer', $condition);
			#counting array length to get number of records to show or hide add new saving offer link
			#admin or sales person can insert max 5 saving offers after that link will be hide automatically.
			$this->set('totalSavingOffers', count($data));
		    $this->set('offers', $data);
   		    $this->set('advertiser_id',$this->params['pass'][0]);//for element use only			
	  }	  
	  /*-----------------This function set all saving offer to view---------------------------------------------------------*/
	  function savingOfferCommon()
	  {
	  		$this->set('title_for_layout','Saving Offer Common');
			$this->set('search_text','Title');
			$this->set('s_date','');
			$this->set('e_date','');
			$this->set('category', 'Category');
			$this->set('advertiser_profile_id', 'Advertiser');
			$this->set('categoryList',$this->common->getAllCategory()); //list categories
			$this->set('advertiserList',$this->common->getAllAdvertiserProfile()); //  List advertisers
			#Getting detail of current logged in user
			$this->set('currentAdmin', $this->Auth->user());
			#making condition to filter data and getting data with pagging and assigning returning data array in a variable
			$cond='';
			$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('SavingOffer.id' => 'DESC'));
			
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		
		if((isset($this->data['saving_offers']['advertiser_profile_id']) and $this->data['saving_offers']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['saving_offers']['advertiser_profile_id']) and $this->data['saving_offers']['advertiser_profile_id'] != ''))
			{
			 	$advertiser_profile_id = $this->data['saving_offers']['advertiser_profile_id'];
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
				 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'];
			} else {
			  $advertiser_profile_id ="";
			}
			$this->set('advertiser_profile_id',$advertiser_profile_id);
		}
		if((isset($this->data['saving_offers']['category']) and $this->data['saving_offers']['category'] != 0)|| (isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
			if((isset($this->data['saving_offers']['category']) and $this->data['saving_offers']['category'] != 0))
			{
				$category = $this->data['saving_offers']['category'];
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             	$category = $this->params['named']['category'];
			} else {
				$category = '';
			}
			$this->set('category',$category);
		}
		
		
		if((isset($this->data['saving_offers']['publish']) and $this->data['saving_offers']['publish'] != '')|| ( isset($this->params['named']['publish']) and $this->params['named']['publish'] !='')){
		
			if((isset($this->data['saving_offers']['publish']) and $this->data['saving_offers']['publish'] != ''))
			{
			 	$publish = $this->data['saving_offers']['publish'] ;
			}
            else if((isset($this->params['named']['publish'])) and $this->params['named']['publish'] !=''){
             	$publish = $this->params['named']['publish'];
			}else{
				$publish = '';
			}
			$this->set('publish',$publish); 
		}
		
		
		if((isset($this->data['saving_offers']['search_text']) and ($this->data['saving_offers']['search_text'] != '' and $this->data['saving_offers']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['saving_offers']['search_text']) and ($this->data['saving_offers']['search_text'] != '' and $this->data['saving_offers']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['saving_offers']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
		
		if((isset($this->data['saving_offers']['s_date']) and $this->data['saving_offers']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['saving_offers']['s_date']) and $this->data['saving_offers']['s_date'] != 0))
			{			
			  $arrS_date = explode("/",$this->data['saving_offers']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['saving_offers']['s_date'] ;
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
			 $s_datetmsp  = mktime(0,0,0,$day,$month,$year);
			}else{
			 $s_date ="";
			}
			//echo $s_date;
			//exit;
			$this->set('s_date',$s_date);
			
			$this->set('s_datetmsp',$s_datetmsp);
			
		
		}
		
		
		if((isset($this->data['saving_offers']['e_date']) and $this->data['saving_offers']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['saving_offers']['e_date']) and $this->data['saving_offers']['e_date'] != ''))
			{
			  $arrE_date = explode("/",$this->data['saving_offers']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['saving_offers']['e_date'];
			  $e_date = str_replace("/","-",$e_date);
			  $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			} else if((isset($this->params['named']['e_date'])) and $this->params['named']['e_date'] !=''){
				 $arrE_date = explode("-",$this->params['named']['e_date']);
				 $eday = $arrE_date[1];
				 $emonth = $arrE_date[0];
				 $eyear = $arrE_date[2];
				 $e_date = $this->params['named']['e_date'];
				 $e_date = str_replace("/","-",$e_date);
				 $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			} else {
			 	$e_date ="";
			}
			$this->set('e_date',$e_date);
			$this->set('e_datetmsp',$e_datetmsp);
		}
		if(isset($advertiser_profile_id) && $advertiser_profile_id !=''){
		 $cond['SavingOffer.advertiser_profile_id'] = $advertiser_profile_id;
		}
		if(isset($category) && $category !=''){
		 $advertisers = $this->common->advertiserByCat($category);
		 $cond[] = "SavingOffer.advertiser_profile_id IN (".implode(',',$advertisers).")";
		}
		if(isset($publish) && $publish !=''){
		 $cond['SavingOffer.status'] = $publish;
		}
		if(isset($search_text) && $search_text !=''){
		 $cond['SavingOffer.title LIKE'] = '%'.$search_text. '%';
		}
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['SavingOffer.offer_start_date >='] = $s_datetmsp ;
		  $cond['SavingOffer.offer_expiry_date <='] = $e_datetmsp ;
		}
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){
		
		   $cond['SavingOffer.offer_start_date ='] = $s_datetmsp;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['SavingOffer.offer_expiry_date ='] = $e_datetmsp ;
		}
		$cond['SavingOffer.live ='] = 1;
		$data = $this->paginate('SavingOffer', $cond);
		$this->set('saving_offers', $data);
   }
	 /*
		 we have admin theme in 5 different colors so this function is checking which color user 
		 wants and then assigning that color in cookie for further use
	 */
	 function setCss($id)
	 {
			$this->Cookie->delete('css_name');
			if($this->params['pass'][0]=='0') {
			   $this->Cookie->write('css_name','theme',false);
			   $this->redirect(array('action' => $this->params['pass'][1]));
			} else {
			   $this->Cookie->write('css_name','theme'.$this->params['pass'][0],false);
			   $this->redirect(array('action' => $this->params['pass'][1]));
		    }
	 }
	/*----to create ajax category list-----------*/
	function selectedCatList(){
	
		if(isset($this->data['SavingOffer']['advertiser_profile_id'])&& $this->data['SavingOffer']['advertiser_profile_id'] !=''){
			$adv_id=$this->data['SavingOffer']['advertiser_profile_id'];
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
		}
		 
	 /*-----------------------------Add/edit OFFER---------------------------------------*/
	 function addNewOffer($id=null)
	 {
	   $currentAdmin =  $this->Auth->user();
	   if(isset($this->params['pass'][0])){
	   		$this->set('adv_profile_id', $this->params['pass'][0]);
		}
		if($id!=null)
		{
			
			if(isset($this->params['pass'][0]) || $this->data['SavingOffer']['advertiser_profile_id'])
			{
				$ad_id =  (isset($this->params['pass'][0])) ? $this->params['pass'][0] : $this->data['SavingOffer']['advertiser_profile_id'];
		   		$this->set('advertiser_id', $ad_id);			
			}
		}
		else
		{
		   if(isset($this->params['pass'][0]))
			{
				$this->set('advertiser_id', $this->params['pass'][0]);//for shortcut purpose because advertiser id is set as this name in element
			}
		}
if($this->Session->read('referer')!='/saving_offers/savingOfferCommon'){ 
			$this->Session->write('referer',$this->referer());
		}
		$this->set('refferer',$this->Session->read('referer'));
		$this->set('reff',$this->referer());
		$this->set('advertiserList',$this->common->getAdvertiserProfilesForSaving()); //  List Advertisers
		
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
  
	   if(isset($this->data))
	   {
			$this->SavingOffer->set($this->data);
			 		
					$allowEmpty = 0;
					if($this->common->groupName($this->Session->read('Auth.Admin.user_group_id'))=='Salesperson' && in_array('offerEditDetail',explode('/',$this->referer()))) {
						$allowEmpty = 1;
					}
				  if($this->SavingOffer->validates() || $allowEmpty){
				  $errors='';
				  
					if(!isset($this->data['SavingOffer']['show_at_home'])) {
						$this->data['SavingOffer']['show_at_home'] = 0;
					}

						$cate_arr[0] = '';
						$cate_arr[1] = '';
					//}
					
					$soffer_type = ($this->data['SavingOffer']['saving_offer']=='current_saving_offer') ? 1:0;
					
					$saving0ffer_id = (isset($this->data['SavingOffer']['id'])) ? $this->data['SavingOffer']['id'] : 0;					
					
					//$validate_date = $this->hangover_2($this->data['SavingOffer']['advertiser_profile_id'],$this->data['SavingOffer']['offer_start_date'],$this->data['SavingOffer']['offer_expiry_date'],$soffer_type,$cate_arr[0],$cate_arr[1],$saving0ffer_id,$this->data['SavingOffer']['show_at_home'],$this->data['SavingOffer']['show_at_category'],$this->data['SavingOffer']['top_ten_status']);
					
					/*echo $validate_date;
					exit;*/
					/*if($validate_date!='') {
						$errors[] = $validate_date;
					}*/
					
					if(!empty($errors))	{
							$this->Session->setFlash(implode('<br>',$errors));
							return false;
					}
					#check for any existing main offer of the advertiser(because one advertiser may have one main offer)
					$cond=array('SavingOffer.advertiser_profile_id'=>$this->data['SavingOffer']['advertiser_profile_id'],'SavingOffer.current_saving_offer'=>1,'SavingOffer.id !='=>$id);
					if($exist_main_offer=$this->SavingOffer->find('all',array('fields'=>'current_saving_offer','conditions'=>$cond)) && !$allowEmpty)
					{
							if($this->data['SavingOffer']['saving_offer'] == 'other_saving_offer') 
							{
								$this->data['SavingOffer']['current_saving_offer'] = 0;
								$this->data['SavingOffer']['other_saving_offer'] = 1;										
							}
							else
							{
								$this->Session->setFlash("You already have a main offer, please select other offer."); 
								return false;
							}
					}
					else
					{
						if($this->data['SavingOffer']['saving_offer'] == 'current_saving_offer') 
						{
							$this->data['SavingOffer']['current_saving_offer'] = 1;
							$this->data['SavingOffer']['other_saving_offer'] = 0;										
						}
						else
						{
							$this->data['SavingOffer']['current_saving_offer'] = 0;
							$this->data['SavingOffer']['other_saving_offer'] = 1;
						}				
					}
					
					/*--------------top ten status validation----------------------*/
					if($this->data['SavingOffer']['saving_offer'] == 'current_saving_offer')
					{
						$this->data['SavingOffer']['top_ten_status'] =  $this->data['SavingOffer']['top_ten_status'];
					}
					else
					{
						$this->data['SavingOffer']['top_ten_status'] =  0;
					}
					/*--------------------------------------------------------------*/
					
					$this->data['SavingOffer']['disclaimer'] =  trim(str_replace('<br />','\n',nl2br($this->data['SavingOffer']['disclaimer'])));
					
					if(isset($this->data['SavingOffer']['not_valid_other'])) {
						$this->data['SavingOffer']['no_valid_other_offer'] = $this->data['SavingOffer']['not_valid_other'];
					}
					
					if(isset($this->data['SavingOffer']['n_transferable'])) {
						$this->data['SavingOffer']['no_transferable'] = $this->data['SavingOffer']['n_transferable'];
					}
					/*if(isset($this->data['SavingOffer']['other'])) {
						$this->data['SavingOffer']['other'] = $this->data['SavingOffer']['other'];
					}*/
					#Here we are find out the category and subcategory of advertiser
					App::import('model','AdvertiserProfile');
					$this->AdvertiserProfile = new AdvertiserProfile();
					$cat_subcat = $this->AdvertiserProfile->find('first',array('fields'=>array('county'),'conditions'=>array('AdvertiserProfile.id'=>$this->data['SavingOffer']['advertiser_profile_id'])));
					if(isset($cat_subcat['AdvertiserProfile']['county']))
					{
						$this->data['SavingOffer']['advertiser_county_id'] = $cat_subcat['AdvertiserProfile']['county'];
					}
					/*--------------------------------------------------------------------------*/	 
					
					#In code below we are converting start date and expiry date in timestamp to save in database
					#if admin not selecting any date then we are inserting current data in database
					if(!empty($this->data['SavingOffer']['offer_start_date']))
					{
						$s_date		= $this->data['SavingOffer']['offer_start_date'];
						$start_date	= explode('/',$s_date);
						$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
					}
					else
					{
					 	//$start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						$start_date = '';
					}
					
					#----------------------------------------------------------------------------------------------
					if(!empty($this->data['SavingOffer']['offer_expiry_date']))
					{
						$e_date		= $this->data['SavingOffer']['offer_expiry_date'];
						$expiry_date	= explode('/',$e_date);
						$expiry_date = mktime(0,0,0,$expiry_date[0],$expiry_date[1],$expiry_date[2]);
					}
					else
					{
					 	//$expiry_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						$expiry_date = '';
					}
					
					#----------------------------------------------------------------------------------------------
						/***********--------------------------valid date validation ------------------------------------------------------*/
						
						if($expiry_date < $start_date && $expiry_date!='' && $start_date!='')
						{
							$this->Session->setFlash('Offer End Date should be greater than or equal to Start Date');  
							return false;						
						}						
						/*----------------------------------------------------------------------------------------------------------------*/					
							$this->data['SavingOffer']['offer_start_date']= $start_date;
							$this->data['SavingOffer']['offer_expiry_date']= $expiry_date;
			   
					 /*-------------------------------------------------------------------*/		

					
					if(isset($this->data['SavingOffer']['id']) and $this->data['SavingOffer']['id']!=''){
						$this->data['SavingOffer']['id'] =  $this->data['SavingOffer']['id'];
					}
					else
					{
						$count_offer=$this->SavingOffer->find('all',array('conditions'=>array('advertiser_profile_id'=>$this->data['SavingOffer']['advertiser_profile_id'])));
						if(count($count_offer)>=5)
						{
							$this->Session->setFlash('You already have five saving offer, So delete anyone to add another');
					  if(isset($this->data['SavingOffer']['prvs_link']) && (strpos($this->data['SavingOffer']['prvs_link'],'masterSheet')!=false)) {
					  		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['SavingOffer']['prvs_link']);			
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
							}else {					  
					  	if(strpos($this->data['SavingOffer']['refferer'],'saving_offers/savingOfferCommon')!==false){
						  		$this->redirect(array('action' => "savingOfferCommon")); 
						  }
						 else{
						  		$this->redirect(array('action' => "index/".$this->data['SavingOffer']['advertiser_profile_id'])); 
						  }
						}					
					  }
					}				
					  
					#---------------------------------------------------------------------------------------------------------------------------#
					$this->SavingOffer->save($this->data,false);
					$saving_offer_id = $this->SavingOffer->getLastInsertId();
					 $saveWorkArray = array();
					if(isset($this->data['SavingOffer']['id']) && $this->data['SavingOffer']['id']!=''){
						  $saveWorkArray['WorkOrder']['subject']   					=  'Saving offer Update';
						  $saveWorkArray['WorkOrder']['message']   					=  'Saving offer has been updated recently by sales team.Details are below:';
						  $saveWorkArray['WorkOrder']['type']   					=  'savingworkorderupdate';
						  $saving_offer_id											=  $this->data['SavingOffer']['id'];
					}else{
						  $saveWorkArray['WorkOrder']['subject']   					=  'New saving offer';
						  $saveWorkArray['WorkOrder']['message']   					=  'A new saving offer has been placed recently by sales team.Details are below:';
						  $saveWorkArray['WorkOrder']['type']   					=  'savingworkorder';
					}											
						  #Insertimg one record in work order table to show this data in inbox of admin
						  App::import('model', 'WorkOrder');
						  $this->WorkOrder = new WorkOrder;
						  $advertiserProfileId = $this->WorkOrder->query("select id,order_id from advertiser_profiles where id='".$this->data['SavingOffer']['advertiser_profile_id']."'");
						 
						  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $advertiserProfileId[0]['advertiser_profiles']['order_id'];
						  $saveWorkArray['WorkOrder']['read_status']   				=  0;

						  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
						  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
						  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');						  
						  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can edit this saving offer or check all other offers for this advertiser in Advertiser profiles section and pulish them. Please follow below url:<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'saving_offers/offerEditDetail/'.$saving_offer_id.'/'.$this->data['SavingOffer']['advertiser_profile_id'].'" style="text-decoration:underline;" target="_blank">Edit New Saving offer</a><br /><br />OR<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'saving_offers/index/'.$this->data['SavingOffer']['advertiser_profile_id'].'" style="text-decoration:underline;" target="_blank">Saving offers Listing</a>';
						  date_default_timezone_set('US/Eastern');
						  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
						  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
						  $saveWorkArray['WorkOrder']['salseperson_id']   			=  $this->common->salesIdForAdvertiser($this->data['SavingOffer']['advertiser_profile_id']);
						  $this->WorkOrder->save($saveWorkArray);
						 
					
					  #setting flash message according to add or edit condition
					  if(isset($this->data['SavingOffer']['id'])){
					        $this->Session->setFlash('Your saving offer with id: '.$this->data['SavingOffer']['id'].' has been updated successfully.');  
					  }else{
							$this->Session->setFlash('Your saving offer has been submitted successfully.');
					  }
					  	if(isset($this->data['SavingOffer']['prvs_link']) && (strpos($this->data['SavingOffer']['prvs_link'],'masterSheet')!=false)) {
					  		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['SavingOffer']['prvs_link']);			
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
						} else {
					  	if(strpos($this->data['SavingOffer']['refferer'],'saving_offers/savingOfferCommon')!==false){
						  		$this->redirect(array('action' => "savingOfferCommon")); 
						}
						 else{
						  		$this->redirect(array('action' => "index/".$this->data['SavingOffer']['advertiser_profile_id'])); 
						  }
						}
                   
				   }else{
						$errors = $this->SavingOffer->invalidFields();
						$this->Session->setFlash(implode('<br>', $errors));  
						if(isset($this->data['SavingOffer']['id'])){
						$this->redirect(array('action' => "offerEditDetail/".$this->data['SavingOffer']['id'].'/'.$this->data['SavingOffer']['advertiser_profile_id'])); 
						}
				   }
					   
			}
	 }
	 
	 #Getting saving offer detail for a perticualr id to show on edit page
	 function offerEditDetail($id=null)
		{ 
		    $this->set('Offer',$this->SavingOffer->offerEditDetail($id));
			$this->set('categoryList',$this->common->getAllCategory()); //list categories
			 $this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
		  	$this->set('advertiserList',$this->common->getAllAdvertiserProfile()); //  List Advertisers
			if(isset($this->params['pass'][1]) || $this->data['SavingOffer']['advertiser_profile_id'])
			{
			 	$ad_id =  (isset($this->params['pass'][1])) ? $this->params['pass'][1] : $this->data['SavingOffer']['advertiser_profile_id'];
	   			$this->set('advertiser_id', $ad_id);
			}	 //for shortcut purpose because advertiser id is set as this name in element
			
			if($this->Session->read('referer')!='/saving_offers/savingOfferCommon')
			{ 
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
		   
		}		
		
	#function to duplicate saving offer to make vip offer
	function makeDuplicateVipOffer($id=null){
				if($id!=''){
					  $savingData = $this->SavingOffer->query("SELECT * FROM saving_offers WHERE id ='".$id."'");
					  App::import('model', 'VipOffer');
					  $this->VipOffer = new VipOffer;
					  $saveVipArray = array();
					  $saveVipArray['VipOffer']['title']   						=  $savingData[0]['saving_offers']['title'];
					  $saveVipArray['VipOffer']['advertiser_profile_id']   		=  $savingData[0]['saving_offers']['advertiser_profile_id'];
					  $saveVipArray['VipOffer']['price']   						=  $savingData[0]['saving_offers']['price'];
					  $saveVipArray['VipOffer']['description']   				=  $savingData[0]['saving_offers']['description'];
					  $saveVipArray['VipOffer']['status']   					=  'no';
					  $saveVipArray['VipOffer']['offer_start_date']   			=  $savingData[0]['saving_offers']['offer_start_date'];
					  $saveVipArray['VipOffer']['offer_expiry_date']   			=  $savingData[0]['saving_offers']['offer_expiry_date'];
					  $this->VipOffer->save($saveVipArray);
					  $this->Session->setFlash('Copy of this saving offer has been made successfully as a vip offer.'); 
					  $this->redirect(array('action' => "index/".$savingData[0]['saving_offers']['advertiser_profile_id'])); 
				}else{
					  $this->Session->setFlash('Sorry we are not able to make copy of this saving offer as a vip offer.'); 
					  $this->redirect(array('action' => "index/".$savingData[0]['saving_offers']['advertiser_profile_id'])); 
				
				}
		}
		
	 
	 #------------------------------Function to Delete saving Offer------------------------------------
		function offerDelete($id,$pass=null,$chk=null) {
			$this->SavingOffer->id = $id;
			$imageOld = $this->SavingOffer->query("SELECT offer_image_small,offer_image_big,advertiser_profile_id FROM saving_offers WHERE id =".$id.";");
			$myAdvertiserInfo=$this->common->getAdvertiserdetailswithOrder($imageOld[0]['saving_offers']['advertiser_profile_id']);  
			
			$this->SavingOffer->delete($id);
			
			
			//------------------------inbox notification------------//								
			  App::import('model', 'WorkOrder');
			  $this->WorkOrder = new WorkOrder;
			  $saveWorkArray = '';
			  $saveWorkArray['WorkOrder']['id']   						=  '';
			  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $myAdvertiserInfo['order_id'];
			  $saveWorkArray['WorkOrder']['read_status']   				=  0;
			  $saveWorkArray['WorkOrder']['subject']   					=  'Saving Offer Deleted';
			  $saveWorkArray['WorkOrder']['message']   					=  'A saving offer has been deleted recently.';
			  $saveWorkArray['WorkOrder']['type']   					=  'savingworkorderdeleted';
			  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
			  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
			  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
			  $saveWorkArray['WorkOrder']['bottom_line']   				=  '';
			  $saveWorkArray['WorkOrder']['salseperson_id'] 			=  $myAdvertiserInfo['creator'];
			  date_default_timezone_set('US/Eastern');
			  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
			  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
			  $saveWorkArray['WorkOrder']['bottom_line']   				=  'A saving offer with id:'.$id.' has been deleted recently. You can check all other offers for this advertiser in Advertiser profiles section and pulish them. Please follow below url:<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'saving_offers/index/'.$myAdvertiserInfo['id'].'" style="text-decoration:underline;" target="_blank">Saving offers Listing</a>';
			  $this->WorkOrder->save($saveWorkArray);
			  
			$this->Session->setFlash('Saving Offer with id: '.$id.' has been deleted.');
			$this->redirect($this->referer());			
		}
//------------------------------------------------this function used to delete saving offer from front end---------------------------------------------------//	
	function offerDeleteFront($id) {
		//Find all saving offers of the logged in advertiser
			$this->SavingOffer->id = $id;
			$this->SavingOffer->delete($id);
			$this->Session->setFlash('Offer Deleted Successfully');
			$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/offer/delete:success');

	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function checkofferFront($aid='',$sdate='',$edate='',$main_offer='',$offer_id='') {
	$this->autoRender = false;
		//Find all saving offers of the logged in advertiser
		
		$error_div1='';
			#if admin not selecting any date then we are inserting current data in database
					if($sdate!='')
					{
						$s_date		= $sdate;
						$start_date	= explode('_',$s_date);
						if(count($start_date)>=3) {
						$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
						}
					}
					else
					{
					 	$start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
					}					
					#----------------------------------------------------------------------------------------------
					if($edate!='')
					{
						$e_date		= $edate;
						$expiry_date	= explode('_',$e_date);
						if(count($expiry_date)>=3) {
						$expiry_date = mktime(0,0,0,$expiry_date[0],$expiry_date[1],$expiry_date[2]);
						}
					}
					else
					{
					 	$expiry_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
					}
					#-----------------------------------------------------------------------------------------------------------------#
					//date validation for valid date range
					/***********--------------------------valid date validation ------------------------------------------------------*/
					if($sdate!='' && $edate!='')
					{
						if($expiry_date < $start_date )
						{
							$error_div1 .= 'Offer End Date should be greater than or equal to Start Date.<br />';						
						}
					}
					/*----------------------------------------------------------------------------------------------------------------*/					
					//date validation for valid start date range
			 		/*------------------------------------find booked start date for same county and category-------------------------------*/
					//$this->loadModel('AdvertiserProfile');
					//$county = $this->AdvertiserProfile->find('all',array('fields'=>array('county','category'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
					
					/*if(!empty($county) && $sdate!=''){ 
					if($start=$this->SavingOffer->query("select title from saving_offers where '".$start_date."' between offer_start_date and offer_expiry_date and advertiser_county_id='".$county[0]['AdvertiserProfile']['county']."'"))
					{
							$error_div1 .= +'Offer Start Date is booked.<br />'; 
							
					}	
					}*/						
			   
			 		/*-------------------------------------------------------------------*/	
		
					/*----------------------------------------------------------------------------------------------------------------*/					
					//date validation for valid end date range
			 		/*------------------------------------find booked end date for same county and category-------------------------------*/
					/*if(!empty($county) && $edate!=''){ 
					if($expiry=$this->SavingOffer->query("select title from saving_offers where '".$expiry_date."' between offer_start_date and offer_expiry_date and advertiser_county_id='".$county[0]['AdvertiserProfile']['county']."'"))
					{
							$error_div1 .= 'Offer Expiry Date is booked.<br />'; 
							
					}	
					}*/
			   
			 		/*-------------------------------------------------------------------*/			
					/*--------------------------check for existing main saving offer-----------------------------------------*/					
					
					if($offer_id!='')
					{
						if($main_offer=='yes' && $main_offer!='')
						{
							$saving_offer = $this->SavingOffer->find('first',array('conditions'=>array('SavingOffer.current_saving_offer="1" AND SavingOffer.status="yes" AND SavingOffer.advertiser_profile_id = "'.$aid.'" AND SavingOffer.id != "'.$offer_id.'"')));
							
							if(!empty($saving_offer) && count($saving_offer)>0)
							$error_div1 .= 'Already have main Offer, So choose other offer.<br />';
						}					
					}
					else
					{
						if($main_offer=='yes' && $main_offer!='')
						{
							$saving_offer = $this->SavingOffer->find('first',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.status'=>'yes','SavingOffer.advertiser_profile_id'=>$aid)));
							
							if(!empty($saving_offer) && count($saving_offer)>0)
							$error_div1 .= 'Already have main Offer, So choose other offer.<br />';
						}
					}
					echo $error_div1;			
	}
	function update_date() {
	$date_type = $this->data['SavingOffer']['date_type'];
	$data_field = ($date_type=='sdate')?'offer_start_date':'offer_expiry_date';
			$i =0; 
			$data1 = array();
			foreach($this->data['SavingOffer'] as $key=>$value) {		
					$get_id = explode('_',$key);	
					if(!isset($get_id[1])) {
						break;
					}	else if($get_id[0]==$date_type) {	
							$id = $get_id[1];
							$data1[$i]['SavingOffer']['id']		= $id;
							$data1[$i]['SavingOffer'][$data_field]  = strtotime($value);
							$i++;
					}
			}
			$this->SavingOffer->saveAll($data1);	
			$this->Session->setFlash('Date has been updated successfully.');
			$this->redirect($this->data['SavingOffer']['full_url']);	
	}
	
	// Update date from index page
	function hangover() {
				$search = $this->SavingOffer->find('first',array('conditions'=>array('SavingOffer.id'=>$this->data['SavingOffer']['hangover_id'])));
				$start	=	strtotime($this->data['SavingOffer']['sdate']);
				$end 	=	strtotime($this->data['SavingOffer']['edate']);
				$city 	=	strtotime($this->data['SavingOffer']['city']);				
				$home_filter 	= 0;
				$cats_filter 	= 0;
				$topten_filter 	= 0;
				$checkpoint = '';
				
				/*pr($search);
				exit;*/
				if($search['SavingOffer']['show_at_home']==1 && $search['SavingOffer']['current_saving_offer']==1) {
																				
					$home_filter = $this->SavingOffer->find('count',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.id<>'.$this->data['SavingOffer']['hangover_id'],'SavingOffer.status'=>'yes','SavingOffer.advertiser_county_id'=>$this->data['SavingOffer']['county_id'],'SavingOffer.show_at_home'=>1,array('OR'=>array(("SavingOffer.offer_start_date<=$start AND SavingOffer.offer_expiry_date>=$start"),("SavingOffer.offer_start_date<=$end AND SavingOffer.offer_expiry_date>=$end"),("SavingOffer.offer_start_date>$start AND SavingOffer.offer_expiry_date<$end"))))));		
											
				} else if($search['SavingOffer']['show_at_category']==1 && $search['SavingOffer']['current_saving_offer']==1) {
					
					$cats_filter = $this->SavingOffer->find('count',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.id<>'.$this->data['SavingOffer']['hangover_id'],'SavingOffer.status'=>'yes','SavingOffer.advertiser_county_id'=>$this->data['SavingOffer']['county_id'],'SavingOffer.show_at_category'=>1,'SavingOffer.category'=>$this->data['SavingOffer']['cat'],'SavingOffer.subcategory'=>$this->data['SavingOffer']['subcat'],array('OR'=>array(("SavingOffer.offer_start_date<=$start AND SavingOffer.offer_expiry_date>=$start"),("SavingOffer.offer_start_date<=$end AND SavingOffer.offer_expiry_date>=$end"),("SavingOffer.offer_start_date>$start AND SavingOffer.offer_expiry_date<$end"))))));
									
				} else if($search['SavingOffer']['top_ten_status']==1 && $search['SavingOffer']['current_saving_offer']==1) {
				
					$topten_filter = $this->SavingOffer->find('count',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.id<>'.$this->data['SavingOffer']['hangover_id'],'SavingOffer.status'=>'yes','SavingOffer.advertiser_county_id'=>$this->data['SavingOffer']['county_id'],'AdvertiserProfile.city'=>$this->data['SavingOffer']['city'],'SavingOffer.category'=>$this->data['SavingOffer']['cat'],'SavingOffer.subcategory'=>$this->data['SavingOffer']['subcat'],'SavingOffer.top_ten_status'=>1,array('OR'=>array(("SavingOffer.offer_start_date<=$start AND SavingOffer.offer_expiry_date>=$start"),("SavingOffer.offer_start_date<=$end AND SavingOffer.offer_expiry_date>=$end"),("SavingOffer.offer_start_date>$start AND SavingOffer.offer_expiry_date<$end"))))));
									
				}
				$redirect_url = str_replace("/msg:error", "", $this->data['SavingOffer']['full_url']);
				
				if($home_filter>10) {
					$checkpoint .= 'Dates are not available for Home Page.<br />';
				}
				if($cats_filter>10) {
					$checkpoint .= 'Dates are not available for Category Page.<br />';
				}
				
				if($topten_filter>10) {
					$checkpoint .= 'Dates are not available for top 10 Page.<br />';
				}
				
				if($checkpoint!='') {
						//$this->set('error','wrong');
						$this->Session->setFlash($checkpoint);
						$this->redirect($redirect_url.'/msg:error');
				} else {
					$savearr = array();
					$savearr['SavingOffer']['id']					= $this->data['SavingOffer']['hangover_id'];
					$savearr['SavingOffer']['offer_start_date']  	= strtotime($this->data['SavingOffer']['sdate']);
					$savearr['SavingOffer']['offer_expiry_date']  	= strtotime($this->data['SavingOffer']['edate']);		
					$this->SavingOffer->save($savearr);
					$this->Session->setFlash('Date has been updated successfully.');
					$this->redirect($redirect_url);
			}
	}
	function check_home($id) {
		$this->autoRender = false;
		echo $this->common->homeSavingPerm($id).'-'.$this->common->categorySavingPerm($id);
	}	
// Validate date on adding an offer
	function hangover_2($adv_id,$s_date,$e_date,$current_saving_offer,$cat,$subcat,$offer_id,$home_page,$cat_page,$top_ten) {
				$this->loadModel('AdvertiserProfile');
				$search = $this->AdvertiserProfile->find('first',array('conditions'=>array('AdvertiserProfile.id'=>$adv_id)));
				$start	=	strtotime($s_date);
				$end 	=	strtotime($e_date);
				$home_filter 	= 0;
				$cats_filter 	= 0;
				$topten_filter 	= 0;	
				$checkpoint = '';	
				if($home_page==1 && $current_saving_offer==1) {
				
					$home_filter = $this->SavingOffer->find('count',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.id<>'.$offer_id,'SavingOffer.status'=>'yes','SavingOffer.advertiser_county_id'=>$search['AdvertiserProfile']['county'],'SavingOffer.show_at_home'=>1,array('OR'=>array(("SavingOffer.offer_start_date<=$start AND SavingOffer.offer_expiry_date>=$start"),("SavingOffer.offer_start_date<=$end AND SavingOffer.offer_expiry_date>=$end"),("SavingOffer.offer_start_date>$start AND SavingOffer.offer_expiry_date<$end"))))));						
					
				} else if($cat_page==1 && $current_saving_offer==1) {
					
					$cats_filter = $this->SavingOffer->find('count',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.id<>'.$offer_id,'SavingOffer.status'=>'yes','SavingOffer.advertiser_county_id'=>$search['AdvertiserProfile']['county'],'SavingOffer.show_at_category'=>1,'SavingOffer.category'=>$cat,'SavingOffer.subcategory'=>$subcat,array('OR'=>array(("SavingOffer.offer_start_date<=$start AND SavingOffer.offer_expiry_date>=$start"),("SavingOffer.offer_start_date<=$end AND SavingOffer.offer_expiry_date>=$end"),("SavingOffer.offer_start_date>$start AND SavingOffer.offer_expiry_date<$end"))))));
					
				} else if($top_ten==1 && $current_saving_offer==1) {
				
					$topten_filter = $this->SavingOffer->find('count',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.id<>'.$offer_id,'SavingOffer.status'=>'yes','SavingOffer.advertiser_county_id'=>$search['AdvertiserProfile']['county'],'AdvertiserProfile.city'=>$search['AdvertiserProfile']['city'],'SavingOffer.category'=>$cat,'SavingOffer.subcategory'=>$subcat,'SavingOffer.top_ten_status'=>1,array('OR'=>array(("SavingOffer.offer_start_date<=$start AND SavingOffer.offer_expiry_date>=$start"),("SavingOffer.offer_start_date<=$end AND SavingOffer.offer_expiry_date>=$end"),("SavingOffer.offer_start_date>$start AND SavingOffer.offer_expiry_date<$end"))))));
									
				}	
				if($home_filter>10) {
					$checkpoint .= 'Dates are not available for Home Page.<br />';
				}
				if($cats_filter>10) {
					$checkpoint .= 'Dates are not available for Category Page.<br />';
				}
				if($topten_filter>10) {
					$checkpoint .= 'Dates are not available for Top 10 Page.<br />';
				}			
				return $checkpoint;
	}
/*------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function hangover_return() {
					for($i=1;$i<$this->data['SavingOffer']['total_i'];$i++) {
					
						$savearr = array();
						$savearr['SavingOffer']['id']					= $this->data['SavingOffer']['hangover_id'.$i];
						$savearr['SavingOffer']['offer_start_date']  	= strtotime($this->data['SavingOffer']['sdate'.$i]);
						$savearr['SavingOffer']['offer_expiry_date']  	= strtotime($this->data['SavingOffer']['edate'.$i]);		
						$this->SavingOffer->save($savearr);
						
					}
					$this->Session->setFlash('Date has been updated successfully.');
					$this->redirect($this->data['SavingOffer']['full_url']);
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
	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username',
            'password' => 'password'
            );
		$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
		$this->Auth->allow('offerDeleteFront','checkofferFront');
   	}
	/* This function is setting all info about current logged in user in
		currentAdmin array so we can use it anywhere in ctp file.Also setting
		cssname for current theme and usergroup detail
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