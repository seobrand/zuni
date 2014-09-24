<?php 
/*
   Programmer : Manoj Pandit (One of the Dadicated soldier of Ishop Army)
   Date  	  : 22 Oct 2012
*/
class JobsController extends AppController {
	  var $name = 'Jobs';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('Auth','common','Cookie','RequestHandler','Session');  
	   //component to check authentication . this component file is exists in app/controllers/components
	  
				
//----------------------------------------------------index page of jobs for listing---------------------------------------------------------------------------//
	   function index(){  
	            $condition='';
			 	 $this->set('CountyList',$this->common->getAllCounty());			
			   

			 	 $this->set('countySearch', 'County');
				 $this->set('title', 'Title');
				 $this->set('publish', 'Publish');
				 //$this->set('publish_date', 'Publish Date');

				$cond = array();
				
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'Job.modified' => 'DESC' ));
					
				if((!empty($this->data['jobs']['countySearch']) && $this->data['jobs']['countySearch']!='County')){
			       $cond['Job.county'] =  $this->data['jobs']['countySearch'];
			      (empty($this->params['named'])) ? $this->set('countySearch', $this->data['jobs']['countySearch']) :$this->set('countySearch', $this->data['named']['countySearch']) ; 
				 }
				 
				if((!empty($this->data['jobs']['title']) && $this->data['jobs']['title']!='Title')){
			       $cond[] =  'Job.title LIKE "%'.$this->data['jobs']['title'].'%"';
			      (empty($this->params['named'])) ? $this->set('title', $this->data['jobs']['title']) :$this->set('title', $this->data['named']['title']) ; 
				 }
				 
				if((!empty($this->data['jobs']['publish']) && $this->data['jobs']['publish']!='Publish')){
			       $cond['Job.status'] =  $this->data['jobs']['publish'];
			      (empty($this->params['named'])) ? $this->set('publish', $this->data['jobs']['publish']) :$this->set('publish', $this->data['named']['publish']) ; 
				 }
				 
				/* if((!empty($this->data['jobs']['publish_date']) && $this->data['jobs']['publish_date']!='Publish Date')){
				 	
					$sdArr=explode('/',$this->data['jobs']['publish_date']);
					$sd_tStamp=mktime(0,0,0,$sdArr[0],$sdArr[1],$sdArr[2]);
					
			       $cond['Job.start_date'] =  $sd_tStamp;
			      (empty($this->params['named'])) ? $this->set('publish_date', $this->data['jobs']['publish_date']) :$this->set('publish_date', $this->data['named']['publish_date']) ; 
				 }*/
				 

				if(!empty($this->params['named'])){
				     
				     if(isset($this->params['named']['countySearch'] )){
					   $cond['Job.county'] = $this->params['named']['countySearch'] ;
					   $this->set('countySearch', $this->params['named']['countySearch']);
					 }
					 
					if(isset($this->params['named']['title'] )){
					    $cond[] =  'Job.title LIKE "%'.$this->params['named']['title'].'%"';
					   $this->set('title', $this->params['named']['title']);
					 }

					if(isset($this->params['named']['publish'] )){
					    $cond['Job.status'] =  $this->params['named']['publish'];
					   $this->set('publish', $this->params['named']['publish']);
					 }
					 
					 /*if(isset($this->params['named']['publish_date'] )){
					 	//$sdpArr=explode('/',$this->params['named']['publish_date']);
						//pr($this->params);
						//$sdp_tStamp=mktime(0,0,0,$sdpArr[0],$sdpArr[1],$sdpArr[2]);
					    //$cond['Job.start_date'] =  $sdp_tStamp;
					   $this->set('publish_date', $this->params['named']['publish_date']);
					 }*/					 
				     
				}
				
				 //If condition array is greater then 1 then combine by AND tag
			   if(is_array($condition) && count($condition) > 1) {
			 	   $condition['AND'] = $cond;
			   } else {
			       $condition  = $cond;
			    }


			  $data = $this->paginate('Job', $condition);
		      $this->set('jobs', $data); 
	   }
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function countyforProfile() {
		$this->layout = false;
			if(isset($this->data['jobs']['state']) && $this->data['jobs']['state'] !=''){
					$state=$this->data['jobs']['state'];
					$selCounty = $this->common->getAllCountyByState($state);
					
			} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
					$state	=	$this->params['pass'][0];
					$selCounty = $this->common->getAllCountyByState($state);
			} else {
					$selCounty = '';
			}
			$county = '';
			if(isset($this->params['pass'][1]) && $this->params['pass'][1]!='') {
					$county	=	$this->params['pass'][1];
			}			
			$this->set('selCounty',$selCounty);
			$this->set('county',$county);	
		
	}
