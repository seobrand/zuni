<?php 
/*
   Coder: Vijender
   Date  : 01 Dec 2010
*/

class CountiesController extends AppController { 
      var $name = 'Counties';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');  
	  var $layout = 'front'; //variable for admin layout
	  var $components = array('common','Cookie','checkurl','Session');
	  function index()
	  {
			
	  }
	  function display()
	  { 
	  
	              //echo $this->Session->id();
	              
	             if(count($this->params['pass'])=='0')
				  {
					$this->error();
					return ;
				  }
				  if(count($this->params['pass'])=='1')
				  {
				   $this->error();
				   $this->render('error');
				   return ;
				  } 
				  if(count($this->params['pass'])=='2' && $this->checkurl->validUrl($this->params)=='1')
				  {
				  $this->set('popularCat',$this->common->popularCat($this->params)); 
				  $this->set('popularCity',$this->common->popularCity($this->params)); 
				   /*-----------------------------------------------------------------------------------------------------------------------------------------*/
				                                            //Start Reporting For County
				  /*------------------------------------------------------------------------------------------------------------------------------------------*/
				   App::import('model','State');
				   $this->state=new State();
				   $state_url=$this->params['pass'][0];
				   $state=$this->state->query("select id from states where page_url='$state_url'");
				   $st_id=$state[0]['states']['id'];				   
				   App::import('model','County');
				   $this->county=new County();
				   $county_url=$this->params['pass'][1];
				   $county=$this->county->query("select id from counties where page_url='$county_url'");
				   $county_id=$county[0]['counties']['id'];
				   App::import('model','Report');
				   $this->report=new Report();
				   $timestamp=$this->common->getTimeStampReport();
				   $this->report->query("insert into reports(`state`,`county`,`date`,`type`) values('$st_id','$county_id','$timestamp',1)");
				   /*-------------------------------------------------End-----------------------------------------------------------------------------------*/
				  
				  
				   $this->set('getTitle',$this->common->getTitle($this->params));
				   $this->set('getKeyword',$this->common->getKeyWord($this->params));
				 
				   
				  }
				  
				  if(count($this->params['pass'])=='3' && $this->checkurl->validUrl($this->params)==1 && $this->params['pass'][2]!='coupon')
				  {
				    $this->set('popularCat',$this->common->popularCat($this->params)); 
				    $this->set('popularCity',$this->common->popularCity($this->params)); 
				  
				    $this->set('combo',$this->common->getCombBox());  
				    $this->set('getPageTitle',$this->common->getPageTitle($this->params));
				     $this->set('getPageKeyword',$this->common->getPageKeyWord($this->params));	
					 $this->set('getDescription',$this->common->getDescription($this->params));
					$this->pageManager();
					return ;
				  }
				  
				  if(count($this->params['pass'])=='3' && $this->params['pass'][2]=='coupon')
				  {
				  pr($this->params);die;
				  }
				 
	    	    if(count($this->params['pass'])=='4' && $this->checkurl->validUrl($this->params)=='1')
				  {
				  $this->set('popularCat',$this->common->popularCat($this->params)); 
				  $this->set('popularCity',$this->common->popularCity($this->params)); 
				  App::import('Model','County');
				  $this->county=new County();
				  $page_url=$this->params['pass'][1];
				  $county=$this->county->find('all',array('conditions' => array('page_url'=> $page_url)));
				  $county_image=$county[0]['County']['logo'];
				  $this->set('countyImage',$county_image);
				 $address=str_replace('-',' ',$this->params['pass'][1]).','.str_replace('-','',$this->params['pass'][0]);
			     //echo $address;die;
			    $this->set('mapAddress',$address);
				  
				  /*-----------------------------------------------------------------------------------------------------------------------------------------*/
				                                            //Start Reporting For Category 
				  /*------------------------------------------------------------------------------------------------------------------------------------------*/
				   App::import('model','State');
				   $this->state=new State();
				   $state_url=$this->params['pass'][0];
				   $state=$this->state->query("select id from states where page_url='$state_url'");
				   $st_id=$state[0]['states']['id'];
				   				   
				   App::import('model','County');
				   $this->county=new County();
				   $county_url=$this->params['pass'][1];
				   $county=$this->county->query("select id from counties where page_url='$county_url'");
				   $county_id=$county[0]['counties']['id'];
				   
				   App::import('model','Category');
				   $this->cat=new Category();
				   $cat_url=$this->params['pass'][2];
				   $category=$this->cat->query("select id from categories where page_url='$cat_url'");
				   $cat_id=$category[0]['categories']['id'];
				   
				   App::import('model','Subcategory');
				   $this->scat=new Subcategory();
				   $scat_url=$this->params['pass'][3];
				   $subcategory=$this->scat->query("select id from subcategories where page_url='$scat_url'");
				   $scat_id=$subcategory[0]['subcategories']['id'];
				   
				   App::import('model','Report');
				   $this->report=new Report();
				   $timestamp=$this->common->getTimeStampReport();
$this->report->query("insert into reports(`state`,`county`,`category`,`subcategory`,`date`,`type`) values('$st_id','$county_id','$cat_id','$scat_id','$timestamp',2)");
				   /*-------------------------------------------------End-----------------------------------------------------------------------------------*/
				   
				   
				   $this->set('getTitle',$this->common->getTitle($this->params));
				   $this->set('getKeyword',$this->common->getKeyWord($this->params));
				  
				   $this->set('getDescription',$this->common->getDescription($this->params));
				    $this->displayCity();
				   $this->render('display_city');
				   return ;
				  }	  
				
				if(count($this->params['pass'])=='5' && $this->checkurl->validUrl($this->params)=='1')
				 {
				 $this->set('popularCat',$this->common->popularCat($this->params)); 
				 $this->set('popularCity',$this->common->popularCity($this->params)); 
								  
			   $address=str_replace('-',' ',$this->params['pass'][2]).' '.str_replace('-','',$this->params['pass'][1]).','.str_replace('-','',$this->params['pass'][0]);
			//echo $address;die;
			$this->set('mapAddress',$address);
				   /*-----------------------------------------------------------------------------------------------------------------------------------------*/
				                                            //Start Reporting For City 
				  /*------------------------------------------------------------------------------------------------------------------------------------------*/
				   App::import('model','State');
				   $this->state=new State();
				   $state_url=$this->params['pass'][0];
				   $state=$this->state->query("select id from states where page_url='$state_url'");
				   $st_id=$state[0]['states']['id'];
				   				   
				   App::import('model','County');
				   $this->county=new County();
				   $county_url=$this->params['pass'][1];
				   $county=$this->county->query("select id from counties where page_url='$county_url'");
				   $county_id=$county[0]['counties']['id'];
				   
				   App::import('model','City');
				   $this->city=new City();
				   $city_url=$this->params['pass'][2];
				   $city=$this->city->query("select id from cities where page_url='$city_url'");
				   $city_id=$city[0]['cities']['id'];
				   
				   App::import('model','Category');
				   $this->cat=new Category();
				   $cat_url=$this->params['pass'][3];
				   $category=$this->cat->query("select id from categories where page_url='$cat_url'");
				   $cat_id=$category[0]['categories']['id'];
				   
				   App::import('model','Subcategory');
				   $this->scat=new Subcategory();
				   $scat_url=$this->params['pass'][4];
				   $subcategory=$this->scat->query("select id from subcategories where page_url='$scat_url'");
				   $scat_id=$subcategory[0]['subcategories']['id'];
				   
				   App::import('model','Report');
				   $this->report=new Report();
				   $timestamp=$this->common->getTimeStampReport();
				   $this->report->query("insert into reports(`state`,`county`,`city`,`category`,`subcategory`,`date`,`type`) values('$st_id','$county_id','$city_id','$cat_id','$scat_id','$timestamp',3)");
				   /*-------------------------------------------------End-----------------------------------------------------------------------------------*/
				   
				   
				   
				   
				   $this->set('getTitle',$this->common->getTitle($this->params));
				   $this->set('getDescription',$this->common->getDescription($this->params));
				   $this->set('getKeyword',$this->common->getKeyWord($this->params));
				   $this->set('subcat_name',$this->common->getCatName($this->params['pass'][4]));
					$this->set('scombo',$this->common->getCombBox($this->common->getCatName($this->params['pass'][4])));  
				   $this->set('city_name',$this->common->getParticularCity($this->params));
				   $this->set('ten_cupon',$this->common->getTenBanner($this->params));
				   $coupon_details=$this->common->getTenBanner($this->params);
						
					if(isset($coupon_details[0]))
					{
					$i=0;
					foreach($coupon_details as $details)
						{
						$coupon_company[$i]=$coupon_details[$i]['ap']['company_name'];
						$coupon_company_phone[$i]=$coupon_details[$i]['ap']['phoneno'];
						$coupon_address[$i]=$coupon_details[$i]['ap']['address'].','.$coupon_details[$i]['ct']['cityname'].' '.$this->params['pass'][1].','.$this->params['pass'][0];
						$i++;
						}
					   $this->set('coupon_company',$coupon_company);
					   $this->set('coupon_address',$coupon_address);
					   $this->set('coupon_company_phone',$coupon_company_phone);
					}   
				   $this->set('getDivCity',$this->common->getAllCatSubcat($class="sub-box",$this->params));
                   $this->pageCity();
				   $this->render('page_city');
				   return ;
					  
				 }	 
			
			    if(count($this->params['pass'])==6 && $this->checkurl->validUrl($this->params)=='1')
				  {
				  $this->set('popularCat',$this->common->popularCat($this->params)); 
				  $this->set('popularCity',$this->common->popularCity($this->params)); 
				  
				   /*-----------------------------------------------------------------------------------------------------------------------------------------*/
				                                            //Start Reporting For City 
				  /*------------------------------------------------------------------------------------------------------------------------------------------*/
				   App::import('model','State');
				   $this->state=new State();
				   $state_url=$this->params['pass'][0];
				   $state=$this->state->query("select id from states where page_url='$state_url'");
				   $st_id=$state[0]['states']['id'];
				   				   
				   App::import('model','County');
				   $this->county=new County();
				   $county_url=$this->params['pass'][1];
				   $county=$this->county->query("select id from counties where page_url='$county_url'");
				   $county_id=$county[0]['counties']['id'];
				   
				   App::import('model','City');
				   $this->city=new City();
				   $city_url=$this->params['pass'][2];
				   $city=$this->city->query("select id from cities where page_url='$city_url'");
				   $city_id=$city[0]['cities']['id'];
				   
				   App::import('model','Category');
				   $this->cat=new Category();
				   $cat_url=$this->params['pass'][3];
				   $category=$this->cat->query("select id from categories where page_url='$cat_url'");
				   $cat_id=$category[0]['categories']['id'];
				   
				   App::import('model','Subcategory');
				   $this->scat=new Subcategory();
				   $scat_url=$this->params['pass'][4];
				   $subcategory=$this->scat->query("select id from subcategories where page_url='$scat_url'");
				   $scat_id=$subcategory[0]['subcategories']['id'];
				   
				   App::import('model','AdvertiserProfile');
				   $this->ad_pro=new AdvertiserProfile();
				   $company_url=$this->params['pass'][5];
				   $company=$this->ad_pro->query("select id from advertiser_profiles where page_url='$company_url'");
				   $company_id=$company[0]['advertiser_profiles']['id'];
				   
				   App::import('model','Report');
				   $this->report=new Report();
				   $timestamp=$this->common->getTimeStampReport();
$this->report->query("insert into reports(`state`,`county`,`city`,`category`,`subcategory`,`company`,`date`,`type`) values('$st_id','$county_id','$city_id','$cat_id','$scat_id','$company_id','$timestamp',4)");
				   /*-------------------------------------------------End-----------------------------------------------------------------------------------*/

					 $this->set('getTitle',$this->common->getTitle($this->params));
				     $this->set('subcat_name',$this->common->getCatName($this->params['pass'][4]));
					 $this->set('scombo',$this->common->getCombBox($this->common->getCatName($this->params['pass'][4])));  
		             $this->set('city_name',$this->common->getParticularCity($this->params));	
					 $this->displayCoupon();
	    			 $this->render('display_coupon');
		    		 return ;
				  }
		      if(count($this->params['pass'])==7 )
					{
					
					
					  if(count($this->common->chkCity($this->params))=='0')
						{
						$this->error();
						$this->render('error');
						return ;
						}
						if($this->params['pass'][6])
						{
						 $this->set('popularCat',$this->common->popularCat($this->params)); 
				         $this->set('popularCity',$this->common->popularCity($this->params)); 
						     $this->set('getTitle',$this->common->getTitle($this->params));
				             //$this->set('getKeyword',$this->common->getKeyWord($this->params));
						 	 $this->displayCoupon();
							 $this->render('display_coupon');
							 return ;
						}
						
						
					}
			
									
	  }
/*Start Function For Searching Category-------------------------------------------------------------------------------------------------------------------------------*/	  
	  function searchBusiness()
	  {
				  App::import('model','Category');
				  App::import('model','Subcategory');
				  App::import('model','City');
				  $this->cat=new Category();
				  $this->scat=new Subcategory();
				  $this->cty=new City();
				  $ids=$this->params['form']['business'];
				  $id=explode('-',$ids);
				  $cat_id=$id[0];
				  $scat_id=$id[1];
				  $cat_details=$this->cat->query("select categoryname,page_url from categories where id='$cat_id' and publish='yes'");
				  $cat_url=$cat_details[0]['categories']['page_url'];
				  $scat_details=$this->scat->query("select categoryname,page_url from subcategories where id='$scat_id' and publish='yes'");
				  $scat_name=$scat_details[0]['subcategories']['categoryname'];
				  $scat_url=$scat_details[0]['subcategories']['page_url'];
				  if($this->params['form']['city']==0)
				  {
				    $this->redirect(array('controller' => 'state/'.$this->params['data']['County']['State'].'/'.$this->params['data']['County']['County'].'/'.$cat_url.'/'.$scat_url));
				  }
				  else
				  {
				  $cty_id=$this->params['form']['city'];
				  $city_details=$this->cty->query("select cityname,page_url from cities where id='$cty_id'");
				  $city_url=$city_details[0]['cities']['page_url'];
				  $city_name=$city_details[0]['cities']['cityname'];
				   $this->redirect(array('controller' => 'state/'.$this->params['data']['County']['State'].'/'.$this->params['data']['County']['County'].'/'.$city_url.'/'.$cat_url.'/'.$scat_url));
				  }
	 }
/*End Function------------------------------------------------------------------------------------------------------------------------------------------------------*/	 

/*Start Function For Searching Company-------------------------------------------------------------------------------------------------------------------------------*/	  
	  function searchCompany()
	  {
	 // pr($this->params);die;
		$company_name=$this->params['form']['company'];	
		$this->loadModel('AdvertiserProfile');
		$company_details=$this->AdvertiserProfile->query("select ad.*,city.cityname,county.countyname,state.statename from advertiser_profiles as ad,cities as city,counties as county,states as state where  city.id=ad.city and county.id=ad.county and state.id=ad.state and ad.company_name LIKE '$company_name %'");
		//pr($company_details);die; 
		$this->redirect(array('controller' => 'state/'.$this->params['data']['County']['State'].'/'.$this->params['data']['County']['County'].'/coupon'));
	 }
/*End Function------------------------------------------------------------------------------------------------------------------------------------------------------*/		  

