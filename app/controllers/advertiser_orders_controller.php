<?php
/*
   Coder: Abhimanyu (the chakravyuh Todak) test git
   Date  : 18 Aug 2010
*/
class AdvertiserOrdersController extends AppController {
	  var $name = 'AdvertiserOrders';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax');
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('Auth','common','Cookie','Email','RequestHandler','emailhtml','mobile','Session');
	  //component to check authentication . this component file is exists in app/controllers/components
	  
      /*------   destroy all current sessions for a perticular SuperAdmins and redirect to login page automatically --------------*/
	 
	  function logout() {
	  	$this->redirect($this->Auth->logout());
	 
      }
     //---------------------- index page of AdvertiserOrder for listing (new)--------------------------//
	   function index() {
             //variable for display number of AdvertiserOrder name per page
	            $condition='';
				$loginDetail = $this->Auth->user();
				$this->set('currentAdmin', $this->Auth->user());
				$this->set('commissionPercent', '');
				$this->set('totalCommission', '');
				$this->set('paymentStatusSearch', '');
			    $this->set('paymentMethodSearch', '');
			    $this->set('packageSearch', '');
				$this->set('advertiser_search', '');
			    $this->set('s_date', '');
				$this->set('e_date', '');
				
			   $this->set('StatesList',$this->common->getAllState());  //  List states
			   $this->set('CitiesList',$this->common->getAllCity());   //  List cities
			   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
			   $this->set('CountriesList',$this->common->getAllCountry()); //  List countries
			   $this->set('categoryList',$this->common->getAllCategory()); //  List categories
			   $this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
			   $this->set('common',$this->common);
			   $this->set('SelsePersons',$this->common->getAllSelsePerson(5));
			   $this->set('UserGroup',$this->common->getAllUserGroup());
				$this->set('company_name','Company Name');
			   $this->set('city','');
			   $this->set('state','');
			   $this->set('county','');
			   $this->set('category','');
			   $this->set('package_id','');
			   $this->set('salse_id','');
			   $this->set('group_id','');
			   $this->set('publish','');
				$cond = array();
				$this->set('Packages', $this->common->getAllPackage(1));
				$this->set('PackagesName', $this->common->getAllPackage(2));
				$this->set('PackagesPrice', $this->common->getAllPackage(3));
				$this->set('AdvertiserProfiles', $this->common->getAllAdvertiserProfileForOrderListing());
				
				$this->loadModel('User');
				$this->set('salesperson', $this->User->returnUsersSales());
				
				$this->loadModel('OrderInstance');
				$this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'OrderInstance.id' => 'desc' ));
			/* if($loginDetail['Admin']['user_group_id']==1 or  $loginDetail['Admin']['user_group_id']==4)
			  {
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'OrderInstance.id' => 'desc' ));
			  }else{
			  	$this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'OrderInstance.id' => 'desc'),'conditions'=>array('AdvertiserOrder.salesperson'=>$loginDetail['Admin']['id']));
			  }*/
			  if(isset($this->data['AdvertiserOrder']['company_name']) && $this->data['AdvertiserOrder']['company_name']=='Company Name') {
			  		$this->data['AdvertiserOrder']['company_name'] = '';
			  }
			  
			  if($this->data['AdvertiserOrder']['company_name']) {
			    $cond[] = 'AdvertiserProfile.company_name LIKE "%' .$this->data['AdvertiserOrder']['company_name']. '%"';
			    (empty($this->params['named'])) ? $this->set('company_name', $this->data['AdvertiserOrder']['company_name']) :$this->set('company_name', $this->data['named']['company_name']) ;
			  }				
				
			if($this->data['AdvertiserOrder']['county']) {
			   $cond[] = 'AdvertiserProfile.county = '.$this->data['AdvertiserOrder']['county'];
			  (empty($this->params['named'])) ? $this->set('county', $this->data['AdvertiserOrder']['county']) :$this->set('county', $this->data['named']['county']) ;
			}			
			
			if($this->data['AdvertiserOrder']['city']) {
			   $cond[] = 'AdvertiserProfile.city = '.$this->data['AdvertiserOrder']['city'];
			  (empty($this->params['named'])) ? $this->set('city', $this->data['AdvertiserOrder']['city']) :$this->set('city', $this->data['named']['city']) ;
			}
					
			if($this->data['AdvertiserOrder']['state']) {
			   $cond[] = 'AdvertiserProfile.state = '.$this->data['AdvertiserOrder']['state'];
			  (empty($this->params['named'])) ? $this->set('state', $this->data['AdvertiserOrder']['state']) :$this->set('state', $this->data['named']['state']) ;
			}
			
			if($this->data['AdvertiserOrder']['category']) {
			   $cond[] = 'AdvertiserProfile.subcategory LIKE "%,' .$this->data['AdvertiserOrder']['category'] . ',%"';
			  (empty($this->params['named'])) ? $this->set('category', $this->data['AdvertiserOrder']['category']) :$this->set('category', $this->data['named']['category']) ;
			}			
			
			if($this->data['AdvertiserOrder']['publish']) {
				  $cond[] = 'AdvertiserProfile.publish = "'.$this->data['AdvertiserOrder']['publish'].'"';	
				  (empty($this->params['named'])) ? $this->set('publish', $this->data['AdvertiserOrder']['publish']) :$this->set('publish', $this->data['named']['publish']) ;
			}
		
		 if($this->data['AdvertiserOrder']['package_id']) {
				 $cond[] = 'AdvertiserOrder.package_id = '.$this->data['AdvertiserOrder']['package_id'];
				  (empty($this->params['named'])) ? $this->set('package_id', $this->data['AdvertiserOrder']['package_id']) :$this->set('package_id', $this->data['named']['package_id']) ;
			}
		
		
		if($this->data['AdvertiserOrder']['salse_id']) {
				$cond[] = 'AdvertiserProfile.creator = '.$this->data['AdvertiserOrder']['salse_id'];
				  (empty($this->params['named'])) ? $this->set('salse_id', $this->data['AdvertiserOrder']['salse_id']) :$this->set('salse_id', $this->data['named']['salse_id']) ;
			}	
					
		if($this->data['AdvertiserOrder']['group_id']) {
				$cond[] = 'AdvertiserOrder.user_group_id = '.$this->data['AdvertiserOrder']['group_id'];
				  (empty($this->params['named'])) ? $this->set('group_id', $this->data['AdvertiserOrder']['group_id']) :$this->set('group_id', $this->data['named']['group_id']) ;
			}				
			
			
				if(!empty($this->params['named'])){
				     if(isset($this->params['named']['company_name'] )){
					   $cond[] = 'AdvertiserProfile.company_name LIKE "%' .$this->params['named']['company_name']. '%"';
					   $this->set('company_name', $this->params['named']['company_name']);
					 }					 
				if(isset($this->params['named']['county'] )){
						$cond[] = 'AdvertiserProfile.county = '.$this->params['named']['county'];
					   $this->set('county', $this->params['named']['county']);
					 }
				if(isset($this->params['named']['city'] )){
						$cond[] = 'AdvertiserProfile.city = '.$this->params['named']['city'];
					   $this->set('city', $this->params['named']['city']);
					 }
				if(isset($this->params['named']['state'] )){
						$cond[] = 'AdvertiserProfile.state = '.$this->params['named']['state'];
					   $this->set('state', $this->params['named']['state']);
					 }	 
				if(isset($this->params['named']['category'] )){
					   $cond[] = 'AdvertiserProfile.subcategory LIKE "%,' .$this->params['named']['category'] . ',%"';	
					   $this->set('category', $this->params['named']['category']);
					 }
				if(isset($this->params['named']['publish'] )){
					    $cond[] = 'AdvertiserProfile.publish = "'.$this->params['named']['publish'].'"';	
					   $this->set('publish', $this->params['named']['publish']);
					 }				 
				if(isset($this->params['named']['package_id'] )){
					   $cond[] = 'AdvertiserOrder.package_id = '.$this->params['named']['package_id'];
					   $this->set('package_id', $this->params['named']['package_id']);
					 }
				if(isset($this->params['named']['salse_id'] )){					   
					   $cond[] = 'AdvertiserProfile.creator = '.$this->params['named']['salse_id'];
					   $this->set('salse_id', $this->params['named']['salse_id']);
					 }	 
				if(isset($this->params['named']['group_id'] )){					   
					   $cond[] = 'AdvertiserOrder.user_group_id = '.$this->params['named']['group_id'];
					   $this->set('group_id', $this->params['named']['group_id']);
					 }	 
				}
				/*if(isset($cond) && count($cond)>0) {
					$condi =  'AND '.implode(' AND ',$cond);
				} else {
					$condi = '';
				}
				
				$ids = '';
				//query to fetch data from both tables
				$result = $this->AdvertiserOrder->query('SELECT AdvertiserOrder.id FROM advertiser_orders as AdvertiserOrder,advertiser_profiles as AdvertiserProfile WHERE AdvertiserOrder.id = AdvertiserProfile.order_id '.$condi);
				//get ids of all orders
				if(is_array($result)) {
					foreach ($result as $result) {
						$ids[] = $result['AdvertiserOrder']['id'];
					}
				}
				//conditions to fetch data from order table from these ids.
				if(is_array($ids)) {
					$condition = array('AdvertiserOrder.id IN ('.implode(',',$ids).')');
				} else {
					$condition = array('AdvertiserOrder.id IN (0)');
				}*/
			 
			  // conditions for advertiser_orders, in which no package is selected
			  $cond[] = 'AdvertiserOrder.package_id != 0';
			  
			  $data = $this->paginate('OrderInstance', $cond);
			 // echo count($data).'<br />';
			  //pr($data);exit;
		      $this->set('OrderInstances', $data);
	   	}

	function recoverOrderInstances()
	{	
		$this->autoRender=false;
		$this->redirect(array('action' => "index"));
		exit;

		
// this code is used to update the inbox mail-subject came from change ordersheet, titled as  "Updation in Advertiser Order" to "Update to Advertiser Profile"		
/*		App::import('model', 'WorkOrder');
		$this->WorkOrder = new WorkOrder;
		$mymags=$this->WorkOrder->find('all',array('recursive'=>-1,'fields'=>array('WorkOrder.id','WorkOrder.subject'),'conditions'=>array('WorkOrder.subject'=>'Updation in Advertiser Order')));
		//echo count($mymags);
		//pr($mymags);
		$mmcc=0;
		foreach($mymags as $mymag)
		{
			$myMsgArr='';
			$myMsgArr['WorkOrder']['id']=$mymag['WorkOrder']['id'];
			$myMsgArr['WorkOrder']['subject']='Update to Advertiser Profile';
			$this->WorkOrder->save($myMsgArr,false);
			$mmcc++;
		}
		
		echo $mmcc.' rows affected';
		exit;*/

		
// this code is used to update the inbox mail-subject came generate vip offer updation, titled as  "Update VIP Offer" to "Update to VIP Offer"		
/*		App::import('model', 'WorkOrder');
		$this->WorkOrder = new WorkOrder;
		$mymags=$this->WorkOrder->find('all',array('recursive'=>-1,'fields'=>array('WorkOrder.id','WorkOrder.subject','WorkOrder.message'),'conditions'=>array('WorkOrder.subject'=>'Update VIP Offer')));
		

		$mmc=0;
		foreach($mymags as $mymag)
		{
			$myMsgArr='';
			$myMsgArr['WorkOrder']['id']=$mymag['WorkOrder']['id'];
			$myMsgArr['WorkOrder']['subject']='Update to VIP Offer';
			$myMsgArr['WorkOrder']['message']='Update to VIP Offer for the following advertiser profile.';
			$this->WorkOrder->save($myMsgArr,false);
			$mmc++;
		}
		
		echo $mmc.' rows affected';
		exit;*/
	 
// this commented code is used to recover all the order, those are previously placed in advertiser_orders table, to order_instances table
	  
/*  $allOrders=$this->AdvertiserOrder->query("SELECT AdvertiserOrder.id, AdvertiserProfile.id, AdvertiserOrder.package_id, AdvertiserOrder.created FROM advertiser_orders as AdvertiserOrder,advertiser_profiles as AdvertiserProfile WHERE AdvertiserOrder.id = AdvertiserProfile.order_id");
	   //echo count($allOrders);
	   //pr($allOrders);
	   
	   $this->loadModel('OrderInstance');
	   $mc=1;
	   foreach($allOrders as $allOrder)
	   {
			
			//pr($allOrder);	
			$this->OrderInstance->query("INSERT into order_instances (id,advertiser_order_id,advertiser_profile_id,package_id,created,modified) VALUES (DEFAULT,".$allOrder['AdvertiserOrder']['id'].",".$allOrder['AdvertiserProfile']['id'].",".$allOrder['AdvertiserOrder']['package_id'].",'".date('Y-m-d H:i:s',$allOrder['AdvertiserOrder']['created'])."','".date('Y-m-d H:i:s','1358890044')."')");
			$mc++;
	   }
	   echo $mc.' rows added';
	   exit;*/
	}

     // index page of AdvertiserOrder for listing (OLDest)
	   function indexOLD(){
	   
	   $this->autoRender=false;
	   $this->redirect(array('action' => "index"));
	   exit;
             //variable for display number of AdvertiserOrder name per page
	            $condition='';
				$loginDetail = $this->Auth->user();
				$this->set('currentAdmin', $this->Auth->user());
				$this->set('commissionPercent', '');
				$this->set('totalCommission', '');
				$this->set('paymentStatusSearch', ''); 
			    $this->set('paymentMethodSearch', '');  
			    $this->set('packageSearch', '');
				$this->set('advertiser_search', '');
			    $this->set('s_date', '');
				$this->set('e_date', '');
				
				$this->set('StatesList',$this->common->getAllState());  //  List states
			   $this->set('CitiesList',$this->common->getAllCity());   //  List cities
			   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
			   $this->set('CountriesList',$this->common->getAllCountry()); //  List countries
			   $this->set('categoryList',$this->common->getAllCategory()); //  List categories
			   $this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
			   $this->set('Packages', $this->common->getAllPackage(1));
			   $this->set('common',$this->common);
			   $this->set('SelsePersons',$this->common->getAllSelsePerson(5));
			   $this->set('UserGroup',$this->common->getAllUserGroup());
				$this->set('company_name','Company Name');
			   $this->set('city','');
			   $this->set('state','');
			   $this->set('county','');
			   $this->set('category','');
			   $this->set('package_id','');
			   $this->set('salse_id','');
			   $this->set('group_id','');
			   $this->set('publish','');
				$cond = array();
				$this->set('Packages', $this->common->getAllPackage(1));
				$this->set('PackagesName', $this->common->getAllPackage(2));
				$this->set('PackagesPrice', $this->common->getAllPackage(3));
				$this->set('AdvertiserProfiles', $this->common->getAllAdvertiserProfileForOrderListing());
				
				$this->loadModel('User');
				$this->set('salesperson', $this->User->returnUsersSales());

			 if($loginDetail['Admin']['user_group_id']==1 or  $loginDetail['Admin']['user_group_id']==4)
			  {
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'AdvertiserOrder.id' => 'desc' ));
			  }else{
			  	$this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'AdvertiserOrder.id' => 'desc'),'conditions'=>array('AdvertiserOrder.salesperson'=>$loginDetail['Admin']['id']));
			  }
			  if(isset($this->data['AdvertiserOrder']['company_name']) && $this->data['AdvertiserOrder']['company_name']=='Company Name') {
			  		$this->data['AdvertiserOrder']['company_name'] = '';
			  }
			  
			  if($this->data['AdvertiserOrder']['company_name']) {
			    $cond[] = 'AdvertiserProfile.company_name LIKE "%' .$this->data['AdvertiserOrder']['company_name']. '%"';
			    (empty($this->params['named'])) ? $this->set('company_name', $this->data['AdvertiserOrder']['company_name']) :$this->set('company_name', $this->data['named']['company_name']) ;
			  }				
				
			if($this->data['AdvertiserOrder']['county']) {
			   $cond[] = 'AdvertiserProfile.county = '.$this->data['AdvertiserOrder']['county'];
			  (empty($this->params['named'])) ? $this->set('county', $this->data['AdvertiserOrder']['county']) :$this->set('county', $this->data['named']['county']) ;
			}			
			
			if($this->data['AdvertiserOrder']['city']) {
			   $cond[] = 'AdvertiserProfile.city = '.$this->data['AdvertiserOrder']['city'];
			  (empty($this->params['named'])) ? $this->set('city', $this->data['AdvertiserOrder']['city']) :$this->set('city', $this->data['named']['city']) ;
			}
					
			if($this->data['AdvertiserOrder']['state']) {
			   $cond[] = 'AdvertiserProfile.state = '.$this->data['AdvertiserOrder']['state'];
			  (empty($this->params['named'])) ? $this->set('state', $this->data['AdvertiserOrder']['state']) :$this->set('state', $this->data['named']['state']) ;
			}
			
			if($this->data['AdvertiserOrder']['category']) {
			   $cond[] = 'AdvertiserProfile.subcategory LIKE "%,' .$this->data['AdvertiserOrder']['category'] . ',%"';
			  (empty($this->params['named'])) ? $this->set('category', $this->data['AdvertiserOrder']['category']) :$this->set('category', $this->data['named']['category']) ;
			}			
			
			if($this->data['AdvertiserOrder']['publish']) {
				  $cond[] = 'AdvertiserProfile.publish = "'.$this->data['AdvertiserOrder']['publish'].'"';	
				  (empty($this->params['named'])) ? $this->set('publish', $this->data['AdvertiserOrder']['publish']) :$this->set('publish', $this->data['named']['publish']) ;
			}
		
		 if($this->data['AdvertiserOrder']['package_id']) {
				 $cond[] = 'AdvertiserOrder.package_id = '.$this->data['AdvertiserOrder']['package_id'];
				  (empty($this->params['named'])) ? $this->set('package_id', $this->data['AdvertiserOrder']['package_id']) :$this->set('package_id', $this->data['named']['package_id']) ;
			}
		
		
		if($this->data['AdvertiserOrder']['salse_id']) {
				$cond[] = 'AdvertiserOrder.salesperson = '.$this->data['AdvertiserOrder']['salse_id'];
				  (empty($this->params['named'])) ? $this->set('salse_id', $this->data['AdvertiserOrder']['salse_id']) :$this->set('salse_id', $this->data['named']['salse_id']) ;
			}	
					
		if($this->data['AdvertiserOrder']['group_id']) {
				$cond[] = 'AdvertiserOrder.user_group_id = '.$this->data['AdvertiserOrder']['group_id'];
				  (empty($this->params['named'])) ? $this->set('group_id', $this->data['AdvertiserOrder']['group_id']) :$this->set('group_id', $this->data['named']['group_id']) ;
			}				
			
			
				if(!empty($this->params['named'])){
				     if(isset($this->params['named']['company_name'] )){
					   $cond[] = 'AdvertiserProfile.company_name LIKE "%' .$this->params['named']['company_name']. '%"';
					   $this->set('company_name', $this->params['named']['company_name']);
					 }					 
				if(isset($this->params['named']['county'] )){
						$cond[] = 'AdvertiserProfile.county = '.$this->params['named']['county'];
					   $this->set('county', $this->params['named']['county']);
					 }
				if(isset($this->params['named']['city'] )){
						$cond[] = 'AdvertiserProfile.city = '.$this->params['named']['city'];
					   $this->set('city', $this->params['named']['city']);
					 }
				if(isset($this->params['named']['state'] )){
						$cond[] = 'AdvertiserProfile.state = '.$this->params['named']['state'];
					   $this->set('state', $this->params['named']['state']);
					 }	 
				if(isset($this->params['named']['category'] )){
					   $cond[] = 'AdvertiserProfile.subcategory LIKE "%,' .$this->params['named']['category'] . ',%"';	
					   $this->set('category', $this->params['named']['category']);
					 }
				if(isset($this->params['named']['publish'] )){
					    $cond[] = 'AdvertiserProfile.publish = "'.$this->params['named']['publish'].'"';	
					   $this->set('publish', $this->params['named']['publish']);
					 }				 
				if(isset($this->params['named']['package_id'] )){
					   $cond[] = 'AdvertiserOrder.package_id = '.$this->params['named']['package_id'];
					   $this->set('package_id', $this->params['named']['package_id']);
					 }
				if(isset($this->params['named']['salse_id'] )){					   
					   $cond[] = 'AdvertiserOrder.salesperson = '.$this->params['named']['salse_id'];
					   $this->set('salse_id', $this->params['named']['salse_id']);
					 }	 
				if(isset($this->params['named']['group_id'] )){					   
					   $cond[] = 'AdvertiserOrder.user_group_id = '.$this->params['named']['group_id'];
					   $this->set('group_id', $this->params['named']['group_id']);
					 }	 
				}
				if(isset($cond) && count($cond)>0) {
					$condi =  'AND '.implode(' AND ',$cond);
				} else {
					$condi = '';
				}
				$ids = '';
				//query to fetch data from both tables
				$result = $this->AdvertiserOrder->query('SELECT AdvertiserOrder.id FROM advertiser_orders as AdvertiserOrder,advertiser_profiles as AdvertiserProfile WHERE AdvertiserOrder.id = AdvertiserProfile.order_id '.$condi);
				//get ids of all orders
				if(is_array($result)) {
					foreach ($result as $result) {
						$ids[] = $result['AdvertiserOrder']['id'];
					}
				}
				//conditions to fetch data from order table from these ids.
				if(is_array($ids)) {
					$condition = array('AdvertiserOrder.id IN ('.implode(',',$ids).')');
				} else {
					$condition = array('AdvertiserOrder.id IN (0)');
				}
			  $data = $this->paginate('AdvertiserOrder', $condition);
		      $this->set('AdvertiserOrders', $data);
			   
	   	}
	   // adding new AdvertiserOrder in database	  
	   
	    function addNewAdvertiserOrder(){
					//detect device on which site is going on
					$platform = ($this->mobile->isMobile() ? ($this->mobile->isTablet() ? 'tablet' : 'mobile') : 'computer');
					$this->set('platform',$platform);
					//$this->Session->read('Auth.Admin.id')
					$checked = 0;
					$offer_count = 1;
					$save_later = 0;
				  	$this->set('checked',$checked);
					$this->set('offer_count',$offer_count);
					$this->set('save_later',$save_later);
					$this->set('description_2','');
					$this->set('description_3','');
					$this->set('description_4','');
					$this->set('description_5','');
					
					$this->set('title_2','');
					$this->set('title_3','');
					$this->set('title_4','');
					$this->set('title_5','');
					for($r=2;$r<=5;$r++) {
						$this->set('main_offer_dscpt1_'.$r,'');
						$this->set('main_offer_dscpt2_'.$r,'');
						$this->set('main_offer_dscpt3_'.$r,'');
					}
					$all_packages = $this->common->getAdminPackage();
		            $this->set('Packages', $all_packages);
					$this->set('StatesList',$this->common->getAllState());  //  List states
					 $this->loadModel('User');
					$this->set('salesperson', $this->User->returnUsersSales());
					$state_id = '';
					if(isset($this->data)) {
						$state_id = $this->data['AdvertiserOrder']['state'];
					}
					$county_id = '';
					if(isset($this->data)) {
					/*pr($this->data);
					exit;*/
						$county_id = $this->data['AdvertiserOrder']['county'];
					}
					$this->set('county_id',$county_id);
					
					$this->set('CitiesList',$this->common->getCountyCity($county_id));   //  List cities
						
					$this->set('CountyList',$this->common->getAllCountyByState($state_id)); //  List counties
					$this->set('categoryList',$this->common->getAllCategory()); //  List categories
					$this->set('AllCatSubcat',$this->common->getAllCatSubcatoption('AdvertiserOrder'));
					//$this->set('AdvertiserProfiles', $this->common->getAllAdvertiserProfile());
					//$this->loadModel('User');
					//$this->set('salesperson', $this->User->returnUsersSales());
					if(isset($this->data)){
					//create pdf
					  $this->set('package_id',$this->data['AdvertiserOrder']['package_id']);
					  $offer_count = $this->data['AdvertiserOrder']['offer_count'];
					  $this->set('offer_count',$offer_count);
					  for($p=2;$p<=$offer_count;$p++) {
						$this->set('description_'.$p,$this->data['AdvertiserOrder']['description_'.$p]);
						$this->set('title_'.$p,$this->data['AdvertiserOrder']['title_'.$p]);
					  }
					for($e=2;$e<=5;$e++) {
						if(isset($this->data['AdvertiserOrder']['main_offer_dscpt1_'.$e]))	{		
							$this->set('main_offer_dscpt1_'.$e,$this->data['AdvertiserOrder']['main_offer_dscpt1_'.$e]);
						}
						if(isset($this->data['AdvertiserOrder']['main_offer_dscpt2_'.$e]))	{
							$this->set('main_offer_dscpt2_'.$e,$this->data['AdvertiserOrder']['main_offer_dscpt2_'.$e]);
						}
						if(isset($this->data['AdvertiserOrder']['main_offer_dscpt3_'.$e]))	{
							$this->set('main_offer_dscpt3_'.$e,$this->data['AdvertiserOrder']['main_offer_dscpt3_'.$e]);
						}
					}
				  if($this->data['AdvertiserOrder']['processed']!='location_processed') {
				  	$checked = 1;
				  	$this->set('checked',$checked);
				  } else {
				  	$checked = 0;
				  	$this->set('checked',$checked);
				  }
	    	      $this->AdvertiserOrder->set($this->data['AdvertiserOrder']);
			              if (empty($this->data)){
                          		$this->data = $this->AdvertiserOrder->find(array('AdvertiserOrder.id' => $id));
                          }
			              if($this->data['AdvertiserOrder']!=''){
						/*setting error message if validation fails*/
						  $errors = $this->AdvertiserOrder->invalidFields();
						  if(isset($errors) && count($errors)>0) {
							$error = implode('<br>', $errors).'<br />';
						  }	else {
							$error = '';
						  }
						if($this->data['AdvertiserOrder']['processed']!='location_processed') {
								if($this->data['AdvertiserOrder']['credit_name']=='') {
									$error.='Please enter name on credit card.<br />';											
								}
								if($this->data['AdvertiserOrder']['credit_number']=='') {
									$error.='Please enter Credit Card Number.<br />';											
								}
								/*if($this->data['AdvertiserOrder']['cvv']=='' || !is_numeric($this->data['AdvertiserOrder']['cvv'])) {
									$error.='Please enter valid CVV number.<br />';											
								}*/
								if($this->data['AdvertiserOrder']['card_exp_month']=='') {
									$error.='Please select credit card expiry month.<br />';											
								}
								if($this->data['AdvertiserOrder']['card_exp_year']=='') {
									$error.='Please select credit card expiry year.<br />';											
								}							
							}
									if(isset($error) && $error!='<br/>' && $error !='') {
									      $this->Session->setFlash($error);  
						             }
					                else{
										  $sid = $this->Auth->user();
										  App::import('model', 'AdvertiserProfile');
										  $this->AdvertiserProfile = new AdvertiserProfile;
										  //here we are checking same email id in database. we will not allow to save same email twice
										  $emailFound = $this->AdvertiserProfile->checkEmail(trim($this->data['AdvertiserOrder']['email']));
										  if(is_array($emailFound) && isset($emailFound[0]['advertiser_profiles']['id']) && $emailFound[0]['advertiser_profiles']['id']!='')
										  {
										 	   $this->Session->setFlash('Email is already exists in database.Please provide another email.');
										  }
										  else {
												if($this->data['AdvertiserOrder']['main_offer_title']== '' && $this->data['AdvertiserOrder']['description_1']=='' && $this->data['AdvertiserOrder']['save_later']!=1) {
													$this->Session->setFlash('Please fill Main Savings Offer.');
												} else {
														$CreditCardTransID = '';
														$ClientTransID ='';
														$TStamp = '';
											 		if($this->data['AdvertiserOrder']['processed']=='manual_process') {
													$this->loadModel('Package');
													$p_price = $this->Package->find('first',array('fields'=>array('Package.setup_price','Package.monthly_price'),'conditions'=>array('Package.id'=>$this->data['AdvertiserOrder']['package_id'])));
													$total_price = ($p_price['Package']['setup_price']+$p_price['Package']['monthly_price']);
													
													//------------------------- Payment Gateway Start ----------------------------//			

													$authNameArr=explode(' ',$this->data['AdvertiserOrder']['credit_name']);
													
													$auth_fname=$authNameArr[0];
													$auth_lname=$authNameArr[1];
														
													$final_exp_date=$this->data['AdvertiserOrder']['card_exp_month'].$this->data['AdvertiserOrder']['card_exp_year'];
													$amount = number_format($total_price,1);
													
													//$amount = 1.0;
													//----------------Auth.net start------------------//
													$post_url = "https://secure.authorize.net/gateway/transact.dll"; // for live account intergartion
													// $post_url = "https://test.authorize.net/gateway/transact.dll"; // for test account intergartion
								
													$post_values = array(
														
														// the API Login ID and Transaction Key must be replaced with valid values
														"x_login"			=> AUTHORIZE_APP_LOGIN,
														"x_tran_key"		=> AUTHORIZE_TRANSACTION_KEY,
													
														"x_version"			=> "3.1",
														"x_delim_data"		=> "TRUE",
														"x_delim_char"		=> "|",
														"x_relay_response"	=> "FALSE",
													
														"x_type"			=> "AUTH_CAPTURE",
														"x_method"			=> "CC",
														"x_card_num"		=> $this->data['AdvertiserOrder']['credit_number'],
														"x_exp_date"		=> $final_exp_date,
													
														"x_amount"			=> $amount,
														"x_description"		=> "",
													
														"x_first_name"		=> $auth_fname,
														"x_last_name"		=> $auth_lname,
														"x_address"			=> "",
														"x_state"			=> "",
														"x_zip"				=> ""
														// Additional fields can be added here as outlined in the AIM integration
														// guide at: http://developer.authorize.net
													);
													
													$post_string = "";
													foreach( $post_values as $key => $value )
														{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
													$post_string = rtrim( $post_string, "& " );
													
													$request = curl_init($post_url); // initiate curl object
													curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
													curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
													curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
													curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
													$post_response = curl_exec($request); // execute curl post and store results in $post_response
													// additional options may be required depending upon your server configuration
													// you can find documentation on curl options at http://www.php.net/curl_setopt
													curl_close ($request); // close curl object
												
													// This line takes the response and breaks it into an array using the specified delimiting character
													$response_array = explode($post_values["x_delim_char"],$post_response);
													
													
													//----------------Auth.net end------------------//																		
													
													if(isset($response_array[0]) && $response_array[0]!='' && $response_array[0]=='1')
													{
														date_default_timezone_set('US/Eastern');
														
														$TStamp = mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
														
														$CreditCardTransID = $response_array[6];
														$ClientTransID = $response_array[7];
													
													}else{
														$this->Session->setFlash("Payment Gateway Error : ".$response_array[3]);
														return false;
													}							
												}
													
													//------------------------- Payment Gateway End ----------------------------//
													
													$logoname = '';												
											if(($this->data['AdvertiserOrder']['processed']!='manual_process') || ($this->data['AdvertiserOrder']['processed']=='manual_process' && $CreditCardTransID!='')) {
									$saveArrayAdvertiser = array();
									// upload uploaded file
									if($this->data['AdvertiserOrder']['logo']['name']!=""){
									
									$type = $this->data['AdvertiserOrder']['logo']['type'];
									if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif"){			                         
									
									$this->data['AdvertiserOrder']['logo']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['AdvertiserOrder']['logo']['name']);
									$logoname = $this->data['AdvertiserOrder']['logo']['name'];
									$docDestination = APP.'webroot/img/logo/'.$this->data['AdvertiserOrder']['logo']['name']; 
									@chmod(APP.'webroot/img/logo',0777);
									move_uploaded_file($this->data['AdvertiserOrder']['logo']['tmp_name'], $docDestination) or die($docDestination);
									$saveArrayAdvertiser['AdvertiserProfile']['logo'] = $this->data['AdvertiserOrder']['logo']['name'];						
									}else{					
											$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
										}					
									}	  //first inserting record in advertiser order table
									
											$saveArray = array();
											$saveArray['AdvertiserOrder']['payment_status']	= 'pending';
											$saveArray['AdvertiserOrder']['order_status']	= 'pending';
											
											if(isset($this->data['AdvertiserOrder']['salesperson'])) {
												$saveArray['AdvertiserOrder']['salesperson']	= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
												$saveArray['AdvertiserOrder']['salesperson']	= $sid['Admin']['id'];
											  }
											  
											  
											if($this->data['AdvertiserOrder']['processed']=='manual_process') {
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_process']  	= $this->data['AdvertiserOrder']['processed'];
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_id'] 	= $CreditCardTransID;
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_client_id']= $ClientTransID;
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_date']  	= $TStamp;
											  $saveArray['AdvertiserOrder']['payment_status']	= 'approved';
											  $saveArray['AdvertiserOrder']['order_status']	= 'approved';
											  
											} else {
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_name']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_number']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['cvv']  			= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['card_exp_month'] 	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['card_exp_year']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_process']  	= $this->data['AdvertiserOrder']['processed'];
											}
											  
											  $saveArray['AdvertiserOrder']['package_id']   				= $this->data['AdvertiserOrder']['package_id'];
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
											  	$saveArray['AdvertiserOrder']['salesperson']  				= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
											  	$saveArray['AdvertiserOrder']['salesperson']  				= $sid['Admin']['id'];
											  }
											  $saveArray['AdvertiserOrder']['user_group_id']     			= $this->Session->read('Auth.Admin.user_group_id');	  
											  $saveArray['AdvertiserOrder']['save_later']     				= $this->data['AdvertiserOrder']['save_later'];	
											  $saveArrayAdvertiser['AdvertiserProfile']['show_address']    	= $this->data['AdvertiserOrder']['show_address'];	
											  $saveArrayAdvertiser['AdvertiserProfile']['address2']     	= $this->data['AdvertiserOrder']['address2'];	
											  $saveArrayAdvertiser['AdvertiserProfile']['show_address2']    = $this->data['AdvertiserOrder']['show_address2'];											
											  $this->AdvertiserOrder->save($saveArray);
											  
											  //aftre getting last inserted id for advertiser table we are inserting in work order table
											  if($saveArray['AdvertiserOrder']['save_later']!=1) {
											  //--------------------------------------------------------------
												$this->loadModel('Setting');
												$this->loadModel('FrontUser');
												$setvale = $this->Setting->find('first',array('fields'=>array('refer_business_bucks')));
												$bucksprice = $setvale['Setting']['refer_business_bucks'];
												//bucks management
												$this->loadModel('ReferredBusiness');
													$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.phone'=>$this->data['AdvertiserOrder']['phoneno'],'ReferredBusiness.status'=>'no')));
												
												if($this->data['AdvertiserOrder']['phoneno2']!='' && empty($checkRefer)) {
													$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.phone'=>$this->data['AdvertiserOrder']['phoneno2'],'ReferredBusiness.status'=>'no')));
												}
												if(is_array($checkRefer) && !empty($checkRefer)) {					
													$savearr['ReferredBusiness']['id'] = $checkRefer['ReferredBusiness']['id'];
													$savearr['ReferredBusiness']['status'] = 'yes';
													$savearr['ReferredBusiness']['bucks'] = $bucksprice;
													$savearr['ReferredBusiness']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
													$savearr['ReferredBusiness']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
													$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
													$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;	
													$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
													$this->ReferredBusiness->save($savearr);
													$this->FrontUser->save($savearr);
													$this->loadModel('Buck');
													$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$this->data['AdvertiserOrder']['county'],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
													if(is_array($checkBuck) && count($checkBuck)) {
														$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
														$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
													} else {
														$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
														$saveBuck['Buck']['county_id'] = $this->data['AdvertiserOrder']['county'];
														$saveBuck['Buck']['bucks'] = $bucksprice;
														$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
													}
													$this->Buck->save($saveBuck);
												}
										}
											 //aftre getting last inserted id for advertiser table we are inserting in advertiser profile table
											  $saveArrayAdvertiser['AdvertiserProfile']['name']   	    	=  $this->data['AdvertiserOrder']['advertiser_name'];
											  $saveArrayAdvertiser['AdvertiserProfile']['company_name']     = $this->data['AdvertiserOrder']['company_name'];
											  $saveArrayAdvertiser['AdvertiserProfile']['email']   	        =  $this->data['AdvertiserOrder']['email'];
											  $saveArrayAdvertiser['AdvertiserProfile']['address']  		= $this->data['AdvertiserOrder']['address'];
											  $saveArrayAdvertiser['AdvertiserProfile']['city']   	        =  $this->data['AdvertiserOrder']['city'];
											  $saveArrayAdvertiser['AdvertiserProfile']['county']  		    = $this->data['AdvertiserOrder']['county'];
											  $saveArrayAdvertiser['AdvertiserProfile']['all_cities'] 		= $this->data['AdvertiserOrder']['all_cities'];
											  $saveArrayAdvertiser['AdvertiserProfile']['website']  	    =  $this->data['AdvertiserOrder']['website'];
											  $saveArrayAdvertiser['AdvertiserProfile']['country']   	    =  840;
											  $saveArrayAdvertiser['AdvertiserProfile']['zip']  			= $this->data['AdvertiserOrder']['zip'];
											  $saveArrayAdvertiser['AdvertiserProfile']['phoneno2']  		= $this->data['AdvertiserOrder']['phoneno2'];
											  $saveArrayAdvertiser['AdvertiserProfile']['city2']  			= $this->data['AdvertiserOrder']['city2'];
											  $saveArrayAdvertiser['AdvertiserProfile']['zip2']  			= $this->data['AdvertiserOrder']['zip2'];
											  date_default_timezone_set('US/Eastern');
											  $saveArrayAdvertiser['AdvertiserProfile']['contract_date']  	= strtotime($this->data['AdvertiserOrder']['contract_date']);
											 	
												
												$lastOrderId = $this->AdvertiserOrder->getLastInsertId();											
												$fileName = '';
												// signature
												if($this->data['AdvertiserOrder']['processed']!='location_processed') {	
												
																								
												if(isset($_POST['output']) && $_POST['output']!=''){
														$img = $this->sigJsonToImage($_POST['output']);
								
														$fileName = time()."_".$lastOrderId."-signature.png";
								
														$filePath = WWW_ROOT."Signature/".$fileName;
								
														imagepng($img, $filePath);
								
														imagedestroy($img);						
								
													}else{ 
								
														$img = imagecreatetruecolor(400, 30);
								
														$bgColour = imagecolorallocate($img, 0xff, 0xff, 0xff);
								
														$penColour = imagecolorallocate($img, 0x14, 0x53, 0x94);
								
														imagefilledrectangle($img, 0, 0, 399, 29, $bgColour);
								
														$text = $this->data['AdvertiserOrder']['name'];
								
														$font = WWW_ROOT.'journal.ttf';
								
														imagettftext($img, 20, 0, 10, 20, $penColour, $font, $text);
								
														// Save to file
								
														$fileName = time()."_".$lastOrderId."-signature.png";
								
														$filePath = WWW_ROOT."Signature/".$fileName;
								
														imagepng($img, $filePath);
								
														imagedestroy($img);
								
													}
												}
												
												/*pr($this->data);
												exit;*/
											  $saveArrayAdvertiser['AdvertiserProfile']['fax']  			= $this->data['AdvertiserOrder']['fax'];
											  $saveArrayAdvertiser['AdvertiserProfile']['modifier']  		= $this->Session->read('Auth.Admin.id');
											  
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
											  	$saveArrayAdvertiser['AdvertiserProfile']['creator']		= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
											  	$saveArrayAdvertiser['AdvertiserProfile']['creator']		= $sid['Admin']['id'];
											  }
											  $saveArrayAdvertiser['AdvertiserProfile']['currency']  		= $this->data['AdvertiserOrder']['currency'];
											  $saveArrayAdvertiser['AdvertiserProfile']['publish']  		= 'no';
											  $saveArrayAdvertiser['AdvertiserProfile']['facebook']  		= $this->data['AdvertiserOrder']['facebook'];
											  $saveArrayAdvertiser['AdvertiserProfile']['twitter']  		= $this->data['AdvertiserOrder']['twitter'];
											  $saveArrayAdvertiser['AdvertiserProfile']['order_id']  		= $lastOrderId;
											  $saveArrayAdvertiser['AdvertiserProfile']['signature']  		= $fileName;
											  //pr($saveArrayAdvertiser);
											  $this->AdvertiserProfile->save($saveArrayAdvertiser);									   
											  $ad_id_latest = $this->AdvertiserProfile->getLastInsertId();
											  
											  /*------------to set the multiple category and subcategory------------------*/
												$this->loadModel('AdvertiserCategory');
												foreach($this->data['AdvertiserOrder']['subcategory'] as $pair) {
													$break = explode('-',$pair);
													$catSubcat = $this->common->returnCatSubcatId($break[0],$break[1]);
													if($catSubcat) {
														$save = '';
														$save['AdvertiserCategory']['id'] = '';
														$save['AdvertiserCategory']['advertiser_profile_id'] = $ad_id_latest;
														$save['AdvertiserCategory']['categories_subcategory_id'] = $catSubcat;
														$this->AdvertiserCategory->save($save,false);
													}
												}
					
					
					
											 //$this->loadModel('FrontUser');	
									if($saveArray['AdvertiserOrder']['save_later']!=1) {
												  App::import('model', 'FrontUser');
												  $this->FrontUser = new FrontUser;
												 $arr = array();
												 $password = $this->common->randomPassword(8);
												 $arr['FrontUser']['password'] = $this->Auth->password($password);
												 $arr['FrontUser']['realpassword'] = $password;
												 $arr['FrontUser']['name'] 		= $this->data['AdvertiserOrder']['advertiser_name'];
												 $arr['FrontUser']['email'] 	= $this->data['AdvertiserOrder']['email'];
												 $arr['FrontUser']['status'] 	= 'yes';
												 $arr['FrontUser']['county_id'] = $this->data['AdvertiserOrder']['county'];		
												 $arr['FrontUser']['state_id'] = $this->data['AdvertiserOrder']['state'];			
												 $arr['FrontUser']['advertiser_profile_id'] = $ad_id_latest;
												 $arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
												 $this->FrontUser->save($arr);
												 //$this->sendUsernamePassword($this->data['AdvertiserOrder']['email'],$password);
											 }											 
											$phoneAdvertiser=$this->data['AdvertiserOrder']['phoneno'];	
											$this->AdvertiserProfile->query("UPDATE advertiser_profiles SET phoneno='".$phoneAdvertiser."', state='".$this->data['AdvertiserOrder']['state']."' WHERE id=$ad_id_latest");
											App::import('model', 'SavingOffer');
										  	 $this->SavingOffer = new SavingOffer;
											 //pr($this->data);
											 $county_id = $this->data['AdvertiserOrder']['county'];	
											 
											 $advertiser_profile_id = $this->AdvertiserProfile->getlastinsertid();
												$pdf_name = 'order_'.time().''.$advertiser_profile_id.'.pdf';
												
												 //aftre getting last inserted id for advertiser table we are inserting in work order table
											if($saveArray['AdvertiserOrder']['save_later']!=1) {
												//----------save the instance of order, when new order is placed (Start)------//
												App::import('model', 'OrderInstance');
												$this->OrderInstance = new OrderInstance;
												$saveInstanceArray = array();
												$saveInstanceArray['OrderInstance']['advertiser_order_id']   =  $lastOrderId;
												$saveInstanceArray['OrderInstance']['advertiser_profile_id']  =  $advertiser_profile_id;
												$saveInstanceArray['OrderInstance']['package_id']   	=  $this->data['AdvertiserOrder']['package_id'];
												$saveInstanceArray['OrderInstance']['insert_status']   	=  4;
												$this->OrderInstance->save($saveInstanceArray);
												//----------save the instance of order, when new order is placed (End)------//
											}
											if($saveArray['AdvertiserOrder']['save_later']!=1) {
												  App::import('model', 'WorkOrder');
												  $this->WorkOrder = new WorkOrder;
												  $saveWorkArray = array();
												  $order_id = $this->AdvertiserOrder->getLastInsertId();
												  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $order_id;
												  $saveWorkArray['WorkOrder']['read_status']   				=  0;
												  $saveWorkArray['WorkOrder']['subject']   					=  'New work order Generated';
												  $saveWorkArray['WorkOrder']['message']	=	'A new work order has been placed recently.Order detail is below:';
												  $saveWorkArray['WorkOrder']['type']   					=  'workorder';
												  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
												  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
												  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');								
												  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can go further and add other details about this advertiser in advertiser profiles section like saving offers , vip offers etc. OR You can view pdf file of sales order sheet. Just <a href="'.FULL_BASE_URL.router::url('/',false).'files/pdf/'.$pdf_name.'" style="color:white" target="_blank">Click Here for PDF</a>';
												  
												  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
													$saveWorkArray['WorkOrder']['salseperson_id']		= $this->data['AdvertiserOrder']['salesperson'];
												  } else {
													$saveWorkArray['WorkOrder']['salseperson_id']		= $sid['Admin']['id'];
												  }
												  
												  date_default_timezone_set('US/Eastern');
											  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
												  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
											   	  $this->WorkOrder->save($saveWorkArray);
												  
												  
												   $saveWorkArray = '';
												  $saveWorkArray['WorkOrder']['id']   						=  '';
												  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $order_id;
												  $saveWorkArray['WorkOrder']['read_status']   				=  0;
												  $saveWorkArray['WorkOrder']['subject']   					=  'New Contract';
												  $saveWorkArray['WorkOrder']['message']   				=  'A new Contract has been placed recently. details are below:';
												  $saveWorkArray['WorkOrder']['type']   					=  'Contract';
												  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
												  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
												  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
												  $saveWorkArray['WorkOrder']['bottom_line']   				=  'The Advertiser is currently unpublish. As per zuni\'s contract plan, Only admin can publish the profile.';
												  
												  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
													$saveWorkArray['WorkOrder']['salseperson_id']		= $this->data['AdvertiserOrder']['salesperson'];
												  } else {
													$saveWorkArray['WorkOrder']['salseperson_id']		= $sid['Admin']['id'];
												  }
												  date_default_timezone_set('US/Eastern');
												  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
												  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
												  $this->WorkOrder->save($saveWorkArray);
											  }	 
											 
											 									
											if($saveArray['AdvertiserOrder']['save_later']==1) {
												$live = 0;
											} else {
												$live = 1;
											}
											 for($i=1;$i<=$offer_count;$i++) {
											 if(isset($this->data['AdvertiserOrder']['description_'.$i])) {											
												if($i==1) {
														$title = $this->data['AdvertiserOrder']['main_offer_title'];
														$off = '';
														$description = $this->data['AdvertiserOrder']['description_1'];
														//$off = $this->data['AdvertiserOrder']['main_offer_discount'];
														$current_saving_offer = 1;
														$other_saving_offer = 0;
														$disclaimer = $this->data['AdvertiserOrder']['main_offer_dscpt1'].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt2'].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt3'];
												}else {
														$title = $this->data['AdvertiserOrder']['title_'.$i];
														$description = $this->data['AdvertiserOrder']['description_'.$i];
														$off = '';
														$current_saving_offer = 0;
														$other_saving_offer = 1;
														$disclaimer = $this->data['AdvertiserOrder']['main_offer_dscpt1_'.$i].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt2_'.$i].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt3_'.$i];
												}										
												
												$category = '';
												$subcategory ='';
												$description = $this->data['AdvertiserOrder']['description_'.$i];
												$offer_start_date =  '';
												$offer_expiry_date = '';
												$no_valid_other_offer = '';
												$no_transferable = '';
												$other = '';
												if(isset($this->data['AdvertiserOrder']['not_valid_other'.$i])) {
													$no_valid_other_offer = $this->data['AdvertiserOrder']['not_valid_other'.$i];
												}
												if(isset($this->data['AdvertiserOrder']['n_transferable'.$i])) {
													$no_transferable = $this->data['AdvertiserOrder']['n_transferable'.$i];
												}
												if(isset($this->data['AdvertiserOrder']['other'.$i])) {
													$other = $this->data['AdvertiserOrder']['other'.$i];
												}
											}											
											$this->SavingOffer->query("INSERT INTO saving_offers (current_saving_offer, other_saving_offer, title, off,  advertiser_profile_id, advertiser_county_id, description, offer_start_date, offer_expiry_date, no_valid_other_offer, no_transferable, other, disclaimer, live) VALUES ('$current_saving_offer', '$other_saving_offer', '$title', '$off', '$advertiser_profile_id', '$county_id', '$description', '$offer_start_date', '$offer_expiry_date', '$no_valid_other_offer', '$no_transferable', '$other', '$disclaimer', '$live')");
											//$this->SavingOffer->save($saveSavingOffer);
										}/*
										echo $offer_count;
										pr($saveSavingOffer);
										exit;*/
											 //getAdminEmail//getSalesEmail
										
											 
									if(isset( $this->data['AdvertiserOrder']['Vip_title']) &&  $this->data['AdvertiserOrder']['Vip_title']!='') {
									   App::import('model', 'VipOffer');
										$this->VipOffer = new VipOffer;
										$this->VipOffer->deleteAll(array('VipOffer.advertiser_profile_id'=>$ad_id_latest));
											 //$vipoffer['VipOffer']['off'] = $this->data['AdvertiserOrder']['main_offer_discount'];
											 $vipoffer['VipOffer']['description'] = $this->data['AdvertiserOrder']['main_offer_discount'];
											 $vipoffer['VipOffer']['advertiser_profile_id'] = $ad_id_latest;
											 $vipoffer['VipOffer']['title'] = $this->data['AdvertiserOrder']['Vip_title'];
											 $vipoffer['VipOffer']['advertiser_county_id'] = $this->data['AdvertiserOrder']['county'];
											 $vipoffer['VipOffer']['category'] = $this->data['AdvertiserOrder']['Vip_Category'];
											 $vipoffer['VipOffer']['status'] = 'yes';
											 
											 $this->VipOffer->save($vipoffer);
											 }
											 $signature = 'No Signature';
											 if($fileName!='') {
											 	$signature = '<img src="'.FULL_BASE_URL.router::url('/',false).'Signature/'.$fileName.'" />';
											 }
												// Here we are sending email to advertiser for notification that his/he order has been placed at Zuni.com
											if($saveArray['AdvertiserOrder']['save_later']!=1) {
											
												App::import('model', 'Setting');
	    										$this->Setting = new Setting;
												$emailArray = $this->Setting->getAdvertiserEmailData();
												$package_name =   $this->common->getAllPackage(2);
												$package_price =   $this->common->getAllPackage(3);
												$bodyData = $this->Setting->replaceUserMarkers($emailArray[0]['settings']['new_advertiser_body'],$this->data['AdvertiserOrder']['advertiser_name'],$package_name[$this->data['AdvertiserOrder']['package_id']],$this->data['AdvertiserOrder']['company_name'],$package_price[$this->data['AdvertiserOrder']['package_id']],$this->AdvertiserOrder->getlastinsertid(),$password,$signature);
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = $this->emailhtml->email_header($county_id);
												$this->body .=$bodyData;
												$this->body .= $this->emailhtml->email_footer($county_id);											
												$this->set('var1',$this->data['AdvertiserOrder']['email']);
												$this->set('var2',$emailArray[0]['settings']['new_advertiser_subject']);
												$this->set('var3',$this->common->getReturnEmail());
												$this->set('var4',$this->common->getFromName().' <'.$this->common->getSalesEmail().'>');
												$this->set('var5',$this->body);											
											//create pdf
												$this->set('fileName',$fileName);
												$this->set('logoname',$logoname);
												$this->set('name',$pdf_name);
										
										if($this->Session->read('Auth.Admin.user_group_id')==1) {
											$this->set('redirectUrl',FULL_BASE_URL.router::url('/',false).'advertiser_profiles/thanksPage/'.$advertiser_profile_id);
										} else {
											$this->set('redirectUrl',FULL_BASE_URL.router::url('/',false).'advertiser_orders');
										}
										
												$this->set('Email',$this->Email);						
												$this->layout = 'pdf';
												$this->set('common',$this->common);
												$this->render('/advertiser_orders/pdf');
									}
										if($saveArray['AdvertiserOrder']['save_later']==1) {
											 	//$this->Session->setFlash('Your order has been submitted successfully.');
											 	//$this->redirect(array('controller'=>'advertiser_profiles','action' => 'thanksPage',$advertiser_profile_id));
											//} else {
												$this->Session->setFlash('Your order has been saved successfully.');
											 	$this->redirect(array('action' => "savedOrder"));
											}
										 }
									 }
								}
						 }
				   }
             }
		}
		// old addNewAdvertiserOrder for backup
	    function __addNewAdvertiserOrder__(){
					//detect device on which site is going on
					$platform = ($this->mobile->isMobile() ? ($this->mobile->isTablet() ? 'tablet' : 'mobile') : 'computer');
					$this->set('platform',$platform);
					//$this->Session->read('Auth.Admin.id')
					$checked = 0;
					$offer_count = 1;
					$save_later = 0;
				  	$this->set('checked',$checked);
					$this->set('offer_count',$offer_count);
					$this->set('save_later',$save_later);
					$this->set('description_2','');
					$this->set('description_3','');
					$this->set('description_4','');
					$this->set('description_5','');
					
					$this->set('title_2','');
					$this->set('title_3','');
					$this->set('title_4','');
					$this->set('title_5','');
					for($r=2;$r<=5;$r++) {
						$this->set('main_offer_dscpt1_'.$r,'');
						$this->set('main_offer_dscpt2_'.$r,'');
						$this->set('main_offer_dscpt3_'.$r,'');
					}
					$all_packages = $this->common->getAdminPackage();
		            $this->set('Packages', $all_packages);
					$this->set('StatesList',$this->common->getAllState());  //  List states
					 $this->loadModel('User');
					$this->set('salesperson', $this->User->returnUsersSales());
					$state_id = '';
					if(isset($this->data)) {
						$state_id = $this->data['AdvertiserOrder']['state'];
					}
					$county_id = '';
					if(isset($this->data)) {
					/*pr($this->data);
					exit;*/
						$county_id = $this->data['AdvertiserOrder']['county'];
					}
					$this->set('county_id',$county_id);
					
					$this->set('CitiesList',$this->common->getCountyCity($county_id));   //  List cities
						
					$this->set('CountyList',$this->common->getAllCountyByState($state_id)); //  List counties
					$this->set('categoryList',$this->common->getAllCategory()); //  List categories
					$this->set('AllCatSubcat',$this->common->getAllCatSubcatoption('AdvertiserOrder'));
					//$this->set('AdvertiserProfiles', $this->common->getAllAdvertiserProfile());
					//$this->loadModel('User');
					//$this->set('salesperson', $this->User->returnUsersSales());
					if(isset($this->data)){
					//create pdf
					  $this->set('package_id',$this->data['AdvertiserOrder']['package_id']);
					  $offer_count = $this->data['AdvertiserOrder']['offer_count'];
					  $this->set('offer_count',$offer_count);
					  for($p=2;$p<=$offer_count;$p++) {
						$this->set('description_'.$p,$this->data['AdvertiserOrder']['description_'.$p]);
						$this->set('title_'.$p,$this->data['AdvertiserOrder']['title_'.$p]);
					  }
					for($e=2;$e<=5;$e++) {
						if(isset($this->data['AdvertiserOrder']['main_offer_dscpt1_'.$e]))	{		
							$this->set('main_offer_dscpt1_'.$e,$this->data['AdvertiserOrder']['main_offer_dscpt1_'.$e]);
						}
						if(isset($this->data['AdvertiserOrder']['main_offer_dscpt2_'.$e]))	{
							$this->set('main_offer_dscpt2_'.$e,$this->data['AdvertiserOrder']['main_offer_dscpt2_'.$e]);
						}
						if(isset($this->data['AdvertiserOrder']['main_offer_dscpt3_'.$e]))	{
							$this->set('main_offer_dscpt3_'.$e,$this->data['AdvertiserOrder']['main_offer_dscpt3_'.$e]);
						}
					}
				  if($this->data['AdvertiserOrder']['processed']!='location_processed') {
				  	$checked = 1;
				  	$this->set('checked',$checked);
				  } else {
				  	$checked = 0;
				  	$this->set('checked',$checked);
				  }
	    	      $this->AdvertiserOrder->set($this->data['AdvertiserOrder']);
			              if (empty($this->data)){
                          		$this->data = $this->AdvertiserOrder->find(array('AdvertiserOrder.id' => $id));
                          }
			              if($this->data['AdvertiserOrder']!=''){
						/*setting error message if validation fails*/
						  $errors = $this->AdvertiserOrder->invalidFields();
						  if(isset($errors) && count($errors)>0) {
							$error = implode('<br>', $errors).'<br />';
						  }	else {
							$error = '';
						  }
						if($this->data['AdvertiserOrder']['processed']!='location_processed') {
								if($this->data['AdvertiserOrder']['credit_name']=='') {
									$error.='Please enter name on credit card.<br />';											
								}
								if($this->data['AdvertiserOrder']['credit_number']=='') {
									$error.='Please enter Credit Card Number.<br />';											
								}
								if($this->data['AdvertiserOrder']['cvv']=='' || !is_numeric($this->data['AdvertiserOrder']['cvv'])) {
									$error.='Please enter valid CVV number.<br />';											
								}
								if($this->data['AdvertiserOrder']['card_exp_month']=='') {
									$error.='Please select credit card expiry month.<br />';											
								}
								if($this->data['AdvertiserOrder']['card_exp_year']=='') {
									$error.='Please select credit card expiry year.<br />';											
								}							
							}
									if(isset($error) && $error!='<br/>' && $error !='') {
									      $this->Session->setFlash($error);  
						             }
					                else{
										  $sid = $this->Auth->user();
										  App::import('model', 'AdvertiserProfile');
										  $this->AdvertiserProfile = new AdvertiserProfile;
										  //here we are checking same email id in database. we will not allow to save same email twice
										  $emailFound = $this->AdvertiserProfile->checkEmail(trim($this->data['AdvertiserOrder']['email']));
										  if(is_array($emailFound) && isset($emailFound[0]['advertiser_profiles']['id']) && $emailFound[0]['advertiser_profiles']['id']!='')
										  {
										 	   $this->Session->setFlash('Email is already exists in database.Please provide another email.');
										  }
										  else {
												if($this->data['AdvertiserOrder']['main_offer_title']== '' && $this->data['AdvertiserOrder']['description_1']=='' && $this->data['AdvertiserOrder']['save_later']!=1) {
													$this->Session->setFlash('Please fill Main Savings Offer.');
												} else {
														$CreditCardTransID = '';
														$ClientTransID ='';
														$TStamp = '';
											 		if($this->data['AdvertiserOrder']['processed']=='manual_process') {
													$this->loadModel('Package');
													$p_price = $this->Package->find('first',array('fields'=>array('Package.setup_price','Package.monthly_price'),'conditions'=>array('Package.id'=>$this->data['AdvertiserOrder']['package_id'])));
													$total_price = ($p_price['Package']['setup_price']+$p_price['Package']['monthly_price']);
													//////////////////// Payment Gateway //////////////////////////			
													ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/Users/keithpalmerjr/Projects/QuickBooks/');
													//error_reporting(E_ALL | E_STRICT);
													//ini_set('display_errors', true);
													require_once (APP.'webroot/quickbooks/QuickBooks.php');
													$dsn = null;
													$path_to_private_key_and_certificate = APP.'webroot/quickbooks/docs/intuit.pem';
													$application_login = INTUIT_APP_LOGIN;
													$connection_ticket = INTUIT_TICKET;
													//$connection_ticket = 'TGT-214-ZfVkTPfjiZZYFg83gYA6Hw';
													$MS = new QuickBooks_MerchantService(
													$dsn, 
													$path_to_private_key_and_certificate, 
													$application_login,
													$connection_ticket);
													//$MS->useTestEnvironment(true);
													$MS->useLiveEnvironment(true);
													$name = NULL;
													//$number = '4427322513320494';
													$number = $this->data['AdvertiserOrder']['credit_number'];
													$expyear =	$this->data['AdvertiserOrder']['card_exp_year'];
													$expmonth = $this->data['AdvertiserOrder']['card_exp_month'];
													$address = NULL;
													$postalcode = NULL;
													$cvv = $this->data['AdvertiserOrder']['cvv'];
													$amount = number_format($total_price,1);
												
													//$amount = 1.0;
													$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
													if ($Transaction = $MS->charge($Card, $amount))
													{
														$trans_result = $Transaction->toArray();
														$CreditCardTransID = $trans_result['CreditCardTransID'];
														$ClientTransID = $trans_result['ClientTransID'];
														$TStamp = $trans_result['TxnAuthorizationStamp'];
														} else	{
															/*$CreditCardTransID = '1';
															$ClientTransID ='';
															$TStamp = '';*/
															$this->Session->setFlash($MS->errorMessage());
															return false;
														}
													}
													$logoname = '';												
											if(($this->data['AdvertiserOrder']['processed']!='manual_process') || ($this->data['AdvertiserOrder']['processed']=='manual_process' && $CreditCardTransID!='')) {
									$saveArrayAdvertiser = array();
									// upload uploaded file
									if($this->data['AdvertiserOrder']['logo']['name']!=""){
									
									$type = $this->data['AdvertiserOrder']['logo']['type'];
									if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif"){			                         
									
									$this->data['AdvertiserOrder']['logo']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['AdvertiserOrder']['logo']['name']);
									$logoname = $this->data['AdvertiserOrder']['logo']['name'];
									$docDestination = APP.'webroot/img/logo/'.$this->data['AdvertiserOrder']['logo']['name']; 
									@chmod(APP.'webroot/img/logo',0777);
									move_uploaded_file($this->data['AdvertiserOrder']['logo']['tmp_name'], $docDestination) or die($docDestination);
									$saveArrayAdvertiser['AdvertiserProfile']['logo'] = $this->data['AdvertiserOrder']['logo']['name'];						
									}else{					
											$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
										}					
									}	  //first inserting record in advertiser order table
									
											$saveArray = array();
											$saveArray['AdvertiserOrder']['payment_status']	= 'pending';
											$saveArray['AdvertiserOrder']['order_status']	= 'pending';
											
											if(isset($this->data['AdvertiserOrder']['salesperson'])) {
												$saveArray['AdvertiserOrder']['salesperson']	= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
												$saveArray['AdvertiserOrder']['salesperson']	= $sid['Admin']['id'];
											  }
											  
											  
											if($this->data['AdvertiserOrder']['processed']=='manual_process') {
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_process']  	= $this->data['AdvertiserOrder']['processed'];
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_id'] 	= $CreditCardTransID;
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_client_id']= $ClientTransID;
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_date']  	= $TStamp;
											  $saveArray['AdvertiserOrder']['payment_status']	= 'approved';
											  $saveArray['AdvertiserOrder']['order_status']	= 'approved';
											  
											} else {
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_name']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_number']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['cvv']  			= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['card_exp_month'] 	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['card_exp_year']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_process']  	= $this->data['AdvertiserOrder']['processed'];
											}
											  
											  $saveArray['AdvertiserOrder']['package_id']   				= $this->data['AdvertiserOrder']['package_id'];
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
											  	$saveArray['AdvertiserOrder']['salesperson']  				= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
											  	$saveArray['AdvertiserOrder']['salesperson']  				= $sid['Admin']['id'];
											  }
											  $saveArray['AdvertiserOrder']['user_group_id']     			= $this->Session->read('Auth.Admin.user_group_id');	  
											  $saveArray['AdvertiserOrder']['save_later']     				= $this->data['AdvertiserOrder']['save_later'];	
											  $saveArrayAdvertiser['AdvertiserProfile']['show_address']    	= $this->data['AdvertiserOrder']['show_address'];	
											  $saveArrayAdvertiser['AdvertiserProfile']['address2']     	= $this->data['AdvertiserOrder']['address2'];	
											  $saveArrayAdvertiser['AdvertiserProfile']['show_address2']    = $this->data['AdvertiserOrder']['show_address2'];											
											  $this->AdvertiserOrder->save($saveArray);
											  
											  //aftre getting last inserted id for advertiser table we are inserting in work order table
											  if($saveArray['AdvertiserOrder']['save_later']!=1) {
											  //--------------------------------------------------------------
												$this->loadModel('Setting');
												$this->loadModel('FrontUser');
												$setvale = $this->Setting->find('first',array('fields'=>array('refer_business_bucks')));
												$bucksprice = $setvale['Setting']['refer_business_bucks'];
												//bucks management
												$this->loadModel('ReferredBusiness');
													$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.phone'=>$this->data['AdvertiserOrder']['phoneno'],'ReferredBusiness.status'=>'no')));
												
												if($this->data['AdvertiserOrder']['phoneno2']!='' && empty($checkRefer)) {
													$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.phone'=>$this->data['AdvertiserOrder']['phoneno2'],'ReferredBusiness.status'=>'no')));
												}
												if(is_array($checkRefer) && !empty($checkRefer)) {					
													$savearr['ReferredBusiness']['id'] = $checkRefer['ReferredBusiness']['id'];
													$savearr['ReferredBusiness']['status'] = 'yes';
													$savearr['ReferredBusiness']['bucks'] = $bucksprice;
													$savearr['ReferredBusiness']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
													$savearr['ReferredBusiness']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
													$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
													$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;	
													$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
													$this->ReferredBusiness->save($savearr);
													$this->FrontUser->save($savearr);
													$this->loadModel('Buck');
													$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$this->data['AdvertiserOrder']['county'],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
													if(is_array($checkBuck) && count($checkBuck)) {
														$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
														$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
													} else {
														$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
														$saveBuck['Buck']['county_id'] = $this->data['AdvertiserOrder']['county'];
														$saveBuck['Buck']['bucks'] = $bucksprice;
														$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
													}
													$this->Buck->save($saveBuck);
												}
										}
											 //aftre getting last inserted id for advertiser table we are inserting in advertiser profile table
											  $saveArrayAdvertiser['AdvertiserProfile']['name']   	    	=  $this->data['AdvertiserOrder']['advertiser_name'];
											  $saveArrayAdvertiser['AdvertiserProfile']['company_name']     = $this->data['AdvertiserOrder']['company_name'];
											  $saveArrayAdvertiser['AdvertiserProfile']['email']   	        =  $this->data['AdvertiserOrder']['email'];
											  $saveArrayAdvertiser['AdvertiserProfile']['address']  		= $this->data['AdvertiserOrder']['address'];
											  $saveArrayAdvertiser['AdvertiserProfile']['city']   	        =  $this->data['AdvertiserOrder']['city'];
											  $saveArrayAdvertiser['AdvertiserProfile']['county']  		    = $this->data['AdvertiserOrder']['county'];
											  $saveArrayAdvertiser['AdvertiserProfile']['all_cities'] 		= $this->data['AdvertiserOrder']['all_cities'];
											  $saveArrayAdvertiser['AdvertiserProfile']['website']  	    =  $this->data['AdvertiserOrder']['website'];
											  $saveArrayAdvertiser['AdvertiserProfile']['country']   	    =  840;
											  $saveArrayAdvertiser['AdvertiserProfile']['zip']  			= $this->data['AdvertiserOrder']['zip'];
											  $saveArrayAdvertiser['AdvertiserProfile']['phoneno2']  		= $this->data['AdvertiserOrder']['phoneno2'];
											  $saveArrayAdvertiser['AdvertiserProfile']['city2']  			= $this->data['AdvertiserOrder']['city2'];
											  $saveArrayAdvertiser['AdvertiserProfile']['zip2']  			= $this->data['AdvertiserOrder']['zip2'];
											  date_default_timezone_set('US/Eastern');
											  $saveArrayAdvertiser['AdvertiserProfile']['contract_date']  	= strtotime($this->data['AdvertiserOrder']['contract_date']);
											 	
												
												$lastOrderId = $this->AdvertiserOrder->getLastInsertId();											
												$fileName = '';
												// signature
												if($this->data['AdvertiserOrder']['processed']!='location_processed') {	
												
																								
												if(isset($_POST['output']) && $_POST['output']!=''){
														$img = $this->sigJsonToImage($_POST['output']);
								
														$fileName = time()."_".$lastOrderId."-signature.png";
								
														$filePath = WWW_ROOT."Signature/".$fileName;
								
														imagepng($img, $filePath);
								
														imagedestroy($img);						
								
													}else{ 
								
														$img = imagecreatetruecolor(400, 30);
								
														$bgColour = imagecolorallocate($img, 0xff, 0xff, 0xff);
								
														$penColour = imagecolorallocate($img, 0x14, 0x53, 0x94);
								
														imagefilledrectangle($img, 0, 0, 399, 29, $bgColour);
								
														$text = $this->data['AdvertiserOrder']['name'];
								
														$font = WWW_ROOT.'journal.ttf';
								
														imagettftext($img, 20, 0, 10, 20, $penColour, $font, $text);
								
														// Save to file
								
														$fileName = time()."_".$lastOrderId."-signature.png";
								
														$filePath = WWW_ROOT."Signature/".$fileName;
								
														imagepng($img, $filePath);
								
														imagedestroy($img);
								
													}
												}
												
												/*pr($this->data);
												exit;*/
											  $saveArrayAdvertiser['AdvertiserProfile']['fax']  			= $this->data['AdvertiserOrder']['fax'];
											  $saveArrayAdvertiser['AdvertiserProfile']['modifier']  		= $this->Session->read('Auth.Admin.id');
											  
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
											  	$saveArrayAdvertiser['AdvertiserProfile']['creator']		= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
											  	$saveArrayAdvertiser['AdvertiserProfile']['creator']		= $sid['Admin']['id'];
											  }
											  $saveArrayAdvertiser['AdvertiserProfile']['currency']  		= $this->data['AdvertiserOrder']['currency'];
											  $saveArrayAdvertiser['AdvertiserProfile']['publish']  		= 'no';
											  $saveArrayAdvertiser['AdvertiserProfile']['facebook']  		= $this->data['AdvertiserOrder']['facebook'];
											  $saveArrayAdvertiser['AdvertiserProfile']['twitter']  		= $this->data['AdvertiserOrder']['twitter'];
											  $saveArrayAdvertiser['AdvertiserProfile']['order_id']  		= $lastOrderId;
											  $saveArrayAdvertiser['AdvertiserProfile']['signature']  		= $fileName;
											  //pr($saveArrayAdvertiser);
											  $this->AdvertiserProfile->save($saveArrayAdvertiser);									   
											  $ad_id_latest = $this->AdvertiserProfile->getLastInsertId();
											  
											  /*------------to set the multiple category and subcategory------------------*/
												$this->loadModel('AdvertiserCategory');
												foreach($this->data['AdvertiserOrder']['subcategory'] as $pair) {
													$break = explode('-',$pair);
													$catSubcat = $this->common->returnCatSubcatId($break[0],$break[1]);
													if($catSubcat) {
														$save = '';
														$save['AdvertiserCategory']['id'] = '';
														$save['AdvertiserCategory']['advertiser_profile_id'] = $ad_id_latest;
														$save['AdvertiserCategory']['categories_subcategory_id'] = $catSubcat;
														$this->AdvertiserCategory->save($save,false);
													}
												}
					
					
					
											 //$this->loadModel('FrontUser');	
									if($saveArray['AdvertiserOrder']['save_later']!=1) {
												  App::import('model', 'FrontUser');
												  $this->FrontUser = new FrontUser;
												 $arr = array();
												 $password = $this->common->randomPassword(8);
												 $arr['FrontUser']['password'] = $this->Auth->password($password);
												 $arr['FrontUser']['realpassword'] = $password;
												 $arr['FrontUser']['name'] 		= $this->data['AdvertiserOrder']['advertiser_name'];
												 $arr['FrontUser']['email'] 	= $this->data['AdvertiserOrder']['email'];
												 $arr['FrontUser']['status'] 	= 'yes';
												 $arr['FrontUser']['county_id'] = $this->data['AdvertiserOrder']['county'];		
												 $arr['FrontUser']['state_id'] = $this->data['AdvertiserOrder']['state'];			
												 $arr['FrontUser']['advertiser_profile_id'] = $ad_id_latest;
												 $arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
												 $this->FrontUser->save($arr);
												 //$this->sendUsernamePassword($this->data['AdvertiserOrder']['email'],$password);
											 }											 
											$phoneAdvertiser=$this->data['AdvertiserOrder']['phoneno'];	
											$this->AdvertiserProfile->query("UPDATE advertiser_profiles SET phoneno='".$phoneAdvertiser."', state='".$this->data['AdvertiserOrder']['state']."' WHERE id=$ad_id_latest");
											App::import('model', 'SavingOffer');
										  	 $this->SavingOffer = new SavingOffer;
											 //pr($this->data);
											 $county_id = $this->data['AdvertiserOrder']['county'];	
											 
											 $advertiser_profile_id = $this->AdvertiserProfile->getlastinsertid();
												$pdf_name = 'order_'.time().''.$advertiser_profile_id.'.pdf';
												
												 //aftre getting last inserted id for advertiser table we are inserting in work order table
											if($saveArray['AdvertiserOrder']['save_later']!=1) {
												//----------save the instance of order, when new order is placed (Start)------//
												App::import('model', 'OrderInstance');
												$this->OrderInstance = new OrderInstance;
												$saveInstanceArray = array();
												$saveInstanceArray['OrderInstance']['advertiser_order_id']   =  $lastOrderId;
												$saveInstanceArray['OrderInstance']['advertiser_profile_id']  =  $advertiser_profile_id;
												$saveInstanceArray['OrderInstance']['package_id']   	=  $this->data['AdvertiserOrder']['package_id'];
												$saveInstanceArray['OrderInstance']['insert_status']   	=  4;
												$this->OrderInstance->save($saveInstanceArray);
												//----------save the instance of order, when new order is placed (End)------//
											}
											if($saveArray['AdvertiserOrder']['save_later']!=1) {
												  App::import('model', 'WorkOrder');
												  $this->WorkOrder = new WorkOrder;
												  $saveWorkArray = array();
												  $order_id = $this->AdvertiserOrder->getLastInsertId();
												  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $order_id;
												  $saveWorkArray['WorkOrder']['read_status']   				=  0;
												  $saveWorkArray['WorkOrder']['subject']   					=  'New work order Generated';
												  $saveWorkArray['WorkOrder']['message']	=	'A new work order has been placed recently.Order detail is below:';
												  $saveWorkArray['WorkOrder']['type']   					=  'workorder';
												  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
												  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
												  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');								
												  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can go further and add other details about this advertiser in advertiser profiles section like saving offers , vip offers etc. OR You can view pdf file of sales order sheet. Just <a href="'.FULL_BASE_URL.router::url('/',false).'files/pdf/'.$pdf_name.'" style="color:white" target="_blank">Click Here for PDF</a>';
												  
												  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
													$saveWorkArray['WorkOrder']['salseperson_id']		= $this->data['AdvertiserOrder']['salesperson'];
												  } else {
													$saveWorkArray['WorkOrder']['salseperson_id']		= $sid['Admin']['id'];
												  }
												  
												  date_default_timezone_set('US/Eastern');
											  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
												  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
											   	  $this->WorkOrder->save($saveWorkArray);
												  
												  
												   $saveWorkArray = '';
												  $saveWorkArray['WorkOrder']['id']   						=  '';
												  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $order_id;
												  $saveWorkArray['WorkOrder']['read_status']   				=  0;
												  $saveWorkArray['WorkOrder']['subject']   					=  'New Contract';
												  $saveWorkArray['WorkOrder']['message']   				=  'A new Contract has been placed recently. details are below:';
												  $saveWorkArray['WorkOrder']['type']   					=  'Contract';
												  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
												  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
												  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
												  $saveWorkArray['WorkOrder']['bottom_line']   				=  'The Advertiser is currently unpublish. As per zuni\'s contract plan, Only admin can publish the profile.';
												  
												  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
													$saveWorkArray['WorkOrder']['salseperson_id']		= $this->data['AdvertiserOrder']['salesperson'];
												  } else {
													$saveWorkArray['WorkOrder']['salseperson_id']		= $sid['Admin']['id'];
												  }
												  date_default_timezone_set('US/Eastern');
												  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
												  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
												  $this->WorkOrder->save($saveWorkArray);
											  }	 
											 
											 									
											if($saveArray['AdvertiserOrder']['save_later']==1) {
												$live = 0;
											} else {
												$live = 1;
											}
											 for($i=1;$i<=$offer_count;$i++) {
											 if(isset($this->data['AdvertiserOrder']['description_'.$i])) {											
												if($i==1) {
														$title = $this->data['AdvertiserOrder']['main_offer_title'];
														$off = '';
														$description = $this->data['AdvertiserOrder']['description_1'];
														//$off = $this->data['AdvertiserOrder']['main_offer_discount'];
														$current_saving_offer = 1;
														$other_saving_offer = 0;
														$disclaimer = $this->data['AdvertiserOrder']['main_offer_dscpt1'].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt2'].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt3'];
												}else {
														$title = $this->data['AdvertiserOrder']['title_'.$i];
														$description = $this->data['AdvertiserOrder']['description_'.$i];
														$off = '';
														$current_saving_offer = 0;
														$other_saving_offer = 1;
														$disclaimer = $this->data['AdvertiserOrder']['main_offer_dscpt1_'.$i].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt2_'.$i].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt3_'.$i];
												}										
												
												$category = '';
												$subcategory ='';
												$description = $this->data['AdvertiserOrder']['description_'.$i];
												$offer_start_date =  '';
												$offer_expiry_date = '';
												$no_valid_other_offer = '';
												$no_transferable = '';
												$other = '';
												if(isset($this->data['AdvertiserOrder']['not_valid_other'.$i])) {
													$no_valid_other_offer = $this->data['AdvertiserOrder']['not_valid_other'.$i];
												}
												if(isset($this->data['AdvertiserOrder']['n_transferable'.$i])) {
													$no_transferable = $this->data['AdvertiserOrder']['n_transferable'.$i];
												}
												if(isset($this->data['AdvertiserOrder']['other'.$i])) {
													$other = $this->data['AdvertiserOrder']['other'.$i];
												}
											}											
											$this->SavingOffer->query("INSERT INTO saving_offers (current_saving_offer, other_saving_offer, title, off,  advertiser_profile_id, advertiser_county_id, description, offer_start_date, offer_expiry_date, no_valid_other_offer, no_transferable, other, disclaimer, live) VALUES ('$current_saving_offer', '$other_saving_offer', '$title', '$off', '$advertiser_profile_id', '$county_id', '$description', '$offer_start_date', '$offer_expiry_date', '$no_valid_other_offer', '$no_transferable', '$other', '$disclaimer', '$live')");
											//$this->SavingOffer->save($saveSavingOffer);
										}/*
										echo $offer_count;
										pr($saveSavingOffer);
										exit;*/
											 //getAdminEmail//getSalesEmail
										
											 
									if(isset( $this->data['AdvertiserOrder']['Vip_title']) &&  $this->data['AdvertiserOrder']['Vip_title']!='') {
									   App::import('model', 'VipOffer');
										$this->VipOffer = new VipOffer;
										$this->VipOffer->deleteAll(array('VipOffer.advertiser_profile_id'=>$ad_id_latest));
											 //$vipoffer['VipOffer']['off'] = $this->data['AdvertiserOrder']['main_offer_discount'];
											 $vipoffer['VipOffer']['description'] = $this->data['AdvertiserOrder']['main_offer_discount'];
											 $vipoffer['VipOffer']['advertiser_profile_id'] = $ad_id_latest;
											 $vipoffer['VipOffer']['title'] = $this->data['AdvertiserOrder']['Vip_title'];
											 $vipoffer['VipOffer']['advertiser_county_id'] = $this->data['AdvertiserOrder']['county'];
											 $vipoffer['VipOffer']['category'] = $this->data['AdvertiserOrder']['Vip_Category'];
											 $vipoffer['VipOffer']['status'] = 'yes';
											 
											 $this->VipOffer->save($vipoffer);
											 }
											 $signature = 'No Signature';
											 if($fileName!='') {
											 	$signature = '<img src="'.FULL_BASE_URL.router::url('/',false).'Signature/'.$fileName.'" />';
											 }
												// Here we are sending email to advertiser for notification that his/he order has been placed at Zuni.com
											if($saveArray['AdvertiserOrder']['save_later']!=1) {
											
												App::import('model', 'Setting');
	    										$this->Setting = new Setting;
												$emailArray = $this->Setting->getAdvertiserEmailData();
												$package_name =   $this->common->getAllPackage(2);
												$package_price =   $this->common->getAllPackage(3);
												$bodyData = $this->Setting->replaceUserMarkers($emailArray[0]['settings']['new_advertiser_body'],$this->data['AdvertiserOrder']['advertiser_name'],$package_name[$this->data['AdvertiserOrder']['package_id']],$this->data['AdvertiserOrder']['company_name'],$package_price[$this->data['AdvertiserOrder']['package_id']],$this->AdvertiserOrder->getlastinsertid(),$password,$signature);
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = $this->emailhtml->email_header($county_id);
												$this->body .=$bodyData;
												$this->body .= $this->emailhtml->email_footer($county_id);											
												$this->set('var1',$this->data['AdvertiserOrder']['email']);
												$this->set('var2',$emailArray[0]['settings']['new_advertiser_subject']);
												$this->set('var3',$this->common->getReturnEmail());
												$this->set('var4',$this->common->getFromName().' <'.$this->common->getSalesEmail().'>');
												$this->set('var5',$this->body);											
											//create pdf
												$this->set('fileName',$fileName);
												$this->set('logoname',$logoname);
												$this->set('name',$pdf_name);
										
										if($this->Session->read('Auth.Admin.user_group_id')==1) {
											$this->set('redirectUrl',FULL_BASE_URL.router::url('/',false).'advertiser_profiles/thanksPage/'.$advertiser_profile_id);
										} else {
											$this->set('redirectUrl',FULL_BASE_URL.router::url('/',false).'advertiser_orders');
										}
										
												$this->set('Email',$this->Email);						
												$this->layout = 'pdf';
												$this->set('common',$this->common);
												$this->render('/advertiser_orders/pdf');
									}
										if($saveArray['AdvertiserOrder']['save_later']==1) {
											 	//$this->Session->setFlash('Your order has been submitted successfully.');
											 	//$this->redirect(array('controller'=>'advertiser_profiles','action' => 'thanksPage',$advertiser_profile_id));
											//} else {
												$this->Session->setFlash('Your order has been saved successfully.');
											 	$this->redirect(array('action' => "savedOrder"));
											}
										 }
									 }
								}
						 }
				   }
             }
		}

		function sendUsernamePassword($email,$password) {
			$subject 		= 'Welcome to Zuni';
			$bodyText 		= 'Thanks for register in Zuni.<br />
								Your login Password is :<br />
								Password : '.$password.'<br />
								<br />Thanks<br />Zuni Admin Team';
			//ADMINMAIL id
			$this->Email->to 		= $email;
			$this->Email->subject 	= strip_tags($subject);
			$this->Email->replyTo 	= $this->common->getReturnEmail();
			$this->Email->from 		= $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
			$this->Email->sendAs 	= 'html';
			//Set the body of the mail as we send it.
			//seperate line in the message body.
			
			$this->body = '';
			$this->body = $this->emailhtml->email_header();
			$this->body .=$bodyText;
			$this->body .= $this->emailhtml->email_footer();
												
												
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
			$this->common->sentMailLog($this->common->getSalesEmail(),$email,strip_tags($subject),$this->body,"send_username_and_password");
			/////////////////////////////////////////////////////////////////////////

		}		   
	   // show data in edit advertiser order form
	   function advertiserOrderEditDetail($id=null){
	         $this->set('AdvertiserOrder',$this->AdvertiserOrder->advertiserOrderEditDetail($id));
			 $this->set('package_name',$this->common->getAllPackage(2));
			 $this->set('package_price',$this->common->getAllPackage(3));
			 $this->loadModel('User');
			 $this->set('salesperson', $this->User->returnUsersSales());
			 $this->loadModel('AdvertiserProfile');
			 $this->set('advertiserProfile', $this->AdvertiserProfile->getAdvertiserDetail($id));
	    }

