<?php

class merchanthtmlComponent extends Object {
	var $components = array('common','Session','Cookie');
	
	function email_header() {
		$newsletter_url = FULL_BASE_URL.router::url('/',false).'img/newsletter_pics/';
		$site_url = FULL_BASE_URL.router::url('/',false);
		$header = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="background-color:#2f2f2f;" bgcolor="#2f2f2f" align="center" valign="top"><table width="650" border="0" cellspacing="0" cellpadding="0" align="center" style="background-color: #2f2f2f;" bgcolor="#2f2f2f">
        <tr>
          <td align="left" valign="top" style="padding-top:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top" width="247"><a href="'.$site_url.'"><img src="'.$newsletter_url.'logo.jpg" width="247" height="101" alt=" " style="display:block; border:0;" border="0" /></a></td>
                <td align="right" valign="top" style="padding-top:20px; padding-right:20px; background-color:#2f2f2f;"><table width="163" border="0" cellspacing="0" cellpadding="0" align="right">
                    <tr>
                      <td align="center" valign="top"><a href="https://www.facebook.com/pages/Zuni/442987622419803" title="Click to share on facebook" target="_blank"><img src="'.$newsletter_url.'facebook.jpg" width="55" height="48" alt="facebook" border="0" style="display:block; border:0" /></a></td>
                      <td align="center" valign="top"><a title="Click to tweet on tweeter" href="https://twitter.com" target="_blank"><img src="'.$newsletter_url.'twitter.jpg" width="56" height="48" alt="twitter" border="0" style="display:block; border:0" /></a></td>
                      <td align="center" valign="top"><a title="Click to pinning on pinterest" href="http://pinterest.com/zunidiscounts/" target="_blank"><img src="'.$newsletter_url.'pinterest.jpg" width="52" height="48" alt="pinterest" border="0" style="display:block; border:0;" /></a></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>';
		
		
		return $header;
	}
	
	
	function email_box($advertiser,$offer) {
		$newsletter_url = FULL_BASE_URL.router::url('/',false).'img/newsletter_pics/';
		$site_url = FULL_BASE_URL.router::url('/',false);
		$offer_data = $this->common->getSavingOfferById($offer);
		$off = ''; 
		

			if($offer_data[0]['SavingOffer']['off_unit']==2) { 
				$off .= $offer_data[0]['SavingOffer']['off_text'];
			} else {
				if($offer_data[0]['SavingOffer']['off_unit']==1) {
					$off .='$';
				}
				$off .= $offer_data[0]['SavingOffer']['off'];
				if($offer_data[0]['SavingOffer']['off_unit']==0) {
					$off .='%';
				}
				$off .=' OFF';
			}
            $image = $this->common->getAdvertiserOfferImagebyId($advertiser);
			
		$box = '<tr>
          <td align="left" valign="top" style="background:#b1000c;" bgcolor="#b1000c"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="281" align="left" valign="top"><img src="'.$newsletter_url.'free-saving.jpg" width="281" height="310" alt="free saving" border="0" style="border:0; display:block;" /></td>
                <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top"><img src="'.$newsletter_url.'top-crv.jpg" width="369" height="43" alt=" " border="0" style="display:block; border:0;" /></td>
                    </tr>
                    <tr>
                      <td height="225" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" valign="top" bgcolor="#eeedf2" style="background-color:#eeedf2; padding-top:8px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="center" valign="top" style="padding-right:8px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td align="center" valign="top" style="font-family:arial; color:#2f2f2f; font-size:16px; text-transform:uppercase; font-weight:bold; padding-top:10px;">'.$this->common->getCompanyNameById($advertiser).'</td>
                                      </tr>
                                      <tr>
                                        <td align="center" valign="top" style="font-size:36px; font-family:arial; color:#ff0011; font-weight:bold; text-transform:uppercase; padding-top:6px;">'.$off.'</td>
                                      </tr>
                                      <tr>
                                        <td align="center" valign="top" style="font-family:arial; color:#2f2f2f; font-size:18px; font-weight:bold;">'.$offer_data[0]['SavingOffer']['title'].'</td>
                                      </tr>
                                      <tr>
                                        <td align="center" valign="top" style="padding-top:12px;"><a href="'.$site_url.'merchants/'.$this->common->getcompanyurl($advertiser).'" target="_blank"><img src="'.$newsletter_url.'view-button2.jpg" width="62" height="32" alt="view" border="0" style="display:block; border:0; margin:0 auto;" /></a></td>
                                      </tr>
                                    </table></td>
                                  <td width="173" align="left" valign="top"><img src="'.$site_url.'img/offer/soffers/'.$image.'" width="161" height="180" alt=" " style="display:block; border:5px solid #ffffff;" /></td>
                                </tr>
                              </table></td>
                            <td width="33" align="left" valign="top"><img src="'.$newsletter_url.'right-crv.jpg" width="33" height="225" alt=" " border="0" style="display:block; border:0;" /></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top"><img src="'.$newsletter_url.'bottom-crv.jpg" width="369" height="42" alt=" " border="0" style="border:0; display:block;" /></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td align="left" valign="top" bgcolor="#ffffff" style="background-color:#ffffff; padding-top:20px; padding-bottom:20px; padding-left:20px; padding-right:20px; font-family:arial; color:#2f2f2f; font-weight:bold; font-size:20px;"> Check out these other great deals at Zuni.com </td>
        </tr>
        <tr>
          <td align="left" valign="top" bgcolor="#ffffff" style="background-color:#ffffff; padding-bottom:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="210" align="left" valign="top" style="padding-left:20px;"><table width="191" border="0" align="left" cellpadding="0" cellspacing="0">';
				return $box;
	}
				
				
	
	
	
