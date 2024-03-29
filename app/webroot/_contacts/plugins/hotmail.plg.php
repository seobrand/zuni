<?php
$_pluginInfo=array(
	'name'=>'Live/Hotmail',
	'version'=>'1.6.4',
	'description'=>"Get the contacts from a Windows Live/Hotmail account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'http://login.live.com/login.srf?id=2',
	'requirement'=>'email',
	'allowed_domains'=>array('/(hotmail)/i','/(live)/i','/(msn)/i','/(chaishop)/i'),
	'imported_details'=>array('first_name','email_1'),
	);
/**
 * Live/Hotmail Plugin
 *
 * Imports user's contacts from Windows Live's AddressBook
 *
 * @author OpenInviter
 * @version 1.5.8
 */
class hotmail extends openinviter_base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $internalError=false;
	protected $timeout=30;

	public $debug_array=array(
				'initial_get'=>'LoginOptions',
				'login_post'=>'location.replace',
				'first_redirect'=>'self.location.href',
				'url_inbox'=>'peopleUrlDomain',
				'message_at_login'=>'peopleUrlDomain',
				'url_sent_to'=>'ContactList.aspx',
				'get_contacts'=>'\x26\x2364\x3',
				);

	/**
	 * Login function
	 *
	 * Makes all the necessary requests to authenticate
	 * the current user to the server.
	 *
	 * @param string $user The current user.
	 * @param string $pass The password for the current user.
	 * @return bool TRUE if the current user was authenticated successfully, FALSE otherwise.
	 */
	function login($user,$pass)
		{
		$this->resetDebugger();
		$this->service='hotmail';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		$res=$this->get("http://login.live.com/login.srf?id=2",true);
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"http://login.live.com/login.srf?id=2",'GET');
		else
			{
			$this->updateDebugBuffer('initial_get',"http://login.live.com/login.srf?id=2",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}

		if (strlen($pass) > 16) $pass=substr($pass, 0, 16);
		$post_action=$this->getElementString($res,'method="POST" target="_top" action="','"');
		$post_elements=$this->getHiddenElements($res);$post_elements["LoginOptions"]=3;$post_elements["login"]=$user;$post_elements["passwd"]=$pass;
		$res=$this->post($post_action,$post_elements,true);
		if ($this->checkResponse("login_post",$res))
			$this->updateDebugBuffer('login_post',"{$post_action}",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"{$post_action}",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}

		$url_redirect=$this->getElementString($res,'.location.replace("','"');
		$res=$this->get($url_redirect,true,true);
		if ($this->checkResponse('first_redirect',$res))
			$this->updateDebugBuffer('first_redirect',"{$url_redirect}",'GET');
		else
			{
			$this->updateDebugBuffer('first_redirect',"{$url_redirect}",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}

		$url_redirect=$this->getElementString($res,'rurl:"','"');
		$base_url="http://".$this->getElementString($res,'burl:"','"');
		$res=$this->get($url_redirect,true);

		if (strpos($res,'MessageAtLoginForm')!==false)
			{
			$form_action=$base_url.'mail/'.html_entity_decode($this->getElementString($res,'method="post" action="','"'));
			$post_elements=$this->getHiddenElements($res);$post_elements['TakeMeToInbox']='Continue';
			$res=$this->post($form_action,$post_elements,true);
			if ($this->checkResponse("message_at_login",$res))
				$this->updateDebugBuffer('message_at_login',"{$form_action}",'POST',true,$post_elements);
			else
				{
				$this->updateDebugBuffer('message_at_login',"{$form_action}",'POST',false,$post_elements);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			}
		else
			{
			if ($this->checkResponse('url_inbox',$res))
			$this->updateDebugBuffer('url_inbox',"{$url_redirect}",'GET');
			else
				{
				$this->updateDebugBuffer('url_inbox',"{$url_redirect}",'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}
			}

		$this->login_ok=$base_url;
		file_put_contents($this->getLogoutPath(),$base_url);
		return true;
		}

	/**
	 * Get the current user's contacts
	 *
	 * Makes all the necesarry requests to import
	 * the current user's contacts
	 *
	 * @return mixed The array if contacts if importing was successful, FALSE otherwise.
	 */
	public function getMyContacts()
		{
		if (!$this->login_ok)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else $base_url=$this->login_ok;
		$res=$this->get("{$base_url}/mail/EditMessageLight.aspx?n=");
		if ($this->checkResponse('url_sent_to',$res))
			$this->updateDebugBuffer('url_sent_to',"{$base_url}mail/EditMessageLight.aspx?n=",'GET');
			else
				{
				$this->updateDebugBuffer('url_sent_to',"{$base_url}mail/EditMessageLight.aspx?n=",'GET',false);
				$this->debugRequest();
				$this->stopPlugin();
				return false;
				}

		$urlContacts="{$base_url}/mail/ContactList.aspx".$this->getElementString($res,'ContactList.aspx','"');
		$res=$this->get($urlContacts);
		if ($this->checkResponse('get_contacts',$res))
			$this->updateDebugBuffer('get_contacts',"{$urlContacts}",'GET');
		else
			{
			$this->updateDebugBuffer('get_contacts',"{$urlContacts}",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		$res=html_entity_decode(urldecode(str_replace('\x', '%', $res)),ENT_QUOTES, "UTF-8");
		$contacts=array();

		$x = explode('contactData:[',$res);
		$x = explode(']]],',$x[1]);
		foreach($x as  $xx)
		{
			$xxx = str_replace('[','',$xx);
			$xxx = str_replace(']','',$xxx);
			$xxx = explode(',',$xxx);
			if(count($xxx) == 8)
			{
				for($i=0;$i<count($xxx);$i++){ $xxx[$i] = str_replace("'",'',$xxx[$i]); }
				$f = explode(' ',$xxx[2]);
				$xxx[2] = $f[0];
				$xxx[3] = $f[1];
				if($f[2])
				{
					$xxx[3] = $f[1] . ' ' . $f[2];
				}
				$contacts[$xxx[7]] = array('first_name'=>$xxx[2],'last_name'=>$xxx[3]);
			}
		}
		/*
		if (preg_match_all("#\'\,\[\'(.+)\@(.+)\'#U",$res,$matches))
		{
			print_r($matches);
			foreach($matches[1] as $key=>$value)if (!empty($matches[2][$key])) $contacts["{$value}@{$matches[2][$key]}"]=array("first_name"=>$value,"email_1"=>"{$value}@{$matches[2][$key]}");
		}
		foreach ($contacts as $email=>$name) if (!$this->isEmail($email)) unset($contacts[$email]);
		*/
		return $this->returnContacts($contacts);
		}

	/**
	 * Terminate session
	 *
	 * Terminates the current user's session,
	 * debugs the request and reset's the internal
	 * debudder.
	 *
	 * @return bool TRUE if the session was terminated successfully, FALSE otherwise.
	 */
	public function logout()
		{
		if (!$this->checkSession()) return false;
		if (file_exists($this->getLogoutPath()))
			{
			$url=file_get_contents($this->getLogoutPath());
			$url_logout=$url."mail/logout.aspx";
			$res=$this->get($url_logout,true);
			}
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}

	}
?>