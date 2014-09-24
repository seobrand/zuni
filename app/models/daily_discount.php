<?php 
class DailyDiscount extends AppModel {
	        var $name = 'DailyDiscount';
			var $validate =  array(
				'title' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please Enter the Title.'),
				'discount_details' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please Enter Big Deal Details.'),
				'subcategory' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please Select Category.'),
				'sdate' => array(
					'Rule-1'=> array(
						'rule' => array('home_sdate'),
						'message' => 'Please select Offer start date for Home page.',
						'last'=>true
					),
					'Rule-2'=>array(
						'rule' 	  => array('county_sdate_validation'),
						'message' => 'County limit has been reached for home page Date range, Please Check Date Availability'
					)				
				),				
				'edate' => array(
					'Rule-1'=> array(
						'rule' => array('home_edate'),
						'message' => 'Please select Offer expiry date for Home page.',
						'last'=>true
					),
					'Rule-2'=>array(
						'rule' 	  => array('home_compare_date'),
						'message' => 'Offer End Date should be greater than or equal to Start Date for Home page.'
					)
				),
				'c_s_date' => array(
					'Rule-1'=> array(
						'rule' => array('home_c_s_date'),
						'message' => 'Please select Offer start date for category page.',
						'last'=>true
					),
					'Rule-2'=>array(
						'rule' 	  => array('county_c_sdate_validation'),
						'message' => 'County limit has been reached for category page Date range, Please Check Date Availability.'
					)
				),				
				'c_e_date' => array(
					'Rule-1'=> array(
						'rule' => array('home_c_e_date'),
						'message' => 'Please select Offer expiry date for category page.',
						'last'=>true
					),
					'Rule-2'=>array(
						'rule' 	  => array('cats_compare_date'),
						'message' => 'Offer End Date should be greater than or equal to Start Date for category page.'
					)
				),			
				'original_price' => array(
        		'rule' =>'numeric',
        		'message' => 'Please Enter Original Price.'),
				'before_noon_saving' => array(
        		'rule' =>'numeric',
        		'message' => 'Please Enter Valid Saving.'),
				/*'after_noon_saving' => array(
        		'rule' =>'numeric',
        		'message' => 'Please Enter Valid After Noon Saving.'),*/
				'total_limit' => array(
        		'rule' =>'numeric',
        		'message' => 'Please Enter Max # to be sold.'),
				'limit_per_user' => array(
        		'rule' =>'numeric',
        		'message' => 'Please Enter Voucher limit per user.'),
				'tipping_point' => array(
        		'rule' =>'numeric',
        		'message' => 'Please Enter Tipping point.'),
				'advertiser_profile_id' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please Select Advertiser.')				
			);			
			function getCityCountyState($advertiser_id) {
				$cityCountyState = $this->query("select city, county, state from advertiser_profiles where id = '".$advertiser_id."'");
				return $cityCountyState;
			}
	//////////////////////////////////////////////////////////////////////////////////			
			function home_sdate() {				
				if($this->data['DailyDiscount']['show_on_home_page']==1 && $this->data['DailyDiscount']['sdate']=='') {
								return false;	
							}
								return true;
			}
			
			function home_edate() {
							if($this->data['DailyDiscount']['show_on_home_page']==1 && $this->data['DailyDiscount']['edate']=='') {
								return false;	
							} 
								return true;
					}
					
			function home_compare_date() {
						if(!empty($this->data['DailyDiscount']['sdate']) && !empty($this->data['DailyDiscount']['edate']))
							{
								$s_date		= $this->data['DailyDiscount']['sdate'];
								$start_date	= explode('/',$s_date);
								$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
								$e_date		= $this->data['DailyDiscount']['edate'];
								$expiry_date	= explode('/',$e_date);
								$expiry_date = mktime(date('h'),date('i'),date('s'),$expiry_date[0],$expiry_date[1],$expiry_date[2]);
								if($expiry_date < $start_date )	{							
									return false;						
								}		
							}
						return true;
					}
			
