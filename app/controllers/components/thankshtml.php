<?php

class thankshtmlComponent extends Object {
	var $components = array('common');
	
	function email_data($advertiser,$offer) {
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
            
	$data = '
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="background-color:#2f2f2f; padding-top:50px; padding-bottom:50px;" bgcolor="#2f2f2f" align="center" valign="top"><table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center" bgcolor="#FFFFFF" style="border-bottom:10px solid #b1000c;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" valign="top" style="padding-top:45px;"><table width="610" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top"><img src="'.$newsletter_url.'thanks-img1.jpg" width="276" height="210" alt=" " border="0" style="display:block; border:0" /></td>
                      <td align="left" valign="top"><img src="'.$newsletter_url.'thanks-img2.jpg" width="334" height="210" alt=" " border="0" style="display:block; border:0" /></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="center" valign="top" style="padding-top:65px;"><table width="610" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="center" valign="top" style="font-family:arial; color:#2f2f2f; font-size:18px;">Come by and Enjoy the savings offer below.<br />
                        (Psst&#8212;feel free to share this with your family &amp; friends too)</td>
                    </tr>
                    <tr>
                      <td align="center" valign="top" style="font-family:arial; color:#2f2f2f; font-size:26px; padding-top:6px; padding-bottom:18px;">See you soon!</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="24" align="left" valign="bottom"><img src="'.$newsletter_url.'left-shadow.jpg" width="24" height="334" alt=" " border="0" style="display:block; border:0" /></td>
                            <td align="left" valign="top" bgcolor="#431601" style="background-color:#431601; padding-top:18px; padding-right:18px; padding-bottom:4px; padding-left:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="center" valign="top" style="font-family:arial; color:#ffffff; font-size:25px; font-weight:bold;">'.$this->common->getCompanyNameById($advertiser).'</td>
                                </tr>
                                <tr>
                                  <td align="center" valign="top" style="font-family:arial; color:#ffffff; font-size:16px; padding-top:8px;">'.$this->common->compnyAddressForDeal($advertiser).'</td>
                                </tr>
                                <tr>
                                  <td align="center" valign="top" style="font-family:arial; color:#ffffff; font-size:25px; padding-top:8px;">'.$this->common->compnyPhoneForDeal($advertiser).'</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="top" style="padding-top:22px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="228" align="left" valign="top"><img src="'.$site_url.'img/offer/soffers/'.$image.'" width="184" height="190" alt=" " style="border:6px solid #ffffff;" /></td>
                                        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td align="center" valign="top" style="font-family:arial; color:#ff0011; font-size:40px; font-weight:bold;">'.$off.'</td>
                                            </tr>
                                            <tr>
                                              <td align="center" valign="top" style="font-family:arial; color:#ffffff; font-size:24px; font-weight:bold;">'.$offer_data[0]['SavingOffer']['title'].'</td>
                                            </tr>';
											
								if($offer_data[0]['SavingOffer']['no_valid_other_offer']==1){			
                                        $data .=    '<tr>
                                              <td align="left" valign="top" style="font-family:arial; color:#ffffff; font-size:16px; padding-top:16px;">-Not valid with any other offer.</td>
                                            </tr>';
									}
								if($offer_data[0]['SavingOffer']['no_transferable']==1){	
											
                                          $data .=   '<tr>
                                              <td align="left" valign="top" style="font-family:arial; color:#ffffff; font-size:16px; padding-top:5px;">-Non transferrable/Not for resale/Not <br />
                                              &nbsp;&nbsp;redeemable for cash</td>
                                            </tr>';
									}
									
								if($offer_data[0]['SavingOffer']['other']==1){				
										$data .=   '<tr>
                                              <td align="left" valign="top" style="font-family:arial; color:#ffffff; font-size:16px; padding-top:5px;">'.$offer_data[0]['SavingOffer']['disclaimer'].'</td>
                                            </tr>';
										}		
											
											
                                        $data .= '<tr>
                                              <td align="left" valign="top" style="padding-top:12px; padding-left:4px;"><a href="'.$site_url.'merchants/'.$this->common->getcompanyurl($advertiser).'" target="_blank"><img src="'.$newsletter_url.'view-button3.jpg" width="62" height="32" alt="view" border="0" style="border:0; display:block;" /></a></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                </tr>
                              </table></td>
                            <td width="24" align="left" valign="top">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top"><img src="'.$newsletter_url.'bottom-crv2.jpg" width="650" height="47" alt=" " border="0" style="display:block; border:0" /></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="center" valign="top"><table width="610" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="right" valign="top" style="padding-bottom:22px;"><table width="201" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><img src="'.$newsletter_url.'powered-by-zuni.jpg" width="201" height="50" alt=" " border="0" style="display:block; border:0" /></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>';
return $data;
}
}
?>