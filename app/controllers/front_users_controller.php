<?php
/*
   Coder: Keshav Sharma
   Date : 23 Sep 2011
*/
class FrontUsersController extends AppController {
      var $name = 'FrontUsers';
	  var $helpers = array('Html','Form','User','Javascript','Text','Image','Paginator');
	  var $layout = 'profile'; //variable for admin layout
 	  var $components = array('Auth','Email','common','Session','Cookie','RequestHandler','emailhtml');



//---------------------------------------------------------------------------------------------------------------------------------//
	function images() {
		$this->loadModel('Image');
		//Find all images of the logged in advertiser
			$adveriser_images = $this->Image->find('all',array('conditions'=>array('Image.advertiser_profile_id'=>$this->Session->read('Auth.FrontUser.advertiser_profile_id'))));
			return $adveriser_images;
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function addImage() {
	if($this->Session->read('Auth.FrontUser'))
	{
		if(isset($this->data))
		{
				$this->loadModel('Image');								
				$this->data['Image']['title']=$this->data['front_users']['title'];
				$this->data['Image']['link']=$this->data['front_users']['link'];
				$this->data['Image']['advertiser_profile_id']=$this->Session->read('Auth.FrontUser.advertiser_profile_id');
				$this->data['Image']['status']='no';
				if(isset($this->data['front_users']['image_big']['name']) && $this->data['front_users']['image_big']['name']!='')
				{										
					$this->data['front_users']['image_big']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['front_users']['image_big']['name']);
					$docDestination = APP.'webroot/img/gallery/'.$this->data['front_users']['image_big']['name'];
					@chmod(APP.'webroot/img/gallery/',0777);
					move_uploaded_file($this->data['front_users']['image_big']['tmp_name'], $docDestination) or die($docDestination);
					$this->data['Image']['image_big'] = $this->data['front_users']['image_big']['name'];
				}
				$this->Image->save($this->data);
				$lastImageInsertId=$this->Image->getLastInsertId();
			
			//after getting last inserted id for video table we are inserting in work order table
					  App::import('model', 'WorkOrder');
					  $this->WorkOrder = new WorkOrder;
					  $saveWorkArray = array();
					  $saveWorkArray['WorkOrder']['subject']   					=  'Needs Approval For Image';
					  $saveWorkArray['WorkOrder']['message']   					=  'The Image has been uploaded by advertiser recently. Please Approved it by changing its status and published it to merchant page on front end.For more details about image, please click on below links : ';
					  $saveWorkArray['WorkOrder']['type']   					=  'imageApproval';
					  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
					  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
					  $saveWorkArray['WorkOrder']['from_group']   				=  'Advertiser';
					  $saveWorkArray['WorkOrder']['archive']   					=  '';
					  $saveWorkArray['WorkOrder']['bottom_line']				=  'Image Details : <a href="'.FULL_BASE_URL.router::url('/',false).'images/imageEditDetail/'.$lastImageInsertId.'/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'" target="_blank">'.FULL_BASE_URL.router::url('/',false).'images/imageEditDetail/'.$lastImageInsertId.'/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'</a><br /><br />Advertiser Details : <a href="'.FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'" target="_blank">'.FULL_BASE_URL.router::url('/',false).'/advertiser_profiles/masterSheet/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'</a>';
					  date_default_timezone_set('US/Eastern');
					  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
					  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
					  $this->WorkOrder->save($saveWorkArray);
				/*-------------------------------------------------------------------*/		
				$this->Session->setFlash('Image Added Successfully');
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/images/add:success');
		}
	} else {
			$this->redirect(FULL_BASE_URL.router::url('/',false).'state/');
	}
}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function editImage() {
	if($this->Session->read('Auth.FrontUser'))
	{
		if(isset($this->data))
		{
				$this->loadModel('Image');								
				$this->data['Image']['title']=$this->data['front_users']['title'];
				$this->data['Image']['link']=$this->data['front_users']['link'];
				$this->data['Image']['id']=$this->data['front_users']['id'];
				$this->data['Image']['status']='no';			
				
				if(isset($this->data['front_users']['image_big']['name']) && $this->data['front_users']['image_big']['name']!='')
				{															
					$this->data['front_users']['image_big']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['front_users']['image_big']['name']);					
					$docDestination = APP.'webroot/img/gallery/'.$this->data['front_users']['image_big']['name']; 
					@chmod(APP.'webroot/img/gallery/',0777);
					move_uploaded_file($this->data['front_users']['image_big']['tmp_name'], $docDestination) or die($docDestination);
					@unlink(APP.'webroot/img/gallery/'.$this->data['front_users']['big_img_hidden']);
					$this->data['Image']['image_big'] = $this->data['front_users']['image_big']['name'];
				}
				else
				{
					$this->data['Image']['image_big'] = $this->data['front_users']['big_img_hidden'];
				}		
				$this->Image->save($this->data);
				$lastImageInsertId=$this->data['front_users']['id'];													
			//aftre getting last inserted id for video table we are inserting in work order table
					  App::import('model', 'WorkOrder');
					  
					  $this->WorkOrder = new WorkOrder;
					  
					  $saveWorkArray = array();
					  
					  $saveWorkArray['WorkOrder']['subject']   					=  'Needs Approval For Image';
					  
					  $saveWorkArray['WorkOrder']['message']   					=  'The Image has been uploaded by advertiser recently. Please Approved it by changing its status and published it to merchant page on front end.For more details about image, please click on below links : ';
					  
					  $saveWorkArray['WorkOrder']['type']   					=  'imageApproval';
					  
					  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
					  
					  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
					  
					  $saveWorkArray['WorkOrder']['from_group']   				=  'Advertiser';
					  
					  $saveWorkArray['WorkOrder']['archive']   					=  '';
					  
					 $saveWorkArray['WorkOrder']['bottom_line']				=  'Image Details : <a href="'.FULL_BASE_URL.router::url('/',false).'images/imageEditDetail/'.$lastImageInsertId.'/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'" target="_blank">'.FULL_BASE_URL.router::url('/',false).'images/imageEditDetail/'.$lastImageInsertId.'/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'</a><br /><br />Advertiser Details : <a href="'.FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'" target="_blank">'.FULL_BASE_URL.router::url('/',false).'/advertiser_profiles/masterSheet/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'</a>';
					  date_default_timezone_set('US/Eastern');
					  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
					  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
					  $this->WorkOrder->save($saveWorkArray);				
				/*-------------------------------------------------------------------*/	
							
				$this->Session->setFlash('Image Updated Successfully');
				
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/images/edit:success');
		}
		
	}
	else
	{
			$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'); 
	}
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function getImage($img_id='') {
		$this->loadModel('Image');
		return $this->Image->find('first',array('conditions'=>array('Image.id'=>$img_id)));
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function videos() {
		if($this->Session->read('Auth.FrontUser'))
		{	
			if(isset($this->data)){
			
				$this->loadModel('Video');
				
				$this->data['Video']['advertiser_profile_id']=$this->Session->read('Auth.FrontUser.advertiser_profile_id');
				
				$this->data['Video']['title']=$this->data['front_users']['title'];
				
				$this->data['Video']['utube_link']=$this->data['front_users']['utube_link'];
				
				if(isset($this->data['front_users']['vid']) && $this->data['front_users']['vid']!='')
				{
					$this->data['Video']['id']=$this->data['front_users']['vid'];
				}
				
				if(isset($this->data['front_users']['file_name']['name']) && $this->data['front_users']['file_name']['name']!='')
				{
						$type = explode(".",$this->data['front_users']['file_name']['name']);
	
						$fileName = $this->common->getTimeStamp()."_".str_replace(' ','-',$type[0]);
						
						$videoFileName = $fileName.'.flv';
											
						$videoDestination = APP.'webroot/img/video/'.$videoFileName;
						
						@chmod(APP.'webroot/img/video/',0777);
																			
					if($this->data['front_users']['file_name']['type']=='video/x-flv' || $this->data['front_users']['file_name']['type']=='application/octet-stream')
					{			 				
						move_uploaded_file($this->data['front_users']['file_name']['tmp_name'], $videoDestination) or die($videoDestination);
						
						$this->data['Video']['file_name'] = $videoFileName;
					}
					else
					{						
						move_uploaded_file($this->data['front_users']['file_name']['tmp_name'], $videoDestination) or die( $videoDestination);
						
						$this->convertToFlv(APP.'webroot/img/video/'.$this->data['front_users']['file_name']['name'], $videoDestination);
						
						$this->data['Video']['file_name'] = $videoFileName;						
					}
					
					if(isset($this->data['front_users']['old_file_name']) && $this->data['front_users']['old_file_name']!='' && file_exists(APP.'webroot/img/video/'.$this->data['front_users']['old_file_name']))
					{
						@unlink(APP.'webroot/img/video/'.$this->data['front_users']['old_file_name']);
					}
					
				}
				elseif(isset($this->data['front_users']['old_file_name']) && $this->data['front_users']['old_file_name']!='')
				{
					$this->data['Video']['file_name'] = $this->data['front_users']['old_file_name'];
				}
				else
				{
					$this->data['Video']['file_name'] = "";
				}

				
				$this->data['Video']['status'] = 'no';

				$this->Video->save($this->data);

				/*-------------code for admin approval when change made---------------*/
									
			//aftre getting last inserted id for video table we are inserting in work order table
					  App::import('model', 'WorkOrder');
					  
					  $this->WorkOrder = new WorkOrder;
					  
					  $saveWorkArray = array();
					  
					  $saveWorkArray['WorkOrder']['subject']   					=  'Needs Approval For Video';
					  
					  $saveWorkArray['WorkOrder']['message']   					=  'The Video has been uploaded by advertiser recently. Please Approved it by changing its status and published it to merchant page on front end.For more details about video, please click on below links : ';
					  
					  $saveWorkArray['WorkOrder']['type']   					=  'videoApproval';
					  
					  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
					  
					  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
					  
					  $saveWorkArray['WorkOrder']['from_group']   				=  'Advertiser';
					  
					  $saveWorkArray['WorkOrder']['archive']   					=  '';
					  
					  $saveWorkArray['WorkOrder']['bottom_line']				=  'Video Details : <a href="'.FULL_BASE_URL.router::url('/',false).'videos/index/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'" target="_blank">'.FULL_BASE_URL.router::url('/',false).'videos/index/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'</a><br /><br />Advertiser Details : <a href="'.FULL_BASE_URL.router::url('/',false).'advertiser_profiles/advertiserProfileEditDetail/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'" target="_blank">'.FULL_BASE_URL.router::url('/',false).'/advertiser_profiles/masterSheet/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'</a>';
					  date_default_timezone_set('US/Eastern');
					  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
					  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
					  
					  $this->WorkOrder->save($saveWorkArray);
				
				/*-------------------------------------------------------------------*/			

				
				$this->Session->setFlash('Video Updated Successfully');
				
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/videos/edit:success');
							
			}	
		}
		else
		{
			$this->redirect(FULL_BASE_URL.router::url('/',false).'state/');		
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function getVideo() {
		$this->loadModel('Video');
		//Find video of the logged in advertiser
			$adveriser_videos = $this->Video->find('first',array('conditions'=>array('Video.advertiser_profile_id'=>$this->Session->read('Auth.FrontUser.advertiser_profile_id'))));
			return $adveriser_videos;	
	}
//-----------------------------------------------convert video into flv format--------------------------------------------------//	
	function convertToFlv($source,$destination) {

	   //create command for video

		$cmd="ffmpeg -i ".$source;

		$options='';
		
		$audioSamplingRate =44100;
		$audioBit =64;
		//$bitRate = 64;

		if($audioSamplingRate) {
				
				$options.=" -ar ".$audioSamplingRate;

			  }
		
		if($audioBit) {
				
				$options.=" -ab ".$audioBit;

			  }	 
			   
/*	   if($bitRate) {

				$options.=" -b ".$bitRate;

			  }

		if($videoSize) {

				$options.=" -s ".$videoSize;

		} */	   
		
		$options.=" -sameq";  

		$cmd .=$options." -f flv ".$destination;

/*		echo $cmd;
*/
		$last_line_vodeo=exec(escapeshellcmd($cmd),$retval); //retval value will generate 0 if video uploaded successfully 

		if(!file_exists($destination) || filesize($destination) < 100 ) {

		  $retval=1;

		  }

		//create command for thumb image from video

		//$this->createThumb($destination,$imgDestination,$retval); //hear destination of flv is image soruce

		@unlink($source);

	 }			
//---------------------------------------------------------------------------------------------------------------------------------//	
	function offer() {
		$this->loadModel('SavingOffer');
		//Find all saving offers of the logged in advertiser
			$saving_offer = $this->SavingOffer->find('all',array('conditions'=>array('SavingOffer.advertiser_profile_id'=>$this->Session->read('Auth.FrontUser.advertiser_profile_id'))));
			return $saving_offer;		
	}	
//---------------------------------------------------------------------------------------------------------------------------------//	
	function getOffer($ofr_id='') {
		$this->loadModel('SavingOffer');
		return $this->SavingOffer->find('first',array('conditions'=>array('SavingOffer.id'=>$ofr_id)));

	}	

//--------------------------------------------------------add Saving Offer from front end------------------------------------------------//	
	function addNewOffer() {
	if($this->Session->read('Auth.FrontUser'))
	{
		if(isset($this->data))
		{	
				/*------------------check for existing offer limit of an advertiser(max. 5 offer may be possible)------------------------*/			
				$this->loadModel('SavingOffer');				
				$count_offer=$this->SavingOffer->find('all',array('conditions'=>array('advertiser_profile_id'=>$this->Session->read('Auth.FrontUser.advertiser_profile_id'))));

				if(count($count_offer)<5)
				{
				$this->data['SavingOffer']['title']	=	$this->data['front_users']['offer_title'];
				$this->data['SavingOffer']['description']	=	$this->data['front_users']['offer_desc'];
				$this->data['SavingOffer']['disclaimer']	=	$this->data['front_users']['disclaimer'];
				if($this->data['saving_offer']=='current_saving_offer')
				{
					$this->data['SavingOffer']['current_saving_offer']	=	1;
					$this->data['SavingOffer']['other_saving_offer']	=	0;
					$this->data['SavingOffer']['top_ten_status']		=	$this->data['publish_top'];
				}
				else
				{
					$this->data['SavingOffer']['current_saving_offer']	=	0;
					$this->data['SavingOffer']['other_saving_offer']	=	1;
					$this->data['SavingOffer']['top_ten_status']		=	0;
				}
				
				$this->data['SavingOffer']['status']='no';
				$this->data['SavingOffer']['off'] =  $this->data['front_users']['off'];
				$this->data['SavingOffer']['off_unit'] =  $this->data['front_users']['off_unit'];
				
				$this->data['SavingOffer']['no_valid_other_offer']	=	$this->data['front_users']['not_valid'];			
				$this->data['SavingOffer']['no_transferable']	=	$this->data['front_users']['non_transferable'];			
				$this->data['SavingOffer']['other']	=	$this->data['front_users']['other'];	

					#Here we are find out the category and subcategory of advertiser
					
					$adv_id=$this->Session->read('Auth.FrontUser.advertiser_profile_id');
					$this->data['SavingOffer']['advertiser_profile_id']	=	$adv_id;		
					App::import('model','AdvertiserProfile');
					$this->AdvertiserProfile = new AdvertiserProfile();
					$cat_subcat = $this->AdvertiserProfile->find('all',array('fields'=>array('county','category','subcategory'),'conditions'=>array('AdvertiserProfile.id'=>$adv_id)));
					if(isset($cat_subcat[0]['AdvertiserProfile']['county']))
					{
						$this->data['SavingOffer']['advertiser_county_id'] = $cat_subcat[0]['AdvertiserProfile']['county'];
					}
					if(isset($this->data['SavingOffer']['subcategory']) && $this->data['SavingOffer']['subcategory']!='')
					{						
						$saveCatArray=explode('-',$this->data['SavingOffer']['subcategory']);
						$this->data['SavingOffer']['category'] = $saveCatArray[0];
						$this->data['SavingOffer']['subcategory'] = $saveCatArray[1];
					}
					#if admin not selecting any date then we are inserting current data in database
					if(!empty($this->data['front_users']['sdate']))
					{
						$s_date		= $this->data['front_users']['sdate'];
						$start_date	= explode('/',$s_date);
						$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
					}
					else
					{
					 	$start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
					}
					
					#----------------------------------------------------------------------------------------------
					if(!empty($this->data['front_users']['edate']))
					{
						$e_date		= $this->data['front_users']['edate'];
						$expiry_date	= explode('/',$e_date);
						$expiry_date = mktime(0,0,0,$expiry_date[0],$expiry_date[1],$expiry_date[2]);
					}
					else
					{
					 	$expiry_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
					}
						
					$this->data['SavingOffer']['offer_start_date']	=	$start_date;			
					$this->data['SavingOffer']['offer_expiry_date']	=	$expiry_date;
																			
					$this->data['front_users']['offer_big_img']['name'] = $this->common->getTimeStamp()."_".$this->data['SavingOffer']['advertiser_profile_id']."_".str_replace(' ','-',$this->data['front_users']['offer_big_img']['name']);
					
					$docDestination = APP.'webroot/img/offer/soffers/'.$this->data['front_users']['offer_big_img']['name']; 
					@chmod(APP.'webroot/img/offer/soffers/',0777);
					move_uploaded_file($this->data['front_users']['offer_big_img']['tmp_name'], $docDestination) or die($docDestination);
					$this->data['SavingOffer']['offer_image_big'] = $this->data['front_users']['offer_big_img']['name'];
		
				$this->SavingOffer->save($this->data);
				
					#Insertimg one record in work order table to show this data in inbox of admin
						  App::import('model', 'WorkOrder');
						  $this->WorkOrder = new WorkOrder;
						  $saveWorkArray = array();
						  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $this->Session->read('Auth.FrontUser.advertiser_profile_id');
						  $saveWorkArray['WorkOrder']['read_status']   				=  0;
						  $saveWorkArray['WorkOrder']['subject']   					=  'New saving offer Added from Front End';
						  $saveWorkArray['WorkOrder']['message']   					=  'A new work order for saving offer has been placed recently by advertiser from front end .Details are below:';
						  $saveWorkArray['WorkOrder']['type']   					=  'savingworkorder';
						  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
						  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
						  $saveWorkArray['WorkOrder']['from_group']   				=  'Advertiser';						  
						  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can check this saving offer and all other offers for this advertiser in Advertiser profiles section and add remaining details.Please follow below url:<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'saving_offers/index/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'" style="text-decoration:underline;" target="_blank">'.FULL_BASE_URL.Router::url('/', false).'saving_offers/index/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'</a>';
						  date_default_timezone_set('US/Eastern');
						  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
						  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
						  $this->WorkOrder->save($saveWorkArray);				
				$this->Session->setFlash('Offer Added Successfully');
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/offer/add:success'); 
				}
				else
				{
				$this->Session->setFlash('You are Already have 5 offfers, So remove other to add new offer');
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/offer/check:success'); 
				}
			}
		}
		else
		{
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'); 
		}	
	}	

//---------------------------------------------------------------------------------------------------------------------------------//

//-----------------------------------------Edit Saving Offer from front end--------------------------------------------------------//	
	function editOffer() {
	if($this->Session->read('Auth.FrontUser'))
	{
		if(isset($this->data))
		{ 	//pr($this->data);	exit;		
				$this->loadModel('SavingOffer');				
				$count_offer=$this->SavingOffer->find('all',array('conditions'=>array('advertiser_profile_id'=>$this->Session->read('Auth.FrontUser.advertiser_profile_id'))));

				$this->data['SavingOffer']['id']	=	$this->data['front_users']['id'];
				$this->data['SavingOffer']['title']	=	$this->data['front_users']['offer_title'];
				$this->data['SavingOffer']['description']	=	$this->data['front_users']['offer_desc'];
				$this->data['SavingOffer']['disclaimer']	=	$this->data['front_users']['disclaimer'];
				if($this->data['saving_offer']=='current_saving_offer')
				{
					$this->data['SavingOffer']['current_saving_offer']	=	1;
					$this->data['SavingOffer']['other_saving_offer']	=	0;
					$this->data['SavingOffer']['top_ten_status']		=	$this->data['publish_top'];
				}
				else
				{
					$this->data['SavingOffer']['current_saving_offer']	=	0;
					$this->data['SavingOffer']['other_saving_offer']	=	1;
					$this->data['SavingOffer']['top_ten_status']		=	0;
				}
				$this->data['SavingOffer']['off'] =  $this->data['front_users']['off'];
				$this->data['SavingOffer']['off_unit'] =  $this->data['front_users']['off_unit'];
				
				$this->data['SavingOffer']['no_valid_other_offer']	=	$this->data['front_users']['not_valid'];			
				$this->data['SavingOffer']['no_transferable']	=	$this->data['front_users']['non_transferable'];			
				$this->data['SavingOffer']['other']	=	$this->data['front_users']['other'];	

					#Here we are find out the category and subcategory of advertiser
					
					$adv_id=$this->Session->read('Auth.FrontUser.advertiser_profile_id');
					$this->data['SavingOffer']['advertiser_profile_id']	=	$adv_id;		
					App::import('model','AdvertiserProfile');
					$this->AdvertiserProfile = new AdvertiserProfile();
					$cat_subcat = $this->AdvertiserProfile->find('all',array('fields'=>array('county'),'conditions'=>array('AdvertiserProfile.id'=>$adv_id)));
					if(isset($cat_subcat[0]['AdvertiserProfile']['county']))
					{
						$this->data['SavingOffer']['advertiser_county_id'] = $cat_subcat[0]['AdvertiserProfile']['county'];
					}						
					if(isset($this->data['SavingOffer']['subcategory']) && $this->data['SavingOffer']['subcategory']!='')
					{						
						$saveCatArray=explode('-',$this->data['SavingOffer']['subcategory']);
						$this->data['SavingOffer']['category'] = $saveCatArray[0];
						$this->data['SavingOffer']['subcategory'] = $saveCatArray[1];
					}
					#if admin not selecting any date then we are inserting current data in database
					if(!empty($this->data['front_users']['sdate']))
					{
						$s_date		= $this->data['front_users']['sdate'];
						$start_date	= explode('/',$s_date);
						$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
					}
					else
					{
					 	$start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
					}
					
					#----------------------------------------------------------------------------------------------
					if(!empty($this->data['front_users']['edate']))
					{
						$e_date		= $this->data['front_users']['edate'];
						$expiry_date	= explode('/',$e_date);
						$expiry_date = mktime(0,0,0,$expiry_date[0],$expiry_date[1],$expiry_date[2]);
					}
					else
					{
					 	$expiry_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
					}
						
					$this->data['SavingOffer']['offer_start_date']	=	$start_date;			
					$this->data['SavingOffer']['offer_expiry_date']	=	$expiry_date;						
					
			/*--------------big image uplad code--------------------*/								
					if(isset($this->data['front_users']['offer_big_img']['name']) && $this->data['front_users']['offer_big_img']['name']!='')
					{					
						$this->data['front_users']['offer_big_img']['name'] = $this->common->getTimeStamp()."_".$this->data['SavingOffer']['advertiser_profile_id']."_".str_replace(' ','-',$this->data['front_users']['offer_big_img']['name']);
						
						$docDestination = APP.'webroot/img/offer/soffers/'.$this->data['front_users']['offer_big_img']['name']; 
						@chmod(APP.'webroot/img/offer/soffers/',0777);
						move_uploaded_file($this->data['front_users']['offer_big_img']['tmp_name'], $docDestination) or die($docDestination);
						$this->data['SavingOffer']['offer_image_big'] = $this->data['front_users']['offer_big_img']['name'];
						@unlink(APP.'webroot/img/offer/soffers/'.$this->data['front_users']['offer_big_img_hidden']);
					}
					else
					{
						$this->data['SavingOffer']['offer_image_big'] = $this->data['front_users']['offer_big_img_hidden'];
					}
		
				$this->SavingOffer->save($this->data);
				
					#Insertimg one record in work order table to show this data in inbox of admin
						  App::import('model', 'WorkOrder');
						  $this->WorkOrder = new WorkOrder;
						  $saveWorkArray = array();
						  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $this->Session->read('Auth.FrontUser.advertiser_profile_id');
						  $saveWorkArray['WorkOrder']['read_status']   				=  0;
						  $saveWorkArray['WorkOrder']['subject']   					=  'Update in saving offer from Front End';
						  $saveWorkArray['WorkOrder']['message']   					=  'A saving offer has been updated recently by advertiser from front end .Details are below:';
						  $saveWorkArray['WorkOrder']['type']   					=  'savingworkorder';
						  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
						  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
						  $saveWorkArray['WorkOrder']['from_group']   				=  'Advertiser';						  
						  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can check this saving offer and all other offers for this advertiser in Advertiser profiles section and add remaining details.Please follow below url:<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'saving_offers/index/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'" style="text-decoration:underline;" target="_blank">'.FULL_BASE_URL.Router::url('/', false).'saving_offers/index/'.$this->Session->read('Auth.FrontUser.advertiser_profile_id').'</a>';
						  date_default_timezone_set('US/Eastern');
						  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
						  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
						  $this->WorkOrder->save($saveWorkArray);				
				$this->Session->setFlash('Offer Updated Successfully');
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/offer/edit:success'); 
				
			}
		}
		else
		{
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'); 
		}	
	}	

//---------------------------------------------------------------------------------------------------------------------------------//		
	function account() {
		return $this->FrontUser->find('first',array('conditions'=>array('FrontUser.id'=>$this->Session->read('Auth.FrontUser.id'))));
	}
	
//---------------------------------------------------------------------------------------------------------------------------------//		
	function info() {
		return $this->FrontUser->find('first',array('conditions'=>array('FrontUser.id'=>$this->Session->read('Auth.FrontConsumer.id'))));
	}
		
//---------------------------------------------------------------------------------------------------------------------------------//
	function getAllCategory() {
	 		App::import('model','Category');
		    $this->Category = new Category(); 
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory();			
			$categoryList = $this->Category->find('list', array('fields' => array('id', 'categoryname'),'order' => 'Category.categoryname ASC','recursive' => -1,'conditions' => array('Category.publish' => 'yes')));
			return $categoryList;
	      }	
//---------------------------------------------------------------------------------------------------------------------------------//	  
	function getAllSubCategory(){	
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory();			
			$subCategoryList = $this->Subcategory->find('list', array('fields' => array('id', 'categoryname'),'order' => 'Subcategory.categoryname ASC','recursive' => -1,'conditions' => array('Subcategory.publish' => 'yes')));
			return $subCategoryList;
	      }
//---------------------------------------------------------------------------------------------------------------------------------//	  
	function newsletter(){
			$this->loadModel('NewsletterUser');		
			$newsletterList = $this->NewsletterUser->find('first', array('conditions'=>array('NewsletterUser.email'=>$this->Session->read('Auth.FrontUser.email'))));
			return $newsletterList;
	      }
//---------------------------------------------------------------------------------------------------------------------------------//	  
	function vip_offers($cat_id='',$county=''){
			$this->loadModel('VipOffer');
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));		
			$VipOffer = $this->VipOffer->find('all', array('conditions'=>array('VipOffer.status'=>'yes','VipOffer.category'=>$cat_id,'VipOffer.offer_start_date <='=>$cur_time,'VipOffer.offer_expiry_date >='=>$cur_time,'VipOffer.advertiser_county_id'=>$county)));
			return $VipOffer;
	      }
//---------------------------------------------------------------------------------------------------------------------------------//	  
	function vouchers($cat_id=''){
			$this->loadModel('FrontUser');
			$bucks_left = $this->FrontUser->find('first',array('fields'=>array('FrontUser.total_bucks'),'conditions'=>array('FrontUser.id'=>$this->Session->read('Auth.FrontConsumer.id'))));
			$this->loadModel('Voucher');
			$this->loadModel('Order');
			$cur_offer_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$ids_arr = '';
			
			// Get all voucher id, already purchased by the consumer
				//$this->loadModel('Order');
//				$voucher_id = $this->Order->find('all',array('fields'=>array('Order.voucher_id'),'conditions'=>array('Order.front_user_id'=>$this->Session->read('Auth.FrontConsumer.id'))));
//				if(is_array($voucher_id)) {
//					foreach($voucher_id as $voucher_id) {
//						$ids_arr[] =  $voucher_id['Order']['voucher_id'];
//					}
//				}
//				if(isset($ids_arr) && is_array($ids_arr)) {
//					$colloect_all = 'Voucher.id NOT IN ('.implode(',',$ids_arr).')';
//				} else {
//					$colloect_all = 'Voucher.id NOT IN (0)';
//				}
				
				$this->loadModel('Setting');			
		 	$waiting_time = $this->Setting->find('first',array('fields'=>array('Setting.waiting_gift'),'conditions'=>array('Setting.id'=>1)));
		 	$waiting_time = $waiting_time['Setting']['waiting_gift'];
				$this->loadModel('Order');
				$block_arr1 = '';
				//$month_back1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
				$month_back1 = mktime(0,0,0,date('m'),date('d')-$waiting_time,date('Y'));
				$block_ad_id1 = $this->Order->find('all',array('fields'=>array('Order.advertiser_profile_id'),'conditions'=>array('Order.front_user_id'=>$this->Session->read('Auth.FrontConsumer.id'),"Order.order_date>$month_back1")));
				
				if(is_array($block_ad_id1)) {
						foreach($block_ad_id1 as $block_ad_id1) {
							$block_arr1[] =  $block_ad_id1['Order']['advertiser_profile_id'];
						}
				}
				if(isset($block_arr1) && is_array($block_arr1)) {
							$colloect_all = 'Voucher.advertiser_profile_id NOT IN ('.implode(',',$block_arr1).')';
						} else {
							$colloect_all = 'Voucher.advertiser_profile_id NOT IN (0)';
				}
				$exchange_rate = $this->common->exchange_rate();
				//$voucher = $this->Voucher->find('all', array('conditions'=>array('Voucher.advertiser_county_id'=>$this->Session->read('county_data.id'),'Voucher.status'=>'yes','Voucher.s_date <='=>$cur_offer_time,'Voucher.e_date >='=>$cur_offer_time,'Voucher.category_id'=>$cat_id,'Voucher.price <='.(int)($bucks_left['FrontUser']['total_bucks']/$exchange_rate),$colloect_all)));
				
				$voucher = $this->Voucher->find('all', array('conditions'=>array('Voucher.advertiser_county_id'=>$this->Session->read('county_data.id'),'Voucher.status'=>'yes','Voucher.s_date <='=>$cur_offer_time,'Voucher.e_date >='=>$cur_offer_time,'Voucher.category_id'=>$cat_id,$colloect_all)));
				
			return $voucher;
	      }
//---------------------------------------------------------------------------------------------------------------------------------//
	function discount_user_school($string) {
		$this->autoRender = false;
		$testing = explode('a',$string);
		if(count($testing)==3 && $testing[2]!='') {
			$current_time = $this->common->getTimeStamp();
			$this->FrontUser->query("INSERT INTO referred_schools (front_user_id, daily_discount_id, school_id, created) VALUES($testing[0], $testing[1], $testing[2], $current_time)");
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//
	function sendPassword($email,$password) {
		$subject 		= 'Welcome to Zuni'; 
		$bodyText 		= 'Thanks for purchasing the discount on Zuni.<br />Login as consumer on site.<br />
							Your login Password is :<br />
							Password : '.$password.'<br />
							<br />Thanks<br />Zuni Admin Team';
							
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

					$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"send_password_to_advertiser");
			//////////////////////////////////////////////////////////////////////////////////////
	}
//---------------------------------------------------------------------------------------------------------------------------------//
	function sendDiscountMail($email,$name,$discount,$advertiser,$totalVoucher,$price,$total,$print_link,$county) {
		
		$arrayTags = array("[consumer_name]","[discount]","[advertiser]","[vouchers]","[price]","[total_price]","[voucher_link]");
		$arrayReplace = array($name,$discount,$advertiser,$totalVoucher,$price,$total,$print_link);
		
		//get Mail format
		$this->loadModel('Setting');
		$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.discount_link_subject','Setting.discount_link_body')));
		$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['discount_link_subject']);
		$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['discount_link_body']);
		
		//ADMINMAIL id
		$this->Email->to 		= $email;
		$this->Email->subject 	= strip_tags($subject);
		$this->Email->replyTo 	= $this->common->getReturnEmail();
		$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
		$this->Email->sendAs 	= 'html';
		//Set the body of the mail as we send it.		
		//seperate line in the message body.
		$this->body = '';				
		$this->body = $this->emailhtml->email_header($county);
		$this->body .=$bodyText;
		$this->body .= $this->emailhtml->email_footer($county);
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
				$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"discount_purchase");
			/////////////////////////////////////////////////////////////////////////
	
				
	}	
