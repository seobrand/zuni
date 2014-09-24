<?php 
/******************************************************************************************
 Coder  : Keshav Sharma
 Object : Controller to handle Front pages
******************************************************************************************/
class PagesController extends AppController {
	var $name = 'Page'; //Model name attached with this controller
	var $helpers = array('Html','Paginator','Ajax','Javascript'); //add some other helpers to controller
	var $components = array('Auth','checkurl','common','Session','Cookie','RequestHandler','Email','emailhtml');//add some other component to controller . this component file is exists in app/controllers/components
	
/********************** This function call for all county pages on front end ************************/
	function home($state='',$county='') {
	// Create session to check the page on daily discount
		if(isset($this->params['pass'][2]) && ($this->params['pass'][2]=='buyDiscount' || $this->params['pass'][2]=='dailydiscount')) {} else {
			$this->Session->write('page_type','home');
		}
		$this->Session->delete('contactReferer');
		// delete contest session
			if($this->Session->read('Contest')) {
				$this->Session->delete('Contest');
			}
		// delete contest session
			if($this->Session->read('Deal')) {
				$this->Session->delete('Deal');
			}
		// delete contest session 
			if($this->Session->read('Discount')) {
				$this->Session->delete('Discount');
			}
// end of process
if($this->checkurl->validUrl($this->params)){
	 		App::import('model','City');
		    $this->City = new City();	
			$CitiesList = $this->City->find('list', array('fields' => array('id', 'cityname'),'order' => 'City.cityname ASC','recursive' => -1,'conditions' => array('City.publish' => 'yes','City.county_id'=>$this->Session->read('county_data.id'))));
			$this->set('CitiesList',$CitiesList);
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 											     This section call Profile Pages of VIP								            //		
   	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($this->Session->read('Auth.FrontUser') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='profile') {
			$this->layout = 'profile';						
			$this->render('/front_users/profile');
		}	
		else if($this->Session->read('Auth.FrontUser') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='account') {
			$this->layout = 'profile';
			$this->render('/front_users/account');
		}				
		else if($this->Session->read('Auth.FrontUser') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='feedback') {
			$this->layout = 'profile';
			$this->render('/front_users/feedback');
		}
		else if($this->Session->read('Auth.FrontUser') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='discount_history') {
			$this->layout = 'profile';
			$this->render('/front_users/discount_history');
		}
		else if($this->Session->read('Auth.FrontUser') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='discount_reedem') {
			$this->layout = 'profile';
			$dicount_id = $this->common->getDiscountId($this->params['named']['discount']);
			if($dicount_id) {
				$this->set('dicount_id',$dicount_id);
				$this->loadModel('FrontUser');
				$this->loadModel('DiscountUser');
				$allBuyers=$this->DiscountUser->find('all',array('conditions'=>array('DiscountUser.daily_discount_id'=>$dicount_id)));
				$this->set('allBuyers',$allBuyers);
			}
			$this->render('/front_users/discount_reedem');
		}
		else if($this->Session->read('Auth.FrontUser') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='discount') {
			$condition ='';
			$cur_offer_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			App::import('model','VipOffer'); //importing Article (pages) model,'VipOffer.category LIKE'=>"%,$cat_id,%",'order'=>array('VipOffer.category'=>'asc')
			$county_id_vip = $this->common->getCountyIdByUrl($this->params['pass'][1]);
			$this->VipOffer = new VipOffer();
			$condition =   array('VipOffer.status'=>'yes','VipOffer.offer_start_date <='=>$cur_offer_time,'VipOffer.offer_expiry_date >='=>$cur_offer_time,'VipOffer.advertiser_county_id'=>$county_id_vip);
			$catsdata =$this->VipOffer->find('all',array('conditions'=>$condition));
			 if(count($catsdata)!=0) {
			 	$cat_str='';
			 	$cat_arr=array();
			
			 foreach($catsdata as $catsdata1)
			 {
			 	$cat_str.=$catsdata1['VipOffer']['category'].',';
			 }
			
			 $cat_arr=array_filter(array_unique(explode(',',$cat_str)));
			 $cat_array_main=implode(",",$cat_arr);
			
			 }
			 else
			 {
			 $cat_array_main = 0;
			 }

 			App::import('model','Category');
			$this->Category = new Category();
			$condition_cat='';
			//echo $cat_array_main;
			$condition_cat[]= "Category.id IN (".$cat_array_main.") ";	
			$condition_cat[]= "Category.publish='yes'";			
			 $this->paginate = array( 'limit' =>20,'order' => array( 'Category.categoryname' => 'asc' ));
			 $data_cat = $this->paginate('Category', $condition_cat);
			 $this->set('categories',$data_cat);

			$this->layout = 'profile';
			$this->render('/front_users/discount');
		}
		
		else if(!$this->Session->read('Auth.FrontUser') && isset($this->params['pass'][2]) && ($this->params['pass'][2]=='profile' || $this->params['pass'][2]=='account' || $this->params['pass'][2]=='feedback' || $this->params['pass'][2]=='discount_history' || $this->params['pass'][2]=='discount_reedem' || $this->params['pass'][2]=='discount')) {
			$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1]);
		}
				
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 											     This section call Profile Pages of Consumer							        //		
   	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		else if($this->Session->read('Auth.FrontConsumer') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='info') {
			$this->layout = false;
			//find daily discount for today
			
			$this->loadModel('DailyDeal');
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
			$this->set('daily_deal',$daily_deal);

			//find daily discount for today
			$this->loadModel('DailyDiscount');
			$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
			$this->set('daily_disc',$daily_disc);
			
			$this->render('/front_users/info');
		}
		else if($this->Session->read('Auth.FrontConsumer') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='spend') {
		
		
		//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
			
			
					//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
						
						
						
						
			$this->loadModel('FrontUser');
			$bucks_left = $this->FrontUser->find('first',array('fields'=>array('FrontUser.total_bucks'),'conditions'=>array('FrontUser.id'=>$this->Session->read('Auth.FrontConsumer.id'))));
			$condition ='';
			$cur_offer_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			App::import('model','Voucher'); // importing Article (pages) model,'VipOffer.category LIKE'=>"%,$cat_id,%",'order' => array( 'VipOffer.category' => 'asc' )
			$this->Voucher = new Voucher();
			 
			$this->loadModel('Setting');
		 	$waiting_time = $this->Setting->find('first',array('fields'=>array('Setting.waiting_gift'),'conditions'=>array('Setting.id'=>1)));
		 	$waiting_time = $waiting_time['Setting']['waiting_gift'];
		 
			$this->loadModel('Order');
			$block_arr = '';
			$month_back = mktime(0,0,0,date('m'),date('d')-$waiting_time,date('Y'));
			$block_ad_id = $this->Order->find('all',array('fields'=>array('Order.advertiser_profile_id'),'conditions'=>array('Order.front_user_id'=>$this->Session->read('Auth.FrontConsumer.id'),"Order.order_date>$month_back")));
			
			if(is_array($block_ad_id)) {
					foreach($block_ad_id as $block_ad_id) {
						$block_arr[] =  $block_ad_id['Order']['advertiser_profile_id'];
					}
			}
			
			if(isset($block_arr) && is_array($block_arr)) {
						$colloect_block = 'Voucher.advertiser_profile_id NOT IN ('.implode(',',$block_arr).')';
					} else {
						$colloect_block = 'Voucher.advertiser_profile_id NOT IN (0)';
			}
				
			//$condition =   array('Voucher.advertiser_county_id'=>$this->Session->read('county_data.id'),'Voucher.price <='.$bucks_left['FrontUser']['total_bucks'],'Voucher.status'=>'yes','Voucher.s_date <='=>$cur_offer_time,'Voucher.e_date >='=>$cur_offer_time,$colloect_block);
			$condition =   array('Voucher.advertiser_county_id'=>$this->Session->read('county_data.id'),'Voucher.status'=>'yes','Voucher.s_date <='=>$cur_offer_time,'Voucher.e_date >='=>$cur_offer_time,$colloect_block);
			
			$catsdata =$this->Voucher->find('all',array('conditions'=>$condition));
			$this->loadModel('Order');
			if(count($catsdata)!=0) {
			 $cat_str='';
			 $cat_arr=array();
			 foreach($catsdata as $catsdata1)
			 {
			 	//$countOrder = $this->Order->find('count',array('conditions'=>array('Order.voucher_id'=>$catsdata1['Voucher']['id'],'Order.front_user_id'=>$this->Session->read('Auth.FrontConsumer.id'))));
				//if($countOrder==0) {
			 		$cat_str[]=$catsdata1['Voucher']['category_id'];
				//}
			 }
			 $cat_arr=array_filter(array_unique($cat_str));
			 $cat_array_main=implode(",",$cat_arr);
			} else {
				$cat_array_main = 0;
			}
 			App::import('model','Category'); // importing Article (pages) model,'VipOffer.category LIKE'=>"%,$cat_id,%",'order' => array( 'VipOffer.category' => 'asc' )
			$this->Category = new Category();
			$condition_cat='';
			$condition_cat[]= "Category.id IN (".$cat_array_main.") ";	
			$condition_cat[]= "Category.publish='yes'";	
			$this->paginate = array('order' => array( 'Category.categoryname' => 'asc' ));
			$catsdata = $this->paginate('Category', $condition_cat);
			//pr($catsdata);
			$this->set('categories', $catsdata);
			$this->layout = false;
			$this->render('/front_users/spend');
		}
		
