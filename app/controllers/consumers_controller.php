<?php 
/*
   Coder: Keshav
*/
class ConsumersController extends AppController{
 var $name = 'Consumers'; 

 var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax'); 

 var $components = array('Auth','common','Session','Cookie','RequestHandler','Email','emailhtml');  //component to check authentication . this component file is exists in app/controllers/components

 var $layout = 'admin'; //this is the layout for admin panel 
 

	 
	 #this function call by default when a controller is called
	 function index()
	 {
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;
		if($this->Session->check('Auth.Admin'))
		{
		   $this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   $this->set('common',$this->common);
		   $condition='';
		   $condition[]= '(Consumer.user_type = "customer" OR Consumer.user_type = "parent")';
		   $this->set('name','Name');
		   $this->set('county_id','');
		   $this->set('status','');	
		   $this->set('type','');		   
	       $this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('Consumer.id' => 'desc'));

		if((isset($this->data['Consumer']['name']) && $this->data['Consumer']['name'] !='Name') ||  (isset($this->params['named']['name']) && $this->params['named']['name'] !='Name'))
		
		 {
		if(isset($this->params['named']['name']))
		{
		    $condition[] = 'Consumer.name LIKE "%' . str_replace("%20"," ",$this->params['named']['name']). '%"';
		}
		else
		{
		 	$condition[] = 'Consumer.name LIKE "%'.$this->data['Consumer']['name'].'%"';
		 }
		(empty($this->params['named'])) ? $this->set('name', $this->data['Consumer']['name']) :$this->set('name', $this->params['named']['name']) ; 
		 } 
				 
	if($this->data['Consumer']['county_id']!='' ||  isset($this->params['named']['county_id'] )) 
	{
		  if(isset($this->params['named']['county_id']))
		  {
			 $condition[] = 'Consumer.county_id = '.$this->params['named']['county_id'];
		  }
		  else
		  {
			  $condition[] = 'Consumer.county_id = '.$this->data['Consumer']['county_id'];
		  }					  
		 (empty($this->params['named'])) ? $this->set('county_id', $this->data['Consumer']['county_id']) :$this->set('county_id', $this->params['named']['county_id']); 
	}
	
	if($this->data['Consumer']['type']!='' ||  isset($this->params['named']['type'] )) 
	{
		  if(isset($this->params['named']['type']))
		  {
			 $condition['Consumer.type'] = $this->params['named']['type'];
		  }
		  else
		  {
			 $condition['Consumer.type'] = $this->data['Consumer']['type'];
		  }					  
		 (empty($this->params['named'])) ? $this->set('type', $this->data['Consumer']['type']) :$this->set('type', $this->params['named']['type']); 
	}
	
	
	
	if((isset($this->data['Consumer']['status']) && $this->data['Consumer']['status']!='') || (isset($this->params['named']['status']) && $this->params['named']['status']!='')) 
	 {
		  if(isset($this->params['named']['status']))
		  {
			 $condition[] = 'Consumer.status = "'.$this->params['named']['status'].'"';
		  }
		  else
		  {
			 $condition[] = 'Consumer.status = "'.$this->data['Consumer']['status'].'"';
		  }
					   
	(empty($this->params['named'])) ? $this->set('status', $this->data['Consumer']['status']) :$this->set('status', $this->params['named']['status']) ; 
	}
				 
				$data = $this->paginate('Consumer', $condition);
		        $this->set('Consumers', $data); 
 
			}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}

	 }
/*---------------------------it is used to autocomplete the search box------------------------------------------------------*/
	function countyforProfile() {
		$this->layout = false;
		if(isset($this->data['Consumer']['state_id']) && $this->data['Consumer']['state_id'] !=''){
				$state_id=$this->data['Consumer']['state_id'];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else if(isset($this->params['pass'][0]) && $this->params['pass'][0]!='') {
				$state_id	=	$this->params['pass'][0];
				$selCounty = $this->common->getAllCountyByState($state_id);
		} else {
				$selCounty = '';
		}
		$county = '';
		if(isset($this->params['pass'][1]) && $this->params['pass'][1]!='') {
				$county	=	$this->params['pass'][1];
		}
		$this->set('selCounty',$selCounty);
		$this->set('county',$county);
	}
//---------------------------------------------------------------------------------------------------//
function addNewConsumer() {
				$this->set('Categorys',$this->common->getAllCategory());
				$this->set('StatesList',$this->common->getAllState());
				//$this->set('CountyList',$this->common->getAllCounty()); //List counties
				if(isset($this->data))
				{
		  		 $this->Consumer->set($this->data);
				  if($this->Consumer->validates() && (isset($this->data['Consumer']['category']) || $this->data['Consumer']['all_cats'])){
						$this->data['Consumer']['name'] 	= $this->data['Consumer']['first_name'].' '.$this->data['Consumer']['last_name'];
						$this->data['Consumer']['user_type']	= 'customer';
						$this->data['Consumer']['status']	= $this->data['Consumer']['publish'];
						$this->data['Consumer']['realpassword']	=	$this->data['Consumer']['m_password'];
						$this->data['Consumer']['password']	= $this->Auth->password($this->data['Consumer']['m_password']);
						$this->data['Consumer']['terms_condition']	= 1;
						$this->data['Consumer']['uid']	=	$this->common->randomPassword(13);
						$this->data['Consumer']['unique_id']=$this->common->randomPassword(10);
						$this->data['Consumer']['register']	=	0;
						if($this->Consumer->save($this->data)) {
						$frontCust = $this->Consumer->getLastInsertId();
						$this->loadModel('NewsletterUser');
						$arr = '';
						$arr['NewsletterUser']['name'] = $this->data['Consumer']['first_name'].' '.$this->data['Consumer']['last_name'];
						$arr['NewsletterUser']['email'] = $this->data['Consumer']['email'];
						$arr['NewsletterUser']['zipcode'] = $this->data['Consumer']['zip'];
						$arr['NewsletterUser']['user_id'] = $frontCust;
						if(isset($this->data['Consumer']['category'])) {
							$arr['NewsletterUser']['category_id'] = implode(',',$this->data['Consumer']['category']);
						}
						$arr['NewsletterUser']['all_cats'] 	= $this->data['Consumer']['all_cats'];
						$arr['NewsletterUser']['county_id'] = $this->data['Consumer']['county_id'];
						$this->NewsletterUser->save($arr);
						$lastNewsletterUser = $this->NewsletterUser->getLastInsertId();
						$county_id = $this->data['Consumer']['county_id'];
						
						
						
						if($this->data['Consumer']['type']=='Zuni Cares') {
							$model = 'Careemaillog';
							$controller = 'careemaillogs';
						} else {
							$model = 'Contestemaillog';
							$controller = 'contestemaillogs';
						}
						
						// Email Tracking
						$this->loadModel($model);
						$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$find = $this->$model->find('first',array('fields'=>array($model.'.unique'),'conditions'=>array($model.'.sending_date'=>$today)));
						if(isset($find[$model]['unique'])) {
							$unique_string = $find[$model]['unique'];
						} else {
							$unique_string = $this->common->randomPassword(10);
						}
				
						if($this->data['Consumer']['type']=='Zuni Cares') {
							//For URL tracking
							$tracking_string = '?care='.$unique_string.'?'.base64_encode($lastNewsletterUser);
						} else {
							//For URL tracking
							$tracking_string = 'contest'.$unique_string.'?'.base64_encode($lastNewsletterUser);
						}
				
				
					$url = FULL_BASE_URL.router::url('/',false);
					$discount_url = $url.'state/'.$this->common->getStateUrl($this->data['Consumer']['county_id']).'/'.$this->common->getCountyUrl($this->data['Consumer']['county_id']).'/dailydiscount';
					
					$county_url = $url.'state/'.$this->common->getStateUrl($this->data['Consumer']['county_id']).'/'.$this->common->getCountyUrl($this->data['Consumer']['county_id']);
					//$popup_url = $url.'state/'.$this->common->getStateUrl($this->data['Consumer']['county_id']).'/'.$this->common->getCountyUrl($this->data['Consumer']['county_id']).'/register/'.$this->data['Consumer']['uid'];
					$popup_url = $county_url;
					$unsubscribe = $url.'newsletters/unsubscribe/'.base64_encode($this->data['Consumer']['email']);
//--------------------------------------------------------Zuni Cares--------------------------------------------------------//
				$mySentBoxMailType='';
				if($this->data['Consumer']['type']=='Zuni Cares') {
					$advertiser_logo='';$advertiser_name='';$advertiser_address='';$offer_image='';$offer='';$title='';$detail='';$expire='';$disclaimer='';$merchant_url='';
					$zuni_care = $this->common->getZuniCare($county_id);
					if(!empty($zuni_care)) {
					
						$merchant_url = FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrls($zuni_care['AdvertiserProfile']['state']).'/'.$this->common->getCountyUrl($zuni_care['AdvertiserProfile']['county']).'/business/coupon/'.$zuni_care['AdvertiserProfile']['page_url'].''.$tracking_string;
						
						$advertiser_logo = FULL_BASE_URL.router::url('/',false).'img/logo/'.$zuni_care['AdvertiserProfile']['logo'];
						$advertiser_name = $zuni_care['AdvertiserProfile']['company_name'];
						$advertiser_address = '';
						if($zuni_care['AdvertiserProfile']['show_address']=='yes') {
							$advertiser_address .= $zuni_care['AdvertiserProfile']['address'].'<br />'.$this->common->getCityName($zuni_care['AdvertiserProfile']['city']).', '.$this->common->getStateName($this->data['Consumer']['state_id']).', '.$zuni_care['AdvertiserProfile']['zip'].'<br />'.$zuni_care['AdvertiserProfile']['phoneno'];
						}
						
						if($zuni_care['AdvertiserProfile']['show_address2']=='yes') {
							$advertiser_address .= '<br />'.$zuni_care['AdvertiserProfile']['address2'].'<br />'.$this->common->getCityName($zuni_care['AdvertiserProfile']['city2']).', '.$this->common->getStateName($this->data['Consumer']['state_id']).', '.$zuni_care['AdvertiserProfile']['zip2'].'<br />'.$zuni_care['AdvertiserProfile']['phoneno2'];
						}
						
						$offer_image = FULL_BASE_URL.router::url('/',false).'img/offer/soffers/'.$zuni_care['SavingOffer']['offer_image_big'];
						$offer = '';
						if($zuni_care['SavingOffer']['off_unit']==2) {
							$offer =  $zuni_care['SavingOffer']['off_text'];
							} else {
								if($zuni_care['SavingOffer']['off_unit']==1) {
									$offer .= '$ ';
								}
								$offer .= $zuni_care['SavingOffer']['off'];
								if($zuni_care['SavingOffer']['off_unit']==0) {
									$offer .= ' %';
								}
							}
						$title = $zuni_care['SavingOffer']['title'];
						$detail = strip_tags($zuni_care['SavingOffer']['description']);
						$expire = date(DATE_FORMAT,$zuni_care['SavingOffer']['offer_expiry_date']);
						$disclaimer = '';
						
						if($zuni_care['SavingOffer']['no_valid_other_offer']==1) {
							$disclaimer .= 'Not valid with any other offer.<br />';
						}
						if($zuni_care['SavingOffer']['no_transferable']==1) {
							$disclaimer .= 'Non-transferable / Not for resale / Not redeemable for cash.<br />';
						}	
                        if($zuni_care['SavingOffer']['other']==1) {
							$disclaimer .= $zuni_care['SavingOffer']['disclaimer'];
						}
					}
					$arrayTags = array("[email]","[password]","[url]","[discount_page]","[popup_url]","[county_url]","[date]","[footerurl]","[advertiser_logo]","[advertiser_name]","[advertiser_address]","[offer_image]","[offer]","[title]","[detail]","[expire]","[disclaimer]","[merchant_url]","[unsubscribe]");
					
					$arrayReplace = array($this->data['Consumer']['email'],$this->data['Consumer']['m_password'],$county_url,$discount_url,$popup_url,$county_url,date('Y'),$url,$advertiser_logo,$advertiser_name,$advertiser_address,$offer_image,$offer,$title,$detail,$expire,$disclaimer,$merchant_url,$unsubscribe);
					//get Mail format
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.zuni_care_subject','Setting.zuni_care_body')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['zuni_care_subject']);
					$bodyText 	= str_replace('border="0" width="249" height="14" />','border="0" width="249" height="14" style="vertical-align:top;display: block;" />',str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['zuni_care_body']));
					$mySentBoxMailType='new_zuni_care_consumer_registration';
				} else {
//--------------------------------------------------------Without Zuni Cares--------------------------------------------------------//
					$content = '<table align="center" width="550" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">';
					$content .= $this->returnNewsletter($county_id,$tracking_string);
					$content .= '</table>';
					
					$arrayTags = array("[email]","[password]","[url]","[discount_page]","[popup_url]","[county_url]","[date]","[discount]","[footerurl]","[unsubscribe]");
					$arrayReplace = array($this->data['Consumer']['email'],$this->data['Consumer']['m_password'],$county_url,$discount_url,$popup_url,$county_url,date('Y'),$content,$url,$unsubscribe);
					//get Mail format
					$this->loadModel('Setting');
					$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.admin_consumer_subject','Setting.admin_consumer_body')));
					$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['admin_consumer_subject']);
					$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['admin_consumer_body']);
					
					$mySentBoxMailType='new_consumer_registration_admin';
				}
				
		
				// For email open tracking
					$open_tracking = '<img src="'.FULL_BASE_URL.router::url('/',false).$controller.'/saveEmailOpen?unique='.$unique_string.'?'.base64_encode($lastNewsletterUser).'" style="display:none;width:0" />';
					
												$bodyText 	= str_replace('target="_blank"><span>','target="_blank" style="color:white;text-decoration:none"><span>',$bodyText);
												$this->Email->sendAs = 'html';
												$this->Email->to = $this->data['Consumer']['email'];
												$this->Email->subject = $subject;
												//$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
												//$this->Email->from = $this->common->getFromName().'<abhi@seobrand.net>';
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = '<html><head></head><body style="margin:0px; padding:0px; font-size:0;">';
												$this->body .=$bodyText;
												$this->body .= $open_tracking;
												$this->body .= '</body></html>';
												$this->Email->smtpOptions = array(
													'port'=>'25',
													'timeout'=>'30',
													'host' =>SMTP_HOST_NAME,
													'username'=>SMTP_USERNAME,
													'password'=>SMTP_PASSWORD
												);
												/* Set delivery method */
												$this->Email->delivery = 'smtp';
												/* Do not pass any args to send()*/
												if($this->Email->send($this->body)) {
													$delevery = 'sent';
												} else {
													$delevery = 'notsent';
												}
												
			///////////////////////////sent mail insert to sent box ///////////////////
				$this->common->sentMailLog($this->common->getSalesEmail(),$this->data['Consumer']['email'],strip_tags($subject),$this->body,$mySentBoxMailType);
			//////////////////////////// Save log into Tracking tables //////////////////////////////////////////////
				$this->common->saveDeleveryTracking($model,$lastNewsletterUser,$delevery,$unique_string);
			/////////////////////////////////////////////////////////////////////////
							$this->Session->setFlash('Consumer profile has been created successfully.');
							$this->redirect(array('controller'=>'consumers','action'=>'index'));
						}
				  }else{
				  		$errors = '';
						$errors= $this->Consumer->invalidFields();
						if(!isset($this->data['Consumer']['category']) && !$this->data['Consumer']['all_cats']) {
							$errors[]= 'Please select Category.';
						}
						$this->Session->setFlash(implode('<br>', $errors));
				   }
			}
	}
