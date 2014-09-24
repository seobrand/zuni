<?php 
	class SavingOffer extends AppModel {
	        var $name = 'SavingOffer';
			
			var $belongsTo = 'AdvertiserProfile';
		
			//Validation for Country
			var $validate =  array(
				 'title'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please insert saving offer title.'),
				'off'=>array('rule' => 'checkOff',
        		 				'message' => 'Please insert valid additional off.'),
				 'description'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please enter description.'),
				 /*'subcategory'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please select category.'),*/
				 'advertiser_profile_id'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please select advertiser.'),
				 'offer_start_date'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please enter offer start date.'),
				 'offer_expiry_date'=>array('rule' => 'notEmpty',
        		 				'message' => 'Please enter offer expiry date.'),
				'saving_offer'=>array('rule'=>'checkMainOffer',
								'message' => 'Main Offer is already exist.'),
				'homecat'=>array('rule'=>'checkHomeCat',
								'message' => 'Please select home category.')				
				);
		function checkMainOffer() {	
			$conditions = '';
			
			if(isset($this->data['SavingOffer']['saving_offer']) && $this->data['SavingOffer']['saving_offer']=='current_saving_offer'){
			if(isset($this->data['SavingOffer']['id'])) {
				$conditions = 'SavingOffer.id!='.$this->data['SavingOffer']['id'];
			}
			App::import('model','SavingOffer');
			$this->SavingOffer = new SavingOffer();
			$main = $this->SavingOffer->find('count',array('conditions'=>array('SavingOffer.advertiser_profile_id'=>$this->data['SavingOffer']['advertiser_profile_id'],'SavingOffer.current_saving_offer'=>1,$conditions)));
			if($main==1) {
				return false;
			}
			return true;
			} else {
				return true;
			}
		}		
		function offerEditDetail($id=null){
			   $this->id = $id;
			   $Offer = $this->read();
			   return $Offer;
	      }
	function checkOff() {
		if($this->data['SavingOffer']['off_unit']!=2 && $this->data['SavingOffer']['off']=='') {
			return false;
		}
		return true;
	}	
	function checkHomeCat() {
		if($this->data['SavingOffer']['show_at_home']==1 && $this->data['SavingOffer']['homecat']=='') {
			return false;
		}
		return true;
	}	
	  
	} 
?>