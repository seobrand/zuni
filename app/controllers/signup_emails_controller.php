<?php 
/*
   Coder: Surbhit
   Date  : 08 Dec 2010
*/ 

class SignupEmailsController  extends AppController { 
      var $name = 'SignupEmails';
	  
     var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax'); 
     var $components = array('Auth','common','Session','Cookie','RequestHandler');  //component to check authentication . this component file is exists in app/controllers/components
      var $layout = 'admin'; //this is the layout for admin panel 
	  function index()
	  {
		
        App::import('model', 'Admin');
	    $this->Admin = new Admin;	
		if($this->Session->check('Auth.Admin'))
		{       
		        $signupemail='';
	            //variable for display number of state name per page
	            $condition='';
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'SignupEmail.id' => 'asc' ));
				
			   				
			  $data = $this->paginate('SignupEmail', $condition);
		      $this->set('signupemail', $data);}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}
			 
			   
			 
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
	 	  
	/*
    	this function is applying images and link header and footer layout
	*/

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
		//$this->Ssl->force();
	} 

}
?>