/*--------------------------------------------View Newsletter---------------------------------------*/
function returnNewsletter($county,$tracking_string='') {
	$content = '';
	$cats = $this->common->getAllParentCats();
	foreach($cats as $cats) {
	$discounts = $this->common->todayDiscount($cats,$county);
	if(!empty($discounts)) {
	$content .= '<tr>
                <td width="550" height="65" align="center" valign="middle" style="background:#202020;" ><table width="550" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="center" valign="middle" style="font:22px Georgia, \'Times New Roman\', Times, serif; color:#ffffff; border:1px dashed #6e6e6e; text-transform:uppercase; font-weight:bold; padding:10px 0;">Today\'s ';
		$content .= $this->common->getCategoryName($cats);
		$content .= ' Big Deals</td>
                    </tr>
                  </table></td>
              </tr>';
			$o = 0;
			foreach($discounts as $discounts) {
			$imgurl = FULL_BASE_URL.router::url('/',false).'img/discounts/'.$discounts['DailyDiscount']['banner_image'];
			if(!file_exists(WWW_ROOT.'img/discounts/'.$discounts['DailyDiscount']['banner_image'])) {
				$imgurl = FULL_BASE_URL.router::url('/',false).'img/newsletter/pro_small.jpg';
			}
			if($discounts['DailyDiscount']['banner_image']=='') {
				$imgurl = FULL_BASE_URL.router::url('/',false).'img/newsletter/pro_small.jpg';
			}
			$disurl = FULL_BASE_URL.router::url('/',false).'img/discount_email/';
			$discount_url = FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrl($county).'/'.$this->common->getCountyUrl($county).'/dailydiscount?unique='.$discounts['DailyDiscount']['unique'].''.$tracking_string;
				if($o%2==0) {
				$content .= '<tr>
                <td align="left" valign="top" style="padding:15px; background:#dbdad5;"><table width="539" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px 0 0 0;">
                          <tr>
                            <td align="left" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="margin:0px; border:solid 4px #181818;">
                                
                                <tr>
                                  <td align="left" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="background:#fff; padding:0 0 0px 0;">
                                      <tr>
                                        <td align="center" valign="middle" colspan="2"  width="253" style=" margin:0px; padding:8px 0px; text-align:right"><img src="'.$disurl.'today_dis_small.jpg" width="234" height="36" alt=" " /></td>
                                      </tr>
                                      <tr>
                                        <td align="center" valign="middle" colspan="2"  width="253" style=" margin:0px; padding:4px 0px; ;font-size:19px; font-family:\'Times New Roman\',Georgia, Times, serif; color:#000000; line-height:24px; font-weight:bold; padding-right:15px; text-align:center;"> '.$this->common->getCompanyNameById($discounts['DailyDiscount']['advertiser_profile_id']).' </td>
                                      </tr>
                                      <tr>
                                        <td width="114" height="120" align="center" valign="top" style="padding:8px;"><img src="'.$imgurl.'" width="100" height="113" alt="pic" style="display:block; border:1px solid #dcdcdb;" /></td>
                                        <td align="left" valign="top" width="135"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td style="font-size:24px; font-family: \'Times New Roman\', Times, serif; color:#b40600; line-height:30px; font-weight:bold; padding-right:15px; text-align:right;">'.$discounts['DailyDiscount']['before_noon_saving'].'% OFF </td>
                                            </tr>
                                            <tr>
                                              <td style="font-size:15px; font-family:Georgia, \'Times New Roman\', Times, serif; color:#000000; line-height:18px;  padding-right:15px; text-align:right;">'.$discounts['DailyDiscount']['title'].' <a href="'.$discount_url.'" style="display:block;"><img src="'.$disurl.'view_now.jpg" width="86" height="25" alt=" " /></a> </td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                </tr>
                              </table></td>';
				} else {
					$content .= '<td align="right" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="margin:0px; border:solid 4px #181818;">
                                
                                <tr>
                                  <td align="left" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="background:#fff; padding:0 0 0px 0;">
                                      <tr>
                                        <td align="center" valign="middle" colspan="2"  width="253" style=" margin:0px; padding:8px 0px; text-align:right"><img src="'.$disurl.'today_dis_small.jpg" width="234" height="36" alt=" " /></td>
                                      </tr>
                                      <tr>
                                        <td align="center" valign="middle" colspan="2"  width="253" style=" margin:0px; padding:4px 0px; ;font-size:19px; font-family:\'Times New Roman\',Georgia, Times, serif; color:#000000; line-height:24px; font-weight:bold; padding-right:15px; text-align:center;"> '.$this->common->getCompanyNameById($discounts['DailyDiscount']['advertiser_profile_id']).' </td>
                                      </tr>
                                      <tr>
                                        <td width="114" height="120" align="center" valign="top" style="padding:8px;"><img src="'.$imgurl.'" width="100" height="113" alt="pic" style="display:block; border:1px solid #dcdcdb;" /></td>
                                        <td align="left" valign="top" width="135"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td style="font-size:24px; font-family: \'Times New Roman\', Times, serif; color:#b40600; line-height:30px; font-weight:bold; padding-right:15px; text-align:right;">'.$discounts['DailyDiscount']['before_noon_saving'].'% OFF </td>
                                            </tr>
                                            <tr>
                                              <td style="font-size:15px; font-family:Georgia, \'Times New Roman\', Times, serif; color:#000000; line-height:18px;  padding-right:15px; text-align:right;">'.$discounts['DailyDiscount']['title'].' <a href="'.$discount_url.'" style="display:block;"><img src="'.$disurl.'view_now.jpg" width="86" height="25" alt=" " /></a> </td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>';
				}
				$o++;
			}
			if($o%2!=0) {
				$content .= '<td align="right" valign="top">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>';
			}
		}
	}
	return $content;
}
//---------------------------------------------------------------------------------------------------//	
function editConsumer($id=NULL) {
				$this->set('StatesList',$this->common->getAllState());
				$this->set('Categorys',$this->common->getAllCategory());
				if(isset($this->data))
				{
		  			$this->Consumer->set($this->data);
				  	if($this->Consumer->validates() && (isset($this->data['Consumer']['category']) || $this->data['Consumer']['all_cats'])){
						
						$this->data['Consumer']['name'] 	= $this->data['Consumer']['first_name'].' '.$this->data['Consumer']['last_name'];
						if($this->data['Consumer']['m_password']!='') {
							$this->data['Consumer']['realpassword']= $this->data['Consumer']['m_password'];
							$this->data['Consumer']['password']= $this->Auth->password($this->data['Consumer']['m_password']);
						}
						$this->data['Consumer']['unique_id']=$this->common->randomPassword(10);
						$this->data['Consumer']['terms_condition']= 1;
						if($this->Consumer->save($this->data)) {
						
							//----------------- updating newsletter info -------------------------//
							$this->loadModel('NewsletterUser');
							$newsletter = $this->NewsletterUser->find('first',array('fields'=>array('NewsletterUser.id'),'conditions'=>array('NewsletterUser.user_id'=>$id)));
							$arr = '';
							if(isset($newsletter['NewsletterUser']['id'])) {
								$arr['NewsletterUser']['id'] = $newsletter['NewsletterUser']['id'];
							}	
								$arr['NewsletterUser']['name'] = $this->data['Consumer']['first_name'].' '.$this->data['Consumer']['last_name'];
								$arr['NewsletterUser']['email'] = $this->data['Consumer']['email'];
								$arr['NewsletterUser']['zipcode'] = $this->data['Consumer']['zip'];
								$arr['NewsletterUser']['user_id'] = $id;
								if(isset($this->data['Consumer']['category'])) {
									$arr['NewsletterUser']['category_id'] = implode(',',$this->data['Consumer']['category']);
								}
								$arr['NewsletterUser']['all_cats'] 	= $this->data['Consumer']['all_cats'];
								$arr['NewsletterUser']['county_id'] = $this->data['Consumer']['county_id'];
								$this->NewsletterUser->save($arr);
							//-------------------------------------------------------------------//
							$this->Session->setFlash('Consumer profile has been updated successfully.');
							$this->redirect(array('controller'=>'consumers','action'=>'index'));
						}
				  }else{
						$errors = $this->Consumer->invalidFields();
						if(!isset($this->data['Consumer']['category']) && !$this->data['Consumer']['all_cats']) {
							$errors[]= 'Please select Category.';
						}
						$this->Session->setFlash(implode('<br>', $errors));
				   }
				} else {
					$this->Consumer->id = $id;
					$this->data = $this->Consumer->read();
					$name = explode(' ',$this->data['Consumer']['name'],2);
					$this->data['Consumer']['first_name'] = $name[0];
					if(isset($name[1])) {
						$this->data['Consumer']['last_name'] = $name[1];
					}
					$this->loadModel('NewsletterUser');
					$newsletter = $this->NewsletterUser->find('first',array('fields'=>array('NewsletterUser.id','NewsletterUser.category_id','NewsletterUser.all_cats'),'conditions'=>array('NewsletterUser.user_id'=>$id)));
					$this->set('newsletter',$newsletter);
					if(!empty($newsletter)) {
						$this->data['Consumer']['all_cats'] = $newsletter['NewsletterUser']['all_cats'];
						$this->data['Consumer']['category'] = array_values(array_filter(explode(',',$newsletter['NewsletterUser']['category_id'])));
					}
			}
	}
	function delete($id=NULL) {
			$this->id = $id;
			if(!$this->id) {
				$this->Session->setFlash('Invalid id.');
				$this->redirect(array('controller'=>'consumers','action'=>'index'));
			} else {
				$this->Consumer->delete();
				$this->loadModel('ReferredFriend');
				$this->loadModel('ReferredBusiness');
				$this->loadModel('DiscountUser');
				$this->loadModel('NewsletterUser');
				$this->loadModel('Order');
				$this->loadModel('Printvoucher');
				$this->loadModel('Kid');
				$this->loadModel('ContestUser');
				$this->loadModel('Buck');
				
				$this->ReferredFriend->deleteAll(array('ReferredFriend.front_user_id'=>$id));
				$this->ReferredBusiness->deleteAll(array('ReferredBusiness.front_user_id'=>$id));
				$this->DiscountUser->deleteAll(array('DiscountUser.front_user_id'=>$id));
				$this->NewsletterUser->deleteAll(array('NewsletterUser.user_id'=>$id));
				$this->Order->deleteAll(array('Order.front_user_id'=>$id));
				$this->Printvoucher->deleteAll(array('Printvoucher.front_user_id'=>$id));
				$this->Kid->deleteAll(array('Kid.front_user_id'=>$id));
				$this->ContestUser->deleteAll(array('ContestUser.front_user_id'=>$id));
				$this->Buck->deleteAll(array('Buck.front_user_id'=>$id));
				
				$this->Session->setFlash('Consumer profile has been deleted successfully.');
				$this->redirect(array('controller'=>'consumers','action'=>'index'));
			}
	}
	function bucks($id=NULL) {
		if($id) {
			$this->id = $id;
			$bucks_left = $this->Consumer->field('Consumer.total_bucks');
			$byFriend = $this->common->returnbucksFriend($id);
			$byBusines = $this->common->returnbucksBusiness($id);
			$byFriend = ($byFriend[0][0]['sum(bucks)']=='')?0:$byFriend[0][0]['sum(bucks)'];
			$byBusines = ($byBusines[0][0]['sum(bucks)']=='')?0:$byBusines[0][0]['sum(bucks)'];
			$this->set('bucks_left',$bucks_left);
			$this->set('friendBucks',$byFriend);
			$this->set('businessBucks',$byBusines);			
			$this->loadModel('Order');
			$allorder = $this->Order->find('all',array('conditions'=>array('Order.front_user_id'=>$id)));
			$this->set('order_history',$allorder);
			pr($allorder);
		} else {
			$this->Session->setFlash('Invalid consumer id.');
			$this->redirect(array('controller'=>'consumers','action'=>'index'));
		}
	}
	function referFriend($id=NULL) {
		if($id) {
			$this->id = $id;		
			$this->loadModel('ReferredFriend');
			$ReferredFriend = $this->ReferredFriend->find('all',array('conditions'=>array('ReferredFriend.front_user_id'=>$id)));
			$this->set('ReferredFriend',$ReferredFriend);
		} else {
			$this->Session->setFlash('Invalid consumer id.');
			$this->redirect(array('controller'=>'consumers','action'=>'index'));
		}
	}
