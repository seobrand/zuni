<?php 
class DailyDeal extends AppModel { 
	        var $name = 'DailyDeal';
			var $validate =  array(
				'title' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please Enter the Title.'),
				'deal_details' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please Enter Freebie Details.'),
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
						'rule' 	  => array('home_sdate_validation'),
						'message' => 'Offer Start Date is Booked for Home page, Please Check Date Availability.'
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
						'message' => 'Offer End Date should be greater than or equal to Start Date for Home page.',
						'last'=>true
					),
					'Rule-3'=>array(
						'rule' 	  => array('home_edate_validation'),
						'message' => 'Offer End Date is Booked for Home page, Please Check Date Availability.'
					),
					'Rule-4'=>array(
						'rule' 	  => array('home_inner_date_validation'),
						'message' => 'An Offer is already placed between date range for Home, Please Check Date Availability.'
					)
				),

				'c_s_date' => array(
					'Rule-1'=> array(
						'rule' => array('home_c_s_date'),
						'message' => 'Please select Offer start date for category page.',
						'last'=>true
					),
					'Rule-2'=>array(
						'rule' 	  => array('home_c_s_date_validation'),
						'message' => 'Offer Start Date is Booked for category page, Please Check Date Availability.'
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
						'message' => 'Offer End Date should be greater than or equal to Start Date for category page.',
						'last'=>true
					),
					'Rule-3'=>array(
						'rule' 	  => array('home_c_e_date_validation'),
						'message' => 'Offer End Date is Booked for category page, Please Check Date Availability.'
					),
					'Rule-4'=>array(
						'rule' 	  => array('cats_inner_date_validation'),
						'message' => 'An Offer is already placed between date range for Category, Please Check Date Availability.'
					)
				),			
				
				'advertiser_profile_id' => array(
        		'rule' => 'notEmpty',
        		'message' => 'Please Select Advertiser.')
				
			);
		
		function getCityCountyState($advertiser_id)
		{
			$cityCountyState = $this->query("select city, county, state from advertiser_profiles where id = '".$advertiser_id."'");
			return $cityCountyState;
		}
		
	//////////////////////////////////////////////////////////////////////////////////		
			function home_sdate() {					
				if($this->data['DailyDeal']['show_on_home_page']==1 && $this->data['DailyDeal']['sdate']=='') {
								return false;	
							}
								return true;
					}		
			
			function home_edate() {
							if($this->data['DailyDeal']['show_on_home_page']==1 && $this->data['DailyDeal']['edate']=='') {
								return false;	
							} 
								return true;
					}
					
			function home_compare_date() {
						if(!empty($this->data['DailyDeal']['sdate']) && !empty($this->data['DailyDeal']['edate']))
							{
								$s_date		= $this->data['DailyDeal']['sdate'];
								$start_date	= explode('/',$s_date);
								$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
								$e_date		= $this->data['DailyDeal']['edate'];
								$expiry_date	= explode('/',$e_date);
								$expiry_date = mktime(date('h'),date('i'),date('s'),$expiry_date[0],$expiry_date[1],$expiry_date[2]);
								if($expiry_date < $start_date )	{							
									return false;						
								}		
							}
						return true;
					}			
						
			function home_sdate_validation() {
			$cond = '';
			if(isset($this->data['DailyDeal']['uid'])) {
					$cond =  ' AND id!='.$this->data['DailyDeal']['uid'];
			}
						if(!empty($this->data['DailyDeal']['sdate']) && isset($this->data['DailyDeal']['advertiser_profile_id']) && $this->data['DailyDeal']['advertiser_profile_id']!='')
							{
								$subcatname = explode('-',$this->data['DailyDeal']['subcategory']);
								$getcounty = $this->getCityCountyState($this->data['DailyDeal']['advertiser_profile_id']);
								$s_date		= $this->data['DailyDeal']['sdate'];
								$start_date	= explode('/',$s_date);
								$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);							
								if(isset($subcatname[1])) {
								$validat_sdate = $this->query("select id from daily_deals where '".$start_date."' between s_date and e_date and advertiser_county_id='".$getcounty[0]['advertiser_profiles']['county']."' and subcategory='".$subcatname[1]."' and category='".$subcatname[0]."'$cond");
								if($validat_sdate) {
									return false;
								}
								}
								return true;
					}
					return true;
				}
			
			function home_edate_validation() {
			$cond = '';
			if(isset($this->data['DailyDeal']['uid'])) {
					$cond =  ' AND id!='.$this->data['DailyDeal']['uid'];
			}
						if(!empty($this->data['DailyDeal']['edate']) && isset($this->data['DailyDeal']['advertiser_profile_id']))
							{
								$subcatname = explode('-',$this->data['DailyDeal']['subcategory']);
								$getcounty = $this->getCityCountyState($this->data['DailyDeal']['advertiser_profile_id']);
								$edate		= $this->data['DailyDeal']['edate'];
								$end_date	= explode('/',$edate);
								$end_date = mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
								if(isset($subcatname[1])) {					
								$validat_sdate = $this->query("select id from daily_deals where '".$end_date."' between s_date and e_date and advertiser_county_id='".$getcounty[0]['advertiser_profiles']['county']."' and subcategory='".$subcatname[1]."' and category='".$subcatname[0]."'$cond");
								if($validat_sdate) {
									return false;
								}
								}
								return true;
					}
					return true;
				}
			
			function home_inner_date_validation() {
						if(!empty($this->data['DailyDeal']['sdate']) && !empty($this->data['DailyDeal']['edate']))
							{							
								$cond = '';
								if(isset($this->data['DailyDeal']['uid'])) {
										$cond =  ' AND id!='.$this->data['DailyDeal']['uid'];
								}
								$subcatname = explode('-',$this->data['DailyDeal']['subcategory']);
								$getcounty = $this->getCityCountyState($this->data['DailyDeal']['advertiser_profile_id']);
								$s_date		= $this->data['DailyDeal']['sdate'];
								$start_date	= explode('/',$s_date);
								if(isset($start_date[2]) && isset($subcatname[1])) {
								$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
								$e_date		= $this->data['DailyDeal']['edate'];
								$expiry_date	= explode('/',$e_date);
								$expiry_date = mktime(date('h'),date('i'),date('s'),$expiry_date[0],$expiry_date[1],$expiry_date[2]);
								$validat_sdate = $this->query("select id from daily_deals where '".$start_date."' < s_date and '".$expiry_date."' > e_date and advertiser_county_id='".$getcounty[0]['advertiser_profiles']['county']."' and subcategory='".$subcatname[1]."' and category='".$subcatname[0]."'$cond");
								if($validat_sdate) {
									return false;
								}		
							}
							}
						return true;
					}	
	//////////////////////////////////////////////////////////////////////////////////			
			function home_c_s_date() {
				if($this->data['DailyDeal']['show_on_category']==1 && $this->data['DailyDeal']['c_s_date']=='') {
								return false;	
							}
								return true;
					}
			
			function home_c_e_date() {
							if($this->data['DailyDeal']['show_on_category']==1 && $this->data['DailyDeal']['c_e_date']=='') {
								return false;	
							} 
								return true;
					}
					
			function cats_compare_date() {
						if(!empty($this->data['DailyDeal']['c_s_date']) && !empty($this->data['DailyDeal']['c_e_date']))
							{
								$s_date		= $this->data['DailyDeal']['c_s_date'];
								$start_date	= explode('/',$s_date);
								if(isset($start_date[1]) && isset($start_date[2])) {
								$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
								$e_date		= $this->data['DailyDeal']['c_e_date'];
								$expiry_date	= explode('/',$e_date);
								$expiry_date = mktime(date('h'),date('i'),date('s'),$expiry_date[0],$expiry_date[1],$expiry_date[2]);
								if($expiry_date < $start_date )	{							
									return false;						
								}		
							}
							}
						return true;
					}
					
			function home_c_s_date_validation() {
			$cond = '';
			if(isset($this->data['DailyDeal']['uid'])) {
					$cond =  ' AND id!='.$this->data['DailyDeal']['uid'];
			}
						if(!empty($this->data['DailyDeal']['c_s_date']) && isset($this->data['DailyDeal']['advertiser_profile_id']))
							{
								$subcatname = explode('-',$this->data['DailyDeal']['subcategory']);
								$getcounty = $this->getCityCountyState($this->data['DailyDeal']['advertiser_profile_id']);
								$s_date		= $this->data['DailyDeal']['c_s_date'];
								$start_date	= explode('/',$s_date);
								if(isset($start_date[1]) && isset($start_date[2]) && isset($subcatname[1])) {
								$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);							
								$validat_sdate = $this->query("select id from daily_deals where '".$start_date."' between c_s_date and c_e_date and advertiser_county_id='".$getcounty[0]['advertiser_profiles']['county']."' and subcategory='".$subcatname[1]."' and category='".$subcatname[0]."'$cond");
									if($validat_sdate) {
										return false;
									}
								}
							return true;
						}
					return true;
				}
			
			function home_c_e_date_validation() {			
			$cond = '';
			if(isset($this->data['DailyDeal']['uid'])) {
					$cond =  ' AND id!='.$this->data['DailyDeal']['uid'];
			}
						if(!empty($this->data['DailyDeal']['c_e_date']) && isset($this->data['DailyDeal']['advertiser_profile_id']))
							{
								$subcatname = explode('-',$this->data['DailyDeal']['subcategory']);
								$getcounty = $this->getCityCountyState($this->data['DailyDeal']['advertiser_profile_id']);
								$edate		= $this->data['DailyDeal']['c_e_date'];
								$end_date	= explode('/',$edate);
								if(isset($end_date[1]) && isset($end_date[2])) {
								$end_date = mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);							
								$validat_sdate = $this->query("select id from daily_deals where '".$end_date."' between c_s_date and c_e_date and advertiser_county_id='".$getcounty[0]['advertiser_profiles']['county']."' and subcategory='".$subcatname[1]."' and category='".$subcatname[0]."'$cond");
								if($validat_sdate) {
									return false;
								}
								}
								return true;
					}
					return true;
			}			
			
			function cats_inner_date_validation() {
						if(!empty($this->data['DailyDeal']['c_s_date']) && !empty($this->data['DailyDeal']['c_e_date']))
							{							
								$cond = '';
								if(isset($this->data['DailyDeal']['uid'])) {
										$cond =  ' AND id!='.$this->data['DailyDeal']['uid'];
								}
								$subcatname = explode('-',$this->data['DailyDeal']['subcategory']);
								$getcounty = $this->getCityCountyState($this->data['DailyDeal']['advertiser_profile_id']);
								$s_date		= $this->data['DailyDeal']['c_s_date'];
								$start_date	= explode('/',$s_date);
								if(isset($start_date[1]) && isset($start_date[2])) {
								$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
								$e_date		= $this->data['DailyDeal']['c_e_date'];
								$expiry_date	= explode('/',$e_date);
								$expiry_date = mktime(date('h'),date('i'),date('s'),$expiry_date[0],$expiry_date[1],$expiry_date[2]);
								$validat_sdate = $this->query("select id from daily_deals where '".$start_date."' < c_s_date and '".$expiry_date."' > c_e_date and advertiser_county_id='".$getcounty[0]['advertiser_profiles']['county']."' and subcategory='".$subcatname[1]."' and category='".$subcatname[0]."'$cond");
								if($validat_sdate) {
									return false;
								}		
							}
							}
						return true;
					}
}
?>