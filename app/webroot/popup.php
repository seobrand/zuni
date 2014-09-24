<?php  

	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */
	if (!defined('ROOT')) {
		define('ROOT', dirname(dirname(dirname(__FILE__))));
	}
/**
 * The actual directory name for the "app".
 *
 */
	if (!defined('APP_DIR')) {
		define('APP_DIR', basename(dirname(dirname(__FILE__))));
	}
/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 */
	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
		define('CAKE_CORE_INCLUDE_PATH', ROOT);
	}

/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 *
 */
	if (!defined('WEBROOT_DIR')) {
		define('WEBROOT_DIR', basename(dirname(__FILE__)));
	}
	if (!defined('WWW_ROOT')) {
		define('WWW_ROOT', dirname(__FILE__) . DS);
	}
	if (!defined('CORE_PATH')) {
		if (function_exists('ini_set') && ini_set('include_path', CAKE_CORE_INCLUDE_PATH . PATH_SEPARATOR . ROOT . DS . APP_DIR . DS . PATH_SEPARATOR . ini_get('include_path'))) {
			define('APP_PATH', null);
			define('CORE_PATH', null);
		} else {
			define('APP_PATH', ROOT . DS . APP_DIR . DS);
			define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
		}
	}
	
	
	//echo "==>".str_replace('webroot/','',WWW_ROOT).CORE_PATH . 'cake' . DS . 'bootstrap.php';
	if (!include(CORE_PATH . 'cake' . DS . 'bootstrap.php')) {
		trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
	}
	
$success = false;
$error = 0;		
	
