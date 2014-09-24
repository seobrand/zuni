<?php

/*
   Coder: Surbhit test123
   Date  : 31 Jul 2010
*/

class AdminsController extends AppController{
 var $name = 'Admins';

 var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');

 var $components = array('Auth','common','Session','Cookie','Ofc');  //component to check authentication . this component file is exists in app/controllers/components

 var $layout = 'admin'; //this is the layout for admin panel
 
	#this function call by default when a controller is called
	function index() {
		if($this->Session->check('Auth.Admin')){
			$this->redirect(array('action' => "home"));
		} else {
			$this->Session->setFlash('You are not authorized to access this location.');
			$this->redirect(array('action' => "login"));
		}
	}
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocompletePage($string='') {
			
			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			 App::import('model','Article'); // importing Article (pages) model
		     $this->Article = new Article();
			$name = $this->Article->query("SELECT Article.title FROM articles AS Article WHERE Article.title LIKE '$string%'");
			foreach($name as $name) {
				$arr[] = $name['Article']['title'];
			}
			echo json_encode($arr);
			}
	}	

/*------------------------------------------------------------------------------------------------------------------------*/ 		 
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
	 
	 /**

     *  The AuthComponent provides the needed functionality
     *  for login, so we can leave this function blank.
     */
   /* function login() {
		//pr($this->Session->read('Auth.redirect'));
	      if($this->Session->check('Auth.Admin')){
		          $this->redirect(array('action' => "home"));
			}else{
			     if($this->data){
				 	$this->Session->setFlash('Login failed. Invalid username or password.');
				 }else{
				 	$this->Session->setFlash('You are not authorized to access this location.');
				 }
			}
    	}*/
		
		function login(){
		
			if(isset($this->data))
			{
				//pr($this->Session->read());
				if ($this->Auth->login()) {
					$this->redirect($this->Auth->redirect());
				}else{
					$this->Session->setFlash('Login failed. Invalid username or password.');
				}	
				
			}elseif($this->Session->check('Auth.Admin')){
				$this->redirect($this->Auth->redirect());
			}	
		}

    /*destroy all current sessions for a perticular SuperAdmins
	  and redirect to login page automatically
	*/

	function logout() {
   		$this->redirect($this->Auth->logout());
    }
	
	#we don't want any action in this function so we are leaving this blank
	function home()
	{
	    //pr($this->Auth->user());
		$this->set('currentAdmin', $this->Auth->user());
		$currentSales = $this->Auth->user();
		$noOfAdvertiseOrder = $this->Admin->query("SELECT count(*) FROM advertiser_profiles");
		$this->set('numberOfOrder', $noOfAdvertiseOrder);
		$noOfUser = $this->Admin->query("SELECT count(*) FROM users");
		$this->set('numberOfUser', $noOfUser); 
		$noOfBanner = $this->Admin->query("SELECT count(*) FROM banners");
		$noOfAdvertiseOrderOfSalesPerson = $this->Admin->query("SELECT count(*) FROM  advertiser_orders where salesperson='".$currentSales['Admin']['id']."'");
		$this->set('numberOfSalesOrder', $noOfAdvertiseOrderOfSalesPerson);
		$inboxMsgOfSalesPerson = $this->Admin->query("SELECT count(*) FROM  work_orders where sent_to='".$currentSales['Admin']['id']."' and read_status=0");
		$this->set('inboxMsgOfSalesPerson', $inboxMsgOfSalesPerson);
		$inboxMsgOfAdminPerson = $this->Admin->query("SELECT count(*) FROM  work_orders where sent_to='0' and read_status=0");
		$this->set('inboxMsgOfAdminPerson', $inboxMsgOfAdminPerson);
		$this->set('numberOfBanner', $noOfBanner);		
		$this->loadModel('SavingOffer');
		$totalSavingOffer = $this->SavingOffer->find('count');
		$this->set('totalSavingOffer',$totalSavingOffer);
		$this->loadModel('VipOffer');
		$totalVipOffer = $this->VipOffer->find('count');
		$this->set('totalVipOffer',$totalVipOffer);		
		$this->loadModel('AdvertiserOrder');
		$totalPending = $this->AdvertiserOrder->find('count',array('conditions'=>array('AdvertiserOrder.order_status'=>'pending')));
		$this->set('totalPending',$totalPending);
		$totalRejected = $this->AdvertiserOrder->find('count',array('conditions'=>array('AdvertiserOrder.order_status'=>'rejected')));
		$this->set('totalRejected',$totalRejected);
		$totalApprove = $this->AdvertiserOrder->find('count',array('conditions'=>array('AdvertiserOrder.order_status'=>'approved')));
		$this->loadModel('ReferredFriend');
		$this->loadModel('ReferredBusiness');
		$this->set('totalApprove',$totalApprove);
		$referredFriend = $this->ReferredFriend->find('count');
		$this->set('referredFriend',$referredFriend);	
		$referredBusiness = $this->ReferredBusiness->find('count');
		$this->set('referredBusiness',$referredBusiness);	
		/*----auto archiving code (3 months before msgs inbox go to archive)---------*/
		
		App::import('model','WorkOrder');
		$this->WorkOrder=new WorkOrder();
		date_default_timezone_set('US/Eastern');
		$cur_time_stamp = strtotime(date(DATE_FORMAT.' h:i:s A'));
	
		//$cur_time_stamp=mktime();
		$three_month_before=$cur_time_stamp-7614000;
		$this->WorkOrder->query("update work_orders SET archive='yes' where created <='".$three_month_before."'");
		
		/*-------------------------------------------------------------------------*/		
		
		App::import('model','Report');
		$this->Report=new Report();
		
		/*---------to set the most view city----------*/
		
		$this->set('mostViewCity',$this->Report->query("Select cities.cityname, sum(reports.no_of_hit) from reports, cities where reports.city=cities.id group by reports.city ORDER BY COUNT(reports.city) DESC LIMIT 0 , 9"));

		/*---------to set the most view county----------*/
		
		$this->set('mostViewCounty',$this->Report->query("Select counties.countyname, sum(reports.no_of_hit) from reports, counties where reports.county=counties.id group by reports.county ORDER BY COUNT(reports.county) DESC LIMIT 0 , 9"));
		
		/*---------to set the most view business----------*/
		
		$this->set('mostViewBusiness',$this->Report->query("Select advertiser_profiles.company_name, reports.company, sum(reports.no_of_hit) from reports, advertiser_profiles where reports.company=advertiser_profiles.id group by reports.company ORDER BY COUNT(reports.company) DESC LIMIT 0 , 9"));


	  App::import('model','AdvertiserProfile');
	  $this->ap=new AdvertiserProfile();
 
     $this->set('newAdvertiser',$this->ap->query("select id,company_name,created from advertiser_profiles ORDER BY id DESC LIMIT 0,10"));
	}

/*--------------this is reporting index page--------------------*/
function reportCommon()  
{
	$this->set('title_for_layout', 'Traffic Reports');
}	
	