//---------------------------------------------------------------------------------------------------//		
	function referBusiness($id=NULL) {
		if($id) {
			$this->id = $id;		
			$this->loadModel('ReferredBusiness');
			$ReferredBusiness = $this->ReferredBusiness->find('all',array('conditions'=>array('ReferredBusiness.front_user_id'=>$id)));
			$this->set('ReferredBusiness',$ReferredBusiness);
		} else {
			$this->Session->setFlash('Invalid consumer id.');
			$this->redirect(array('controller'=>'consumers','action'=>'index'));
		}
	}	
	
/*---------------------------it is used to autocomplete the search box------------------------------*/
	function autocomplete($string='') {

			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			 App::import('model', 'Consumer');
			$this->Consumer = new Consumer;
			$name = $this->Consumer->query("SELECT Consumer.name FROM front_users AS Consumer WHERE Consumer.name LIKE '$string%' and Consumer.user_type='customer'");
			foreach($name as $name) {
				$arr[] = $name['Consumer']['name'];
			}
				echo json_encode($arr);
			}
	}
/*------------------------------------------------------------------------------------------------------------------------*/
	function parent_user() {
	   	App::import('model', 'Admin');
	    $this->Admin = new Admin;	
		if($this->Session->check('Auth.Admin'))
		{
			$this->set('SchoolList',$this->common->getAllSchool()); //  List Schools
			$this->set('CountyList',$this->common->getAllCounty()); //  List counties
		   	$this->set('common',$this->common);
		   	$condition='';
		   	$condition[]= 'Consumer.user_type = "parent"';
		   	$this->set('name','Name');
		   	$this->set('county_id','');
		   	$this->set('school_id',''); 
	       	$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('Consumer.id' => 'desc'));

		if((isset($this->data['Consumer']['name']) && $this->data['Consumer']['name'] !='Name') ||  (isset($this->params['named']['name']) && $this->params['named']['name'] !='Name'))
		 {
		if(isset($this->params['named']['name']))
		{
		    $condition[] = 'Consumer.name LIKE "%' . str_replace("%20"," ",$this->params['named']['name']). '%"';
		}
		else
		{
		 	$condition[] = 'Consumer.name LIKE "%' .$this->data['Consumer']['name']. '%"';
		 }
		(empty($this->params['named'])) ? $this->set('name', $this->data['Consumer']['name']) :$this->set('name', $this->params['named']['name']) ; 
		 } 
				 
	if($this->data['Consumer']['county_id']!='' ||  isset($this->params['named']['county_id'] )) 
	{
		  if(isset($this->params['named']['county_id']))
		  {
			 $condition[] = 'Consumer.county_id = '.$this->params['named']['county_id'];
		  }
		  else
		  {
			  $condition[] = 'Consumer.county_id = '.$this->data['Consumer']['county_id'];
		  }
					  
		 (empty($this->params['named'])) ? $this->set('county_id', $this->data['Consumer']['county_id']) :$this->set('county_id', $this->params['named']['county_id']) ; 
	}
				 


	if($this->data['Consumer']['school_id']!='' ||  isset($this->params['named']['school_id'] )) 
	{
		  if(isset($this->params['named']['school_id']))
		  {
			 $condition[] = 'Consumer.school_id = '.$this->params['named']['school_id'];
		  }
		  else
		  {
			  $condition[] = 'Consumer.school_id = '.$this->data['Consumer']['school_id'];
		  }
					  
		 (empty($this->params['named'])) ? $this->set('school_id', $this->data['Consumer']['school_id']) :$this->set('school_id', $this->params['named']['school_id']) ; 
	}
				 
				$data = $this->paginate('Consumer', $condition);
		        $this->set('Consumers', $data);
 
			}
			else
			{
				$this->Session->setFlash('You are not authorized to access this location.');
				$this->redirect(array('action' => "login"));
			}

	 }
