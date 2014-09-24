<?php 
/*
   Programmer : Manoj Pandit (One of the Dadicated soldier of Ishop Army)
   Date  	  : 19 March 2012
*/
class SchoolsController extends AppController {
	  var $name = 'Schools';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('Auth','common','Cookie','Session');  //component to check authentication . this component file is exists in app/controllers/components
	  
//---------------------------------destroy all current sessions for a perticular SuperAdmins and redirect to login page automatically------------------------------//
	    function logout() {
   		         $this->redirect($this->Auth->logout());
        }

//---------------------------------destroy all current sessions for a perticular SuperAdmins and redirect to login page automatically------------------------------//
	function discount_school($id=NULL) {
			$this->loadModel('ReferredSchool');
			$this->layout = 'admin';
			$cond['ReferredSchool.school_id'] = $id;		
			$this->set('title_for_layout','Daily Discounts');
			$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('ReferredSchool.created' => 'desc'));
			$data = $this->paginate('ReferredSchool',$cond);
			$this->set('data', $data);
	}
//---------------------------------destroy all current sessions for a perticular SuperAdmins and redirect to login page automatically------------------------------//
	function discount_details($id=NULL) {
	
			$this->loadModel('ReferredSchool');
			$this->layout = 'admin';
			$this->ReferredSchool->id = $id;
			$data = $this->ReferredSchool->read();
			$this->set('title_for_layout','Daily Discounts details');
			$this->set('data', $data);
	}					
//-------------------------------------------------------index page of school for listing---------------------------------------------------------------------------//
	   function index(){  
	            $condition='';
				
				$cond = array();
				
				$this->set('States', $this->common->getAllState());
			 
			    $this->set('Countys', $this->common->getAllCounty());
				
				$this->set('Citys', $this->common->getAllCity());
				
				$this->set('stateSearch', 'State'); 
				
				$this->set('countySearch', 'County');
				
				$this->set('citySearch', 'City');
				
				$this->set('search_text', 'School Name');
				
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'School.schoolname' => 'asc' ));

				if($this->data['schools']['citySearch']) {
			        $cond['School.city_id'] =  $this->data['schools']['citySearch'] ;
			      (empty($this->params['named'])) ? $this->set('citySearch', $this->data['schools']['citySearch']) :$this->set('citySearch', $this->data['named']['citySearch']) ; 
			    }
				if($this->data['schools']['countySearch']) {
			        $cond['School.county_id'] =  $this->data['schools']['countySearch'] ;
			      (empty($this->params['named'])) ? $this->set('countySearch', $this->data['schools']['countySearch']) :$this->set('countySearch', $this->data['named']['countySearch']) ; 
			    }
				if($this->data['schools']['stateSearch']) {
			        $cond['School.state_id'] =  $this->data['schools']['stateSearch'] ;
			      (empty($this->params['named'])) ? $this->set('stateSearch', $this->data['schools']['stateSearch']) :$this->set('stateSearch', $this->data['named']['stateSearch']) ; 
			    }
			//--------------if school name is set-----------------//
		if(isset($this->data['schools']['search_text']) and ($this->data['schools']['search_text'] != '' and $this->data['schools']['search_text'] != 'School Name')){
		
			if((isset($this->data['schools']['search_text']) and ($this->data['schools']['search_text'] != '' and $this->data['schools']['search_text'] != 'School Name')))
			{
			 $search_text = $this->data['schools']['search_text'] ;
			  $cond['School.schoolname LIKE'] = '%'.$this->data['schools']['search_text'].'%';
			}
			
			$this->set('search_text',$search_text); 
		}
			//---------------------------------------------//	
				if(!empty($this->params['named'])){
				     if(isset($this->params['named']['citySearch'] )){
					   $cond['School.city_id'] =  $this->params['named']['citySearch'] ;
					   $this->set('citySearch', $this->params['named']['citySearch']);
					 }
					 if(isset($this->params['named']['countySearch'] )){
					   $cond['School.county_id'] =  $this->params['named']['countySearch'] ;
					   $this->set('countySearch', $this->params['named']['countySearch']);
					 }
					  if(isset($this->params['named']['stateSearch'] )){
					   $cond['School.state_id'] =  $this->params['named']['stateSearch'] ;
					   $this->set('stateSearch', $this->params['named']['stateSearch']);
					 }
					 if(isset($this->params['named']['search_text'] )){
					   $cond['School.schoolname LIKE'] = '%'.$this->params['named']['search_text'].'%';
					   $this->set('search_text', $this->params['named']['search_text']);
					 }
				}
				
				 //If condition array is greater then 1 then combine by AND tag
			   if(is_array($condition) && count($condition) > 1) {
			 	   $condition['AND'] = $cond;
			   } else {
			       $condition  = $cond;
			    }

			  $data = $this->paginate('School', $condition);
		      $this->set('schools', $data); 
	   }
