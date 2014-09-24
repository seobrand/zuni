<?php 
	class Meta extends AppModel { 
	        var $name = 'Meta';
			var $belongsTo = array('State','County','City','Subcategory','Category');
			
			  function validatesMetaInfo($arr,$params,$idd='') {
		      $error = array();
		      App::import('Core', 'Validation'); 
		      $validation = new Validation();
		   /* Account information validation */  
		    if(!trim($arr['Meta']['state_id'])) 
			{
			 	$error[] = 'Please select State.';
		    }
			if(!trim($arr['Meta']['county_id'])) 
			{
			 	$error[] = 'Please select County.';
		    }
		   	if(!trim($arr['Meta']['meta_title'])) 
			{
			 	$error[] = 'Please enter Title';
		    }
			
			if(trim($arr['Meta']['state_id']) && trim($arr['Meta']['meta_title'])) {
				App::import('model', 'Meta'); 
				$this->Meta = new Meta();
			
				$state = $arr['Meta']['state_id'];
				$county = $arr['Meta']['county_id'];
				$city = ($arr['Meta']['city_id']) ? $arr['Meta']['city_id'] : 0;
				
				$cats = explode('/',$arr['Meta']['category']);
				$cat = (isset($cats[0]) && $cats[0]!='') ? $cats[0] : 0;
				$subcat = (isset($cats[1]) && $cats[1]!='') ? $cats[1] : 0;
				
				$id = (isset($arr['Meta']['id'])) ? $arr['Meta']['id'] : 0;
				
				App::import('model', 'Meta'); 
				$this->Meta = new Meta();
				$count = $this->Meta->find('count',array('conditions'=>array('Meta.state_id'=>$state,'Meta.county_id'=>$county,'Meta.city_id'=>$city,'Meta.category_id'=>$cat,'Meta.subcategory_id'=>$subcat,'Meta.id!='.$id)));
				if($count) {
					$error[] = 'Combination is already exists.';
				}
			}	
		   	return $error;
		}
	}
?>