<?php 
/*
   Coder: Surbhit
   Date  : 07 Aug 2011
*/ 
class StatesController extends AppController { 
	  var $name = 'States';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('Auth','common','Cookie','Session');  //component to check authentication . this component file is exists in app/controllers/components
	  
      /*    destroy all current sessions for a perticular SuperAdmins
	        and redirect to login page automatically
	 */
	    function logout() {
   		         $this->redirect($this->Auth->logout());
        }
     // index page of state for listing
	   function index(){  
             //variable for display number of state name per page	
	            $condition='';
				if(isset($this->params['named']['message']))
		           {
			          if($this->params['named']['message']=='success')
			          {
				        $this->set('success','success');
			          }else{
			            $this->set('error','error');
			          }
		          }
				  
				$this->set('search_text', 'state name'); 

			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'State.statename' => 'asc' ));
			    if((!empty($this->data['states']['search_text'])) &&($this->data['states']['search_text']!="state name"))  {
                           $this->set('search_text', $this->data['states']['search_text']); 
				           $condition =   array('State.statename LIKE' => '%' . $this->data['states']['search_text'] . '%');
		        }
	           if((isset($this->params['named']['search_text'])) &&($this->params['named']['search_text']!="state name")){
			   
                          $this->set('search_text', $this->params['named']['search_text']);
				          $condition =   array('State.statename LIKE' => '%' . $this->params['named']['search_text'] . '%');   
						  
		        } 
			  $data = $this->paginate('State', $condition);
		      $this->set('states', $data); 
	   }

/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocompleteState($string='') {

			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			$name = $this->State->query("SELECT State.statename FROM states AS State WHERE State.statename LIKE '$string%'");
			foreach($name as $name) {
				$arr[] = $name['State']['statename'];
			}
			echo json_encode($arr);
			}
	}	

