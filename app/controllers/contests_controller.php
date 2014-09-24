<?php 
/******************************************************************************************
 Coder  : Keshav Sharma
 Object : Controller to handle Contests
******************************************************************************************/
class contestsController extends AppController {
	var $name = 'Contests'; //Model name attached with this controller 
	 var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar'); 
	 var $components = array('Auth','common','Session','Cookie','RequestHandler','Email','emailhtml');
	 var $layout = 'admin';
	
/***-----------------------This function is the Index function i.e. call by default-------------------------------------------------------------------------------*/
	function index(){
		$this->set('title_for_layout','Contest Manager');
		$this->set('search_text','Title');
		$this->set('Countys', $this->common->getAllCounty());
		$this->set('county_id','');
		$this->set('status','');
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('Contest.id' => 'DESC'));
		
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		//if title is set
		if(isset($this->data['contests']['search_text']) and $this->data['contests']['search_text'] != ''){
			$search_text = $this->data['contests']['search_text'];
			$this->set('search_text',$search_text);
		} else if(isset($this->params['named']['search_text']) && $this->params['named']['search_text'] != '') {
			$search_text = $this->params['named']['search_text'];
			$this->set('search_text',$search_text);
		}
		if(isset($this->data['contests']['county_id']) and $this->data['contests']['county_id'] != ''){
			$county_id = $this->data['contests']['county_id'];			
			$this->set('county_id',$county_id); 
		} else if(isset($this->params['named']['county_id']) && $this->params['named']['county_id']) {
			$county_id = $this->params['named']['county_id'];
			$this->set('county_id',$county_id); 
		}
		
