<?php 
/*
   Coder: Manoj Pandit
   Date  : 26 June 2013
*/ 

class MailChimpsController  extends AppController { 
      var $name = 'MailChimps';
	  
     var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator'); 
     var $components = array('Auth','common','Session','Email','Cookie','RequestHandler','mailchimp','thankshtml','merchanthtml');
     var $layout = 'admin'; //this is the layout for admin panel
	  
//--------------------------------------------to get all lists from mailchimp--------------------------------------------//
	  function index($adv_id=0)
	  {	  $this->set('title_for_layout','Mailchimp Manager');
		  $adv_list_member_count=0;
		  $this->set('adv_id',$adv_id);
		  App::import('model','AdvertiserProfile');
		  $this->AdvertiserProfile = new AdvertiserProfile();
		  $advertiserDetails=$this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.id','AdvertiserProfile.company_name','AdvertiserProfile.mailchimp_list_id','AdvertiserProfile.county','AdvertiserProfile.email','AdvertiserProfile.data_collection_email'),'conditions'=>array('AdvertiserProfile.id'=>$adv_id),'recursive'=>-1));
		  $this->set('advertiserDetails',$advertiserDetails);
		  //pr($advertiserDetails);
		  $mylists='';
		  $mailchimplists=$this->mailchimp->lists();
		  
		  
		  //pr($mailchimplists);
		  
		  foreach($mailchimplists['data'] as $mailchimplist)
		  {
			$mylists[$mailchimplist['id']]=$mailchimplist['name']; // make list of id and name of existing mailchimp list
			
			  if(isset($advertiserDetails['AdvertiserProfile']['mailchimp_list_id']) && $advertiserDetails['AdvertiserProfile']['mailchimp_list_id']!='' && ($advertiserDetails['AdvertiserProfile']['mailchimp_list_id']==$mailchimplist['id']))
			  {
					$adv_list_member_count=$mailchimplist['stats']['member_count'];
			  }
			
		  }
		  $this->set('allMailchimpLists',$mylists);
		  $this->set('adv_list_member_count',$adv_list_member_count);

		  
			  $offer=array(0);
			  if(isset($advertiserDetails['AdvertiserProfile']['id']) && $advertiserDetails['AdvertiserProfile']['id']!='' && $advertiserDetails['AdvertiserProfile']['mailchimp_list_id']!='' && (in_array($advertiserDetails['AdvertiserProfile']['mailchimp_list_id'],array_keys($mylists))))
			  {	
				  $this->loadModel('SavingOffer');
				  $Offer = $this->SavingOffer->find('all',array('fields'=>array('SavingOffer.advertiser_profile_id','SavingOffer.title','SavingOffer.id','SavingOffer.off_unit','SavingOffer.off_text','SavingOffer.off','AdvertiserProfile.city','AdvertiserProfile.main_image','AdvertiserProfile.main_image_type','AdvertiserProfile.logo','AdvertiserProfile.company_name','SavingOffer.current_saving_offer'),'conditions'=>array('SavingOffer.status = "yes" AND FROM_UNIXTIME(`offer_start_date`) < CURDATE() AND FROM_UNIXTIME(`offer_expiry_date`) > CURDATE() AND SavingOffer.advertiser_profile_id='.$advertiserDetails['AdvertiserProfile']['id']),'order' =>'SavingOffer.id ASC'));
			  }
			  $this->set(compact('Offer'));
		  
		  if(isset($this->data) && isset($this->data['MailChimp']['submit1']) && $this->data['MailChimp']['submit1']=='my_submit1'){
		  
				if(isset($this->data['MailChimp']['mailchimp_list_id']) && $this->data['MailChimp']['mailchimp_list_id']=='')
				{
					$this->Session->setFlash("Please select mailchimp list");
					$this->redirect(array('controller'=>'mail_chimps','action'=>'index/'.$adv_id.'/msg:error'));
				}else{
					$saveListArr='';
					$saveListArr['AdvertiserProfile']['id']=$adv_id;
					$saveListArr['AdvertiserProfile']['mailchimp_list_id']=$this->data['MailChimp']['mailchimp_list_id'];
					$this->AdvertiserProfile->save($saveListArr,false);
					$this->Session->setFlash("MailChimp List associated with this advertiser successfully.");
					$this->redirect(array('controller'=>'mail_chimps','action'=>'index/'.$adv_id));
				}
		  }elseif(isset($this->data) && isset($this->data['MailChimp']['sendtolist']) && $this->data['MailChimp']['sendtolist']=='sendtolist'){
						
					//segment of code to email send to list or create a campaign and make email blast
					if($this->Session->read('email_content') && $this->Session->read('email_content')!='' && isset($this->data['MailChimp']['selected_mailchimp_list_id']) && $this->data['MailChimp']['selected_mailchimp_list_id']!=''){
						
						if(isset($this->data['MailChimp']['selected_template']) && $this->data['MailChimp']['selected_template']!='' && $this->data['MailChimp']['selected_template']=='category_email')
							$myMailSubject='Zuni Merchant Categories Email';
						else
							$myMailSubject='Zuni Merchant Thank You Email';
						
						$my_email_contents = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Merchant Offer And Category Email</title></head><body style="padding:0px; margin:0px;">';	
						$my_email_contents.=$this->Session->read('email_content');
						
						//$my_email_contents=str_replace(' & ','&amp;',$my_email_contents);
						
						$my_email_contents.='<style> table {  border-collapse:collapse; table-layout:fixed;} table td table{ border-collapse:collapse; table-layout:fixed; }</style></body></html>';
						
						$opts=array(0);
						$opts['list_id'] = $this->data['MailChimp']['selected_mailchimp_list_id'];
						$opts['subject'] = $myMailSubject;
						$opts['from_email'] = $this->data['MailChimp']['data_collection_email']; //old email id => $this->data['MailChimp']['from_email']; 
						$opts['from_name'] = $this->data['MailChimp']['from_name'];
						
						$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);
						
						$opts['title'] = $myMailSubject." (".$this->data['MailChimp']['from_name'].")";
						
						$my_content = array('html'=>$my_email_contents);
						
						$returnCamp=$this->mailchimp->campaignCreate('regular', $opts, $my_content);
						
						if($this->mailchimp->errorCode){
							$this->Session->setFlash("Error occured : ".$this->mailchimp->errorMessage.". (".$this->mailchimp->errorCode.")");
							$this->redirect(array('controller'=>'mail_chimps','action'=>'index/'.$adv_id.'/msg:error'));
						}else{
							$returnCampSend=$this->mailchimp->campaignSendNow($returnCamp);
							
							if($this->mailchimp->errorCode){
								$this->Session->setFlash("Campaign has been created, but email blast failed due to error : ".$this->mailchimp->errorMessage.". (".$this->mailchimp->errorCode.")");
								$this->redirect(array('controller'=>'mail_chimps','action'=>'index/'.$adv_id.'/msg:error'));
							}else{
								$this->Session->setFlash("Email blast is complete.");
							}
						}
					}else{
						$this->Session->setFlash("Connection error, please try again ");
						$this->redirect(array('controller'=>'mail_chimps','action'=>'index/'.$adv_id.'/msg:error'));
					}
						
		  }
		  
		  
	  }
