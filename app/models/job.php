<?php 
	class Job extends AppModel { 
	        var $name = 'Job';
		
			//Validation for contact
			var $validate =  array(
				 'title'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please enter job title.'
								),
				 'no_of_post'=>array(
					 'rule-1'=>array('rule' => 'notEmpty',
									'message' => 'Please enter no of post.',
									'last'=>true
									),
					 'rule-2'=>array('rule' => 'numeric',
									'message' => 'Please enter numeric value in no of post field.'
									)
								),/*
				 'state'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please select state.'
								),
				 'county'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please select county.'
								),*/
				 'start_date'=>array(
					 'rule-1'=>array('rule' => 'notEmpty',
									'message' => 'Please enter publish date.',
									'last'=>true
									)
								),
				 'end_date'=>array(
					 'rule-1'=>array('rule' => 'notEmpty',
									'message' => 'Please enter Expiration date.',
									'last'=>true
									),
					 'rule-2'=>array('rule' => 'chkBigDate',
									'message' => 'Expiration date should be greater than publish date.',
									'last'=>true
									)
								),
				 'contents'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please enter job details.'
								)
				 );	   
		function chkBigDate()
		{
			if(isset($this->data['Job']['start_date']) && $this->data['Job']['start_date']!='')
			{
				
				$sdArr=explode('/',$this->data['Job']['start_date']);
				$sd_tStamp=mktime(0,0,0,$sdArr[0],$sdArr[1],$sdArr[2]);
				$edArr=explode('/',$this->data['Job']['end_date']);
				$ed_tStamp=mktime(0,0,0,$edArr[0],$edArr[1],$edArr[2]);
				
				if($sd_tStamp>$ed_tStamp)
				{
					return false;
				}
				return true;
			}
			return true;
		}
	} 
?>