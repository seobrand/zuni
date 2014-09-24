<?php 
	class MailChimp extends AppModel { 
	        var $name = 'MailChimp';
			var $useTable = 'work_orders';
			var $validate = array(
								'mailchimp_list_id'=>array(
										'rule' => 'mailchimpListCheck',
										'message' => 'Please select a mailchimp list.'
									),
								'csv_file_url' => array(
										'Rule-1'=> array(
											'rule' => 'nullCsvCheck',
											'message' => 'Please upload csv.',
											'last'=>true
										),
										'Rule-2'=>array(
											'rule' 	  => array('csvTypeCheck'),
											'message' => 'Please upload .csv file only.'
										)
									)
			);
	function mailchimpListCheck()
		{
			if(isset($this->data['MailChimp']['mailchimp_list_id']) && $this->data['MailChimp']['mailchimp_list_id']=='')
			{
				return false;
			}
			return true;
		}
		function nullCsvCheck()
		{
			if(isset($this->data['MailChimp']['csv_file_url']['name']) && $this->data['MailChimp']['csv_file_url']['name']=='')
			{
				return false;
			}
			return true;
		}
		
		function csvTypeCheck()
		{
				$csvtype = end(explode(".",$this->data['MailChimp']['csv_file_url']['name']));
				if(($this->data['MailChimp']['csv_file_url']['type']!='application/csv' && $this->data['MailChimp']['csv_file_url']['type']!='application/vnd.ms-excel') || $this->data['MailChimp']['csv_file_url']['type']['error']!=0) {
					return false;
 				}elseif(strtolower($csvtype)=="csv"){
					return true;
				}else{
					return false;
				}
				return true;
			
		}
	} 
?>