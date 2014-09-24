<?php
/*This 'GRANTH' was written by IShop Creative members :)
common (bechara) functions to use in any page as you wish
*/
class discountComponent extends Object {
	var $components = array('Auth');
	
	function discounts($county) {
		$newsletter_url = FULL_BASE_URL.router::url('/',false).'img/newsletter/';
		$site_url = FULL_BASE_URL.router::url('/',false);
		$discounts = '<tbody>
<tr>
<td style="background:#202020;" width="567" height="65" align="center" valign="middle">
<table style="width: 550px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="font:45px Arial, Helvetica, sans-serif; color:#ffffff; border:1px dashed #303030; text-transform:uppercase;" align="center" valign="middle">today\'s discount</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">
<table style="background-color: #dbdad5; padding: 0px; margin: 0px; width: 567px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="font:28px Arial, Helvetica, sans-serif; color:#b50601; font-weight:bold; text-transform:uppercase; margin:0px; padding:0px;" colspan="2" width="567" height="58" align="center" valign="middle">paradise nail &amp; Tanning Salon</td>
</tr>
<tr>
<td width="290" height="300" align="center" valign="top"><img src="../../img/newsletter/nail.jpg" border="0" alt="pic" width="248" height="278" /></td>
<td align="left" valign="top">
<table style="width: 277px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td width="277" align="left" valign="top"><img src="../../img/newsletter/off.jpg" border="0" alt="pic" width="250" height="238" /></td>
</tr>
<tr>
<td align="left" valign="top"><a href="[discount_page]"><img src="'.$newsletter_url.'buy_now.jpg" border="0" alt="pic" width="142" height="41" /></a></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">
<table style="width: 567px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td align="left" valign="top">
<table style="padding: 20px 0px 0px; width: 100%;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td align="left" valign="top">
<table style="margin: 0px; width: 273px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="background:#202020;" width="273" height="48" align="center" valign="middle">
<table style="width: 263px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="font-family:Arial, Helvetica, sans-serif; font-size:22px; color:#ffffff; border:1px dashed #303030; text-transform:uppercase;" align="center" valign="middle">today\'s discount</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">
<table style="background-color: #e0dfda; padding: 0px; width: 273px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#b50601; text-transform:uppercase; margin:0px; padding:0px;" colspan="2" width="273" height="31" align="center" valign="middle">paradise nail &amp; Tanning Salon</td>
</tr>
<tr>
<td width="145" height="170" align="center" valign="top"><img src="../../img/newsletter/nail_small.jpg" border="0" alt="pic" width="131" height="148" /></td>
<td align="left" valign="top">
<table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td width="128" align="left" valign="top"><img src="../../img/newsletter/off_small.jpg" border="0" alt="pic" width="126" height="123" /></td>
</tr>
<tr>
<td align="left" valign="top"><a href="[discount_page]"><img src="'.$newsletter_url.'buy_now_small.jpg" border="0" alt="pic" width="102" height="27" /></a></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
<td align="right" valign="top">
<table style="margin: 0px; width: 273px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="background:#202020;" width="273" height="48" align="center" valign="middle">
<table style="width: 263px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="font-family:Arial, Helvetica, sans-serif; font-size:22px; color:#ffffff; border:1px dashed #303030; text-transform:uppercase;" align="center" valign="middle">today\'s discount</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">
<table style="background-color: #e0dfda; padding: 0px; width: 273px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style=" font-family:Arial, Helvetica, sans-serif; font-size:15px; text-transform:uppercase; margin:0px; padding:0px;" colspan="2" width="273" height="31" align="center" valign="middle">paradise nail &amp; Tanning Salon</td>
</tr>
<tr>
<td width="145" height="170" align="center" valign="top"><img src="../../img/newsletter/nail_small.jpg" border="0" alt="pic" width="131" height="148" /></td>
<td align="left" valign="top">
<table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td width="128" align="left" valign="top"><img src="../../img/newsletter/off_small.jpg" border="0" alt="pic" width="126" height="123" /></td>
</tr>
<tr>
<td align="left" valign="top"><a href="[discount_page]"><img src="'.$newsletter_url.'buy_now_small.jpg" border="0" alt="pic" width="102" height="27" /></a></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>';
		return $discounts;
	}
}
?>