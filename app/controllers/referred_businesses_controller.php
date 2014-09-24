<?php 
/*
   Coder: Keshav Sharma
   Date : 24 Nov 2011
*/ 
class ReferredBusinessesController extends AppController { 
      var $name = 'ReferredBusinesses';
	  var $helpers = array('Html','Form','User','Javascript','Text','Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
 	  var $components = array('Auth','common','Session','Cookie','RequestHandler','Email');
	 #this function call by default when a controller is called
	 function index() 
	 {
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;	
		if($this->Session->check('Auth.Admin'))
		{
		   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   $this->set('common',$this->common);
		   $condition='';
		   $this->set('name','Name');
		   $this->set('county_id','');
		   $this->set('status','');		   
	       $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('ReferredBusiness.id' => 'desc'));

		if((isset($this->data['ReferredBusiness']['name']) && $this->data['ReferredBusiness']['name'] !='Name') ||  (isset($this->params['named']['name']) && $this->params['named']['name'] !='Name'))
		
		 {
		if(isset($this->params['named']['name']))
		{
		    $condition[] = 'ReferredBusiness.name LIKE "%' . str_replace("%20"," ",$this->params['named']['name']). '%"';
		}
		else
		{
		 	$condition[] = 'ReferredBusiness.name LIKE "%' .$this->data['ReferredBusiness']['name']. '%"';
		 }
		(empty($this->params['named'])) ? $this->set('name', $this->data['ReferredBusiness']['name']) :$this->set('name', $this->params['named']['name']) ; 
		 } 
				 
	if($this->data['ReferredBusiness']['county_id']!='' ||  isset($this->params['named']['county_id'] )) 
	{
		  if(isset($this->params['named']['county_id']))
		  {
			 $condition[] = 'ReferredBusiness.county_id = '.$this->params['named']['county_id'];
		  }
		  else
		  {
			  $condition[] = 'ReferredBusiness.county_id = '.$this->data['ReferredBusiness']['county_id'];
		  }
					  
		 (empty($this->params['named'])) ? $this->set('county_id', $this->data['ReferredBusiness']['county_id']) :$this->set('county_id', $this->params['named']['county_id']) ; 
	}
				 

	 if((isset($this->data['ReferredBusiness']['status']) && $this->data['ReferredBusiness']['status']!='') || (isset($this->params['named']['status']) && $this->params['named']['status']!='')) 
	 {
		  if(isset($this->params['named']['status']))
		  {
			 $condition[] = 'ReferredBusiness.status = "'.$this->params['named']['status'].'"';
		  }
		  else
		  {
			 $condition[] = 'ReferredBusiness.status = "'.$this->data['ReferredBusiness']['status'].'"';
		  }
					   
	(empty($this->params['named'])) ? $this->set('status', $this->data['ReferredBusiness']['status']) :$this->set('status', $this->params['named']['status']) ; 
	}
				 
				$data = $this->paginate('ReferredBusiness', $condition);
		        $this->set('ReferredBusiness', $data); 
 
			}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}

	 }
	 
	 function suspicious() 
	 {
	 
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;	
		if($this->Session->check('Auth.Admin'))
		{
		   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   $this->set('common',$this->common);
		  	$condition[] = 'ReferredBusiness.refer_ip=ReferredBusiness.refered_ip';
		   $this->set('name','Name');
		   $this->set('county_id','');
		   $this->set('status','');		   
	       $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('ReferredBusiness.id' => 'desc'));

		if((isset($this->data['ReferredBusiness']['name']) && $this->data['ReferredBusiness']['name'] !='Name') ||  (isset($this->params['named']['name']) && $this->params['named']['name'] !='Name'))
		
		 {
		if(isset($this->params['named']['name']))
		{
		    $condition[] = 'ReferredBusiness.name LIKE "%' . str_replace("%20"," ",$this->params['named']['name']). '%"';
		}
		else
		{
		 	$condition[] = 'ReferredBusiness.name LIKE "%' .$this->data['ReferredBusiness']['name']. '%"';
		 }
		(empty($this->params['named'])) ? $this->set('name', $this->data['ReferredBusiness']['name']) :$this->set('name', $this->params['named']['name']) ; 
		 } 
				 
	if($this->data['ReferredBusiness']['county_id']!='' ||  isset($this->params['named']['county_id'] )) 
	{
		  if(isset($this->params['named']['county_id']))
		  {
			 $condition[] = 'ReferredBusiness.county_id = '.$this->params['named']['county_id'];
		  }
		  else
		  {
			  $condition[] = 'ReferredBusiness.county_id = '.$this->data['ReferredBusiness']['county_id'];
		  }
					  
		 (empty($this->params['named'])) ? $this->set('county_id', $this->data['ReferredBusiness']['county_id']) :$this->set('county_id', $this->params['named']['county_id']) ; 
	}
 
				$data = $this->paginate('ReferredBusiness', $condition);
		        $this->set('ReferredBusiness', $data); 
 
			}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}

	 }
	 
	function view($id=null){
		$this->ReferredBusiness->id = $id;
		$this->set('data',$this->ReferredBusiness->read());
	}
	  
	  function delete($id=null){	  	
	  	 $this->ReferredBusiness->delete($id);
		 $this->Session->setFlash('Referred Business has been deleted.');
		 $this->redirect(array('action'=>'index'));
	  }
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocomplete($string='') {

			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			 App::import('model', 'ReferredBusiness');
			$this->ReferredBusiness = new ReferredBusiness;
			$name = $this->ReferredBusiness->query("SELECT ReferredBusiness.name FROM referred_businesses AS ReferredBusiness WHERE ReferredBusiness.name LIKE '$string%'");
			foreach($name as $name) {
				$arr[] = $name['ReferredBusiness']['name'];
			}
			echo json_encode($arr);
			}
	}
		 
	function beforeFilter() { 
			$this->Auth->fields = array(
				'username' => 'username', 
				'password' => 'password'
				);
				$this->Auth->allow('');
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