if(count($_POST) !=0){ 

        APP::import('Model','SignupEmail');
		$SignupEmailObj = new SignupEmail();
		
        //$resultConst = $User->find('all');
		//pr($resultConst);
		$data = array();
		//$data['SignupEmail']['first_name'] = $_POST['first_name'];
		//$data['SignupEmail']['last_name']  = $_POST['last_name'];
		//$data['SignupEmail']['email'] = $_POST['email'];
		//$data['SignupEmail']['address'] = $_POST['address'];
		//$data['SignupEmail']['zip_code'] = $_POST['zip_code'];
		//$data['SignupEmail']['special_offers'] = $_POST['special_offers'];
		//$date = $_POST['dob_month']."-".$_POST['dob_day']."-".$_POST['dob_year'];  
		
		//$data['SignupEmail']['dob'] = mktime(0,0,0,$_POST['dob_month'],$_POST['dob_day'],$_POST['dob_year']);
		$dob = mktime(0,0,0,$_POST['dob_month'],$_POST['dob_day'],$_POST['dob_year']);
		$currenttimestamp = date('y-m-d h:i:s');
		
		
		//$SignupEmail->save($data);
		
		$res = $SignupEmailObj->query("SELECT email FROM signup_emails WHERE email ='".$_POST['email']."' ");
		if(count($res)==0){
		$result = $SignupEmailObj->query("INSERT INTO  signup_emails (first_name, last_name, email, address, zip_code, special_offers, dob, created, modified) values('".$_POST['first_name']."','".$_POST['last_name']."','".$_POST['email']."','".$_POST['address']."','".$_POST['zip_code']."','".$_POST['special_offers']."','".$dob."','".$currenttimestamp."','".$currenttimestamp."')");
		if($result){
		$success = 1;
		}else{
		$error = 1;
		$msg ='Signup failed';
		}
		}else{
		$error = 1;
		$msg ='Email already exist';
		}
		
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>I Shop</title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/reset.css" rel="stylesheet" type="text/css" />
</head>
<script type="text/javascript">
function validate()
{
   if(document.getElementById('first_name').value==''){
    alert('Please Enter your First name');
	document.getElementById('first_name').focus();
	return false;
   }
   if(document.getElementById('last_name').value==''){
    alert('Please Enter your Last name');
	document.getElementById('last_name').focus();
	return false;
   }
   
    if(document.getElementById("email").value=="")
          {
	      //document.getElementById('rightformate').style.display = 'block';
	      alert("Please Enter Email");
	      document.getElementById("email").focus();
	      return false;
          }
    if(document.getElementById('email').value != "")
 		 {
	     fieldValue = document.getElementById('email').value;
		 if ( fieldValue == '')
		    {
		    alert("Please Enter Email");
			document.getElementById("email").focus();
			return false;
		    }
		else
		    {
			i=fieldValue.indexOf("@")
			j=fieldValue.indexOf(".",i)
			k=fieldValue.indexOf(",")
			kk=fieldValue.indexOf(" ")
			jj=fieldValue.lastIndexOf(".")+1
			len=fieldValue.length
		
			if ((i>0) && (j>(1+1)) && (k==-1) && (kk==-1)) 
			{
				/* Right Email Address  */
			}
			else
			{
				alert("Please Enter Valid Email Address in the Given Formate mail@email.com");
				document.getElementById("email").focus();
				return false;
			}
		}
	}
   
   if(document.getElementById('confirm_email').value==''){
    alert('Please Enter your confirm email');
	document.getElementById('confirm_email').focus();
	return false;
   }
   
   if(document.getElementById('confirm_email').value !=''){
   if(document.getElementById('email').value !=document.getElementById('confirm_email').value){
    alert('Confirm email not matched with entered email');
	document.getElementById('confirm_email').focus();
	return false;
   }
   }
   
   if(document.getElementById('address').value==''){
    alert('Please Enter your address');
	document.getElementById('address').focus();
	return false;
   }
   if(document.getElementById('zip_code').value==''){
    alert('Please Enter your zip code');
	document.getElementById('zip_code').focus();
	return false;
   }
   if(document.getElementById('dob_month').value==''){
    alert('Please Enter your dob month');
	document.getElementById('dob_month').focus();
	return false;
   }
   if(document.getElementById('dob_day').value==''){
    alert('Please Enter your dob day');
	document.getElementById('dob_day').focus();
	return false;
   }
   if(document.getElementById('dob_year').value==''){
    alert('Please Enter your dob year');
	document.getElementById('dob_year').focus();
	return false;
   }

   
   if(document.getElementById('special_offers').checked == false){
    alert('Please Select Special offers');
	document.getElementById('special_offers').focus();
	return false;
   }
   
    if(document.getElementById('privacy_policy').checked == false){
    alert('Please check this box to confirm that you are over 18');
	document.getElementById('privacy_policy').focus();
	return false;
   }
   
   



}
</script>
<body>
<?php if($success){?>
<br/>
<br/>
<div align="center"> <font style='color:#006633; font-weight:bold;' >Sign up Complete.<br/>
  <br/>
  </font></div>
<?php }else{ ?>


<form action="" method="post" onsubmit="return validate();">
  <div class="signup"> <img src="img/free_email.png" alt="Free Email Sign Up" />
  <?php if($error ==1){?>
<div align="center" style="padding:2px 0 0 0;"> <font style='color:#FF0000; font-weight:bold;' >
  <?=$msg;?>
</font></div>
<?php }?>
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
                    <td align="left" valign="middle"><input type="text" name="first_name" id="first_name" class="input_back" /></td>
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
                          <td align="right"><select  name="dob_month" id="dob_month" class="select_menu">
                              <option value="">MM</option>
                              <?php for($j=1 ; $j<=12; $j++){?>
                              <option value="<?=$j;?>">
                              <?=$j;?>
                              </option>
                              <?php }?>
                            </select>
                          </td>
                          <td align="right"><select name="dob_day" id="dob_day" class="select_menu">
                              <option value="">DD</option>
                              <?php for($i=1 ; $i<=31; $i++){?>
                              <option value="<?=$i;?>">
                              <?=$i;?>
                              </option>
                              <?php }?>
                            </select></td>
                          <td align="right"><select name="dob_year" id="dob_year" class="select_menu">
                              <option value="">YYYY</option>
                              <?php for($k=1930 ; $k<=2010; $k++){?>
                              <option value="<?=$k;?>">
                              <?=$k;?>
                              </option>
                              <?php }?>
                            </select>
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
          <td height="49" valign="top"><input type="checkbox" name="special_offers" id="special_offers" value="yes" />
            By checking this box, you have chosen to reveive discounts and special offers from participating businesses and agree to this site's Terms and conditions and Privacy Policy</td>
        </tr>
        <tr>
          <td height="44" valign="top"><input type="checkbox" name="privacy_policy" id="privacy_policy" value="yes" />
            This site is for individuals 18 years of age and over. Please check this box to confirm that you are over 18.</td>
        </tr>
        <tr>
          <td align="center"><table width="220" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td valign="top" colspan="2" align="center"><input type='image' src="img/submit.jpg" name="submit" alt="submit" /></td>
               
              </tr>
            </table></td>
        </tr>
      </table>
    </div>
    <div class="clear"></div>
  </div>
</form>
<?php }?>
</body>
</html>