/*------------------------------------------------------------------------------------------------------------------------*/ 	 
	function referred($id=null) {
		if($id) {
			$this->loadModel('RefferedParent');
			$referred = $this->RefferedParent->find('all',array('conditions'=>array('RefferedParent.front_user_id'=>$id)));
			$this->set('ReferredFriend',$referred);
		} else {
			$this->redirect(array('action' => "parent_user"));
		}			
	}
/*------------------------------------------------------------------------------------------------------------------------*/ 	
	function editParent($id=NULL) {
				$this->set('CountyList',$this->common->getAllCounty()); //  List counties			
				if(isset($this->data))
				{
		  $this->Consumer->set($this->data);
				  if($this->Consumer->validates()){
						$this->data['Consumer']['name'] 	= $this->data['Consumer']['first_name'].' '.$this->data['Consumer']['last_name'];			
						$this->data['Consumer']['user_type']= 'parent';
						if($this->data['Consumer']['m_password']!='') {
							$this->data['Consumer']['realpassword']= $this->data['Consumer']['m_password'];							
							$this->data['Consumer']['password']= $this->Auth->password($this->data['Consumer']['m_password']);							
						}
						$this->data['Consumer']['terms_condition']= 1;
						$this->data['Consumer']['unique_id']=$this->common->randomPassword(10);
						if($this->Consumer->save($this->data)) {
							$this->Session->setFlash('Consumer profile has been updated successfully.');
							$this->redirect(array('controller'=>'consumers','action'=>'parent_user'));
						}					
				  }else{				   
						$errors = $this->Consumer->invalidFields();	
						$this->Session->setFlash(implode('<br>', $errors));			   
				   }
				} else {
					$this->Consumer->id = $id;
					$this->data = $this->Consumer->read();
					$name = explode(' ',$this->data['Consumer']['name'],2);
					$this->data['Consumer']['first_name'] = $name[0];
					if(isset($name[1])) {
						$this->data['Consumer']['last_name'] = $name[1];
					}
			}
	}
