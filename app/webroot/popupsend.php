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
 
   APP::import('Model','Setting');
   $setting=new Setting();
    $content=$setting->query("select subject,content from settings");
	$msg=$content[0]['settings']['content'];
	$subject=$content[0]['settings']['subject'];
    //$subject = "Invitation to join ishop : ";
	$to = $_POST['email'];
	$from='refer@ishop.com';
	$headers  =  "From:".$from."\r\n".'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$content  = "Dear ".$_POST['fr_name'] ."<br/><br/>";
	$content .= $_POST['message']."<br/>";
	$content .= $msg."<br/>";
	$content .= "Take a look below url :- <br/>";
	$content .= $_POST['url'] ."<br/><br/> Thanks";
	if( mail($to, $subject, $content, $headers) )
	{ 
	
	   $success = 1;
       //echo  "<font style='color:#FF0000'>Email sent Successfully.<br/><br/></font>";
	 }else{
	   
	   $error = 1;
	  //echo  "<font style='color:#FF0000'>Email not sent.<br/><br/></font>";
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
function validateNew()
{
   if(document.getElementById('first_name').value==''){
    alert('Please Enter your name');
	document.getElementById('first_name').focus();
	return false;
   }
   
    if(document.getElementById("ur_email").value=="")
          {
	      //document.getElementById('rightformate').style.display = 'block';
	      alert("Please Enter your Email");
	      document.getElementById("ur_email").focus();
	      return false;
          }
    if(document.getElementById('ur_email').value != "")
 		 {
	     fieldValue = document.getElementById('ur_email').value;
		 if ( fieldValue == '')
		    {
		    alert("Please Enter Your Email");
			document.getElementById("ur_email").focus();
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
				document.getElementById("ur_email").value ='';
				document.getElementById("ur_email").focus();
				return false;
			}
		}
	}
	
   if(document.getElementById('fr_name').value==''){
    alert('Please Enter friend  name');
	document.getElementById('fr_name').focus();
	return false;
   }	
     
   if(document.getElementById("email").value=="")
          {
	      //document.getElementById('rightformate').style.display = 'block';
	      alert("Please Enter your friend's Email");
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
				document.getElementById("email").value ='';
				document.getElementById("email").focus();
				return false;
			}
		}
	}
   
   if(document.getElementById('message').value==''){
    alert('Please Enter your message');
	document.getElementById('message').focus();
	return false;
   }

}
</script>
<body>
<?php if($success){ ?>
<br/><br/>
<div align="center">
<font style='color:#006633; font-weight:bold;' >Email sent successfully.<br/><br/></font></div>

<?php }else{

if($error==1){ ?>

<div align="center">
<font style='color:#FF0000; font-weight:bold;' >Email not sent.<br/><br/></font></div>
<?php } ?>

<form action="" method="post" onsubmit="return validateNew();">
<div class="signup"> <img src="img/send_img.png" alt="Free Email Sign Up" />
  <div class="formbg">
    <h1>Send to a  friend!</h1>
    <div class="signup_gray_box">
      <div class="formtop"></div>
      <div class="formmid">
     
        <table width="400" border="0" cellspacing="0" cellpadding="0" align="left">
          <tr>
            <td align="left" valign="top" style="padding:0 0 0 0;"><table width="450" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left" valign="middle" >Your name:</td>
                  <td align="left" valign="middle"><input type="text" name="first_name" id="first_name" class="input_back" /></td>
                </tr>
                <tr>
                  <td align="left" valign="middle" >Your E-mail:</td>
                  <td align="left" valign="middle"><input type="text" name="ur_email" id="ur_email" class="input_back" /></td>
                </tr>
                 <tr>
                  <td align="left" valign="middle" >Friend's name:</td>
                  <td align="left" valign="middle"><input type="text" name="fr_name" id="fr_name" class="input_back" /></td>
                </tr>
                <tr>
                  <td align="left" valign="middle" >Friend's E-mail:</td>
                  <td align="left" valign="middle"><input type="text" name="email" id="email" class="input_back" /></td>
                </tr>
                <tr>
                  <td align="left" valign="top" >Message:</td>
                  <td width="288" align="left" valign="middle"><textarea cols="50" rows="10" id="message" name="message" style="font-size:12px; font-weight:bold;" ></textarea></td>
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
  <br/>
    <table width="524" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><table width="220" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td valign="top"><input type='image' src="img/submit.jpg" name="submit" alt="submit" /></td>
              <td align="right" valign="top">&nbsp;<!--<img src="img/close.jpg" alt="close" />--></td>
            </tr>
          </table>
          </td>
      </tr>
    </table>
  </div>
  <div class="clear"></div>
</div> 
<input type="hidden" name="url" id="url" value="<?=$_GET['url'];?>" class="input_back" />
</form> 

<?php }?>
</body>
</html>