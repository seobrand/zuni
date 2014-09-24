<?php 
         class Category extends AppModel { 
	        var $name = 'Category';
			var $hasMany = array('CountyCategory');
				 //Validation for Category
				 var $validate =  array(
				 			 'county_count'=>array(
							              'rule' => 'countyNotEmpty',
										  'message' => 'Please select county.'
							            ),
								 'categoryname'=>array(
									   'categoryname-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please insert category name.'
											 ),
										'categoryname-2'=>array(
											 'rule' => array('maxLength', 25),
											 'message' => 'Category name can\'t exceed 25 characters limit.'
											 ),
										'categoryname-3'=> array(
										 'rule' => 'isUnique',
										 'message' => 'Category name alerady exist.'
										 )
									),
							   'order'=>array(							   
							              'rule' => 'isUnique',
										  'allowEmpty' => true,
										  'message' => 'Order Number already exist'
							  )
			);
		
		function countyNotEmpty()
		{
			if(!isset($this->data['Category']['county']) && empty($this->data['Category']['county']))
			{
				return false;
			}
			return true;	
		}
		function categoryEditDetail($id=null)	{
			    $this->id = $id;
			    $Category = $this->read();
			    return $Category;			   
	       }		   
	}
?>