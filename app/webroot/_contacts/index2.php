<?php
ini_set('display_errors', 0);
session_start();
if(strpos($_SERVER['HTTP_REFERER'],'webroot/contacts')) {} else {
	$_SESSION['siteurl'] = $_SERVER['HTTP_REFERER'];
}
include('openinviter.php');
$inviter=new OpenInviter();
$oi_services=$inviter->getPlugins();

if (isset($_POST['provider_box']))
{
	if (isset($oi_services['email'][$_POST['provider_box']])) $plugType='email';
	elseif (isset($oi_services['social'][$_POST['provider_box']])) $plugType='social';
	else $plugType='';
}
else $plugType = '';
function ers($ers)
	{
	if (!empty($ers))
		{
		$contents="<table cellspacing='0' cellpadding='0' style='border:1px solid red;' align='center'><tr><td valign='middle' style='padding:3px' valign='middle'><img src='images/ers.gif'></td><td valign='middle' style='color:red;padding:5px;'>";
		foreach ($ers as $key=>$error)
			$contents.="{$error}<br >";
		$contents.="</td></tr></table><br >";
		return $contents;
		}
	}

function oks($oks)
	{
	if (!empty($oks))
		{
		$contents="<table border='0' cellspacing='0' cellpadding='10' style='border:1px solid #5897FE;' align='center'><tr><td valign='middle' valign='middle'><img src='images/oks.gif' ></td><td valign='middle' style='color:#5897FE;padding:5px;'>	";
		foreach ($oks as $key=>$msg)
			$contents.="{$msg}<br >";
		$contents.="</td></tr></table><br >";
		return $contents;
		}
	}

if (!empty($_POST['step'])) $step=$_POST['step'];
else $step='get_contacts';

$ers=array();$oks=array();$import_ok=false;$done=false;
if ($_SERVER['REQUEST_METHOD']=='POST')
	{
	if ($step=='get_contacts')
		{
		if (empty($_POST['email_box']))
			$ers['email']="Email missing !";
		if (empty($_POST['password_box']))
			$ers['password']="Password missing !";
		if (empty($_POST['provider_box']))
			$ers['provider']="Provider missing !";
		if (count($ers)==0)
			{
			$inviter->startPlugin($_POST['provider_box']);
			$internal=$inviter->getInternalError();
			if ($internal)
				$ers['inviter']=$internal;
			elseif (!$inviter->login($_POST['email_box'],$_POST['password_box']))
				{
				$internal=$inviter->getInternalError();
				$ers['login']=($internal?$internal:"Login failed. Please check the email and password you have provided and try again later !");
				}
			elseif (false===$contacts=$inviter->getMyContacts())
				$ers['contacts']="Unable to get contacts !";
			else
				{
				$import_ok=true;
				$step='send_invites';
				$_POST['oi_session_id']=$inviter->plugin->getSessionID();
				$_POST['message_box']='';
				}
			}
		}
	elseif ($step=='send_invites')
		{

		if (empty($_POST['provider_box'])) $ers['provider']='Provider missing !';
		else
			{
			$inviter->startPlugin($_POST['provider_box']);
			$internal=$inviter->getInternalError();
			if ($internal) $ers['internal']=$internal;
			else
				{
				if (empty($_POST['email_box'])) $ers['inviter']='Inviter information missing !';
				if (empty($_POST['oi_session_id'])) $ers['session_id']='No active session !';
				//if (empty($_POST['message_box'])) $ers['message_body']='Message missing !';
				//else $_POST['message_box']=strip_tags($_POST['message_box']);
				$selected_contacts=array();$contacts=array();
				//$message=array('subject'=>$inviter->settings['message_subject'],'body'=>$inviter->settings['message_body'],'attachment'=>"\n\rAttached message: \n\r".$_POST['message_box']);
				if ($inviter->showContacts())
					{
					foreach ($_POST as $key=>$val)
						if (strpos($key,'check_')!==false)
							$selected_contacts[$_POST['email_'.$val]]=array($_POST['first_name_'.$val],$_POST['last_name_'.$val]);
						elseif (strpos($key,'email_')!==false)
							{
							$temp=explode('_',$key);$counter=$temp[1];
							if (is_numeric($temp[1])) $contacts[$val]=array('first_name'=>$_POST['first_name_'.$temp[1]],'last_name'=>$_POST['last_name_'.$temp[1]]);
							}
					if (count($selected_contacts)==0) $ers['contacts']="You haven't selected any contacts.";
					}
				}
			}

		if (count($ers)==0)
			{

				echo '
					<script language="JavaScript">

					var contacts = new Array();
					';
				$i=0;
				foreach($selected_contacts as $k=>$v)
				{
					echo 'contacts['.$i++.'] = new Array( "'.$k.'","'.$v[0].'","'.$v[1].'" );';
				}
				echo '
					window.opener.fillContacts(contacts);
	  				top.close();
					</script>
				';
				exit;
			}
		}
	}
