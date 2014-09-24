<?php 
	class Banner extends AppModel { 
	        var $name = 'Banner';
			 
			//Validation for users
		var $validate =  array(
				'title'=>array(
				 			'title-1' =>array(
								'rule'=> 'notEmpty',
        		 				'message' => 'Please Insert Banner Title.'),
							'title-2' =>array(
								'rule'=> 'isUnique',
        		 				'message' => 'Page Title already in use, Please try another.',
								'on'=>'create'
								)
								),
				'advertiser_profile_id' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please Select Advertiser.',
								'on'=>'create'
								),
				'banner_size' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please Select Advertisement Type.'),
				'logo'=>array(
				 			'title-1' =>array(
								'rule'=> 'imgValidate',
        		 				'message' => 'Please Upload Banner Image.',
								'on'=>'create'
								),
	
							'title-2' =>array(
								'rule'=> 'imgTypeValidate',
        		 				'message' => 'Please Upload Image File Only.',
								'on'=>'create'
								)
								
								),																																
				'date-11-mm' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Banner Publish Date.'),
				'date-1-mm' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Banner Expiration Date.'),
				'category_id' => array(
        						'rule' => 'catValidate',
        						'message' => 'Please Select Category.'),
				);	
		
		function bannerEditDetail($id=null){
			$this->id = $id;
			$Banner = $this->read();
			return $Banner;
	      }	
		  
		  function imgValidate()
		  {
			if($this->data['Banner']['logo']['name']=='') {
							return false;	
						}
							return true;		  
		  }
		  function imgTypeValidate()
		  {
			if($this->data['Banner']['logo']['name']!='') {
			$bannerType=explode('/',$this->data['Banner']['logo']['type']);
					if($bannerType[0]!="image")
					{
						return false;	
					}
				}
							return true;		  
		  }
		  function catValidate()
		  {
			if(isset($this->data['Banner']['category_id']) && $this->data['Banner']['category_id']=='') {
							return false;	
						}
							return true;		  
		  }
		 
		  /*function dateValidationCheck($check) {
		$publishStartDate = mktime(date("H"), date("i"), date("s"), date($this->data['Banner']['date-11-mm']), date($this->data['Banner']['date-11-dd']), date($this->data['Banner']['date-11']));
		                 echo $publishStartDate;
                      //return preg_match('|^[0-9a-zA-Z_-]*$|', $value);
                }*/	 	 
	} 
?>