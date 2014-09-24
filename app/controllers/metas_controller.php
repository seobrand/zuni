<?php 
/*
   Coder: Vijender Singh Rana
   Date  : 12 Jan 2011
*/ 
class MetasController extends AppController { 
	  var $name = 'Metas';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('Auth','common','Cookie','Session');  //component to check authentication . this component file is exists in app/controllers/components
	  
      /*    destroy all current sessions for a perticular SuperAdmins
	       and redirect to login page automatically
	 */
		function logout() {
				 $this->redirect($this->Auth->logout());
		}
     // index page of link for listing
		
	   function index(){
		
		$this->set('StatesList',$this->common->getAllState());
	   $this->set('getCategory',$this->common->getCatList());
	   
		$state_id = '';
		if(isset($this->data)) {
			$state_id = $this->data['Meta']['state_id'];
		} else if(isset($this->params['named']['state_id'] )){
			$state_id = $this->params['named']['state_id'];
		}
		
		$county_id = '';
		if(isset($this->data)) {
			$county_id = $this->data['Meta']['county_id'];
		} else if(isset($this->params['named']['county_id'] )){
			$county_id = $this->params['named']['county_id'];
		}
		
		$city_id = '';
		if(isset($this->data)) {
			$city_id = $this->data['Meta']['city_id'];
		} else if(isset($this->params['named']['city_id'] )){
			$city_id = $this->params['named']['city_id'];
		}
		
		$category = '';
		if(isset($this->data)) {
			$category = $this->data['Meta']['category'];
		} else if(isset($this->params['named']['category'] )){
			$category = $this->params['named']['category'];
		}		 
		
		$this->set('state_id',$state_id);
		$this->set('county_id',$county_id);
		$this->set('city_id',$city_id);
		$this->set('category',$category);
		
		$this->set('Cities',$this->common->getCountyCity($county_id));   //  List cities
		$this->set('Counties',$this->common->getAllCountyByState($state_id)); //  List counties
		
		
		if(isset($this->params['named']['message'])) {
		  if($this->params['named']['message']=='success') {
			 $this->set('success','success');
		  }else{
		   $this->set('error','error');
		  }
		}
		//pr($this->params);die;
	     $condition='';
		$cond = array();
		$this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'Meta.id' => 'asc' ));
		
		if(!empty($this->data['Meta']['state_id']))
		{
		     $cond['Meta.state_id'] = $this->data['Meta']['state_id'];
		 }
		 
		 if(!empty($this->data['Meta']['city_id']))
		{
		     $cond['Meta.city_id'] = $this->data['Meta']['city_id'];
		 }
		 
				
		if(!empty($this->data['Meta']['county_id'])){
		     $cond['Meta.county_id'] = $this->data['Meta']['county_id'];
		 }
		 
		 if(!empty($this->data['Meta']['category'])){
		       $cat_id=explode('/',$this->data['Meta']['category']);
		       $cond['Meta.category_id'] = $cat_id[0];
			   $cond['Meta.subcategory_id'] = $cat_id[1];
		 }
		 
		 
				if(!empty($this->params['named'])){
					
					if(isset($this->params['named']['state_id'] )){
					   $cond['Meta.state_id'] = $this->params['named']['state_id'];
					 }
					
				     if(isset($this->params['named']['city_id'] )){
					   $cond['Meta.city_id'] = $this->params['named']['city_id'];
					 }
					 
				     if(isset($this->params['named']['county_id'] )){
					   $cond['Meta.county_id'] = $this->params['named']['county_id'] ;
					 }
					 
					 if(isset($this->params['named']['category'] )){
					 	$cat_id=explode('-',$this->params['named']['category']);
		       			$cond['Meta.category_id'] = $cat_id[0];
			   			$cond['Meta.subcategory_id'] = $cat_id[1];
					 }
				}
		 
				 
       if(is_array($condition) && count($condition) > 1) {
			 	   $condition['AND'] = $cond;
			   } else {
			       $condition  = $cond;
			    }

			  $data = $this->paginate('Meta', $condition);
		      $this->set('Metadetail', $data); 
	 
	 
	
	   }
	   // adding new ,link in database
	  
	    function addmeta(){
		$this->set('StatesList',$this->common->getAllState());
		$this->set('getCategory',$this->common->getCatList());
		$state_id = '';
		if(isset($this->data)) {
			$state_id = $this->data['Meta']['state_id'];
		}
		$county_id = '';
		if(isset($this->data)) {
			$county_id = $this->data['Meta']['county_id'];
		}
		$city_id = '';
		if(isset($this->data)) {
			$city_id = $this->data['Meta']['city_id'];
		}
		$cat = '';
		if(isset($this->data)) {
			$cat = $this->data['Meta']['category'];
		}
		$this->set('state',$state_id);
		$this->set('county',$county_id);
		$this->set('city',$city_id);
		$this->set('cat',$cat);
		
		$this->set('CitiesList',$this->common->getCountyCity($county_id));   //  List cities
		$this->set('CountyList',$this->common->getAllCountyByState($state_id)); //  List counties
		if(isset($this->data))
		  {
				$this->Meta->set($this->data);
				$errors = $this->Meta->validatesMetaInfo($this->data,$this->params);
				if(count($errors)==0)
				{
					if($this->data['Meta']['category']!='') {
					   	$scat_id=explode('/',$this->data['Meta']['category']);
					   	$this->data['Meta']['subcategory_id']=$scat_id[1];
					   	$this->data['Meta']['category_id']=$scat_id[0];
					 } else {
					 	$this->data['Meta']['subcategory_id']=0;
					   	$this->data['Meta']['category_id']=0;
					 }
					 if($this->data['Meta']['city_id']=='') {
					 	$this->data['Meta']['city_id']=0;
					 }
						if($this->Meta->save($this->data))
						{
							$this->Session->setFlash('Meta details has been save successfully.');
							$this->redirect(array('controller'=>'metas','action' => "index/message:success"));
						}
						else
						{
							$this->Session->setFlash('Meta details save problem, Please try again'); 
						}
				} else {
						$this->Session->setFlash(implode('<br>', $errors));
						//$this->redirect(array('controller'=>'metas','action' => "addmeta"));
					}  
			  }
		}
		//edit banner data
	   function editmeta($id=null){
	   //pr($this->Meta->read());die;
	   	$metadetails=$this->Meta->read();
		$this->set('Metadetail',$metadetails);
		$this->set('StatesList',$this->common->getAllState());
		$this->set('getCategory',$this->common->getCatList());
		$state_id = $metadetails['Meta']['state_id'];
		if(isset($this->data)) {
			$state_id = $this->data['Meta']['state_id'];
		}
		$county_id = $metadetails['Meta']['county_id'];
		if(isset($this->data)) {
			$county_id = $this->data['Meta']['county_id'];
		}
		$city_id = $metadetails['Meta']['city_id'];
		if(isset($this->data)) {
			$city_id = $this->data['Meta']['city_id'];
		}
		$cat = $metadetails['Meta']['category_id'].'/'.$metadetails['Meta']['subcategory_id'];
		if(isset($this->data)) {
			$cat = $this->data['Meta']['category'];
		}
		$this->set('state',$state_id);
		$this->set('county',$county_id);
		$this->set('city',$city_id);
		$this->set('cat',$cat);
		
		$this->set('CitiesList',$this->common->getCountyCity($county_id));   //  List cities
		$this->set('CountyList',$this->common->getAllCountyByState($state_id)); //  List counties
		if(isset($this->data))
		  {
		  		$this->data['Meta']['id'] = $metadetails['Meta']['id'];
				$this->Meta->set($this->data);
				$errors = $this->Meta->validatesMetaInfo($this->data,$this->params);
				if(count($errors)==0)
				{
					if($this->data['Meta']['category']!='') {
					   	$scat_id=explode('/',$this->data['Meta']['category']);
					   	$this->data['Meta']['subcategory_id']=$scat_id[1];
					   	$this->data['Meta']['category_id']=$scat_id[0];
					 } else {
					 	$this->data['Meta']['subcategory_id']=0;
					   	$this->data['Meta']['category_id']=0;
					 }
					 if($this->data['Meta']['city_id']=='') {
					 	$this->data['Meta']['city_id']=0;
					 }
					 
					 
						 if($this->Meta->save($this->data,false))
						{
							$this->Session->setFlash('Meta details has been save successfully.');
							$this->redirect(array('controller'=>'metas','action' => "index/message:success"));
						}
						else
						{
							$this->Session->setFlash('Meta details save problem, Please try again'); 
						}
				} else {
						$this->Session->setFlash(implode('<br>', $errors));
						//$this->redirect(array('controller'=>'metas','action' => "addmeta"));
					}  
			  }
		
	   }
		
		//delete link data in database
	   function editmetadetail() {

	    if(isset($this->data))
		  {	
		  
		  		$this->Meta->set($this->data);				
		       	if(count($this->Meta->validatesMetaInfo($this->data,$this->params,$this->data['Meta']['id']))==0)
				{ 
                       $scat_id=explode('-',$this->params['form']['business']);
                       $this->data['Meta']['subcategory_id']=$scat_id[1];
					   if($this->Meta->save($this->data,false))
						{
						$this->Session->setFlash('Meta details has been updated successfully.');  
					    $this->redirect(array('controller'=>'metas','action' => "index/message:success"));
						}
						else
						{
						$this->Session->setFlash('Meta details updated problem, Please try again'); 
						}
			      }
					  
				
					else
					{
					    $errors = $this->Meta->validatesMetaInfo($this->data,$this->params,$this->data['Meta']['id']);
						$this->Session->setFlash(implode('<br>', $errors));  
						$this->redirect(array('controller'=>'metas','action' => "editmeta/".$this->data['Meta']['id']));
					}  
		      }
	   }
	   
	   function deletemeta()
	   {
	   $this->Meta->delete($id);
	   $this->Session->setFlash('Meta detail has been deleted.');
		$this->redirect(array('action'=>'index' , 'message'=>'success'));
	   }
	   
	   
	   //Set css for different color options
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
    this function is checking username and password in database
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
} 