<?php
/*
   Coder: Keshav
   Date  : 13 May 2011
*/
class VouchersController extends AppController{
 var $name = 'Vouchers';
 var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax','Calendar');
 var $components = array('Auth','common','Session','Cookie','RequestHandler');
 var $layout = 'admin';
 
/***-----------------------This function is the Index function i.e. call by default-------------------------------------------------------------------------------*/
	function index(){
	if(!isset($this->params['pass'][0])) {
		$this->redirect(array('action'=>'voucherCommon'));
	}
		$this->set('title_for_layout','Gift Certificate');
		$this->set('categoryList',$this->common->getAllCategory());
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		$this->set('cityList',$this->common->getAllCity()); 		//  List cities
		$this->set('stateList',$this->common->getAllState()); 		//  List states
		$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
		$this->set('search_text','Title');
		$this->set('s_date','');
		$this->set('e_date','');
		$this->set('category', 'Category');
		$this->set('county', 'County');
		$this->set('advertiser_profile_id', 'Advertiser');
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('Voucher.id' => 'asc'));
		
		/*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		
		if(isset($this->params['pass'][0]) && $this->params['pass'][0] !='' )
		  {
			 $cond=array('Voucher.advertiser_profile_id' => $this->params['pass'][0]);
			 (empty($this->params['named'])) ? $this->set('advertiser_profile_id', $this->params['pass'][0]) :$this->set('advertiser_profile_id', $this->params['pass'][0]) ; 
			 $this->set('advertiser_id',$this->params['pass'][0]);
		  }
		//if advertiser is set		
		if((isset($this->data['vouchers']['advertiser_profile_id']) and $this->data['vouchers']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['vouchers']['advertiser_profile_id']) and $this->data['vouchers']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['vouchers']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'] ;
			}else{
			  
			  $advertiser_profile_id ="";
			}
			
			$this->set('advertiser_profile_id',$advertiser_profile_id); 
		}
		
		
		//if categry is set
		if((isset($this->data['vouchers']['category']) and $this->data['vouchers']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
		
		
			if((isset($this->data['vouchers']['category']) and $this->data['vouchers']['category'] != 0))
			{
			 $category = $this->data['vouchers']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
		//if county is set
		if((isset($this->data['vouchers']['county']) and $this->data['vouchers']['county'] != 0)|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
		
		
		
			if((isset($this->data['vouchers']['county']) and $this->data['vouchers']['county'] != 0))
			{
			 $county = $this->data['vouchers']['county'] ;
			}
            else if( (isset($this->params['named']['county'])) and $this->params['named']['county'] !=''){
             $county = $this->params['named']['county'] ;
			}else{
			$county = '';
			}
			$this->set('county',$county); 
		}
		
		//if title is set	 		 
		if((isset($this->data['vouchers']['search_text']) and ($this->data['vouchers']['search_text'] != '' and $this->data['vouchers']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['vouchers']['search_text']) and ($this->data['vouchers']['search_text'] != '' and $this->data['vouchers']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['vouchers']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
		
		//if start and end dates are set 
		
		if((isset($this->data['vouchers']['s_date']) and $this->data['vouchers']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['vouchers']['s_date']) and $this->data['vouchers']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['vouchers']['s_date']);
			  $day = $arrS_date[0] ;
			  $month = $arrS_date[1] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['vouchers']['s_date'] ;
			 $s_date = str_replace("/","-",$s_date);
			 $s_datetmsp  = mktime(0,0,0,$month,$day,$year);
			}
			else if( (isset($this->params['named']['s_date'])) and $this->params['named']['s_date'] !=''){
			 
			  $arrS_date = explode("-",$this->params['named']['s_date']);
			  $day = $arrS_date[0] ;
			  $month = $arrS_date[1] ;
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
		
		
		if((isset($this->data['vouchers']['e_date']) and $this->data['vouchers']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['vouchers']['e_date']) and $this->data['vouchers']['e_date'] != ''))
			{
			
			  $arrE_date = explode("/",$this->data['vouchers']['e_date']);
			  $eday = $arrE_date[0] ;
			  $emonth = $arrE_date[1] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['vouchers']['e_date'] ;
			  $e_date = str_replace("/","-",$e_date);
			  $e_datetmsp  = mktime(0,0,0,$emonth,$eday,$eyear);
			}
			else if( (isset($this->params['named']['e_date'])) and $this->params['named']['e_date'] !=''){
			 
			  $arrE_date = explode("-",$this->params['named']['e_date']);
			  $eday = $arrE_date[0] ;
			  $emonth = $arrE_date[1] ;
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
		 $cond['Voucher.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($category) && $category !=''){
		 $cond['Voucher.category_id'] = $category;
		}
		
		if(isset($county) && $county !=''){
		 $cond['Voucher.advertiser_county_id'] = $county;
		}

		if(isset($search_text) && $search_text !=''){
		 $cond['Voucher.title LIKE'] = '%'.$search_text. '%';
		}
		
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['Voucher.s_date >='] = $s_datetmsp ;
		  $cond['Voucher.e_date <='] = $e_datetmsp ;
		}
		
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){

		
		   $cond['Voucher.s_date ='] = $s_datetmsp ;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['Voucher.e_date ='] = $e_datetmsp ;
		}

		
		$data = $this->paginate('Voucher', $cond);
		$this->set('vouchers', $data);
	
	}
	
/***----------This function is the Index function for all deals--   Surbhit  Date :- 08 June 2011-----------------------------------------*/	
function voucherCommon(){
		$this->set('title_for_layout','Gift Certificate Common');
		$this->set('categoryList',$this->common->getAllCategory());
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		$this->set('cityList',$this->common->getAllCity()); 		//  List cities
		$this->set('stateList',$this->common->getAllState()); 		//  List states
		$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
		$this->set('search_text','Title');
		$this->set('s_date','');
		$this->set('e_date','');
		$this->set('category', 'Category');
		$this->set('county', 'County');
		$this->set('advertiser_profile_id', 'Advertiser');
		$cond = '';
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('Voucher.id' => 'asc'));
		
		
		//pr($this->params);
		
