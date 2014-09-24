<?php
/*
   Coder: Manoj Pandit
   Date  : 03 Jan 2013
*/

class ImageLibrariesController extends AppController {
	var $name = 'ImageLibraries'; //Model name attached with this controller
	 var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar');
	 var $components = array('Auth','common','Session','Cookie','RequestHandler','Email','emailhtml');
	 var $layout = 'admin';
	
/***-----------------------This function is the Index function i.e. call by default-------------------------------------------------------------------------------*/
	function index(){
		$this->set('title_for_layout','Image Library Manager');
		$this->set('search_text','Title');
		$this->set('advertiser_profile_id','Advertiser');
		$this->set('advertiserList',$this->common->getWholeAdvertiserProfileList()); //  List advertisers
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('ImageLibrary.id' => 'DESC'));
		/*if(isset($this->data)) {
			pr($this->data);
			exit;
		}*/
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		//if title is set
		if(isset($this->data['image_libraries']['search_text']) and $this->data['image_libraries']['search_text'] != ''){
			$search_text = $this->data['image_libraries']['search_text'];
			$this->set('search_text',$search_text);
		} else if(isset($this->params['named']['search_text']) && $this->params['named']['search_text'] != '') {
			$search_text = $this->params['named']['search_text'];
			$this->set('search_text',$search_text);
		}
		
		//if title is set
		if(isset($this->data['image_libraries']['advertiser_profile_id']) and $this->data['image_libraries']['advertiser_profile_id'] != ''){
			$advertiser_profile_id = $this->data['image_libraries']['advertiser_profile_id'];
			$this->set('advertiser_profile_id',$advertiser_profile_id);
		} else if(isset($this->params['named']['advertiser_profile_id']) && $this->params['named']['advertiser_profile_id'] != '') {
			$advertiser_profile_id = $this->params['named']['advertiser_profile_id'];
			$this->set('advertiser_profile_id',$advertiser_profile_id);
		}
	/*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		if(isset($search_text) && $search_text !='' && $search_text!='Title'){
		 	$cond['ImageLibrary.title LIKE'] = '%'.$search_text. '%';
		}
		if(isset($advertiser_profile_id) && $advertiser_profile_id !='' && $advertiser_profile_id!='Advertiser'){
		 	$cond['ImageLibrary.advertiser_profile_id'] = $advertiser_profile_id;
		}
		/*pr($cond);
		exit;*/
		$data = $this->paginate('ImageLibrary', $cond);
		$this->set('libraries', $data);
	}
