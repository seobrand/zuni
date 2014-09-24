<?php
class FreebieemaillogsController extends AppController {
	  var $name = 'Freebieemaillogs';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');
	  var $layout = 'admin';
	  var $components = array('Auth','common','Cookie','Email','RequestHandler','emailhtml','mobile','Session');
//------------------------------------------------------------------------------------------------------------------------//
	   function index(){
	   				$this->Freebieemaillog->query("DELETE FROM freebieemaillogs WHERE sending_date is null");
					if(isset($this->params['pass'][0])) {
						$lastlog = $this->Freebieemaillog->find('first',array('conditions'=>array('Freebieemaillog.unique'=>$this->params['pass'][0],'Freebieemaillog.sending_date!=""'),'order'=>'Freebieemaillog.id DESC'));
					} else {
						$lastlog = $this->Freebieemaillog->find('first',array('conditions'=>array('Freebieemaillog.sending_date!=""'),'order'=>'Freebieemaillog.id DESC'));
					}
					$this->set('lastlog',$lastlog);
					$sent = 0;
					$delivered = 0;
					$opened = 0;
					$link = 0;
					$this->loadModel('DiscountNewsletter');
					if(!empty($lastlog)) {
						$ids = $lastlog['Freebieemaillog']['sent'].','.$lastlog['Freebieemaillog']['notsent'];
						$idarr = implode(',',array_values(array_unique(array_filter(explode(',',$ids)))));
						$cond[] = "DiscountNewsletter.id IN (".$idarr.")";
						
						$delivered = implode(',',array_values(array_unique(array_filter(explode(',',$lastlog['Freebieemaillog']['sent'])))));
						$cond1 = '';
						if($delivered) {
							$cond1[] = "DiscountNewsletter.id IN (".$delivered.")";
							$cond1[] = "DiscountNewsletter.email!=''";
							$cond1['DiscountNewsletter.status'] = 'yes';
							$delivered = $this->DiscountNewsletter->find('count',array('conditions'=>$cond1));
						} else {
							$delivered = 0;
						}
						
						$opened = implode(',',array_values(array_unique(array_filter(explode(',',$lastlog['Freebieemaillog']['email_opened'])))));
						$cond1 = '';
						if($opened) {
							$cond1[] = "DiscountNewsletter.id IN (".$opened.")";
							$cond1[] = "DiscountNewsletter.email!=''";
							$cond1['DiscountNewsletter.status'] = 'yes';
							$opened = $this->DiscountNewsletter->find('count',array('conditions'=>$cond1));
						} else {	
							$opened = 0;
						}
						
						$link = implode(',',array_values(array_unique(array_filter(explode(',',$lastlog['Freebieemaillog']['opened'])))));
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
					
	    			$this->set('title_for_layout', 'Today\'s freebie email tracking log');
					
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
			$this->common->saveFreebieEmailOpenStatus($breakstring[0],base64_decode($breakstring[1]));
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