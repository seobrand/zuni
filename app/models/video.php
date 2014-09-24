<?php 
	class Video extends AppModel { 
	        var $name = 'Video';
		
			//Validation for Country
			var $validate =  array(
				 'title'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please insert video title.'),
				'utube_link'=>array(
				'rule-1'=>array(
							'rule'=>array('validCheck'),
							'message'=>'Please insert either youtube video url OR uplaod any mp4 video.',
							'last'=>true
					 ),
				 'rule-2'=>array(
						'rule'=>array('validCheckMP4'),
						'message'=>'Please uplaod MP4 video file only.',
						'last'=>true
				 	)
				 )

				 );
				 
		function validCheck() {
		   if($this->data['Video']['utube_link']=='' && $this->data['Video']['file_name']['name']=='' && $this->data['Video']['old_video']==''){
		   		return false;
		   }else{
		   		if($this->data['Video']['old_video']=='1' && $this->data['Video']['utube_link']=='' && $this->data['Video']['file_name']['name']=='')
				{
					return false;
				}
				else if($this->data['Video']['old_video']=='2' && $this->data['Video']['utube_link']=='' && $this->data['Video']['file_name']['name']==''){
					return true;
				}
				else if($this->data['Video']['old_video']=='3' && $this->data['Video']['utube_link']=='' && $this->data['Video']['file_name']['name']==''){
					return true;
				}
				else{
					return true;
				
				}
		   }
		}
	 function validCheckMP4(){
	 	if(isset($this->data['Video']['file_name']['name']) && $this->data['Video']['file_name']['name']!='' && isset($this->data['Video']['file_name']['type']) && $this->data['Video']['file_name']['type']!='')
		{
			if($this->data['Video']['file_name']['type']=='video/mp4')
			{
				return true;
			}else{
				return false;
			}
		}
	 	return true;
	 }		   
	} 
?>