////////////----------------------------------------------------------------------------------------------//////////////////////////////	
function bucksAwarded() {


	$this->set('consumerList',$this->common->getConsumerProfileAll()); //  List advertisers
	$this->set('countyList',$this->common->getAllCounty()); //List Counties
	$consumerList=$this->common->getConsumerProfileAll();
	
	if(isset($this->params['data']))
	{
			 App::import('model','Buck');
			$this->Buck=new Buck();			
			
			if($this->params['data']['Admin']['consumer_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_discount=$this->params['data']['Admin']['consumer_id'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					
					
					$this->set('trafficDiscount',$this->Buck->query("SELECT sum(Buck.bucks),Buck.*  FROM bucks as Buck WHERE Buck.front_user_id = $traffic_discount and date BETWEEN '$fdate' and '$edate' group by Buck.date ORDER BY Buck.date ASC "));
	
					$viewDiscount=$this->Buck->query("SELECT sum(Buck.bucks),Buck.*  FROM bucks as Buck WHERE Buck.front_user_id = $traffic_discount and date BETWEEN '$fdate' and '$edate' group by Buck.date ORDER BY COUNT(Buck.id) DESC ");
							
					if(count($viewDiscount)>0)
					{

	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Bucks Awarded of '.$consumerList[$viewDiscount[0]['Buck']['front_user_id']], '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
					{
						$sort[$viewDiscount[$i]['Buck']['date']]=$viewDiscount[$i][0]['sum(Buck.bucks)'];
						$i++;
					}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
				
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$consumerList[$viewDiscount[0]['Buck']['front_user_id']].' Bucks Awarded','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'Bucks', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			
						
					
					
			}
			else if($this->params['data']['Admin']['consumer_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_discount=$this->params['data']['Admin']['consumer_id'];
			
			
					$this->set('trafficDiscount',$this->Buck->query("SELECT sum(Buck.bucks),Buck.*  FROM bucks as Buck WHERE Buck.front_user_id = $traffic_discount group by Buck.date ORDER BY Buck.date ASC "));
	
					$viewDiscount=$this->Buck->query("SELECT sum(Buck.bucks),Buck.*  FROM bucks as Buck WHERE Buck.front_user_id = $traffic_discount group by Buck.date ORDER BY COUNT(Buck.id) DESC ");
			
			if(count($viewDiscount)>0)
					{
	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Bucks Awarded of '.$consumerList[$viewDiscount[0]['Buck']['front_user_id']], '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
						{
							$sort[$viewDiscount[$i]['Buck']['date']]=$viewDiscount[$i][0]['sum(Buck.bucks)'];
						$i++;
						}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$consumerList[$viewDiscount[0]['Buck']['front_user_id']].' Bucks Awarded','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'Bucks', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/	
		
			
			}
			else if($this->params['data']['Admin']['consumer_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "bucksAwarded", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['consumer_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "bucksAwarded", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['consumer_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "bucksAwarded", 'message'=>'advertiser'));
			}
			else if($this->params['data']['Admin']['consumer_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "bucksAwarded", 'message'=>'fadvertiser'));
			}
		 else if($this->params['data']['Admin']['consumer_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "bucksAwarded", 'message'=>'eadvertiser'));
			}
         else if($this->params['data']['Admin']['consumer_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "bucksAwarded", 'message'=>'sadvertiser'));
			}			
			 
    }


}
////////////----------------------------------------------------------------------------------------------//////////////////////////////	
function couponPrinted() {
	$this->set('consumerList',$this->common->getConsumerProfileAll());
	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
	$this->set('countyList',$this->common->getAllCounty()); //List Counties
	$advertiserList=$this->common->getAdvertiserProfileAll();
	
	if(isset($this->params['data']))
	{
			 App::import('model','Printvoucher');
			$this->Printvoucher=new Printvoucher();
			
			
			if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_discount=$this->params['data']['Admin']['advertiser_profile_id'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					
					
					$this->set('trafficDiscount',$this->Printvoucher->query("SELECT sum(Printvoucher.hit),Printvoucher.*  FROM printvouchers as Printvoucher WHERE Printvoucher.type = 'voucher' and Printvoucher.advertiser_profile_id='$traffic_discount' and date BETWEEN '$fdate' and '$edate' group by Printvoucher.front_user_id,Printvoucher.date ORDER BY Printvoucher.date ASC "));
	
					$viewDiscount=$this->Printvoucher->query("SELECT sum(Printvoucher.hit),Printvoucher.*  FROM printvouchers as Printvoucher WHERE Printvoucher.type = 'voucher' and Printvoucher.advertiser_profile_id='$traffic_discount' and date BETWEEN '$fdate' and '$edate' group by Printvoucher.date ORDER BY COUNT(front_user_id) DESC ");
							
					if(count($viewDiscount)>0)
					{

	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Zuni coupons printed/ emailed', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
					{
						$sort[$viewDiscount[$i]['Printvoucher']['date']]=$viewDiscount[$i][0]['sum(Printvoucher.hit)'];
						$i++;
					}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
				
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$advertiserList[$viewDiscount[0]['Printvoucher']['advertiser_profile_id']].' Deal','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'printed/ emailed', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			
						
					
					
			}
			else if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_discount=$this->params['data']['Admin']['advertiser_profile_id'];
			
			
			$this->set('trafficDiscount',$this->Printvoucher->query("SELECT sum(Printvoucher.hit),Printvoucher.*  FROM printvouchers as Printvoucher WHERE Printvoucher.type = 'voucher' and Printvoucher.advertiser_profile_id='$traffic_discount' group by Printvoucher.front_user_id,Printvoucher.date ORDER BY Printvoucher.date ASC "));
	
					$viewDiscount=$this->Printvoucher->query("SELECT sum(Printvoucher.hit),Printvoucher.*  FROM printvouchers as Printvoucher WHERE Printvoucher.type = 'voucher' and Printvoucher.advertiser_profile_id='$traffic_discount' group by Printvoucher.date ORDER BY COUNT(front_user_id) DESC ");
			
			if(count($viewDiscount)>0)
					{
	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Zuni coupons printed/ emailed.', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
						{
							$sort[$viewDiscount[$i]['Printvoucher']['date']]=$viewDiscount[$i][0]['sum(Printvoucher.hit)'];
						$i++;
						}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>'Coupons ','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'printed/ emailed', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/	
		
			
			}
			else if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "couponPrinted", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "couponPrinted", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "couponPrinted", 'message'=>'advertiser'));
			}
			else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "couponPrinted", 'message'=>'fadvertiser'));
			}
		 else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "couponPrinted", 'message'=>'eadvertiser'));
			}
         else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "couponPrinted", 'message'=>'sadvertiser'));
			}			
			 
    }


}
////////////----------------------------------------------------------------------------------------------//////////////////////////////	
function discountPrinted() {
	$this->set('consumerList',$this->common->getConsumerProfileAll());
	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
	$advertiserList=$this->common->getAdvertiserProfileAll();
	
	if(isset($this->params['data']))
	{
			 App::import('model','FrontUser');
			$this->FrontUser=new FrontUser();
			
			
			if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_discount=$this->params['data']['Admin']['advertiser_profile_id'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					
					
					$this->set('trafficDiscount',$this->FrontUser->query("SELECT sum(FrontUser.vouchers),FrontUser.*  FROM front_users as FrontUser WHERE FrontUser.advertiser_id='$traffic_discount' and purchase_date BETWEEN '$fdate' and '$edate' group by FrontUser.id,FrontUser.purchase_date ORDER BY FrontUser.purchase_date ASC "));
	
					$viewDiscount=$this->FrontUser->query("SELECT sum(FrontUser.vouchers),FrontUser.*  FROM front_users as FrontUser WHERE FrontUser.advertiser_id='$traffic_discount' and purchase_date BETWEEN '$fdate' and '$edate' group by FrontUser.purchase_date ORDER BY COUNT(id) DESC ");
							
					if(count($viewDiscount)>0)
					{

	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Daily discount printed/ emailed.', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
					{
						$sort[$viewDiscount[$i]['FrontUser']['purchase_date']]=$viewDiscount[$i][0]['sum(FrontUser.vouchers)'];
						$i++;
					}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
				
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>'Daily discount printed/ emailed','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'printed/ emailed', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			
						
					
					
			}
			else if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_discount=$this->params['data']['Admin']['advertiser_profile_id'];
			
			
			$this->set('trafficDiscount',$this->FrontUser->query("SELECT sum(FrontUser.vouchers),FrontUser.*  FROM front_users as FrontUser WHERE FrontUser.advertiser_id='$traffic_discount' group by FrontUser.id,FrontUser.purchase_date ORDER BY FrontUser.purchase_date ASC "));
	
					$viewDiscount=$this->FrontUser->query("SELECT sum(FrontUser.vouchers),FrontUser.*  FROM front_users as FrontUser WHERE FrontUser.advertiser_id='$traffic_discount' group by FrontUser.purchase_date ORDER BY COUNT(id) DESC ");
			
			if(count($viewDiscount)>0)
					{
	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Daily discount printed/ emailed.', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
						{
							$sort[$viewDiscount[$i]['FrontUser']['purchase_date']]=$viewDiscount[$i][0]['sum(FrontUser.vouchers)'];
						$i++;
						}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>'Daily discount printed/ emailed','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'printed/ emailed', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/	
		
			
			}
			else if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "discountPrinted", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "discountPrinted", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "discountPrinted", 'message'=>'advertiser'));
			}
			else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "discountPrinted", 'message'=>'fadvertiser'));
			}
		 else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "discountPrinted", 'message'=>'eadvertiser'));
			}
         else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "discountPrinted", 'message'=>'sadvertiser'));
			}			
			 
    }

}
////////////----------------------------------------------------------------------------------------------//////////////////////////////	
function bucksRedeemed() {
	$this->set('consumerList',$this->common->getConsumerProfileAll());
	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
	$this->set('voucherList',$this->common->getVoucherAll());
	$this->set('countyList',$this->common->getAllCounty()); //List Counties
	$advertiserList=$this->common->getAdvertiserProfileAll();
	
	if(isset($this->params['data']))
	{
			 App::import('model','Order');
			$this->Order=new Order();
			
			
			if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_discount=$this->params['data']['Admin']['advertiser_profile_id'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					
					
					$this->set('trafficDiscount',$this->Order->query("SELECT sum(orders.bucks),orders.* FROM orders WHERE orders.advertiser_profile_id='$traffic_discount' and orders.order_date BETWEEN '$fdate' and '$edate' group by orders.front_user_id,orders.voucher_id,orders.order_date ORDER BY orders.order_date ASC "));
	
					$viewDiscount=$this->Order->query("SELECT sum(orders.bucks),orders.* FROM orders WHERE orders.advertiser_profile_id='$traffic_discount' and orders.order_date BETWEEN '$fdate' and '$edate' group by orders.order_date ORDER BY COUNT(front_user_id) DESC ");
							
					if(count($viewDiscount)>0)
					{

	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Zuni Bucks Redeemed', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
					{
						$sort[$viewDiscount[$i]['orders']['order_date']]=$viewDiscount[$i][0]['sum(orders.bucks)'];
						$i++;
					}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
				
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$advertiserList[$viewDiscount[0]['orders']['advertiser_profile_id']].' Deal','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'Bucks Redeemed', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			
						
					
					
			}
			else if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_discount=$this->params['data']['Admin']['advertiser_profile_id'];
			
			
			$this->set('trafficDiscount',$this->Order->query("SELECT sum(orders.bucks), orders.* FROM orders WHERE orders.advertiser_profile_id='$traffic_discount' group by orders.front_user_id,orders.voucher_id,orders.order_date ORDER BY orders.order_date ASC "));
	
					$viewDiscount=$this->Order->query("SELECT sum(orders.bucks),orders.* FROM orders WHERE orders.advertiser_profile_id='$traffic_discount' group by orders.order_date ORDER BY COUNT(front_user_id) DESC ");
			
			if(count($viewDiscount)>0)
					{
	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Zuni Bucks Redeemed.', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
						{
							$sort[$viewDiscount[$i]['orders']['order_date']]=$viewDiscount[$i][0]['sum(orders.bucks)'];
						$i++;
						}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>'Bucks Redeemed ','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'Bucks Redeemed', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/	
		
			
			}
			else if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "bucksRedeemed", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['advertiser_profile_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "bucksRedeemed", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "bucksRedeemed", 'message'=>'advertiser'));
			}
			else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "bucksRedeemed", 'message'=>'fadvertiser'));
			}
		 else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "bucksRedeemed", 'message'=>'eadvertiser'));
			}
         else if($this->params['data']['Admin']['advertiser_profile_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "bucksRedeemed", 'message'=>'sadvertiser'));
			}			
			 
    }



}