else
	{
	$_POST['email_box']='';
	$_POST['password_box']='';
	$_POST['provider_box']='';
	}

$contents="<script type='text/javascript'>
	function toggleAll(element){
		var form = document.forms.openinviter, z = 0;
		var i = 0;
		for(z=0; z<form.length;z++){
			if(form[z].type == 'checkbox' && i <= 25){
				form[z].checked = element.checked;
				i++;
			}else{
				form[z].checked = '';
			}
		}
	}	
	
	function countNumberContacts(currentCheckbox){
		var cnt = 0;
		for(z=1; z<=1000;z++){
			var aval = eval(document.getElementById('check_'+z));
			if(aval != null){
				if(document.getElementById('check_'+z).checked == true){
					cnt++;
				}
			}		
		}
		
		if(cnt == 26){
			currentCheckbox.checked = false;
			alert('Maximum limit of 25 contacts to import has been reached.');
		}
		
	}
</script>";


if (!$done)
	{
	if ($step=='send_invites')
		{
		if ($inviter->showContacts())
			{
			
			if (count($contacts)==0) { 		
				$contents.="<link href='styles.css' rel='stylesheet' type='text/css' />
				<div class='formsbg' style='margin:0 0 0 10px;'>
				  <div class='formsbg_top' style='overflow:hidden;height:30px;'><img src='images/import.jpg' alt='import contact to refer' align='left' style='margin:0 0 0 15px;' /></div>
				  <div class='formsbg_mid'>
					<table align='center' cellpadding='10' cellspacing='10' width='350' >
					  <tr>
						<td width='350' align='center' cellpadding='10' cellspacing='10' ><form action='".$_SESSION['siteurl']."' method='POST' name='openinviter' id='openinviter'>".ers($ers).oks($oks);
						
			   } else {
			  $contents.="<link href='styles.css' rel='stylesheet' type='text/css' />
				<div class='emailbg'>
				  <div class='emailtop1' style='padding-left:20px;width:809px; '><img src='images/import.jpg' alt='import contact to refer' align='left' /></div>
				  <div class='emailmid'>
					<table align='center' cellpadding='10' cellspacing='10' width='650' >
					  <tr>
						<td width='650' align='center' cellpadding='10' cellspacing='10' ><form action='".$_SESSION['siteurl']."' method='POST' name='openinviter' id='openinviter'>".ers($ers).oks($oks);
						
			   }
			 }
		} 
		
	if ($step=='get_contacts') {
	
	$contents.="<link href='styles.css' rel='stylesheet' type='text/css' />
<div class='formsbg'  style='margin:0 0 0 10px;'>
  <div class='formsbg_top' style='overflow:hidden;height:30px;'><img src='images/import.jpg' alt='import contact to refer' align='left' style='margin:0 0 0 15px;' /></div>
  <div class='formsbg_mid'>
    <table align='center' cellpadding='10' cellspacing='10' width='350' >
      <tr>
        <td width='350' align='center' cellpadding='10' cellspacing='10' ><form action='' method='POST' name='openinviter'>".ers($ers).oks($oks);
	}
}
				
		
if (!$done)
	{
	if ($step=='get_contacts')
		{
		$contents.="<table align='center' class='thTable' cellspacing='10' cellpadding='0' width='350'>";
		$contents.="
		<tr class='thTableRow'>
                <td align='right'><label for='provider_box'>Your Email Provider</label></td>
                <td><select class='thSelect' name='provider_box' style='width:133px;'>
                    <option value=''></option>";
		foreach ($oi_services as $type=>$providers)
			{
			$contents.="<optgroup label='{$inviter->pluginTypes[$type]}'>";
			foreach ($providers as $provider=>$details)
				$contents.="<option value='{$provider}'".($_POST['provider_box']==$provider?' selected':'').">{$details['name']}</option>";
			$contents.="</optgroup>";
			}
		$contents.="</select></td></tr>";

		$contents.="
		<tr class='thTableRow'>
                <td align='right'><label for='email_box'>Your Email Username</label></td>
                <td><input class='thTextbox' name='email_box' type='text' value='{$_POST['email_box']}' size='20'></td>
              </tr>
              <tr class='thTableRow'>
                <td align='right'><label for='password_box'>Your Email Password</label></td>
                <td><input class='thTextbox' name='password_box' type='password' value='{$_POST['password_box']}' size='20'></td>
              </tr>";

		$contents.="
		<tr class='thTableImportantRow'>
                <td height='40' align='right' valign='top'><img src='images/cancel.jpg' title='cancel' onClick='top.close();' style='cursor:pointer;' /></td> 
                <td height='40' valign='top'>   <input type='image' src='images/start-import.jpg' title='start import'>&nbsp;</td>
           
              </tr>
           
              <tr> <td colspan='2'>   * Your username & password will not be stored on this server. </td>
              </tr>
            </table>
			<input type='hidden' name='step' value='get_contacts'>";
		}
	else
		$contents.="";
	}