//---------------------------------------------------------------------------------------------------------------------------------//
	function logout() {
	    $this->Session->delete('Auth.FrontConsumer');
		$this->Session->delete('Auth.FrontUser');
		$this->Session->delete('Auth.AdvertiserProfile');
		$this->Cookie->delete('logedInUser');
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county'));
	}
//---------------------------------------------------------------------------------------------------------------------------------//
function forgot_password($email='')
{
	$this->autoRender=false;
	$exist_user=$this->FrontUser->find('first',array('fields'=>array('FrontUser.realpassword'),'conditions'=>array('FrontUser.email'=>$email,'FrontUser.status'=>'yes','FrontUser.user_type'=>'advertiser')));
	if(count($exist_user)>0 && !empty($exist_user))
		{
				$arrayTags = array("[password]");
				$arrayReplace = array($exist_user['FrontUser']['realpassword']);
				
				//get Mail format
				$this->loadModel('Setting');
				$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.password_subject','Setting.password_body')));
				$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['password_subject']);
				$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['password_body']);
				
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
				$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"advertiser_forgot_password");
			/////////////////////////////////////////////////////////////////////////


			echo '<span style="color:green;margin-left:130px;font-size:14px;">Your Password is successfully delivered to your email id.</span>';
		}
		else
		{
			echo 'You have entered incorrect email id.';
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//	

	function forgot_password_parent($email='')
		{
			$this->autoRender=false;
			$exist_user=$this->FrontUser->find('first',array('fields'=>array('FrontUser.realpassword'),'conditions'=>array('FrontUser.email'=>$email,'FrontUser.status'=>'yes','FrontUser.user_type'=>'parent')));
			if(count($exist_user)>0 && !empty($exist_user)) 
				{
					$arrayTags = array("[password]");
					$arrayReplace = array($exist_user['FrontUser']['realpassword']);
					
					//get Mail format
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.password_subject','Setting.password_body')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['password_subject']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['password_body']);
					
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"consumer_forgot_password");
					/////////////////////////////////////////////////////////////////////////


					echo '<span style="color:green;margin-left:130px;font-size:14px;">Your Password is successfully delivered to your email id.</span>';
				}
				else
				{
					echo 'You have entered incorrect email id.';
				}
			}			
