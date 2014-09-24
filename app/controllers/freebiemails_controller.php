<?php
class FreebiemailsController extends AppController {
	  var $name = 'Freebiemails';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax','Calendar');
	  //var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar');
	  var $layout = 'admin';
	  var $components = array('Auth','common','Cookie','Email','RequestHandler','emailhtml','mobile','Session','freebiehtml','autofreebiehtml');
//------------------------------------------------------------------------------------------------------------------------//
	   function index(){
	    			$this->set('title_for_layout', 'Today\'s Freebie Email');
					$this->paginate = array('limit' => PER_PAGE_RECORD, 'order' => array('Freebiemail.id' => 'desc'));
					$data = $this->paginate('Freebiemail');
		    		$this->set('data', $data);
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function offerEmail() {
		$this->set('title_for_layout', 'Today\'s Freebie Email');
		$this->set('advertiserList',$this->common->getAdvertiserProfileAll());
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function offerHtml() {
		$this->set('title_for_layout', 'Today\'s Freebie Email');
		if(isset($this->data)) {
			$advertiser = $this->data['Freebiemail']['advertisers'];
			$freebie = $this->data['Freebiemail']['freebie'];
			$this->set('advertiser',$advertiser);
			$this->set('freebie',$freebie);
		} else {
			$this->redirect(array('action'=>'offerEmail'));
		}
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function scheduleEmail() {
		if(isset($this->data)) {
			$this->Freebiemail->save($this->data);
			$this->Session->setFlash('Today\'s freebie email has been scheduled successfully.');
			$this->redirect(array('action'=>'index'));
		}
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/
	function view($id) {
		if($id) {
			$this->Freebiemail->id = base64_decode(base64_decode($id));
			$advertisers = $this->Freebiemail->field('advertisers');
			$freebie = $this->Freebiemail->field('freebie');
			$modeInfo=$this->Freebiemail->find('first',array('fields'=>array('Freebiemail.id','Freebiemail.mode','Freebiemail.specified_email'),'conditions'=>array('Freebiemail.id'=>$this->Freebiemail->id)));			
			$this->set('modeInfo',$modeInfo);
			$this->set('advertiser',$advertisers);
			$this->set('freebie',$freebie);
		} else {
			$this->redirect(array('action'=>'index'));
		}	
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function delete($id) {
		if($id) {
			$this->Freebiemail->id = base64_decode(base64_decode($id));
			$this->Freebiemail->delete();
			$this->Session->setFlash('Today\'s freebie email has been deleted successfully.');
			$this->redirect(array('action'=>'index'));
		} else {
			$this->redirect(array('action'=>'index'));
		}
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function test_offer_email() {
		$this->autoRender = false;
		if(isset($this->data)) {
				$advertiser = $this->data['Freebiemail']['array_string'];
				$freebie = $this->data['Freebiemail']['freebie'];
				$content = '';
				$content .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Zuni Merchant Page / Everyday Savings Offers</title></head><body style="margin:0px; padding:0px; font-size:0; ">';
				$content .= $this->freebiehtml->email_header();
				$content .= $this->freebiehtml->email_box();
				$content .= $this->freebiehtml->email_content($advertiser,'',$freebie);
				$content .= $this->freebiehtml->email_footer().'</body></html>';

				$this->Email->sendAs = 'html';
				$this->Email->to = $this->data['Freebiemail']['email'];
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
			if(strpos($this->referer(),'ronemails/view')) {
				$this->redirect($this->referer());
			} else {
				$this->redirect(array('action'=>'index'));
			}
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function bulkOfferEmail() {
		date_default_timezone_set('US/Eastern');
		$this->autoRender = false;
		
		$mode_info=unserialize($this->data['Freebiemail']['mode_info']);
		
		if(isset($mode_info['Freebiemail']['mode']) && $mode_info['Freebiemail']['mode']==1 && isset($this->data['Freebiemail']['array_string']) && $this->data['Freebiemail']['array_string']!=''){
				$advertiser = $this->data['Freebiemail']['array_string'];
				$freebie = $this->data['Freebiemail']['freebie'];
				$content = '';
				$content .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Zuni Merchant Page / Everyday Savings Offers</title></head><body style="margin:0px; padding:0px; font-size:0; ">';
				$content .= $this->freebiehtml->email_header();
				$content .= $this->freebiehtml->email_box();
				$footer  = $this->freebiehtml->email_footer().'</body></html>';
				
				$succedd = '';
				$failed = '';
				$unique_string = $this->common->randomPassword(10);
				
							$this->Email->sendAs = 'html';
							$this->Email->to = $mode_info['Freebiemail']['specified_email'];
							$this->Email->subject = $this->common->getOfferEmailSubject(); //'Zuni Merchant Page / Everyday Savings Offers';
							$this->Email->replyTo = $this->common->getReturnEmail();
							$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
							$content_final = '';
							//For URL tracking
							$tracking_string = '';//'?unique='.$unique_string.'?'.base64_encode($mode_info['Freebiemail']['id']);
							$content_final = '';
							$content_final .= $this->freebiehtml->email_content($advertiser,$tracking_string,$freebie);
							
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
								$saveCron = '';
								$saveCron['Freebiemail']['id'] = $mode_info['Freebiemail']['id'];
								$saveCron['Freebiemail']['status'] = 1;
								$this->Freebiemail->save($saveCron);
								$this->Session->setFlash('Emails sent successfully to '.$mode_info['Freebiemail']['specified_email'].'.');
							} else {
								$this->Session->setFlash('Mailing Error, please try later.');
							}
							
							$this->Email->reset();
							$this->redirect(array('controller'=>'Freebiemails','action'=>'index'));
				
		}elseif(isset($this->data['Freebiemail']['array_string']) && $this->data['Freebiemail']['array_string']!='') {
				$advertiser = $this->data['Freebiemail']['array_string'];
				$freebie = $this->data['Freebiemail']['freebie'];
				$content = '';
				$content .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Zuni Merchant Page / Everyday Savings Offers</title></head><body style="margin:0px; padding:0px; font-size:0; ">';
				$content .= $this->freebiehtml->email_header();
				$content .= $this->freebiehtml->email_box();
				$footer  = $this->freebiehtml->email_footer().'</body></html>';
				
				set_time_limit(0);
				
				$succedd = '';
				$failed = '';
				$unique_string = $this->common->randomPassword(10);
				$this->loadModel('DiscountNewsletter');
				$users = $this->DiscountNewsletter->find('all',array('fields'=>array('id,email'),'conditions'=>array('status'=>'yes')));
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
							$tracking_string = 'pgrnz6gh'.$unique_string.'?'.base64_encode($id);
							$content_final = '';
							$content_final .= $this->freebiehtml->email_content($advertiser,$tracking_string,$freebie);
							
							// For email open tracking
							$content_final .= '<img src="https://zuni.com/freebieemaillogs/saveEmailOpen?unique='.$unique_string.'?'.base64_encode($id).'" style="display:none;width:0" />';
							
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
			$check_same = $this->common->check_same_date_freebie($today);
			
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
				$save['Offeremaillog']['advrtisers']=$match['Freebiemail']['advertisers'];
				$this->Offeremaillog->save($save);
		//----------------------------------Save Email Log----------------------------------------//
		
				$saveCron = '';
				$saveCron['Freebiemail']['id'] = $mode_info['Freebiemail']['id'];
				$saveCron['Freebiemail']['status'] = 1;
				$this->Freebiemail->save($saveCron);
				
				$this->Session->setFlash('Emails sent successfully to all Subscribers.');
				$this->redirect(array('controller'=>'freebieemaillogs','action'=>'index'));
			}
			
	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function autoBulkEmail() {
	date_default_timezone_set('US/Eastern');
	
	//$match = $this->Freebiemail->query("SELECT id,advertisers FROM Freebiemails WHERE crondate='".date('m/d/Y')."' AND crontime='".date('g')."' AND session='".date('a')."' AND status='0'"); //'fields'=>array('Freebiemail.id','Freebiemail.advertisers'),//
	$match = $this->Freebiemail->find('first',array('conditions'=>array('Freebiemail.crondate'=>date('m/d/Y'),'Freebiemail.crontime'=>date('g'),'Freebiemail.session'=>date('a'),'Freebiemail.status'=>0)));
	
		$this->autoRender = false;
		if(isset($match['Freebiemail']['mode']) && $match['Freebiemail']['mode']==1 && isset($match['Freebiemail']['advertisers']) && $match['Freebiemail']['advertisers']!=''){
				// for under process status
				$saveCron = '';
				$saveCron['Freebiemail']['id'] = $match['Freebiemail']['id'];
				$saveCron['Freebiemail']['status'] = 2;
				$this->Freebiemail->save($saveCron);
				
				
				$advertiser = $match['Freebiemail']['advertisers'];
				$freebie = $match['Freebiemail']['freebie'];
				$content = '';
				$content .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Zuni Merchant Page / Everyday Savings Offers</title></head><body style="margin:0px; padding:0px; font-size:0; ">';
				$content .= $this->autofreebiehtml->email_header();
				$content .= $this->autofreebiehtml->email_box();
				$footer  = $this->autofreebiehtml->email_footer().'</body></html>';
				
				$succedd = '';
				$failed = '';
				$unique_string = $this->common->randomPassword(10);
				
							$this->Email->sendAs = 'html';
							$this->Email->to = $match['Freebiemail']['specified_email'];
							$this->Email->subject = $this->common->getOfferEmailSubject(); //'Zuni Merchant Page / Everyday Savings Offers';
							$this->Email->replyTo = $this->common->getReturnEmail();
							$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
							$content_final = '';
							//For URL tracking
							$tracking_string = '';//'?unique='.$unique_string.'?'.base64_encode($match['Freebiemail']['id']);
							$content_final = '';
							$content_final .= $this->autofreebiehtml->email_content($advertiser,$tracking_string,$freebie);
							
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
								$saveCron = '';
								$saveCron['Freebiemail']['id'] = $match['Freebiemail']['id'];
								$saveCron['Freebiemail']['status'] = 1;
								$this->Freebiemail->save($saveCron);
							}
							
							$this->Email->reset();
							
		}elseif(isset($match['Freebiemail']['advertisers']) && $match['Freebiemail']['advertisers']!='') {
				
				// for under process status
				$saveCron = '';
				$saveCron['Freebiemail']['id'] = $match['Freebiemail']['id'];
				$saveCron['Freebiemail']['status'] = 2;
				$this->Freebiemail->save($saveCron);
				
				$advertiser = $match['Freebiemail']['advertisers'];
				$freebie = $match['Freebiemail']['freebie'];
				$content = '';
				$content .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Zuni Merchant Page / Everyday Savings Offers</title></head><body style="margin:0px; padding:0px; font-size:0; ">';
				$content .= $this->autofreebiehtml->email_header();
				$content .= $this->autofreebiehtml->email_box();
				$footer  = $this->autofreebiehtml->email_footer().'</body></html>';
				
				set_time_limit(0);
				
				$succedd = '';
				$failed = '';
				$unique_string = $this->common->randomPassword(10);
				$this->loadModel('DiscountNewsletter');
				$users = $this->DiscountNewsletter->find('all',array('fields'=>array('id,email'),'conditions'=>array('status'=>'yes')));
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
							$tracking_string = 'pgrnz6gh'.$unique_string.'?'.base64_encode($id);
							$content_final = '';
							$content_final .= $this->autofreebiehtml->email_content($advertiser,$tracking_string,freebie);
							
							// For email open tracking
							$content_final .= '<img src="https://zuni.com/freebieemaillogs/saveEmailOpen?unique='.$unique_string.'?'.base64_encode($id).'" style="display:none;width:0" />';
							
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
			$check_same = $this->common->check_same_date_freebie($today);
			
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
				$save['Offeremaillog']['advrtisers']=$match['Freebiemail']['advertisers'];
				$this->Offeremaillog->save($save);
		//----------------------------------Save Email Log----------------------------------------//
		
				$saveCron = '';
				$saveCron['Freebiemail']['id'] = $match['Freebiemail']['id'];
				$saveCron['Freebiemail']['status'] = 1;
				$this->Freebiemail->save($saveCron);
			}
	}
/************************************ Function to confirm to delete advertiser profile *****************************/
	function intermediate() {}
//-----------------------------------------------------------------------------------------------------------------//
	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username',
            'password' => 'password'
            );
			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
			$this->Auth->allow('autoBulkEmail');
   	}
	/* This function is setting all info about current SuperAdmins in
	currentAdmin array so we can use it anywhere lie name id etc.
	*/
	 function beforeRender(){
		    $this->set('currentAdmin', $this->Auth->user());
			$this->set('cssName',$this->Cookie->read('css_name'));
			$this->set('groupDetail',$this->common->adminDetails());
			$this->set('common',$this->common);
			$this->set('freebiehtml',$this->freebiehtml);
	  }
}