//--------------------------------------------import csv file for subsribers to mailchimp------------------------------------------------//
	  function addList($adv_id=0)
	  {	  
	  	  $this->autoRender=false;		  
		  
		  if(isset($this->data))
		  {		  	
			$this->MailChimp->set($this->data);
			if($this->MailChimp->validates())
			{		
				set_time_limit(0);
				$time=time();
				$name =WWW_ROOT.'files/'.$time.'_'.$this->data['MailChimp']['csv_file_url']['name'];
				move_uploaded_file($this->data['MailChimp']['csv_file_url']['tmp_name'],$name);
				
				//------------------------//
				$row = 1;
				$batch = array();
				$batch[] = '';//'list_id,first_name,last_name,email';
				if (($handle = fopen($name , "r")) !== FALSE) {
					while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
						$num = count($data);
						if($row!=1) {
							if(trim($data[2])!='') {
								$batch[] = array('EMAIL'=>str_replace(',','',trim($data[2])), 'FNAME'=>str_replace(',','',trim($data[0])), 'LNAME'=>str_replace(',','',trim($data[1])));
							}
						}
						$row++;
					}
					fclose($handle);
				}
				//-----------------//
				unlink(WWW_ROOT.'files/'.$time.'_'.$this->data['MailChimp']['csv_file_url']['name']);
				
				
				if(!empty($batch))
				{
					$batch_filtered=array_filter($batch); // fileter the array for blank values
					$optin = true; //yes, send optin emails
					$up_exist = false; // yes, update currently subscribed users
					$replace_int = false; // no, add interest, don't replace					
					$this->mailchimp->listBatchSubscribe($this->data['MailChimp']['mailchimp_list_hidden_id'],$batch_filtered,$optin, $up_exist, $replace_int);
				}
				
				$this->Session->setFlash('All contacts are imported to mailchimp successfully.');
				$this->redirect(array('controller'=>'mail_chimps','action'=>'index/'.$adv_id));
				
			}else{
				$errors = $this->MailChimp->invalidFields();
				$this->Session->setFlash(implode('<br>', $errors));
				$this->redirect(array('controller'=>'mail_chimps','action'=>'index/'.$adv_id.'/msg:error'));
			}
		  }
	  }