if (!$done)
	{
	if ($step=='send_invites')
		{
		if ($inviter->showContacts())
			{		   
		   echo '<script language="javascript">self.resizeTo(900, 600);</script>';
			//$contents.="<table class='thTable' align='center' cellspacing='0' cellpadding='0' width='650'><tr class='thTableHeader'><td colspan='".($plugType=='email'? "3":"2")."'>Your contacts</td></tr>";
			if (count($contacts)==0)
				$contents.="<table class='thTable' align='center' cellspacing='0' cellpadding='0' width='350'><tr class='thTableHeader'><td colspan='".($plugType=='email'? "3":"2")."'>Your contacts</td></tr><tr class='thTableOddRow'><td align='center' style='padding:20px;' colspan='".($plugType=='email'? "3":"2")."'>You do not have any contacts in your address book.</td></tr>";
			else
				{
				$contents.="<table class='thTable' align='center' cellspacing='0' cellpadding='0' width='650'><tr class='thTableHeader'><td colspan='".($plugType=='email'? "3":"2")."'>Your contacts</td></tr><tr class='thTableDesc'><td><input type='checkbox' onChange='toggleAll(this)' name='toggle_all' title='Select/Deselect all' ></td><td>Add</td><td>Name</td>".($plugType == 'email' ?"<td>E-mail</td>":"")."</tr>";
				$odd=true;$counter=0;
				foreach ($contacts as $email=>$data)
					{
					$counter++;
					if ($odd) $class='thTableOddRow'; else $class='thTableEvenRow';					
					if($data['first_name']=='' && $data['last_name']=='')	$data['first_name'] = 'Friend';
					$contents.="<tr class='{$class}'><td colspan=2><input name='check_{$counter}' id='check_{$counter}' value='{$counter}' type='checkbox' class='thCheckbox' onclick='countNumberContacts(this)' ><input type='hidden' name='email_{$counter}' id='email_{$counter}' value='{$email}'><input type='hidden' name='first_name_{$counter}' id='first_name_{$counter}' value='{$data['first_name']}'><input type='hidden' name='last_name_{$counter}' id='last_name_{$counter}' value='{$data['last_name']}'></td><td>{$data['first_name']} {$data['last_name']}</td>".($plugType == 'email' ?"<td>{$email}</td>":"")."</tr>";
					$odd=!$odd;
					}
				$contents.="<tr class='thTableFooter'><td colspan='".($plugType=='email'? "4":"3")."' style='padding:3px;'><input type='submit' name='send' value='Add Contacts' class='thButton' onclick='getallcontacts()'></td></tr>";
				}
			$contents.="</table>";
			}
		$contents.="<input type='hidden' name='step' value='send_invites'>
			<input type='hidden' name='provider_box' value='{$_POST['provider_box']}'>
			<input type='hidden' name='email_box' value='{$_POST['email_box']}'>
			<input type='hidden' name='oi_session_id' value='{$_POST['oi_session_id']}'>";
		}
	}
	
	
	
	if (!$done)
	{
	if ($step=='send_invites')
		{
		if ($inviter->showContacts())
			{	
			
			if (count($contacts)==0) { 		
			$contents.="</form></td>
      </tr>
    </table>
    <div class='clear'></div>
  </div>
  <div class='formsbg_bot'></div>
  <div class='clear'></div>
</div>";
			} 
			else {
			
				$contents.="</form></td>
						  </tr>

						</table>
						<div class='clear'></div>
					  </div>
					  <div class='emailbot'></div>
					  <div class='clear'></div>
					</div>";
			}	
								
			 }
		} 
		
	if ($step=='get_contacts') {	
	$contents.="</form></td>
      </tr>
    </table>
    <div class='clear'></div>
  </div>
  <div class='formsbg_bot'></div>
  <div class='clear'></div>
</div>";
	}
}
?>


