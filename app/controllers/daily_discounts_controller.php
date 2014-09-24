<?php
/*
   Coder	:	 Manoj
   Date  	: 	 16 May 2011
*/

class DailyDiscountsController extends AppController{
 var $name = 'DailyDiscounts';
 var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar');
 var $components = array('Auth','common','Session','Cookie','RequestHandler','Email','emailhtml');
 var $layout = 'admin';

 /***-----------------------This function is the Index function i.e. call by default-----------------------------------------------------------------*/
	function index(){
		$this->set('title_for_layout','Today\'s Big Deal');
		$this->set('categoryList',$this->common->getAllCategory());
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		$this->set('cityList',$this->common->getAllCity()); 		//  List cities
		$this->set('stateList',$this->common->getAllState()); 		//  List states
		$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
		$this->set('search_text','Title');
		$this->set('publish_page','');
		$this->set('s_date','');
		$this->set('e_date','');
		$this->set('category', 'Category');
		$this->set('advertiser_profile_id', 'Advertiser');
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('DailyDiscount.id' => 'desc'));
		
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] !='' ){
		
		$cond=array('DailyDiscount.advertiser_profile_id' => $this->params['pass'][0]);
		(empty($this->params['named'])) ? $this->set('advertiser_profile_id', $this->params['pass'][0]) :$this->set('advertiser_profile_id', $this->params['pass'][0]) ;
		$this->set('advertiser_id',$this->params['pass'][0]);
		}
		 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
//if advertiser is set		
		if((isset($this->data['daily_discounts']['advertiser_profile_id']) and $this->data['daily_discounts']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['daily_discounts']['advertiser_profile_id']) and $this->data['daily_discounts']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['daily_discounts']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'];
			}else{
			  $advertiser_profile_id ="";
			}
			
			$this->set('advertiser_profile_id',$advertiser_profile_id); 
		}
		