//-------------------------------------------------------adding new ,school in database---------------------------------------------------------------------------//
	  
	    function addNewSchool(){
				
				   $this->set('States', $this->common->getAllState());
			 
			       $this->set('Countys', $this->common->getAllCounty());
				
				   $this->set('Citys', $this->common->getAllCity());
					
					
	              if(isset($this->data)){
			  
	    	               $this->School->set($this->data['schools']);
				   
			               if (empty($this->data)){
                          		   $this->data = $this->School->find(array('School.id' => $id));
                             }
							 
			               if($this->data['schools']!=''){

					                 if ($this->School->validates()) {
									      //making data array so we can pass in save mathod
									      $saveArray = array();
									      $saveArray['School']    =  $this->data['schools'];
										  $saveArray['School']['page_url'] 	=  $this->common->makeAlias(trim($this->data['schools']['schoolname']));
										  $this->School->save($saveArray);
									      $this->Session->setFlash('School Information has been submitted successfully.');  
									      $this->redirect(array('action' => "index"));
						             }else{  

									      /*setting error message if validation fails*/
									      $errors = $this->School->invalidFields();
									      $this->Session->setFlash(implode('<br>', $errors));  
						             }
				            }
	              }
        
	   }
//-------------------------------------------------------show data in edit school form---------------------------------------------------------------------------//

	   function schoolEditDetail($id=null){
	         $this->set('States', $this->common->getAllState());
			 $this->set('Countys', $this->common->getAllCounty());
			 $this->set('Citys', $this->common->getAllCity());
	         $this->set('School',$this->School->schoolEditDetail($id));
	    }
			
//--------------------------------------------------------------------edit school data---------------------------------------------------------------------------//
	   function schoolEdit($id=null){
	  
	         $this->School->set($this->data['schools']);	

			 if ($this->School->validates()) {


							  $saveArray = array();
							  $saveArray['School']    =  $this->data['schools'];
							  //$saveArray['School']['page_url'] 	=  $this->common->makeAlias(trim($this->data['schools']['schoolname']));
							  $this->School->save($saveArray);
							  $this->Session->setFlash('School Information has been updated successfully.');  
							  $this->redirect(array('action' => "index"));

			  } else{  

							/*setting error message if validation fails*/
							$errors = $this->School->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "schoolEditDetail/".$this->data['schools']['id'])); 
							
			  }
	    }
		
	
//--------------------------------------------------------------delete school data in database-----------------------------------------------------------------------//

	   function schoolDelete($id) {
								
			     $this->School->delete($id);
				 
			     $this->Session->setFlash('The School detail with id: '.$id.' has been deleted.');
			
			     $this->redirect(array('action'=>'index'));
	   }

//----------------------------------------------------------------it is used to autocomplete the search box----------------------------------------------------------//

	function autocomplete($string='') {

			$this->autoRender = false;
			
	
			if($string!=''){
			$arr = '';

			$name = $this->School->query("SELECT School.schoolname FROM schools AS School WHERE School.schoolname LIKE '%$string%'");

			
			foreach($name as $name) {
				$arr[] = $name['School']['schoolname'];
			}
			echo json_encode($arr);
			}
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