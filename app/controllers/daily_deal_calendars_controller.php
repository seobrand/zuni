<?php 
/*
   Coder	:	 Manoj
   Date  	: 	 18 May 2011
*/ 


class DailyDealCalendarsController extends AppController{
 var $name = 'DailyDealCalendars'; 
 var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar','booked'); 
 var $components = array('Auth','common','Session','Cookie','RequestHandler');
 var $layout = 'admin'; 


/*********************************************************************************************************************************************************************/

/***--------------------This function Set the All Advertiser info in popup which booked the particular date for all county ------------------------------------------*/

function calAdvertiserList($timestamp=null,$county=null,$category=null,$advertiser=null,$searchType=null)
{
		$this->layout = '';
		$this->set('advertiserList',$this->common->getAllAdvertiserProfile()); //  List advertisers
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		$this->set('categoryList',$this->common->getAllCategory()); 	//list categories
		$this->set('subcategoryList',$this->common->getAllSubCategory()); 	//list subcategories
		$mainCondition='';
		$condArr=' AND daily_deals.advertiser_county_id='.$county;
		$seacrhCriteria='';
		if(isset($searchType) && $searchType!='' && $searchType=='home')
		{
			$condArr.=" AND daily_deals.show_on_home_page=1";
			$seacrhCriteria='home';
			$mainCondition="(daily_deals.s_date IN (select s_date from daily_deals where '".$timestamp."' between s_date and e_date) 
	AND daily_deals.e_date IN (select e_date from daily_deals where '".$timestamp."' between s_date and e_date))";
		}
		elseif(isset($searchType) && $searchType!='' && $searchType=='category')
		{
			$condArr.=" AND daily_deals.show_on_category=1";
			$seacrhCriteria='category';
			$mainCondition="(daily_deals.c_s_date IN (select c_s_date from daily_deals where '".$timestamp."' between c_s_date and c_e_date) 
	AND daily_deals.c_e_date IN (select c_e_date from daily_deals where '".$timestamp."' between c_s_date and c_e_date))";		
		}
		else
		{
			$mainCondition="(daily_deals.s_date IN (select s_date from daily_deals where '".$timestamp."' between s_date and e_date) 
	AND daily_deals.e_date IN (select e_date from daily_deals where '".$timestamp."' between s_date and e_date)) OR 
	(daily_deals.c_s_date IN (select c_s_date from daily_deals where '".$timestamp."' between c_s_date and c_e_date) 
	AND daily_deals.c_e_date IN (select c_e_date from daily_deals where '".$timestamp."' between c_s_date and c_e_date))";
		}
		$this->set('seacrhCriteria',$seacrhCriteria);		
		
		if(isset($category) && $category!='' && $category!=0 && $category!='null')
		{
			$catArr=explode('-',$category);
			$condArr.=" AND daily_deals.category=".$catArr[0];
			$condArr.=" AND daily_deals.subcategory=".$catArr[1];
		}
		if(isset($advertiser) && $advertiser!='' && $advertiser!=0 && $advertiser!='null')
			$condArr.=" AND daily_deals.advertiser_profile_id=".$advertiser;
			
		
		
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();

	$adv_details=	$this->AdvertiserProfile->query("SELECT 
    advertiser_profiles.id, 
    advertiser_profiles.name, 
    advertiser_profiles.company_name, 
    advertiser_profiles.county,
	daily_deals.s_date,
	daily_deals.e_date,
	daily_deals.c_s_date,
	daily_deals.c_e_date,
	daily_deals.category,
	daily_deals.subcategory,
	daily_deals.status
FROM 
    advertiser_profiles
LEFT JOIN 
    daily_deals
ON
    advertiser_profiles.id = daily_deals.advertiser_profile_id 
WHERE 
	($mainCondition)
	$condArr
	");  
		//pr($adv_details);
		$this->set('adv_details',$adv_details);
		$this->set('timestamp',$timestamp);
}