/************************** function to confirm to delete advertiser profile ******************************/
	function confirmDelete($order_id) {
		$this->id = $order_id;
		$this->set('order_id',$order_id);
	}
	/************************** function to confirm to delete advertiser profile ******************************/
	function savedDelete($order_id) {
		$this->id = $order_id;
		$this->set('order_id',$order_id);
	}	
		//delete category data in database
	   function advertiserOrderDelete($order_id) {				   
	   			 $this->AdvertiserOrder->id = $order_id;
				 $this->loadModel('AdvertiserProfile');
				 $advertiser_id = $this->AdvertiserProfile->field('AdvertiserProfile.id',array('AdvertiserProfile.order_id'=>$order_id));	
				 $this->loadModel('Image');
				 $this->loadModel('Video');
				 $this->loadModel('WorkOrder');
				 $this->loadModel('DailyDeal');
				 $this->loadModel('DailyDiscount');
				 $this->loadModel('SavingOffer');
				 $this->loadModel('VipOffer');
				 $this->loadModel('TopTenBusiness');
				 $this->loadModel('Voucher');
				 $this->loadModel('FrontUser');
				 $this->loadModel('OrderInstance');
				 
				 
				$images = $this->Image->find('all',array('conditions'=>array('Image.advertiser_profile_id'=>$advertiser_id)));
			   	$video = $this->Video->find('first',array('conditions'=>array('Video.advertiser_profile_id'=>$advertiser_id)));

			    if(count($images) > 0 ){
				   foreach($images as $images){
					unlink(APP.'webroot/img/gallery/'.$images['Image']['image_thumb']);
					unlink(APP.'webroot/img/gallery/'.$images['Image']['image_big']);
					}
			  	 }
				  if(isset($video['Video']['file_name']) && $video['Video']['file_name'] !=''){
						unlink(APP.'webroot/img/video/'.$video['Video']['file_name']);
				  }				  
				 $this->Image->deleteAll(array('Image.advertiser_profile_id'=>$advertiser_id));
				 $this->Video->deleteAll(array('Video.advertiser_profile_id'=>$advertiser_id));					 
				 $this->WorkOrder->deleteAll(array('WorkOrder.advertiser_order_id'=>$order_id));			 				 
				 $this->DailyDeal->deleteAll(array('DailyDeal.advertiser_profile_id'=>$advertiser_id));				 
				 $this->DailyDiscount->deleteAll(array('DailyDiscount.advertiser_profile_id'=>$advertiser_id));	
				 $this->SavingOffer->deleteAll(array('SavingOffer.advertiser_profile_id'=>$advertiser_id));	
				 $this->VipOffer->deleteAll(array('VipOffer.advertiser_profile_id'=>$advertiser_id));	
				 $this->TopTenBusiness->deleteAll(array('TopTenBusiness.advertiser_profile_id'=>$advertiser_id));
				 $this->Voucher->deleteAll(array('Voucher.advertiser_profile_id'=>$advertiser_id));				 			 
				 $this->AdvertiserProfile->deleteAll(array('AdvertiserProfile.order_id'=>$order_id));
				 $this->FrontUser->deleteAll(array('FrontUser.advertiser_profile_id'=>$advertiser_id));
				$this->OrderInstance->deleteAll(array('OrderInstance.advertiser_order_id'=>$order_id)); 
				 
			 	 $this->AdvertiserOrder->delete($order_id);
			     $this->Session->setFlash('The Advertiser Order detail with id: '.$order_id.' has been deleted.');
				 	   if(strpos($this->referer(),'savedDelete')) {
							$this->redirect(array('action'=>'savedOrder'));
					   }
					   else {
							$this->redirect(array('action'=>'index'));
					   }
	   }	   
 // adding new AdvertiserOrder in database
	    function savedOrder(){
			/*if(isset($this->data)) {
				pr($this->data);
			}*/
             //variable for display number of AdvertiserOrder name per page	
	            $condition='';
				$loginDetail = $this->Auth->user();
				$this->set('currentAdmin', $this->Auth->user());
				$this->set('commissionPercent', '');
				$this->set('totalCommission', '');
				$this->set('paymentStatusSearch', ''); 
			    $this->set('paymentMethodSearch', '');  
			    $this->set('packageSearch', '');
				$this->set('advertiser_search', '');
			    $this->set('s_date', '');
				$this->set('e_date', '');
				
				$this->set('StatesList',$this->common->getAllState());  //  List states
			   $this->set('CitiesList',$this->common->getAllCity());   //  List cities
			   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
			   $this->set('CountriesList',$this->common->getAllCountry()); //  List countries
			   $this->set('categoryList',$this->common->getAllCategory()); //  List categories
			   $this->set('subCategoryList',$this->common->getAllSubCategory()); //  List Subcategories
			   $this->set('Packages', $this->common->getAllPackage(1));
			   $this->set('common',$this->common);
			   $this->set('SelsePersons',$this->common->getAllSelsePerson(5));
			   $this->set('UserGroup',$this->common->getAllUserGroup());
				$this->set('company_name','Company Name');
			   $this->set('city','');
			   $this->set('state','');
			   $this->set('county','');
			   $this->set('category','');
			   $this->set('package_id','');
			   $this->set('salse_id','');
			   $this->set('group_id','');
			   $this->set('publish','');
		   
		   				
			
				$cond = array();
				$this->set('Packages', $this->common->getAllPackage(1));
				$this->set('PackagesName', $this->common->getAllPackage(2));
				$this->set('PackagesPrice', $this->common->getAllPackage(3));
				$this->set('AdvertiserProfiles', $this->common->getAllAdvertiserProfileForOrderListing());
				
				$this->loadModel('User');
				$this->set('salesperson', $this->User->returnUsersSales());

			 if($loginDetail['Admin']['user_group_id']==1 or  $loginDetail['Admin']['user_group_id']==4)
			  {
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'AdvertiserOrder.id' => 'desc' ));
			  }else{
			  	$this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'AdvertiserOrder.id' => 'desc'),'conditions'=>array('AdvertiserOrder.salesperson'=>$loginDetail['Admin']['id']));
			  }
			  if(isset($this->data['AdvertiserOrder']['company_name']) && $this->data['AdvertiserOrder']['company_name']=='Company Name') {
			  		$this->data['AdvertiserOrder']['company_name'] = '';
			  }
			  
			  if($this->data['AdvertiserOrder']['company_name']) {
			    $cond[] = 'AdvertiserProfile.company_name LIKE "%' .$this->data['AdvertiserOrder']['company_name']. '%"';
			    (empty($this->params['named'])) ? $this->set('company_name', $this->data['AdvertiserOrder']['company_name']) :$this->set('company_name', $this->data['named']['company_name']) ;
			  }				
				
			if($this->data['AdvertiserOrder']['county']) {
			   $cond[] = 'AdvertiserProfile.county = '.$this->data['AdvertiserOrder']['county'];
			  (empty($this->params['named'])) ? $this->set('county', $this->data['AdvertiserOrder']['county']) :$this->set('county', $this->data['named']['county']) ;
			}						 
			
			
			if($this->data['AdvertiserOrder']['city']) {
			   $cond[] = 'AdvertiserProfile.city = '.$this->data['AdvertiserOrder']['city'];
			  (empty($this->params['named'])) ? $this->set('city', $this->data['AdvertiserOrder']['city']) :$this->set('city', $this->data['named']['city']) ;
			}
			
			if($this->data['AdvertiserOrder']['state']) {
			   $cond[] = 'AdvertiserProfile.state = '.$this->data['AdvertiserOrder']['state'];
			  (empty($this->params['named'])) ? $this->set('state', $this->data['AdvertiserOrder']['state']) :$this->set('state', $this->data['named']['state']) ;
			}
			
			if($this->data['AdvertiserOrder']['category']) {
		  			$cat = $this->data['AdvertiserOrder']['category'];
		  			$this->loadModel('AdvertiserCategory');
					$cats_pair = explode('-',$cat);
					
					$data = $this->AdvertiserCategory->find('all',array('fields'=>'AdvertiserCategory.advertiser_profile_id','conditions'=>array('CategoriesSubcategory.category_id'=>$cats_pair[0],'CategoriesSubcategory.subcategory_id'=>$cats_pair[1])));
					$profile = '';
					if(!empty($data)) {
						foreach($data as $data) {
							$profile[] = $data['AdvertiserCategory']['advertiser_profile_id'];
						}
					}
					if(is_array($profile)) {
						$profile_ids = implode(',',array_values(array_filter($profile)));
					} else {
						$profile_ids = 0;
					}
					$cond[] = 'AdvertiserProfile.id IN ('.$profile_ids.')';
			  		(empty($this->params['named'])) ? $this->set('category', $this->data['AdvertiserOrder']['category']) :$this->set('category', $this->data['named']['category']) ;
			}			
			
			if($this->data['AdvertiserOrder']['publish']) {
				  $cond[] = 'AdvertiserProfile.publish = "'.$this->data['AdvertiserOrder']['publish'].'"';	
				  (empty($this->params['named'])) ? $this->set('publish', $this->data['AdvertiserOrder']['publish']) :$this->set('publish', $this->data['named']['publish']) ;
			}
		
		 if($this->data['AdvertiserOrder']['package_id']) {
				 $cond[] = 'AdvertiserOrder.package_id = '.$this->data['AdvertiserOrder']['package_id'];
				  (empty($this->params['named'])) ? $this->set('package_id', $this->data['AdvertiserOrder']['package_id']) :$this->set('package_id', $this->data['named']['package_id']) ;
			}		
		
		if($this->data['AdvertiserOrder']['salse_id']) {
				$cond[] = 'AdvertiserOrder.salesperson = '.$this->data['AdvertiserOrder']['salse_id'];
				  (empty($this->params['named'])) ? $this->set('salse_id', $this->data['AdvertiserOrder']['salse_id']) :$this->set('salse_id', $this->data['named']['salse_id']) ;
			}	
					
		if($this->data['AdvertiserOrder']['group_id']) {
				$cond[] = 'AdvertiserOrder.user_group_id = '.$this->data['AdvertiserOrder']['group_id'];
				  (empty($this->params['named'])) ? $this->set('group_id', $this->data['AdvertiserOrder']['group_id']) :$this->set('group_id', $this->data['named']['group_id']) ;
			}
				if(!empty($this->params['named'])){
				     if(isset($this->params['named']['company_name'] )){
					   $cond[] = 'AdvertiserProfile.company_name LIKE "%' .$this->params['named']['company_name']. '%"';
					   $this->set('company_name', $this->params['named']['company_name']);
					 }					 
				if(isset($this->params['named']['county'] )){
						$cond[] = 'AdvertiserProfile.county = '.$this->params['named']['county'];
					   $this->set('county', $this->params['named']['county']);
					 }
				if(isset($this->params['named']['city'] )){
						$cond[] = 'AdvertiserProfile.city = '.$this->params['named']['city'];
					   $this->set('city', $this->params['named']['city']);
					 }
				if(isset($this->params['named']['state'] )){
						$cond[] = 'AdvertiserProfile.state = '.$this->params['named']['state'];
					   $this->set('state', $this->params['named']['state']);
					 }	 
				if(isset($this->params['named']['category'] )){
						$cat = $this->params['named']['category'];
						$this->loadModel('AdvertiserCategory');
						$cats_pair = explode('-',$cat);
						
						$data = $this->AdvertiserCategory->find('all',array('fields'=>'AdvertiserCategory.advertiser_profile_id','conditions'=>array('CategoriesSubcategory.category_id'=>$cats_pair[0],'CategoriesSubcategory.subcategory_id'=>$cats_pair[1])));
						$profile = '';
						if(!empty($data)) {
							foreach($data as $data) {
								$profile[] = $data['AdvertiserCategory']['advertiser_profile_id'];
							}
						}
						if(is_array($profile)) {
							$profile_ids = implode(',',array_values(array_filter($profile)));
						} else {
							$profile_ids = 0;
						}
						$cond[] = 'AdvertiserProfile.id IN ('.$profile_ids.')';
					   $this->set('category', $this->params['named']['category']);
					 }					 
				if(isset($this->params['named']['publish'] )){
					    $cond[] = 'AdvertiserProfile.publish = "'.$this->params['named']['publish'].'"';	
					   $this->set('publish', $this->params['named']['publish']);
					 }					 
				if(isset($this->params['named']['package_id'] )){
					   $cond[] = 'AdvertiserOrder.package_id = '.$this->params['named']['package_id'];
					   $this->set('package_id', $this->params['named']['package_id']);
					 }
				if(isset($this->params['named']['salse_id'] )){					   
					   $cond[] = 'AdvertiserOrder.salesperson = '.$this->params['named']['salse_id'];
					   $this->set('salse_id', $this->params['named']['salse_id']);
					 }	 
				if(isset($this->params['named']['group_id'] )){					   
					   $cond[] = 'AdvertiserOrder.user_group_id = '.$this->params['named']['group_id'];
					   $this->set('group_id', $this->params['named']['group_id']);
					 }	 					 
				}
				if(isset($cond) && count($cond)>0) {
					$condi =  'AND '.implode(' AND ',$cond).' AND AdvertiserOrder.save_later=1';
				} else {
					$condi = 'AND AdvertiserOrder.save_later=1';
				}
				$ids = '';
				//query to fetch data from both tables
				$result = $this->AdvertiserOrder->query('SELECT AdvertiserOrder.id FROM advertiser_orders as AdvertiserOrder,advertiser_profiles as AdvertiserProfile WHERE AdvertiserOrder.id = AdvertiserProfile.order_id '.$condi);
				//get ids of all orders
				if(is_array($result)) {
					foreach ($result as $result) {
						$ids[] = $result['AdvertiserOrder']['id'];
					}
				}
				//conditions to fetch data from order table from these ids.
				if(is_array($ids)) {
					$condition = array('AdvertiserOrder.id IN ('.implode(',',$ids).')');
				} else {
					$condition = array('AdvertiserOrder.id IN (0)');
				}
			  $data = $this->paginate('AdvertiserOrder', $condition);
		      $this->set('AdvertiserOrders', $data);
	   	}	   