	 /*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                                                  /* Start Function For Error Page */
/*------------------------------------------------------------------------------------------------------------------------------------------------------------------*/	
	 
	  function error()
	  {
	         $this->layout="error";
			 return;
	  }
/*End Function---------------------------------------------------------------------------------------------------------------------------------------------------*/	  
	  	 
	/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                                                  /* Start Function For PageManager */
/*------------------------------------------------------------------------------------------------------------------------------------------------------------------*/	
	
	
	  function pageManager()
	  {
	        $url=$this->params['pass'][2];
			$this->art=new Article();
			$desc=$this->art->query("select * from articles where page_url='$url' and published='yes'");
			if(count($desc)>0)
			{
			 $this->set('page_description',$desc); 
			}
			else
			{
			 $this->error();
			  $this->render('error');
			  return ;
			}
			$this->render('page_manager');
			 App::import('model','Article');
			return;
	  }
/*End Function-----------------------------------------------------------------------------------------------------------------------------------------------------*/	

/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                                                  /* Start Function For Display Category Subcategory Page */
/*------------------------------------------------------------------------------------------------------------------------------------------------------------------*/	
	  
	  function displayCity()
	  {
	  	   $url=$this->params['pass'][3];
		   $this->layout="category";
		   App::import('model','Subcategory');
		   $this->scat=new Subcategory();
	       $this->set('scat_name',$this->scat->query("select categoryname from subcategories where page_url='$url'"));
		   $this->set('county_coupon',$this->common->getAllCountyBanner($this->params));
		   $this->set('subcat_name',$this->common->getCatName($this->params['pass'][3]));
		   $this->set('scombo',$this->common->getCombBox($this->common->getCatName($this->params['pass'][3])));  
		   $this->set('city_name',$this->common->getParticularCity($this->params));
	  	   $this->set('getDivCity',$this->common->getAllCatSubcat($class="sub-box",$this->params));
	  }
	  