		else if($this->Session->read('Auth.FrontConsumer') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='spendBucks') {
			
			if(isset($this->params['url']['gift']) && $this->params['url']['gift']!='') {
			
			$voucher = base64_decode(base64_decode($this->params['url']['gift']));
			
			$this->loadModel('Order');
			$check = $this->Order->find('count',array('conditions'=>array('Order.voucher_id'=>$voucher,'Order.front_user_id'=>$this->Session->read('Auth.FrontConsumer.id'))));
			if($check) {
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1].'/spend');
				exit;
			}
			
			$detail = $this->common->voucher_detail($voucher);
			if(!empty($detail)) {
					$this->set('detail',$detail);
					//find daily discount for today
					$this->loadModel('DailyDeal');
					$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
					$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
					$this->set('daily_deal',$daily_deal);
		
					//find daily discount for today
					$this->loadModel('DailyDiscount');
					$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
					$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
					$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
					$this->set('daily_disc',$daily_disc);
					$this->layout = false;
					$this->render('/front_users/spendBucks');
				} else {
					$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1]);
				}
			} else {
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1]);
			}
			
			
		}
		
		else if($this->Session->read('Auth.FrontConsumer') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='refer_business') {
			$this->layout = false;
			if($this->Session->read('reffer_business')) {
				$this->set('popSet',1);
			}else{
				$this->set('popSet',0);
			}
			$this->Session->delete('reffer_business');
			$this->loadModel('DailyDeal');
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
			$this->set('daily_deal',$daily_deal);


		//find daily discount for today
			$this->loadModel('DailyDiscount');
			$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
			$this->set('daily_disc',$daily_disc);
			$this->render('/front_users/refer_business');
		}
		else if($this->Session->read('Auth.FrontConsumer') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='refer_friend') {
			//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
			
			
					//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
						$this->layout = false;
						$this->render('/front_users/refer_friend');
		}	  
		 
		else if(!$this->Session->read('Auth.FrontConsumer') && isset($this->params['pass'][2]) && ($this->params['pass'][2]=='info' || $this->params['pass'][2]=='spend' || $this->params['pass'][2]=='spendBucks' || $this->params['pass'][2]=='refer_business' || $this->params['pass'][2]=='refer_friend')) {
			$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1]);
		}
		  //Set default layout
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//												This section call Index Page													//
	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   else if(count($this->params['pass'])<=2 && !isset($this->params['pass'][2])) {
			$this->Cookie->write('LastUrl', $this->params['url']['url'], true, '+30 day');
			$this->layout = 'default';
			$error = '';
					 	
				//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
				//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
						//$this->Session->write('daily_discount_id',$daily_disc['DailyDiscount']['id']);
						//$this->Session->write('daily_deal_id',$daily_deal['DailyDeal']['id']);
				
				//this increase the page view counter in database(discount reporting)
				   $st_id='';
				   $county_id='';
				   $condi_report='';
				   $st_id=$this->common->getIdfromPageUrl('State',$this->params['pass'][0]);
				   $county_id=$this->common->getIdfromPageUrl('County',$this->params['pass'][1]);
				   App::import('model','Report');
				   $this->Report=new Report();
				   $timestamp=$this->common->getTimeStampReport();
				   $st_id=$st_id['State']['id'];
				   $county_id=$county_id['County']['id'];
				   $condi_report['Report.state']=$st_id;
				   $condi_report['Report.county']=$county_id;
				   $condi_report['Report.date']=$timestamp;
				   $condi_report['Report.type']=1;
				   $exist_rec=$this->Report->find('first',array('conditions'=>$condi_report));
				  
				   if(empty($exist_rec))
				   {
					   $reportArray=array();
					   $reportArray['Report']['state']=$st_id;
					   $reportArray['Report']['county']=$county_id;
					   $reportArray['Report']['date']=$timestamp;
					   $reportArray['Report']['type']=1;
					   $reportArray['Report']['no_of_hit']=1;
					   $this->Report->save($reportArray);
				   }
				   else
				   {
					   $reportArray=array();
					   $reportArray['Report']['id']=$exist_rec['Report']['id'];
					   $reportArray['Report']['no_of_hit']=$exist_rec['Report']['no_of_hit']+1;
					   $reportArray['Report']['state']=$st_id;
					   $reportArray['Report']['county']=$county_id;
					   $reportArray['Report']['date']=$timestamp;
					   $reportArray['Report']['type']=1;
					   $this->Report->save($reportArray);
				   }
				}
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//												This section call Contest Page													//
	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  else if(count($this->params['pass'])<=3 && $this->params['pass'][2]=='contest') {
				$this->layout = 'contest';
				//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
				//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
						if($this->Session->read('Auth.FrontConsumer.id')) {
							$this->render('contest');
						} else {
							$this->render('nonLoginContest');
						}
				}

		elseif(count($this->params['pass'])<=3 && $this->params['pass'][2]=='advertiser') {
					$this->layout = false;
					//find daily discount for today
					$this->loadModel('DailyDeal');
					$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
					$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
					$this->set('daily_deal',$daily_deal);
					
				//find daily discount for today
					$this->loadModel('DailyDiscount');
					$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
					$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
					$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
					$this->set('daily_disc',$daily_disc);
					$this->render('/front_users/advertiser');
		}
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 											     This section calls DAILY DISCOUNT							                	//	
   	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		elseif(count($this->params['pass'])<=3 && $this->params['pass'][2]=='dailydiscount') {
					$this->layout = 'dailydiscount';
					
					if(isset($this->params['pass'][2]) && $this->params['pass'][2]!='' && $this->params['pass'][2]=='dailydiscount' && isset($this->params['url']['unique']) && $this->params['url']['unique']!='')
					 {}else{
						$this->set('title_for_layout','Daily Discount : '.ucfirst($this->params['pass'][1]));
						$this->set('keyword_for_layout','Daily Discount : '.ucfirst($this->params['pass'][1]));
						$this->set('description_for_layout','Daily Discount : '.ucfirst($this->params['pass'][1]));
					}
					//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
					//email tracking
					if(isset($this->params['url']['unique'])) {
						$contestString = explode('contest',$this->params['url']['unique']);
						$breakstring = explode('?',$contestString[0]);
						if(count($breakstring)==3) {
							$this->common->saveEmailOpen($breakstring[1],base64_decode($breakstring[2]));
						}
						if(isset($contestString[1])) {
							$contestbreak = explode('?',$contestString[1]);
							if(count($contestbreak)==2) {
								$this->common->saveContestLinkOpen($contestbreak[0],base64_decode($contestbreak[1]));
							}
						}
						$disc = $this->DailyDiscount->find('first',array('conditions'=>array('DailyDiscount.unique'=>$breakstring[0])));
					}else if($this->Session->read('daily_discount_id')) {
							$disc = $this->DailyDiscount->find('first',array('conditions'=>array("DailyDiscount.id=".$this->Session->read('daily_discount_id')." AND ((DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2) || (DailyDiscount.c_s_date<=$today2 AND DailyDiscount.c_e_date>=$today2)) AND DailyDiscount.status='yes' AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id'))));
						} else {
							$disc = $this->DailyDiscount->find('first',array('conditions'=>array("DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.show_on_home_page = 1 AND DailyDiscount.status='yes' AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id'))));
						}
						$this->set('disc',$disc);
		/*------------------------this increase the page view counter in database(discount reporting)--------------------------*/
				   $st_id='';
				   $county_id='';
				   $condi_report='';
				   $st_id=$this->common->getIdfromPageUrl('State',$this->params['pass'][0]);
				   $county_id=$this->common->getIdfromPageUrl('County',$this->params['pass'][1]);
				   App::import('model','InnerReport');
				   $this->InnerReport=new InnerReport();
				   $timestamp=$this->common->getTimeStampReport();
				   $st_id=$st_id['State']['id'];				   
				   $county_id=$county_id['County']['id'];
				   $condi_report['InnerReport.state']=$st_id;
				   $condi_report['InnerReport.county']=$county_id;
				   $condi_report['InnerReport.date']=$timestamp;
				   $condi_report['InnerReport.type']='discount';
				   $condi_report['InnerReport.advertiser_id']=$disc['DailyDiscount']['advertiser_profile_id'];
				   $exist_rec=$this->InnerReport->find('first',array('conditions'=>$condi_report));
					
				   if(empty($exist_rec))
				   {
					   $reportArray=array();
					   $reportArray['InnerReport']['state']=$st_id;
					   $reportArray['InnerReport']['county']=$county_id;
					   $reportArray['InnerReport']['date']=$timestamp;
					   $reportArray['InnerReport']['type']='discount';
					   $reportArray['InnerReport']['advertiser_id']=$disc['DailyDiscount']['advertiser_profile_id'];
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
					   $reportArray['InnerReport']['advertiser_id']=$disc['DailyDiscount']['advertiser_profile_id'];
					   $reportArray['InnerReport']['type']='discount';
					   $this->InnerReport->save($reportArray);
				   }	
						
		/*---------------------------------------------------------------------------------------*/								
						$cond_disc = '';
						$date_valid = '';
						
						if($this->Session->read('page_type') && $this->Session->read('page_type')=='cate') {
							$cat = $disc['DailyDiscount']['category'];
							$subcat = $disc['DailyDiscount']['subcategory'];
							$cond_disc = " AND DailyDiscount.show_on_category = 1 AND DailyDiscount.category=$cat AND DailyDiscount.subcategory=$subcat";
							$date_valid = " AND DailyDiscount.c_s_date<=$today2 AND DailyDiscount.c_e_date>=$today2";
						}
						else if($this->Session->read('page_type') && $this->Session->read('page_type')=='home') {
							$cond_disc = " AND DailyDiscount.show_on_home_page = 1";
							$date_valid = " AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2";
						}
						else {
							$cond_disc = " AND DailyDiscount.show_on_home_page = 1";
							$date_valid = " AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2";
						}					
						//Find remaining daily discounts
						$all_daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.id<>".$disc['DailyDiscount']['id']." $date_valid AND DailyDiscount.status='yes' AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')."".$cond_disc."".$date_valid)));
						$this->set('all_daily_disc',$all_daily_disc);
						
				
				
				if($this->Session->read('page_type') && $this->Session->read('page_type')=='cate') {
						
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.c_s_date<=$today2 AND DailyDiscount.c_e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.category=".$cat." AND DailyDiscount.subcategory=".$subcat." AND DailyDiscount.show_on_category=1"),'order'=>array('RAND()')));
				$this->set('daily_disc',$daily_disc);
				
				//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.c_s_date<=$today AND DailyDeal.c_e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.category=".$cat." AND DailyDeal.subcategory=".$subcat." AND DailyDeal.show_on_category=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
						
						} else {
						//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
			
			
					//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
			
			}
			
						$this->render('dailydiscount');
				}
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 											     This section calls Buy DAILY DISCOUNT							                	//	
   	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		elseif(count($this->params['pass'])<=3 && $this->params['pass'][2]=='buyDiscount') {
					$this->layout = 'dailydiscount';
					
					if(!isset($this->params['url']['unique']) || $this->params['url']['unique']=='') {
					
						$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1]);
						exit;
						
						
					} else if(!$this->Session->read('Auth.FrontConsumer')) {
						$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1].'/dailydiscount?unique='.$this->params['url']['unique']);
						exit;
					}
					
					if(isset($this->params['pass'][2]) && $this->params['pass'][2]!='' && $this->params['pass'][2]=='buyDiscount' && isset($this->params['url']['unique']) && $this->params['url']['unique']!='')
					 {}else{
						$this->set('title_for_layout','Daily Discount : '.ucfirst($this->params['pass'][1]));
						$this->set('keyword_for_layout','Daily Discount : '.ucfirst($this->params['pass'][1]));
						$this->set('description_for_layout','Daily Discount : '.ucfirst($this->params['pass'][1]));
					}
					//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
					//email tracking
					if(isset($this->params['url']['unique'])) {
						$disc = $this->DailyDiscount->find('first',array('conditions'=>array('DailyDiscount.unique'=>$this->params['url']['unique'])));
					}else if($this->Session->read('daily_discount_id')) {
							$disc = $this->DailyDiscount->find('first',array('conditions'=>array("DailyDiscount.id=".$this->Session->read('daily_discount_id')." AND ((DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2) || (DailyDiscount.c_s_date<=$today2 AND DailyDiscount.c_e_date>=$today2)) AND DailyDiscount.status='yes' AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id'))));
						} else {
							$disc = $this->DailyDiscount->find('first',array('conditions'=>array("DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.show_on_home_page = 1 AND DailyDiscount.status='yes' AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id'))));
						}
						$this->set('disc',$disc);
						
						//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
			
			
					//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
			
			
			
						$this->render('buyDiscount');
				}				
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 											     This section calls DAILY DEAL							                	//	
   	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*else if(!$this->Session->read('Auth.FrontConsumer') && isset($this->params['pass'][2]) && $this->params['pass'][2]=='login'){
			$this->layout = 'login';	
			//pr($this->Session->read('login_redirect'));			
			$this->render('login');
		}*/ elseif(count($this->params['pass'])<=3 && $this->params['pass'][2]=='dailydeal') {
		
				
					
					
			//if($this->Session->read('Auth.FrontConsumer')) {
				$this->loadModel('DailyDeal');
				$this->layout = 'dailydeal';
				if(isset($this->params['pass'][2]) && $this->params['pass'][2]!='' && $this->params['pass'][2]=='dailydeal' && isset($this->params['url']['unique']) && $this->params['url']['unique']!='')
					 {}else{
					$this->set('title_for_layout','Daily Deal : '.ucfirst($this->params['pass'][1]));
					$this->set('keyword_for_layout','Daily Deal : '.ucfirst($this->params['pass'][1]));
					$this->set('description_for_layout','Daily Deal : '.ucfirst($this->params['pass'][1]));
				}
				//find daily discount for today
					if(isset($this->params['url']['unique'])) {
					
						$breakstring = explode('pgrnz6gh',$this->params['url']['unique']);
						if(count($breakstring)==2) {
							$interdata = explode('?',$breakstring[1]);
							$this->common->saveFreebieLinkOpen($interdata[0],base64_decode($interdata[1]));
						}
					
					
					//find daily discount for today
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
						
					//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
						
							
						
						$DailyDeal = $this->DailyDeal->find('first',array('conditions'=>array('DailyDeal.unique'=>$breakstring[0])));
						if(is_array($DailyDeal) && !empty($DailyDeal)) {
							$this->set('DailyDeal',$DailyDeal);
							$this->common->dealReport($DailyDeal['DailyDeal']['advertiser_profile_id'],$this->params['pass'][0],$this->params['pass'][1]);
							$this->render('dailydeal');
						} else {
							$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1]);
						}
					} else {
						$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1]);
					}
				/*} else {
							$this->set('title_for_layout','Daily Deal : '.ucfirst($this->params['pass'][1]));
							$this->set('keyword_for_layout','Daily Deal : '.ucfirst($this->params['pass'][1]));
							$this->set('description_for_layout','Daily Deal : '.ucfirst($this->params['pass'][1]));
							$this->layout = 'login';		
							$this->render('login');

				}	*/
			}
		 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 											     This section calls top ten search page											//		
   	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   
	   /*------------------------------(code for toptensearch page by business_name )(Coder:- manoj{Sep.4th,2011})----------------------------*/
	   
		elseif(count($this->params['pass'])==4 && $this->params['pass'][2]=='business' && $this->params['pass'][3]!='coupon') {
						//$this->layout = 'topten';	
						$state = $this->Session->read('state');
						$county_id = $this->Session->read('county_data.id');					 
				// find all city	 
						 $cityList=$this->common->getAllCity();
						 $this->set('cityList',$cityList);	
						 
						$this->Session->write('discount_proof_cats','');
						//$this->Session->write('discount_proof_subcats','');
						// Set page type
						$this->Session->write('page_type','');
						
						
//---------*************************** commented during launch process for display same deal n discount as home page ******************-------------------//							
				/*//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('first',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
						
				//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('first',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
						$this->Session->write('daily_discount_id',$daily_disc['DailyDiscount']['id']);
						$this->Session->write('daily_deal_id',$daily_deal['DailyDeal']['id']);*/
						
															
						//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
				//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
						//$this->Session->write('daily_discount_id',$daily_disc['DailyDiscount']['id']);
						//$this->Session->write('daily_deal_id',$daily_deal['DailyDeal']['id']);	
						
						
						
						
						
						$conditions='';
						$business= $this->params['pass'][3];
						$business_alias = $this->common->makeAlias($business);
					//to set the county in codition array by default
						$conditions['AdvertiserProfile.county'] = $county_id;
		
					//to set the published field by default
						$conditions['AdvertiserProfile.publish'] = 'yes';
						
					//if business is set
						$conditions['AdvertiserProfile.page_url LIKE'] = '%'.$business_alias.'%';
						
					// find all county	 
							 $countyList=$this->common->getAllCounty();
							 $this->set('countyList',$countyList);
							 
					// find all state
							 $stateList=$this->common->getAllState();
							 $this->set('stateList',$stateList);
							 
					// find all country	 
							 $countryList=$this->common->getAllCountry();
							 $this->set('countryList',$countryList);						 
							 	
					if(isset($this->params['pass'][3]))
						{
							
					//manage the highlighted business for home page
						 App::import('model','AdvertiserProfile');
						 
						 $this->AdvertiserProfile=new AdvertiserProfile();
						 					 		
						 
					//to find the advertiser profile data		
					 $map_address=$this->AdvertiserProfile->find('all',array('conditions'=>$conditions,'recursive'=>-1));
					//find the small image of advertiser
					 $ss_offer='';
						for($m=0;$m<count($map_address);$m++)
						{
							//$ss_offer_demo=$this->common->getmainSavingOfferImg_topten($map_address[$m]['AdvertiserProfile']['id']);
							$ss_offer_demo=$this->common->getmainSavingOfferBusiness($map_address[$m]['AdvertiserProfile']['id']);
							if(isset($ss_offer_demo) and !empty($ss_offer_demo))
							{
								$ss_offer[] = $ss_offer_demo;
							}
						}
						//pr($ss_offer);
							$this->set('high_business',$ss_offer);						
					
					//set the adv_id array
						$adv_id='';
						if(!empty($ss_offer))
						{
							for($n=0;$n<count($ss_offer);$n++){
								$adv_id[]=$ss_offer[$n]['SavingOffer']['advertiser_profile_id'];
							}
						}

													
							$a = 0;
							$address1 = '';
						//to make the full map address of advertiser				
						if(!empty($adv_id))
						{									
								 foreach($map_address as $address)
								 {				

								 	if(in_array($address['AdvertiserProfile']['id'],$adv_id))
									{
										 
										 
										 
										 
										 $county= $countyList[$address['AdvertiserProfile']['county']];
										 
										 $state= $stateList[$address['AdvertiserProfile']['state']];
										 
										 $country= $countryList[$address['AdvertiserProfile']['country']];
										 
										 $name= $address['AdvertiserProfile']['name'];
										 
										 $company_name= $address['AdvertiserProfile']['company_name'];
										 
										 $logo= $address['AdvertiserProfile']['logo'];
										 
										 $image = "<img src='".FULL_BASE_URL.router::url('/',false)."app/webroot/img/logo/".$logo."' width='60' height='40'/><br />";
										 
										
										if($address['AdvertiserProfile']['show_address']=='yes') {
											$add = $address['AdvertiserProfile']['address'];
											$city = '';
											if(isset($cityList[$address['AdvertiserProfile']['city']])) {
												$city= $cityList[$address['AdvertiserProfile']['city']];
											}
											$zip= $address['AdvertiserProfile']['zip'];
											$phone= $address['AdvertiserProfile']['phoneno'];
											$address1[$a][]=$add.' '.$city.' '.$county.' '.$state.' '.$country;
										 	$address1[$a][]='<strong>'.ucwords(strtolower($company_name)).'</strong><br/>'.$phone.'<br />'.$add.' '.$city.' '.$county.'<br />'.$state.' '.$country.' '.$zip;
											$a++;
										 }										 
										 if($address['AdvertiserProfile']['show_address2']=='yes') {
										 	$add = $address['AdvertiserProfile']['address2'];
											$city = '';
											if(isset($cityList[$address['AdvertiserProfile']['city2']])) {
												$city= $cityList[$address['AdvertiserProfile']['city2']];
											}
											$zip= $address['AdvertiserProfile']['zip2'];
											$phone= $address['AdvertiserProfile']['phoneno2'];
											$address1[$a][]=$add.' '.$city.' '.$county.' '.$state.' '.$country;
										 	$address1[$a][]='<strong>'.ucwords(strtolower($company_name)).'</strong><br/>'.$phone.'<br />'.$add.' '.$city.' '.$county.'<br />'.$state.' '.$country.' '.$zip;
											$a++;
										 }
 										 $company_link[]=$address['AdvertiserProfile']['page_url'];
										 
										 
									 }
								 }	
								 $this->set('company_link',$company_link);
							   }					
							 $this->set('address',$address1);
							 }
							 $this->set('sel_business','Enter the Business Name :');
							 //$this->render('top_ten_area');
							 $this->render('topten_search');				
			}

			/*------------------------------(code for merchant page to view all details)(Coder:- manoj{Sep.5th,2011})------------------------*/
			
					elseif((count($this->params['pass'])==6 && ($this->common->validateCity($this->params)!='0')) || (count($this->params['pass'])==5 && $this->checkurl->chkCountyUrl($this->params['pass'][0],$this->params['pass'][1])!='0' && $this->checkurl->chkCompanyUrl($this->params['pass'][4])!='0' && $this->checkurl->chkCity($this->params['pass'][2],$this->params['pass'][1],$this->params['pass'][0])==0) || (count($this->params['pass'])==5 && $this->params['pass'][2]=='business' &&  $this->params['pass'][3]=='coupon')) {
					$this->set('page_type','merchant');
					if(isset($this->params['url']['unique'])) {
						$breakstring = explode('?',$this->params['url']['unique']);
						if(count($breakstring)==2) {
							$this->common->saveOfferLinkOpen($breakstring[0],base64_decode($breakstring[1]));
						}
					}
					
					if(isset($this->params['url']['care'])) {
						$breakstring = explode('?',$this->params['url']['care']);
						if(count($breakstring)==2) {
							$this->common->saveCareLinkOpen($breakstring[0],base64_decode($breakstring[1]));
						}
					}
					
					//if categorry, subcat and city are set
						 if(count($this->params['pass'])==6)	
						 	$pageurl = $this->params['pass'][5];
					//if categorry, subcat and city are set
						 if(count($this->params['pass'])==5 && $this->params['pass'][2]!='business')
						 	$pageurl = $this->params['pass'][4];							 
					//if business name is  set
						 if(count($this->params['pass'])==5 && $this->params['pass'][2]=='business')
							$pageurl = $this->params['pass'][4];
							
							
							
					/*$this->redirect(FULL_BASE_URL.router::url('/',false).'merchants/'.$pageurl);
					exit;*/
					//if(count($this->params['pass'])==5 && $this->params['pass'][2]!='business')
						$this->layout='default2';
						
						App::import('model','AdvertiserProfile');
						$this->AdvertiserProfile=new AdvertiserProfile();
						$cond['AdvertiserProfile.publish']='yes';	
					//if categorry, subcat and city are set
						 if(count($this->params['pass'])==6)	
						 	$cond['AdvertiserProfile.page_url']=$this->params['pass'][5];
					//if categorry, subcat and city are set
						 if(count($this->params['pass'])==5 && $this->params['pass'][2]!='business')
						 	$cond['AdvertiserProfile.page_url']=$this->params['pass'][4];							 
					//if business name is  set
						 if(count($this->params['pass'])==5 && $this->params['pass'][2]=='business')
							$cond['AdvertiserProfile.page_url']=$this->params['pass'][4];
							
					$adv_data=$this->AdvertiserProfile->find('first',array('conditions'=>$cond));
					
					////////////////////////////////////////
					// find all city	 
					 $cityList=$this->common->getAllCity();
					 $this->set('cityList',$cityList);	 					
					 
					 // find all county	 
							 $countyList=$this->common->getAllCounty();
							 $this->set('countyList',$countyList);
							 
					// find all state
							 $stateList=$this->common->getAllState();
							 $this->set('stateList',$stateList);
							 
					// find all country	 
							 $countryList=$this->common->getAllCountry();
							 $this->set('countryList',$countryList);						
					
					///////////////////map code///////////////////
							$address1 = '';	

								 	if($adv_data['AdvertiserProfile']['id'])
									{
										$add = '';
										$city = '';
										$zip = '';
										$phone = '';
										$showAddress = 0;
										if($adv_data['AdvertiserProfile']['show_address']=='yes') {
											$showAddress = 1;
										 	$add[] = $adv_data['AdvertiserProfile']['address'];
											$city[] = '';
											if(isset($cityList[$adv_data['AdvertiserProfile']['city']])) {
												$city[]= $cityList[$adv_data['AdvertiserProfile']['city']];
											}
											$zip[]= $adv_data['AdvertiserProfile']['zip'];
											$phone[]= $adv_data['AdvertiserProfile']['phoneno'];
										 }										 
										 if($adv_data['AdvertiserProfile']['show_address2']=='yes') {
										 	$showAddress = 1;
											$city[] = '';
										 	$add[] = $adv_data['AdvertiserProfile']['address2'];
											if(isset($cityList[$adv_data['AdvertiserProfile']['city2']])) {
												$city[]= $cityList[$adv_data['AdvertiserProfile']['city2']];
											}
											$zip[]= $adv_data['AdvertiserProfile']['zip2'];
											$phone[]= $adv_data['AdvertiserProfile']['phoneno2'];
										 }										 
										 $county= $countyList[$adv_data['AdvertiserProfile']['county']];
										 
										 $state= $stateList[$adv_data['AdvertiserProfile']['state']];
										 
										 $country= $countryList[$adv_data['AdvertiserProfile']['country']];
										 										 
										 $name= $adv_data['AdvertiserProfile']['name'];
										 
										 $company_name= $adv_data['AdvertiserProfile']['company_name'];
										 
										 $logo= $adv_data['AdvertiserProfile']['logo'];
										 
										 //$image = "<img src='".FULL_BASE_URL.router::url('/',false)."app/webroot/img/logo/".$logo."' width='60' height='40'/><br />";
										 $o = 0;
										 if(is_array($add)) {
											 foreach($add as $add) {	
													$address1[$o][0]=$add.', '.$city[$o].', '.$county.', '.$state.', '.$country;									 
													//$address1[$o][1]=$image.'<strong>'.ucwords(strtolower($company_name)).'</strong><br />Contact : '.ucwords(strtolower($name)).'<br/>'.$phone[$o].'<br />'.$add.' '.$city[$o].' '.$county.'<br />'.$state.' '.$country.' '.$zip[$o];
													$address1[$o][1]='<strong>'.ucwords(strtolower($company_name)).'</strong><br />'.$phone[$o].'<br />'.$add.' '.$city[$o].' '.$county.'<br />'.$state.' '.$country.' '.$zip[$o];												
												$o++;
											 }
										 } else {
										 		$address1[0][0]=$county.', '.$state.', '.$country;										 
												//$address1[0][1]=$image.'<strong>'.ucwords(strtolower($company_name)).'</strong><br />Contact : '.ucwords(strtolower($name)).'<br/>'.$county.'<br />'.$state.' '.$country;
												$address1[0][1]='<strong>'.ucwords(strtolower($company_name)).'</strong><br />'.$county.'<br />'.$state.' '.$country;
										 }
										 //$address1[0][0]=$add.', '.$city.', '.$county.', '.$state.', '.$country;										 
										 //$address1[0][1]=$image.'<strong>'.ucwords(strtolower($company_name)).'</strong><br />Contact : '.ucwords(strtolower($name)).'<br/>'.$phone.'<br />'.$add.' '.$city.' '.$county.'<br />'.$state.' '.$country.' '.$zip;
								 }
								 $this->set('address',$address1);
								 $this->set('showAddress',$showAddress);
					/////////////////////////////////////
					$this->set('add',$adv_data);
					///////////////video section/////////////////////
					$video=$this->common->getVedio_front($adv_data['AdvertiserProfile']['id']);
					$this->set('vedio',$video);
					/////////////////////////////////////////////////
					
					///////////////video section/////////////////////
					$images=$this->common->getImages_front($adv_data['AdvertiserProfile']['id']);
					$this->set('images',$images);
					/////////////////////////////////////////////////
					
				//fetch the main saving offer
				
					if(isset($this->params['url']['offer'])) {
						$breakit = explode('?',$this->params['url']['offer']);
						$saving_offer_big=$this->common->SavingOfferUnique($breakit[0]);
						if(isset($saving_offer_big) && !empty($saving_offer_big))
						{
							$saving_offer_smalls=$this->common->getotherSavingOfferImg_merchantUnique($adv_data['AdvertiserProfile']['id'],$saving_offer_big['SavingOffer']['id']);
							$saving_offer_bigArr[0]=$saving_offer_big;
							
							$final_offers_arr=array_merge($saving_offer_bigArr,$saving_offer_smalls);
							
							$this->set('all_saving_offers',$final_offers_arr);
						}else{
							$saving_offer_small=$this->common->getotherSavingOfferImg_merchant($adv_data['AdvertiserProfile']['id']);
					
							$this->set('all_saving_offers',$saving_offer_small);
						}
						
					}else{
						//fetch the other saving offer
						$saving_offer_small=$this->common->getotherSavingOfferImg_merchant($adv_data['AdvertiserProfile']['id']);
						
						$this->set('all_saving_offers',$saving_offer_small);	
						
					}
						//----------------------Redirect to home page, if no offer available for that advertiser-----------------------//
						if(empty($saving_offer_small))
						{
							$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1]);
						}
						//--------------------------------------------------------------------------------------------------------------//
					
					
				//this increase the page view counter in database(business reporting)
				   $st_id='';
				   $county_id='';
				   $rep_cat_id='';
				   $rep_subcat_id='';
				   $rep_city_id='';
				   $rep_company_id='';
				   $condi_report='';
				   $st_id=$this->common->getIdfromPageUrl('State',$this->params['pass'][0]);
				   $county_id=$this->common->getIdfromPageUrl('County',$this->params['pass'][1]);
				   if(count($this->params['pass'])==6)
				   {
					   $rep_city_id=$this->common->getIdfromPageUrl('City',$this->params['pass'][2]);
					   $rep_cat_id=$this->common->getIdfromPageUrl('Category',$this->params['pass'][3]);
					   $rep_subcat_id=$this->common->getIdfromPageUrl('Subcategory',$this->params['pass'][4]);
					   $rep_company_id=$this->common->getIdfromPageUrl('AdvertiserProfile',$this->params['pass'][5]);
					   $rep_city_id=$rep_city_id['City']['id'];
					   $rep_cat_id=$rep_cat_id['Category']['id'];
					   $rep_subcat_id=$rep_subcat_id['Subcategory']['id'];
					   $rep_company_id=$rep_company_id['AdvertiserProfile']['id'];	
					  	   
				   }
				   elseif(count($this->params['pass'])==5 && $this->params['pass'][2]!='business' && $this->params['pass'][3]!='coupon')
				   {
					   $rep_cat_id=$this->common->getIdfromPageUrl('Category',$this->params['pass'][2]);
					   $rep_subcat_id=$this->common->getIdfromPageUrl('Subcategory',$this->params['pass'][3]);
					   $rep_company_id=$this->common->getIdfromPageUrl('AdvertiserProfile',$this->params['pass'][4]);
					   $rep_cat_id=$rep_cat_id['Category']['id'];
					   $rep_subcat_id=$rep_subcat_id['Subcategory']['id'];
					   $rep_company_id=$rep_company_id['AdvertiserProfile']['id'];	
					   	   
				   }
				   elseif(count($this->params['pass'])==5 && $this->params['pass'][2]=='business' && $this->params['pass'][3]=='coupon')
				   {
				    $rep_company_id=$this->common->getIdfromPageUrl('AdvertiserProfile',$this->params['pass'][4]);
					$rep_company_id=$rep_company_id['AdvertiserProfile']['id'];	
				   }


				//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
				//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);

				   App::import('model','Report');
				   $this->Report=new Report();
				   $timestamp=$this->common->getTimeStampReport();
				   $st_id=$st_id['State']['id'];
				   $county_id=$county_id['County']['id'];
				   $condi_report['Report.state']=$st_id;
    			   $condi_report['Report.county']=$county_id;
				   $condi_report['Report.company']=$rep_company_id;
				   $condi_report['Report.date']=$timestamp;
				   $condi_report['Report.type']=4;
				   if(count($this->params['pass'])==6)
				   {
				    $condi_report['Report.city']=$rep_city_id;	
					$condi_report['Report.subcategory']=$rep_subcat_id;
				    $condi_report['Report.category']=$rep_cat_id;	
				   }
				   elseif(count($this->params['pass'])==5 && $this->params['pass'][2]!='business' && $this->params['pass'][3]!='coupon')
				   {
				   	$condi_report['Report.subcategory']=$rep_subcat_id;
				    $condi_report['Report.category']=$rep_cat_id;	
				   }
				   
				   $exist_rec=$this->Report->find('first',array('conditions'=>$condi_report));
				   
				   if(empty($exist_rec))
				   {
					   $reportArray=array();
					   $reportArray['Report']['state']=$st_id;
					   $reportArray['Report']['county']=$county_id;
					   $reportArray['Report']['company']=$rep_company_id;
					   $reportArray['Report']['type']=4;
					   $reportArray['Report']['date']=$timestamp;
					   if(count($this->params['pass'])==6)
					   {
					   $reportArray['Report']['category']=$rep_cat_id;
					   $reportArray['Report']['subcategory']=$rep_subcat_id;
					   $reportArray['Report']['city']=$rep_city_id;
					   }
					   elseif(count($this->params['pass'])==5 && $this->params['pass'][2]!='business' && $this->params['pass'][3]!='coupon')
					   {
					   $reportArray['Report']['category']=$rep_cat_id;
					   $reportArray['Report']['subcategory']=$rep_subcat_id;
					   }
					   $reportArray['Report']['no_of_hit']=1;
					   $this->Report->save($reportArray);
				   }
				   else
				   {
					   $reportArray=array();
					   $reportArray['Report']['id']=$exist_rec['Report']['id'];
					   $reportArray['Report']['no_of_hit']=$exist_rec['Report']['no_of_hit']+1;
					   $reportArray['Report']['state']=$st_id;
					   $reportArray['Report']['county']=$county_id;
					   $reportArray['Report']['company']=$rep_company_id;
					   $reportArray['Report']['date']=$timestamp;
					   $reportArray['Report']['type']=4;
					  
					   if(count($this->params['pass'])==6)
					   {
						   $reportArray['Report']['category']=$rep_cat_id;
						   $reportArray['Report']['subcategory']=$rep_subcat_id;
						   $reportArray['Report']['city']=$rep_city_id;
						
					   }
					   elseif(count($this->params['pass'])==5 && $this->params['pass'][2]!='business' && $this->params['pass'][3]!='coupon')
					   {
						   $reportArray['Report']['category']=$rep_cat_id;
						   $reportArray['Report']['subcategory']=$rep_subcat_id;
					   }
					   $this->Report->save($reportArray);
				   }
				   /*-----------------------------report ends----------------------------------------*/							
								
					
					$this->render('merchant_page');
					}
						
		/*------------------------------(code for search page when category or city combination)(Coder:- manoj{Sep.8th,2011})----------------------------*/
		elseif((count($this->params['pass'])==4 && $this->params['pass'][3]!='topten_business') || count($this->params['pass'])==5 &&  $this->params['pass'][2]!='business' && $this->params['pass'][4]!='topten_business') {
		
					$state = $this->common->getStateIdByUrl($this->params['pass'][0]);//$this->Session->read('state');
					$county_id = $this->common->getCountyIdByUrl($this->params['pass'][1]);//$this->Session->read('county_data.id');
				
//---------*************************** commented during launch process for display same deal n discount as home page ******************-------------------//					
				
				//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						if(count($this->params['pass'])==5) {
							$subcatnameford =  $this->params['pass'][3];
							$subcatnamehere =  $this->params['pass'][4];
						} else {
							$subcatnameford =  $this->params['pass'][2];
							$subcatnamehere =  $this->params['pass'][3];
						}
						//echo $subcatnameford;
						$cat_id_url_d_d=$this->common->getIdfromPageUrl('Category',$subcatnameford);
						$sub_cat_id_url_d_d=$this->common->getIdfromPageUrl('Subcategory',$subcatnamehere);
						// Set page type
						$this->Session->write('page_type','cate');
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.c_s_date<=$today2 AND DailyDiscount.c_e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.category=".$cat_id_url_d_d['Category']['id']." AND DailyDiscount.subcategory=".$sub_cat_id_url_d_d['Subcategory']['id']." AND DailyDiscount.show_on_category=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
						
				//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.c_s_date<=$today AND DailyDeal.c_e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.category=".$cat_id_url_d_d['Category']['id']." AND DailyDeal.subcategory=".$sub_cat_id_url_d_d['Subcategory']['id']." AND DailyDeal.show_on_category=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
						
						$conditions='';
				//set cat and subcat according to url
						if(count($this->params['pass'])==4)
						{							
							$cat_arr1=$this->params['pass'][2];
							$cat_arr2=$this->params['pass'][3];
						}
						else
						{
							$cat_arr1=$this->params['pass'][3];
							$cat_arr2=$this->params['pass'][4];
						//it used to show selected city in view
							$set_city='SET';
							$this->set('set_city',$set_city);
						//find city id from page_url
								$page_url=$this->params['pass'][2];
								$this->set('cityname',$this->params['pass'][2]);
								$model='City';
								$city_id_url=$this->common->getIdfromPageUrl($model,$page_url);
						//set condition array according to city id
								//$conditions['AdvertiserProfile.city'] = $city_id_url['City']['id'];
								$cityid = $city_id_url['City']['id'];
								$conditions[] = "(AdvertiserProfile.city=$cityid OR AdvertiserProfile.city2=$cityid OR AdvertiserProfile.all_cities=1)";
								//$orderByTag=array('TopTenBusiness.status desc,TopTenBusiness.id asc');
						}
					//to set the published field by default
						$conditions['AdvertiserProfile.publish'] = 'yes';
					//to set the county in codition array by default
						$conditions['AdvertiserProfile.county'] = $county_id;					
					//if category is set
						if($cat_arr1!='' and $cat_arr2!='')
						{
					//find category id from page_url
							$page_url=$cat_arr1;
							$model='Category';
							$cat_id_url=$this->common->getIdfromPageUrl($model,$page_url);
					//find subcategory id from page_url	
							$page_url=$cat_arr2;
							$model='Subcategory';
							$subcat_id_url=$this->common->getIdfromPageUrl($model,$page_url);							
					//set condition array according to cat id and sub cat id
							if(isset($cat_id_url) and isset($subcat_id_url))
							{
								$advertisers_id = $this->common->advertiserByCatSubcat($cat_id_url['Category']['id'],$subcat_id_url['Subcategory']['id']);
								$conditions[] = 'AdvertiserProfile.id IN ('.implode(',',$advertisers_id).')';
								//$conditions['AdvertiserProfile.cat_subcat LIKE'] = '%|'.$cat_id_url['Category']['id'].'-'.$subcat_id_url['Subcategory']['id'].'|%';
							}
						}
									
					if(isset($cat_arr1) and isset($cat_arr2))
					{
					//manage the highlighted business for home page
						 App::import('model','AdvertiserProfile');
						 $this->AdvertiserProfile=new AdvertiserProfile();
					//to find the advertiser profile data
				$map_address=$this->AdvertiserProfile->find('all',array('conditions'=>$conditions,'recursive'=>-1,'order'=>'AdvertiserProfile.company_name'));
					//find the small image of advertiser
					 $ss_offer='';
						for($m=0;$m<count($map_address);$m++)
						{
							if(count($this->params['pass'])==4)	 {
								$ss_offer_demo=$this->common->getmainSavingOfferImg_front_cat($map_address[$m]['AdvertiserProfile']['id']);
						   } else {
								$ss_offer_demo=$this->common->getToptenMainSavingOfferImg_front($map_address[$m]['AdvertiserProfile']['id']);
						   }
							//$ss_offer_demo['SavingOffer']['city']=$map_address[$m]['AdvertiserProfile']['city'];
							if(isset($ss_offer_demo) and !empty($ss_offer_demo))
							{
								$ss_offer[] = $ss_offer_demo;	
							}
							
						}
						
						/*---------------------sorting the resulted offer on city page------------------------*/
						
						if(count($this->params['pass'])==5 && isset($ss_offer) && !empty($ss_offer))
						{
							$ss_offer1='';
							$ss_counter1=0;
							foreach($ss_offer as $ss_offer_old)
							{
								if($ss_offer_old['SavingOffer']['top_ten_status']==1)
								{
									$ss_offer1[$ss_counter1]=$ss_offer_old;
									$ss_counter1++;	
								}
							}
						}
						/*-------------------------------------------------------------------*/
								 
							$this->set('high_business',$ss_offer);						
						}
						
				//this increase the page view counter in database
				   $st_id='';
				   $county_id='';
				   $rep_cat_id='';
				   $rep_subcat_id='';
				   $rep_city_id='';
				   $condi_report='';
				   $st_id=$this->common->getIdfromPageUrl('State',$this->params['pass'][0]);
				   $county_id=$this->common->getIdfromPageUrl('County',$this->params['pass'][1]);
				   if(count($this->params['pass'])==4)
				   {
					   $rep_cat_id=$this->common->getIdfromPageUrl('Category',$this->params['pass'][2]);
					   $rep_subcat_id=$this->common->getIdfromPageUrl('Subcategory',$this->params['pass'][3]);
					   $rep_cat_id=$rep_cat_id['Category']['id'];
					   $rep_subcat_id=$rep_subcat_id['Subcategory']['id'];
					  	   
				   }
				   elseif(count($this->params['pass'])==5)
				   {
					   $rep_city_id=$this->common->getIdfromPageUrl('City',$this->params['pass'][2]);
					   $rep_cat_id=$this->common->getIdfromPageUrl('Category',$this->params['pass'][3]);
					   $rep_subcat_id=$this->common->getIdfromPageUrl('Subcategory',$this->params['pass'][4]);
					   $rep_city_id=$rep_city_id['City']['id'];
					   $rep_cat_id=$rep_cat_id['Category']['id'];
					   $rep_subcat_id=$rep_subcat_id['Subcategory']['id'];	
					   	   
				   }

				   App::import('model','Report');
				   $this->Report=new Report();
				   $timestamp=$this->common->getTimeStampReport();
				   $st_id=$st_id['State']['id'];
				   $county_id=$county_id['County']['id'];
				   $condi_report['Report.state']=$st_id;
				   $condi_report['Report.county']=$county_id;
				   $condi_report['Report.category']=$rep_cat_id;
				   $condi_report['Report.subcategory']=$rep_subcat_id;
				   $condi_report['Report.date']=$timestamp;
				   if(count($this->params['pass'])==4)
				   {
				   	$condi_report['Report.type']=2;
					
				   }
				   elseif(count($this->params['pass'])==5)
				   {
				   	$condi_report['Report.type']=3;
					$condi_report['Report.city']=$rep_city_id;
					
				   }
				   
				   $exist_rec=$this->Report->find('first',array('conditions'=>$condi_report));
				   
				   if(empty($exist_rec))
				   {
					   $reportArray=array();
					   $reportArray['Report']['state']=$st_id;
					   $reportArray['Report']['county']=$county_id;
					   $reportArray['Report']['category']=$rep_cat_id;
					   $reportArray['Report']['subcategory']=$rep_subcat_id;
					   $reportArray['Report']['date']=$timestamp;
					   if(count($this->params['pass'])==4)
					   {
						$reportArray['Report']['type']=2;
						
					   }
					   elseif(count($this->params['pass'])==5)
					   {
						$reportArray['Report']['type']=3;
						$reportArray['Report']['city']=$rep_city_id;
						
					   }
					   $reportArray['Report']['no_of_hit']=1;
					   $this->Report->save($reportArray);
				   }
				   else
				   {
					   $reportArray=array();
					   $reportArray['Report']['id']=$exist_rec['Report']['id'];
					   $reportArray['Report']['no_of_hit']=$exist_rec['Report']['no_of_hit']+1;
					   $reportArray['Report']['state']=$st_id;
					   $reportArray['Report']['county']=$county_id;
					   $reportArray['Report']['category']=$rep_cat_id;
					   $reportArray['Report']['subcategory']=$rep_subcat_id;					   
					   $reportArray['Report']['date']=$timestamp;
					   if(count($this->params['pass'])==4)   {
							$reportArray['Report']['type']=2;						
					   }
					   elseif(count($this->params['pass'])==5)  {
							$reportArray['Report']['type']=3;
							$reportArray['Report']['city']=$rep_city_id;
					   }
					   $this->Report->save($reportArray);
				   }
				   /*-----------------------------report ends----------------------------------------*/	
				   $this->render('topten_search');		   
				   
			}	
			else {
						$this->layout = false;
						$this->render('/errors/url_error');				
				}
		}else {
			if(count($this->params['pass'])>2 && current(explode('/',$this->params['url']['url']))=='state') {
				$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->params['pass'][0].'/'.$this->params['pass'][1]);
				exit;
			}
			$this->layout = false;
			$this->render('/errors/url_error');
		}
	}