//---------------------------------destroy all current sessions for a perticular SuperAdmins and redirect to login page automatically------------------------------//
	function addFundraiser() {
		if(isset($this->data)) {
			if($this->data['Consumer']['parent_file']['error']!=0){
				$this->Session->setFlash('Please upload a file.');
			}
			else if($this->data['Consumer']['parent_file']['type']!='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && $this->data['Consumer']['parent_file']['type']!='application/vnd.ms-excel') {
				$this->Session->setFlash('Please upload .xls file or .xlsx file.');
			}
			else if($this->data['Consumer']['parent_file']['error']==0) {
			set_time_limit(0);
				$docDestination = APP.'webroot/fundraiser/'.$this->data['Consumer']['parent_file']['name'];
				@chmod(APP.'webroot/fundraiser',0777);
				move_uploaded_file($this->data['Consumer']['parent_file']['tmp_name'], $docDestination) or die($docDestination);
				$this->loadModel('ReferredFriend');
				//---------------------------------------------------------------------------------------------//
				if($this->data['Consumer']['parent_file']['type']=='application/vnd.ms-excel') {
					require_once APP.'webroot/simplexls.class.php';
					$xls = new Spreadsheet_Excel_Reader($docDestination);
					for ($row=2;$row<=$xls->rowcount();$row++) {
						$parent = '';
						$newarr='';
						for ($col=1;$col<=$xls->colcount();$col++) {
							$newarr[$col]=trim($xls->val($row,$col));						
						}
						//check if email id is present or blank
						if(isset($newarr[10]) && $newarr[10]!='') {
							//check if parent email id is already exist or not
							$parent = $this->common->getParentDetails(trim($newarr[10]));
							if(is_array($parent) && !empty($parent)) {
								//check if refer email id is already exist or not
								$check_refer = $this->common->checkReferral($newarr[14]);
								//if not exist then save
								if(empty($check_refer)) {
										$kid = $this->common->getKid($parent['FrontUser']['id']);
										$savearr = '';
										$savearr['ReferredFriend']['id'] = '';
										$savearr['ReferredFriend']['name'] = $newarr[12].' '.$newarr[13];
										$savearr['ReferredFriend']['email'] = $newarr[14];
										$savearr['ReferredFriend']['front_user_id'] = $parent['FrontUser']['id'];
										$savearr['ReferredFriend']['county_id'] = $parent['FrontUser']['county_id'];
										$savearr['ReferredFriend']['kid_id'] = $kid['Kid']['id'];
										$savearr['ReferredFriend']['school_id'] = $kid['Kid']['school_id'];
										$savearr['ReferredFriend']['state_id'] = $this->common->getStateByCountyId($parent['FrontUser']['county_id']);
										$savearr['ReferredFriend']['status'] = 'no';
										$savearr['ReferredFriend']['refer_ip'] = $_SERVER['REMOTE_ADDR'];
										$refer_date = '';
										$refer_date = explode('/',$newarr[11]);
										$savearr['ReferredFriend']['refered_date'] = mktime(0,0,0,$refer_date[0],$refer_date[1]-1,$refer_date[2]);
										//$savearr['ReferredFriend']['refered_date'] = mktime(0,0,0,date('m'),date('d'),date('Y'));										
										$this->ReferredFriend->save($savearr);
								}								
							} else {
								$stateid 	= $this->common->checkState($newarr[1]);
								$countyid 	= $this->common->checkCounty($newarr[2],$stateid);
								$schoolid 	= $this->common->checkSchool($newarr[3],$stateid,$countyid);
								$this->loadModel('FrontUser');
								$saveUser = '';
								$saveUser['FrontUser']['id'] = '';
								$saveUser['FrontUser']['name'] = trim($newarr[8]).' '.trim($newarr[9]);
								$saveUser['FrontUser']['email'] = trim($newarr[10]);
								$saveUser['FrontUser']['county_id'] = $countyid;
								$saveUser['FrontUser']['user_type'] = 'parent';
								$saveUser['FrontUser']['grade'] 	= trim($newarr[4]);
								$saveUser['FrontUser']['teacher'] 	= trim($newarr[5]);
								$saveUser['FrontUser']['school_id'] = $schoolid;
								$saveUser['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
								$this->FrontUser->save($saveUser);
								$parent_id = $this->FrontUser->getlastinsertid();
								//Savw Kid
								$this->loadModel('Kid');
								$saveKid = '';
								$saveKid['Kid']['id'] = '';
								$saveKid['Kid']['child_name'] = trim($newarr[6]).' '.trim($newarr[7]);
								$saveKid['Kid']['front_user_id'] = $parent_id;
								$saveKid['Kid']['school_id'] = $schoolid;
								$this->Kid->save($saveKid);
								$kid_id = $this->Kid->getlastinsertid();								
								
								//check if refer email id is already exist or not
								$check_refer = $this->common->checkReferral($newarr[14]);
								//if not exist then save
								if(empty($check_refer)) {
										$savearr = '';
										$savearr['ReferredFriend']['id'] = '';
										$savearr['ReferredFriend']['name'] = $newarr[12].' '.$newarr[13];
										$savearr['ReferredFriend']['email'] = $newarr[14];
										$savearr['ReferredFriend']['front_user_id'] = $parent_id;
										$savearr['ReferredFriend']['county_id'] = $countyid;
										$savearr['ReferredFriend']['kid_id'] = $kid_id;
										$savearr['ReferredFriend']['school_id'] = $schoolid;
										$savearr['ReferredFriend']['state_id'] =$stateid;
										$savearr['ReferredFriend']['status'] = 'no';
										$savearr['ReferredFriend']['refer_ip'] = $_SERVER['REMOTE_ADDR'];
										$refer_date = '';
										$refer_date = explode('/',$newarr[11]);
										$savearr['ReferredFriend']['refered_date'] = mktime(0,0,0,$refer_date[0],$refer_date[1]-1,$refer_date[2]);
										//$savearr['ReferredFriend']['refered_date'] = mktime(0,0,0,date('m'),date('d'),date('Y'));
										$this->ReferredFriend->save($savearr);
								}
							}
						}
					}
				} else if($this->data['Consumer']['parent_file']['type']=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
					require_once APP.'webroot/simplexlsx.class.php';
					$xlsx = new SimpleXLSX($docDestination);
					list($cols,) = $xlsx->dimension();
					$check = 1;
					foreach( $xlsx->rows() as $k => $r) {
							if($check!=1) {
								$parent = '';
								$newarr='';
								for( $i = 0; $i < $cols; $i++) {
									$newarr[$i+1]=trim($r[$i]);
								}
								//check if email id is present or blank
								if(isset($newarr[10]) && $newarr[10]!='') {
									//check if parent email id is already exist or not
									$parent = $this->common->getParentDetails(trim($newarr[10]));
									if(is_array($parent) && !empty($parent)) {
										//check if refer email id is already exist or not
										$check_refer = $this->common->checkReferral($newarr[14]);
										//if not exist then save
										if(empty($check_refer)) {
												$kid = $this->common->getKid($parent['FrontUser']['id']);
												$savearr = '';
												$savearr['ReferredFriend']['id'] = '';
												$savearr['ReferredFriend']['name'] = $newarr[12].' '.$newarr[13];
												$savearr['ReferredFriend']['email'] = $newarr[14];
												$savearr['ReferredFriend']['front_user_id'] = $parent['FrontUser']['id'];
												$savearr['ReferredFriend']['county_id'] = $parent['FrontUser']['county_id'];
												$savearr['ReferredFriend']['kid_id'] = $kid['Kid']['id'];
												$savearr['ReferredFriend']['school_id'] = $kid['Kid']['school_id'];
												$savearr['ReferredFriend']['state_id'] = $this->common->getStateByCountyId($parent['FrontUser']['county_id']);
												$savearr['ReferredFriend']['status'] = 'no';
												$savearr['ReferredFriend']['refer_ip'] = $_SERVER['REMOTE_ADDR'];
												$savearr['ReferredFriend']['refered_date'] = mktime(0,0,0,date('m'),date('d'),date('Y'));
												//$savearr['ReferredFriend']['refered_date'] = mktime(0,0,0,05,14,2012);
												$this->ReferredFriend->save($savearr);
										}								
									} else {
										$stateid 	= $this->common->checkState($newarr[1]);
										$countyid 	= $this->common->checkCounty($newarr[2],$stateid);
										$schoolid 	= $this->common->checkSchool($newarr[3],$stateid,$countyid);
										$this->loadModel('FrontUser');
										$saveUser = '';
										$saveUser['FrontUser']['id'] = '';
										$saveUser['FrontUser']['name'] = trim($newarr[8]).' '.trim($newarr[9]);
										$saveUser['FrontUser']['email'] = trim($newarr[10]);
										$saveUser['FrontUser']['county_id'] = $countyid;
										$saveUser['FrontUser']['user_type'] = 'parent';										
										$saveUser['FrontUser']['grade'] 	= trim($newarr[4]);
										$saveUser['FrontUser']['teacher'] 	= trim($newarr[5]);
										$saveUser['FrontUser']['school_id'] = $schoolid;
										$saveUser['FrontUser']['unique_id']	=	$this->common->randomPassword(10);
										$this->FrontUser->save($saveUser);
										$parent_id = $this->FrontUser->getlastinsertid();
										//Savw Kid
										$this->loadModel('Kid');
										$saveKid = '';
										$saveKid['Kid']['id'] = '';
										$saveKid['Kid']['child_name'] = trim($newarr[6]).' '.trim($newarr[7]);
										$saveKid['Kid']['front_user_id'] = $parent_id;
										$saveKid['Kid']['school_id'] = $schoolid;
										$this->Kid->save($saveKid);
										$kid_id = $this->Kid->getlastinsertid();							
										
										//check if refer email id is already exist or not
										$check_refer = $this->common->checkReferral($newarr[14]);
										//if not exist then save
										if(empty($check_refer)) {
												$savearr = '';
												$savearr['ReferredFriend']['id'] = '';
												$savearr['ReferredFriend']['name'] = $newarr[12].' '.$newarr[13];
												$savearr['ReferredFriend']['email'] = $newarr[14];
												$savearr['ReferredFriend']['front_user_id'] = $parent_id;
												$savearr['ReferredFriend']['county_id'] = $countyid;
												$savearr['ReferredFriend']['kid_id'] = $kid_id;
												$savearr['ReferredFriend']['school_id'] = $schoolid;
												$savearr['ReferredFriend']['state_id'] =$stateid;
												$savearr['ReferredFriend']['status'] = 'no';
												$savearr['ReferredFriend']['refer_ip'] = $_SERVER['REMOTE_ADDR'];
												$savearr['ReferredFriend']['refered_date'] = mktime(0,0,0,date('m'),date('d'),date('Y'));
												//$savearr['ReferredFriend']['refered_date'] = mktime(0,0,0,05,14,2012);
												$this->ReferredFriend->save($savearr);
										}
									}
								}							
							}
						$check++;
					}
				}
				//---------------------------------------------------------------------------------------------//
				unlink($docDestination);
				$this->Session->setFlash('Excel sheet has been uploaded successfully.');
				$this->redirect(array('action'=>'parent_user'));
			}
		}
	}
/*------------------------------------------------------------------------------------------------------------------------*/
	function parent_delete($id=NULL) {
			$this->id = $id;
			if(!$this->id) {
				$this->Session->setFlash('Invalid id.');
				$this->redirect($this->referer());
			} else {
				$this->loadModel('ReferredFriend');
				$this->loadModel('ReferredBusiness');
				$this->loadModel('Kid');
				$this->Consumer->delete();
				$this->Kid->deleteAll(array('Kid.front_user_id'=>$id));
				$this->ReferredFriend->deleteAll(array('ReferredFriend.front_user_id'=>$id));
				$this->ReferredBusiness->deleteAll(array('ReferredBusiness.front_user_id'=>$id));
				$this->Session->setFlash('Consumer profile has been deleted successfully.');
				$this->redirect($this->referer());
			}
	}
//------------------------------------------------------------------------------------------------------------------------//
	function addcounsumers($file='first.xls'){
				$this->autoRender = false;
				set_time_limit(0);
				$docDestination = APP.'webroot/lists/'.$file;
				//---------------------------------------------------------------------------------------------//
					require_once APP.'webroot/simplexls.class.php';
					$xls = new Spreadsheet_Excel_Reader($docDestination);
					for ($row=2;$row<=$xls->rowcount();$row++) {
						$parent = '';
						$newarr='';
						for ($col=1;$col<=$xls->colcount();$col++) {
							$newarr[$col]=trim($xls->val($row,$col));
						}
						$checkmail = $this->Consumer->find('count',array('conditions'=>array('Consumer.email'=>$newarr[3],'Consumer.county_id'=>123,'Consumer.user_type'=>'customer')));
						if(!$checkmail) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------//
						$consumerarr = '';
						$consumerarr['Consumer']['id'] = '';
						$consumerarr['Consumer']['name'] 	= $newarr[1].' '.$newarr[2];
						$consumerarr['Consumer']['user_type']	= 'customer';
						$consumerarr['Consumer']['email']	= $newarr[3];
						$consumerarr['Consumer']['state_id']	= 3;
						$consumerarr['Consumer']['county_id']	= 123;
						$consumerarr['Consumer']['status']	= 'yes';
						$password = $this->common->randomPassword(8);
						$consumerarr['Consumer']['realpassword']	=	$password;
						$consumerarr['Consumer']['password']	= $this->Auth->password($password);
						$consumerarr['Consumer']['terms_condition']	= 1;
						$consumerarr['Consumer']['receive_email']	= 1;
						$consumerarr['Consumer']['uid']	=	$this->common->randomPassword(13);
						$consumerarr['Consumer']['unique_id']=$this->common->randomPassword(10);
						$consumerarr['Consumer']['register']	=	0;
						if($this->Consumer->save($consumerarr,false)) {
						$frontCust = $this->Consumer->getLastInsertId();
						$this->loadModel('NewsletterUser');
						$arr = '';
						$arr['NewsletterUser']['id'] = '';
						$arr['NewsletterUser']['name'] = $newarr[1].' '.$newarr[2];
						$arr['NewsletterUser']['email'] = $newarr[3];
						$arr['NewsletterUser']['user_id'] = $frontCust;
						$arr['NewsletterUser']['category_id'] = ',26,';
						$arr['NewsletterUser']['county_id'] = 123;
						$this->NewsletterUser->save($arr,false);
						
						$this->loadModel('DailyDiscount');
						$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
						$today2 = mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$county_id = 123;
						$daily_disc = $this->DailyDiscount->find('all',array('fields'=>array('DailyDiscount.title','DailyDiscount.before_noon_saving','DailyDiscount.banner_image','DailyDiscount.discount_details','DailyDiscount.id'),'conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today1 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=123 AND DailyDiscount.show_on_home_page=1"),'limit'=>3,'order'=>array('RAND()')));
					
					
					$url = FULL_BASE_URL.router::url('/',false);
					$discount_url = $url.'state/'.$this->common->getStateUrl(123).'/'.$this->common->getCountyUrl(123).'/dailydiscount';
					$popup_url = $url.'state/'.$this->common->getStateUrl(123).'/'.$this->common->getCountyUrl(123).'/register/'.$consumerarr['Consumer']['uid'];
					$county_url = $url.'state/'.$this->common->getStateUrl(123).'/'.$this->common->getCountyUrl(123).'/';
					$disids = '';
					$table1 = '<table align="center" width="568" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;"><tr><td width="550" height="65" align="center" valign="middle" style="background:#202020;" ><table width="550" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="middle" style="font:22px Georgia, \'Times New Roman\', Times, serif; color:#ffffff; border:1px dashed #6e6e6e; text-transform:uppercase; font-weight:bold; padding:10px 0;">Today\'s Home Page Variety Big Deals</td></tr></table></td></tr>';
					if(isset($daily_disc[0]['DailyDiscount']['title'])) {
						$disids[] = $daily_disc[0]['DailyDiscount']['id'];
						$title1 = $daily_disc[0]['DailyDiscount']['title'];
						$img1 = FULL_BASE_URL.router::url('/',false).'img/discounts/'.$daily_disc[0]['DailyDiscount']['banner_image'];
						if(!file_exists(WWW_ROOT.'img/discounts/'.$daily_disc[0]['DailyDiscount']['banner_image'])) {
							$img1 = FULL_BASE_URL.router::url('/',false).'img/no_DailyDiscounts.jpg';
						}
						if($daily_disc[0]['DailyDiscount']['banner_image']=='') {
							$img1 = FULL_BASE_URL.router::url('/',false).'img/no_DailyDiscounts.jpg';
						}
						$off1 = $daily_disc[0]['DailyDiscount']['before_noon_saving'];
						$discount_details = strip_tags($daily_disc[0]['DailyDiscount']['discount_details']);
						
						$discount1 = '<tr><td align="left" valign="top" style="padding:30px 15px ; background:#dbdad5;"><table width="428" border="0" cellspacing="0" cellpadding="0" style="background:#fff; padding:0 0 0px 0; width:428px; margin:0 auto; border: 4px solid #181818;"><tr><td align="center" valign="middle" colspan="2" width="428" style="font:28px Georgia, \'Times New Roman\', Times, serif; color:#b50601; font-weight:bold; text-transform:uppercase; margin:0px; padding:10px 0; text-align:right;"><img src="'.$url.'img/newsletter/today_dis.jpg" width="325" height="48" alt=" " /></td></tr><tr><td width="200" align="center" valign="top" style="padding:15px;"><img src="'.$img1.'" width="200" height="235" alt="pic" style="display:block; border:1px solid #dcdcdb;" /></td><td align="left" valign="top" width="200"><table width="200" border="0" cellspacing="0" cellpadding="0"><tr><td width="200" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td style="font-size:32px; font-family: \'Times New Roman\', Times, serif; color:#b40600; line-height:50px; font-weight:bold; padding-right:15px; text-align:right;" width="185">'.$off1.'% OFF </td></tr><tr><td style="font-size:35px; font-family:\'Times New Roman\',Georgia, Times, serif; color:#000000; line-height:50px; font-weight:bold; padding-right:15px; text-align:right;" width="250">'.$title1.'</td></tr></table></td></tr><tr><td align="left" valign="top" style=" text-align:right; padding:10px 0; padding-right:15px;"><a href="'.$discount_url.'"><img src="'.$url.'img/newsletter/view_now_bt.jpg" width="118" height="33" alt=" " /></a></td></tr></table></td></tr></table></td></tr>';
					} else {
						$img1 = FULL_BASE_URL.router::url('/',false).'img/big_click.jpg';
						$discount1 ='<a href="'.$discount_url.'"><img src="'.$img1.'" alt="pic" /></a>';
					}
					if(isset($daily_disc[1]['DailyDiscount']['title'])) {
						$disids[] = $daily_disc[1]['DailyDiscount']['id'];
						$title2 = $daily_disc[1]['DailyDiscount']['title'];
						$img2 = FULL_BASE_URL.router::url('/',false).'img/discounts/'.$daily_disc[1]['DailyDiscount']['banner_image'];
						if(!file_exists(WWW_ROOT.'img/discounts/'.$daily_disc[1]['DailyDiscount']['banner_image'])) {
							$img2 = FULL_BASE_URL.router::url('/',false).'img/newsletter/pro_small.jpg';
						}
						if($daily_disc[1]['DailyDiscount']['banner_image']=='') {
							$img2 = FULL_BASE_URL.router::url('/',false).'img/newsletter/pro_small.jpg';
						}
						$discount2 = '<tr><td align="left" valign="top" style="padding:15px; background:#dbdad5;"><table width="539" border="0" cellspacing="0" cellpadding="0"><tr><td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px 0 0 0;"><tr><td align="left" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="margin:0px; border:solid 4px #181818;"><tr></tr><tr><td align="left" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="background:#fff; padding:0 0 0px 0;"><tr><td align="center" valign="middle" colspan="2"  width="253" style=" margin:0px; padding:8px 0px; text-align:right"><img src="'.$url.'img/newsletter/today_dis_small.jpg" width="234" height="36" alt=" " /></td></tr><tr><td width="114" align="center" valign="top" style="padding:8px;"><img src="'.$img2.'" width="100" height="113" alt="pic" style="display:block; border:1px solid #dcdcdb;" /></td><td align="left" valign="top" width="135"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="135" align="left" valign="top" style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#930100; line-height:25px; text-align:center; padding:8px 0; padding-right:8px;">'.$title2.'<a href="'.$discount_url.'" style="display:block;"><img src="'.$url.'img/newsletter/view_now.jpg" width="86" height="25" alt=" " /></a></td></tr></table></td></tr></table></td></tr></table></td>';				
					} else {
						$discount2 = '<tr><td align="left" valign="top" style="padding:15px; background:#dbdad5;"><table width="539" border="0" cellspacing="0" cellpadding="0"><tr><td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px 0 0 0;"><tr><td align="left" valign="top">&nbsp;</td>';
					}
					if(isset($daily_disc[2]['DailyDiscount']['title'])) {
						$disids[] = $daily_disc[2]['DailyDiscount']['id'];
						$title3 = $daily_disc[2]['DailyDiscount']['title'];
						$img3 = FULL_BASE_URL.router::url('/',false).'img/discounts/'.$daily_disc[2]['DailyDiscount']['banner_image'];
						if(!file_exists(WWW_ROOT.'img/discounts/'.$daily_disc[2]['DailyDiscount']['banner_image'])) {
							$img3 = FULL_BASE_URL.router::url('/',false).'img/newsletter/pro_small.jpg';
						}
						if($daily_disc[2]['DailyDiscount']['banner_image']=='') {
							$img3 = FULL_BASE_URL.router::url('/',false).'img/newsletter/pro_small.jpg';
						}
						
						$discount3 = '<td align="right" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="margin:0px; border:solid 4px #181818;"><tr></tr><tr><td align="left" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="background:#fff; padding:0 0 0px 0;"><tr><td align="center" valign="middle" colspan="2"  width="253" style=" margin:0px; padding:8px 0px; text-align:right"><img src="'.$url.'img/newsletter/today_dis_small.jpg" width="234" height="36" alt=" " /></td></tr><tr><td width="114" align="center" valign="top" style="padding:8px;"><img src="'.$img3.'" width="100" height="113" alt="pic" style="display:block; border:1px solid #dcdcdb;" /></td><td align="left" valign="top" width="135"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="135" align="left" valign="top" style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#930100; line-height:25px; text-align:center; padding:8px 0; padding-right:8px">'.$title3.'<a href="'.$discount_url.'" style="display:block;"><img src="'.$url.'img/newsletter/view_now.jpg" width="86" height="25" alt=" " /></a></td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr>';
					} else {
						$discount3  ='<td align="right" valign="top">&nbsp;</td></tr></table></td></tr></table></td></tr>';
					}		
					
					
					
						$table2 = '</table>';
						$remaining = '';
					if(count($disids)==3) {
						$id_cond = "DailyDiscount.id NOT IN (".implode(',',$disids).")";
						$daily_disc_remain = $this->DailyDiscount->find('all',array('fields'=>array('DailyDiscount.title','DailyDiscount.before_noon_saving','DailyDiscount.banner_image','DailyDiscount.discount_details'),'conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today1 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=$county_id AND DailyDiscount.show_on_home_page=1 AND $id_cond"),'order'=>array('RAND()')));
						$needextra = 0;
						if(count($daily_disc_remain%2)!=0) {
							$needextra = 1;
						}
						$counter = 0;
						foreach($daily_disc_remain as $daily_disc_remain) {
							$img = FULL_BASE_URL.router::url('/',false).'img/discounts/'.$daily_disc_remain['DailyDiscount']['banner_image'];
							if(!file_exists(WWW_ROOT.'img/discounts/'.$daily_disc_remain['DailyDiscount']['banner_image'])) {
								$img = FULL_BASE_URL.router::url('/',false).'img/newsletter/pro_small.jpg';
							}						
							$title = $daily_disc_remain['DailyDiscount']['title'];
								
							if($counter%2==0) {								
								$remaining.='<tr><td align="left" valign="top" style="padding:15px; background:#dbdad5;"><table width="539" border="0" cellspacing="0" cellpadding="0"><tr><td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px 0 0 0;"><tr><td align="left" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="margin:0px; border:solid 4px #181818;"><tr></tr><tr><td align="left" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="background:#fff; padding:0 0 0px 0;"><tr><td align="center" valign="middle" colspan="2"  width="253" style=" margin:0px; padding:8px 0px; text-align:right"><img src="'.$url.'img/newsletter/today_dis_small.jpg" width="234" height="36" alt=" " /></td></tr><tr><td width="114" align="center" valign="top" style="padding:8px;"><img src="'.$img.'" width="100" height="113" alt="pic" style="display:block; border:1px solid #dcdcdb;" /></td><td align="left" valign="top" width="135"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="135" align="left" valign="top" style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#930100; line-height:25px; text-align:center; padding:8px 0; padding-right:8px;">'.$title.'<a href="'.$discount_url.'" style="display:block;"><img src="'.$url.'img/newsletter/view_now.jpg" width="86" height="25" alt=" " /></a></td></tr></table></td></tr></table></td></tr></table></td>';
							} else {
								$remaining.='<td align="right" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="margin:0px; border:solid 4px #181818;"><tr></tr><tr><td align="left" valign="top"><table width="253" border="0" cellspacing="0" cellpadding="0" style="background:#fff; padding:0 0 0px 0;"><tr><td align="center" valign="middle" colspan="2"  width="253" style=" margin:0px; padding:8px 0px; text-align:right"><img src="'.$url.'img/newsletter/today_dis_small.jpg" width="234" height="36" alt=" " /></td></tr><tr><td width="114" align="center" valign="top" style="padding:8px;"><img src="'.$img.'" width="100" height="113" alt="pic" style="display:block; border:1px solid #dcdcdb;" /></td><td align="left" valign="top" width="135"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="135" align="left" valign="top" style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#930100; line-height:25px; text-align:center; padding:8px 0; padding-right:8px">'.$title.'<a href="'.$discount_url.'" style="display:block;"><img src="'.$url.'img/newsletter/view_now.jpg" width="86" height="25" alt="" /></a></td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr>';
							}
							$counter++;
						} 
						
						
						if($needextra==1) {
							$remaining.='<td align="right" valign="top">&nbsp;</td></tr></table></td></tr></table></td></tr>';
							
						}
					}
						
												$arrayTags = array("[email]","[password]","[url]","[discount_page]","[popup_url]","[county_url]","[discount1]","[discount2]","[discount3]","[table1]","[table2]","[date]","[alldiscounts]","[footerurl]");
												$arrayReplace = array($newarr[3],$password,$county_url,$discount_url,$popup_url,$county_url,$discount1,$discount2,$discount3,$table1,$table2,date('Y'),$remaining,$url);
												//get Mail format
												$this->loadModel('Setting');
												$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.admin_consumer_subject','Setting.admin_consumer_body')));
												$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['admin_consumer_subject']);
												$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['admin_consumer_body']);
												$bodyText = str_replace('target="_blank"><span>','target="_blank" style="color:white;text-decoration:none"><span>',$bodyText);												
												App::import('model', 'Setting');
	    										$this->Setting = new Setting;
												$emailArray = $this->Setting->getConsumerEmailData();
												$this->Email->sendAs = 'html';
												$this->Email->to = $newarr[3];
												$this->Email->subject = $subject;
												//$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
												//$this->Email->from = $this->common->getFromName().'<abhi@seobrand.net>';
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = '<html><head></head><body style="margin:0px; padding:0px; font-size:0;">';
												$this->body .=$bodyText;
												$this->body .= '</body></html>';
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
												/*echo htmlentities($this->body);
												exit;*/
												$this->Email->send($this->body);
												
					
					///////////////////////////sent mail insert to sent box ///////////////////
						$this->common->sentMailLog($this->common->getSalesEmail(),$newarr[3],strip_tags($subject),$this->body,"new_consumer_registration");
					/////////////////////////////////////////////////////////////////////////
	
												
												$this->Email->reset();
						}}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------//						
					}
					$this->Session->setFlash('Consumer profiles have been created successfully.');							
					$this->redirect(array('controller'=>'consumers','action'=>'index'));
	}
//------------------------------------------------------------------------------------------------------------------------//
	function addzunicares($file='first.xls'){
				$this->autoRender = false;
				set_time_limit(0);
				$docDestination = APP.'webroot/lists/cares/'.$file;
				//---------------------------------------------------------------------------------------------//
					require_once APP.'webroot/simplexls.class.php';
					$xls = new Spreadsheet_Excel_Reader($docDestination);
					
						$county_id = 123;
						
						$url = FULL_BASE_URL.router::url('/',false);
						$discount_url = $url.'state/'.$this->common->getStateUrl(123).'/'.$this->common->getCountyUrl(123).'/dailydiscount';
						$popup_url = $url.'state/'.$this->common->getStateUrl(123).'/'.$this->common->getCountyUrl(123).'/register/'.$this->data['Consumer']['uid'];
						$county_url = $url.'state/'.$this->common->getStateUrl(123).'/'.$this->common->getCountyUrl(123);
					
					

						$advertiser_logo='';$advertiser_name='';$advertiser_address='';$offer_image='';$offer='';$title='';$detail='';$expire='';$disclaimer='';$merchant_url='';
						$zuni_care = $this->common->getZuniCare(123);
						if(!empty($zuni_care)) {
						$merchant_url = FULL_BASE_URL.router::url('/',false).'state/'.$this->common->getStateUrls($zuni_care['AdvertiserProfile']['state']).'/'.$this->common->getCountyUrl($zuni_care['AdvertiserProfile']['county']).'/business/coupon/'.$zuni_care['AdvertiserProfile']['page_url'];
							$advertiser_logo = FULL_BASE_URL.router::url('/',false).'img/logo/'.$zuni_care['AdvertiserProfile']['logo'];
							$advertiser_name = $zuni_care['AdvertiserProfile']['company_name'];
							$advertiser_address = '';
							if($zuni_care['AdvertiserProfile']['show_address']=='yes') {
								$advertiser_address .= $zuni_care['AdvertiserProfile']['address'].'<br />'.$this->common->getCityName($zuni_care['AdvertiserProfile']['city']).', '.$this->common->getCountyName(123).', '.$zuni_care['AdvertiserProfile']['zip'].'<br />'.$zuni_care['AdvertiserProfile']['phoneno'];
							}
							if($zuni_care['AdvertiserProfile']['show_address2']=='yes') {
								$advertiser_address .= '<br />'.$zuni_care['AdvertiserProfile']['address2'].'<br />'.$this->common->getCityName($zuni_care['AdvertiserProfile']['city2']).', '.$this->common->getCountyName(123).', '.$zuni_care['AdvertiserProfile']['zip2'].'<br />'.$zuni_care['AdvertiserProfile']['phoneno2'];
							}
							
							$offer_image = FULL_BASE_URL.router::url('/',false).'img/offer/soffers/'.$zuni_care['SavingOffer']['offer_image_big'];
							$offer = '';
							if($zuni_care['SavingOffer']['off_unit']==2) {
								$offer =  $zuni_care['SavingOffer']['off_text'];
								} else {
									if($zuni_care['SavingOffer']['off_unit']==1) {
										$offer .= '$ ';
									}
									$offer .= $zuni_care['SavingOffer']['off'];
									if($zuni_care['SavingOffer']['off_unit']==0) {
										$offer .= ' %';
									}
								}
							$title = $zuni_care['SavingOffer']['title'];
							$detail = strip_tags($zuni_care['SavingOffer']['description']);
							$expire = date(DATE_FORMAT,$zuni_care['SavingOffer']['offer_expiry_date']);
							$disclaimer = '';
							
							if($zuni_care['SavingOffer']['no_valid_other_offer']==1) {
								$disclaimer .= 'Not valid with any other offer.<br />';
							}
							if($zuni_care['SavingOffer']['no_transferable']==1) {
								$disclaimer .= 'Non-transferable / Not for resale / Not redeemable for cash.<br />';
							}	
							if($zuni_care['SavingOffer']['other']==1) { 
								$disclaimer .= $zuni_care['SavingOffer']['disclaimer'];
							}
						}
					
						$arrayTags = array("[url]","[discount_page]","[popup_url]","[county_url]","[date]","[footerurl]","[advertiser_logo]","[advertiser_name]","[advertiser_address]","[offer_image]","[offer]","[title]","[detail]","[expire]","[disclaimer]","[merchant_url]");
					
						$arrayReplace = array($county_url,$discount_url,$popup_url,$county_url,date('Y'),$url,$advertiser_logo,$advertiser_name,$advertiser_address,$offer_image,$offer,$title,$detail,$expire,$disclaimer,$merchant_url);
					
						//get Mail format
						$this->loadModel('Setting');
						$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.zuni_care_subject','Setting.zuni_care_body')));
						$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['zuni_care_subject']);
						$bodyText 	= str_replace('border="0" width="249" height="14" />','border="0" width="249" height="14" style="vertical-align:top" />',str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['zuni_care_body']));
						$bodyText 	= str_replace('target="_blank"><span>','target="_blank" style="color:white;text-decoration:none"><span>',$bodyText);
						
					
					
					
					for ($row=2;$row<=$xls->rowcount();$row++) {
						$parent = '';
						$newarr='';
						for ($col=1;$col<=$xls->colcount();$col++) {
							$newarr[$col]=trim($xls->val($row,$col));
						}
						$checkmail = $this->Consumer->find('count',array('conditions'=>array('Consumer.email'=>$newarr[3],'Consumer.county_id'=>123,'Consumer.user_type'=>'customer')));
						if(!$checkmail) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------//
						$consumerarr = '';
						$consumerarr['Consumer']['id'] = '';
						$consumerarr['Consumer']['name'] 	= $newarr[1].' '.$newarr[2];
						$consumerarr['Consumer']['user_type']	= 'customer';
						$consumerarr['Consumer']['email']	= $newarr[3];
						$consumerarr['Consumer']['state_id']	= 3;
						$consumerarr['Consumer']['county_id']	= 123;
						$consumerarr['Consumer']['status']	= 'yes';
						$password = $this->common->randomPassword(8);
						$consumerarr['Consumer']['realpassword']	=$password;
						$consumerarr['Consumer']['password']	= $this->Auth->password($password);
						$consumerarr['Consumer']['terms_condition']	= 1;
						$consumerarr['Consumer']['receive_email']	= 1;
						$consumerarr['Consumer']['uid']	=	$this->common->randomPassword(13);
						$consumerarr['Consumer']['unique_id']=$this->common->randomPassword(10);
						$consumerarr['Consumer']['register']	=	0;
						$consumerarr['Consumer']['type']		=	'Zuni Cares';
						
						
						if($this->Consumer->save($consumerarr,false)) {
						$frontCust = $this->Consumer->getLastInsertId();
						$this->loadModel('NewsletterUser');
						$arr = '';
						$arr['NewsletterUser']['id'] = '';
						$arr['NewsletterUser']['name'] = $newarr[1].' '.$newarr[2];
						$arr['NewsletterUser']['email'] = $newarr[3];
						$arr['NewsletterUser']['user_id'] = $frontCust;
						$arr['NewsletterUser']['category_id'] = '';
						$arr['NewsletterUser']['county_id'] = 123;
						$arr['NewsletterUser']['all_cats'] = 1;
						$this->NewsletterUser->save($arr,false);
						
						$arrayTags1 = array("[email]","[password]");
						
						$arrayReplace1 = array($newarr[3],$password);
						
						$bodyText1 	= str_replace($arrayTags1,$arrayReplace1,$bodyText);
						
												$this->Email->sendAs = 'html';
												$this->Email->to = $newarr[3];
												$this->Email->subject = $subject;
												//$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
												//$this->Email->from = $this->common->getFromName().'<abhi@seobrand.net>';
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = '<html><head></head><body style="margin:0px; padding:0px; font-size:0;">';
												$this->body .=$bodyText1;
												$this->body .= '</body></html>';
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
												/*echo htmlentities($this->body);
												exit;*/
												$this->Email->send($this->body);
					
					///////////////////////////sent mail insert to sent box ///////////////////
						$this->common->sentMailLog($this->common->getSalesEmail(),$newarr[3],strip_tags($subject),$this->body,"new_zuni_care_consumer_registration");
					/////////////////////////////////////////////////////////////////////////
	
												$this->Email->reset();
						}}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------//						
					}
					$this->Session->setFlash('Consumer profiles have been created successfully.');							
					$this->redirect(array('controller'=>'consumers','action'=>'index'));
	}	
	
