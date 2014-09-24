<?php 
/*
   Coder :  Abul Muzaffar Muhy-ud-Din Muhammad Aurangzeb Alamgir  
   Date  :  18 Aug 1857
*/ 
class PackagesController extends AppController { 
	  var $name = 'Packages';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('Auth','common','Cookie','Session');  //component to check authentication . this component file is exists in app/controllers/components
	  
      /*    destroy all current sessions for a perticular SuperAdmins
	       and redirect to login page automatically
	 */
	    function logout() {
   		         $this->redirect($this->Auth->logout());
        }


     // index page of package for listing
	   function index(){  
             //variable for display number of package name per page	
	            $condition[] =   array("Package.type != 'special'");
				
	            $this->set('search_text', 'Package Name'); 
				
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'Package.name' => 'asc' ));
			    if(!empty($this->data['packages']['search_text'] ))  {
				
                           $this->set('search_text', $this->data['packages']['search_text']); 
				           $condition[] =   array('Package.name LIKE' => '%' . $this->data['packages']['search_text'] . '%');
						   
		        } 
				
	           if(isset($this->params['named']['search_text'])){
			   
                          $this->set('search_text', $this->params['named']['search_text']);
				          $condition[] =   array('Package.name LIKE' => '%' . $this->params['named']['search_text'] . '%');   
						  
		        } 
			  $data = $this->paginate('Package', $condition);
		      $this->set('packages', $data); 
	   }
     // index page of package for listing
	   function specialpkg(){
             //variable for display number of package name per page	
	             $condition[] =   array('Package.type'=> 'special');
				
	            $this->set('search_text', 'Package Name'); 
				
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'Package.name' => 'asc' ));
			    if(!empty($this->data['packages']['search_text'] ))  {
				
                           $this->set('search_text', $this->data['packages']['search_text']); 
				           $condition[] =   array('Package.name LIKE' => '%' . $this->data['packages']['search_text'] . '%');
						   
		        } 
				
	           if(isset($this->params['named']['search_text'])){
			   
                          $this->set('search_text', $this->params['named']['search_text']);
				          $condition[] =   array('Package.name LIKE' => '%' . $this->params['named']['search_text'] . '%');   
						  
		        }
			  $data = $this->paginate('Package', $condition);
		      $this->set('packages', $data); 
	   }	   
	  // adding new package in database
	  
	    function addNewPackage(){
					$type = '';					
	              if(isset($this->data)){
	    	               	$this->Package->set($this->data['packages']);
			               if (empty($this->data)){
                          		   $this->data = $this->Package->find(array('Package.id' => $id));
                             }
							 
			               if($this->data['packages']!=''){

					                 if ($this->Package->validates()) {
									      //making data array so we can pass in save mathod
									      $saveArray = array();
									      $saveArray['Package']['name']     		= $this->data['packages']['name'];
									      $saveArray['Package']['status']   		= $this->data['packages']['status'];
										  $type = '';
										  if($this->data['packages']['front_page']==1) {
										  	$type .= ',front';
										  }
										  if($this->data['packages']['sales_page']==1) {
										  	$type .= ',sales_person';
										  }
										  $saveArray['Package']['type'] = $type;
										  if($this->data['packages']['type']=='special') {
												$saveArray['Package']['banner']		=0;
												$saveArray['Package']['merchant']	=0;
												$saveArray['Package']['promotional']=0;
												$saveArray['Package']['website']	=0;
												$saveArray['Package']['map']		=0;
												$saveArray['Package']['picture']	=0;
												$saveArray['Package']['video']		=0;
												$saveArray['Package']['vip']		=0;
												$saveArray['Package']['deal']		=0;
												$saveArray['Package']['discount']	=0;
												$saveArray['Package']['is_contest']	=0;
												$saveArray['Package']['emarketing']	=0;
												$saveArray['Package']['home_page']	=0;
												$saveArray['Package']['package_detail'] = strip_tags($this->data['packages']['package_detail']);
											} else {
												$saveArray['Package']['package_detail'] = $this->data['packages']['package_detail'];
											}
										  $saveArray['Package']['setup_price']  	= $this->data['packages']['setup_price'];
										  $saveArray['Package']['monthly_price']    = $this->data['packages']['monthly_price'];
									      $this->Package->save($saveArray);
									      $this->Session->setFlash('Your data has been submitted successfully.');
									      $this->redirect(array('action' => "index"));
						             } else {
									      /*setting error message if validation fails*/
									      $errors = $this->Package->invalidFields();
									      $this->Session->setFlash(implode('<br>', $errors));
									      //$this->redirect(array('action' => "userGroup", 'message'=>'error'));
						             }
				            }
	              }
				  $this->set('type',$type);
	   }
	// show data in edit package form
	   function packageEditDetail($id=null){
	         $this->set('Package',$this->Package->packageEditDetail($id));
	    }
	 
	 //edit package data
	   function packageEdit($id=null){
	  
	         $this->Package->set($this->data['packages']);	

			 if ($this->Package->validates()) {

							//making data array so we can pass in save mathod
							$saveArray = array();
							$saveArray['Package']['name'] 			= $this->data['packages']['name'];
							$saveArray['Package']['status'] 		= $this->data['packages']['status'];							
							$type = '';
							  if($this->data['packages']['front_page']==1) {
								$type .= ',front';
							  }
							  if($this->data['packages']['sales_page']==1) {
								$type .= ',sales_person';
							  }
							  $saveArray['Package']['type'] = $type;
							if($this->data['packages']['type']=='special') {
								$saveArray['Package']['banner']		=0;
								$saveArray['Package']['merchant']	=0;
								$saveArray['Package']['promotional']=0;
								$saveArray['Package']['website']	=0;
								$saveArray['Package']['map']		=0;
								$saveArray['Package']['picture']	=0;
								$saveArray['Package']['video']		=0;
								$saveArray['Package']['vip']		=0;
								$saveArray['Package']['deal']		=0;
								$saveArray['Package']['discount']	=0;
								$saveArray['Package']['is_contest']	=0;
								$saveArray['Package']['emarketing']	=0;
								$saveArray['Package']['home_page']	=0;
								$saveArray['Package']['package_detail'] = strip_tags($this->data['packages']['package_detail']);
							} else {
								$saveArray['Package']['package_detail'] = $this->data['packages']['package_detail'];
							}
						  	$saveArray['Package']['setup_price']  	= $this->data['packages']['setup_price'];
						  	$saveArray['Package']['monthly_price']  = $this->data['packages']['monthly_price'];					
							$this->Package->save($saveArray);
							$this->Session->setFlash('Package has been updated successfully.');  
							$this->redirect(array('action' => "index"));

			  } else{  

							/*setting error message if validation fails*/
							$errors = $this->Package->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "packageEditDetail/".$this->data['packages']['id'])); 
							
			  }
	    }


	 //edit package data
	   function specialEdit($id=null){
	  		
			if(isset($this->data)) {
	         $this->Package->set($this->data['packages']);	

			 if ($this->Package->validates()) {

							//making data array so we can pass in save mathod
							$saveArray = array();
							$saveArray['Package']['name'] 			= $this->data['packages']['name'];
							$saveArray['Package']['status'] 		= $this->data['packages']['status'];
							$saveArray['Package']['type'] = 'special';
								$saveArray['Package']['banner']		=0;
								$saveArray['Package']['merchant']	=0;
								$saveArray['Package']['promotional']=0;
								$saveArray['Package']['website']	=0;
								$saveArray['Package']['map']		=0;
								$saveArray['Package']['picture']	=0;
								$saveArray['Package']['video']		=0;
								$saveArray['Package']['vip']		=0;
								$saveArray['Package']['deal']		=0;
								$saveArray['Package']['discount']	=0;
								$saveArray['Package']['is_contest']	=0;
								$saveArray['Package']['emarketing']	=0;
								$saveArray['Package']['package_detail'] = strip_tags($this->data['packages']['package_detail']);
						  	$saveArray['Package']['setup_price']  	= $this->data['packages']['setup_price'];
						  	$saveArray['Package']['monthly_price']  = $this->data['packages']['monthly_price'];					
							$this->Package->save($saveArray);
							$this->Session->setFlash('Package has been updated successfully.');  
							$this->redirect(array('action' => "specialpkg"));

			  } else{  

							/*setting error message if validation fails*/
							$errors = $this->Package->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "specialEdit/".$this->data['packages']['id']));
							
			  }
			  
			  } else {
			  	$this->set('Package',$this->Package->packageEditDetail($id));
			  }
	    }
	
	 //delete package data in database
	   function packageDelete($id) {
	   
			 $result = $this->Package->query("SELECT * FROM advertiser_orders where package_id = $id");

			 if(!empty($result)){
			  
			      $this->Session->setFlash('This package contain Package Order.You have to delete first advertise order of this packages.');
				  
				  $this->redirect($this->referer().'/index/msg:error');

			  } else {
			  
			     $this->Package->delete($id);
				 
			     $this->Session->setFlash('The Package with id: '.$id.' has been deleted.');
			
			     $this->redirect($this->referer());
				 
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
	 
	/* This function is setting all info about current SuperAdmins in 
	currentAdmin array so we can use it anywhere to get name ,id etc.
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