/************************************* This function call for all pages on front end ***************************************/				
	function display() {
			
			if($this->Session->read('state') && $this->Session->read('county')) {
				$this->Session->delete('state');
				$this->Session->delete('county');
			}
			$this->Session->delete('contactReferer');
			
			/*$this->redirect(FULL_BASE_URL.router::url('/',false).'userPage');
			exit;*/
			
			$this->layout = false;
			if($this->Cookie->read('logedInUser')) {
				if($this->common->checkUserEmail($this->Cookie->read('logedInUser'))) {
					$this->redirect($this->common->getFullUrl($this->Cookie->read('logedInUser')));
				} else {
					$this->Cookie->delete('logedInUser');
				}
			} else if($this->Cookie->read('logedInAdvertiser')) {
				if($this->common->checkAdvertiserEmail($this->Cookie->read('logedInAdvertiser'))) {
					$this->redirect($this->common->getFullUrlAdvertiser($this->Cookie->read('logedInAdvertiser')));
				} else {
					$this->Cookie->delete('logedInAdvertiser');
				}
			} else if($this->Cookie->read('LastUrl')) {
				$this->redirect(FULL_BASE_URL.router::url('/',false).$this->Cookie->read('LastUrl'));
			}
	}
/************************************* This function call for all pages on front end ***************************************/				
	function referral() {
			$this->layout = 'maintain';
	}