//------------------------------------send test mail to specified email address--------------------------------------------//		
	function test_offer_email() {
		$this->autoRender = false;
		if(isset($this->data)) {
				
				if(isset($this->data['MailChimp']['selected_template']) && $this->data['MailChimp']['selected_template']!='' && $this->data['MailChimp']['selected_template']=='category_email')
					$testMailSubject='Zuni Merchant Categories Email';
				else
					$testMailSubject='Zuni Merchant Thank You Email';
				
				$content = '';
				$content .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Merchant Offer And Category Email</title></head><body style="padding:0px; margin:0px;">';
				$content .=  $this->Session->read('email_content');
				$content .= '</body></html>';

				$this->Email->sendAs = 'html';
				$this->Email->to = $this->data['MailChimp']['email'];
				$this->Email->subject = $testMailSubject;//'Zuni Merchant Page / Everyday Savings Offers';
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
				if($this->Email->send($this->body)) {
					$this->Session->setFlash('Test email sent successfully.');
					$this->redirect($this->referer());
				} else {
					$this->Session->setFlash('Problem in sending email. Please try later.');
					$this->redirect($this->referer().'/type:error');
				}
			}
	}

//-----------------------------------------------------------------------------------------------------------------//		
	function saveDataCollectionMail($adv_id=0) {
		$this->set('title_for_layout', 'Set-up the data collection email');
		$this->set('adv_id',$adv_id);
				
		if(isset($_POST['adv_dc_id']) && $_POST['adv_dc_id']!='' && isset($_POST['adv_dc_email']) && $_POST['adv_dc_email']!='')
		{
			$this->loadModel('AdvertiserProfile');
			$saveDcArr='';
			$saveDcArr['AdvertiserProfile']['data_collection_email']=$_POST['adv_dc_email'];
			$saveDcArr['AdvertiserProfile']['id']=$_POST['adv_dc_id'];
			
			if($this->AdvertiserProfile->save($saveDcArr,false))
			{	
				$this->Session->setFlash('Data Collection Email Saved Successfully');
				echo 'success';exit;
			}else{
				echo 'fail';exit;
			}
			
		}
		
	}
