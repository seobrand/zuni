<?php 
/******************************************************************************************
 Coder  : Keshav Sharma
 Object : Controller to handle NewsManagers
******************************************************************************************/
class NewsManagersController extends AppController {
	var $name = 'NewsManagers'; //Model name attached with this controller 
	 var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar'); 
	 var $components = array('Auth','common','Session','Cookie','RequestHandler','Email','emailhtml');
	 var $layout = 'admin';
	
/***-----------------------This function is the Index function i.e. call by default-------------------------------------------------------------------------------*/
	function index(){ 
		$this->set('title_for_layout','NewsManager Manager');
		$this->set('search_text','Title');
		$this->set('Countys', $this->common->getAllCounty());
		$this->set('county_id','');
		$this->set('status','');		
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('NewsManager.id' => 'asc'));
		
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		//if title is set	 		 
		if(isset($this->data['NewsManagers']['search_text']) and $this->data['NewsManagers']['search_text'] != ''){
			$search_text = $this->data['NewsManagers']['search_text'];			
			$this->set('search_text',$search_text); 
		} else if(isset($this->params['named']['search_text']) && $this->params['named']['search_text'] != '') {
			$search_text = $this->params['named']['search_text'];			
			$this->set('search_text',$search_text); 
		}
		
		if(isset($this->data['NewsManagers']['county_id']) and $this->data['NewsManagers']['county_id'] != ''){
			$county_id = $this->data['NewsManagers']['county_id'];			
			$this->set('county_id',$county_id); 
		} else if(isset($this->params['named']['county_id']) && $this->params['named']['county_id']) {
			$county_id = $this->params['named']['county_id'];
			$this->set('county_id',$county_id); 
		}
		