//---------------------------------------------------------------------------------------------------------------------------------//	
	function actVipLogin()
	{
		if(isset($this->data))
		{
			$this->loadModel('County');
			$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$this->data['FrontUser']['county'])));
		
			if($this->Session->read('Auth.FrontConsumer')) {
				$this->Session->delete('Auth.FrontConsumer');
			}
		
			$dbuser_info = '';
			$this->loadModel('FrontUser');
			if($this->common->getMasterPassword()==$this->data['FrontUser']['d_password']) {
				$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$this->data['FrontUser']['d_email'],'FrontUser.user_type'=>'advertiser','FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
			}
		
			if(!is_array($dbuser_info)) {
				$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$this->data['FrontUser']['d_email'],'FrontUser.password'=>$this->Auth->password($this->data['FrontUser']['d_password']),'FrontUser.user_type'=>'advertiser','FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
			}

			if(is_array($dbuser_info) && !empty($dbuser_info)){
				$this->Session->write('Auth.FrontUser',$dbuser_info['FrontUser']);
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->data['FrontUser']['state'].'/'.$this->data['FrontUser']['county'].'/profile');
			} else {
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->data['FrontUser']['state'].'/'.$this->data['FrontUser']['county']);
			}
			
		} else {
			$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->data['FrontUser']['state'].'/'.$this->data['FrontUser']['county']);
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function actParentLogin()
	{
		if(isset($this->data))
		{
			$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$this->data['FrontUser']['c_login_email'],'FrontUser.password'=>$this->Auth->password($this->data['FrontUser']['c_login_pass']),'FrontUser.user_type'=>'parent','FrontUser.status'=>'yes')));
			if(!empty($dbuser_info)){
				$this->Session->write('Auth.Parent',$dbuser_info['FrontUser']);			
				$this->redirect(FULL_BASE_URL.router::url('/',false).'fundraisers/refer');
			} else {
				$this->redirect(FULL_BASE_URL.router::url('/',false).'fundraisers');
			}
		} else {
			$this->redirect(FULL_BASE_URL.router::url('/',false).'fundraisers');
		}
		
	}	
//---------------------------------------------------------------------------------------------------------------------------------//	
	function actBusinessLogin()
	{
		if(isset($this->data))
		{
			$this->loadModel('County');
			$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$this->data['FrontUser']['county'])));
			
			$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$this->data['FrontUser']['refer_email'],'FrontUser.password'=>$this->Auth->password($this->data['FrontUser']['refer_password']),'FrontUser.user_type'=>$this->data['FrontUser']['refer_user_type'],'FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
			if(!empty($dbuser_info)){
			if($this->data['FrontUser']['refer_user_type']=='customer')
			{
			
			if($this->Session->read('Auth.FrontUser')) {
				$this->Session->delete('Auth.FrontUser');
			}
			$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);
			if($this->data['FrontUser']['refer_friend']!='refer_friend')
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->data['FrontUser']['state'].'/'.$this->data['FrontUser']['county'].'/refer_business');
			else
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->data['FrontUser']['state'].'/'.$this->data['FrontUser']['county'].'/refer_friend');
			}
			
			} else {
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->data['FrontUser']['state'].'/'.$this->data['FrontUser']['county']);
			}
		} else {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->data['FrontUser']['state'].'/'.$this->data['FrontUser']['county']);
		}		
	}	
//---------------------------------------------------------------------------------------------------------------------------------//	
	function actBuckLogin()
	{
		if(isset($this->data))
		{
			$this->loadModel('County');
			$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$this->data['FrontUser']['county'])));
			
			$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$this->data['FrontUser']['bucks_email'],'FrontUser.password'=>$this->Auth->password($this->data['FrontUser']['bucks_password']),'FrontUser.user_type'=>$this->data['FrontUser']['bucks_user_type'],'FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
			if(!empty($dbuser_info)){
			if($this->data['FrontUser']['bucks_user_type']=='customer')
			{
			
			if($this->Session->read('Auth.FrontUser')) {
				$this->Session->delete('Auth.FrontUser');
			}
			$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);
			
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->data['FrontUser']['state'].'/'.$this->data['FrontUser']['county'].'/spend');
			
			}
			
			} else {
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->data['FrontUser']['state'].'/'.$this->data['FrontUser']['county']);
			}
		} else {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->data['FrontUser']['state'].'/'.$this->data['FrontUser']['county']);
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
					$this->Email->replyTo =$this->common->getReturnEmail();
					$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
					
					$this->body = $this->emailhtml->email_header();
					$this->body .="<p>Dear Advertiser</p>";
					$this->body .="<p>Please click on given link for registration on Zuni.</p><p>";
					$this->body .= $url.'</p>';
					$this->body .="<p><div>Thanks</div><div>Zuni Sales Team</div></p>";
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
				$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"send_registration_link");
			/////////////////////////////////////////////////////////////////////////


				}
			}
//---------------------------------------------------------------------------------------------------------------------------------//		  		  
	function contestlogin($name='',$pass='',$county='',$state='') {
	$this->layout=false;
		$token = 0;
		$state_id = $this->common->getStateIdByUrl($state);
		$this->loadModel('County');
		$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$county,'County.state_id'=>$state_id)));
		
		$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$name,'FrontUser.password'=>$this->Auth->password($pass),'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
		if(!empty($dbuser_info)) {	
			$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);	
			//Today Contest 
			$contest_timstmp = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$this->loadModel('Contest');
			$today_contest = $this->Contest->find('first',array('conditions'=>array('Contest.county_id'=>$this->Session->read('county_data.id'),'Contest.s_date <='.$contest_timstmp,'Contest.e_date >='.$contest_timstmp,'Contest.status'=>'yes')));
			$this->set('today_contest',$today_contest);				
			$token = 1;
			$this->loadModel('ContestUser');
			$winner_list = $this->ContestUser->find('all',array('fields'=>array('FrontUser.name','ContestUser.created','Contest.prize','Contest.e_date'),'conditions'=>array('Contest.county_id'=>$this->Session->read('county_data.id'),'ContestUser.winner'=>1),'order'=>array('ContestUser.id'=>'DESC'),'limit'=>4));
			$this->set('winner_list',$winner_list);
		}
		else{
			$token = 0;
		}
		$this->set('token',$token);
	}
//---------------this function also used in deal login, if user diretcly hits the deal url and not logged in -----------------------//
function dealloginaction($name='',$pass='',$county='',$state='',$uniqueString='')
{
		$this->layout=false;
		$token = 0;
		$state_id = $this->common->getStateIdByUrl($state);
		$this->loadModel('County');
		$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$county,'County.state_id'=>$state_id)));
		
		$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$name,'FrontUser.password'=>$this->Auth->password($pass),'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
		if(!empty($dbuser_info)) {
			$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);
			//Today Deal
			$this->loadModel('DailyDeal');
			
			$daily_deal = $this->DailyDeal->find('first',array('conditions'=>array("DailyDeal.unique=$uniqueString")));
			$this->set('daily_deal',$daily_deal);
			$token = 1;
			echo 'pass'; exit;
		}
		else{
			$token = 0;
			echo 'fail'; exit;
		}
		$this->set('token',$token);
		
}
//---------------------------------------------------------------------------------------------------------------------------------//		  		  
	function deallogin($name='',$pass='',$county='',$state='',$deal=0) {
		$this->layout=false;
		$token = 0;
		$state_id = $this->common->getStateIdByUrl($state);
		$this->loadModel('County');
		$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$county,'County.state_id'=>$state_id)));
		
		$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$name,'FrontUser.password'=>$this->Auth->password($pass),'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
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
function disklogin($name='',$pass='',$county='',$state='',$deal=0) {
		$this->autoRender=false;
		$token = 0;
		$state_id = $this->common->getStateIdByUrl($state);
		$this->loadModel('County');
		$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$county,'County.state_id'=>$state_id)));
		$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$name,'FrontUser.password'=>$this->Auth->password($pass),'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
		if(!empty($dbuser_info)) {
			$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);						
			echo $dbuser_info['FrontUser']['unique_id'];
			exit;
		}
		else{
			echo 'fail';
			exit;
		}
		$this->set('token',$token);
	}
//-----====----====-----=====--------==========--------------======== Daily Discount Extra Content ==========-------------=========---------=====-----====//
	function dailyDiscountData($id) {
		$this->layout = false;
		if(isset($id) && $id!='') {
			$this->loadModel('DailyDiscount');
			$this->DailyDiscount->id = $id;
			$this->set('dcnt_data',$this->DailyDiscount->read());
			//echo $this->DailyDiscount->field('DailyDiscount.extra_content');
		}
	}	