	  function pageCity()
	  {
	  
	  
	  }
 /*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                                                  /* Start Function For Coupon page */
/*------------------------------------------------------------------------------------------------------------------------------------------------------------------*/	
  
   function displayCoupon()
	  {
	       App::import('model','AdvertiserProfile');
		   $this->ad_pro=new AdvertiserProfile();
		   $ad_details=$this->ad_pro->find('first',array('conditions'=>array('page_url'=>$this->params['pass'][5])));
	  	   $this->set('getDescription',$ad_details['AdvertiserProfile']['description']);
		   $this->set('getKeyword',$ad_details['AdvertiserProfile']['keyword']);
           if(count($this->params['pass'])==7)
		   {
	        $this->set('getOffer',$this->common->getOffer($this->params));
			}
	        
			 App::import('model','State');
			 $this->state=new State();
			 $state_url=$this->params['pass'][0];
			 $state=$this->state->query("select id from states where page_url='$state_url'");
			 $st_id=$state[0]['states']['id'];
			 
		     App::import('model','County');
			 $this->county=new 	County();
		     $county_url=$this->params['pass'][1];
			 $county=$this->county->query("select id from counties where page_url='$county_url'");
			 $county_id=$county[0]['counties']['id'];
			 
			 App::import('model','City');
			 $this->cty=new City();
			 $cty_url=$this->params['pass'][2];
			 $city=$this->cty->query("select id from cities where page_url='$cty_url'");
			 $cty_id=$city[0]['cities']['id'];
			 
			 App::import('model','Category');
			 $this->cat=new Category();
			 $cat_url=$this->params['pass'][3];
			 $category=$this->cat->query("select id from categories where page_url='$cat_url'");
			 $cat_id=$category[0]['categories']['id'];
			 
			 
			 App::import('model','Subcategory');
			 $this->scat=new Subcategory();
			 $scat_url=$this->params['pass'][4];
			 $subcategory=$this->cat->query("select id from subcategories where page_url='$scat_url'");
			 $scat_id=$subcategory[0]['subcategories']['id'];
			 
			 $company_url=$this->params['pass'][5];
			 
			 App::import('model','AdvertiserProfile');
			 $this->ap=new AdvertiserProfile();
		
		$ap_id= $this->ap->query("select id from advertiser_profiles where  state='$st_id'and county='$county_id' and city='$cty_id' and category LIKE Concat('%,',$cat_id,',%') and subcategory LIKE Concat('%,',$scat_id,',%') and page_url='$company_url' and publish='yes'");
		$ap_id=$ap_id[0]['advertiser_profiles']['id'];
		
		  App::import('model','Offer');
		  $this->off=new Offer();
		    $this->set('offer_detail',$this->off->query("select * from offers where advertiser_profile_id='$ap_id' and status='yes'"));
			 
	         App::import('model','AdvertiserProfile');
		     $this->ad_pro=new AdvertiserProfile();
	         $page_url=$this->params['pass']['5'];
             //pr($this->params);die;	  
		     $this->set('company_details',$this->common->getCompanyDetails($this->params));		  
		     $this->set('getDivCity',$this->common->getAllCatSubcat($class="coupon",$this->params));
			 if(count($this->params['pass'])==7 && $this->params['pass'][6]=='video')
			 {
			 $this->set('getVideo',$this->common->getVideo($this->params));
			 }
		    if(count($this->params['pass'])==7 && $this->params['pass'][6]=='picture')
			 {
			  
			  App::import('model','Image');
			  $this->img=new Image();
			  $image=$this->ad_pro->query("select id from advertiser_profiles where page_url='$page_url' and publish='yes'");
			  $id=$image[0]['advertiser_profiles']['id'];
			  //echo "select * from images where advertiser_profile_id='$id' and status='yes'";die;
			   $img=$this->img->query("select title,description,image_thumb,image_big,link from images where advertiser_profile_id='$id' and status='yes'");
			   
				$this->set('image_details',$img);
			
			 }
			
			 if(count($this->params['pass'])==7 && $this->params['pass'][6]=='map')
			 {
				  $company_detail=$this->common->getMapDetails($this->params);
				  $s_id=$company_detail[0]['advertiser_profiles']['state'];
				  $c_id=$company_detail[0]['advertiser_profiles']['county'];
				  $cty_id=$company_detail[0]['advertiser_profiles']['city'];
				  $company_address=$company_detail[0]['advertiser_profiles']['address'];
				  $company_name=$company_detail[0]['advertiser_profiles']['company_name'];
				 
				  App::import('model','State');
				  $this->state=new State();
				  $st_name=$this->state->query("select statename from states where id='$s_id'");
				  $state_name=$st_name[0]['states']['statename'];
				 
				  App::import('model','County');
				  $this->cnty=new County();
				  $ct_name=$this->cnty->query("select countyname from counties where id='$c_id'");
				  $county_name=$ct_name[0]['counties']['countyname'];
				  
				  App::import('model','City');
				  $this->cty=new City();
				  $cty_name=$this->cty->query("select cityname from cities where id='$cty_id'");
				  $city_name=$cty_name[0]['cities']['cityname'];
				 
				  $address=$company_address.','.$city_name.' '.$county_name.','.$state_name.' '.$company_detail[0]['advertiser_profiles']['zip'];
				  $this->set('company_name',$company_name);
				  $this->set('address',$address);
			 }
			 $this->set('subcat_name',$this->common->getCatName($this->params['pass'][4]));
			 $this->set('scombo',$this->common->getCombBox($this->common->getCatName($this->params['pass'][4])));  
		     $this->set('city_name',$this->common->getParticularCity($this->params));
				  
	  }
	  
	  
/*End -----------------------------------------------------------------------------------------------------------------------------------------------------------*/
	  

