<?php 
/*
   Coder: Surbhit
   Date  : 08 Dec 2010
*/ 

class WorkOrdersController  extends AppController { 
      var $name = 'WorkOrders';
	  
     var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator'); 
     var $components = array('Auth','common','Session','Cookie','RequestHandler');  //component to check authentication . this component file is exists in app/controllers/components
      var $layout = 'admin'; //this is the layout for admin panel
	  
	  function index()
	  {
		//get all my user permission
	  	$myUserGroupPermissions=$this->common->getEmailPermissions($this->Session->read('Auth.Admin.user_group_id'));
			
		$myUserGroupPermissionsArr=array_filter(explode(',',$myUserGroupPermissions['permissions']));
		//pr($myUserGroupPermissions);	
	  	$this->set('alluser',$this->common->getadminusers());
		$this->set('SelsePersons',$this->common->getAllSelsePerson(5));
		$this->set('salse_id','');

		  $workorder='';
		  $condition='';
		  $condition[]= 'WorkOrder.archive != "yes"';
		  
		  $this->set('search_text','Subject');
		  $this->set('s_date','');
		  $this->set('e_date','');
			  
        App::import('model', 'Admin');
	    $this->Admin = new Admin;
		
		if((!empty($myUserGroupPermissionsArr) && in_array(3,$myUserGroupPermissionsArr)) || (!empty($myUserGroupPermissions) && ($myUserGroupPermissions['new_order']==1 || $myUserGroupPermissions['new_contract']==1 || $myUserGroupPermissions['update_contract']==1 || $myUserGroupPermissions['new_saving_offer']==1 || $myUserGroupPermissions['new_vip_offer']==1 || $myUserGroupPermissions['new_discount']==1 || $myUserGroupPermissions['new_deal']==1 || $myUserGroupPermissions['gift_certificate']==1 || $myUserGroupPermissions['advertiser_feedback']==1 || $myUserGroupPermissions['consumer_feedback']==1 || $myUserGroupPermissions['edit_vip_offer']==1 || $myUserGroupPermissions['edit_saving_offer']==1 || $myUserGroupPermissions['id']==1 || $myUserGroupPermissions['reffered_business']==1)))
		{
		$loginDetail = $this->Auth->user();     

			/*$allData = $this->WorkOrder->query("select * from advertiser_orders,advertiser_profiles,work_orders where advertiser_orders.id = advertiser_profiles.order_id and advertiser_orders.id = work_orders.advertiser_order_id");
*/

			  /* we */
			  /*if($loginDetail['Admin']['user_group_id']==1 or  $loginDetail['Admin']['user_group_id']==4)
			  {
			  	$this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'WorkOrder.id' => 'desc' ),'conditions'=>array('WorkOrder.sent_to'=>0,'WorkOrder.archive !='=>'yes'));
			  }else{
			  	$this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'WorkOrder.id' => 'desc'),'conditions'=>array('WorkOrder.sent_to'=>$loginDetail['Admin']['id'],'WorkOrder.archive !='=>'yes'));
			  }*/
			  
			  /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		// If subject is set	 
			 
		if(($this->data['WorkOrder']['search_text'] && $this->data['WorkOrder']['search_text'] !='Subject') ||  (isset($this->params['named']['search_text']) && $this->params['named']['search_text'] !='Subject'))

		 {
		if(isset($this->params['named']['search_text']))
		{
		    $condition['WorkOrder.subject LIKE'] = '%' . str_replace("%20"," ",$this->params['named']['search_text']). '%';
		}
		else
		{
		 $condition['WorkOrder.subject LIKE'] = '%' .$this->data['WorkOrder']['search_text']. '%';
		 }
		(empty($this->params['named'])) ? $this->set('search_text', $this->data['WorkOrder']['search_text']) :$this->set('search_text', $this->params['named']['search_text']);
		 }

		// If only start date is set
	
	if(($this->data['WorkOrder']['s_date'] && $this->data['WorkOrder']['s_date'] !='') ||  (isset($this->params['named']['s_date']) && $this->params['named']['s_date'] !=''))
		
		 {
				if($this->data['WorkOrder']['s_date'])
				{
						$s_date		= $this->data['WorkOrder']['s_date'];
						$start_date	= explode('/',$s_date);
						$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
						$this->data['WorkOrder']['s_date']=$start_date;
									
					if(isset($this->params['named']['s_date']))
					{
						($condition['WorkOrder.created >='] = $this->params['named']['s_date']) and ($condition['WorkOrder.created <'] = ($this->params['named']['s_date']+86400));				//( 24 hours = 86400 sec.[timestamp of 1 day])
					}
					else
					{
					 ($condition['WorkOrder.created >='] = $this->data['WorkOrder']['s_date']) and ($condition['WorkOrder.created <'] = ($this->data['WorkOrder']['s_date']+86400));		//( 24 hours = 86400 sec.[timestamp of 1 day])
					 }
			 (empty($this->params['named'])) ? $this->set('s_date', $this->data['WorkOrder']['s_date']) :$this->set('s_date',$this->params['named']['s_date']) ; 
				}
		 }		 
		 //if only end date is set	
		if(($this->data['WorkOrder']['e_date'] && $this->data['WorkOrder']['e_date'] !='') ||  (isset($this->params['named']['e_date']) && $this->params['named']['e_date'] !=''))		
		 {
		 	 	if($this->data['WorkOrder']['e_date'])
				{
					$e_date		= $this->data['WorkOrder']['e_date'];
					$end_date	= explode('/',$e_date);
					$end_date = mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					$this->data['WorkOrder']['e_date']=$end_date;
								
					if(isset($this->params['named']['e_date']))
					{
						($condition['WorkOrder.created >='] = $this->params['named']['e_date']) and ($condition['WorkOrder.created <'] = ($this->params['named']['e_date']+86400));			//( 24 hours = 86400 sec.[timestamp of 1 day])
					}
					else
					{
					 	($condition['WorkOrder.created >='] = $this->data['WorkOrder']['e_date']) and ($condition['WorkOrder.created <'] = ($this->data['WorkOrder']['e_date']+86400));	//( 24 hours = 86400 sec.[timestamp of 1 day])
					 }
				  (empty($this->params['named'])) ? $this->set('e_date', $this->data['WorkOrder']['e_date']) :$this->set('e_date', $this->params['named']['e_date']) ; 			
				  }
		 }
	  
	  //if Both start and end dates are set 
		 
	if(($this->data['WorkOrder']['s_date'] && $this->data['WorkOrder']['s_date'] !='')and ($this->data['WorkOrder']['e_date'] && $this->data['WorkOrder']['e_date'] !=''))
		{								
				if(isset($this->params['named']['s_date']) && isset($this->params['named']['e_date']))
				{
					($condition['WorkOrder.created >='] = $this->params['named']['s_date']) and ($condition['WorkOrder.created <'] = ($this->params['named']['e_date']+86400));
				}
				else
				{
				 ($condition['WorkOrder.created >='] = $this->data['WorkOrder']['s_date']) and ($condition['WorkOrder.created <'] = ($this->data['WorkOrder']['e_date']+86400));
				 }
			(empty($this->params['named'])) ? $this->set('s_date', $this->data['WorkOrder']['s_date']) :$this->set('s_date',$this->params['named']['s_date']) ; 
			(empty($this->params['named'])) ? $this->set('e_date', $this->data['WorkOrder']['e_date']) :$this->set('e_date', $this->params['named']['e_date']);
	
		}
		//if all fields are set (subject, start and end dates)		 
	if(($this->data['WorkOrder']['s_date'] && $this->data['WorkOrder']['s_date'] !='')and ($this->data['WorkOrder']['e_date'] && $this->data['WorkOrder']['e_date'] !='') && (isset($this->data['WorkOrder']['search_text']) && $this->data['WorkOrder']['search_text'] !='Subject'))
	{								
		if(isset($this->params['named']['s_date']) && isset($this->params['named']['e_date']) && isset($this->params['named']['search_text']))
				{
					$condition=array('WorkOrder.created >=' => $this->params['named']['s_date'],'WorkOrder.created <' => $this->params['named']['e_date']+86400,'WorkOrder.subject LIKE'=> '%' . str_replace("%20"," ",$this->params['named']['search_text']). '%');
				}
				else
				{
				 $condition=array('WorkOrder.created >=' => $this->data['WorkOrder']['s_date'],'WorkOrder.created <' => $this->data['WorkOrder']['e_date']+86400,'WorkOrder.subject LIKE'=>'%' .$this->data['WorkOrder']['search_text']. '%');
				 }
			(empty($this->params['named'])) ? $this->set('search_text', $this->data['WorkOrder']['search_text']) :$this->set('search_text', $this->params['named']['search_text']) ; 	 
			(empty($this->params['named'])) ? $this->set('s_date', $this->data['WorkOrder']['s_date']) :$this->set('s_date',$this->params['named']['s_date']) ; 
			(empty($this->params['named'])) ? $this->set('e_date', $this->data['WorkOrder']['e_date']) :$this->set('e_date', $this->params['named']['e_date']);		
	
	
	}
			
		// If Salse person is set	 
			 
		if((isset($this->data['WorkOrder']['salse_id']) && $this->data['WorkOrder']['salse_id'] !='') ||  (isset($this->params['named']['salse_id']) && $this->params['named']['salse_id'] !=''))
		
		 {
		if(isset($this->params['named']['salse_id']))
		{
		    $condition['WorkOrder.salseperson_id'] = $this->params['named']['salse_id'];
		}
		else
		{
		 $condition['WorkOrder.salseperson_id'] = $this->data['WorkOrder']['salse_id'];
		 }
		(empty($this->params['named'])) ? $this->set('salse_id', $this->data['WorkOrder']['salse_id']) :$this->set('salse_id', $this->params['named']['salse_id']) ; 
		 }		
		 /*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/

			 
			if(!empty($this->params['named']))
			{ 
					 
					 //if only subject is set
					 if((isset($this->params['named']['search_text'] ) && $this->params['named']['search_text']!='Subject') && !isset($this->params['named']['s_date'])&& !isset($this->params['named']['e_date'])){
					 
						 $this->set('search_text', $this->params['named']['search_text']); 
						 $condition =   array('WorkOrder.subject LIKE' => '%' . $this->params['named']['search_text'] . '%');
					 }
					 
					 // if only start date is set
					 if((isset($this->params['named']['s_date'] ) && $this->params['named']['s_date']!='') && !isset($this->params['named']['search_text'])){
					 
						$this->set('s_date',$this->params['named']['s_date']); 
						$condition =   array('WorkOrder.created >=' => $this->params['named']['s_date'],'WorkOrder.created <' => $this->params['named']['s_date']+86400);   					//( 24 hours = 86400 sec.[timestamp of 1 day])
					 }
					 
					 // if only end date is set
					 if((isset($this->params['named']['e_date'] ) && $this->params['named']['e_date']!='') && !isset($this->params['named']['search_text'])){
						 $this->set('e_date',$this->params['named']['e_date']); 
						$condition =   array('WorkOrder.created <' => $this->params['named']['e_date']+86400); //( 24 hours = 86400 sec.[timestamp of 1 day]) 
					 }
					 
					 // if both start date and end date are set and subject is set
					  if((isset($this->params['named']['s_date'] ) && $this->params['named']['s_date']!='') && !isset($this->params['named']['search_text']) && (isset($this->params['named']['e_date'] ) && $this->params['named']['e_date']!=''))
					  {
							$this->set('s_date',$this->params['named']['s_date']); 
							$this->set('e_date',$this->params['named']['e_date']); 
							$condition =   array('WorkOrder.created >=' => $this->params['named']['s_date'],'WorkOrder.created <' => $this->params['named']['s_date']+86400,'WorkOrder.created <' => $this->params['named']['e_date']+86400);  //( 24 hours = 86400 sec.[timestamp of 1 day])  
					  }
					  
					  
					  // if all fields  are set(subject, start date and end date)
					   if((isset($this->params['named']['s_date'] ) && $this->params['named']['s_date']!='') && (isset($this->params['named']['search_text']) && $this->params['named']['search_text']!='Subject') && (isset($this->params['named']['e_date'] ) && $this->params['named']['e_date']!=''))
					  {
					  		 $this->set('search_text', $this->params['named']['search_text']); 
							$this->set('s_date',$this->params['named']['s_date']);
							$this->set('e_date',$this->params['named']['e_date']); 
							$condition =   array('WorkOrder.subject LIKE' => '%' .$this->params['named']['search_text'].'%' ,'WorkOrder.created >=' => $this->params['named']['s_date'],'WorkOrder.created <' => $this->params['named']['s_date']+86400,'WorkOrder.created <' => $this->params['named']['e_date']+86400); 
							//( 24 hours = 86400 sec.[timestamp of 1 day])   
					  }
			}
			
		$setFlag=0;
		//pr($myUserGroupPermissions);
		$msg_perm = '';
		//if user have email permission of new order placed notification
		if($myUserGroupPermissions['new_order']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "workorder"';
			
			$setFlag=1;
		}
		//if user have email permission of new contract placed notification
		if($myUserGroupPermissions['new_contract']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "Contract"';
			$msg_perm[] = 'WorkOrder.type = "orderplaced"';
			$setFlag=1;
		}
		//if user have email permission of contract info updates notification
		if($myUserGroupPermissions['update_contract']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "Contract Updated"';
			$setFlag=1;
		}
		//if user have email permission of new saving_offer placed notification
		if($myUserGroupPermissions['new_saving_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "savingworkorder"';
			$setFlag=1;
		}
		//if user have email permission of new vip_offer placed notification
		if($myUserGroupPermissions['new_vip_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "VIP Offer Workorder"';
			$setFlag=1;
		}
		//if user have email permission of new new_discount placed notification
		if($myUserGroupPermissions['new_discount']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "Daily Discount Workorder"';
			$setFlag=1;
		}
		//if user have email permission of new new_deal placed notification
		if($myUserGroupPermissions['new_deal']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "Daily Deal Workorder"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['gift_certificate']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "giftPurchased"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['advertiser_feedback']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "adv_feedback"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['consumer_feedback']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "consumer_feedback"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['proofsent']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "proofsent"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['proofreject']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "proofreject"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['proofaccept']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "proofaccept"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['orderupdated']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "orderupdated"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['imageuploaded']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "imageuploaded"';
			$setFlag=1;
		}
		
		//if user have email permission of update vip offer notification
		if($myUserGroupPermissions['edit_vip_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "VIP Offer Workorder Update"';
			$setFlag=1;
		}	

		//if user have email permission of update saving offer notification
		if($myUserGroupPermissions['edit_saving_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "savingworkorderupdate"';
			$setFlag=1;
		}

		//if user have email permission of delete saving offer notification
		if($myUserGroupPermissions['edit_saving_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "savingworkorderdeleted"';
			$setFlag=1;
		}

		//if user have email permission of add home page daily discount notification
		if($myUserGroupPermissions['new_discount']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "addhomediscountupdate"';
			$setFlag=1;
		}

		//if user have email permission of add home page daily deal notification
		if($myUserGroupPermissions['new_deal']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "addhomedealupdate"';
			$setFlag=1;
		}

		//if user have email permission of add home page banner notification
		if($myUserGroupPermissions['new_saving_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "addhomebannerupdate"';
			$setFlag=1;
		}

		//if user have email permission of advertiser profile publish  notification
		if($myUserGroupPermissions['profilepublish']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "profilepublish"';
			$setFlag=1;
		}			
				
		//if user have email permission of reffered business notification
		if($myUserGroupPermissions['reffered_business']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "reffered_business"';
			$setFlag=1;
		}
		
		if(is_array($msg_perm)) {
			//echo '('.implode(' OR ',$msg_perm).')';
			$condition[] = '('.implode(' OR ',$msg_perm).')';
		}
		
		//pr($condition);
		if($setFlag==0)
		{
			$condition = '';
			$condition['WorkOrder.id'] = null;
 		}
		if($myUserGroupPermissions['group_name']=='Salesperson') {
			if($myUserGroupPermissions['reffered_business']==1)
			{
				$condition['WorkOrder.salseperson_id'] = 0;
			}else{
				$condition['WorkOrder.salseperson_id'] = $this->Session->read('Auth.Admin.id');
			}
		}
		  $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'WorkOrder.id' => 'desc' ),'conditions'=>$condition);
		  $data = $this->paginate('WorkOrder', $condition);
		  $this->set('WorkOrder', $data);
		}
		else
		{
			$this->Session->setFlash('Your user group not have permission to access.');
			$this->set('WorkOrder','');
		}
	 }
//-------------archive index starts-------//	  
	function archiveIndex() {
	  	//get all my user permission
	  		$myUserGroupPermissions=$this->common->getEmailPermissions($this->Session->read('Auth.Admin.user_group_id'));
			
			$myUserGroupPermissionsArr=array_filter(explode(',',$myUserGroupPermissions['permissions']));
			
	 // pr($myUserGroupPermissions);
	  	$this->set('alluser',$this->common->getadminusers());
		$this->set('SelsePersons',$this->common->getAllSelsePerson(5));
		$this->set('salse_id','');

			  $workorder='';
			  $condition='';
			  $condition[]= 'WorkOrder.archive = "yes"';
			  
			  $this->set('search_text','Subject');
			  $this->set('s_date','');
			  $this->set('e_date','');
			  
        App::import('model', 'Admin');
	    $this->Admin = new Admin;
		if((!empty($myUserGroupPermissionsArr) && in_array(3,$myUserGroupPermissionsArr)) || (!empty($myUserGroupPermissions) && ($myUserGroupPermissions['new_order']==1 || $myUserGroupPermissions['new_contract']==1 || $myUserGroupPermissions['update_contract']==1 || $myUserGroupPermissions['new_saving_offer']==1 || $myUserGroupPermissions['new_vip_offer']==1 || $myUserGroupPermissions['new_discount']==1 || $myUserGroupPermissions['new_deal']==1 || $myUserGroupPermissions['gift_certificate']==1 || $myUserGroupPermissions['advertiser_feedback']==1 || $myUserGroupPermissions['consumer_feedback']==1 || $myUserGroupPermissions['edit_vip_offer']==1 || $myUserGroupPermissions['edit_saving_offer']==1 || $myUserGroupPermissions['id']==1 || $myUserGroupPermissions['reffered_business']==1)))
		{
		$loginDetail = $this->Auth->user();     

			/*$allData = $this->WorkOrder->query("select * from advertiser_orders,advertiser_profiles,work_orders where advertiser_orders.id = advertiser_profiles.order_id and advertiser_orders.id = work_orders.advertiser_order_id");
*/

			  /* we */
			  /*if($loginDetail['Admin']['user_group_id']==1 or  $loginDetail['Admin']['user_group_id']==4)
			  {
			  	$this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'WorkOrder.id' => 'desc' ),'conditions'=>array('WorkOrder.sent_to'=>0,'WorkOrder.archive !='=>'yes'));
			  }else{
			  	$this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'WorkOrder.id' => 'desc'),'conditions'=>array('WorkOrder.sent_to'=>$loginDetail['Admin']['id'],'WorkOrder.archive !='=>'yes'));
			  }*/
			  
			  /*--------------------------------------setting diff condition in paginate function according to search criteria-----------------------------*/
		// If subject is set	 
			 
		if(($this->data['WorkOrder']['search_text'] && $this->data['WorkOrder']['search_text'] !='Subject') ||  (isset($this->params['named']['search_text']) && $this->params['named']['search_text'] !='Subject'))

		 {
		if(isset($this->params['named']['search_text']))
		{
		    $condition['WorkOrder.subject LIKE'] = '%' . str_replace("%20"," ",$this->params['named']['search_text']). '%';
		}
		else
		{
		 $condition['WorkOrder.subject LIKE'] = '%' .$this->data['WorkOrder']['search_text']. '%';
		 }
		(empty($this->params['named'])) ? $this->set('search_text', $this->data['WorkOrder']['search_text']) :$this->set('search_text', $this->params['named']['search_text']);
		 }

		// If only start date is set
	
	if(($this->data['WorkOrder']['s_date'] && $this->data['WorkOrder']['s_date'] !='') ||  (isset($this->params['named']['s_date']) && $this->params['named']['s_date'] !=''))
		
		 {
				if($this->data['WorkOrder']['s_date'])
				{
						$s_date		= $this->data['WorkOrder']['s_date'];
						$start_date	= explode('/',$s_date);
						$start_date = mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
						$this->data['WorkOrder']['s_date']=$start_date;
									
					if(isset($this->params['named']['s_date']))
					{
						($condition['WorkOrder.created >='] = $this->params['named']['s_date']) and ($condition['WorkOrder.created <'] = ($this->params['named']['s_date']+86400));				//( 24 hours = 86400 sec.[timestamp of 1 day])
					}
					else
					{
					 ($condition['WorkOrder.created >='] = $this->data['WorkOrder']['s_date']) and ($condition['WorkOrder.created <'] = ($this->data['WorkOrder']['s_date']+86400));		//( 24 hours = 86400 sec.[timestamp of 1 day])
					 }
			 (empty($this->params['named'])) ? $this->set('s_date', $this->data['WorkOrder']['s_date']) :$this->set('s_date',$this->params['named']['s_date']) ; 
				}
		 }		 
		 //if only end date is set	
		if(($this->data['WorkOrder']['e_date'] && $this->data['WorkOrder']['e_date'] !='') ||  (isset($this->params['named']['e_date']) && $this->params['named']['e_date'] !=''))		
		 {
		 	 	if($this->data['WorkOrder']['e_date'])
				{
					$e_date		= $this->data['WorkOrder']['e_date'];
					$end_date	= explode('/',$e_date);
					$end_date = mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
					$this->data['WorkOrder']['e_date']=$end_date;
								
					if(isset($this->params['named']['e_date']))
					{
						($condition['WorkOrder.created >='] = $this->params['named']['e_date']) and ($condition['WorkOrder.created <'] = ($this->params['named']['e_date']+86400));			//( 24 hours = 86400 sec.[timestamp of 1 day])
					}
					else
					{
					 	($condition['WorkOrder.created >='] = $this->data['WorkOrder']['e_date']) and ($condition['WorkOrder.created <'] = ($this->data['WorkOrder']['e_date']+86400));	//( 24 hours = 86400 sec.[timestamp of 1 day])
					 }
				  (empty($this->params['named'])) ? $this->set('e_date', $this->data['WorkOrder']['e_date']) :$this->set('e_date', $this->params['named']['e_date']) ; 			
				  }
		 }
	  
	  //if Both start and end dates are set 
		 
	if(($this->data['WorkOrder']['s_date'] && $this->data['WorkOrder']['s_date'] !='')and ($this->data['WorkOrder']['e_date'] && $this->data['WorkOrder']['e_date'] !=''))
		{								
				if(isset($this->params['named']['s_date']) && isset($this->params['named']['e_date']))
				{
					($condition['WorkOrder.created >='] = $this->params['named']['s_date']) and ($condition['WorkOrder.created <'] = ($this->params['named']['e_date']+86400));
				}
				else
				{
				 ($condition['WorkOrder.created >='] = $this->data['WorkOrder']['s_date']) and ($condition['WorkOrder.created <'] = ($this->data['WorkOrder']['e_date']+86400));
				 }
			(empty($this->params['named'])) ? $this->set('s_date', $this->data['WorkOrder']['s_date']) :$this->set('s_date',$this->params['named']['s_date']) ; 
			(empty($this->params['named'])) ? $this->set('e_date', $this->data['WorkOrder']['e_date']) :$this->set('e_date', $this->params['named']['e_date']);
	
		}
		//if all fields are set (subject, start and end dates)		 
	if(($this->data['WorkOrder']['s_date'] && $this->data['WorkOrder']['s_date'] !='')and ($this->data['WorkOrder']['e_date'] && $this->data['WorkOrder']['e_date'] !='') && (isset($this->data['WorkOrder']['search_text']) && $this->data['WorkOrder']['search_text'] !='Subject'))
	{								
		if(isset($this->params['named']['s_date']) && isset($this->params['named']['e_date']) && isset($this->params['named']['search_text']))
				{
					$condition=array('WorkOrder.created >=' => $this->params['named']['s_date'],'WorkOrder.created <' => $this->params['named']['e_date']+86400,'WorkOrder.subject LIKE'=> '%' . str_replace("%20"," ",$this->params['named']['search_text']). '%');
				}
				else
				{
				 $condition=array('WorkOrder.created >=' => $this->data['WorkOrder']['s_date'],'WorkOrder.created <' => $this->data['WorkOrder']['e_date']+86400,'WorkOrder.subject LIKE'=>'%' .$this->data['WorkOrder']['search_text']. '%');
				 }
			(empty($this->params['named'])) ? $this->set('search_text', $this->data['WorkOrder']['search_text']) :$this->set('search_text', $this->params['named']['search_text']) ; 	 
			(empty($this->params['named'])) ? $this->set('s_date', $this->data['WorkOrder']['s_date']) :$this->set('s_date',$this->params['named']['s_date']) ; 
			(empty($this->params['named'])) ? $this->set('e_date', $this->data['WorkOrder']['e_date']) :$this->set('e_date', $this->params['named']['e_date']);		
	
	
	}
			
		// If Salse person is set	 
			 
		if((isset($this->data['WorkOrder']['salse_id']) && $this->data['WorkOrder']['salse_id'] !='') ||  (isset($this->params['named']['salse_id']) && $this->params['named']['salse_id'] !=''))
		
		 {
		if(isset($this->params['named']['salse_id']))
		{
		    $condition['WorkOrder.salseperson_id'] = $this->params['named']['salse_id'];
		}
		else
		{
		 $condition['WorkOrder.salseperson_id'] = $this->data['WorkOrder']['salse_id'];
		 }
		(empty($this->params['named'])) ? $this->set('salse_id', $this->data['WorkOrder']['salse_id']) :$this->set('salse_id', $this->params['named']['salse_id']) ; 
		 }		
		 /*----------------------------------At the time of sorting Filteration on basis of these fields------------------------------*/

			 
			if(!empty($this->params['named']))
			{ 
					 
					 //if only subject is set
					 if((isset($this->params['named']['search_text'] ) && $this->params['named']['search_text']!='Subject') && !isset($this->params['named']['s_date'])&& !isset($this->params['named']['e_date'])){
					 
						 $this->set('search_text', $this->params['named']['search_text']); 
						 $condition =   array('WorkOrder.subject LIKE' => '%' . $this->params['named']['search_text'] . '%');
					 }
					 
					 // if only start date is set
					 if((isset($this->params['named']['s_date'] ) && $this->params['named']['s_date']!='') && !isset($this->params['named']['search_text'])){
					 
						$this->set('s_date',$this->params['named']['s_date']); 
						$condition =   array('WorkOrder.created >=' => $this->params['named']['s_date'],'WorkOrder.created <' => $this->params['named']['s_date']+86400);   					//( 24 hours = 86400 sec.[timestamp of 1 day])
					 }
					 
					 // if only end date is set
					 if((isset($this->params['named']['e_date'] ) && $this->params['named']['e_date']!='') && !isset($this->params['named']['search_text'])){
						 $this->set('e_date',$this->params['named']['e_date']); 
						$condition =   array('WorkOrder.created <' => $this->params['named']['e_date']+86400); //( 24 hours = 86400 sec.[timestamp of 1 day]) 
					 }
					 
					 // if both start date and end date are set and subject is set
					  if((isset($this->params['named']['s_date'] ) && $this->params['named']['s_date']!='') && !isset($this->params['named']['search_text']) && (isset($this->params['named']['e_date'] ) && $this->params['named']['e_date']!=''))
					  {
							$this->set('s_date',$this->params['named']['s_date']); 
							$this->set('e_date',$this->params['named']['e_date']); 
							$condition =   array('WorkOrder.created >=' => $this->params['named']['s_date'],'WorkOrder.created <' => $this->params['named']['s_date']+86400,'WorkOrder.created <' => $this->params['named']['e_date']+86400);  //( 24 hours = 86400 sec.[timestamp of 1 day])  
					  }
					  
					  
					  // if all fields  are set(subject, start date and end date)
					   if((isset($this->params['named']['s_date'] ) && $this->params['named']['s_date']!='') && (isset($this->params['named']['search_text']) && $this->params['named']['search_text']!='Subject') && (isset($this->params['named']['e_date'] ) && $this->params['named']['e_date']!=''))
					  {
					  		 $this->set('search_text', $this->params['named']['search_text']); 
							$this->set('s_date',$this->params['named']['s_date']);
							$this->set('e_date',$this->params['named']['e_date']); 
							$condition =   array('WorkOrder.subject LIKE' => '%' .$this->params['named']['search_text'].'%' ,'WorkOrder.created >=' => $this->params['named']['s_date'],'WorkOrder.created <' => $this->params['named']['s_date']+86400,'WorkOrder.created <' => $this->params['named']['e_date']+86400); 
							//( 24 hours = 86400 sec.[timestamp of 1 day])   
					  }
			}
			
		$setFlag=0;
		//pr($myUserGroupPermissions);
		$msg_perm = '';
		//if user have email permission of new order placed notification
		if($myUserGroupPermissions['new_order']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "workorder"';
			$setFlag=1;
		}
		//if user have email permission of new contract placed notification
		if($myUserGroupPermissions['new_contract']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "Contract"';
			$msg_perm[] = 'WorkOrder.type = "orderplaced"';
			$setFlag=1;
		}
		//if user have email permission of contract info updates notification
		if($myUserGroupPermissions['update_contract']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "Contract Updated"';
			$setFlag=1;
		}
		//if user have email permission of new saving_offer placed notification
		if($myUserGroupPermissions['new_saving_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "savingworkorder"';
			$setFlag=1;
		}
		//if user have email permission of new vip_offer placed notification
		if($myUserGroupPermissions['new_vip_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "VIP Offer Workorder"';
			$setFlag=1;
		}
		//if user have email permission of new new_discount placed notification
		if($myUserGroupPermissions['new_discount']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "Daily Discount Workorder"';
			$setFlag=1;
		}
		//if user have email permission of new new_deal placed notification
		if($myUserGroupPermissions['new_deal']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "Daily Deal Workorder"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['gift_certificate']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "giftPurchased"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['advertiser_feedback']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "adv_feedback"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['consumer_feedback']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "consumer_feedback"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['proofsent']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "proofsent"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['proofreject']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "proofreject"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['proofaccept']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "proofaccept"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['orderupdated']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "orderupdated"';
			$setFlag=1;
		}
		
		//if user have email permission of new gift_certificate purchased notification
		if($myUserGroupPermissions['imageuploaded']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "imageuploaded"';
			$setFlag=1;
		}
		
		//if user have email permission of update vip offer notification
		if($myUserGroupPermissions['edit_vip_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "VIP Offer Workorder Update"';
			$setFlag=1;
		}	

		//if user have email permission of update saving offer notification
		if($myUserGroupPermissions['edit_saving_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "savingworkorderupdate"';
			$setFlag=1;
		}

		//if user have email permission of delete saving offer notification
		if($myUserGroupPermissions['edit_saving_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "savingworkorderdeleted"';
			$setFlag=1;
		}

		//if user have email permission of add home page daily discount notification
		if($myUserGroupPermissions['new_discount']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "addhomediscountupdate"';
			$setFlag=1;
		}

		//if user have email permission of add home page daily deal notification
		if($myUserGroupPermissions['new_deal']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "addhomedealupdate"';
			$setFlag=1;
		}

		//if user have email permission of add home page banner notification
		if($myUserGroupPermissions['new_saving_offer']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "addhomebannerupdate"';
			$setFlag=1;
		}

		//if user have email permission of advertiser profile publish  notification
		if($myUserGroupPermissions['profilepublish']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "profilepublish"';
			$setFlag=1;
		}										

		//if user have email permission of reffered business notification
		if($myUserGroupPermissions['reffered_business']==1 || $myUserGroupPermissions['id']==1)
		{
			$msg_perm[] = 'WorkOrder.type = "reffered_business"';
			$setFlag=1;
		}
		
		if(is_array($msg_perm)) {
			//echo '('.implode(' OR ',$msg_perm).')';
			$condition[] = '('.implode(' OR ',$msg_perm).')';
		}
		
		//pr($condition);
		if($setFlag==0)
		{
			$condition = '';
			$condition['WorkOrder.id'] = null;
 		}
		if($myUserGroupPermissions['group_name']=='Salesperson') {
			if($myUserGroupPermissions['reffered_business']==1)
			{
				$condition['WorkOrder.salseperson_id'] = 0;
			}else{
				$condition['WorkOrder.salseperson_id'] = $this->Session->read('Auth.Admin.id');
			}
		}
		  $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'WorkOrder.id' => 'desc' ),'conditions'=>$condition);
		  $data = $this->paginate('WorkOrder', $condition);
		  $this->set('WorkOrder', $data);
		}
		else
		{
			$this->Session->setFlash('Your user group not have permission to access.');
			$this->set('WorkOrder','');
		}
	 }
	/////////-------archive index ends---------///////	  
	  function WorkOrderDetail($id=null){
	  
	    $id=base64_decode($id);
	  	
		$this->set('referer',$this->referer());
		
	  	$this->set('workOrder',$this->WorkOrder->workOrderEditDetail($id));
		$this->set('salesEmail',$this->common->getSalesEmail());
		$this->set('adminEmail',$this->common->getAdminEmail());
		$this->set('package_name',$this->common->getAllPackage(2));
		$this->set('package_price',$this->common->getAllPackage(3));
		$this->loadModel('User');
		$this->set('salesperson', $this->User->returnUsersSales());
		$this->WorkOrder->updateReadStatus($id);
	 }
	 function WorkOrderDelete($id=null){
	  	
	  	 $this->WorkOrder->delete($id);
		 $this->Session->setFlash('Message has been deleted from inbox.');
		 $this->redirect(array('action'=>'index'));
	 }
	 function setCss($id)
	 {
			$this->Cookie->delete('css_name');
			if($this->params['pass'][0]=='0'){
			   $this->Cookie->write('css_name','theme',false);
			   $this->redirect(array('action' => $this->params['pass'][1]));
			}else{
			   $this->Cookie->write('css_name','theme'.$this->params['pass'][0],false);
			   $this->redirect(array('action' => $this->params['pass'][1]));
		    }
	 }
	function archive(){
		$i=0;
		$data1 = array('');
		foreach($this->data['WorkOrder'] as $key=>$value) {
				if($value == 0) {
				}else {
					$data1['WorkOrder']['id']		= $value;
					$data1['WorkOrder']['archive']  = 'yes';
					$this->WorkOrder->save($data1);
				}	
		}
		$this->redirect($this->referer()); 	
	}	
	
	function beforeFilter() {

     		$this->Auth->fields = array(
            	'username' => 'username', 
            	'password' => 'password'
            );
			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
   	}
	/* This function is setting all info about current Admins in 
	currentAdmin array so we can use it anywhere lie name id etc.
	*/
	function beforeRender(){
		$this->set('currentAdmin', $this->Auth->user());
		$this->set('cssName',$this->Cookie->read('css_name'));
        $this->set('groupDetail',$this->common->adminDetails());
		$this->set('common',$this->common);
		//$this->Ssl->force();
	}
	function hellotest() {
		$this->find->all('first',array('fields'=>array('find.id'),'conditions'=>array('')));
	}
}
?>