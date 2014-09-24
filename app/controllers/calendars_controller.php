<?php 
/*
   Coder	: 	Manoj
   Date  	: 	12 May 2011
*/ 


class CalendarsController extends AppController{
 var $name = 'Calendars';
 var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar','Booked'); 
 var $components = array('Auth','common','Session','Cookie','RequestHandler');
 var $layout = ''; 
 function index(){}
/*---------------------------------This function set the booked date of daily discount to popup view-----------------------------------------------------------------*/

	function calendar($year = null, $month = null, $id = null, $cats=null,$discount_id = null) {
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'calendars/calendar'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'calendars';
		$data = null;
 		
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
 		/*------------------------------This section find current month book date and set to view----------------------------------*/
		
		$s_date='1/'.$month_num.'/'.$year;
		$start_date	= explode('/',$s_date);	
		$start_date = mktime(0,0,0,$start_date[1],$start_date[0],$start_date[2]);   //find the start date timestamp of the month
		$days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));			// find days in that month
		$end_date = ($start_date + ($days_in_month * 86400))-1;							//find end date timestamp of the the month
		App::import('model','DailyDiscount');
		$this->DailyDiscount = new DailyDiscount();
		
		$cond=array('AdvertiserProfile.id'=>$id);	
		$this->loadModel('AdvertiserProfile');
		$county=$this->AdvertiserProfile->find('first',array('fields'=>array('id','county'),'conditions'=>$cond));
		if($county)
		$county=$county['AdvertiserProfile']['county'];
		
		//pr($data);
		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
		$this->set('data', $data);
		$this->set('id', $id);
		$this->set('cats', $cats);
		$this->set('county', $county);				
		$this->set('discount_id',$discount_id);
	}

/*---------------------------------This function set the booked date of daily discount to popup view-----------------------------------------------------------------*/

	function ccalendar($year = null, $month = null, $id = null, $cats=null,$discount_id = null) {
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'calendars/ccalendar'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'calendars';
		$data = null;
 		
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
 		/*------------------------------This section find current month book date and set to view----------------------------------*/
		
		$s_date='1/'.$month_num.'/'.$year;
		$start_date	= explode('/',$s_date);	
		$start_date = mktime(0,0,0,$start_date[1],$start_date[0],$start_date[2]);   //find the start date timestamp of the month
		$days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));			// find days in that month
		$end_date = ($start_date + ($days_in_month * 86400))-1;							//find end date timestamp of the the month
		App::import('model','DailyDiscount');
		$this->DailyDiscount = new DailyDiscount();
		
		$cond=array('AdvertiserProfile.id'=>$id);	
		$this->loadModel('AdvertiserProfile');
		$county=$this->AdvertiserProfile->find('first',array('fields'=>array('id','county'),'conditions'=>$cond));
		if($county)
		$county=$county['AdvertiserProfile']['county'];
		
		//pr($data);
		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
		$this->set('data', $data);
		$this->set('id', $id);
		$this->set('cats', $cats);
		$this->set('county', $county);		
		$this->set('discount_id',$discount_id);
	}
	
		
/*---------------------------------This function set the booked date of daily deal to popup view-----------------------------------------------------------------*/	
	
	function calendarDeal($year = null, $month = null, $id = null, $cats=null) {
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'calendars/calendarDeal'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'calendars';
		$data = null;
 		
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
 		/*------------------------------This section find current month book date and set to view----------------------------------*/
		
		$s_date='1/'.$month_num.'/'.$year;
		$start_date	= explode('/',$s_date);	
		$start_date = mktime(0,0,0,$start_date[1],$start_date[0],$start_date[2]);   //find the start date timestamp of the month
		$days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));			// find days in that month
		$end_date = ($start_date + ($days_in_month * 86400))-1;							//find end date timestamp of the the month
		App::import('model','DailyDeal');
		$this->DailyDeal = new DailyDeal();
		
		$cond=array('AdvertiserProfile.id'=>$id);	
		$this->loadModel('AdvertiserProfile');
		$county=$this->AdvertiserProfile->find('first',array('fields'=>array('id','county'),'conditions'=>$cond));
		if($county)
		$county=$county['AdvertiserProfile']['county'];
		
		//pr($data);
		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
		$this->set('data', $data);
		$this->set('id', $id);
		$this->set('cats', $cats);
		$this->set('county', $county);
	}
	

/*---------------------------------This function set the booked date of daily deal to popup view-----------------------------------------------------------------*/	
	
	function ccalendarDeal($year = null, $month = null, $id = null, $cats=null) {
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'calendars/ccalendarDeal'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'calendars';
		$data = null;
 		
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
 		/*------------------------------This section find current month book date and set to view----------------------------------*/
		
		$s_date='1/'.$month_num.'/'.$year;
		$start_date	= explode('/',$s_date);	
		$start_date = mktime(0,0,0,$start_date[1],$start_date[0],$start_date[2]);   //find the start date timestamp of the month
		$days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));			// find days in that month
		$end_date = ($start_date + ($days_in_month * 86400))-1;							//find end date timestamp of the the month
		App::import('model','DailyDeal');
		$this->DailyDeal = new DailyDeal();
		
		$cond=array('AdvertiserProfile.id'=>$id);	
		$this->loadModel('AdvertiserProfile');
		$county=$this->AdvertiserProfile->find('first',array('fields'=>array('id','county'),'conditions'=>$cond));
		if($county)
		$county=$county['AdvertiserProfile']['county'];
		
		//pr($data);
		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
		$this->set('data', $data);
		$this->set('id', $id);
		$this->set('cats', $cats);
		$this->set('county', $county);
	}
	
		