<html>
<style>
body { margin: 5; padding: 0; text-align: center;  background-color: #E6E5CB; background-image: url(../../images/site_back.gif); font: 10px helvetica, arial, san-serif;  color: #333; }

table.thTable2 {
	background:#E6E5CB;
	border:1px solid #d9d9d9;
	width:500px;
}

.thTable td{
	font-size:11px;
	padding:4px 1px;
 }

.thTableOddRow {
	background:#efefef;
}

.thTableHeader td {
	font-weight:bold;
	font-size:12pt;
}

.thTableDesc{
	background:#d2d2d2;
	font-weight:bold;
	font-size:9pt;
}

</style>

<table class="thTable2" align="center" cellpadding="20" width="500"><tr><td width="500">
<?=$contents;?>
</td></tr></table>
<div id="collect_mails" style="display:none"></div>
</html>
<script type="text/javascript">
function getallcontacts() {
var myform = document.getElementById('openinviter');
var inputTags = myform.getElementsByTagName('input');
var checkboxCount = 0;
for (var i=0, length = inputTags.length; i<length; i++) {
     if (inputTags[i].type == 'checkbox') {
         checkboxCount++;
     }
}
for (var j=0;j<=checkboxCount; j++) {
     if(document.getElementById('check_'+j)!=null && document.getElementById('check_'+j).checked==true) {	 	
	 	if(document.getElementById('collect_mails').innerHTML=='')
		{
		document.getElementById('collect_mails').innerHTML = document.getElementById('email_'+j).value;
		}
		else
		{
		document.getElementById('collect_mails').innerHTML = document.getElementById('collect_mails').innerHTML+','+document.getElementById('email_'+j).value;		
		}
//		window.opener.document.getElementById('id').value++;
     }	 
}
	if(window.opener.document.getElementById('allemails').value=='')
	{
	window.opener.document.getElementById('allemails').value=document.getElementById('collect_mails').innerHTML;
	}
	else
	{
	window.opener.document.getElementById('allemails').value=window.opener.document.getElementById('allemails').value+','+document.getElementById('collect_mails').innerHTML;
	window.opener.document.getElementById('allemails').value =window.opener.document.getElementById('allemails').value.replace(",,", ",");
	}
	
	window.close();
}
</script>