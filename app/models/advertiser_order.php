<?php 
	class AdvertiserOrder extends AppModel { 
	        var $name = 'AdvertiserOrder';
			var $belongsTo = array('Package');
			
			//Validation for users
		   var $validate =  array(
				'contract_date' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please enter contract date.'),
				/*'contract_expiry_date' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please enter contract end date.'),		*/		
				'company_name' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please enter company name.'),					 
				'address' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please enter address 1.'),
				'state' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select state.'),
				'phoneno' => array(
							'rule1'=> array(
        						'rule' => 'notEmpty',
        						'message' => 'Please enter phone 1.',
								'last'  =>true
								)/*,
							'rule2' => array(
								'rule' =>'numeric',
								'message'=>'Please enter Numeric Phone no.'
								)*/
							),								
/*				'phoneno' => array(
								'rule' => array('phone', null, 'us'),
								'message' => 'Please enter phone number in correct format.'),	*/																				 				'advertiser_name' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please enter contact name.'),
				'package_id' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select type of advertisement.'),
				/*'subcategory' => array(
        						'rule' => array('checkCats'),
    	    					'message' => 'Please select Category.'),*/
				'county' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select county.'),
				'city' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select city 1.'),
				'zip'=>array(
								'rule1'=>array(
									'rule'=>'notEmpty',
									'message'=>'Please enter zip 1.'								
								),
								'rule2'=>array(
									'rule'=>array('numeric'),
									'message'=>'Please enter valid zip 1.'								
								)								
					),
/*				'zip' => array(
        						'rule' =>array('postal', null, 'us'),
        						'message' => 'Please enter zip in correct format.'),*/
				'email' => array(
							  'emailRule-1'=> array(
									'rule' => 'email',
									'message' => 'Please insert valid email-id.'
									)
							  ),
				'salesperson' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select Salesperson.')/*,											,							  								
				'credit_name' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please enter name on credit card.'),
				'credit_number' => array(
								'rule' => array('cc', array('visa', 'maestro', 'amex', 'disc'), false, null),
								'message' => 'The credit card number you supplied was invalid.'),
				'cvv' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please enter cvv number.'),
				'cvv' => array(
								'rule' => 'numeric',  
								'message' => 'Please enter only numbers in cvv.'),
				'card_exp_month' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select credit card expiry month.'),
				'card_exp_year' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please select credit card expiry year.')*/
				 );	
		
		function advertiserOrderEditDetail($id=null){
			$this->id = $id;
			$AdvertiserOrder = $this->read();
			return $AdvertiserOrder;
	      }	
	
	    function packageDetail($advertiserId){
			$packageQuery = $this->query("select order_id from advertiser_profiles where id = '".$advertiserId."'");
			$packageIdQuery = $this->query("select package_id from advertiser_orders where id = '".$packageQuery[0]['advertiser_profiles']['order_id']."'");
			return $packageIdQuery[0]['advertiser_orders']['package_id'];
	      }	
		  
	    function salesPersonDetail($advertiserId){
			$salesPersonQuery = $this->query("select order_id from advertiser_profiles where id = '".$advertiserId."'");
			$salesPersonIdQuery = $this->query("select salesperson from advertiser_orders where id = '".$salesPersonQuery[0]['advertiser_profiles']['order_id']."'");
			if(isset($salesPersonIdQuery[0]['advertiser_orders']['salesperson'])) {
				return $salesPersonIdQuery[0]['advertiser_orders']['salesperson'];
			} else {
				return 0;
			}
	      }
		 /*function checkCats() {
		 	if(isset($this->data['AdvertiserOrder']['subcategory'][0]) && $this->data['AdvertiserOrder']['subcategory'][0]==0) {
				return false;
			}
				return true;
		 }*/		  
	} 
?>