//if category is set
		if((isset($this->data['daily_discounts']['category']) and $this->data['daily_discounts']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
		
		
			if((isset($this->data['daily_discounts']['category']) and $this->data['daily_discounts']['category'] != 0))
			{
			 $category = $this->data['daily_discounts']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
//if county is set
		if((isset($this->data['daily_discounts']['county']) and $this->data['daily_discounts']['county'] != 0)|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
		
		
		
			if((isset($this->data['daily_discounts']['county']) and $this->data['daily_discounts']['county'] != 0))
			{
			 $county = $this->data['daily_discounts']['county'] ;
			}
            else if( (isset($this->params['named']['county'])) and $this->params['named']['county'] !=''){
             $county = $this->params['named']['county'] ;
			}else{
			$county = '';
			}
			$this->set('county',$county); 
		}
//if title is set
		if((isset($this->data['daily_discounts']['search_text']) and ($this->data['daily_discounts']['search_text'] != '' and $this->data['daily_discounts']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['daily_discounts']['search_text']) and ($this->data['daily_discounts']['search_text'] != '' and $this->data['daily_discounts']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['daily_discounts']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
// if start date is set
		if((isset($this->data['daily_discounts']['s_date']) and $this->data['daily_discounts']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['daily_discounts']['s_date']) and $this->data['daily_discounts']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['daily_discounts']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['daily_discounts']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}
			else if( (isset($this->params['named']['s_date'])) and $this->params['named']['s_date'] !=''){
			 
			  $arrS_date = explode("-",$this->params['named']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			 
			 $s_date = $this->params['named']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}else{
			 $s_date ="";
			}
			
			$this->set('s_date',$s_date);
			
			$this->set('s_datetmsp',$s_datetmsp);
			
		
		}
		
//if end date is set
		if((isset($this->data['daily_discounts']['e_date']) and $this->data['daily_discounts']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['daily_discounts']['e_date']) and $this->data['daily_discounts']['e_date'] != ''))
			{
			
			  $arrE_date = explode("/",$this->data['daily_discounts']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['daily_discounts']['e_date'] ;
			  $e_date = str_replace("/","-",$e_date);
			  $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			}
			else if( (isset($this->params['named']['e_date'])) and $this->params['named']['e_date'] !=''){
			 
			  $arrE_date = explode("-",$this->params['named']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			 
			 $e_date = $this->params['named']['e_date'] ;
			 $e_date = str_replace("/","-",$e_date);
			 $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			}else{
			 $e_date ="";
			}
			
			$this->set('e_date',$e_date);
			$this->set('e_datetmsp',$e_datetmsp);
		
		}
//if show on publish page is set			
		
		if((isset($this->data['daily_discounts']['publish_page']) and $this->data['daily_discounts']['publish_page'] != '')|| ( isset($this->params['named']['publish_page']) and $this->params['named']['publish_page'] !='')){
			if((isset($this->data['daily_discounts']['publish_page']) and $this->data['daily_discounts']['publish_page'] != ''))
			{
			 $publish_page = $this->data['daily_discounts']['publish_page'] ;
			}
			else if( isset($this->params['named']['publish_page']) and $this->params['named']['publish_page'] !=''){
			 $publish_page = $this->params['named']['publish_page'] ;
			}else{
			  
			  $publish_page ="";
			}
			
			$this->set('publish_page',$publish_page); 
		}				
		 /*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		
		if(isset($advertiser_profile_id) && $advertiser_profile_id !=''){
		 $cond['DailyDiscount.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($category) && $category !=''){
		 $cond['DailyDiscount.category'] = $category;
		}
		if(isset($county) && $county !=''){
		 $cond['DailyDiscount.advertiser_county_id'] = $county;
		}
		if(isset($search_text) && $search_text !=''){
		 $cond['DailyDiscount.title LIKE'] = '%'.$search_text. '%';
		}
		
		if(isset($publish_page) && $publish_page !=''){
			if($publish_page=="home_page")
			{
				$cond['DailyDiscount.show_on_home_page'] = 1;
		 	}
		 	else
		 	{
		  		$cond['DailyDiscount.show_on_category'] = 1;
			}
		}		
		
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['DailyDiscount.s_date >='] = $s_datetmsp ;
		  $cond['DailyDiscount.e_date <='] = $e_datetmsp ;
		}
		
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){
		
		   $cond['DailyDiscount.s_date ='] = $s_datetmsp ;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['DailyDiscount.e_date ='] = $e_datetmsp ;
		}			 
		
		$data = $this->paginate('DailyDiscount', $cond);
		$this->set('daily_discounts', $data);
	}
/**------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

/***-----------------------This function show all records of all advertiser of daily discount on common page in database-(8-june-2011)-------------------------------*/

	function dailyDiscountCommon()
	{
		$this->set('title_for_layout','Today\'s Big Deal');
		$this->set('categoryList',$this->common->getAllCategory());
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		$this->set('cityList',$this->common->getAllCity()); 		//  List cities
		$this->set('stateList',$this->common->getAllState()); 		//  List states
		$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
		$this->set('search_text','Title');
		$this->set('s_date','');
		$this->set('e_date','');
		$this->set('publish_page','');
		$this->set('category', 'Category');
		$this->set('county', 'County');
		$this->set('advertiser_profile_id', 'Advertiser');
		$chk=1;
		$this->set('chk',$chk);// used to check the redircted page after the update
		
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('DailyDiscount.id' => 'desc'));

		 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
//if advertiser is set		
		if((isset($this->data['daily_discounts']['advertiser_profile_id']) and $this->data['daily_discounts']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['daily_discounts']['advertiser_profile_id']) and $this->data['daily_discounts']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['daily_discounts']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'] ;
			} else {			  
			  $advertiser_profile_id ="";
			}			
			$this->set('advertiser_profile_id',$advertiser_profile_id); 
		}
//if category is set
		
		if((isset($this->data['daily_discounts']['category']) and $this->data['daily_discounts']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
			if((isset($this->data['daily_discounts']['category']) and $this->data['daily_discounts']['category'] != 0))
			{
			 $category = $this->data['daily_discounts']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
// if county is set
		if((isset($this->data['daily_discounts']['county']) and $this->data['daily_discounts']['county'] != 0)|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
			if((isset($this->data['daily_discounts']['county']) and $this->data['daily_discounts']['county'] != 0))	{
				$county = $this->data['daily_discounts']['county'] ;
			}
            else if( (isset($this->params['named']['county'])) and $this->params['named']['county'] !=''){
             	$county = $this->params['named']['county'] ;
			}else{
				$county = '';
			}
			$this->set('county',$county);
		}
// if title is set
		if((isset($this->data['daily_discounts']['search_text']) and ($this->data['daily_discounts']['search_text'] != '' and $this->data['daily_discounts']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )) {
			if((isset($this->data['daily_discounts']['search_text']) and ($this->data['daily_discounts']['search_text'] != '' and $this->data['daily_discounts']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['daily_discounts']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			} else {
               $search_text ='';
			}
			$this->set('search_text',$search_text);
		}
// if start date is set
		if((isset($this->data['daily_discounts']['s_date']) and $this->data['daily_discounts']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['daily_discounts']['s_date']) and $this->data['daily_discounts']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['daily_discounts']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['daily_discounts']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}
			else if( (isset($this->params['named']['s_date'])) and $this->params['named']['s_date'] !=''){
			 
			  $arrS_date = explode("-",$this->params['named']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			 
			 $s_date = $this->params['named']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}else{
			 $s_date ="";
			}
			
			$this->set('s_date',$s_date);
			
			$this->set('s_datetmsp',$s_datetmsp);
			
		
		}
		
//if end date is set
		if((isset($this->data['daily_discounts']['e_date']) and $this->data['daily_discounts']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['daily_discounts']['e_date']) and $this->data['daily_discounts']['e_date'] != ''))
			{
			
			  $arrE_date = explode("/",$this->data['daily_discounts']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['daily_discounts']['e_date'] ;
			  $e_date = str_replace("/","-",$e_date);
			  $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			}
			else if( (isset($this->params['named']['e_date'])) and $this->params['named']['e_date'] !=''){
			 
			  $arrE_date = explode("-",$this->params['named']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			 
			 $e_date = $this->params['named']['e_date'] ;
			 $e_date = str_replace("/","-",$e_date);
			 $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			}else{
			 $e_date ="";
			}
			
			$this->set('e_date',$e_date);
			$this->set('e_datetmsp',$e_datetmsp);
		
		}
//if show on publish page is set			
		
		if((isset($this->data['daily_discounts']['publish_page']) and $this->data['daily_discounts']['publish_page'] != '')|| ( isset($this->params['named']['publish_page']) and $this->params['named']['publish_page'] !='')){
			if((isset($this->data['daily_discounts']['publish_page']) and $this->data['daily_discounts']['publish_page'] != ''))
			{
			 $publish_page = $this->data['daily_discounts']['publish_page'] ;
			}
			else if( isset($this->params['named']['publish_page']) and $this->params['named']['publish_page'] !=''){
			 $publish_page = $this->params['named']['publish_page'] ;
			}else{
			  
			  $publish_page ="";
			}
			
			$this->set('publish_page',$publish_page); 
		}		
		 /*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		 	
		if(isset($advertiser_profile_id) && $advertiser_profile_id !=''){
		 $cond['DailyDiscount.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($category) && $category !=''){
		 $cond['DailyDiscount.category'] = $category;
		}
		if(isset($county) && $county !=''){
		 $cond['DailyDiscount.advertiser_county_id'] = $county;
		}
		if(isset($search_text) && $search_text !=''){
		 $cond['DailyDiscount.title LIKE'] = '%'.$search_text. '%';
		}
		
		if(isset($publish_page) && $publish_page !=''){
			if($publish_page=="home_page")
			{
				$cond['DailyDiscount.show_on_home_page'] = 1;
		 	}
		 	else
		 	{
		  		$cond['DailyDiscount.show_on_category'] = 1;
			}
		}		
		
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['DailyDiscount.s_date >='] = $s_datetmsp ;
		  $cond['DailyDiscount.e_date <='] = $e_datetmsp ;
		}
		
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){
		
		   $cond['DailyDiscount.s_date ='] = $s_datetmsp ;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['DailyDiscount.e_date ='] = $e_datetmsp ;
		}
		$data = $this->paginate('DailyDiscount', $cond);
		$this->set('daily_discounts', $data);
	}
	
	
/***-----------------------This function show all records of all archive daily discount on common page in database-(20-sep-2011)-------------------------------*/

	function archivedailyDiscount()
	{
		$this->set('title_for_layout','Big Deal Archive');
		$this->set('categoryList',$this->common->getAllCategory());
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		$this->set('cityList',$this->common->getAllCity()); 		//  List cities
		$this->set('stateList',$this->common->getAllState()); 		//  List states
		$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
		$this->set('search_text','Title');
		$this->set('s_date','');
		$this->set('e_date','');
		$this->set('category', 'Category');
		$this->set('advertiser_profile_id', 'Advertiser');
		$cond = '';
		$time = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$cond['DailyDiscount.e_date <'] = $time;
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('DailyDiscount.id' => 'desc'));
		
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] !='' ){
		
		$cond=array('DailyDiscount.advertiser_profile_id' => $this->params['pass'][0]);
		(empty($this->params['named'])) ? $this->set('advertiser_profile_id', $this->params['pass'][0]) :$this->set('advertiser_profile_id', $this->params['pass'][0]) ;
		$this->set('advertiser_id',$this->params['pass'][0]);
		}
		 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
//if advertiser is set		
		if((isset($this->data['daily_discounts']['advertiser_profile_id']) and $this->data['daily_discounts']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['daily_discounts']['advertiser_profile_id']) and $this->data['daily_discounts']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['daily_discounts']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'];
			}else{
			  $advertiser_profile_id ="";
			}
			
			$this->set('advertiser_profile_id',$advertiser_profile_id); 
		}
		
//if category is set
		if((isset($this->data['daily_discounts']['category']) and $this->data['daily_discounts']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
		
		
			if((isset($this->data['daily_discounts']['category']) and $this->data['daily_discounts']['category'] != 0))
			{
			 $category = $this->data['daily_discounts']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
//if county is set
		if((isset($this->data['daily_discounts']['county']) and $this->data['daily_discounts']['county'] != 0)|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
		
		
		
			if((isset($this->data['daily_discounts']['county']) and $this->data['daily_discounts']['county'] != 0))
			{
			 $county = $this->data['daily_discounts']['county'] ;
			}
            else if( (isset($this->params['named']['county'])) and $this->params['named']['county'] !=''){
             $county = $this->params['named']['county'] ;
			}else{
			$county = '';
			}
			$this->set('county',$county); 
		}
//if title is set
		if((isset($this->data['daily_discounts']['search_text']) and ($this->data['daily_discounts']['search_text'] != '' and $this->data['daily_discounts']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['daily_discounts']['search_text']) and ($this->data['daily_discounts']['search_text'] != '' and $this->data['daily_discounts']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['daily_discounts']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
// if start date is set
		if((isset($this->data['daily_discounts']['s_date']) and $this->data['daily_discounts']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['daily_discounts']['s_date']) and $this->data['daily_discounts']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['daily_discounts']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['daily_discounts']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}
			else if( (isset($this->params['named']['s_date'])) and $this->params['named']['s_date'] !=''){
			 
			  $arrS_date = explode("-",$this->params['named']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			 
			 $s_date = $this->params['named']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}else{
			 $s_date ="";
			}
			
			$this->set('s_date',$s_date);
			
			$this->set('s_datetmsp',$s_datetmsp);
			
		
		}
		
//if end date is set
		if((isset($this->data['daily_discounts']['e_date']) and $this->data['daily_discounts']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['daily_discounts']['e_date']) and $this->data['daily_discounts']['e_date'] != ''))
			{
			
			  $arrE_date = explode("/",$this->data['daily_discounts']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['daily_discounts']['e_date'] ;
			  $e_date = str_replace("/","-",$e_date);
			  $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			}
			else if( (isset($this->params['named']['e_date'])) and $this->params['named']['e_date'] !=''){
			 
			  $arrE_date = explode("-",$this->params['named']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			 
			 $e_date = $this->params['named']['e_date'] ;
			 $e_date = str_replace("/","-",$e_date);
			 $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			}else{
			 $e_date ="";
			}
			
			$this->set('e_date',$e_date);
			$this->set('e_datetmsp',$e_datetmsp);
		
		}			
		 /*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		
		if(isset($advertiser_profile_id) && $advertiser_profile_id !=''){
		 $cond['DailyDiscount.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($category) && $category !=''){
		 $cond['DailyDiscount.category'] = $category;
		}
		if(isset($county) && $county !=''){
		 $cond['DailyDiscount.advertiser_county_id'] = $county;
		}
		if(isset($search_text) && $search_text !=''){
		 $cond['DailyDiscount.title LIKE'] = '%'.$search_text. '%';
		}	
		
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['DailyDiscount.s_date >='] = $s_datetmsp ;
		  $cond['DailyDiscount.e_date <='] = $e_datetmsp ;
		}
		
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){
		
		   $cond['DailyDiscount.s_date ='] = $s_datetmsp ;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['DailyDiscount.e_date ='] = $e_datetmsp ;
		}			 
		
		$data = $this->paginate('DailyDiscount', $cond);
		$this->set('daily_discounts', $data);
	}
	
/***-----------------------This function used to view archive daily discount in database-----------------------------------------------------------------------------*/
	
	function viewArchiveDiscount($id=null,$adv_id=null)
	{
		$this->set('title_for_layout','Archive Big Deal');
		$this->set('advertiser_id',$adv_id);	
		$this->set('data',$this->DailyDiscount->findbyId($id));	
	}
				
/***-----------------------This function Add new daily discount in database-------------------***/
	function addDailyDiscount($id=null){
		if(isset($this->params['pass'][0])){
	   		$this->set('advertiser_id', $this->params['pass'][0]);
		}
		$this->set('title_for_layout','Add Big Deal');
		//$this->set('refferer',$_SERVER['HTTP_REFERER']);
		if($this->referer()=='/daily_discounts/addDailyDiscount' || $this->referer()=='/'){ } else {
			$this->Session->write('referer',$this->referer());
		}
		$this->set('refferer',$this->Session->read('referer'));	
	 	$this->set('categoryList',$this->common->getAllCategory()); //  List categories
	  	//$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List Advertisers		
		$this->set('advertiserList',$this->common->getAdvertiserProfilesForDiscount()); //  List Advertisers			
		/*------------------validation for redirect 2 mastersheet if it is initiated from master sheet-----------------*/
		  if((strpos($this->referer(),'masterSheet')!=false)) {
		  	$this->Session->write('reff',$this->referer());
		  }
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		   }
		   
		/*----------------------------------------------------------------------------------------------------------*/		
		if(isset($this->data))
				{
				date_default_timezone_set('US/Eastern');
				if(isset($this->data['daily_discount']['subcategory'])) {
					$this->data['DailyDiscount']['subcategory'] = $this->data['daily_discount']['subcategory'];
				}
				  $this->DailyDiscount->set($this->data['DailyDiscount']);
		  	   	  $this->set('advertiser_id', $this->data['DailyDiscount']['advertiser_profile_id']);//element use only
				  
				  if($this->DailyDiscount->validates())
				  { 	
				  			$cats_combination = explode('-',$this->data['DailyDiscount']['subcategory']);
							$this->data['DailyDiscount']['category'] = $cats_combination[0];
							$this->data['DailyDiscount']['subcategory'] = $cats_combination[1];
						/*------------------------------------find county of specified advertiser---------------------------------------------*/
						
						$county = $this->DailyDiscount->getCityCountyState($this->data['DailyDiscount']['advertiser_profile_id']);
						
						$this->data['DailyDiscount']['advertiser_county_id']	=	$county[0]['advertiser_profiles']['county'];
						
			/*----------------------------------------------HOME PAGE----------------------------------------------------------------------*/	
						if($this->data['DailyDiscount']['h_s_hour']=='') {$this->data['DailyDiscount']['h_s_hour']=0;}
						if($this->data['DailyDiscount']['h_s_min']=='') {$this->data['DailyDiscount']['h_s_min']=0;}
						if($this->data['DailyDiscount']['h_s_sec']=='') {$this->data['DailyDiscount']['h_s_sec']=0;}
						if($this->data['DailyDiscount']['h_e_hour']=='') {$this->data['DailyDiscount']['h_e_hour']=23;}
						if($this->data['DailyDiscount']['h_e_min']=='') {$this->data['DailyDiscount']['h_e_min']=59;}
						if($this->data['DailyDiscount']['h_e_sec']=='') {$this->data['DailyDiscount']['h_e_sec']=59;}
						
						if(!empty($this->data['DailyDiscount']['sdate']))
						{
							$s_date		= $this->data['DailyDiscount']['sdate'];
							$start_date	= explode('/',$s_date);
							$start_date = mktime($this->data['DailyDiscount']['h_s_hour'],$this->data['DailyDiscount']['h_s_min'],$this->data['DailyDiscount']['h_s_sec'],$start_date[0],$start_date[1],$start_date[2]);
						}
						else
						{
							//$start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
							$start_date = 0;
						}
						$this->data['DailyDiscount']['s_date']= $start_date;
						
						if(!empty($this->data['DailyDiscount']['edate']))
						{
							$e_date		= $this->data['DailyDiscount']['edate'];
							$expiry_date	= explode('/',$e_date);
							$expiry_date = mktime($this->data['DailyDiscount']['h_e_hour'],$this->data['DailyDiscount']['h_e_min'],$this->data['DailyDiscount']['h_e_sec'],$expiry_date[0],$expiry_date[1],$expiry_date[2]);
						}
						else
						{
							//$expiry_date = mktime(date('h'),date('i'),date('s'),date('m'),date('t',strtotime('today')),date('Y'));
							$expiry_date = 0;
						}
							$this->data['DailyDiscount']['e_date']= $expiry_date;						
											
			/*-------------------------------------------------------CATEGORY PAGE-------------------------------------------------------------*/
						if($this->data['DailyDiscount']['c_s_hour']=='') {$this->data['DailyDiscount']['c_s_hour']=0;}
						if($this->data['DailyDiscount']['c_s_min']=='') {$this->data['DailyDiscount']['c_s_min']=0;}
						if($this->data['DailyDiscount']['c_s_sec']=='') {$this->data['DailyDiscount']['c_s_sec']=0;}
						if($this->data['DailyDiscount']['c_e_hour']=='') {$this->data['DailyDiscount']['c_e_hour']=23;}
						if($this->data['DailyDiscount']['c_e_min']=='') {$this->data['DailyDiscount']['c_e_min']=59;}
						if($this->data['DailyDiscount']['c_e_sec']=='') {$this->data['DailyDiscount']['c_e_sec']=59;}
						
						if(!empty($this->data['DailyDiscount']['c_s_date']))
						{
							$c_s_date		= $this->data['DailyDiscount']['c_s_date'];
							$c_start_date	= explode('/',$c_s_date);
							$c_start_date = mktime($this->data['DailyDiscount']['c_s_hour'],$this->data['DailyDiscount']['c_s_min'],$this->data['DailyDiscount']['c_s_sec'],$c_start_date[0],$c_start_date[1],$c_start_date[2]);
						}
						else
						{
							//$c_start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
							$c_start_date = 0;
						}
						$this->data['DailyDiscount']['c_s_date']= $c_start_date;
						
						if(!empty($this->data['DailyDiscount']['c_e_date']))
						{
							$c_e_date		= $this->data['DailyDiscount']['c_e_date'];
							$c_expiry_date	= explode('/',$c_e_date);
							$c_expiry_date = mktime($this->data['DailyDiscount']['c_e_hour'],$this->data['DailyDiscount']['c_e_min'],$this->data['DailyDiscount']['c_e_sec'],$c_expiry_date[0],$c_expiry_date[1],$c_expiry_date[2]);
						}
						else
						{
							$c_expiry_date = 0;
						}
							$this->data['DailyDiscount']['c_e_date']= $c_expiry_date;
					
						/*-------------------------------banner image uploaded function-------------------------------------------------------------*/
					if($this->data['DailyDiscount']['banner_image']['name']!='')
					{
						$type = $this->data['DailyDiscount']['banner_image']['type'];
						
						if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif")
						{
							$this->data['DailyDiscount']['banner_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['DailyDiscount']['banner_image']['name']);
							$docDestination = APP.'webroot/img/discounts/'.$this->data['DailyDiscount']['banner_image']['name']; 
							
							@chmod(APP.'webroot/img/discounts',0777);
							
							move_uploaded_file($this->data['DailyDiscount']['banner_image']['tmp_name'], $docDestination) or die($docDestination);
							
							$this->data['DailyDiscount']['banner_image'] = $this->data['DailyDiscount']['banner_image']['name'];
							
						}
						else
						{
							$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
						}	
					}
					else
					{
						$this->data['DailyDiscount']['banner_image'] = $this->data['DailyDiscount']['banner_image']['name'];
					}
						$this->data['DailyDiscount']['unique']	=	$this->common->randomPassword(13);
						$this->DailyDiscount->save($this->data['DailyDiscount']);
						$DailyDiscount_id = $this->DailyDiscount->getLastInsertId();
						/*----------------------------------------------------------------------------------------------------*/
						  #Insertimg one record in work order table to show this data in inbox of admin if sales person add a discount
						  App::import('model', 'WorkOrder');
						  $this->WorkOrder = new WorkOrder;					 
						  $orderid = $this->common->getOrderId($this->data['DailyDiscount']['advertiser_profile_id']);
						  
						  $saveWorkArray = array();
						  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $orderid['AdvertiserProfile']['order_id'];
						  $saveWorkArray['WorkOrder']['read_status']   				=  0;
						  $saveWorkArray['WorkOrder']['subject']   					=  'New Big Deal';
						  $saveWorkArray['WorkOrder']['message']   					=  'New Big Deal has been launched for the following advertiser profile.';
						  $saveWorkArray['WorkOrder']['type']   					=  'Daily Discount Workorder';
						  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
						  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
						  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');						  
						  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can edit this Big Deal or check all other Big Deals for this advertiser in Advertiser profiles section and pulish them. Please follow below url:<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'daily_discounts/editDailyDiscount/'.$DailyDiscount_id.'/'.$this->data['DailyDiscount']['advertiser_profile_id'].'" style="text-decoration:underline;" target="_blank">Edit New Big Deal</a><br /><br />OR<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'daily_discounts/index/'.$this->data['DailyDiscount']['advertiser_profile_id'].'" style="text-decoration:underline;" target="_blank">Big Deals Listing</a>';
		
						  date_default_timezone_set('US/Eastern');
						  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
						  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
						  $saveWorkArray['WorkOrder']['salseperson_id']   			=  $this->common->salesIdForAdvertiser($this->data['DailyDiscount']['advertiser_profile_id']);
						  $this->WorkOrder->save($saveWorkArray);
						  
						  
						  
					/*----------------------------------------------------------------------------------------------------------*/
						$this->Session->setFlash('Big Deal Successfully Saved');
						if(isset($this->data['DailyDiscount']['prvs_link']) && (strpos($this->data['DailyDiscount']['prvs_link'],'masterSheet')!=false))
						{
					 		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['DailyDiscount']['prvs_link']);		
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
						}
						elseif(strpos($this->data['DailyDiscount']['refferer'],'dailyDiscountCommon')!=false)
						{
							$this->redirect(array('action' => 'dailyDiscountCommon')); 
						}
						else
						{
							$this->redirect(array('action' => 'index/'.$this->data['DailyDiscount']['advertiser_profile_id']));				  
						}
				  }
				  else
				  {				
						$errors = $this->DailyDiscount->invalidFields();
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
	}
/***----------------------This function Edit Existing daily discount in database------------------------------------------------------------------------------------*/
	function editDailyDiscount($id=null,$advertiser_id=null){
	
		//pr($this->data);
		
		if($id == '' || !isset($id))
		{
			$id = $this->data['DailyDiscount']['uid'];
		}
		else
		{
			$id = $id;
		}
		if(isset($this->params['pass'][1]) || $this->data['DailyDiscount']['advertiser_profile_id']){
			$ad_id =  (isset($this->params['pass'][1])) ? $this->params['pass'][1] : $this->data['DailyDiscount']['advertiser_profile_id'];
	   		$this->set('advertiser_id', $ad_id);
		}	

//		$this->set('refferer',$_SERVER['HTTP_REFERER']);	
		if($this->Session->read('referer')!='/daily_discounts/dailyDidscountCommon'){ 
			$this->Session->write('referer',$this->referer());			
		}		
		$this->set('refferer',$this->Session->read('referer'));
		  if((strpos($this->referer(),'masterSheet')!=false)) {
		  	$this->Session->write('reff',$this->referer());
		  }
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		   }
		$this->set('title_for_layout','Edit Big Deal');
	 	$this->set('categoryList',$this->common->getAllCategory()); //  List categories
	  	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List Advertisers
		$this->set('data',$this->DailyDiscount->findbyId($id));
		//pr($this->DailyDiscount->findbyId($id));
	  	if(isset($this->data))
				{
				date_default_timezone_set('US/Eastern');
				//pr($this->data);
				//exit;
				if(isset($this->data['daily_discount']['subcategory'])) {
					$this->data['DailyDiscount']['subcategory'] = $this->data['daily_discount']['subcategory'];
				}
				  $this->DailyDiscount->set($this->data['DailyDiscount']);
				  if($this->DailyDiscount->validates())
				  { 
				  			$cats_combination = explode('-',$this->data['DailyDiscount']['subcategory']);
							$this->data['DailyDiscount']['category'] = $cats_combination[0];
							$this->data['DailyDiscount']['subcategory'] = $cats_combination[1];
					/*----------------------------------------------HOME PAGE----------------------------------------------------------------------*/
						if($this->data['DailyDiscount']['h_s_hour']=='') {$this->data['DailyDiscount']['h_s_hour']=0;}
						if($this->data['DailyDiscount']['h_s_min']=='') {$this->data['DailyDiscount']['h_s_min']=0;}
						if($this->data['DailyDiscount']['h_s_sec']=='') {$this->data['DailyDiscount']['h_s_sec']=0;}
						if($this->data['DailyDiscount']['h_e_hour']=='') {$this->data['DailyDiscount']['h_e_hour']=23;}
						if($this->data['DailyDiscount']['h_e_min']=='') {$this->data['DailyDiscount']['h_e_min']=59;}
						if($this->data['DailyDiscount']['h_e_sec']=='') {$this->data['DailyDiscount']['h_e_sec']=59;}
						
						if(!empty($this->data['DailyDiscount']['sdate']))
						{
							$s_date		= $this->data['DailyDiscount']['sdate'];
							$start_date	= explode('/',$s_date);
							$start_date = mktime($this->data['DailyDiscount']['h_s_hour'],$this->data['DailyDiscount']['h_s_min'],$this->data['DailyDiscount']['h_s_sec'],$start_date[0],$start_date[1],$start_date[2]);
						}
						else
						{
							$start_date = 0;
						}
						$this->data['DailyDiscount']['s_date']= $start_date;
						
						
						if(!empty($this->data['DailyDiscount']['edate']))
						{
							$e_date		= $this->data['DailyDiscount']['edate'];
							$expiry_date	= explode('/',$e_date);
							$expiry_date = mktime($this->data['DailyDiscount']['h_e_hour'],$this->data['DailyDiscount']['h_e_min'],$this->data['DailyDiscount']['h_e_sec'],$expiry_date[0],$expiry_date[1],$expiry_date[2]);
						}
						else
						{
							$expiry_date = 0;
						}					
						$this->data['DailyDiscount']['e_date']= $expiry_date;							

			/*-------------------------------------------------------CATEGORY PAGE-------------------------------------------------------------*/
						if($this->data['DailyDiscount']['c_s_hour']=='') {$this->data['DailyDiscount']['c_s_hour']=0;}
						if($this->data['DailyDiscount']['c_s_min']=='') {$this->data['DailyDiscount']['c_s_min']=0;}
						if($this->data['DailyDiscount']['c_s_sec']=='') {$this->data['DailyDiscount']['c_s_sec']=0;}
						if($this->data['DailyDiscount']['c_e_hour']=='') {$this->data['DailyDiscount']['c_e_hour']=23;}
						if($this->data['DailyDiscount']['c_e_min']=='') {$this->data['DailyDiscount']['c_e_min']=59;}
						if($this->data['DailyDiscount']['c_e_sec']=='') {$this->data['DailyDiscount']['c_e_sec']=59;}
						
					if(!empty($this->data['DailyDiscount']['c_s_date']))
						{
							$c_s_date		= $this->data['DailyDiscount']['c_s_date'];
							$c_start_date	= explode('/',$c_s_date);
							$c_start_date = mktime($this->data['DailyDiscount']['c_s_hour'],$this->data['DailyDiscount']['c_s_min'],$this->data['DailyDiscount']['c_s_sec'],$c_start_date[0],$c_start_date[1],$c_start_date[2]);
						}
						else
						{
							$c_start_date = 0;
						}
						$this->data['DailyDiscount']['c_s_date']= $c_start_date;
						
						if(!empty($this->data['DailyDiscount']['c_e_date']))
						{
							$c_e_date		= $this->data['DailyDiscount']['c_e_date'];
							$c_expiry_date	= explode('/',$c_e_date);
							$c_expiry_date = mktime($this->data['DailyDiscount']['c_e_hour'],$this->data['DailyDiscount']['c_e_min'],$this->data['DailyDiscount']['c_e_sec'],$c_expiry_date[0],$c_expiry_date[1],$c_expiry_date[2]);
						}
						else
						{
							$c_expiry_date = 0;
						}			
						$this->data['DailyDiscount']['c_e_date']= $c_expiry_date;
						
					/*-------------------------------------------------------------------------------------------------------------------*/
					
					if($this->data['DailyDiscount']['banner_image']['name']!='')
					{
						
						$type = $this->data['DailyDiscount']['banner_image']['type'];
						
						if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif")
						{                          
							$this->data['DailyDiscount']['banner_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['DailyDiscount']['banner_image']['name']);
							@unlink(APP.'webroot/img/discounts/'.$this->data['DailyDiscount']['oldfilename']);
							
							$docDestination = APP.'webroot/img/discounts/'.$this->data['DailyDiscount']['banner_image']['name']; 
							
							@chmod(APP.'webroot/img/discounts',0777);
							
							move_uploaded_file($this->data['DailyDiscount']['banner_image']['tmp_name'], $docDestination) or die($docDestination);
							
							$this->data['DailyDiscount']['banner_image'] = $this->data['DailyDiscount']['banner_image']['name'];
							
						}
						else
						{
							$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
						}	
					}
					else
					{
						$this->data['DailyDiscount']['banner_image'] = $this->data['DailyDiscount']['oldfilename'];
					}					
					
					/*-------------------------------------------------------------------------------------------------------------------*/	
						
						$this->data['DailyDiscount']['id']	=	$this->data['DailyDiscount']['uid'];
						
						$this->DailyDiscount->save($this->data['DailyDiscount']);						
										 						  
						/*---------------------------------------------------------------------------------------------------------*/						
						$this->Session->setFlash('Big Deal with id : '.$this->data['DailyDiscount']['uid'].' Successfully Updated'); 
						if(isset($this->data['DailyDiscount']['prvs_link']) && (strpos($this->data['DailyDiscount']['prvs_link'],'masterSheet')!=false)) {
						$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['DailyDiscount']['prvs_link']);			
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
							}else {						
						if(strpos($this->data['DailyDiscount']['refferer'],'dailyDiscountCommon'))
						{
							$this->redirect(array('action' => 'dailyDiscountCommon')); 
						}
						else
						{
							$this->redirect(array('action' => 'index/'.$this->data['DailyDiscount']['advertiser_profile_id']));				  
						}
					}	
				  }
				  else
				  	{
						$errors = $this->DailyDiscount->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				 	}
				}
			}
/***----------------------This function Edit Existing daily discount in database------------------------------------------------------------------------------------*/
	function publishDailyDiscount($id=null,$advertiser_id=null){
	
		//pr($this->data);
		
		if($id == '' || !isset($id))
		{
			$id = $this->data['DailyDiscount']['uid'];
		}
		else
		{
			$id = $id;
		}
		if(isset($this->params['pass'][1]) || $this->data['DailyDiscount']['advertiser_profile_id']){
			$ad_id =  (isset($this->params['pass'][1])) ? $this->params['pass'][1] : $this->data['DailyDiscount']['advertiser_profile_id'];
	   		$this->set('advertiser_id', $ad_id);
		}	
//		$this->set('refferer',$_SERVER['HTTP_REFERER']);	
		if($this->Session->read('referer')!='/daily_discounts/dailyDidscountCommon'){ 
			$this->Session->write('referer',$this->referer());			
		}		
		$this->set('refferer',$this->Session->read('referer'));
		  if((strpos($this->referer(),'masterSheet')!=false)) {
		  	$this->Session->write('reff',$this->referer());
		  }
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		 }
		 
		$this->set('title_for_layout','Edit Big Deal');
	 	$this->set('categoryList',$this->common->getAllCategory()); //  List categories
	  	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List Advertisers
		$this->set('data',$this->DailyDiscount->findbyId($id));
		//pr($this->DailyDiscount->findbyId($id));
	  	if(isset($this->data))
				{ 
				date_default_timezone_set('US/Eastern');
				//pr($this->data);
//				exit;
				if(isset($this->data['daily_discount']['subcategory'])) {
					$this->data['DailyDiscount']['subcategory'] = $this->data['daily_discount']['subcategory'];
				}
				  $this->DailyDiscount->set($this->data['DailyDiscount']);
				  if($this->DailyDiscount->validates())
				  { 
				  			$cats_combination = explode('-',$this->data['DailyDiscount']['subcategory']);
							$this->data['DailyDiscount']['category'] = $cats_combination[0];
							$this->data['DailyDiscount']['subcategory'] = $cats_combination[1];
					/*----------------------------------------------HOME PAGE----------------------------------------------------------------------*/
						if($this->data['DailyDiscount']['h_s_hour']=='') {$this->data['DailyDiscount']['h_s_hour']=0;}
						if($this->data['DailyDiscount']['h_s_min']=='') {$this->data['DailyDiscount']['h_s_min']=0;}
						if($this->data['DailyDiscount']['h_s_sec']=='') {$this->data['DailyDiscount']['h_s_sec']=0;}
						if($this->data['DailyDiscount']['h_e_hour']=='') {$this->data['DailyDiscount']['h_e_hour']=23;}
						if($this->data['DailyDiscount']['h_e_min']=='') {$this->data['DailyDiscount']['h_e_min']=59;}
						if($this->data['DailyDiscount']['h_e_sec']=='') {$this->data['DailyDiscount']['h_e_sec']=59;}
						if(!empty($this->data['DailyDiscount']['sdate']))
						{
							$s_date		= $this->data['DailyDiscount']['sdate'];
							$start_date	= explode('/',$s_date);
							$start_date = mktime($this->data['DailyDiscount']['h_s_hour'],$this->data['DailyDiscount']['h_s_min'],$this->data['DailyDiscount']['h_s_sec'],$start_date[0],$start_date[1],$start_date[2]);
						}
						else
						{
							$start_date =  0;
						}
						$this->data['DailyDiscount']['s_date']= $start_date;
						
						
						if(!empty($this->data['DailyDiscount']['edate']))
						{
							$e_date		= $this->data['DailyDiscount']['edate'];
							$expiry_date	= explode('/',$e_date);
							$expiry_date = mktime($this->data['DailyDiscount']['h_e_hour'],$this->data['DailyDiscount']['h_e_min'],$this->data['DailyDiscount']['h_e_sec'],$expiry_date[0],$expiry_date[1],$expiry_date[2]);
						}
						else
						{
							$expiry_date = 0;
						}					
						$this->data['DailyDiscount']['e_date']= $expiry_date;							

			/*-------------------------------------------------------CATEGORY PAGE-------------------------------------------------------------*/
						if($this->data['DailyDiscount']['c_s_hour']=='') {$this->data['DailyDiscount']['c_s_hour']=0;}
						if($this->data['DailyDiscount']['c_s_min']=='') {$this->data['DailyDiscount']['c_s_min']=0;}
						if($this->data['DailyDiscount']['c_s_sec']=='') {$this->data['DailyDiscount']['c_s_sec']=0;}
						if($this->data['DailyDiscount']['c_e_hour']=='') {$this->data['DailyDiscount']['c_e_hour']=23;}
						if($this->data['DailyDiscount']['c_e_min']=='') {$this->data['DailyDiscount']['c_e_min']=59;}
						if($this->data['DailyDiscount']['c_e_sec']=='') {$this->data['DailyDiscount']['c_e_sec']=59;}
					if(!empty($this->data['DailyDiscount']['c_s_date']))
						{
							$c_s_date		= $this->data['DailyDiscount']['c_s_date'];
							$c_start_date	= explode('/',$c_s_date);
							$c_start_date = mktime($this->data['DailyDiscount']['c_s_hour'],$this->data['DailyDiscount']['c_s_min'],$this->data['DailyDiscount']['c_s_sec'],$c_start_date[0],$c_start_date[1],$c_start_date[2]);
						}
						else
						{
							$c_start_date =  0;
						}
						$this->data['DailyDiscount']['c_s_date']= $c_start_date;
						
						if(!empty($this->data['DailyDiscount']['c_e_date']))
						{
							$c_e_date		= $this->data['DailyDiscount']['c_e_date'];
							$c_expiry_date	= explode('/',$c_e_date);
							$c_expiry_date = mktime($this->data['DailyDiscount']['c_e_hour'],$this->data['DailyDiscount']['c_e_min'],$this->data['DailyDiscount']['c_e_sec'],$c_expiry_date[0],$c_expiry_date[1],$c_expiry_date[2]);
						}
						else
						{
							$c_expiry_date =  0;
						}			
						$this->data['DailyDiscount']['c_e_date']= $c_expiry_date;								
	
					/*-------------------------------------------------------------------------------------------------------------------*/
					
					if($this->data['DailyDiscount']['banner_image']['name']!='')
					{
						$type = $this->data['DailyDiscount']['banner_image']['type'];
						
						if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif")
						{                        
							$this->data['DailyDiscount']['banner_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['DailyDiscount']['banner_image']['name']);
							@unlink(APP.'webroot/img/discounts/'.$this->data['DailyDiscount']['oldfilename']);
							
							$docDestination = APP.'webroot/img/discounts/'.$this->data['DailyDiscount']['banner_image']['name']; 
							
							@chmod(APP.'webroot/img/discounts',0777);
							
							move_uploaded_file($this->data['DailyDiscount']['banner_image']['tmp_name'], $docDestination) or die($docDestination);
							
							$this->data['DailyDiscount']['banner_image'] = $this->data['DailyDiscount']['banner_image']['name'];
							
						}
						else
						{
							$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
						}	
					}
					else
					{
						$this->data['DailyDiscount']['banner_image'] = $this->data['DailyDiscount']['oldfilename'];
					}					
					
					/*-------------------------------------------------------------------------------------------------------------------*/	
						
						$this->data['DailyDiscount']['id'] = '';
						$this->data['DailyDiscount']['total_purchase'] = 0;
						$this->data['DailyDiscount']['unique']	=	$this->common->randomPassword(13);
						/*pr($this->data);
						exit;*/
						$this->DailyDiscount->save($this->data['DailyDiscount']);						
										 						  
						/*---------------------------------------------------------------------------------------------------------*/						
						$this->Session->setFlash('Big Deal successfully re-published.');
						if(isset($this->data['DailyDiscount']['prvs_link']) && (strpos($this->data['DailyDiscount']['prvs_link'],'masterSheet')!=false)) {
						$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['DailyDiscount']['prvs_link']);			
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
							}else {						
						if(strpos($this->data['DailyDiscount']['refferer'],'dailyDiscountCommon'))
						{
							$this->redirect(array('action' => 'dailyDiscountCommon')); 
						}
						else
						{
							$this->redirect(array('action' => 'index/'.$this->data['DailyDiscount']['advertiser_profile_id']));				  
						}
					}
				  }
				  else
				  {
			
						$errors = $this->DailyDiscount->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
		}
				
/***-----------------------This function Delete the Daily Discount from database-----------------------------------------------------------------------------------*/
	
	function deleteDailyDiscount($id=null,$advertiser_id=null,$chk=null){
			
					$banner_image = $this->DailyDiscount->query("SELECT banner_image FROM daily_discounts WHERE id ='".$id."'");
					@chmod(APP.'webroot/img/discounts',0777);
					@unlink(APP.'webroot/img/discounts/'.$banner_image[0]['daily_discounts']['banner_image']);
					
					$this->DailyDiscount->delete($id);
					
					$this->Session->setFlash('The Big Deal with id:  '.$id.' has been Deleted Successfully!!');
			if((strpos($this->referer(),'masterSheet')!=false)) {
				$ad_id = explode('/',$this->referer());			
				$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
			}else {							
						$this->redirect($this->referer());
				}
	}
	
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocomplete($id='',$string='') {

			$this->autoRender = false;
			
			if($id==0)
			$id='';
						
			if($string!=''){
			$arr = '';
			if($string!='' and $id!='' and $id!=-1)
			{
			$name = $this->DailyDiscount->query("SELECT DailyDiscount.title FROM daily_discounts AS DailyDiscount WHERE DailyDiscount.title LIKE '$string%' AND DailyDiscount.advertiser_profile_id='$id' ");
			}
			elseif($string!='' and $id==-1)
			{
			$cur_time = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$name = $this->DailyDiscount->query("SELECT DailyDiscount.title FROM daily_discounts AS DailyDiscount WHERE DailyDiscount.title LIKE '$string%' AND DailyDiscount.e_date < $cur_time");
			}
			else
			{
			$name = $this->DailyDiscount->query("SELECT DailyDiscount.title FROM daily_discounts AS DailyDiscount WHERE DailyDiscount.title LIKE '$string%'");
			}
			foreach($name as $name) {
				$arr[] = $name['DailyDiscount']['title'];
			}
			echo json_encode($arr);
			}
	}	

/*------------------------------------------------------------------------------------------------------------------------*/ 	
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

function preview($id=0) {
	//echo $id;
	$this->layout = false;
	$daily_disc = $this->DailyDiscount->find('first',array('conditions'=>array('DailyDiscount.id'=>$id)));
	$this->set('daily_disc',$daily_disc);						
}

	function selectedCatList(){
	
		if(isset($this->data['DailyDiscount']['advertiser_profile_id'])&& $this->data['DailyDiscount']['advertiser_profile_id'] !=''){
		$adv_id=$this->data['DailyDiscount']['advertiser_profile_id'];
		}
		elseif(isset($this->params['pass'][0]))
		{
		$adv_id=$this->params['pass'][0];
		}
		else
		{
		$adv_id = '';
		}
		$this->set('a_id',$adv_id);
		
	if(isset($this->params['pass'][1]))
		$this->set('cat_select',$this->params['pass'][1]);
	else
		$this->set('cat_select','');

if(isset($this->params['pass'][2]))
		$this->set('pcat_select',$this->params['pass'][2]);
	else
		$this->set('pcat_select','');	
	}	
/*-------------------------------------------Used to fetch all discount buyers----------------------------------------------*/
function ajaxDiscountUser($id)
{
	$this->autoRender = false;
	//echo 'Discount id =>'.$id;
	
	$this->loadModel('FrontUser');
	$this->loadModel('DiscountUser');
	$allBuyers=$this->DiscountUser->find('all',array('conditions'=>array('DiscountUser.daily_discount_id'=>$id)));
	$buyerListStr='';
	if(!empty($allBuyers))
	{
		$buyerListStr.="<table width='100%' style='font-size: 11px;'>";
		$redeemText=0;
		
		$buyerListStr.="<tr><th align='left' width='12%'>Name</th><th align='left' width='12%'>Purchased<br />Vouchers</th><th align='left' width='12%'>Redeemed<br />Vouchers</th><th align='left' width='12%'>Remaining<br />Vouchers</th><th align='left' width='12%'>Purchase Date</th><th align='left' width='16%'>date of redemption</th><th align='left' width='16%'># of vouchers<br />to be redeemed</th><th align='left' width='8%'>&nbsp;</th></tr>";
		
		foreach($allBuyers as $allBuyers)
		{
			$redeem = '';
			$full_history = '';
			if($allBuyers['DiscountUser']['voucher_history']) {
			$history = explode('|',$allBuyers['DiscountUser']['voucher_history']);
			if(!empty($history)) {
				$i = 1;
				$total = count($history);
				foreach($history as $hstry) {
					$inner_htry = '';
					$inner_htry = explode('-',$hstry);
					if($total==$i) {
						$full_history .= $inner_htry[0].' ('.date(DATE_FORMAT,$inner_htry[1]).')';
					} else {
						$full_history .= $inner_htry[0].' ('.date(DATE_FORMAT,$inner_htry[1]).')<br />';
					}
					$i++;
				}
			}
			}
		  	$fid=$allBuyers['FrontUser']['id'];
			$redeem="redeem_voucher".$redeemText;
			$ulimit=$allBuyers['DiscountUser']['vouchers'];
			$rem_voucher=0;
			
			$rem_voucher=$allBuyers['DiscountUser']['vouchers']-$allBuyers['DiscountUser']['voucher_redeemed'];
			
			$buyerListStr.="<tr><td align='left'>".$allBuyers['FrontUser']['name']."</td><td align='left'>".$allBuyers['DiscountUser']['vouchers']."</td><td align='left'><span id='voucherRedeem'>".$allBuyers['DiscountUser']['voucher_redeemed']."</span></td><td align='left'><span id='voucherRemaining'>".$rem_voucher."</span></td><td align='left'>".date(DATE_FORMAT,$allBuyers['DiscountUser']['purchase_date'])."</td><td align='left'><span id='history".$allBuyers['FrontUser']['id']."'>".$full_history."</span></td>";
			
			if($allBuyers['DiscountUser']['voucher_redeemed']<$ulimit)
			{
				$buyerListStr.="<td align='left'><input type='text' name='redeem_voucher".$redeemText."' id='redeem_voucher".$redeemText."' size='8' /></td><td align='left'><img src='".FULL_BASE_URL.router::url('/',false)."img/profile/save_icon.jpeg"."' style='width:30px; height:30px;cursor:pointer;' onclick=validateRedeem('".$fid."','".$redeem."','".$ulimit."','".$allBuyers['DiscountUser']['id']."') title='Update the Redeem Voucher'></td>";
			}
			$buyerListStr.="</tr>";
			$redeemText++;
		}
		$buyerListStr.="</table>";
		echo $buyerListStr;
	} else {
		echo '<div style="text-align:center; font-weight:bold;">No Big Deal Buyer Available</div>';
	}
}
/*-------------------------------------------Used to fetch all discount buyers----------------------------------------------*/
function printDiscountUser($id)
{
	$this->layout = 'discount_user_print';	
	//echo 'Discount id =>'.$id;
	$this->loadModel('DailyDiscount');
	$discount = $this->DailyDiscount->find('first',array('fields'=>array('DailyDiscount.title'),'conditions'=>array('DailyDiscount.id'=>$id)));
	echo "<h2>".$discount['DailyDiscount']['title']."</h2>";
	$this->loadModel('DiscountUser');
	$allBuyers=$this->DiscountUser->find('all',array('conditions'=>array('DiscountUser.daily_discount_id'=>$id)));
	$buyerListStr='';
	if(!empty($allBuyers))
	{
		$buyerListStr.="<table width='100%' style='font-size: 14px;'>";
		$redeemText=0;
		
		$buyerListStr.="
		<tr>
			<th width='13%' valign='top'>Name</th>
			<th width='18%' valign='top'>Purchased Vouchers</th>
			<th width='18%' valign='top'>Redeemed Vouchers</th>
			<th width='17%' valign='top'>Remaining Vouchers</th>
			<th width='17%' valign='top'>Purchase Date</th>
			<th width='17%' valign='top'>date of redemption</th>
		</tr>";
		
		foreach($allBuyers as $allBuyers)
		{
			$redeem = '';
			$full_history = '';
			if($allBuyers['DiscountUser']['voucher_history']) {
			$history = explode('|',$allBuyers['DiscountUser']['voucher_history']);
			if(!empty($history)) {
				$i = 1;
				$total = count($history);
				foreach($history as $hstry) {
					$inner_htry = '';
					$inner_htry = explode('-',$hstry);
					if($total==$i) {
						$full_history .= $inner_htry[0].' ('.date(DATE_FORMAT,$inner_htry[1]).')';
					} else {
						$full_history .= $inner_htry[0].' ('.date(DATE_FORMAT,$inner_htry[1]).')<br />';
					}
					$i++;
				}
			}
			}
		  	$fid=$allBuyers['FrontUser']['id'];
			$redeem="redeem_voucher".$redeemText;
			$ulimit=$allBuyers['DiscountUser']['vouchers'];
			$rem_voucher=0;
			
			$rem_voucher=$allBuyers['DiscountUser']['vouchers']-$allBuyers['DiscountUser']['voucher_redeemed'];
			
			$buyerListStr.="<tr><td align='center' valign='top'>".$allBuyers['FrontUser']['name']."</td><td align='center' valign='top'>".$allBuyers['DiscountUser']['vouchers']."</td><td align='center' valign='top'><span id='voucherRedeem'>".$allBuyers['DiscountUser']['voucher_redeemed']."</span></td><td align='center' valign='top'><span id='voucherRemaining'>".$rem_voucher."</span></td><td align='center' valign='top'>".date(DATE_FORMAT,$allBuyers['DiscountUser']['purchase_date'])."</td><td align='center' valign='top'><span id='history".$allBuyers['FrontUser']['id']."'>".$full_history."</span></td>";
			$buyerListStr.="</tr>";
			$redeemText++;
		}
		$buyerListStr.="</table>";
		echo $buyerListStr;
	} else {
		echo '<div style="text-align:center; font-weight:bold;">No Big Deal Buyer Available</div>';
	}
}	
/*-------------------------------------------Used to fetch all discount buyers----------------------------------------------*/
function printDiscountUser1($dicount_id)	{
			$this->layout = 'discount_user_print';		
			if($dicount_id) {
				$this->set('dicount_id',$dicount_id);
				$this->loadModel('FrontUser');
				$this->loadModel('DiscountUser');
				$allBuyers=$this->DiscountUser->find('all',array('conditions'=>array('DiscountUser.daily_discount_id'=>$dicount_id)));
				$this->set('allBuyers',$allBuyers);
			}
	}

/*-------------------------------------------Used to update redeemed discount vouchers----------------------------------------------*/
function ajaxRedeemUpdate($fid='',$redeem='',$d_user_id='')
{
	$this->autoRender = false;
	$this->loadModel('FrontUser');
	$this->loadModel('DiscountUser');
	$this->DiscountUser->id=$d_user_id;
	$redeemedAlraedyVoucher=$this->DiscountUser->field('DiscountUser.vouchers');
	$redeemedVoucher=$this->DiscountUser->field('DiscountUser.voucher_redeemed');	
	$voucher_history=$this->DiscountUser->field('DiscountUser.voucher_history');
	if($voucher_history!='') {
		$save_history = $voucher_history.'|'.$redeem.'-'.time();
	} else {
		$save_history = $redeem.'-'.time();
	}	
	$redeemOrg=$redeemedVoucher+$redeem;
	
	if($redeemedAlraedyVoucher==$redeemedVoucher)
	{
		echo 'redeemed';
	}	
	elseif($redeemOrg>$redeemedAlraedyVoucher)
	{
		echo 'fail';			
	}
	else
	{
		$saveRedeemArray='';
		$saveRedeemArray['DiscountUser']['id']=$d_user_id;
		$saveRedeemArray['DiscountUser']['voucher_redeemed']=$redeemOrg;
		$saveRedeemArray['DiscountUser']['voucher_history']=$save_history;
		$this->DiscountUser->save($saveRedeemArray);
		echo "success_$redeemOrg";
	}
}

	function printVoucher($unique_id='',$unique_string='',$encode_email='') {
		$this->layout = false;
		$email = base64_decode($encode_email);
		if($unique_id && $unique_string) {
			$this->set('voucher_no',$unique_string);
			$discount = $this->DailyDiscount->find('first',array('conditions'=>array('DailyDiscount.unique'=>$unique_id)));
			if(is_array($discount) && !empty($discount)) {
				$this->set('discount',$discount);
				$this->set('email',$email);
			} else {
				$this->render('/errors/url_error');
			}			
		} else {
			$this->render('/errors/url_error');
		}	
	}
	
	function mailVoucher($unique_id='',$unique_string='',$encode_email='') {
		$this->layout = false;
		$email = base64_decode($encode_email);
		if($unique_id && $unique_string) {
			$this->set('voucher_no',$unique_string);
			$discount = $this->DailyDiscount->find('first',array('conditions'=>array('DailyDiscount.unique'=>$unique_id)));
			if(is_array($discount) && !empty($discount)) {
				$this->set('discount',$discount);
				$this->set('email',$email);
			} else {
				$this->render('/errors/url_error');
			}			
		} else {
			$this->render('/errors/url_error');
		}	
	}
/*-----------------------------------------------------------------------------------------------------------------------------*/				
	function discountSplit($dicount_id='',$ad_id='') {
		if($dicount_id && $ad_id) {
		
			$this->DailyDiscount->id = $dicount_id;
			$discount_info = $this->DailyDiscount->read();
			$this->set('discount_info',$discount_info);
			$this->set('ad_id',$ad_id);
			
			$this->loadModel('DiscountUser');
			$allDiscount = $this->DiscountUser->find('all',array('conditions'=>array('DiscountUser.daily_discount_id'=>$dicount_id)));
			$this->set('Discount',$allDiscount);
						
			//pr($allDiscount);
		} else {
			$this->Session->setFlash('Invalid Big Deal id.');
			$this->redirect(array('action'=>'dailyDiscountCommon'));
		}
	}
/*-----------------------------------------------------------------------------------------------------------------------------*/				
	function printDiscountSplit($dicount_id='',$ad_id='') {
		if($dicount_id && $ad_id) {
			$this->layout = false;
			$this->DailyDiscount->id = $dicount_id;
			$discount_info = $this->DailyDiscount->read();
			$this->set('discount_info',$discount_info);
			$this->set('ad_id',$ad_id);
			
			$this->loadModel('DiscountUser');
			$allDiscount = $this->DiscountUser->find('all',array('conditions'=>array('DiscountUser.daily_discount_id'=>$dicount_id)));
			$this->set('Discount',$allDiscount);
		} else {
			$this->Session->setFlash('Invalid Big Deal id.');
			$this->redirect(array('action'=>'dailyDiscountCommon'));
		}
	}
//---------------------------------------------------------------------------------------------//
	function SaveDiscountInfo($d_info_id,$d_user_id) {
			$this->autoRender = false;
			if($this->Session->read('Auth.FrontUser')) {
				$this->loadModel('DiscountInfo');
				$this->loadModel('DiscountUser');
				
				$savearr = '';
				$savearr1 = '';
				
				$savearr['DiscountInfo']['id'] = $d_info_id;
				$savearr['DiscountInfo']['status'] = 1;
				$savearr['DiscountInfo']['redeem_date'] = time();
				$this->DiscountInfo->save($savearr);
				
				$totalVoucher = $this->DiscountUser->find('first',array('fields'=>array('DiscountUser.voucher_redeemed'),'conditions'=>array('DiscountUser.id'=>$d_user_id)));				
				$savearr1['DiscountUser']['id'] = $d_user_id;
				$savearr1['DiscountUser']['voucher_redeemed'] = $totalVoucher['DiscountUser']['voucher_redeemed']+1;
				$this->DiscountUser->save($savearr1);
				$this->Session->setFlash('Voucher redeemed successfully.');
				echo FULL_BASE_URL.router::url('/',false).''.$this->referer();
			}			
		}
/***-----------------------This function show all records of daily discount vouchers(18-dec-2012)-------------------------------*/
	function allDiscountVoucher() {
		$this->set('title_for_layout','Big Deal Order Manager');
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
		$this->set('search_text','Discount Title');
		$this->set('s_date','');
		$this->set('county', 'County');
		$this->set('advertiser_profile_id', 'Advertiser');
		$chk=1;
		$this->set('chk',$chk);// used to check the redircted page after the update
		
		$cond = '';
		$cond[] = "FrontUser.id!='' AND DailyDiscount.id!='' AND AdvertiserProfile.id!=''";
		$this->loadModel('DiscountUser');
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('DiscountUser.created' => 'desc'));
		

		 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
//if advertiser is set		
		if((isset($this->data['daily_discounts']['advertiser_profile_id']) and $this->data['daily_discounts']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['daily_discounts']['advertiser_profile_id']) and $this->data['daily_discounts']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['daily_discounts']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'] ;
			} else {			  
			  $advertiser_profile_id ="";
			}			
			$this->set('advertiser_profile_id',$advertiser_profile_id); 
		}
		
		

// if county is set
		if((isset($this->data['daily_discounts']['county']) and $this->data['daily_discounts']['county'] != 0)|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
			if((isset($this->data['daily_discounts']['county']) and $this->data['daily_discounts']['county'] != 0))	{
				$county = $this->data['daily_discounts']['county'] ;
			}
            else if((isset($this->params['named']['county'])) and $this->params['named']['county'] !=''){
             	$county = $this->params['named']['county'] ;
			}else{
				$county = '';
			}
			$this->set('county',$county);
		}
		
		
// if title is set
		if((isset($this->data['daily_discounts']['search_text']) and ($this->data['daily_discounts']['search_text'] != '' and $this->data['daily_discounts']['search_text'] != 'Discount Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Discount Title') )) {
			if((isset($this->data['daily_discounts']['search_text']) and ($this->data['daily_discounts']['search_text'] != '' and $this->data['daily_discounts']['search_text'] != 'Discount Title')))
			{
			 $search_text = $this->data['daily_discounts']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Discount Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			} else {
               $search_text ='';
			}
			$this->set('search_text',$search_text);
		}
		
		
// if start date is set
		if((isset($this->data['daily_discounts']['s_date']) and $this->data['daily_discounts']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['daily_discounts']['s_date']) and $this->data['daily_discounts']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['daily_discounts']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['daily_discounts']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}
			else if( (isset($this->params['named']['s_date'])) and $this->params['named']['s_date'] !=''){
			 
			  $arrS_date = explode("-",$this->params['named']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			 
			 $s_date = $this->params['named']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}else{
			 $s_date ="";
			}
			
			$this->set('s_date',$s_date);
			
			$this->set('s_datetmsp',$s_datetmsp);
			
		
		}

		 /*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
		 	
		if(isset($advertiser_profile_id) && $advertiser_profile_id !=''){
		 $cond['DailyDiscount.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($county) && $county !=''){
		 $cond['DailyDiscount.advertiser_county_id'] = $county;
		}
		
		if(isset($search_text) && $search_text !=''){
		 $cond['DailyDiscount.title LIKE'] = '%'.$search_text. '%';
		}
		
		
		if(isset($s_date) && $s_date !=''){
		
		   $cond['DiscountUser.purchase_date ='] = $s_datetmsp ;
		
		}
		
		$data = $this->paginate('DiscountUser', $cond);
		$this->set('daily_discount_orders', $data);
	}
/*-------------------------view discount order detail---------------*/
function discountVoucherView($did=null){
		$this->set('title_for_layout','Big Deal Order Detail');
		$this->loadModel('DiscountUser');
		$this->set('data',$this->DiscountUser->findbyId($did));	
		$this->loadModel('DiscountInfo');
		$this->set('dataVouchers',$this->DiscountInfo->find('all',array('conditions'=>array('DiscountInfo.discount_user_id'=>$did))));}
//---------------------------------------------------------------------------------------------------------------------------------//
	function sendDiscountMail($discount_user) {
		
		if($discount_user) {
			$this->loadModel('DiscountUser');
			$this->id = $discount_user;
			$info = $this->DiscountUser->read('',$this->id);
			$county = $info['FrontUser']['county_id'];
			$email=$info['FrontUser']['email'];
			$name=$info['FrontUser']['name'];
			$discount=$info['DailyDiscount']['title'];
			$advertiser=$info['AdvertiserProfile']['company_name'];
			$totalVoucher=$info['DiscountUser']['vouchers'];
			$price=number_format(($info['DiscountUser']['total_price']/$info['DiscountUser']['vouchers']),2);
			$total=$info['DiscountUser']['total_price'];
			
			$this->loadModel('DiscountInfo');
			$data = $this->DiscountInfo->find('all',array('conditions'=>array('DiscountInfo.discount_user_id'=>$info['DiscountUser']['id'])));
			
			$uniquearr = '';
			$print_link = '<br />';
			$encoded_email = base64_encode($info['FrontUser']['email']);
			$w = 0;
			foreach($data as $data) {
				$print_link .= '<a href="'.FULL_BASE_URL.router::url('/',false).'daily_discounts/printVoucher/'.$info['DailyDiscount']['unique'].'/'.$data['DiscountInfo']['voucher'].'/'.$encoded_email.'">Print Voucher '.($w+1).'</a><br />';							
				$w++;
			}
			
			$arrayTags = array("[consumer_name]","[discount]","[advertiser]","[vouchers]","[price]","[total_price]","[voucher_link]");
			$arrayReplace = array($name,$discount,$advertiser,$totalVoucher,$price,$total,$print_link);
			
			//get Mail format
			$this->loadModel('Setting');
			$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.discount_link_subject','Setting.discount_link_body')));
			$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['discount_link_subject']);
			$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['discount_link_body']);
			
			//ADMINMAIL id
			$this->Email->to 		= $email;
			$this->Email->subject 	= strip_tags($subject);
			$this->Email->replyTo 	= $this->common->getReturnEmail();
			$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
			$this->Email->sendAs 	= 'html';
			//Set the body of the mail as we send it.
			//seperate line in the message body.
			$this->body = '';
			$this->body = $this->emailhtml->email_header($county);
			$this->body .=$bodyText;
			$this->body .= $this->emailhtml->email_footer($county);
			$this->Email->smtpOptions = array(
					'port'=>'25',
					'timeout'=>'30',
					'host' =>SMTP_HOST_NAME,
					'username'=>SMTP_USERNAME,
					'password'=>SMTP_PASSWORD
				);
				/* Set delivery method */
				$this->Email->delivery = 'smtp';
				/* Do not pass any args to send() */
				$this->Email->send($this->body);
			///////////////////////////sent mail insert to sent box ///////////////////
				$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"discount_purchase_admin");
			/////////////////////////////////////////////////////////////////////////
				$this->Session->setFlash('Voucher receipt has been sent successfully.');
				$this->redirect($this->referer());
		}
			$this->redirect($this->referer());
	}
//------------------------------------------------------------------------------------------------------------------//
	function ecommerceTracking($transaction_id,$company,$totalPrice,$city,$state,$discount_id,$discount_title,$single_price,$quantity) {
		$this->layout = false;
		$this->loadModel('DiscountUser');
		$data = $this->DiscountUser->find('first',array('fields'=>array('DiscountUser.id'),'conditions'=>array('DiscountUser.transaction_id'=>$transaction_id)));
		$transaction_id = $data['DiscountUser']['id'];
		
		$this->set('transaction_id',$transaction_id);
		$this->set('company',$company);
		$this->set('totalPrice',$totalPrice);
		$this->set('city',$city);
		$this->set('state',$state);
		$this->set('discount_id',$discount_id);
		$this->set('discount_title',$discount_title);
		$this->set('single_price',$single_price);
		$this->set('quantity',$quantity);
	}
//------------------------------------------------------------------------------------------------------------------//
/*
    this function is checking username and password in database
	and if true then redirect to home page
	*/
	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );
			$this->Auth->allow('ajaxDiscountUser','ajaxRedeemUpdate','PrintDiscountUser','printDiscountUser1','printVoucher','mailVoucher','SaveDiscountInfo','ecommerceTracking');
			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
   	}
	
/*------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function crop($pass='') {
	if($pass!='') {
		$break = explode('/',base64_decode($pass));
		if(count($break)==2) {
			$img = base64_decode($break[0]);
			$url = base64_decode($break[1]);
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				if(trim($_POST['w'])=='' || trim($_POST['h'])=='') {
					$this->Session->setFlash('Invalid dimensions for resizing.');
				} else {
				ini_set('memory_limit', '-1');
				$size = getimagesize(WWW_ROOT.'img/'.$img); 
					switch ($size['mime']) { 
					case "image/gif": 
						$src_image = imagecreatefromgif(WWW_ROOT.'img/'.$img);
						break; 
					case "image/jpeg": 
						$src_image = imagecreatefromjpeg(WWW_ROOT.'img/'.$img);
						break; 
					case "image/png": 
						$src_image = imagecreatefrompng(WWW_ROOT.'img/'.$img);
						break;
					}
					
					$dst_x = 0;
					$dst_y = 0;
					$src_x = $_POST['x1']; // Crop Start X
					$src_y = $_POST['y1']; // Crop Srart Y
					$dst_w = (int)$_POST['w']; // Thumb width
					$dst_h = (int)$_POST['h']; // Thumb height
					$src_w = (int)$_POST['w'];//(int)($_POST['w']+$_POST['x2']);
					$src_h = (int)$_POST['h'];//(int)($_POST['h']+$_POST['y2']);
					
					$dst_image = imagecreatetruecolor($dst_w,$dst_h);
					imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
					
					switch ($size['mime']) { 
					case "image/gif": 
						imagegif($dst_image, WWW_ROOT.'img/'.$img);
						break; 
					case "image/jpeg": 
						imagejpeg($dst_image, WWW_ROOT.'img/'.$img);
						break; 
					case "image/png": 
						imagepng($dst_image, WWW_ROOT.'img/'.$img);
						break;
					}
					$this->Session->setFlash('Image has been resized successfully.');
					$this->redirect($url.'/type:success');
				}	
			}
			$this->set('img',$img);
			$this->set('url',$url);
		} else {
			$this->redirect($this->referer());
		}
	} else {
		$this->redirect($this->referer());
	}
}
	/* This function is setting all info about current Admins in 
	currentAdmin array so we can use it anywhere lie name id etc.
	*/
	function check_home($id=0) {
		$this->autoRender = false;
		if($id) {
			echo $this->common->homeDiscountPerm($id).'-'.$this->common->categoryDiscountPerm($id);
		} else {
			echo '0-0';
		}
	}
	function beforeRender(){
		$this->set('currentAdmin', $this->Auth->user());
		$this->set('cssName',$this->Cookie->read('css_name'));
        $this->set('groupDetail',$this->common->adminDetails());
		$this->set('common',$this->common);
		$this->set('Email',$this->Email);
		//$this->Ssl->force();
	}
	
}
?>