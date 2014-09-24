<?php

class autoofferhtmlComponent extends Object {
	var $components = array('common');
	
	
	
	
	
	
	function email_header() {
	
		$newsletter_url = 'https://zuni.com/img/offer_email/';
		$site_url = 'https://zuni.com/';
		$header = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="background-color:#2f2f2f; padding-top:24px;"><table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center" valign="top"><table width="520" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td align="left" valign="top" width="289"><a href="'.$site_url.'" target="_blank"><img src="'.$newsletter_url.'logo.png" width="289" height="115" alt=" " style="display:block; border:0" border="0" /></a></td>
                <td align="right" valign="top" style="padding-top:27px;"><table width="143" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top"><a href="https://www.facebook.com/pages/Zuni/442987622419803" title="Click to share on facebook" target="_blank"><img src="'.$newsletter_url.'facebook-icon.png" width="48" height="51" alt="facebook" style="display:block; border:0" border="0" /></a></td>
                      <td align="left" valign="top"><a title="Click to tweet on tweeter" href="https://twitter.com" target="_blank"><img src="'.$newsletter_url.'twitter-icon.jpg" width="48" height="51" alt="twitter" style="display:block; border:0" border="0" /></a></td>
                      <td align="left" valign="top"><a title="Click to pinning on pinterest" href="http://pinterest.com/zunidiscounts/" target="_blank"><img src="'.$newsletter_url.'pinterest-icon.jpg" width="47" height="51" alt="pinterest" style="display:block; border:0" border="0" /></a></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>';
		return $header;
	}
	
	
	function email_box() {
		$newsletter_url = 'https://zuni.com/img/offer_email/';
		$site_url = 'https://zuni.com/';
		$box = '<tr>
          <td align="left" valign="top"><table width="600" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top"><img src="'.$newsletter_url.'top-crv1.jpg" width="600" height="40" alt=" " style="display:block; border:0" border="0" /></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top"><table width="540" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                            <td bgcolor="#B0000B" style="background-color:#B0000B; text-align:center; font-family:arial; color:#ffffff; font-size:32px; font-weight:bold;"> '.$this->common->getOfferSubjectFromSetting().' </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top"><img src="'.$newsletter_url.'bottom-crv1.jpg" width="600" height="39" alt=" " style="display:block; border:0" border="0" /></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="left" valign="top"><table width="600" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top" width="40"><img src="'.$newsletter_url.'left-crv.jpg" width="40" height="91" alt=" " style="display:block; border:0" border="0" /></td>
                      <td align="left" valign="top"><table width="520" border="0" cellspacing="0" cellpadding="0">
                      
                      
                          <tr>
                            <td align="center" valign="top" style="background-color:#cbc9c9;" bgcolor="#cbc9c9"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                  <td align="center" valign="top" style="padding-bottom:10px;"><table width="480" border="0" align="center" cellpadding="0" cellspacing="0">
                                      <tr>
                                        <td align="left" valign="top"><span style="font-family:arial; font-size:20px; font-weight:bold; color:#000000;">'.str_replace('[zuni_url]','https://zuni.com',$this->common->getOfferContentsFromSetting()).'</td>
                                      </tr>
                                    </table></td>
                                </tr>
								<tr>
                                  <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td align="left" valign="top"><img src="'.$newsletter_url.'top-crv2.jpg" width="520" height="53" alt=" " style="display:block; border:0" border="0" /></td>
                                      </tr>
                                      <tr>
                                        <td align="center" valign="top"><table width="480" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                              <td align="center" valign="top" bgcolor="#ffffffff" style="background-color:#ffffff;">
                                              
                                              <table width="440" border="0" align="center" cellpadding="0" cellspacing="0">';
		return $box;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	function email_content($advertiser,$tracking_string='') {
		$newsletter_url = 'https://zuni.com/img/offer_email/';
		$site_url = 'https://zuni.com/';
		$header = '';
		
		foreach($advertiser as $key=>$value) {
			
			$advertiser_logo = $site_url.'img/offer/soffers/'.$this->common->getAdvertiserOfferImagebyId($value);
			$advertiser_name = $this->common->getCompanyName($value);
			$advertiser_address = $this->common->advertiserBothAddress($value);
			$offers = $this->common->getotherSavingOfferImg_front($value);
			$county = $this->common->getCountyUrl($this->common->getCompanyCounty($value));
			$state = $this->common->getStateUrls($this->common->getCompanystate($value));
			$companyurl = $this->common->getcompanyurl($value);
			
			$companyUrl = $site_url.'state/'.$state.'/'.$county.'/business/coupon/'.$companyurl.''.$tracking_string;
			
		$header .= '
  
  
  <tr>
    <td align="left" valign="top" style=" border-bottom:3px solid #cccccc; padding-bottom:15px; padding-top:15px;"><table width="440" border="0" align="center" cellpadding="0" cellspacing="0">
                                                  <tr>
                                                    <td align="center" valign="middle" style="border:1px solid #e1e1e1; padding-top:12px; padding-right:12px; padding-bottom:12px; padding-left:12px;"><a href="'.$companyUrl.'" target="_blank" style="cursor:pointer;"><img src="'.$advertiser_logo.'" width="398" height="316" alt="'.$advertiser_name.'" style="display:block; border:0" border="0" /></a></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="center" valign="top" style="background:#f7f7f7; border-bottom:1px solid #e1e1e1; border-right:1px solid #e1e1e1; border-left:1px solid #e1e1e1; padding-top:20px; padding-bottom:20px; padding-left:20px; padding-right:20px; font-family:arial; color:#000000; font-size:14px; font-weight:bold;" bgcolor="#f7f7f7">'.$advertiser_address.'</td>
                                                  </tr>
                                                  <tr>
                                                    <td align="left" valign="top" style="padding-top:"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
							
							
							
							
							$total = count($offers);
							
							
								for($i=0;$i<$total;$i++) {
								if($i==0 || $i==2 || $i==4) {
										$header .= ' <tr><td align="left" valign="top" style="padding-top:15px;"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
									}
								if(isset($offers[$i]))  {
								
									$off = '';
									$offer = '';
									$istext = 0;
									if($offers[$i]['SavingOffer']['off_unit']==2) {
										$off .= $offers[$i]['SavingOffer']['off_text'];
										$istext = 1;
									} else {
										if($offers[$i]['SavingOffer']['off_unit']==1) {
											$off .= '$';
										}
										$off .= $offers[$i]['SavingOffer']['off'];
										
										if($offers[$i]['SavingOffer']['off_unit']==0) {
											$off .= '%';
										}
										$offer = 'OFF';
									}
									
									
							$header .= '<td align="center" valign="top"><a href="'.$companyUrl.'" target="_blank" style="cursor:pointer;text-decoration:none;"><table width="200" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td align="left" valign="top"><img src="'.$newsletter_url.'box-top.jpg" width="200" height="9" alt=" " style="display:block; border:0" border="0" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="center" valign="top" bgcolor="#181818" style="background-color:#181818;"><table width="180" border="0" align="center" cellpadding="0" cellspacing="0">
                                                                          <tr>
                                                                            <td align="left" valign="top" style="font-family:arial; color:#ffffff; font-size:18px; font-weight:bold; text-transform:uppercase;"><a href="'.$companyUrl.'" target="_blank" style="font-family:arial; color:#ffffff; font-size:18px; font-weight:bold; text-decoration:none;">'.$offers[$i]['SavingOffer']['title'].'</a></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td align="left" valign="top" style="padding-top:6px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tr style="height:65px;">';
																				
																				
							
					if($istext) {
							$header .= '<td align="left" valign="top" style="font-family:arial; color:#ffb826; font-size:42px; width:auto; font-weight:bold;"><a href="'.$companyUrl.'" target="_blank" style="cursor:pointer;text-decoration:none;color:#ffb826;">'.$off.'</a></td>';
						} else {
						 	$header .= '<td width="58" align="left" valign="top" style="font-family:arial; color:#ffb826; font-size:42px; width:auto; font-weight:bold;"><a href="'.$companyUrl.'" target="_blank" style="cursor:pointer;text-decoration:none;color:#ffb826;">'.$off.'</a></td>
                             <td width="101" align="left" valign="top" style="font-family:arial; color:#ffb826; font-size:25px; font-weight:bold;"><a href="'.$companyUrl.'" target="_blank" style="cursor:pointer;text-decoration:none;color:#ffb826;">'.$offer.'</a></td>';
						}
						
																			
                            
                   
				   
				   
				   $header .= '<td align="right" valign="bottom" width="21"><a href="'.$companyUrl.'" target="_blank"  style="text-decoration:none;"><img src="'.$newsletter_url.'arrow.jpg" width="21" height="21" alt=" " style="display:block; border:0" border="0" /></a></td>
                                                                                </tr>
                                                                              </table></td>
                                                                          </tr>
                                                                        </table></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left" valign="top"><img src="'.$newsletter_url.'box-bottom.jpg" width="200" height="9" alt=" "  style="display:block; border:0" border="0" /></td>
                                                                    </tr>
                                                                  </table></a></td>';
								} else {
									$header .= '<td align="center" valign="top">&nbsp;</td>';
								}
								if($i==1 || $i==3) {
										$header .= '</tr></table></td></tr>';
								}
							}
								
								
								
								if($total%2==1) {
									$header .= '<td align="center" valign="top">&nbsp;</td></tr></table></td></tr>';
								}
								
										  
                             $header .= '</table></td>
                                                  </tr>
                                                </table></td></tr>';
		}
		return $header;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function email_footer() {
		$newsletter_url = 'https://zuni.com/img/offer_email/';
		$site_url = 'https://zuni.com/';
		$footer = '</table>

                                              </td>
                                            </tr>
                                            
                                          </table></td>
                                      </tr>
                                    </table></td>
                                </tr></table></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top"><img src="'.$newsletter_url.'bottom-crv.jpg" width="520" height="39" alt=" " style="display:block; border:0" border="0"  /></td>
                          </tr>
                        </table></td>
                      <td align="right" valign="top" width="40"><img src="'.$newsletter_url.'right-crv.jpg" width="40" height="73" alt=" " style="display:block; border:0" border="0" /></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
		<tr>
          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" valign="top" style="padding-top:15px; padding-bottom:15px;"><table width="424" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                      <td align="center" valign="top"><table width="131" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#cccccc; font-weight:bold; font-size:14px; border-bottom:1px solid #3f3f3f; padding-bottom:6px;">Zuni</td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Advertiser Login</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'contact" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Advertise w/Us</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'userPage" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Refer A Business</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'userPage" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Consumer Login</a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top"><table width="131" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#cccccc; font-weight:bold; font-size:14px; border-bottom:1px solid #3f3f3f; padding-bottom:6px;">About Zuni.Com</td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'pages/page/about-us" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">About Us</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'careers" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Careers</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'pages/page/zuni-cares" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Zuni Cares</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'pages/page/local-zuni-news" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Local Zuni News</a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top"><table width="131" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#cccccc; font-weight:bold; font-size:14px; border-bottom:1px solid #3f3f3f; padding-bottom:6px;">Support</td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'pages/page/terms" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Terms of Use</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'pages/page/privacy-policy" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Privacy</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'contact" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Contact Us</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="padding-top:6px; padding-bottom:6px;"><a href="'.$site_url.'pages/page/faq" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Help / FAQ</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="center" valign="top" style="border-top:1px solid #414141; font-family:Arial; color:#fff; font-size:12px; text-align:center; padding-top:14px; padding-bottom:20px;"> Copyright &copy; '.date('Y').' Zuni. All rights reserved </td>
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