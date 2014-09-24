<?php

/*This 'GRANTH' was written by IShop Creative members :)

common (bechara) functions to use in any page as you wish

*/

class emailhtmlComponent extends Object {
	var $components = array('Auth');
	function email_header($county_id='') {
		$county_url = '';
		if($county_id) {
			$county_url = 'state/'.$this->getStateUrl($county_id).'/'.$this->getCountyUrl($county_id).'/';
		}
		$newsletter_url = FULL_BASE_URL.router::url('/',false).'img/offer_email/';
		$site_url = FULL_BASE_URL.router::url('/',false).''.$county_url;
		$footer_url = FULL_BASE_URL.router::url('/',false);
		$header = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
        </tr>
        <tr>
          <td align="left" valign="top"><table width="600" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" valign="top"><table width="520" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td align="left" valign="top" style="background-color:#b1000b; height:6px;" bgcolor="#b1000b"></td>
                    </tr>
                    <tr>
                      <td align="center" valign="top" bgcolor="#cbc9ca" style="background-color:#cbc9ca"><table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#000000; font-size:18px; padding-top:62px; padding-bottom:50px;">';
		return $header;		
	}
	function email_footer($county_id='') {
		$county_url = '';
		if($county_id) {
			$county_url = 'state/'.$this->getStateUrl($county_id).'/'.$this->getCountyUrl($county_id).'/';
		}
		$newsletter_url = FULL_BASE_URL.router::url('/',false).'img/offer_email/';
		$site_url = FULL_BASE_URL.router::url('/',false).''.$county_url;
		$footer_url = FULL_BASE_URL.router::url('/',false);
		$footer = '<br /></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#000000; font-size:18px; font-weight:bold; padding-top:26px;"></td>
                          </tr>
                      </table></td>
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
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'contact" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Advertise w/Us</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'userPage" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Refer A Business</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'userPage" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Consumer Login</a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top"><table width="131" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#cccccc; font-weight:bold; font-size:14px; border-bottom:1px solid #3f3f3f; padding-bottom:6px;">About Zuni.Com</td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'pages/page/about-us" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">About Us</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'careers" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Careers</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'pages/page/zuni-cares" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Zuni Cares</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'pages/page/local-zuni-news" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Local Zuni News</a></td>
                          </tr>
                        </table></td>
                      <td align="center" valign="top"><table width="131" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="left" valign="top" style="font-family:arial; color:#cccccc; font-weight:bold; font-size:14px; border-bottom:1px solid #3f3f3f; padding-bottom:6px;">Support</td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'pages/page/terms" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Terms of Use</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'pages/page/privacy-policy" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Privacy</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="border-bottom:1px dashed #3f3f3f; padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'contact" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Contact Us</a></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" style="padding-top:6px; padding-bottom:6px;"><a href="'.$footer_url.'pages/page/faq" target="_blank" style="font-family:arial; color:#cccccc; font-size:12px; text-decoration:none;">Help / FAQ</a></td>
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
</table>
</body>
</html>';
		return $footer;
	}
	function discount_header($county_id='') {
		$county_url = '';
		if($county_id) {
			$county_url = 'state/'.$this->getStateUrl($county_id).'/'.$this->getCountyUrl($county_id).'/';
		}
		$header = '';
		$footer_url = FULL_BASE_URL.router::url('/',false);
		$newsletter_url = FULL_BASE_URL.router::url('/',false).'img/discount_email/';
		$site_url = FULL_BASE_URL.router::url('/',false).''.$county_url;
		$header .= '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body style="margin:0px; padding:0px;"><table width="800" align="center" border="0" cellspacing="0" cellpadding="0" style="background:#ffffff; margin:0 auto; font-size:0px;">
  <tr>
    <td><table width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
        <tr>
          <td><table width="600" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
              <tr>
                <td width="600" height="20" align="left" valign="top"><img src="'.$newsletter_url.'newsletter_top01.jpg" width="600" height="21" alt="pic" border="0" style="display:block;" /></td>
              </tr>
              <tr>
                <td width="600" align="left" valign="top"><table width="600" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="42"><img src="'.$newsletter_url.'header_lf.jpg" height="160" width="42" /></td>
                      <td width="135"><a href="'.$site_url.'" style="border:none;"><img src="'.$newsletter_url.'logo_main.jpg" width="135" height="160" alt="Zuni" title="Zuni" /></a></td>
                      <td width="423"><img src="'.$newsletter_url.'click_save_rep.jpg" width="423" height="160" alt=" " /></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="background:#f1f8f8; padding:20px 0 20px 0;"><table align="center" width="550" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">';
		  return $header;
	}
	function discount_footer($county_id='') {
		$county_url = '';
		if($county_id) {
			$county_url = 'state/'.$this->getStateUrl($county_id).'/'.$this->getCountyUrl($county_id).'/';
		}
		$newsletter_url = FULL_BASE_URL.router::url('/',false).'img/discount_email/';
		$site_url = FULL_BASE_URL.router::url('/',false).''.$county_url;
		$footer = '';
		$footer_url = FULL_BASE_URL.router::url('/',false);
		$footer .= '</table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td align="left" valign="top" height="20"></td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#8e0500" width="800" style="margin:0px 0 0 0; padding:16px 0 10px 0; display:block;"><table width="567" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto; padding:0px 0 0 0;">
        <tr>
          <td align="left" valign="top" width="145"><table width="145" border="0" cellspacing="0" cellpadding="0" style="padding:0px 0 9px 0px;">
              <tr>
                <td align="left" valign="top" colspan="2" style=" font:16px Arial, Helvetica, sans-serif; color:#ffffff; font-weight:bold; margin:0px; padding:0px 0 7px 0;">About <a href="'.$site_url.'" style="color:white;text-decoration:none;">zuni.com</a></td>
              </tr>
              <tr>
                <td align="left" valign="middle" width="12"><img src="'.$newsletter_url.'arrow.png" width="6" height="8" alt="arrow" style="display:block;" /></td>
                <td align="left" valign="top" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; margin:0px; padding:0px 0 3px 0;"><a href="'.$site_url.'" target="_blank" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none;">Home</a></td>
              </tr>
              <tr>
                <td align="left" valign="middle" width="12"><img src="'.$newsletter_url.'arrow.png" width="6" height="8" alt="arrow" style="display:block;" /></td>
                <td align="left" valign="top" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; margin:0px; padding:0px 0 3px 0;"><a href="'.$footer_url.'pages/page/about-us" target="_blank" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none;">About Us</a></td>
              </tr>
              <tr>
                <td align="left" valign="middle" width="12"><img src="'.$newsletter_url.'arrow.png" width="6" height="8" alt="arrow" style="display:block;" /></td>
                <td align="left" valign="top" style=" font:12px Arial, Helvetica, sans-serif , Arial, Helvetica, sans-serif; color:#ffffff; margin:0px; padding:0px 0 3px 0;"><a href="'.$footer_url.'careers" target="_blank" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none;">Careers</a></td>
              </tr>
			  <tr>
                <td align="left" valign="middle" width="12"><img src="'.$newsletter_url.'arrow.png" width="6" height="8" alt="arrow" style="display:block;" /></td>
                <td align="left" valign="top" style=" font:12px Arial, Helvetica, sans-serif , Arial, Helvetica, sans-serif; color:#ffffff; margin:0px; padding:0px 0 3px 0;"><a href="'.$footer_url.'pages/page/zuni-cares" target="_blank" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none;">Zuni Cares</a></td>
              </tr>
			  <tr>
                <td align="left" valign="middle" width="12"><img src="'.$newsletter_url.'arrow.png" width="6" height="8" alt="arrow" style="display:block;" /></td>
                <td align="left" valign="top" style=" font:12px Arial, Helvetica, sans-serif , Arial, Helvetica, sans-serif; color:#ffffff; margin:0px; padding:0px 0 3px 0;"><a href="'.$footer_url.'pages/page/local-zuni-news" target="_blank" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none;">Local Zuni News</a></td>
              </tr>
            </table></td>
          <td align="left" valign="top" width="145"><table width="145" border="0" cellspacing="0" cellpadding="0" style="padding:0px 0 9px 23px;">
              <tr>
                <td align="left" valign="top" colspan="2" style=" font:16px Arial, Helvetica, sans-serif; color:#ffffff; font-weight:bold; margin:0px; padding:0px 0 7px 0;">Support</td>
              </tr>
              <tr>
                <td align="left" valign="middle" width="12"><img src="'.$newsletter_url.'arrow.png" width="6" height="8" alt="arrow" style="display:block;" /></td>
                <td align="left" valign="top" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; margin:0px; padding:0px 0 3px 0;"><a href="'.$footer_url.'pages/page/terms" target="_blank" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none;">Terms of Use</a></td>
              </tr>
              <tr>
                <td align="left" valign="middle" width="12"><img src="'.$newsletter_url.'arrow.png" width="6" height="8" alt="arrow" style="display:block;" /></td>
                <td align="left" valign="top" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; margin:0px; padding:0px 0 3px 0;"><a href="'.$footer_url.'pages/page/privacy-policy" target="_blank" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none;">Privacy</a></td>
              </tr>
              <tr>
                <td align="left" valign="middle" width="12"><img src="'.$newsletter_url.'arrow.png" width="6" height="8" alt="arrow" style="display:block;" /></td>
                <td align="left" valign="top" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; margin:0px; padding:0px 0 3px 0;"><a href="'.$footer_url.'contact" target="_blank" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none;">Contact Us</a></td>
              </tr>
              <tr>
                <td align="left" valign="middle" width="12"><img src="'.$newsletter_url.'arrow.png" width="6" height="8" alt="arrow" style="display:block;" /></td>
                <td align="left" valign="top" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; margin:0px; padding:0px 0 3px 0;"><a href="'.$footer_url.'pages/page/faq" target="_blank" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none; display:block;">Help / FAQ</a></td>
              </tr>
            </table></td>
          <td align="right" valign="top" width="145"><a href="'.$site_url.'" target="_blank" style="border:none;" ><img src="'.$newsletter_url.'footer_logo.png" border="0" width="110" height="108" alt="arrow" style="display:block;" /></a></td>
        </tr>
        <tr>
          <td colspan="4" align="center" valign="top" style=" font:12px Arial, Helvetica, sans-serif; color:#ffffff; margin:0px; padding:8px 0 0px 0;">&copy; Copyright '.date('Y').' Zuni. All rights Reserved.</td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="800" align="center" border="0" cellspacing="0" cellpadding="0" style="background:#ffffff; margin:0 auto; font-size:0px;">
  <tr>
    <td colspan="4" align="center" valign="top" bgcolor="#FFFFFF" style=" font:12px Arial, Helvetica, sans-serif; color:#000; margin:0px; padding:8px 0 0px 0;"> You are receiving this email because you signed up to receive Zuni.com communications. If you wish to unsubscribe from future<br />
      Zuni.com entails you can always log in to your consumer account on <a href="'.$site_url.'" target="_blank">www.zuni.com</a> and visit your "My Account" section or if you<br />
      want to change your email category preferences. Please don\'t reply to this email- use the "Contact Us" link above or the "Feedback"<br />
      section in your Zuni.com account.<br />
      To purchase one of the Daily Discount in todays email you must click the View Now! button and follow the instructions.Once you<br />
      have made your purchase you can either print the voucher or have it emailed to you. <br />
      <br />
    </td>
  </tr>
</table></body></html>';
return $footer;
	}
	function getCountyUrl($id) {
			App::import('model','County');
		    $this->County = new County();			
			$County = $this->County->find('first',array('fields'=>('County.page_url'),'conditions'=>array('County.id'=>$id)));
			return $County['County']['page_url'];	
	}
	function getStateUrl($county_id) {
			App::import('model','County');
		    $this->County = new County();
			$County = $this->County->find('first',array('fields'=>('County.state_id'),'conditions'=>array('County.id'=>$county_id)));
			App::import('model','State');
		    $this->State = new State();
			$State = $this->State->find('first',array('fields'=>('State.page_url'),'conditions'=>array('State.id'=>$County['County']['state_id'])));
			return $State['State']['page_url'];
	}	
}
?>