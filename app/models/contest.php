<?php
class Contest extends AppModel {
	var $name = 'Contest';	
	//var $hasMany = array('ContestUser');
			//Validation for Contest
				 var $validate =  array(
				 				'county_id'=>array(
										'rule' => 'notEmpty',
										'message' => 'Please select County.'
									),
								/*'temp_image'=>array(
									   	'rule' => array('validatetemplate'),
										'message' => 'Please upload Template image.'
									),*/
								/*'first_title'=>array(
									   	'rule' => 'notEmpty',
										'message' => 'Please enter First Title.'
									),
								'second_title'=>array(
									   	'rule' => 'notEmpty',
										'message' => 'Please enter Second Title.'
									),*/
								/*'banner_image'=>array(
									   	'rule' => array('validateBackground'),
										'message' => 'Please upload Background Image.'
									),*/
								/*'color' => array(
										 'rule' => 'notEmpty',
										 'message' => 'Please select Text Color.'
                                    ),	*/																
                              	'header' => array(
										 'rule' => 'notEmpty',
										 'message' => 'Please enter Contest Header.'
                                    ),
								'description' => array(
										 'rule' => 'notEmpty',
										 'message' => 'Please enter Contest Details.'
                                    ),
								'submission_type' => array(
										 'rule' => 'notEmpty',
										 'message' => 'Please enter Submission Details.'
                                    ),
								's_date' => array(
										'Rule-1'=> array(
											'rule' => 'notEmpty',
											'message' => 'Please enter Start Date',
											'last'=>true
										),
										'Rule-2'=>array(
											'rule' 	  => array('home_sdate_validation'),
											'message' => 'Contest Start Date is Booked.'
										)
									),
								'e_date' => array(
										'Rule-1'=> array(
											'rule' => 'notEmpty',
											'message' => 'Please enter End Date',
											'last'=>true
										),
										'Rule-2'=>array(
											'rule' 	  => array('home_edate_validation'),
											'message' => 'Contest End Date is Booked.',
											'last'=>true
									),
										'Rule-3'=>array(
											'rule' 	  => array('inner_date_validation'),
											'message' => 'A Contest is already placed between date range.'
									)
								),
								'user_limit' => array(
										 'rule' => 'numeric',
										 'message' => 'Please enter valid user limit.',
										 'allowEmpty' =>true
                                 )
				 );	
				function validatetemplate(){				
				 	if(isset($this->data['Contest']['temp_image']['error']) && $this->data['Contest']['temp_image']['error']!= 0 && !isset($this->data['Contest']['id'])) {
						return false;
					}
					return true;
				}
				 function validateBackground() {
				 	if(isset($this->data['Contest']['banner_image']['error']) && $this->data['Contest']['banner_image']['error']!= 0 && !isset($this->data['Contest']['id'])) {
						return false;
					}
					return true;
				 }
///////////////////////////////////////////////////////////////////////////////////
			function home_sdate_validation() {
										
							if(isset($this->data['Contest']['county_id']) && $this->data['Contest']['county_id']!='' && isset($this->data['Contest']['s_date']) && $this->data['Contest']['s_date']!='') {
							$cond = '';
							if(isset($this->data['Contest']['id'])) {
							 	$cond = ' AND id not in ('.$this->data['Contest']['id'].')';
							 }
								$start_date = $this->data['Contest']['s_date'];																
								$validat_sdate = '';
								$validat_sdate = $this->query("select id from contests where s_date <= $start_date AND  $start_date <= e_date and status ='yes' and county_id='".$this->data['Contest']['county_id']."'$cond");				
									if($validat_sdate) {
										return false;
									}
									return true;
							}		
							return true;
				}
///////////////////////////////////////////////////////////////////////////////////
			function home_edate_validation() {
										
							if(isset($this->data['Contest']['county_id']) && $this->data['Contest']['county_id']!='' && isset($this->data['Contest']['e_date']) && $this->data['Contest']['e_date']!='') {
							$cond = '';
							if(isset($this->data['Contest']['id'])) {
							 	$cond = ' AND id not in ('.$this->data['Contest']['id'].')';
							 }						
							
								$end_date = $this->data['Contest']['e_date'];																
								$validat_edate = '';
								$validat_edate = $this->query("select id from contests where s_date <= $end_date AND  $end_date <= e_date and status ='yes' and county_id='".$this->data['Contest']['county_id']."'$cond");				
									if($validat_edate) {
										return false;
									}
									return true;
							}		
							return true;
				}
///////////////////////////////////////////////////////////////////////////////////
function inner_date_validation() {
			if(!empty($this->data['Contest']['s_date']) && !empty($this->data['Contest']['e_date'])) {							
								$cond = '';
								if(isset($this->data['Contest']['id'])) {
										$cond = ' AND id not in ('.$this->data['Contest']['id'].')';
								}
								$start_date		= $this->data['Contest']['s_date'];
								$expiry_date = $this->data['Contest']['e_date'];
								$validat_sdate = $this->query("select id from contests where '".$start_date."' < s_date and '".$expiry_date."' > e_date and status ='yes' and county_id='".$this->data['Contest']['county_id']."'$cond");
								if($validat_sdate) {
									return false;
								}
							}
						return true;
			}
///////////////////////////////////////////////////////////////////////////////////					
}
?>