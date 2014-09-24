<?php 
         class Voucher extends AppModel { 
	        var $name = 'Voucher';
			var $validate =  array(
				'title' => array(
					'rule' => 'notEmpty',
					'message' => 'Please Enter the Title.'),
				'price' => array(
					'rule1'=>array(
					'rule' => 'notEmpty',
					'message' => 'Please Enter Redemption Amount.'),
					'rule2'=>array(
					'rule' => 'numeric',
					'message' => 'Please Enter valid Redemption Amount.')
					),						
				'deal_details' => array(
					'rule' => 'notEmpty',
					'message' => 'Please Enter Deal Details.'),
				'category' => array(
					'rule' => 'notEmpty',
					'message' => 'Please Select Category.'),
				'sdate' => array(
					'rule' => 'notEmpty',
					'message' => 'Please Enter Start Date.'),
				'edate' => array(
					'rule' => 'notEmpty',
					'message' => 'Please Enter End Date.'),
				'advertiser_profile_id' => array(
					'rule' => 'notEmpty',
					'message' => 'Please Select Advertiser.')
				
			);
		
		function getCityCountyState($advertiser_id)
		{
			$cityCountyState = $this->query("select city, county, state from advertiser_profiles where id = '".$advertiser_id."'");
			return $cityCountyState;
		}

}
?>