//------------------------------------------------------------------------------//
 // adding new AdvertiserOrder in database	   
	    function editSavedOrder($id){
					$platform = ($this->mobile->isMobile() ? ($this->mobile->isTablet() ? 'tablet' : 'mobile') : 'computer');
					$this->set('platform',$platform);
					if($id) {
							$this->set('save_later',0);
							$this->loadModel('User');
							$this->set('salesperson', $this->User->returnUsersSales());
							$this->set('offer_count',1);
							$this->set('Packages', $this->common->getAdminPackage());
							$this->set('StatesList',$this->common->getAllState());  //  List states
							//$this->set('CitiesList',$this->common->getAllCity());   //  List cities
							//$this->set('CountyList',$this->common->getAllCounty()); //  List counties
							$state_id = '';
							if(isset($this->data)) {
								$state_id = $this->data['AdvertiserOrder']['state'];
							}else {
								$state_id = $this->common->getCompanystate($id);
							}
							$county_id = '';
							if(isset($this->data)) {
								$county_id = $this->data['AdvertiserOrder']['county'];
							}else {
								$county_id = $this->common->getCompanyCounty($id);
							}
							$this->set('county_id',$county_id);
							$this->set('CitiesList',$this->common->getCountyCity($county_id));   //  List cities						
							$this->set('CountyList',$this->common->getAllCountyByState($state_id)); //  List counties
							
							
							$this->set('AllCatSubcat',$this->common->getAllCatSubcatoption('AdvertiserOrder'));
							$this->set('categoryList',$this->common->getAllCategory());
					if(isset($this->data)) {
					$this->set('not_valid_other1','');
					$this->set('n_transferable1','');
					$this->set('other1','');		
					$subcat_array = array();
					if(isset($this->data['AdvertiserOrder']['subcategory']) && count($this->data['AdvertiserOrder']['subcategory'])) {
						foreach($this->data['AdvertiserOrder']['subcategory'] as $catearr) {
							$sucat = '';
							$sucat = explode('-',$catearr);
							$subcat_array[] = $sucat[1];
						}
					}
				$this->set('subcat_array',$subcat_array);				
				if($this->data['AdvertiserOrder']['logo']['error']) {
					$this->set('logo',$this->data['AdvertiserOrder']['old_logo']);
				} else {
					$this->set('logo','');
				}
					  $this->set('package_id',$this->data['AdvertiserOrder']['package_id']);
					  $offer_count = $this->data['AdvertiserOrder']['offer_count'];
					  $this->set('offer_count',$offer_count);
					  for($p=2;$p<=$offer_count;$p++) {
						$this->set('description_'.$p,$this->data['AdvertiserOrder']['description_'.$p]);
						$this->set('title_'.$p,$this->data['AdvertiserOrder']['title_'.$p]);
					 }
					  if($this->data['AdvertiserOrder']['processed']!='location_processed') {
						$checked = 1;
						$this->set('checked',$checked);
					  } else {
						$checked = 0;
						$this->set('checked',$checked);
					  }
					  $this->AdvertiserOrder->set($this->data['AdvertiserOrder']);
			               if (empty($this->data)){
                          		$this->data = $this->AdvertiserOrder->find(array('AdvertiserOrder.id' => $id));
                           }
			               if($this->data['AdvertiserOrder']!=''){
					/*setting error message if validation fails*/
						  $errors = $this->AdvertiserOrder->invalidFields();
						  if(isset($errors) && count($errors)>0) {
							$error = implode('<br>', $errors).'<br />';
						  }	else {
							$error = '';
						  }
						if($this->data['AdvertiserOrder']['processed']!='location_processed') {
								if($this->data['AdvertiserOrder']['credit_name']=='') {
									$error.='Please enter name on credit card.<br />';											
								}
								if($this->data['AdvertiserOrder']['credit_number']=='') {
									$error.='Please enter Credit Card Number.<br />';											
								}
								/*if($this->data['AdvertiserOrder']['cvv']=='' || !is_numeric($this->data['AdvertiserOrder']['cvv'])) {
									$error.='Please enter valid CVV number.<br />';		
								}*/
								if($this->data['AdvertiserOrder']['card_exp_month']=='') {
									$error.='Please select credit card expiry month.<br />';
								}
								if($this->data['AdvertiserOrder']['card_exp_year']=='') {
									$error.='Please select credit card expiry year.<br />';										
								}
							}
									if(isset($error) && $error!='<br/>' && $error !='') {
									      $this->Session->setFlash($error);
						             }
					                else{
										  $sid = $this->Auth->user();
										  App::import('model', 'AdvertiserProfile');
										  $this->AdvertiserProfile = new AdvertiserProfile;
										  //here we are checking same email id in database. we will not allow to save same email twice
										 $emailFound = $this->AdvertiserProfile->checkEmailNid(trim($this->data['AdvertiserOrder']['email']),$this->params['pass'][0]);
								if(is_array($emailFound) && isset($emailFound[0]['advertiser_profiles']['id']) && $emailFound[0]['advertiser_profiles']['id']!='')
										  {
										 	   $this->Session->setFlash('Email is already exists in database.Please provide another email.');					   
										  }
										  else {
												if($this->data['AdvertiserOrder']['main_offer_title']== '' && $this->data['AdvertiserOrder']['description_1']=='' && $this->data['AdvertiserOrder']['save_later']!=1) {
													$this->Session->setFlash('Please fill Main Savings Offer.');
												} else {
												
														$CreditCardTransID = '';
														$ClientTransID ='';
														$TStamp = '';
											 		if($this->data['AdvertiserOrder']['processed']=='manual_process') {
													$this->loadModel('Package');													
													$p_price = $this->Package->find('first',array('fields'=>array('Package.setup_price','Package.monthly_price'),'conditions'=>array('Package.id'=>$this->data['AdvertiserOrder']['package_id'])));
													$total_price = ($p_price['Package']['setup_price']+$p_price['Package']['monthly_price']);
													//--------------------- Payment Gateway Start-----------------------------//	
													
													$authNameArr=explode(' ',$this->data['AdvertiserOrder']['credit_name']);
													
													$auth_fname=$authNameArr[0];
													$auth_lname=$authNameArr[1];
														
													$final_exp_date=$this->data['AdvertiserOrder']['card_exp_month'].$this->data['AdvertiserOrder']['card_exp_year'];
													$amount = number_format($total_price,1);
													
													//$amount = 1.0;
													//----------------Auth.net start------------------//
													$post_url = "https://secure.authorize.net/gateway/transact.dll"; // for live account intergartion
													// $post_url = "https://test.authorize.net/gateway/transact.dll"; // for test account intergartion
								
													$post_values = array(
														
														// the API Login ID and Transaction Key must be replaced with valid values
														"x_login"			=> AUTHORIZE_APP_LOGIN,
														"x_tran_key"		=> AUTHORIZE_TRANSACTION_KEY,
													
														"x_version"			=> "3.1",
														"x_delim_data"		=> "TRUE",
														"x_delim_char"		=> "|",
														"x_relay_response"	=> "FALSE",
													
														"x_type"			=> "AUTH_CAPTURE",
														"x_method"			=> "CC",
														"x_card_num"		=> $this->data['AdvertiserOrder']['credit_number'],
														"x_exp_date"		=> $final_exp_date,
													
														"x_amount"			=> $amount,
														"x_description"		=> "",
													
														"x_first_name"		=> $auth_fname,
														"x_last_name"		=> $auth_lname,
														"x_address"			=> "",
														"x_state"			=> "",
														"x_zip"				=> ""
														// Additional fields can be added here as outlined in the AIM integration
														// guide at: http://developer.authorize.net
													);
													
													$post_string = "";
													foreach( $post_values as $key => $value )
														{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
													$post_string = rtrim( $post_string, "& " );
													
													$request = curl_init($post_url); // initiate curl object
													curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
													curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
													curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
													curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
													$post_response = curl_exec($request); // execute curl post and store results in $post_response
													// additional options may be required depending upon your server configuration
													// you can find documentation on curl options at http://www.php.net/curl_setopt
													curl_close ($request); // close curl object
												
													// This line takes the response and breaks it into an array using the specified delimiting character
													$response_array = explode($post_values["x_delim_char"],$post_response);
													
													
													//----------------Auth.net end------------------//																		
													
													if(isset($response_array[0]) && $response_array[0]!='' && $response_array[0]=='1')
													{
														date_default_timezone_set('US/Eastern');
														
														$TStamp = mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
														
														$CreditCardTransID = $response_array[6];
														$ClientTransID = $response_array[7];
													
													}else{
														$CreditCardTransID = '';
														$ClientTransID ='';
														$TStamp = '';
														$this->Session->setFlash("Payment Gateway Error : ".$response_array[3]);
														return false;
													}
													
													
													
													//--------------------- Payment Gateway End-----------------------------//
													
													
															
													
													}
													$logoname = '';
											if(($this->data['AdvertiserOrder']['processed']!='manual_process') || ($this->data['AdvertiserOrder']['processed']=='manual_process' && $CreditCardTransID!='')) {
											 	
									$saveArrayAdvertiser = array();
									// upload uploaded file
									if($this->data['AdvertiserOrder']['logo']['name']!=""){
									$type = $this->data['AdvertiserOrder']['logo']['type'];
									if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif"){				                         
									
									$this->data['AdvertiserOrder']['logo']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['AdvertiserOrder']['logo']['name']);

									$logoname = $this->data['AdvertiserOrder']['logo']['name'];
									$docDestination = APP.'webroot/img/logo/'.$this->data['AdvertiserOrder']['logo']['name']; 
									@chmod(APP.'webroot/img/logo',0777);
									move_uploaded_file($this->data['AdvertiserOrder']['logo']['tmp_name'], $docDestination) or die($docDestination);
									$saveArrayAdvertiser['AdvertiserProfile']['logo'] = $this->data['AdvertiserOrder']['logo']['name'];						
									}else{					
											$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
										}					
									}	  //first inserting record in advertiser order table
									$advertiser_id = $this->params['pass'][0];
									$order_id = $this->common->getonlyOrderId($advertiser_id);									
											  	$saveArray = array();
											  	$saveArray['AdvertiserOrder']['payment_status']	= 'pending';
												$saveArray['AdvertiserOrder']['order_status']	= 'pending';												
												
												$fileName = '';
												// signature
												if($this->data['AdvertiserOrder']['processed']!='location_processed') {
																								
												if(isset($_POST['output']) && $_POST['output']!=''){
														$img = $this->sigJsonToImage($_POST['output']);
								
														$fileName = time()."_".$order_id."-signature.png";
								
														$filePath = WWW_ROOT."Signature/".$fileName;
								
														imagepng($img, $filePath);
								
														imagedestroy($img);						
								
													}else{ 
								
														$img = imagecreatetruecolor(400, 30);
								
														$bgColour = imagecolorallocate($img, 0xff, 0xff, 0xff);
								
														$penColour = imagecolorallocate($img, 0x14, 0x53, 0x94);
								
														imagefilledrectangle($img, 0, 0, 399, 29, $bgColour);
								
														$text = $this->data['AdvertiserOrder']['name'];
								
														$font = WWW_ROOT.'journal.ttf';
								
														imagettftext($img, 20, 0, 10, 20, $penColour, $font, $text);
								
														// Save to file
								
														$fileName = time()."_".$order_id."-signature.png";
								
														$filePath = WWW_ROOT."Signature/".$fileName;
								
														imagepng($img, $filePath);
								
														imagedestroy($img);
								
													}
												}	
											if($this->data['AdvertiserOrder']['processed']=='manual_process') {
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_process']  	= $this->data['AdvertiserOrder']['processed'];
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_id'] 	= $CreditCardTransID;
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_client_id']= $ClientTransID;
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_date']  	= $TStamp;
											  $saveArray['AdvertiserOrder']['payment_status']	= 'approved';
											  $saveArray['AdvertiserOrder']['order_status']	= 'approved';
											} else {
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_name']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_number']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['cvv']  			= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['card_exp_month'] 	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['card_exp_year']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_process']  	= $this->data['AdvertiserOrder']['processed'];
											}																					  							
											  $saveArray['AdvertiserOrder']['id']   						= $order_id;				
											  $saveArray['AdvertiserOrder']['package_id']   				= $this->data['AdvertiserOrder']['package_id'];
											  
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
												$saveArray['AdvertiserOrder']['salesperson']		= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
												$saveArray['AdvertiserOrder']['salesperson']		= $sid['Admin']['id'];
											  }
											  
											  $saveArrayAdvertiser['AdvertiserProfile']['modifier']  		= $this->Session->read('Auth.Admin.id');
											  
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
												$saveArrayAdvertiser['AdvertiserProfile']['creator']	= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
												$saveArrayAdvertiser['AdvertiserProfile']['creator']	= $sid['Admin']['id'];
											  }
											  $saveArray['AdvertiserOrder']['save_later']     				= $this->data['AdvertiserOrder']['save_later'];	
											  $saveArray['AdvertiserOrder']['user_group_id']     			= $this->Session->read('Auth.Admin.user_group_id');	
											 $saveArrayAdvertiser['AdvertiserProfile']['show_address']     	= $this->data['AdvertiserOrder']['show_address'];	
											 $saveArrayAdvertiser['AdvertiserProfile']['address2']     		= $this->data['AdvertiserOrder']['address2'];	
											 $saveArrayAdvertiser['AdvertiserProfile']['show_address2']     = $this->data['AdvertiserOrder']['show_address2'];
											 $saveArrayAdvertiser['AdvertiserProfile']['signature']  		= $fileName;							
											  $saveArrayAdvertiser['AdvertiserProfile']['phoneno2']  		= $this->data['AdvertiserOrder']['phoneno2'];
											  $saveArrayAdvertiser['AdvertiserProfile']['city2']  			= $this->data['AdvertiserOrder']['city2'];
											  $saveArrayAdvertiser['AdvertiserProfile']['zip2']  			= $this->data['AdvertiserOrder']['zip2'];
											  /*pr($saveArrayAdvertiser);
											  exit;	*/						
											  $this->AdvertiserOrder->save($saveArray);
											  //aftre getting last inserted id for advertiser table we are inserting in work order table
											  if($this->data['AdvertiserOrder']['save_later']!=1) {
											  App::import('model', 'WorkOrder');
										  	  $this->WorkOrder = new WorkOrder;
											  $saveWorkArray = array();
											  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $order_id;
											  $saveWorkArray['WorkOrder']['read_status']   				=  0;
											  $saveWorkArray['WorkOrder']['subject']   					=  'New work order Generated';
											  $saveWorkArray['WorkOrder']['message']	=  'A new work order has been placed recently.Order details are below:';
											  $saveWorkArray['WorkOrder']['type']   					=  'workorder';
											  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
											  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
											  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');								
											  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can go further and add other details about this advertiser in advertiser profiles section like saving offers , vip offers etc.';
											  
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
												$saveWorkArray['WorkOrder']['salseperson_id']	= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
												$saveWorkArray['WorkOrder']['salseperson_id']	= $sid['Admin']['id'];
											  }
											  
											  date_default_timezone_set('US/Eastern');
											  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
											  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
											  $this->WorkOrder->save($saveWorkArray);
											  $saveWorkArray = '';
											  $saveWorkArray['WorkOrder']['id']   						=  '';
											  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $order_id;
											  $saveWorkArray['WorkOrder']['read_status']   				=  0;
											  $saveWorkArray['WorkOrder']['subject']   					=  'New Contract';
									  		  $saveWorkArray['WorkOrder']['message']   				=  'A new Contract has been placed recently. details are below:';
											  $saveWorkArray['WorkOrder']['type']   					=  'Contract';
											  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
											  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
											  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
											  $saveWorkArray['WorkOrder']['bottom_line']   				=  'The Advertiser is currently unpublish. As per zuni\'s contract plan, Only admin can publish the profile.';
											  
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
												$saveWorkArray['WorkOrder']['salseperson_id']	= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
												$saveWorkArray['WorkOrder']['salseperson_id']	= $sid['Admin']['id'];
											  }
											  date_default_timezone_set('US/Eastern');
											  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
											  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
											  
											  $this->WorkOrder->save($saveWorkArray);
											  
											   //--------------------------------------------------------------
												$this->loadModel('FrontUser');
												$this->loadModel('Setting');
												$setvale = $this->Setting->find('first',array('fields'=>array('refer_business_bucks')));
												$bucksprice = $setvale['Setting']['refer_business_bucks'];
												//bucks management
												$this->loadModel('ReferredBusiness');												
												$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.phone'=>$this->data['AdvertiserOrder']['phoneno'],'ReferredBusiness.status'=>'no')));
												
												if($this->data['AdvertiserOrder']['phoneno2']!='' && empty($checkRefer)) {
													$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.phone'=>$this->data['AdvertiserOrder']['phoneno2'],'ReferredBusiness.status'=>'no')));
												}
												if(is_array($checkRefer) && !empty($checkRefer)) {				
													$savearr['ReferredBusiness']['id'] = $checkRefer['ReferredBusiness']['id'];
													$savearr['ReferredBusiness']['status'] = 'yes';
													$savearr['ReferredBusiness']['bucks'] = $bucksprice;
													$savearr['ReferredBusiness']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
													$savearr['ReferredBusiness']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
													$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
													$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;	
													$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
													$this->ReferredBusiness->save($savearr);
													$this->FrontUser->save($savearr);
													$this->loadModel('Buck');
													$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$this->data['AdvertiserOrder']['county'],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
													if(is_array($checkBuck) && count($checkBuck)) {
														$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
														$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
													} else {
														$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
														$saveBuck['Buck']['county_id'] = $this->data['AdvertiserOrder']['county'];
														$saveBuck['Buck']['bucks'] = $bucksprice;
														$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
													}
													$this->Buck->save($saveBuck);
												}
											 }
											 //aftre getting last inserted id for advertiser table we are inserting in advertiser profile table
											  $saveArrayAdvertiser['AdvertiserProfile']['id']   	    	=  $advertiser_id;
											  $saveArrayAdvertiser['AdvertiserProfile']['name']   	    	=  $this->data['AdvertiserOrder']['advertiser_name'];
											  $saveArrayAdvertiser['AdvertiserProfile']['company_name']     = $this->data['AdvertiserOrder']['company_name'];
											  $saveArrayAdvertiser['AdvertiserProfile']['email']   	        =  $this->data['AdvertiserOrder']['email'];
											  $saveArrayAdvertiser['AdvertiserProfile']['address']  		= $this->data['AdvertiserOrder']['address'];
											  $saveArrayAdvertiser['AdvertiserProfile']['city']   	        =  $this->data['AdvertiserOrder']['city'];
											  $saveArrayAdvertiser['AdvertiserProfile']['county']  		    = $this->data['AdvertiserOrder']['county'];
//											  $saveArrayAdvertiser['AdvertiserProfile']['state']  	        = $this->data['AdvertiserOrder']['state'];
											  $saveArrayAdvertiser['AdvertiserProfile']['website']  	    =  $this->data['AdvertiserOrder']['website'];
											  $saveArrayAdvertiser['AdvertiserProfile']['country']   	    =  840;
											  $saveArrayAdvertiser['AdvertiserProfile']['zip']  			= $this->data['AdvertiserOrder']['zip'];
											  $saveArrayAdvertiser['AdvertiserProfile']['all_cities'] 		= $this->data['AdvertiserOrder']['all_cities'];
											  date_default_timezone_set('US/Eastern');
											  $saveArrayAdvertiser['AdvertiserProfile']['contract_date']  	= strtotime($this->data['AdvertiserOrder']['contract_date']);
											  //$saveArrayAdvertiser['AdvertiserProfile']['contract_expiry_date']  	= strtotime($this->data['AdvertiserOrder']['contract_expiry_date']);
												
												
												 /*------------to set the multiple category and subcategory------------------*/
												$this->loadModel('AdvertiserCategory');
												$id = $advertiser_id;
												$this->AdvertiserCategory->deleteAll(array('AdvertiserCategory.advertiser_profile_id'=>$advertiser_id));
												foreach($this->data['AdvertiserOrder']['subcategory'] as $pair) {
													$break = '';
													$break = explode('-',$pair);
													$catSubcat = $this->common->returnCatSubcatId($break[0],$break[1]);
													if($catSubcat) {
														$save = '';
														$save['AdvertiserCategory']['id'] = '';
														$save['AdvertiserCategory']['advertiser_profile_id'] = $advertiser_id;
														$save['AdvertiserCategory']['categories_subcategory_id'] = $catSubcat;
														$this->AdvertiserCategory->save($save,false);
													}
												}
												
											  $saveArrayAdvertiser['AdvertiserProfile']['fax']  			= $this->data['AdvertiserOrder']['fax'];
											  $saveArrayAdvertiser['AdvertiserProfile']['currency']  		= $this->data['AdvertiserOrder']['currency'];
											  $saveArrayAdvertiser['AdvertiserProfile']['publish']  		= 'no';
											  $saveArrayAdvertiser['AdvertiserProfile']['facebook']  		= $this->data['AdvertiserOrder']['facebook'];
											  $saveArrayAdvertiser['AdvertiserProfile']['twitter']  		= $this->data['AdvertiserOrder']['twitter'];
											  $saveArrayAdvertiser['AdvertiserProfile']['order_id']  		= $order_id;
											   /* pr($saveArrayAdvertiser);
												exit;*/
											  $this->AdvertiserProfile->save($saveArrayAdvertiser,false);
											  $ad_id_latest = $advertiser_id;
											  
											   if($this->data['AdvertiserOrder']['save_later']!=1) {
											  //----------save the instance of order, when new order is placed (Start)------//
												App::import('model', 'OrderInstance');
												$this->OrderInstance = new OrderInstance;
												$saveInstanceArray = array();
												$saveInstanceArray['OrderInstance']['advertiser_order_id']   = $order_id; 
												$saveInstanceArray['OrderInstance']['advertiser_profile_id']  =  $ad_id_latest;
												$saveInstanceArray['OrderInstance']['package_id']   	=  $this->data['AdvertiserOrder']['package_id'];
												$saveInstanceArray['OrderInstance']['insert_status']   	=  3;
												$this->OrderInstance->save($saveInstanceArray,false);
												//----------save the instance of order, when new order is placed (End)------//
											  }
											  
											 //$this->loadModel('FrontUser');
											 if($this->data['AdvertiserOrder']['save_later']!=1) {
												 App::import('model', 'FrontUser');
												 $this->FrontUser = new FrontUser;
												 $arr = array();
												 $password = $this->common->randomPassword(8);
												 $arr['FrontUser']['password'] = $this->Auth->password($password);
												 $arr['FrontUser']['realpassword'] = $password;
												 $arr['FrontUser']['name'] 		= $this->data['AdvertiserOrder']['advertiser_name'];
												 $arr['FrontUser']['email'] 	= $this->data['AdvertiserOrder']['email'];
												 $arr['FrontUser']['status'] 	= 'yes';
												 $arr['FrontUser']['county_id'] = $this->data['AdvertiserOrder']['county'];	
												 $arr['FrontUser']['state_id'] = $this->data['AdvertiserOrder']['state'];					
												 $arr['FrontUser']['advertiser_profile_id'] = $ad_id_latest;
												 $arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
												 $this->FrontUser->save($arr);
												 //$this->sendUsernamePassword($this->data['AdvertiserOrder']['email'],$password);
											 }
											$phoneAdvertiser=$this->data['AdvertiserOrder']['phoneno'];	
											$this->AdvertiserProfile->query("UPDATE advertiser_profiles SET phoneno='".$phoneAdvertiser."', state='".$this->data['AdvertiserOrder']['state']."' WHERE id=$ad_id_latest");
											
											
											App::import('model', 'SavingOffer');
										  	$this->SavingOffer = new SavingOffer;
											$this->SavingOffer->deleteAll(array('SavingOffer.advertiser_profile_id'=>$this->params['pass'][0]));	
											//pr($this->data);
											$county_id = $this->data['AdvertiserOrder']['county'];
											if($saveArray['AdvertiserOrder']['save_later']==1) {
												$live = 0;
											} else {
												$live = 1;
											}
											
											/*pr($this->data);
											exit;*/
											for($i=1;$i<=$offer_count;$i++) {
											if(isset($this->data['AdvertiserOrder']['description_'.$i])) {											
												if($i==1) {
														$title = $this->data['AdvertiserOrder']['main_offer_title'];
														$off = '';
														//$off = $this->data['AdvertiserOrder']['main_offer_discount'];
														$description = $this->data['AdvertiserOrder']['description_1'];
														$current_saving_offer = 1;
														$other_saving_offer = 0;
														$disclaimer = $this->data['AdvertiserOrder']['main_offer_dscpt1'].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt2'].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt3'];
												} else {
														$title = $this->data['AdvertiserOrder']['title_'.$i];
														$description = $this->data['AdvertiserOrder']['description_'.$i];
														$off = '';
														$current_saving_offer = 0;
														$other_saving_offer = 1;
														$disclaimer = $this->data['AdvertiserOrder']['main_offer_dscpt1_'.$i].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt2_'.$i].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt3_'.$i];
												}
												$advertiser_profile_id = $advertiser_id;
												$category = '';
												$subcategory ='';												
												$offer_start_date =  '';
												$offer_expiry_date = '';
												$no_valid_other_offer = '';
												$no_transferable = '';
												$other = '';
												if(isset($this->data['AdvertiserOrder']['not_valid_other'.$i])) {
													$no_valid_other_offer = $this->data['AdvertiserOrder']['not_valid_other'.$i];
												}
												if(isset($this->data['AdvertiserOrder']['n_transferable'.$i])) {
													$no_transferable = $this->data['AdvertiserOrder']['n_transferable'.$i];
												}
												if(isset($this->data['AdvertiserOrder']['other'.$i])) {
													$other = $this->data['AdvertiserOrder']['other'.$i];
												}
											}
											$this->SavingOffer->query("INSERT INTO saving_offers (current_saving_offer, other_saving_offer, title, off,  advertiser_profile_id, advertiser_county_id, description, offer_start_date, offer_expiry_date, no_valid_other_offer, no_transferable, other, disclaimer, live) VALUES ('$current_saving_offer', '$other_saving_offer', '$title', '$off', '$advertiser_id', '$county_id', '$description', '$offer_start_date', '$offer_expiry_date', '$no_valid_other_offer', '$no_transferable', '$other', '$disclaimer', '$live')");
											//$this->SavingOffer->save($saveSavingOffer);
										}/*
										echo $offer_count;
										pr($saveSavingOffer);
										exit;*/
										//getAdminEmail//getSalesEmail
									   // Here we are sending email to advertiser for notification that his/he order has been placed at Zuni.com
									   if(isset($this->data['AdvertiserOrder']['Vip_title']) && $this->data['AdvertiserOrder']['Vip_title']!='') {
									   App::import('model', 'VipOffer');
										$this->VipOffer = new VipOffer;
										$this->VipOffer->deleteAll(array('VipOffer.advertiser_profile_id'=>$this->params['pass'][0]));
											 //$vipoffer['VipOffer']['off'] = $this->data['AdvertiserOrder']['main_offer_discount'];
											 $vipoffer['VipOffer']['description'] = $this->data['AdvertiserOrder']['main_offer_discount'];
											 $vipoffer['VipOffer']['title'] = $this->data['AdvertiserOrder']['Vip_title'];
											 $vipoffer['VipOffer']['advertiser_profile_id'] = $ad_id_latest;
											 $vipoffer['VipOffer']['category'] = $this->data['AdvertiserOrder']['Vip_Category'];
											 $vipoffer['VipOffer']['advertiser_county_id'] = $this->data['AdvertiserOrder']['county'];
											 $vipoffer['VipOffer']['status'] = 'yes';
											 $this->VipOffer->save($vipoffer);
											 }
											  $signature = 'No Signature';
											 if($fileName!='') {
											 	$signature = '<img src="'.FULL_BASE_URL.router::url('/',false).'Signature/'.$fileName.'" />';
											 }
											 
											 
										if($this->data['AdvertiserOrder']['save_later']!=1) {
											
												App::import('model', 'Setting');
	    										$this->Setting = new Setting;
												$emailArray = $this->Setting->getAdvertiserEmailData();
												$package_name =   $this->common->getAllPackage(2);
												$package_price =   $this->common->getAllPackage(3);
												$bodyData = $this->Setting->replaceUserMarkers($emailArray[0]['settings']['new_advertiser_body'],$this->data['AdvertiserOrder']['advertiser_name'],$package_name[$this->data['AdvertiserOrder']['package_id']],$this->data['AdvertiserOrder']['company_name'],$package_price[$this->data['AdvertiserOrder']['package_id']],$this->AdvertiserOrder->getlastinsertid(),$password,$signature);
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = $this->emailhtml->email_header($this->data['AdvertiserOrder']['county']);
												$this->body .=$bodyData;
												$this->body .= $this->emailhtml->email_footer($this->data['AdvertiserOrder']['county']);											
												$this->set('var1',$this->data['AdvertiserOrder']['email']);
												$this->set('var2',$emailArray[0]['settings']['new_advertiser_subject']);
												$this->set('var3',$this->common->getReturnEmail());
												$this->set('var4',$this->common->getFromName().' <'.$this->common->getSalesEmail().'>');
												$this->set('var5',$this->body);											
											//create pdf
												$this->set('fileName',$fileName);
												$this->set('logoname',$logoname);
												$pdf_name = 'order_'.time().''.$advertiser_profile_id.'.pdf';
												$this->set('name',$pdf_name);
											$this->set('redirectUrl',FULL_BASE_URL.router::url('/',false).'advertiser_profiles/thanksPage/'.$advertiser_profile_id);
												$this->set('Email',$this->Email);						
												$this->layout = 'pdf';
												$this->set('common',$this->common);
												$this->render('/advertiser_orders/pdf');
									}
												
											if($saveArray['AdvertiserOrder']['save_later']==1) {
											 	/*$this->Session->setFlash('Your order has been submitted successfully.');  
											 	$this->redirect(array('controller'=>'advertiser_profiles','action' => 'thanksPage',$advertiser_profile_id));
											} else {*/
												$this->Session->setFlash('Your order has been saved successfully.');  
											 	$this->redirect(array('action' => "savedOrder"));
											}										  										  
										}								  
									}
								}
							}
						}
					} else {
							$this->loadModel('AdvertiserProfile');
							//$this->AdvertiserProfile->id = $id;
							 $data = $this->AdvertiserProfile->find('first',array('conditions'=>array('AdvertiserProfile.id'=>$id),'contain' => array('AdvertiserCategory'=>array('CategoriesSubcategory'=>array('Category.id','Subcategory.id')))));
							 
							$this->data = $data;
							$this->data['AdvertiserOrder'] = $this->data['AdvertiserProfile'];
							$this->data['AdvertiserOrder']['advertiser_name'] = $this->data['AdvertiserProfile']['name'];
							$this->set('logo',$this->data['AdvertiserProfile']['logo']);
							//vip offer
					$this->loadModel('VipOffer');
					$vipoffer = $this->VipOffer->find('first',array('fields'=>array('VipOffer.title','VipOffer.description','VipOffer.category'),'conditions'=>array('VipOffer.advertiser_profile_id'=>$this->params['pass'][0])));
					
					$this->data['AdvertiserOrder']['main_offer_discount'] = $vipoffer['VipOffer']['description'];
					$this->data['AdvertiserOrder']['Vip_title'] = $vipoffer['VipOffer']['title'];
					$this->data['AdvertiserOrder']['Vip_Category'] = $vipoffer['VipOffer']['category'];
								
							//pr($this->data['AdvertiserProfile']);
							$order = $this->common->getorderdetail($this->data['AdvertiserProfile']['order_id']);
							$this->data['AdvertiserOrder']['salesperson'] = $this->data['AdvertiserProfile']['creator'];
							//pr($order);
							$this->set('package_id',$order['AdvertiserOrder']['package_id']);		
							$checked = 0;
							$this->set('checked',$checked);
							$subcat_array=array_filter(explode(',',$this->data['AdvertiserProfile']['subcategory']));
							$this->set('subcat_array',$subcat_array);
							$this->loadModel('SavingOffer');
							$main_saving_offer = $this->SavingOffer->find('first',array('conditions'=>array('SavingOffer.advertiser_profile_id'=>$this->data['AdvertiserProfile']['id'],'SavingOffer.current_saving_offer'=>1),'recursive'=>-1));
							$other_saving_offer = $this->SavingOffer->find('all',array('conditions'=>array('SavingOffer.advertiser_profile_id'=>$this->data['AdvertiserProfile']['id'],'SavingOffer.other_saving_offer'=>1),'recursive'=>-1));
							$this->set('offer_count',count($other_saving_offer)+1);
							$a=2;
							//pr($other_saving_offer);
							if(count($other_saving_offer)>0) {
								foreach($other_saving_offer as $other) {
									$extra_disclaimer = '';
									$extra_disclaimer = explode('.',$other['SavingOffer']['disclaimer']);
									if($a==2) {
										$this->set('title_2',$other['SavingOffer']['title']);
										$this->set('description_2',$other['SavingOffer']['description']);
										if(isset($extra_disclaimer[0]))  {
											$this->set('main_offer_dscpt1_2',$extra_disclaimer[0]);
										} else {$this->set('main_offer_dscpt1_2','');}
										if(isset($extra_disclaimer[1]))  {
											$this->set('main_offer_dscpt2_2',$extra_disclaimer[1]);
										} else {$this->set('main_offer_dscpt2_2','');}
										if(isset($extra_disclaimer[2]))  {
											$this->set('main_offer_dscpt3_2',$extra_disclaimer[2]);
										} else {$this->set('main_offer_dscpt3_2','');}
									}
									if($a==3) {
										$this->set('title_3',$other['SavingOffer']['title']);
										$this->set('description_3',$other['SavingOffer']['description']);
										if(isset($extra_disclaimer[0]))  {
											$this->set('main_offer_dscpt1_3',$extra_disclaimer[0]);
										} else {$this->set('main_offer_dscpt1_3','');}
										if(isset($extra_disclaimer[1]))  {
											$this->set('main_offer_dscpt2_3',$extra_disclaimer[1]);
										} else {$this->set('main_offer_dscpt2_3','');}
										if(isset($extra_disclaimer[2]))  {
											$this->set('main_offer_dscpt3_3',$extra_disclaimer[2]);
										} else {$this->set('main_offer_dscpt3_3','');}
									}
									if($a==4) {
										$this->set('title_4',$other['SavingOffer']['title']);
										$this->set('description_4',$other['SavingOffer']['description']);
										if(isset($extra_disclaimer[0]))  {
											$this->set('main_offer_dscpt1_4',$extra_disclaimer[0]);
										} else {$this->set('main_offer_dscpt1_4','');}
										if(isset($extra_disclaimer[1]))  {
											$this->set('main_offer_dscpt2_4',$extra_disclaimer[1]);
										} else {$this->set('main_offer_dscpt2_4','');}
										if(isset($extra_disclaimer[2]))  {
											$this->set('main_offer_dscpt3_4',$extra_disclaimer[2]);
										} else {$this->set('main_offer_dscpt3_4','');}
									}
									if($a==5) {
										$this->set('title_5',$other['SavingOffer']['title']);
										$this->set('description_5',$other['SavingOffer']['description']);
										if(isset($extra_disclaimer[0]))  {
											$this->set('main_offer_dscpt1_5',$extra_disclaimer[0]);
										} else {$this->set('main_offer_dscpt1_5','');}
										if(isset($extra_disclaimer[1]))  {
											$this->set('main_offer_dscpt2_5',$extra_disclaimer[1]);
										} else {$this->set('main_offer_dscpt2_5','');}
										if(isset($extra_disclaimer[2]))  {
											$this->set('main_offer_dscpt3_5',$extra_disclaimer[2]);
										} else {$this->set('main_offer_dscpt3_5','');}
									}
									
									$not_valid_other[$a] = $other['SavingOffer']['no_valid_other_offer'];
									$n_transferable[$a] = $other['SavingOffer']['no_transferable'];
									$others[$a] = $other['SavingOffer']['other'];						
									
									
									$this->set('not_valid_other',$not_valid_other);
									$this->set('n_transferable',$n_transferable);
									$this->set('others',$others);
									$a++;
								}
							}
							if(count($main_saving_offer)>0) {
									$this->data['AdvertiserOrder']['main_offer_title'] 	= $main_saving_offer['SavingOffer']['title'];
									$this->data['AdvertiserOrder']['description_1'] 	= $main_saving_offer['SavingOffer']['description'];
									//$this->data['AdvertiserOrder']['main_offer_discount'] 	= $main_saving_offer['SavingOffer']['off'];
									$this->set('not_valid_other1',$main_saving_offer['SavingOffer']['no_valid_other_offer']);
									$this->set('n_transferable1',$main_saving_offer['SavingOffer']['no_transferable']);
									$this->set('other1',$main_saving_offer['SavingOffer']['other']);
									
									$extra_content = explode('.',$main_saving_offer['SavingOffer']['disclaimer']);
									if(isset($extra_content[0]))  {
										$this->data['AdvertiserOrder']['main_offer_dscpt1'] 	= $extra_content[0];
									}
									if(isset($extra_content[1]))  {
										$this->data['AdvertiserOrder']['main_offer_dscpt2'] 	= $extra_content[1];
									}
									if(isset($extra_content[2]))  {
										$this->data['AdvertiserOrder']['main_offer_dscpt3'] 	= $extra_content[2];
									}
								}
							}							
						} else {
							$this->Session->setFlash('Invalid Order id.');
							$this->redirect(array('action' => "savedOrder"));
						}
	   				}