/***----------------------This function Set the Daily Discount all booked date for particular county/category/advertiser from database-------------------------------*/
	
	function calendarDailyDeal($year = null, $month = null, $county=null, $advertiser=null, $category=null){
		
		$this->set('countyList',$this->common->getAllCounty()); 	              //  List counties
		$this->set('advertiserList',$this->common->getAllAdvertiserCompany());    //  List advertisers

		if(isset($this->data['DailyDealCalendars']['county']) || isset($this->params['pass'][2]) && $this->params['pass'][2] !='')
		{
		 if($this->data['DailyDealCalendars']['county'] !=''){ 
		 $county=$this->data['DailyDealCalendars']['county'];
		 }else if($this->params['pass'][2] !=''){
		  $county=$this->params['pass'][2];
		 }else{
		   $county =0;
		 }
		 $this->set('county',$county); 
		}
		
		
		if(isset($this->data['DailyDealCalendars']['advertiser']) || isset($this->params['pass'][3]) && $this->params['pass'][3] !='')
		 {
			 if($this->data['DailyDealCalendars']['county'] !=''){ 
			  $advertiser=$this->data['DailyDealCalendars']['advertiser'];
			 }else if($this->params['pass'][3] !=''){
			  $advertiser=$this->params['pass'][3];
			 }else{
			  $advertiser=0;
			 }
		 $this->set('advertiser',$advertiser);
		 }
		 
		 if(isset($this->data['DailyDealCalendars']['category']) || isset($this->params['pass'][4]) && $this->params['pass'][4] !=''){
		 
		 
		 if($this->data['DailyDealCalendars']['county'] !=''){ 
		  $category=$this->data['DailyDealCalendars']['category'];
		 }else if($this->params['pass'][4] !=''){
		  $category=$this->params['pass'][4];
		 }else{
		  $category=0;
		 }
		 $this->set('category',$category); 
		}
		 
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'DailyDealCalendars/calendarDailyDeal'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'DailyDealCalendars';
		$data = null;
		$dataMonth = null;
 		
		if(!$year || !$month) {
			$year = date('Y');
			$month = date('M');
			$month_num = date('n');
			$item = null;
		}
 
		$flag = 0;
 
		for($i = 0; $i < 12; $i++) { // check the month is valid if set
			if(strtolower($month) == $month_list[$i]) {
				if(intval($year) != 0) {
					$flag = 1;
					$month_num = $i + 1;
					$month_name = $month_list[$i];
					break;
				}
			}
		}
 
		if($flag == 0) { // if no date set, then use the default values
			$year = date('Y');
			$month = date('M');
			$month_name = date('F');
			$month_num = date('m');
		}
		
		
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
		$this->set('data', $data);
	
	}
		
	
	
/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------*/



/***--------------This function Set the Daily Discount booked date which are show on home page for particular county/category/advertiser from database---------------*/
	
	function homeCalDailyDeal($year = null, $month = null, $county=null, $advertiser=null, $category=null){
		
		$this->set('countyList',$this->common->getAllCounty()); 	              //  List counties
		$this->set('advertiserList',$this->common->getAllAdvertiserCompany());    //  List advertisers

		if(isset($this->data['DailyDealCalendars']['county']) || isset($this->params['pass'][2]) && $this->params['pass'][2] !='')
		{
		 if($this->data['DailyDealCalendars']['county'] !=''){ 
		 $county=$this->data['DailyDealCalendars']['county'];
		 }else if($this->params['pass'][2] !=''){
		  $county=$this->params['pass'][2];
		 }else{
		   $county =0;
		 }
		 $this->set('county',$county); 
		}
		
		
		if(isset($this->data['DailyDealCalendars']['advertiser']) || isset($this->params['pass'][3]) && $this->params['pass'][3] !='')
		 {
			 if($this->data['DailyDealCalendars']['county'] !=''){ 
			  $advertiser=$this->data['DailyDealCalendars']['advertiser'];
			 }else if($this->params['pass'][3] !=''){
			  $advertiser=$this->params['pass'][3];
			 }else{
			  $advertiser=0;
			 }
		 $this->set('advertiser',$advertiser);
		 }
		 
		 if(isset($this->data['DailyDealCalendars']['category']) || isset($this->params['pass'][4]) && $this->params['pass'][4] !=''){
		 
		 
		 if($this->data['DailyDealCalendars']['county'] !=''){ 
		  $category=$this->data['DailyDealCalendars']['category'];
		 }else if($this->params['pass'][4] !=''){
		  $category=$this->params['pass'][4];
		 }else{
		  $category=0;
		 }
		 $this->set('category',$category); 
		}
		 
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'DailyDealCalendars/homeCalDailyDeal'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'DailyDealCalendars';
		$data = null;
		$dataMonth = null;
 		
		if(!$year || !$month) {
			$year = date('Y');
			$month = date('M');
			$month_num = date('n');
			$item = null;
		}
 
		$flag = 0;
 
		for($i = 0; $i < 12; $i++) { // check the month is valid if set
			if(strtolower($month) == $month_list[$i]) {
				if(intval($year) != 0) {
					$flag = 1;
					$month_num = $i + 1;
					$month_name = $month_list[$i];
					break;
				}
			}
		}
 
		if($flag == 0) { // if no date set, then use the default values
			$year = date('Y');
			$month = date('M');
			$month_name = date('F');
			$month_num = date('m');
		}
		
		

		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
	
	}
		
	
	