//---------------------------------------------------------------------------------------------------------------------------------//	
	function consumer_feedback() {
	if($this->Session->read('Auth.FrontConsumer'))
	{
			if(isset($this->data))
			{
				 $msg=$this->data['front_users']['feedback'];				
						#Insertimg one record in work order table to show this data in inbox of admin
							  App::import('model', 'WorkOrder');
							  $this->WorkOrder = new WorkOrder;
							  $saveWorkArray = array();
							  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $this->Session->read('Auth.FrontConsumer.id');
							  $saveWorkArray['WorkOrder']['read_status']   				=  0;
							  $saveWorkArray['WorkOrder']['subject']   					=  'Feedback from Consumer';
							  $saveWorkArray['WorkOrder']['message']   					=  $msg;
							  $saveWorkArray['WorkOrder']['type']   					=  'consumer_feedback';
							  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
							  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
							  $saveWorkArray['WorkOrder']['from_group']   				=  'Feedback';
							  date_default_timezone_set('US/Eastern');
							  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
							  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
							  $this->WorkOrder->save($saveWorkArray);
							  $this->Session->setFlash('Feedback Sent Successfully');
							  $this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/consumer_feedback/send:success');
			}
		} else {
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county'));
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//
	function fillCategory($id){
		$this->layout = false;
			$user = $this->common->getConsumerDetails($id);
			$this->loadModel('NewsletterUser');
			$newsletter = $this->NewsletterUser->find('first',array('fields'=>array('NewsletterUser.category_id','NewsletterUser.all_cats'),'conditions'=>array('NewsletterUser.email'=>$user['email'],'NewsletterUser.county_id'=>$user['county_id'])));
			$this->set('newsletter',$newsletter);
	}
	
//---------------------------------------------------------------------------------------------------------------------------------//
	function fillCategorys($id){
		$this->layout = false;
			$user = $this->common->getConsumerDetails($id);
			$this->loadModel('NewsletterUser');
			$newsletter = $this->NewsletterUser->find('first',array('fields'=>array('NewsletterUser.category_id','NewsletterUser.all_cats'),'conditions'=>array('NewsletterUser.email'=>$user['email'],'NewsletterUser.county_id'=>$user['county_id'])));
			$this->set('newsletter',$newsletter);
	}	
//---------------------------------------------------------------------------------------------------------------------------------//		
	function save_user_cats($string){
		$this->autoRender = false;
		$data = explode('|',$string);
		$user = $this->common->getUserByUnique($data[0]);
		$arr = '';
		$this->loadModel('NewsletterUser');
		$user_cat = $this->NewsletterUser->find('first',array('fields'=>array('NewsletterUser.id','NewsletterUser.category_id'),'conditions'=>array('NewsletterUser.user_id'=>$user['id'])));
		
		if(is_array($user_cat) && !empty($user_cat)) {
			$arr['NewsletterUser']['id'] = $user_cat['NewsletterUser']['id'];
			$arr['NewsletterUser']['category_id'] = $user_cat['NewsletterUser']['category_id'].','.$data[1];
		} else {
			$arr['NewsletterUser']['category_id'] = $data[1];
		}		
		$arr['NewsletterUser']['name'] = $user['name'];
		$arr['NewsletterUser']['email'] = $user['email'];
		$arr['NewsletterUser']['zipcode'] = $user['zip'];
		$arr['NewsletterUser']['user_id'] = $user['id'];
		
		$arr['NewsletterUser']['county_id'] = $user['county_id'];
		$this->NewsletterUser->save($arr);
		$savearr = '';
		$savearr['FrontUser']['id'] = $user['id'];
		$savearr['FrontUser']['total_bucks'] = $user['total_bucks']+10;
		$savearr['FrontUser']['register'] =1;
		$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
		$this->FrontUser->save($savearr);
	}
/************************************* This function call for all refered friend ***************************************/
	function referFriend($string1) {
			//pr($this->data);
			$this->autoRender = false;
			$data = explode('<>',$string1);
			$savedata = '';
			$user = $this->common->getUserByUnique($data[1]);
				
			$from = $user['email'];
			$from_name=$user['name'];
				
			$emailids 	= '';
			$fillurl 	= FULL_BASE_URL.router::url('/',false).'state/'.$data[4].'/'.$data[3];
			$repeat_email ='';
			$this->loadModel('ReferredFriend');
			$email_group = explode(',',$data[5]);
				$i = 0;
				foreach ($email_group as $emailGroup) {
					$newarr = '';
					$newarr = explode('|',$emailGroup);
				if(isset($newarr[0]) && $newarr[0]!='' && $newarr[0]!='Email') {
					$checkemail = $this->ReferredFriend->find('count',array('conditions'=>array('ReferredFriend.email'=>$newarr[0],'ReferredFriend.county_id'=>$user['county_id'])));
					if($checkemail==0) {
							$emailids[$i]['email']=$newarr[0];
							$emailids[$i]['name']=$newarr[1];
							$savedata[$i]['ReferredFriend']['name']=$newarr[1];
							$savedata[$i]['ReferredFriend']['email']=$newarr[0];
							$savedata[$i]['ReferredFriend']['front_user_id']= $user['id'];
							$savedata[$i]['ReferredFriend']['county_id'] 	= $user['county_id'];
							$savedata[$i]['ReferredFriend']['refer_ip'] 	= $_SERVER['REMOTE_ADDR'];
							$savedata[$i]['ReferredFriend']['state_id'] 	= $this->common->getStateByCountyId($user['county_id']);
							$savedata[$i]['ReferredFriend']['refered_date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
						} else {
							$repeat_email[] = $newarr[0];
						}
					}
					$i++;
				}
			if(is_array($savedata)) {
				$this->ReferredFriend->saveAll($savedata);
			}
			App::import('model', 'Setting');
			$this->Setting = new Setting;	
			$emailArray = $this->Setting->getFriendEmailData();
			$subject=$emailArray[0]['settings']['send_to_friend_subject'];
			$msg_format=$emailArray[0]['settings']['send_to_frient_body'];
			$link='<a href="'.$fillurl.'">'.$fillurl.'</a>';
			//echo $bodyText  =$this->Setting->replaceMarkersFriend($msg_format,'manoj',$link,'how r u',$from_name);
			if(is_array($emailids)) {	
			foreach ($emailids as $emailids) {
				if($emailids['email']!='') {
					$bodyText 	= '';
					$bodyText  .=$this->Setting->replaceMarkersFriend($msg_format,$emailids['name'],$link,$data[2],$from_name);
					
					//ADMINMAIL id
					$this->Email->to 		= $emailids['email'];
					$this->Email->subject 	= strip_tags($subject);
					$this->Email->replyTo 	= $this->common->getReturnEmail();
					$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
					$this->Email->sendAs 	= 'html';
					//Set the body of the mail as we send it.			
					//seperate line in the message body.
					$this->body = '';				
					$this->body = $this->emailhtml->email_header($user['county_id']);
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer($user['county_id']);

					//$this->body .= "<br />".FULL_BASE_URL.Router::url('/', false);
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$emailids['email'],strip_tags($subject),$this->body,"referred_friend");
					/////////////////////////////////////////////////////////////////////////
	
					$this->Email->reset();
			}
		}}
		if(is_array($repeat_email) && !empty($repeat_email)) {
			echo 'Thank you for referring your friend. These emails are already referred : '.implode(', ',$repeat_email);
		} else {
			echo 'Thank you for referring your friend.';
		}
	}
	////////////////////////////////// Right after consdumer registration ////////////////////////////////
	function goalCode() {
		$this->layout = false;
	}
//---------------------------------------------- Sitemap ------------------------------------------//	
	function createSitemap_old() {
		set_time_limit(0);
		$this->autoRender = false;
		$myFile = 'sitemap.xml';
		$fh = fopen($myFile, 'w') or die("can't open file");
		
		$stringData = "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">";
		fwrite($fh, $stringData);
		
		$https = 'https://zuni.com';
		$pre_url = $https.'/state/';
		
		
		$stringData = '<url><loc>'.$https.'</loc><changefreq>weekly</changefreq></url>';
		fwrite($fh, $stringData);
		
		
		
		$this->loadModel('State');
		$this->loadModel('County');
		$this->loadModel('Category');
		$this->loadModel('Subcategory');
		$this->loadModel('City');
		$this->loadModel('FrontCategory');
		$hotBut = $this->FrontCategory->find('all',array('fields'=>array('FrontCategory.page_url'),'conditions'=>array('FrontCategory.publish'=>'yes'),'Order'=>'FrontCategory.order'));
		
$State = $this->State->find('all',array('fields'=>array('State.id','State.page_url'),'conditions'=>array('State.status'=>'yes'),'Order'=>'State.statename'));

if(!empty($State)) {
foreach($State as $State) {
	$County = $this->County->find('all',array('fields'=>array('County.id','County.page_url'),'conditions'=>array('County.publish'=>'yes','County.state_id'=>$State['State']['id']),'Order'=>'County.countyname'));
	
	if(!empty($County)) {
	foreach($County as $County) {
		//---------------------- Writing state/county url ---------------------------//
		$stringData = '<url><loc>'.$pre_url.$State['State']['page_url'].'/'.$County['County']['page_url'].'</loc><changefreq>weekly</changefreq></url>';
		fwrite($fh, $stringData);
		
		//---------------------- Writing state/county/hot buttons url ---------------------------//
		if(!empty($hotBut)) {
		foreach($hotBut as $hot) {
		
			$stringData = '<url><loc>'.$pre_url.$State['State']['page_url'].'/'.$County['County']['page_url'].'/cat/'.$hot['FrontCategory']['page_url'].'</loc><changefreq>weekly</changefreq></url>';
			fwrite($fh, $stringData);
		}} // Ending Hot Buttons Loop
		
		$cities = $this->City->find('all',array('fields'=>array('City.id','City.page_url'),'conditions'=>array('City.publish'=>'yes','City.front_status'=>1,'City.state_id'=>$State['State']['id'],'City.county_id'=>$County['County']['id']),'Order'=>'City.cityname'));
		
		$cats = $this->Category->find('all',array('fields'=>array('Category.id','Category.page_url'),'conditions'=>array('Category.publish'=>'yes','Category.county LIKE \'%,'.$County['County']['id'].',%\''),'Order'=>'Category.categoryname'));
		
		if(!empty($cats)) {
		foreach($cats as $cats) {
			
			$subcats = $this->Subcategory->find('all',array('fields'=>array('Subcategory.id','Subcategory.page_url'),'conditions'=>array('Subcategory.publish'=>'yes','Subcategory.category_id LIKE \'%,'.$cats['Category']['id'].',%\''),'Order'=>'Subcategory.categoryname'));
			
			if(!empty($subcats)) {
			foreach($subcats as $subcats) {
				
				//---------------------- Writing state/county/cat/sdubcat url ---------------------------//
				$stringData = '<url><loc>'.$pre_url.$State['State']['page_url'].'/'.$County['County']['page_url'].'/'.$cats['Category']['page_url'].'/'.$subcats['Subcategory']['page_url'].'</loc><changefreq>weekly</changefreq></url>';
				fwrite($fh, $stringData);
				
				if(!empty($cities)) {
				foreach($cities as $city) {
					//---------------------- Writing state/county/city/cat/sdubcat url ---------------------------//
					$stringData = '<url><loc>'.$pre_url.$State['State']['page_url'].'/'.$County['County']['page_url'].'/'.$city['City']['page_url'].'/'.$cats['Category']['page_url'].'/'.$subcats['Subcategory']['page_url'].'</loc><changefreq>weekly</changefreq></url>';
					fwrite($fh, $stringData);
				}} // Ending City Loop
			}} // Ending Subcategory Loop
		}} // Ending Category Loop
	}} // Ending County Loop
}} // Ending State Loop

		$stringData = '</urlset>';
		fwrite($fh, $stringData);
		fclose($fh);
		//$this->redirect(FULL_BASE_URL.router::url('/',false).'sitemap.xml');
	}
	
//---------------------------------------------- Sitemap ------------------------------------------//	
	function createSitemap() {
		set_time_limit(0);
		$this->autoRender = false;
		$myFile = 'sitemap.xml';
		$fh = fopen($myFile, 'w') or die("can't open file");
		
		$stringData = "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">";
		fwrite($fh, $stringData);
		
		$https = 'https://zuni.com';
		$pre_url = $https.'/state/';
		
		
		$stringData = '<url><loc>'.$https.'</loc><changefreq>weekly</changefreq></url>';
		fwrite($fh, $stringData);
		
		
		
		$this->loadModel('State');
		$this->loadModel('County');
		$this->loadModel('Category');
		$this->loadModel('Subcategory');
		$this->loadModel('City');
		//$this->loadModel('FrontCategory');
//$hotBut = $this->FrontCategory->find('all',array('fields'=>array('FrontCategory.page_url'),'conditions'=>array('FrontCategory.publish'=>'yes'),'Order'=>'FrontCategory.order'));

//$allcats = $this->Category->find('all',array('fields'=>array('Category.id','Category.page_url'),'conditions'=>array('Category.publish'=>'yes'),'Order'=>'Category.categoryname'));


$State = $this->State->find('all',array('fields'=>array('State.id','State.page_url'),'conditions'=>array('State.status'=>'yes'),'Order'=>'State.statename'));

if(!empty($State)) {
foreach($State as $State) {
	$County = $this->County->find('all',array('fields'=>array('County.id','County.page_url'),'conditions'=>array('County.publish'=>'yes','County.state_id'=>$State['State']['id']),'Order'=>'County.countyname'));
	
	if(!empty($County)) {
	foreach($County as $County) {
		//---------------------- Writing state/county url ---------------------------//
		$stringData = '<url><loc>'.$pre_url.$State['State']['page_url'].'/'.$County['County']['page_url'].'</loc><changefreq>weekly</changefreq></url>';
		fwrite($fh, $stringData);
		
		//---------------------- Writing state/county/hot buttons url ---------------------------//
		//if(!empty($hotBut)) {
//		foreach($hotBut as $hot) {
//		
//			$stringData = '<url><loc>'.$pre_url.$State['State']['page_url'].'/'.$County['County']['page_url'].'/cat/'.$hot['FrontCategory']['page_url'].'</loc><changefreq>weekly</changefreq></url>';
//			fwrite($fh, $stringData);
//		}} // Ending Hot Buttons Loop
		
		$cities = $this->City->find('all',array('fields'=>array('City.id','City.page_url'),'conditions'=>array('City.publish'=>'yes','City.front_status'=>1,'City.state_id'=>$State['State']['id'],'City.county_id'=>$County['County']['id']),'Order'=>'City.cityname'));
		
		$allcats = '';
		$allcats = $this->Category->find('all',array('fields'=>array('Category.id','Category.page_url'),'conditions'=>array('Category.publish'=>'yes','Category.county LIKE \'%,'.$County['County']['id'].',%\''),'Order'=>'Category.categoryname'));
		
		
		if(!empty($allcats)) {
		foreach($allcats as $cats) {
			
			$subcats = '';
			$subcats = $this->Subcategory->find('all',array('fields'=>array('Subcategory.id','Subcategory.page_url'),'conditions'=>array('Subcategory.publish'=>'yes','Subcategory.category_id LIKE \'%,'.$cats['Category']['id'].',%\'','Subcategory.county LIKE \'%,'.$County['County']['id'].',%\''),'Order'=>'Subcategory.categoryname'));
			
			if(!empty($subcats)) {
			foreach($subcats as $subcats) {
				
				//---------------------- Writing state/county/cat/sdubcat url ---------------------------//
				$stringData = '<url><loc>'.$pre_url.$State['State']['page_url'].'/'.$County['County']['page_url'].'/'.$cats['Category']['page_url'].'/'.$subcats['Subcategory']['page_url'].'</loc><changefreq>weekly</changefreq></url>';
				fwrite($fh, $stringData);
				
				if(!empty($cities)) {
				foreach($cities as $city) {
					//---------------------- Writing state/county/city/cat/sdubcat url ---------------------------//
					$stringData = '<url><loc>'.$pre_url.$State['State']['page_url'].'/'.$County['County']['page_url'].'/'.$city['City']['page_url'].'/'.$cats['Category']['page_url'].'/'.$subcats['Subcategory']['page_url'].'</loc><changefreq>weekly</changefreq></url>';
					fwrite($fh, $stringData);
				}} // Ending City Loop
			}} // Ending Subcategory Loop
		}} // Ending Category Loop
	}} // Ending County Loop
}} // Ending State Loop
// Advertiser loop start
$advertisers = $this->common->getAllCompanyUrl();
if(!empty($advertisers)) {
foreach($advertisers as $advertisers) {
//---------------------- Writing state/county/city/cat/sdubcat url ---------------------------//
					$company = '<url><loc>'.$https.'/merchants/'.$advertisers['AdvertiserProfile']['page_url'].'</loc><changefreq>weekly</changefreq></url>';
					fwrite($fh, $company);
}} // Ending Advertiser Loop
				
				
		$stringData = '</urlset>';
		fwrite($fh, $stringData);
		fclose($fh);
		//$this->redirect(FULL_BASE_URL.router::url('/',false).'sitemap.xml');
	}
	
	
	
//------------------------------------------------------------------------- ZUNI NEW DESIGN -----------------------------------------------------------------------//

//------------------------------------------- Consumer Login popup------------------------------------------------------------//
function consumerLogin() {
	$this->layout = false;
}
//------------------------------------------- Consumer Login popup------------------------------------------------------------//
function offerLogin() {
	$this->layout = false;
}
//------------------------------------------- Consumer Login popup------------------------------------------------------------//
function advertiserLogin() {
	$this->layout = false;
}
//---------------------------------------------------------------------------------------------------------------------------------//		  		  
	function ConsumerloginRequest() 
	{
		$this->autoRender=false;
		if(isset($_POST['email']) && trim($_POST['email']) && isset($_POST['pass']) && trim($_POST['pass'])) {
			$email = trim($_POST['email']);
			$pass = $_POST['pass'];
			
			$this->loadModel('FrontUser');
			$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$email,'FrontUser.password'=>$this->Auth->password($pass),'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.status'=>'yes')));
			if(!empty($dbuser_info)) {
				if($this->Session->read('Auth.FrontUser')) {
						$this->Session->delete('Auth.FrontUser');
				}
					
				$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);
				// contest save 
				if($this->Session->read('Contest')) {
					$this->loadModel('Contest');
					$this->loadModel('ContestUser');
					$limit = $this->Contest->find('first',array('fields'=>array('Contest.user_limit'),'conditions'=>array('Contest.id'=>$this->Session->read('Contest.id'))));
					$userCount = $this->ContestUser->find('count',array('conditions'=>array('ContestUser.contest_id'=>$this->Session->read('Contest.id'),'ContestUser.front_user_id'=>$this->common->getConsumerIdByEmail($email))));

					if($limit['Contest']['user_limit']!='') {
						if($userCount==$limit['Contest']['user_limit']) {
							echo 'contestlimit';
							//$this->Session->delete('Contest');
							exit;
						}
						else if($userCount>$limit['Contest']['user_limit']) {
							echo 'contestlimit';
							//$this->Session->delete('Contest');
							exit;
						}
					}
						$this->contest_save($this->Session->read('Contest.id'),$this->Session->read('Contest.file'),$this->Session->read('Contest.desc'),$this->Session->read('Contest.address'),$this->Session->read('Contest.city'),$this->Session->read('Contest.zip'),$this->Session->read('Contest.county'),$this->Session->read('Contest.state'));
					echo 'contestsuccess';
					exit;
				}
				echo 'success';
				exit;
			}
			else{
				echo 'Login failed. Invalid email or password.';
				exit;
			}
		} else {
			echo 'Login failed. Invalid email or password.';
			exit;
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function validateConsumerLogin()
	{
		if(isset($this->data))
		{
			if(trim($this->data['FrontUser']['c_login_email']) && trim($this->data['FrontUser']['c_login_pass'])) {
				$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>trim($this->data['FrontUser']['c_login_email']),'FrontUser.password'=>$this->Auth->password($this->data['FrontUser']['c_login_pass']),'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.status'=>'yes')));
				if(!empty($dbuser_info)){
					if($this->Session->read('Auth.FrontUser')) {
						$this->Session->delete('Auth.FrontUser');
					}
					$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);
					//Write cookie
					if($this->data['FrontUser']['c_login_status']) {
						$this->Cookie->write('logedInUser', $dbuser_info['FrontUser']['email'], true, '+2 weeks');
					}
					
					if(isset($this->data['FrontUser']['hidden_url']) && $this->data['FrontUser']['hidden_url']!='') {
						$this->redirect($this->data['FrontUser']['hidden_url']);
					} else if($this->Session->read('Deal.unique')) {
						$this->redirect($this->common->getDealByUnique($this->Session->read('Deal.unique')));
					} else if($this->Session->read('Discount.unique')) {
						$this->redirect($this->common->getDiscountByUnique($this->Session->read('Discount.unique')));
					} else {
						$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrls($dbuser_info['FrontUser']['state_id']).'/'.$this->common->getCountyUrl($dbuser_info['FrontUser']['county_id']));
					}
				} else {
					$this->redirect(FULL_BASE_URL.router::url('/',false));
				}
			} else {
				$this->redirect(FULL_BASE_URL.router::url('/',false));
			}
		} else {
			$this->redirect(FULL_BASE_URL.router::url('/',false));
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//
	function forgot_password_consumer()
	{
		$this->autoRender=false;
		if(isset($_POST['email']) && trim($_POST['email'])) {
			$email = trim($_POST['email']);
			$exist_user=$this->FrontUser->find('first',array('fields'=>array('FrontUser.realpassword'),'conditions'=>array('FrontUser.email'=>$email,'FrontUser.status'=>'yes','(FrontUser.user_type="customer" OR FrontUser.user_type="parent")')));
			
			if(count($exist_user)>0 && !empty($exist_user)) {
				$arrayTags = array("[password]");
				$arrayReplace = array($exist_user['FrontUser']['realpassword']);
				
				//get Mail format
				$this->loadModel('Setting');
				$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.password_subject','Setting.password_body')));
				$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['password_subject']);
				$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['password_body']);
				
				//ADMINMAIL id
				$this->Email->to 		= $email;
				$this->Email->subject 	= strip_tags($subject);
				$this->Email->replyTo 	= $this->common->getReturnEmail();
				$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
				$this->Email->sendAs 	= 'html';
				//Set the body of the mail as we send it.			
				//seperate line in the message body.
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
					$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"consumer_forgot_password");
				/////////////////////////////////////////////////////////////////////////
					echo 'success';
				} else {
					echo 'You have entered incorrect email id.';
				}
		} else {
			echo 'You have entered incorrect email id.';
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//		  		  
	function AdvertiserloginRequest() 
	{
		$this->autoRender=false;
		if(isset($_POST['email']) && trim($_POST['email']) && isset($_POST['pass']) && trim($_POST['pass'])) {
			$email = trim($_POST['email']);
			$pass = $_POST['pass'];
			
			$this->loadModel('FrontUser');
			
			$dbuser_info = '';
			
			if($this->common->getMasterPassword()==$pass) {
				$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$email,'FrontUser.user_type'=>'advertiser','FrontUser.status'=>'yes')));
			}
		
			if(!is_array($dbuser_info)) {
				$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$email,'FrontUser.password'=>$this->Auth->password($pass),'FrontUser.user_type'=>'advertiser','FrontUser.status'=>'yes')));
			}

			if(is_array($dbuser_info) && !empty($dbuser_info)){


				if($this->Session->read('Auth.FrontConsumer')) {
						$this->Session->delete('Auth.FrontConsumer');
				}
				$this->Session->write('Auth.FrontUser',$dbuser_info['FrontUser']);
				echo 'success';
				exit;
			}
			else{
				echo 'Login failed. Invalid email or password.';
				exit;
			}
		} else {
			echo 'Login failed. Invalid email or password.';
			exit;
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function validateAdvertiserLogin()
	{
		if(isset($this->data))
		{
			if(trim($this->data['FrontUser']['c_login_email']) && trim($this->data['FrontUser']['c_login_pass'])) {				
				$dbuser_info = '';
			
			if($this->common->getMasterPassword()==$this->data['FrontUser']['c_login_pass']) {
				$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>trim($this->data['FrontUser']['c_login_email']),'FrontUser.user_type'=>'advertiser','FrontUser.status'=>'yes')));
			}
		
			if(!is_array($dbuser_info)) {
				$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>trim($this->data['FrontUser']['c_login_email']),'FrontUser.password'=>$this->Auth->password($this->data['FrontUser']['c_login_pass']),'FrontUser.user_type'=>'advertiser','FrontUser.status'=>'yes')));
			}

			if(is_array($dbuser_info) && !empty($dbuser_info)){
			
					if($this->Session->read('Auth.FrontConsumer')) {
							$this->Session->delete('Auth.FrontConsumer');
					}
					$this->Session->write('Auth.FrontUser',$dbuser_info['FrontUser']);
					//Write cookie
					if($this->data['FrontUser']['c_login_status']) {
						$this->Cookie->write('logedInAdvertiser', $dbuser_info['FrontUser']['email'], true, '+2 weeks');
					}
					$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrl($dbuser_info['FrontUser']['county_id']).'/'.$this->common->getCountyUrl($dbuser_info['FrontUser']['county_id']).'/profile');
				} else {
					$this->redirect(FULL_BASE_URL.router::url('/',false));
				}
			} else {
				$this->redirect(FULL_BASE_URL.router::url('/',false));
			}
		} else {
			$this->redirect(FULL_BASE_URL.router::url('/',false));
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//
	function forgot_password_advertiser()
	{
		$this->autoRender=false;
		if(isset($_POST['email']) && trim($_POST['email'])) {
			$email = trim($_POST['email']);
			$exist_user=$this->FrontUser->find('first',array('fields'=>array('FrontUser.realpassword'),'conditions'=>array('FrontUser.email'=>$email,'FrontUser.status'=>'yes','FrontUser.user_type'=>'advertiser')));
			
			if(count($exist_user)>0 && !empty($exist_user)) {
				$arrayTags = array("[password]");
				$arrayReplace = array($exist_user['FrontUser']['realpassword']);
				
				//get Mail format
				$this->loadModel('Setting');
				$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.password_subject','Setting.password_body')));
				$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['password_subject']);
				$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['password_body']);
				
				//ADMINMAIL id
				$this->Email->to 		= $email;
				$this->Email->subject 	= strip_tags($subject);
				$this->Email->replyTo 	= $this->common->getReturnEmail();
				$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
				$this->Email->sendAs 	= 'html';
				//Set the body of the mail as we send it.			
				//seperate line in the message body.
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
					$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"advertiser_forgot_password");
				/////////////////////////////////////////////////////////////////////////
					echo 'success';
				} else {
					echo 'You have entered incorrect email id.';
				}
		} else {
			echo 'You have entered incorrect email id.';
		}
	}	