//------------------------------------------------------------------------------//
 // OLD adding new AdvertiserOrder in database	for backup   
	    function __editSavedOrder__($id){
					$platform = ($this->mobile->isMobile() ? ($this->mobile->isTablet() ? 'tablet' : 'mobile') : 'computer');
					$this->set('platform',$platform);
					if($id) {
							$this->set('save_later',0);
							$this->loadModel('User');
							$this->set('salesperson', $this->User->returnUsersSales());
							$this->set('offer_count',1);
							$this->set('Packages', $this->common->getAdminPackage());
							$this->set('StatesList',$this->common->getAllState());  //  List states
							//$this->set('CitiesList',$this->common->getAllCity());   //  List cities
							//$this->set('CountyList',$this->common->getAllCounty()); //  List counties
							$state_id = '';
							if(isset($this->data)) {
								$state_id = $this->data['AdvertiserOrder']['state'];
							}else {
								$state_id = $this->common->getCompanystate($id);
							}
							$county_id = '';
							if(isset($this->data)) {
								$county_id = $this->data['AdvertiserOrder']['county'];
							}else {
								$county_id = $this->common->getCompanyCounty($id);
							}
							$this->set('county_id',$county_id);
							$this->set('CitiesList',$this->common->getCountyCity($county_id));   //  List cities						
							$this->set('CountyList',$this->common->getAllCountyByState($state_id)); //  List counties
							
							
							$this->set('AllCatSubcat',$this->common->getAllCatSubcatoption('AdvertiserOrder'));
							$this->set('categoryList',$this->common->getAllCategory());
					if(isset($this->data)) {
					$this->set('not_valid_other1','');
					$this->set('n_transferable1','');
					$this->set('other1','');		
					$subcat_array = array();
					if(isset($this->data['AdvertiserOrder']['subcategory']) && count($this->data['AdvertiserOrder']['subcategory'])) {
						foreach($this->data['AdvertiserOrder']['subcategory'] as $catearr) {
							$sucat = '';
							$sucat = explode('-',$catearr);
							$subcat_array[] = $sucat[1];
						}
					}
				$this->set('subcat_array',$subcat_array);				
				if($this->data['AdvertiserOrder']['logo']['error']) {
					$this->set('logo',$this->data['AdvertiserOrder']['old_logo']);
				} else {
					$this->set('logo','');
				}
					  $this->set('package_id',$this->data['AdvertiserOrder']['package_id']);
					  $offer_count = $this->data['AdvertiserOrder']['offer_count'];
					  $this->set('offer_count',$offer_count);
					  for($p=2;$p<=$offer_count;$p++) {
						$this->set('description_'.$p,$this->data['AdvertiserOrder']['description_'.$p]);
						$this->set('title_'.$p,$this->data['AdvertiserOrder']['title_'.$p]);
					 }
					  if($this->data['AdvertiserOrder']['processed']!='location_processed') {
						$checked = 1;
						$this->set('checked',$checked);
					  } else {
						$checked = 0;
						$this->set('checked',$checked);
					  }
					  $this->AdvertiserOrder->set($this->data['AdvertiserOrder']);
			               if (empty($this->data)){
                          		$this->data = $this->AdvertiserOrder->find(array('AdvertiserOrder.id' => $id));
                           }
			               if($this->data['AdvertiserOrder']!=''){
					/*setting error message if validation fails*/
						  $errors = $this->AdvertiserOrder->invalidFields();
						  if(isset($errors) && count($errors)>0) {
							$error = implode('<br>', $errors).'<br />';
						  }	else {
							$error = '';
						  }
						if($this->data['AdvertiserOrder']['processed']!='location_processed') {
								if($this->data['AdvertiserOrder']['credit_name']=='') {
									$error.='Please enter name on credit card.<br />';											
								}
								if($this->data['AdvertiserOrder']['credit_number']=='') {
									$error.='Please enter Credit Card Number.<br />';											
								}
								if($this->data['AdvertiserOrder']['cvv']=='' || !is_numeric($this->data['AdvertiserOrder']['cvv'])) {
									$error.='Please enter valid CVV number.<br />';		
								}
								if($this->data['AdvertiserOrder']['card_exp_month']=='') {
									$error.='Please select credit card expiry month.<br />';
								}
								if($this->data['AdvertiserOrder']['card_exp_year']=='') {
									$error.='Please select credit card expiry year.<br />';										
								}
							}
									if(isset($error) && $error!='<br/>' && $error !='') {
									      $this->Session->setFlash($error);
						             }
					                else{
										  $sid = $this->Auth->user();
										  App::import('model', 'AdvertiserProfile');
										  $this->AdvertiserProfile = new AdvertiserProfile;
										  //here we are checking same email id in database. we will not allow to save same email twice
										 $emailFound = $this->AdvertiserProfile->checkEmailNid(trim($this->data['AdvertiserOrder']['email']),$this->params['pass'][0]);
								if(is_array($emailFound) && isset($emailFound[0]['advertiser_profiles']['id']) && $emailFound[0]['advertiser_profiles']['id']!='')
										  {
										 	   $this->Session->setFlash('Email is already exists in database.Please provide another email.');					   
										  }
										  else {
												if($this->data['AdvertiserOrder']['main_offer_title']== '' && $this->data['AdvertiserOrder']['description_1']=='' && $this->data['AdvertiserOrder']['save_later']!=1) {
													$this->Session->setFlash('Please fill Main Savings Offer.');
												} else {
												
														$CreditCardTransID = '';
														$ClientTransID ='';
														$TStamp = '';
											 		if($this->data['AdvertiserOrder']['processed']=='manual_process') {
													$this->loadModel('Package');													
													$p_price = $this->Package->find('first',array('fields'=>array('Package.setup_price','Package.monthly_price'),'conditions'=>array('Package.id'=>$this->data['AdvertiserOrder']['package_id'])));
													$total_price = ($p_price['Package']['setup_price']+$p_price['Package']['monthly_price']);
													//////////////////// Payment Gateway //////////////////////////			
													ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/Users/keithpalmerjr/Projects/QuickBooks/');
													//error_reporting(E_ALL | E_STRICT);
													//ini_set('display_errors', true);
													require_once (APP.'webroot/quickbooks/QuickBooks.php');
													$dsn = null;
													$path_to_private_key_and_certificate = APP.'webroot/quickbooks/docs/intuit.pem';
													$application_login = INTUIT_APP_LOGIN;
													$connection_ticket = INTUIT_TICKET;
													//$connection_ticket = 'TGT-214-ZfVkTPfjiZZYFg83gYA6Hw';
													$MS = new QuickBooks_MerchantService(
													$dsn, 
													$path_to_private_key_and_certificate, 
													$application_login,
													$connection_ticket);
													//$MS->useTestEnvironment(true);
													$MS->useLiveEnvironment(true);
													$name = NULL;
													//$number = '4427322513320494';
													$number = $this->data['AdvertiserOrder']['credit_number'];
													$expyear =	$this->data['AdvertiserOrder']['card_exp_year'];
													$expmonth = $this->data['AdvertiserOrder']['card_exp_month'];
													$address = NULL;
													$postalcode = NULL;
													$cvv = $this->data['AdvertiserOrder']['cvv'];
													$amount = number_format($total_price,1);
													//$amount = 1.0;
													$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
													if ($Transaction = $MS->charge($Card, $amount))
													{
														$trans_result = $Transaction->toArray();
														$CreditCardTransID = $trans_result['CreditCardTransID'];
														$ClientTransID = $trans_result['ClientTransID'];
														$TStamp = $trans_result['TxnAuthorizationStamp'];
														} else	{
															$CreditCardTransID = '';
															$ClientTransID ='';
															$TStamp = '';
															$this->Session->setFlash($MS->errorMessage());
															return false;
														}
													}
													$logoname = '';
											if(($this->data['AdvertiserOrder']['processed']!='manual_process') || ($this->data['AdvertiserOrder']['processed']=='manual_process' && $CreditCardTransID!='')) {
											 	
									$saveArrayAdvertiser = array();
									// upload uploaded file
									if($this->data['AdvertiserOrder']['logo']['name']!=""){
									$type = $this->data['AdvertiserOrder']['logo']['type'];
									if($type =="image/png" || $type =="image/jpeg"  || $type =="image/gif"){				                         
									
									$this->data['AdvertiserOrder']['logo']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','-',$this->data['AdvertiserOrder']['logo']['name']);

									$logoname = $this->data['AdvertiserOrder']['logo']['name'];
									$docDestination = APP.'webroot/img/logo/'.$this->data['AdvertiserOrder']['logo']['name']; 
									@chmod(APP.'webroot/img/logo',0777);
									move_uploaded_file($this->data['AdvertiserOrder']['logo']['tmp_name'], $docDestination) or die($docDestination);
									$saveArrayAdvertiser['AdvertiserProfile']['logo'] = $this->data['AdvertiserOrder']['logo']['name'];						
									}else{					
											$this->Session->setFlash('Please upload .jpg file or .png file or .gif file.'); 
										}					
									}	  //first inserting record in advertiser order table
									$advertiser_id = $this->params['pass'][0];
									$order_id = $this->common->getonlyOrderId($advertiser_id);									
											  	$saveArray = array();
											  	$saveArray['AdvertiserOrder']['payment_status']	= 'pending';
												$saveArray['AdvertiserOrder']['order_status']	= 'pending';												
												
												$fileName = '';
												// signature
												if($this->data['AdvertiserOrder']['processed']!='location_processed') {
																								
												if(isset($_POST['output']) && $_POST['output']!=''){
														$img = $this->sigJsonToImage($_POST['output']);
								
														$fileName = time()."_".$order_id."-signature.png";
								
														$filePath = WWW_ROOT."Signature/".$fileName;
								
														imagepng($img, $filePath);
								
														imagedestroy($img);						
								
													}else{ 
								
														$img = imagecreatetruecolor(400, 30);
								
														$bgColour = imagecolorallocate($img, 0xff, 0xff, 0xff);
								
														$penColour = imagecolorallocate($img, 0x14, 0x53, 0x94);
								
														imagefilledrectangle($img, 0, 0, 399, 29, $bgColour);
								
														$text = $this->data['AdvertiserOrder']['name'];
								
														$font = WWW_ROOT.'journal.ttf';
								
														imagettftext($img, 20, 0, 10, 20, $penColour, $font, $text);
								
														// Save to file
								
														$fileName = time()."_".$order_id."-signature.png";
								
														$filePath = WWW_ROOT."Signature/".$fileName;
								
														imagepng($img, $filePath);
								
														imagedestroy($img);
								
													}
												}	
											if($this->data['AdvertiserOrder']['processed']=='manual_process') {
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_process']  	= $this->data['AdvertiserOrder']['processed'];
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_id'] 	= $CreditCardTransID;
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_client_id']= $ClientTransID;
											  $saveArrayAdvertiser['AdvertiserProfile']['transaction_date']  	= $TStamp;
											  $saveArray['AdvertiserOrder']['payment_status']	= 'approved';
											  $saveArray['AdvertiserOrder']['order_status']	= 'approved';
											} else {
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_name']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_number']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['cvv']  			= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['card_exp_month'] 	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['card_exp_year']  	= '';
											  $saveArrayAdvertiser['AdvertiserProfile']['credit_process']  	= $this->data['AdvertiserOrder']['processed'];
											}																					  							
											  $saveArray['AdvertiserOrder']['id']   						= $order_id;				
											  $saveArray['AdvertiserOrder']['package_id']   				= $this->data['AdvertiserOrder']['package_id'];
											  
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
												$saveArray['AdvertiserOrder']['salesperson']		= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
												$saveArray['AdvertiserOrder']['salesperson']		= $sid['Admin']['id'];
											  }
											  
											  $saveArrayAdvertiser['AdvertiserProfile']['modifier']  		= $this->Session->read('Auth.Admin.id');
											  
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
												$saveArrayAdvertiser['AdvertiserProfile']['creator']	= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
												$saveArrayAdvertiser['AdvertiserProfile']['creator']	= $sid['Admin']['id'];
											  }
											  $saveArray['AdvertiserOrder']['save_later']     				= $this->data['AdvertiserOrder']['save_later'];	
											  $saveArray['AdvertiserOrder']['user_group_id']     			= $this->Session->read('Auth.Admin.user_group_id');	
											 $saveArrayAdvertiser['AdvertiserProfile']['show_address']     	= $this->data['AdvertiserOrder']['show_address'];	
											 $saveArrayAdvertiser['AdvertiserProfile']['address2']     		= $this->data['AdvertiserOrder']['address2'];	
											 $saveArrayAdvertiser['AdvertiserProfile']['show_address2']     = $this->data['AdvertiserOrder']['show_address2'];
											 $saveArrayAdvertiser['AdvertiserProfile']['signature']  		= $fileName;							
											  $saveArrayAdvertiser['AdvertiserProfile']['phoneno2']  		= $this->data['AdvertiserOrder']['phoneno2'];
											  $saveArrayAdvertiser['AdvertiserProfile']['city2']  			= $this->data['AdvertiserOrder']['city2'];
											  $saveArrayAdvertiser['AdvertiserProfile']['zip2']  			= $this->data['AdvertiserOrder']['zip2'];
											  /*pr($saveArrayAdvertiser);
											  exit;	*/						
											  $this->AdvertiserOrder->save($saveArray);
											  //aftre getting last inserted id for advertiser table we are inserting in work order table
											  if($this->data['AdvertiserOrder']['save_later']!=1) {
											  App::import('model', 'WorkOrder');
										  	  $this->WorkOrder = new WorkOrder;
											  $saveWorkArray = array();
											  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $order_id;
											  $saveWorkArray['WorkOrder']['read_status']   				=  0;
											  $saveWorkArray['WorkOrder']['subject']   					=  'New work order Generated';
											  $saveWorkArray['WorkOrder']['message']	=  'A new work order has been placed recently.Order details are below:';
											  $saveWorkArray['WorkOrder']['type']   					=  'workorder';
											  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
											  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
											  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');								
											  $saveWorkArray['WorkOrder']['bottom_line']   				=  'You can go further and add other details about this advertiser in advertiser profiles section like saving offers , vip offers etc.';
											  
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
												$saveWorkArray['WorkOrder']['salseperson_id']	= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
												$saveWorkArray['WorkOrder']['salseperson_id']	= $sid['Admin']['id'];
											  }
											  
											  date_default_timezone_set('US/Eastern');
											  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
											  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
											  $this->WorkOrder->save($saveWorkArray);
											  $saveWorkArray = '';
											  $saveWorkArray['WorkOrder']['id']   						=  '';
											  $saveWorkArray['WorkOrder']['advertiser_order_id']   		=  $order_id;
											  $saveWorkArray['WorkOrder']['read_status']   				=  0;
											  $saveWorkArray['WorkOrder']['subject']   					=  'New Contract';
									  		  $saveWorkArray['WorkOrder']['message']   				=  'A new Contract has been placed recently. details are below:';
											  $saveWorkArray['WorkOrder']['type']   					=  'Contract';
											  $saveWorkArray['WorkOrder']['sent_to']   					=  0;
											  $saveWorkArray['WorkOrder']['sent_to_group']   			=  1;
											  $saveWorkArray['WorkOrder']['from_group']   				=  $this->Session->read('Auth.Admin.user_group_id');
											  $saveWorkArray['WorkOrder']['bottom_line']   				=  'The Advertiser is currently unpublish. As per zuni\'s contract plan, Only admin can publish the profile.';
											  
											  if(isset($this->data['AdvertiserOrder']['salesperson'])) {
												$saveWorkArray['WorkOrder']['salseperson_id']	= $this->data['AdvertiserOrder']['salesperson'];
											  } else {
												$saveWorkArray['WorkOrder']['salseperson_id']	= $sid['Admin']['id'];
											  }
											  date_default_timezone_set('US/Eastern');
											  $saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
											  $saveWorkArray['WorkOrder']['created']   					=  strtotime(date(DATE_FORMAT.' h:i:s A'));
											  
											  $this->WorkOrder->save($saveWorkArray);
											  
											   //--------------------------------------------------------------
												$this->loadModel('FrontUser');
												$this->loadModel('Setting');
												$setvale = $this->Setting->find('first',array('fields'=>array('refer_business_bucks')));
												$bucksprice = $setvale['Setting']['refer_business_bucks'];
												//bucks management
												$this->loadModel('ReferredBusiness');												
												$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.phone'=>$this->data['AdvertiserOrder']['phoneno'],'ReferredBusiness.status'=>'no')));
												
												if($this->data['AdvertiserOrder']['phoneno2']!='' && empty($checkRefer)) {
													$checkRefer = $this->ReferredBusiness->find('first',array('conditions'=>array('ReferredBusiness.phone'=>$this->data['AdvertiserOrder']['phoneno2'],'ReferredBusiness.status'=>'no')));
												}
												if(is_array($checkRefer) && !empty($checkRefer)) {				
													$savearr['ReferredBusiness']['id'] = $checkRefer['ReferredBusiness']['id'];
													$savearr['ReferredBusiness']['status'] = 'yes';
													$savearr['ReferredBusiness']['bucks'] = $bucksprice;
													$savearr['ReferredBusiness']['refered_ip'] = $_SERVER['REMOTE_ADDR'];
													$savearr['ReferredBusiness']['register_date'] =  mktime(0, 0, 0, date('m'), date('d'), date('Y'));
													$savearr['FrontUser']['id'] =$checkRefer['FrontUser']['id'];
													$savearr['FrontUser']['total_bucks'] = $checkRefer['FrontUser']['total_bucks']+$bucksprice;	
													$savearr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
													$this->ReferredBusiness->save($savearr);
													$this->FrontUser->save($savearr);
													$this->loadModel('Buck');
													$checkBuck = $this->Buck->find('first',array('conditions'=>array('Buck.front_user_id'=>$checkRefer['FrontUser']['id'],'Buck.county_id'=>$this->data['AdvertiserOrder']['county'],'Buck.date'=>mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
													if(is_array($checkBuck) && count($checkBuck)) {
														$saveBuck['Buck']['id'] = $checkBuck['Buck']['id'];
														$saveBuck['Buck']['bucks'] = $checkBuck['Buck']['bucks']+$bucksprice;
													} else {
														$saveBuck['Buck']['front_user_id'] = $checkRefer['FrontUser']['id'];
														$saveBuck['Buck']['county_id'] = $this->data['AdvertiserOrder']['county'];
														$saveBuck['Buck']['bucks'] = $bucksprice;
														$saveBuck['Buck']['date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
													}
													$this->Buck->save($saveBuck);
												}
											 }
											 //aftre getting last inserted id for advertiser table we are inserting in advertiser profile table
											  $saveArrayAdvertiser['AdvertiserProfile']['id']   	    	=  $advertiser_id;
											  $saveArrayAdvertiser['AdvertiserProfile']['name']   	    	=  $this->data['AdvertiserOrder']['advertiser_name'];
											  $saveArrayAdvertiser['AdvertiserProfile']['company_name']     = $this->data['AdvertiserOrder']['company_name'];
											  $saveArrayAdvertiser['AdvertiserProfile']['email']   	        =  $this->data['AdvertiserOrder']['email'];
											  $saveArrayAdvertiser['AdvertiserProfile']['address']  		= $this->data['AdvertiserOrder']['address'];
											  $saveArrayAdvertiser['AdvertiserProfile']['city']   	        =  $this->data['AdvertiserOrder']['city'];
											  $saveArrayAdvertiser['AdvertiserProfile']['county']  		    = $this->data['AdvertiserOrder']['county'];
//											  $saveArrayAdvertiser['AdvertiserProfile']['state']  	        = $this->data['AdvertiserOrder']['state'];
											  $saveArrayAdvertiser['AdvertiserProfile']['website']  	    =  $this->data['AdvertiserOrder']['website'];
											  $saveArrayAdvertiser['AdvertiserProfile']['country']   	    =  840;
											  $saveArrayAdvertiser['AdvertiserProfile']['zip']  			= $this->data['AdvertiserOrder']['zip'];
											  $saveArrayAdvertiser['AdvertiserProfile']['all_cities'] 		= $this->data['AdvertiserOrder']['all_cities'];
											  date_default_timezone_set('US/Eastern');
											  $saveArrayAdvertiser['AdvertiserProfile']['contract_date']  	= strtotime($this->data['AdvertiserOrder']['contract_date']);
											  //$saveArrayAdvertiser['AdvertiserProfile']['contract_expiry_date']  	= strtotime($this->data['AdvertiserOrder']['contract_expiry_date']);
												
												
												 /*------------to set the multiple category and subcategory------------------*/
												$this->loadModel('AdvertiserCategory');
												$id = $advertiser_id;
												$this->AdvertiserCategory->deleteAll(array('AdvertiserCategory.advertiser_profile_id'=>$advertiser_id));
												foreach($this->data['AdvertiserOrder']['subcategory'] as $pair) {
													$break = '';
													$break = explode('-',$pair);
													$catSubcat = $this->common->returnCatSubcatId($break[0],$break[1]);
													if($catSubcat) {
														$save = '';
														$save['AdvertiserCategory']['id'] = '';
														$save['AdvertiserCategory']['advertiser_profile_id'] = $advertiser_id;
														$save['AdvertiserCategory']['categories_subcategory_id'] = $catSubcat;
														$this->AdvertiserCategory->save($save,false);
													}
												}
												
											  $saveArrayAdvertiser['AdvertiserProfile']['fax']  			= $this->data['AdvertiserOrder']['fax'];
											  $saveArrayAdvertiser['AdvertiserProfile']['currency']  		= $this->data['AdvertiserOrder']['currency'];
											  $saveArrayAdvertiser['AdvertiserProfile']['publish']  		= 'no';
											  $saveArrayAdvertiser['AdvertiserProfile']['facebook']  		= $this->data['AdvertiserOrder']['facebook'];
											  $saveArrayAdvertiser['AdvertiserProfile']['twitter']  		= $this->data['AdvertiserOrder']['twitter'];
											  $saveArrayAdvertiser['AdvertiserProfile']['order_id']  		= $order_id;
											   /* pr($saveArrayAdvertiser);
												exit;*/
											  $this->AdvertiserProfile->save($saveArrayAdvertiser,false);
											  $ad_id_latest = $advertiser_id;
											  
											   if($this->data['AdvertiserOrder']['save_later']!=1) {
											  //----------save the instance of order, when new order is placed (Start)------//
												App::import('model', 'OrderInstance');
												$this->OrderInstance = new OrderInstance;
												$saveInstanceArray = array();
												$saveInstanceArray['OrderInstance']['advertiser_order_id']   = $order_id; 
												$saveInstanceArray['OrderInstance']['advertiser_profile_id']  =  $ad_id_latest;
												$saveInstanceArray['OrderInstance']['package_id']   	=  $this->data['AdvertiserOrder']['package_id'];
												$saveInstanceArray['OrderInstance']['insert_status']   	=  3;
												$this->OrderInstance->save($saveInstanceArray,false);
												//----------save the instance of order, when new order is placed (End)------//
											  }
											  
											 //$this->loadModel('FrontUser');
											 if($this->data['AdvertiserOrder']['save_later']!=1) {
												 App::import('model', 'FrontUser');
												 $this->FrontUser = new FrontUser;
												 $arr = array();
												 $password = $this->common->randomPassword(8);
												 $arr['FrontUser']['password'] = $this->Auth->password($password);
												 $arr['FrontUser']['realpassword'] = $password;
												 $arr['FrontUser']['name'] 		= $this->data['AdvertiserOrder']['advertiser_name'];
												 $arr['FrontUser']['email'] 	= $this->data['AdvertiserOrder']['email'];
												 $arr['FrontUser']['status'] 	= 'yes';
												 $arr['FrontUser']['county_id'] = $this->data['AdvertiserOrder']['county'];	
												 $arr['FrontUser']['state_id'] = $this->data['AdvertiserOrder']['state'];					
												 $arr['FrontUser']['advertiser_profile_id'] = $ad_id_latest;
												 $arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
												 $this->FrontUser->save($arr);
												 //$this->sendUsernamePassword($this->data['AdvertiserOrder']['email'],$password);
											 }
											$phoneAdvertiser=$this->data['AdvertiserOrder']['phoneno'];	
											$this->AdvertiserProfile->query("UPDATE advertiser_profiles SET phoneno='".$phoneAdvertiser."', state='".$this->data['AdvertiserOrder']['state']."' WHERE id=$ad_id_latest");
											
											
											App::import('model', 'SavingOffer');
										  	$this->SavingOffer = new SavingOffer;
											$this->SavingOffer->deleteAll(array('SavingOffer.advertiser_profile_id'=>$this->params['pass'][0]));	
											//pr($this->data);
											$county_id = $this->data['AdvertiserOrder']['county'];
											if($saveArray['AdvertiserOrder']['save_later']==1) {
												$live = 0;
											} else {
												$live = 1;
											}
											
											/*pr($this->data);
											exit;*/
											for($i=1;$i<=$offer_count;$i++) {
											if(isset($this->data['AdvertiserOrder']['description_'.$i])) {											
												if($i==1) {
														$title = $this->data['AdvertiserOrder']['main_offer_title'];
														$off = '';
														//$off = $this->data['AdvertiserOrder']['main_offer_discount'];
														$description = $this->data['AdvertiserOrder']['description_1'];
														$current_saving_offer = 1;
														$other_saving_offer = 0;
														$disclaimer = $this->data['AdvertiserOrder']['main_offer_dscpt1'].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt2'].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt3'];
												} else {
														$title = $this->data['AdvertiserOrder']['title_'.$i];
														$description = $this->data['AdvertiserOrder']['description_'.$i];
														$off = '';
														$current_saving_offer = 0;
														$other_saving_offer = 1;
														$disclaimer = $this->data['AdvertiserOrder']['main_offer_dscpt1_'.$i].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt2_'.$i].'.'.$this->data['AdvertiserOrder']['main_offer_dscpt3_'.$i];
												}
												$advertiser_profile_id = $advertiser_id;
												$category = '';
												$subcategory ='';												
												$offer_start_date =  '';
												$offer_expiry_date = '';
												$no_valid_other_offer = '';
												$no_transferable = '';
												$other = '';
												if(isset($this->data['AdvertiserOrder']['not_valid_other'.$i])) {
													$no_valid_other_offer = $this->data['AdvertiserOrder']['not_valid_other'.$i];
												}
												if(isset($this->data['AdvertiserOrder']['n_transferable'.$i])) {
													$no_transferable = $this->data['AdvertiserOrder']['n_transferable'.$i];
												}
												if(isset($this->data['AdvertiserOrder']['other'.$i])) {
													$other = $this->data['AdvertiserOrder']['other'.$i];
												}
											}
											$this->SavingOffer->query("INSERT INTO saving_offers (current_saving_offer, other_saving_offer, title, off,  advertiser_profile_id, advertiser_county_id, description, offer_start_date, offer_expiry_date, no_valid_other_offer, no_transferable, other, disclaimer, live) VALUES ('$current_saving_offer', '$other_saving_offer', '$title', '$off', '$advertiser_id', '$county_id', '$description', '$offer_start_date', '$offer_expiry_date', '$no_valid_other_offer', '$no_transferable', '$other', '$disclaimer', '$live')");
											//$this->SavingOffer->save($saveSavingOffer);
										}/*
										echo $offer_count;
										pr($saveSavingOffer);
										exit;*/
										//getAdminEmail//getSalesEmail
									   // Here we are sending email to advertiser for notification that his/he order has been placed at Zuni.com
									   if(isset($this->data['AdvertiserOrder']['Vip_title']) && $this->data['AdvertiserOrder']['Vip_title']!='') {
									   App::import('model', 'VipOffer');
										$this->VipOffer = new VipOffer;
										$this->VipOffer->deleteAll(array('VipOffer.advertiser_profile_id'=>$this->params['pass'][0]));
											 //$vipoffer['VipOffer']['off'] = $this->data['AdvertiserOrder']['main_offer_discount'];
											 $vipoffer['VipOffer']['description'] = $this->data['AdvertiserOrder']['main_offer_discount'];
											 $vipoffer['VipOffer']['title'] = $this->data['AdvertiserOrder']['Vip_title'];
											 $vipoffer['VipOffer']['advertiser_profile_id'] = $ad_id_latest;
											 $vipoffer['VipOffer']['category'] = $this->data['AdvertiserOrder']['Vip_Category'];
											 $vipoffer['VipOffer']['advertiser_county_id'] = $this->data['AdvertiserOrder']['county'];
											 $vipoffer['VipOffer']['status'] = 'yes';
											 $this->VipOffer->save($vipoffer);
											 }
											  $signature = 'No Signature';
											 if($fileName!='') {
											 	$signature = '<img src="'.FULL_BASE_URL.router::url('/',false).'Signature/'.$fileName.'" />';
											 }
											 
											 
										if($this->data['AdvertiserOrder']['save_later']!=1) {
											
												App::import('model', 'Setting');
	    										$this->Setting = new Setting;
												$emailArray = $this->Setting->getAdvertiserEmailData();
												$package_name =   $this->common->getAllPackage(2);
												$package_price =   $this->common->getAllPackage(3);
												$bodyData = $this->Setting->replaceUserMarkers($emailArray[0]['settings']['new_advertiser_body'],$this->data['AdvertiserOrder']['advertiser_name'],$package_name[$this->data['AdvertiserOrder']['package_id']],$this->data['AdvertiserOrder']['company_name'],$package_price[$this->data['AdvertiserOrder']['package_id']],$this->AdvertiserOrder->getlastinsertid(),$password,$signature);
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = $this->emailhtml->email_header($this->data['AdvertiserOrder']['county']);
												$this->body .=$bodyData;
												$this->body .= $this->emailhtml->email_footer($this->data['AdvertiserOrder']['county']);											
												$this->set('var1',$this->data['AdvertiserOrder']['email']);
												$this->set('var2',$emailArray[0]['settings']['new_advertiser_subject']);
												$this->set('var3',$this->common->getReturnEmail());
												$this->set('var4',$this->common->getFromName().' <'.$this->common->getSalesEmail().'>');
												$this->set('var5',$this->body);											
											//create pdf
												$this->set('fileName',$fileName);
												$this->set('logoname',$logoname);
												$pdf_name = 'order_'.time().''.$advertiser_profile_id.'.pdf';
												$this->set('name',$pdf_name);
											$this->set('redirectUrl',FULL_BASE_URL.router::url('/',false).'advertiser_profiles/thanksPage/'.$advertiser_profile_id);
												$this->set('Email',$this->Email);						
												$this->layout = 'pdf';
												$this->set('common',$this->common);
												$this->render('/advertiser_orders/pdf');
									}
												
											if($saveArray['AdvertiserOrder']['save_later']==1) {
											 	/*$this->Session->setFlash('Your order has been submitted successfully.');  
											 	$this->redirect(array('controller'=>'advertiser_profiles','action' => 'thanksPage',$advertiser_profile_id));
											} else {*/
												$this->Session->setFlash('Your order has been saved successfully.');  
											 	$this->redirect(array('action' => "savedOrder"));
											}										  										  
										}								  
									}
								}
							}
						}
					} else {
							$this->loadModel('AdvertiserProfile');
							//$this->AdvertiserProfile->id = $id;
							 $data = $this->AdvertiserProfile->find('first',array('conditions'=>array('AdvertiserProfile.id'=>$id),'contain' => array('AdvertiserCategory'=>array('CategoriesSubcategory'=>array('Category.id','Subcategory.id')))));
							 
							$this->data = $data;
							$this->data['AdvertiserOrder'] = $this->data['AdvertiserProfile'];
							$this->data['AdvertiserOrder']['advertiser_name'] = $this->data['AdvertiserProfile']['name'];
							$this->set('logo',$this->data['AdvertiserProfile']['logo']);
							//vip offer
					$this->loadModel('VipOffer');
					$vipoffer = $this->VipOffer->find('first',array('fields'=>array('VipOffer.title','VipOffer.description','VipOffer.category'),'conditions'=>array('VipOffer.advertiser_profile_id'=>$this->params['pass'][0])));
					
					$this->data['AdvertiserOrder']['main_offer_discount'] = $vipoffer['VipOffer']['description'];
					$this->data['AdvertiserOrder']['Vip_title'] = $vipoffer['VipOffer']['title'];
					$this->data['AdvertiserOrder']['Vip_Category'] = $vipoffer['VipOffer']['category'];
								
							//pr($this->data['AdvertiserProfile']);
							$order = $this->common->getorderdetail($this->data['AdvertiserProfile']['order_id']);
							$this->data['AdvertiserOrder']['salesperson'] = $this->data['AdvertiserProfile']['creator'];
							//pr($order);
							$this->set('package_id',$order['AdvertiserOrder']['package_id']);		
							$checked = 0;
							$this->set('checked',$checked);
							$subcat_array=array_filter(explode(',',$this->data['AdvertiserProfile']['subcategory']));
							$this->set('subcat_array',$subcat_array);
							$this->loadModel('SavingOffer');
							$main_saving_offer = $this->SavingOffer->find('first',array('conditions'=>array('SavingOffer.advertiser_profile_id'=>$this->data['AdvertiserProfile']['id'],'SavingOffer.current_saving_offer'=>1),'recursive'=>-1));
							$other_saving_offer = $this->SavingOffer->find('all',array('conditions'=>array('SavingOffer.advertiser_profile_id'=>$this->data['AdvertiserProfile']['id'],'SavingOffer.other_saving_offer'=>1),'recursive'=>-1));
							$this->set('offer_count',count($other_saving_offer)+1);
							$a=2;
							//pr($other_saving_offer);
							if(count($other_saving_offer)>0) {
								foreach($other_saving_offer as $other) {
									$extra_disclaimer = '';
									$extra_disclaimer = explode('.',$other['SavingOffer']['disclaimer']);
									if($a==2) {
										$this->set('title_2',$other['SavingOffer']['title']);
										$this->set('description_2',$other['SavingOffer']['description']);
										if(isset($extra_disclaimer[0]))  {
											$this->set('main_offer_dscpt1_2',$extra_disclaimer[0]);
										} else {$this->set('main_offer_dscpt1_2','');}
										if(isset($extra_disclaimer[1]))  {
											$this->set('main_offer_dscpt2_2',$extra_disclaimer[1]);
										} else {$this->set('main_offer_dscpt2_2','');}
										if(isset($extra_disclaimer[2]))  {
											$this->set('main_offer_dscpt3_2',$extra_disclaimer[2]);
										} else {$this->set('main_offer_dscpt3_2','');}
									}
									if($a==3) {
										$this->set('title_3',$other['SavingOffer']['title']);
										$this->set('description_3',$other['SavingOffer']['description']);
										if(isset($extra_disclaimer[0]))  {
											$this->set('main_offer_dscpt1_3',$extra_disclaimer[0]);
										} else {$this->set('main_offer_dscpt1_3','');}
										if(isset($extra_disclaimer[1]))  {
											$this->set('main_offer_dscpt2_3',$extra_disclaimer[1]);
										} else {$this->set('main_offer_dscpt2_3','');}
										if(isset($extra_disclaimer[2]))  {
											$this->set('main_offer_dscpt3_3',$extra_disclaimer[2]);
										} else {$this->set('main_offer_dscpt3_3','');}
									}
									if($a==4) {
										$this->set('title_4',$other['SavingOffer']['title']);
										$this->set('description_4',$other['SavingOffer']['description']);
										if(isset($extra_disclaimer[0]))  {
											$this->set('main_offer_dscpt1_4',$extra_disclaimer[0]);
										} else {$this->set('main_offer_dscpt1_4','');}
										if(isset($extra_disclaimer[1]))  {
											$this->set('main_offer_dscpt2_4',$extra_disclaimer[1]);
										} else {$this->set('main_offer_dscpt2_4','');}
										if(isset($extra_disclaimer[2]))  {
											$this->set('main_offer_dscpt3_4',$extra_disclaimer[2]);
										} else {$this->set('main_offer_dscpt3_4','');}
									}
									if($a==5) {
										$this->set('title_5',$other['SavingOffer']['title']);
										$this->set('description_5',$other['SavingOffer']['description']);
										if(isset($extra_disclaimer[0]))  {
											$this->set('main_offer_dscpt1_5',$extra_disclaimer[0]);
										} else {$this->set('main_offer_dscpt1_5','');}
										if(isset($extra_disclaimer[1]))  {
											$this->set('main_offer_dscpt2_5',$extra_disclaimer[1]);
										} else {$this->set('main_offer_dscpt2_5','');}
										if(isset($extra_disclaimer[2]))  {
											$this->set('main_offer_dscpt3_5',$extra_disclaimer[2]);
										} else {$this->set('main_offer_dscpt3_5','');}
									}
									
									$not_valid_other[$a] = $other['SavingOffer']['no_valid_other_offer'];
									$n_transferable[$a] = $other['SavingOffer']['no_transferable'];
									$others[$a] = $other['SavingOffer']['other'];						
									
									
									$this->set('not_valid_other',$not_valid_other);
									$this->set('n_transferable',$n_transferable);
									$this->set('others',$others);
									$a++;
								}
							}
							if(count($main_saving_offer)>0) {
									$this->data['AdvertiserOrder']['main_offer_title'] 	= $main_saving_offer['SavingOffer']['title'];
									$this->data['AdvertiserOrder']['description_1'] 	= $main_saving_offer['SavingOffer']['description'];
									//$this->data['AdvertiserOrder']['main_offer_discount'] 	= $main_saving_offer['SavingOffer']['off'];
									$this->set('not_valid_other1',$main_saving_offer['SavingOffer']['no_valid_other_offer']);
									$this->set('n_transferable1',$main_saving_offer['SavingOffer']['no_transferable']);
									$this->set('other1',$main_saving_offer['SavingOffer']['other']);
									
									$extra_content = explode('.',$main_saving_offer['SavingOffer']['disclaimer']);
									if(isset($extra_content[0]))  {
										$this->data['AdvertiserOrder']['main_offer_dscpt1'] 	= $extra_content[0];
									}
									if(isset($extra_content[1]))  {
										$this->data['AdvertiserOrder']['main_offer_dscpt2'] 	= $extra_content[1];
									}
									if(isset($extra_content[2]))  {
										$this->data['AdvertiserOrder']['main_offer_dscpt3'] 	= $extra_content[2];
									}
								}
							}							
						} else {
							$this->Session->setFlash('Invalid Order id.');
							$this->redirect(array('action' => "savedOrder"));
						}
	   				}