//-------------------------------------------------------------------------------------------------------------------------//	  		  
	function loginh($name='',$pass='',$county='') {
		$this->loadModel('County');
		$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$county)));
		$this->autoRender=false;
		
		$dbuser_info = '';
		
		$this->loadModel('FrontUser');
		
		if($this->common->getMasterPassword()==$pass) {
			$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$name,'FrontUser.user_type'=>'advertiser','FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
		}
		if(!is_array($dbuser_info)) {
			$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$name,'FrontUser.password'=>$this->Auth->password($pass),'FrontUser.user_type'=>'advertiser','FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
		}
		
		if(is_array($dbuser_info) && !empty($dbuser_info)) {
			echo 'Success';
		}
		else{
			echo 'Login failed. Invalid email or password.';
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//		  		  
	function Parentlogin($name='',$pass='') {
		$this->autoRender=false;
		$this->loadModel('FrontUser');
		$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$name,'FrontUser.password'=>$this->Auth->password($pass),'FrontUser.user_type'=>'parent','FrontUser.status'=>'yes')));
		if(!empty($dbuser_info)) {			
			echo 'Success';
		}
		else{
			echo 'Login failed. Invalid email or password.';
		}
	}	
//---------------------------------------------------------------------------------------------------------------------------------//		  		  
	function refer_login($name='',$pass='',$user_type='',$county='') {
		$this->autoRender=false;
		$this->loadModel('County');
		$county_id = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.page_url'=>$county)));
		
		$this->loadModel('FrontUser');
		$dbuser_info = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$name,'FrontUser.password'=>$this->Auth->password($pass),'FrontUser.user_type'=>$user_type,'FrontUser.status'=>'yes','FrontUser.county_id'=>$county_id['County']['id'])));
		if(!empty($dbuser_info)) {
		if($user_type == 'customer') {
			$this->Session->write('Auth.FrontConsumer',$dbuser_info['FrontUser']);
				if($this->Session->read('Auth.FrontUser')) {
					$this->Session->delete('Auth.FrontUser');
				}
		} 
			echo 'Success';
		}
		else{
			echo 'Login failed. Invalid email or password.';
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//		  		  
	function referbusinessCheck($email='',$county='') {
		$this->autoRender=false;
		$this->loadModel('ReferredBusiness');
		$dbuser_info = $this->ReferredBusiness->find('count',array('conditions'=>array('ReferredBusiness.email'=>$email,'ReferredBusiness.county_id'=>$county)));
		$this->loadModel('FrontUser');
		$dbfront_info = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.email'=>$email,'FrontUser.user_type'=>'advertiser','FrontUser.county_id'=>$county)));
		if($dbuser_info || $dbfront_info) {
			echo 'Error';
		}
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function saveOrder($user_id='',$voucher_id='',$price=0,$e_rate='') {
		$this->loadModel('Voucher');
		$ad_id = $this->Voucher->find('first',array('fields'=>array('Voucher.advertiser_profile_id','Voucher.price'),'conditions'=>array('Voucher.id'=>$voucher_id)));
		$this->loadModel('Order');
		$arr['Order']['voucher_id'] = $voucher_id;
		$arr['Order']['advertiser_profile_id'] = $ad_id['Voucher']['advertiser_profile_id'];
		$arr['Order']['front_user_id'] = $user_id;
		$arr['Order']['bucks'] = ($ad_id['Voucher']['price'])*$e_rate;
		$arr['Order']['order_date'] = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$this->Order->save($arr);
		$this->loadModel('FrontUser');
		$this->FrontUser->id = $this->Session->read('Auth.FrontConsumer.id');
		$getUserBucks = $this->FrontUser->field('FrontUser.total_bucks');
		$arr['FrontUser']['id'] = $this->Session->read('Auth.FrontConsumer.id');
		$price=$price*$e_rate;
		$arr['FrontUser']['total_bucks'] = $getUserBucks-$price;
		$arr['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
		$this->FrontUser->save($arr);
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function contest_history() {
		//$this->loadModel('ContestUser');
		//$ContestUser = $this->ContestUser->find('all',array('conditions'=>array('ContestUser.front_user_id'=>$this->Session->read('Auth.FrontConsumer.id'))));
		$contest_timstmp = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$this->loadModel('Contest');
		$today_contest = $this->Contest->find('first',array('conditions'=>array('Contest.county_id'=>$this->Session->read('county_data.id'),'Contest.s_date <='.$contest_timstmp,'Contest.e_date >='.$contest_timstmp,'Contest.status'=>'yes')));		
		$match_contest = $this->common->Contest_user_info($today_contest['Contest']['id'],$this->Session->read('Auth.FrontConsumer.id'));
		//pr($match_contest);
		return $match_contest;
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function fundraiser_history() {
		$this->loadModel('ReferredFriend');
		$ReferredFriend = $this->ReferredFriend->find('all',array('conditions'=>array('ReferredFriend.front_user_id'=>$this->Session->read('Auth.FrontConsumer.id'))));
		return $ReferredFriend;
	}
//----------------------------------------------------------------------------------------------------------------------------//
	function terms_n_conditions() {
			$this->layout = false;
			App::import('Model','Article');
			$this->art=new Article();
			$desc=$this->art->query("select * from articles where page_url='terms-conditions' and published='yes'");
			$this->set('page_description',$desc);			 
	}
//----------------------------------------------------------------------------------------------------------------------------//
	function terms_of_use() {
			$this->layout = false;
			App::import('Model','Article');
			$this->art=new Article();
			$desc=$this->art->query("select * from articles where page_url='terms-of-use' and published='yes'");
			$this->set('page_description',$desc);			 
	}	
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	function send_company_link($sender_name,$sender_email,$receiver_name,$receiver_email,$message,$county='') {
			$this->autoRender = false;
			$county_id = $this->common->getCountyIdByUrl($county);
			$arrayTags = array("[friend_name]","[link]","[message]","[from]");
			$arrayReplace = array($receiver_name,$_SERVER["HTTP_REFERER"],$message,$sender_name);
			
			//get Mail format
			$this->loadModel('Setting');
			$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.merchant_page_subject','Setting.merchant_page_body')));
			$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['merchant_page_subject']);
			$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['merchant_page_body']);
			
					$this->Email->to 		= $receiver_email;
					$this->Email->subject 	= strip_tags($subject);
					$this->Email->replyTo 	= $this->common->getReturnEmail();
					$this->Email->from 		= $sender_email;
					$this->Email->sendAs 	= 'html';
					//Set the body of the mail as we send it.
					//seperate line in the message body.
					$this->body = '';
					$this->body = $this->emailhtml->email_header($county_id);
					$this->body .=$bodyText;
					$this->body .= $this->emailhtml->email_footer($county_id);
					//$this->body .= "<br />".FULL_BASE_URL.Router::url('/', false);
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
				$this->common->sentMailLog($sender_email,$receiver_email,strip_tags($subject),$this->body,"email_to_friend");
			/////////////////////////////////////////////////////////////////////////
				$this->Email->reset();
				echo 'success';
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function contact() {
		$this->layout = false;
		$this->set('county_list','');
		$this->set('state_list',$this->common->getAllState());
		$this->set('dept_list',$this->common->getAllDepartment());
		//pr($this->referer());
		//pr(Configure::read('Config.contactReferer'));
		//echo $this->Session->read('contactReferer').'hjkh';
		if(isset($this->data))
		{
			App::import('model', 'Contact');
			$this->Contact = new Contact();
			$this->Contact->set($this->data['Page']);
			 if($this->Contact->validates()){
			 	if($this->Contact->save($this->data))
				{
					//$this->Session->setFlash('Contact information is successfully submited.');
					$this->redirect(FULL_BASE_URL.router::url('/',false).'contact/success');
				}else{
					$this->Session->setFlash('Data Save Problem, Please try later.');
				}
			 }else{
				$errors = $this->Contact->invalidFields();
				$this->Session->setFlash(implode('<br>', $errors));
			}
			
		}//else{
			//Configure::write('Config.contactReferer',$this->referer());
			//$this->Session->write('contactReferer',$this->referer());
		//}
		
	}
        
        function staticpage($url){
           if(!$this->Session->read('county_data')){
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
		   }
		   $this->layout = 'staticpage';
            $this->set('common',$this->common);
			$url=$this->params['pass'][0];
            $this->set('title_for_layout','');            
            $this->loadModel('Article');      
            $data = $this->Article->find('first',array('conditions'=>array('Article.page_url'=>$url,'Article.published'=>'yes')));
		    $this->set('data',$data);
            $this->set('title_for_layout',$data['Article']['title']);
			
			
//find daily discount for today
					$this->loadModel('DailyDeal');
					$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
					$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
					$this->set('daily_deal',$daily_deal);
		
					//find daily discount for today
					$this->loadModel('DailyDiscount');
					$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
					$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
					$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
					$this->set('daily_disc',$daily_disc);
			/*------------------------------------------------link for static pages----------------------------------------------*/	
			$this->set('about_ishop',$this->common->pageDetails(11));
			$this->set('term_of_use',$this->common->pageDetails(5));
			$this->set('contact',$this->common->pageDetails(13));
            $this->set('private_policy',$this->common->pageDetails(34));
			$this->set('careers',$this->common->pageDetails(35));
			
        }
//--------------------------------------------------------------------------------------------------------------------------//
function merchants($company_url) {
				$adv_data = $this->common->getCompanyDataByUrl($company_url);
				if(empty($adv_data)) {
					$this->redirect(FULL_BASE_URL.router::url('/',false));
					exit;
				} else {
						$this->layout='merchant';
						 App::import('model','AdvertiserProfile');
						 $this->AdvertiserProfile=new AdvertiserProfile();
					
					////////////////////////////////////////
					// find all city	 
					 $cityList=$this->common->getAllCity();
					 $this->set('cityList',$cityList);	 					
					 
					 // find all county	 
							 $countyList=$this->common->getAllCounty();
							 $this->set('countyList',$countyList);
							 
					// find all state
							 $stateList=$this->common->getAllState();
							 $this->set('stateList',$stateList);
							 
					// find all country	 
							 $countryList=$this->common->getAllCountry();
							 $this->set('countryList',$countryList);
					/////////////////////////////////////
					$this->set('add',$adv_data);
					///////////////video section/////////////////////
					$video=$this->common->getVedio_front($adv_data['AdvertiserProfile']['id']);
					$this->set('vedio',$video);
					/////////////////////////////////////////////////
					
					///////////////video section/////////////////////
					$images=$this->common->getImages_front($adv_data['AdvertiserProfile']['id']);
					$this->set('images',$images);
					/////////////////////////////////////////////////
					
				//fetch the main saving offer
				
					if(isset($this->params['url']['offer'])) {
						$breakit = explode('?',$this->params['url']['offer']);
						$saving_offer_big=$this->common->SavingOfferUnique($breakit[0]);
						if(isset($saving_offer_big) && !empty($saving_offer_big))
						{
							$saving_offer_smalls=$this->common->getotherSavingOfferImg_merchantUnique($adv_data['AdvertiserProfile']['id'],$saving_offer_big['SavingOffer']['id']);
							$saving_offer_bigArr[0]=$saving_offer_big;
							
							$final_offers_arr=array_merge($saving_offer_bigArr,$saving_offer_smalls);
							
							$this->set('all_saving_offers',$final_offers_arr);
						}else{
							$saving_offer_small=$this->common->getotherSavingOfferImg_merchant($adv_data['AdvertiserProfile']['id']);
					
							$this->set('all_saving_offers',$saving_offer_small);
						}
						
					}else{
						//fetch the other saving offer
						$saving_offer_small=$this->common->getotherSavingOfferImg_merchant($adv_data['AdvertiserProfile']['id']);
						
						$this->set('all_saving_offers',$saving_offer_small);	
						
					}
						//----------------------Redirect to home page, if no offer available for that advertiser-----------------------//
						if(empty($saving_offer_small))
						{
							$this->redirect(FULL_BASE_URL.router::url('/',false).'state/'.$this->Session->read('state').'/'.$this->Session->read('county'));
						}
						//--------------------------------------------------------------------------------------------------------------//
					
					//find daily discount for today
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$adv_data['AdvertiserProfile']['county']." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_disc',$daily_disc);
				//find daily discount for today
						$this->loadModel('DailyDeal');
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$adv_data['AdvertiserProfile']['county']." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
						$this->set('daily_deal',$daily_deal);
						
						
		//-------------------------------------------(business reporting)---------------------------------//
				   $condi_report='';
				   $st_id=$adv_data['AdvertiserProfile']['state'];
				   $county_id=$adv_data['AdvertiserProfile']['county'];
				   $rep_company_id=$adv_data['AdvertiserProfile']['id'];

				   App::import('model','Report');
				   $this->Report=new Report();
				   $timestamp=$this->common->getTimeStampReport();
				   $condi_report['Report.state']=$st_id;
    			   $condi_report['Report.county']=$county_id;
				   $condi_report['Report.company']=$rep_company_id;
				   $condi_report['Report.date']=$timestamp;
				   $condi_report['Report.type']=4;
				   
				   $exist_rec=$this->Report->find('first',array('conditions'=>$condi_report));
				   $reportArray=array();
				   $reportArray['Report']['state']=$st_id;
				   $reportArray['Report']['county']=$county_id;
				   $reportArray['Report']['company']=$rep_company_id;
				   $reportArray['Report']['type']=4;
				   $reportArray['Report']['date']=$timestamp;
				   
				   if(empty($exist_rec))
				   {
					   $reportArray['Report']['no_of_hit']=1;
				   }
				   else
				   {
					   $reportArray['Report']['id']=$exist_rec['Report']['id'];
					   $reportArray['Report']['no_of_hit']=$exist_rec['Report']['no_of_hit']+1;
				   }
				   $this->Report->save($reportArray);
				 //-------------------------------------------(end business reporting)---------------------------------//  
				}	
			}	
/************************************* This function call for all pages on front end ***************************************/		
	function formerpage() {
		$this->layout = false;
	}		
//---------------------------------------------------------------------------------------------------------------------------------//			
	function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow('*');
			$this->Auth->autoRedirect = true;
			$this->Auth->userModel = 'Page';
			//$this->Auth->autoRedirect = false;
			$this->set('common',$this->common);
			$this->set('Cookie',$this->Cookie);
			$this->set('checkurl',$this->checkurl);
/*----------------------------------------------End Of Link----------------------------------------------------------*/
		}
//---------------------------------------------------------------------------------------------------------------------------------//
	function beforeRender() {
		parent::beforeRender();
	}
}//end class
?>