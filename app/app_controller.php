<?php
class AppController extends Controller {
var $components = array('Ssl','Cookie');
   	function beforeFilter() {
		//FULL_BASE_URL.Router::reverse($this->params);
		//$this->common->updateNewTables();
		//$this->common->updateNewTable();
		//$this->common->updateAdvertiserCats();
		$this->static_redirects();
		$detect_root = new Mobile_Detect();
		$device = ($detect_root->isMobile() ? ($detect_root->isTablet() ? 'tablet' : 'mobile') : 'computer');
		$this->set('device',$device);
		//delete all business referred before 1 month
		$this->common->delete_refer_business();
		//manage all settings on home page
		 $this->loadModel('Setting');
		 $settings = $this->Setting->find('first');
		 //pr($settings['Setting']);
		 $this->set('setting',$settings['Setting']);
		 //For merchant page's saving offer's data sharing on facebook
		 $offerdata = '';
		 $disofferdata = '';
		 $dealofferdata = '';
		 $myurlstring='';
		 $mydealurlstring='';
		 if(isset($this->params['pass'][2]) && $this->params['pass'][2]!='' && ($this->params['pass'][2]=='dailydiscount' || $this->params['pass'][2]=='buyDiscount') && isset($this->params['url']['unique']) && $this->params['url']['unique']!='')
		 {
		 	//remove contest email seal
		 	$contestString = explode('contest',$this->params['url']['unique']);
			$myurlstring=explode('?',$contestString[0]);
			$this->loadModel('DailyDiscount');
			$disofferdata = $this->DailyDiscount->find('first',array('fields'=>array('DailyDiscount.banner_image,DailyDiscount.discount_details,DailyDiscount.title'),'conditions'=>array('DailyDiscount.unique'=>$myurlstring[0])));
		 }
		 if(isset($this->params['pass'][2]) && $this->params['pass'][2]!='' && $this->params['pass'][2]=='dailydeal' && isset($this->params['url']['unique']) && $this->params['url']['unique']!='')
		 {
			$mydealurlstring=explode('?',$this->params['url']['unique']);
			$this->loadModel('DailyDeal');
			$dealofferdata = $this->DailyDeal->find('first',array('fields'=>array('DailyDeal.banner_image,DailyDeal.deal_details,DailyDeal.title'),'conditions'=>array('DailyDeal.unique'=>$mydealurlstring[0])));
		 }
	if(isset($this->params['url']['offer']) && $this->params['url']['offer']!='') {
		$myofferurlstring=explode('?',$this->params['url']['offer']);
		$this->loadModel('SavingOffer');
		$offerdata = $this->SavingOffer->find('first',array('fields'=>array('SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.title,SavingOffer.off_unit,SavingOffer.off_text,SavingOffer.off,SavingOffer.offer_expiry_date,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.other,SavingOffer.disclaimer,SavingOffer.advertiser_profile_id'),'conditions'=>array('SavingOffer.unique'=>$myofferurlstring[0])));
	}
	if(is_array($offerdata) && !empty($offerdata)) {
						$title = '';
						if($offerdata['SavingOffer']['off_unit']==2) {
							$title .=  $offerdata['SavingOffer']['off_text'];
						} else {
							if($offerdata['SavingOffer']['off_unit']==1) {
								$title .= '$ ';
							}
							$title .= $offerdata['SavingOffer']['off'];
							if($offerdata['SavingOffer']['off_unit']==0) {
								$title .= ' %';
							}
							$title .= ' OFF';
						}
					$title .= '. '.$offerdata['SavingOffer']['title'].' ('.$this->common->getCompanyNameById($offerdata['SavingOffer']['advertiser_profile_id']).')';
						$detail = strip_tags($offerdata['SavingOffer']['description']).'. ';
						$detail .= 'Expires: '.date(DATE_FORMAT,$offerdata['SavingOffer']['offer_expiry_date']).'. ';
						
						if($offerdata['SavingOffer']['no_valid_other_offer']==1) {
							$detail .= 'Not valid with any other offer. ';
						}
						if($offerdata['SavingOffer']['no_transferable']==1) {
							$detail .= 'Non-transferable / Not for resale / Not redeemable for cash. ';
						}
                        if($offerdata['SavingOffer']['other']==1) {
							$detail .= $offerdata['SavingOffer']['disclaimer'];
						}
					$this->set('title_for_layout',$title);
					$this->set('keyword_for_layout',$offerdata['SavingOffer']['title']);
					$this->set('description_for_layout',$detail);
					$this->set('og_image',$offerdata['SavingOffer']['offer_image_big']);
	}elseif(is_array($disofferdata) && !empty($disofferdata)) {
					$this->set('title_for_layout',$disofferdata['DailyDiscount']['title']);
					$this->set('keyword_for_layout',$disofferdata['DailyDiscount']['title']);
					$this->set('description_for_layout',$disofferdata['DailyDiscount']['discount_details']);
					$this->set('og_image',$disofferdata['DailyDiscount']['banner_image']);
	}elseif(is_array($dealofferdata) && !empty($dealofferdata)) {
					$this->set('title_for_layout',$dealofferdata['DailyDeal']['title']);
					$this->set('keyword_for_layout',$dealofferdata['DailyDeal']['title']);
					$this->set('description_for_layout',$dealofferdata['DailyDeal']['deal_details']);
					$this->set('og_image',$dealofferdata['DailyDeal']['banner_image']);
	}
	/*----------------meta section--------------------------------------------------------*/
	else if($this->params['controller']=='pages' && $this->params['action']=='merchants') {
	//its for merchant page
				$company_id = $this->common->getCompanyidByUrl($this->params['pass'][0]);
				$place_marks = array('[COMPANY]','[COUNTY]','[STATE]');
				$meta_state = $this->common->getStateName($this->common->getCompanystate($company_id));
				$meta_county = $this->common->getCountyName($this->common->getCompanyCounty($company_id));
				$meta_company = $this->common->getCompanyName($company_id);
				$place_words = array($meta_company,$meta_county,$meta_state);
				
				$this->set('title_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['merchant_meta_title']));
				$this->set('keyword_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['merchant_meta_keyword']));
				$this->set('description_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['merchant_meta_description']));
	} else if((count($this->params['pass'])==6 && ($this->common->validateCity($this->params)!='0') && $this->params['pass'][5]!='topten_business') || (count($this->params['pass'])==5 && $this->checkurl->chkCountyUrl($this->params['pass'][0],$this->params['pass'][1])!='0' && $this->checkurl->chkCompanyUrl($this->params['pass'][4])!='0' && $this->params['pass'][4]!='topten_business') || (count($this->params['pass'])==5 && $this->params['pass'][2]=='business' &&  $this->params['pass'][3]=='coupon' && $this->params['pass'][4]!='topten_business')) {
	//its for merchant page
				$place_marks = array('[COMPANY]','[COUNTY]','[STATE]');
				$meta_state = $this->common->getStateDetails_url($this->params['pass'][0]);
				$meta_county = $this->common->getCountyDetails_url($this->params['pass'][1]);
				$meta_company = $this->common->getCompanyDetails_url(end($this->params['pass']));
				$place_words = array($meta_company,$meta_county,$meta_state);
				
				$this->set('title_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['merchant_meta_title']));
				$this->set('keyword_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['merchant_meta_keyword']));
				$this->set('description_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['merchant_meta_description']));
	} else if((($this->Session->read('Auth.FrontConsumer') && count($this->params['pass'])<4) || ($this->Session->read('Auth.FrontUser') && count($this->params['pass'])<4) || (count($this->params['pass'])<=2 && !isset($this->params['pass'][2]) || (isset($this->params['pass'][3]) && $this->params['pass'][2]=='register'))	|| (count($this->params['pass'])==4 && $this->params['pass'][2]=='cat') || (count($this->params['pass'])==4 && isset($this->params['pass'][2]) && $this->params['pass'][2]=='business' && isset($this->params['pass'][3]) && $this->params['pass'][3]!='coupon')) && (isset($this->params['pass'][1]))) {
		//its for home page
				$meta = $this->common->meta_details($this->params['pass'][0],$this->params['pass'][1],'','','');
				if(!empty($meta) && count($this->params['pass'])==2) {
					$this->set('title_for_layout',$meta['Meta']['meta_title']);
					$this->set('keyword_for_layout',$meta['Meta']['meta_keyword']);
					$this->set('description_for_layout',$meta['Meta']['meta_description']);
				} else {
					$place_marks = array('[STATE]','[COUNTY]');
					$meta_state = $this->common->getStateDetails_url($this->params['pass'][0]);
					$meta_county = $this->common->getCountyDetails_url($this->params['pass'][1]);
					$place_words = array($meta_state,$meta_county);
					$this->set('title_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['meta_title']));
					$this->set('keyword_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['meta_keyword']));
					$this->set('description_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['meta_description']));
				}	
		} else if(count($this->params['pass'])==4) {
		//its for category page
				$meta = $this->common->meta_details($this->params['pass'][0],$this->params['pass'][1],'',$this->params['pass'][2],$this->params['pass'][3]);
				if(!empty($meta)) {
					$this->set('title_for_layout',$meta['Meta']['meta_title']);
					$this->set('keyword_for_layout',$meta['Meta']['meta_keyword']);
					$this->set('description_for_layout',$meta['Meta']['meta_description']);
				} else {
					$place_marks = array('[CATEGORY]','[SUBCATEGORY]','[STATE]','[COUNTY]');
					$meta_category_name = $this->common->getCategoryDetails_url($this->params['pass'][2]);
					$meta_sub_cat_name = $this->common->getSubcategoryDetails_url($this->params['pass'][3]);
					$meta_state = $this->common->getStateDetails_url($this->params['pass'][0]);
					$meta_county = $this->common->getCountyDetails_url($this->params['pass'][1]);
					$place_words = array($meta_category_name,$meta_sub_cat_name,$meta_state,$meta_county);
					$this->set('title_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['cat_meta_title']));
					$this->set('keyword_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['cat_meta_keyword']));
					$this->set('description_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['cat_meta_description']));
				}		
		} else if(count($this->params['pass'])==5) {
		//its for category-city page
				$meta = $this->common->meta_details($this->params['pass'][0],$this->params['pass'][1],$this->params['pass'][2],$this->params['pass'][3],$this->params['pass'][4]);
				if(!empty($meta)) {
					$this->set('title_for_layout',$meta['Meta']['meta_title']);
					$this->set('keyword_for_layout',$meta['Meta']['meta_keyword']);
					$this->set('description_for_layout',$meta['Meta']['meta_description']);
				} else {
					$place_marks = array('[CATEGORY]','[SUBCATEGORY]','[STATE]','[COUNTY]','[CITY]');
					$meta_category_name = $this->common->getCategoryDetails_url($this->params['pass'][3]);
					$meta_sub_cat_name = $this->common->getSubcategoryDetails_url($this->params['pass'][4]);
					$meta_state = $this->common->getStateDetails_url($this->params['pass'][0]);
					$meta_county = $this->common->getCountyDetails_url($this->params['pass'][1]);
					$meta_city = $this->common->getCityDetails_url($this->params['pass'][2]);
					$place_words = array($meta_category_name,$meta_sub_cat_name,$meta_state,$meta_county,$meta_city);
					$this->set('title_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['catcity_meta_title']));
					$this->set('keyword_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['catcity_meta_keyword']));
					$this->set('description_for_layout',str_replace($place_marks,$place_words,$settings['Setting']['catcity_meta_description']));
				}
		} else {
				$title=$this->common->getPageTitle($this->params);
				if($title!='') {
					$this->set('title_for_layout',$title);
				} elseif($this->Session->read('Auth.FrontUser'))
				{
					$this->set('title_for_layout','Advertiser Profile');
				}
				elseif($this->Session->read('Auth.FrontConsumer'))
				{
					$this->set('title_for_layout','Consumer Profile');
				}
				else
				{
					$this->set('title_for_layout',$this->Session->read('county').' - zuni');
				}
				$keyword=$this->common->getPageKeyWord($this->params);
				if($keyword!='')
					$this->set('keyword_for_layout',$keyword);
				else
					$this->set('keyword_for_layout',$this->Session->read('county').' - zuni');
					$description=$this->common->getDescription($this->params);
				if($description!='')
					$this->set('description_for_layout',$description);
				else
					$this->set('description_for_layout',$this->Session->read('county').' - zuni');
			}
	/*------------------------------------------------------------------------------------*/
		 $url_error ='';
		 /*if($settings['Setting']['site_down']==1) {
			 $this->layout=false;
			 $this->render('/errors/maintenace_error');		 	 
		 } else {*/
if($this->params['action']!='toptenSearch' && $this->params['action']!='merchantPage' && $this->params['action']!='referFriend' && $this->params['action']!='Consumerlogin' && $this->params['action']!='loginh' && $this->params['action']!='refer_login' && $this->params['action']!='referbusinessCheck' && $this->params['action']!='referbusiness' && $this->params['action']!='saveOrder' && $this->params['action']!='order_history' && $this->params['action']!='contest_history' && $this->params['action']!='getAdvertiser' && $this->params['action']!='send_company_link' && $this->params['action']!='fundraiser_history'){
	if((isset($this->params['pass'][0]) && $this->params['pass'][0]!='') && (isset($this->params['pass'][1]) && $this->params['pass'][1]!='')) {
			$url_state = $this->params['pass'][0];
			$url_county = $this->params['pass'][1];
			$this->Session->write('state',$url_state);
			$this->Session->write('county',$url_county);
		//Access state and county data
				$this->loadModel('State');
				$this->loadModel('County');
				$this->loadModel('HeaderLogo');
				$state_id = $this->State->find('first',array('fields'=>array('State.id'),'conditions'=>array('State.page_url'=>$this->params['pass'][0])));
				if(isset($state_id) && is_array($state_id)) {
						$county_data = $this->County->find('first',array('conditions'=>array('County.page_url'=>$this->params['pass'][1],'County.state_id'=>$state_id['State']['id'])));
						if(isset($county_data) && is_array($county_data)) {
							$this->Session->write('county_data',$county_data['County']);
							$logo_time = time();
							//importing the header logo model for accessing the county header image						 		
							$header_img=$this->HeaderLogo->query("select * from header_logos where county_id=".$county_data['County']['id']." AND start_date<$logo_time AND $logo_time < end_date");
							$this->Session->delete('header_img');
							if(isset($header_img[0]['header_logos']['logo']) and $header_img[0]['header_logos']['logo']!='') {
					 				$this->Session->write('header_img',$header_img[0]['header_logos']['logo']);
							}
						}
						else {
							$url_error = 'county_error';
						}
				} else {
					$url_error = 'state_error';
			}
	}
	else {
		$url_error = 'url_error';
	}
	if($url_error!='') {
				//$this->Session->destroy();
				//$this->render('/errors/'.$url_error);
	}
} //}
	if(($this->params['action']=='addDailyDeal' || $this->params['action']=='editDailyDeal' || $this->params['action']=='addDailyDiscount' || $this->params['action']=='editDailyDiscount' || $this->params['action']=='addNewOffer' || $this->params['action']=='offerEditDetail') && $this->Session->read('referer')){}else{
			$this->Session->delete('referer');
		}

	$this->set('myCookie', $this->Cookie);
	}
	
 function static_redirects() {
 	if($this->params['url']['url']=='category/state/florida/marion/ocala/shopping') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion/ocala/shopping/wedding');
	}
	else if($this->params['url']['url']=='category/state/florida/marion/ocala/food-and-dining') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion/ocala/food-and-dining/catering');
	}
	else if($this->params['url']['url']=='category/state/florida/marion/ocala/medical-and-dental') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion/ocala/medical-and-dental/dentists');
	}
	else if($this->params['url']['url']=='category/state/florida/marion/anthony') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion/anthony/food-and-dining/coffee-shop');
	}
	else if($this->params['url']['url']=='author/admin/feed') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'admins/login');
	}
	else if($this->params['url']['url']=='FREE') {
		$this->redirect(FULL_BASE_URL.router::url('/',false));
	}
	else if($this->params['url']['url']=='state/florida/marion/marion-oaks/home-and-garden/pressure-washing') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion/marion-oaks/home-and-garden/pressure-washing-roof-cleaning');
	}
	else if($this->params['url']['url']=='florida/marion') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion');
	}
	else if($this->params['url']['url']=='monmouth') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/new-jersey/monmouth');
	}
	else if($this->params['url']['url']=='monmouth/new-jersey') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/new-jersey/monmouth');
	}
	else if($this->params['url']['url']=='monmouth/new-jersey/') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/new-jersey/monmouth/');
	}
	else if($this->params['url']['url']=='new-jersey/monmouth') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/new-jersey/monmouth');
	}
	else if($this->params['url']['url']=='category/state/florida/marion/ocala/page/2') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion/ocala/food-and-dining/catering');
	}
	else if($this->params['url']['url']=='category/state/florida/marion/ocala/travel-transportation-lodging') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion/ocala/travel-transportation-lodging/hotels');
	}
	else if($this->params['url']['url']=='category/state/florida/marion/page/2') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion');
	}
	else if($this->params['url']['url']=='category/state/florida/marion/ocala/home-and-garden') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion/ocala/home-and-garden/pool-spa');
	}
	else if($this->params['url']['url']=='category/state/florida/marion/ocala') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion/ocala/food-and-dining/catering');
	}
	else if($this->params['url']['url']=='category/state/florida/marion') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion');
	}
	else if($this->params['url']['url']=='page/2') {
		$this->redirect(FULL_BASE_URL.router::url('/',false));
	}
	else if($this->params['url']['url']=='marion/florida') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion');
	}
	else if($this->params['url']['url']=='1/marion/florida') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion');
	}
	else if($this->params['url']['url']=='marion/florida/1') {
		$this->redirect(FULL_BASE_URL.router::url('/',false).'state/florida/marion');
	}
	else if(end(explode('/',$_SERVER['REQUEST_URI']))=='business') {
		$this->redirect(FULL_BASE_URL.router::url('/',false));
	}
	else if(end(explode('/',$_SERVER['REQUEST_URI']))=='topten_business') {
		$this->redirect(FULL_BASE_URL.router::url('/',false));
	}
 }
        /* Download function path if folder any folder in img path */
	function downloadFile($folder,$fielname) {
		$this->autoLayout = false;
		$newFileName = $fielname;
		$folder = str_replace('-','/',$folder);
		//Replace - to / to view subfolder
	    $path =  WWW_ROOT.$folder.'/'.$fielname;
		if(file_exists($path) && is_file($path)) {	
			$mimeContentType = 'application/octet-stream';
			$temMimeContentType = $this->_getMimeType($path); 
			if(isset($temMimeContentType)  && !empty($temMimeContentType))	{ 
							$mimeContentType = $temMimeContentType;
			}
	
			// START ANDR SILVA DOWNLOAD CODE
			// required for IE, otherwise Content-disposition is ignored
			if(ini_get('zlib.output_compression'))
			  	ini_set('zlib.output_compression', 'Off');
			header("Pragma: public"); // required
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false); // required for certain browsers
			header("Content-Type: " . $mimeContentType );
			// change, added quotes to allow spaces in filenames, by Rajkumar Singh
			header("Content-Disposition: attachment; filename=\"".(is_null($newFileName)?basename($path):$newFileName)."\";" );
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($path));
			readfile($path);
			exit();
			// END ANDR SILVA DOWNLOAD CODE
		 }
		 if(isset($_SERVER['HTTP_REFERER'])) {
		 	 $this->Session->setFlash('File not found.');
			 $this->redirect($_SERVER['HTTP_REFERER']);
		 }
 	}
	function beforeRender() {
		//$this->Ssl->force();
	}
	function _getMimeType($filepath) {
		ob_start();
		system("file -i -b {$filepath}");
		$output = ob_get_clean();
		$output = explode("; ",$output);
		if ( is_array($output) ) {
			$output = $output[0];
		}
		return $output;
	}
}
?>