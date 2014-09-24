<?php 
class Career extends AppModel {
	var $name="Career";
        
			var $validate =  array(
				 'fname'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please Enter First Name.'
								),
                            'lname'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please Enter Last Name.'
								),
				'email' => array(
                                              'emailRule-1'=> array(
                                                            'rule' => 'notEmpty',
                                                            'message' => 'Please Enter Email.',
                                                            'last'=>true
                                                            ),
                                              'emailRule-2'=>array(
                                                            'rule' => 'email',
                                                            'message' => 'Please Enter a Valid Email.'
                                                            )
                                              ),
				 'state'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please Select State.'
								),
				/*'job_id' => array(
							  'jobRule-1'=> array(
											'rule' => 'notEmpty',
											'message' => 'Invalid job selected.',
											'last'=>true
											),
							  'jobRule-2'=>array(
											'rule' => 'numeric',
											'message' => 'Invalid job selected.',
											'last'=>true
											),
							  'jobRule-3'=>array(
											'rule' => 'checkJobAvailability',
											'message' => 'Invalid job selected.'
											)
							  ),*/
				 /*'date_available'=>array('rule' => 'validateDateFormat',
				 				'allowEmpty'=>true,
        		 				'message' => 'Invalid date format in date available.'
								),
				 'exp1_start_date'=>array('rule' => 'validateDateFormat1',
				 				'allowEmpty'=>true,
        		 				'message' => 'Invalid date format in experience 1 start date.'
								),
				 'exp1_end_date'=>array('rule' => 'validateDateFormat2',
				 				'allowEmpty'=>true,
        		 				'message' => 'Invalid date format in experience 1 end date.'
								),
				 'exp2_start_date'=>array('rule' => 'validateDateFormat3',
				 				'allowEmpty'=>true,
        		 				'message' => 'Invalid date format in experience 2 start date.'
								),
				 'exp2_end_date'=>array('rule' => 'validateDateFormat4',
				 				'allowEmpty'=>true,
        		 				'message' => 'Invalid date format in experience 2 end date.'
								),*/
				 'resume' => array(
                                              'resumeRule-1'=> array(
                                                            'rule' => 'FilenotEmpty',
                                                            'message' => 'Please attach your resume below.',
                                                            'last'=>true
                                                            ),
                                              'resumeRule-2'=>array(
															'rule' => array('extension', array('doc', 'docx', 'rtf', 'odt')),
															'message' => 'Please supply a valid format of resume.'
                                )
                              )
				 );
				 
				 function FilenotEmpty()
				 {
				 	if(isset($this->data['Career']['resume']['name']) && $this->data['Career']['resume']['name']=='')
					{
						return false;
					}
					return true;
				 }
				 
				 function checkJobAvailability()
				 { // check for right job is selected by the user or not
					App::import('model','Job');
            		$this->Job = new Job();					
					$todaymodel = mktime(0,0,0,date('m'),date('d'),date('Y'));
           		 	$matchingJobsModle = $this->Job->find('first',array('conditions'=>array('Job.id'=>$this->data['Career']['job_id'],'Job.start_date <= '=>$todaymodel,'Job.end_date >= '=>$todaymodel,'status'=>'yes')));
					if(empty($matchingJobsModle))
					{
						return false;
					}
					return true;
				 }
				 
				 function validateDateFormat()
				 {
						 if(isset($this->data['Career']['date_available']) && $this->data['Career']['date_available']!='')
						 {
								if (preg_match("/^([0-9]{2})\/([0-9]{2})\/([0-9]{2})$/", $this->data['Career']['date_available'], $parts) || preg_match("/^([0-9]{1})\/([0-9]{1})\/([0-9]{2})$/", $this->data['Career']['date_available'], $parts))
								{
									if(!checkdate($parts[1],$parts[2],$parts[3]))
									{ return false;}
									
								 }
								 else
								 {return false;}
						}
						return true;
				}
				
				function validateDateFormat1()
				 {
						 if(isset($this->data['Career']['exp1_start_date']) && $this->data['Career']['exp1_start_date']!='')
						 {
								if (preg_match("/^([0-9]{2})\/([0-9]{2})\/([0-9]{2})$/", $this->data['Career']['exp1_start_date'], $parts) || preg_match("/^([0-9]{1})\/([0-9]{1})\/([0-9]{2})$/", $this->data['Career']['exp1_start_date'], $parts))
								{
									if(!checkdate($parts[1],$parts[2],$parts[3]))
									{ return false;}
									
								 }
								 else
								 {return false;}
						}
						return true;
				}
					
				function validateDateFormat2()
			    {
					 if(isset($this->data['Career']['exp1_end_date']) && $this->data['Career']['exp1_end_date']!='')
						 {
								if (preg_match("/^([0-9]{2})\/([0-9]{2})\/([0-9]{2})$/", $this->data['Career']['exp1_end_date'], $parts) || preg_match("/^([0-9]{1})\/([0-9]{1})\/([0-9]{2})$/", $this->data['Career']['exp1_end_date'], $parts))
								{
									if(!checkdate($parts[1],$parts[2],$parts[3]))
									{ return false;}
									
								 }
								 else
								 {return false;}
						}
						return true;
				}
				
				function validateDateFormat3()
				 {
						 if(isset($this->data['Career']['exp2_start_date']) && $this->data['Career']['exp2_start_date']!='')
						 {
								if (preg_match("/^([0-9]{2})\/([0-9]{2})\/([0-9]{2})$/", $this->data['Career']['exp2_start_date'], $parts) || preg_match("/^([0-9]{1})\/([0-9]{1})\/([0-9]{2})$/", $this->data['Career']['exp2_start_date'], $parts))
								{
									if(!checkdate($parts[1],$parts[2],$parts[3]))
									{ return false;}
									
								 }
								 else
								 {return false;}
						}
						return true;
				 }

				function validateDateFormat4()
				 {
						 if(isset($this->data['Career']['exp2_end_date']) && $this->data['Career']['exp2_end_date']!='')
						 {
								if (preg_match("/^([0-9]{2})\/([0-9]{2})\/([0-9]{2})$/", $this->data['Career']['exp2_end_date'], $parts) || preg_match("/^([0-9]{1})\/([0-9]{1})\/([0-9]{2})$/", $this->data['Career']['exp2_end_date'], $parts))
								{
									if(!checkdate($parts[1],$parts[2],$parts[3]))
									{ return false;}
									
								 }
								 else
								 {return false;}
						}
						
					return true;
					
					}
					
}
?>