//---------------------------------------------------------------------------------------------------------------------------------//
	function userSignup()
	{
		$this->layout = false;
	}
//---------------------------------------------------------------------------------------------------------------------------------//
	function merchantEmail()
	{
		$this->layout = false;
	}

//---------------------------------------------------------------------------------------------------------------------------------//
	function userPage()
	{
		$this->layout = false;
		if($this->Cookie->read('logedInUser')) {
			if($this->common->checkUserEmail($this->Cookie->read('logedInUser'))) {
				$this->redirect($this->common->getFullUrl($this->Cookie->read('logedInUser')));
			} else {
				$this->Cookie->delete('logedInUser');
			}
		}
	}	
//---------------------------------------------------------------------------------------------------------------------------------//
	function userEmailValidate()
	{
		$this->autoRender = false;
		if(isset($_POST['email']) && trim($_POST['email'])!='') {
			$email = trim($_POST['email']);
			$totalProfile = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.email'=>$email,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")')));
			
			$this->loadModel('NewsletterUser');
			$totalNewsltr = $this->NewsletterUser->find('count',array('conditions'=>array('NewsletterUser.email'=>$email,'NewsletterUser.status'=>'yes')));
			
			if($totalProfile>0 || $totalNewsltr>0) {
				echo 'Email address is already in use.';
				exit;
			}
			if($this->Session->read('Contest')) {
				echo 'contestsuccess';
				exit;
			}
				echo 'success';
				exit;
		} else {
			echo 'Invalid data.';
			exit;
		}	
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function validateSignup($data) {
	
		$email = trim($data['email']);
		$totalProfile = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.email'=>$email,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")')));
		$this->loadModel('NewsletterUser');
		$totalNewsltr = $this->NewsletterUser->find('count',array('conditions'=>array('NewsletterUser.email'=>$email,'NewsletterUser.status'=>'yes')));
		if($totalProfile>0 || $totalNewsltr>0) {
			return false;
		}
		if(trim($data['f_name'])=='') {
			return false;
		} else if(trim($data['l_name'])=='') {
			return false;
		} else if($email=='') {
			return false;
		} else if(trim($data['confirm_email'])=='') {
			return false;
		} else if(trim($data['password'])=='') {
			return false;
		} else if(trim($data['confirm_password'])=='') {
			return false;
		} else if(trim($data['area'])=='') {
			return false;
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		} return true;
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function userSignupSave() {
		$this->autoRender = false;
		if(isset($this->data)) {
			if($this->validateSignup($this->data['FrontUser'])) {
				//--------------------------------------------------------------------//
				$date = date('Y-m-d h:i:s');
				$area = explode('-',$this->data['FrontUser']['area']);
				$this->data['FrontUser']['name']		=	$this->data['FrontUser']['f_name'].' '.$this->data['FrontUser']['l_name'];
				$this->data['FrontUser']['realpassword']= 	$this->data['FrontUser']['password'];
				$this->data['FrontUser']['password'] 	= 	$this->Auth->password($this->data['FrontUser']['password']);
				$this->data['FrontUser']['state_id']	=	$area[0];
				$this->data['FrontUser']['county_id']	=	$area[1];
				$this->data['FrontUser']['user_type']	=	'customer';
				$this->data['FrontUser']['type']		=	'Front End';
				$this->data['FrontUser']['status']		=	'yes';
				$this->data['FrontUser']['terms_condition']	= $this->data['FrontUser']['terms'];
				$this->data['FrontUser']['unique_id']	= $this->common->randomPassword(15);
				$this->data['FrontUser']['total_bucks']	= 5;
				
				$this->FrontUser->save($this->data);
				$consumer_id = $this->FrontUser->getlastinsertid();
				
				$this->loadModel('NewsletterUser');		
				$arr['NewsletterUser']['name'] = $this->data['FrontUser']['name'];
				$arr['NewsletterUser']['email'] = $this->data['FrontUser']['email'];
				$arr['NewsletterUser']['user_id'] = $consumer_id;
				$arr['NewsletterUser']['all_cats'] = 1;
				$arr['NewsletterUser']['county_id'] = $area[1];				
				$this->NewsletterUser->save($arr);
				
				$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.id'=>$consumer_id)));
				$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);
				// contest save 
				if($this->Session->read('Contest')) {
					$this->contest_save($this->Session->read('Contest.id'),$this->Session->read('Contest.file'),$this->Session->read('Contest.desc'),$this->Session->read('Contest.address'),$this->Session->read('Contest.city'),$this->Session->read('Contest.zip'),$this->Session->read('Contest.county'),$this->Session->read('Contest.state'));
					$this->Session->delete('Contest');
				}
				
				//Write cookie
					if($this->data['FrontUser']['login_status']) {
						$this->Cookie->write('logedInUser', $this->data['FrontUser']['email'], true, '+2 weeks');
					}
					
				$this->loadModel('Setting');
				$setvale = $this->Setting->find('first',array('fields'=>array('refer_friend_bucks')));
				$bucksprice = $setvale['Setting']['refer_friend_bucks'];
				$this->loadModel('ReferredFriend');
				$checkRefer = $this->ReferredFriend->find('first',array('conditions'=>array('ReferredFriend.email'=>$this->data['FrontUser']['email'],'ReferredFriend.status'=>'no')));
				if(is_array($checkRefer) && !empty($checkRefer)) {					
					$savearr['ReferredFriend']['id'] = $checkRefer['ReferredFriend']['id'];
					$savearr['ReferredFriend']['status'] = 'yes';
					$savearr['ReferredFriend']['bucks'] = $bucksprice;
					$savearr['ReferredFriend']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
					$savearr['ReferredFriend']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$savearr['FrontUser']['id'] = $checkRefer['FrontUser']['id'];
					$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;		
					$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(15);			
					$this->ReferredFriend->save($savearr);
					$this->FrontUser->save($savearr);
					
					$this->loadModel('Buck');
					$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$area[1],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
					if(is_array($checkBuck) && count($checkBuck)) {
						$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
						$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
					} else {
						$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
						$saveBuck['Buck']['county_id'] = $area[1];
						$saveBuck['Buck']['bucks'] = $bucksprice;
						$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					}
					$this->Buck->save($saveBuck);
			}
				//------------------------------------------ Welcome Email -----------------------------------//
					$arrayTags = array("[consumer_name]","[url]");
					$full_url = FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrl($area[1]).'/'.$this->common->getCountyUrl($area[1]);
					$url = '<a href="'.$full_url.'" target="_blank">'.$full_url.'</a>';
					$arrayReplace = array($this->data['FrontUser']['name'],$url);
					//get Mail format
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.new_consumer_subject','Setting.new_consumer_body','Setting.newsletter_from_email')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_subject']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_body']);
					//ADMINMAIL id
					
					$this->body = '';
					$this->body = $this->emailhtml->email_header($area[1]);
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer($area[1]);
					
					$this->Email->to 		= $this->data['FrontUser']['email'];
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$this->data['FrontUser']['email'],strip_tags($subject),$this->body,"new_consumer_registration");
					/////////////////////////////////////////////////////////////////////////
					$goal_code = '?user='.base64_encode('success');
					if($this->Session->read('Deal.unique')) {
						$this->redirect($this->common->getDealByUnique($this->Session->read('Deal.unique')));
					} else if($this->Session->read('Discount.unique')) {
						$this->redirect($this->common->getDiscountByUnique($this->Session->read('Discount.unique')));
					} else {
						$this->redirect($full_url.$goal_code);
					}	
				//-------------------------------------------------------------------//
			} else {
				$this->redirect(FULL_BASE_URL.router::url('/',false));
			}
		} else {
			$this->redirect(FULL_BASE_URL.router::url('/',false));
		}				
	}
	
	function savefFbData() {
			define('FACEBOOK_APP_ID', '490348541029842');
            define('FACEBOOK_SECRET', '2581eb7c31fe30332a1d8b1c5814eca9');

			// No need to change function body
            function parse_signed_request($signed_request, $secret) {
                list($encoded_sig, $payload) = explode('.', $signed_request, 2);

                // decode the data
                $sig = base64_url_decode($encoded_sig);
                $data = json_decode(base64_url_decode($payload), true);

                if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
                    error_log('Unknown algorithm. Expected HMAC-SHA256');
                    return null;
                }

                // check sig
                $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
                if ($sig !== $expected_sig) {
                    error_log('Bad Signed JSON signature!');
                    return null;
                }

                return $data;
            }

            function base64_url_decode($input) {
                return base64_decode(strtr($input, '-_', '+/'));
            }

            if ($_REQUEST) {
                $response = parse_signed_request($_REQUEST['signed_request'],
                                FACEBOOK_SECRET);
				
				$email = $response["registration"]["email"];
				if($this->common->validateEmail($email)) {
				
				$areas = str_replace('Array','',$response["registration"]["area"]);
				//--------------------------------------------------------------------//
				$date = date('Y-m-d h:i:s');
				$area = explode('-',$areas);
				$this->data['FrontUser']['name']		=	$response["registration"]["name"];
				$this->data['FrontUser']['email']		=	$email;
				$this->data['FrontUser']['realpassword']= 	$response["registration"]['password'];
				$this->data['FrontUser']['password'] 	= 	$this->Auth->password($response["registration"]['password']);
				$this->data['FrontUser']['state_id']	=	$area[0];
				$this->data['FrontUser']['county_id']	=	$area[1];
				$this->data['FrontUser']['user_type']	=	'customer';
				$this->data['FrontUser']['type']		=	'Front End';
				$this->data['FrontUser']['status']		=	'yes';
				$this->data['FrontUser']['terms_condition']	= 1;
				$this->data['FrontUser']['unique_id']	= $this->common->randomPassword(15);
				
				$this->data['FrontUser']['total_bucks']	= 5;
				
				
				$this->FrontUser->save($this->data);
				$consumer_id = $this->FrontUser->getlastinsertid();
				
				$this->loadModel('NewsletterUser');		
				$arr['NewsletterUser']['name'] = $response["registration"]["name"];
				$arr['NewsletterUser']['email'] = $email;
				$arr['NewsletterUser']['user_id'] = $consumer_id;
				$arr['NewsletterUser']['all_cats'] = 1;
				$arr['NewsletterUser']['county_id'] = $area[1];				
				$this->NewsletterUser->save($arr);
				
				$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.id'=>$consumer_id)));
				$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);
				// contest save 
				if($this->Session->read('Contest')) {
					$this->contest_save($this->Session->read('Contest.id'),$this->Session->read('Contest.file'),$this->Session->read('Contest.desc'),$this->Session->read('Contest.address'),$this->Session->read('Contest.city'),$this->Session->read('Contest.zip'),$this->Session->read('Contest.county'),$this->Session->read('Contest.state'));
					$this->Session->delete('Contest');
				}
				//Write cookie
					if($response["registration"]["status"]) {
						$this->Cookie->write('logedInUser', $email, true, '+2 weeks');
					}
					
				$this->loadModel('Setting');
				$setvale = $this->Setting->find('first',array('fields'=>array('refer_friend_bucks')));
				$bucksprice = $setvale['Setting']['refer_friend_bucks'];
				$this->loadModel('ReferredFriend');
				$checkRefer = $this->ReferredFriend->find('first',array('conditions'=>array('ReferredFriend.email'=>$email,'ReferredFriend.status'=>'no')));
				if(is_array($checkRefer) && !empty($checkRefer)) {					
					$savearr['ReferredFriend']['id'] = $checkRefer['ReferredFriend']['id'];
					$savearr['ReferredFriend']['status'] = 'yes';
					$savearr['ReferredFriend']['bucks'] = $bucksprice;
					$savearr['ReferredFriend']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
					$savearr['ReferredFriend']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$savearr['FrontUser']['id'] = $checkRefer['FrontUser']['id'];
					$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;		
					$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(15);			
					$this->ReferredFriend->save($savearr);
					$this->FrontUser->save($savearr);
					
					$this->loadModel('Buck');
					$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$area[1],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
					if(is_array($checkBuck) && count($checkBuck)) {
						$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
						$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
					} else {
						$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
						$saveBuck['Buck']['county_id'] = $area[1];
						$saveBuck['Buck']['bucks'] = $bucksprice;
						$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					}
					$this->Buck->save($saveBuck);
			}
				//------------------------------------------ Welcome Email -----------------------------------//
					$arrayTags = array("[consumer_name]","[url]");
					$full_url = FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrl($area[1]).'/'.$this->common->getCountyUrl($area[1]);
					$url = '<a href="'.$full_url.'" target="_blank">'.$full_url.'</a>';
					$arrayReplace = array($response["registration"]["name"],$url);
					//get Mail format
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.new_consumer_subject','Setting.new_consumer_body','Setting.newsletter_from_email')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_subject']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['new_consumer_body']);
					//ADMINMAIL id
					
					$this->body = '';
					$this->body = $this->emailhtml->email_header($area[1]);
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer($area[1]);
					
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
					$this->Email->delivery = 'smtp';
					$this->Email->send($this->body);
					
					///////////////////////////sent mail insert to sent box ///////////////////
						$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"new_consumer_registration");
					/////////////////////////////////////////////////////////////////////////	
					$this->redirect($full_url);
				//-------------------------------------------------------------------//
			} else {
					$this->Session->setFlash('Email address is already in use.');
					$this->redirect(FULL_BASE_URL.router::url('/',false).'fbSignup');
				}
            } else {
                echo '$_REQUEST is empty';
            }
		exit;
	}