/*------------------------------------------------------------------------------------------------------------------------*/ 	
	   
	  // adding new state in database
	  
	    function addNewState(){
	              if(isset($this->data)){			  
	    	               $this->State->set($this->data['states']);				   
			               if (empty($this->data)){
                          		   $this->data = $this->State->find(array('State.id' => $id));
                           }							 
			               if($this->data['states']!=''){
					                 if ($this->State->validates()) {
									      //making data array so we can pass in save mathod
									      $saveArray = array();
									      $saveArray['State']['statename'] = $this->data['states']['statename'];
									      $saveArray['State']['status']    = $this->data['states']['status'];
										  $saveArray['State']['page_url'] = $this->common->makeAlias(trim($this->data['states']['statename']));
									      $this->State->save($saveArray);
									      $this->Session->setFlash('Your data has been submitted successfully.');  
									      $this->redirect(array('action' => "index" , 'message'=>'success'));
						             }else{
									      /*setting error message if validation fails*/
									      $errors = $this->State->invalidFields();
									      $this->Session->setFlash(implode('<br>', $errors));  
									       //$this->redirect(array('action' => "userGroup", 'message'=>'error'));
						             }
				            }
	              }        
	   }
	// show data in edit state form
	   function stateEditDetail($id=null){
	         $this->set('State',$this->State->stateEditDetail($id));
	   }
	 //edit state data
	   function stateEdit($id=null){
	  
	         $this->State->set($this->data['states']);	

			 if ($this->State->validates()) {

							//making data array so we can pass in save mathod
							$saveArray = array();
							$saveArray['State']['statename'] = $this->data['states']['statename'];
							$saveArray['State']['status'] = $this->data['states']['status'];
							$saveArray['State']['page_url'] = $this->common->makeAlias(trim($this->data['states']['statename']));
							
							$this->State->save($saveArray);
							$this->Session->setFlash('Your data has been updated successfully.');  
							$this->redirect(array('action' => "index" , 'message'=>'success'));

			  } else{  

							/*setting error message if validation fails*/
							$errors = $this->State->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "stateEditDetail/".$this->data['states']['id'])); 
							
			  }
	    }
	
	
	 //delete state data in database
	   function stateDelete($id) {
	    
			  $result                  = $this->State->query("SELECT * FROM counties where state_id = $id");
			  
			  $resultCity              = $this->State->query("SELECT * FROM cities where state_id = $id");
			  
			  $resultLink   		   = $this->State->query("SELECT * FROM links where state_id = $id");
			  
			  $resultAdvertiserProfile = $this->State->query("SELECT * FROM advertiser_profiles where state = $id");
			  
			  $resultBanner            = $this->State->query("SELECT * FROM banners where state_id = $id");

			  if((!empty($result))||(!empty($resultLink))||(!empty($resultAdvertiserProfile))||(!empty($resultBanner))||(!empty($resultCity))){
			  
			      if(!empty($result))
			      $delete['result'] = 'This state contain County.You have to delete first counties of this state.';
				  
				  if(!empty($resultLink))
			      $delete['resultLink'] = 'This state contain Link Manager.You have to delete first Link Manager of this state.';
				  
				  if(!empty($resultAdvertiserProfile))
			      $delete['resultAdvertiserProfile'] = 'This state contain Advertiser Profile.You have to delete first Advertiser Profile of this state.';
				  				  
				  if(!empty($resultBanner))
			      $delete['resultBanner'] = 'This state contain Bannner.You have to delete first Banner Detail of this state.';
				  
				  if(!empty($resultCity))
			      $delete['resultCity'] = 'This state contain City.You have to delete first City  of this state.';
				  
				  $this->Session->setFlash(implode('<br>', $delete));
				  
				  $this->redirect(array('action' => "index" , 'message'=>'error'));
				  
				   
			  } else {
			
			     $this->State->delete($id);
				 
			     $this->Session->setFlash('The State with id: '.$id.' has been deleted.');
			
			     $this->redirect(array('action'=>'index' , 'message'=>'success'));
			  
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
			$this->Auth->allow('getcountyList','getcountyListContact','getcountyListCareer','getcountyListMobile','getcountyListContactMobile');
			
   	    }
	
	 /*------------------------------function to Listing Searching county------------------------------------*/
	 
     function county() {  
			 $condition='';
			  
			 $this->set('States', $this->common->getAllState());
			 
			 if(isset($this->params['named']['message']))
		           {
			          if($this->params['named']['message']=='success')
			          {
				        $this->set('success','success');
			          }else{
			            $this->set('error','error');
			          }
		          }
			 
			 App::import('model','County'); // importing Article (pages) model
			  
		     $this->County = new County();
			 #declare variables in cakephp.$this->set is used to declare variables in cakephp 
			 
			 $this->set('title', 'county name'); 
			 $this->set('published', '');  
			 $this->set('stateSearch', 'State');
			 $this->paginate = array(
				'limit' => PER_PAGE_RECORD,
				'order' => array('County.countyname' => 'asc')
			  );
			 #setting diff condition in paginate function according to search criteria
			 
			if((!empty($this->data['states']['title']) && $this->data['states']['title']!='county name')&& $this->data['states']['stateSearch']==""){
				 $this->set('title', $this->data['states']['title']); 
				 $condition =   array('County.countyname LIKE' => '%' . $this->data['states']['title'] . '%');
						  
			 }
			 
			if(($this->data['states']['title'] == "" || $this->data['states']['title']=='county name') && $this->data['states']['stateSearch'] != ""){
				 $this->set('stateSearch', $this->data['states']['stateSearch']); 
				 $condition =   array('County.state_id'  => $this->data['states']['stateSearch'] );   
						  
			 }
			 
			if((!empty($this->data['states']['title'] ) && $this->data['states']['title']!='county name') && $this->data['states']['stateSearch'] != "")
			 {
				 $this->set('title', $this->data['states']['title']); 
				 $this->set('stateSearch', $this->data['states']['stateSearch']); 
				 $condition = 	array (	'AND' => array ('County.countyname LIKE' => '%' . $this->data['states']['title'] . '%', 'County.state_id' =>$this->data['states']['stateSearch'] ));  
						  
			 } 
			
			 //----------------------------------At the time of sorting Filteration on basis of these fields------------------------------
			if(!empty($this->params['named'])){
			 
					 if((isset($this->params['named']['title'] ) && $this->params['named']['title']!='county name') && !isset($this->params['named']['stateSearch'])){
					 
						 $this->set('title', $this->params['named']['title']); 
						 $condition =   array('County.countyname LIKE' => '%' . $this->params['named']['title'] . '%');   
								  
					 }
					 
					 if((!isset($this->params['named']['title'])|| $this->params['named']['title']=='county name') && isset($this->params['named']['stateSearch'])){
					 
						 $this->set('stateSearch', $this->params['named']['stateSearch']); 
						 $condition =   array('County.state_id ' => $this->params['named']['stateSearch']);   
								  
					 } 
					 
					 if((isset($this->params['named']['title'] ) && $this->params['named']['title']!='county name') && isset($this->params['named']['stateSearch'])){
					 
						 $this->set('title', $this->params['named']['title']); 
						 $this->set('stateSearch', $this->params['named']['stateSearch']);
						 $condition = 	array (	'AND' => array ('County.countyname LIKE' => '%' . $this->params['named']['title'] . '%', 'County.state_id' =>$this->params['named']['stateSearch'] ));    
								  
					 }  
			 }
			 
			 $data = $this->paginate('County', $condition);
		     $this->set('countys', $data); 
	
	}
	
	
	
	/*------------------------------function to Add New County------------------------------------*/
	
	function addNewCounty(){
	           
	         $this->set('States', $this->common->getAllState());
			 
			 App::import('model','County'); // importing Article model
			 $this->County = new County();
			 
			 App::import('model','HeaderLogo'); 
			 $this->h_logo = new HeaderLogo(); 
			 
			 App::import('model','CategoryLimit'); 
			 $this->CategoryLimit = new CategoryLimit(); 
			 
			 $this->set('categoryList',$this->common->getAllCategoryDetail());
			
			if($this->data){
			
				 $this->County->set($this->data['states']);
				
				 if($this->data['states']!=''){
					 if ($this->County->validates()) {					 
									//making data array so we can pass in save mathod
									$saveArray = array();
									$saveArray['County']['logo'] = '';
									
								   if($this->data['states']['meta_description']=='')
									{
									$saveArray['County']['meta_description']=strip_tags($this->data['states']['description']);
									} else{
										$saveArray['County']['meta_description']=strip_tags($this->data['states']['meta_description']);
									}
									
									
									if($this->data['states']['meta_keyword']=='')
									{
									$saveArray['County']['meta_keyword']=$this->data['states']['countyname'];
									}else {
										$saveArray['County']['meta_keyword']=$this->data['states']['meta_keyword'];
									}
									
									
									if($this->data['states']['meta_title']=='')
									{
									$saveArray['County']['meta_title']=$this->data['states']['countyname'];
									} else {
										$saveArray['County']['meta_title']=$this->data['states']['meta_title'];
									}
									
									//--------set default value of max. # no advertiser, if its not set/entered by user--------//
									
									if($this->data['states']['advertiser_limit_home']!='')
									{
										$saveArray['County']['advertiser_limit_home'] = $this->data['states']['advertiser_limit_home'];
									}
									else
									{
										$saveArray['County']['advertiser_limit_home'] = 1;
									}
									
									//---------------------------------------------------------------------------------------//
									
									$saveArray['County']['countyname'] 	= $this->data['states']['countyname'];
									$saveArray['County']['facebook_url']= $this->data['states']['facebook_url'];
									$saveArray['County']['twitter_url'] = $this->data['states']['twitter_url'];
									$saveArray['County']['pine_url'] = $this->data['states']['pine_url'];
									$saveArray['County']['advertiser_days'] = $this->data['states']['advertiser_days'];
									$saveArray['County']['publish']    	= $this->data['states']['publish'];
									$saveArray['County']['state_id']   	= $this->data['states']['state_id'];
									$saveArray['County']['description'] = $this->data['states']['description'];
									$saveArray['County']['split'] 		= $this->data['states']['split'];
									$saveArray['County']['page_url'] 	=  $this->common->makeAlias(trim($this->data['states']['countyname']));						  										
									//////// Upload images
					$imgarr = '';
					$imgarr[0] = '';	
					for($k=1;$k<=12;$k++)
							{	
								if($this->data['County']['logo'][$k]['name']!="")
								  {
									$type = explode(".",$this->data['County']['logo'][$k]['name']);
									if(strtolower($type[1]) =="png" || strtolower($type[1]) =="jpeg" || strtolower($type[1]) =="jpg"  || strtolower($type[1]) =="gif")
										{
											$imgarr[$k] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['County']['logo'][$k]['name']);
											$docDestination = APP.'webroot/img/county/'.$imgarr[$k]; 
											@chmod(APP.'webroot/img/county',0777);
											move_uploaded_file($this->data['County']['logo'][$k]['tmp_name'], $docDestination) or die($docDestination);																				
										}
								  else
										{
											$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
										}	
								  }	
								else
								{   
								   $imgarr[$k]= '';
								}
						}									
									//// end upload images  
				/************************************************************************************************************************************/
					if($this->data['County']['header_img']['name']!='')
					{
							$type = explode(".",$this->data['County']['header_img']['name']);							
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{														               
								$this->data['County']['header_img']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['County']['header_img']['name']);
								$docDestination = APP.'webroot/img/header/'.$this->data['County']['header_img']['name'];								
								@chmod(APP.'webroot/img/header',0777);								
								move_uploaded_file($this->data['County']['header_img']['tmp_name'], $docDestination) or die($docDestination);								
								$saveArray['County']['header_image'] = $this->data['County']['header_img']['name'];								
							}
					}
				/************************************************************************************************************************************/						
					$this->County->save($saveArray);
					$county_id = $this->County->id;
					for($i=1;$i<=12;$i++)
					{					
							if(!empty($this->data['states']['sdate'.$i]))
							{
							$s_date=$this->data['states']['sdate'.$i];
							$start_date=explode('/',$s_date);
							$StartDate = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
							}
							else
							 {
							 $StartDate = "";
							 }
						   if(!empty($this->data['states']['edate'.$i]))
							{
							 $e_date=$this->data['states']['edate'.$i];
							 $end_date=explode('/',$e_date);
							 $EndDate = mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
							}
							else
							 {
							 $EndDate = "";
							 }					
		$this->h_logo->query("insert into header_logos(`county_id`,`start_date`,`end_date`,`index`,`logo`) values('$county_id','$StartDate','$EndDate','$i','$imgarr[$i]')");										
																					
				}
				/*******************************************save the max_limit(categoryLimit)********************************************************/
						
				for($rx=0;$rx<$this->data['County']['CategoryLimit']['category_count'];$rx++)
				{
					$saveCategoryLimit='';
					$this->CategoryLimit->id='';
					$saveCategoryLimit['CategoryLimit']['county_id']=$county_id;
					$saveCategoryLimit['CategoryLimit']['category_id']=$this->data['County']['CategoryLimit']['category_id'.$rx];
					if($this->data['County']['CategoryLimit']['max_limit'.$rx]!='')
						$saveCategoryLimit['CategoryLimit']['max_limit']=$this->data['County']['CategoryLimit']['max_limit'.$rx];
					else
						$saveCategoryLimit['CategoryLimit']['max_limit']='7';
					$this->CategoryLimit->save($saveCategoryLimit);
				}
				/************************************************************************************************************************************/												
									$this->Session->setFlash('Your data has been submitted successfully.');  
									$this->redirect(array('action' => "county"));

						}else{  
						
									/*setting error message if validation fails*/
									$errors = $this->County->invalidFields();	
									$this->Session->setFlash(implode('<br>', $errors));  
									
						}
				 }
		    }
	
	}
	
	
	// show data in edit state form
	   function countyEditDetail($id=null){ 
	   	       App::import('model','HeaderLogo');
			   $this->h_logo=new HeaderLogo();
			   //pr($this->h_logo->query("select * from header_logos where county_id='$id'"));die;
                $this->set('County_logo',$this->h_logo->query("select * from header_logos where county_id='$id'"));			    
			     $this->set('States', $this->common->getAllState());
	   		     App::import('model','County'); // importing Article model
		    	 $this->County = new County(); 
	            $this->set('County',$this->County->countyEditDetail($id));
				
			 App::import('model','CategoryLimit'); 
			 $this->CategoryLimit = new CategoryLimit();
			 $this->set('categoryLimitRecord',$this->CategoryLimit->find('all',array('conditions'=>array('CategoryLimit.county_id'=>$id))));
			 
			 $this->set('categoryList',$this->common->getAllCategoryDetail());
		   
	    }
		
		//edit county data
	   function countyEdit($id=null){	  
			
			//pr($this->data);
			 $this->set('States', $this->common->getAllState());
			  
	         App::import('model','County'); // importing Article model
			 $this->County = new County(); 
			 
 			 App::import('model','CategoryLimit'); 
			 $this->CategoryLimit = new CategoryLimit(); 
			 
			 $this->set('categoryList',$this->common->getAllCategoryDetail());
	  
	         $this->County->set($this->data['states']);
			 
              $stateId = $this->data['states']['id'];
			  $saveArray['states']['id']= $stateId;
			  $stateDetailArr = $this->County->find("id = $stateId");   
					//pr($stateDetailArr);
					for($i=1;$i<=12;$i++)
					 {	
 					 App::import('model','HeaderLogo');
			         $this->h_logo=new HeaderLogo();
					 $imageold=$this->h_logo->query("select `logo` from header_logos where `county_id`='$stateId' and `index`='$i'");
					// pr($imageold);die;
					 	if($this->data['County']['logo'][$i]['name']!="")
						  {
						  //echo $this->data['County']['logo'][$i]['name'];die;
				    		$type = explode(".",$this->data['County']['logo'][$i]['name']);
							if(strtolower($type[1]) =="png" || strtolower($type[1]) =="jpeg" || strtolower($type[1]) =="jpg"  || strtolower($type[1]) =="gif")
					    		{
								    if(isset($imageold[0]))
									{
									unlink(WWW_ROOT.'img/county/'.$imageold[0]['header_logos']['logo']);
									}
									$logo = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['County']['logo'][$i]['name']);
									$docDestination = APP.'webroot/img/county/'.$logo; 
									@chmod(APP.'webroot/img/county',0777);
									move_uploaded_file($this->data['County']['logo'][$i]['tmp_name'], $docDestination) or die($docDestination);
																				
								}
						  else
								{
									$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
								}	
						  }	
						else if(isset($imageold[0]))
						{   
						   $logo= $imageold[0]['header_logos']['logo'];
						}
						else
						{
						$logo='';
						}							
							if(!empty($this->data['states']['sdate'.$i]))
							{
								$s_date=$this->data['states']['sdate'.$i];
								$start_date=explode('/',$s_date);
								$StartDate = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
							}
							else
							 {
							 	$StartDate = "";
							 }
						   if(!empty($this->data['states']['edate'.$i]))
							{
								 $e_date=$this->data['states']['edate'.$i];
								 $end_date=explode('/',$e_date);
								 $EndDate = mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
							}
							else
							 {
							 	$EndDate = "";
							 }
							$countthis = $this->h_logo->query("SELECT * FROM header_logos where `county_id`='$stateId' and `index`='$i'");
							if(count($countthis)==1) {
	$this->h_logo->query("update header_logos set `county_id`='$stateId',`logo`='$logo',`start_date`='$StartDate',`end_date`='$EndDate',`index`='$i' where `county_id`='$stateId' and `index`='$i'");
	} else {
		$this->h_logo->query("insert into header_logos(`county_id`,`logo`,`start_date`,`end_date`,`index`) values('$stateId','$logo','$StartDate','$EndDate','$i')");	
	}									
																					
				}
				/*******************************************save the max_limit(categoryLimit)********************************************************/
				if(isset($this->data))
				{		
					for($rx=0;$rx<$this->data['County']['CategoryLimit']['category_count'];$rx++)
					{
						$saveCategoryLimit='';
						if($this->data['County']['CategoryLimit']['catLimId'.$rx]!='')
							$saveCategoryLimit['CategoryLimit']['id']=$this->data['County']['CategoryLimit']['catLimId'.$rx];
						else					
							$saveCategoryLimit['CategoryLimit']['id']='';
						
						$saveCategoryLimit['CategoryLimit']['county_id']= $this->data['states']['id'];
						$saveCategoryLimit['CategoryLimit']['category_id']=$this->data['County']['CategoryLimit']['category_id'.$rx];
						if($this->data['County']['CategoryLimit']['max_limit'.$rx]!='')
							$saveCategoryLimit['CategoryLimit']['max_limit']=$this->data['County']['CategoryLimit']['max_limit'.$rx];
						else
							$saveCategoryLimit['CategoryLimit']['max_limit']='7';
						$this->CategoryLimit->save($saveCategoryLimit);
					}
				}
				/************************************************************************************************************************************/		
			
			 if ($this->County->validates()) {
							//making data array so we can pass in save mathod
							$saveArray = array();
							$saveArray['County']['countyname'] = $this->data['states']['countyname'];
							$saveArray['County']['facebook_url'] = $this->data['states']['facebook_url'];
							$saveArray['County']['twitter_url'] = $this->data['states']['twitter_url'];	
							$saveArray['County']['pine_url'] = $this->data['states']['pine_url'];
							$saveArray['County']['advertiser_days'] = $this->data['states']['advertiser_days'];
							$saveArray['County']['advertiser_limit_home'] = $this->data['states']['advertiser_limit_home'];
							$saveArray['County']['publish'] = $this->data['states']['publish'];
							$saveArray['County']['state_id'] = $this->data['states']['state_id'];
							$saveArray['County']['split'] = $this->data['states']['split'];
							$saveArray['County']['description'] = $this->data['states']['description'];							
							$saveArray['County']['page_url'] =  $this->common->makeAlias(trim($saveArray['County']['countyname']));							
							//$saveArray['Setting']['h_logo'] = $this->data['settings']['h_logo'];							
							if(!empty($this->data['states']['logo']['name']))
							{
											 $imageName  =$this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['states']['logo']['name']);
											 $docDestination = APP.'webroot/img/county/'.$imageName; 
									         @chmod(APP.'webroot/img/county',0777);
									         move_uploaded_file($this->data['states']['logo']['tmp_name'], $docDestination) or die($docDestination);

											 
											 if($stateDetailArr['County']['logo']!="")
											 {
											      unlink(WWW_ROOT.'img/county/'.$stateDetailArr['County']['logo']);
											 }
											
											 $saveArray['County']['logo'] = $imageName;
											
							}
							 else 
							 {
							
							    $saveArray['County']['logo'] = $stateDetailArr['County']['logo'];
							 }
							
							if($this->data['states']['meta_description']=='')
							{
							$saveArray['County']['meta_description']=strip_tags($this->data['states']['description']);
							}
							if($this->data['states']['meta_keyword']=='')
							{
							$saveArray['County']['meta_keyword']=$this->data['states']['countyname'];
							}
							if($this->data['states']['meta_title']=='')
							{
							$saveArray['County']['meta_title']=$this->data['states']['countyname'];
							}
							/************************************************************************************************************************************/
							if($this->data['County']['header_img']['name']!='')
							{
									$type = explode(".",$this->data['County']['header_img']['name']);							
									if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
									{     
										if(isset($this->data['County']['default_header']) && $this->data['County']['default_header']!='') {					
											unlink(APP.'webroot/img/header/'.$this->data['County']['default_header']);    
										}								               
										$this->data['County']['header_img']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['County']['header_img']['name']);
										$docDestination = APP.'webroot/img/header/'.$this->data['County']['header_img']['name'];								
										@chmod(APP.'webroot/img/header',0777);								
										move_uploaded_file($this->data['County']['header_img']['tmp_name'], $docDestination) or die($docDestination);								
										$saveArray['County']['header_image'] = $this->data['County']['header_img']['name'];								
									}
							}
				/************************************************************************************************************************************/	
							$this->County->save($saveArray);
							$this->Session->setFlash('Your data has been updated successfully.');  
							$this->redirect(array('action' => "county"));
			  } else {
							/*setting error message if validation fails*/
							$errors = $this->County->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "countyEditDetail/".$this->data['states']['id'])); 
							
			  }
	    }		
		//delete state data in database
	   function countyDelete($id) {
	   
	         App::import('model','County'); // importing Article model
			  $this->County = new County(); 
			  
			  
	           $resultCity              = $this->State->query("SELECT * FROM cities where county_id = $id");
			  
			   $resultLink   		    = $this->State->query("SELECT * FROM links where county_id = $id");
			  
			   $resultAdvertiserProfile = $this->State->query("SELECT * FROM advertiser_profiles where county = $id");
			  
			   $resultBanner            = $this->State->query("SELECT * FROM banners where county_id = $id");

			  if((!empty($resultLink))||(!empty($resultAdvertiserProfile))||(!empty($resultBanner))||(!empty($resultCity))){
			  
				  if(!empty($resultLink))
			      $delete['resultLink'] = 'This county contain Link Manager.You have to delete first Link Manager of this county.';
				  
				  if(!empty($resultAdvertiserProfile))
			      $delete['resultAdvertiserProfile'] = 'This county contain Advertiser Profile.You have to delete first Advertiser Profile of this county.';
				  				  
				  if(!empty($resultBanner))
			      $delete['resultBanner'] = 'This county contain Bannner.You have to delete first Banner Detail of this county.';
				  
				  if(!empty($resultCity))
			      $delete['resultCity'] = 'This county contain City.You have to delete first City  of this county.';
				  
				  $this->Session->setFlash(implode('<br>', $delete));
				  
				  $this->redirect(array('action' => "county" , 'message'=>'error'));
				   
			  } else {
			
			     $this->County->delete($id);
			
			     $this->Session->setFlash('The County with id: '.$id.' has been deleted.');
			
			     $this->redirect(array('action'=>'county' , 'message'=>'success'));
			  
			  }
			 
			
	   }
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocompleteCounty($string='') {

			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			 App::import('model', 'County');
			$this->County = new County;
			$name = $this->County->query("SELECT County.countyname FROM counties AS County WHERE County.countyname LIKE '$string%'");
			foreach($name as $name) {
				$arr[] = $name['County']['countyname'];
			}
			echo json_encode($arr);
			}
	}	

