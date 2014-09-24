<?php 
	class County extends AppModel { 
	        var $name = 'County';
		    //var $hasMany=array('HeaderLogo');
			//Validation for state
				 var $validate =  array(
								 'countyname'=>array(
									   'countyname-1'=>array(
											 'rule' => 'notEmpty',
											 'message' => 'Please insert county name.'
											 )/*,
										'countyname-2'=> array(
										 'rule' => 'defaultImgCheck',
										 'message' => 'County alerady exist.'
										 ),*/
									 ),
								'state_id' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select state of the county.'),
								'split' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please enter split for the county.')
								
				 );
				 
		function countyEditDetail($id=null){
		
			    $this->id = $id;
				  
			    $County = $this->read();
			  
			    return $County;
	      }	
		  
		  
	} 
?>