//------------------------------------------------------------------------------//	   	   
	   	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username',
            'password' => 'password'
            );
			$this->Auth->allow('pdfout');
		$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
   	}
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocomplete($string='') {

			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			$name = $this->AdvertiserOrder->query("SELECT AdvertiserProfile.company_name FROM advertiser_profiles AS AdvertiserProfile WHERE AdvertiserProfile.company_name LIKE '$string%'");
			foreach($name as $name) {
				$arr[] = $name['AdvertiserProfile']['company_name'];
			}
			echo json_encode($arr);
			}
	}	   
	   //Set css for different color options
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
	 
	 
	function drawThickLine ($img, $startX, $startY, $endX, $endY, $colour, $thickness) {

		  $angle = (atan2(($startY - $endY), ($endX - $startX)));

		

		  $dist_x = $thickness * (sin($angle));

		  $dist_y = $thickness * (cos($angle));

		

		  $p1x = ceil(($startX + $dist_x));

		  $p1y = ceil(($startY + $dist_y));

		  $p2x = ceil(($endX + $dist_x));

		  $p2y = ceil(($endY + $dist_y));

		  $p3x = ceil(($endX - $dist_x));

		  $p3y = ceil(($endY - $dist_y));

		  $p4x = ceil(($startX - $dist_x));

		  $p4y = ceil(($startY - $dist_y));

		

		  $array = array(0=>$p1x, $p1y, $p2x, $p2y, $p3x, $p3y, $p4x, $p4y);

		  imagefilledpolygon($img, $array, (count($array)/2), $colour);

		}
		
	function sigJsonToImage ($json, $options = array()) {

		  $defaultOptions = array(

			'imageSize' => array(198, 55)

			,'bgColour' => array(0xff, 0xff, 0xff)

			,'penWidth' => 2

			,'penColour' => array(0x14, 0x53, 0x94)

			,'drawMultiplier'=> 12

		  );

		

		  $options = array_merge($defaultOptions, $options);

		

		  $img = imagecreatetruecolor($options['imageSize'][0] * $options['drawMultiplier'], $options['imageSize'][1] * $options['drawMultiplier']);

		

		  if ($options['bgColour'] == 'transparent') {

			imagesavealpha($img, true);

			$bg = imagecolorallocatealpha($img, 0, 0, 0, 127);

		  } else {

			$bg = imagecolorallocate($img, $options['bgColour'][0], $options['bgColour'][1], $options['bgColour'][2]);

		  }

		

		  $pen = imagecolorallocate($img, $options['penColour'][0], $options['penColour'][1], $options['penColour'][2]);

		  imagefill($img, 0, 0, $bg);

		

		  if (is_string($json))

			$json = json_decode(stripslashes($json));

		

		  foreach ($json as $v)

			$this->drawThickLine($img, $v->lx * $options['drawMultiplier'], $v->ly * $options['drawMultiplier'], $v->mx * $options['drawMultiplier'], $v->my * $options['drawMultiplier'], $pen, $options['penWidth'] * ($options['drawMultiplier'] / 2));

		

		  $imgDest = imagecreatetruecolor($options['imageSize'][0], $options['imageSize'][1]);

		

		  if ($options['bgColour'] == 'transparent') {

			imagealphablending($imgDest, false);

			imagesavealpha($imgDest, true);

		  }
		  imagecopyresampled($imgDest, $img, 0, 0, 0, 0, $options['imageSize'][0], $options['imageSize'][0], $options['imageSize'][0] * $options['drawMultiplier'], $options['imageSize'][0] * $options['drawMultiplier']);
		  imagedestroy($img);
		  return $imgDest;
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
					$this->redirect($url);
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
/*------------------------------------------------------------------------------------------------------------------------------------------------------------*/	
	/* This function is setting all info about current SuperAdmins in 
	currentAdmin array so we can use it anywhere lie name id etc.
	*/
	//function pdf($data,$name) {
	function pdfout() {
	//pr($data);
		$this->layout = 'pdf';//this will use the pdf.ctp layout 
       	$this->render();
	}
	 function beforeRender(){
		    $this->set('currentAdmin', $this->Auth->user());
			$this->set('cssName',$this->Cookie->read('css_name'));
			$this->set('groupDetail',$this->common->adminDetails());
			$this->set('common',$this->common);
			//$this->Ssl->force();
			//$this->set('mobile',$this->mobile);
	  } 
}