/*------------------------------------------------------------------------------------------------------------------------*/ 		   
		/*------------------------------function to Listing Searching city------------------------------------*/
	 
     function city() {  
	 
			 $condition='';
			 
			 $this->set('States', $this->common->getAllState());
			 
			 $this->set('Countys', $this->common->getAllCounty());
			 
			 if(isset($this->params['named']['message']))
		           {
			          if($this->params['named']['message']=='success')
			          {
				        $this->set('success','success');
			          }else{
			            $this->set('error','error');
			          }
		          }
			  
			 
			 App::import('model','City'); // importing Article (pages) model
			  
			   
		     $this->City = new City();
			 
			 $this->set('title', 'city name'); 
			 
			 $this->set('published', '');  
			 
			 $this->set('stateSearch', 'State');
			 
			 $this->set('countySearch', 'County');
			 
			 $this->paginate = array(

				'limit' => PER_PAGE_RECORD,

				'order' => array('City.id' => 'DESC')

			  );
			 #setting diff condition in paginate function according to search criteria
			 
			if((!empty($this->data['states']['title']) && $this->data['states']['title']!='city name')&& ($this->data['states']['stateSearch']=="")&& ($this->data['states']['countySearch']=="")){
			 
				 $this->set('title', $this->data['states']['title']); 
				 $condition =   array('City.cityname LIKE' => '%' . $this->data['states']['title'] . '%');
						  
			 }
			 
			if(($this->data['states']['title'] == "" || $this->data['states']['title']=='city name') && ($this->data['states']['stateSearch'] != "")&&($this->data['states']['countySearch'] == "")){
			 
				 $this->set('stateSearch', $this->data['states']['stateSearch']); 
				 $condition =   array('City.state_id'  => $this->data['states']['stateSearch'] );   
						  
			 }
			 
			 if(($this->data['states']['title'] == "" || $this->data['states']['title']=='city name') && ($this->data['states']['stateSearch'] == "")&&($this->data['states']['countySearch'] != "")){
			 
				 $this->set('countySearch', $this->data['states']['countySearch']); 
				 $condition =   array('City.county_id'  => $this->data['states']['countySearch'] );   
						  
			 }
			 
			 if(($this->data['states']['title'] == "" || $this->data['states']['title']=='city name') && ($this->data['states']['stateSearch'] != "")&&($this->data['states']['countySearch'] != "")){
			 
				$this->set('stateSearch', $this->data['states']['stateSearch']);
				$this->set('countySearch', $this->data['states']['countySearch']); 
				 $condition = 	array (	'AND' => array ('City.state_id' =>$this->data['states']['stateSearch'], 'City.county_id' =>$this->data['states']['countySearch'] ));   
						  
			 }
			 
			if((!empty($this->data['states']['title'] ) && $this->data['states']['title']!='city name') && ($this->data['states']['stateSearch'] != "")&&($this->data['states']['countySearch'] == ""))
			 {
				 $this->set('title', $this->data['states']['title']); 
				 $this->set('stateSearch', $this->data['states']['stateSearch']); 
				 $condition = 	array (	'AND' => array ('City.cityname LIKE' => '%' . $this->data['states']['title'] . '%', 'City.state_id' =>$this->data['states']['stateSearch'] ));  
				}
		    
			if((!empty($this->data['states']['title'] ) && $this->data['states']['title']!='city name') && ($this->data['states']['stateSearch'] == "")&&($this->data['states']['countySearch'] != ""))
			 {
				 $this->set('title', $this->data['states']['title']); 
				 $this->set('countySearch', $this->data['states']['countySearch']); 
				 $condition = 	array (	'AND' => array ('City.cityname LIKE' => '%' . $this->data['states']['title'] . '%', 'City.county_id' =>$this->data['states']['countySearch'] ));
						  
			 } 
			 if((!empty($this->data['states']['title'] ) && $this->data['states']['title']!='city name') && ($this->data['states']['stateSearch'] != "")&&($this->data['states']['countySearch'] != ""))
			 {
				 $this->set('title', $this->data['states']['title']); 
				 $this->set('countySearch', $this->data['states']['countySearch']);
				 $this->set('stateSearch', $this->data['states']['stateSearch']);  
				 $condition = 	array (	'AND' => array ('City.cityname LIKE' => '%' . $this->data['states']['title'] . '%', 'City.county_id' =>$this->data['states']['countySearch'] , 'City.state_id' =>$this->data['states']['stateSearch'] ));
						  
			 }
			
			 //----------------------------------At the time of sorting Filteration on basis of these fields------------------------------
			if(!empty($this->params['named'])){
			 
					 if((isset($this->params['named']['title'] ) && $this->params['named']['title']!='city name') && !isset($this->params['named']['stateSearch'])&& !isset($this->params['named']['countySearch'])){
					 
						 $this->set('title', $this->params['named']['title']); 
						 $condition =   array('City.cityname LIKE' => '%' . $this->params['named']['title'] . '%');   
								  
					 }
					 
					 if((!isset($this->params['named']['title'])|| $this->params['named']['title']=='city name') && isset($this->params['named']['stateSearch'])&& !isset($this->params['named']['countySearch'])){
					    
						 $this->set('stateSearch', $this->params['named']['stateSearch']); 
						 $this->set('countySearch', 'County');
						 $condition =   array('City.state_id ' => $this->params['named']['stateSearch']);    
								  
					 } 
					 
					 if((!isset($this->params['named']['title'])|| $this->params['named']['title']=='city name') && !isset($this->params['named']['stateSearch'])&& isset($this->params['named']['countySearch'])){
					 
						 $this->set('countySearch', $this->params['named']['countySearch']); 
						 $condition =   array('City.county_id ' => $this->params['named']['countySearch']);   
								  
					 }
					 if((!isset($this->params['named']['title'])|| $this->params['named']['title']=='city name') && isset($this->params['named']['stateSearch'])&& isset($this->params['named']['countySearch'])){
					     $this->set('stateSearch', $this->params['named']['stateSearch']); 
						 $this->set('countySearch', $this->params['named']['countySearch']); 
						 $condition =   array('City.county_id ' => $this->params['named']['countySearch'], 'City.state_id' =>$this->params['named']['stateSearch']);   
								  
					 }
					 
					 if((isset($this->params['named']['title'] ) && $this->params['named']['title']!='city name') && isset($this->params['named']['stateSearch'])&& !isset($this->params['named']['countySearch'])){
					 
						 $this->set('title', $this->params['named']['title']); 
						 $this->set('stateSearch', $this->params['named']['stateSearch']);
						 $condition = 	array (	'AND' => array ('City.cityname LIKE' => '%' . $this->params['named']['title'] . '%', 'City.state_id' =>$this->params['named']['stateSearch'] ));    
								  
					 } 
					  
					 if((isset($this->params['named']['title'] ) && $this->params['named']['title']!='city name') && !isset($this->params['named']['stateSearch'])&& isset($this->params['named']['countySearch'])){
					 
						 $this->set('title', $this->params['named']['title']); 
						 $this->set('countySearch', $this->params['named']['countySearch']);
						 $condition = 	array (	'AND' => array ('City.cityname LIKE' => '%' . $this->params['named']['title'] . '%', 'City.county_id' =>$this->params['named']['countySearch'] ));    
								  
					 }
					 if((isset($this->params['named']['title'] ) && $this->params['named']['title']!='city name') && isset($this->params['named']['stateSearch'])&& isset($this->params['named']['countySearch'])){
					 
						 $this->set('title', $this->params['named']['title']); 
						 $this->set('countySearch', $this->params['named']['countySearch']);
						 $this->set('stateSearch', $this->params['named']['stateSearch']);
						 $condition = 	array (	'AND' => array ('City.cityname LIKE' => '%' . $this->params['named']['title'] . '%', 'City.county_id' =>$this->params['named']['countySearch'] , 'City.state_id' =>$this->params['named']['stateSearch']));    
								  
					 }
			 }
			 
			 $data = $this->paginate('City', $condition);
		     $this->set('citys', $data); 
	
	}
	
	
	/*------------------------------function to Add New County------------------------------------*/
	
	function addNewCity(){
	           
	         $this->set('States', $this->common->getAllState());
			 $this->set('Countys', $this->common->getAllCounty());
			 
			 App::import('model','City'); // importing Article model
			 $this->City = new City(); 
			
			if($this->data){
				 $this->City->set($this->data['states']);
				 if($this->data['states']!=''){

					 if ($this->City->validates()) {
									//making data array so we can pass in save mathod
									$saveArray = array();
									$saveArray['City']['cityname']    = $this->data['states']['cityname'];
									$saveArray['City']['publish']     = $this->data['states']['publish'];
									$saveArray['City']['county_id']   = $this->data['states']['county_id'];
									$saveArray['City']['state_id']    = $this->data['states']['state_id'];
									if($this->data['states']['meta_title']=='')
									{
									$saveArray['City']['meta_title']=$this->data['states']['cityname'];
									}
									$saveArray['City']['page_url']    =  $this->common->makeAlias(trim($this->data['states']['cityname']));	
									
									if(trim($this->data['states']['long'])=='' || trim($this->data['states']['lat'])=='') {
										$latlong = $this->common->latLong('United+States+'.$this->common->getStateName($this->data['states']['state_id']).'+'.$this->common->getCountyName($this->data['states']['county_id']).'+'.trim($this->data['states']['cityname']));
									}		
									if(trim($this->data['states']['long'])=='') {
										$saveArray['City']['long'] = $latlong['long'];
									} else {
										$saveArray['City']['long'] = $this->data['states']['long'];
									}
									
									if(trim($this->data['states']['lat'])=='') {
										$saveArray['City']['lat'] = $latlong['lat'];
									} else {
										$saveArray['City']['lat'] = $this->data['states']['lat'];
									}
									
									
																
									$this->City->save($saveArray);
									$this->Session->setFlash('Your data has been submitted successfully.');  
									$this->redirect(array('action' => "city"));

						}else{  

									/*setting error message if validation fails*/
									$errors = $this->City->invalidFields();	
									$this->Session->setFlash(implode('<br>', $errors));  
						}
				 }
		    }
	
	  }
	
	// show data in edit state form
	   function cityEditDetail($id=null){
	   
			 $this->set('States', $this->common->getAllState());
			 $this->set('Countys', $this->common->getAllCounty());
		     App::import('model','City'); // importing Article model
			 $this->City = new City(); 
	
	         $this->set('City',$this->City->cityEditDetail($id));
		   
	    }
		
		
		//edit county data
	   function cityEdit($id=null){
	          
			 $this->set('States', $this->common->getAllState());
			 
			 $this->set('Countys', $this->common->getAllCounty());
			  
	         App::import('model','City'); // importing Article model
			 $this->City = new City(); 
	  
	         $this->City->set($this->data['states']);	

			 if ($this->City->validates()) {

							//making data array so we can pass in save mathod
							$saveArray = array();
							$saveArray['City']['cityname']  = $this->data['states']['cityname'];
							$saveArray['City']['publish']   = $this->data['states']['publish'];
							$saveArray['City']['county_id'] = $this->data['states']['county_id'];
							$saveArray['City']['state_id']  = $this->data['states']['state_id'];
						    $saveArray['City']['page_url']    =  $this->common->makeAlias(trim($this->data['states']['cityname']));
							if($this->data['states']['meta_title']=='')
							{
							$saveArray['City']['meta_title']=$this->data['states']['cityname'];
							}
							
							if(trim($this->data['states']['long'])=='' || trim($this->data['states']['lat'])=='') {
								$latlong = $this->common->latLong('United+States+'.$this->common->getStateName($this->data['states']['state_id']).'+'.$this->common->getCountyName($this->data['states']['county_id']).'+'.trim($this->data['states']['cityname']));
							}	 
							if(trim($this->data['states']['long'])=='') {
								$saveArray['City']['long'] = $latlong['long'];
							} else {
								$saveArray['City']['long'] = $this->data['states']['long'];
							}
							
							if(trim($this->data['states']['lat'])=='') {
								$saveArray['City']['lat'] = $latlong['lat'];
							} else {
								$saveArray['City']['lat'] = $this->data['states']['lat'];
							}
									
									
							$this->City->save($saveArray);
							$this->Session->setFlash('Your data has been updated successfully.');  
							$this->redirect(array('action' => "city"));

			  } else{  

							/*setting error message if validation fails*/
							$errors = $this->City->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "cityEditDetail/".$this->data['states']['id'])); 
							
			  }
	    }
		
		//delete state data in database
	   function cityDelete($id) {
	   
	         App::import('model','City'); // importing Article model
			  $this->City = new City(); 
			  
			    $resultLink   		    = $this->State->query("SELECT * FROM links where city_id = $id");
			  
			    $resultAdvertiserProfile = $this->State->query("SELECT * FROM advertiser_profiles where city = $id");
			  
			   
			   if((!empty($resultLink))||(!empty($resultAdvertiserProfile))){
			  
				  if(!empty($resultLink))
			      $delete['resultLink'] = 'This city contain Link Manager.You have to delete first Link Manager of this city.';
				  
				  if(!empty($resultAdvertiserProfile))
			      $delete['resultAdvertiserProfile'] = 'This city contain Advertiser Profile.You have to delete first Advertiser Profile of this city.';
				  				  
				  
				  $this->Session->setFlash(implode('<br>', $delete));
				  
				  $this->redirect(array('action' => "city" , 'message'=>'error'));
				   
			  } else {
			       $this->City->delete($id);
				   
			       $this->Session->setFlash('The City with id: '.$id.' has been deleted.');
				   
			       $this->redirect(array('action'=>'city' , 'message'=>'success'));
				  
			 }
			
	   }
	   
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocompleteCity($string='') {

			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			 App::import('model', 'City');
			$this->City = new City;
			$name = $this->City->query("SELECT City.cityname FROM cities AS City WHERE City.cityname LIKE '$string%'");
			foreach($name as $name) {
				$arr[] = $name['City']['cityname'];
			}
				echo json_encode($arr);
			}
	}
	
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function getcountyList($state='',$county_id='') {
		$this->layout = false;
		if($state!=''){
				$selCounty = $this->common->getAllCountyByState($state);
		} else {
				$selCounty = '';
		}
		$this->set('selCounty',$selCounty);		
	}