		if(isset($this->data['NewsManagers']['status']) and $this->data['NewsManagers']['status'] != ''){
			$status = $this->data['NewsManagers']['status'];			
			$this->set('status',$status); 
		} else if(isset($this->params['named']['status']) && $this->params['named']['status']) {
			$status = $this->params['named']['status'];
			$this->set('status',$status); 
		}	 
	/*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		if(isset($search_text) && $search_text !='' && $search_text!='Title'){
		 	$cond['NewsManager.header LIKE'] = '%'.$search_text. '%';
		}
		if(isset($county_id) && $county_id !=''){
		 	$cond['NewsManager.county_id'] = $county_id;
		}
		if(isset($status) && $status !=''){
		 	$cond['NewsManager.status'] = $status;
		}		
		$data = $this->paginate('NewsManager', $cond);
		$this->set('NewsManagers', $data);		
	}	
/***-----------------------This function Add new daily deal in database------------------------------------------------------------------------------------------*/
	function add(){
		$this->set('title_for_layout','Add News');		
		if(isset($this->data))
				{
				
				  $this->NewsManager->set($this->data['NewsManager']);
				  if($this->NewsManager->validates())
				  { 
		/*-------------------------------Template image upload-------------------------------------------------------------*/
					    
						if($this->data['NewsManager']['image']['name']!='')
						{
							$type = explode(".",$this->data['NewsManager']['image']['name']);
							
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{                           
								$this->data['NewsManager']['image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['NewsManager']['image']['name']);
								$docDestination = APP.'webroot/img/NewsManager/'.$this->data['NewsManager']['image']['name']; 
								
								@chmod(APP.'webroot/img/NewsManager',0777);
								
								move_uploaded_file($this->data['NewsManager']['image']['tmp_name'], $docDestination) or die($docDestination);
								
								$this->data['NewsManager']['image1'] = $this->data['NewsManager']['image']['name'];
								
							}
							else
							{
								$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
							}	
						}
						else
						{
							$this->data['NewsManager']['image1'] = '';
						}					
						/*---------------------------------------------------------------------------------*/
						
						
						/*---------------------------------------------------------------------------------*/						
						$this->NewsManager->save($this->data);
                                                //pr($this->data);exit;
					/*----------------------------------------------------------------------------------------------------------*/						
						$this->Session->setFlash('NewsManager Successfully Saved'); 
						$this->redirect(array('action' => 'index'));
				  }
				  else
				  {				
						$errors = $this->NewsManager->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
	}
/***----------------------This function Edit Existing daily deal in database------------------------------------------------------------------------------------*/
	function edit($id=null){	
	if(isset($id) || isset($this->data['NewsManager']['id'])) {
		$this->set('title_for_layout','Add NewsManager');	
		$this->set('Countys', $this->common->getAllCounty());
		if(isset($this->data))
				{
				  $this->data['NewsManager']['s_date'] = strtotime($this->data['NewsManager']['s_date']);
				  $this->data['NewsManager']['e_date'] = strtotime($this->data['NewsManager']['e_date']);
				  $this->NewsManager->set($this->data['NewsManager']);
				  if($this->NewsManager->validates())
				  {				  
		/*------------------------------- Template image upload-------------------------------------------------------------*/					    
						if($this->data['NewsManager']['temp_image']['name']!='')
						{
							$type = explode(".",$this->data['NewsManager']['temp_image']['name']);
							
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{        
								unlink(APP.'webroot/img/NewsManager/'.$this->data['NewsManager']['image']);                   
								$this->data['NewsManager']['temp_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['NewsManager']['temp_image']['name']);
								$docDestination = APP.'webroot/img/NewsManager/'.$this->data['NewsManager']['temp_image']['name']; 
								
								@chmod(APP.'webroot/img/NewsManager',0777);
								
								move_uploaded_file($this->data['NewsManager']['temp_image']['tmp_name'], $docDestination) or die($docDestination);
								
								$this->data['NewsManager']['image'] = $this->data['NewsManager']['temp_image']['name'];								
							}
							else
							{
								$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
							}	
						}				
						/*---------------------------------------------------------------------------------*/
		/*------------------------------- Background image upload -----------------------------------------*/
					    
						if($this->data['NewsManager']['banner_image']['name']!='')
						{
							$type = explode(".",$this->data['NewsManager']['banner_image']['name']);
							
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{        
								unlink(APP.'webroot/img/NewsManager/'.$this->data['NewsManager']['background']);                   
								$this->data['NewsManager']['banner_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['NewsManager']['banner_image']['name']);
								$docDestination = APP.'webroot/img/NewsManager/'.$this->data['NewsManager']['banner_image']['name'];
								
								@chmod(APP.'webroot/img/NewsManager',0777);
								
								move_uploaded_file($this->data['NewsManager']['banner_image']['tmp_name'], $docDestination) or die($docDestination);
								
								$this->data['NewsManager']['background'] = $this->data['NewsManager']['banner_image']['name'];
								
							}
							else
							{
								$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
							}	
						}				
						/*---------------------------------------------------------------------------------*/
						
						$this->NewsManager->save($this->data);
						
					/*----------------------------------------------------------------------------------------------------------*/						
						$this->Session->setFlash('NewsManager Successfully Saved'); 
						$this->redirect(array('action' => 'index'));		  
				  }
				  else
				  {
				
						$errors = $this->NewsManager->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			} else {
					$this->NewsManager->id = $id;
					$this->data = $this->NewsManager->read();					
			}
		} else {						
						$this->redirect(array('action' => 'index'));
		}
	}
			
/***--------------------------------------------------This function Delete the Daily Deal from database---------------------------------------------------------------*/
	function delete($id=null){
	
					$banner_image = $this->NewsManager->query("SELECT background,image FROM NewsManagers WHERE id ='".$id."'"); 
					
					@unlink(APP.'webroot/img/NewsManager/'.$banner_image[0]['NewsManagers']['background']);
					
					@unlink(APP.'webroot/img/NewsManager/'.$banner_image[0]['NewsManagers']['image']);
					
					$this->NewsManager->delete($id);
					
					$this->Session->setFlash('The NewsManager with id:  '.$id.' has been Deleted Successfully!!');
							
					 $this->redirect(array('action'=>'index'));					 
	}	
/***--------------------------------------------------This function Delete the Daily Deal from database---------------------------------------------------------------*/
	function NewsManager_user($id=NULL) {
		$this->layout = 'admin';
		$this->loadModel('NewsManagerUser');
		$cond['NewsManagerUser.NewsManager_id'] = $id;
		
		$this->set('check_winner','');
		$this->set('title_for_layout','NewsManager User Manager');
		$this->set('search_text','Name');
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('NewsManagerUser.id' => 'asc'));
		
		$check_winner = $this->NewsManagerUser->find('count',array('conditions'=>array('NewsManagerUser.NewsManager_id'=>$id,'NewsManagerUser.winner'=>1)));
		if($check_winner!=0) {
			$this->set('check_winner',"1");
		}
		
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		//if title is set	 		 
		if(isset($this->data['NewsManager']['search_text']) and $this->data['NewsManager']['search_text'] != ''){
			$search_text = $this->data['NewsManager']['search_text'];			
			$this->set('search_text',$search_text); 
		} else if(isset($this->params['named']['search_text']) && $this->params['named']['search_text'] != '') {
			$search_text = $this->params['named']['search_text'];			
			$this->set('search_text',$search_text);
		}			 
	/*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		if(isset($search_text) && $search_text !='' && $search_text!='Name'){
		 	$cond['NewsManagerUser.fname LIKE'] = '%'.$search_text. '%';
		}	
		$data = $this->paginate('NewsManagerUser', $cond);
		$this->set('NewsManagers', $data);
		$this->set('id',$id);
	}
/***-------------------------this function is checking username and password in database and if true then redirect to home page----------------------------------*/	
	function view_NewsManager_user($id=NULL) {
		$this->layout = 'admin';
		$this->loadModel('NewsManagerUser');
		$this->NewsManagerUser->id = $id;
		$data = $this->NewsManagerUser->read();
		$this->set('data',$data);
		//pr($data);
	}	
/***-------------------------this function is checking username and password in database and if true then redirect to home page----------------------------------*/	
	function delete_NewsManager_user($id=NULL) {	
		$this->loadModel('NewsManagerUser');
		$this->NewsManagerUser->id = $id;
		$attached_file = $this->NewsManagerUser->field('NewsManagerUser.attachment');
		@chmod(WWW_ROOT.'img/NewsManager/userdata',0777);
		if($attached_file!='') {
			@unlink(WWW_ROOT.'img/NewsManager/userdata/'.$attached_file);
		}
		$this->NewsManagerUser->delete();
		$this->Session->setFlash('User successfully deleted.');
		$this->redirect($this->referer());
	}
/***-------------------------this function is checking username and password in database and if true then redirect to home page----------------------------------*/		
	function download($filename){ 
		$this->downloadFile('img/NewsManager/userdata',$filename);
	}	
	/* Download function path if folder any folder in img path */
	function downloadFile($folder,$fielname)	{
		$this->autoLayout = false;
		$newFileName = $fielname;
		$folder = str_replace('-','/',$folder);
		//Replace - to / to view subfolder
	    $path =  WWW_ROOT.$folder.'/'.$fielname;
		if(file_exists($path) && is_file($path)) {	
			$mimeContentType = 'application/octet-stream';
			$temMimeContentType = $this->_getMimeType($path); 
			if(isset($temMimeContentType)  && !empty($temMimeContentType))	{ 
							$mimeContentType = $temMimeContentType;
			}
		    //echo  'sssssssssss--->' . $mimeContentType;		 exit;
			// START ANDR SILVA DOWNLOAD CODE
			// required for IE, otherwise Content-disposition is ignored
			if(ini_get('zlib.output_compression'))
			  	ini_set('zlib.output_compression', 'Off');
			header("Pragma: public"); // required
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false); // required for certain browsers 
			header("Content-Type: " . $mimeContentType );
			// change, added quotes to allow spaces in filenames, by Rajkumar Singh
			header("Content-Disposition: attachment; filename=\"".(is_null($newFileName)?basename($path):$newFileName)."\";" );
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($path));
			readfile($path);
			exit();
			// END ANDR SILVA DOWNLOAD CODE												
		 }
		 if(isset($_SERVER['HTTP_REFERER'])) {
		 	 $this->Session->setFlash('File not found.');
			 $this->redirect($_SERVER['HTTP_REFERER']);
		 }	 
 	}	
	function _getMimeType($filepath) {
		ob_start();
		system("file -i -b {$filepath}");
		$output = ob_get_clean();
		$output = explode("; ",$output);
		if ( is_array($output) ) {
			$output = $output[0];
		}
		return $output;
	}		
/***-----------------------This function save the winner for daily discount-------------------------------------------------------------------------------------*/	
	function save_winner() {
		$this->autoRender = false;
		$winner_id = '';
		if(isset($this->data)) {
			foreach ($this->data['NewsManager'] as $key=>$value) {
				if($value==1) {
					$winner_id = $key;
					break;
				}
			}
			$this->loadModel('NewsManagerUser');
			$savarr = '';
			$savarr['NewsManagerUser']['id'] 	= $winner_id;
			$savarr['NewsManagerUser']['winner']= 1;
			$this->NewsManagerUser->save($savarr);
			
			//get name & email
			$user = $this->NewsManagerUser->find('first',array('fields'=>array('NewsManagerUser.fname','NewsManagerUser.email','NewsManagerUser.NewsManager_id'),'conditions'=>array('NewsManagerUser.id'=>$winner_id)));
			
			$this->loadModel('Setting');
			$setting = $this->Setting->find('first',array('fields'=>array('Setting.winner_subject','Setting.winner_body')));
			$subject = $setting['Setting']['winner_subject'];
			$content = $setting['Setting']['winner_body'];		
			
			$this->loadModel('NewsManager');
			$NewsManager = $this->NewsManager->find('first',array('fields'=>array('NewsManager.first_title'),'conditions'=>array('NewsManager.id'=>$user['NewsManagerUser']['NewsManager_id'])));
			
			// Mail to winner User
			$arrayTags 		= array("[name]","[NewsManager]");
			$arrayReplace 	= array($user['NewsManagerUser']['fname'],$NewsManager['NewsManager']['first_title']);
			$subject 		= str_replace($arrayTags,$arrayReplace,$setting['Setting']['winner_subject']);
			$content 		= str_replace($arrayTags,$arrayReplace,$setting['Setting']['winner_body']);
			
			$this->Email->to 		= $user['NewsManagerUser']['email'];
			$this->Email->subject 	= strip_tags($subject);
			$this->Email->replyTo 	= $this->common->getReturnEmail();
			$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
			$this->Email->sendAs 	= 'html';
			//Set the body of the mail as we send it.
			//seperate line in the message body.
			
			$this->body = '';				
			$this->body = $this->emailhtml->email_header();
			$this->body .=$content;
			$this->body .= $this->emailhtml->email_footer();

			$this->Email->send($this->body);
			
			///////////////////////////sent mail insert to sent box ///////////////////			

			//$this->common->sentMailLog($this->common->getSalesEmail(),$user['NewsManagerUser']['email'],strip_tags($subject),$this->body,"winner_mail");
			////////////////////////////////////////////////////////////////////////////
			
			$this->Email->reset();
			$this->Session->setFlash('NewsManager winner has been selected successfully.');
			$this->redirect($this->referer());
		}
	}							
/***-------------------------this function is checking username and password in database and if true then redirect to home page----------------------------------*/		
	function beforeFilter() {
		  $this->Auth->fields = array(
			   'username' => 'username', 
			   'password' => 'password'
		   );
		  $this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');   	
	}	
/***---------------------- This function is setting all info about current Admins in currentAdmin array so we can use it anywhere lie name id etc.------------------*/
	 function beforeRender() {
		    $this->set('currentAdmin', $this->Auth->user());
			$this->set('cssName',$this->Cookie->read('css_name'));
			$this->set('groupDetail',$this->common->adminDetails());
			$this->set('common',$this->common);
			//$this->Ssl->force();
	  }
}		
?>