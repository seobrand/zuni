<?php
/*
   Coder: Manoj Pandit
   Date  : 15 Apr 2013
*/

class PhotoGalleriesController extends AppController {
	var $name = 'PhotoGalleries'; //Model name attached with this controller
	 var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar');
	 var $components = array('Auth','common','Session','Cookie','RequestHandler','Email','emailhtml');
	 var $layout = 'admin';
	
/***-----------------------This function is the Index function i.e. call by default-------------------------------------------------------------------------------*/
	function index(){
		$this->set('title_for_layout','Photo Gallery Manager');
		$this->set('search_text','Tag');
		$this->set('subcategory',0);
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('PhotoGallery.id' => 'DESC'));
		
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		//if title is set
		if(isset($this->data['photo_galleries']['search_text']) && $this->data['photo_galleries']['search_text'] != '' && $this->data['photo_galleries']['search_text'] != 'Tag'){
			$search_text = $this->data['photo_galleries']['search_text'];
			$this->set('search_text',$search_text);
		} else if(isset($this->params['named']['search_text']) && $this->params['named']['search_text'] != '') {
			$search_text = $this->params['named']['search_text'];
			$this->set('search_text',$search_text);
		}
		
		//if title is set
		if(isset($this->data['photo_galleries']['subcategory']) && $this->data['photo_galleries']['subcategory'] != ''  && $this->data['photo_galleries']['subcategory'] != 0){
			$subcategory = $this->data['photo_galleries']['subcategory'];
			$this->set('subcategory',$subcategory);
		} else if(isset($this->params['named']['subcategory']) && $this->params['named']['subcategory'] != '') {
			$subcategory = $this->params['named']['subcategory'];
			$this->set('subcategory',$subcategory);
		}
	/*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		if(isset($search_text) && $search_text !='' && $search_text!='Tag'){
		 	$cond['PhotoGallery.tags LIKE'] = '%'.$search_text.'%';
		}
		if(isset($subcategory) && $subcategory !='' && $subcategory!=0){
		 	$cond['PhotoGallery.subcategory'] = $subcategory;
		}
		$data = $this->paginate('PhotoGallery', $cond);
		$this->set('galleries', $data);
	}
/***-----------------------This function Add new photo in gallery----------------------------------------------------------*/
	function add() {
		$this->set('title_for_layout','Add new photo in gallery');
		if(isset($this->data))
				{
				  $this->PhotoGallery->set($this->data['photo_galleries']);
				  if($this->PhotoGallery->validates())
				  {
					/*-------------------------------photo gallery upload-------------------------------------------------------------*/
						
						$this->data['photo_galleries']['image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['photo_galleries']['image']['name']);
						$docDestination = APP.'webroot/img/photo_gallery/'.$this->data['photo_galleries']['image']['name'];
						
						@chmod(APP.'webroot/img/photo_gallery',0777);
						
						move_uploaded_file($this->data['photo_galleries']['image']['tmp_name'], $docDestination) or die($docDestination);
						
						$this->data['PhotoGallery']['image'] = $this->data['photo_galleries']['image']['name'];
						
						
						$this->PhotoGallery->save($this->data,false);
					  					
						$this->Session->setFlash('Photo is successfully saved in gallery'); 
						$this->redirect(array('action' => 'index'));
				  }
				  else
				  {				
						$errors = $this->PhotoGallery->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
	}
/***----------------------This function Edit Existing photo in gallery------------------------------------------------------------------------------------*/
	function edit($id=null){
	if(isset($id) || isset($this->data['photo_galleries']['id'])) {
		$this->set('title_for_layout','Update the Photo Gallery');
		if(isset($this->data))
				{
				  $this->PhotoGallery->set($this->data['photo_galleries']);
				  if($this->PhotoGallery->validates())
				  {						
						$this->PhotoGallery->save($this->data,false);
						
					/*----------------------------------------------------------------------------------------------------------*/						
						$this->Session->setFlash('Photo is successfully updated in gallery');
						$this->redirect(array('action' => 'index'));
				  }
				  else
				  {
						$errors = $this->PhotoGallery->invalidFields();
						$this->Session->setFlash(implode('<br>', $errors));
						
						return false;
				  }
			} else {
					$this->PhotoGallery->id = $id;
					$this->data = $this->PhotoGallery->read();
			}
		} else {
						$this->redirect(array('action' => 'index'));
		}
	}
/***--------------------------------------------------This function Delete the photo from gallery---------------------------------------------------------------*/
	function delete($id=null) {
					
					$this->redirect(array('action'=>'index'));
					exit;
					
					$this->PhotoGallery->id = (int)$id;
					
					$my_image= $this->PhotoGallery->find('first',array('fields'=>array('PhotoGallery.image'),'conditions'=>array('PhotoGallery.id'=>$id)));
					
					@chmod(APP.'webroot/img/photo_gallery',0777);
					
					@unlink(APP.'webroot/img/photo_gallery/'.$my_image['PhotoGallery']['image']);
					
					$this->PhotoGallery->delete($id);
					
					$this->Session->setFlash('The Photo with id:  '.$id.' from Gallery has been Deleted Successfully!!');
					
					$this->redirect(array('action'=>'index'));
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
/***-----------------------This function is used to select photo in popup--------------------------------------------------------------------------------------*/
	function mypopup(){
		$this->set('title_for_layout','Photo Gallery Manager');
		$this->set('search_text','Tag');
		$this->set('subcategory',0);
		//$cond = '';
		//$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('PhotoGallery.id' => 'DESC'));
		
		$data = $this->PhotoGallery->find('all',array('conditions'=>array('PhotoGallery.remove_flag'=>0),'order' => array('PhotoGallery.id' => 'DESC')));
		
		$this->set('galleries', $data);
	}

/***----------------------ajax filter result ---------------------------------------------------------------------*/
function filterResult($mycat=0,$subcat=0,$stext='')
{
	$this->layout=false;
	

		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('PhotoGallery.id' => 'DESC'));
		
		if($mycat!=0 || $subcat!=0)
		{
			$cond['PhotoGallery.subcategory'] = $mycat.'/'.$subcat;
		}
		
		if(isset($stext) && $stext !='' && $stext!='Tag'){
		 	$cond['PhotoGallery.tags LIKE'] = '%'.$stext.'%';
		}

		$data = $this->paginate('PhotoGallery', $cond);
		$this->set('galleries', $data);
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