function reportBanner()
{

	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
	$this->set('countyList',$this->common->getAllCounty()); //List Counties
	
	$advertiserList=$this->common->getAdvertiserProfileAll();
	
	if(isset($this->params['data']))
	{
			 App::import('model','InnerReport');
			$this->InnerReport=new InnerReport();
			
			
			if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_discount=$this->params['data']['Admin']['advertiser_id'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					
					
					$this->set('trafficDiscount',$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'banner' and InnerReport.advertiser_id='$traffic_discount' and date BETWEEN '$fdate' and '$edate' group by InnerReport.date ORDER BY InnerReport.date ASC "));
	
					$viewDiscount=$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'banner' and InnerReport.advertiser_id='$traffic_discount' and date BETWEEN '$fdate' and '$edate' group by InnerReport.date ORDER BY COUNT(advertiser_id) DESC ");
							
					if(count($viewDiscount)>0)
					{

	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Banner of '.$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']], '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
					{
						$sort[$viewDiscount[$i]['InnerReport']['date']]=$viewDiscount[$i][0]['sum(InnerReport.no_of_hit)'];
						$i++;
					}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
				
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']].' Banner','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			
						
					
					
			}
			else if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_discount=$this->params['data']['Admin']['advertiser_id'];
			
			
			$this->set('trafficDiscount',$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'discount' and InnerReport.advertiser_id='$traffic_discount' group by InnerReport.date ORDER BY InnerReport.date ASC "));
	
					$viewDiscount=$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'discount' and InnerReport.advertiser_id='$traffic_discount' group by InnerReport.date ORDER BY COUNT(advertiser_id) DESC ");
			
			if(count($viewDiscount)>0)
					{
	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Banner of '.$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']], '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
						{
							$sort[$viewDiscount[$i]['InnerReport']['date']]=$viewDiscount[$i][0]['sum(InnerReport.no_of_hit)'];
						$i++;
						}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']].' Banner','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/	
		
			
			}
			else if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportBanner", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportBanner", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportBanner", 'message'=>'advertiser'));
			}
			else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportBanner", 'message'=>'fadvertiser'));
			}
		 else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportBanner", 'message'=>'eadvertiser'));
			}
         else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportBanner", 'message'=>'sadvertiser'));
			}			
			 
    }


}
function reportDeal()
{


	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
	$this->set('countyList',$this->common->getAllCounty()); //List Counties
	$advertiserList=$this->common->getAdvertiserProfileAll();
	
	if(isset($this->params['data']))
	{
			 App::import('model','InnerReport');
			$this->InnerReport=new InnerReport();
			
			
			if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_discount=$this->params['data']['Admin']['advertiser_id'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					
					
					$this->set('trafficDiscount',$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'deal' and InnerReport.advertiser_id='$traffic_discount' and date BETWEEN '$fdate' and '$edate' group by InnerReport.date ORDER BY InnerReport.date ASC "));
	
					$viewDiscount=$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'deal' and InnerReport.advertiser_id='$traffic_discount' and date BETWEEN '$fdate' and '$edate' group by InnerReport.date ORDER BY COUNT(advertiser_id) DESC ");
							
					if(count($viewDiscount)>0)
					{

	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Deal of '.$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']], '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
					{
						$sort[$viewDiscount[$i]['InnerReport']['date']]=$viewDiscount[$i][0]['sum(InnerReport.no_of_hit)'];
						$i++;
					}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
				
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']].' Deal','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			
						
					
					
			}
			else if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_discount=$this->params['data']['Admin']['advertiser_id'];
			
			
			$this->set('trafficDiscount',$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'discount' and InnerReport.advertiser_id='$traffic_discount' group by InnerReport.date ORDER BY InnerReport.date ASC "));
	
					$viewDiscount=$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'discount' and InnerReport.advertiser_id='$traffic_discount' group by InnerReport.date ORDER BY COUNT(advertiser_id) DESC ");
			
			if(count($viewDiscount)>0)
					{
	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Deal of '.$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']], '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
						{
							$sort[$viewDiscount[$i]['InnerReport']['date']]=$viewDiscount[$i][0]['sum(InnerReport.no_of_hit)'];
						$i++;
						}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']].' Deal','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/	
		
			
			}
			else if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportDeal", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportDeal", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportDeal", 'message'=>'advertiser'));
			}
			else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportDeal", 'message'=>'fadvertiser'));
			}
		 else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportDeal", 'message'=>'eadvertiser'));
			}
         else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportDeal", 'message'=>'sadvertiser'));
			}			
			 
    }


}	
function reportDiscount()
{

	$this->set('advertiserList',$this->common->getAdvertiserProfileAll()); //  List advertisers
	$this->set('countyList',$this->common->getAllCounty()); //List Counties
	$advertiserList=$this->common->getAdvertiserProfileAll();
	
	if(isset($this->params['data']))
	{
			 App::import('model','InnerReport');
			$this->InnerReport=new InnerReport();
			
			$traffic_discount=$this->params['data']['Admin']['advertiser_id'];
			
			
			if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_discount=$this->params['data']['Admin']['advertiser_id'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					
					
					$this->set('trafficDiscount',$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'discount' and InnerReport.advertiser_id='$traffic_discount' and date BETWEEN '$fdate' and '$edate' group by InnerReport.date ORDER BY InnerReport.date ASC "));
	
					$viewDiscount=$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'discount' and InnerReport.advertiser_id='$traffic_discount' and date BETWEEN '$fdate' and '$edate' group by InnerReport.date ORDER BY COUNT(advertiser_id) DESC ");
							
					if(count($viewDiscount)>0)
					{

	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Discount of '.$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']], '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
					{
						$sort[$viewDiscount[$i]['InnerReport']['date']]=$viewDiscount[$i][0]['sum(InnerReport.no_of_hit)'];
						$i++;
					}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
				
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']].' Discount','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			
						
					
					
			}
			else if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_discount=$this->params['data']['Admin']['advertiser_id'];
			
			
			$this->set('trafficDiscount',$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'discount' and InnerReport.advertiser_id='$traffic_discount' group by InnerReport.date ORDER BY InnerReport.date ASC "));
	
					$viewDiscount=$this->InnerReport->query("SELECT sum(InnerReport.no_of_hit),InnerReport.*  FROM inner_reports InnerReport WHERE InnerReport.type = 'discount' and InnerReport.advertiser_id='$traffic_discount' group by InnerReport.date ORDER BY COUNT(advertiser_id) DESC ");
			
			if(count($viewDiscount)>0)
					{
	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For Discount of '.$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']], '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewDiscount as $city)
						{
							$sort[$viewDiscount[$i]['InnerReport']['date']]=$viewDiscount[$i][0]['sum(InnerReport.no_of_hit)'];
						$i++;
						}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$advertiserList[$viewDiscount[0]['InnerReport']['advertiser_id']].' Discount','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/	
		
			
			}
			else if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportDiscount", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['advertiser_id']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportDiscount", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportDiscount", 'message'=>'advertiser'));
			}
			else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportDiscount", 'message'=>'fadvertiser'));
			}
		 else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportDiscount", 'message'=>'eadvertiser'));
			}
         else if($this->params['data']['Admin']['advertiser_id']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportDiscount", 'message'=>'sadvertiser'));
			}			
			 
    }

}