//-----------------------------------------------------------------------------------------------------------------//		
	function copy() {
		$this->set('title_for_layout', 'Get The HTML Code');
		if(!$this->Session->read('email_content')) {
			$this->redirect(array('action'=>'index'));
			exit;
		}
	}
//-----------------------------------------------------------------------------------------------------------------//	
	function sendToList() {
		$this->autoRender = false;
		if(isset($this->data)) {
		$mailchimplists=$this->mailchimp->lists();
		pr($mailchimplists);
			pr($this->data);
		}else{
			echo 'empty form';
		}
		exit;
	}
//----------------------------------to view the all subscribers of specified list------------------------------------------------------------//
	function viewListSubscribers($specified_list_id=0,$specified_list_name='')
	{
		$this->set('title_for_layout','Mailchimp subscribers of list ');		
		$allSubscribedUsers=$this->mailchimp->listMembers(base64_decode($specified_list_id));
		$this->set('allSubscribedUsers',$allSubscribedUsers);
		$this->set('specific_list_id',$specified_list_id);
		$this->set('specified_list_name',base64_decode($specified_list_name));
		
		// $this->render('/mail_chimps/pagination_view_list_subscribers'); // //
	}
//----------------------------------to get detail of subscriber of specified list------------------------------------------------------------//
	function viewSubscriberInfo($specified_list_id=0,$specified_email='')
	{
		$this->set('title_for_layout','Mailchimp Subscriber Details');
		$subscribedUserDetails=$this->mailchimp->listMemberInfo(base64_decode($specified_list_id),base64_decode($specified_email));
		$this->set('subscribedUserDetails',$subscribedUserDetails);
	}

//----------------------------------to get mailchimp Template Name------------------------------------------------------------//
	function getTemplateName($specified_tpl_id=0)
	{
		$this->set('title_for_layout','All User Templates');
		$allTemplates=$this->mailchimp->templates(array('user'=>true,'gallery'=>false));
		if(!empty($allTemplates)){
			foreach($allTemplates['user'] as $allTemplate){
				if(in_array($specified_tpl_id,$allTemplate))
				{
					return $allTemplate['name'];
				}
			}
		}
		return false;
	}


