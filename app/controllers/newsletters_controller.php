<?php          
 /*
   Coder: Manoj
   Date  : 10 May 2011
*/ 
  class NewslettersController extends AppController {
	    var $name    = 'Newsletters';
	    var $helpers = array('Html', 'Form', 'User', 'Javascript', 'Text', 'Image', 'Paginator','Ajax');
	    var $layout  = 'admin';
	    var $components = array('Auth','common','Cookie','RequestHandler','Email','emailhtml','Session');/*,'Email'=>array('from' => 'info@ishop.com','sendAs' => 'html')*/
		
		//component to check authentication . this component file is exists in app/controllers/components
		
/*-----------------------------------------------------------index page of category for listing -------------------------------------------------------*/
	function index(){
	   	$cond = '';
	    $this->set('title_for_layout', 'Newsletter Management');
		
		/* Sets all categories to view */
		$this->set('Categorys',$this->common->getAllCategory());
		
		/* Sets all counties to view */
		$this->set('Countys',$this->common->getAllCounty());
		
		/* Find and paginate all data to the view of newsletter*/
		$this->paginate = array('limit' => PER_PAGE_RECORD, 'order' => array('Newsletter.id' => 'desc'));
		$this->set('search_text','Title');
		$this->set('category', 'Category');
		$this->set('county', 'County');
		
		 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		//if county is set
		if((isset($this->data['newsletters']['county']) and $this->data['newsletters']['county'] != '')|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
			if((isset($this->data['newsletters']['county']) and $this->data['newsletters']['county'] != ''))
			{
			 $county = $this->data['newsletters']['county'] ;
			}
			else if( isset($this->params['named']['county']) and $this->params['named']['county'] !=''){
			 $county = $this->params['named']['county'] ;
			}else{
			  
			  $county ="";
			}
			
			$this->set('county',$county); 
		}
		
		//if category is set
		if((isset($this->data['newsletters']['category']) and $this->data['newsletters']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
			if((isset($this->data['newsletters']['category']) and $this->data['newsletters']['category'] != 0))
			{
			 $category = $this->data['newsletters']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}		
		//if title is set
		if((isset($this->data['newsletters']['search_text']) and ($this->data['newsletters']['search_text'] != '' and $this->data['newsletters']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['newsletters']['search_text']) and ($this->data['newsletters']['search_text'] != '' and $this->data['newsletters']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['newsletters']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
			 /*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		if(isset($county) && $county !=''){
		 $cond['Newsletter.county'] = $county;
		}
		
		if(isset($category) && $category !=''){
		 $cond['Newsletter.category'] = $category;
		}
		
		if(isset($search_text) && $search_text !=''){
		 $cond['Newsletter.title LIKE'] = '%'.$search_text.'%';
		}
		
		/*----------------------------------It sets data to view by specified condition--------------------------------------------------------*/
		 		$data = $this->paginate('Newsletter', $cond);				
		    	$this->set('newsletters', $data);		
	   }	  
/*-----------------------------------------------------------Function of Adding Newsletter in database ---------------------------------------------------------------*/
	  
function addNewsletter(){

   	    $this->set('title_for_layout', 'Add Newsletter');
		/* Sets all categories to view */
		$this->set('Categorys',$this->common->getAllCategory());
		/* Sets all counties to view */
		$this->set('Countys',$this->common->getAllCounty());
		  if(isset($this->data))
		  {
		   $this->Newsletter->set($this->data['newsletters']);
		    if($this->data['newsletters']!='')
			{
				 if ($this->Newsletter->validates())
				  {
				  	$saveArray = array();
					$saveArray['Newsletter']['title'] 			= 	$this->data['newsletters']['title'];
					$saveArray['Newsletter']['subject'] 		= 	$this->data['newsletters']['subject'];
					$saveArray['Newsletter']['county'] 			=	$this->data['newsletters']['county'];
					$saveArray['Newsletter']['category'] 		= 	$this->data['newsletters']['category'];
					$saveArray['Newsletter']['email_content'] 	= 	$this->data['newsletters']['email_content'];
					$saveArray['Newsletter']['published'] 		= 	$this->data['newsletters']['published'];
					$no_of_copy = $this->data['newsletters']['duplicate_copy'];
					
					/*Saved One copy of the news letter if all data are valid*/
					if($no_of_copy == 0)
					{
					 	$this->Newsletter->save($saveArray);
						$this->Session->setFlash('Newsletter has been Saved');
						$this->redirect(array('action' => "index" , 'message'=>'success'));  
					}
					
					/*Saved specified copy of the news letter if all data are valid*/
					else
					{	$i = 1;
						while($i <= $no_of_copy+1)
						{
							$this->Newsletter->save($saveArray);
							
							/*Saved Newsletter Id and increment it by one*/
							$saveArray['Newsletter']['id'] 	= ($this->Newsletter->id)+1;
							$i++;
						}
						$this->Session->setFlash('Newsletter has been Saved with Its '.$no_of_copy.' Duplicate Copies'); 
						$this->redirect(array('action' => "index" , 'message'=>'success'));
					}
					 	
				  }
				 else
				  {  
		
					/*setting error message if validation fails*/
					$errors = $this->Newsletter->invalidFields();	
					$this->Session->setFlash(implode('<br>', $errors));  
					return false;						
				  }
				}
			}
		}
		
/*-------------------------------------------------------Function of Updating Newsletter in database  ----------------------------------------------------------------*/

function editNewsletter($id=null){

   	    $this->set('title_for_layout', 'Edit Newsletter');
		
		/*********************************************************************************************/
		/* Sets all categories to view */
		$this->set('Categorys',$this->common->getAllCategory());
		/* Sets all counties to view */
		$this->set('Countys',$this->common->getAllCounty());
		  /* Sets all all data regarding specified Id to view */
		  $this->set('data',$this->Newsletter->findbyId($id));
		  if(isset($this->data))
		  {
		  	if($this->data['newsletters']!='')
			{
				 if ($this->Newsletter->validates())
				  {
					$data['Newsletter']['id'] 				= 	$this->data['newsletters']['uid'];
					$data['Newsletter']['subject'] 			= 	$this->data['newsletters']['subject'];
					$data['Newsletter']['title'] 			= 	$this->data['newsletters']['title'];
					$data['Newsletter']['county'] 			= 	$this->data['newsletters']['county'];
					$data['Newsletter']['category'] 		= 	$this->data['newsletters']['category'];
					$data['Newsletter']['email_content'] 	= 	$this->data['newsletters']['email_content'];
					$data['Newsletter']['published'] 		= 	$this->data['newsletters']['published'];
					
					/*********************************************************************************************/
					/* Update Data in DB table with specified Id*/
					
				  	$this->Newsletter->save($data);
				  	$this->Session->setFlash('Newsletter has been Updated with Id: '.$this->data['newsletters']['uid'].' Successfully!!'); 
					$this->redirect(array('action' => "index" , 'message'=>'success')); 
		  		  }
				   else
				  {  
					/*********************************************************************************************/
					/*setting error message if validation fails*/
					
					$errors = $this->Newsletter->invalidFields();	
					$this->Session->setFlash(implode('<br>', $errors));  
					return false;						
				  }
			}
		 }
	}  
		
/*-------------------------------------------------------Function of Deleting Newsletter From database----------------------------------------------------------------*/		
	
	function deleteNewsletter($id=null){
	
					/*********************************************************************************************/
					/*Delete the newsletter with specified Id value*/
						
					$this->Newsletter->delete($id);
					$this->Session->setFlash('The Newsletter with id:  '.$id.' has been Deleted Successfully!!');
					$this->redirect(array('action'=>'index'));

	}
	
	
	
/*----------------------------------------------Function of Sending Newsletter AS Email to specified users -----------------------------------------------------------*/
	  
	function sendNewsletter(){
					$this->set('title_for_layout', 'Send Newsletters');
					/**** Sets all categories i.e. registered for newsletter to view ************************************************************/
					
					App::import('model','Category');
					$this->Category = new Category();
					$cat=$this->Newsletter->find('list',array('fields'=>'category')); 
					$conditions = array('Category.id'=>$cat);
					$Category = $this->Category->find('list', array('fields' => array('id', 'categoryname'),'conditions'=>$conditions,'order' => 'Category.categoryname ASC','recursive' => -1 )); 
					$this->set('Categorys',$Category);
					//$this->set('Categorys','');
					
					/*********************************************************************************************/
					/* Sets all counties i.e. registered for newsletter to view */					
					App::import('model','County');
					$this->County = new County();
					$count=$this->Newsletter->find('list',array('fields'=>'county'));
					$conditions = array('County.id'=>$count);
					$County = $this->County->find('list', array('fields' => array('id', 'countyname'),'conditions'=>$conditions,'order' => 'County.countyname ASC','recursive' => -1 ));
					$this->set('Countys',$County);
					
					/*********************************************************************************************/
					/* Sets all Newsletters i.e. published to view */					
					$conditions = array('Newsletter.published'=>'yes');
					$Newsletter=$this->Newsletter->find('list', array('fields' => array('id', 'title'),'conditions'=>$conditions,'order' => 'Newsletter.title ASC','recursive' => -1));
					$this->set('Newsletters',$Newsletter);					
					//$this->set('Newsletters','');
					/*********************************************************************************************/
					/* Sets all Users to view */
					
					App::import('model','NewsletterUser');
					$this->NewsletterUser = new NewsletterUser(); 
					$User=$this->NewsletterUser->find('list', array('fields' => array('email', 'name'),'conditions'=>array('NewsletterUser.status'=>'yes'),'order' => 'NewsletterUser.name ASC','recursive' => -1 )); 
					$this->set('Users',$User);
					//$this->set('Users','');
					if(isset($this->data))
		  			{
					/*pr($this->data);
					exit;*/
					/*********************************************************************************************/
					/* Checks the initial criteria required to send mail has been filled or not */
					
						$msgError = '';
						/*pr($this->data);*/
		  				if(isset($this->data['newsletters']['county']) && $this->data['newsletters']['county'] == '')
						{
							$msgError['County']='Please Select County';
						}
						if(isset($this->data['newsletters']['category']) && $this->data['newsletters']['category'] == '')
						{
							$msgError['Category']='Please Select Category';						
						}						
		  				if(isset($this->data['newsletters']['newsletter']) && $this->data['newsletters']['newsletter'] == '')
						{
							$msgError['Newsletter']='Please Select Newsletter';
						}						
		  				if((isset($this->data['newsletters']['massmail']) && $this->data['newsletters']['massmail'] == 0) && (isset($this->data['newsletters']['user']) && $this->data['newsletters']['user'] == '') && (isset($this->data['newsletters']['want_test']) && $this->data['newsletters']['want_test'] == 0))
						{
							$msgError['Massmail']='Please Select User Or MassMail Checkbox';
						}						
						if(!is_array($msgError))
						{
							$county = $this->data['newsletters']['county'];
							$category = $this->data['newsletters']['category'];
							/************Return true if Newsletter is found with selected County, Category, Title value(and published yes)*******/
							$conditions['Newsletter.id']=$this->data['newsletters']['newsletter'];
							
							if($letter=$this->Newsletter->find('first',array('fields'=>array('Newsletter.id','Newsletter.subject','Newsletter.email_content'),'conditions'=>$conditions)))
							{
//-------------------------------------------------------------------------------------------------------------------------------------------------------------//
							if($this->data['newsletters']['want_test']==1 && $this->data['newsletters']['test_email']!='') {							
								$emails[0] = $this->data['newsletters']['test_email'];
							}
							else if($this->data['newsletters']['massmail'] == 0)	// Single mail section
								{
									$emails = $this->data['newsletters']['user'];
								}
								else	// Massmail section
								{
									$condi = array("NewsletterUser.county_id = ".$county,"NewsletterUser.category_id =".$category,"NewsletterUser.status='yes'");									
									$emails=$this->NewsletterUser->find('list', array('fields' => array('email'),'conditions'=>$condi,'order'=>'NewsletterUser.name ASC','recursive' => -1));
								}
									$bottom_line = $this->common->getNewsLetterBottom();
									$from=$this->common->getNewsLetterEmail();
									$main_content = $this->returnNewsletter($letter['Newsletter']['id']);
									$subject = $letter['Newsletter']['subject'];
									//pr($emails);
									//exit;
									$email_ids = array_chunk($emails, EMAIL_LIST);
									foreach($email_ids as $email_id) {
											foreach($email_id as $email) {
												$msg ='';							
					$unsubscribe = '<a href="'.FULL_BASE_URL.router::url('/',false).'newsletters/unsubscribe/'.base64_encode($email).'">Unsubscribe</a>';				
												$msg = $main_content;
												$msg .= $bottom_line;
												$msg .= $unsubscribe;
												$this->Email->to 		= $email;
												$this->Email->subject 	= strip_tags($subject);
												$this->Email->replyTo 	= $this->common->getReturnEmail();
												$this->Email->from 		= $from;
												$this->Email->sendAs 	= 'html';
												//Set the body of the mail as we send it.			
												//seperate line in the message body.
												$this->body = '';
												$this->body = $this->emailhtml->email_header($county);
												$this->body .=$msg;
												$this->body .= $this->emailhtml->email_footer($county);
					
									/*SMTP Options*/
										   $this->Email->smtpOptions = array(
												'port'=>'25', 
												'timeout'=>'30',
												'host' =>SMTP_HOST_NAME,
												'username'=>SMTP_USERNAME,
												'password'=>SMTP_PASSWORD
											);										
												/* Set delivery method */
												$this->Email->delivery = 'smtp';										
												/* Do not pass any args to send() */
												$this->Email->send($this->body);												
											}
											sleep(2);								
										}
										$this->Session->setFlash('The Newsletter has been Sent as MassMail Successfully!!');
										$this->redirect(array('action' => 'sendNewsletter'));
//-------------------------------------------------------------------------------------------------------------------------------------------------------------//								
							}	
							else
							{
							$this->Session->setFlash('Newsletter Not Found for Selected County and Category!!');
							return false;
							}
						}
						else
						{
						$this->set('msgError',$msgError);
						$this->Session->setFlash(implode('<br>', $msgError));
						return false;	
						}	
	
				 }
	}	
	function selectedCatList(){
	
	App::import('model','Category');
	$this->Category = new Category();
	
	if(isset($this->data['newsletters']['county'])&& $this->data['newsletters']['county'] !=''){
	 $cond = array('Newsletter.county'=>$this->data['newsletters']['county']);
	} else if(isset($this->params['pass'][0])&& $this->params['pass'][0] !=''){
	 $cond = array('Newsletter.county'=>$this->params['pass'][0]);
	} else{
	 $cond = '';
	}
	
	 $cat = $this->Newsletter->find('list',array('fields'=>'category','conditions'=>$cond ));
	 
	 //pr($cat);
	 //$cat = implode(",",array_unique($cat));
	 $conditions ['Category.id'] =  array_unique($cat);	
	 $Category = $this->Category->find('list', array('fields' => array('id', 'categoryname'),'conditions'=>$conditions,'order' => 'Category.categoryname ASC')); 
	 $this->set('Categorys',$Category);
	 	$single_cat = '';
		 if(isset($this->params['pass'][1])) {
			$single_cat = $this->params['pass'][1];
		 }
		 $this->set('single_cat',$single_cat);
	}	
	function selectedNewsletter(){
		if(isset($this->data['newsletters']['category']) && $this->data['newsletters']['category']!=''){
			$conditions['Newsletter.category'] =   $this->data['newsletters']['category'];
			$this->set('categorySelect',$this->data['newsletters']['category']);
		} 
		else if(isset($this->params['pass'][1]) && $this->params['pass'][1]!=''){
			$conditions['Newsletter.category'] =   $this->params['pass'][1];
			$this->set('categorySelect',$this->params['pass'][1]);
		} else {
			$this->set('categorySelect',"");
		}
	
	
	if(isset($this->params['pass'][0])){
	   if($this->params['pass'][0] !=''){
	   $conditions['Newsletter.county'] = $this->params['pass'][0];
	   $this->set('countySelect',$this->params['pass'][0]);
	  }
	}else{	
	 	$this->set('countySelect',"");	
	}
	
	$conditions['Newsletter.published'] ='yes';
	$Newsletter=$this->Newsletter->find('list', array('fields' => array('id', 'title'),'conditions'=>$conditions,'order' => 'Newsletter.title ASC'));
	$this->set('Newsletters',$Newsletter);
		$single_latter = '';
		 if(isset($this->params['pass'][2])) {
			$single_latter = $this->params['pass'][2];
		 }
		$this->set('single_latter',$single_latter);
}

    function selectedUser(){ 
	$conditions='';
	if(isset($this->params['pass'][1])){
	   if($this->params['pass'][1]!=''){
	    //$conditions['NewsletterUser.category_id'] = $this->params['pass'][1];
		$conditions[] = 'NewsletterUser.category_id LIKE "%,'.$this->params['pass'][1].',%"';
	  }
	}
	if(isset($this->params['pass'][0])){
	   if($this->params['pass'][0] !=''){
	   $conditions['NewsletterUser.county_id'] = $this->params['pass'][0];
	  }
	}
	$conditions['NewsletterUser.status'] = 'yes';
		App::import('model','NewsletterUser');
		$this->NewsletterUser = new NewsletterUser(); 
		$User=$this->NewsletterUser->find('list', array('fields' => array('email', 'name'),'conditions'=>$conditions,'order' => 'NewsletterUser.name ASC','recursive' => -1 )); 
		$this->set('Users',$User);
}
	
/*----------------------------------------------Function of Setting Newsletter Subscribers to View ------------------------------------------------------------------*/
	  


	function subscribers(){
	
					$cond[] = "NewsletterUser.email!=''";
	    			$this->set('title_for_layout', 'Newsletter Subscribers');
					
					$this->set('Categorys',$this->common->getAllCategory());
					
					$this->set('Countys',$this->common->getAllCounty());
					
					App::import('model','NewsletterUser');
					
					$this->NewsletterUser = new NewsletterUser();
					
					$this->paginate = array('limit' => PER_PAGE_RECORD, 'order' => array('NewsletterUser.id' => 'desc')); 
					
					$this->set('search_text','Email');
					
					$this->set('category', 'Category');
					
					$this->set('county', 'County');
					
					
					 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
			 
		//if county is set
		if((isset($this->data['newsletters']['county']) and $this->data['newsletters']['county'] != '')|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
			if((isset($this->data['newsletters']['county']) and $this->data['newsletters']['county'] != ''))
			{
			 $county = $this->data['newsletters']['county'] ;
			}
			else if( isset($this->params['named']['county']) and $this->params['named']['county'] !=''){
			 $county = $this->params['named']['county'] ;
			}else{
			  
			  $county ="";
			}
			
			$this->set('county',$county); 
		}
		
		//if category is set
		if((isset($this->data['newsletters']['category']) and $this->data['newsletters']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
		
		
			if((isset($this->data['newsletters']['category']) and $this->data['newsletters']['category'] != 0))
			{
			 $category = $this->data['newsletters']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
		
		//if title is set
		if((isset($this->data['newsletters']['search_text']) and ($this->data['newsletters']['search_text'] != '' and $this->data['newsletters']['search_text'] != 'Email'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Email') )){
		
			if((isset($this->data['newsletters']['search_text']) and ($this->data['newsletters']['search_text'] != '' and $this->data['newsletters']['search_text'] != 'Email')))
			{
			 $search_text = $this->data['newsletters']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Email')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
			 /*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/


		
		if(isset($county) && $county !=''){
		 $cond['NewsletterUser.county_id'] = $county;
		}
		
		if(isset($category) && $category !=''){
		$cond[] = "NewsletterUser.category_id LIKE '%,".$category.",%'";
		}
		
		if(isset($search_text) && $search_text !=''){
		 $cond['NewsletterUser.email LIKE'] = '%'.$search_text. '%';
		}
		$cond['NewsletterUser.status'] = 'yes';
		
		
		/*----------------------------------It sets data to view by specified condition--------------------------------------------------------*/
		 		
				
				
				$data = $this->paginate('NewsletterUser', $cond);
				
		    	$this->set('subscribers', $data); 
					
	}


/*----------------------------------------------Function of Setting Newsletter Subscribers to View ------------------------------------------------------------------*/
	  


	function unsubscribers(){
	
					$cond = '';
	    			$this->set('title_for_layout', 'Newsletter Unsubscribers');
					
					$this->set('Categorys',$this->common->getAllCategory());
					
					$this->set('Countys',$this->common->getAllCounty());
					
					App::import('model','NewsletterUser');
					
					$this->NewsletterUser = new NewsletterUser();
					
					$this->paginate = array('limit' => PER_PAGE_RECORD, 'order' => array('NewsletterUser.id' => 'desc')); 
					
					$this->set('search_text','Email');
					
					$this->set('category', 'Category');
					
					$this->set('county', 'County');
					
					
					 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
			 
		//if county is set
		if((isset($this->data['newsletters']['county']) and $this->data['newsletters']['county'] != '')|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
			if((isset($this->data['newsletters']['county']) and $this->data['newsletters']['county'] != ''))
			{
			 $county = $this->data['newsletters']['county'] ;
			}
			else if( isset($this->params['named']['county']) and $this->params['named']['county'] !=''){
			 $county = $this->params['named']['county'] ;
			}else{
			  
			  $county ="";
			}
			
			$this->set('county',$county); 
		}
		
		//if category is set
		if((isset($this->data['newsletters']['category']) and $this->data['newsletters']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
		
		
			if((isset($this->data['newsletters']['category']) and $this->data['newsletters']['category'] != 0))
			{
			 $category = $this->data['newsletters']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
		
		//if title is set
		if((isset($this->data['newsletters']['search_text']) and ($this->data['newsletters']['search_text'] != '' and $this->data['newsletters']['search_text'] != 'Email'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Email') )){
		
			if((isset($this->data['newsletters']['search_text']) and ($this->data['newsletters']['search_text'] != '' and $this->data['newsletters']['search_text'] != 'Email')))
			{
			 $search_text = $this->data['newsletters']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Email')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
			 /*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/


		
		if(isset($county) && $county !=''){
		 $cond['NewsletterUser.county_id'] = $county;
		}
		
		if(isset($category) && $category !=''){
		 $cond['NewsletterUser.category_id'] = $category;
		}
		
		if(isset($search_text) && $search_text !=''){
		 $cond['NewsletterUser.email LIKE'] = '%'.$search_text. '%';
		}

		$cond['NewsletterUser.status'] = 'no';
		
		/*----------------------------------It sets data to view by specified condition--------------------------------------------------------*/
		 		
				
				
				$data = $this->paginate('NewsletterUser', $cond);
				
		    	$this->set('subscribers', $data); 
					
	}	
	
/*-------------------------------------------------------Function of Deleting Newsletter from database----------------------------------------------------------------*/		
	
function deleteSubscriber($id=null){

					/*Delete the subscriber with specified Id value*/
					
					App::import('model','NewsletterUser');
					
					$this->NewsletterUser = new NewsletterUser();
						
					$this->NewsletterUser->delete($id);
					
					$this->Session->setFlash('The Subscriber with id:  '.$id.' has been Deleted Successfully!!');
					
					$this->redirect(array('action'=>'subscribers'));

	}

/*-------------------------------------------------------Function of Deleting Newsletter from database----------------------------------------------------------------*/		
	
function deleteUnSubscriber($id=null){

					/*Delete the subscriber with specified Id value*/
					
					App::import('model','NewsletterUser');
					
					$this->NewsletterUser = new NewsletterUser();
						
					$this->NewsletterUser->delete($id);
					
					$this->Session->setFlash('The Unsubscriber with id:  '.$id.' has been Deleted Successfully!!');
					
					$this->redirect(array('action'=>'unsubscribers'));

	}	
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocomplete($string='') {

			$this->autoRender = false;
			
	
			if($string!=''){
			$arr = '';

			$name = $this->Newsletter->query("SELECT Newsletter.title FROM newsletters AS Newsletter WHERE Newsletter.title LIKE '$string%'");

			
			foreach($name as $name) {
				$arr[] = $name['Newsletter']['title'];
			}
			echo json_encode($arr);
			}
	}	

/*------------------------------------------------------------------------------------------------------------------------*/ 		
	 
/*---------------destroy all current sessions for a particular SuperAdmins and redirect to login page automatically -------------------------------------------------*/

function logout() {
		
   		         	$this->redirect($this->Auth->logout());
				 
        }
/*-----------------------------------------this function Set up the css  --------------------------------------------------------------------------------------------*/

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
	 
	function unsubscribe($string) {
		$this->autoRender = false;
	 	if(isset($string) && $string!='') {
	 		$email = base64_decode($string);
			App::import('model','NewsletterUser');
			$this->NewsletterUser = new NewsletterUser(); 
			$User=$this->NewsletterUser->find('first',array('conditions'=>array('NewsletterUser.email'=>$email)));
			$savedata['NewsletterUser']['id'] 		= $User['NewsletterUser']['id'];
			$savedata['NewsletterUser']['status'] 	= 'no';
			$this->NewsletterUser->save($savedata);
			echo 'Your newsletter unsubscription request has been sent successfully.';
		}
	 }
/*--------------------------------------------View Newsletter---------------------------------------*/	 
function viewNewsletter($id=NULL) {	
	$this->set('content',$this->returnNewsletter($id));
}	
/*--------------------------------------------View Newsletter---------------------------------------*/
	function returnNewsletter($id=NULL) {
					//$this->id = $id;
					$data = $this->Newsletter->find('first',array('conditions'=>array('Newsletter.id'=>$id)));
					//pr($data);
					
					$county_id = $data['Newsletter']['county'];
					//if(count($this->params['pass'])==5 && $this->params['pass'][2]!='business')
					//$this->layout='topten';				
					//find all city
					 $cityList=$this->common->getAllCity();
						
					$conditions='';
					//to set the published field by default
						$conditions['AdvertiserProfile.publish'] = 'yes';						
					//to set the county in codition array by default
						$conditions['AdvertiserProfile.county'] = $county_id;					
					//if category is set				
							$cat_id_url=$data['Newsletter']['category'];					
					//set condition array according to cat id and sub cat id						
							if(isset($cat_id_url))
							{
								$conditions['AdvertiserProfile.category LIKE'] = '%,'.$cat_id_url.',%';
							}												 
					// find all county	 
							 $countyList=$this->common->getAllCounty();
							 $this->set('countyList',$countyList);							 
					// find all state
							 $stateList=$this->common->getAllState();
							 $this->set('stateList',$stateList);							 
					// find all country	 
							 $countryList=$this->common->getAllCountry();
							 $this->set('countryList',$countryList);
					//manage the highlighted business for home page
						 App::import('model','AdvertiserProfile');
						 $this->AdvertiserProfile=new AdvertiserProfile();
					//to find the advertiser profile data
							 $map_address=$this->AdvertiserProfile->find('all',array(/*'fields'=>array('AdvertiserProfile.id','AdvertiserProfile.name','AdvertiserProfile.address','AdvertiserProfile.city','AdvertiserProfile.county','AdvertiserProfile.state','AdvertiserProfile.country','AdvertiserProfile.zip','AdvertiserProfile.logo','AdvertiserProfile.phoneno','AdvertiserProfile.company_name','AdvertiserProfile.page_url'),*/'conditions'=>$conditions,'order'=>array('TopTenBusiness.status,RAND()')));
					//find the small image of advertiser
					 $ss_offer='';
						for($m=0;$m<count($map_address);$m++)
						{
							$ss_offer_demo=$this->common->getmainSavingOfferImg_front_cat($map_address[$m]['AdvertiserProfile']['id']);
							//$ss_offer_demo['SavingOffer']['city']=$map_address[$m]['AdvertiserProfile']['city'];
							if(isset($ss_offer_demo) and !empty($ss_offer_demo))
							{
								$ss_offer[] = $ss_offer_demo;	
							}
							
						}
						/*---------------------sorting the resulted offer on city page------------------------*/
						
						if(isset($ss_offer) && !empty($ss_offer))
						{
							$ss_offer1='';
							$ss_offer2='';
							$ss_counter1=0;
							$ss_counter2=0;
							$ss_counter_later='';
							foreach($ss_offer as $ss_offer_old)
							{
								if($ss_offer_old['SavingOffer']['top_ten_status']==1)
								{
									$ss_offer1[$ss_counter1]=$ss_offer_old;
									$ss_counter1++;
								}
								else
								{
									$ss_offer2[$ss_counter2]=$ss_offer_old;
									$ss_counter2++;									
								}
							}
							
							//$ss_offer=array_merge($ss_offer1,$ss_offer2);
							/*-------manual array merging--------*/
							if(is_array($ss_offer1)) {
								for($arr_c=0;$arr_c<count($ss_offer1);$arr_c++)
								{
									$ss_offer[$arr_c]=$ss_offer1[$arr_c];
								}
							}	
								$ss_counter_later=count($ss_offer1);
								
								
								if(isset($ss_offer2) and !empty($ss_offer2))
								{
									foreach($ss_offer2 as $ss_offer2_later)
									{
										$ss_offer[$ss_counter_later]=$ss_offer2_later;
										$ss_counter_later++;
									}
								}
							
							/*------------------------------*/
						}
						
						/*-------------------------------------------------------------------*/
								 
							$high_business = $ss_offer;						
					
					//set the adv_id array
						$adv_id='';
						if(!empty($ss_offer))
						{
							for($n=0;$n<count($ss_offer);$n++){
								$adv_id[]=$ss_offer[$n]['SavingOffer']['advertiser_profile_id'];
							}
						}  
						//$this->set('',$map_address);
							$a = 0;
							$address1 = '';
					//to make the full map address of advertiser				
						if(!empty($adv_id))
						{
								 foreach($map_address as $address)
								 {									 	
									if(in_array($address['AdvertiserProfile']['id'],$adv_id))
									{		
										 $add = $address['AdvertiserProfile']['address'];
										 
										 $city= $cityList[$address['AdvertiserProfile']['city']];
										 
										 $county= $countyList[$address['AdvertiserProfile']['county']];
										 
										 $state= $stateList[$address['AdvertiserProfile']['state']];
										 
										 $country= $countryList[$address['AdvertiserProfile']['country']];
										 
										 $zip= $address['AdvertiserProfile']['zip'];
										 
										 $name= $address['AdvertiserProfile']['name'];
										 
										 $company_name= $address['AdvertiserProfile']['company_name'];
										 
										 $phone= $address['AdvertiserProfile']['phoneno'];
										 
										 $logo= $address['AdvertiserProfile']['logo'];
										 
										 $image = "<img src='".FULL_BASE_URL.router::url('/',false)."app/webroot/img/logo/".$logo."' width='60' height='40'/><br />";
										 
										 $address1[$a][]=$add.' '.$city.' '.$county.' '.$state.' '.$country;
										 
										 $address1[$a][]=$image.'<strong>'.ucwords(strtolower($company_name)).'</strong><br />Contact : '.ucwords(strtolower($name)).'<br/>'.$phone.'<br />'.$add.' '.$city.' '.$county.'<br />'.$state.' '.$country.' '.$zip;
										 $a++;
									 }
								 }
							   }
							$address = $address1;
							
	//-----------------------------------------------					
							
	$htmlData = '';	
	

	$county_url = $this->common->getCountyUrl($county_id);
	$state_url = $this->common->getStateUrl($county_id);
 if(!empty($high_business)){ 

$htmlData .= '<table border="0" cellspacing="0" cellpadding="0" style="margin:0; padding:0; font-size:0;width:457px; margin-left:10px;"><tr><td align="center"><h2 style="font: 400 18px \'OswaldRegular\', Arial, Helvetica, sans-serif;color: #920C08;text-transform: uppercase;margin:0;padding:0;">HIGHLIGHTED BUSINESSES IN <strong style="font: 600 18px \'OswaldRegular\';color: #920C08;">'.$this->common->getCountyName($county_id).' COUNTY</strong></h2></td></tr><tr><td style="border:7px solid #000000; border-bottom:none; width:443px;"><table border="0" cellspacing="0" cellpadding="0" style="margin:0; padding:0; font-size:0;width:443px;">';

				   for($c=0;$c<count($high_business);$c++){
				   if(!isset($adv_link[$c]))
				   		$adv_link[$c]='';
						 $hover_txt='';
						 $hover_txt_final='';
						 $hover_txt[]=explode('/', $adv_link[$c]);
						 $hover_txt_final=end($hover_txt[0]);
						 $hover_txt_final=ucwords(str_replace('-',' ',$hover_txt_final));
				//------set advertiser logo------
				  		$advLogo='';
				  		$advLogo=$this->common->getAdvertiserLogo($high_business[$c]['SavingOffer']['advertiser_profile_id']);
				  		$cat_name = array_values(explode(',',$high_business[$c]['SavingOffer']['category']));
						$subcat_name = array_values(explode(',',$high_business[$c]['SavingOffer']['subcategory']));
						$catname = $this->common->getcateurl($cat_name[0]);
						$subcat_name = $this->common->getsubcateurl($subcat_name[0]);
						$companyurl = $this->common->getcompanyurl($high_business[$c]['SavingOffer']['advertiser_profile_id']);

	if($c%2==0) {
		$htmlData .= '<tr><td style="margin:0; padding:0; font-size:0; border-bottom:7px #000000 solid;"><table border="0" cellspacing="0" cellpadding="0" style="margin:0; padding:0; font-size:0; width:443px;"><tr>';
	} else {
		$htmlData .= '<td style="background:#000000; width:7px; margin:0; padding:0;border-top:4px solid #000;border-bottom:4px solid #000;">&nbsp;</td>';
	}
	
	
	
	$htmlData .= '<td style="margin:0; padding:0; font-size:0; width:210px;border:4px solid #ebebeb;"><table border="0" cellspacing="0" cellpadding="0" style="margin:0; padding:0; font-size:0; "><tr><td style="width:134px;">';
	
	
	
	
	
	
    
 if($advLogo!='' || file_exists(FULL_BASE_URL.router::url('/',false).'app/webroot/img/logo/'.$advLogo)) {

$htmlData .= '<img src="'.FULL_BASE_URL.router::url('/',false).'image_resizer.php?img='.WWW_ROOT.'img/logo/'.$advLogo.'&newWidth=134&newHeight=76" alt="Zuni_coupon" style="border:0" />';
                          }
                          else
                          {
$htmlData .= '<img src="'.FULL_BASE_URL.router::url('/',false).'image_resizer.php?img='.WWW_ROOT.'img/no_images207.jpg&newWidth=134&newHeight=76" alt="Zuni_default_coupon" style="border:0" />';

                 }
				 
$htmlData .= '</td><td style="background:#ebebeb; width:4px;">&nbsp;</td><td width="70" style="border: 1px dashed black;background: url('.FULL_BASE_URL.router::url('/',false).'img/front/yellow_bg.jpg) bottom repeat-x #F9DE2A;" ><p style="font: bold 14px Arial, Helvetica, sans-serif;color: #801113; line-height:25px; margin:0; padding:0;text-align: center;">';
				 
if($high_business[$c]['SavingOffer']['off_unit']==1) 
									$htmlData .= '$';
								if($high_business[$c]['SavingOffer']['off_unit']==2) 
									$htmlData .= $high_business[$c]['SavingOffer']['off_text'];

								if($high_business[$c]['SavingOffer']['off_unit']!=2) {
								if(isset($high_business[$c]['SavingOffer']['off']) && $high_business[$c]['SavingOffer']['off']!='') 
                                    {
                                       $htmlData .= $high_business[$c]['SavingOffer']['off'];
                                    } else {
                                        $htmlData .= '0';
                                    }
								}	

								if($high_business[$c]['SavingOffer']['off_unit']==0)
									$htmlData .= '%';			 


			$htmlData .= '</p><p style="text-align: center;margin:0; padding:0;"><span style="font: bold 11px Arial, Helvetica, sans-serif;color: #801113;line-height: 20px;">';
	
	 if($high_business[$c]['SavingOffer']['off_unit']!=2) {
                      $htmlData .= 'OFF';
                      }
					  
					  
			$htmlData .= '<p style="text-align: center;margin:0; padding:0;"> <a href="'.FULL_BASE_URL.router::url('/',false).'state/'.$state_url.'/'.$county_url.'/'.$catname.'/'.$subcat_name.'/'.$companyurl.'" title="'.$companyurl.'" target="_blank" style="font: bold 10px/10px Arial, Helvetica, sans-serif;color: #3F3F3F;text-decoration: none;">Click Here</a></p></td></tr></table></td>';
			



			if($c%2!=0) {
					$htmlData .= '</tr></table></td></tr>';
				}
		
	}
	
	if(count($high_business)%2==1) {
		$htmlData .= '<td style="background:#000000; width:7px; margin:0; padding:0;border-top:4px solid #000;border-bottom:4px solid #000;">&nbsp;</td><td style="margin:0; padding:0; font-size:0; width:210px;border:4px solid #ebebeb;">&nbsp;</td></tr></table></td></tr>';
	}

	$htmlData .= '</table></td></tr></table>';

	
	return str_replace('[SAVING_OFFER]',$htmlData,$data['Newsletter']['email_content']);
 } else {
	$empty = '';
	return $empty;
}
}			
/*--------------this function is checking username and pasword in database and if true then redirect to home page--------------*/

	function beforeFilter() { 
	 
            	 	$this->Auth->fields = array(
             		'username' => 'username', 
            	 	'password' => 'password'
           	 		);
					$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
					$this->Auth->allow('unsubscribe');
			
   	    }	 
/*----------This function is setting all info about current SuperAdmins in currentAdmin array so we can use it anywhere lie name id etc.------------------------------*/


function beforeRender(){
	 
		    $this->set('currentAdmin', $this->Auth->user());
			$this->set('cssName',$this->Cookie->read('css_name'));
			$this->set('groupDetail',$this->common->adminDetails());
			$this->set('common',$this->common);
			//$this->Ssl->force();

	  } 
}//end class