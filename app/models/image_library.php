<?php
class ImageLibrary extends AppModel {
	var $name = 'ImageLibrary';	
	
			//Validation for ImageLibrary
				 var $validate =  array(
				 				'title'=>array(
										'rule' => 'notEmpty',
										'message' => 'Please enter title.'
									),	
								'advertiser_profile_id'=>array(
										'rule' => 'notEmpty',
										'message' => 'Please select an advertiser.'
									),															
								'image_url' => array(
										'Rule-1'=> array(
											'rule' => 'nullImageCheck',
											'message' => 'Please upload image',
											'last'=>true
										),
										'Rule-2'=>array(
											'rule' 	  => array('fileTypeCheck'),
											'message' => 'Please upload .jpg file or .png file or .gif file only.'
										)
									)
				 );
	function nullImageCheck()
	{
		if(isset($this->data['ImageLibrary']['image_url']['name']) && $this->data['ImageLibrary']['image_url']['name']=='' && !isset($this->data['ImageLibrary']['image_url_old']))
		{
			return false;
		}
		return true;
	}
	
	function fileTypeCheck()
	{
		if(!isset($this->data['ImageLibrary']['image_url_old']))
		{
			$ftype = explode(".",$this->data['ImageLibrary']['image_url']['name']);
			if(strtolower($ftype[1]) =="png" || strtolower($ftype[1]) =="jpeg" || strtolower($ftype[1]) =="jpg"  || strtolower($ftype[1]) =="gif")
			{
				return true;
			}else{
				return false;
			}
			return true;
		}
		return true;
	}
	
}
?>