<?php

/*Made by anoop

common functions to use in any page

*/

class checkurlComponent extends Object {

/*------------------------------------------------ Start function-For Checking Correct County Name -----------------------------------------*/
	function chkCountyUrl($st_url,$cnty_url)
		{
			App::import('model','State');
			$this->State=new State();
			App::import('model','County');
			$this->County=new County();
			$st_id = $this->State->find('first',array('fields'=>'State.id','conditions'=>array('State.page_url'=>$st_url,'State.status'=>'yes')));
		    if(count($st_id)>0)
				 {
				  $id=mysql_real_escape_string($st_id['State']['id']);
				  $cnty_url = mysql_real_escape_string($cnty_url);
				  $count = $this->County->find('count',array('conditions'=>array('County.state_id'=>$id,'County.page_url'=>$cnty_url,'County.publish'=>'yes')));
				   	if($count) {
						return 1;
					} else {
					  	return 0;
					}
				  } else {
					return 0;
				}
			}
/*Start function-For Checking Correct City Name-----------------------------------------------------------------*/					 
		 function chkCity($ct_url,$cnt_url,$state_url)
		 {
		 		App::import('model','State');
				$this->State=new State();
				$s_id = $this->State->find('first',array('fields'=>'State.id','conditions'=>array('State.page_url'=>$state_url)));
				$state_id=$s_id['State']['id'];
				
				App::import('model','County');
				$this->county=new County();
				$county_id = $this->County->find('first',array('fields'=>'County.id','conditions'=>array('County.state_id'=>$state_id,'County.page_url'=>$cnt_url)));
				$cnty_id=$county_id['County']['id'];
				 
				App::import('model','City');
				$this->City=new City();
				$count = $this->City->find('count',array('conditions'=>array('City.county_id'=>$cnty_id,'City.page_url'=>$ct_url,'City.publish'=>'yes')));
				if($count){
				  return 1;
				} else {
				  return 0;
				}
		 }
/*Start function-For Checking Correct Company Name Url---------------------------------------------------------------------------------------------------------------*/			
		 function chkCompanyUrl($comp_url)
		 {
		 	App::import('model','AdvertiserProfile');
		 	$this->AdvertiserProfile = new AdvertiserProfile();
			$count = $this->AdvertiserProfile->find('count',array('conditions'=>array('AdvertiserProfile.page_url'=>$comp_url,'AdvertiserProfile.publish'=>'yes')));
			if($count) {
				return 1;
			} else {
				return 0;
			}
		}
//-----------------------------------------------------------------------------------------------------------------------------------------------//					 
	function chkCatSubcatUrl($cat_url,$scat_url,$county_url) {
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory=new CategoriesSubcategory();
		
		App::import('model','CountiesCategoriesSubcategory');
		$this->CountiesCategoriesSubcategory=new CountiesCategoriesSubcategory();
		
		$catsubcat = $this->CategoriesSubcategory->find('first',array('fields'=>'CategoriesSubcategory.id','conditions'=>array('Category.page_url'=>$cat_url,'Category.publish'=>'yes','Subcategory.page_url'=>$scat_url,'Subcategory.publish'=>'yes')));
		
		if(isset($catsubcat['CategoriesSubcategory']['id'])) {
			$id = $catsubcat['CategoriesSubcategory']['id'];
			$count = $this->CountiesCategoriesSubcategory->find('count',array('conditions'=>array('County.page_url'=>$county_url,'County.publish'=>'yes','CountiesCategoriesSubcategory.categories_subcategory_id'=>$id)));
			if($count) {
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
/*End---------------------------------------------------------------------------------------------------------------------------------------------------------------*/

/*Start function-For Checking Correct-------------------------------------------------------------------------------------------------------------------*/					
		function validUrl($params)
    	{
		if($params['action']=='display'){
		return 1;
		
		}
		             if(count($params['pass'])==0 && count($params['pass'])==1)
						{
						return 0;
						}
						else if(count($params['pass'])==2)
						{
						//return $this->chkCountyUrl($params['pass'][0],$params['pass'][1]);
						if($this->chkCountyUrl($params['pass'][0],$params['pass'][1])!=0)
						return 1;
						}
						else if(count($params['pass'])==3 )
						{
						
						$result=$this->chkCountyUrl($params['pass'][0],$params['pass'][1]);
						if($result==0)
						{
						 return 0;
						}
						else
						{
					if($params['pass'][2]=='refer_friend' || $params['pass'][2]=='dailydiscount' || $params['pass'][2]=='buyDiscount' || $params['pass'][2]=='archivediscount' || $params['pass'][2]=='dailydeal' || $params['pass'][2]=='profile' || $params['pass'][2]=='offer'  || $params['pass'][2]=='feedback' || $params['pass'][2]=='consumer_feedback' || $params['pass'][2]=='account' || $params['pass'][2]=='discount' || $params['pass'][2]=='Consumer' || $params['pass'][2]=='refer_business' || $params['pass'][2]=='order_history' || $params['pass'][2]=='bucks' || $params['pass'][2]=='spend' || $params['pass'][2]=='spendBucks' || $params['pass'][2]=='saveUserData' || $params['pass'][2]=='info' || $params['pass'][2]=='contest_history' || $params['pass'][2]=='discount_history' || $params['pass'][2]=='fundraiser_history' || $params['pass'][2]=='discount_reedem' || $params['pass'][2]=='login' || $params['pass'][2]=='consumer_login' || $params['pass'][2]=='advertiserlogin' || $params['pass'][2]=='signup' || $params['pass'][2]=='consumer_signup' || $params['pass'][2]=='advertiser_signup' || $params['pass'][2]=='contestlogin' || $params['pass'][2]=='contest' || $params['pass'][2]=='logout' || $params['pass'][2]=='deal' || $params['pass'][2]=='deallogin' || $params['pass'][2]=='buydiscount' || $params['pass'][2]=='discountlogin' || $params['pass'][2]=='discount_reedem_voucher' || $params['pass'][2]=='advertiser_forgot_password' || $params['pass'][2]=='consumer_forgot_password' || $params['pass'][2]=='consumer_category_select' || $params['pass'][2]=='category_search' || $params['pass'][2]=='business_search' || $params['pass'][2]=='advertiser' || $params['pass'][2]=='change_category')
							{
								return 1;
							} else {
								$page_url=$params['pass'][2];
								App::import('model','Article');
								$this->art=new Article();
								$desc=$this->art->query("select * from articles where page_url='$page_url' and published='yes'");
								if(count($desc)==0)
								{
								return 0;
								}
								else
								{
								return 1;
								}
							}
						}		
						}
						else if(count($params['pass'])==4)
    					{
						
						$result=$this->chkCountyUrl($params['pass'][0],$params['pass'][1]);
						
								if($result==0)
								{
								return $result;
								}
								////////edit start/////////////
								
								elseif($params['pass'][2]=='business')
								{
									return 1;
								}
								else
								{
								$result=$this->chkCatSubcatUrl($params['pass'][2],$params['pass'][3],$params['pass'][1]);
								return $result;
								}
	    				}
						else if(count($params['pass'])==5)
    					{
						  $result=$this->chkCountyUrl($params['pass'][0],$params['pass'][1]);
					      if($result==0)
							{
							return $result;
							}
								////////edit start/////////////
								
								elseif($params['pass'][2]=='business' && ($this->chkCompanyUrl($params['pass'][4])!=0))
								{
									return 1;
								}
								elseif($params['pass'][4]=='topten_business')
								{
										return 1;
								}								
								elseif($params['pass'][2]!='business' && ($this->chkCatSubcatUrl($params['pass'][2],$params['pass'][3],$params['pass'][1])!=0) && ($this->chkCompanyUrl($params['pass'][4])!=0))
								{
									return 1;
								}
								
								/////////edit end//////////////
						  else
							{
									$result=$this->chkCity($params['pass'][2],$params['pass'][1],$params['pass'][0]);
									if($result==0)
									{
									return 0;
									}
									else
									{
									$result=$this->chkCatSubcatUrl($params['pass'][3],$params['pass'][4],$params['pass'][1]);
									return $result;
									}
							}	
	    				
						}
					   else if(count($params['pass'])==6)
    					{
						  $result=$this->chkCountyUrl($params['pass'][0],$params['pass'][1]);
						  
						   if($result==0)
							{
							return $result;
							}
						  else
							{
									$result=$this->chkCity($params['pass'][2],$params['pass'][1],$params['pass'][0]);
								
									if($result==0)
									{
										return 0;
									}
									else
									{
										$result=$this->chkCatSubcatUrl($params['pass'][3],$params['pass'][4],$params['pass'][1]);
										
										if($result==0)
										{
											return 0;
										}
										elseif($params['pass'][5]=='topten_business')
										{
											return 1;
										}
										else
										{
											$result=$this->chkCompanyUrl($params['pass'][5]);
											return $result;
										}
									}
								}
							}
						}
/*End----------------------------------------------------------------------------------------------------------------------------------------------------------*/
/*Start function-For Checking Static Page Url---------------------------------------------------------------------------------------------------------------*/			
		 function chkStaticPageUrl($page_url)
		 {
		 	App::import('model','Article');
			$this->Article=new Article();
			$count = $this->Article->find('count',array('conditions'=>array('Article.page_url'=>$page_url,'Article.published'=>'yes')));
			if($count)
			{
				return 1;
			}
			else
			{
				return 0;
			}
		 }	
}
?>