	function category($county) {
		$newsletter_url = FULL_BASE_URL.router::url('/',false).'img/newsletter_pics/';
		$site_url = FULL_BASE_URL.router::url('/',false);		
		
        App::import('model','CountyCategory');
		$this->CountyCategory = new CountyCategory();
		
		App::import('model','AdvertiserCategory');
		$this->AdvertiserCategory = new AdvertiserCategory();
		
		$data = $this->CountyCategory->find('all',array('fields'=>array('DISTINCT Category.id','Category.categoryname'),'conditions'=>array('CountyCategory.county_id'=>$county,'Category.publish'=>'yes'),'order'=>array('Category.order,Category.categoryname')));
		
        
		$color = array('#ffc200','#e3000e','#5c8200','#0096ff','#26a000','#793c77','#aeac00','#ffa200','#754c24','#1cbbb4','#00aeef','#39b54a');
		$box = '';
		$i=0;
		if(!empty($data)) {
		foreach($data as $data) {
		$box .='<tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="left" valign="top"><img src="'.$newsletter_url.'top'.($i+1).'.jpg" width="191" height="5" alt=" " border="0" style="border:0; display:block;" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="top" bgcolor="'.$color[$i].'" style="background-color:'.$color[$i].'; font-family:arial; color:#ffffff; text-transform:uppercase; font-size:18px; padding-left:10px; padding-bottom:6px;"> '.$data['Category']['categoryname'].'</td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" bgcolor="#f6f6f6" style="background-color:#f6f6f6; padding-top:3px; padding-left:10px; padding-bottom:10px; padding-left:10px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
                                
							$adv = $this->AdvertiserCategory->find('all',array('fields'=>array('DISTINCT AdvertiserCategory.advertiser_profile_id','AdvertiserProfile.company_name','AdvertiserProfile.page_url'),'conditions'=>array('CategoriesSubcategory.category_id'=>$data['Category']['id'],'AdvertiserProfile.publish'=>'yes','AdvertiserProfile.county'=>$county)));
								
						foreach($adv as $adv) {
							$box .= '<tr>
                                  <td align="left" valign="middle" width="14" style="padding-top:7px; padding-bottom:3px;"><img src="'.$newsletter_url.'arrow-small.png" width="7" height="7" alt=" " /></td>
                                  <td align="left" valign="middle" style="font-family:arial; color:#464646; font-size:12px; padding-top:6px; padding-bottom:3px;"><a href="'.$site_url.'merchants/'.$adv['AdvertiserProfile']['page_url'].'" target="_blank" style="font-family:arial; color:#464646; font-size:12px; text-decoration:none;">'.$adv['AdvertiserProfile']['company_name'].'</a></td>
                                </tr>';
						}
								
                     $box .= '</table></td>
                          </tr>
                        </table></td>
                    </tr>';
					
			if($i>=11) {$i=0;} else {$i++;}
			}
		}
		
		$box .= '</table></td>
                <td align="right" valign="top" style="padding-right:20px;"><table width="400" border="0" cellspacing="0" cellpadding="0">';									  
		return $box;
	}
	
	
	