/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------*/




/***--------------This function Set the Daily Discount booked date which are show on category page for particular county/category/advertiser from database-----------*/
	
	function categoryCalDailyDeal($year = null, $month = null, $county=null, $advertiser=null, $category=null){
		
		$this->set('countyList',$this->common->getAllCounty()); 	              //  List counties
		$this->set('advertiserList',$this->common->getAllAdvertiserCompany());    //  List advertisers
		
		if(isset($this->data['DailyDealCalendars']['county']) || isset($this->params['pass'][2]) && $this->params['pass'][2] !='')
		{
		 if($this->data['DailyDealCalendars']['county'] !=''){ 
		 $county=$this->data['DailyDealCalendars']['county'];
		 }else if($this->params['pass'][2] !=''){
		  $county=$this->params['pass'][2];
		 }else{
		   $county =0;
		 }
		 $this->set('county',$county); 
		}
		
		
		if(isset($this->data['DailyDealCalendars']['advertiser']) || isset($this->params['pass'][3]) && $this->params['pass'][3] !='')
		 {
			 if($this->data['DailyDealCalendars']['county'] !=''){ 
			  $advertiser=$this->data['DailyDealCalendars']['advertiser'];
			 }else if($this->params['pass'][3] !=''){
			  $advertiser=$this->params['pass'][3];
			 }else{
			  $advertiser=0;
			 }
		 $this->set('advertiser',$advertiser);
		 }
		 
		 if(isset($this->data['DailyDealCalendars']['category']) || isset($this->params['pass'][4]) && $this->params['pass'][4] !=''){
		 
		 
		 if($this->data['DailyDealCalendars']['county'] !=''){ 
		  $category=$this->data['DailyDealCalendars']['category'];
		 }else if($this->params['pass'][4] !=''){
		  $category=$this->params['pass'][4];
		 }else{
		  $category=0;
		 }
		 $this->set('category',$category); 
		}
		 
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'DailyDealCalendars/categoryCalDailyDeal'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'DailyDealCalendars';
		$data = null;
		$dataMonth = null;
 		
		if(!$year || !$month) {
			$year = date('Y');
			$month = date('M');
			$month_num = date('n');
			$item = null;
		}
 
		$flag = 0;
 
		for($i = 0; $i < 12; $i++) { // check the month is valid if set
			if(strtolower($month) == $month_list[$i]) {
				if(intval($year) != 0) {
					$flag = 1;
					$month_num = $i + 1;
					$month_name = $month_list[$i];
					break;
				}
			}
		}
 
		if($flag == 0) { // if no date set, then use the default values
			$year = date('Y');
			$month = date('M');
			$month_name = date('F');
			$month_num = date('m');
		}
		
		

		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
	
	}
		
	
	
/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------*/



/***---------------This function Set the Daily Discount booked date which are expired for particular county/category/advertiser from database-----------------------*/
	
	function expiredCalDailyDeal($year = null, $month = null, $county=null, $advertiser=null, $category=null){
		
		$this->set('countyList',$this->common->getAllCounty()); 	              //  List counties
		$this->set('advertiserList',$this->common->getAllAdvertiserCompany());    //  List advertisers

		if(isset($this->data['DailyDealCalendars']['county']) || isset($this->params['pass'][2]) && $this->params['pass'][2] !='')
		{
		 if($this->data['DailyDealCalendars']['county'] !=''){ 
		 $county=$this->data['DailyDealCalendars']['county'];
		 }else if($this->params['pass'][2] !=''){
		  $county=$this->params['pass'][2];
		 }else{
		   $county =0;
		 }
		 $this->set('county',$county); 
		}
		
		
		if(isset($this->data['DailyDealCalendars']['advertiser']) || isset($this->params['pass'][3]) && $this->params['pass'][3] !='')
		 {
			 if($this->data['DailyDealCalendars']['county'] !=''){ 
			  $advertiser=$this->data['DailyDealCalendars']['advertiser'];
			 }else if($this->params['pass'][3] !=''){
			  $advertiser=$this->params['pass'][3];
			 }else{
			  $advertiser=0;
			 }
		 $this->set('advertiser',$advertiser);
		 }
		 
		 if(isset($this->data['DailyDealCalendars']['category']) || isset($this->params['pass'][4]) && $this->params['pass'][4] !=''){
		 
		 
		 if($this->data['DailyDealCalendars']['county'] !=''){ 
		  $category=$this->data['DailyDealCalendars']['category'];
		 }else if($this->params['pass'][4] !=''){
		  $category=$this->params['pass'][4];
		 }else{
		  $category=0;
		 }
		 $this->set('category',$category); 
		}
		 
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'DailyDealCalendars/expiredCalDailyDeal'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'DailyDealCalendars';
		$data = null;
		$dataMonth = null;
 		
		if(!$year || !$month) {
			$year = date('Y');
			$month = date('M');
			$month_num = date('n');
			$item = null;
		}
 
		$flag = 0;
 
		for($i = 0; $i < 12; $i++) { // check the month is valid if set
			if(strtolower($month) == $month_list[$i]) {
				if(intval($year) != 0) {
					$flag = 1;
					$month_num = $i + 1;
					$month_name = $month_list[$i];
					break;
				}
			}
		}
 
		if($flag == 0) { // if no date set, then use the default values
			$year = date('Y');
			$month = date('M');
			$month_name = date('F');
			$month_num = date('m');
		}
		
		

		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
	
	}
		
	
	
