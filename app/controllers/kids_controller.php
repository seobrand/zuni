<?php 
/*
   Programmer : Keshav Sharma
   Date  	  : 9 April 2012
*/
class kidsController extends AppController {
	  var $name = 'kids';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('Auth','common','Cookie','Session');  //component to check authentication . this component file is exists in app/controllers/components
	  
//-------------------------------------------------------index page of school for listing---------------------------------------------------------------------------//
   function index()	{
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;	
		if($this->Session->check('Auth.Admin'))
		{
			$this->set('SchoolList',$this->common->getAllSchool()); //  List Schools
		   	$this->set('common',$this->common);
		   	$condition='';
		   	$this->set('name','Name');
		   	$this->set('school_id','');
	       	$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('Kid.id' => 'desc'));

		if((isset($this->data['Kid']['name']) && $this->data['Kid']['name'] !='Name') ||  (isset($this->params['named']['name']) && $this->params['named']['name'] !='Name'))
		
		 {
		if(isset($this->params['named']['name']))
		{
		    $condition[] = 'Kid.child_name LIKE "%' . str_replace("%20"," ",$this->params['named']['name']). '%"';
		}
		else
		{
		 	$condition[] = 'Kid.child_name LIKE "%' .$this->data['Kid']['name']. '%"';
		 }
		(empty($this->params['named'])) ? $this->set('name', $this->data['Kid']['name']) :$this->set('name', $this->params['named']['name']) ; 
		 } 
				 
	if($this->data['Kid']['school_id']!='' ||  isset($this->params['named']['school_id'] )) 
	{
		  if(isset($this->params['named']['school_id']))
		  {
			 $condition[] = 'Kid.school_id = '.$this->params['named']['school_id'];
		  }
		  else
		  {
			  $condition[] = 'Kid.school_id = '.$this->data['Kid']['school_id'];
		  }
					  
		 (empty($this->params['named'])) ? $this->set('school_id', $this->data['Kid']['school_id']) :$this->set('school_id', $this->params['named']['school_id']) ; 
	}
				 
				$data = $this->paginate('Kid', $condition);
		        $this->set('kids', $data);
 
			}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}
	 }
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function delete($id=NULL) {
			$this->id = $id;
			if(!$this->id) {
				$this->Session->setFlash('Invalid id.');
				$this->redirect($this->referer());
			} else {				
				$this->Kid->delete();
				$this->Session->setFlash('Child profile has been deleted successfully.');
				$this->redirect($this->referer());
			}
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------//	
	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username',
            'password' => 'password'
            );
			//$this->Auth->allow('demo');
			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
   	}	
//-------------- This function is setting all info about current SuperAdmins in currentAdmin array so we can use it anywhere lie name id etc.-------------------------//
	 function beforeRender(){
		    $this->set('currentAdmin', $this->Auth->user());
			$this->set('cssName',$this->Cookie->read('css_name'));
			$this->set('groupDetail',$this->common->adminDetails());
			$this->set('common',$this->common);
			//$this->Ssl->force();
	  }
}
?>