    function displayVideo()
	{
	
     $this->set('company_details',$this->common->getCompanyDetails($this->params)); 	  
	 $this->set('getVideo',$this->common->getVideo($this->params));
			  
	 $this->set('getDivCity',$this->common->getAllCatSubcat($class="coupon",$this->params));
			  
   }
  
	  


	function beforeFilter() 
	{
	        
			 if(count($this->params['pass'])>'1')
			  {
			       if($this->checkurl->validUrl($this->params)=='0')
					 {
						  $this->error();
						  $this->render('error');
						  return;
					 }
					else
					{ 
							if(count($this->params['pass'])==2)
							{
							$this->set('combo',$this->common->getCombBox());  
							}
					$this->set('city_name',$this->common->getParticularCity($this->params));
					
					if(!$this->Session->check('sessionid')) {
					
										$this->Session->write('sessionid',$this->common->randomPassword());
				    }
					//echo $sessionid= $this->Session->write('sessionid');die;
					 						/*------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                                                  /*Ten Coupon For Home Page */
/*------------------------------------------------------------------------------------------------------------------------------------------------------------------*/	
					
					App::import('model','County');
					$this->county=new County();
					$ct_url=$this->params['pass'][1];
					$ct=$this->county->query("select id from counties where page_url='$ct_url'");
					$ct_id=$ct[0]['counties']['id'];
					
					App::import('model','AdvertiserProfile');
					$this->ad_pro=new AdvertiserProfile();
			
			$advertiser_profiles=$this->ad_pro->query("select * from advertiser_profiles ad,cities ct,categories cat,subcategories scat where ad.city=ct.id and cat.id=REPLACE(ad.category,',','') and scat.id=REPLACE(ad.subcategory,',','') and ad.coupon!='' and cat.publish='yes' and scat.publish='yes' and ad.publish='yes' and ad.county='$ct_id'  ORDER BY RAND() limit 10");
				
				
				
					
					$this->set('coupon',$advertiser_profiles);
					
/*-------------End--------------------------------------------------------------------------------------------------------------------------------------------------*/	
				
					/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                                                  /*Category SubCategory list*/
/*------------------------------------------------------------------------------------------------------------------------------------------------------------------*/	
					
					
					
			        $this->set('getDiv',$this->common->getAllCatSubcat($class="sub",$this->params));
					 $this->set('counties',$this->common->chkCounty($this->params));

/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                                                  /*Header Image Of County */
/*------------------------------------------------------------------------------------------------------------------------------------------------------------------*/					

				App::import('model','HeaderLogo');
				$this->h_logo=new HeaderLogo();
				$timestamp=$this->common->getTimeStamp();
				$county=$this->common->chkCounty($this->params);
				$county_id=$county[0]['counties']['id'];
				$logo=$this->h_logo->query("select logo from header_logos where $timestamp BETWEEN start_date and end_date and county_id='$county_id'");
				
				$this->set('getLogo',$this->h_logo->query("select logo from header_logos where $timestamp BETWEEN start_date and end_date and county_id='$county_id'"));
					 
/*-----------------------------------------------End Of Header Image------------------------------------------------------------------------------------------*/		 
					 
					 //for link of bottom
					App::import('model', 'Setting');
					$this->Setting = new Setting;
					$this->set('settings',$this->common->getAllSetting());	
					

/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                                                  /*Footer Banner For  County*/
/*------------------------------------------------------------------------------------------------------------------------------------------------------------------*/					

					App::import('model', 'Banner');
					$this->Banner = new Banner;
					$date=$this->common->getTimeStamp();
					if(count($this->params['pass'])==2 || count($this->params['pass'])==3)
					{
					$bigBanner=$this->Banner->query("select * from banners WHERE $date BETWEEN publish_date and publish_enddate and banner_size='961x228' and image!='' and publish='yes' and county_id='$ct_id' and category_id='' ORDER BY RAND() limit 1");
	
					$this->set('bigBanner',$bigBanner);
					if(count($bigBanner)!=0)
					{
					
						if(isset($bigBanner[0]['banners']['advertiser_profile_id']))
						{
						 $category_id = $bigBanner[0]['banners']['category_id']; 
						 $ad_id = $bigBanner[0]['banners']['advertiser_profile_id']; 
						 $this->loadModel('AdvertiserProfile');
                         
						 /*--------------------Link on Footer big banner Surbhit 11/2/2011-------------------------*/
						 $company_details=$this->AdvertiserProfile->query("select advertiser_profiles.city,advertiser_profiles.category, advertiser_profiles.subcategory,advertiser_profiles.page_url, advertiser_profiles.state,advertiser_profiles.county,counties.page_url,states.page_url,cities.page_url from advertiser_profiles,counties,states,cities where counties.id=advertiser_profiles.county and states.id=advertiser_profiles.state and cities.id = advertiser_profiles.city and advertiser_profiles.id='$ad_id';");
						 
						 /*echo "<pre>";
						 print_r($company_details);
						 echo "</pre>";*/
						 
						 						 
						 if($company_details[0]['advertiser_profiles']['category'] !=''){ 
						 $cat_pageurl =$this->AdvertiserProfile->query("select categories.page_url from categories where categories.id=".str_replace(",","",$company_details[0]['advertiser_profiles']['category']).";"); 
						 }
						 
						 if($company_details[0]['advertiser_profiles']['subcategory'] !=''){ 
						 $subcat_pageurl = $this->AdvertiserProfile->query("select subcategories.page_url from subcategories where subcategories.id=".str_replace(",","",$company_details[0]['advertiser_profiles']['subcategory']).";"); 
						 }
						 
						 $this->set('catpageurl',$cat_pageurl);
						 $this->set('subcatpageurl',$subcat_pageurl);	 
						 
						  /*--------------------Link on Footer big banner Surbhit 11/2/2011-------------------------*/	 
						 $this->set('Merchant',$company_details);
						}
                    }					
					$this->set('midBanner',$this->Banner->query("select * from banners WHERE $date BETWEEN publish_date and publish_enddate and banner_size='451x101' and image!='' and publish='yes' and county_id='$ct_id' and category_id='' ORDER BY RAND() limit 1"));
					
					$this->set('smallBanner',$this->Banner->query("select * from banners WHERE $date BETWEEN publish_date and publish_enddate and banner_size='449x97' and image!='' and publish='yes' and county_id='$ct_id' and category_id='' ORDER BY RAND() limit 1"));
					
					$this->set('banners',$this->common->getAllBottomBanner());	
					}
					else if(count($this->params['pass'])==4)
					{
					$scat_url=$this->params['pass'][3];
					App::import('model','Subcategory');
					$this->scat=new Subcategory();
					$subcategory=$this->scat->query("select id from subcategories where page_url='$scat_url'");
					$cat_id=','.$subcategory[0]['subcategories']['id'].',';
					
					$bigBanner = $this->Banner->query("select * from banners WHERE $date BETWEEN publish_date and publish_enddate and banner_size='961x228' and image!='' and publish='yes' and county_id='$ct_id' and category_id like '%$cat_id%' ORDER BY RAND() limit 1");  
					
					$this->set('bigBanner',$bigBanner);
					if(count($bigBanner)!=0)
					{
						if(isset($bigBanner[0]['banners']['advertiser_profile_id']))
						{
						  
					     $category_id = $bigBanner[0]['banners']['category_id']; 
						 $ad_id = $bigBanner[0]['banners']['advertiser_profile_id'];
						 $this->loadModel('AdvertiserProfile');
                         
						 /*--------------------Link on Footer big banner Surbhit 11/2/2011-------------------------*/
						 $company_details=$this->AdvertiserProfile->query("select advertiser_profiles.city,advertiser_profiles.category, advertiser_profiles.subcategory,advertiser_profiles.page_url, advertiser_profiles.state,advertiser_profiles.county,counties.page_url,states.page_url,cities.page_url from advertiser_profiles,counties,states,cities where counties.id=advertiser_profiles.county and states.id=advertiser_profiles.state and cities.id = advertiser_profiles.city and advertiser_profiles.id='$ad_id';");
						 
				 
						 						 
						 if($company_details[0]['advertiser_profiles']['category'] !=''){ 
						 $cat_pageurl =$this->AdvertiserProfile->query("select categories.page_url from categories where categories.id=".str_replace(",","",$company_details[0]['advertiser_profiles']['category']).";"); 
						 }
						 
						 if($company_details[0]['advertiser_profiles']['subcategory'] !=''){ 
						 $subcat_pageurl = $this->AdvertiserProfile->query("select subcategories.page_url from subcategories where subcategories.id=".str_replace(",","",$company_details[0]['advertiser_profiles']['subcategory']).";"); 
						 }
						 
						 $this->set('catpageurl',$cat_pageurl);
						 $this->set('subcatpageurl',$subcat_pageurl);	 
						 
						  /*--------------------Link on Footer big banner Surbhit 11/2/2011-------------------------*/	 
						 $this->set('Merchant',$company_details);
						}
                    }
					
					$this->set('midBanner',$this->Banner->query("select * from banners WHERE $date BETWEEN publish_date and publish_enddate and banner_size='451x101' and image!='' and publish='yes' and county_id='$ct_id' and category_id like '%$cat_id%' ORDER BY RAND() limit 1"));
					
					$this->set('smallBanner',$this->Banner->query("select * from banners WHERE $date BETWEEN publish_date and publish_enddate and banner_size='449x97' and image!='' and publish='yes' and county_id='$ct_id' and category_id like '%$cat_id%' ORDER BY RAND() limit 1"));
					
					$this->set('banners',$this->common->getAllBottomBanner());	
					}
					else
					{ 
					$scat_url=$this->params['pass'][4];
					App::import('model','Subcategory');
					$this->scat=new Subcategory();
					$subcategory=$this->scat->query("select id from subcategories where page_url='$scat_url'");
					$cat_id=','.$subcategory[0]['subcategories']['id'].',';
					$bigBanner = $this->Banner->query("select * from banners WHERE $date BETWEEN publish_date and publish_enddate and banner_size='961x228' and image!='' and publish='yes' and county_id='$ct_id' and category_id like '%$cat_id%' ORDER BY RAND() limit 1");  
					
				    $this->set('bigBanner',$bigBanner);
					if(count($bigBanner)!=0)
					{
					
						if(isset($bigBanner[0]['banners']['advertiser_profile_id']))
						{
						 $category_id = $bigBanner[0]['banners']['category_id']; 
						 $ad_id = $bigBanner[0]['banners']['advertiser_profile_id'];
						 $this->loadModel('AdvertiserProfile');
                         
						 /*--------------------Link on Footer big banner Surbhit 11/2/2011-------------------------*/
						 $company_details=$this->AdvertiserProfile->query("select advertiser_profiles.city,advertiser_profiles.category, advertiser_profiles.subcategory,advertiser_profiles.page_url, advertiser_profiles.state,advertiser_profiles.county,counties.page_url,states.page_url,cities.page_url from advertiser_profiles,counties,states,cities where counties.id=advertiser_profiles.county and states.id=advertiser_profiles.state and cities.id = advertiser_profiles.city and advertiser_profiles.id='$ad_id';");
						 
						 /*echo "<pre>";
						 print_r($company_details);
						 echo "</pre>";*/
						 
						 						 
						 if($company_details[0]['advertiser_profiles']['category'] !=''){ 
						 $cat_pageurl =$this->AdvertiserProfile->query("select categories.page_url from categories where categories.id=".str_replace(",","",$company_details[0]['advertiser_profiles']['category']).";"); 
						 }
						 
						 if($company_details[0]['advertiser_profiles']['subcategory'] !=''){ 
						 $subcat_pageurl = $this->AdvertiserProfile->query("select subcategories.page_url from subcategories where subcategories.id=".str_replace(",","",$company_details[0]['advertiser_profiles']['subcategory']).";"); 
						 }
						 
						 $this->set('catpageurl',$cat_pageurl);
						 $this->set('subcatpageurl',$subcat_pageurl);	 
						 
						  /*--------------------Link on Footer big banner Surbhit 11/2/2011-------------------------*/	 
						 $this->set('Merchant',$company_details);
						}
                    }
					
					$this->set('midBanner',$this->Banner->query("select * from banners WHERE $date BETWEEN publish_date and publish_enddate and banner_size='451x101' and image!='' and publish='yes' and county_id='$ct_id' and category_id like '%$cat_id%' ORDER BY RAND() limit 1"));
					$this->set('smallBanner',$this->Banner->query("select * from banners WHERE $date BETWEEN publish_date and publish_enddate and banner_size='449x97' and image!='' and publish='yes' and county_id='$ct_id' and category_id like '%$cat_id%' ORDER BY RAND() limit 1"));
					$this->set('banners',$this->common->getAllBottomBanner());	
					}
					
/*----------------------------------------End Of Banner-----------------------------------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                                                  /*Show All Link Of PageManager*/
/*------------------------------------------------------------------------------------------------------------------------------------------------------------------*/					
					$this->set('about_ishop',$this->common->pageDetails(11));
					$this->set('term_of_use',$this->common->pageDetails(5));
					$this->set('ad_with_us',$this->common->pageDetails(23));
					$this->set('ask_learn_hire',$this->common->pageDetails(24));
					$this->set('aff_program',$this->common->pageDetails(25));
					$this->set('ad_or_edit_business',$this->common->pageDetails(26));
					$this->set('feedback',$this->common->pageDetails(27));
					$this->set('online_shoping',$this->common->pageDetails(28));
					$this->set('pop_cat',$this->common->pageDetails(29));
					$this->set('feat_business',$this->common->pageDetails(30));
					$this->set('cons_center',$this->common->pageDetails(31));
					$this->set('site_map',$this->common->pageDetails(32));
					$this->set('partner',$this->common->pageDetails(33));
                    $this->set('private_policy',$this->common->pageDetails(34));
					$this->set('careers',$this->common->pageDetails(35)); 
					
/*----------------------------------------End Of Link------------------------------------------------------------------------------------------------------------*/					
				}					
			}
			
	}
	
/***---------------------- This function is setting all info about current Admins in currentAdmin array so we can use it anywhere lie name id etc.------------------*/
	 function beforeRender() {
			//$this->Ssl->force();
	  }	
}
?>