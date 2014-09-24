<?php 
/*
   Programmer : Manoj Pandit (One of the Dadicated soldier of Ishop Army)
   Date  	  : 18 Oct 2012
*/
class DepartmentsController extends AppController {
	  var $name = 'Departments';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('Auth','common','Cookie','Session');  //component to check authentication . this component file is exists in app/controllers/components
	  
				
//----------------------------------------------------index page of department for listing---------------------------------------------------------------------------//
	   function index(){  
	            $condition='';
				
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'Department.name' => 'asc' ));

			  $data = $this->paginate('Department', $condition);
		      $this->set('departments', $data); 
	   }
//-------------------------------------------------------adding new ,department in database---------------------------------------------------------------------------//
	  
	    function addNewDepartment(){
					
	              if(isset($this->data)){
			  
	    	               $this->Department->set($this->data['departments']);
				   
			               if (empty($this->data)){
                          		   $this->data = $this->Department->find(array('Department.id' => $id));
                             }
							 
			               if($this->data['departments']!=''){

					                 if ($this->Department->validates()) {
									      //making data array so we can pass in save mathod
										  $this->Department->save($this->data);
									      $this->Session->setFlash('Department Information has been submitted successfully.');  
									      $this->redirect(array('action' => "index"));
						             }else{  

									      /*setting error message if validation fails*/
									      $errors = $this->Department->invalidFields();
									      $this->Session->setFlash(implode('<br>', $errors));  
						             }
				            }
	              }
        
	   }
			
//--------------------------------------------------------------------edit department data---------------------------------------------------------------------------//
	   function departmentEditDetail($id=null){
	  
	         
			 if(isset($this->data))
			 {
				 
				 $this->Department->set($this->data['departments']);	
	
				 if ($this->Department->validates()) {
								  $this->Department->save($this->data);
								  $this->Session->setFlash('Department Information has been updated successfully.');  
								  $this->redirect(array('action' => "index"));
	
				  } else{  
	
								/*setting error message if validation fails*/
								$errors = $this->Department->invalidFields();	
								$this->Session->setFlash(implode('<br>', $errors));
								$this->set('id',$id);
								$this->redirect(array('action' => "departmentEditDetail/".$this->data['departments']['id'])); 
								
				  }
			  }
			 
			 $this->set('id',$id);
			 $this->set('departments',$this->Department->read(null,$id));
			
	    }
		
	
//----------------------------------------------------------delete Department data in database-----------------------------------------------------------------------//

	   function departmentDelete($id) {
								
			     $this->Department->delete($id);
				 
			     $this->Session->setFlash('The Department detail has been deleted.');
			
			     $this->redirect(array('action'=>'index'));
	   }
	

//----------------------------------------------------------------Set css for different color options-----------------------------------------------------------------// 		  
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
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------//
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