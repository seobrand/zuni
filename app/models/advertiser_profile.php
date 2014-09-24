<?php 
	class AdvertiserProfile extends AppModel {
	        var $name = 'AdvertiserProfile';
			var $actsAs = array('Containable');
			var $hasMany = 'AdvertiserCategory';
			var $hasOne = array(
				 'TopTenBusiness' => array(
				 'className' => 'TopTenBusiness',
				 'dependent' => true
				 ),'FrontUser'		 
				 ); 
			//var $hasMany = array('SavingOffer');  
			
			var $validate =  array(
				'contract_date' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please enter contract date.'),
				'name' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please enter your full name.'),
					
				'city' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please select city 1.'),
				
								
				'county' => array(
				'rule1'=>array(
					'rule' => 'notEmpty',
					'message' => 'Please select county.'),				
				'rule2'=>array(
					'rule' => array('checkCounty'),
					'message' => 'Please select Valid county.')
				),				
				'state' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please select state.'),
				
				'country' => array(
        			'rule' => 'notEmpty',
        			'message' => 'Please select country.'),					
					'package_id' => array(
        				'rule' => 'notEmpty',
        				'message' => 'Please select package.'),
					
					'salesperson' => array(
        				'rule' => 'notEmpty',
        				'message' => 'Please select salesperson.'),		
					'logo' => array(
        				'rule' => 'checkLogo',
        				'message' => 'Please upload logo image.'),
					'offer_image' => array(
        				'rule' => 'checkOfferImage',
        				'message' => 'Please upload offer image.'),
					'main_image_type' => array(
        						'rule' => 'checkMainImage',
        						'message' => 'Please upload main image.'),			
				'email' => array(
								'emailRule-1'=>array(
									'rule' => 'notEmpty',
									'message' => 'Please enter email id.',
									'last'=>true
									),
								'emailRule-2'=>array(
									'rule' => 'email',
									'message' => 'Please enter valid email id.',
									'last'=>true
									),
							    'emailRule-3'=>array(
									'rule' => 'isUnique',
									'message' => 'Email id already in use, Please try another.',
									'on' => 'create'
									)
							  ),
				'data_collection_email' => array(
								'emailRule-1'=>array(
									'rule' => 'notEmpty',
									'message' => 'Please enter data collection email.',
									'last'=>true
									),
								'emailRule-2'=>array(
									'rule' => 'email',
									'message' => 'Please enter valid data collection email.'
									)
							  ),
				'phoneno'=>array(
								'rule1'=>array(
									'rule'=>'notEmpty',
									'message'=>'Please enter phone 1.'
								),
								'rule2'=>array(
									'rule'=>array('validatePhoneNo'),
									'message'=>'Please enter phone 1 in US Format.'								
								)								
					),				
				'company_name' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please enter company name.'),
				
				'address' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please enter address 1.'),
				
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
				'package_id' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please select package.'),
				'salesperson' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please select sales person.')
				 );	
				 
	 	function checkCounty() {
			if(isset($this->data['AdvertiserProfile']['county']) && $this->data['AdvertiserProfile']['county'] !='' && isset($this->data['AdvertiserProfile']['state']) && $this->data['AdvertiserProfile']['state'] !='')	 {
				$county = $this->query("select id from counties where id = ".$this->data['AdvertiserProfile']['county']." AND state_id=".$this->data['AdvertiserProfile']['state']);
				if(count($county)==0) {
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}			
		}
		 function advertiserProfileEditDetail($id=null){
			$this->id = $id;
			$AdvertiserProfile = $this->read();
			return $AdvertiserProfile;
	      }
		  function checkEmail($email){
			$EmailQuery = $this->query("select id from advertiser_profiles where email = '".$email."'");
			return $EmailQuery;
	      }		  
		  function checkEmailNid($email,$id){
			$EmailQuery = $this->query("select id from advertiser_profiles where email = '".$email."' AND id!=$id");
			return $EmailQuery;
	      }	
		  
		 function getAdvertiserId($orderId){
			$advertiserIdQuery = $this->query("select id from advertiser_profiles where order_id = '".$orderId."'");
			return $advertiserIdQuery[0]['advertiser_profiles']['id'];
	      }	
		  
		 function getAdvertiserDetail($orderId){
			$advertiserIdQuery = $this->query("select id,name,company_name from advertiser_profiles where order_id = '".$orderId."'");
			return $advertiserIdQuery;
	      }	
		function validatePhoneNo()
		{
		  if(preg_match('/\(?\d{3}\)?[-\s.]?\d{3}[-\s.]\d{4}/x', $this->data['AdvertiserProfile']['phoneno'])) {
			 return true;
			}
			 return false;
		}
		function checkLogo() {
			if((isset($this->data['AdvertiserProfile']['logo']['name']) && $this->data['AdvertiserProfile']['logo']['name']=='' && !isset($this->data['AdvertiserProfile']['old_logo'])) || (isset($this->data['AdvertiserProfile']['old_logo']) && $this->data['AdvertiserProfile']['old_logo']=='' && isset($this->data['AdvertiserProfile']['logo']['name']) && $this->data['AdvertiserProfile']['logo']['name']=='')) {
				return false;
			}
			return true;
		}
		function checkMainImage()
		{
		if(isset($this->data['AdvertiserProfile']['id']) && isset($this->data['AdvertiserProfile']['old_main_image']) && $this->data['AdvertiserProfile']['old_main_image']=='' && $this->data['AdvertiserProfile']['main_image_upload']['error'] && $this->data['AdvertiserProfile']['main_image']=='') {
			return false;
		}
		elseif(isset($this->data['AdvertiserProfile']['id']) && isset($this->data['AdvertiserProfile']['old_main_image']) && $this->data['AdvertiserProfile']['old_main_image']=='' && isset($this->data['AdvertiserProfile']['main_image_type']) && $this->data['AdvertiserProfile']['main_image_type']==1 && isset($this->data['AdvertiserProfile']['main_image_upload']['name']) && $this->data['AdvertiserProfile']['main_image_upload']['name']=="")
			{
				return false;
			}elseif(isset($this->data['AdvertiserProfile']['id']) && isset($this->data['AdvertiserProfile']['main_image_type']) && $this->data['AdvertiserProfile']['main_image_type']==0 && isset($this->data['AdvertiserProfile']['main_image']) && $this->data['AdvertiserProfile']['main_image']==""){
				return false;
			}
		elseif(isset($this->data['AdvertiserProfile']['main_image_type']) && $this->data['AdvertiserProfile']['main_image_type']==1 && isset($this->data['AdvertiserProfile']['main_image']['name']) && $this->data['AdvertiserProfile']['main_image']['name']=="")
			{
				return false;
			}elseif(isset($this->data['AdvertiserProfile']['main_image_type']) && $this->data['AdvertiserProfile']['main_image_type']==0 && isset($this->data['AdvertiserProfile']['main_image']) && $this->data['AdvertiserProfile']['main_image']==""){
				return false;
			}
			return true;
		}

		function checkOfferImage()
		{
			if((isset($this->data['AdvertiserProfile']['offer_image']['name']) && $this->data['AdvertiserProfile']['offer_image']['name']=='' && !isset($this->data['AdvertiserProfile']['old_offer_image'])) || (isset($this->data['AdvertiserProfile']['old_offer_image']) && $this->data['AdvertiserProfile']['old_offer_image']=='' && isset($this->data['AdvertiserProfile']['offer_image']['name']) && $this->data['AdvertiserProfile']['offer_image']['name']=='')) {
				return false;
			}
			return true;
		}
		
	}
?>