//------------------------------------------------------------------------------------------------------------------------//
	function addprofile($file='first.xls'){
				$this->autoRender = false;
				set_time_limit(0);
				$docDestination = APP.'webroot/lists/contests/'.$file;
				//---------------------------------------------------------------------------------------------//
					require_once APP.'webroot/simplexls.class.php';
					$xls = new Spreadsheet_Excel_Reader($docDestination);
					
						$county_id = 123;
						$content = '<table align="center" width="550" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">';
						$content .= $this->returnNewsletter(123);
						$content .= '</table>';
						$url = FULL_BASE_URL.router::url('/',false);
						$discount_url = $url.'state/'.$this->common->getStateUrl(123).'/'.$this->common->getCountyUrl(123).'/dailydiscount';
						$popup_url = $url.'state/'.$this->common->getStateUrl(123).'/'.$this->common->getCountyUrl(123).'/register/'.$this->data['Consumer']['uid'];
						$county_url = $url.'state/'.$this->common->getStateUrl(123).'/'.$this->common->getCountyUrl(123);
					
						$arrayTags = array("[url]","[discount_page]","[popup_url]","[county_url]","[date]","[discount]","[footerurl]");
						$arrayReplace = array($county_url,$discount_url,$popup_url,$county_url,date('Y'),$content,$url);
						//get Mail format
						$this->loadModel('Setting');
						$emailTemplateDet = $this->Setting->find('first',array('fields'=>array('Setting.admin_consumer_subject','Setting.admin_consumer_body')));
						$subject 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['admin_consumer_subject']);
						$bodyText 	= str_replace($arrayTags,$arrayReplace,$emailTemplateDet['Setting']['admin_consumer_body']);
						$bodyText 	= str_replace('target="_blank"><span>','target="_blank" style="color:white;text-decoration:none"><span>',$bodyText);
						
					
					
					
					for ($row=2;$row<=$xls->rowcount();$row++) {
						$parent = '';
						$newarr='';
						for ($col=1;$col<=$xls->colcount();$col++) {
							$newarr[$col]=trim($xls->val($row,$col));
						}
						$checkmail = $this->Consumer->find('count',array('conditions'=>array('Consumer.email'=>$newarr[3],'Consumer.county_id'=>123,'Consumer.user_type'=>'customer')));
						if(!$checkmail) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------//
						$consumerarr = '';
						$consumerarr['Consumer']['id'] = '';
						$consumerarr['Consumer']['name'] 	= $newarr[1].' '.$newarr[2];
						$consumerarr['Consumer']['user_type']	= 'customer';
						$consumerarr['Consumer']['email']	= $newarr[3];
						$consumerarr['Consumer']['state_id']	= 3;
						$consumerarr['Consumer']['county_id']	= 123;
						$consumerarr['Consumer']['status']	= 'yes';
						$password = $this->common->randomPassword(8);
						$consumerarr['Consumer']['realpassword']	=$password;
						$consumerarr['Consumer']['password']	= $this->Auth->password($password);
						$consumerarr['Consumer']['terms_condition']	= 1;
						$consumerarr['Consumer']['receive_email']	= 1;
						$consumerarr['Consumer']['uid']	=	$this->common->randomPassword(13);
						$consumerarr['Consumer']['unique_id']=$this->common->randomPassword(10);
						$consumerarr['Consumer']['register']	=	0;
						$consumerarr['Consumer']['type']		=	'Contest Box';
						
						
						if($this->Consumer->save($consumerarr,false)) {
						$frontCust = $this->Consumer->getLastInsertId();
						$this->loadModel('NewsletterUser');
						$arr = '';
						$arr['NewsletterUser']['id'] = '';
						$arr['NewsletterUser']['name'] = $newarr[1].' '.$newarr[2];
						$arr['NewsletterUser']['email'] = $newarr[3];
						$arr['NewsletterUser']['user_id'] = $frontCust;
						$arr['NewsletterUser']['category_id'] = '';
						$arr['NewsletterUser']['county_id'] = 123;
						$arr['NewsletterUser']['all_cats'] = 1;
						$this->NewsletterUser->save($arr,false);
						
						$arrayTags1 = array("[email]","[password]");
						
						$arrayReplace1 = array($newarr[3],$password);
						
						$bodyText1 	= str_replace($arrayTags1,$arrayReplace1,$bodyText);
						
												$this->Email->sendAs = 'html';
												$this->Email->to = $newarr[3];
												$this->Email->subject = $subject;
												//$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->replyTo = $this->common->getReturnEmail();
												$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
												//$this->Email->from = $this->common->getFromName().'<abhi@seobrand.net>';
												//$this->body = $bodyData;
												$this->body = '';
												$this->body = '<html><head></head><body style="margin:0px; padding:0px; font-size:0;">';
												$this->body .=$bodyText1;
												$this->body .= '</body></html>';
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
												/*echo htmlentities($this->body);
												exit;*/
												$this->Email->send($this->body);
					
					///////////////////////////sent mail insert to sent box ///////////////////
						$this->common->sentMailLog($this->common->getSalesEmail(),$newarr[3],strip_tags($subject),$this->body,"new_advertiser_registration");
					/////////////////////////////////////////////////////////////////////////
	
												$this->Email->reset();
						}}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------//						
					}
					$this->Session->setFlash('Consumer profiles have been created successfully.');							
					$this->redirect(array('controller'=>'consumers','action'=>'index'));
	}
