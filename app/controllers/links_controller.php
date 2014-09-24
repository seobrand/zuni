<?php 
/*
   Coder: Abhimanyu
   Date  : 16 Aug 2010
*/ 
class LinksController extends AppController { 
	  var $name = 'Links';
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
             //variable for display number of link name per page	
	            $condition='';
				$cond = array();
				$this->set('Categorys', $this->common->getAllCategory());
				
				$this->set('States', $this->common->getAllState());
			 
			    $this->set('Countys', $this->common->getAllCounty());
				
				$this->set('Citys', $this->common->getAllCity());
				
				$this->set('Subcategorys', $this->common->getAllSubCategory());
				
	            $this->set('categorySearch', 'Category');
				
				$this->set('stateSearch', 'State'); 
				
				$this->set('countySearch', 'County');
				
				$this->set('citySearch', 'City');
				
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'Link.title' => 'asc' ));
				
				if($this->data['links']['citySearch']) {
			        $cond['Link.city_id'] =  $this->data['links']['citySearch'] ;
			      (empty($this->params['named'])) ? $this->set('citySearch', $this->data['links']['citySearch']) :$this->set('citySearch', $this->data['named']['citySearch']) ; 
			    }
				if($this->data['links']['countySearch']) {
			        $cond['Link.county_id'] =  $this->data['links']['countySearch'] ;
			      (empty($this->params['named'])) ? $this->set('countySearch', $this->data['links']['countySearch']) :$this->set('countySearch', $this->data['named']['countySearch']) ; 
			    }
				if($this->data['links']['stateSearch']) {
			        $cond['Link.state_id'] =  $this->data['links']['stateSearch'] ;
			      (empty($this->params['named'])) ? $this->set('stateSearch', $this->data['links']['stateSearch']) :$this->set('stateSearch', $this->data['named']['stateSearch']) ; 
			    }
				if($this->data['links']['categorySearch']!="" )  {
				     $cond['Link.category_id LIKE'] = '%' .','. $this->data['links']['categorySearch'] .','. '%';
			        (empty($this->params['named'])) ? $this->set('categorySearch', $this->data['links']['categorySearch']) :$this->set('categorySearch', $this->data['named']['categorySearch']) ;  
		        } 
				if(!empty($this->params['named'])){
				     if(isset($this->params['named']['citySearch'] )){
					   $cond['Link.city_id'] =  $this->params['named']['city_id'] ;
					   $this->set('area', $this->params['named']['city_id']);
					 }
					 if(isset($this->params['named']['countySearch'] )){
					   $cond['Link.county_id'] =  $this->params['named']['countySearch'] ;
					   $this->set('countySearch', $this->params['named']['countySearch']);
					 }
					  if(isset($this->params['named']['stateSearch'] )){
					   $cond['Link.state_id'] =  $this->params['named']['stateSearch'] ;
					   $this->set('stateSearch', $this->params['named']['stateSearch']);
					 }
					 if(isset($this->params['named']['categorySearch'] )){
					   $cond['Link.category_id LIKE'] = '%'.',' . $this->params['named']['categorySearch'].',' . '%';
					   $this->set('categorySearch', $this->params['named']['categorySearch']);
					 }
				}
				
				 //If condition array is greater then 1 then combine by AND tag
			   if(is_array($condition) && count($condition) > 1) {
			 	   $condition['AND'] = $cond;
			   } else {
			       $condition  = $cond;
			    }
			  $data = $this->paginate('Link', $condition);
		      $this->set('links', $data); 
	   }
	   // adding new ,link in database
	  
	    function addNewLink(){  	
		
		          // echo "hi";
		           $this->set('Categorys', $this->common->getAllCategory());
				
				   $this->set('States', $this->common->getAllState());
			 
			       $this->set('Countys', $this->common->getAllCounty());
				
				   $this->set('Citys', $this->common->getAllCity());
				
				   $this->set('Subcategorys', $this->common->getAllSubCategory());
					
					
	              if(isset($this->data)){
			  
	    	               $this->Link->set($this->data['links']);
				   
			               if (empty($this->data)){
                          		   $this->data = $this->Link->find(array('Link.id' => $id));
                             }
							 
			               if($this->data['links']!=''){

					                 if ($this->Link->validates()) {
									    if($this->data['links']['category_id']!=""){
									      //making data array so we can pass in save mathod
									      $saveArray = array();
									      $saveArray['Link']    =  $this->data['links'];
										  
										  $parentCategory   = ",";
					                      $parentCategory  .= implode(",",$this->data['links']['category_id']);
					                      $parentCategory  .= ",";
										  $saveArray['Link']['category_id'] = $parentCategory;
										  if($this->data['links']['subcategory_id']!=""){
										   $childCategory   = ",";
					                       $childCategory  .= implode(",",$this->data['links']['subcategory_id']);
					                       $childCategory  .= ",";  
										   $saveArray['Link']['subcategory_id'] = $childCategory;
										  } else {
										    $saveArray['Link']['subcategory_id'] = "";
										  }
									      $this->Link->save($saveArray);
									      $this->Session->setFlash('Your data has been submitted successfully.');  
									      $this->redirect(array('action' => "index"));
										  
										 } else {
                                         /*setting error message if validation fails*/
							             $errors = "Please select the  category ";
									     $this->Session->setFlash($errors);
                                      }

						             }else{  

									      /*setting error message if validation fails*/
									      $errors = $this->Link->invalidFields();
									      $this->Session->setFlash(implode('<br>', $errors));  
									       //$this->redirect(array('action' => "userGroup", 'message'=>'error'));
						             }
				            }
	              }
        
	   }
	  // show data in edit link form
	   function linkEditDetail($id=null){
	         $this->set('States', $this->common->getAllState());
			 $this->set('Countys', $this->common->getAllCounty());
			 $this->set('Categorys', $this->common->getAllCategory());
			 $this->set('Citys', $this->common->getAllCity());
			 $this->set('Subcategorys', $this->common->getAllSubCategory());
	         $this->set('Link',$this->Link->linkEditDetail($id));
	    }
		
		//edit banner data
	   function linkEdit($id=null){
	  
	         $this->Link->set($this->data['links']);	

			 if ($this->Link->validates()) {
			          if($this->data['links']['category_id']!=""){

							             $saveArray = array();
									      $saveArray['Link']    =  $this->data['links'];
										  
										  $parentCategory   = ",";
					                      $parentCategory  .= implode(",",$this->data['links']['category_id']);
					                      $parentCategory  .= ",";
										  $saveArray['Link']['category_id'] = $parentCategory;
										  if($this->data['links']['subcategory_id']!=""){
										   $childCategory   = ",";
					                       $childCategory  .= implode(",",$this->data['links']['subcategory_id']);
					                       $childCategory  .= ",";  
										   $saveArray['Link']['subcategory_id'] = $childCategory;
										  } else {
										    $saveArray['Link']['subcategory_id'] = "";
										  }
									      $this->Link->save($saveArray);
							              $this->Session->setFlash('Your data has been updated successfully.');  
							              $this->redirect(array('action' => "index"));
						} else {
                                   /*setting error message if validation fails*/
							        $errors = "Please select the  category";
									$this->Session->setFlash($errors);
                        }
			  } else{  

							/*setting error message if validation fails*/
							$errors = $this->Link->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "linkEditDetail/".$this->data['links']['id'])); 
							
			  }
	    }
		
		//delete link data in database
	   function linkDelete($id) {
								
			     $this->Link->delete($id);
				 
			     $this->Session->setFlash('The Link detail with id: '.$id.' has been deleted.');
			
			     $this->redirect(array('action'=>'index'));
	   }
	   // add data in city link form
	   function addNewLinkCity($id=null){
	         $this->set('States', $this->common->getAllState());
			 $this->set('Countys', $this->common->getAllCounty());
			 
			 App::import('model','City'); // importing Article model
			 $this->City = new City(); 
			
			if($this->data){
				 $this->City->set($this->data['links']);
				 if($this->data['links']!=''){

					 if ($this->City->validates()) {
									//making data array so we can pass in save mathod
									$saveArray = array();
									$saveArray['City']['cityname']    = $this->data['links']['cityname'];
									$saveArray['City']['publish']     = $this->data['links']['publish'];
									$saveArray['City']['county_id']   = $this->data['links']['county_id'];
									$saveArray['City']['state_id']    = $this->data['links']['state_id'];
																
									$this->City->save($saveArray);
									$this->Session->setFlash('Your data has been submitted successfully.');  
									if($this->data['links']['id']==""){  
									   $this->redirect(array('action' => "addNewLink"));
									} else {
									   $this->redirect(array('action' => "linkEditDetail",$this->data['links']['id']));
									}

						}else{  

									/*setting error message if validation fails*/
									$errors = $this->City->invalidFields();	
									$this->Session->setFlash(implode('<br>', $errors));  
									if($this->data['links']['id']==""){  
									   $this->redirect(array('action' => "addNewLink"));
									} else {
									   $this->redirect(array('action' => "linkEditDetail",$this->data['links']['id']));
									} 
						}
				 }
		    }
	
	  }
	   // add data in county link form
	   function addNewLinkCounty($id=null){
	         $this->set('States', $this->common->getAllState());
			 
			 App::import('model','County'); // importing Article model
			 $this->County = new County(); 
			
			if($this->data){

				 $this->County->set($this->data['links']);
				 if($this->data['links']!=''){

					 if ($this->County->validates()) {
									//making data array so we can pass in save mathod
									$saveArray = array();
									$saveArray['County']['countyname'] = $this->data['links']['countyname'];
									$saveArray['County']['publish']    = $this->data['links']['publish'];
									$saveArray['County']['state_id']   = $this->data['links']['state_id'];
																
									$this->County->save($saveArray);
									$this->Session->setFlash('Your data has been submitted successfully.');
									if($this->data['links']['id']==""){  
									   $this->redirect(array('action' => "addNewLink"));
									} else {
									   $this->redirect(array('action' => "linkEditDetail",$this->data['links']['id']));
									}

						}else{  
						
									/*setting error message if validation fails*/
									$errors = $this->County->invalidFields();	
									$this->Session->setFlash(implode('<br>', $errors)); 
									if($this->data['links']['id']==""){  
									   $this->redirect(array('action' => "addNewLink"));
									} else {
									   $this->redirect(array('action' => "linkEditDetail",$this->data['links']['id']));
									} 
									
						}
				 }
		    }
	
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
	 //
	function beforeFilter() { 
        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );
			$this->Auth->allow('send_proof');
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