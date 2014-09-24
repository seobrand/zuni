<?php
class ThankemailsController extends AppController {
	  var $name = 'Thankemails';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax','Calendar');
	  //var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar');
	  var $layout = 'admin';
	  var $components = array('Auth','common','Cookie','Email','RequestHandler','Session','thankshtml');
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function index() {
		if(isset($this->data)){
			if($this->data['Thankemail']['advertisers'] && $this->data['Thankemail']['offer']) {
				$data = base64_encode(serialize($this->data));
				$this->redirect(array('action'=>'offerHtml',$data));
				//pr(unserialize(base64_decode($data)));
				exit;
			}
		} 
		$this->set('title_for_layout', 'Thanks Email');
		$this->set('advertiserList',$this->common->getAdvertiserProfileAll());
	}
//-----------------------------------------------------------------------------------------------------------------//	
	function checkOffer() {
		$this->layout = false;
		if(isset($this->params['isAjax']) && isset($this->params['form']['advertiser'])) {
			$advertiser = $this->params['form']['advertiser'];
			$this->loadModel('SavingOffer');
			$Offer = $this->SavingOffer->find('all',array('fields'=>array('SavingOffer.advertiser_profile_id','SavingOffer.title','SavingOffer.id','SavingOffer.off_unit','SavingOffer.off_text','SavingOffer.off','AdvertiserProfile.city','AdvertiserProfile.main_image','AdvertiserProfile.main_image_type','AdvertiserProfile.logo','AdvertiserProfile.company_name','SavingOffer.current_saving_offer'),'conditions'=>array('SavingOffer.status = "yes" AND FROM_UNIXTIME(`offer_start_date`) < CURDATE() AND FROM_UNIXTIME(`offer_expiry_date`) > CURDATE() AND SavingOffer.advertiser_profile_id='.$advertiser),'order' =>'SavingOffer.id ASC'));
			$this->set(compact('Offer'));
		}
	}
//-----------------------------------------------------------------------------------------------------------------//		
	function offerHtml() {
		$this->set('title_for_layout', 'Merchant Thank You Email');
		if(isset($this->params['pass'][0])) {
			$data = unserialize(base64_decode($this->params['pass'][0]));
			$advertiser = $data['Thankemail']['advertisers'];
			$offer = $data['Thankemail']['offer'];
			$this->set('advertiser',$advertiser);
			$this->set('offer',$offer);
		} else {
			$this->redirect(array('action'=>'index'));
			exit;
		}

	}
//-----------------------------------------------------------------------------------------------------------------//		
	function copy() {
		$this->set('title_for_layout', 'Merchant Thank You Email');
		if(isset($this->params['pass'][0])) {
			$data = unserialize(base64_decode($this->params['pass'][0]));
			$advertiser = $data['Thankemail']['advertisers'];
			$offer = $data['Thankemail']['offer'];
			$this->set('advertiser',$advertiser);
			$this->set('offer',$offer);
		} else {
			$this->redirect(array('action'=>'index'));
			exit;
		}

	}
/************************************ Function to confirm to delete advertiser profile *********************************************/		
	function test_offer_email() {
		$this->autoRender = false;
		if(isset($this->data)) {
				$advertiser = $this->data['Thankemail']['array_string'];
				$offer = $this->data['Thankemail']['offer'];
				$content = '';
				$content .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Merchant Thank You Email</title></head><body style="margin:0px; padding:0px; font-size:0; background-color:#2f2f2f;">';
				$content .= $this->thankshtml->email_data($advertiser,$offer);
				$content .= '</body></html>';

				$this->Email->sendAs = 'html';
				$this->Email->to = $this->data['Thankemail']['email'];
				$this->Email->subject = 'Zuni Merchant Thank You Email';//'Zuni Merchant Page / Everyday Savings Offers';
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
	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username',
            'password' => 'password'
            );
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
			$this->set('thankshtml',$this->thankshtml);
	  }
}