//------------------------------------------------------------------------------------------------------------------------//
	function repeat(){
				$this->autoRender = false;
				set_time_limit(0);
				$docDestination = APP.'webroot/repeat/Zuni Cares Email List 12-14-12.xls';
				$full_arr = array('');
				$repeat_arr = array('');
				$tripal_arr = array('');
				$qudr_arr = array('');
				//---------------------------------------------------------------------------------------------//
					require_once APP.'webroot/simplexls.class.php';
					$xls = new Spreadsheet_Excel_Reader($docDestination);
					for ($row=2;$row<=$xls->rowcount();$row++) {
						for ($col=1;$col<=$xls->colcount();$col++) {
							$newarr[$col]=trim($xls->val($row,$col));
						}
						if(in_array($newarr[4],$full_arr)) {
								/*if(in_array($newarr[3],$repeat_arr)) {
									if(in_array($newarr[3],$tripal_arr)) {
										$qudr_arr[] = $newarr[3];
									} else {
									
										$tripal_arr[] = $newarr[3];
									}	
								} else {*/
								
									$repeat_arr[] = $newarr[4];
							//}
						} else {
							$full_arr[] = $newarr[4];
						}
						
										
					}
					pr($repeat_arr);
					//pr($tripal_arr);
					//pr($qudr_arr);
	}
/*------------------------------------------------------------------------------------------------------------------------*/		
	function savebuck($id,$buck){
				$this->autoRender = false;
				$this->data['Consumer']['id']=$id;
				$this->data['Consumer']['total_bucks']= $this->common->getConsumerBucks($id)+intval($buck);
				$this->Consumer->save($this->data);
	}
/*------------------------------------------------------------------------------------------------------------------------*/	
	function beforeFilter() {
        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );
			$this->Auth->allow('');
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
}//end class
?>