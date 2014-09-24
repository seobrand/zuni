<?php
class NewsManager extends AppModel {
	var $name = 'NewsManager';	
	//var $hasMany = array('ContestUser');
			//Validation for Contest
				 var $validate =  array(	
																					
                              	'title' => array(
										 'rule' => 'notEmpty',
										 'message' => 'Please enter News Title.'
                                    ),
								'description' => array(
										 'rule' => 'notEmpty',
										 'message' => 'Please enter News Description.'
                                    ),			
                                         'image'=>array(
									   	'rule' => array('validateBackground'),
										'message' => 'Please upload Image.'
									),
				 );	
				
				 function validateBackground() {
				 	if(isset($this->data['NewsManager']['image']['error']) && $this->data['NewsManager']['image']['error']!= 0 && !isset($this->data['NewsManager']['id'])) {
						return false;
					}
					return true;
				 }
					
}
?>