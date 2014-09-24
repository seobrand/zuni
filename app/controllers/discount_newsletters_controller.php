<?php          
 /*
   Coder: Manoj
   Date  : 10 May 2011
*/ 
  class DiscountNewslettersController extends AppController {
	    var $name    = 'DiscountNewsletters';
	    var $helpers = array('Html', 'Form', 'User', 'Javascript', 'Text', 'Image', 'Paginator','Ajax');
	    var $layout  = 'admin';
	    var $components = array('Auth','common','Cookie','RequestHandler','Email','emailhtml','Session','cronhtml');
		/*,'Email'=>array('from' => 'info@ishop.com','sendAs' => 'html')*/
		//component to check authentication . this component file is exists in app/controllers/components

/*------------------------------Function of Setting Newsletter Subscribers to View -----------------*/

function subscribers(){

					$cond[] = "DiscountNewsletter.email!=''";
					
	    			$this->set('title_for_layout', 'Big Deal Newsletter Subscribers');
					
					$this->set('Categorys',$this->common->getAllCategory());
					
					$this->set('Countys',$this->common->getAllCounty());
					
					$this->paginate = array('limit' => PER_PAGE_RECORD, 'order' => array('DiscountNewsletter.id' => 'desc'));
					
					$this->set('search_text','Email');
					
					$this->set('category', 'Category');
					
					$this->set('county', 'County');
					/*if(isset($this->data)) {
						pr($this->data);
						exit;
					}*/
					
					 /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
			 
		//if county is set
		if((isset($this->data['discount_newsletters']['county']) and $this->data['discount_newsletters']['county'] != '')|| ( isset($this->params['named']['county']) and $this->params['named']['county'] !='')){
			if((isset($this->data['discount_newsletters']['county']) and $this->data['discount_newsletters']['county'] != ''))
			{
			 $county = $this->data['discount_newsletters']['county'] ;
			}
			else if( isset($this->params['named']['county']) and $this->params['named']['county'] !=''){
			 $county = $this->params['named']['county'] ;
			}else{
			  
			  $county ="";
			}
			
			$this->set('county',$county); 
		}
		
		//if category is set
		if((isset($this->data['discount_newsletters']['category']) and $this->data['discount_newsletters']['category'] != 0)|| ( isset($this->params['named']['category']) and $this->params['named']['category'] !='')){
		
		
		
			if((isset($this->data['discount_newsletters']['category']) and $this->data['discount_newsletters']['category'] != 0))
			{
			 $category = $this->data['discount_newsletters']['category'] ;
			}
            else if( (isset($this->params['named']['category'])) and $this->params['named']['category'] !=''){
             $category = $this->params['named']['category'] ;
			}else{
			$category = '';
			}
			$this->set('category',$category); 
		}
		
		//if title is set
		if((isset($this->data['discount_newsletters']['search_text']) and ($this->data['discount_newsletters']['search_text'] != '' and $this->data['discount_newsletters']['search_text'] != 'Email'))|| ( isset($this->params['named']['search_text']) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Email') )){
		
			if((isset($this->data['discount_newsletters']['search_text']) and ($this->data['discount_newsletters']['search_text'] != '' and $this->data['discount_newsletters']['search_text'] != 'Email')))
			{
			 $search_text = $this->data['discount_newsletters']['search_text'] ;
			}
			else if( (isset($this->params['named']['search_text'])) and ($this->params['named']['search_text'] !='' and $this->params['named']['search_text'] !='Email')){
			  $search_text =  $this->params['named']['search_text'] ;
			}else{
               $search_text ='';
			}
			$this->set('search_text',$search_text);
		}
			 /*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/
			 
		if(isset($county) && $county !=''){
		 	$cond['DiscountNewsletter.county_id'] = $county;
		}
		
		if(isset($category) && $category !=''){
		 	$cond[] = "DiscountNewsletter.category_id LIKE '%,".$category.",%'";
		}
		
		if(isset($search_text) && $search_text !=''){
			$cond['DiscountNewsletter.email LIKE'] = '%'.$search_text. '%';
		}
			$cond['DiscountNewsletter.status'] = 'yes';
		
		/*----------------------------------It sets data to view by specified condition--------------------------------------------------------*/
				$data = $this->paginate('DiscountNewsletter', $cond);
				//pr($data);
		    	$this->set('subscribers', $data);
	}
/*--------------------------------------------View Newsletter---------------------------------------*/
function viewNewsletter($id=NULL) {
	$this->set('content',$this->returnNewsletter($id));
	$this->set('title_for_layout','Big Deal Newsletters');
	//pr($this->DiscountNewsletter->read(null,$id));
	$dis_email_arr=$this->DiscountNewsletter->read(null,$id);
	$this->set('dis_email',$dis_email_arr['DiscountNewsletter']['email']);
	$this->set('id',$id);
}
/*--------------------------------------------View Newsletter---------------------------------------*/
function returnCounty($id=NULL) {
	$data = $this->DiscountNewsletter->find('first',array('fields'=>array('DiscountNewsletter.county_id'),'conditions'=>array('DiscountNewsletter.id'=>$id)));
	return $data['DiscountNewsletter']['county_id'];
}
/*--------------------------------------------View Newsletter---------------------------------------*/
function returnNewsletter($id=NULL,$unique=NULL) {
	$content = '';
	$data = $this->DiscountNewsletter->find('first',array('conditions'=>array('DiscountNewsletter.id'=>$id)));
	if($data['DiscountNewsletter']['all_cats']) {
		$cats = $this->common->getAllParentCats();
	} else {
		$cats = array_values(array_filter(explode(',',$data['DiscountNewsletter']['category_id'])));
	}	
	$county = $data['DiscountNewsletter']['county_id'];
	foreach($cats as $cats) {
	$discounts = $this->common->todayDiscount($cats,$county);
	if(!empty($discounts)) {
	$content .= '<tr>
                <td width="550" align="center" valign="middle" style="background:#202020;padding:10px 0" ><table width="550" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="center" valign="middle" style="font:22px Georgia, \'Times New Roman\', Times, serif; color:#ffffff; border:1px dashed #6e6e6e; text-transform:uppercase; font-weight:bold; padding:10px 0;">Today\'s ';
		$content .= $this->common->getCategoryName($cats);
		$content .= ' Big Deals</td>
                    </tr>
                  </table></td>
              </tr>';
			$o = 0;
			$tracking_string = '';
			if($unique) {
				$tracking_string = '?'.$unique.'?'.base64_encode($id);
			}
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
	if($content!='') {
		$content .= '<img src="'.FULL_BASE_URL.router::url('/',false).'emaillogs/saveEmailOpen?unique='.$unique.'?'.base64_encode($id).'" style="display:none;width:0" />';
	}
	return $content;
}
/*-------------------------------Function of Deleting Newsletter from database----------------------*/	
	function deleteSubscriber($id=null){

					/*Delete the subscriber with specified Id value*/
						
					$this->DiscountNewsletter->delete($id);
					
					$this->Session->setFlash('The Subscriber with id:  '.$id.' has been Deleted Successfully!!');
					
					$this->redirect(array('action'=>'subscribers'));

	}
/*-------------------------------Function of Deleting Newsletter from database----------------------*/		
function test_email2() {
			$this->autoRender = false;
			if(isset($this->data)) {
					$this->loadModel('Emaillog');
					$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
					$check_same = $this->common->check_same_date($today);
					if(!empty($check_same)) {
						$unique_string = $check_same['Emaillog']['unique'];
					} else {
						$unique_string = $this->common->randomPassword(10);
					}
					$id = $this->data['discount_newsletters']['newsletter_user_id'];
					$content = $this->returnNewsletter($id,$unique_string);
					if($content!='') {
						$this->Email->sendAs = 'html';
						$this->Email->to = $this->data['discount_newsletters']['email'];
						$this->Email->subject = 'Check out Today\'s Big Deal from Zuni!';
						$this->Email->replyTo = $this->common->getReturnEmail();
						$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
						$this->body = '';
						$this->body .= $this->emailhtml->discount_header($this->returnCounty($id));
						$this->body .= $content;
						$this->body .= $this->emailhtml->discount_footer($this->returnCounty($id));
						$this->Email->smtpOptions = array(
							'port'=>'25',
							'timeout'=>'30',
							'host' =>SMTP_HOST_NAME,
							'username'=>SMTP_USERNAME,
							'password'=>SMTP_PASSWORD
						);
						$this->Email->delivery = 'smtp';
						if($this->Email->send($this->body)) {
							$sent = 1;
							$this->Session->setFlash('Email has been sent successfully.');
						} else {
							$sent = 0;
							$this->Session->setFlash('Email not sent.');
						}						
						$avail_discount = 1;
					} else {
						$sent = 0;
						$avail_discount = 0;
					}
					//----------------------------------Save Email Log----------------------------------------//
										$save = '';
										if(isset($check_same['Emaillog']['id'])) {
											$save['Emaillog']['id'] = $check_same['Emaillog']['id'];
											if($sent) {
												$save['Emaillog']['sent'] = $check_same['Emaillog']['sent'].','.$id.',';
											} else {
												$save['Emaillog']['notsent'] = $check_same['Emaillog']['notsent'].','.$id.',';
											}
											$save['Emaillog']['opened'] = str_replace(','.$id.',','',$check_same['Emaillog']['opened']);
											$save['Emaillog']['email_opened'] = str_replace(','.$id.',','',$check_same['Emaillog']['email_opened']);
										} else {
											$save['Emaillog']['sending_date'] = $today;
											if($sent) {
												$save['Emaillog']['sent'] =','.$id.',';
												$save['Emaillog']['notsent'] = ',';
											} else {
												$save['Emaillog']['sent'] =',';
												$save['Emaillog']['notsent'] = ','.$id.',';
											}
											$save['Emaillog']['opened'] = '';
											$save['Emaillog']['email_opened'] = '';
										}	
											$save['Emaillog']['unique']=$unique_string;
											$this->Emaillog->save($save,false);
					//----------------------------------Save Email Log----------------------------------------//
					if($avail_discount==0) {
						$this->Session->setFlash('No Big Deal available so email not sent.');
						$this->redirect(FULL_BASE_URL.router::url('/',false).'discount_newsletters/viewNewsletter/'.$id.'/type:error');
						exit;
					}
				}
				if($sent==0) {
					$this->redirect(FULL_BASE_URL.router::url('/',false).'discount_newsletters/viewNewsletter/'.$id.'/type:error');
				} else {
					$this->redirect(array('action'=>'viewNewsletter',$id));
				}
}
/*-------------------------------Function of Deleting Newsletter from database----------------------*/		
function test_email() {
			$this->autoRender = false;
			if(isset($this->data)) {
					$id = $this->data['discount_newsletters']['newsletter_user_id'];
					$content = $this->returnNewsletter($id);
					if($content!='') {
						$this->Email->sendAs = 'html';
						$this->Email->to = $this->data['discount_newsletters']['email'];
						$this->Email->subject = 'Check out Today\'s Big Deal from Zuni!';
						$this->Email->replyTo = $this->common->getReturnEmail();
						$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
						$this->body = '';
						$this->body .= $this->emailhtml->discount_header($this->returnCounty($id));
						$this->body .= $content;
						$this->body .= $this->emailhtml->discount_footer($this->returnCounty($id));
						$this->Email->smtpOptions = array(
							'port'=>'25',
							'timeout'=>'30',
							'host' =>SMTP_HOST_NAME,
							'username'=>SMTP_USERNAME,
							'password'=>SMTP_PASSWORD
						);
						$this->Email->delivery = 'smtp';
						$this->Email->send($this->body);
						$this->Session->setFlash('Email has been sent successfully.');
					} else {
						$this->Session->setFlash('No Big Deal available so email not sent.');
						$this->redirect($this->referer().'/type:error');
						exit;
					}
				}
				$this->redirect(array('action'=>'viewNewsletter',$id));
}
/*-------------------------------Function of Deleting Newsletter from database----------------------*/		
function bulk_email() {
			$this->autoRender = false;
			set_time_limit(0);
			$users = $this->DiscountNewsletter->find('all',array('fields'=>array('id,email'),'conditions'=>array('status'=>'yes')));
			$email_ids = array_chunk($users, EMAIL_LIST);
			$succedd = '';
			$failed = '';
			$unique_string = $this->common->randomPassword(10);
			foreach($email_ids as $email_id) {
					foreach($email_id as $email) {
							$id = $email['DiscountNewsletter']['id'];
							$content = $this->returnNewsletter($id,$unique_string);
							if($content!='') {
								$this->Email->sendAs = 'html';
								$this->Email->to = $email['DiscountNewsletter']['email'];
								$this->Email->subject = 'Check out Today\'s Big Deal from Zuni!';
								$this->Email->replyTo = $this->common->getReturnEmail();
								$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
								$this->body = '';
								$this->body .= $this->emailhtml->discount_header($this->returnCounty($id));
								$this->body .= $content;
								$this->body .= $this->emailhtml->discount_footer($this->returnCounty($id));
								$this->Email->smtpOptions = array(
									'port'=>'25',
									'timeout'=>'30',
									'host' =>SMTP_HOST_NAME,
									'username'=>SMTP_USERNAME,
									'password'=>SMTP_PASSWORD
								);
								$this->Email->delivery = 'smtp';
								if($this->Email->send($this->body)) {
									$succedd[]= $id;
								} else {
									$failed[]= $id;
								}
								$this->Email->reset();
							} else {
								$failed[]= $id;
							}	
					}
					//sleep(2);
			}
	//----------------------------------Save Email Log----------------------------------------//		
			$this->loadModel('Emaillog');
			$sent = ',';
			$notsent = ',';
			if(is_array($succedd)) {
				$sent = ','.implode(',',$succedd).',';
			}
			if(is_array($failed)) {
				$notsent = ','.implode(',',$failed).',';
			}
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$check_same = $this->common->check_same_date($today);
			
			$save = '';
			if(isset($check_same['Emaillog']['id'])) {
				$save['Emaillog']['id'] = $check_same['Emaillog']['id'];
			}
				$save['Emaillog']['sending_date'] = $today;
				$save['Emaillog']['sent'] = $sent;
				$save['Emaillog']['notsent'] = $notsent;
				$save['Emaillog']['opened'] = '';
				$save['Emaillog']['email_opened'] = '';
				$save['Emaillog']['unique']=$unique_string;
			
			$this->Emaillog->save($save);
		//----------------------------------Save Email Log----------------------------------------//		
			
			$this->Session->setFlash('Manual Emails have been sent successfully.');
			$this->redirect(array('controller'=>'discount_newsletters','action'=>'subscribers'));
}
/*--------------this function is checking username and pasword in database and if true then redirect to home page--------------*/
function enableauto() {
	$this->loadModel('Setting');
	$save  = '';
	$save['Setting']['id'] = 1;
	$save['Setting']['discount_email'] = 1;
	$this->Setting->save($save);
	$this->Session->setFlash('Automatic email service has been enabled successfully.');
	$this->redirect(array('controller'=>'discount_newsletters','action'=>'subscribers'));
}
/*--------------this function is checking username and pasword in database and if true then redirect to home page--------------*/
function disableauto() {
	$this->loadModel('Setting');
	$save  = '';
	$save['Setting']['id'] = 1;
	$save['Setting']['discount_email'] = 0;
	$this->Setting->save($save);
	$this->Session->setFlash('Automatic email service has been disabled successfully.');
	$this->redirect(array('controller'=>'discount_newsletters','action'=>'subscribers'));
}
/*-------------------------------Function of Deleting Newsletter from database----------------------*/		
function auto_bulk_email() {
			$this->autoRender = false;
			if($this->common->discount_email()) {
			set_time_limit(0);
			$users = $this->DiscountNewsletter->find('all',array('fields'=>array('id,email'),'conditions'=>array('status'=>'yes')));
			$email_ids = array_chunk($users, EMAIL_LIST);
			$succedd = '';
			$failed = '';
			$unique_string = $this->common->randomPassword(10);
			foreach($email_ids as $email_id) {
					foreach($email_id as $email) {
							$id = $email['DiscountNewsletter']['id'];
							$content = $this->returnNewsletter($id,$unique_string);
							if($content!='') {
								$this->Email->sendAs = 'html';
								$this->Email->to = $email['DiscountNewsletter']['email'];
								$this->Email->subject = 'Check out Today\'s Big Deal from Zuni!';
								$this->Email->replyTo = $this->common->getReturnEmail();
								$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
								$this->body = '';
								$this->body .= $this->cronhtml->discount_header($this->returnCounty($id));
								$this->body .= $content;
								$this->body .= $this->cronhtml->discount_footer($this->returnCounty($id));
								$this->Email->smtpOptions = array(
									'port'=>'25',
									'timeout'=>'30',
									'host' =>SMTP_HOST_NAME,
									'username'=>SMTP_USERNAME,
									'password'=>SMTP_PASSWORD
								);
								$this->Email->delivery = 'smtp';
								if($this->Email->send($this->body)) {
									$succedd[]= $id;
								} else {
									$failed[]= $id;
								}
								$this->Email->reset();
							} else {
								$failed[]= $id;
							}
					}
					//sleep(2);
			}
			//----------------------------------Save Email Log----------------------------------------//		
			$this->loadModel('Emaillog');
			$sent = ',';
			$notsent = ',';
			if(is_array($succedd)) {
				$sent = ','.implode(',',$succedd).',';
			}
			if(is_array($failed)) {
				$notsent = ','.implode(',',$failed).',';
			}
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$check_same = $this->common->check_same_date($today);
			
			$save = '';
			if(isset($check_same['Emaillog']['id'])) {
				$save['Emaillog']['id'] = $check_same['Emaillog']['id'];
			}
				$save['Emaillog']['sending_date'] = $today;
				$save['Emaillog']['sent'] = $sent;
				$save['Emaillog']['notsent'] = $notsent;
				$save['Emaillog']['opened'] = '';
				$save['Emaillog']['email_opened'] = '';
				$save['Emaillog']['unique']=$unique_string;
			
			$this->Emaillog->save($save);
		//----------------------------------Save Email Log----------------------------------------//
		}	
}
/*-------------------------------Function of Deleting Newsletter from database----------------------*/		
function hello_email() {
			$this->autoRender = false;
							$this->Email->sendAs = 'html';
							$this->Email->to = 'keshav@planetwebsolution.com';
							$this->Email->subject = 'Check out Today\'s Big Deal from Zuni!';
							$this->Email->replyTo = $this->common->getReturnEmail();
							$this->Email->from = $this->common->getFromName().' <'.$this->common->getSalesEmail().'>';
							$this->body = 'hello, fgh df h dfh df h df h';
							$this->Email->smtpOptions = array(
								'port'=>'25',
								'timeout'=>'30',
								'host' =>SMTP_HOST_NAME,
								'username'=>SMTP_USERNAME,
								'password'=>SMTP_PASSWORD
							);
							$this->Email->delivery = 'smtp';
							$this->Email->send($this->body);
}
/************************************ Function to confirm to delete advertiser profile *********************************************/	
	function intermediate() {}
/*--------------this function is checking username and pasword in database and if true then redirect to home page--------------*/
	function beforeFilter() {
            	 	$this->Auth->fields = array(
             			'username' => 'username', 
            	 		'password' => 'password'
           	 		);
					$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
					$this->Auth->allow('unsubscribe','auto_bulk_email','hello_email');
   	    }
/*----------This function is setting all info about current SuperAdmins in currentAdmin array so we can use it anywhere lie name id etc.---------*/
function beforeRender(){

		    $this->set('currentAdmin', $this->Auth->user());
			$this->set('cssName',$this->Cookie->read('css_name'));
			$this->set('groupDetail',$this->common->adminDetails());
			$this->set('common',$this->common);
			//$this->Ssl->force();
	  } 
}//end class