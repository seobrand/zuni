<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>I Shop</title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/reset.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php echo $form->create('County', array('action'=>'signupEmail','id'=>'form'));?>
<div class="signup"> <img src="img/free_email.png" alt="Free Email Sign Up" />
  <div class="formbg">
    <h1>Sign up now for free!</h1>
    <div class="signup_gray_box">
      <div class="formtop"></div>
      <div class="formmid">
     
        <table width="400" border="0" cellspacing="0" cellpadding="0" align="left">
          <tr>
            <td align="left" valign="top" style="padding:0 0 0 0;"><table width="450" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left" valign="middle" >First name:</td>
                  <td align="left" valign="middle"><input type="text" name="first_name" id="first_name" class="input_back" /><?php echo $form->input('first_name', array('label'=>'First name: :<font color="#993400">*</font>','id'=>'first_name','tabindex'=>1,'class'=>'pad_t5','div'=>false,'value'=>$AdvertiserProfile['AdvertiserProfile']['name'])); ?></td>
                </tr>
                <tr>
                  <td align="left" valign="middle" >Last name:</td>
                  <td align="left" valign="middle"><input type="text" name="last_name" id="last_name" class="input_back" /></td>
                </tr>
                <tr>
                  <td align="left" valign="middle" >E-mail:</td>
                  <td align="left" valign="middle"><input type="text" name="email" id="email" class="input_back" /></td>
                </tr>
                <tr>
                  <td align="left" valign="middle" >Confirm email:</td>
                  <td width="288" align="left" valign="middle"><input type="text" name="confirm_email" id="confirm_email" class="input_back" /></td>
                </tr>
                <tr>
                  <td align="left" valign="middle" >Address:</td>
                  <td align="left" valign="middle"><input type="text" name="address" id="address" class="input_back" /></td>
                </tr>
                <tr>
                  <td align="left" valign="top" >Zip code: </td>
                  <td align="left" valign="middle"><input type="text" name="zip_code" id="zip_code" class="input_back1" /></td>
                </tr>
                <tr>
                  <td align="left" valign="top" >Birthday </td>
                  <td align="left" ><table width="176" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="right">
                        <select  name="dob_month" id="dob_month" class="select_menu">
                        <option value="">MM</option>
                        <?php for($j=1 ; $j<=12; $j++){?>
                        <option value="<?=$j;?>"><?=$j;?></option>
                        <?php }?>
                        </select>
                        </td>
                        <td align="right">
                        <select name="dob_day" id="dob_day" class="select_menu">
                        <option value="">DD</option>
                        <?php for($i=1 ; $i<=31; $i++){?>
                        <option value="<?=$i;?>"><?=$i;?></option>
                        <?php }?>
                        </select></td>
                        <td align="right">
                        <select name="dob_year" id="dob_year" class="select_menu">
                         <option value="">YYYY</option>
                         <?php for($k=1930 ; $k<=2010; $k++){?>
                         <option value="<?=$k;?>"><?=$k;?></option>
                         <?php }?></select>
                          </td>  
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
        </table> 
      
        <div class="clear"></div>
      </div>
      <div class="formbot"></div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
  <div class="check">
    <table width="524" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><h1> Check Both Boxes to proceed</h1></td>
      </tr>
      <tr>
        <td height="49" valign="top"><input type="checkbox" name="special_offers" id="special_offers" />
          By checking this box, you have chosen to reveive discounts and special offers from participating businesses and agree to this site's Terms and conditions and Privacy Policy</td>
      </tr>
      <tr>
        <td height="44" valign="top"><input type="checkbox" name="privacy_policy" id="privacy_policy" />
          This site is for individuals 18 years of age and over. Please check this box to confirm that you are over 18.</td>
      </tr>
      <tr>
        <td><table width="220" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td valign="top"><input type='image' src="img/submit.jpg" name="submit" alt="submit" /></td>
              <td align="right" valign="top">&nbsp;<!--<img src="img/close.jpg" alt="close" />--></td>
            </tr>
          </table></td>
      </tr>
    </table>
  </div>
  <div class="clear"></div>
</div> 
</form>
</body>
</html>
