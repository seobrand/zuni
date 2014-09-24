<?php 
/*
   Coder: Manoj Pandit
   Date  : 18 Oct 2012
*/ 

class ContactLeadsController  extends AppController { 
     var $name = 'ContactLeads';
	 var $useTable = 'contacts';
     var $helpers = array('Html', 'Form','User', 'Javascript','Text','Image','Paginator','Ajax'); 
     var $components = array('Auth','common','Session','Cookie','RequestHandler','Cookie');  
	 //component to check authentication . this component file is exists in app/controllers/components
     
	  var $layout = 'admin'; //this is the layout for admin panel 
//----------------------------------------------------------list contact data from database-----------------------------------------------------------------------//	  
	  function index()
	  {
             //variable for display number of state name per page	
	            $condition='';
				$this->set('StatesList',$this->common->getAllState());
			 	 $this->set('CountyList',$this->common->getAllCounty());
				 $this->set('dept_list',$this->common->getAllDepartment());
			 	 $this->set('countySearch', 'County');
				  $this->set('deptSearch', 'Department');

				$cond = array();
				
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'ContactLead.created' => 'DESC' ));
					
				if((!empty($this->data['contact_leads']['state']) && $this->data['contact_leads']['state']!='state')){
			       $cond['ContactLead.state'] =  $this->data['contact_leads']['state'];
			      (empty($this->params['named'])) ? $this->set('state', $this->data['contact_leads']['state']) :$this->set('state', $this->data['named']['state']);
				 }
				 
				 if((!empty($this->data['contact_leads']['countySearch']) && $this->data['contact_leads']['countySearch']!='County')){
			       $cond['ContactLead.county'] =  $this->data['contact_leads']['countySearch'];
			      (empty($this->params['named'])) ? $this->set('countySearch', $this->data['contact_leads']['countySearch']) :$this->set('countySearch', $this->data['named']['countySearch']);
				 }
				 
				if((!empty($this->data['contact_leads']['deptSearch']) && $this->data['contact_leads']['deptSearch']!='Department')){
			       $cond['ContactLead.department'] =  $this->data['contact_leads']['deptSearch'];
			      (empty($this->params['named'])) ? $this->set('deptSearch', $this->data['contact_leads']['deptSearch']) :$this->set('deptSearch', $this->data['named']['deptSearch']) ; 
				 }

				if(!empty($this->params['named'])){
				     
				     if(isset($this->params['named']['state'] )){
					   $cond['ContactLead.state'] = $this->params['named']['state'] ;
					   $this->set('state', $this->params['named']['state']);
					 }
					 
					 if(isset($this->params['named']['countySearch'] )){
					   $cond['ContactLead.county'] = $this->params['named']['countySearch'] ;
					   $this->set('countySearch', $this->params['named']['countySearch']);
					 }
					 
					  if(isset($this->params['named']['deptSearch'] )){
					   $cond['ContactLead.department'] = $this->params['named']['deptSearch'] ;
					   $this->set('deptSearch', $this->params['named']['deptSearch']);
					 }
					 
				     
				}
				
				 //If condition array is greater then 1 then combine by AND tag
			   if(is_array($condition) && count($condition) > 1) {
			 	   $condition['AND'] = $cond;
			   } else {
			       $condition  = $cond;
			    }

			  $data = $this->paginate('ContactLead', $condition);
		      $this->set('contact_leads', $data);
	  }
//----------------------------------------------------------view contact data from database-----------------------------------------------------------------------//
	  function viewContactDetail($id=null){
	  		$this->set('mycontact',$this->ContactLead->read(null,$id));
	  }
	  
//----------------------------------------------------------delete contact data in database-----------------------------------------------------------------------//
	   function contactDelete($id) {
								
			     $this->ContactLead->delete($id);
				 
			     $this->Session->setFlash('The Contact Lead detail has been deleted.');
			
			     $this->redirect(array('action'=>'index'));
	   }	  
//-----------------------------------font action for contact details----------------------------------------------------------------------------------------------//	
	function contact() {
	
	           if(!$this->Session->read('county_data')){
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
			   }
	//find daily discount for today
					$this->loadModel('DailyDeal');
					$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
					$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
					$this->set('daily_deal',$daily_deal);
		
					//find daily discount for today
					$this->loadModel('DailyDiscount');
					$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
					$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
					$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
					$this->set('daily_disc',$daily_disc);
					
					
					
		$this->layout = 'staticpage';
		$this->set('county_list','');
		$this->set('state_list',$this->common->getAllState());
		$this->set('dept_list',$this->common->getAllDepartment());
		if(isset($this->data))
		{
			App::import('model', 'Contact');
			$this->Contact = new Contact();
			$this->ContactLead->set($this->data);
			
			 if($this->ContactLead->validates()){
			 	if($this->ContactLead->save($this->data))
				{
					//$this->Session->setFlash('Contact information is successfully submited.');
					$this->redirect(FULL_BASE_URL.router::url('/',false).'contact/success');
					
				}else{
					$this->Session->setFlash('Data Save Problem, Please try later.');
				}
				
			 }else{
				$errors = $this->ContactLead->invalidFields();
				$this->Session->setFlash(implode('<br>', $errors));
			}
			
		}	else{if(!$this->Session->read('contactReferer')){
				$this->Session->write('contactReferer',$this->referer());
			}
		}
	}

//-----------------------------------front action for contact details mobile-----------------------------------------------------------------------------------------//	
	function contactMobile() {
		$this->layout = 'staticpage_mobile';
		
		if(!$this->Session->read('state') || !$this->Session->read('county')){
				$this->Session->write('login_referer','contact');
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
		}
		
		$this->set('title_for_layout','Contact us for any query or suggestion &minus; Zuni');
		$this->set('keyword_for_layout','Daily deals,  deal of the day, hot deals,  best deals, local deals, all deals, shopping discounts, best daily deal, daily offers');
		$this->set('description_for_layout','or any query or suggestion, contact Zuni by filling the given form. Let us know your precious suggestions which make us better and better.');
		
		$this->set('county_list','');
		$this->set('state_list',$this->common->getAllState());
		$this->set('dept_list',$this->common->getAllDepartment());
		
	}
//-----------------------------------front action for contact details mobile(ajax)-----------------------------------------------------------------------------------//	
	function contactMobileAjax($mystr='') {
		$this->layout=false;
		$this->autoRender=false;
		App::import('model', 'Contact');
		$this->Contact = new Contact();
		$saveMyArr='';
		$myStrArr=explode('*|*',$mystr);
		$saveMyArr='';
		$saveMyArr['ContactLead']['name']=$myStrArr[0];
		$saveMyArr['ContactLead']['email']=$myStrArr[1];
		$saveMyArr['ContactLead']['state']=$myStrArr[2];
		$saveMyArr['ContactLead']['county']=$myStrArr[3];
		$saveMyArr['ContactLead']['phone']=$myStrArr[4];
		$saveMyArr['ContactLead']['call_time']=$myStrArr[5];
		$saveMyArr['ContactLead']['department']=$myStrArr[6];
		$saveMyArr['ContactLead']['comments']=$myStrArr[7];
		
		$this->ContactLead->save($saveMyArr);
		echo 'success'; exit;
		
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
			$this->Auth->allow('contact','contactMobile','contactMobileAjax');
			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
			$this->set('myCookie', $this->Cookie);
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