function reportAll()
{

		$this->set('countyList',$this->common->getAllCounty()); 	//  List counties
		$countyList=$this->common->getAllCounty();
	if(isset($this->params['data']))
	{
			App::import('model','Report');
			$this->report=new Report();
			
			if($this->params['data']['Admin']['county']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_county=$this->params['data']['Admin']['county'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					
					
					$this->set('trafficCounty',$this->report->query("SELECT sum(report.no_of_hit),report.*  FROM counties county, reports report WHERE report.county = county.id and report.county='$traffic_county' and date BETWEEN '$fdate' and '$edate' group by report.date ORDER BY report.date ASC "));
					
					$viewCounty=$this->report->query("SELECT sum(report.no_of_hit),report.*  FROM counties county, reports report WHERE report.county = county.id and report.county='$traffic_county' and date BETWEEN '$fdate' and '$edate' group by report.date ORDER BY COUNT(county) DESC ");
					
		//pr($viewCity);			
					if(count($viewCounty)>0)
					{

	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Overall Site Visit Report For '.$countyList[$viewCounty[0]['report']['county']].' County', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewCounty as $city)
					{
						$sort[$viewCounty[$i]['report']['date']]=$viewCounty[$i][0]['sum(report.no_of_hit)'];
						$i++;
					}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
				
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$countyList[$viewCounty[0]['report']['county']].'&nbsp;County','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			
						
					
					
			}
			else if($this->params['data']['Admin']['county']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_county=$this->params['data']['Admin']['county'];
			
			
			$this->set('trafficCounty',$this->report->query("SELECT sum(report.no_of_hit),report.*  FROM counties county, reports report WHERE report.county = county.id and report.county='$traffic_county' group by report.date ORDER BY report.date ASC "));
			
			$viewCounty=$this->report->query("SELECT sum(report.no_of_hit),report.*  FROM counties county, reports report WHERE report.county = county.id and report.county='$traffic_county' group by report.date ORDER BY COUNT(county) DESC ");
			
			if(count($viewCounty)>0)
					{
	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Overall Site Visit Report For '.$countyList[$viewCounty[0]['report']['county']].' County', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					 foreach($viewCounty as $city)
						{
						$sort[$viewCounty[$i]['report']['date']]=$viewCounty[$i][0]['sum(report.no_of_hit)'];
						$i++;
						}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$countyList[$viewCounty[0]['report']['county']].'&nbsp;County','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/	
		
			
			}
			else if($this->params['data']['Admin']['county']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportAll", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['county']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportAll", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['county']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportAll", 'message'=>'county'));
			}
			else if($this->params['data']['Admin']['county']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportAll", 'message'=>'fcounty'));
			}
		 else if($this->params['data']['Admin']['county']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportAll", 'message'=>'ecounty'));
			}
         else if($this->params['data']['Admin']['county']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportAll", 'message'=>'scounty'));
			}			
			 
    }

}
function reportCity()
{

	$this->set('CitiesList',$this->common->getAllCity());   //  List cities
	$CitiesList=$this->common->getAllCity();
	if(isset($this->params['data']))
	{
			App::import('model','Report');
			$this->report=new Report();
			
			if($this->params['data']['Admin']['city']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_city=$this->params['data']['Admin']['city'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					//$this->redirect(array('action' => "user", 'message'=>'error'));
					
					
					$this->set('trafficCity',$this->report->query("SELECT sum(report.no_of_hit),report.*  FROM cities city, reports report WHERE report.city = city.id and report.city='$traffic_city' and date BETWEEN '$fdate' and '$edate' group by report.date ORDER BY report.date ASC "));
					
					$viewCity=$this->report->query("SELECT sum(report.no_of_hit),report.*  FROM cities city, reports report WHERE report.city = city.id and report.city='$traffic_city' and date BETWEEN '$fdate' and '$edate' group by report.date ORDER BY COUNT(city) DESC ");
					
		//pr($viewCity);			
					if(count($viewCity)>0)
					{

	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For '.$CitiesList[$viewCity[0]['report']['city']].' City', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewCity as $city)
					{
						$sort[$viewCity[$i]['report']['date']]=$viewCity[$i][0]['sum(report.no_of_hit)'];
						$i++;
					}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
				
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$CitiesList[$viewCity[0]['report']['city']].'&nbsp;City','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			
						
					
					
			}
			else if($this->params['data']['Admin']['city']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_city=$this->params['data']['Admin']['city'];
			
			
			$this->set('trafficCity',$this->report->query("SELECT sum(report.no_of_hit),report.*  FROM cities city, reports report WHERE report.city = city.id and report.city='$traffic_city' group by report.date ORDER BY report.date ASC "));
			
			$viewCity=$this->report->query("SELECT sum(report.no_of_hit),report.*  FROM cities city, reports report WHERE report.city = city.id and report.city='$traffic_city' group by report.date ORDER BY COUNT(city) DESC ");
			
			if(count($viewCity)>0)
					{
	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For '.$CitiesList[$viewCity[0]['report']['city']].' City', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					 foreach($viewCity as $city)
						{
						$sort[$viewCity[$i]['report']['date']]=$viewCity[$i][0]['sum(report.no_of_hit)'];
						$i++;
						}
						ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$CitiesList[$viewCity[0]['report']['city']].'&nbsp;City','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/	
		
			
			}
			else if($this->params['data']['Admin']['city']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportCity", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['city']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportCity", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['city']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportCity", 'message'=>'city'));
			}
			else if($this->params['data']['Admin']['city']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportCity", 'message'=>'fcity'));
			}
		 else if($this->params['data']['Admin']['city']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportCity", 'message'=>'ecity'));
			}
         else if($this->params['data']['Admin']['city']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportCity", 'message'=>'scity'));
			}			
			 
    }
}

function reportCounty()
{

	$this->set('CountiesList',$this->common->getAllCounty());   //  List cities
	$CountiesList=$this->common->getAllCounty();
	if(isset($this->params['data']))
	{
			App::import('model','Report');
			$this->report=new Report();
			
			if($this->params['data']['Admin']['county']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_county=$this->params['data']['Admin']['county'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					//$this->redirect(array('action' => "user", 'message'=>'error'));
							
					$this->set('trafficCounty',$this->report->query("SELECT sum(report.no_of_hit),report.* FROM counties AS cty, reports report WHERE report.county = cty.id and report.county='$traffic_county' and date BETWEEN '$fdate' and '$edate' group by report.date
ORDER BY report.date ASC "));

           $viewCounty=$this->report->query("SELECT  sum(report.no_of_hit),report.* FROM counties AS cty, reports report WHERE report.county = cty.id and report.county='$traffic_county' and date BETWEEN '$fdate' and '$edate' group by report.date
ORDER BY COUNT(county) DESC ");


           if(count($viewCounty)>0)
					{
/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For '.$CountiesList[$viewCounty[0]['report']['county']].' County', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewCounty as $county)
					{
					$sort[$viewCounty[$i]['report']['date']]=$viewCounty[$i][0]['sum(report.no_of_hit)'];
					$i++;
					}
					ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
							$date[$i]=date(DATE_FORMAT,$key);
							$data[$i]=$value;
							$i++;
						}
					//pr($date);die;
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$CountiesList[$viewCounty[0]['report']['county']].'&nbsp;County','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
	               
				   }				
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			


					
					
					
					
			}
			else if($this->params['data']['Admin']['county']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_county=$this->params['data']['Admin']['county'];
			$this->set('trafficCounty',$this->report->query("SELECT sum(report.no_of_hit),report.* FROM counties AS cty, reports report WHERE report.county = cty.id and report.county='$traffic_county' group by report.date
ORDER BY report.date ASC "));


           $viewCounty=$this->report->query("SELECT  sum(report.no_of_hit),report.* FROM counties AS cty, reports report WHERE report.county = cty.id and report.county='$traffic_county' group by report.date
ORDER BY COUNT(county) DESC ");
         
		 if(count($viewCounty)>0)
					{

	/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title('Report For '.$CountiesList[$viewCounty[0]['report']['county']].' County', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewCounty as $county)
					{
					$sort[$viewCounty[$i]['report']['date']]=$viewCounty[$i][0]['sum(report.no_of_hit)'];
					$i++;
					}
					ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					//pr($date);die;
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$CountiesList[$viewCounty[0]['report']['county']].'&nbsp;County','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
  /*------------------------------------------------------------End----------------------------------------------------------------------------------------*/	
		
			
			}
			else if($this->params['data']['Admin']['county']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportCounty", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['county']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportCounty", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['county']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportCounty", 'message'=>'county'));
			}
			else if($this->params['data']['Admin']['county']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportCounty", 'message'=>'fcounty'));
			}
		 else if($this->params['data']['Admin']['county']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportCounty", 'message'=>'ecounty'));
			}
         else if($this->params['data']['Admin']['county']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportCounty", 'message'=>'scounty'));
			}			
			 
    }
}
function reportState()
{


	$this->set('StatesList',$this->common->getAllState());   //  List cities
	$StatesList=$this->common->getAllState();
	if(isset($this->params['data']))
	{
			App::import('model','Report');
			$this->report=new Report();
			
			if($this->params['data']['Admin']['state']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
					$traffic_state=$this->params['data']['Admin']['state'];
					$from_date=explode('/',$this->params['data']['Admin']['fdate']);
					$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
					$end_date=explode('/',$this->params['data']['Admin']['edate']);
					$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
												
					$this->set('trafficState',$this->report->query("SELECT sum(report.no_of_hit),report.* FROM states AS st, reports report WHERE report.state = st.id and report.state='$traffic_state' and date BETWEEN '$fdate' and '$edate' group by report.date
ORDER BY report.date ASC "));




               $viewState=$this->report->query("SELECT sum(report.no_of_hit),report.* FROM states AS st, reports report WHERE report.state = st.id and report.state='$traffic_state' and date BETWEEN '$fdate' and '$edate' group by report.date
ORDER BY COUNT(state) DESC ");


/*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					
				if(count($viewState)>0)
					{
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For '.$StatesList[$viewState[0]['report']['state']].' State', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewState as $state)
					{
					$sort[$viewState[$i]['report']['date']]=$viewState[$i][0]['sum(report.no_of_hit)'];
					$i++;
					}
					
					ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					//pr($date);die;
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$StatesList[$viewState[0]['report']['state']].'&nbsp;State','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
				}
					
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			

					
					
			}
			else if($this->params['data']['Admin']['state']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$traffic_state=$this->params['data']['Admin']['state'];
			$this->set('trafficState',$this->report->query("SELECT sum(report.no_of_hit),report.* FROM states AS st, reports report WHERE report.state = st.id and report.state='$traffic_state' group by report.date ORDER BY report.date ASC "));

           $viewState=$this->report->query("SELECT sum(report.no_of_hit),report.* FROM states AS st, reports report WHERE report.state = st.id and report.state='$traffic_state' group by report.date ORDER BY COUNT(state) DESC ");
   /*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					if(count($viewState)>0)
					{
					
					 $this->Ofc->set_ofc_webroot($this->webroot);
					 $this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For '.$StatesList[$viewState[0]['report']['state']].' State', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewState as $state)
					{
					$sort[$viewState[$i]['report']['date']]=$viewState[$i][0]['sum(report.no_of_hit)'];
					$i++;
					}
					ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					//pr($date);die;
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$StatesList[$viewState[0]['report']['state']].'&nbsp;State','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			

			
			}
			else if($this->params['data']['Admin']['state']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportState", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['state']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportState", 'message'=>'fdate'));
			}
		 else if($this->params['data']['Admin']['state']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportState", 'message'=>'state'));
			}
			else if($this->params['data']['Admin']['state']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportState", 'message'=>'fstate'));
			}
		 else if($this->params['data']['Admin']['state']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportState", 'message'=>'estate'));
			}
         else if($this->params['data']['Admin']['state']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportState", 'message'=>'sstate'));
			}			
			 
    }
}

function reportCategory()
{
	$this->set('getCategory',$this->common->getCatList());   //  List cities
	$this->set('subcategoryList',$this->common->getAllSubCategory()); //List Subcat
	$subcategoryList=$this->common->getAllSubCategory();
	if(isset($this->params['form']['business']))
	{
	        App::import('model','Report');
			$this->report=new Report();
			
			App::import('model','Subcategory');
			$this->scat=new Subcategory();
			
			if($this->params['form']['business']!=0 && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			
				$catSubcat=explode('-',$this->params['form']['business']);
				//pr($catSubcat);die;
				$cat_id=$catSubcat[0];
				$scat_id=$catSubcat[1];
				
				$scat_name=$this->scat->query("select categoryname from subcategories where id='$scat_id'");
				$categoryname=$scat_name[0]['subcategories']['categoryname'];
				$this->set('getCategory',$this->common->getCatList($categoryname));
				
				$from_date=explode('/',$this->params['data']['Admin']['fdate']);
				$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
				$end_date=explode('/',$this->params['data']['Admin']['edate']);
				$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);

				$this->set('trafficCategory',$this->report->query("SELECT sum(report.no_of_hit),report.* FROM subcategories scat, reports report WHERE report.subcategory = scat.id and report.subcategory='$scat_id' and date BETWEEN '$fdate' and '$edate' group by report.date
 ORDER BY report.date ASC "));
 
                $viewCategory=$this->report->query("SELECT  sum(report.no_of_hit),report.*  FROM subcategories scat, reports report WHERE report.subcategory = scat.id and report.subcategory='$scat_id' and date BETWEEN '$fdate' and '$edate' group by report.date
ORDER BY COUNT(subcategory) DESC ");

        /*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					if(count($viewCategory)>0)
					{
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For '.$subcategoryList[$viewCategory[0]['report']['subcategory']].' Category', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewCategory as $category)
					{
					$sort[$viewCategory[$i]['report']['date']]=$viewCategory[$i][0]['sum(report.no_of_hit)'];
					$i++;
					}
					ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					//pr($date);die;
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$subcategoryList[$viewCategory[0]['report']['subcategory']].'&nbsp;Category','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					
				   }
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			







			}
			else if($this->params['form']['business']!=0 && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$catSubcat=explode('-',$this->params['form']['business']);
			$cat_id=$catSubcat[0];
			$scat_id=$catSubcat[1];
			$scat_name=$this->scat->query("select categoryname from subcategories where id='$scat_id'");
			$categoryname=$scat_name[0]['subcategories']['categoryname'];
			$this->set('getCategory',$this->common->getCatList($categoryname));
				
			$this->set('trafficCategory',$this->report->query("SELECT sum(report.no_of_hit),report.* FROM subcategories scat, reports report WHERE report.subcategory = scat.id and report.subcategory='$scat_id' group by report.date ORDER BY report.date ASC "));
             
			$viewCategory=$this->report->query("SELECT  sum(report.no_of_hit),report.*  FROM subcategories scat, reports report WHERE report.subcategory = scat.id and report.subcategory='$scat_id' group by report.date ORDER BY COUNT(subcategory) DESC ");

     /*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					if(count($viewCategory)>0)
					{
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For '.$subcategoryList[$viewCategory[0]['report']['subcategory']].' Category', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewCategory as $category)
					{
					$sort[$viewCategory[$i]['report']['date']]=$viewCategory[$i][0]['sum(report.no_of_hit)'];
					$i++;
					}
					ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					//pr($date);die;
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$subcategoryList[$viewCategory[0]['report']['subcategory']].'&nbsp;Category','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					}
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			

			}
			
			else if($this->params['form']['business']!=0 && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportCategory", 'message'=>'edate'));
			}
		    else if($this->params['form']['business']!=0 && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportCategory", 'message'=>'fdate'));
			}
		    else if($this->params['form']['business']==0 && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportCategory", 'message'=>'category'));
			}
			else if($this->params['form']['business']==0 && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportCategory", 'message'=>'fcategory'));
			}
		   else if($this->params['form']['business']==0 && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportCategory", 'message'=>'ecategory'));
			}
           else if($this->params['form']['business']==0 && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportCategory", 'message'=>'scategory'));
			}
	}
	
}

function reportBusiness()
{
    
	$this->set('getBusiness',$this->common->getBusList());   //  List businesses
	
	$businessList=$this->common->getBusList();
	
	if(isset($this->params['data']['Admin']))
	{
	        App::import('model','Report');
			$this->report=new Report();
			
			
			
			if($this->params['data']['Admin']['business']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
				$company_id=$this->params['data']['Admin']['business'];			
				$from_date=explode('/',$this->params['data']['Admin']['fdate']);
				$fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]);
					
				$end_date=explode('/',$this->params['data']['Admin']['edate']);
				$edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
             
				
				$this->set('trafficBusiness',$this->report->query("SELECT sum(report.no_of_hit),report.company,report.date FROM advertiser_profiles ad, reports report WHERE report.company = ad.id  and report.company='$company_id'  and date BETWEEN '$fdate' and '$edate' group by report.date
ORDER BY report.date ASC "));
 
 
                $viewBusiness=$this->report->query("SELECT sum(report.no_of_hit),report.company,report.date FROM advertiser_profiles ad, reports report WHERE report.company = ad.id and report.company='$company_id' and date BETWEEN '$fdate' and '$edate' group by report.date
ORDER BY COUNT(report.company) DESC ");




        /*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					if(count($viewBusiness)>0)
					{
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For '.$businessList[$viewBusiness[0]['report']['company']].' Business', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewBusiness as $business)
					{
					$sort[$viewBusiness[$i]['report']['date']]=$viewBusiness[$i][0]['sum(report.no_of_hit)'];
					$i++;
					}
					ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					//pr($date);die;
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$businessList[$viewBusiness[0]['report']['company']].'&nbsp;Business','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					
				   }
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			







			}
			else if($this->params['data']['Admin']['business']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
                $company_id=$this->params['data']['Admin']['business']; 
							
				$this->set('trafficBusiness',$this->report->query("SELECT sum(report.no_of_hit),report.company,report.date FROM advertiser_profiles ad, reports report WHERE report.company = ad.id  and report.company='$company_id' group by report.date ORDER BY report.date ASC "));
 
                $viewBusiness=$this->report->query("SELECT sum(report.no_of_hit),report.company,report.date FROM advertiser_profiles ad, reports report WHERE report.company = ad.id and report.company='$company_id' group by report.date ORDER BY COUNT(report.company) DESC ");
        /*----------------------------------------Display Graph-----------------------------------------------------------------------------------------------------*/
					if(count($viewBusiness)>0)
					{
					
					$this->Ofc->set_ofc_webroot($this->webroot);
					$this->Ofc->set_ofc_size(715,300);
					
					srand((double)microtime()*1000000);
					
					$this->Ofc->set_ofc_title( 'Report For '.$businessList[$viewBusiness[0]['report']['company']].' Business', '{font-size: 20px; color: #736AFF}' );
					$i=0;
					foreach($viewBusiness as $business)
					{
					$sort[$viewBusiness[$i]['report']['date']]=$viewBusiness[$i][0]['sum(report.no_of_hit)'];
					$i++;
					}
					ksort($sort);
						$i=0;
						foreach($sort as $key=>$value)
						{
						$date[$i]=date(DATE_FORMAT,$key);
						$data[$i]=$value;
						$i++;
						}
					//pr($date);die;
					
					$this->Ofc->set_ofc_x_info($date, array('size'=>10,'color'=>'0x000000','orientation'=>2,'step'=>1));
					$this->Ofc->set_ofc_y_info(300,5,array('text'=>$businessList[$viewBusiness[0]['report']['company']].'&nbsp;Business','size'=>12,'color'=>'#736AFF'));
					
					
					
					//line_dot chart
					$this->Ofc->init();
					$this->Ofc->setup();
					$this->Ofc->set_ofc_data( $data );
					$this->Ofc->line_dot( 3, 5, '0xCC3399', 'View', 10);
					
									 
										
					$this->set('graph',$this->Ofc->ofc_render());   
					
				   }
		/*------------------------------------------------------------End----------------------------------------------------------------------------------------*/			
			}
			
			else if($this->params['data']['Admin']['business']!='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportBusiness", 'message'=>'edate'));
			}
		    else if($this->params['data']['Admin']['business']!='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportBusiness", 'message'=>'fdate'));
			}
		    else if($this->params['data']['Admin']['business']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportBusiness", 'message'=>'business'));
			}
			else if($this->params['data']['Admin']['business']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']!='')
			{
			$this->redirect(array('action' => "reportBusiness", 'message'=>'fbusiness'));
			}
		   else if($this->params['data']['Admin']['business']=='' && $this->params['data']['Admin']['fdate']!='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportBusiness", 'message'=>'ebusiness'));
			}
           else if($this->params['data']['Admin']['business']=='' && $this->params['data']['Admin']['fdate']=='' && $this->params['data']['Admin']['edate']=='')
			{
			$this->redirect(array('action' => "reportBusiness", 'message'=>'sbusiness'));
			}
	}
	
}

function reportCommission()
{
     $this->loadModel('User');
	 $this->set('salesperson', $this->User->returnUsersSales());
	 if(isset($this->data))
	 {
	 //pr($this->data);die;
	 $salesperson=$this->data['Admin']['salesperson'];
	 $from_date=explode('/',$this->data['Admin']['fdate']);
	 $fdate=mktime(0,0,0,$from_date[0],$from_date[1],$from_date[2]); 
	 
	 $end_date=explode('/',$this->data['Admin']['edate']);
	 $edate=mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
	 
	$this->set('commission_details',$this->User->query("select advertiser_profiles.name,packages.name,packages.price,users.commission,advertiser_orders.created from advertiser_orders,users,packages,advertiser_profiles where advertiser_orders.salesperson=$salesperson and packages.id=advertiser_orders.package_id and users.id=$salesperson and advertiser_profiles.id=advertiser_orders.advertiser_profile_id and advertiser_profiles.publish='yes' and advertiser_orders.payment_status='Confirmed' and advertiser_orders.created BETWEEN $fdate and $edate "));
	
	 } 
 
}
	
	
	function page()
	{
			 $condition='';
			 App::import('model','Article'); // importing Article (pages) model
		     $this->Article = new Article();
			 #declare variables in cakephp.$this->set is used to declare variables in cakephp 
			 $this->set('title', 'page name'); 
			 $this->set('published', ''); 
			 
			 $this->paginate = array(
				'limit' => PER_PAGE_RECORD,

				'order' => array('Article.id' => 'DESC')

			 );
			 
			 #setting diff condition in paginate function according to search criteria
			 if((!empty($this->data['admins']['title']) && $this->data['admins']['title']!='page name') && $this->data['admins']['published'] == "")
			 {
				 $this->set('title', $this->data['admins']['title']); 
				 $condition =   array('Article.title LIKE' => '%' . $this->data['admins']['title'] . '%');   
						  
			 }
			 if(($this->data['admins']['title'] == "" || $this->data['admins']['title']=='page name') && $this->data['admins']['published'] != "")
			 {
				 $this->set('published', $this->data['admins']['published']); 
				 $condition =   array('Article.published'  => $this->data['admins']['published'] );   
						  
			 }
			 if((!empty($this->data['admins']['title'] ) && $this->data['admins']['title']!='page name') && $this->data['admins']['published'] != "")
			 {
				 $this->set('title', $this->data['admins']['title']); 
				 $this->set('published', $this->data['admins']['published']); 
				 $condition = 	array (	'AND' => array ('Article.title LIKE' => '%' . $this->data['admins']['title'] . '%', 'Article.published' =>$this->data['admins']['published'] ));  
						  
			 } 
			
			 //----------------------------------At the time of sorting Filteration on basis of these fields------------------------------
			 if(!empty($this->params['named'])){
			 
					 if((isset($this->params['named']['title'] ) && $this->params['named']['title']!='page name') && !isset($this->params['named']['published']))
					 {
						 $this->set('title', $this->params['named']['title']); 
						 $condition =   array('Article.title LIKE' => '%' . $this->params['named']['title'] . '%');   
								  
					 }
					 if((!isset($this->params['named']['title'])|| $this->params['named']['title']=='page name') && isset($this->params['named']['published']))
					 {
						 $this->set('published', $this->params['named']['published']); 
						 $condition =   array('Article.published ' => $this->params['named']['published']);   
								  
					 } 
					 if((isset($this->params['named']['title'] ) && $this->params['named']['title']!='page name') && isset($this->params['named']['published']))
					 {
						 $this->set('title', $this->params['named']['title']); 
						 $this->set('published', $this->params['named']['published']);
						 $condition = 	array (	'AND' => array ('Article.title LIKE' => '%' . $this->params['named']['title'] . '%', 'Article.published' =>$this->params['named']['published'] ));    
								  
					 }  
			}
			 
			 $data = $this->paginate('Article', $condition);
		     $this->set('Articles', $data); 
	}
	

	
	/*------------------------------function to Add New Page------------------------------------*/
	function addNewPage()
	{
	 
			 App::import('model','Article'); // importing Article model
			 $this->Article = new Article(); 
			
			if($this->data){

				$this->Article->set($this->data['admins']);
				if($this->data['admins']!=''){

					if ($this->Article->validates()) {
									//making data array so we can pass in save mathod
									$saveArray = array();
									$saveArray['Article']['title'] = $this->data['admins']['title'];
									$saveArray['Article']['description'] = $this->data['admins']['description'];
									$saveArray['Article']['published'] = $this->data['admins']['published'];
									$saveArray['Article']['meta_keyword'] = $this->data['admins']['meta_keyword'];
									$saveArray['Article']['meta_description'] = $this->data['admins']['meta_description'];
									
									if(trim($this->data['admins']['page_url'])!="")
									{
									  	$saveArray['Article']['page_url'] =  $this->common->makeAlias(trim($this->data['admins']['page_url']));	
									}else{
									  	$saveArray['Article']['page_url'] =  $this->common->makeAlias(trim($saveArray['Article']['title']));	
									}
									
									if(trim($this->data['admins']['meta_title'])!="")
									{
									 	$saveArray['Article']['meta_title'] = $this->data['admins']['meta_title'];	
									}else{
									 	$saveArray['Article']['meta_title'] = $saveArray['Article']['title'];	
									}
																
									$this->Article->save($saveArray);
									$this->Session->setFlash('Your data has been submitted successfully.');  
									$this->redirect(array('action' => "page"));

						}else{  

									/*setting error message if validation fails*/
									$errors = $this->Article->invalidFields();	
									$this->Session->setFlash(implode('<br>', $errors));  
						}
				 }
		    }
	}
	
	
	/*------------------------------Function to Delete Page------------------------------------*/
		function pageDelete($id) {
			App::import('model','Article'); // importing article model
			$this->Article = new Article(); 
			$this->Article->id = $id;
			$this->Article->delete($id);
			$this->Session->setFlash('The Page with id: '.$id.' has been deleted.');
			$this->redirect(array('action'=>'page'));
		}
		
	/*------------------------------Function to Edit Particular Page------------------------------------*/	

	function pageEdit($id=null){

			App::import('model','Article'); // importing Ppc model
			$this->Article = new Article(); 
			$this->Article->id = $id;

			$this->Article->set($this->data['admins']);	

			if ($this->Article->validates()) {

							//making data array so we can pass in save mathod
							$saveArray = array();
							$saveArray['Article']['title'] = $this->data['admins']['title'];
							$saveArray['Article']['description'] = $this->data['admins']['description'];
							$saveArray['Article']['published'] = $this->data['admins']['published'];
							$saveArray['Article']['meta_keyword'] = $this->data['admins']['meta_keyword'];
							$saveArray['Article']['meta_description'] = $this->data['admins']['meta_description'];
							
							if(trim($this->data['admins']['page_url'])!="")
							{
							  $saveArray['Article']['page_url'] =  $this->common->makeAlias(trim($this->data['admins']['page_url']));	
							}else{
							  $saveArray['Article']['page_url'] =  $this->common->makeAlias(trim($saveArray['Article']['title']));	
							}
							
							if(trim($this->data['admins']['meta_title'])!="")
							{
							  $saveArray['Article']['meta_title'] = $this->data['admins']['meta_title'];	
							}else{
							  $saveArray['Article']['meta_title'] = $saveArray['Article']['title'];	
							}
														
							$this->Article->save($saveArray);
							$this->Session->setFlash('Your data has been updated successfully.');  
							$this->redirect(array('action' => "page"));

			} else{  

							/*setting error message if validation fails*/
							$errors = $this->Article->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "pageEditDetail/".$this->data['admins']['id'])); 
			}

		}

   	/*fetching page data from article table to show on page edit form*/	
	function pageEditDetail($id=null){
		App::import('model', 'Article');
		$this->Article = new Article;
		$this->set('Article',$this->Article->pageEditDetail($id));
	}
	
	/*
	this function is checking username and pasword in database
	and if true then redirect to home page
	*/

	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username',
            'password' => 'password'
            );
		$this->Auth->userScope 	   = array('Admin.active' => 'yes');
		$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
		//$this->Auth->autoRedirect = false;
   	}

	/* This function is setting all info about current SuperAdmins in 
	currentAdmin array so we can use it anywhere lie name id etc.
	*/

	function beforeRender(){
        
	    $this->set('currentAdmin', $this->Auth->user());
		$this->set('cssName',$this->Cookie->read('css_name'));
		$this->set('groupDetail',$this->common->adminDetails());
		$this->set('common',$this->common);
		//$this->Ssl->force();
	} 


}//end class
?>
