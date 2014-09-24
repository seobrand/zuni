<?php 
/*
   Coder: Surbhit
   Date  : 08 Dec 2010
*/ 

class ImagesController extends AppController { 
      var $name = 'Images';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('common','Cookie','Auth','Session');
	  function index()
	  {
			$this->set('adv_profile_id', $this->params['pass'][0]);
			$adverName = $this->Image->query("SELECT company_name FROM advertiser_profiles WHERE id ='".$this->params['pass'][0]."'");
			$this->set('adverName', $adverName[0]['advertiser_profiles']['company_name']);
			$condition =array('Image.advertiser_profile_id '=>$this->params['pass'][0]);
			$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('Image.id' => 'asc'));
			$data = $this->paginate('Image', $condition);			
		    $this->set('images', $data);			
	  }
	  
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
	 
	 /*-----------------------------AddImage---------------------------------------*/
	 function addNewImage($id=null)
	 {
		/*------------------validation for redirect 2 mastersheet if it is initiated from master sheet-----------------*/
		  if((strpos($this->referer(),'masterSheet')!=false)) {
		  	$this->Session->write('reff',$this->referer());
		  }
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		   }
		/*----------------------------------------------------------------------------------------------------------*/		   
		   
		   
	   if(isset($this->params['pass'][0])){
	   		$this->set('adv_profile_id', $this->params['pass'][0]);
		}	   
	   if(isset($this->data))
	   {	
			$this->Image->set($this->data);
			if($this->data['Image']['advertiser_profile_id']){
	   			$this->set('adv_profile_id', $this->data['Image']['advertiser_profile_id']);
	         }

				  if($this->Image->validates()){ 
				   				   
					$errors='';
					$this->data['Image']['title'] =  $this->data['Image']['title']; 
					$this->data['Image']['status'] =  $this->data['Image']['status'];
					$this->data['Image']['advertiser_profile_id'] =  $this->data['Image']['advertiser_profile_id'];
					
					/*------image validation for picture and thumb---------*/
					
					/*if($this->data['Image']['image_thumb']['name']=='')
							$errors[]='Please upload thumb image';		*/			
						
					if($this->data['Image']['image_big']['name']=='')						
							$errors[]='Please upload big image';				
					
					if(!empty($errors))
					{
							$this->Session->setFlash(implode('<br>',$errors)); 
							return false;
					}
					
					/*----------------------------------------------------*/		
					
			        
				         $this->data['Image']['image_thumb'] = '';

					  
					if($this->data['Image']['image_big']['name']!=""){

					$type = explode(".",$this->data['Image']['image_big']['name']);
					
					if(strtolower($type[1]) =="png" || strtolower($type[1]) =="jpeg" || strtolower($type[1]) =="jpg"  || strtolower($type[1]) =="gif"){
					
					                         
						
						

						$this->data['Image']['image_big']['name'] = $this->common->getTimeStamp()."_".$this->data['Image']['advertiser_profile_id']."_big_".str_replace(' ','-',$this->data['Image']['image_big']['name']);

						$docDestination = APP.'webroot/img/gallery/'.$this->data['Image']['image_big']['name']; 

						@chmod(APP.'webroot/img/gallery',0777);

						move_uploaded_file($this->data['Image']['image_big']['tmp_name'], $docDestination) or die($docDestination);
						
						$this->data['Image']['image_big'] = $this->data['Image']['image_big']['name'];
						
					}else{

						$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
					}	

				}else{   
				        
				         $this->data['Image']['image_big'] = '';
				      }  
					
																								

					if($this->Image->saveAll($this->data)){
					
					   	$this->Session->setFlash('Your Image has been submitted successfully.');  
					}
						
						if(isset($this->data['Image']['prvs_link']) && (strpos($this->data['Image']['prvs_link'],'masterSheet')!=false)) {
					 		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['Image']['prvs_link']);		
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
							}else {						
						$this->redirect(array('action' => "index/".$this->data['Image']['advertiser_profile_id'])); 
						}		
						
                   }else{
						$errors = $this->Image->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
				   }
					   
				}
	   
	  
	 }
	 
	  /*-----------------------------edit Image---------------------------------------*/
	 function editImage($id=null)
	 {
	   if(isset($this->data))
	   {
			$this->Image->set($this->data);
			if($this->data['Image']['advertiser_profile_id']){
	   			$this->set('adv_profile_id', $this->data['Image']['advertiser_profile_id']);
	         }

				  if($this->Image->validates()){  
				   	
					$errors='';			   
					$this->data['Image']['title'] =  $this->data['Image']['title']; 
					$this->data['Image']['id'] =  $this->data['Image']['id']; 
					$this->data['Image']['link'] =  $this->data['Image']['link'];
					$this->data['Image']['status'] =  $this->data['Image']['status'];
					$this->data['Image']['advertiser_profile_id'] =  $this->data['Image']['advertiser_profile_id'];
					
			        
					/*------image validation for picture and thumb---------*/
					
					if($this->data['Image']['image_big']['name']=='' && $this->data['images']['oldbigimage']=='')						
							$errors[]='Please upload big image';	
								
				 	if(!empty($errors))
					{
							$this->Session->setFlash(implode('<br>',$errors)); 
							$this->redirect(array('action' => "imageEditDetail/".$this->data['Image']['id']."/".$this->data['Image']['advertiser_profile_id'])); 
					}
								
					/*----------------------------------------------------*/		
					
					if($this->data['Image']['image_big']['name']!=""){

					$type = explode(".",$this->data['Image']['image_big']['name']);
					
					if(strtolower($type[1]) =="png" || strtolower($type[1]) =="jpeg" || strtolower($type[1]) =="jpg"  || strtolower($type[1]) =="gif"){
					
					                         
						unlink(APP.'webroot/img/gallery/'.$this->data['images']['oldbigimage']);
						

						$this->data['Image']['image_big']['name'] = $this->common->getTimeStamp()."_".$this->data['Image']['advertiser_profile_id']."_big_".str_replace(' ','-',$this->data['Image']['image_big']['name']);

						$docDestination = APP.'webroot/img/gallery/'.$this->data['Image']['image_big']['name']; 

						@chmod(APP.'webroot/img/gallery',0777);

						move_uploaded_file($this->data['Image']['image_big']['tmp_name'], $docDestination) or die($docDestination);
						
						$this->data['Image']['image_big'] = $this->data['Image']['image_big']['name'];
						
					}else{

						$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
					}	

				}else{   
				        
				         $this->data['Image']['image_big'] = $this->data['images']['oldbigimage'];
				      }  
					
																								

					if($this->Image->saveAll($this->data)){
					
					   	$this->Session->setFlash('Your Image has been updated successfully.');  
					}
					 if(isset($this->data['Image']['prvs_link']) && (strpos($this->data['Image']['prvs_link'],'masterSheet')!=false)) {
					 		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['Image']['prvs_link']);		
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
							}else {						
						$this->redirect(array('action' => "index/".$this->data['Image']['advertiser_profile_id'])); 
						}
                   }else{
						$errors = $this->Image->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));
						$this->redirect(array('action' => "imageEditDetail/".$this->data['Image']['id']."/".$this->data['Image']['advertiser_profile_id']));   
				   }
					   
				}  
	 }
	 function imageEditDetail($id=null)
		{
	       $this->set('Image',$this->Image->imageEditDetail($id));
		  if((strpos($this->referer(),'masterSheet')!=false)) {
		  	$this->Session->write('reff',$this->referer());
		  }
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		   }
		   
		}
		
	 
	 /*------------------------------Function to Delete Image------------------------------------*/
		function imageDelete($id) {

			$this->Image->id = $id;
			
			$imageOld = $this->Image->query("SELECT image_big FROM images WHERE id =".$id.";");
			if($imageOld[0]['images']['image_big']!=''){
			unlink(APP.'webroot/img/gallery/'.$imageOld[0]['images']['image_big']);
			}
			
			$this->Image->delete($id);
			$this->Session->setFlash('The Image with id: '.$id.' has been deleted.');
		if((strpos($this->referer(),'masterSheet')!=false)) {
				$ad_id = explode('/',$this->referer());			
				$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
			}else {		
			
			$this->redirect(array('action'=>'index/'.$this->params['pass'][1]));
		}	
	}	
	
//------------------------------------------------this function used to delete images from front end---------------------------------------------------//	
	function imageDeleteFront($id) {
		//Find all images of the logged in advertiser
			$this->Image->id = $id;
			$imageOld = $this->Image->query("SELECT image_big FROM images WHERE id =".$id.";");

			if($imageOld[0]['images']['image_big']!=''){
				@unlink(APP.'webroot/img/gallery/'.$imageOld[0]['images']['image_big']);
			}
			
			$this->Image->delete($id);
			$this->Session->setFlash('Image Deleted Successfully');
			$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county').'/images/delete:success');

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
/*----------------------------------------------this function is applying images and link header and footer layout-----------------------------------------*/

	function beforeFilter() { 

        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );

			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
			$this->Auth->allow('imageDeleteFront');
   	}
	

	/* This function is setting all info about current Admins in 
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