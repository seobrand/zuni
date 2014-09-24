<?php 
	class Subcategory extends AppModel { 
	        var $name = 'Subcategory';
		    var $hasMany = 'CategoriesSubcategory';
			var $actsAs = array('Containable');
			//Validation for SubCategory
				 var $validate =  array(
								'categoryname'=>array(
									   'categoryname-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please insert subcategory name.'
											 ),
										'categoryname-2'=> array(
										 'rule' => 'isUnique',
										 'message' => 'Subcategory alerady exist.'
										 )
									),
								'county_count'=>array(
							              'rule' => 'countyNotEmpty',
										  'message' => 'Please select county.'
							            ),

								'meta_title'=>array(
							              'rule' => 'categoryNotEmpty',
										  'message' => 'Please select category.'
							            )
				 );

		function countyNotEmpty()
		{
			if(!isset($this->data['Subcategory']['county']) && empty($this->data['Subcategory']['county']))
			{
				return false;
			}
			return true;	
		}
		
		function categoryNotEmpty()
		{
			if((!isset($this->data['Subcategory']['category_id'])) || (isset($this->data['Subcategory']['category_id']) && is_array($this->data['Subcategory']['category_id']) && empty($this->data['Subcategory']['category_id'][0])))
			{
				return false;
			}
			return true;	
		}
		
			function subcategoryEditDetail($id=null){
		
			    $this->id = $id;
				$this->recursive = 2;
			    $Subcategory = $this->read();
			  
			    return $Subcategory;
	      }	 		 
	} 
?>