//---------------------------------------------------------------------------------------------------------------------//	
	function fbSignup() {
		$this->layout = false;
	}
//------------------------------------------------------------------------------------------------------------------------------//
	function contest_save($contest_id='',$attachment='',$desc='',$address='',$city='',$zip='',$county='',$state='') {
		if($attachment=='blank') {
			$attachment = '';
		}
		if($desc=='blank') {
			$desc = '';
		}
		$this->autoRender = false;
		if($this->Session->read('Auth.FrontConsumer.id')) {
		
				$this->loadModel('Contest');
				$this->loadModel('ContestUser');
				$limit = $this->Contest->find('first',array('fields'=>array('Contest.user_limit'),'conditions'=>array('Contest.id'=>$contest_id)));
				$userCount = $this->ContestUser->find('count',array('conditions'=>array('ContestUser.contest_id'=>$contest_id,'ContestUser.front_user_id'=>$this->Session->read('Auth.FrontConsumer.id'))));
				
				if(($userCount<$limit['Contest']['user_limit']) || !$limit['Contest']['user_limit']) {
				
							$this->loadModel('ContestUser');
							$savearr = '';
							$savearr['ContestUser']['contest_id'] 	= $contest_id;
							$savearr['ContestUser']['front_user_id']= $this->Session->read('Auth.FrontConsumer.id');
							$savearr['ContestUser']['description'] 	= $desc;
							$savearr['ContestUser']['attachment'] 	= $attachment;
							$savearr['ContestUser']['created'] 		= time();
							$this->ContestUser->save($savearr);
							
							$save = '';
							$save['FrontUser']['id'] 	= $this->Session->read('Auth.FrontConsumer.id');
							$save['FrontUser']['address']= $address;
							$save['FrontUser']['city_id'] 	= $city;
							$save['FrontUser']['zip'] 	= $zip;
							$this->FrontUser->save($save,false);
						}	
				}
			}	
//------------------------------------------------------------------------------------------------------------------------------//
	function contest_session($contest_id='',$attachment='',$desc='',$address='',$city='',$zip='',$county='',$state='') {
		if($attachment=='blank') {
			$attachment = '';
		}
		if($desc=='blank') {
			$desc = '';
		}
		$this->autoRender = false;
		$this->Session->write('Contest.id',$contest_id);
		$this->Session->write('Contest.file',$attachment);
		$this->Session->write('Contest.desc',$desc);
		$this->Session->write('Contest.address',$address);
		$this->Session->write('Contest.city',$city);
		$this->Session->write('Contest.zip',$zip);
		$this->Session->write('Contest.county',$county);
		$this->Session->write('Contest.state',$state);
	}
//------------------------------------------------------------------------------------------------------------------------------//
	function dealSession($dealUnique='') {
		$this->Session->write('Deal.unique',$dealUnique);
		$this->redirect(FULL_BASE_URL.router::url('/',false).'userPage');
	}
//------------------------------------------------------------------------------------------------------------------------------//
	function discountSession($diskUnique='') {
		$this->Session->write('Discount.unique',$diskUnique);
		$this->redirect(FULL_BASE_URL.router::url('/',false).'userPage');
	}
/************************************* This function call for browse the county ***************************************/				
	function browseCounty() {
			$this->layout = false;
	}