/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function getcountyListMobile() {
		$this->layout = false;		
		if(isset($this->data['Mobile']['state']) && $this->data['Mobile']['state'] !=''){
				$state_id=$this->data['Mobile']['state'];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else {
				$selCounty = '';
		}
		$this->set('selCounty',$selCounty);		
	}
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function getcountyListContact($state='') {
		$this->layout = false;
			
		if(isset($state) && $state!=''){
				$state_id=$state;
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else {
				$selCounty = '';
		}
		$this->set('selCounty',$selCounty);		
	}

	function getcountyListContactMobile() {
		$this->layout = false;		
		if(isset($this->data['ContactLead']['state']) && $this->data['ContactLead']['state'] !=''){
				$state_id=$this->data['ContactLead']['state'];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else {
				$selCounty = '';
		}
		$this->set('selCounty',$selCounty);		
	}

        function getcountyListCareer() {
		$this->layout = false;		
		if(isset($this->data['Career']['state']) && $this->data['Career']['state'] !=''){
				$state_id=$this->data['Career']['state'];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else {
				$selCounty = '';
		}
		$this->set('selCounty',$selCounty);		
	}
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function countyforDiscount() {
		$this->layout = false;
		if(isset($this->data['DailyDiscountCalendars']['state']) && $this->data['DailyDiscountCalendars']['state'] !=''){
				$state_id=$this->data['DailyDiscountCalendars']['state'];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
				$state_id	=	$this->params['pass'][0];
				$selCounty = $this->common->getAllCountyByState($state_id);
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
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function countyforOrder() {
		$this->layout = false;
		if(isset($this->data['AdvertiserOrder']['state']) && $this->data['AdvertiserOrder']['state'] !=''){
				$state_id=$this->data['AdvertiserOrder']['state'];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
				$state_id	=	$this->params['pass'][0];
				$selCounty = $this->common->getAllCountyByState($state_id);
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
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function cityforOrder() {
	
		$this->layout = false;
		if(isset($this->data['AdvertiserOrder']['county']) && $this->data['AdvertiserOrder']['county'] !=''){
				$county_id=$this->data['AdvertiserOrder']['county'];
				$selCity = $this->common->getCountyCity($county_id);
		} else if(isset($this->params['pass'][1]) && $this->params['pass'][1]!='') {
				$county_id	=	$this->params['pass'][1];
				$selCity = $this->common->getCountyCity($county_id);
		} else {
				$selCity = '';
		}
		$city = '';
		if(isset($this->params['pass'][2]) && $this->params['pass'][2]!='') {
				$city	=	$this->params['pass'][2];
		}
		$city2 = '';
		if(isset($this->params['pass'][3]) && $this->params['pass'][3]!='') {
				$city2	=	$this->params['pass'][3];
		}			
		$this->set('selCity',$selCity);
		$this->set('city',$city);
		$this->set('city2',$city2);		
	}
	
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function countyforMeta() {
		$this->layout = false;
		if(isset($this->data['Meta']['state']) && $this->data['Meta']['state'] !=''){
				$state_id=$this->data['Meta']['state'];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
				$state_id	=	$this->params['pass'][0];
				$selCounty = $this->common->getAllCountyByState($state_id);
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
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function cityforMeta() {
		$this->layout = false;
		if(isset($this->data['Meta']['county']) && $this->data['Meta']['county'] !=''){
				$county_id=$this->data['Meta']['county'];
				$selCity = $this->common->getCountyCity($county_id);
		} else if(isset($this->params['pass'][1]) && $this->params['pass'][1]!='') {
				$county_id	=	$this->params['pass'][1];
				$selCity = $this->common->getCountyCity($county_id);
		} else {
				$selCity = '';
		}
		$city = '';
		if(isset($this->params['pass'][2]) && $this->params['pass'][2]!='') {
				$city	=	$this->params['pass'][2];
		}
		$this->set('selCity',$selCity);
		$this->set('city',$city);
	}
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function countyforProfile() {
		$this->layout = false;
		if(isset($this->data['AdvertiserProfile']['state']) && $this->data['AdvertiserProfile']['state'] !=''){
				$state_id=$this->data['AdvertiserProfile']['state'];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
				$state_id	=	$this->params['pass'][0];
				$selCounty = $this->common->getAllCountyByState($state_id);
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
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function countyforChange() {
		$this->layout = false;
		if(isset($this->data['AdvertiserProfile']['state']) && $this->data['AdvertiserProfile']['state'] !=''){
				$state_id=$this->data['AdvertiserProfile']['state'];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
				$state_id	=	$this->params['pass'][0];
				$selCounty = $this->common->getAllCountyByState($state_id);
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
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function countyforContact() {
		$this->layout = false;
		if(isset($this->data['contact_leads']['state']) && $this->data['contact_leads']['state'] !=''){
				$state_id=$this->data['contact_leads']['state'];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
				$state_id	=	$this->params['pass'][0];
				$selCounty = $this->common->getAllCountyByState($state_id);
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
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function cityforChange() {
		$this->layout = false;
		if(isset($this->data['AdvertiserProfile']['county']) && $this->data['AdvertiserProfile']['county'] !=''){
				$county_id=$this->data['AdvertiserProfile']['county'];
				$selCity = $this->common->getAllCityByCounty($county_id);
		} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
				$county_id	=	$this->params['pass'][0];
				$selCity = $this->common->getAllCityByCounty($county_id);
		} else {
				$selCity = '';
		}
		$city = '';
		if(isset($this->params['pass'][1]) && $this->params['pass'][1]!='') {
				$city	=	$this->params['pass'][1];
		}			
		$this->set('selCity',$selCity);
		$this->set('city',$city);
	}		
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function cityforProfile() {
		$this->layout = false;
		if(isset($this->data['AdvertiserProfile']['county']) && $this->data['AdvertiserProfile']['county'] !=''){
				$county_id=$this->data['AdvertiserProfile']['county'];
				$selCity = $this->common->getCountyCity($county_id);
		} else if(isset($this->params['pass'][1]) && $this->params['pass'][1]!='') {
				$county_id	=	$this->params['pass'][1];
				$selCity = $this->common->getCountyCity($county_id);
		} else {
				$selCity = '';
		}
		$city = '';
		if(isset($this->params['pass'][2]) && $this->params['pass'][2]!='') {
				$city	=	$this->params['pass'][2];
		}
		$city2 = '';
		if(isset($this->params['pass'][3]) && $this->params['pass'][3]!='') {
				$city2	=	$this->params['pass'][3];
		}
		$this->set('selCity',$selCity);
		$this->set('city',$city);
		$this->set('city2',$city2);
	}
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function advertisers() {
		$this->layout = false;
		if(isset($this->data['AdvertiserProfile']['city']) && $this->data['AdvertiserProfile']['city'] !=''){
				$city_id=$this->data['AdvertiserProfile']['city'];
				$advertiserList = $this->common->getAdvertiserCity($city_id); // List advertisers
		} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
				$city_id	=	$this->params['pass'][0];
				$advertiserList = $this->common->getAdvertiserCity($city_id); // List advertisers
		} else {
				$advertiserList = '';
		}
		$advertiser = '';
		if(isset($this->params['pass'][1]) && $this->params['pass'][1]!='') {
				$advertiser	=	$this->params['pass'][1];
		}			
		$this->set('advertiserList',$advertiserList);
		$this->set('advertiser',$advertiser);		
	}	
	
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function package() {
		$this->layout = false;
		if(isset($this->data['AdvertiserProfile']['advertiser_profile_id']) && $this->data['AdvertiserProfile']['advertiser_profile_id'] !=''){
				$ad_id=$this->data['AdvertiserProfile']['advertiser_profile_id'];
				$order_id = $this->common->getonlyOrderId($ad_id); // List advertisers
				$package_id = $this->common->getPackageId($order_id); // List advertisers
				$Packages = $this->common->getAllPackage(1);
				if($package_id) {
					$this->set('Packages',$Packages[$package_id]);
				} else {
					$this->set('Packages','No Package');
				}
		} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
				$ad_id=$this->params['pass'][0];
				$order_id = $this->common->getonlyOrderId($ad_id); // List advertisers
				$package_id = $this->common->getPackageId($order_id); // List advertisers
				$Packages = $this->common->getAllPackage(1);
				if($package_id) {
					$this->set('Packages',$Packages[$package_id]);
				} else {
					$this->set('Packages','No Package');
				}
		} else {
				$this->set('Packages','No Package');
		}	
	}
/*------------------------------------------------------------------------------------------------------------------------*/ 		
	function getAdvertiserCats($id=0) {
		$this->autoRender = false;
		echo $this->common->advertiserCatCombo($id);
	}
/*------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function crop($pass='') {
	if($pass!='') {
		$break = explode('/',base64_decode($pass));
		if(count($break)==2) {
			$img = base64_decode($break[0]);
			$url = base64_decode($break[1]);
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				if(trim($_POST['w'])=='' || trim($_POST['h'])=='') {
					$this->Session->setFlash('Invalid dimensions for resizing.');
				} else {
				ini_set('memory_limit', '-1');
				$size = getimagesize(WWW_ROOT.'img/'.$img); 
					switch ($size['mime']) { 
					case "image/gif": 
						$src_image = imagecreatefromgif(WWW_ROOT.'img/'.$img);
						break; 
					case "image/jpeg": 
						$src_image = imagecreatefromjpeg(WWW_ROOT.'img/'.$img);
						break; 
					case "image/png": 
						$src_image = imagecreatefrompng(WWW_ROOT.'img/'.$img);
						break;
					}
					
					$dst_x = 0;
					$dst_y = 0;
					$src_x = $_POST['x1']; // Crop Start X
					$src_y = $_POST['y1']; // Crop Srart Y
					$dst_w = (int)$_POST['w']; // Thumb width
					$dst_h = (int)$_POST['h']; // Thumb height
					$src_w = (int)$_POST['w'];//(int)($_POST['w']+$_POST['x2']);
					$src_h = (int)$_POST['h'];//(int)($_POST['h']+$_POST['y2']);
					
					$dst_image = imagecreatetruecolor($dst_w,$dst_h);
					imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
					
					switch ($size['mime']) { 
					case "image/gif": 
						imagegif($dst_image, WWW_ROOT.'img/'.$img);
						break; 
					case "image/jpeg": 
						imagejpeg($dst_image, WWW_ROOT.'img/'.$img);
						break; 
					case "image/png": 
						imagepng($dst_image, WWW_ROOT.'img/'.$img);
						break;
					}
					$this->Session->setFlash('Image has been resized successfully.');
					$this->redirect($url.'/type:success');
				}	
			}
			$this->set('img',$img);
			$this->set('url',$url);
		} else {
			$this->redirect($this->referer());
		}
	} else {
		$this->redirect($this->referer());
	}
}				
/*------------------------------------------------------------------------------------------------------------------------*/ 		   
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