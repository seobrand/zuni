<?php 
/*
   Software Engineer : Manoj
   Date  : 13 May 2011
*/ 
class DailyDealsController extends AppController{
 var $name = 'DailyDeals'; 
 var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar'); 
 var $components = array('Auth','common','Session','Cookie','RequestHandler');
 var $layout = 'admin'; 
 
 
/***-----------------------This function is the Index function i.e. call by default-------------------------------------------------------------------------------*/
	function index(){
		$this->set('title_for_layout','Today\'s Freebie');
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
		$this->set('county', 'County');
		$this->set('advertiser_profile_id', 'Advertiser');
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('DailyDeal.id' => 'asc'));
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] !='' )
		  {
			 $cond=array('DailyDeal.advertiser_profile_id' => $this->params['pass'][0]);
			 (empty($this->params['named'])) ? $this->set('advertiser_profile_id', $this->params['pass'][0]) :$this->set('advertiser_profile_id', $this->params['pass'][0]) ;
			 $this->set('advertiser_id',$this->params['pass'][0]);
		  }
		//if advertiser is set
		if((isset($this->data['daily_deals']['advertiser_profile_id']) and $this->data['daily_deals']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['daily_deals']['advertiser_profile_id']) and $this->data['daily_deals']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['daily_deals']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'];
			}else{
			  $advertiser_profile_id ="";
			}
			$this->set('advertiser_profile_id',$advertiser_profile_id);
		}
		//if categry is set
		if((isset($this->data['daily_deals']['category']) and $this->data['daily_deals']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
			if((isset($this->data['daily_deals']['category']) and $this->data['daily_deals']['category'] != 0))
			{
			 	$category = $this->data['daily_deals']['category'];
			} else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             	$category = $this->params['named']['category'];
			} else {
				$category = '';
			}
			$this->set('category',$category);
		}
		//if county is set
		if((isset($this->data['daily_deals']['county']) and $this->data['daily_deals']['county'] != 0)|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
			if((isset($this->data['daily_deals']['county']) and $this->data['daily_deals']['county'] != 0))
			{
			 	$county = $this->data['daily_deals']['county'] ;
			}
            else if( (isset($this->params['named']['county'])) and $this->params['named']['county'] !=''){
             	$county = $this->params['named']['county'];
			}else{
				$county = '';
			}
			$this->set('county',$county);
		}
		//if title is set
		if((isset($this->data['daily_deals']['search_text']) and ($this->data['daily_deals']['search_text'] != '' and $this->data['daily_deals']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['daily_deals']['search_text']) and ($this->data['daily_deals']['search_text'] != '' and $this->data['daily_deals']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['daily_deals']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
		
		//if start and end dates are set 
		
		if((isset($this->data['daily_deals']['s_date']) and $this->data['daily_deals']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['daily_deals']['s_date']) and $this->data['daily_deals']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['daily_deals']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['daily_deals']['s_date'] ;
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
		
		
		if((isset($this->data['daily_deals']['e_date']) and $this->data['daily_deals']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['daily_deals']['e_date']) and $this->data['daily_deals']['e_date'] != ''))
			{
			
			  $arrE_date = explode("/",$this->data['daily_deals']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['daily_deals']['e_date'] ;
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
		
		if((isset($this->data['daily_deals']['publish_page']) and $this->data['daily_deals']['publish_page'] != '')|| ( isset($this->params['named']['publish_page']) and $this->params['named']['publish_page'] !='')){
			if((isset($this->data['daily_deals']['publish_page']) and $this->data['daily_deals']['publish_page'] != ''))
			{
			 $publish_page = $this->data['daily_deals']['publish_page'] ;
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
		 $cond['DailyDeal.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($category) && $category !=''){
		 $cond['DailyDeal.category'] = $category;
		}
		
		if(isset($county) && $county !=''){
		 $cond['DailyDeal.advertiser_county_id'] = $county;
		}

		if(isset($search_text) && $search_text !=''){
		 $cond['DailyDeal.title LIKE'] = '%'.$search_text. '%';
		}
		
		if(isset($publish_page) && $publish_page !=''){
			if($publish_page=="home_page")
			{
				$cond['DailyDeal.show_on_home_page'] = 1;
		 	}
		 	else
		 	{
		  		$cond['DailyDeal.show_on_category'] = 1;
			}
		}
				
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['DailyDeal.s_date >='] = $s_datetmsp ;
		  $cond['DailyDeal.e_date <='] = $e_datetmsp ;
		}
		
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){
		
		   $cond['DailyDeal.s_date ='] = $s_datetmsp ;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['DailyDeal.e_date ='] = $e_datetmsp ;
		}

		
		$data = $this->paginate('DailyDeal', $cond);
		$this->set('daily_deals', $data);
	
	}
	
/***----------This function is the Index function for all deals--   Surbhit  Date :- 08 June 2011-----------------------------------------*/	
function dailyDealCommon(){
		$this->set('title_for_layout','Today\'s Freebie Common');
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
		$this->set('county', 'County');
		$this->set('advertiser_profile_id', 'Advertiser');
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('DailyDeal.id' => 'asc'));
		
		
		//pr($this->params);
		
		if((isset($this->data['daily_deals']['advertiser_profile_id']) and $this->data['daily_deals']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['daily_deals']['advertiser_profile_id']) and $this->data['daily_deals']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['daily_deals']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'] ;
			}else{
			  
			  $advertiser_profile_id ="";
			}
			
			$this->set('advertiser_profile_id',$advertiser_profile_id); 
		}
		
		
		if((isset($this->data['daily_deals']['category']) and $this->data['daily_deals']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
		
		
			if((isset($this->data['daily_deals']['category']) and $this->data['daily_deals']['category'] != 0))
			{
			 $category = $this->data['daily_deals']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
// if county is set
		if((isset($this->data['daily_deals']['county']) and $this->data['daily_deals']['county'] != 0)|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
		
		
		
			if((isset($this->data['daily_deals']['county']) and $this->data['daily_deals']['county'] != 0))
			{
			 $county = $this->data['daily_deals']['county'] ;
			}
            else if( (isset($this->params['named']['county'])) and $this->params['named']['county'] !=''){
             $county = $this->params['named']['county'] ;
			}else{
			$county = '';
			}
			$this->set('county',$county);
		}
		
		if((isset($this->data['daily_deals']['search_text']) and ($this->data['daily_deals']['search_text'] != '' and $this->data['daily_deals']['search_text'] != 'Title'))|| (isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['daily_deals']['search_text']) and ($this->data['daily_deals']['search_text'] != '' and $this->data['daily_deals']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['daily_deals']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
		
		if((isset($this->data['daily_deals']['s_date']) and $this->data['daily_deals']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['daily_deals']['s_date']) and $this->data['daily_deals']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['daily_deals']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['daily_deals']['s_date'] ;
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
		
		
		if((isset($this->data['daily_deals']['e_date']) and $this->data['daily_deals']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['daily_deals']['e_date']) and $this->data['daily_deals']['e_date'] != ''))
			{
			
			  $arrE_date = explode("/",$this->data['daily_deals']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['daily_deals']['e_date'] ;
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
		
		if((isset($this->data['daily_deals']['publish_page']) and $this->data['daily_deals']['publish_page'] != '')|| ( isset($this->params['named']['publish_page']) and $this->params['named']['publish_page'] !='')){
			if((isset($this->data['daily_deals']['publish_page']) and $this->data['daily_deals']['publish_page'] != ''))
			{
			 $publish_page = $this->data['daily_deals']['publish_page'] ;
			}
			else if( isset($this->params['named']['publish_page']) and $this->params['named']['publish_page'] !=''){
			 $publish_page = $this->params['named']['publish_page'] ;
			}else{
			  
			  $publish_page ="";
			}
			
			$this->set('publish_page',$publish_page); 
		}
		
				
		if(isset($advertiser_profile_id) && $advertiser_profile_id !=''){
		 $cond['DailyDeal.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($category) && $category !=''){
		 $cond['DailyDeal.category'] = $category;
		}
		
		if(isset($county) && $county !=''){
		 $cond['DailyDeal.advertiser_county_id'] = $county;
		}

		if(isset($search_text) && $search_text !=''){
		 $cond['DailyDeal.title LIKE'] = '%'.$search_text. '%';
		}

		if(isset($publish_page) && $publish_page !=''){
			if($publish_page=="home_page")
			{
				$cond['DailyDeal.show_on_home_page'] = 1;
		 	}
		 	else
		 	{
		  		$cond['DailyDeal.show_on_category'] = 1;
			}
		}
		
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['DailyDeal.s_date >='] = $s_datetmsp ;
		  $cond['DailyDeal.e_date <='] = $e_datetmsp ;
		}
		
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){
		
		   $cond['DailyDeal.s_date ='] = $s_datetmsp ;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['DailyDeal.e_date ='] = $e_datetmsp ;
		}

		$data = $this->paginate('DailyDeal', $cond);
		$this->set('daily_deals', $data);
	
	} 
	
	
/***----------This function is the Index function for all archive deals--  Keshav  Date :- 20 Sep 2011-----------------------------------------*/	
function archivedailyDeal(){
		$this->set('title_for_layout','Freebie Archive');
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
		$this->set('county', 'County');
		$this->set('advertiser_profile_id', 'Advertiser');
		$cond = '';
		$time = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$cond['DailyDeal.e_date <'] = $time;
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('DailyDeal.id' => 'asc'));
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] !='' )
		  {
			 $cond=array('DailyDeal.advertiser_profile_id' => $this->params['pass'][0]);
			 (empty($this->params['named'])) ? $this->set('advertiser_profile_id', $this->params['pass'][0]) :$this->set('advertiser_profile_id', $this->params['pass'][0]) ;
			 $this->set('advertiser_id',$this->params['pass'][0]);
		  }
		//if advertiser is set
		if((isset($this->data['daily_deals']['advertiser_profile_id']) and $this->data['daily_deals']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['daily_deals']['advertiser_profile_id']) and $this->data['daily_deals']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['daily_deals']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'];
			}else{
			  $advertiser_profile_id ="";
			}
			$this->set('advertiser_profile_id',$advertiser_profile_id);
		}
		//if categry is set
		if((isset($this->data['daily_deals']['category']) and $this->data['daily_deals']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
			if((isset($this->data['daily_deals']['category']) and $this->data['daily_deals']['category'] != 0))
			{
			 	$category = $this->data['daily_deals']['category'];
			} else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             	$category = $this->params['named']['category'];
			} else {
				$category = '';
			}
			$this->set('category',$category);
		}
		//if county is set
		if((isset($this->data['daily_deals']['county']) and $this->data['daily_deals']['county'] != 0)|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
			if((isset($this->data['daily_deals']['county']) and $this->data['daily_deals']['county'] != 0))
			{
			 	$county = $this->data['daily_deals']['county'] ;
			}
            else if( (isset($this->params['named']['county'])) and $this->params['named']['county'] !=''){
             	$county = $this->params['named']['county'];
			}else{
				$county = '';
			}
			$this->set('county',$county);
		}
		//if title is set
		if((isset($this->data['daily_deals']['search_text']) and ($this->data['daily_deals']['search_text'] != '' and $this->data['daily_deals']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['daily_deals']['search_text']) and ($this->data['daily_deals']['search_text'] != '' and $this->data['daily_deals']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['daily_deals']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
		
		//if start and end dates are set 
		
		if((isset($this->data['daily_deals']['s_date']) and $this->data['daily_deals']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['daily_deals']['s_date']) and $this->data['daily_deals']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['daily_deals']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['daily_deals']['s_date'] ;
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
		
		
		if((isset($this->data['daily_deals']['e_date']) and $this->data['daily_deals']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['daily_deals']['e_date']) and $this->data['daily_deals']['e_date'] != ''))
			{
			
			  $arrE_date = explode("/",$this->data['daily_deals']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['daily_deals']['e_date'] ;
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
		
		if((isset($this->data['daily_deals']['publish_page']) and $this->data['daily_deals']['publish_page'] != '')|| ( isset($this->params['named']['publish_page']) and $this->params['named']['publish_page'] !='')){
			if((isset($this->data['daily_deals']['publish_page']) and $this->data['daily_deals']['publish_page'] != ''))
			{
			 $publish_page = $this->data['daily_deals']['publish_page'];
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
		 $cond['DailyDeal.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($category) && $category !=''){
		 $cond['DailyDeal.category'] = $category;
		}
		
		if(isset($county) && $county !=''){
		 $cond['DailyDeal.advertiser_county_id'] = $county;
		}

		if(isset($search_text) && $search_text !=''){
		 $cond['DailyDeal.title LIKE'] = '%'.$search_text. '%';
		}
		
		if(isset($publish_page) && $publish_page !=''){
			if($publish_page=="home_page")
			{
				$cond['DailyDeal.show_on_home_page'] = 1;
		 	}
		 	else
		 	{
		  		$cond['DailyDeal.show_on_category'] = 1;
			}
		}
				
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['DailyDeal.s_date >='] = $s_datetmsp ;
		  $cond['DailyDeal.e_date <='] = $e_datetmsp ;
		}
		
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){
		
		   $cond['DailyDeal.s_date ='] = $s_datetmsp ;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['DailyDeal.e_date ='] = $e_datetmsp ;
		}

		
		$data = $this->paginate('DailyDeal', $cond);
		$this->set('daily_deals', $data);
	
	} 
/***-----------------------This function used to view archive daily deal in database-----------------------------------------------------------------------------*/
	
	function viewArchiveDeal($id=null,$adv_id=null)
	{
		$this->set('title_for_layout','Archive Daily Deal');
		$this->set('advertiser_id',$adv_id);	
		$this->set('data',$this->DailyDeal->findbyId($id));	
	}
			
		
/***-----------------------This function Add new daily deal in database------------------------------------------------------------------------------------------*/
	function addDailyDeal($id=null){
		if(isset($this->params['pass'][0])){
	   		$this->set('advertiser_id', $this->params['pass'][0]);
		}		
		if($this->referer()=='/daily_deals/addDailyDeal' || $this->referer()=='/'){ } else {
			$this->Session->write('referer',$this->referer());
		}		
		$this->set('refferer',$this->Session->read('referer'));
		$this->set('title_for_layout','Add Freebie');
		$this->set('categoryList',$this->common->getAllCategory()); //  List categories
	  	//$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List Advertisers
		$this->set('advertiserList',$this->common->getAdvertiserProfilesForDeal()); //  List Advertisers
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
				if(isset($this->data['daily_deal']['subcategory'])) {
					$this->data['DailyDeal']['subcategory'] = $this->data['daily_deal']['subcategory'];
				}
				  $this->DailyDeal->set($this->data['DailyDeal']);
		  	   	  $this->set('advertiser_id', $this->data['DailyDeal']['advertiser_profile_id']);//element use only
				  if($this->DailyDeal->validates())
				  { 
							$cats_combination = explode('-',$this->data['DailyDeal']['subcategory']);
							$this->data['DailyDeal']['category'] = $cats_combination[0];
							$this->data['DailyDeal']['subcategory'] = $cats_combination[1];
							
					/*------------------------------------find county of specified advertiser---------------------------------------------*/
						
						$county = $this->DailyDeal->getCityCountyState($this->data['DailyDeal']['advertiser_profile_id']);
						
						$this->data['DailyDeal']['advertiser_county_id']	=	$county[0]['advertiser_profiles']['county'];
						
					/*--------------------------------------------------------------------------------------------------------------------*/	
											
						if(!empty($this->data['DailyDeal']['sdate']))
						{
							$s_date		= $this->data['DailyDeal']['sdate'];
							$start_date	= explode('/',$s_date);
							$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
						}
						else
						{
							$start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						}
						$this->data['DailyDeal']['s_date']= $start_date;
						
						
						if(!empty($this->data['DailyDeal']['edate']))
						{
							$e_date		= $this->data['DailyDeal']['edate'];
							$expiry_date	= explode('/',$e_date);
							$expiry_date = mktime(0,0,0,$expiry_date[0],$expiry_date[1],$expiry_date[2]);
						}
						else
						{
							$expiry_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						}
						$this->data['DailyDeal']['e_date']= $expiry_date;			
						
			/*-------------------------------------------------------CATEGORY PAGE-------------------------------------------------------------*/						
						if(!empty($this->data['DailyDeal']['c_s_date']))
						{
							$c_s_date		= $this->data['DailyDeal']['c_s_date'];
							$c_start_date	= explode('/',$c_s_date);
							$c_start_date = mktime(0,0,0,$c_start_date[0],$c_start_date[1],$c_start_date[2]);
						}
						else
						{
							$c_start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						}
						$this->data['DailyDeal']['c_s_date']= $c_start_date;
						
						if(!empty($this->data['DailyDeal']['c_e_date']))
						{
							$c_e_date		= $this->data['DailyDeal']['c_e_date'];
							$c_expiry_date	= explode('/',$c_e_date);
							$c_expiry_date = mktime(date('h'),date('i'),date('s'),$c_expiry_date[0],$c_expiry_date[1],$c_expiry_date[2]);
						}
						else
						{
							$c_expiry_date = mktime(date('h'),date('i'),date('s'),date('m'),date('t',strtotime('today')),date('Y'));
						}				
						$this->data['DailyDeal']['c_e_date']= $c_expiry_date;					
						
						/*-------------------------------image uploaded function-------------------------------------------------------------*/
					
					if($this->data['DailyDeal']['banner_image']['name']!='')
					{
						$type = $this->data['DailyDeal']['banner_image']['type'];
						
						if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif")
						{                           
							$this->data['DailyDeal']['banner_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['DailyDeal']['banner_image']['name']);
							$docDestination = APP.'webroot/img/deals/'.$this->data['DailyDeal']['banner_image']['name']; 
							
							@chmod(APP.'webroot/img/deals',0777);
							
							move_uploaded_file($this->data['DailyDeal']['banner_image']['tmp_name'], $docDestination) or die($docDestination);
							
							$this->data['DailyDeal']['banner_image'] = $this->data['DailyDeal']['banner_image']['name'];
							
						}
						else
						{
							$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
						}	
					}
					else
					{
						$this->data['DailyDeal']['banner_image'] = $this->data['DailyDeal']['banner_image']['name'];
					}
					
						$this->data['DailyDeal']['unique']	=	$this->common->randomPassword(13);										
					
						$this->DailyDeal->save($this->data['DailyDeal']);
						$DailyDeal_id = $this->DailyDeal->getLastInsertId();
						/*----------------------------------------------------------------------------------------------------*/
						  App::import('model', 'WorkOrder');
						  $this->WorkOrder = new WorkOrder;			  
						  $orderid = $this->common->getOrderId($this->data['DailyDeal']['advertiser_profile_id']);
						  
						  $saveWorkArray = array();
						  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $orderid['AdvertiserProfile']['order_id'];
						  $saveWorkArray['WorkOrder']['read_status']   				=  0;
						  $saveWorkArray['WorkOrder']['subject']   					=  'New Freebie';
						  $saveWorkArray['WorkOrder']['message']   					=  'New Freebie has been launched for the following advertiser profile.';
						  $saveWorkArray['WorkOrder']['type']   					=  'Daily Deal Workorder';
						  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
						  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
						  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');						  
						  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can edit this Freebie or check all other Freebies for this advertiser in Advertiser profiles section and pulish them. Please follow below url:<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'daily_deals/editDailyDeal/'.$DailyDeal_id.'/'.$this->data['DailyDeal']['advertiser_profile_id'].'" style="text-decoration:underline;" target="_blank">Edit New Freebie</a><br /><br />OR<br /><br />
		<a href="'.FULL_BASE_URL.Router::url('/', false).'daily_deals/index/'.$this->data['DailyDeal']['advertiser_profile_id'].'" style="text-decoration:underline;" target="_blank">Freebies Listing</a>';
		
						  date_default_timezone_set('US/Eastern');
						  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
						  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
						  $saveWorkArray['WorkOrder']['salseperson_id']   			=  $this->common->salesIdForAdvertiser($this->data['DailyDeal']['advertiser_profile_id']);
						  $this->WorkOrder->save($saveWorkArray);
						  
					/*----------------------------------------------------------------------------------------------------------*/						
						$this->Session->setFlash('Freebie Successfully Saved'); 
						if(isset($this->data['DailyDeal']['prvs_link']) && (strpos($this->data['DailyDeal']['prvs_link'],'masterSheet')!=false))
						 {
							$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['DailyDeal']['prvs_link']);			
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
						}else {		
								if(strpos($this->data['DailyDeal']['refferer'],'dailyDealCommon')!=false)
								{
						  			$this->redirect(array('action' => "dailyDealCommon/")); 
						  		}else{
									$this->redirect(array('action' => 'index/'.$this->data['DailyDeal']['advertiser_profile_id']));
								}
						}			  
				  }
				  else
				  {
				
						$errors = $this->DailyDeal->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
	}	

/***----------------------This function Edit Existing daily deal in database------------------------------------------------------------------------------------*/
	function editDailyDeal($id=null,$advertiser_id=null){
	
		if($id == '' || !isset($id))
		{
			$id = $this->data['DailyDeal']['uid'];
		}
		else
		{
			$id = $id;
		}
		if(isset($this->params['pass'][1]) || $this->data['DailyDeal']['advertiser_profile_id']){
			$ad_id =  (isset($this->params['pass'][1])) ? $this->params['pass'][1] : $this->data['DailyDeal']['advertiser_profile_id'];
	   		$this->set('advertiser_id', $ad_id);
		}
		  if((strpos($this->referer(),'masterSheet')!=false)) {
		  	$this->Session->write('reff',$this->referer());
		  }
		  if($this->Session->read('reff')) {
		   	$this->set('reff',$this->Session->read('reff'));
		   } else {
		   	$this->set('reff',$this->referer());
		   }
		$this->set('title_for_layout','Edit Freebie');
	 	$this->set('categoryList',$this->common->getAllCategory()); //  List categories
	 	$this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
	  	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List Advertisers
		//$this->set('refferer',$_SERVER['HTTP_REFERER']);
		if($this->Session->read('referer')!='/daily_deals/dailyDealCommon'){ 
			$this->Session->write('referer',$this->referer());
			
		}		
		$this->set('refferer',$this->Session->read('referer'));

		$this->set('data',$this->DailyDeal->findbyId($id));
	  	if(isset($this->data))
				{
				if(isset($this->data['daily_deal']['subcategory'])) {
					$this->data['DailyDeal']['subcategory'] = $this->data['daily_deal']['subcategory'];
				}
				  $this->DailyDeal->set($this->data['DailyDeal']);
				  if($this->DailyDeal->validates())
				  { 
				  		$cats_combination = explode('-',$this->data['DailyDeal']['subcategory']);
						$this->data['DailyDeal']['category'] = $cats_combination[0];
						$this->data['DailyDeal']['subcategory'] = $cats_combination[1];				  	
						
						if(!empty($this->data['DailyDeal']['sdate']))
						{
							$s_date		= $this->data['DailyDeal']['sdate'];
							$start_date	= explode('/',$s_date);
							$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
						}
						else
						{
							$start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						}
						$this->data['DailyDeal']['s_date']= $start_date;
						
						if(!empty($this->data['DailyDeal']['edate']))
						{
							$e_date		= $this->data['DailyDeal']['edate'];
							$expiry_date	= explode('/',$e_date);
							$expiry_date = mktime(0,0,0,$expiry_date[0],$expiry_date[1],$expiry_date[2]);
						}
						else
						{
							$expiry_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						}
						$this->data['DailyDeal']['e_date']= $expiry_date;
						
			/*-------------------------------------------------------CATEGORY PAGE-------------------------------------------------------------*/						
						if(!empty($this->data['DailyDeal']['c_s_date']))
						{
							$c_s_date		= $this->data['DailyDeal']['c_s_date'];
							$c_start_date	= explode('/',$c_s_date);
							$c_start_date = mktime(0,0,0,$c_start_date[0],$c_start_date[1],$c_start_date[2]);
						}
						else
						{
							$c_start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						}
						$this->data['DailyDeal']['c_s_date']= $c_start_date;
						
						if(!empty($this->data['DailyDeal']['c_e_date']))
						{
							$c_e_date		= $this->data['DailyDeal']['c_e_date'];
							$c_expiry_date	= explode('/',$c_e_date);
							$c_expiry_date = mktime(date('h'),date('i'),date('s'),$c_expiry_date[0],$c_expiry_date[1],$c_expiry_date[2]);
						}
						else
						{
							$c_expiry_date = mktime(date('h'),date('i'),date('s'),date('m'),date('t',strtotime('today')),date('Y'));
						}
						$this->data['DailyDeal']['c_e_date']= $c_expiry_date;
						
						/*-------------------------------image uploaded function-------------------------------------------------------------*/
					
					if($this->data['DailyDeal']['banner_image']['name']!='')
					{
						$type = $this->data['DailyDeal']['banner_image']['type'];
						
						if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif")
						{                          
							$this->data['DailyDeal']['banner_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['DailyDeal']['banner_image']['name']);
							@unlink(APP.'webroot/img/deals/'.$this->data['DailyDeal']['oldfilename']);
							
							$docDestination = APP.'webroot/img/deals/'.$this->data['DailyDeal']['banner_image']['name']; 
							
							@chmod(APP.'webroot/img/deals',0777);
							
							move_uploaded_file($this->data['DailyDeal']['banner_image']['tmp_name'], $docDestination) or die($docDestination);
							
							$this->data['DailyDeal']['banner_image'] = $this->data['DailyDeal']['banner_image']['name'];
							
						}
						else
						{
							$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
						}	
					}
					else
					{
						$this->data['DailyDeal']['banner_image'] = $this->data['DailyDeal']['oldfilename'];
					}
					
					/*------------------------------------find county of specified advertiser---------------------------------------------*/
						
						$county = $this->DailyDeal->getCityCountyState($this->data['DailyDeal']['advertiser_profile_id']);
						
						$this->data['DailyDeal']['advertiser_county_id']	=	$county[0]['advertiser_profiles']['county'];
						
						/*---------------------------------------------------------------------------------*/
						
						$this->data['DailyDeal']['id']	=	$this->data['DailyDeal']['uid'];
						
						$this->data['DailyDeal']['unique']	=	$this->common->randomPassword(13);				
						
						if($this->DailyDeal->save($this->data['DailyDeal']))
						
						$this->Session->setFlash('Freebie with id : '.$this->data['DailyDeal']['uid'].' Successfully Updated'); 
						
						/*---------------------------------------------------------------------------------------------------------*/						
						if(isset($this->data['DailyDeal']['prvs_link']) && (strpos($this->data['DailyDeal']['prvs_link'],'masterSheet')!=false)) {
							$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['DailyDeal']['prvs_link']);			
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
							}else {	
						
						if(strpos($this->data['DailyDeal']['refferer'],'dailyDealCommon')!=false){
						  		$this->redirect(array('action' => "dailyDealCommon/")); 
						  }else{
						  		$this->redirect(array('action' => 'index/'.$this->data['DailyDeal']['advertiser_profile_id']));
						  }
						}		
				  
				  }
				  else
				  {
				
						$errors = $this->DailyDeal->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
	}			
/***-----------------------This function Delete the Daily Deal from database-----------------------------------------------------------------------------------*/
	
	function deleteDailyDeal($id=null,$advertiser_id=null){
	  
					$banner_image = $this->DailyDeal->query("SELECT banner_image FROM daily_deals WHERE id ='".$id."'"); 
					
					@unlink(APP.'webroot/img/deals/'.$banner_image[0]['daily_deals']['banner_image']);
					
					$this->DailyDeal->delete($id);
					
					$this->Session->setFlash('The Freebie with id:  '.$id.' has been Deleted Successfully!!');
					
			if((strpos($this->referer(),'masterSheet')!=false)) {
				$ad_id = explode('/',$this->referer());			
				$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
			}else {					
					
					if(isset($this->params['pass'][2])){
					 
					 if($this->params['pass'][2] =='common'){
					 
					 $this->redirect(array('action'=>'dailyDealCommon/'));
					 }
					
					}else{
					$this->redirect(array('action'=>'index/'.$advertiser_id));
					}
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
			$name = $this->DailyDeal->query("SELECT DailyDeal.title FROM daily_deals AS DailyDeal WHERE DailyDeal.title LIKE '$string%' AND DailyDeal.advertiser_profile_id='$id' ");
			}
			elseif($string!='' and $id==-1)
			{
			$cur_time = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$name = $this->DailyDeal->query("SELECT DailyDeal.title FROM daily_deals AS DailyDeal WHERE DailyDeal.title LIKE '$string%' AND DailyDeal.e_date < $cur_time");
			}			
			else
			{
			$name = $this->DailyDeal->query("SELECT DailyDeal.title FROM daily_deals AS DailyDeal WHERE DailyDeal.title LIKE '$string%'");
			}
			foreach($name as $name) {
				$arr[] = $name['DailyDeal']['title'];
			}
			echo json_encode($arr);
			}
	}	

	/*----------------------To show priview on front end in admin-------------------------------------------------------*/ 		
	function preview($id=0) {
			$this->layout = false;
			$DailyDeal = $this->DailyDeal->find('first',array('conditions'=>array('DailyDeal.id'=>$id)));
			$this->set('DailyDeal',$DailyDeal);						
	}	
	
	/*----------------------To maintain reporting on front end--------------------------------------------------------*/ 		
	function reportDeal($adv_id='',$state='',$county='') {
				   $this->autoRender = false;
				   $st_id='';
				   $county_id='';
				   $condi_report='';
				   
				   $st_id=$this->common->getIdfromPageUrl('State',$state);
				   $county_id=$this->common->getIdfromPageUrl('County',$county);
				   App::import('model','InnerReport');
				   $this->InnerReport=new InnerReport();
				   $timestamp=$this->common->getTimeStampReport();
				   $st_id=$st_id['State']['id'];
				   $county_id=$county_id['County']['id'];
				   $condi_report['InnerReport.state']=$st_id;
				   $condi_report['InnerReport.county']=$county_id;
				   $condi_report['InnerReport.date']=$timestamp;
				   $condi_report['InnerReport.type']='deal';
				   $condi_report['InnerReport.advertiser_id']=$adv_id;
				   $exist_rec=$this->InnerReport->find('first',array('conditions'=>$condi_report));
				   
				   if(empty($exist_rec))
				   {
					   $reportArray=array();
					   $reportArray['InnerReport']['state']=$st_id;
					   $reportArray['InnerReport']['county']=$county_id;
					   $reportArray['InnerReport']['date']=$timestamp;
					   $reportArray['InnerReport']['type']='deal';
					   $reportArray['InnerReport']['advertiser_id']=$adv_id;
					   $reportArray['InnerReport']['no_of_hit']=1;
					   $this->InnerReport->save($reportArray);
				   }
				   else
				   {
					   $reportArray=array();
					   $reportArray['InnerReport']['id']=$exist_rec['InnerReport']['id'];
					   $reportArray['InnerReport']['no_of_hit']=$exist_rec['InnerReport']['no_of_hit']+1;
					   $reportArray['InnerReport']['state']=$st_id;
					   $reportArray['InnerReport']['county']=$county_id;
					   $reportArray['InnerReport']['date']=$timestamp;
					   $reportArray['InnerReport']['advertiser_id']=$adv_id;
					   $reportArray['InnerReport']['type']='deal';
					   $this->InnerReport->save($reportArray);
				   }
	}
	/***-----------------------This function Set the Css for Particlar Theme Selection-----------------------------------*/
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

	function selectedCatList(){
	
		if(isset($this->data['DailyDeal']['advertiser_profile_id'])&& $this->data['DailyDeal']['advertiser_profile_id'] !=''){
		$adv_id=$this->data['DailyDeal']['advertiser_profile_id'];
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
//----------------------------------------------------------------------------------------------------------------------------------------------//
	function printVoucher($unique='') {
		$this->layout = false;
		if($unique) {		
			$deal = $this->DailyDeal->find('first',array('conditions'=>array('DailyDeal.unique'=>$unique)));
			if(is_array($deal) && !empty($deal)) {
				$this->set('deal',$deal);
			} else {
				$this->render('/errors/url_error');
			}			
		} else {
			$this->render('/errors/url_error');
		}	
	}
	function check_home($id) {
		$this->autoRender = false;
		echo $this->common->homeDealPerm($id).'-'.$this->common->categoryDealPerm($id);
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
//----------------------------------------------------------------------------------------------------------------------------------------------//					
	/*
    this function is checking username and password in database
	and if true then redirect to home page
	*/
	function beforeFilter() {
        $this->Auth->fields = array(
				'username' => 'username', 
				'password' => 'password'
            );
			$this->Auth->allow('reportDeal','printVoucher');
			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
   	}
	/* This function is setting all info about current Admins in 
	currentAdmin array so we can use it anywhere lie name id etc.
	*/
	function beforeRender(){
		$this->set('currentAdmin', $this->Auth->user());
		$this->set('cssName',$this->Cookie->read('css_name'));
        $this->set('groupDetail',$this->common->adminDetails());
		$this->set('common',$this->common);
		//$this->Ssl->force();
	}
}
?>