			function county_sdate_validation() {
					$cond = '';
					if(isset($this->data['DailyDiscount']['uid'])) {
							$cond =  'DailyDiscount.id!='.$this->data['DailyDiscount']['uid'];
					}
								if(!empty($this->data['DailyDiscount']['sdate']) && !empty($this->data['DailyDiscount']['edate']))
									{
										$getcounty 	= $this->getCityCountyState($this->data['DailyDiscount']['advertiser_profile_id']);
										$county_id 	= $getcounty[0]['advertiser_profiles']['county'];
										$limit 		= $this->getCountyLimit($county_id);
										$sdate		= strtotime($this->data['DailyDiscount']['sdate']);
										$edate		= strtotime($this->data['DailyDiscount']['edate']);
										$oneday 	= 0;
										App::import('model','DailyDiscount');
										$this->DailyDiscount = new DailyDiscount();
										while($sdate<$edate) {
											$sdate = $sdate+$oneday;											
											$limit_search = $this->DailyDiscount->find('count',array('conditions'=>array('DailyDiscount.advertiser_county_id'=>$county_id,'DailyDiscount.show_on_home_page'=>1,'(DailyDiscount.s_date<='.$sdate.' AND DailyDiscount.e_date >='.$sdate.')',$cond)));											
											//echo date(DATE_FORMAT,$sdate).'-'.$limit_search.'<br />';
											if($limit_search>=$limit) {
												return false;
												break;
											}											
											$oneday = 86400;
										}
										//exit;
							return true;
						}
					return true;
				}	
			
			function county_c_sdate_validation() {
					$cond = '';
					if(isset($this->data['DailyDiscount']['subcategory']) && $this->data['DailyDiscount']['subcategory']!='') {
					$cat_id = explode('-',$this->data['DailyDiscount']['subcategory']);
								if(isset($this->data['DailyDiscount']['uid'])) {
										$cond =  'DailyDiscount.id!='.$this->data['DailyDiscount']['uid'];
								}
								if(!empty($this->data['DailyDiscount']['c_s_date']) && !empty($this->data['DailyDiscount']['c_e_date']))
									{										
										$getcounty 	= $this->getCityCountyState($this->data['DailyDiscount']['advertiser_profile_id']);
										$county_id 	= $getcounty[0]['advertiser_profiles']['county'];
										$limit 		= $this->getCountyLimit_cats($county_id,$cat_id[0]);
										if($limit) {								
											$sdate		= strtotime($this->data['DailyDiscount']['c_s_date']);
											$edate		= strtotime($this->data['DailyDiscount']['c_e_date']);
											$oneday = 0;										
											App::import('model','DailyDiscount');
											$this->DailyDiscount = new DailyDiscount();
											while($sdate<$edate) {
												$sdate = $sdate+$oneday;																						
												$limit_search = $this->DailyDiscount->find('count',array('conditions'=>array('DailyDiscount.advertiser_county_id'=>$county_id,'DailyDiscount.category'=>$cat_id[0],'DailyDiscount.show_on_category'=>1,'(DailyDiscount.c_s_date<='.$sdate.' AND DailyDiscount.c_e_date >'.$sdate.')',$cond)));										//echo date(DATE_FORMAT,$sdate).'-'.$limit_search.'<br />';
												if($limit_search>=$limit) {
													return false;
													break;
												}					
												$oneday = 86400;
											}
										}
							return true;
						}
						}
					return true;
				}
			
			function getCountyLimit($id) {
					App::import('model','County');
					$this->County = new County(); 
					$limit = $this->County->find('first',array('fields'=>array('County.advertiser_limit_home'),'conditions'=>array('County.id'=>$id)));
					return $limit['County']['advertiser_limit_home'];
			}
			
			function getCountyLimit_cats($id,$cat) {
					App::import('model','CategoryLimit');
					$this->CategoryLimit = new CategoryLimit(); 
					$limit = $this->CategoryLimit->find('first',array('fields'=>array('CategoryLimit.max_limit'),'conditions'=>array('CategoryLimit.county_id'=>$id,'CategoryLimit.category_id'=>$cat)));
					if(!empty($limit)) {
						$limit_total =  $limit['CategoryLimit']['max_limit'];
					} else {
						$limit_total =  '';
					}
					return $limit_total;
			}	
	//////////////////////////////////////////////////////////////////////////////////			
			function home_c_s_date() {
				if($this->data['DailyDiscount']['show_on_category']==1 && $this->data['DailyDiscount']['c_s_date']=='') {
								return false;	
							}
								return true;
					}
			
			function home_c_e_date() {
							if($this->data['DailyDiscount']['show_on_category']==1 && $this->data['DailyDiscount']['c_e_date']=='') {
								return false;	
							} 
								return true;
					}
					
			function cats_compare_date() {
						if(!empty($this->data['DailyDiscount']['c_s_date']) && !empty($this->data['DailyDiscount']['c_e_date']))
							{
								$s_date		= $this->data['DailyDiscount']['c_s_date'];
								$start_date	= explode('/',$s_date);
								if(isset($start_date[1]) && isset($start_date[2])) {
								$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
								$e_date		= $this->data['DailyDiscount']['c_e_date'];
								$expiry_date	= explode('/',$e_date);
								$expiry_date = mktime(date('h'),date('i'),date('s'),$expiry_date[0],$expiry_date[1],$expiry_date[2]);
								if($expiry_date < $start_date )	{							
									return false;						
								}		
							}
							}
						return true;
					}
}
?>