		if(isset($this->data['contests']['status']) and $this->data['contests']['status'] != ''){
			$status = $this->data['contests']['status'];			
			$this->set('status',$status); 
		} else if(isset($this->params['named']['status']) && $this->params['named']['status']) {
			$status = $this->params['named']['status'];
			$this->set('status',$status); 
		}	 
	/*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		if(isset($search_text) && $search_text !='' && $search_text!='Title'){
		 	$cond['Contest.header LIKE'] = '%'.$search_text. '%';
		}
		if(isset($county_id) && $county_id !=''){
		 	$cond['Contest.county_id'] = $county_id;
		}
		if(isset($status) && $status !=''){
		 	$cond['Contest.status'] = $status;
		}		
		$data = $this->paginate('Contest', $cond);
		$this->set('contests', $data);		
	}	
/***-----------------------This function Add new daily deal in database------------------------------------------------------------------------------------------*/
	function add() {
		$this->set('title_for_layout','Add Contest');
		$this->set('Countys', $this->common->getAllCounty());
		if(isset($this->data))
				{
				if(isset($this->data['Contest']['s_date']) && $this->data['Contest']['s_date'] != '') {
					$this->data['Contest']['s_date'] = strtotime($this->data['Contest']['s_date']);
				}
				if(isset($this->data['Contest']['e_date']) && $this->data['Contest']['e_date'] != '') {
					$this->data['Contest']['e_date'] = strtotime($this->data['Contest']['e_date']);
				}
				  $this->Contest->set($this->data['Contest']);
				  if($this->Contest->validates())
				  {	
						/*-------------------------------Background image upload-------------------------------------------------------------*/
					    
						if($this->data['Contest']['banner_image']['name']!='')
						{
							$type = explode(".",$this->data['Contest']['banner_image']['name']);
							
							if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
							{                           
								$this->data['Contest']['banner_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['Contest']['banner_image']['name']);
								$docDestination = APP.'webroot/img/Contest/'.$this->data['Contest']['banner_image']['name']; 
								
								@chmod(APP.'webroot/img/Contest',0777);
								
								move_uploaded_file($this->data['Contest']['banner_image']['tmp_name'], $docDestination) or die($docDestination);
								
								$this->data['Contest']['background'] = $this->data['Contest']['banner_image']['name'];
								
							}
							else
							{
								$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
							}	
						}
						else
						{
							$this->data['Contest']['background'] = 'default.png';
						}
						/*---------------------------------------------------------------------------------*/						
						$this->Contest->save($this->data);
					/*----------------------------------------------------------------------------------------------------------*/						
						$this->Session->setFlash('Contest Successfully Saved'); 
						$this->redirect(array('action' => 'index'));
				  }
				  else
				  {				
						$errors = $this->Contest->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
	}
/***----------------------This function Edit Existing daily deal in database------------------------------------------------------------------------------------*/
	function edit($id=null){	
	if(isset($id) || isset($this->data['Contest']['id'])) {
		$this->set('title_for_layout','Add Contest');	
		$this->set('Countys', $this->common->getAllCounty());
		if(isset($this->data))
				{
				  $this->data['Contest']['s_date'] = strtotime($this->data['Contest']['s_date']);
				  $this->data['Contest']['e_date'] = strtotime($this->data['Contest']['e_date']);
				  $this->Contest->set($this->data['Contest']);
				  if($this->Contest->validates())
				  {
		/*------------------------------- Background image upload -----------------------------------------*/
					    if($this->data['Contest']['removeLogo']==1) {
							@unlink(APP.'webroot/img/Contest/'.$this->data['Contest']['background']);
							$this->data['Contest']['background'] = '';
						}
						if(!$this->data['Contest']['banner_image']['error']) {
						
							$type = $this->data['Contest']['banner_image']['type'];
							
							if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif")
							{						
								@unlink(APP.'webroot/img/Contest/'.$this->data['Contest']['background']);
								$this->data['Contest']['banner_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['Contest']['banner_image']['name']);
								$docDestination = APP.'webroot/img/Contest/'.$this->data['Contest']['banner_image']['name'];
								
								@chmod(APP.'webroot/img/Contest',0777);
								
								move_uploaded_file($this->data['Contest']['banner_image']['tmp_name'], $docDestination) or die($docDestination);
								
								$this->data['Contest']['background'] = $this->data['Contest']['banner_image']['name'];
								
							}
							else
							{
								$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.');
								return false;
							}
						}
						/*---------------------------------------------------------------------------------*/
						
						$this->Contest->save($this->data);
						
					/*----------------------------------------------------------------------------------------------------------*/						
						$this->Session->setFlash('Contest Successfully Saved'); 
						$this->redirect(array('action' => 'index'));		  
				  }
				  else
				  {
						$errors = $this->Contest->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }
			} else {
					$this->Contest->id = $id;
					$this->data = $this->Contest->read();					
			}
		} else {
						$this->redirect(array('action' => 'index'));
		}
	}
			
/***--------------------------------------------------This function Delete the Daily Deal from database---------------------------------------------------------------*/
	function delete($id=null){
	
					$banner_image = $this->Contest->query("SELECT background,image FROM contests WHERE id ='".$id."'"); 
					
					if($banner_image[0]['contests']['background']!='default.png') {
						@unlink(APP.'webroot/img/Contest/'.$banner_image[0]['contests']['background']);
					}
					
					@unlink(APP.'webroot/img/Contest/'.$banner_image[0]['contests']['image']);
					
					$this->Contest->delete($id);
					
					$this->Session->setFlash('The Contest with id:  '.$id.' has been Deleted Successfully!!');
							
					 $this->redirect(array('action'=>'index'));					 
	}	
/***--------------------------------------------------This function Delete the Daily Deal from database---------------------------------------------------------------*/
	function contest_user($id=NULL) {
		$this->layout = 'admin';
		$this->loadModel('ContestUser');
		$cond['ContestUser.contest_id'] = $id;
		
		$this->set('check_winner','');
		$this->set('title_for_layout','Contest User Manager');
		$this->set('search_text','Name');
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('ContestUser.id' => 'DESC'));
		
		$check_winner = $this->ContestUser->find('count',array('conditions'=>array('ContestUser.contest_id'=>$id,'ContestUser.winner'=>1)));
		if($check_winner!=0) {
			$this->set('check_winner',"1");
		}
		
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		//if title is set	 		 
		if(isset($this->data['Contest']['search_text']) and $this->data['Contest']['search_text'] != ''){
			$search_text = $this->data['Contest']['search_text'];			
			$this->set('search_text',$search_text); 
		} else if(isset($this->params['named']['search_text']) && $this->params['named']['search_text'] != '') {
			$search_text = $this->params['named']['search_text'];			
			$this->set('search_text',$search_text);
		}			 
	/*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		if(isset($search_text) && $search_text !='' && $search_text!='Name'){
		 	$cond['FrontUser.name LIKE'] = '%'.trim($search_text). '%';
		}	
		$data = $this->paginate('ContestUser', $cond);
		$this->set('contests', $data);
		$this->set('id',$id);
	}
/***-------------------------this function is checking username and password in database and if true then redirect to home page----------------------------------*/	
	function view_contest_user($id=NULL) {
		$this->layout = 'admin';
		$this->loadModel('ContestUser');
		$this->ContestUser->id = $id;
		$data = $this->ContestUser->read();
		$this->set('data',$data);
	}	
/***-------------------------this function is checking username and password in database and if true then redirect to home page----------------------------------*/	
	function delete_contest_user($id=NULL) {	
		$this->loadModel('ContestUser');
		$this->ContestUser->id = $id;
		$attached_file = $this->ContestUser->field('ContestUser.attachment');
		@chmod(WWW_ROOT.'img/Contest/userdata',0777);
		if($attached_file!='') {
			@unlink(WWW_ROOT.'img/Contest/userdata/'.$attached_file);
		}
		$this->ContestUser->delete();
		$this->Session->setFlash('User successfully deleted.');
		$this->redirect($this->referer());
	}
/***-------------------------this function is checking username and password in database and if true then redirect to home page----------------------------------*/		
	function download($filename){ 
		$this->downloadFile('img/Contest/userdata',$filename);
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
/***-----------------------This function save the winner for contest-------------------------------------------------------------------------------------*/	
	function save_winner() {
		$this->autoRender = false;
		$winner_id = '';
		if(isset($this->data)) {
			foreach ($this->data['Contest'] as $key=>$value) {
				if($value==1) {
					$winner_id = $key;
					break;
				}
			}
			$this->loadModel('ContestUser');
			$savarr = '';
			$savarr['ContestUser']['id'] 	= $winner_id;
			$savarr['ContestUser']['winner']= 1;
			$this->ContestUser->save($savarr);
			
			//get name & email
			$user = $this->ContestUser->find('first',array('fields'=>array('FrontUser.name','FrontUser.email','ContestUser.contest_id'),'conditions'=>array('ContestUser.id'=>$winner_id)));
			
			$this->loadModel('Setting');
			$setting = $this->Setting->find('first',array('fields'=>array('Setting.winner_subject','Setting.winner_body')));
			$subject = $setting['Setting']['winner_subject'];
			$content = $setting['Setting']['winner_body'];		
			
			$this->loadModel('Contest');
			$contest = $this->Contest->find('first',array('fields'=>array('Contest.header'),'conditions'=>array('Contest.id'=>$user['ContestUser']['contest_id'])));
			
			// Mail to winner User
			$arrayTags 		= array("[name]","[contest]");
			$arrayReplace 	= array($user['FrontUser']['name'],$contest['Contest']['header']);
			$subject 		= str_replace($arrayTags,$arrayReplace,$setting['Setting']['winner_subject']);
			$content 		= str_replace($arrayTags,$arrayReplace,$setting['Setting']['winner_body']);
			
			$this->Email->to 		= $user['FrontUser']['email'];
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
				$this->common->sentMailLog($this->common->getSalesEmail(),$user['FrontUser']['email'],strip_tags($subject),$this->body,"winner_mail");
			/////////////////////////////////////////////////////////////////////////
	
			$this->Email->reset();
			$this->Session->setFlash('Contest winner has been selected successfully.');
			$this->redirect($this->referer());
		}
	}
/***-------------------------Function to check limit for an user to play a contest----------------------------------*/		
	function check_limit($contest='') {
		$this->autoRender = false;
		
		if($this->Session->read('Auth.FrontConsumer.id')) {
			if($contest!='') {
				$contest_id = base64_decode($contest);
				$limit = $this->Contest->find('first',array('fields'=>array('Contest.user_limit'),'conditions'=>array('Contest.id'=>$contest_id)));
				if($limit['Contest']['user_limit']) {
					$this->loadModel('ContestUser');
					$userCount = $this->ContestUser->find('count',array('conditions'=>array('ContestUser.contest_id'=>$contest_id,'ContestUser.front_user_id'=>$this->Session->read('Auth.FrontConsumer.id'))));
					if($userCount>=$limit['Contest']['user_limit']) {
						echo 'You have already entered this contest the maximum times allowed. Please watch for the next contest.';
					} else {
						echo 'ok';
					}
				} else {
					echo 'ok';
				}
			} else {
				echo 'No contest available. Please try later.';
			}
		} else {
			echo 'You are not able to play this contest. Please login first.';
		}
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
/***-------------------------this function is checking username and password in database and if true then redirect to home page----------------------------------*/		
	function beforeFilter() {
		  $this->Auth->fields = array(
			   'username' => 'username', 
			   'password' => 'password'
		   );
		  $this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
		  $this->Auth->allow('check_limit');
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