/***-----------------------This function Add new daily deal in database----------------------------------------------------------*/
	function add() {
		$this->set('title_for_layout','Add new image in library');
		$this->set('advertiserList',$this->common->getWholeAdvertiserProfileList()); //  List Advertisers
		if(isset($this->data))
				{
				  $this->ImageLibrary->set($this->data['ImageLibrary']);
				  if($this->ImageLibrary->validates())
				  {
					/*-------------------------------image library upload-------------------------------------------------------------*/
						
						$this->data['ImageLibrary']['image_url']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['ImageLibrary']['image_url']['name']);
						$docDestination = APP.'webroot/img/image_library/'.$this->data['ImageLibrary']['image_url']['name'];
						
						@chmod(APP.'webroot/img/image_library',0777);
						
						move_uploaded_file($this->data['ImageLibrary']['image_url']['tmp_name'], $docDestination) or die($docDestination);
						
						$this->data['ImageLibrary']['image_url'] = $this->data['ImageLibrary']['image_url']['name'];
						
						$this->ImageLibrary->save($this->data,false);
				/*----------------------------------------------------------------------------------------------------------*/
					App::import('model', 'WorkOrder');
					  $this->WorkOrder = new WorkOrder;
					  $saveWorkArray = array();
					  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $this->common->getonlyOrderId($this->data['ImageLibrary']['advertiser_profile_id']);
					  $saveWorkArray['WorkOrder']['read_status']   				=  0;
					  $saveWorkArray['WorkOrder']['subject']   					=  'New Image uploaded';
					  $saveWorkArray['WorkOrder']['message']   					=  'A new image has been uploaded for "'.$this->common->getCompanyName($this->data['ImageLibrary']['advertiser_profile_id']).'" recently by "'.$this->common->groupName($this->Session->read('Auth.Admin.user_group_id')).' team (Name : '.$this->Session->read('Auth.Admin.name').')".';
					  
					  $saveWorkArray['WorkOrder']['type']   					=  'imageuploaded';
					  $saveWorkArray['WorkOrder']['sent_to']   					=  1;
					  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
					  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
					  $saveWorkArray['WorkOrder']['bottom_line']				=  'For more details, click on this link : <a href="'.FULL_BASE_URL.router::url('/',false).'image_libraries/view/'.$this->ImageLibrary->getLastInsertId().'">Details</a>';
					  date_default_timezone_set('US/Eastern');
					  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
					  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
					  $saveWorkArray['WorkOrder']['salseperson_id'] 			=  0;
					  $this->WorkOrder->save($saveWorkArray);
				/*----------------------------------------------------------------------------------------------------------*/
					  					
						$this->Session->setFlash('Image is successfully saved in library'); 
						$this->redirect(array('action' => 'index'));
				  }
				  else
				  {				
						$errors = $this->ImageLibrary->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
	}
/***----------------------This function Edit Existing daily deal in database------------------------------------------------------------------------------------*/
	function edit($id=null){
	if(isset($id) || isset($this->data['ImageLibrary']['id'])) {
		$this->set('advertiserList',$this->common->getWholeAdvertiserProfileList()); //  List Advertisers
		$this->set('title_for_layout','Update the image Library');
		if(isset($this->data))
				{
				  $this->ImageLibrary->set($this->data['ImageLibrary']);
				  if($this->ImageLibrary->validates())
				  {
				/*------------------------------- Template image upload-------------------------------------------------------------*/
				
						if(isset($this->data['ImageLibrary']['image_url']['name']) && $this->data['ImageLibrary']['image_url']['name']!='')
						{
							$this->data['ImageLibrary']['image_url']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['ImageLibrary']['image_url']['name']);
							$docDestination = APP.'webroot/img/image_library/'.$this->data['ImageLibrary']['image_url']['name'];
							
							@chmod(APP.'webroot/img/image_library',0777);
							
							move_uploaded_file($this->data['ImageLibrary']['image_url']['tmp_name'], $docDestination) or die($docDestination);
							
							@unlink(WWW_ROOT.'img/image_library/'.$this->data['ImageLibrary']['image_url_old']);
							
							$this->data['ImageLibrary']['image_url'] = $this->data['ImageLibrary']['image_url']['name'];
							
						} else {
							$this->data['ImageLibrary']['image_url'] = $this->data['ImageLibrary']['image_url_old'];
						}
						$this->ImageLibrary->save($this->data,false);
						
					/*----------------------------------------------------------------------------------------------------------*/						
						$this->Session->setFlash('Image is successfully updated in library');
						$this->redirect(array('action' => 'index'));
				  }
				  else
				  {
						$errors = $this->ImageLibrary->invalidFields();
						$this->Session->setFlash(implode('<br>', $errors));
						return false;
				  }
			} else {
					$this->ImageLibrary->id = $id;
					$this->data = $this->ImageLibrary->read();
			}
		} else {
						$this->redirect(array('action' => 'index'));
		}
	}
/***--------------------------------------------------This function Delete the Daily Deal from database---------------------------------------------------------------*/
	function delete($id=null) {
	
					$this->ImageLibrary->id = (int)$id;
					
					$my_image= $this->ImageLibrary->find('first',array('fields'=>array('ImageLibrary.image_url'),'conditions'=>array('ImageLibrary.id'=>$id)));
					
					@chmod(APP.'webroot/img/image_library',0777);
					
					@unlink(APP.'webroot/img/image_library/'.$my_image['ImageLibrary']['image_url']);
					
					$this->ImageLibrary->delete($id);
					
					$this->Session->setFlash('The Image with id:  '.$id.' from Library has been Deleted Successfully!!');
					
					$this->redirect(array('action'=>'index'));
	}
/***--------------------------------------------------This function view the Daily Deal from database------------------------------------------*/
	function view($id=null){
		$this->set('title_for_layout','View the image Library');	
		$this->ImageLibrary->id = (int)$id;
		$this->set('mydata',$this->ImageLibrary->read());
	}
/***-------------------------this function is checking username and password in database and if true then redirect to home page----------------------------------*/		
	function download($filename){
		$this->downloadFile('img/image_library',$filename);
	}
	/* Download function path if folder any folder in img path */
	function downloadFile($folder,$fielname) {
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