//---------------------------------------this function is used to test new functions-------------------------------//	
	function myTestFunction()
	{
		
		$my_campaigns = $this->mailchimp->campaigns();//'15553'
		pr($my_campaigns);
		
		exit;
		if ($this->mailchimp->errorCode){
			echo "Unable to run templateDelete!";
			echo "\n\tCode=".$this->mailchimp->errorCode;
			echo "\n\tMsg=".$this->mailchimp->errorMessage."\n";
		} else {
			echo "Template=".$my_folders."\n";
		}
		
		pr($my_folders);
		exit;
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
	//------------------------download sample csv file START----------------------------//
	function downloadSampleCsv($filename){
		$this->downloadFile('files/',$filename);
	}
	//--------------------------------------Download function path if folder any folder in img path-------------------------------------------//
	function downloadFile($folder,$fielname) {
		$this->autoLayout = false;
		$newFileName = $fielname;
		$folder = str_replace('-','/',$folder);
		//Replace - to / to view subfolder
	    $path =  WWW_ROOT.$folder.'/'.$fielname;
		if(file_exists($path) && is_file($path)) {
			$mimeContentType = 'application/octet-stream';
			$temMimeContentType = $this->_getMimeType($path);
			if(isset($temMimeContentType)  && !empty($temMimeContentType))	{
							$mimeContentType = $temMimeContentType;
			}
		    //echo  'sssssssssss--->' . $mimeContentType;		 exit;
			// START ANDR SILVA DOWNLOAD CODE
			// required for IE, otherwise Content-disposition is ignored
			if(ini_get('zlib.output_compression'))
			  	ini_set('zlib.output_compression', 'Off');
			header("Pragma: public"); // required
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false); // required for certain browsers 
			header("Content-Type: " . $mimeContentType );
			// change, added quotes to allow spaces in filenames, by Rajkumar Singh
			header("Content-Disposition: attachment; filename=\"".(is_null($newFileName)?basename($path):$newFileName)."\";" );
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($path));
			readfile($path);
			exit();
			// END ANDR SILVA DOWNLOAD CODE												
		 }
		 if(isset($_SERVER['HTTP_REFERER'])) {
		 	 $this->Session->setFlash('File not found.');
			 $this->redirect($_SERVER['HTTP_REFERER']);
		 }	 
 	}	
	function _getMimeType($filepath) {
		ob_start();
		system("file -i -b {$filepath}");
		$output = ob_get_clean();
		$output = explode("; ",$output);
		if ( is_array($output) ) {
			$output = $output[0];
		}
		return $output;
	}
	  //------------------------to get all lists from mailchimp------------------------------------------------------------------------//
	  function manager()
	  {	  $this->set('title_for_layout','Mailchimp Lists');
	  	  $mylists='';
		  $mailchimplists=$this->mailchimp->lists();
		  $this->set('allMailchimpLists',$mailchimplists);
	  }

	 //------------------------import csv file for subsribers to mailchimp------------------------------------------------//
	  function addToList()
	  {	  
	  	  $this->set('title_for_layout','Import subscribers to Mailchimp');
		  $mylists='';
		  $mailchimplists=$this->mailchimp->lists();
		  
		  foreach($mailchimplists['data'] as $mailchimplist)
		  {
			$mylists[$mailchimplist['id']]=$mailchimplist['name']; // make list of id and name of existing mailchimp list
		  }
		  $this->set('allMailchimpLists',$mylists);
		  
		  if(isset($this->data))
		  {
		  	
			$this->MailChimp->set($this->data);
			if($this->MailChimp->validates())
			{				
				set_time_limit(0);
				$time=time();
				$name =WWW_ROOT.'files/'.$time.'_'.$this->data['MailChimp']['csv_file_url']['name'];
				move_uploaded_file($this->data['MailChimp']['csv_file_url']['tmp_name'],$name);
				
				//------------------------//
				$row = 1;
				$batch = array();
				$batch[] = '';//'list_id,first_name,last_name,email';
				if (($handle = fopen($name , "r")) !== FALSE) {
					while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
						$num = count($data);
						if($row!=1) {
							if(trim($data[2])!='') {
								$batch[] = array('EMAIL'=>str_replace(',','',trim($data[2])), 'FNAME'=>str_replace(',','',trim($data[0])), 'LNAME'=>str_replace(',','',trim($data[1])));
							}
						}
						$row++;
					}
					fclose($handle);
				}
				//-----------------//
				unlink(WWW_ROOT.'files/'.$time.'_'.$this->data['MailChimp']['csv_file_url']['name']);
				
				
				if(!empty($batch))
				{
					$batch_filtered=array_filter($batch); // fileter the array for blank values
					$optin = true; //yes, send optin emails
					$up_exist = false; // yes, update currently subscribed users
					$replace_int = false; // no, add interest, don't replace					
					$this->mailchimp->listBatchSubscribe($this->data['MailChimp']['mailchimp_list_id'],$batch_filtered,$optin, $up_exist, $replace_int);
				}
				
				$this->Session->setFlash('All contacts are imported to mailchimp successfully.');
				$this->redirect(array('action' =>'manager'));
				
			}else{
				$errors = $this->MailChimp->invalidFields();
				$this->Session->setFlash(implode('<br>', $errors));
			}
		  }
	  }
	//------------------------download sample csv file END----------------------------//
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
		$this->set('mailchimp',$this->mailchimp);
		$this->set('thankshtml',$this->thankshtml);
		$this->set('merchanthtml',$this->merchanthtml);
		$this->set('Session',$this->Session);
	}
}
?>