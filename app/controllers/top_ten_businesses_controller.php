<?php 
/*
   Coder: Surbhit
   Date  : 31 Jul 2010
*/ 

class TopTenBusinessesController extends AppController{
 var $name = 'TopTenBusinesses'; 

 var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator'); 

 var $components = array('Auth','common','Session','Cookie');  //component to check authentication . this component file is exists in app/controllers/components

 var $layout = 'admin'; //this is the layout for admin panel 
 

	 
	 #this function call by default when a controller is called
	 function index() {
	      

	 		if($this->Session->check('Auth.Admin')){
			    $this->set('StatesList',$this->common->getAllState());  //  List states
				$this->set('CitiesList',$this->common->getAllCity());   //  List cities
				$this->set('CountyList',$this->common->getAllCounty()); //  List counties
				$this->set('CountriesList',$this->common->getAllCountry()); //  List countries
				$this->set('categoryList',$this->common->getAllCategory()); //  List categories
				$this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
			    $this->redirect(array('action' => "home"));
			}else{
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
	
	function home()
	{
             
			    $this->set('StatesList',$this->common->getAllState());  //  List states
				$this->set('CitiesList',$this->common->getAllCity());   //  List cities
				$this->set('CountyList',$this->common->getAllCounty()); //  List counties
				$this->set('CountriesList',$this->common->getAllCountry()); //  List countries
				$this->set('categoryList',$this->common->getAllCategory()); //  List categories
				$this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
				
				$this->set('city','');
				$this->set('county','');
				$this->set('state','');
				$this->set('category','');
				$this->set('subcategory','');
				$this->set('topcategoryString','');
				$this->set('topsubcategoryString','');
				
				
			App::import('model', 'AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile;	
			 
			 $condition='';
			 $this->paginate = array(
			 	'conditions' => array('TopTenBusiness.publish' => 'yes'),
				'limit' => PER_PAGE_RECORD,
				'order' => array('TopTenBusiness.id' => 'desc')

			 );
		/*----if city is set----------*/ 
		   if($this->data['TopTenBusiness']['city']!='' ||  isset($this->params['named']['city'])) {
				
				   if(isset($this->params['named']['city'])){
				      $condition['TopTenBusiness.city'] = $this->params['named']['city'];
				      }
					else{
					  $condition['TopTenBusiness.city'] = $this->data['TopTenBusiness']['city'];
				   }
				  
				   (empty($this->params['named'])) ? $this->set('city', $this->data['TopTenBusiness']['city']) :$this->set('city', $this->params['named']['city']) ; 
				 }
				 
		  /*----if county is set----------*/	 
		   if($this->data['TopTenBusiness']['county']!='' ||  isset($this->params['named']['county'])) {
				
				   if(isset($this->params['named']['county'])){
				      $condition['TopTenBusiness.county'] = $this->params['named']['county'];
				      }
					else{
					  $condition['TopTenBusiness.county'] = $this->data['TopTenBusiness']['county'];
				   }
				   
				   (empty($this->params['named'])) ? $this->set('county', $this->data['TopTenBusiness']['county']) :$this->set('county', $this->params['named']['county']) ; 
				 }	
				 
			/*----if state is set----------*/	 
			   if($this->data['TopTenBusiness']['state']!='' ||  isset($this->params['named']['state'])) {
				
				   if(isset($this->params['named']['state'])){
				      $condition['TopTenBusiness.state'] = $this->params['named']['state'];
				      }
					else{
					  $condition['TopTenBusiness.state'] = $this->data['TopTenBusiness']['state'];
				   }
				   
				   (empty($this->params['named'])) ? $this->set('state', $this->data['TopTenBusiness']['state']) :$this->set('state', $this->params['named']['state']) ; 
				 }				 
				 			 
			/*----if category is set----------*/	 	 
				if((isset($this->data['TopTenBusiness']['category']) && $this->data['TopTenBusiness']['category']!='') || (isset($this->params['named']['category']) && $this->params['named']['category']!='')) {
				 
				   if(isset($this->params['named']['category'])){
				      $condition['TopTenBusiness.category LIKE'] = '%,' .$this->params['named']['category']. ',%';
				      }else{
					 $condition['TopTenBusiness.category LIKE'] = '%,' .$this->data['TopTenBusiness']['category'] . ',%';
				   }
				   
				   (empty($this->params['named'])) ? $this->set('category', $this->data['TopTenBusiness']['category']) :$this->set('category', $this->params['named']['category']) ; 
				 }
			/*----if subcategory is set----------*/	 	 
			if((isset($this->data['TopTenBusiness']['subcategory']) && $this->data['TopTenBusiness']['subcategory']!='') || (isset($this->params['named']['subcategory']) && $this->params['named']['subcategory']!='')) {				 
				   if(isset($this->params['named']['subcategory'])){
				      $condition['TopTenBusiness.subcategory LIKE'] = '%,' .$this->params['named']['subcategory']. ',%';
				      }else{
					 $condition['TopTenBusiness.subcategory LIKE'] = '%,' .$this->data['TopTenBusiness']['subcategory'] . ',%';
				   }
				   
				   (empty($this->params['named'])) ? $this->set('subcategory', $this->data['TopTenBusiness']['subcategory']) :$this->set('subcategory', $this->params['named']['subcategory']) ; 
				 }

			 $data = $this->paginate('AdvertiserProfile', $condition);
		     $this->set('TopTenBusinesses', $data); 
			 //pr($condition);
	}
	

    function statusChange()
	{ 
	    //echo "hii";die;
	    //echo "<pre>";
	   //print_r($this->data);
	   // App::import('model','AdvertiserProfile'); // importing Ppc model
	   //$this->AdvertiserProfile = new AdvertiserProfile(); 
	  if(isset($this->data)){
	   $saveArray = array();
	   if($this->data['TopTenBusiness']['status']=='enable')
	   {
	   $saveArray['TopTenBusiness']['publish']='yes';
	   }
	   else
	   {
	   $saveArray['TopTenBusiness']['publish']='no';
	   }
	   $saveArray['TopTenBusiness']['status'] = $this->data['TopTenBusiness']['status'];
	   $saveArray['TopTenBusiness']['id'] = $this->data['TopTenBusiness']['id'];
	  // $saveArray['AdvertiserProfile']['id'] = $this->data['AdvertiserProfile']['id'];
	   $this->TopTenBusiness->save($saveArray);
	   $this->Session->setFlash('Your data has been updated successfully.');  
	   $this->redirect(array('action' => "index"));
	   }
	   
	}
	/*

	this function is checking username and pasword in database
	and if true then redirect to home page
	*/

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
		//$this->Ssl->force();
	} 


}//end class
?>
