<?php 
/*
   Coder: Keshav Sharma
   Date  : 08 Dec 2010
*/ 

class VideosController extends AppController { 
      var $name = 'Videos';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('common','Cookie','Auth','Session');
	  function index()
	  {
			$this->set('adv_profile_id', $this->params['pass'][0]);
			$video = $this->Video->find('all', array('conditions' => array('Video.advertiser_profile_id' => $this->params['pass'][0])));
			$this->set('Video',$video);
		  if((strpos($this->referer(),'masterSheet')!=false)) {
		  	$this->Session->write('reff',$this->referer());
		  }
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		   }			
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
	 
	 /*-----------------------------Add/edit OFFER---------------------------------------*/
	 function addNewVideo()
	 {
	   if(isset($this->data))
	   {
				  $this->Video->set($this->data);
				  if($this->Video->validates()){ 
				   	
								   
					$this->data['Video']['title'] =  $this->data['Video']['title']; 
					$this->data['Video']['description'] =  $this->data['Video']['description'];
					$this->data['Video']['utube_link'] =  $this->data['Video']['utube_link'];
					$this->data['Video']['status'] =  $this->data['Video']['status'];
					$this->data['Video']['advertiser_profile_id'] =  $this->data['Video']['advertiser_profile_id'];
					
					if(isset($this->data['Video']['id'])){
						$this->data['Video']['id'] =  $this->data['Video']['id'];
					}
				
					if(isset($this->data['Video']['id'])){
						$videoOld = $this->Video->query("SELECT file_name FROM videos WHERE id =".$this->data['Video']['id'].";");
					}
									  
					if($this->data['Video']['file_name']['name']!='') { 
					
							   @chmod(WWW_ROOT.'img/video',0777);
							   if(isset($videoOld[0]['videos']['file_name']) && file_exists(APP.'webroot/img/video/'.$videoOld[0]['videos']['file_name'])){
						           @unlink(APP.'webroot/img/video/'.$videoOld[0]['videos']['file_name']);
						          }

								$fileName = $this->common->getTimeStamp()."_".$this->data['Video']['advertiser_profile_id']."_".$this->data['Video']['file_name']['name'];

								$this->data['Video']['file_name']['name'] = $fileName;
													
						    	$videoDestination = APP.'webroot/img/video/'.$fileName;
							
								move_uploaded_file($this->data['Video']['file_name']['tmp_name'], $videoDestination) or die($videoDestination);
								
								$this->data['Video']['file_name'] = $this->data['Video']['file_name']['name'];

							}else{ 
							   if(isset($videoOld[0]['videos']['file_name'])){
							     $this->data['Video']['file_name'] = $videoOld[0]['videos']['file_name'];
							    }else{
								 $this->data['Video']['file_name'] = '';
								}
							}
														
					$this->Video->saveAll($this->data['Video'],false);
					
					if(isset($this->data['Video']['id'])){
					       $this->Session->setFlash('Your Video has been updated successfully.');  
					 }else{
					$this->Session->setFlash('Your Video has been submitted successfully.');  
					}
					 if(isset($this->data['Video']['prvs_link']) && (strpos($this->data['Video']['prvs_link'],'masterSheet')!=false)) {
					 		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['Video']['prvs_link']);		
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
							}else {	
								$this->redirect(array('controller'=>'advertiser_profiles','action' => "index"));
						} 
                   }else{
				    $errors = $this->Video->invalidFields();	
					$this->Session->setFlash(implode('<br>', $errors));  
					$this->redirect(array('controller'=>'videos','action' => "index/".$this->data['Video']['advertiser_profile_id'])); 
				   }
					   
				}
	   
	  
	 }
	 	  	/*
    	this function is applying images and link header and footer layout
	*/

	function beforeFilter() { 

        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );

			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
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
	
	
	function convertToFlv($source,$destination) {

	   //create command for video

		$cmd="ffmpeg -i ".$source;

		$options='';
		
		$audioSamplingRate =44100;
		$audioBit =64;
		//$bitRate = 64;

		if($audioSamplingRate) {
				
				$options.=" -ar ".$audioSamplingRate;

			  }
		
		if($audioBit) {
				
				$options.=" -ab ".$audioBit;

			  }	 
			   
/*	   if($bitRate) {

				$options.=" -b ".$bitRate;

			  }

		if($videoSize) {

				$options.=" -s ".$videoSize;

		} */	   
		
		$options.=" -sameq";  

		$cmd .=$options." -f flv ".$destination;

/*		echo $cmd;
*/
		$last_line_vodeo=exec(escapeshellcmd($cmd),$retval); //retval value will generate 0 if video uploaded successfully 

		if(!file_exists($destination) || filesize($destination) < 100 ) {

		  $retval=1;

		  }

		//create command for thumb image from video

		//$this->createThumb($destination,$imgDestination,$retval); //hear destination of flv is image soruce

		@unlink($source);

	 }
	 function delete($vid,$ad_id) {
			   $this->Video->delete($vid);
			   $this->Session->setFlash('Video has been deleted successfully.');
			   $this->redirect(array('controller'=>'advertiser_profiles','action'=>'masterSheet',$ad_id));
	 
	 }

}
?>