<?php 
	class VipOffer extends AppModel { 
	        var $name = 'VipOffer';
		
			//Validation for VipOffer
			var $validate =  array(
				 'advertiser_profile_id'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please select advertiser.'),
				 'title'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please insert vip offer title.'),
				 'price'=>array('rule' => 'Numeric',
        		 				'message' => 'Please insert vip offer price.'),
				 'category'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please select a category.'),
				 'description'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please insert vip offer description.'),
				 'offer_start_date'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please enter offer start date.'),
				 'offer_expiry_date'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please enter offer expiry date.')		
				 );
				 
		function offerEditDetail($id=null){
			   $this->id = $id;
			   $Offer = $this->read();
			   return $Offer;
	      }	
		 
	} 
?>