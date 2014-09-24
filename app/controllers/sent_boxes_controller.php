<?php 
/*
   Coder: Manoj Pandit
   Date  : 06 Feb 2013
*/ 

class SentBoxesController extends AppController {
	var $name = 'SentBoxes'; //Model name attached with this controller 
	 var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar'); 
	 var $components = array('Auth','common','Session','Cookie','RequestHandler','Email','emailhtml');
	 var $layout = 'admin';
	
/***-----------------------This function is the Index function i.e. call by default-------------------------------------------------------------------------------*/
	function index(){
		$this->set('title_for_layout','Sent Box');
		/*$this->set('search_text','Title');*/
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('SentBox.id' => 'DESC'));
		
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		//if title is set
		/*if(isset($this->data['image_libraries']['search_text']) and $this->data['image_libraries']['search_text'] != ''){
			$search_text = $this->data['image_libraries']['search_text'];
			$this->set('search_text',$search_text);
		} else if(isset($this->params['named']['search_text']) && $this->params['named']['search_text'] != '') {
			$search_text = $this->params['named']['search_text'];
			$this->set('search_text',$search_text);
		}*/
		
	/*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		/*if(isset($search_text) && $search_text !='' && $search_text!='Title'){
		 	$cond['ImageLibrary.title LIKE'] = '%'.$search_text. '%';
		}
		if(isset($advertiser_profile_id) && $advertiser_profile_id !='' && $advertiser_profile_id!='Advertiser'){
		 	$cond['ImageLibrary.advertiser_profile_id'] = $advertiser_profile_id;
		}*/
		
		$cond[] = 'SentBox.to <>""';
		$cond[] = 'SentBox.from <>""';
		$data = $this->paginate('SentBox',$cond);
		
		$this->set('sent_items', $data);
	}
/***-------------------------this function is used to download attachment----------------------------------*/		
	function download($filename){
		$this->downloadFile('files/pdf/',$filename);
	}
	/* Download function path if folder any folder in img path */
	function downloadFile($folder,$filename) {
		$this->autoLayout = false;
		$newFileName = $filename;
		$folder = str_replace('-','/',$folder);
		//Replace - to / to view subfolder
	    $path =  WWW_ROOT.$folder.'/'.$filename;
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
/***---------------------------------This function Delete the sent item from sent box from database---------------------------------------------------------------*/
	function delete($id=null) {
					$id=base64_decode($id);
					
					$this->SentBox->id = (int)$id;
					
					$this->SentBox->delete($id);
					
					//$this->Session->setFlash('The Sent item with id:  '.$id.' from SentBox has been Deleted Successfully!!');
					
					$this->redirect(array('action'=>'index'));
	}
/***--------------------------------------------------This function view the sent item from sentbox from database------------------------------------------*/
	function view($id=null){
		$this->set('title_for_layout','Sent Mail Content');	
		$id=base64_decode($id);
		$this->SentBox->id = (int)$id;
		$this->set('mydata',$this->SentBox->read());
		$this->SentBox->query("update sent_boxes set read_status ='1' where id= '".$id."'");	
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