//---------------------------------------------------------------------------------------------------------------------------------//
	function saveUserData() {
		$this->autoRender = false;
		if(!$this->Session->read('Auth.FrontConsumer.id')) {
			echo 'Session time out. Please login again.';
			exit;
		} else if(isset($_POST['discount_id']) && trim($_POST['discount_id'])!='' && isset($_POST['cardHolder']) && trim($_POST['cardHolder'])!='' && isset($_POST['card']) && trim($_POST['card'])!='' && isset($_POST['address']) && trim($_POST['address'])!='' && isset($_POST['city']) && trim($_POST['city'])!='' && isset($_POST['state']) && trim($_POST['state'])!='' && isset($_POST['zip']) && trim($_POST['zip'])!='' && isset($_POST['cardno']) && trim($_POST['cardno'])!='' && isset($_POST['security']) && trim($_POST['security'])!='' && isset($_POST['exp_m']) && trim($_POST['exp_m'])!='' && isset($_POST['exp_y']) && trim($_POST['exp_y'])!='' && isset($_POST['voucher']) && trim($_POST['voucher'])!='' && isset($_POST['total']) && trim($_POST['total'])!='') {
			extract($_POST);
				
			$user_limit = $this->common->discountLimit($discount_id);
			$totalPurchase = $this->common->totalPurchase($this->Session->read('Auth.FrontConsumer.id'),$discount_id);
				
			if(($voucher+$totalPurchase)>$user_limit) {
				echo "You have already purchased $totalPurchase vouchers. Please check limit per person.";
				exit;
			}
				
			$userAvail = 0;
			$resetPassword = 0;
			$totalcount = $this->common->getConsumerDetails($this->Session->read('Auth.FrontConsumer.id'));
			
			if(empty($totalcount)) {
				echo 'Invalid Login.';
				exit;
			} else {
				$front_user_id = $totalcount['id'];
				$front_user_email = $totalcount['email'];
				$front_user_name = $totalcount['name'];
				$unique_id = $totalcount['unique_id'];
			}
			
			//////////////////// Payment Gateway //////////////////////////
					
					$authNameArr=explode(' ',$cardHolder);
					
					$auth_fname=$authNameArr[0];
					$auth_lname=$authNameArr[1];
					
					$amount = number_format($total,1);
					
					if((intval($exp_m))<10)
						$exp_m="0".$exp_m;
						
					$final_exp_date=$exp_m.$exp_y;
					
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
						"x_card_num"		=> $cardno,
						"x_exp_date"		=> $final_exp_date,
					
						"x_amount"			=> $amount,
						"x_description"		=> "",
					
						"x_first_name"		=> $auth_fname,
						"x_last_name"		=> $auth_lname,
						"x_address"			=> $address,
						"x_state"			=> $state,
						"x_zip"				=> $zip
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
					
										
					
					if(isset($response_array[0]) && $response_array[0]!='' && $response_array[0]=='1')
					{
						$CreditCardTransID = $response_array[6];
						$ClientTransID = $response_array[7];
						
						date_default_timezone_set('US/Eastern');
						
						$TStamp = mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						
						//Saving data//
						$arr1 = '';
						$this->loadModel('DiscountUser');
						$arr1['DiscountUser']['daily_discount_id'] = $discount_id;
						$arr1['DiscountUser']['vouchers'] = $voucher;
						$arr1['DiscountUser']['total_price'] = $total;
						$arr1['DiscountUser']['transaction_id'] = $CreditCardTransID;
						$arr1['DiscountUser']['transaction_client_id'] = $ClientTransID;
						$arr1['DiscountUser']['transaction_date'] = $TStamp;
						$arr1['DiscountUser']['purchase_date'] = mktime(0,0,0,date('m'),date('d'),date('Y'));

						$this->loadModel('DailyDiscount');
						$total_purchase = $this->DailyDiscount->find('first',array('fields'=>array('DailyDiscount.total_purchase','DailyDiscount.title','DailyDiscount.advertiser_profile_id','DailyDiscount.unique','DailyDiscount.advertiser_county_id'),'conditions'=>array('DailyDiscount.id'=>$discount_id)));
							 
						$disarr['DailyDiscount']['id'] = $discount_id;
						$disarr['DailyDiscount']['total_purchase'] = $total_purchase['DailyDiscount']['total_purchase']+$voucher;						
						$this->DailyDiscount->save($disarr);
											
						$arr1['DiscountUser']['advertiser_profile_id'] = $total_purchase['DailyDiscount']['advertiser_profile_id'];
						$arr1['DiscountUser']['front_user_id'] = $front_user_id;
						$this->DiscountUser->save($arr1);
						
						$discountuser_id = $this->DiscountUser->getlastinsertid();
						$this->loadModel('DiscountInfo');
						$uniquearr = '';
						$print_link = '<br />';
						$encoded_email = base64_encode($front_user_email);
						for($w=0;$w<$voucher;$w++) {
							$unique = $this->common->randomPassword(10);
							$print_link .= '<a href="'.FULL_BASE_URL.router::url('/',false).'daily_discounts/printVoucher/'.$total_purchase['DailyDiscount']['unique'].'/'.$unique.'/'.$encoded_email.'">Print Voucher '.($w+1).'</a><br />';
							$uniquearr[] = $unique;
							$savearr = '';
							$savearr['DiscountInfo']['id'] = '';
							$savearr['DiscountInfo']['discount_user_id'] = $discountuser_id;
							$savearr['DiscountInfo']['daily_discount_id'] = $discount_id;
							$savearr['DiscountInfo']['voucher'] = $unique;
							$this->DiscountInfo->save($savearr);
						}
						
						$single_price = $total/$voucher;
			
							 
						$this->sendDiscountMail($front_user_email,$front_user_name,$total_purchase['DailyDiscount']['title'],$this->common->getCompanyNameById($total_purchase['DailyDiscount']['advertiser_profile_id']),$voucher,$single_price,$total,$print_link,$total_purchase['DailyDiscount']['advertiser_county_id']);
						
							//echo 'success'; exit;
							echo $CreditCardTransID.'trans123'; exit;
						} else	{
							echo 'Error : '.$response_array[3];	//$MS->errorMessage();
							exit;
						}
			} else {
			echo 'Invalid data.';
			exit;
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//
	function __saveUserData__() {
		$this->autoRender = false;
		if(!$this->Session->read('Auth.FrontConsumer.id')) {
			echo 'Session time out. Please login again.';
			exit;
		} else if(isset($_POST['discount_id']) && trim($_POST['discount_id'])!='' && isset($_POST['cardHolder']) && trim($_POST['cardHolder'])!='' && isset($_POST['card']) && trim($_POST['card'])!='' && isset($_POST['address']) && trim($_POST['address'])!='' && isset($_POST['city']) && trim($_POST['city'])!='' && isset($_POST['state']) && trim($_POST['state'])!='' && isset($_POST['zip']) && trim($_POST['zip'])!='' && isset($_POST['cardno']) && trim($_POST['cardno'])!='' && isset($_POST['security']) && trim($_POST['security'])!='' && isset($_POST['exp_m']) && trim($_POST['exp_m'])!='' && isset($_POST['exp_y']) && trim($_POST['exp_y'])!='' && isset($_POST['voucher']) && trim($_POST['voucher'])!='' && isset($_POST['total']) && trim($_POST['total'])!='') {
			extract($_POST);
				
			$user_limit = $this->common->discountLimit($discount_id);
			$totalPurchase = $this->common->totalPurchase($this->Session->read('Auth.FrontConsumer.id'),$discount_id);
				
			if(($voucher+$totalPurchase)>$user_limit) {
				echo "You have already purchased $totalPurchase vouchers. Please check limit per person.";
				exit;
			}
				
			$userAvail = 0;
			$resetPassword = 0;
			$totalcount = $this->common->getConsumerDetails($this->Session->read('Auth.FrontConsumer.id'));
			
			if(empty($totalcount)) {
				echo 'Invalid Login.';
				exit;
			} else {
				$front_user_id = $totalcount['id'];
				$front_user_email = $totalcount['email'];
				$front_user_name = $totalcount['name'];
				$unique_id = $totalcount['unique_id'];
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
					$MS = new QuickBooks_MerchantService(
					$dsn,
					$path_to_private_key_and_certificate,
					$application_login,
					$connection_ticket);
					//$MS->useTestEnvironment(true);
					$MS->useLiveEnvironment(true);
					$name = $cardHolder;
					$number = $cardno;
					$expyear =	$exp_y;
					$expmonth = $exp_m;
					$address = $address.', '.$city.', '.$state;
					$postalcode = $zip;
					$cvv = $security;
					$amount = number_format($total,1);
					//$amount = 1.0;
					$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
					
					if ($Transaction = $MS->charge($Card, $amount))
					{
						$trans_result = $Transaction->toArray();
						$CreditCardTransID = $trans_result['CreditCardTransID'];
						$ClientTransID = $trans_result['ClientTransID'];
						$TStamp = $trans_result['TxnAuthorizationStamp'];

						/*$trans_result = 1;
						$CreditCardTransID = 1;
						$ClientTransID = 1;
						$TStamp = 1;*/
						
						//Saving data//
						$arr1 = '';
						$this->loadModel('DiscountUser');
						$arr1['DiscountUser']['daily_discount_id'] = $discount_id;
						$arr1['DiscountUser']['vouchers'] = $voucher;
						$arr1['DiscountUser']['total_price'] = $total;
						$arr1['DiscountUser']['transaction_id'] = $CreditCardTransID;
						$arr1['DiscountUser']['transaction_client_id'] = $ClientTransID;
						$arr1['DiscountUser']['transaction_date'] = $TStamp;
						$arr1['DiscountUser']['purchase_date'] = mktime(0,0,0,date('m'),date('d'),date('Y'));

						$this->loadModel('DailyDiscount');
						$total_purchase = $this->DailyDiscount->find('first',array('fields'=>array('DailyDiscount.total_purchase','DailyDiscount.title','DailyDiscount.advertiser_profile_id','DailyDiscount.unique','DailyDiscount.advertiser_county_id'),'conditions'=>array('DailyDiscount.id'=>$discount_id)));
							 
						$disarr['DailyDiscount']['id'] = $discount_id;
						$disarr['DailyDiscount']['total_purchase'] = $total_purchase['DailyDiscount']['total_purchase']+$voucher;						
						$this->DailyDiscount->save($disarr);
											
						$arr1['DiscountUser']['advertiser_profile_id'] = $total_purchase['DailyDiscount']['advertiser_profile_id'];
						$arr1['DiscountUser']['front_user_id'] = $front_user_id;
						$this->DiscountUser->save($arr1);
						
						$discountuser_id = $this->DiscountUser->getlastinsertid();
						$this->loadModel('DiscountInfo');
						$uniquearr = '';
						$print_link = '<br />';
						$encoded_email = base64_encode($front_user_email);
						for($w=0;$w<$voucher;$w++) {
							$unique = $this->common->randomPassword(10);
							$print_link .= '<a href="'.FULL_BASE_URL.router::url('/',false).'daily_discounts/printVoucher/'.$total_purchase['DailyDiscount']['unique'].'/'.$unique.'/'.$encoded_email.'">Print Voucher '.($w+1).'</a><br />';
							$uniquearr[] = $unique;
							$savearr = '';
							$savearr['DiscountInfo']['id'] = '';
							$savearr['DiscountInfo']['discount_user_id'] = $discountuser_id;
							$savearr['DiscountInfo']['daily_discount_id'] = $discount_id;
							$savearr['DiscountInfo']['voucher'] = $unique;
							$this->DiscountInfo->save($savearr);
						}
						
						$single_price = $total/$voucher;
			
							 
						$this->sendDiscountMail($front_user_email,$front_user_name,$total_purchase['DailyDiscount']['title'],$this->common->getCompanyNameById($total_purchase['DailyDiscount']['advertiser_profile_id']),$voucher,$single_price,$total,$print_link,$total_purchase['DailyDiscount']['advertiser_county_id']);
						
						//echo 'success';
							echo $CreditCardTransID.'trans123';
						} else	{
							echo 'Error : '.$MS->errorMessage();
							exit;
						}
			} else {
			echo 'Invalid data.';
			exit;
		}
	}
	
//---------------------------------------------------------------------------------------------------------------------//	
	function setDefaultCountyState()
	{
		$this->autoRender=false;
		//echo $_POST['currentState']; exit;
		if(isset($_POST['currentStateUrl']) && trim($_POST['currentStateUrl']) && isset($_POST['currentCountyUrl']) && trim($_POST['currentCountyUrl'])) {
			
			if($this->Session->read('Auth.FrontConsumer'))
			{
			
				$this->Cookie->write('LastUrl', 'state/'.trim($_POST['currentStateUrl']).'/'.trim($_POST['currentCountyUrl']), true, '+30 day');
				
				
				$currStateId=$this->common->getStateIdByUrl(trim($_POST['currentStateUrl']));
				$currCountyId=$this->common->getCountyIdByUrl(trim($_POST['currentCountyUrl']));
				
				$this->loadModel('FrontUser');
				
				$frontUserSaveData='';
				$frontUserSaveData['FrontUser']['id']=$this->Session->read('Auth.FrontConsumer.id');
				$frontUserSaveData['FrontUser']['state_id']=$currStateId;
				$frontUserSaveData['FrontUser']['county_id']=$currCountyId;
				
				$user_id = $this->Session->read('Auth.FrontConsumer.id');
				$this->loadModel('NewsletterUser');
				$newsletter_id = $this->NewsletterUser->find('first',array('fields'=>array('NewsletterUser.id'),'conditions'=>array('NewsletterUser.user_id'=>$user_id)));
				if(isset($newsletter_id['NewsletterUser']['id'])) {
					$save = '';
					$save['NewsletterUser']['id'] = $newsletter_id['NewsletterUser']['id'];
					$save['NewsletterUser']['county_id'] = $currCountyId;
					$this->NewsletterUser->save($save);
				}
				
				if($this->FrontUser->save($frontUserSaveData))
				{
					$this->Session->write('Auth.FrontConsumer.county_id',$currCountyId);
					$this->Session->write('Auth.FrontConsumer.state_id',$currStateId);
					echo 'success';
					exit;
				}else{
					echo 'doNotSave';
					exit;
				}
			}else{
				echo 'notLoggedIn';
				exit;
			}
		} else {
			echo 'Invalid data.';
			exit;
		}
	}
/************************************* This function call for all refered friend ***************************************/				
	function referAFriend() {
			$this->autoRender = false;
			$savedata = '';
			if(!$this->Session->read('Auth.FrontConsumer.id')) {
				echo 'Invalid data';
				exit;
			}
			else if(isset($_POST['name']) && $_POST['name']!='' && isset($_POST['email']) && $_POST['email']!='' && isset($_POST['state']) && $_POST['state']!='' && isset($_POST['county']) && $_POST['county']!='') {
				extract($_POST);
				$user 		= 	$this->common->getConsumerDetails($this->Session->read('Auth.FrontConsumer.id'));
				$from 		= 	$user['email'];
				$from_name	=	$user['name'];
				
				$this->loadModel('ReferredFriend');
				$checkemail = $this->ReferredFriend->find('count',array('conditions'=>array('ReferredFriend.email'=>$email)));
				if($checkemail==0) {
						$county_id = $this->common->getCountyIdByUrl($county);
						$savedata = '';
						$savedata['ReferredFriend']['name']	=	$name;
						$savedata['ReferredFriend']['email'] =	$email;
						$savedata['ReferredFriend']['front_user_id'] 	= $user['id'];
						$savedata['ReferredFriend']['refer_ip'] 		= $_SERVER['REMOTE_ADDR'];
						$savedata['ReferredFriend']['county_id'] 		= $county_id;
						$savedata['ReferredFriend']['state_id'] 		= $this->common->getStateIdByUrl($state);
						$savedata['ReferredFriend']['refered_date'] 	= mktime(0, 0, 0, date('m'), date('d'), date('Y'));
						$this->ReferredFriend->save($savedata);
						
						$fillurl = FULL_BASE_URL.router::url('/',false).'state/'.$state.'/'.$county;
						$this->loadModel('Setting');
						$emailArray = $this->Setting->getFriendEmailData();
						$subject=$emailArray[0]['settings']['send_to_friend_subject'];
						$msg_format=$emailArray[0]['settings']['send_to_frient_body'];
						$link='<a href="'.$fillurl.'">'.$fillurl.'</a>';
						
						
						$bodyText 	= '';
						$bodyText  .=$this->Setting->replaceMarkersFriend($msg_format,$name,$link,$msg,$from_name);
						
						//ADMINMAIL id
						$this->Email->to 		= $email;
						$this->Email->subject 	= strip_tags($subject);
						$this->Email->replyTo 	= $this->common->getReturnEmail();
						$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
						$this->Email->sendAs 	= 'html';
						//Set the body of the mail as we send it.			
						//seperate line in the message body.
						$this->body = '';				
						$this->body = $this->emailhtml->email_header($county_id);
						$this->body .=$bodyText;
						$this->body .= $this->emailhtml->email_footer($county_id);
			
						//$this->body .= "<br />".FULL_BASE_URL.Router::url('/', false);
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
						$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"referred_friend");
			
						echo 'Thank you for referring your friend.';
						exit;
			
						
					} else {
						echo 'This email id is already referred.';
						exit;
					}
			}
	}
	
	//---------------------------------------------------------------------------------------------------------------------------------//		
	function referbusiness() {
			$this->autoRender = false;
			$savedata = '';
			if(!$this->Session->read('Auth.FrontConsumer.id')) {
				echo 'Invalid data';
				exit;
			} else if(!$this->common->refer_check($this->Session->read('Auth.FrontConsumer.id'))) {
				echo 'You cannot refer another business until 1 month after your last referral.';
				exit;
			}
			else if(isset($_POST['company']) && trim($_POST['company'])!='' && trim($_POST['company'])!='Business Name' && isset($_POST['city']) && trim($_POST['city'])!='' && trim($_POST['city'])!='Business City' && isset($_POST['state']) && trim($_POST['state'])!='' && trim($_POST['state'])!='Business State' && isset($_POST['phone']) && trim($_POST['phone'])!='' && trim($_POST['phone'])!='Business Phone Number') {
			
			
				extract($_POST);
				
					$address = (trim($address)=='Business Address') ? '' : $address;
					$zip = (trim($zip)=='Business Zip Code') ? '' : $zip;
					$name = (trim($name)=='Business Contact Name') ? '' : $name;
					$email = (trim($email)=='Business Email') ? '' : $email;
	
	
	
				$user 		= 	$this->common->getConsumerDetails($this->Session->read('Auth.FrontConsumer.id'));
				$from 		= 	$user['email'];
				$from_name	=	$user['name'];
				
				$this->loadModel('ReferredBusiness');
				$checkemail = $this->ReferredBusiness->find('count',array('conditions'=>array('ReferredBusiness.phone'=>$phone)));
				if($checkemail==0) {
						$county_id = $this->common->getCountyIdByUrl($county);
						$state_id = $this->common->getStateIdByUrl($state);
						$savedata = '';
						$savedata['ReferredBusiness']['company_name']	=	$company;
						$savedata['ReferredBusiness']['name']	=	$name;
						$savedata['ReferredBusiness']['email'] =	$email;
						$savedata['ReferredBusiness']['phone'] 	= $phone;
						$savedata['ReferredBusiness']['address'] 	= $address;
						$savedata['ReferredBusiness']['zipcode'] 	= $zip;
						$savedata['ReferredBusiness']['front_user_id'] 	= $user['id'];
						$savedata['ReferredBusiness']['how_do_u_know'] 	= $hear;
						$savedata['ReferredBusiness']['city_id'] 	= $this->common->checkCity($city,$county_id,$state_id);
						$savedata['ReferredBusiness']['refer_ip'] 		= $_SERVER['REMOTE_ADDR'];
						$savedata['ReferredBusiness']['county_id'] 		= $county_id;
						$savedata['ReferredBusiness']['state_id'] 		= $state_id;
						$savedata['ReferredBusiness']['refered_date'] 	= mktime(0, 0, 0, date('m'), date('d'), date('Y'));
						$this->ReferredBusiness->save($savedata);
						
						$business_reffer_id = $this->ReferredBusiness->getlastinsertid();
					
					// Save Workorder Start
					$this->loadModel('WorkOrder');
					  $saveWork = '';
					  $saveWork['WorkOrder']['advertiser_order_id']			=  '';
					  $saveWork['WorkOrder']['subject']   					=  'Business referred by Consumer';
					  $saveWork['WorkOrder']['message']   					=  'A business has been referred by consumer recently. For more details about consumer, please click on below links : ';
					  $saveWork['WorkOrder']['type']   						=  'reffered_business';
					  $saveWork['WorkOrder']['sent_to']   					=  0;
					  $saveWork['WorkOrder']['sent_to_group']   			=  1;
					  $saveWork['WorkOrder']['from_group']   				=  'Consumer';
					  $saveWork['WorkOrder']['archive']   					=  '';				  
					  $saveWork['WorkOrder']['bottom_line']				=  'Reffered Business Details : <a href="'.FULL_BASE_URL.router::url('/',false).'referred_businesses/view/'.$business_reffer_id.'" target="_blank">'.FULL_BASE_URL.router::url('/',false).'referred_businesses/view/'.$business_reffer_id.'</a>';
					  date_default_timezone_set('US/Eastern');
					  $saveWork['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
					  $saveWork['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
					  $this->WorkOrder->save($saveWork);
					// Save Workorder End
												
						
						if(trim($email)!='' && trim($email)!='Business Email') {
							$this->loadModel('Setting');
							$emailArray = $this->Setting->getBusinessEmailData();
							
							$subject=$emailArray[0]['settings']['new_business_subject'];
			
							$fullurl=FULL_BASE_URL.router::url('/',false).'state/'.$state.'/'.$county;
							
							$link='<a href="'.$fullurl.'">'.$fullurl.'</a>';
							
							$arrayTags 		= array("[name]","[consumer_name]","[link]");
					
							$arrayReplace 	= array($name,$from_name,$link);
							
							$bodyText 	= '';
							$bodyText  .= str_replace($arrayTags,$arrayReplace,$emailArray[0]['settings']['new_business_body']);
							
							//ADMINMAIL id
							$this->Email->to 		= $email;
							$this->Email->subject 	= strip_tags($subject);
							$this->Email->replyTo 	= $this->common->getReturnEmail();
							$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
							$this->Email->sendAs 	= 'html';
							//Set the body of the mail as we send it.			
							//seperate line in the message body.
							$this->body = '';				
							$this->body = $this->emailhtml->email_header($county_id);
							$this->body .=$bodyText;
							$this->body .= $this->emailhtml->email_footer($county_id);
				
							//$this->body .= "<br />".FULL_BASE_URL.Router::url('/', false);
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
							$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"referred_business");
						}
						echo 'Thank you for referring a business. You will receive your Zuni bucks once your referred business has successfully signed up with Zuni.';
						exit;
					} else {
						echo 'This business is already referred.';
						exit;
					}
			}
	}
//--------------------------------------------------- Function to update address from gift cerificate ------------------------------//
	function save_gist_address() {
		$this->autoRender = false;
		if(!$this->Session->read('Auth.FrontConsumer.id')) {
			echo 'Invalid data';
			exit;
		}
		if(isset($_POST)) {
			extract($_POST);
			if(trim($id) && trim($name) && trim($address) && trim($city) && trim($zip) && trim($state)) {
			
				$voucher = base64_decode(base64_decode($id));
				
				$this->loadModel('Order');
				$check = $this->Order->find('count',array('conditions'=>array('Order.voucher_id'=>$voucher,'Order.front_user_id'=>$this->Session->read('Auth.FrontConsumer.id'))));
				if($check) {
					echo 'You have already purchased this gift certificate.';
					exit;
				}
			
				$detail = $this->common->voucher_detail($voucher);
				if(!empty($detail)) {
					// save Order
					$this->loadModel('Order');
					$bucks = $this->common->currency1($detail['price']);
					$arr = '';
					$arr['Order']['voucher_id'] = $voucher;
					$arr['Order']['advertiser_profile_id'] = $detail['advertiser_profile_id'];
					$arr['Order']['front_user_id'] =$this->Session->read('Auth.FrontConsumer.id');
					$arr['Order']['bucks'] = $bucks;
					$arr['Order']['order_date'] = mktime(0,0,0,date('m'),date('d'),date('Y'));
					$this->Order->save($arr);
					$order_id = $this->Order->getlastinsertid();
					// save User data
					$this->FrontUser->id = $this->Session->read('Auth.FrontConsumer.id');
					$getUserBucks = $this->FrontUser->field('FrontUser.total_bucks');
					
					$save = '';
					$save['FrontUser']['id'] = $this->Session->read('Auth.FrontConsumer.id');
					$save['FrontUser']['name'] = trim($name);
					$save['FrontUser']['address'] = trim($address);
					$save['FrontUser']['gift_city'] = trim($city);
					$save['FrontUser']['zip'] = trim($zip);
					$save['FrontUser']['gift_state'] = trim($state);
					$save['FrontUser']['total_bucks'] = $getUserBucks-($bucks);
					$this->FrontUser->save($save,false);
					
					// Save Workorder
					$this->loadModel('WorkOrder');
					  $saveWork = '';
					  $saveWork['WorkOrder']['advertiser_order_id']			=  $this->common->getonlyOrderId($detail['advertiser_profile_id']);
					  $saveWork['WorkOrder']['subject']   					=  'Gift Certificate purchased by Consumer';
					  $saveWork['WorkOrder']['message']   					=  'A Gift Certificate has been purchased by consumer recently. For more details about consumer and voucher, please click on below links : ';
					  $saveWork['WorkOrder']['type']   					=  'giftPurchased';
					  $saveWork['WorkOrder']['sent_to']   					=  0;
					  $saveWork['WorkOrder']['sent_to_group']   			=  1;
					  $saveWork['WorkOrder']['from_group']   				=  'Consumer';
					  $saveWork['WorkOrder']['archive']   					=  '';				  
					  $saveWork['WorkOrder']['bottom_line']				=  'Gift Certificate Details : <a href="'.FULL_BASE_URL.router::url('/',false).'vouchers/gift_details/'.$order_id.'" target="_blank">'.FULL_BASE_URL.router::url('/',false).'vouchers/gift_details/'.$order_id.'</a>';
					  date_default_timezone_set('US/Eastern');
					  $saveWork['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
					  $saveWork['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
					  $this->WorkOrder->save($saveWork);
					  
					  echo 'success';
				} else {
					echo 'invalid data';
					exit;
				}
			} else {
				echo 'invalid data';
				exit;
			}
		} else {
			echo 'invalid data';
			exit;
		}
	}
//---------------------------------------------------------------------------------------------------------------------//	
	function saveAccountInfo() {
		$this->autoRender = false;
		if(!$this->Session->read('Auth.FrontConsumer.id')) {
			echo 'Invalid data';
			exit;
		}	
		if(isset($_POST)) {
			extract($_POST);
			if(trim($name) && trim($address) && trim($city) && trim($zip) && trim($email)) {
				if(!$this->common->checkEmailWithId(trim($email),$this->Session->read('Auth.FrontConsumer.id'))) {
					echo 'Email id is already exist.';
					exit;
				}
					$save = '';
					$save['FrontUser']['id'] = $this->Session->read('Auth.FrontConsumer.id');
					$save['FrontUser']['name'] = trim($name);
					$save['FrontUser']['address'] = trim($address);
					$save['FrontUser']['zip'] = trim($zip);
					$save['FrontUser']['city_id'] = trim($city);
					$save['FrontUser']['email'] = trim($email);
					$this->FrontUser->save($save);
					  echo 'success';
				} else {
					echo 'invalid data';
					exit;
				}
			} else {
				echo 'invalid data';
				exit;
			}
	}	
//---------------------------------------------------------------------------------------------------------------------//	
	function savePswdInfo() {
		$this->autoRender = false;
		if(!$this->Session->read('Auth.FrontConsumer.id')) {
			echo 'Invalid data';
			exit;
		}	
		else if(isset($_POST)) {
			extract($_POST);
			if(trim($old_pswd)=='' || trim($old_pswd)=='Old Password') {
				echo 'Please enter Old Password.';
				exit;
			}
			else if(!$this->common->checkUserPswd($old_pswd,$this->Session->read('Auth.FrontConsumer.id'))) {
					echo 'Old Password is not correct.';
					exit;
			}
			else if(trim($new_pswd)=='' || trim($new_pswd)=='New Password') {
					echo 'Please enter New Password.';
					exit;
			}
			else if(strlen(trim($new_pswd))<8) {
					echo 'New Password should be 8 characters long.';
					exit;
			}
			else if($new_pswd!=$confirm_pswd) {
					echo 'Password confirmation failed.';
					exit;
			} else {
					$save = '';
					$save['FrontUser']['id'] = $this->Session->read('Auth.FrontConsumer.id');
					$save['FrontUser']['password'] = $this->Auth->password($new_pswd);
					$save['FrontUser']['realpassword'] = $new_pswd;
					$this->FrontUser->save($save);
					echo 'success';
				}
			} else {
				echo 'invalid data';
				exit;
			}
	}
//---------------------------------------------------------------------------------------------------------------------//	
	function savePswdInfoAdvertiser() {
		$this->autoRender = false;
		if(!$this->Session->read('Auth.FrontUser.id')) {
			echo 'Invalid data';
			exit;
		}
		else if(isset($_POST)) {
			extract($_POST);
			if(trim($old_pswd)=='' || trim($old_pswd)=='Old Password') {
				echo 'Please enter Old Password.';
				exit;
			}
			else if(!$this->common->checkUserPswd($old_pswd,$this->Session->read('Auth.FrontUser.id'))) {
					echo 'Old Password is not correct.';
					exit;
			}
			else if(trim($new_pswd)=='' || trim($new_pswd)=='New Password') {
					echo 'Please enter New Password.';
					exit;
			}
			else if(strlen(trim($new_pswd))<8) {
					echo 'New Password should be 8 characters long.';
					exit;
			}
			else if($new_pswd!=$confirm_pswd) {
					echo 'Password confirmation failed.';
					exit;
			} else {
					$save = '';
					$save['FrontUser']['id'] = $this->Session->read('Auth.FrontUser.id');
					$save['FrontUser']['password'] = $this->Auth->password($new_pswd);
					$save['FrontUser']['realpassword'] = $new_pswd;
					$this->FrontUser->save($save);
					echo 'success';
				}
			} else {
				echo 'invalid data';
				exit;
			}
	}	
//-----====----====-----=====--------==========--------------======== Daily Discount Extra Content ==========-------------=========---------=====-----====//
	function unsubscribeUser() {
		$this->autoRender = false;
		if(!$this->Session->read('Auth.FrontConsumer.id')) {
			echo 'Invalid data';
			exit;
		} else {
			$id = $this->Session->read('Auth.FrontConsumer.id');
			$email = $this->common->getConsumerEmailById($id);
			$this->loadModel('NewsletterUser');
			$checkMail = $this->NewsletterUser->find('count',array('conditions'=>array('NewsletterUser.email'=>$email)));
			if($checkMail) {
				$checkStatus = $this->NewsletterUser->find('first',array('fields'=>array('NewsletterUser.id','NewsletterUser.status'),'conditions'=>array('NewsletterUser.email'=>$email)));
				if($checkStatus['NewsletterUser']['status']=='yes') {
					$savearray = '';
					$savearray['NewsletterUser']['id'] = $checkStatus['NewsletterUser']['id'];
					$savearray['NewsletterUser']['status'] = 'no';
					$this->NewsletterUser->save($savearray);
				//get Mail format 
					$arrayTags = array("[consumer_name]");
					$arrayReplace = array($this->common->getConsumerNameById($id));
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.news_unsub_sub','Setting.news_unsub_cont')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['news_unsub_sub']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['news_unsub_cont']);			
					//ADMINMAIL id
					$this->Email->to 		= $this->common->getConsumerEmailById($id);
					$this->Email->subject 	= $subject;
					$this->Email->replyTo 	= $this->common->getReturnEmail();
					$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
					$this->Email->sendAs 	= 'html';
					//Set the body of the mail as we send it.
					//seperate line in the message body.
					$this->body = '';				
					//$this->body = $this->emailhtml->email_header();
					$this->body .=$bodyText;
					//$this->body .= $this->emailhtml->email_footer();

					//$this->body .= "<br />".FULL_BASE_URL.Router::url('/', false);
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
				$this->common->sentMailLog($this->common->getSalesEmail(),$this->common->getConsumerEmailById($id),strip_tags($subject),$this->body,"email_unsubscription");
				echo 'You have unsubscribed for all email services successfully.';	
				exit;		
				} else {
					echo 'You have already unsubscribed for all email services.';
					exit;
				}				
			} else {
				echo 'You are not subscribed for any email service at all.';
				exit;
			}
		}
	}
//-----====----====-----=====--------==========--------------======== Daily Discount Extra Content ==========-------------=========---------=====-----====//
	function subscribeUser() {
		$this->autoRender = false;
		if(!$this->Session->read('Auth.FrontConsumer.id')) {
			echo 'Invalid data';
			exit;
		} else {
			$id = $this->Session->read('Auth.FrontConsumer.id');
			$email = $this->common->getConsumerEmailById($id);
			$this->loadModel('NewsletterUser');
			$checkMail = $this->NewsletterUser->find('count',array('conditions'=>array('NewsletterUser.email'=>$email)));
			if($checkMail) {
				$checkStatus = $this->NewsletterUser->find('first',array('fields'=>array('NewsletterUser.id','NewsletterUser.status'),'conditions'=>array('NewsletterUser.email'=>$email)));
				if($checkStatus['NewsletterUser']['status']=='no') {
					$savearray = '';
					$savearray['NewsletterUser']['id'] = $checkStatus['NewsletterUser']['id'];
					$savearray['NewsletterUser']['status'] = 'yes';
					$this->NewsletterUser->save($savearray);
					echo 'You have subscribed for all email services successfully.';
					exit;
				} else {
					echo 'You have already subscribed for all email services.';
					exit;
				}				
			} else {
				echo 'You are not subscribed for any email service at all.';
				exit;
			}
		}
	}
//---------------------------------------------------------------------------------------------------------------------//	
	function changeCounty() {
		$this->layout = false;
		if(isset($_POST['state'])) {
			if($_POST['state']!=''){
					$selCounty = $this->common->getAllCountyByState($_POST['state']);
			} else {
					$selCounty = '';
			}
			$this->set('selCounty',$selCounty);
		}
	}
//---------------------------------------------------------------------------------------------------------------------//		
function savedata() {
			$this->autoRender = false;
			if(isset($_POST)) {
			extract($_POST);
			if(!trim($name) || !trim($phone) || !trim($email) || !trim($state) || !trim($county)) {
				echo 'invalid data';
				exit;
			} else if(!$this->common->checkEmailAdvertiser(trim($email))) {
				echo 'Email id is already exist.';
				exit;
			} else {
	  		
			$date = date('Y-m-d h:i:s');
			//Save data into advertiser order
			$this->loadModel('AdvertiserOrder');
			$this->loadModel('AdvertiserProfile');
			$this->AdvertiserOrder->save(array('package_id'=>0,'payment_status'=>'pending','order_status'=>'pending'));
			$order_id = $this->AdvertiserOrder->getLastInsertId();
			//Save data into advertiser profile
			$this->AdvertiserProfile->query("INSERT INTO advertiser_profiles (name,company_name,phoneno,email,county,state,publish,created,modified,represent_call,order_id) VALUES ('".$name."' ,'".$name."' ,'".$phone."' ,'".$email."' ,'".$county."' ,'".$state."' ,'no' ,'".$date."' ,'".$date."' ,'yes' ,'".$order_id."')");
				$this->loadModel('FrontUser');
				$arr['FrontUser']['name'] = $name;
				$arr['FrontUser']['email']= $email;
				$arr['FrontUser']['status'] 	= 'yes';
				$arr['FrontUser']['state_id']  = $state;
				$arr['FrontUser']['county_id']  = $county;
				$ad_id = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.id'),'conditions'=>array('AdvertiserProfile.order_id'=>$order_id)));		
				$arr['FrontUser']['advertiser_profile_id'] = $ad_id['AdvertiserProfile']['id'];
				//Save data into Front user
				$arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
				$this->FrontUser->save($arr);
				
				//------------------------------------------ Welcome Email -----------------------------------//
				/*$subject 	= 'New User';
				$bodyText 	= 'Hi '.$alldata[5].',<br /><br />Thanks for registration on zuni.<br />Our Representative will call you shortly.<br /><br />Thanks,<br />Zuni Team';
				$this->body = '';					
				$this->body = $this->emailhtml->email_header($this->Session->read('county_data.id'));
				$this->body .=$bodyText;
				$this->body .= $this->emailhtml->email_footer($this->Session->read('county_data.id'));
				$this->loadModel('Setting');
				$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.newsletter_from_email')));					
				$this->Email->to 		= $alldata[6];
				$this->Email->subject 	= $subject;
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
				$this->common->sentMailLog($this->common->getSalesEmail(),$alldata[6],strip_tags($subject),$this->body,"new_advertiser_registration");*/
				
				//work order entry
				$sub 	= 'New work order Generated';
				$msg 	= 'A new work order has been placed recently and wants a representative to call with details. Order detail is below:';
				$type 	= 'workorder';
				$to 	= 0;
				$to_grp = 1;
				$form 	= 1;
				$b_line	= 'You can go further and add other details about this advertiser in advertiser profiles section like saving offers , vip offers etc.';
				$salse_id = 0;
				$this->common->proof_message($order_id,$sub,$msg,$type,$to,$to_grp,$form,$b_line,$salse_id);
				echo 'success';
				exit;
			}} else {
				echo 'invalid data';
				exit;
			}
		}
//---------------------------------------------------------------------------------------------------------------------//
function changecategory() {
			$this->layout = false;
			if(!$this->Session->read('Auth.FrontConsumer.id')) {
				echo 'Invalid data';
				exit;
			} else {
				$id = $this->Session->read('Auth.FrontConsumer.id');
				$user = $this->common->getConsumerDetails($id);
				$this->loadModel('NewsletterUser');
				$newsletter = $this->NewsletterUser->find('first',array('fields'=>array('NewsletterUser.category_id','NewsletterUser.all_cats'),'conditions'=>array('NewsletterUser.email'=>$user['email'],'NewsletterUser.county_id'=>$user['county_id'])));
				$this->set('newsletter',$newsletter);
			}	
}
/************************** function to confirm to delete advertiser profile ******************************/
	function saveCategorydata($cats,$verity) {
			$this->autoRender = false;
			if(!$this->Session->read('Auth.FrontConsumer.id')) {
				echo 'Invalid data';
				exit;
			} else {
			$id = $this->Session->read('Auth.FrontConsumer.id');
			$userEmail = $this->common->getConsumerDetails($id);
			$this->loadModel('NewsletterUser');
			$newsletter = $this->NewsletterUser->find('first',array('conditions'=>array('NewsletterUser.email'=>$userEmail['email'],'NewsletterUser.county_id'=>$userEmail['county_id'])));
			if(empty($newsletter)) {
				$arr['NewsletterUser']['name'] = $userEmail['name'];
				$arr['NewsletterUser']['email'] = $userEmail['email'];
				$arr['NewsletterUser']['status'] = 'yes';
				$arr['NewsletterUser']['zipcode'] = $userEmail['zip'];
				$arr['NewsletterUser']['user_id'] = $id;
				$arr['NewsletterUser']['county_id'] = $userEmail['county_id'];
			} else {
				$arr['NewsletterUser']['id'] = $newsletter['NewsletterUser']['id'];
			}
				$arr['NewsletterUser']['category_id'] = $cats;
				$arr['NewsletterUser']['all_cats'] = $verity;
				$this->NewsletterUser->save($arr);	
			}				
	}	
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	function send_company_link() {
			$this->autoRender = false;
			//echo 'form is post';exit;
		if(isset($_POST['self_name']) && trim($_POST['self_name'])!='' && isset($_POST['self_email']) && trim($_POST['self_email'])!='' && isset($_POST['ur_frnd_name']) && trim($_POST['ur_frnd_name'])!='' && isset($_POST['ur_frnd_email']) && trim($_POST['ur_frnd_email'])!='') {
		
		$sender_name=$_POST['self_name'];
		$sender_email=$_POST['self_email'];
		$receiver_name=$_POST['ur_frnd_name'];
		$receiver_email=$_POST['ur_frnd_email'];
		$message=$_POST['ur_frnd_msg'];
		
		
			$arrayTags = array("[friend_name]","[link]","[message]","[from]");
			$arrayReplace = array($receiver_name,$_SERVER["HTTP_REFERER"],$message,$sender_name);
			
			//get Mail format
			$this->loadModel('Setting');
			$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.merchant_page_subject','Setting.merchant_page_body')));
			$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['merchant_page_subject']);
			$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['merchant_page_body']);

					$this->Email->to 		= $receiver_email;
					$this->Email->subject 	= strip_tags($subject);
					$this->Email->replyTo 	= $this->common->getReturnEmail();
					$this->Email->from 		= $sender_email;
					$this->Email->sendAs 	= 'html';
					//Set the body of the mail as we send it.			
					//seperate line in the message body.
					$this->body = '';				
					$this->body = $this->emailhtml->email_header();
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer();
					//$this->body .= "<br />".FULL_BASE_URL.Router::url('/', false); 
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
				$this->common->sentMailLog($sender_email,$receiver_email,strip_tags($subject),$this->body,"email_to_friend");
			/////////////////////////////////////////////////////////////////////////
	
				
					$this->Email->reset();
					echo 'success';
		} else {
			echo 'Invalid data.';
			exit;
		}				
	}

//--------------------------------------------set cookie for hide footer on front------------------------------------------//
function hideFooter()
{
	$this->layout=false;
	$this->autoRender=false;
	$this->Cookie->write('footer', 'hide', false, 86400);
	exit;
}
//--------------------------------------------set cookie for show footer on front------------------------------------------//
function showFooter()
{
	$this->layout=false;
	$this->autoRender=false;
	$this->Cookie->delete('footer');
	exit;
}
//-------------------------------------------------------------------------------------------------------------------//
	function address() {
		$this->autoRender = false;
		if($this->Session->read('Auth.FrontUser.id')) {
			$this->loadModel('AdvertiserProfile');
			$savearr = '';
			$savearr['AdvertiserProfile']['id'] = $this->Session->read('Auth.FrontUser.advertiser_profile_id');
			$savearr['AdvertiserProfile']['show_address'] = $_POST['status'];
			$this->AdvertiserProfile->save($savearr);
		}
	}
//----------------------------------------------------------------------------------------------------------------//	
	function address2() {
		$this->autoRender = false;
		if($this->Session->read('Auth.FrontUser.id')) {
			$this->loadModel('AdvertiserProfile');
			$savearr = '';
			$savearr['AdvertiserProfile']['id'] = $this->Session->read('Auth.FrontUser.advertiser_profile_id');
			$savearr['AdvertiserProfile']['show_address2'] = $_POST['status'];
			$this->AdvertiserProfile->save($savearr);
		}	
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function feedback() {
	if($this->Session->read('Auth.FrontUser'))
	{
		if(isset($this->data))
		{
			 $msg=$this->data['front_users']['feedback'];
			 $sman=$this->data['front_users']['salesman'];
			 $admin=$this->data['front_users']['admin'];
				
			if($admin==1)
			{
					#Insertimg one record in work order table to show this data in inbox of admin
						  App::import('model', 'WorkOrder');
						  $this->WorkOrder = new WorkOrder;
						  $saveWorkArray = array();
						  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $this->Session->read('Auth.FrontUser.advertiser_profile_id');
						  $saveWorkArray['WorkOrder']['read_status']   				=  0;
						  $saveWorkArray['WorkOrder']['subject']   					=  'Feedback from Advertiser';
						  $saveWorkArray['WorkOrder']['message']   					=  $msg;
						  $saveWorkArray['WorkOrder']['type']   					=  'adv_feedback';
						  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
						  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
						  $saveWorkArray['WorkOrder']['from_group']   				=  'Feedback';
						  date_default_timezone_set('US/Eastern');
						  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
						  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
						  if($sman==1) {
						  	$saveWorkArray['WorkOrder']['salseperson_id']   			=  $this->common->salesIdForAdvertiser($this->Session->read('Auth.FrontUser.advertiser_profile_id'));
						  }
						  $this->WorkOrder->save($saveWorkArray);
			
			}		
			$this->Session->setFlash('<span style="padding:0 0 10px 5px; font:15px OpenSansSemibold; color:#006633;">Feedback Sent Successfully</span>');
			$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/feedback/send:success'); 
		}		
	} else {
			$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county')); 
		}			
	}
//---------------------------------------------------------------------------------------------------------------------------------//
	function discount_history() {
		$this->loadModel('DailyDiscount');
		//Find all discounts of the logged in advertiser
			$all_discounts = $this->DailyDiscount->find('all',array('conditions'=>array('DailyDiscount.advertiser_profile_id'=>$this->Session->read('Auth.FrontUser.advertiser_profile_id'))));
			return $all_discounts;
	}
//---------------------------------------------------------------------------------------------------------------------------------//
	function vip_offer_print($id) {
		if($this->Session->read('Auth.FrontUser')) {
			$this->layout = false;
			$this->id = $id;
			App::import('model', 'VipOffer');
			$this->VipOffer = new VipOffer;
			$offer = $this->VipOffer->find('first',array('conditions'=>array('VipOffer.id'=>$id)));
			$this->set('offer',$offer);
		} else {
			$this->layout = false;
			$this->render('/errors/url_error');
		}
	}
//---------------------------------------------------------------------------------------------------------------------//
	function getCompanyUrlFromName() {
		$this->autoRender=false;
        if(isset($_POST['bussiness_name']) && trim($_POST['bussiness_name'])) {
        $this->loadModel('AdvertiserProfile');
		$advertuserPro_id = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.id','AdvertiserProfile.page_url'),'conditions'=>array('AdvertiserProfile.company_name'=>$_POST['bussiness_name'])));
         	if(isset($advertuserPro_id['AdvertiserProfile']['id']) && $advertuserPro_id['AdvertiserProfile']['id']!='')
                echo $advertuserPro_id['AdvertiserProfile']['page_url'];
			else
				echo 'Invalid data';
                
        } else {
			echo 'Invalid data';
			exit;
		}
	}
//---------------------------------------------------------------------------------------------------------------------//

	function beforeFilter() {
			$this->Auth->userModel = 'FrontUser';
			$this->Auth->allow('*');
			$this->set('common',$this->common);
			$this->Auth->autoRedirect = true;
			$this->set('myCookie', $this->Cookie);
	}
}
?>