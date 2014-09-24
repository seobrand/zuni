<?php
class PhotoGallery extends AppModel {
	var $name = 'PhotoGallery';	
	
			//Validation for ImageLibrary
				// var $validate =  ''; 
				  var $validate = array(
								'subcategory'=>array(
										'rule' => 'subcatCheck',
										'message' => 'Please select a subcategory.'
									),															
								'image' => array(
										'Rule-1'=> array(
											'rule' => 'nullImageCheck',
											'message' => 'Please upload image.',
											'last'=>true
										),
										'Rule-2'=>array(
											'rule' 	  => array('fileTypeCheck'),
											'message' => 'Please upload .jpg file or .png file or .gif file only.'
										)
									)
				 );
function subcatCheck()
	{
		if(isset($this->data['PhotoGallery']['subcategory']) && $this->data['PhotoGallery']['subcategory']==0)
		{
			return false;
		}
		return true;
	}
	function nullImageCheck()
	{
		if(!isset($this->data['PhotoGallery']['id']) && isset($this->data['PhotoGallery']['image']['name']) && $this->data['PhotoGallery']['image']['name']=='')
		{
			return false;
		}
		return true;
	}
	
	function fileTypeCheck()
	{
		
			if(isset($this->data['PhotoGallery']['id']))
			{
				return true;
			}
			
			$ftype = explode(".",$this->data['PhotoGallery']['image']['name']);
			if(strtolower($ftype[1]) =="png" || strtolower($ftype[1]) =="jpeg" || strtolower($ftype[1]) =="jpg"  || strtolower($ftype[1]) =="gif")
			{
				return true;
			}else{
				return false;
			}
			return true;
		
	}
	
}
?>