/***----------------------This function Set the Daily Discount booked date for particular county  from database-------------------------------------------------------*/
	
	function calendarDailyDiscount($year = null, $month = null, $county=null){
		
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		if(isset($this->data))
		{
		 $county=$this->data['calendars']['county'];
		 $this->set('county',$county);
		}
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'calendars/calendarDailyDiscount'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'calendars';
		$data = null;
 		
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
 		/*------------------------------This section find current month book date and set to view----------------------------------*/
		
		$s_date='1/'.$month_num.'/'.$year;
		$start_date	= explode('/',$s_date);	
		$start_date = mktime(0,0,0,$start_date[1],$start_date[0],$start_date[2]);   //find the start date timestamp of the month
		$days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));			// find days in that month
		$end_date = ($start_date + ($days_in_month * 86400))-1;							//find end date timestamp of the the month
		App::import('model','DailyDiscount');
		$this->DailyDiscount = new DailyDiscount();
		$conditions=array('DailyDiscount.s_date >='=>$start_date,'DailyDiscount.e_date <='=>$end_date,'DailyDiscount.advertiser_county_id'=>$county);
		 
		$booked=$this->DailyDiscount->find('all',array('fields'=>array('s_date','e_date','category'),'order'=>'DailyDiscount.id asc','conditions'=>$conditions));
		//find all booked date range of that month
		 
		foreach($booked as $book): 
		{
	 		if(isset($book)) 
			{	
					// setting the book date range in array as string						
					$data[]=date('d',$book['DailyDiscount']['s_date']).'/'.date('d',$book['DailyDiscount']['e_date']).'/'.$this->common->getCategoryName($book['DailyDiscount']['category']);		
			}
		}
		endforeach;
		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
		$this->set('data', $data);
		$this->set('county', $county);
	
	}
		
	
	
/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

	
	
/***----------------------This function Set the Daily Deal booked date for particular county  from database----------------------------------------------------------*/
	
	function calendarDailyDeal($year = null, $month = null, $county=null){
		
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		if(isset($this->data))
		{
		 $county=$this->data['calendars']['county'];
		 $this->set('county',$county);
		}
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'calendars/calendarDailyDeal'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'calendars';
		$data = null;
 		
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
 		/*------------------------------This section find current month book date and set to view----------------------------------*/
		
		$s_date='1/'.$month_num.'/'.$year;
		$start_date	= explode('/',$s_date);	
		$start_date = mktime(0,0,0,$start_date[1],$start_date[0],$start_date[2]);   //find the start date timestamp of the month
		$days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));			// find days in that month
		$end_date = ($start_date + ($days_in_month * 86400))-1;							//find end date timestamp of the the month
		App::import('model','DailyDeal');
		$this->DailyDeal = new DailyDeal();
		$conditions=array('DailyDeal.s_date >='=>$start_date,'DailyDeal.e_date <='=>$end_date,'DailyDeal.advertiser_county_id'=>$county);
		 
		$booked=$this->DailyDeal->find('all',array('fields'=>array('s_date','e_date','category'),'order'=>'DailyDeal.id asc','conditions'=>$conditions));
		//find all booked date range of that month
		 
		foreach($booked as $book): 
		{
	 		if(isset($book)) 
			{	
					// setting the book date range in array as string						
					$data[]=date('d',$book['DailyDeal']['s_date']).'/'.date('d',$book['DailyDeal']['e_date']).'/'.$this->common->getCategoryName($book['DailyDeal']['category']);		
			}
		}
		endforeach;
		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
		$this->set('data', $data);
		$this->set('county', $county);
	
	}
		
/*
    this function is checking username and password in database
	and if true then redirect to home page
	*/
	
	
/*---------------------------------This function set the booked date of contest to popup view-----------------------------------------------------------------*/

	function calendar_contest($year = null, $month = null, $county = null,$contest_id = null) {
		
		$month_list = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
		$day_list = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$base_url = $this->webroot . 'calendars/calendar_contest'; // NOT not used in the current helper version but used in the data array
		$view_base_url = $this->webroot. 'calendars';
		$data = null;
 		
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
 		/*------------------------------This section find current month book date and set to view----------------------------------*/
		
		$s_date='1/'.$month_num.'/'.$year;
		$start_date	= explode('/',$s_date);	
		$start_date = mktime(0,0,0,$start_date[1],$start_date[0],$start_date[2]);   //find the start date timestamp of the month
		$days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));			// find days in that month
		$end_date = ($start_date + ($days_in_month * 86400))-1;							//find end date timestamp of the the month
		
		//pr($data);
		/*---------------------------------------------------------------------------------------------------------------------*/
		$this->set('year', $year);
		$this->set('month', $month);
		$this->set('base_url', $base_url);
		$this->set('data', $data);
		$this->set('county', $county);				
		$this->set('contest_id',$contest_id);
	}
	
	
	function beforeFilter() { 

        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );

			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
   	}	
	
/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

	function beforeRender(){
		//$this->Ssl->force();
	}
	
 }
 ?>