	function email_content($county) {
		$newsletter_url = FULL_BASE_URL.router::url('/',false).'img/newsletter_pics/';
		$site_url = FULL_BASE_URL.router::url('/',false);
		$content = '';
		$cats = $this->common->allCatWIthActiveSavingOffer($county);
		
		$color = array('#ffc200','#e3000e','#5c8200','#0096ff','#26a000','#793c77','#aeac00','#ffa200','#754c24','#1cbbb4','#00aeef','#39b54a');
		$i=0;
	//pr($cats);
	$total_cats = count($cats);
	foreach($cats as $cats) {
		$merchantLink = '';
		if($cats['saving_offers']['homecat']!='') {
		// Check if offset is set in cookie or need to initiate
		  	if($this->Cookie->read('CateOffset.'.$cats['categories']['page_url'])!='') {
				$offset = $this->Cookie->read('CateOffset.'.$cats['categories']['page_url']);
				if($offset >= ($cats[0]['Total']-1)) {
					$offset = 0;
				} else {
					$offset = $offset+1;
				}
				$this->Cookie->write('CateOffset.'.$cats['categories']['page_url'], $offset, true, 3600);
			} else {
				$this->Cookie->write('CateOffset.'.$cats['categories']['page_url'], 0, true, 3600);
				$offset = 0;
			}
		   	$offer = $this->common->getCurrentOffer($county,$cats['categories']['id'],$offset);
			$merchantLink = $site_url.'merchants/'.$this->common->getcompanyurl($offer['SavingOffer']['advertiser_profile_id']);
		}
		$align='left';
		//$top_img = 'top2.jpg';
		//$color = '#e3000e';
		$height = 229;
    if($i%2) {
		$align='right';
		//$top_img = 'top3.jpg';
		//$color = '#5c8200';
		$height = 228;
	}
		
	  if(isset($offer) && is_array($offer) && !empty($offer)){
		  $advLogo='';
		  if(isset($offer['AdvertiserProfile']['main_image']) && $offer['AdvertiserProfile']['main_image']!='') {
			$advLogo='img/logo/main_image/'.$offer['AdvertiserProfile']['main_image'];
		  }	else {
			$advLogo='img/logo/'.$offer['AdvertiserProfile']['logo'];
		  }
		  
			$off = ''; 
			if($offer['SavingOffer']['off_unit']==2) { 
				$off .= $offer['SavingOffer']['off_text'];
			} else {
				if($offer['SavingOffer']['off_unit']==1) {
					$off .='$';
				}
				$off .= $offer['SavingOffer']['off'];
				if($offer['SavingOffer']['off_unit']==0) {
					$off .='%';
				}
				$off .='OFF';
			}
		}
			
	if($i%2==0) {$content .='<tr>';}
	$style = '';
	if($i>1) {$style =' style="padding-top:20px;"';}
	
    $content .= '<td align="'.$align.'" valign="top"'.$style.'><table width="191" height="330" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top"><img src="'.$newsletter_url.'top'.($i+1).'.jpg" width="191" height="5" alt=" " border="0" style="border:0; display:block;" /></td>
          </tr>
          <tr>
            <td align="center" valign="top" bgcolor="'.$color[$i].'" style="background-color:'.$color.'; font-family:arial; color:#ffffff; text-transform:uppercase; font-size:19px; padding-bottom:7px; padding-top:4px;">'.$this->common->splitString($cats['categories']['categoryname'],14).'</td>
          </tr>
        </table></td>
    </tr>';
	
	if($cats['saving_offers']['homecat']!='') {	
	$content .= '<tr>
      <td align="left" valign="top" style="padding-top:2px;"><img src="'.$site_url.$advLogo.'" width="191" height="'.$height.'" alt=" " border="0" style="border:0; display:block;" /></td>
    </tr>
    <tr>
      <td align="left" valign="top" bgcolor="#1f1f1f" style="background-color:#1f1f1f; padding-top:10px; padding-left:10px; padding-bottom:10px; padding-right:10px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="58" align="left" valign="top" style="font-family:arial; color:#ffb826; font-size:13px; color:#ffb826;"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left" valign="top" style="font-family:arial; color:#ffb826; font-size:13px; color:#ffb826;">'.$offer['AdvertiserProfile']['company_name'].'</td>
                </tr>
                <tr>
                  <td align="left" valign="top" style="font-family:arial; color:#ffffff; font-size:12px; padding-top:3px;">'.$offer['SavingOffer']['title'].'</td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" valign="middle" style="font-family:arial; color:#ffffff; font-size:18px;">'.$off.'</td>
                  <td width="62" align="right" valign="middle"><a href="'.$merchantLink.'" target="_blank"><img src="'.$newsletter_url.'view-button.jpg" width="62" height="32" alt="view" border="0" style="border:0; display:block;" /></a></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>';
} else {
	if($this->Cookie->read('CateOffset.'.$cats['categories']['page_url'])) {
			$this->Cookie->delete('CateOffset.'.$cats['categories']['page_url']);
	}
}	
 $content .= '</table></td>';
     if($i%2==1) {$content .='</tr>';}
$i++;
}	
if($total_cats%2==1) {
	$content .='<td align="right" valign="top"></td></tr>';
}

		return $content;
	}
	
	
function email_footer() {
		$site_url = FULL_BASE_URL.router::url('/',false);
		$newsletter_url = FULL_BASE_URL.router::url('/',false).'img/newsletter_pics/';
		$footer = '</table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top:10px solid #b1000c; padding-top:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" valign="top" style="padding-bottom:15px;"><table width="506" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                      <td width="158" align="left" valign="top"><table width="140" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:13px; font-weight:bold; border-bottom:1px solid #3f3f3f; padding-bottom:6px; padding-left:10px;">Zuni</td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; border-bottom:1px dotted #3f3f3f; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Advertiser Login</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; border-bottom:1px dotted #3f3f3f; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'contact" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Advertise w/Us</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; border-bottom:1px dotted #3f3f3f; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'userPage" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Refer A Business</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'userPage" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Consumer Login</a></td>
                          </tr>
                        </table></td>
                      <td width="160" align="left" valign="top" style="padding-left:30px;"><table width="140" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:13px; font-weight:bold; border-bottom:1px solid #3f3f3f; padding-bottom:6px; padding-left:10px;">About Zuni.Com</td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; border-bottom:1px dotted #3f3f3f; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'pages/page/about-us" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">About Us</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; border-bottom:1px dotted #3f3f3f; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'careers" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Careers</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; border-bottom:1px dotted #3f3f3f; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'pages/page/zuni-cares" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Zuni Cares</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'pages/page/local-zuni-news" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Local Zuni News</a></td>
                          </tr>
                        </table></td>
                      <td align="right" valign="top"><table width="140" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:13px; font-weight:bold; border-bottom:1px solid #3f3f3f; padding-bottom:6px; padding-left:10px;">Support</td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; border-bottom:1px dotted #3f3f3f; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'pages/page/terms" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Terms of Use</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; border-bottom:1px dotted #3f3f3f; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'pages/page/privacy-policy" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Privacy</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; border-bottom:1px dotted #3f3f3f; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'contact" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Contact Us</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; padding-top:6px; padding-bottom:6px; padding-left:10px;"><a href="'.$site_url.'pages/page/faq" target="_blank" style="font-family:arial; color:#c1c1c1; font-size:11px; font-weight:bold; text-decoration:none;">Help / FAQ</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="center" valign="top" style="border-top:1px solid #414141; padding-top:14px; padding-bottom:14px; font-family:arial; color:#ffffff; font-size:12px;"> Copyright &copy; '.date('Y').' Zuni. All rights reserved </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>';
		return $footer;
	}
}
?>