		if((isset($this->data['vouchers']['advertiser_profile_id']) and $this->data['vouchers']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['vouchers']['advertiser_profile_id']) and $this->data['vouchers']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['vouchers']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'] ;
			}else{
			  
			  $advertiser_profile_id ="";
			}
			
			$this->set('advertiser_profile_id',$advertiser_profile_id); 
		}
		
		
		if((isset($this->data['vouchers']['category']) and $this->data['vouchers']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
		
		
			if((isset($this->data['vouchers']['category']) and $this->data['vouchers']['category'] != 0))
			{
			 $category = $this->data['vouchers']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
// if county is set
		if((isset($this->data['vouchers']['county']) and $this->data['vouchers']['county'] != 0)|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
		
		
		
			if((isset($this->data['vouchers']['county']) and $this->data['vouchers']['county'] != 0))
			{
			 $county = $this->data['vouchers']['county'] ;
			}
            else if( (isset($this->params['named']['county'])) and $this->params['named']['county'] !=''){
             $county = $this->params['named']['county'] ;
			}else{
			$county = '';
			}
			$this->set('county',$county); 
		}

		
		if((isset($this->data['vouchers']['search_text']) and ($this->data['vouchers']['search_text'] != '' and $this->data['vouchers']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['vouchers']['search_text']) and ($this->data['vouchers']['search_text'] != '' and $this->data['vouchers']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['vouchers']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
		
		if((isset($this->data['vouchers']['s_date']) and $this->data['vouchers']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['vouchers']['s_date']) and $this->data['vouchers']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['vouchers']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['vouchers']['s_date'] ;
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
		
		
		if((isset($this->data['vouchers']['e_date']) and $this->data['vouchers']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['vouchers']['e_date']) and $this->data['vouchers']['e_date'] != ''))
			{
			
			  $arrE_date = explode("/",$this->data['vouchers']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['vouchers']['e_date'] ;
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
		
		
		if(isset($advertiser_profile_id) && $advertiser_profile_id !=''){
		 $cond['Voucher.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($category) && $category !=''){
		 $cond['Voucher.category_id'] = $category;
		}
		
		if(isset($county) && $county !=''){
		 $cond['Voucher.advertiser_county_id'] = $county;
		}

		if(isset($search_text) && $search_text !=''){
		 $cond['Voucher.title LIKE'] = '%'.$search_text. '%';
		}
		
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['Voucher.s_date >='] = $s_datetmsp ;
		  $cond['Voucher.e_date <='] = $e_datetmsp ;
		}
		
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){
		
		   $cond['Voucher.s_date ='] = $s_datetmsp ;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['Voucher.e_date ='] = $e_datetmsp ;
		}

		$data = $this->paginate('Voucher', $cond);
		$this->set('vouchers', $data);
	
	} 
	
	
/***----------This function is the Index function for all archive deals--  Keshav  Date :- 20 Sep 2011-----------------------------------------*/	
function archivevoucher(){
		$this->set('title_for_layout','Archive Gift Certificate');
		$this->set('categoryList',$this->common->getAllCategory());
		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		$this->set('cityList',$this->common->getAllCity()); 		//  List cities
		$this->set('stateList',$this->common->getAllState()); 		//  List states
		$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
		$this->set('search_text','Title');
		$this->set('s_date','');
		$this->set('e_date','');
		$this->set('category', 'Category');
		$this->set('county', 'County');
		$this->set('advertiser_profile_id', 'Advertiser');
		$cond = '';
		$time = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$cond['Voucher.e_date <'] = $time;
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('Voucher.id' => 'asc'));
		
		
		//pr($this->params);
		
		if((isset($this->data['vouchers']['advertiser_profile_id']) and $this->data['vouchers']['advertiser_profile_id'] != '')|| ( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !='')){
			if((isset($this->data['vouchers']['advertiser_profile_id']) and $this->data['vouchers']['advertiser_profile_id'] != ''))
			{
			 $advertiser_profile_id = $this->data['vouchers']['advertiser_profile_id'] ;
			}
			else if( isset($this->params['named']['advertiser_profile_id']) and $this->params['named']['advertiser_profile_id'] !=''){
			 $advertiser_profile_id = $this->params['named']['advertiser_profile_id'] ;
			}else{
			  
			  $advertiser_profile_id ="";
			}
			
			$this->set('advertiser_profile_id',$advertiser_profile_id); 
		}
		
		
		if((isset($this->data['vouchers']['category']) and $this->data['vouchers']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
		
		
			if((isset($this->data['vouchers']['category']) and $this->data['vouchers']['category'] != 0))
			{
			 $category = $this->data['vouchers']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
// if county is set
		if((isset($this->data['vouchers']['county']) and $this->data['vouchers']['county'] != 0)|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
		
		
		
			if((isset($this->data['vouchers']['county']) and $this->data['vouchers']['county'] != 0))
			{
			 $county = $this->data['vouchers']['county'] ;
			}
            else if( (isset($this->params['named']['county'])) and $this->params['named']['county'] !=''){
             $county = $this->params['named']['county'] ;
			}else{
			$county = '';
			}
			$this->set('county',$county); 
		}

		
		if((isset($this->data['vouchers']['search_text']) and ($this->data['vouchers']['search_text'] != '' and $this->data['vouchers']['search_text'] != 'Title'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title') )){
		
			if((isset($this->data['vouchers']['search_text']) and ($this->data['vouchers']['search_text'] != '' and $this->data['vouchers']['search_text'] != 'Title')))
			{
			 $search_text = $this->data['vouchers']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Title')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text); 
		}
		
		if((isset($this->data['vouchers']['s_date']) and $this->data['vouchers']['s_date'] != 0)|| ( isset($this->params['named']['s_date']) and $this->params['named']['s_date'] !='')){
		
			if((isset($this->data['vouchers']['s_date']) and $this->data['vouchers']['s_date'] != 0))
			{
			  $arrS_date = explode("/",$this->data['vouchers']['s_date']);
			  $day = $arrS_date[1] ;
			  $month = $arrS_date[0] ;
			  $year = $arrS_date[2] ;
			  
			 $s_date = $this->data['vouchers']['s_date'] ;
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
		
		
		if((isset($this->data['vouchers']['e_date']) and $this->data['vouchers']['e_date'] != '')|| ( isset($this->params['named']['e_date']) and $this->params['named']['e_date'] !='')){
		
			if((isset($this->data['vouchers']['e_date']) and $this->data['vouchers']['e_date'] != ''))
			{
			
			  $arrE_date = explode("/",$this->data['vouchers']['e_date']);
			  $eday = $arrE_date[1] ;
			  $emonth = $arrE_date[0] ;
			  $eyear = $arrE_date[2] ;
			  $e_date = $this->data['vouchers']['e_date'] ;
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
		
		
		if(isset($advertiser_profile_id) && $advertiser_profile_id !=''){
		 $cond['Voucher.advertiser_profile_id'] = $advertiser_profile_id;
		}
		
		if(isset($category) && $category !=''){
		 $cond['Voucher.category_id'] = $category;
		}
		
		if(isset($county) && $county !=''){
		 $cond['Voucher.advertiser_county_id'] = $county;
		}

		if(isset($search_text) && $search_text !=''){
		 $cond['Voucher.title LIKE'] = '%'.$search_text. '%';
		}
		
		if(isset($s_date) && $s_date !='' && isset($e_date) && $e_date !=''){
		  $cond['Voucher.s_date >='] = $s_datetmsp ;
		  $cond['Voucher.e_date <='] = $e_datetmsp ;
		}
		
		else if(isset($s_date) && $s_date !='' && (!isset($e_date) || $e_date =='')){
		
		   $cond['Voucher.s_date ='] = $s_datetmsp ;
		
		}else if((!isset($s_date) || $s_date =='') && (isset($e_date) && $e_date !='')){
		 $cond['Voucher.e_date ='] = $e_datetmsp ;
		}

		$data = $this->paginate('Voucher', $cond);
		$this->set('vouchers', $data);
	
	} 
	
		
/***-----------------------This function Add new voucher in database------------------------------------------------------------------------------------------*/
	function addVoucher(){
		if(isset($this->params['pass'][0])){
	   		$this->set('advertiser_id', $this->params['pass'][0]);
		}
		
		if($this->referer()=='/vouchers/addVoucher' || $this->referer()=='/'){ } else {
			$this->Session->write('referer',$this->referer());
		}		
		$this->set('refferer',$this->Session->read('referer'));
		$this->set('title_for_layout','Add Gift Certificate');
		$this->set('categoryList',$this->common->getAllCategory()); //  List categories
	  	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List Advertisers

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
				  $this->Voucher->set($this->data['voucher']);
				
		  	   	  $this->set('advertiser_id', $this->data['voucher']['advertiser_profile_id']);//element use only
				  if($this->Voucher->validates())
				  { 
						
					/*------------------------------------find county of specified advertiser---------------------------------------------*/
						
						$county = $this->Voucher->getCityCountyState($this->data['voucher']['advertiser_profile_id']);
						
						$this->data['Voucher']['advertiser_county_id']	=	$county[0]['advertiser_profiles']['county'];
						
					/*--------------------------------------------------------------------------------------------------------------------*/	
						
						if(!empty($this->data['voucher']['sdate']))
						{
							$s_date		= $this->data['voucher']['sdate'];
							$start_date	= explode('/',$s_date);
							$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
						}
						else
						{
							$start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						}
						
						
						
						if(!empty($this->data['voucher']['edate']))
						{
							$e_date		= $this->data['voucher']['edate'];
							$expiry_date	= explode('/',$e_date);
							$expiry_date = mktime(0,0,0,$expiry_date[0],$expiry_date[1],$expiry_date[2]);
						}
						else
						{
							$expiry_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						}
						/***********--------------------------valid date validation ------------------------------------------------------*/
						
						if($expiry_date < $start_date )
						{
							$this->Session->setFlash('End Date should be greater than or equal to Start Date');  
							return false;						
						}
						
						/*----------------------------------------------------------------------------------------------------------------*/	
							$this->data['Voucher']['s_date']= $start_date;
							$this->data['Voucher']['e_date']= $expiry_date;
						/*---------------------------------------------------------------------------------*/
						$lastid = $this->Voucher->find('first',array('fields'=>array('Voucher.id'),'order'=>array('Voucher.id DESC'),'limit'=>1));
						if(is_array($lastid)) {
							$this->data['Voucher']['id'] = $lastid['Voucher']['id']+1;
						} else {
							$this->data['Voucher']['id'] = 1;
						}
						
						$this->data['Voucher']['title']	=	$this->data['voucher']['title'];
						
						$this->data['Voucher']['description']	=	$this->data['voucher']['description'];
						
						$this->data['Voucher']['price']	=	$this->data['voucher']['price'];
						
						$this->data['Voucher']['advertiser_profile_id']	=	$this->data['voucher']['advertiser_profile_id'];
						
						$this->data['Voucher']['category_id']	=	$this->data['voucher']['category_id'];
												
						$this->data['Voucher']['status']	=	$this->data['voucher']['status'];										
						/*pr($this->data['Voucher']);
						*/
						$this->Voucher->save($this->data);
						
						/*----------------------------------------------------------------------------------------------------*/
						  App::import('model', 'WorkOrder');
						  $this->WorkOrder = new WorkOrder;			  
						  $orderid = $this->common->getOrderId($this->data['Voucher']['advertiser_profile_id']);
	  					  $salsemain_detail = $this->common->getorderdetail($orderid['AdvertiserProfile']['order_id']);
						  $saveWorkArray = array();
						  $saveWorkArray['WorkOrder']['advertiser_order_id']   =  $orderid['AdvertiserProfile']['order_id'];
						  $saveWorkArray['WorkOrder']['read_status']   		   =  0;
						  $saveWorkArray['WorkOrder']['subject']   			   =  'New Workorder for Gift Certificate';
						  $saveWorkArray['WorkOrder']['message']   			   =  'New Workorder for Gift Certificate has been launched for the following advertiser profile.';
						  $saveWorkArray['WorkOrder']['type']   			   =  'Gift Certificate Workorder';
						  $saveWorkArray['WorkOrder']['sent_to']   			   =  $salsemain_detail['AdvertiserOrder']['salesperson'];
						  $saveWorkArray['WorkOrder']['sent_to_group']   	   =  5;
						  $saveWorkArray['WorkOrder']['from_group']   		   =  $this->Session->read('Auth.Admin.user_group_id');
						  $saveWorkArray['WorkOrder']['salseperson_id'] 	   =  0;
						  date_default_timezone_set('US/Eastern');
						  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
						  $saveWorkArray['WorkOrder']['created']   			   =  strtotime(date(DATE_FORMAT.' h:i:s A'));
						  $this->WorkOrder->save($saveWorkArray);
					/*----------------------------------------------------------------------------------------------------------*/						
						$this->Session->setFlash('Gift Certificate Successfully Saved'); 
						if(isset($this->data['voucher']['prvs_link']) && (strpos($this->data['voucher']['prvs_link'],'masterSheet')!=false))
						{
					 		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['voucher']['prvs_link']);		
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
						}
						else
						{
							$this->redirect(array('action' => 'index/'.$this->data['voucher']['advertiser_profile_id']));				  
						}
						
				  }
				  else
				  {
				
						$errors = $this->Voucher->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
	}	

/***----------------------This function Edit Existing daily deal in database------------------------------------------------------------------------------------*/
	function editVoucher($id=null,$advertiser_id=null){
	
		if($id == '' || !isset($id))
		{
			$id = $this->data['voucher']['uid'];
		}
		else
		{
			$id = $id;
		}
		if(isset($this->params['pass'][1]) || $this->data['voucher']['advertiser_profile_id']){
			$ad_id =  (isset($this->params['pass'][1])) ? $this->params['pass'][1] : $this->data['voucher']['advertiser_profile_id'];
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
		$this->set('title_for_layout','Edit Gift Certificate');
	 	$this->set('categoryList',$this->common->getAllCategory()); //  List categories
	 	$this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
	  	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List Advertisers
		//$this->set('refferer',$_SERVER['HTTP_REFERER']);
		if($this->Session->read('referer')!='/vouchers/voucherCommon'){ 
			$this->Session->write('referer',$this->referer());
			
		}		
		$this->set('refferer',$this->Session->read('referer'));

		$this->set('data',$this->Voucher->findbyId($id));
	  	if(isset($this->data))
				{
				  $this->Voucher->set($this->data['voucher']);
				  if($this->Voucher->validates())
				  { 
						if(!empty($this->data['voucher']['sdate']))
						{
							$s_date		= $this->data['voucher']['sdate'];
							$start_date	= explode('/',$s_date);
							$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
						}
						else
						{
							$start_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						}
						
						
						
						if(!empty($this->data['voucher']['edate']))
						{
							$e_date		= $this->data['voucher']['edate'];
							$expiry_date	= explode('/',$e_date);
							$expiry_date = mktime(0,0,0,$expiry_date[0],$expiry_date[1],$expiry_date[2]);
						}
						else
						{
							$expiry_date = mktime(0,0,0,date('m'),date('t',strtotime('today')),date('Y'));
						}

						/***********--------------------------valid date validation ------------------------------------------------------*/
						
						if($expiry_date < $start_date )
						{
							$this->Session->setFlash('End Date should be greater than or equal to Start Date');  
							return false;						
						}
						
						/*----------------------------------------------------------------------------------------------------------------*/					
							$this->data['Voucher']['s_date']= $start_date;
							$this->data['Voucher']['e_date']= $expiry_date;
						
					
						/*----------------------------------------------------------------------------------------------------------------------*/
						
						/*-------------------------------image uploaded function-------------------------------------------------------------*/
					
					/*if($this->data['voucher']['banner_image']['name']!='')
					{
						$type = explode(".",$this->data['voucher']['banner_image']['name']);
						
						if($type[1] =="png" || $type[1] =="jpeg" || $type[1] =="jpg"  || $type[1] =="gif")
						{                          
							$this->data['voucher']['banner_image']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['voucher']['banner_image']['name']);
							@unlink(APP.'webroot/img/voucher/'.$this->data['voucher']['oldfilename']);
							
							$docDestination = APP.'webroot/img/voucher/'.$this->data['voucher']['banner_image']['name']; 
							
							@chmod(APP.'webroot/img/voucher',0777);
							
							move_uploaded_file($this->data['voucher']['banner_image']['tmp_name'], $docDestination) or die($docDestination);
							
							$this->data['Voucher']['banner_image'] = $this->data['voucher']['banner_image']['name'];
							
						}
						else
						{
							$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
						}	
					}
					else
					{
						$this->data['Voucher']['banner_image'] = $this->data['voucher']['oldfilename'];
					}*/
					
					
					/*-------------------------------------------------------------------------------------------------------------------*/
					
					/*------------------------------------find county of specified advertiser---------------------------------------------*/
						
						$county = $this->Voucher->getCityCountyState($this->data['voucher']['advertiser_profile_id']);
						
						$this->data['Voucher']['advertiser_county_id']	=	$county[0]['advertiser_profiles']['county'];
						
					/*--------------------------------------------------------------------------------------------------------------------*/	
					/*----------------------------------------------------------------------------------------------------------------------*/

						/*---------------------------------------------------------------------------------*/	
						$this->data['Voucher']['advertiser_county_id']	=	$this->data['voucher']['advertiser_county_id'];
						
						$this->data['Voucher']['title']	=	$this->data['voucher']['title'];
						
						$this->data['Voucher']['price']	=	$this->data['voucher']['price'];
						
						$this->data['Voucher']['description']	=	$this->data['voucher']['description'];
						
						$this->data['Voucher']['advertiser_profile_id']	=	$this->data['voucher']['advertiser_profile_id'];
						
						$this->data['Voucher']['category_id']	=	$this->data['voucher']['category_id'];
									
						$this->data['Voucher']['status']	=	$this->data['voucher']['status'];
						
						$this->data['Voucher']['id']	=	$this->data['voucher']['uid'];

						if($this->Voucher->save($this->data['Voucher']))
						
						$this->Session->setFlash('Gift Certificate with id : '.$this->data['voucher']['uid'].' Successfully Updated'); 
						
						/*---------------------------------------------------------------------------------------------------------*/						
						if(isset($this->data['voucher']['prvs_link']) && (strpos($this->data['voucher']['prvs_link'],'masterSheet')!=false))
						{
					 		$this->Session->delete('reff');
							$ad_id = explode('/',$this->data['voucher']['prvs_link']);		
							$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
						}
						elseif(strpos($this->data['voucher']['refferer'],'voucherCommon')!=false)
						{
							$this->redirect(array('action' => 'voucherCommon')); 
						}
						else
						{
							$this->redirect(array('action' => 'index/'.$this->data['voucher']['advertiser_profile_id']));		  
						}			  
				  }
				  else
				  {				
						$errors = $this->Voucher->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));
						return false;
				  }	  
			}
	}			
/***-----------------------This function Delete the Daily Deal from database-----------------------------------------------------------------------------------*/
	
	function deleteVoucher($id=null,$advertiser_id=null){
	  
					$banner_image = $this->Voucher->query("SELECT banner_image FROM vouchers WHERE id ='".$id."'"); 
					
					@unlink(APP.'webroot/img/voucher/'.$banner_image[0]['vouchers']['banner_image']);
					
					$this->Voucher->delete($id);
					
					$this->Session->setFlash('The Gift Certificate with id:  '.$id.' has been Deleted Successfully!!');
							
				   if((strpos($this->referer(),'masterSheet')!=false)) {
				   $ad_id = explode('/',$this->referer());			
				   		$this->redirect(FULL_BASE_URL.router::url('/',false).'advertiser_profiles/masterSheet/'.$ad_id[3]);
					}else {
						$this->redirect(array('action'=>'index/'.$advertiser_id));
					}
					//$this->redirect(array('action'=>'voucherCommon/'));

	
	
	}
	
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocomplete($id='',$string='') {

			$this->autoRender = false;
			
			if($id==0)
			$id='';
						
			if($string!=''){
			$arr = '';
			if($string!='' and $id!='')
			{
			$name = $this->Voucher->query("SELECT Voucher.title FROM vouchers AS Voucher WHERE Voucher.title LIKE '$string%' AND Voucher.advertiser_profile_id='$id' ");
			}
			else
			{
			$name = $this->Voucher->query("SELECT Voucher.title FROM vouchers AS Voucher WHERE Voucher.title LIKE '$string%'");
			}
			foreach($name as $name) {
				$arr[] = $name['Voucher']['title'];
			}
			echo json_encode($arr);
			}
	}	
		
/***-----------------------This function Save print report-----------------------------------*/	
	function printreport($id=0) {
		$this->Voucher->id = $id;
		$voucher_data = $this->Voucher->read();	
		$this->loadModel('Printvoucher');
		$arr['Printvoucher']['county_id']= $this->Session->read('county_data.id');
		$arr['Printvoucher']['advertiser_profile_id']= $voucher_data['Voucher']['advertiser_profile_id'];
		$arr['Printvoucher']['front_user_id']= $this->Session->read('Auth.FrontConsumer.id');
		$arr['Printvoucher']['hit']= 1;
		$arr['Printvoucher']['voucher_id']= $id;
		$arr['Printvoucher']['type']= 'voucher';
		$arr['Printvoucher']['date']= mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$this->Printvoucher->save($arr);
	}
/***-----------------------This function Save print report-----------------------------------*/	
	function gift_details($id=NULL) {	
		if(!$id) {
			$this->Session->setFlash('No details are available.');
			$this->redirect($this->referer());
		} else {
			$this->loadModel('Order');
			$this->Order->id = $id;
			$gift_data = $this->Order->read();			
			if(empty($gift_data)) {
				$this->redirect($this->referer());
			}
			$this->set('gift_data',$gift_data);
		}
	}
/***-----------------------This function Set the Css for Particlar Theme Selection----------*/
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
/*------------------------------------------------------------------------------------------------------------------------*/ 		
	function getAdvertiserCats($id=0) {
		$this->autoRender = false;
		echo $this->common->advertiserCatCombo($id,'voucher','category_id');
	}					
/*
    this function is checking username and password in database
	and if true then redirect to home page
	*/

	function beforeFilter() { 	

        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );
			$this->Auth->allow('printreport','gift_details');
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