//-------------------------------------------------------adding new ,department in database---------------------------------------------------------------------------//
	  
	    function addNewJob(){
				$this->set('stateList',$this->common->getAllState());
	              if(isset($this->data)){
			  
	    	               $this->Job->set($this->data['jobs']);
				   
			               if (empty($this->data)){
                          		   $this->data = $this->Job->find(array('Job.id' => $id));
                             }
							 
			               if($this->data['jobs']!=''){

					              
									 if ($this->Job->validates()) {
									      //making data array so we can pass in save mathod
										$sdArr=explode('/',$this->data['jobs']['start_date']);
										$sd_tStamp=mktime(0,0,0,$sdArr[0],$sdArr[1],$sdArr[2]);
										$edArr=explode('/',$this->data['jobs']['end_date']);
										$ed_tStamp=mktime(0,0,0,$edArr[0],$edArr[1],$edArr[2]);
										$this->data['jobs']['start_date']=$sd_tStamp;		
										$this->data['jobs']['end_date']=$ed_tStamp;
										  $this->Job->save($this->data['jobs'],false);
									      $this->Session->setFlash('Job Information has been saved successfully.');  
									      $this->redirect(array("controller"=>"jobs","action" => "index"));
						             }else{  

									      /*setting error message if validation fails*/
									      $errors = $this->Job->invalidFields();
									      $this->Session->setFlash(implode('<br>', $errors));  
						             }
				            }
	              }
        
	   }
			
//--------------------------------------------------------------------edit department data---------------------------------------------------------------------------//
	   function jobEditDetail($id=null){
	  
	         $this->set('stateList',$this->common->getAllState());
			 if(isset($this->data))
			 {
				 
				 $this->Job->set($this->data['jobs']);	
				 if ($this->Job->validates()) {
				 				
								//pr($this->data);exit;
								$sdArr=explode('/',$this->data['jobs']['start_date']);
								$sd_tStamp=mktime(0,0,0,$sdArr[0],$sdArr[1],$sdArr[2]);
								$edArr=explode('/',$this->data['jobs']['end_date']);
								$ed_tStamp=mktime(0,0,0,$edArr[0],$edArr[1],$edArr[2]);
								$this->data['jobs']['start_date']=$sd_tStamp;		
								$this->data['jobs']['end_date']=$ed_tStamp;
												
								  $this->Job->save($this->data['jobs'],false);
								  $this->Session->setFlash('Job Information has been updated successfully.');  
								  $this->redirect(array('action' => "index"));
	
				  } else{  
	
								/*setting error message if validation fails*/
								$errors = $this->Job->invalidFields();	
								$this->Session->setFlash(implode('<br>', $errors));
								$this->set('id',$id);
								return false;
								//$this->redirect(array('action' => "jobEditDetail/".$this->data['jobs']['id'])); 
								
				  }
			  }
			 
			 $this->set('id',$id);
			$mdata=$this->Job->read(null,$id);
			$mdata['Job']['start_date']=date(DATE_FORMAT,$mdata['Job']['start_date']);
			$mdata['Job']['end_date']=date(DATE_FORMAT,$mdata['Job']['end_date']);			
			$this->data['jobs']=$mdata['Job'];
			 //$this->set('jobs',$this->Job->read(null,$id));
			
	    }
		
	
//----------------------------------------------------------delete Department data in database-----------------------------------------------------------------------//

	   function jobDelete($id) {
								
			     $this->Job->delete($id);
				 
			     $this->Session->setFlash('The Job detail has been deleted.');
			
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