/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------*/



/***---------------This function Set the Daily Discount booked date which are scheduled for particular county/category/advertiser from database----------------------*/
	function scheduledCalDailyDeal($year = null, $month = null, $county=null, $advertiser=null, $category=null){
	
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		$this->set('advertiserList',$this->common->getAllAdvertiserCompany()); //  List advertisers
		
		if(isset($this->data['DailyDealCalendars']['county']) || isset($this->params['pass'][2]) && $this->params['pass'][2] !='')
		{
		 if($this->data['DailyDealCalendars']['county'] !=''){ 
		 $county=$this->data['DailyDealCalendars']['county'];
		 }else if($this->params['pass'][2] !=''){
		  $county=$this->params['pass'][2];
		 }else{
		   $county =0;
		 }
		 $this->set('county',$county); 
		}
		
		
		if(isset($this->data['DailyDealCalendars']['advertiser']) || isset($this->params['pass'][3]) && $this->params['pass'][3] !='')
		 {
			 if($this->data['DailyDealCalendars']['county'] !=''){ 
			  $advertiser=$this->data['DailyDealCalendars']['advertiser'];
			 }else if($this->params['pass'][3] !=''){
			  $advertiser=$this->params['pass'][3];
			 }else{
			  $advertiser=0;
			 }
		 $this->set('advertiser',$advertiser);
		 }
		 
		 if(isset($this->data['DailyDealCalendars']['category']) || isset($this->params['pass'][4]) && $this->params['pass'][4] !=''){
		 
		 
		 if($this->data['DailyDealCalendars']['county'] !=''){ 
		  $category=$this->data['DailyDealCalendars']['category'];
		 }else if($this->params['pass'][4] !=''){
		  $category=$this->params['pass'][4];
		 }else{
		  $category=0;
		 }
		 $this->set('category',$category); 
		}
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'DailyDealCalendars/scheduledCalDailyDeal'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'DailyDealCalendars';
		$data = null;
		$dataMonth = null;
		if(!$year || !$month) {
			$year = date('Y');
			$month = date('M');
			$month_num = date('n');
			$item = null;
		}
 
		$flag = 0;
 
		for($i = 0; $i < 12; $i++) { // check the month is valid if set
			if(strtolower($month) == $month_list[$i]) {
				if(intval($year) != 0) {
					$flag = 1;
					$month_num = $i + 1;
					$month_name = $month_list[$i];
					break;
				}
			}
		}
 
		if($flag == 0) { // if no date set, then use the default values
			$year = date('Y');
			$month = date('M');
			$month_name = date('F');
			$month_num = date('m');
		}
		

		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
 }
	
	
/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------*/


/*********************************************************************************************************************************************************************/
/*********************************************************************************************************************************************************************/

/***-----------------------This function Set the Css for Particlar Theme Selection---------------------------------------------------------------------------------*/
	function setCss($id)
	 {
			$this->Cookie->delete('css_name');
			if($this->params['pass'][0]=='0'){
			   $this->Cookie->write('css_name','theme',false);
			   $this->redirect(array('action' => $this->params['pass'][1]));
			}else{
			   $this->Cookie->write('css_name','theme'.$this->params['pass'][0],false);
			   $this->redirect(array('action' => $this->params['pass'][1]));
		    }
			

	 }
					
/*
    this function is checking username and password in database
	and if true then redirect to home page
	*/

	function beforeFilter() { 
		$this->set('title_for_layout','Freebie Calendar');
        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );

			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
   	}
	

	/* This function is setting all info about current Admins in 
	currentAdmin array so we can use it anywhere lie name id etc.*/

	function beforeRender(){
		$this->set('currentAdmin', $this->Auth->user());
		$this->set('cssName',$this->Cookie->read('css_name'));
        $this->set('groupDetail',$this->common->adminDetails());
		$this->set('common',$this->common);
		//$this->Ssl->force();
	} 


}
?>