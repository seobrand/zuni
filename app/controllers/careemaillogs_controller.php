<?php
class CareemaillogsController extends AppController {
	  var $name = 'Careemaillogs';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');
	  var $layout = 'admin';
	  var $components = array('Auth','common','Cookie','Email','RequestHandler','emailhtml','mobile','Session');
//------------------------------------------------------------------------------------------------------------------------//
	   function index(){
	   				$this->Careemaillog->query("DELETE FROM careemaillogs WHERE sending_date is null");
					if(isset($this->params['pass'][0])) {
						$lastlog = $this->Careemaillog->find('first',array('conditions'=>array('Careemaillog.unique'=>$this->params['pass'][0],'Careemaillog.sending_date!=""'),'order'=>'Careemaillog.id DESC'));
					} else {
						$lastlog = $this->Careemaillog->find('first',array('conditions'=>array('Careemaillog.sending_date!=""'),'order'=>'Careemaillog.id DESC'));
					}
					$this->set('lastlog',$lastlog);
					$sent = 0;
					$delivered = 0;
					$opened = 0;
					$link = 0;
					$this->loadModel('DiscountNewsletter');
					if(!empty($lastlog)) {
						$ids = $lastlog['Careemaillog']['sent'].','.$lastlog['Careemaillog']['notsent'];
						$idarr = implode(',',array_values(array_unique(array_filter(explode(',',$ids)))));
						$cond[] = "DiscountNewsletter.id IN (".$idarr.")";
						
						$delivered = implode(',',array_values(array_unique(array_filter(explode(',',$lastlog['Careemaillog']['sent'])))));
						$cond1 = '';
						if($delivered) {
							$cond1[] = "DiscountNewsletter.id IN (".$delivered.")";
							$cond1[] = "DiscountNewsletter.email!=''";
							$cond1['DiscountNewsletter.status'] = 'yes';
							$delivered = $this->DiscountNewsletter->find('count',array('conditions'=>$cond1));
						} else {
							$delivered = 0;
						}
						
						$opened = implode(',',array_values(array_unique(array_filter(explode(',',$lastlog['Careemaillog']['email_opened'])))));
						$cond1 = '';
						if($opened) {
							$cond1[] = "DiscountNewsletter.id IN (".$opened.")";
							$cond1[] = "DiscountNewsletter.email!=''";
							$cond1['DiscountNewsletter.status'] = 'yes';
							$opened = $this->DiscountNewsletter->find('count',array('conditions'=>$cond1));
						} else {	
							$opened = 0;
						}
						
						$link = implode(',',array_values(array_unique(array_filter(explode(',',$lastlog['Careemaillog']['opened'])))));
						$cond1 = '';
						if($link) {
							$cond1[] = "DiscountNewsletter.id IN (".$link.")";
							$cond1[] = "DiscountNewsletter.email!=''";
							$cond1['DiscountNewsletter.status'] = 'yes';
							$link = $this->DiscountNewsletter->find('count',array('conditions'=>$cond1));
						} else {
							$link = 0;
						}
					}
					$this->set('delivered',$delivered);
					$this->set('opened',$opened);
					$this->set('link',$link);
					
					
					
					$cond[] = "DiscountNewsletter.email!=''";
					
	    			$this->set('title_for_layout', 'Saving offer email tracking log');
					
					$this->paginate = array('limit' => PER_PAGE_RECORD, 'order' => array('DiscountNewsletter.id' => 'desc'));

					$cond['DiscountNewsletter.status'] = 'yes';
					
					$sent = $this->DiscountNewsletter->find('count',array('conditions'=>$cond));
					if(!$link) {$link=0;}
					$this->set('sent',$sent);
				
				
		/*----------------------------------It sets data to view by specified condition--------------------------------------------------------*/
				$data = $this->paginate('DiscountNewsletter', $cond);
				//pr($data);
		    	$this->set('subscribers', $data);
				
	}
//------------------------------------------------------------------------------------------------------------------------//	
	function saveEmailOpen() {
		$this->autoRender = false;
		$breakstring = explode('?',$this->params['url']['unique']);
		if(count($breakstring)==2) {
			$this->common->saveCareEmailOpenStatus($breakstring[0],base64_decode($breakstring[1]));
		}
	}
//------------------------------------------------------------------------------------------------------------------------//
	/*
    this function is checking username and password in database
	and if true then redirect to home page
	*/
	function beforeFilter() {

        $this->Auth->fields = array(
            'username' => 'username',
            'password' => 'password'
            );
			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
			$this->Auth->allow('saveEmailOpen');
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