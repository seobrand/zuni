<?php

/*This 'GRANTH' was written by Zuni Creative crue members :)

common (bechara), keeping functions to use anywhere you wish

*/

class commonComponent extends Object {

     var $components = array('Auth','Session');
	 
	 /** * create a random password

	 * @param	int $length - length of the returned password

	 * @return	string - password * */
	 
	/* Get time stamp of given date */
	
	function getTimeStamp() {
	  return mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
	}
	function getTimeStampReport() {
	  return mktime(date(0),date(0),date(0),date('m'),date('d'),date('Y'));
	}
	function exchange_rate() {
			App::import('model','Setting');
		    $this->Setting = new Setting();
			$rate = $this->Setting->find('first', array('fields' => array('Setting.exchange_rate')));
			return $rate['Setting']['exchange_rate'];
	}
	function randomPassword($length = 8)
	{
		$pass = "";
		
		// possible password chars.

		$chars = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",

			   "k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T",

			   "u","U","v","V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");

		for($i=0 ; $i < $length ; $i++)

		{

			$pass .= $chars[mt_rand(0, count($chars) -1)];

		}
		
		return $pass;
	}
	function makeAlias($data){
		 $string_alias = $data;
		 $string_alias = preg_replace('/\W/', ' ', $string_alias);
		 // replace all white space sections with a dash
		 $string_alias = preg_replace('/\ +/', '-', $string_alias);
		 // trim dashes
		 $string_alias = preg_replace('/\-$/', '', $string_alias);
		 $string_alias = preg_replace('/^\-/', '', $string_alias);
		 $string_alias = strtolower($string_alias);
	     return $string_alias;
	}
	//-----------------------------Listing of all States-------------------------	
	function getAllState(){
	
	 		App::import('model','State');
		    $this->State = new State(); 

			$StatesList = $this->State->find('list', array('fields' => array('id', 'statename'),'order' => 'State.statename ASC','recursive' => -1,'conditions' => array('State.status' => 'yes'))); 
			return $StatesList;
	      }
		  
	
	//-----------------------------Listing of Bottom Banners-------------------------	
	function getAllBottomBanner(){
	
	 		App::import('model','Banner');
		    $this->Banner = new Banner(); 

			$BannerList = $this->Banner->find('all', array('conditions' => array('Banner.area' => 'bottom'))); 
			return $BannerList;
	      }
		  	  
		  
	//-----------------------------Listing of all Cities-------------------------	  
	function getAllCity(){
	
	 		App::import('model','City');
		    $this->City = new City(); 
			
			$CitiesList = $this->City->find('list', array('fields' => array('id', 'cityname'),'order' => 'City.cityname ASC','recursive' => -1,'conditions' => array('City.publish' => 'yes'))); 
			
			return $CitiesList;
	      }
	//-----------------------------Listing of all Counties of specified state-------------------------	  
	function getAllCityByCounty($countyid){
	
	 		App::import('model','City');
		    $this->City = new City(); 
			
			$CityList = $this->City->find('list', array('fields' => array('id', 'cityname'),'order' => 'City.cityname ASC','recursive' => -1,'conditions' => array('City.publish' => 'yes','City.county_id' => $countyid))); 
			
			return $CityList;
	      }	
 	//-----------------------------Listing of all Cities of particular county-------------------------	  
	function getCountyCity($county=''){
	
	 		App::import('model','City');
		    $this->City = new City(); 
			
			$CitiesList = $this->City->find('list', array('fields' => array('id', 'cityname'),'order' => 'City.cityname ASC','recursive' => -1,'conditions' => array('City.publish' => 'yes','City.county_id' => $county))); 
			
			return $CitiesList;
	      }   
 	//-----------------------------Listing of all Cities of particular county-------------------------	  
	function getCountyCity_front($county=''){
	
	 		App::import('model','City');
		    $this->City = new City(); 
			
			$CitiesList = $this->City->find('list', array('fields' => array('page_url', 'cityname'),'order' => 'City.cityname ASC','recursive' => -1,'conditions' => array('City.publish' => 'yes','City.front_status' =>1,'City.county_id' => $county))); 
			
			return $CitiesList;
	      }   	
	//-----------------------------Listing of all Busniesses-------------------------	  
	function getBusList()
		  {
		    App::import('model','AdvertiserProfile');
		    $this->ad_pro = new AdvertiserProfile(); 
			
			$BusnessesList = $this->ad_pro->find('list', array('fields' => array('id', 'company_name'),'order' => 'id ASC','recursive' => -1,'conditions' => array('publish' => 'yes'))); 
			return $BusnessesList;
		  }	
		  
	
	//-----------------------------Listing of all Countries-------------------------	  
	function getAllCountry(){
	
	 		App::import('model','Country');
		    $this->Country = new Country(); 
			
			$CountriesList = $this->Country->find('list', array('fields' => array('id', 'countryname'),'order' => 'Country.countryname ASC','recursive' => -1 )); 
			
			return $CountriesList;
	      }	
	
	
	//-----------------------------Listing of all Counties-------------------------	  
	function getAllCounty(){
	
	 		App::import('model','County');
		    $this->County = new County(); 
			
			$CountyList = $this->County->find('list', array('fields' => array('id', 'countyname'),'order' => 'County.countyname ASC','recursive' => -1,'conditions' => array('County.publish' => 'yes'))); 
			
			return $CountyList;
	      }	
	//-----------------------------Listing of all business name-------------------------	  
	function getAllBusiness(){
	
	 		App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile(); 
			
			$BusinessList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.publish' => 'yes'))); 
			
			return $BusinessList;
	      }		
	
	//-----------------------------Listing of all usergroups-------------------------	  
	function getAllUserGroup(){
	
	 		App::import('model','UserGroup');
		    $this->UserGroup = new UserGroup(); 
			
			$UserGroupList = $this->UserGroup->find('list', array('fields' => array('id', 'group_name'),'order' => 'UserGroup.group_name ASC','recursive' => -1,'conditions' => array('UserGroup.active' => 'yes'))); 
			return $UserGroupList;
	      }	
	
	
	//-----------------------------Listing of all category (publish only)-------------------------	  
	function getAllCategory(){
	 		App::import('model','Category');
		    $this->Category = new Category(); 
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory();
			
			$categoryList = $this->Category->find('list', array('fields' => array('id', 'categoryname'),'order' => 'Category.order,Category.categoryname ASC','recursive' => -1,'conditions' => array('Category.publish' => 'yes')));
			return $categoryList;
	      }
	//-----------------------------Listing of all category (publish only)-------------------------	  
	function getFullCategory(){
	
	 		App::import('model','Category');
		    $this->Category = new Category(); 
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory(); 
			
			$categoryList = $this->Category->find('list', array('fields' => array('id', 'categoryname'),'order' => 'Category.order,Category.categoryname ASC','recursive' => -1));
			return $categoryList;
	      }			    

	
	//-----------------------------Listing of all category (all)-------------------------	  
	function getAllCategoryHot(){
	
	 		App::import('model','Category');
		    $this->Category = new Category(); 
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory(); 
			
			$categoryList = $this->Category->find('list', array('fields' => array('id', 'categoryname'),'order' => 'Category.order,Category.categoryname ASC','recursive' => -1));
			return $categoryList;
	      }
	//-----------------------------Listing of all category with details-------------------------	  
	function getAllCategoryDetail(){
	
	 		App::import('model','Category');
		    $this->Category = new Category(); 
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory(); 
			
			$categoryList = $this->Category->find('all', array('order' => 'Category.categoryname ASC','recursive' => -1,'conditions' => array('Category.publish' => 'yes')));
			return $categoryList;
	      }	  

	//-----------------------------Listing of all usergroups-------------------------	  
	function getAllCategoryFundraiser(){
	
	 		App::import('model','Category');
		    $this->Category = new Category(); 

			
			$categoryList = $this->Category->find('all', array('fields' => array('id', 'categoryname'),'order' => 'Category.order,Category.categoryname ASC','recursive' => -1,'conditions' => array('Category.publish' => 'yes')));
			return $categoryList;
	      }	  


	//-----------------------------Listing of all categories selected by advertiser-------------------------	  
	function getAllCategoryVip($id){
	
	 		App::import('model','Category');
		    $this->Category = new Category(); 
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile(); 
			$all_data = $this->AdvertiserProfile->find('all', array('fields' => array('id', 'category'),'order' => 'AdvertiserProfile.id ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.id' => $id)));
			if(isset($all_data[0]['AdvertiserProfile']['category']))
			$cat_str=substr($all_data[0]['AdvertiserProfile']['category'],1,-1);
			
			if(isset($all_data[0]['AdvertiserProfile']['category']))
			{
			$categoryList = $this->Category->find('list', array('fields' => array('id', 'categoryname'),'order' => 'Category.order,Category.categoryname ASC','recursive' => -1,'conditions' => array('Category.publish = "yes" AND Category.id IN ('.$cat_str.')')));
			return $categoryList;
			}
	      }	  
	/*Start Function----------------Listing of all categories and subcategories selected by advertiser-----------------------------------------*/	  
	function getAllCatSubcatSaving($modelname='',$cate='',$ad_id='')
     {
				App::import('model','Category');
				$this->Category = new Category();
				App::import('model','Subcategory');
				$this->Subcategory = new Subcategory();
				
				App::import('model','AdvertiserProfile');
				$this->AdvertiserProfile = new AdvertiserProfile();
				
				$div= '<select name="data['.$modelname.'][subcategory]" id="subcategory" tabindex="4" style="width:300px;">';
				$div .=  '<option value="">Select Category</option>';
				
				
				if(isset($ad_id) && $ad_id!='')
				{
					
				$catSubcat=$this->AdvertiserProfile->find('first',array('fields'=>array('category','subcategory'),'recursive' => -1,'conditions' => array('AdvertiserProfile.id'=>$ad_id)));	
					
					$catArray=array_unique(array_filter(explode(',',$catSubcat['AdvertiserProfile']['category'])));
					$subCatArray=array_filter(explode(',',$catSubcat['AdvertiserProfile']['subcategory']));
					$catSring=implode(',',$catArray);//substr($catSubcat['AdvertiserProfile']['category'],1,-1);
					$subCatSring=substr($catSubcat['AdvertiserProfile']['subcategory'],1,-1);

					if($catSring!='') {

					$catscombo = $this->Category->query("select * from categories where publish='yes' and id IN ($catSring) ORDER BY `order`,`categoryname` ASC");
				if(is_array($catscombo) && !empty($catscombo)) {
				foreach($catscombo as $category)
		    	{
					$div.= '<optgroup label="'.$category['categories']['categoryname'].'">';	
					$id=$category['categories']['id'];
					foreach($this->Subcategory->query("select * from subcategories where publish='yes' and category_id LIKE Concat('%,',$id,',%') ORDER BY `categoryname` ASC") as $subCat)
					{
						  if(in_array($subCat['subcategories']['id'],$subCatArray))
						  {
						  	  if($subCat['subcategories']['categoryname']!='' && $subCat['subcategories']['id']==$cate)
								{
								
								$div.= '<option value="'.$id.'-'.$subCat['subcategories']['id'].'" selected="selected">'.$subCat['subcategories']['categoryname'].'</option>';						
								
								}
							  else
								{
								
								$div.= '<option value="'.$id.'-'.$subCat['subcategories']['id'].'">'.$subCat['subcategories']['categoryname'].'</option>';
								}
						}
			
					}	
								$div.='</optgroup>';
				}
				}}
			}
				
				$div.='</select>';
	
			return $div;
	 }	
	/*Start Function----------------Listing of all categories and subcategories selected by advertiser-----------------------------------------*/	  
	function getAllCatSubcatdiscount($modelname='',$cate='',$ad_id='',$pcat='')
     {
				App::import('model','Category');
				$this->Category = new Category();
				
				App::import('model','Subcategory');
				$this->Subcategory = new Subcategory();
				
				App::import('model','AdvertiserCategory');
				$this->AdvertiserCategory = new AdvertiserCategory();
				
				$div= '<select name="data[daily_discount][subcategory]" id="subcategory" tabindex="4" style="width:250px;">';
				$div .=  '<option value="">Select Category</option>';
				
				if(isset($ad_id) && $ad_id!='')
				{
				$catSubcat = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT CategoriesSubcategory.category_id','conditions'=>array('AdvertiserCategory.advertiser_profile_id'=>$ad_id)));
				$arr = '';
				if(is_array($catSubcat) && !empty($catSubcat)) {
					foreach($catSubcat as $catSubcat) {
						$arr[] = $catSubcat['CategoriesSubcategory']['category_id'];
					}
				}
				
				if(is_array($arr)) {
				
				$catSring=implode(',',$arr);
				$cat_arra =  $this->Category->query("select * from categories where publish='yes' and id IN ($catSring) ORDER BY `order`,`categoryname` ASC");	
				
				if(is_array($cat_arra)) {
				foreach($cat_arra as $category)
		    	{
					$div.= '<optgroup label="'.$category['categories']['categoryname'].'">';
					$id=$category['categories']['id'];
					$sub = array(0);
					$Subcats = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT CategoriesSubcategory.subcategory_id','conditions'=>array('AdvertiserCategory.advertiser_profile_id'=>$ad_id,'CategoriesSubcategory.category_id'=>$id)));
					foreach($Subcats as $Subcats) {
						$sub[] = $Subcats['CategoriesSubcategory']['subcategory_id'];
					}
					$subcatSring=implode(',',$sub);
					
					foreach($this->Subcategory->query("select * from subcategories where publish='yes' and id IN ($subcatSring) ORDER BY `categoryname` ASC") as $subCat)
					{
						  	  if($subCat['subcategories']['categoryname']!='' && $subCat['subcategories']['id']==$cate && $id==$pcat)
								{
								
								$div.= '<option value="'.$id.'-'.$subCat['subcategories']['id'].'" selected="selected">'.$subCat['subcategories']['categoryname'].'</option>';
								}
							  else
								{
								
								$div.= '<option value="'.$id.'-'.$subCat['subcategories']['id'].'">'.$subCat['subcategories']['categoryname'].'</option>';
								}
			
					}	
								$div.='</optgroup>';
				}
				}
			}
			}	
				$div.='</select>';
	
			return $div;
	 }		
	/*Start Function----------------Listing of all categories and subcategories selected by advertiser-----------------------------------------*/	  
	function getAllCatSubcatdeal($modelname='',$cate='',$ad_id='',$pcat='')
     {
				App::import('model','Category');
				$this->Category = new Category(); 
				App::import('model','Subcategory');
				$this->Subcategory = new Subcategory(); 
				
				App::import('model','AdvertiserCategory');
				$this->AdvertiserCategory = new AdvertiserCategory();
				
				$div= '<select name="data[daily_deal][subcategory]" id="subcategory" tabindex="4" style="width:250px;">';
				$div .=  '<option value="">Select Category</option>';
				
				
				if(isset($ad_id) && $ad_id!='')
				{
					
				$catSubcat = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT CategoriesSubcategory.category_id','conditions'=>array('AdvertiserCategory.advertiser_profile_id'=>$ad_id)));
				
				$arr = '';
				if(is_array($catSubcat) && !empty($catSubcat)) {
					foreach($catSubcat as $catSubcat) {
						$arr[] = $catSubcat['CategoriesSubcategory']['category_id'];
					}
				}
				
				if(is_array($arr)) {
				
					$catSring=implode(',',$arr);
					
				
				foreach($this->Category->query("select * from categories where publish='yes' and id IN ($catSring) ORDER BY `order`,`categoryname` ASC") as $category)
		    	{
					$div.= '<optgroup label="'.$category['categories']['categoryname'].'">';	
					$id=$category['categories']['id'];
					
					$sub = array(0);
					$Subcats = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT CategoriesSubcategory.subcategory_id','conditions'=>array('AdvertiserCategory.advertiser_profile_id'=>$ad_id,'CategoriesSubcategory.category_id'=>$id)));
					foreach($Subcats as $Subcats) {
						$sub[] = $Subcats['CategoriesSubcategory']['subcategory_id'];
					}
					$subcatSring=implode(',',$sub);
					
					foreach($this->Subcategory->query("select * from subcategories where publish='yes' and id IN ($subcatSring) ORDER BY `categoryname` ASC") as $subCat)
					{
						  	  if($subCat['subcategories']['categoryname']!='' && $subCat['subcategories']['id']==$cate && $id==$pcat)
								{
								
								$div.= '<option value="'.$id.'-'.$subCat['subcategories']['id'].'" selected="selected">'.$subCat['subcategories']['categoryname'].'</option>';						
								
								}
							  else
								{
								
								$div.= '<option value="'.$id.'-'.$subCat['subcategories']['id'].'">'.$subCat['subcategories']['categoryname'].'</option>';
								}
					}	
								$div.='</optgroup>';
				}
				
			}
				}
				$div.='</select>';
	
			return $div;
	 }			  		  
	//-----------------------------Listing of all Salseperson-------------------------	  
	function getAllSelsePerson($id=5){
	
	 		App::import('model','User');
		    $this->User = new User(); 
			
			$UserList = $this->User->find('list', array('fields' => array('id', 'name'),'order' => 'User.name ASC','recursive' => -1,'conditions' => array('User.active' => 'yes','User.user_group_id' =>$id))); 
			
			return $UserList;
	      }	
	
	function getadminid($id){
	
	 		App::import('model','User');
		    $this->User = new User(); 
			
			$UserList = $this->User->find('id', array('fields' => array('id', 'name'),'order' => 'User.name ASC','recursive' => -1,'conditions' => array('User.active' => 'yes','User.user_group_id' =>$id))); 
			
			return $UserList;
	      }			  	
	//-----------------------------Listing of all Setting-------------------------	  
	function getAllSetting(){
	
	 		App::import('model','Setting');
		    $this->Setting = new Setting(); 
			
						//$settingList = $this->Setting->find('list', array('fields' => array('id', 'categoryname'),'order' => 'Category.categoryname ASC','recursive' => -1,'conditions' => array('Category.publish' => 'yes'))); 
			$settingList = $this->Setting->find('all'); 

			return $settingList;
	      }	  
		  
		  
		  
	
	//-----------------------------Listing of all subcategory-------------------------	  
	function getAllSubCategory(){
	
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory(); 
			
			$subCategoryList = $this->Subcategory->find('list', array('fields' => array('id', 'categoryname'),'order' => 'Subcategory.categoryname ASC','recursive' => -1,'conditions' => array('Subcategory.publish' => 'yes'))); 
			

			return $subCategoryList;
	      }
		
	
	//-----------------------------Listing of all published AdvertiserProfileList-------------------------
	function getAllAdvertiserProfile(){
	
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile(); 
			
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'order' => 'AdvertiserProfile.name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.publish' => 'yes'))); 
			

			return $AdvertiserProfileList;
	  }
	//-----------------------------Listing of all published Advertiser Comapny List-------------------------	  
	function getAllAdvertiserCompany(){
	
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile(); 
			
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'order' => 'AdvertiserProfile.name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.publish' => 'yes'))); 
			

			return $AdvertiserProfileList;
	  }
	  	//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList-------------------------	  
	function getAdvertiserProfileAll(){	
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.publish' => 'yes','AdvertiserProfile.company_name!=""')));
			return $AdvertiserProfileList;
	  }
	  	//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList-------------------------	  
	function getAdvertiserCounty($county){
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('AdvertiserProfile.id', 'AdvertiserProfile.company_name'),'conditions'=>array('AdvertiserProfile.county'=>$county),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1));
			return $AdvertiserProfileList;
	}
	  	//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList-------------------------	  
	function getAdvertiserCity($city){	
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('AdvertiserProfile.id', 'AdvertiserProfile.company_name'),'conditions'=>array('AdvertiserProfile.city'=>$city),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1));
			return $AdvertiserProfileList;
	  }
	  	//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList-------------------------	  
	function getConsumerProfileAll(){	
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();
			$FrontUser = $this->FrontUser->find('list', array('fields' => array('id', 'name'),'conditions'=>array('FrontUser.user_type'=>'customer','FrontUser.status'=>'yes'),'order' => 'FrontUser.name ASC','recursive' => -1));
			return $FrontUser;
	  }	  
	  	//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList-------------------------	  
	function getVoucherAll(){
	
			App::import('model','Voucher');
		    $this->Voucher = new Voucher(); 
			
			$Voucher = $this->Voucher->find('list', array('fields' => array('id', 'title'),'conditions'=>array('Voucher.status'=>'yes'),'order' => 'Voucher.title ASC','recursive' => -1)); 
			

			return $Voucher;
	  }
	//-----------------------------Listing of all AdvertiserProfileList published and unpublished-------------------------	  
	function getAllAdvertiserProfileForOrderListing(){
	
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile(); 
			
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'conditions'=>array('AdvertiserProfile.company_name!=""'),'order' => 'AdvertiserProfile.name ASC','recursive' => -1));		

			return $AdvertiserProfileList;
	  }
	
	//-----------------------------Listing of all Package-------------------------	  
	function getAllPackage($var){
	
			App::import('model','Package');
		    $this->Package = new Package(); 
			$result = $this->Package->query("SELECT id, CONCAT(name,' ($',price,')' ) as namePrice, setup_price, monthly_price, name from packages where status='yes'");
			$countArray = 0;
			while($countArray<sizeof($result)){
			      $idArray = $result[$countArray]['packages']['id'];
			      //$packageList1[$idArray] = $result[$countArray][0]['namePrice'];
				  $packageList1[$idArray] = $result[$countArray]['packages']['name'].' ($'.($result[$countArray]['packages']['setup_price']+$result[$countArray]['packages']['monthly_price']).')';
				  $countArray++;
			}
			$packageList2 = $this->Package->find('list', array('fields' => array('id', 'name'),'order' => 'Package.name ASC','recursive' => -1,'conditions' => '' ));
			$packageList3 = $this->Package->find('all', array('fields' => array('id', 'setup_price','monthly_price'),'conditions'=>array('Package.status'=>'yes'),'order' => 'Package.price ASC','recursive' => -1));
			$newPackage = '';
			if(!empty($packageList3)) {
				foreach($packageList3 as $packageList3) {
					$newPackage[$packageList3['Package']['id']] = ($packageList3['Package']['setup_price']+$packageList3['Package']['monthly_price']);
				}
			}
              if($var == 1){
			    return $packageList1;
				}
			  if($var == 2){
			    return $packageList2;
				}
			  if($var == 3){
			    return $newPackage;
				}
	      }//-----------------------------Listing of all Package-------------------------	  
	function getAdminPackage(){
	
			App::import('model','Package');
		    $this->Package = new Package();
			$packageList1 = '';
			$result = $this->Package->query("SELECT id, CONCAT(name,' ($',price,')' ) as namePrice, setup_price, monthly_price, name from packages where status='yes' AND type like '%sales_person%'");
			$countArray = 0;
			while($countArray<sizeof($result)){
			      $idArray = $result[$countArray]['packages']['id'];
			      //$packageList1[$idArray] = $result[$countArray][0]['namePrice'];
				  $packageList1[$idArray] = $result[$countArray]['packages']['name'].' ($'.($result[$countArray]['packages']['setup_price']+$result[$countArray]['packages']['monthly_price']).')';
				  $countArray++;
			}			
			    return $packageList1;
	      }//-----------------------------Listing of all Package-------------------------	  
	function getspclPackage(){
	
			App::import('model','Package');
		    $this->Package = new Package();
			$packageList1 = '';
			$result = $this->Package->query("SELECT id, CONCAT(name,' ($',price,')' ) as namePrice, setup_price, monthly_price, name from packages where status='yes' AND type like '%special%'");
			$countArray = 0;
			while($countArray<sizeof($result)){
			      $idArray = $result[$countArray]['packages']['id'];
			      //$packageList1[$idArray] = $result[$countArray][0]['namePrice'];
				  $packageList1[$idArray] = $result[$countArray]['packages']['name'].' ($'.($result[$countArray]['packages']['setup_price']+$result[$countArray]['packages']['monthly_price']).')';
				  $countArray++;
			}			
			    return $packageList1;
	      }	
	function getOnlyPackage(){
	
			App::import('model','Package');
		    $this->Package = new Package();
			$packageList1 = '';
			$result = $this->Package->query("SELECT id, CONCAT(name,' ($',price,')' ) as namePrice, setup_price, monthly_price, name from packages where status='yes' AND type NOT like '%special%'");
			$countArray = 0;
			while($countArray<sizeof($result)){
			      $idArray = $result[$countArray]['packages']['id'];
			      //$packageList1[$idArray] = $result[$countArray][0]['namePrice'];
				  $packageList1[$idArray] = $result[$countArray]['packages']['name'].' ($'.($result[$countArray]['packages']['setup_price']+$result[$countArray]['packages']['monthly_price']).')';
				  $countArray++;
			}			
			    return $packageList1;
	      }			  		     
    //---------------------------function for uploading image--------------------------------------------------------------------
	   /**
         * uploads files to the server
         * @params:
         *		$folder 	= the folder to upload the files e.g. 'img/files'
         *		$formdata 	= the array containing the form files
         *		$itemId 	= id of the item (optional) will create a new sub folder
         * @return:
         *		will return an array with the success of each file upload
         */
     function uploadFiles($folder, $formdata, $itemId = null) {
	    // setup dir names absolute and relative
		
	     $folder_url = WWW_ROOT.$folder; 
		 
		  //$folder_url =FULL_BASE_URL.Router::url('/', false).$folder;
		 
	      $rel_url = $folder;
		//echo $rel_url;die;
	   // create the folder if it does not exist
	     if(!is_dir($folder_url)) {
		      mkdir($folder_url);
	      }
		
	    // if itemId is set create an item folder
	   if($itemId) {
		    // set new absolute folder
		    $folder_url = WWW_ROOT.$folder.'/'.$itemId; 
			
		    // set new relative folder
		    $rel_url = $folder.'/'.$itemId;
		   // create directory
		   if(!is_dir($folder_url)) {
			   mkdir($folder_url);
		   }
	  }
	//pr($formdata);die;
	   // list of permitted file types, this is only images but documents can be added
	    $permitted = array('image/gif','image/jpeg','image/pjpeg','image/png');
	   // loop through and deal with the files
	    foreach($formdata as $file) {
		
		   // replace spaces with underscores
		  $filename = str_replace(' ', '_', $file['name']);
		  
		   // assume filetype is false
		   $typeOK = false;
		   
		   //print_r($file['type']);
		  // check filetype is ok
		foreach($permitted as $type) {
		//pr($type);die;
			if($type == $file['type']) {
				$typeOK = true;
				break;
			}
		  }
		
		  // if file type ok upload the file
		  if($typeOK) {
		  
			  // switch based on error code
			  switch($file['error']) {
				  case 0:
						// create unique filename and upload file
						//ini_set('date.timezone', 'Europe/London');
						$now      = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
						$filename = str_replace(' ','',$filename);
						$full_url = $folder_url.'/'.$now.$filename;
						$url      = $rel_url.'/'.$now.$filename;
						$success  = move_uploaded_file($file['tmp_name'], $url);
					// if upload was successful
					if($success) {
						// save the url of the file
						$result['urls'][] = $url;
					} else {
						$result['errors'][] = "Error uploaded $filename. Please try again.";
					}
					break;
				case 3:
					// an error occured
					$result['errors'][] = "Error uploading $filename. Please try again.";
					break;
				default:
					// an error occured
					$result['errors'][] = "System error uploading $filename. Contact webmaster.";
					break;
			}
		  } elseif($file['error'] == 4) {
			// no file was selected for upload
			$result['nofiles'][] = "No file Selected";
		  } else {
			// unacceptable file type
			$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
		  }
	   }
       return $result;
    }
	/*Start Function----------------Listing of all categories and subcategories selected by advertiser-----------------------------------------*/	  
	function getAllCatSubcatBanner($modelname='',$cate='',$ad_id='',$pcat='')
     {
				App::import('model','Category');
				$this->Category = new Category(); 
				App::import('model','Subcategory');
				$this->Subcategory = new Subcategory(); 
				
				App::import('model','AdvertiserProfile');
				$this->AdvertiserProfile = new AdvertiserProfile();
				
				$div= '<select name="data[Banner][category_id]" id="category_id" tabindex="4" style="width:250px;">';
				$div .=  '<option value="">Select Category</option>';
				
				
				if(isset($ad_id) && $ad_id!='')
				{
					
				$catSubcat=$this->AdvertiserProfile->find('first',array('fields'=>array('category','subcategory'),'recursive' => -1,'conditions' => array('AdvertiserProfile.id'=>$ad_id)));
				if($catSubcat['AdvertiserProfile']['category']!='') {
					$catArray=array_unique(array_filter(explode(',',$catSubcat['AdvertiserProfile']['category'])));
					$subCatArray=array_filter(explode(',',$catSubcat['AdvertiserProfile']['subcategory']));
					$catSring=implode(',',$catArray);//substr($catSubcat['AdvertiserProfile']['category'],1,-1);
					$subCatSring=substr($catSubcat['AdvertiserProfile']['subcategory'],1,-1);
				$cat_arra =  $this->Category->query("select * from categories where publish='yes' and id IN ($catSring) ORDER BY `order`,`categoryname` ASC");	
				if(is_array($cat_arra)) {
				foreach($cat_arra as $category)
		    	{
					$div.= '<optgroup label="'.$category['categories']['categoryname'].'">';	
					$id=$category['categories']['id'];
					foreach($this->Subcategory->query("select * from subcategories where publish='yes' and category_id LIKE Concat('%,',$id,',%') ORDER BY `categoryname` ASC") as $subCat)
					{
						  if(in_array($subCat['subcategories']['id'],$subCatArray))
						  {
						  	  if($subCat['subcategories']['categoryname']!='' && $subCat['subcategories']['id']==$cate && $id==$pcat)
								{
								
								$div.= '<option value="'.$id.'-'.$subCat['subcategories']['id'].'" selected="selected">'.$subCat['subcategories']['categoryname'].'</option>';						
								
								}
							  else
								{
								
								$div.= '<option value="'.$id.'-'.$subCat['subcategories']['id'].'">'.$subCat['subcategories']['categoryname'].'</option>';
								}
						}
			
					}	
								$div.='</optgroup>';
				}
				}
			}
			}	
				$div.='</select>';
	
			return $div;
	 }			
	/*Start Function-----------------------------Listing of cat and subcat--------------------------------------------------------------------------*/	  
	function getAllCatSubcat($class="sub",$param)
     {
				App::import('model','Category');
				$this->Category = new Category(); 
				App::import('model','CategoriesSubcategory');
				$this->CategoriesSubcategory = new CategoriesSubcategory();
				$div=' <div class="'.$class.'" >';
				foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		    	{
			 		$div.='<h2>'.$category['categories']['categoryname'].'</h2>';
					$id=$category['categories']['id'];
					$data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
					
					$div.='<p>';	
					foreach($data as $subCat)
					{
						   if($subCat['Subcategory']['categoryname']!='')
								{
								$div.='<a href="'.FULL_BASE_URL.Router::url('/', false).'state/'.$param['pass'][0].'/'.$param['pass'][1].'/'.$category['categories']['page_url'].'/'.$subCat['Subcategory']['page_url'].'" style="text-decoration:underline;" title="'.$subCat['Subcategory']['categoryname'].'">'.$subCat['Subcategory']['categoryname'].'</a>,&nbsp;'."\n";
								}
			
					}	
								$div.='</p>';
				}
				$div.='</div>';
	
			return $div;
	 }	
	/*End Function----------------------------------------------------------------------------------------------------------------------------------*/	
		  
	/*Start Function-----------------------------Listing of cat and subcat--------------------------------------------------------------------------*/	  
	function getAllCatSubcatoption($modelname="User",$cate='')
     {
				App::import('model','Category');
				$this->Category = new Category(); 
				App::import('model','CategoriesSubcategory');
				$this->CategoriesSubcategory = new CategoriesSubcategory();
				$div= '<select name="data['.$modelname.'][subcategory]" id="subcategory" tabindex="15">';
				$div .=  '<option value="">Select Category</option>';
				foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		    	{
					$div.= '<optgroup label="'.$category['categories']['categoryname'].'">';	
					$id=$category['categories']['id'];
					$data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
					
					foreach($data as $subCat)
					{
						  if($subCat['Subcategory']['categoryname']!='' && $subCat['Subcategory']['id']==$cate)
								{
								
								$div.= '<option value="'.$id.'-'.$subCat['Subcategory']['id'].'" selected="selected">'.$subCat['Subcategory']['categoryname'].'</option>';						
								
								}
							else
								{
								
								$div.= '<option value="'.$id.'-'.$subCat['Subcategory']['id'].'">'.$subCat['Subcategory']['categoryname'].'</option>';
								}
						
			
					}	
								$div.='</optgroup>';
				}
				$div.='</select>';
	
			return $div;
	 }	
	/*Start Function-----------------------------Listing of cat and subcat--------------------------------------------------------------------------*/	  
	function getAllCatSubcat1($modelname="User",$cate)
    {
				App::import('model','Category');
				$this->Category = new Category();
				App::import('model','CategoriesSubcategory');
				$this->CategoriesSubcategory = new CategoriesSubcategory();
				$div= '<select name="data['.$modelname.'][category]" id="subcategory" style="width:175px;">';
				$div .=  '<option value="">Select Category</option>';
				foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		    	{
					$div.= '<optgroup label="'.$category['categories']['categoryname'].'">';
					$id=$category['categories']['id'];
					$data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
					foreach($data as $subCat)
					{
					   if($subCat['Subcategory']['categoryname']!='' && ($id.'-'.$subCat['Subcategory']['id']) != $cate)
							{
								$div.= '<option value="'.$id.'-'.$subCat['Subcategory']['id'].'">'.$subCat['Subcategory']['categoryname'].'</option>';
							}
							else{
								$div.= '<option value="'.$id.'-'.$subCat['Subcategory']['id'].'" selected="selected">'.$subCat['Subcategory']['categoryname'].'</option>';
							}
					}
								$div.='</optgroup>';
				}
				$div.='</select>';
			return $div;
	 }
/*End Function----------------------------------------------------------------------------------------------------------------------------------*/		
	function getCombBox($scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='<select  name="business" id="business" class="search_input_combo" tooltipText="Select category in which you want to search.">';
		if($scatname=="")
				 {
				 $combo.='<option value="0" selected="selected">Select Category</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.$category['categories']['categoryname'].'" >';
				$id=$category['categories']['id'];
				$data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['categoryname']!='')
								{
								if(strtolower($scatname)==strtolower($subCat['Subcategory']['categoryname']))
								{
								$combo.='<option value="'.$id.'-'.$subCat['Subcategory']['id'].'" selected="selected">&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								else
								{
								$combo.='<option value="'.$id.'-'.$subCat['Subcategory']['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								}
					}
					
				}
				$combo.='</optgroup></select>';
				return $combo;
		}
		  
	
	/*-Start Of--Checking the Correct State Name Or County Name---------------------------------------------------------------------------------------*/
	function chkCounty($params)
	{
        $state_name=$params['pass'][0];
		$county_name=$params['pass'][1];
	    App::import('model','State');
		$this->State = new State();
		App::import('model','County');
		$this->County = new County();
		$st_details=$this->State->query("select * from states where page_url='$state_name'");
		if(count($st_details)==0)
		{
			return 0;
		}
		else
		{
		         $state_id=$st_details[0]['states']['id'];
				 $ct_name=$this->County->query("select * from counties where page_url='$county_name'");
				 if(count($ct_name)==0)
				 {
				   return 0;
				 }
				 
				 else
				 {
				    //echo "select * from counties where countyname='$county_name' and state_id='$state_id'";die; 
					$ct_name_valid=$this->County->query("select * from counties where page_url='$county_name' and state_id='$state_id'");
					 if(count($ct_name_valid)==0)
					 {
					   return 0;
					 }
					 else
					 {
					   return $ct_name_valid ;
					 }
				 }
		}

	}

	/*End Of--Checking  the Correct State Name Or County Name-----------------------------------------------------------------------------------*/ 
	
	
	/*-Start Of--Checking the Correct Category Name Or SubCategory Name---------------------------------------------------------------------*/ 
	function chkCatSubcat($param)
	{
	        $cat=$param['pass'][2];
			$subCat=$param['pass'][3];
			App::import('model','Category');
			$this->Category=new Category();
			App::import('model','Subcategory');
			$this->Subcategory=new Subcategory();
			
			if($subCat=='')
			{
			return 0;
			}
			$cat=$this->Category->query("Select * from categories where page_url='".$cat."'");
			$cat_count=count($cat);
			if($cat_count=='0')
			{
		    	return 0;
			}
			else
			{
					$cat_id=$cat[0]['categories']['id'];
					$sub=$this->Subcategory->query("Select * from subcategories where page_url='".$subCat."'");
					$subCat_count=count($sub);
					if($subCat_count=='0')
					{
					  return 0;
					}
					else
					{
				 $sub_details=$this->Subcategory->query("Select * from subcategories where page_url='".$subCat."' and category_id LIKE Concat('%,',$cat_id,',%')");
						if(count($sub_details)==0)
						{
						return 0;
						}
						else
						{
						return ;
					    }
					}
			}
	}
/*-End Of--Checking the Correct Category Name Or SubCategory Name---------------------------------------------------------------------------------------------------*/

	
/*Start Funtion-Get The Particular City-----------------------------------------------------------------------------------------------------------------------------*/	
	function getParticularCity($county_name)
		{
		   $county_name=$county_name['pass'][1];
			App::import('model','County');
			$this->County=new County();
			$county_id=$this->County->query("Select id from counties where countyname='".$county_name."'");
			$id=$county_id[0]['counties']['id'];
			App::import('model','City');
			$this->City=new City();
			$city_name=$this->City->query("Select DISTINCT city.id,city.cityname,city.page_url from cities as city,advertiser_profiles ap where county_id='".$id."' and city.id=ap.city and ap.publish='yes'");
			
		
			return $city_name;
		}
	/*End Function--------------------------------------------------------------------------------------------------------------------------------------*/		
		
		
		
	/* Start Function--- Getting Top Ten Banner----------------------------------------------------------------------------------------------------------*/	
   
       function getTenBanner($params)
	   {
	   //SELECT * FROM $table_name where status='yes' ORDER BY RAND() limit 10
	     $cot_name=$params['pass'][1];
		 $cat_url=$params['pass'][3];
		 $scat_url=$params['pass'][4];
		 $cty_url=$params['pass'][2];
		 App::import('model','County');
		 $this->County=new County();
         App::import('model','Category');
		 App::import('model','Subcategory');
		 App::import('model','City');
		 $this->cat=new Category();
		 $this->scat=new Subcategory();
		 $this->cty=new City();
		 $cat_id=$this->cat->query("select id from categories where page_url='$cat_url'");
		 $scat_id=$this->scat->query("select id from subcategories where page_url='$scat_url'");
		 $cty_id=$this->scat->query("select id from cities where page_url='$cty_url'");
		 $category_id=$cat_id[0]['categories']['id'];
		 $subcategory_id=$scat_id[0]['subcategories']['id'];
		 $city_id=$cty_id[0]['cities']['id'];
		 $cot_id=$this->County->query("select id from counties where countyname='$cot_name'"); 
		 $ct_id=$cot_id[0]['counties']['id'];
		 App::import('model','AdvertiserProfile');	
		 $this->ad_pro=new AdvertiserProfile();
	//echo "select ap.zip,ap.company_name,ap.page_url,ap.address,ap.coupon,ap.coupon2,ct.cityname from advertiser_profiles ap,top_ten_businesses top,cities ct where ap.category LIKE Concat('%,',$category_id,',%') and ap.subcategory LIKE Concat('%,',$subcategory_id,',%') and ap.city=$city_id and ap.id=top.advertiser_profile_id and ap.publish='yes' and ap.coupon!='' ORDER BY RAND() limit 10";die;
		
		 $ad_profile=$this->ad_pro->query("select * from advertiser_profiles ap,top_ten_businesses top,cities ct where ap.category LIKE Concat('%,',$category_id,',%') and ap.subcategory LIKE Concat('%,',$subcategory_id,',%') and ap.city=$city_id and ap.city=ct.id and ap.id=top.advertiser_profile_id and ap.publish='yes' and ap.coupon!='' ORDER BY RAND() limit 10");
		
		 return $ad_profile;
	   }	
		
		function getTopTenBanner($params)
		{
		 $cot_name=$params['pass'][1];
		 App::import('model','County');
		 $this->County=new County();
		 $cot_id=$this->County->query("select id from counties where countyname='$cot_name'");
		 $ct_id=$cot_id[0]['counties']['id'];
		App::import('model','AdvertiserProfile');	
		$this->ad_pro=new AdvertiserProfile();
		$ad_profile=$this->ad_pro->query("select ap.coupon,ap.coupon2 from advertiser_profiles ap,top_ten_businesses tp where ap.id=tp.advertiser_profile_id  and ap.coupon!='' and  ap.county='$ct_id' and tp.publish='yes'");
		//pr($ad_profile);die;
		return $ad_profile;
			
		}
/*End Function -------------------------------------------------------------------------------------------------------------------------------------------------------*/		
		
		
		
/*Start Function --Get All The Banner Of Particular County -----------------------------------------------------------------------------------------------------------*/	
  function getAllCountyBanner($params)
	{           
	            
	            $scat_url=$params['pass'][3]; 
				App::import('model','Subcategory');
				$this->scat=new  Subcategory();
				$scat_id=$this->scat->query("select id from subcategories where page_url='$scat_url'");
				$s_id=$scat_id[0]['subcategories']['id'];
				
				$county_name=$params['pass'][1]; 
	            App::import('model','County');
				$this->County=new County();
				$c_name=$this->County->query("select id from counties where countyname='$county_name'");
				$c_id=$c_name[0]['counties']['id'];
				App::import('model','AdvertiserProfile');
				$this->ad_pro=new AdvertiserProfile();
				$coupon_details=$this->ad_pro->query("select ap.coupon,ap.coupon2,ap.company_name,ap.page_url,city.cityname,city.page_url from advertiser_profiles ap,cities city where ap.city=city.id and ap.county='$c_id'  and ap.publish='yes' and ap.coupon!='' and ap.subcategory LIKE Concat('%,',$s_id,',%')");
				
			
				return  $coupon_details;
			
	}
	

/*End Function -------------------------------------------------------------------------------------------------------------------------------------------------------*/				
			
/*Start Function--Get The Video---------------------------------------------------------------------------------------------------------------------------------------*/			
   function getVideo($params)
   {
			        $city_name=$params['pass'][2];
					$company_url=$params['pass'][5];
					App::import('model','City');
					$this->City=new City();
					$city=$this->City->query("Select id from cities where page_url='$city_name'");
					if(count($city)>0)
					{
				
								$city_id=$city[0]['cities']['id'];
								App::import('model','AdvertiserProfile');
								$this->ad_profile=new AdvertiserProfile();
							    $pro=$this->ad_profile->query("Select id from advertiser_profiles where city='$city_id' and page_url='$company_url'");
								if(count($pro)>0)
								{
								$pro_id=$pro[0]['advertiser_profiles']['id'];
								App::import('model','Video');
								$this->video=new Video();
								$video=$this->video->query("Select utube_link,file_name from videos where advertiser_profile_id='$pro_id'");
								return $video; 
							}
					}
					return false;
	 }
			
/*End Function-------------------------------------------------------------------------------------------------------------------------------------------------------*/


/*Start -Checking valid city name-----------------------------------------------------------------------------------------------------------------------------------*/
	function chkCity($params)
	{
				//pr($params);die;
				$cot_name=$params['pass'][1];
				$cty_name=$params['pass'][2];
				App::import('model','County');
				$this->County=new County();
				$cot_id=$this->County->query("select id from counties where countyname='$cot_name'");
				$county_id=$cot_id[0]['counties']['id'];
				App::import('model','City');
				$this->City=new City();
				$city=$this->City->query("select * from cities where county_id='$county_id' and page_url='$cty_name'");
				return $city;
	}
				

/*End Function-----------------------------------------------------------------------------------------------------------------------------------------------------*/	

//used to validate the county and city
 	function validateCity($params)
	{			
				$state_name=$params['pass'][0];
				App::import('model','State');
				$this->State=new State();
				$s_id=$this->State->query("select id from states where page_url='$state_name'");
				$state_id=$s_id[0]['states']['id'];
				
				$cot_name=$params['pass'][1];
				$cty_name=$params['pass'][2];
				App::import('model','County');
				$this->County=new County();
				$cot_id=$this->County->query("select id from counties where state_id='$state_id' and page_url='$cot_name'");
				$county_id=$cot_id[0]['counties']['id'];
				App::import('model','City');
				$this->City=new City();
				$city=$this->City->query("select * from cities where county_id='$county_id' and page_url='$cty_name'");				
				if(!empty($city))
					return 1;
				else
					return 0;
				
	}
		     
/*Start Function------Get all company details----------------------------------------------------------------------------------------------------------------------*/
	function getCompanyDetails($params)
	{
	   
	   $city_detail=$this->getCityDetails($params['pass'][2]);
	   $page_url=$params['pass'][5];
       $city_id=$city_detail['City']['id'];
		//echo $city_id;die;
		App::import('model','AdvertiserProfile');
		$this->ad_pro=new AdvertiserProfile();
		 $company_details=$this->ad_pro->query("select * from advertiser_profiles where city='$city_id' and page_url='$page_url' and publish='yes'");
	  
	   return $company_details;
	  
	}
	
/*End Function----------------------------------------------------------------------------------------------------------------------------------------------------*/	 	
	function getCatName($params)
	{
	$subcategory_detail=$this->getSubcategoryDetails($params);
	return $scat_name=$subcategory_detail['Subcategory']['categoryname'];
	} 
	
	function pageDetails($id)
	{
	 App::import('model','Article');
	 $this->art=new Article();
	 $page_details=$this->art->query("select * from articles where id='$id' and published='yes'");
	 
	 return $page_details;
	}
	
	function pageDetailsUrl($url)
	{
	 App::import('model','Article');
	 $this->art=new Article();
	 $page_details=$this->art->query("select * from articles where page_url='$url' and published='yes'");
	 
	 return $page_details;
	}
	/*Function for getting title*/
	    function getTitle($params)
	     { 
	  $county_detail=$this->getCountyDetails($params['pass'][1]);
	  $state_detail=$this->getStateDetails($params['pass'][0]);
	  if(count($params['pass'])=='2')
	  {
	   $title=$county_detail=$this->getCountyDetails($params['pass'][1]);
	  }
	  elseif(count($params['pass'])=='4')
	  {
		 $category_detail=$this->getCategoryDetails($params['pass'][2]);
		 $subcategory_detail=$this->getSubcategoryDetails($params['pass'][3]);
		  
		  App::import('Model','Meta');
		  $this->meta=new Meta();
		 $meta_details= $this->meta->find('first',array('conditions'=>array('Meta.county_id'=>$county_detail['County']['id'],'Meta.subcategory_id'=>$subcategory_detail['Subcategory']['id'])));
	
	 if($meta_details['Meta']['meta_title']!='')
		 {
		 $title=$meta_details['Meta']['meta_title'].' - '.$category_detail['Category']['categoryname'].' - '.$county_detail['County']['meta_title'].' - '.$state_detail['State']['statename'];
		 }
		 else
		 {
		 $title=$subcategory_detail['Subcategory']['meta_title'].' - '.$category_detail['Category']['categoryname'].' - '.$county_detail['County']['meta_title'].' - '.$state_detail['State']['statename'];
		 }
			  
	  }
		  elseif(count($params['pass'])=='5')
			 {
				 $city_detail=$this->getCityDetails($params['pass'][2]);
				 $category_detail=$this->getCategoryDetails($params['pass'][3]);
				// pr($category_detail);die;
				 $subcategory_detail=$this->getSubcategoryDetails($params['pass'][4]);
				 App::import('Model','Meta');
				 $this->meta=new Meta();
				$meta_details= $this->meta->find('first',array('conditions'=>array('Meta.city_id'=>$city_detail['City']['id'],'Meta.subcategory_id'=>$subcategory_detail['Subcategory']['id'])));
								 
				 if($meta_details['Meta']['meta_title']!='')
				 {
				 $title=$meta_details['Meta']['meta_title'].' - '.$category_detail['Category']['categoryname'].' - '.$subcategory_detail['Subcategory']['categoryname'].' - '.$county_detail['County']['meta_title'].' - '.$state_detail['State']['statename'];
				 }
				 else
				 {
				  $title=$city_detail['City']['meta_title'].' - '.$category_detail['Category']['categoryname'].' - '.$subcategory_detail['Subcategory']['categoryname'].' - '.$county_detail['County']['meta_title'].' - '.$state_detail['State']['statename'];				  
				 }			 
			 }
			elseif(count($params['pass'])=='6')
				 {
				      App::import('model','AdvertiserProfile');
		              $this->ad_pro=new AdvertiserProfile();
					
					  $city_detail=$this->getCityDetails($params['pass'][2]);
					  $category_detail=$this->getCategoryDetails($params['pass'][3]);
					 // pr($category_detail);die;
					  $subcategory_detail=$this->getSubcategoryDetails($params['pass'][4]);
					  $com_url=$params['pass'][5];
					  $comp_name=$this->ad_pro->query("select company_name from advertiser_profiles where page_url='$com_url'");
		 $title=$comp_name[0]['advertiser_profiles']['company_name'].' - '.$subcategory_detail['Subcategory']['categoryname'].' - '.$category_detail['Category']['categoryname'].' - '.$city_detail['City']['cityname'].' - '.$county_detail['County']['countyname'].' - '.$state_detail['State']['statename'];
				 }
				 elseif(count($params['pass'])=='7')
				 {
				       App::import('model','AdvertiserProfile');
		               $this->ad_pro=new AdvertiserProfile();
					
					   $city_detail=$this->getCityDetails($params['pass'][2]);
					   $category_detail=$this->getCategoryDetails($params['pass'][3]);
					 // pr($category_detail);die;
					  $subcategory_detail=$this->getSubcategoryDetails($params['pass'][4]);
					  $com_url=$params['pass'][5];
					  $comp_name=$this->ad_pro->query("select company_name from advertiser_profiles where page_url='$com_url'");
		 $title=$comp_name[0]['advertiser_profiles']['company_name'].' - '.$subcategory_detail['Subcategory']['categoryname'].' - '.$category_detail['Category']['categoryname'].' - '.$city_detail['City']['cityname'].' - '.$county_detail['County']['countyname'].' - '.$state_detail['State']['statename'];
				 }
		 return $title;
		}
		
		/*Function for getting keyword*/
		function getKeyWord($params)
	    {
		     	 if(count($params['pass'])=='2')
					 {
					 $county_detail=$this->getCountyDetails($params['pass'][1]);
					  $key=$county_detail['County']['meta_keyword'];
					  //$key=$params['pass'][1];
					 }
					 
					 elseif(count($params['pass'])=='4')
					 {
					  $county_detail=$this->getCountyDetails($params['pass'][1]);
					  $subcategory_detail=$this->getSubcategoryDetails($params['pass'][3]);
					  App::import('Model','Meta');
					  $this->meta=new Meta();
					$meta_details= $this->meta->find('first',array('conditions'=>array('Meta.county_id'=>$county_detail['County']['id'],'Meta.subcategory_id'=>$subcategory_detail['Subcategory']['id'])));
				 
						  if($meta_details['Meta']['meta_keyword']!='')
						  {
						 $key=$meta_details['Meta']['meta_keyword'];
						  }
						 else
						 {
						$key=$subcategory_detail['Subcategory']['meta_keyword'];
						 }				
			    	 }
					elseif(count($params['pass'])=='5')
					 {
					  $city_detail=$this->getCityDetails($params['pass'][2]);
					  $subcategory_detail=$this->getSubcategoryDetails($params['pass'][4]);
					  App::import('Model','Meta');
					  $this->meta=new Meta();
					$meta_details= $this->meta->find('first',array('conditions'=>array('Meta.city_id'=>$city_detail['City']['id'],'Meta.subcategory_id'=>$subcategory_detail['Subcategory']['id'])));
				 
				 	  if($meta_details['Meta']['meta_keyword']!='')
						  {
						  $key=$meta_details['Meta']['meta_keyword'];
						  }
						 else
						 {
						$key=$city_detail['City']['meta_keyword'];
						 }				
			    	 }
										 
					 return $key;
		}
		
		/*----------Function for getting Page Description (Meta)(manoj)-------------------------------------------------------*/
		function getDescription($params)
		{
			$cond='';
			// 1.set the Title for home page OR set the Title for topten search page(when searching by business name)
			if((count($params['pass'])==2 && !isset($params['pass'][2])) || (count($params['pass'])==4 && $params['pass'][2]=='business' && $params['pass'][3]!='coupon'))
			{
						$title='';
						 App::import('model','County'); 
						 $this->County=new County();
						 $cond['County.page_url']=$params['pass'][1];
						 $title=$this->County->find('first',array('conditions'=>$cond));
						 if(!empty($title))
						 {
							 if($title['County']['description']!='')				
							 {
								return $title['County']['description'];
							 }
							 else
							 {
								return $title['County']['description'];
							 }
						 }
										
			}
			
			// 2.set the Title for merchant page
			elseif(count($params['pass'])==6 || (count($params['pass'])==5 && $this->chkCompanyUrl($params['pass'][4])!='0'))
			{
					$title='';
					 if(count($params['pass'])==6)
					 {
						 App::import('model','AdvertiserProfile'); 
						 $this->AdvertiserProfile=new AdvertiserProfile();
						 $cond['AdvertiserProfile.page_url']=$params['pass'][5];
						 $title_main=$this->AdvertiserProfile->find('first',array('conditions'=>$cond));
						 if(!empty($title_main) && $title_main['AdvertiserProfile']['description']!='')				
							return $title_main['AdvertiserProfile']['description'];
						 else
						 {
							 $cond='';
							 $title='';
							 $meta_title='';
							 App::import('model','Meta'); 
							 $this->Meta=new Meta();
							 $county_id=$this->getIdfromPageUrl('County',$params['pass'][1]);
							 $county_id= $county_id['County']['id'];
							 $city_id=$this->getIdfromPageUrl('City',$params['pass'][2]);
							 $city_id= $city_id['City']['id'];
							 $subcat_id=$this->getIdfromPageUrl('Subcategory',$params['pass'][4]);
							 $subcat_id=$subcat_id['Subcategory']['id'];
							 $cond['Meta.subcategory_id']=$subcat_id;
							 $cond['Meta.city_id']=$city_id;
							 $title=$this->Meta->find('first',array('conditions'=>$cond));
							 if(!empty($title) && $title['Meta']['meta_description']!='')
								 return $title['Meta']['meta_description'];	
							 else
							 {
								 $cond='';
								 $title='';
								 $meta_title='';
								 $cond['Meta.subcategory_id']=$subcat_id;
								 $cond['Meta.county_id']=$county_id;
								 $title=$this->Meta->find('first',array('conditions'=>$cond));
								 if(!empty($title) && $title['Meta']['meta_description']!='')
									 return $title['Meta']['meta_description'];
								 else
								 {
									return $title_main['AdvertiserProfile']['company_name'];							 
								 }							 						 
							 }				 
						 }
					 }
					 elseif(count($params['pass'])==5)
					 {
						 App::import('model','AdvertiserProfile'); 
						 $this->AdvertiserProfile=new AdvertiserProfile();
						 $cond['AdvertiserProfile.page_url']=$params['pass'][4];
						 $title=$this->AdvertiserProfile->find('first',array('conditions'=>$cond));
						 if(!empty($title))				
							return $title['AdvertiserProfile']['company_name'];				 	
					 }
	
			}
			// 3.set the Title for topten search page(when searching by category OR city OR Both)
			elseif(count($params['pass'])==4 || count($params['pass'])==5 && $params['pass'][2]!='business')
			{	
				$title='';
				if(count($params['pass'])==4)
				{
					 App::import('model','Subcategory'); 
					 $this->Subcategory=new Subcategory();
					 $cond['Subcategory.page_url']=$params['pass'][3];
					 $title=$this->Subcategory->find('first',array('conditions'=>$cond));
					 if(!empty($title) && $title['Subcategory']['meta_description']!='')
					 {
							return $title['Subcategory']['meta_description'];
					 }
					 else
					 {
						 $cond='';
						 $title_meta='';
						 App::import('model','Meta'); 
						 $this->Meta=new Meta();
						 $county_id=$this->getIdfromPageUrl('County',$params['pass'][1]);
						 $county_id= $county_id['County']['id'];
						 $subcat_id=$this->getIdfromPageUrl('Subcategory',$params['pass'][3]);
						 $subcat_id=$subcat_id['Subcategory']['id'];
						 $cond['Meta.subcategory_id']=$subcat_id;
						 $cond['Meta.county_id']=$county_id;
						 $title_meta=$this->Meta->find('first',array('conditions'=>$cond));
						 if(!empty($title_meta) && $title_meta['Meta']['meta_description'])
							return $title_meta['Meta']['meta_description'];
						 elseif($title['Subcategory']['categoryname']!='')
							return $title['Subcategory']['categoryname'];
											 
					 }
										
				}
				elseif(count($params['pass'])==5)
				{
					 $cond='';
					 $title='';
					 $meta_title='';
					 App::import('model','Meta'); 
					 $this->Meta=new Meta();
					 $county_id=$this->getIdfromPageUrl('County',$params['pass'][1]);
					 $county_id= $county_id['County']['id'];
					 $city_id=$this->getIdfromPageUrl('City',$params['pass'][2]);
					 $city_id= $city_id['City']['id'];
					 $subcat_id=$this->getIdfromPageUrl('Subcategory',$params['pass'][4]);
					 $subcat_id=$subcat_id['Subcategory']['id'];
					 $cond['Meta.subcategory_id']=$subcat_id;
					 $cond['Meta.city_id']=$city_id;
					 $title=$this->Meta->find('first',array('conditions'=>$cond));
					 if(!empty($title) && $title['Meta']['meta_description']!='')
						 return $title['Meta']['meta_description'];	
					 else
					 {
						 $cond='';
						 $title_meta='';
						 $cond['Meta.subcategory_id']=$subcat_id;
						 $cond['Meta.county_id']=$county_id;
						 $title_meta=$this->Meta->find('first',array('conditions'=>$cond));
						 if(!empty($title_meta) && $title_meta['Meta']['meta_description'])
							return $title_meta['Meta']['meta_description'];
						 else
						 {
							$title='';
							$cond='';
							 App::import('model','Subcategory'); 
							 $this->Subcategory=new Subcategory();
							 $cond['Subcategory.page_url']=$params['pass'][4];
							 $title=$this->Subcategory->find('first',array('conditions'=>$cond));
							 if(!empty($title) && $title['Subcategory']['meta_description']!='')
							 {
									return $title['Subcategory']['meta_description'];
							 }
							 else
							 {
									return $title['Subcategory']['categoryname'];
							 }		 
						 }				 
					 }	
				}
			}		
	
		
		}
		
		

		/*---------------------------------Function for getting page title (Meta)(manoj)-----------------------------------------*/
		function getPageTitle($params)
	    {
		$cond='';
		// 1.set the Title for home page OR set the Title for topten search page(when searching by business name)
		if((count($params['pass'])==2 && !isset($params['pass'][2])) || (count($params['pass'])==4 && $params['pass'][2]=='business' && $params['pass'][3]!='coupon'))
		{
					$title='';
					 App::import('model','County'); 
					 $this->County=new County();
					 $cond['County.page_url']=$params['pass'][1];
 					 $title=$this->County->find('first',array('conditions'=>$cond));
					 if($title['County']['meta_title']!='')				
					 {
						return strtoupper($title['County']['meta_title']);
					 }
					 else
					 {
					 	return strtoupper($title['County']['countyname']);
					 }
					 				
		}
		
		// 2.set the Title for merchant page
		elseif(count($params['pass'])==6 || (count($params['pass'])==5 && $this->chkCompanyUrl($params['pass'][4])!='0'))
		{
				$title='';
				 if(count($params['pass'])==6)
				 {
					 App::import('model','AdvertiserProfile'); 
					 $this->AdvertiserProfile=new AdvertiserProfile();
					 $cond['AdvertiserProfile.page_url']=$params['pass'][5];
					 $title=$this->AdvertiserProfile->find('first',array('conditions'=>$cond));
					 if(!empty($title))				
						return strtoupper($title['AdvertiserProfile']['company_name']);
				 }
				 elseif(count($params['pass'])==5)
				 {
					 App::import('model','AdvertiserProfile'); 
					 $this->AdvertiserProfile=new AdvertiserProfile();
					 $cond['AdvertiserProfile.page_url']=$params['pass'][4];
					 $title=$this->AdvertiserProfile->find('first',array('conditions'=>$cond));
					 if(!empty($title))				
						return strtoupper($title['AdvertiserProfile']['company_name']);				 	
				 }

		}
		// 3.set the Title for topten search page(when searching by category OR city OR Both)
		elseif(count($params['pass'])==4 || count($params['pass'])==5 && $params['pass'][2]!='business')
		{	
			$title='';
			if(count($params['pass'])==4)
			{
				 App::import('model','Subcategory'); 
				 $this->Subcategory=new Subcategory();
				 $cond['Subcategory.page_url']=$params['pass'][3];
				 $title=$this->Subcategory->find('first',array('conditions'=>$cond));
				 if(!empty($title) && $title['Subcategory']['meta_title']!='')
				 {
						return strtoupper($title['Subcategory']['meta_title']);
				 }
				 else
				 {
					 $cond='';
					 $title_meta='';
					 App::import('model','Meta'); 
					 $this->Meta=new Meta();
					 $county_id=$this->getIdfromPageUrl('County',$params['pass'][1]);
					 $county_id= $county_id['County']['id'];
					 $subcat_id=$this->getIdfromPageUrl('Subcategory',$params['pass'][3]);
					 $subcat_id=$subcat_id['Subcategory']['id'];
					 $cond['Meta.subcategory_id']=$subcat_id;
					 $cond['Meta.county_id']=$county_id;
					 $title_meta=$this->Meta->find('first',array('conditions'=>$cond));
					 if(!empty($title_meta) && $title_meta['Meta']['meta_title'])
						return strtoupper($title_meta['Meta']['meta_title']);
					 elseif($title['Subcategory']['categoryname']!='')
						return strtoupper($title['Subcategory']['categoryname']);
										 
				 }
					 				
			}
			elseif(count($params['pass'])==5)
			{
				 $cond='';
				 $title='';
				 $meta_title='';
				 App::import('model','Meta'); 
				 $this->Meta=new Meta();
				 $county_id=$this->getIdfromPageUrl('County',$params['pass'][1]);
				 $county_id= $county_id['County']['id'];
				 $city_id=$this->getIdfromPageUrl('City',$params['pass'][2]);
				 $city_id= $city_id['City']['id'];
				 $subcat_id=$this->getIdfromPageUrl('Subcategory',$params['pass'][4]);
				 $subcat_id=$subcat_id['Subcategory']['id'];
				 $cond['Meta.subcategory_id']=$subcat_id;
				 $cond['Meta.city_id']=$city_id;
				 $title=$this->Meta->find('first',array('conditions'=>$cond));
				 if(!empty($title) && $title['Meta']['meta_title']!='')
				     return strtoupper($title['Meta']['meta_title']);	
				 else
				 {
					 $cond='';
					 $title_meta='';
					 $cond['Meta.subcategory_id']=$subcat_id;
					 $cond['Meta.county_id']=$county_id;
					 $title_meta=$this->Meta->find('first',array('conditions'=>$cond));
					 if(!empty($title_meta) && $title_meta['Meta']['meta_title'])
						return strtoupper($title_meta['Meta']['meta_title']);
					 else
					 {
						$title='';
						$cond='';
						 App::import('model','Subcategory'); 
						 $this->Subcategory=new Subcategory();
						 $cond['Subcategory.page_url']=$params['pass'][4];
						 $title=$this->Subcategory->find('first',array('conditions'=>$cond));
						 if(!empty($title) && $title['Subcategory']['meta_title']!='')
						 {
								return strtoupper($title['Subcategory']['meta_title']);
						 }
						 else
						 {
						 		return strtoupper($title['Subcategory']['categoryname']);
						 }		 
					 }				 
				 }	
			}
		}		

}
		
/*-----------------------------------Function for getting page keyword(meta)--(manoj)----------------------------------------------------------------------*/
		function getPageKeyWord($params)
	    {
		$cond='';
		// 1.set the Title for home page OR set the Title for topten search page(when searching by business name)
		if((count($params['pass'])==2 && !isset($params['pass'][2])) || (count($params['pass'])==4 && $params['pass'][2]=='business' && $params['pass'][3]!='coupon'))
		{
					$title='';
					 App::import('model','County'); 
					 $this->County=new County();
					 $cond['County.page_url']=$params['pass'][1];
 					 $title=$this->County->find('first',array('conditions'=>$cond));
					 if(!empty($title) && $title['County']['countyname']!='')
					 {
						 if($title['County']['meta_keyword']!='')				
						 {
							return $title['County']['meta_keyword'];
						 }
						 else
						 {
							return $title['County']['countyname'];
						 }
					 }
					 				
		}
		
		// 2.set the Title for merchant page
		elseif(count($params['pass'])==6 || (count($params['pass'])==5 && $this->chkCompanyUrl($params['pass'][4])!='0'))
		{
				$title='';
				 if(count($params['pass'])==6)
				 {
					 App::import('model','AdvertiserProfile'); 
					 $this->AdvertiserProfile=new AdvertiserProfile();
					 $cond['AdvertiserProfile.page_url']=$params['pass'][5];
					 $title_main=$this->AdvertiserProfile->find('first',array('conditions'=>$cond));
					 if(!empty($title_main) && $title_main['AdvertiserProfile']['keyword']!='')				
						return $title_main['AdvertiserProfile']['keyword'];
					 else
					 {
						 $cond='';
						 $title='';
						 $meta_title='';
						 App::import('model','Meta'); 
						 $this->Meta=new Meta();
						 $county_id=$this->getIdfromPageUrl('County',$params['pass'][1]);
						 $county_id= $county_id['County']['id'];
						 $city_id=$this->getIdfromPageUrl('City',$params['pass'][2]);
						 $city_id= $city_id['City']['id'];
						 $subcat_id=$this->getIdfromPageUrl('Subcategory',$params['pass'][4]);
						 $subcat_id=$subcat_id['Subcategory']['id'];
						 $cond['Meta.subcategory_id']=$subcat_id;
						 $cond['Meta.city_id']=$city_id;
						 $title=$this->Meta->find('first',array('conditions'=>$cond));
						 if(!empty($title) && $title['Meta']['meta_keyword']!='')
							 return $title['Meta']['meta_keyword'];	
						 else
						 {
							 $cond='';
							 $title='';
							 $meta_title='';
							 $cond['Meta.subcategory_id']=$subcat_id;
							 $cond['Meta.county_id']=$county_id;
							 $title=$this->Meta->find('first',array('conditions'=>$cond));
							 if(!empty($title) && $title['Meta']['meta_keyword']!='')
								 return $title['Meta']['meta_keyword'];
							 else
							 {
								return $title_main['AdvertiserProfile']['company_name'];							 
							 }							 						 
						 }				 
					 }
				 }
				 elseif(count($params['pass'])==5)
				 {
					 App::import('model','AdvertiserProfile'); 
					 $this->AdvertiserProfile=new AdvertiserProfile();
					 $cond['AdvertiserProfile.page_url']=$params['pass'][4];
					 $title=$this->AdvertiserProfile->find('first',array('conditions'=>$cond));
					 if(!empty($title))				
						return $title['AdvertiserProfile']['company_name'];				 	
				 }

		}
		// 3.set the Title for topten search page(when searching by category OR city OR Both)
		elseif(count($params['pass'])==4 || count($params['pass'])==5 && $params['pass'][2]!='business')
		{	
			$title='';
			if(count($params['pass'])==4)
			{
				 App::import('model','Subcategory'); 
				 $this->Subcategory=new Subcategory();
				 $cond['Subcategory.page_url']=$params['pass'][3];
				 $title=$this->Subcategory->find('first',array('conditions'=>$cond));
				 if(!empty($title) && $title['Subcategory']['meta_keyword']!='')
				 {
						return $title['Subcategory']['meta_keyword'];
				 }
				 else
				 {
					 $cond='';
					 $title_meta='';
					 App::import('model','Meta'); 
					 $this->Meta=new Meta();
					 $county_id=$this->getIdfromPageUrl('County',$params['pass'][1]);
					 $county_id= $county_id['County']['id'];
					 $subcat_id=$this->getIdfromPageUrl('Subcategory',$params['pass'][3]);
					 $subcat_id=$subcat_id['Subcategory']['id'];
					 $cond['Meta.subcategory_id']=$subcat_id;
					 $cond['Meta.county_id']=$county_id;
					 $title_meta=$this->Meta->find('first',array('conditions'=>$cond));
					 if(!empty($title_meta) && $title_meta['Meta']['meta_keyword'])
						return $title_meta['Meta']['meta_keyword'];
					 elseif($title['Subcategory']['categoryname']!='')
						return $title['Subcategory']['categoryname'];
										 
				 }
					 				
			}
			elseif(count($params['pass'])==5)
			{
				 $cond='';
				 $title='';
				 $meta_title='';
				 App::import('model','Meta'); 
				 $this->Meta=new Meta();
				 $county_id=$this->getIdfromPageUrl('County',$params['pass'][1]);
				 $county_id= $county_id['County']['id'];
				 $city_id=$this->getIdfromPageUrl('City',$params['pass'][2]);
				 $city_id= $city_id['City']['id'];
				 $subcat_id=$this->getIdfromPageUrl('Subcategory',$params['pass'][4]);
				 $subcat_id=$subcat_id['Subcategory']['id'];
				 $cond['Meta.subcategory_id']=$subcat_id;
				 $cond['Meta.city_id']=$city_id;
				 $title=$this->Meta->find('first',array('conditions'=>$cond));
				 if(!empty($title) && $title['Meta']['meta_keyword']!='')
				     return $title['Meta']['meta_keyword'];	
				 else
				 {
					 $cond='';
					 $title_meta='';
					 $cond['Meta.subcategory_id']=$subcat_id;
					 $cond['Meta.county_id']=$county_id;
					 $title_meta=$this->Meta->find('first',array('conditions'=>$cond));
					 if(!empty($title_meta) && $title_meta['Meta']['meta_keyword'])
						return $title_meta['Meta']['meta_keyword'];
					 else
					 {
						$title='';
						$cond='';
						 App::import('model','Subcategory');
						 $this->Subcategory=new Subcategory();
						 $cond['Subcategory.page_url']=$params['pass'][4];
						 $title=$this->Subcategory->find('first',array('conditions'=>$cond));
						 if(!empty($title) && $title['Subcategory']['meta_keyword']!='')
						 {
								return $title['Subcategory']['meta_keyword'];
						 }
						 else
						 {
						 		return $title['Subcategory']['categoryname'];
						}		 
					}				 
				}	
			}
		}
	}
/*Start function-For Checking Correct Company Name Url---------------------------------------------------------------------------------------------------------------*/			
		 function chkCompanyUrl($comp_url)
		 {
		 App::import('model','AdvertiserProfile');
		$this->company=$this->ad_pro=new AdvertiserProfile();
		$company=$this->company->query("select id from advertiser_profiles where page_url='$comp_url'");
		
		 if(count($company)>0)
		  {
		    	 return 1;
		  }
		  else
			{
			  return 0;
			}
		 }
		 
/*End---------------------------------------------------------------------------------------------------------------------------------------------------------------*/	
		function getMapDetails($params)
		{
			$company_url=$params['pass'][5];
			App::import('model','AdvertiserProfile');
			$this->ad_pro=new AdvertiserProfile();
			$company_details=$this->ad_pro->query("select company_name,city,county,state,email,phoneno,zip,address,name from advertiser_profiles where page_url='$company_url' and publish='yes'");
			return $company_details;
		}
		
		
	        function getPageUrl($model,$id)
			{
	
	 		App::import('model',$model);
		    $this->$model = new $model(); 
			
			$urllist = $this->$model->find('list', array('fields' => array('page_url'),'conditions' => array("$model.id" => $id))); 
			
			return $urllist;
	        }	
	//---------------------------------------------------------------------------------//
	        function getPageUrlById($model,$id)
			{
	
	 		App::import('model',$model);
		    $this->$model = new $model(); 
			
			$pageUrl = $this->$model->find('first', array('fields' => array('page_url'),'conditions' => array("$model.id" => $id))); 
			
			return $pageUrl[$model]['page_url'];
	        }
		  
	//this function is used to show all categories and subcategoris listbox at banner add and edit form	  
	function getMultiCombBox($scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='<select  name="data[Banner][category_id][]" id="category_id" class="search_input_combo" style="width:300px;" size="9" tabindex="8" multiple="multiple">';
		if($scatname=="")
				 {
				 	$sel ='selected="selected"';
				 }else{
				 	$sel ='';
				 }
				 	$combo.='<option value="" '.$sel.'>Select Category</option>';

		foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                $combo.='<optgroup label="'.$category['categories']['categoryname'].'" >';
				$id=$category['categories']['id'];
				$data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['id']!='')
								{
								$scateId = explode(',',$scatname);
								if(in_array($subCat['Subcategory']['id'],$scateId))
								{
									$combo.='<option value="'.$subCat['Subcategory']['id'].'" selected="selected">&nbsp;&nbsp;&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								else
								{
									$combo.='<option value="'.$subCat['Subcategory']['id'].'">&nbsp;&nbsp;&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								}
					}
					
				}
				$combo.='</optgroup></select>';
				return $combo;
		}
		
		
		function getOffer($params)
		{
			 $offer_id=$params['pass'][6];
			 App::import('model','Offer');
			 $this->off=new Offer();
			 $offer_detail=$this->off->query("select * from offers where id='$offer_id' and status='yes'");
			 return  $offer_detail;
        }  
	
		/*-------------------------------------------------Graph------------------------------------------------------------------------------------------------*/
		
		
		
		function getCatList($scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='<select  name="business" id="business" class="search_input_combo" tooltipText="Select category in which you want to search." style="width:280px;">';
		if($scatname=="")
				 {
				 $combo.='<option value="0" selected="selected">Select Subcategory</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.$category['categories']['categoryname'].'" >';
				 $id=$category['categories']['id'];
				 $data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				 
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['categoryname']!='')
								{
								if(strtolower($scatname)==strtolower($subCat['Subcategory']['categoryname']))
								{
								$combo.='<option value="'.$id.'-'.$subCat['Subcategory']['id'].'" selected="selected">&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								else
								{
								$combo.='<option value="'.$id.'-'.$subCat['Subcategory']['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
							}
						}
				}
				$combo.='</optgroup></select>';
				return $combo;

		}
		
		
		function getCatoptions($scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='';
		if($scatname=="")
				 {
				 $combo.='<option value="0" selected="selected">Select Subcategory</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.$category['categories']['categoryname'].'" >';
				 $id=$category['categories']['id'];
				 $data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				 
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['categoryname']!='')
								{
								if(is_array($scatname) && in_array($subCat['Subcategory']['id'],$scatname))
								{
								$combo.='<option value="'.$id.'-'.$subCat['Subcategory']['id'].'" selected="selected">&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								else
								{
								$combo.='<option  value="'.$id.'-'.$subCat['Subcategory']['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								}
					}					
				}
				$combo.='</optgroup>';
				return $combo;
		}
		
		function getCatoptions_sales($scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='';
		if($scatname=="")
				 {
				 $combo.='<option value="0" selected="selected">Select Subcategory</option>';
				 }
		foreach($this->Category->query("select * from categories ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.$category['categories']['categoryname'].'" >';
				 $id=$category['categories']['id'];
				 $data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				 
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['categoryname']!='')
								{
								if(is_array($scatname) && in_array($subCat['Subcategory']['id'],$scatname))
								{
								$combo.='<option value="'.$id.'-'.$subCat['Subcategory']['id'].'" selected="selected">&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								else
								{
								$combo.='<option  value="'.$id.'-'.$subCat['Subcategory']['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								}
					}					
				}
				$combo.='</optgroup>';
				return $combo;
		}
		
		
		function getCatoptions_sales_order($scatname="",$county="")
		{
		App::import('model','CountyCategory');
		$this->CountyCategory = new CountyCategory();
		App::import('model','Subcategory');
		$this->Subcategory = new Subcategory();
		App::import('model','CountiesCategoriesSubcategory');
		$this->CountiesCategoriesSubcategory = new CountiesCategoriesSubcategory();
		
		
		$combo='';
		if($scatname=="")
				 {
				 	$combo.='<option value="0" selected="selected">Select Subcategory</option>';
				 }
				 $data = $this->CountyCategory->find('all',array('fields'=>array('DISTINCT Category.id','Category.page_url','Category.categoryname'),'conditions'=>array('CountyCategory.county_id'=>$county,'Category.publish'=>'yes'),'order'=>array('Category.order,Category.categoryname')));
				 
		foreach($data as $category)
		   	{
                 $combo.='<optgroup label="'.$category['Category']['categoryname'].'" >';
				 $id=$category['Category']['id'];
				 
				 $sucats = array(0);
				$subdata = $this->CountiesCategoriesSubcategory->find('all',array('fields'=>array('DISTINCT CategoriesSubcategory.subcategory_id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'CountiesCategoriesSubcategory.county_id'=>$county)));
				foreach($subdata as $subdata) {
					$sucats[] = $subdata['CategoriesSubcategory']['subcategory_id'];
				}
				$subCat = $this->Subcategory->query("select * from subcategories where id IN(".implode(',',$sucats).") and publish='yes' ORDER BY `categoryname`,`categoryname` ASC");
				
				
				foreach($subCat as $subCat)
					{
					if($subCat['subcategories']['categoryname']!='')
						{
							if(is_array($scatname) && in_array($id.'-'.$subCat['subcategories']['id'],$scatname))
							{
								$combo.='<option value="'.$id.'-'.$subCat['subcategories']['id'].'" selected="selected">&nbsp;'.$subCat['subcategories']['categoryname'].'</option>';
							}
							else
							{
								$combo.='<option  value="'.$id.'-'.$subCat['subcategories']['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$subCat['subcategories']['categoryname'].'</option>';
							}
						}
					}					
				}
				$combo.='</optgroup>';
				return $combo;
		}
		
		function getCatoptions_register($scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='';
		if($scatname=="")
				 {
				 $combo.='<option value="0" selected="selected">Select Subcategory</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.$category['categories']['categoryname'].'" >';
				 $id=$category['categories']['id'];
				 $data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				 
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['categoryname']!='')
								{
								if(in_array($subCat['Subcategory']['id'],$scatname))
								{
								$combo.='<option value="'.$subCat['Subcategory']['id'].'" selected="selected">&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								else
								{
								$combo.='<option  value="'.$subCat['Subcategory']['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								}
					}					
				}
				$combo.='</optgroup>';
				return $combo;
		}
						
		function getCatoptions_front($scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='';
		if($scatname=="")
				 {
				 $combo.='<option value="0" selected="selected">Subcategory</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.strtoupper($category['categories']['categoryname']).'" >';
				 $id=$category['categories']['id'];
				 $data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				 
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['categoryname']!='')
								{
								if(strtolower($scatname)==strtolower($subCat['Subcategory']['page_url']))
								{
								$combo.='<option value="'.$category['categories']['page_url'].'/'.$subCat['Subcategory']['page_url'].'" selected="selected"><b>'.$subCat['Subcategory']['categoryname'].'</b></option>';
								}
								else
								{
								$combo.='<option style="font-weight:bold; width:400px;padding-left: 30px;" value="'.$category['categories']['page_url'].'/'.$subCat['Subcategory']['page_url'].'">&nbsp;<b>'.$subCat['Subcategory']['categoryname'].'</b></option>';
								}
								}
					}					
				}
				$combo.='</optgroup>';
				return $combo;
		}
		
		
		function getCatoptions_frontByCounty($scatname="",$county="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='';
		if($scatname=="")
				 {
				 $combo.='<option value="0" selected="selected">Subcategory</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' AND county LIKE '%,".$county.",%' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.strtoupper($category['categories']['categoryname']).'" >';
				 $id=$category['categories']['id'];
				 $data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				 
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['categoryname']!='')
								{
								if(strtolower($scatname)==strtolower($subCat['Subcategory']['page_url']))
								{
								$combo.='<option value="'.$category['categories']['page_url'].'/'.$subCat['Subcategory']['page_url'].'" selected="selected"><b>'.$subCat['Subcategory']['categoryname'].'</b></option>';
								}
								else
								{
								$combo.='<option style="font-weight:bold; width:400px;padding-left: 30px;" value="'.$category['categories']['page_url'].'/'.$subCat['Subcategory']['page_url'].'">&nbsp;<b>'.$subCat['Subcategory']['categoryname'].'</b></option>';
								}
								}
					}					
				}
				$combo.='</optgroup>';
				return $combo;
		}
		
		
		
		function getCatoptions_byCounty($county="",$scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','Subcategory');
		$this->Subcategory = new Subcategory();
		$combo='';
		if($scatname=="")
				 {
				 $combo.='<option value="0" selected="selected">What?</option>';
				 }else{
				 $combo.='<option value="0">What?</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' AND county LIKE '%,".$county.",%' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.strtoupper($category['categories']['categoryname']).'" >';
				 $id=$category['categories']['id'];
				foreach($this->Subcategory->query("select * from subcategories where publish='yes' and category_id LIKE Concat('%,',$id,',%') AND county LIKE '%,".$county.",%' ORDER BY `categoryname`,`categoryname` ASC") as $subCat)
					{
					if($subCat['subcategories']['categoryname']!='')
								{
								if(strtolower($scatname)==strtolower($subCat['subcategories']['page_url']))
								{
								$combo.='<option value="'.$category['categories']['page_url'].'/'.$subCat['subcategories']['page_url'].'" selected="selected"><b>'.$subCat['subcategories']['categoryname'].'</b></option>';
								}
								else
								{
								$combo.='<option value="'.$category['categories']['page_url'].'/'.$subCat['subcategories']['page_url'].'">&nbsp;<b>'.$subCat['subcategories']['categoryname'].'</b></option>';
								}
								}
					}					
				}
				$combo.='</optgroup>';
				return $combo;
		}
		
		function getCatoptions_meta($scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='';
			if($scatname=="")
				 {
				 	$combo.='<option value="" selected="selected">Subcategory</option>';
				 } else {
					 $combo.='<option value="">Subcategory</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.strtoupper($category['categories']['categoryname']).'" >';
				 $id=$category['categories']['id'];
				 
				 $data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				 
				 
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['categoryname']!='')
								{
								if(strtolower($scatname)==($id.'/'.$subCat['Subcategory']['id']))
								{
								$combo.='<option value="'.$id.'/'.$subCat['Subcategory']['id'].'" selected="selected"><b>'.$subCat['Subcategory']['categoryname'].'</b></option>';
								}
								else
								{
								$combo.='<option style="font-weight:bold; width:400px;padding-left: 30px;" value="'.$id.'/'.$subCat['Subcategory']['id'].'">&nbsp;<b>'.$subCat['Subcategory']['categoryname'].'</b></option>';
								}
								}
					}					
				}
				$combo.='</optgroup>';
				return $combo;
		}

		function getMultiCat($scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		$combo='';
		
		if($scatname=="")
				 {
				 $combo.='<option value="0" selected="selected">Select Category</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{

					if($category['categories']['categoryname']!='')
								{
								if(in_array($category['categories']['id'],$scatname))
								{
									$combo.='<option value="'.$category['categories']['id'].'" selected="selected">&nbsp;'.$category['categories']['categoryname'].'</option>';
								}
								else
								{
									$combo.='<option  value="'.$category['categories']['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$category['categories']['categoryname'].'</option>';
								}
								}
								
				}
				
				
				return $combo;
		}
				
	 function popularCat($params)
	 {
	 $county_url=$params['pass'][1];
	 App::import('model','County');
	 $this->county=new County();
	 $coty_id=$this->county->query("select id from counties where page_url='$county_url'");
	 $county_id=$coty_id[0]['counties']['id'];
	 
	 App::import('model','Report');
	 $this->report=new Report();
	
	 $category=$this->report->query("select cat.page_url,scat.page_url,scat.categoryname  from categories cat,subcategories scat,reports reports where reports.category=cat.id and reports.county='$county_id' and reports.subcategory=scat.id group by subcategory  order by COUNT(subcategory) desc LIMIT 0,3");
	
	
	 return $category;
	 }	
	 
function getSubcatCal($catname="", $scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='';
		if($scatname=="" && $catname=="")
				 {
				 $combo.='<option value="0" selected="selected">Select Subcategory</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.$category['categories']['categoryname'].'" >';
				 $id=$category['categories']['id'];
				 $data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				 
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['categoryname']!='')
								{
								if($subCat['Subcategory']['id']==$scatname && $id ==$catname)
								{
								$combo.='<option value="'.$id.'-'.$subCat['Subcategory']['id'].'" selected="selected">&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								else
								{
								$combo.='<option  value="'.$id.'-'.$subCat['Subcategory']['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$subCat['Subcategory']['categoryname'].'</option>';
								}
								}
					}					
				}
				$combo.='</optgroup>';
				return $combo;
		}	
		 
	 function popularCity($params)
	 {
	 $county_url=$params['pass'][1];
	 App::import('model','County');
	 $this->county=new County();
	 $coty_id=$this->county->query("select id from counties where page_url='$county_url'");
	 $county_id=$coty_id[0]['counties']['id'];
	 
		App::import('model','Report');
		$this->report=new Report();
	
	$category=$this->report->query("select city.page_url,city.cityname,cat.page_url,scat.page_url  from cities city,categories cat,subcategories scat,reports where reports.city=city.id and reports.county='$county_id' and reports.category=cat.id and reports.subcategory=scat.id group by city order by COUNT(city) desc LIMIT 0,3");
	
	return $category;
}
/* Function to return common seprated string */
	function arrayToCsvString($arr) {
	 	$csvStr = '';
		if(is_array($arr)) {
		  $arr = array_unique($arr);
		  foreach($arr as $val) {
			if($val) {
				$csvStr .= ($csvStr) ? ',' : '';
				$csvStr .= $val;
			}
		  }
		}
	  return $csvStr;
	}
	function adminDetails()
	{
		$admin=$this->Auth->user();
		$group_id=$admin['Admin']['user_group_id'];
		App::import('Model','UserGroup');
		$this->UserGroup=new UserGroup();
		$details=$this->UserGroup->find('all', array('conditions' => array('UserGroup.id' => $group_id)));
		//pr($details);die;
		if(isset($details[0]))
		{
			$permission=explode(',',$details[0]['UserGroup']['permissions']);
			//pr(array_unique($permission));die;
			return array_unique($permission);
		}
	}
	function groupName($gid){
	
		 App::import('Model','UserGroup');
		 $this->UserGroup = new UserGroup();
		 $gName=$this->UserGroup->query("select group_name from user_groups where id='$gid'");
		 $gNameShow=$gName[0]['user_groups']['group_name'];
		 return $gNameShow;
	}
	function getCountyDetails($page_url)
	{
		App::import('Model','County');
		$this->county=new County();
		$page_details=$this->county->find('first',array('conditions'=>array('page_url'=>$page_url)));
		return $page_details;
	}
	function getStateDetails($page_url)
	{
	App::import('Model','State');
	$this->state=new State();
	$page_details=$this->state->find('first',array('conditions'=>array('page_url'=>$page_url)));
	return $page_details;
	}
	
	function getCategoryDetails($page_url)
	{
	App::import('Model','Category');
	$this->category=new Category();
	$page_details=$this->category->find('first',array('conditions'=>array('page_url'=>$page_url)));
	
	return $page_details;
	}
	function getSubcategoryDetails($page_url)
	{
	App::import('Model','Subcategory');
	$this->subcategory=new Subcategory();
	$page_details=$this->subcategory->find('first',array('conditions'=>array('page_url'=>$page_url)));
	
	return $page_details;
	}	
	
	function getCityDetails($page_url)
	{
		App::import('Model','City');
		$this->city=new City();
		$page_details=$this->city->find('first',array('conditions'=>array('page_url'=>$page_url)));
		return $page_details;
	}
	
	function getCountyDetails_url($page_url)
	{
		App::import('Model','County');
		$this->county=new County();
		$page_details=$this->county->find('first',array('fields'=>array('countyname'),'conditions'=>array('page_url'=>$page_url)));
		return $page_details['County']['countyname'];
	}
	function getStateDetails_url($page_url)
	{
		App::import('Model','State');
		$this->state=new State();
		$page_details=$this->state->find('first',array('fields'=>array('statename'),'conditions'=>array('page_url'=>$page_url)));
		return $page_details['State']['statename'];
	}	
	function getCategoryDetails_url($page_url)
	{
		App::import('Model','Category');
		$this->category=new Category();
		$page_details=$this->category->find('first',array('fields'=>array('categoryname'),'conditions'=>array('page_url'=>$page_url)));	
		return $page_details['Category']['categoryname'];
	}
	function getSubcategoryDetails_url($page_url)
	{
	App::import('Model','Subcategory');
	$this->subcategory=new Subcategory();
	$page_details=$this->subcategory->find('first',array('fields'=>array('categoryname'),'conditions'=>array('page_url'=>$page_url)));
	
	return $page_details['Subcategory']['categoryname'];
	}	
	
	function getCityDetails_url($page_url)
	{
	App::import('Model','City');
	$this->city=new City();
	$page_details=$this->city->find('first',array('fields'=>array('cityname'),'conditions'=>array('page_url'=>$page_url)));
	
	return $page_details['City']['cityname'];
	}
	
	function getCompanyDetails_url($page_url)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$page_details=$this->AdvertiserProfile->find('first',array('fields'=>array('company_name'),'conditions'=>array('page_url'=>$page_url)));
		return $page_details['AdvertiserProfile']['company_name'];
	}
	function getCompanyDataByUrl($page_url)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$page_details=$this->AdvertiserProfile->find('first',array('conditions'=>array('AdvertiserProfile.page_url'=>$page_url,'AdvertiserProfile.publish'=>'yes')));
		return $page_details;
	}
	function getCompanyidByUrl($page_url)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$page_details=$this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.id'),'conditions'=>array('AdvertiserProfile.page_url'=>$page_url)));
		return $page_details['AdvertiserProfile']['id'];
	}
	function getCompanyNameById($id)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$page_details=$this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.company_name'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
		return $page_details['AdvertiserProfile']['company_name'];
	}	
	function getSalesEmail(){
		 App::import('Model','Setting');
		 $this->Setting 	= new Setting();
		 $salesEmail 		= $this->Setting->query("select sales_email from settings where id=1");
		 $salesEmailShow	= $salesEmail[0]['settings']['sales_email'].'@zuni.com';
		 return $salesEmailShow;
	}
	function getFromName(){
		 App::import('Model','Setting');
		 $this->Setting 	= new Setting();
		 $salesEmail 		= $this->Setting->query("select newsletter_from_email from settings where id=1");
		 $salesEmailShow	= $salesEmail[0]['settings']['newsletter_from_email'];
		 return $salesEmailShow;
	}
	function getReturnEmail(){
		 App::import('Model','Setting');
		 $this->Setting 	= new Setting();
		 $salesEmail 		= $this->Setting->query("select admin_email from settings where id=1");
		 $salesEmailShow	= $salesEmail[0]['settings']['admin_email'].'@zuni.com';
		 return $salesEmailShow;	
	}	
	function getAdvertiserEmailBody(){
		 App::import('Model','Setting');
		 $this->Setting 	= new Setting();
		 $salesEmail 		= $this->Setting->query("select new_advertiser_body from  settings where id=1");
		 $salesEmailShow	= $salesEmail[0]['settings']['new_advertiser_body'];
		 return $salesEmailShow;
	}
		/*
			This function is replacing all placemarkers from email body like advertiser name , company , order total etc.
		*/
	function replaceMarkersAdvertiser($bodyText,$advertiser_name,$package_name,$company_name,$package_price,$order_number){
				$data = str_replace('[advertiser_name]',$advertiser_name,$bodyText);
				$data = str_replace('[package_name]',$package_name,$data);
				$data = str_replace('[company_name]',$company_name,$data);
				$data = str_replace('[package_price]','$'.$package_price,$data);
				$data = str_replace('[order_number]',$order_number,$data);
				return $data;
	}	
	function getAdminEmail(){
		 App::import('Model','Setting');
		 $this->Setting 	= new Setting();
		 $adminEmail 		= $this->Setting->query("select admin_email from  settings where id=1");
		 $adminEmailShow	= $adminEmail[0]['settings']['admin_email'].'@zuni.com';
		 return $adminEmailShow;	
	}
	function getorderdetail($order_id) {
			
			App::import('model','AdvertiserOrder');
		    $this->AdvertiserOrder = new AdvertiserOrder(); 			
			$AdvertiserOrder = $this->AdvertiserOrder->find('first', array('conditions'=>array('AdvertiserOrder.id'=>$order_id)));
			return $AdvertiserOrder;
	}
	function getOrderId($advertiserid){
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();
			$orderid = $this->AdvertiserProfile->find('first',array('conditions'=>array('AdvertiserProfile.id'=>$advertiserid)));
			return $orderid;
	}
	function getonlyOrderId($advertiserid){
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();
			$orderid = $this->AdvertiserProfile->find('first',array('fields'=>array('order_id'),'conditions'=>array('AdvertiserProfile.id'=>$advertiserid)));
			return $orderid['AdvertiserProfile']['order_id'];
	}
	function getadminemailbyid($id) {			
			App::import('model','User');
		    $this->User = new User(); 			
			$user = $this->User->find('first', array('conditions'=>array('User.id'=>$id)));
			return $user;	
	}	
	function getAllSavingOffers($AdvertiserId=NULL){
			$SavingOfferid = '';
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer(); 			
			$SavingOffer = $this->SavingOffer->find('all',array('fields'=>('SavingOffer.id'),'conditions'=>array('SavingOffer.advertiser_profile_id'=>$AdvertiserId)));
			foreach($SavingOffer as $SavingOffer) {
				$SavingOfferid[] = $SavingOffer['SavingOffer']['id'];
			}
			if(is_array($SavingOfferid)) {
				return implode(', ',$SavingOfferid);
			} else {
				return '';
			}
	}
	function getAdvertiserLogo($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$AdvertiserProfile = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.logo'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			$logo = ($AdvertiserProfile['AdvertiserProfile']['logo']!='')?$AdvertiserProfile['AdvertiserProfile']['logo']:'';
			return $logo;		
	}	

	function getAdvertiserMainImageLogo($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$AdvertiserProfileLogo = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.main_image','AdvertiserProfile.logo'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			
			return $AdvertiserProfileLogo['AdvertiserProfile'];		
	}

	function getStateName($id) {
			App::import('model','State');
		    $this->State = new State();			
			$State = $this->State->find('first',array('fields'=>('State.statename'),'conditions'=>array('State.id'=>$id)));
			return $State['State']['statename'];	
	}
	function getStateUrls($id) {
			App::import('model','State');
		    $this->State = new State();			
			$State = $this->State->find('first',array('fields'=>('State.page_url'),'conditions'=>array('State.id'=>$id)));
			return $State['State']['page_url'];	
	}
	function getCountryName($id) {
			App::import('model','Country');
		    $this->Country = new Country();			
			$State = $this->Country->find('first',array('fields'=>('Country.countryname'),'conditions'=>array('Country.id'=>$id)));
			return $State['Country']['countryname'];	
	}
	function getPackageName($id) {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>('Package.name,Package.setup_price,Package.monthly_price'),'conditions'=>array('Package.id'=>$id)));
			return $Package['Package']['name'].' ($'.($Package['Package']['setup_price']+$Package['Package']['monthly_price']).')';
	}
	function getPackageDisclaimer($id) {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>('Package.disclaimer'),'conditions'=>array('Package.id'=>$id)));
			return $Package['Package']['disclaimer'];
	}		
	function getPackageSetup($id) {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>('Package.setup_price'),'conditions'=>array('Package.id'=>$id)));
			return '$'.number_format($Package['Package']['setup_price'],2);
	}
	function getPackageMonth($id) {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>('Package.monthly_price'),'conditions'=>array('Package.id'=>$id)));
			return '$'.number_format($Package['Package']['monthly_price'],2);
	}
	function getPackagePrice($id) {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>('Package.setup_price'),'conditions'=>array('Package.id'=>$id)));
			return '$'.$Package['Package']['setup_price'];
	}
	function getMainPackagePrice($id) {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>('Package.setup_price,Package.monthly_price'),'conditions'=>array('Package.id'=>$id)));
			return '$'.($Package['Package']['setup_price']+$Package['Package']['monthly_price']);
	}
	function getPackageTotal($id) {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>('Package.setup_price,Package.monthly_price'),'conditions'=>array('Package.id'=>$id)));
			return ($Package['Package']['setup_price']+$Package['Package']['monthly_price']);
	}
	function packageDisclaimer($id) {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>('Package.disclaimer'),'conditions'=>array('Package.id'=>$id)));
			return $Package['Package']['disclaimer'];
	}
	function getCountyName($id) {
			App::import('model','County');
		    $this->County = new County();
			$County = $this->County->find('first',array('fields'=>('County.countyname'),'conditions'=>array('County.id'=>$id)));
			return $County['County']['countyname'];
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
	function getCityName($id) {
			App::import('model','City');
		    $this->City = new City();		
			$City = $this->City->find('first',array('fields'=>('City.cityname'),'conditions'=>array('City.id'=>$id)));
			return $City['City']['cityname'];
	}
	function getCategoryName($id) {
			App::import('model','Category');
		    $this->Category = new Category();			
			$Category = $this->Category->find('first',array('fields'=>('Category.categoryname'),'conditions'=>array('Category.id'=>$id)));
			return $Category['Category']['categoryname'];	
	}
	function getSubcategoryName($id) {
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory();			
			$Category = $this->Subcategory->find('first',array('fields'=>('Subcategory.categoryname'),'conditions'=>array('Subcategory.id'=>$id)));
			return $Category['Subcategory']['categoryname'];	
	}
	function getCategoryUrl($id) {
			App::import('model','Category');
		    $this->Category = new Category();			
			$Category = $this->Category->find('first',array('fields'=>('Category.page_url'),'conditions'=>array('Category.id'=>$id)));
			return $Category['Category']['page_url'];	
	}
	function getSubcategoryUrl($id) {
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory();			
			$Category = $this->Subcategory->find('first',array('fields'=>('Subcategory.page_url'),'conditions'=>array('Subcategory.id'=>$id)));
			return $Category['Subcategory']['page_url'];	
	}
	


	function getCityUrl($id) {
			App::import('model','City');
		    $this->City = new City();			
			$City = $this->City->find('first',array('fields'=>('City.page_url'),'conditions'=>array('City.id'=>$id)));
			return $City['City']['page_url'];	
	}
	function getBanner($id){
			App::import('model','Banner');
		    $this->Banner = new Banner();			
			$Banner = $this->Banner->find('all',array('fields'=>('Banner.id,Banner.image'),'conditions'=>array('Banner.advertiser_profile_id'=>$id)));
			return $Banner;	
	}
	function getSavingOffer($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();			
			$SavingOffer = $this->SavingOffer->find('all',array('fields'=>('SavingOffer.id,SavingOffer.title,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.current_saving_offer,SavingOffer.other_saving_offer,SavingOffer.zuni_care'),'conditions'=>array('SavingOffer.advertiser_profile_id'=>$id)));
			return $SavingOffer;	
	}
function getFullSavingOffer($id,$sdate,$edate){
			$cond = '';
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();
			if($sdate) {$cond[] = "SavingOffer.offer_start_date >=".strtotime($sdate); }
			if($edate) {$cond[] = "SavingOffer.offer_expiry_date <=".strtotime($edate);}		
			$SavingOffer = $this->SavingOffer->find('all',array('conditions'=>array('SavingOffer.advertiser_profile_id'=>$id,$cond)));
			return $SavingOffer;	
	}
	function getmainSavingOffer($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();			
			$SavingOffer = $this->SavingOffer->find('all',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.advertiser_profile_id'=>$id)));
			return $SavingOffer;	
	}
	function getmainSavingOffer_front($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();			
			$SavingOffer = $this->SavingOffer->find('all',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.advertiser_profile_id'=>$id)));
			return $SavingOffer;	
	}
	function SavingOfferUnique($unique){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();			
			$SavingOffer = $this->SavingOffer->find('first',array('conditions'=>array('SavingOffer.unique'=>$unique)));
			return $SavingOffer;	
	}	
	function getmainSavingOfferImg_front($id){	
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('first',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.disclaimer,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.show_at_home'=>1,'SavingOffer.current_saving_offer'=>1,'SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time)));
			return $SavingOffer;
	}
	
function countSavingOfferLatestAdded($countyUrl){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();	
			$myCountyId=$this->getCountyIdByUrl($countyUrl);
			$days = $this->getCountyAdvertiserDays($myCountyId);	
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$end_time=mktime(0,0,0,date('m'),date('d')-($days),date('Y'));
			
			$this->SavingOffer->bindModel(array('belongsTo'=>array('AdvertiserProfile')));
			
			$SavingOfferLatest = $this->SavingOffer->find('count',array('conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.status'=>'yes','SavingOffer.offer_start_date <= '=>$cur_time,'SavingOffer.offer_start_date >= '=>$end_time,'SavingOffer.offer_expiry_date >='=>$cur_time,'SavingOffer.advertiser_county_id'=>$myCountyId,'AdvertiserProfile.publish'=>'yes')));
			
			$this->SavingOffer->unbindModel(array('belongsTo'=>array('AdvertiserProfile')));
			
			return $SavingOfferLatest;	
	}
	
	function getCountyAdvertiserDays($id) {
			App::import('model','County');
		    $this->County = new County();
			$this->County->id = $id;
			return $this->County->field('County.advertiser_days');
	}
	
	function getmainSavingOfferLatestAdded($countyUrl,$offset=0,$total=0){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();	
			$myCountyId=$this->getCountyIdByUrl($countyUrl);
			$days = $this->getCountyAdvertiserDays($myCountyId);
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$end_time=mktime(0,0,0,date('m'),date('d')-($days),date('Y'));
			
			$this->SavingOffer->bindModel(array('belongsTo'=>array('AdvertiserProfile')));
			
			$SavingOfferLatest = $this->SavingOffer->find('all',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.disclaimer,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.status'=>'yes','SavingOffer.offer_start_date <= '=>$cur_time,'SavingOffer.offer_start_date >= '=>$end_time,'SavingOffer.offer_expiry_date >='=>$cur_time,'SavingOffer.advertiser_county_id'=>$myCountyId,'AdvertiserProfile.publish'=>'yes'),'order'=>array('SavingOffer.id'=>'asc'),'limit'=>4,'offset'=>$offset*4));
			
			$count = count($SavingOfferLatest);
			$remaining = array();
			if($count<4 && $total>4) {
				$remaining = $this->SavingOffer->find('all',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.disclaimer,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.current_saving_offer'=>1,'SavingOffer.status'=>'yes','SavingOffer.offer_start_date <= '=>$cur_time,'SavingOffer.offer_start_date >= '=>$end_time,'SavingOffer.offer_expiry_date >='=>$cur_time,'SavingOffer.advertiser_county_id'=>$myCountyId,'AdvertiserProfile.publish'=>'yes'),'order'=>array('SavingOffer.id'=>'asc'),'limit'=>(4-$count),'offset'=>0));
			}
			$SavingOfferLatest = array_merge($SavingOfferLatest,$remaining);
			$this->SavingOffer->unbindModel(array('belongsTo'=>array('AdvertiserProfile')));
			
			return $SavingOfferLatest;
	}
	function getmainSavingOfferBusiness($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('first',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.disclaimer,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.current_saving_offer'=>1,'SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time),'limit'=>10));
			return $SavingOffer;
	}
function getmainSavingOfferImg_topten($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('first',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.disclaimer,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.current_saving_offer'=>1,'SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time,'SavingOffer.top_ten_status'=>1),'limit'=>10));
			return $SavingOffer;
	}
	function getToptenMainSavingOfferImg_front($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();			
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('first',array('fields'=>array('SavingOffer.id,SavingOffer.title,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.off_text,SavingOffer.other,SavingOffer.disclaimer,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.top_ten_status'=>1,'SavingOffer.current_saving_offer'=>1,'SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time)));
			return $SavingOffer;
	}
	function getmainSavingOfferImg_front_cat($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('first',array('fields'=>array('SavingOffer.id,SavingOffer.title,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.disclaimer,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.show_at_category'=>1,'SavingOffer.current_saving_offer'=>1,'SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time)));
			return $SavingOffer;
	}
	
		
	function getotherSavingOfferImg_front($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();	
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('all',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.disclaimer,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time),'order'=>array('SavingOffer.current_saving_offer'),'limit'=>10));
			return $SavingOffer;	
	}
	
	function freebieForAdvertiser($id){
			App::import('model','DailyDeal');
		    $this->DailyDeal = new DailyDeal();
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.advertiser_profile_id=$id AND ((DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.show_on_home_page=1) OR (DailyDeal.c_s_date<=$today AND DailyDeal.c_e_date>=$today AND DailyDeal.show_on_category=1))")));
			return $daily_deal;	
	}
	
	function getotherSavingOfferImg_merchant($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();	
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('all',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.disclaimer,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time),'order'=>array('SavingOffer.current_saving_offer'=>'desc'),'limit'=>5));
			return $SavingOffer;	
	}	
	function getotherSavingOfferImg_merchantUnique($id,$notIn){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();	
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('all',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.disclaimer,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time,'SavingOffer.id !='=>$notIn),'order'=>array('SavingOffer.current_saving_offer'=>'desc'),'limit'=>4));
			return $SavingOffer;	
	}	
	function getotherSavingOfferImg_front_mobile($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();	
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('all',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.disclaimer,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time),'order'=>array('SavingOffer.other_saving_offer'),'limit'=>10));
			return $SavingOffer;	
	}

	function getOneSavingOfferImg_front($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();	
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('first',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.description,SavingOffer.off,SavingOffer.off_unit,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.disclaimer,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.title,SavingOffer.off_text,SavingOffer.other,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time),'order'=>array('SavingOffer.current_saving_offer DESC')));
			return $SavingOffer;
	}	
	function getAnySavingOffer_front($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();			
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$SavingOffer = $this->SavingOffer->find('first',array('fields'=>array('SavingOffer.id,SavingOffer.advertiser_profile_id,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.offer_start_date,SavingOffer.offer_expiry_date,SavingOffer.top_ten_status,SavingOffer.off_text,SavingOffer.other,SavingOffer.unique,SavingOffer.homecat'),'conditions'=>array('SavingOffer.status'=>'yes','SavingOffer.advertiser_profile_id'=>$id,'SavingOffer.offer_start_date <='=>$cur_time,'SavingOffer.offer_expiry_date >='=>$cur_time)));
			return $SavingOffer;	
	}
	function getotherSavingOffer($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();			
			$SavingOffer = $this->SavingOffer->find('all',array('conditions'=>array('SavingOffer.other_saving_offer'=>1,'SavingOffer.advertiser_profile_id'=>$id)));
			return $SavingOffer;	
	}		
	
	function getdiscount($id){
			App::import('model','DailyDiscount');
		    $this->DailyDiscount = new DailyDiscount();			
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$DailyDiscount = $this->DailyDiscount->find('first',array('conditions'=>array("DailyDiscount.advertiser_profile_id = $id AND (DailyDiscount.e_date >=$cur_time OR DailyDiscount.c_e_date >=$cur_time)"),'order'=>array('DailyDiscount.id DESC')));
			return $DailyDiscount;	
	}
	function getAllCompanyDiscount($id){
			App::import('model','DailyDiscount');
		    $this->DailyDiscount = new DailyDiscount();			
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$DailyDiscount = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.advertiser_profile_id = $id AND (DailyDiscount.e_date >=$cur_time OR DailyDiscount.c_e_date >=$cur_time)"),'order'=>array('DailyDiscount.id DESC')));
			return $DailyDiscount;	
	}	
	
	function getAllArchiveDiscount($id){
			App::import('model','DailyDiscount');
		    $this->DailyDiscount = new DailyDiscount();			
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$DailyDiscount = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.advertiser_profile_id = $id AND (DailyDiscount.e_date < $cur_time AND DailyDiscount.c_e_date < $cur_time)"),'order'=>array('DailyDiscount.id DESC')));
			return $DailyDiscount;	
	}	
	function getAllArchiveDeal($id){
			App::import('model','DailyDeal');
		    $this->DailyDeal = new DailyDeal();	
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));		
			$DailyDeal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.advertiser_profile_id = $id AND (DailyDeal.e_date < $cur_time AND DailyDeal.c_e_date < $cur_time)"),'order'=>array('DailyDeal.id DESC')));
			return $DailyDeal;
	}	
	function getFulldiscount($id){
			App::import('model','DailyDiscount');
		    $this->DailyDiscount = new DailyDiscount();			
			$DailyDiscount = $this->DailyDiscount->find('first',array('conditions'=>array('DailyDiscount.advertiser_profile_id'=>$id),'order'=>array('DailyDiscount.id DESC')));
			return $DailyDiscount;	
	}
	function getAlldiscount($id,$sdate,$edate){
			$cond = '';
			App::import('model','DailyDiscount');
		    $this->DailyDiscount = new DailyDiscount();
			$edatearr = explode('/',$edate);
			if($sdate) {$cond[] = "DailyDiscount.s_date >=".strtotime($sdate); }
			if($edate) {$cond[] = "DailyDiscount.e_date <=".mktime(23,59,59,$edatearr[0],$edatearr[1],$edatearr[2]);}
			$DailyDiscount = $this->DailyDiscount->find('all',array('conditions'=>array('DailyDiscount.advertiser_profile_id'=>$id,$cond),'order'=>array('DailyDiscount.id DESC')));
			return $DailyDiscount;		
	}				
	function getdeal($id){
			App::import('model','DailyDeal');
		    $this->DailyDeal = new DailyDeal();	
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));		
			$DailyDeal = $this->DailyDeal->find('first',array('conditions'=>array("DailyDeal.advertiser_profile_id = $id AND (DailyDeal.e_date >=$cur_time OR DailyDeal.c_e_date >=$cur_time)"),'order'=>array('DailyDeal.id DESC')));
			return $DailyDeal;
	}
	function getAllCompanyDeal($id){
			App::import('model','DailyDeal');
		    $this->DailyDeal = new DailyDeal();	
			$cur_time=mktime(0,0,0,date('m'),date('d'),date('Y'));		
			$DailyDeal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.advertiser_profile_id = $id AND (DailyDeal.e_date >=$cur_time OR DailyDeal.c_e_date >=$cur_time)"),'order'=>array('DailyDeal.id DESC')));
			return $DailyDeal;
	}	
		function getAlldeal($id,$sdate,$edate){
			$cond = '';
			App::import('model','DailyDeal');
		    $this->DailyDeal = new DailyDeal();	
			$edatearr = explode('/',$edate);
			if($sdate) {$cond[] = "DailyDeal.s_date >=".strtotime($sdate); }
			if($edate) {$cond[] = "DailyDeal.e_date <=".strtotime($edate);}
			$DailyDeal = $this->DailyDeal->find('all',array('conditions'=>array('DailyDeal.advertiser_profile_id'=>$id,$cond),'order'=>array('DailyDeal.id DESC')));
			return $DailyDeal;	
	}
	function getcatename($id) {
			App::import('model','Category');
		    $this->Category = new Category();			
			$Category = $this->Category->find('first',array('fields'=>('Category.categoryname'),'conditions'=>array('Category.id'=>$id)));
			return $Category['Category']['categoryname'];	
	}	
	function getVipOffer($id) {
			App::import('model','VipOffer');
		    $this->VipOffer = new VipOffer();			
			$VipOffer = $this->VipOffer->find('all',array('fields'=>('VipOffer.id,VipOffer.title,VipOffer.category,VipOffer.description'),'conditions'=>array('VipOffer.advertiser_profile_id'=>$id)));
			return $VipOffer;	
	}
	function getAllVipOffer($id,$sdate,$edate) {
			App::import('model','VipOffer');
		    $this->VipOffer = new VipOffer();
			$cond = '';
			$edatearr = explode('/',$edate);
			if($sdate) {$cond[] = "VipOffer.offer_start_date >=".strtotime($sdate); }
			if($edate) {$cond[] = "VipOffer.offer_expiry_date <=".strtotime($edate);}	
				
			$VipOffer = $this->VipOffer->find('all',array('conditions'=>array('VipOffer.advertiser_profile_id'=>$id,$cond)));
			return $VipOffer;	
	}	
	function getVedio($id) {
			App::import('model','Video');
		    $this->Video = new Video();			
			$Video = $this->Video->find('first',array('conditions'=>array('Video.advertiser_profile_id'=>$id)));
			return $Video;	
	}
	function getImages($id) {
			App::import('model','Image');
		    $this->Image = new Image();			
			$Image = $this->Image->find('all',array('conditions'=>array('Image.advertiser_profile_id'=>$id)));
			return $Image;	
	}
	function getVouchers($id) {
			App::import('model','Voucher');
		    $this->Voucher = new Voucher();			
			$Voucher = $this->Voucher->find('all',array('conditions'=>array('Voucher.advertiser_profile_id'=>$id)));
			return $Voucher;	
	}	
	function getVedio_front($id) {
			App::import('model','Video');
		    $this->Video = new Video();			

			$Video = $this->Video->find('first',array('conditions'=>array('Video.advertiser_profile_id'=>$id,'Video.status'=>'yes')));
			return $Video;	
	}
	function getImages_front($id) {
			App::import('model','Image');
		    $this->Image = new Image();			
			$Image = $this->Image->find('all',array('conditions'=>array('Image.advertiser_profile_id'=>$id,'Image.status'=>'yes')));
			return $Image;	
	}	
	function getadminusers() {
		App::import('model','UserGroup');
		$this->UserGroup = new UserGroup();
			$UserGroup = $this->UserGroup->find('list', array('fields' => array('UserGroup.id', 'UserGroup.group_name'),'order' => 'UserGroup.group_name ASC','recursive' => -1,'conditions' => array('UserGroup.active' => 'yes'))); 
			return $UserGroup;	
	}
	
	/*---------------------this is used to get Id from pageurl by passing only modelname and pageurl---------------------------*/
	function getIdfromPageUrl($model,$page_url)
	{
	
		App::import('model',$model);
		$this->$model = new $model(); 
		
		$idlist = $this->$model->find('first', array('fields' => array('id'),'conditions' => array("$model.page_url" => $page_url))); 
		
		return $idlist;
	}
	/*-------------------------------------------------------------------------------------------------------------------------*/
		
	function chkBusinessByUrl($model,$page_url)
	{
	
		App::import('model',$model);
		$this->$model = new $model(); 
		
		$bus = $this->$model->find('first', array('fields' => array('id'),'conditions' => array("$model.page_url" => $page_url))); 
		 if(empty($bus))
		 	return 1;
		else
			return 0;

	}	
	
	function getCategoryNameById($cat_id,$model)
	{
		App::import('Model',$model);
		$this->$model=new $model();
		$cat_name=$this->$model->find('first',array('fields' => array('page_url'),'conditions'=>array("$model.id"=>$cat_id)));
		return $cat_name;
	}
	//to fetch the list of comapny name
	function getAllCompanyName(){
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();
			$companyList = $this->AdvertiserProfile->find('list',array('fields'=>array('AdvertiserProfile.id','AdvertiserProfile.company_name')));
			return $companyList;
	}
	function getPageDetails($id)
	{
			App::import('model','Article');
		    $this->Article = new Article();
			$article=$this->Article->find('first',array('conditions'=>array('Article.id'=>$id,'Article.published'=>'yes')));
			return $article;	
	}
	function getRegionalAd()
	{
			App::import('model','Banner');
		    $this->Banner = new Banner();
			$cur_time=time();
			$r_banner=$this->Banner->query("select * from banners where publish_date <= $cur_time AND publish_enddate >= $cur_time AND banner_size ='REGIONAL' AND publish='yes' order by RAND() LIMIT 1");
			return $r_banner;		
	}
	function getNationalAd()
	{
			App::import('model','Banner');
		    $this->Banner = new Banner();
			$cur_time=time();
			$n_banner=$this->Banner->query("select * from banners where publish_date <= $cur_time AND publish_enddate >= $cur_time AND banner_size ='NATIONAL' AND publish='yes' order by RAND() LIMIT 1");
			return $n_banner;		
	}
	function getAdminVideo()
	{
	 		App::import('model','Setting');
		    $this->Setting = new Setting(); 
			 
			$adminVideos = $this->Setting->find('first'); 

			return $adminVideos;
	} 
	//-----------------------------find all front category combination for hot button-------------------------	  
	function getAllFrontCategory(){
	
	 		App::import('model','FrontCategory');
		    $this->FrontCategory = new FrontCategory(); 
			
			$frontCategoryList = $this->FrontCategory->find('all',array('conditions'=>array('FrontCategory.publish'=>'yes'),'order' => 'FrontCategory.order,FrontCategory.order')); 

			return $frontCategoryList;
	      }	
	//-----------------------------find all front category combination for hot button unpublished also-------------------------	  
	function getAllFrontCategoryHot(){
	
	 		App::import('model','FrontCategory');
		    $this->FrontCategory = new FrontCategory(); 
			
			$frontCategoryList = $this->FrontCategory->find('all',array('order' => 'FrontCategory.order')); 

			return $frontCategoryList;
	      }	
	//-----------------------------find record of  front category combination from page url for hot button-------------------------	  		  		
	function getFrontCategoryFromPageurl($button_url)
		 {
		 App::import('model','FrontCategory');
		 $this->FrontCategory = new FrontCategory(); 
		 $frontcategory=$this->FrontCategory->find('first',array('conditions'=>array('FrontCategory.publish'=>'yes','FrontCategory.page_url'=>$button_url)));
		 return $frontcategory;
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function returnReferFriend($id) {
			App::import('model','ReferredFriend');
		 $this->ReferredFriend = new ReferredFriend(); 
			$friend = $this->ReferredFriend->find('count',array('conditions'=>array('ReferredFriend.front_user_id'=>$id)));
			return $friend;
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function returnRgstrFriend($id) {
			App::import('model','ReferredFriend');
		 $this->ReferredFriend = new ReferredFriend(); 
			$friend = $this->ReferredFriend->find('count',array('conditions'=>array('ReferredFriend.front_user_id'=>$id,'ReferredFriend.status'=>'yes')));
			return $friend;
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function returnbucksFriend($id) {
			App::import('model','ReferredFriend');
		 $this->ReferredFriend = new ReferredFriend(); 
			$friend = $this->ReferredFriend->find('all',array('fields'=>array('sum(bucks)'),'conditions'=>array('ReferredFriend.front_user_id'=>$id,'ReferredFriend.status'=>'yes')));
			return $friend;
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function returnReferBusiness($id) {
			App::import('model','ReferredBusiness');
		 $this->ReferredBusiness = new ReferredBusiness(); 
			$business= $this->ReferredBusiness->find('count',array('conditions'=>array('ReferredBusiness.front_user_id'=>$id)));
			return $business;
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function returnRgstrBusiness($id) {
			App::import('model','ReferredBusiness');
		 $this->ReferredBusiness = new ReferredBusiness(); 
			$business= $this->ReferredBusiness->find('count',array('conditions'=>array('ReferredBusiness.front_user_id'=>$id,'ReferredBusiness.status'=>'yes')));
			return $business;
	}

//---------------------------------------------------------------------------------------------------------------------------------//			  
	function returnbucksBusiness($id) {
			App::import('model','ReferredBusiness');
		 $this->ReferredBusiness = new ReferredBusiness(); 
			$business= $this->ReferredBusiness->find('all',array('fields'=>array('sum(bucks)'),'conditions'=>array('ReferredBusiness.front_user_id'=>$id,'ReferredBusiness.status'=>'yes')));
			return $business;
	}		 
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getCompanyName($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$company = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.company_name'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			return $company['AdvertiserProfile']['company_name'];	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function advertiserByOrder($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$company = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.company_name'),'conditions'=>array('AdvertiserProfile.order_id'=>$id)));
			return $company['AdvertiserProfile']['company_name'];
	}	
	function getCompanyEmail($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$company = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.email'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			return $company['AdvertiserProfile']['email'];	
	}	
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getCompanySplit($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$company = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.split'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			return $company['AdvertiserProfile']['split'];	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getCompanyCounty($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$company = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.county'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			return $company['AdvertiserProfile']['county'];	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getCompanystate($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$company = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.state'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			return $company['AdvertiserProfile']['state'];	
	}		
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getConsumerNameById($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('fields'=>('FrontUser.name'),'conditions'=>array('FrontUser.id'=>$id)));
			return $company['FrontUser']['name'];	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getConsumerBucks($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('fields'=>('FrontUser.total_bucks'),'conditions'=>array('FrontUser.id'=>$id)));
			return $company['FrontUser']['total_bucks'];	
	}	
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getConsumerEmailById($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('fields'=>('FrontUser.email'),'conditions'=>array('FrontUser.id'=>$id)));
			return $company['FrontUser']['email'];	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getConsumerIdByEmail($email) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('fields'=>('FrontUser.id'),'conditions'=>array('FrontUser.email'=>$email,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")')));
			return $company['FrontUser']['id'];	
	}	
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getConsumerDetails($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.id'=>$id)));
			return $company['FrontUser'];	
	}	
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getConsumerAddressById($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('fields'=>('FrontUser.address'),'conditions'=>array('FrontUser.id'=>$id)));
			return $company['FrontUser']['address'];	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getConsumerCityById($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();
			$company = $this->FrontUser->find('first',array('fields'=>('FrontUser.city_id'),'conditions'=>array('FrontUser.id'=>$id)));
			return $this->getCityName($company['FrontUser']['city_id']);
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getConsumerGiftCityById($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('fields'=>('FrontUser.gift_city'),'conditions'=>array('FrontUser.id'=>$id)));
			return $company['FrontUser']['gift_city'];	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function returnBucks($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();	
			$bucks_left = $this->FrontUser->find('first',array('fields'=>array('FrontUser.total_bucks'),'conditions'=>array('FrontUser.id'=>$id)));
			return $bucks_left['FrontUser']['total_bucks'];
	}	
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getConsumerGiftStateById($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('fields'=>('FrontUser.gift_state'),'conditions'=>array('FrontUser.id'=>$id)));
			return $company['FrontUser']['gift_state'];	
	}	
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getConsumerZipById($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('fields'=>('FrontUser.zip'),'conditions'=>array('FrontUser.id'=>$id)));
			return $company['FrontUser']['zip'];	
	}			
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getCompanyNameByPageurl($page_url) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$company = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.company_name'),'conditions'=>array('AdvertiserProfile.page_url'=>$page_url)));
			return $company['AdvertiserProfile']['company_name'];	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getAdvertiserNameById($id) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('fields'=>('FrontUser.name'),'conditions'=>array('FrontUser.id'=>$id,'FrontUser.user_type'=>'advertiser')));
			return $company['FrontUser']['name'];	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getUserNameByEmail($email,$county) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();
			$company = $this->FrontUser->find('first',array('fields'=>array('FrontUser.name','FrontUser.id'),'conditions'=>array('FrontUser.email'=>$email,'FrontUser.county_id'=>$county,'FrontUser.user_type !='=>'advertiser')));
			return $company['FrontUser'];	
	}	
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getAdvertiserCNameById($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$company = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.company_name'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			return $company['AdvertiserProfile']['company_name'];	
	}		
//------------------------------------------Function to get all parent cats -------------------------------//
	function gatParentCats() {
			App::import('model','Category');
		    $this->Category = new Category();
			$catlist = $this->Category->find('all',array('conditions'=>array('Category.publish'=>'yes'),'fields'=>array('Category.id','Category.categoryname','Category.page_url'),'order'=>array('Category.order,Category.categoryname')));
			return $catlist;
	}
//------------------------------------------Function to get all parent cats -------------------------------//
	function gatChildCats($parent_id) {
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory(); 
			$catlist = $this->Subcategory->find('all',array('conditions'=>array('Subcategory.category_id LIKE "%,'.$parent_id.',%" AND Subcategory.publish = "yes"'),'fields'=>array('Subcategory.id','Subcategory.categoryname','Subcategory.page_url'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
			return $catlist;
	}
//------------------------------------------Function to get all parent cats -------------------------------//
	function voucher_detail($voucher_id) {
			App::import('model','Voucher');
		    $this->Voucher = new Voucher(); 
			$details = $this->Voucher->find('first',array('conditions'=>array('Voucher.id'=>$voucher_id)));
			return $details['Voucher'];
	}	
//------------------------------------------get County Default Image -------------------------------//
	function getCountyDefaultImage($pageUrl) {
			App::import('model','County');
		    $this->County = new County(); 
			$defaultImage = $this->County->find('first',array('conditions'=>array('County.page_url'=>$pageUrl)));
			return $defaultImage['County']['header_image'];
	}
//------------------------------------------ get County Default Image -------------------------------//
	function Contest_info($id) {
			App::import('model','Contest');
		    $this->Contest = new Contest(); 
			$Contest_info = $this->Contest->find('first',array('conditions'=>array('Contest.id'=>$id)));
			return $Contest_info['Contest'];
	}
//------------------------------------------ get County Default Image -------------------------------//	
function getCountyIdByUrl($page_url) {
			App::import('model','County');
		    $this->County = new County();			
			$County = $this->County->find('first',array('fields'=>('County.id'),'conditions'=>array('County.page_url'=>$page_url)));
			
			return $County['County']['id'];	
	}
//------------------------------------------ get County Default Image -------------------------------//	
function getStateIdByUrl($page_url) {
			App::import('model','State');
		    $this->State = new State();			
			$State = $this->State->find('first',array('fields'=>('State.id'),'conditions'=>array('State.page_url'=>$page_url)));
			//pr($State);exit;
			return $State['State']['id'];
	}
//------------------------------------------ get County Default Image -------------------------------//	
function getCityIdByUrl($page_url) {
			App::import('model','City');
		    $this->City = new City();			
			$City = $this->City->find('first',array('fields'=>('City.id'),'conditions'=>array('City.page_url'=>$page_url)));
			return $City['City']['id'];
	}
//------------------------------------------ get County Default Image -------------------------------//	
function getCatIdByUrl($page_url) {
			App::import('model','Category');
		    $this->Category = new Category();			
			$Category = $this->Category->find('first',array('fields'=>('Category.id'),'conditions'=>array('Category.page_url'=>$page_url)));
			return $Category['Category']['id'];
	}
//------------------------------------------ get County Default Image -------------------------------//	
function getSubcatIdByUrl($page_url) {
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory();			
			$Subcategory = $this->Subcategory->find('first',array('fields'=>('Subcategory.id'),'conditions'=>array('Subcategory.page_url'=>$page_url)));
			return $Subcategory['Subcategory']['id'];
	}			
//------------------------------------------ get County Default Image -------------------------------//	
function getStateByCityId($city_id) {
			App::import('model','City');
		    $this->City = new City();			
			$City = $this->City->find('first',array('fields'=>('City.state_id'),'conditions'=>array('City.id'=>$city_id)));
			return  $this->getStateName($City['City']['state_id']);
	}
	//-----------------------------Listing of all Counties-------------------------	  
	function getAllSchoolByCounty($county_id){	
	 		App::import('model','School');
		    $this->School = new School();			
			$SchoolList = $this->School->find('list', array('fields' => array('id', 'schoolname'),'order' => 'School.schoolname ASC','recursive' => -1,'conditions' => array('School.publish' => 'yes','School.county_id'=>$county_id)));			
			return $SchoolList;
	      }			
	//-----------------------------Listing of all school-------------------------	  
	function getAllSchool(){
	
	 		App::import('model','School');
		    $this->School = new School(); 
			
			$SchoolList = $this->School->find('list', array('fields' => array('id', 'schoolname'),'order' => 'School.schoolname ASC','recursive' => -1,'conditions' => array('School.publish' => 'yes'))); 
			
			return $SchoolList;
	      }
	//-----------------------------Listing of all school-------------------------	  
	function getAllChild(){
	 		App::import('model','Kid');
		    $this->Kid = new Kid();			
			$KidList = $this->Kid->find('list', array('fields' => array('id', 'child_name'),'order' => 'Kid.child_name ASC','recursive' => -1));			
			return $KidList;
	}		  	  
	//-----------------------------Listing of all Counties of specified state-------------------------	  
	function getAllCountyByState($stateid){
	
	 		App::import('model','County');
		    $this->County = new County(); 
			
			$CountyList = $this->County->find('list', array('fields' => array('id', 'countyname'),'order' => 'County.countyname ASC','recursive' => -1,'conditions' => array('County.publish' => 'yes','County.state_id' => $stateid))); 
			
			return $CountyList;
	      }	
	//-----------------------------Listing of all Counties urls of specified state-------------------------	  
	function getAllCountyUrlListByState($stateid){
	
	 		App::import('model','County');
		    $this->County = new County(); 
			
			$CountyList = $this->County->find('list', array('fields' => array('page_url', 'countyname'),'order' => 'County.countyname ASC','recursive' => -1,'conditions' => array('County.publish' => 'yes','County.state_id' => $stateid))); 
			
			return $CountyList;
	      }	
	//-----------------------------Listing of all Counties of specified state-------------------------	  
	function getSchoolById($id){
	
	 		App::import('model','School');
			
		    $this->School = new School(); 
			
			$School = $this->School->find('first', array('fields' => array('schoolname'),'conditions' => array('School.id' => $id))); 
			
			return $School['School']['schoolname'];
	      }
	//-----------------------------Get Child name by parent id-------------------------	  
	function getChildName($parent_id)	{
		
			App::import('model','Kid');
					
		    $this->Kid = new Kid();
					
			$Child = $this->Kid->find('first', array('fields' => array('child_name'),'conditions' => array('Kid.front_user_id' => $parent_id)));	
					
			return $Child['Kid']['child_name'];
	      
		}
//-----------------------------Get Child name by parent id-------------------------	  
	function getChildId($parent_id)	{
		
			App::import('model','Kid');
					
		    $this->Kid = new Kid();
					
			$Child = $this->Kid->find('first', array('fields' => array('Kid.id'),'conditions' => array('Kid.front_user_id' => $parent_id)));	
					
			return $Child['Kid']['id'];
	      
		}		
//------------------------------------------ get County Default Image -------------------------------//	
	function getStateByCountyId($county_id) {
				App::import('model','County');
				$this->County = new County();			
				$County = $this->County->find('first',array('fields'=>('County.state_id'),'conditions'=>array('County.id'=>$county_id)));
				return $County['County']['state_id'];
		}
//------------------------------------------ get County Default Image -------------------------------//	
	function getCountySplit($county_id) {
				App::import('model','County');
				$this->County = new County();			
				$County = $this->County->find('first',array('fields'=>('County.split'),'conditions'=>array('County.id'=>$county_id)));
				return $County['County']['split'];
		}				
//------------------------------------------ get County Default Image -------------------------------//
	function Contest_user_info($contest_id,$user_id) {
			App::import('model','ContestUser');
		    $this->ContestUser = new ContestUser(); 
			$ContestUser = $this->ContestUser->find('first',array('conditions'=>array('ContestUser.contest_id'=>$contest_id,'ContestUser.front_user_id'=>$user_id),'order'=>array('ContestUser.created'=>'DESC')));		
			return $ContestUser['ContestUser'];
	}	
	//-----------------------------get category limit for county home page-------------------------	  
	function getCategoryLimit($county_id,$cat_id)	{
		
			App::import('model','CategoryLimit');
					
		    $this->CategoryLimit = new CategoryLimit();
					
			$CatLimit=$this->CategoryLimit->find('first', array('fields'=>array('id','county_id','category_id','max_limit'),'conditions' => array('CategoryLimit.county_id' => $county_id,'CategoryLimit.category_id' => $cat_id)));	
					
			return $CatLimit['CategoryLimit'];
	      
		}
	//-----------------------------get category limit for calendar availability for category page-------------------
	function getCategoryLimitCalendar($county_id,$cat_id)	{
		
			App::import('model','CategoryLimit');
					
		    $this->CategoryLimit = new CategoryLimit();
					
			$CatLimit=$this->CategoryLimit->find('first', array('fields'=>array('max_limit'),'conditions' => array('CategoryLimit.county_id' => $county_id,'CategoryLimit.category_id' => $cat_id)));	
					
			return $CatLimit['CategoryLimit']['max_limit'];
	      
		}
//------------------------------------------ get County Default Image -------------------------------//
	function refer_check($user_id) {
			App::import('model','ReferredBusiness');
		    $this->ReferredBusiness = new ReferredBusiness();
			$time = mktime(0,0,0,date('m')-1,date('d'),date('Y'));						
			$check_user = $this->ReferredBusiness->find('count',array('conditions'=>array('ReferredBusiness.front_user_id'=>$user_id,'ReferredBusiness.refered_date>'.$time)));
			if($check_user) {

				return false;
			}	
			return true;
	}
//------------------------------------------ get County Default Image -------------------------------//
	function delete_refer_business() {
			App::import('model','ReferredBusiness');
		    $this->ReferredBusiness = new ReferredBusiness();
			$time = mktime(0,0,0,date('m')-1,date('d'),date('Y'));	
			$this->ReferredBusiness->deleteAll(array('ReferredBusiness.status'=>'no','ReferredBusiness.refered_date<'.$time));
	}
//------------------------------------------ get County Default Image -------------------------------//	
	function getNewsLetterEmail(){
		 App::import('Model','Setting');
		 $this->Setting 	= new Setting();
		 $Email 		= $this->Setting->query("select newsletter_from_email from  settings where id=1");
		 $adminEmailShow	= $Email[0]['settings']['newsletter_from_email'].'@zuni.com';
		 return $adminEmailShow;	
	}
//------------------------------------------ get County Default Image -------------------------------//	
	function getNewsLetterBottom(){
		 App::import('Model','Setting');
		 $this->Setting 	= new Setting();
		 $newsletter_bottom 		= $this->Setting->query("select newsletter_bottom from settings where id=1");
		 $bottom	= $newsletter_bottom[0]['settings']['newsletter_bottom'];
		 return $bottom;
	}
//--------------------------------------------------------------------------------------------------//
	function getParentDetails($email) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();
			$FrontUser = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$email,'FrontUser.user_type'=>'parent'),'recursive'=>-1));
			return $FrontUser;
	}
//--------------------------------------------------------------------------------------------------//
	function checkReferral($email) {
			App::import('model','ReferredFriend');
		    $this->ReferredFriend = new ReferredFriend(); 
			$ReferredFriend = $this->ReferredFriend->find('first',array('conditions'=>array('ReferredFriend.email'=>$email),'recursive'=>-1));
			return $ReferredFriend;
	}
//--------------------------------------------------------------------------------------------------//
	function getKid($parent_id) {
			App::import('model','Kid');
		    $this->Kid = new Kid(); 
			$Kid = $this->Kid->find('first',array('fields'=>array('Kid.id','Kid.school_id'),'conditions'=>array('Kid.front_user_id'=>$parent_id)));
			return $Kid;
	}
//--------------------------------------------------------------------------------------------------//
	function checkState($name) {
			App::import('model','State');
		    $this->State = new State();
			$State = $this->State->find('first',array('fields'=>array('State.id'),'conditions'=>array('State.statename="'.$name.'" OR State.page_url="'.$name.'"')));
			if(!empty($State)) {
				$id = $State['State']['id'];
			} else {
				$save['State']['id'] 		= '';
				$save['State']['statename'] = $name;
				$save['State']['status'] 	= 'yes';
				$save['State']['created'] 	= time();
				$save['State']['modified'] 	= time();
				$save['State']['page_url'] 	= $this->makeAlias(trim($name));
				$this->State->save($save);
				$id = $this->State->getlastinsertid();
			}
			return $id;
	}
//--------------------------------------------------------------------------------------------------//
	function checkCounty($name,$state_id) {
			App::import('model','County');
		    $this->County = new County();
			$County = $this->County->find('first',array('fields'=>array('County.id'),'conditions'=>array('County.state_id='.$state_id.' AND (County.countyname="'.$name.'" OR County.page_url="'.$name.'")')));
			if(!empty($County)) {
				$id = $County['County']['id'];
			} else {
				$save['County']['id'] 			= '';
				$save['County']['countyname'] 	= $name;
				$save['County']['state_id'] 	= $state_id;
				$save['County']['publish'] 		= 'yes';
				$save['County']['page_url'] 	= $this->makeAlias(trim($name));
				$this->County->save($save);
				$id = $this->County->getlastinsertid();
			}
			return $id;
	}
//--------------------------------------------------------------------------------------------------//
	function checkSchool($name,$state_id,$county_id) {
			App::import('model','School');
		    $this->School = new School();
			$School = $this->School->find('first',array('fields'=>array('School.id'),'conditions'=>array('School.state_id='.$state_id.' AND School.county_id='.$county_id.' AND (School.schoolname="'.$name.'" OR School.page_url="'.$name.'")')));
			if(!empty($School)) {
				$id = $School['School']['id'];
			} else {
				$save['School']['id'] 			= '';
				$save['School']['schoolname'] 	= $name;
				$save['School']['state_id'] 	= $state_id;
				$save['School']['county_id'] 	= $county_id;		
				$save['School']['page_url'] 	= $this->makeAlias(trim($name));
				$this->School->save($save);
				$id = $this->School->getlastinsertid();
			}
			return $id;
	}
//--------------------------------------------------------------------------------------------------//
	function getParent($email) {
			App::import('model','ReferredFriend');
		    $this->ReferredFriend = new ReferredFriend();			
			$ReferredFriend = $this->ReferredFriend->find('first',array('fields'=>array('ReferredFriend.name','FrontUser.name','FrontUser.county_id'),'conditions'=>array('ReferredFriend.email'=>$email)));
			$parentdata['county'] = $this->getCountyName($ReferredFriend['FrontUser']['county_id']);
			$parent = explode(' ',$ReferredFriend['FrontUser']['name']);
			$parentdata['name'] = $ReferredFriend['FrontUser']['name'];
			$parentdata['first_name'] = $parent[0];
			$parentdata['friend'] = $ReferredFriend['ReferredFriend']['name'];
			return $parentdata;
	}
//--------------------------------------------------------------------------------------------------//
	function getReferCounty($email) {
			App::import('model','ReferredFriend');
		    $this->ReferredFriend = new ReferredFriend();			
			$ReferredFriend = $this->ReferredFriend->find('first',array('fields'=>array('ReferredFriend.county_id'),'conditions'=>array('ReferredFriend.email'=>$email)));
			return $ReferredFriend['ReferredFriend']['county_id'];
	}
//-----------------------------Listing of all school-------------------------	  
	function getAllTeacher(){
	 		App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();
			$TeacherList = $this->FrontUser->find('list', array('fields' => array('teacher', 'teacher'),'order' => 'FrontUser.teacher ASC','recursive' => -1));			
			return $TeacherList;
	}
//---------------------------------------------------------------------------
	function getTotalVoucher($advertiser_id) {
		App::import('model','DiscountUser');
	    $this->DiscountUser = new DiscountUser();
		$total = $this->DiscountUser->query("SELECT SUM(vouchers) as total FROM discount_users WHERE advertiser_profile_id=".$advertiser_id." GROUP BY advertiser_profile_id");
		return $total;
	}
//---------------------------------------------------------------------------
	function salseperson($user_id) {
		$name = '--';
		App::import('model','User');
	    $this->User = new User();
		$data = $this->User->find('first',array('fields'=>array('User.name'),'conditions'=>array('User.id'=>$user_id)));
		if(!empty($data)) {
			$name = $data['User']['name'];
		}
		return $name;
	}
//---------------------------------------------------------------------------------
	function currency($bucks) {
		App::import('model','Setting');
	    $this->Setting = new Setting();
		$Setting = $this->Setting->find('first',array('fields'=>array('Setting.exchange_rate')));
		return $bucks/($Setting['Setting']['exchange_rate']);
	}	
//---------------------------------------------------------------------------------
	function currency1($price) {
		App::import('model','Setting');
	    $this->Setting = new Setting();
		$Setting = $this->Setting->find('first',array('fields'=>array('Setting.exchange_rate')));
		return $price*($Setting['Setting']['exchange_rate']);
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getTotalPrice($dis_id,$front_id) {
			App::import('model','DiscountUser');
		    $this->DiscountUser = new DiscountUser();
			$company = $this->DiscountUser->find('first',array('fields'=>array('DiscountUser.total_price'),'conditions'=>array('DiscountUser.front_user_id'=>$front_id,'DiscountUser.daily_discount_id'=>$dis_id)));
			return $company['DiscountUser']['total_price'];
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getsinglePrice($dis_id,$front_id) {
			App::import('model','DiscountUser');
		    $this->DiscountUser = new DiscountUser();
			$company = $this->DiscountUser->find('first',array('fields'=>array('DiscountUser.total_price','DiscountUser.vouchers'),'conditions'=>array('DiscountUser.front_user_id'=>$front_id,'DiscountUser.daily_discount_id'=>$dis_id)));
			return number_format(($company['DiscountUser']['total_price']/$company['DiscountUser']['vouchers']),2);
	}
//---------------------------------------------------------------------------------
	function getDiscountId($unique) {
		App::import('model','DailyDiscount');
	    $this->DailyDiscount = new DailyDiscount();
		$DailyDiscount = $this->DailyDiscount->find('first',array('fields'=>array('DailyDiscount.id'),'conditions'=>array('DailyDiscount.unique'=>$unique)));
		return $DailyDiscount['DailyDiscount']['id'];
	}
//---------------------------------------------------------------------------------
	function discountyUnique($unique) {
		App::import('model','DailyDiscount');
	    $this->DailyDiscount = new DailyDiscount();
		$DailyDiscount = $this->DailyDiscount->find('first',array('conditions'=>array('DailyDiscount.unique'=>$unique)));
		return $DailyDiscount;
	}		
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getDiscountUserVoucher($discount_user_id) {
			App::import('model','DiscountInfo');
		    $this->DiscountInfo = new DiscountInfo();
			$allVouchers = $this->DiscountInfo->find('all',array('conditions'=>array('DiscountInfo.discount_user_id'=>$discount_user_id)));
			return $allVouchers;
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getDiscountUserName($id) {
			App::import('model','DiscountUser');
		    $this->DiscountUser = new DiscountUser();
			$user = $this->DiscountUser->find('first',array('fields'=>array('FrontUser.name'),'conditions'=>array('DiscountUser.id'=>$id)));
			return $user['FrontUser']['name'];
	}
//---------------------------------------------------------------------------------
	function getDiscountTitle($id) {
		App::import('model','DailyDiscount');
	    $this->DailyDiscount = new DailyDiscount();
		$DailyDiscount = $this->DailyDiscount->find('first',array('fields'=>array('DailyDiscount.title'),'conditions'=>array('DailyDiscount.id'=>$id)));
		return $DailyDiscount['DailyDiscount']['title'];
	}
//---------------------------------------------------------------------------------
	function getDiscountDetail($id) {
		App::import('model','DailyDiscount');
	    $this->DailyDiscount = new DailyDiscount();
		$DailyDiscount = $this->DailyDiscount->find('first',array('fields'=>array('DailyDiscount.discount_details'),'conditions'=>array('DailyDiscount.id'=>$id)));
		return strip_tags($DailyDiscount['DailyDiscount']['discount_details']);
	}	
	
//---------------------------------------------------------------------------------	
	function getPackageHome($id) {
		App::import('model','Package');
		$this->Package = new Package();			
		$Package = $this->Package->find('first',array('fields'=>('Package.show_at_home'),'conditions'=>array('Package.id'=>$id)));
		return $Package['Package']['show_at_home'];	
	}	
//---------------------------------------------------------------------------------
	function homePermission($ad_id) {
		$order = $this->getonlyOrderId($ad_id);		
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		return $this->getPackageHome($package['AdvertiserOrder']['package_id']);
	}
//---------------------------------------------------------------------------------	
	function getPackageCategory($id) {
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.show_at_cats'),'conditions'=>array('Package.id'=>$id)));
		return $Package['Package']['show_at_cats'];
	}
//---------------------------------------------------------------------------------
	function catPermission($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));	
		return $this->getPackageCategory($package['AdvertiserOrder']['package_id']);
	}
//----------------------------------------------------------------------------------
	function discountLimit($id){
			App::import('model','DailyDiscount');
		    $this->DailyDiscount = new DailyDiscount();
			$DailyDiscount = $this->DailyDiscount->find('first',array('fields'=>array('DailyDiscount.limit_per_user'),'conditions'=>array('DailyDiscount.id'=>$id)));
			return $DailyDiscount['DailyDiscount']['limit_per_user'];
	}
//----------------------------------------------------------------------------------
	function totalPurchase($user_id,$discount){
			App::import('model','DiscountUser');
		    $this->DiscountUser = new DiscountUser();					
			$DiscountUser = $this->DiscountUser->query("SELECT SUM(vouchers) as Vouchers FROM discount_users WHERE front_user_id=$user_id AND daily_discount_id=$discount");
			if($DiscountUser[0][0]['Vouchers']!='') {
				return $DiscountUser[0][0]['Vouchers'];
			} else {
				return 0;
			}
		}
//-----------------------------------------------------------------------------------
	function getcateurl($id) {
			App::import('model','Category');
		    $this->Category = new Category();			
			$Category = $this->Category->find('first',array('fields'=>('Category.page_url'),'conditions'=>array('Category.id'=>$id)));
			return $Category['Category']['page_url'];
	}
//-----------------------------------------------------------------------------------
	function getsubcateurl($id) {
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory();
			$Subcategory = $this->Subcategory->find('first',array('fields'=>('Subcategory.page_url'),'conditions'=>array('Subcategory.id'=>$id)));
			return $Subcategory['Subcategory']['page_url'];
	}
//-----------------------------------------------------------------------------------
	function getcompanyurl($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$AdvertiserProfile = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.page_url'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			return $AdvertiserProfile['AdvertiserProfile']['page_url'];
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getUserByUnique($uid) {
			App::import('model','FrontUser');
		    $this->FrontUser = new FrontUser();			
			$company = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.uid'=>$uid)));
			return $company['FrontUser'];
	}
//---------------------------------------------------------------------------------------------------------------------------------//			
	function getPackageDetail($id) {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('conditions'=>array('Package.id'=>$id)));
			return $Package['Package'];
	}	
//---------------------------------------------------------------------------------------------------------------------------------//			
	function getPackageId($order_id) {
			App::import('model','AdvertiserOrder');
		    $this->AdvertiserOrder = new AdvertiserOrder();
			$Order = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order_id)));
			return $Order['AdvertiserOrder']['package_id'];
	}
//---------------------------------------------------------------------------------------------------------------------------------//			
	function homeDiscountPkg() {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>array('Package.id'),'conditions'=>array('Package.package_detail'=>'ADD HOME DAILY DISCOUNT')));
			return $Package['Package']['id'];
	}
//---------------------------------------------------------------------------------------------------------------------------------//			
	function homeDealPkg() {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>array('Package.id'),'conditions'=>array('Package.package_detail'=>'ADD HOME DAILY DEALS')));
			return $Package['Package']['id'];
	}
//---------------------------------------------------------------------------------------------------------------------------------//			
	function homeSavingPkg() {
			App::import('model','Package');
		    $this->Package = new Package();
			$Package = $this->Package->find('first',array('fields'=>array('Package.id'),'conditions'=>array('Package.package_detail'=>'ADD HOME BANNER')));
			return $Package['Package']['id'];
	}
//---------------------------------------------------------------------------------
	function categoryDiscountPerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));	
		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.discount'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['discount'];
	}
//---------------------------------------------------------------------------------
	function homeDiscountPerm($ad_id) {
		/*App::import('model','AdvertiserProfile');
	    $this->AdvertiserProfile = new AdvertiserProfile();
		$spcl_pkg = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.spclpkg'),'conditions'=>array('AdvertiserProfile.id'=>$ad_id)));
		$home_dis = $this->homeDiscountPkg();
		if(strpos($spcl_pkg['AdvertiserProfile']['spclpkg'],$home_dis.',')) {
			return 1;
		} else {
			return 0;
		}*/
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.home_page'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['home_page'];
	}
//---------------------------------------------------------------------------------
	function categoryDealPerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.deal'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['deal'];
	}
//---------------------------------------------------------------------------------
	function homeDealPerm($ad_id) {
		/*App::import('model','AdvertiserProfile');
	    $this->AdvertiserProfile = new AdvertiserProfile();
		$spcl_pkg = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.spclpkg'),'conditions'=>array('AdvertiserProfile.id'=>$ad_id)));
		$home_deal = $this->homeDealPkg();
		if(strpos($spcl_pkg['AdvertiserProfile']['spclpkg'],$home_deal.',')) {
			return 1;
		} else {
			return 0;
		}*/
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.home_page'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['home_page'];
	}
//---------------------------------------------------------------------------------
	function categorySavingPerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.promotional'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['promotional'];
	}
//---------------------------------------------------------------------------------
	function homeSavingPerm($ad_id) {
		/*App::import('model','AdvertiserProfile');
	    $this->AdvertiserProfile = new AdvertiserProfile();
		$spcl_pkg = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.spclpkg'),'conditions'=>array('AdvertiserProfile.id'=>$ad_id)));
		$home_sav = $this->homeSavingPkg();
		if(strpos($spcl_pkg['AdvertiserProfile']['spclpkg'],$home_sav.',')) {
			return 1;
		} else {
			return 0;
		}*/
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.home_page'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['home_page'];
	}
//---------------------------------------------------------------------------------
	function onlyHomePerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.home_page'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['home_page'];
	}			
//---------------------------------------------------------------------------------
	function bannerPerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.banner'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['banner'];
	}
//---------------------------------------------------------------------------------
	function merchantPerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.merchant'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['merchant'];
	}
//---------------------------------------------------------------------------------
	function websitePerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.website'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['website'];
	}
//---------------------------------------------------------------------------------
	function mapPerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.promotional'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['promotional'];
	}
//---------------------------------------------------------------------------------
	function picturePerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.map'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['map'];
	}
//---------------------------------------------------------------------------------
	function videoPerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.video'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['video'];
	}
//---------------------------------------------------------------------------------
	function vipPerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.vip'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['vip'];
	}
//---------------------------------------------------------------------------------
	function contestPerm($ad_id) {
		$order = $this->getonlyOrderId($ad_id);
		App::import('model','AdvertiserOrder');
	    $this->AdvertiserOrder = new AdvertiserOrder();
		$package = $this->AdvertiserOrder->find('first',array('fields'=>array('AdvertiserOrder.package_id'),'conditions'=>array('AdvertiserOrder.id'=>$order)));		
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.is_contest'),'conditions'=>array('Package.id'=>$package['AdvertiserOrder']['package_id'])));
		return $Package['Package']['is_contest'];
	}
//---------------------------------------------------------------------------------
	function pkgMonthlyFee($pkg_id) {
		App::import('model','Package');
		$this->Package = new Package();
		$Package = $this->Package->find('first',array('fields'=>('Package.monthly_price'),'conditions'=>array('Package.id'=>$pkg_id)));
		return number_format($Package['Package']['monthly_price'],2);
	}
//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList-------------------------	  
	function getAdvertiserProfilesForDeal(){
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();
			$ids = '';
			$all_ids = 0;
			$ad_id = $this->AdvertiserProfile->find('all', array('fields' => array('id'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.publish' => 'yes')));
			if(is_array($ad_id) && !empty($ad_id)) {
				foreach($ad_id as $ad_id) {
					$id = $ad_id['AdvertiserProfile']['id'];
					if($this->homeDealPerm($id) || $this->categoryDealPerm($id)) {
						$ids[] = $id;
					}
				}
			}
			if(is_array($ids)) {
				$all_ids = implode(',',$ids);
			}
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.id IN ('.$all_ids.')')));
			return $AdvertiserProfileList;
	}
//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList-------------------------	  
	function getAdvertiserProfilesForDiscount(){
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();
			$ids = '';
			$all_ids = 0;
			$ad_id = $this->AdvertiserProfile->find('all', array('fields' => array('id'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.publish' => 'yes')));
			if(is_array($ad_id) && !empty($ad_id)) {
				foreach($ad_id as $ad_id) {
					$id = $ad_id['AdvertiserProfile']['id'];
					if($this->homeDiscountPerm($id) || $this->categoryDiscountPerm($id)) {
						$ids[] = $id;
					}
				}
			}
			if(is_array($ids)) {
				$all_ids = implode(',',$ids);
			}
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.id IN ('.$all_ids.')')));
			return $AdvertiserProfileList;
	}
//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList-------------------------	  
	function getAdvertiserProfilesForSaving(){
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();
			$ids = '';
			$all_ids = 0;
			$ad_id = $this->AdvertiserProfile->find('all', array('fields' => array('id'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.publish' => 'yes')));
			if(is_array($ad_id) && !empty($ad_id)) {
				foreach($ad_id as $ad_id) {
					$id = $ad_id['AdvertiserProfile']['id'];
					if($this->homeSavingPerm($id) || $this->categorySavingPerm($id)) {
						$ids[] = $id;
					}
				}
			}
			if(is_array($ids)) {
				$all_ids = implode(',',$ids);
			}
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.id IN ('.$all_ids.')')));
			return $AdvertiserProfileList;
	}
//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList-------------------------	  
	function getAdvertiserProfilesForVip(){
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();
			$ids = '';
			$all_ids = 0;
			$ad_id = $this->AdvertiserProfile->find('all', array('fields' => array('id'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.publish' => 'yes')));
			if(is_array($ad_id) && !empty($ad_id)) {
				foreach($ad_id as $ad_id) {
					$id = $ad_id['AdvertiserProfile']['id'];
					if($this->vipPerm($id)) {
						$ids[] = $id;
					}
				}
			}
			if(is_array($ids)) {
				$all_ids = implode(',',$ids);
			}
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.id IN ('.$all_ids.')')));
			return $AdvertiserProfileList;
	}
//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList-------------------------	  
	function getAdvertiserProfilesForBanner(){
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();
			$ids = '';
			$all_ids = 0;
			$ad_id = $this->AdvertiserProfile->find('all', array('fields' => array('id'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.publish' => 'yes')));
			if(is_array($ad_id) && !empty($ad_id)) {
				foreach($ad_id as $ad_id) {
					$id = $ad_id['AdvertiserProfile']['id'];
					if($this->bannerPerm($id)) {
						$ids[] = $id;
					}
				}
			}
			if(is_array($ids)) {
				$all_ids = implode(',',$ids);
			}
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.id IN ('.$all_ids.')')));
			return $AdvertiserProfileList;
	}	
	//-----------------------------Listing of all department-------------------------	  
	function getAllDepartment(){
	
	 		App::import('model','Department');
		    $this->Department = new Department(); 
			
			$DepartmentList = $this->Department->find('list', array('fields' => array('id', 'name'),'order' => 'Department.name ASC','recursive' => -1,'conditions' => array('Department.status' => 'yes'))); 
			
			return $DepartmentList;
	      }		
	//-----------------------------get department name by id-------------------------	  
	function getDepartmentNameById($did){
	 		App::import('model','Department');
		    $this->Department = new Department();			
			$DepartmentName = $this->Department->find('first', array('fields' => array('id', 'name'),'recursive' => -1,'conditions' => array('Department.id' => $did)));		
			return $DepartmentName['Department']['name'];
	      }
	//-----------------------------get department name by id-------------------------		  
	function subcats($cat_id,$ccounty) {
			App::import('model','Subcategory');
		    $this->Subcategory = new Subcategory();
			$sub = $this->Subcategory->find('all',array('conditions'=>array('Subcategory.publish'=>'yes',"Subcategory.category_id LIKE Concat('%,',$cat_id,',%')"),'order'=>array('Subcategory.order,Subcategory.categoryname')));
			return $sub;
			
			/*,"Subcategory.county LIKE Concat('%,".$ccounty.",%')"*/
	}
	//-----------------------------get department name by id-------------------------		  
	function todayDiscount($cat,$county) {
			App::import('model','DailyDiscount');
		    $this->DailyDiscount = new DailyDiscount();
			$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$today2 = mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			$daily_discount = $this->DailyDiscount->find('all',array('fields'=>array('DailyDiscount.banner_image','DailyDiscount.advertiser_profile_id','DailyDiscount.before_noon_saving','DailyDiscount.title','DailyDiscount.unique'),'conditions'=>array("DailyDiscount.status='yes' AND ((DailyDiscount.c_s_date<=$today1 AND DailyDiscount.c_e_date>=$today2) OR (DailyDiscount.s_date<=$today1 AND DailyDiscount.e_date>=$today2)) AND DailyDiscount.advertiser_county_id=".$county." AND (DailyDiscount.show_on_home_page=1) AND DailyDiscount.category=".$cat),'order'=>array('DailyDiscount.id')));
			return $daily_discount;
	}
	//-----------------------------get department name by id-------------------------		
function discount_email() {
			App::import('model','Setting');
		    $this->Setting = new Setting();
			$rate = $this->Setting->find('first', array('fields' => array('Setting.discount_email'))); 
			return $rate['Setting']['discount_email'];
	}
//-----------------------------get department name by id-------------------------		
function meta_details($state='',$county='',$city='',$cat='',$subcat='') {
			if($state!='') {
				$state = $this->getStateIdByUrl($state);
			} else {
				$state = 0;
			}	
			if($county!='') {
				$county = $this->getCountyIdByUrl($county);
			} else {
				$county = 0;
			}
			if($city!='') {
				$city = $this->getCityIdByUrl($city);
			} else {
				$city = 0;
			}
			if($cat!='') {
				$cat = $this->getCatIdByUrl($cat);
			} else {
				$cat = 0;
			}
			if($subcat!='') {
				$subcat = $this->getSubcatIdByUrl($subcat);
			} else {
				$subcat = 0;
			}
			App::import('model','Meta');
		    $this->Meta = new Meta();
			$arr = array('Meta.state_id'=>$state,'Meta.county_id'=>$county,'Meta.city_id'=>$city,'Meta.category_id'=>$cat,'Meta.subcategory_id'=>$subcat);
			$meta_arr = $this->Meta->find('first', array('fields' => array('Meta.meta_title','Meta.meta_description','Meta.meta_keyword'),'conditions'=>array('Meta.state_id'=>$state,'Meta.county_id'=>$county,'Meta.city_id'=>$city,'Meta.category_id'=>$cat,'Meta.subcategory_id'=>$subcat))); 
			return $meta_arr;
	}
//------------------------------------------Function to get all parent cats -------------------------------//
	function getAllParentCats() {
			App::import('model','Category');
		    $this->Category = new Category();
			$catlist = $this->Category->find('list',array('conditions'=>array('Category.publish'=>'yes'),'fields'=>array('Category.id'),'order'=>array('Category.order,Category.categoryname')));
			return array_values($catlist);
	}
//-----------------------------------------------------------------------------------------//	  
	function discount_history($user_id) {
			App::import('model','DiscountUser');
		    $this->DiscountUser = new DiscountUser();
			$history = $this->DiscountUser->find('all',array('fields'=>array('AdvertiserProfile.company_name','DailyDiscount.title','DiscountUser.purchase_date','DiscountUser.vouchers','DiscountUser.total_price','DiscountUser.id'),'conditions'=>array('DiscountUser.front_user_id'=>$user_id),'order'=>array('DiscountUser.id'=>'DESC')));
			return $history;
	}
//---------------------------------------------------------------------------------------//
	function discountPurchaseDate($voucher) {
		App::import('model','DiscountInfo');
		$this->DiscountInfo = new DiscountInfo();
		$discount = $this->DiscountInfo->find('first',array('fields'=>array('DiscountInfo.created'),'conditions'=>array('DiscountInfo.voucher'=>$voucher)));
		return $discount['DiscountInfo']['created'];
	}
//---------------------------------------------------------------------------------------//	
	function advertiserCats($id)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$details=$this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.category','AdvertiserProfile.subcategory','AdvertiserProfile.cat_subcat'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
		return $details['AdvertiserProfile'];
	}
//---------------------------------------------------------------------------------------//	
	function checkDelivery($id,$logid,$model = 'Emaillog')
	{
		App::import('model',$model);
		$this->$model = new $model();
		$details = $this->$model->find('first',array('conditions'=>array($model.".sent LIKE '%,".$id.",%'",$model.'.id'=>$logid)));
		if(!empty($details)) {
			return 'Yes';
		} else {
			return 'No';
		}
	}
//---------------------------------------------------------------------------------------//
	function checkOpened($id,$logid,$model = 'Emaillog')
	{
		App::import('model',$model);
		$this->$model = new $model();
		$details = $this->$model->find('first',array('conditions'=>array($model.".opened LIKE '%,".$id.",%'",$model.'.id'=>$logid)));
		if(!empty($details)) {
			return 'Yes';
		} else {
			return 'No';
		}
	}
//---------------------------------------------------------------------------------------//	
	function checkEmailOpen($id,$logid,$model = 'Emaillog')
	{
		App::import('model',$model);
		$this->$model = new $model();
		$details = $this->$model->find('first',array('conditions'=>array($model.".email_opened LIKE '%,".$id.",%'",$model.'.id'=>$logid)));
		if(!empty($details)) {
			return 'Yes';
		} else {
			return 'No';
		}
	}
//---------------------------------------------------------------------------------------//	
	function checkOfferDelivery($id,$logid)
	{
		App::import('model','Offeremaillog');
		$this->Offeremaillog = new Offeremaillog();
		$details = $this->Offeremaillog->find('first',array('conditions'=>array("Offeremaillog.sent LIKE '%,".$id.",%'",'Offeremaillog.id'=>$logid)));
		if(!empty($details)) {
			return 'Yes';
		} else {
			return 'No';
		}
	}
//---------------------------------------------------------------------------------------//	
	function checkFreebieDelivery($id,$logid)
	{
		App::import('model','Freebieemaillog');
		$this->Freebieemaillog = new Freebieemaillog();
		$details = $this->Freebieemaillog->find('first',array('conditions'=>array("Freebieemaillog.sent LIKE '%,".$id.",%'",'Freebieemaillog.id'=>$logid)));
		if(!empty($details)) {
			return 'Yes';
		} else {
			return 'No';
		}
	}	
//---------------------------------------------------------------------------------------//	
	function checkOfferOpened($id,$logid)
	{
		App::import('model','Offeremaillog');
		$this->Offeremaillog = new Offeremaillog();
		$details = $this->Offeremaillog->find('first',array('conditions'=>array("Offeremaillog.opened LIKE '%,".$id.",%'",'Offeremaillog.id'=>$logid)));
		if(!empty($details)) {
			return 'Yes';
		} else {
			return 'No';
		}
	}
//---------------------------------------------------------------------------------------//	
	function checkFreebieOpened($id,$logid)
	{
		App::import('model','Freebieemaillog');
		$this->Freebieemaillog = new Freebieemaillog();
		$details = $this->Freebieemaillog->find('first',array('conditions'=>array("Freebieemaillog.opened LIKE '%,".$id.",%'",'Freebieemaillog.id'=>$logid)));
		if(!empty($details)) {
			return 'Yes';
		} else {
			return 'No';
		}
	}	
//---------------------------------------------------------------------------------------//	
	function checkOfferEmailOpen($id,$logid)
	{
		App::import('model','Offeremaillog');
		$this->Offeremaillog = new Offeremaillog();
		$details = $this->Offeremaillog->find('first',array('conditions'=>array("Offeremaillog.email_opened LIKE '%,".$id.",%'",'Offeremaillog.id'=>$logid)));
		if(!empty($details)) {
			return 'Yes';
		} else {
			return 'No';
		}
	}
//---------------------------------------------------------------------------------------//	
	function checkFreebieEmailOpen($id,$logid)
	{
		App::import('model','Freebieemaillog');
		$this->Freebieemaillog = new Freebieemaillog();
		$details = $this->Freebieemaillog->find('first',array('conditions'=>array("Freebieemaillog.email_opened LIKE '%,".$id.",%'",'Freebieemaillog.id'=>$logid)));
		if(!empty($details)) {
			return 'Yes';
		} else {
			return 'No';
		}
	}	
//---------------------------------------------------------------------------------------//	
	function saveEmailOpen($unique,$user_id)
	{
		if($unique!='' && $user_id!='') {
			App::import('model','Emaillog');
			$this->Emaillog = new Emaillog();
			$details = $this->Emaillog->find('first',array('fields'=>array('Emaillog.id','Emaillog.opened'),'conditions'=>array('Emaillog.unique'=>$unique)));
			$save = '';
			$save['Emaillog']['id'] = $details['Emaillog']['id'];
			$save['Emaillog']['opened'] = str_replace(','.$user_id.',','',$details['Emaillog']['opened']).','.$user_id.',';
			$this->Emaillog->save($save,false);
		}
	}
//---------------------------------------------------------------------------------------//
	function saveOfferLinkOpen($unique,$user_id)
	{
		if($unique!='' && $user_id!='') {
			App::import('model','Offeremaillog');
			$this->Offeremaillog = new Offeremaillog();
			$details = $this->Offeremaillog->find('first',array('fields'=>array('Offeremaillog.id','Offeremaillog.opened'),'conditions'=>array('Offeremaillog.unique'=>$unique)));
			$save = '';
			$save['Offeremaillog']['id'] = $details['Offeremaillog']['id'];
			$save['Offeremaillog']['opened'] = str_replace(','.$user_id.',','',$details['Offeremaillog']['opened']).','.$user_id.',';
			$this->Offeremaillog->save($save,false);
		}
	}
	
//---------------------------------------------------------------------------------------//
	function saveFreebieLinkOpen($unique,$user_id)
	{
		if($unique!='' && $user_id!='') {
			App::import('model','Freebieemaillog');
			$this->Freebieemaillog = new Freebieemaillog();
			$details = $this->Freebieemaillog->find('first',array('fields'=>array('Freebieemaillog.id','Freebieemaillog.opened'),'conditions'=>array('Freebieemaillog.unique'=>$unique)));
			if(isset($details['Freebieemaillog']['id'])) {
				$save = '';
				$save['Freebieemaillog']['id'] = $details['Freebieemaillog']['id'];
				$save['Freebieemaillog']['opened'] = str_replace(','.$user_id.',','',$details['Freebieemaillog']['opened']).','.$user_id.',';
				$this->Freebieemaillog->save($save,false);
			}	
		}
	}
//---------------------------------------------------------------------------------------//
	function saveCareLinkOpen($unique,$user_id)
	{
		if($unique!='' && $user_id!='') {
			App::import('model','Careemaillog');
			$this->Careemaillog = new Careemaillog();
			$details = $this->Careemaillog->find('first',array('fields'=>array('Careemaillog.id','Careemaillog.opened'),'conditions'=>array('Careemaillog.unique'=>$unique)));
			$save = '';
			$save['Careemaillog']['id'] = $details['Careemaillog']['id'];
			$save['Careemaillog']['opened'] = str_replace(','.$user_id.',','',$details['Careemaillog']['opened']).','.$user_id.',';
			$this->Careemaillog->save($save,false);
		}
	}
//---------------------------------------------------------------------------------------//
	function saveContestLinkOpen($unique,$user_id)
	{
		if($unique!='' && $user_id!='') {
			App::import('model','Contestemaillog');
			$this->Contestemaillog = new Contestemaillog();
			$details = $this->Contestemaillog->find('first',array('fields'=>array('Contestemaillog.id','Contestemaillog.opened'),'conditions'=>array('Contestemaillog.unique'=>$unique)));
			$save = '';
			$save['Contestemaillog']['id'] = $details['Contestemaillog']['id'];
			$save['Contestemaillog']['opened'] = str_replace(','.$user_id.',','',$details['Contestemaillog']['opened']).','.$user_id.',';
			$this->Contestemaillog->save($save,false);
		}
	}
//---------------------------------------------------------------------------------------//
	function saveEmailOpenStatus($unique,$user_id)
	{
		if($unique!='' && $user_id!='') {
			App::import('model','Emaillog');
			$this->Emaillog = new Emaillog();
			$details = $this->Emaillog->find('first',array('fields'=>array('Emaillog.id','Emaillog.email_opened'),'conditions'=>array('Emaillog.unique'=>$unique)));
			$save = '';
			$save['Emaillog']['id'] = $details['Emaillog']['id'];
			$save['Emaillog']['email_opened'] = str_replace(','.$user_id.',','',$details['Emaillog']['email_opened']).','.$user_id.',';
			$this->Emaillog->save($save,false);
		}
	}
//---------------------------------------------------------------------------------------//
	function saveOfferEmailOpenStatus($unique,$user_id)
	{
		if($unique!='' && $user_id!='') {
			App::import('model','Offeremaillog');
			$this->Offeremaillog = new Offeremaillog();
			$details = $this->Offeremaillog->find('first',array('fields'=>array('Offeremaillog.id','Offeremaillog.email_opened'),'conditions'=>array('Offeremaillog.unique'=>$unique)));
			if(isset($details['Offeremaillog']['id'])) {
				$save = '';
				$save['Offeremaillog']['id'] = $details['Offeremaillog']['id'];
				$save['Offeremaillog']['email_opened'] = str_replace(','.$user_id.',','',$details['Offeremaillog']['email_opened']).','.$user_id.',';
				$this->Offeremaillog->save($save,false);
			}	
		}
	}
//---------------------------------------------------------------------------------------//
	function saveFreebieEmailOpenStatus($unique,$user_id)
	{
		if($unique!='' && $user_id!='') {
			App::import('model','Freebieemaillog');
			$this->Freebieemaillog = new Freebieemaillog();
			$details = $this->Freebieemaillog->find('first',array('fields'=>array('Freebieemaillog.id','Freebieemaillog.email_opened'),'conditions'=>array('Freebieemaillog.unique'=>$unique)));
			if(isset($details['Freebieemaillog']['id'])) {
				$save = '';
				$save['Freebieemaillog']['id'] = $details['Freebieemaillog']['id'];
				$save['Freebieemaillog']['email_opened'] = str_replace(','.$user_id.',','',$details['Freebieemaillog']['email_opened']).','.$user_id.',';
				$this->Freebieemaillog->save($save,false);
			}
		}
	}	
//---------------------------------------------------------------------------------------//
	function saveContestEmailOpenStatus($unique,$user_id)
	{
		if($unique!='' && $user_id!='') {
			App::import('model','Contestemaillog');
			$this->Contestemaillog = new Contestemaillog();
			$details = $this->Contestemaillog->find('first',array('fields'=>array('Contestemaillog.id','Contestemaillog.email_opened'),'conditions'=>array('Contestemaillog.unique'=>$unique)));
			$save = '';
			$save['Contestemaillog']['id'] = $details['Contestemaillog']['id'];
			$save['Contestemaillog']['email_opened'] = str_replace(','.$user_id.',','',$details['Contestemaillog']['email_opened']).','.$user_id.',';
			$this->Contestemaillog->save($save,false);
		}
	}
//---------------------------------------------------------------------------------------//
	function saveCareEmailOpenStatus($unique,$user_id)
	{
		if($unique!='' && $user_id!='') {
			App::import('model','Careemaillog');
			$this->Careemaillog = new Careemaillog();
			$details = $this->Careemaillog->find('first',array('fields'=>array('Careemaillog.id','Careemaillog.email_opened'),'conditions'=>array('Careemaillog.unique'=>$unique)));
			$save = '';
			$save['Careemaillog']['id'] = $details['Careemaillog']['id'];
			$save['Careemaillog']['email_opened'] = str_replace(','.$user_id.',','',$details['Careemaillog']['email_opened']).','.$user_id.',';
			$this->Careemaillog->save($save,false);
		}
	}
//---------------------------------------------------------------------------------------//	
	function logDates($Emaillog = 'Emaillog')
	{
		App::import('model',$Emaillog);
		$this->$Emaillog = new $Emaillog();
		$date_arr = array();
		$details = $this->$Emaillog->find('all',array('fields'=>array($Emaillog.'.unique',$Emaillog.'.sending_date'),'order'=>array($Emaillog.'.id DESC')));
		
		if(!empty($details)) {
			foreach($details as $details) {
				$date_arr[$details[$Emaillog]['unique']] = date(DATE_FORMAT,$details[$Emaillog]['sending_date']);
			}
		}
		return $date_arr;
	}
//---------------------------------------------------------------------------------------//
	function offerlogDates()
	{
		App::import('model','Offeremaillog');
		$this->Offeremaillog = new Offeremaillog();
		$date_arr = array();
		$details = $this->Offeremaillog->find('all',array('fields'=>array('Offeremaillog.unique','Offeremaillog.sending_date'),'order'=>array('Offeremaillog.id DESC')));
		
		if(!empty($details)) {
			foreach($details as $details) {
				$date_arr[$details['Offeremaillog']['unique']] = date(DATE_FORMAT,$details['Offeremaillog']['sending_date']);
			}	
		}
		return $date_arr;
	}
//---------------------------------------------------------------------------------------//
	function freebielogDates()
	{
		App::import('model','Freebieemaillog');
		$this->Freebieemaillog = new Freebieemaillog();
		$date_arr = array();
		$details = $this->Freebieemaillog->find('all',array('fields'=>array('Freebieemaillog.unique','Freebieemaillog.sending_date'),'order'=>array('Freebieemaillog.id DESC')));
		
		if(!empty($details)) {
			foreach($details as $details) {
				$date_arr[$details['Freebieemaillog']['unique']] = date(DATE_FORMAT,$details['Freebieemaillog']['sending_date']);
			}	
		}
		return $date_arr;
	}			
	
//---------------------------------------------------------------------------------------//	
	function check_same_date($date)
	{
		App::import('model','Emaillog');
		$this->Emaillog = new Emaillog();
		$details = $this->Emaillog->find('first',array('conditions'=>array('Emaillog.sending_date'=>$date)));
		return $details;
	}
//---------------------------------------------------------------------------------------//	
	function check_same_date_offer($date)
	{
		App::import('model','Offeremaillog');
		$this->Offeremaillog = new Offeremaillog();
		$details = $this->Offeremaillog->find('first',array('conditions'=>array('Offeremaillog.sending_date'=>$date)));
		return $details;
	}
//---------------------------------------------------------------------------------------//	
	function check_same_date_freebie($date)
	{
		App::import('model','Freebieemaillog');
		$this->Freebieemaillog = new Freebieemaillog();
		$details = $this->Freebieemaillog->find('first',array('conditions'=>array('Freebieemaillog.sending_date'=>$date)));
		return $details;
	}	
//---------------------------------------------------------------------------------------//
	function checkZuniCare($saving,$county)
	{
		$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
		App::import('model','SavingOffer');
		$this->SavingOffer = new SavingOffer();
		$offer = $this->SavingOffer->find('first',array('fields'=>array('AdvertiserProfile.company_name','SavingOffer.offer_start_date','SavingOffer.offer_expiry_date'),'conditions'=>array('SavingOffer.zuni_care'=>1,'SavingOffer.id !='.$saving,'AdvertiserProfile.county'=>$county,'SavingOffer.offer_start_date <='.$today,'SavingOffer.offer_expiry_date >='.$today)));
		return $offer;
	}
//---------------------------------------------------------------------------------------//
	function checkSavingOffer($id)
	{
		App::import('model','SavingOffer');
		$this->SavingOffer = new SavingOffer();
		$offer = $this->SavingOffer->find('first',array('fields'=>array('SavingOffer.offer_expiry_date','SavingOffer.status'),'conditions'=>array('SavingOffer.id'=>$id)));
		return $offer['SavingOffer'];
	}
//---------------------------------------------------------------------------------------//	
	function getZuniCare($county)
	{
		$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
		App::import('model','SavingOffer');
		$this->SavingOffer = new SavingOffer();
		$offer = $this->SavingOffer->find('first',array('conditions'=>array('SavingOffer.zuni_care'=>1,'AdvertiserProfile.county'=>$county,'SavingOffer.offer_start_date <='.$today,'SavingOffer.offer_expiry_date >='.$today)));
		return $offer;
	}
function getSavingOfferById($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();			
			$SavingOffer = $this->SavingOffer->find('all',array('fields'=>('SavingOffer.id,SavingOffer.off_unit,SavingOffer.off,SavingOffer.no_valid_other_offer ,SavingOffer.no_transferable,SavingOffer.other,SavingOffer.title,SavingOffer.offer_image_small,SavingOffer.offer_image_big,SavingOffer.current_saving_offer,SavingOffer.other_saving_offer,SavingOffer.zuni_care,SavingOffer.disclaimer,SavingOffer.off_text'),'conditions'=>array('SavingOffer.id'=>$id)));
			return $SavingOffer;	
	}	
//---------------------------------------------------------------------------------------//	
	function getCompnyAddress($id)
	{
		$address = '';
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$advertiser = $this->AdvertiserProfile->find('first',array('conditions'=>array('AdvertiserProfile.id'=>$id)));
		
		if($advertiser['AdvertiserProfile']['show_address']=='yes') {
			$address .= $advertiser['AdvertiserProfile']['address'].'<br />';
			if($common->getCityName($advertiser['AdvertiserProfile']['city'])!='') {
				$address .= $common->getCityName($advertiser['AdvertiserProfile']['city']).', ';
			}	
			$address .= $common->getStateName($advertiser['AdvertiserProfile']['state']);
			if($advertiser['AdvertiserProfile']['zip']!='') {
				$address .= ', '.$advertiser['AdvertiserProfile']['zip'];
			}
			$address .= '<br /><br />';
		}
		if($advertiser['AdvertiserProfile']['show_address2']=='yes') {
			$address .= $advertiser['AdvertiserProfile']['address2'].'<br />';
			if($common->getCityName($advertiser['AdvertiserProfile']['city2'])!='') {
				$address .= $common->getCityName($advertiser['AdvertiserProfile']['city2']).', ';
			}	
			$address .= $common->getStateName($advertiser['AdvertiserProfile']['state']);
			if($advertiser['AdvertiserProfile']['zip2']!='') {
				$address .= ', '.$advertiser['AdvertiserProfile']['zip2'];
			}
			$address .= '<br />';
		}
		$add = '';
		if($advertiser['AdvertiserProfile']['phoneno']!='') {
			$add[] .= $advertiser['AdvertiserProfile']['phoneno'];
		}
		if($advertiser['AdvertiserProfile']['phoneno2']!='') {
			$add[] .= $advertiser['AdvertiserProfile']['phoneno2'];
		}
		$address .= '<br />'.implode(', ',$add);
	}	
	function usertype($id) {
		App::import('model','FrontUser');
		$this->FrontUser = new FrontUser();
		$FrontUser = $this->FrontUser->find('first',array('fields'=>array('FrontUser.type'),'conditions'=>array('FrontUser.id'=>$id)));
		return $FrontUser['FrontUser']['type'];
	}
//--------------------------get email permissions-------------------------------------------------------------//
	function getEmailPermissions($myid)
	{
		App::import('model','UserGroup');
		$this->UserGroup = new UserGroup();
		$permisssions = $this->UserGroup->find('first',array('conditions'=>array('UserGroup.id'=>$myid)));
		return $permisssions['UserGroup'];
	}
//--------------------------get email permissions-------------------------------------------------------------//
	function salesIdForAdvertiser($id)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$data = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.creator'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
		return $data['AdvertiserProfile']['creator'];
	}
//--------------------------get email permissions-------------------------------------------------------------//
	function getOfferContentsFromSetting()
	{
		App::import('model','Setting');
		$this->Setting = new Setting();
		$data = $this->Setting->find('first',array('fields'=>array('Setting.offer_content'),'conditions'=>array('Setting.id'=>1)));
		return $data['Setting']['offer_content'];
	}
//--------------------------get email permissions-------------------------------------------------------------//
	function getOfferSubjectFromSetting()
	{
		App::import('model','Setting');
		$this->Setting = new Setting();
		$data = $this->Setting->find('first',array('fields'=>array('Setting.offer_title'),'conditions'=>array('Setting.id'=>1)));
		return $data['Setting']['offer_title'];
	}	
//--------------------------get email permissions-------------------------------------------------------------//
	function advertiserAddress($id)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$data = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.show_address','AdvertiserProfile.address','AdvertiserProfile.city','AdvertiserProfile.state','AdvertiserProfile.zip','AdvertiserProfile.phoneno','AdvertiserProfile.show_address2','AdvertiserProfile.address2','AdvertiserProfile.city2','AdvertiserProfile.zip2','AdvertiserProfile.phoneno2'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			$advertiser_address = '';
			if($data['AdvertiserProfile']['show_address']=='yes') {
				$advertiser_address .= $data['AdvertiserProfile']['address'].'<br />'.$this->getCityName($data['AdvertiserProfile']['city']).', '.$this->getStateName($data['AdvertiserProfile']['state']).' '.$data['AdvertiserProfile']['zip'].'<br />'.$data['AdvertiserProfile']['phoneno'];
			} else if($data['AdvertiserProfile']['show_address2']=='yes') {
				$advertiser_address .= '<br />'.$data['AdvertiserProfile']['address2'].'<br />'.$this->getCityName($data['AdvertiserProfile']['city2']).', '.$this->getStateName($data['AdvertiserProfile']['state']).' '.$data['AdvertiserProfile']['zip2'].'<br />'.$data['AdvertiserProfile']['phoneno2'];
			}
			return $advertiser_address;
	}
	
//--------------------------get email permissions-------------------------------------------------------------//
	function advertiserBothAddress($id)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$data = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.show_address','AdvertiserProfile.address','AdvertiserProfile.city','AdvertiserProfile.state','AdvertiserProfile.county','AdvertiserProfile.country','AdvertiserProfile.zip','AdvertiserProfile.phoneno','AdvertiserProfile.show_address2','AdvertiserProfile.address2','AdvertiserProfile.city2','AdvertiserProfile.zip2','AdvertiserProfile.phoneno2'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			$advertiser_address = '';
			if($data['AdvertiserProfile']['show_address']=='yes') {
				$advertiser_address .= $data['AdvertiserProfile']['address'].'<br />'.$this->getCityName($data['AdvertiserProfile']['city']).', '.$this->getStateName($data['AdvertiserProfile']['state']).' '.$data['AdvertiserProfile']['zip'].'<br />'.$data['AdvertiserProfile']['phoneno'];
			}else{
				$advertiser_address .= $this->getCountyName($data['AdvertiserProfile']['county']).', '.$this->getStateName($data['AdvertiserProfile']['state']).', '.$this->getCountryName($data['AdvertiserProfile']['country']).'<br />'.$data['AdvertiserProfile']['phoneno'];
			}
			if($data['AdvertiserProfile']['show_address2']=='yes') {
				$advertiser_address .= '<br />'.$data['AdvertiserProfile']['address2'].'<br />'.$this->getCityName($data['AdvertiserProfile']['city2']).', '.$this->getStateName($data['AdvertiserProfile']['state']).' '.$data['AdvertiserProfile']['zip2'].'<br />'.$data['AdvertiserProfile']['phoneno2'];
			}else{
				$advertiser_address .= $data['AdvertiserProfile']['phoneno2'];
			}
			return $advertiser_address;
	}	

/* -----------get offer email subject from setting---*/
	function getOfferEmailSubject() {
			App::import('model','Setting');
		    $this->Setting = new Setting();
			$rate = $this->Setting->find('first', array('fields' => array('Setting.offer_email_subject'))); 
			return $rate['Setting']['offer_email_subject'];
	}
/* -----------get offer email footer text from setting---*/
	function getOfferEmailFooterText() {
			App::import('model','Setting');
		    $this->Setting = new Setting();
			$rate = $this->Setting->find('first', array('fields' => array('Setting.offer_email_footer_text'))); 
			return $rate['Setting']['offer_email_footer_text'];
	}
	//-----------------------------get advertiser details with order details-------------------------	  
	function getAdvertiserdetailswithOrder($aid){
	
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile(); 
			
			$AdvertiserdetailswithOrder = $this->AdvertiserProfile->find('first', array('fields' => array('AdvertiserProfile.id', 'AdvertiserProfile.order_id','AdvertiserProfile.creator'),'conditions' => array('AdvertiserProfile.id' => $aid))); 
			return $AdvertiserdetailswithOrder['AdvertiserProfile'];
	  }
//--------------------------get both addresses of advertiser for front deal popup-------------------------------------------------------------//
	function advertiserBothAddressDealPopup($id)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$data = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.show_address','AdvertiserProfile.address','AdvertiserProfile.city','AdvertiserProfile.state','AdvertiserProfile.zip','AdvertiserProfile.phoneno','AdvertiserProfile.show_address2','AdvertiserProfile.address2','AdvertiserProfile.city2','AdvertiserProfile.zip2','AdvertiserProfile.phoneno2'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			$advertiser_address = '';
			if($data['AdvertiserProfile']['show_address']=='yes') {
				$advertiser_address .= $data['AdvertiserProfile']['address'].'<br />'.$this->getCityName($data['AdvertiserProfile']['city']).', '.$this->getStateName($data['AdvertiserProfile']['state']).' '.$data['AdvertiserProfile']['zip'].'<br />'.$data['AdvertiserProfile']['phoneno'];
			}
			$advertiser_address .= '_$#*#$_'; //Remove this line at your own risk, no deal can be viewed on front end
			if($data['AdvertiserProfile']['show_address2']=='yes') {
				$advertiser_address .= $data['AdvertiserProfile']['address2'].'<br />';
				if(isset($data['AdvertiserProfile']['city2']) && $data['AdvertiserProfile']['city2']!='')
				$advertiser_address .=$this->getCityName($data['AdvertiserProfile']['city2']).', ';
				$advertiser_address .=$this->getStateName($data['AdvertiserProfile']['state']).' '.$data['AdvertiserProfile']['zip2'].'<br />'.$data['AdvertiserProfile']['phoneno2'];
			}
			return $advertiser_address;
	}
//----------------get master for advertiser setup account------//
	function getMasterPassword()
	{
		App::import('model','Setting');
		$this->Setting = new Setting();
		$Setting=$this->Setting->find('first',array('fields'=>array('Setting.master_pswd')));
		return $Setting['Setting']['master_pswd'];
	}
//----------------function used to insert sent item into sent box------//
	function sentMailLog($from='',$to='',$subject='',$content='',$type='',$attachment='')
	{
		App::import('model','SentBox');
		$this->SentBox = new SentBox();
		$savearr = '';
		$savearr['SentBox']['id'] = '';
		$savearr['SentBox']['from'] = $from;
		$savearr['SentBox']['to'] = $to;
		$savearr['SentBox']['subject'] = $subject;
		$savearr['SentBox']['content'] = $content;
		$savearr['SentBox']['type'] = $type;
		$savearr['SentBox']['read_status'] = 0;
		$savearr['SentBox']['attachment'] = $attachment;
		$this->SentBox->save($savearr);
	}
	  	//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList------------------------//
	function getWholeAdvertiserProfileList(){
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();
			$AdvertiserProfileList = $this->AdvertiserProfile->find('list', array('fields' => array('id', 'company_name'),'order' => 'AdvertiserProfile.company_name ASC','recursive' => -1,'conditions' => array('AdvertiserProfile.company_name!=""')));
			return $AdvertiserProfileList;
	  }
	//-----------------------------Listing of all (published/unpublished) AdvertiserProfileList------------------------//	  
	  	function savingOfferPinit($id){
			App::import('model','SavingOffer');
		    $this->SavingOffer = new SavingOffer();			
			$saving_offer_big = $this->SavingOffer->find('first',array('fields'=>('SavingOffer.off_unit,SavingOffer.off_text,SavingOffer.off,SavingOffer.title,SavingOffer.description,SavingOffer.offer_expiry_date,SavingOffer.no_valid_other_offer,SavingOffer.no_transferable,SavingOffer.other,SavingOffer.disclaimer,SavingOffer.advertiser_profile_id'),'conditions'=>array('SavingOffer.id'=>$id)));
			$content = '';
			$content .= $this->getCompanyNameById($saving_offer_big['SavingOffer']['advertiser_profile_id']).' - ';
			
			if($saving_offer_big['SavingOffer']['off_unit']==2) { $content .= $saving_offer_big['SavingOffer']['off_text'];} else {
			if($saving_offer_big['SavingOffer']['off_unit']==1) { $content .= '$';}
			$content .= $saving_offer_big['SavingOffer']['off'];
			if($saving_offer_big['SavingOffer']['off_unit']==0) {$content  .= '%';} $content .= ' OFF'; }
			 //$content  .= '. '.$saving_offer_big['SavingOffer']['title'].' ('.$this->getCompanyNameById($saving_offer_big['SavingOffer']['advertiser_profile_id']).')';
            $content  .= '. '.$saving_offer_big['SavingOffer']['title'];
			
			$content  .= '. '.strip_tags($saving_offer_big['SavingOffer']['description']);
			$content  .= '. Expires: '.date(DATE_FORMAT,$saving_offer_big['SavingOffer']['offer_expiry_date']);
            if($saving_offer_big['SavingOffer']['no_valid_other_offer']==1){ $content .= '. Not valid with any other offer.';}
			if($saving_offer_big['SavingOffer']['no_transferable']==1){ $content .= ' Non-transferable / Not for resale / Not redeemable for cash.';}
			if($saving_offer_big['SavingOffer']['other']==1){ $content .= ' '.$saving_offer_big['SavingOffer']['disclaimer'];}
			
			return stripslashes(strip_tags(str_replace('"','&#34;',$content)));	
	}
//----------------function used to insert sent item into sent box------//
	function saveDeleveryTracking($model='Contestemaillog',$user_id='',$status='sent',$unique_string)
	{
		App::import('model',$model);
		$this->$model = new $model();
		$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$savearr = '';
		$findid = $this->$model->find('first',array('fields'=>array($model.'.id',$model.'.'.$status),'conditions'=>array($model.'.unique'=>$unique_string)));
		
		if(isset($findid[$model]['id'])) {
			$savearr[$model]['id'] = $findid[$model]['id'];
			$savearr[$model][$status] = $findid[$model][$status].','.$user_id.',';
		} else {
			$savearr[$model][$status] = ','.$user_id.',';
			$savearr[$model]['unique'] = $unique_string;
			$savearr[$model]['sending_date'] = $today;
		}
		$this->$model->save($savearr);
	}
	/*Start Function----------------Listing of all categories and subcategories selected by advertiser-----------------------------------------*/	  
	function getAllCatListAjax($county='',$sel_cat='')
     {
				App::import('model','Category');
				$this->Category = new Category();
				
				App::import('model','CountyCategory');
				$this->CountyCategory = new CountyCategory();
				
				$div= '<select name="data[categories][category_id][]" id="category_id" style="height:155px;width:410px;" multiple="multiple">';
				if(isset($county) && $county!='')
					$div .='<option value="">Select Category</option>';
				else
					$div .='<option value="" selected="selected">Select Category</option>';
				
				$sel_catArr='';
				if($sel_cat!='')
				if(!is_array($sel_cat)) {
					$sel_catArr=explode('-',$sel_cat);
				} else {
					$sel_catArr=$sel_cat;
				}
				
				if(isset($county) && $county!='')
				{
				$county=explode('...',$county);
				
				
				
				$cond = '';
				$county_ids = array();
				foreach($county as $county) {
					$county_ids[] = $county;
					//$cond[] = 'county LIKE "%,'.$county.',%"';
				}
				$county_ids = implode(',',$county_ids);
				$catsids = $this->CountyCategory->find('all',array('fields'=>'DISTINCT CountyCategory.category_id','conditions'=>array('CountyCategory.county_id IN('.$county_ids.')')));
				$cats = array();
				if(!empty($catsids)) {
				foreach($catsids as $catsids) {
					$cats[] = $catsids['CountyCategory']['category_id'];
				}
				$cond = 'where id IN('.implode(',',array_filter($cats)).')';
					$catArr = $this->Category->query("select * from categories $cond ORDER BY `categoryname` ASC");				
				if(is_array($catArr) && !empty($catArr)) {
				foreach($catArr as $category)
		    	{
						
					  if(is_array($sel_catArr) && !empty($sel_catArr) && $category['categories']['categoryname']!='' && in_array($category['categories']['id'],$sel_catArr))
						{
						
							$div.= '<option value="'.$category['categories']['id'].'" selected="selected">'.$category['categories']['categoryname'].'</option>';						
						
						}
					  else
						{
						
							$div.= '<option value="'.$category['categories']['id'].'">'.$category['categories']['categoryname'].'</option>';
						}
						
				}
			}
				
		}
	}
		
		$div.='</select>';
	
			return $div;
	 }
	/*Start Function----------------Listing of all categories and subcategories selected by advertiser-----------------------------------------*/		 
	function dealReport($adv_id='',$state='',$county='') {
				   $st_id='';
				   $county_id='';
				   $condi_report='';
				   
				   $st_id=$this->getIdfromPageUrl('State',$state);
				   $county_id=$this->getIdfromPageUrl('County',$county);
				   App::import('model','InnerReport');
				   $this->InnerReport=new InnerReport();
				   $timestamp=$this->getTimeStampReport();
				   $st_id=$st_id['State']['id'];  
				   $county_id=$county_id['County']['id'];
				   $condi_report['InnerReport.state']=$st_id;
				   $condi_report['InnerReport.county']=$county_id;
				   $condi_report['InnerReport.date']=$timestamp;
				   $condi_report['InnerReport.type']='deal';
				   $condi_report['InnerReport.advertiser_id']=$adv_id;
				   $exist_rec=$this->InnerReport->find('first',array('conditions'=>$condi_report));
				  
				   if(empty($exist_rec))
				   {
					   $reportArray=array();
					   $reportArray['InnerReport']['state']=$st_id;
					   $reportArray['InnerReport']['county']=$county_id;
					   $reportArray['InnerReport']['date']=$timestamp;
					   $reportArray['InnerReport']['type']='deal';
					   $reportArray['InnerReport']['advertiser_id']=$adv_id;
					   $reportArray['InnerReport']['no_of_hit']=1;
					   $this->InnerReport->save($reportArray);
				   }
				   else
				   {
					   $reportArray=array();
					   $reportArray['InnerReport']['id']=$exist_rec['InnerReport']['id'];
					   $reportArray['InnerReport']['no_of_hit']=$exist_rec['InnerReport']['no_of_hit']+1;
					   $reportArray['InnerReport']['state']=$st_id;
					   $reportArray['InnerReport']['county']=$county_id;
					   $reportArray['InnerReport']['date']=$timestamp;
					   $reportArray['InnerReport']['advertiser_id']=$adv_id;
					   $reportArray['InnerReport']['type']='deal';
					   $this->InnerReport->save($reportArray);
				   }
	}
//----------------------------------------------------------------------------------------------------------------------------//	
	function getFullUrl($email) {
					App::import('model','FrontUser');
				   	$this->FrontUser=new FrontUser();
					$data = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$email,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.status'=>'yes'),'recursive'=>-1));
					if(!empty($data)) {
						$this->Session->write('Auth.FrontConsumer',$data['FrontUser']);
						if($this->Session->read('Auth.FrontUser')) {
							$this->Session->delete('Auth.FrontUser');
						}
						return FULL_BASE_URL.router::url('/',false).'state/'.$this->getStateUrls($data['FrontUser']['state_id']).'/'.$this->getCountyUrl($data['FrontUser']['county_id']);
					} else {
						return FULL_BASE_URL.router::url('/',false);
					}
	}
//----------------------------------------------------------------------------------------------------------------------------//	
	function getFullUrlAdvertiser($email) {
					App::import('model','FrontUser');
				   	$this->FrontUser=new FrontUser();
					$data = $this->FrontUser->find('first',array('conditions'=>array('FrontUser.email'=>$email,'FrontUser.user_type'=>'advertiser','FrontUser.status'=>'yes'),'recursive'=>-1));
					if(!empty($data)) {
						$this->Session->write('Auth.FrontUser',$data['FrontUser']);
						if($this->Session->read('Auth.FrontConsumer')) {
							$this->Session->delete('Auth.FrontConsumer');
						}
						return FULL_BASE_URL.router::url('/',false).'state/'.$this->getStateUrl($data['FrontUser']['county_id']).'/'.$this->getCountyUrl($data['FrontUser']['county_id']);
					} else {
						return FULL_BASE_URL.router::url('/',false);
					}
	}	
//----------------------------------------------------------------------------------------------------------------------------//	
function checkUserEmail($email) {
					App::import('model','FrontUser');
				   	$this->FrontUser=new FrontUser();
					$data = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.email'=>$email,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.status'=>'yes')));
					if($data) {
						return true;
					}
					return false;
	}
//----------------------------------------------------------------------------------------------------------------------------//	
function checkAdvertiserEmail($email) {
					App::import('model','FrontUser');
				   	$this->FrontUser=new FrontUser();
					$data = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.email'=>$email,'FrontUser.user_type'=>'advertiser','FrontUser.status'=>'yes')));
					if($data) {
						return true;
					}
					return false;
	}
//----------------------------------------------------------------------------------------------------------------------------//	
	function getAllArea() {
		$states = $this->getAllState();
		$area = '';
		foreach($states as $key=>$name) {
			$county = '';
			$county = $this->getAllCountyByState($key);
			foreach($county as $key1=>$name1) {
				$area[$key.'-'.$key1] = $name.' / '.$name1;
			}
		}
		return $area;
	}

//-----------------------------------------Function to get county current image by the county url----------------------------------//	
	function getCountyCurrentImage($page_url)
	{
		$countyId=$this->getCountyIdByUrl($page_url);
		if($countyId)
		{
			 App::import('Model','HeaderLogo');
			 $this->HeaderLogo = new HeaderLogo();
			 $cur_timing=mktime(0,0,0,date('m'),date('d'),date('Y'));
			 $my_current_county_image=$this->HeaderLogo->find('first',array('conditions'=>array('HeaderLogo.county_id'=>$countyId,'HeaderLogo.start_date <='=>$cur_timing,'HeaderLogo.end_date >='=>$cur_timing)));
			 if(!empty($my_current_county_image))
			 	return $my_current_county_image['HeaderLogo']['logo'];
			 else
			 	return false;
			 
		}
		return false;
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getAdvertiserCategoryById($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$myCategory = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.cat_subcat'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			if(isset($myCategory['AdvertiserProfile']['cat_subcat']) && $myCategory['AdvertiserProfile']['cat_subcat']!='')
			{
				return $this->getCategoryName(current(explode('-',current(array_values(array_filter(explode('|',$myCategory['AdvertiserProfile']['cat_subcat'])))))));
			}
			return false;	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getAdvertiserCategoryIdByAdvId($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$myCategory = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.cat_subcat'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			if(isset($myCategory['AdvertiserProfile']['cat_subcat']) && $myCategory['AdvertiserProfile']['cat_subcat']!='')
			{
				return current(explode('-',current(array_values(array_filter(explode('|',$myCategory['AdvertiserProfile']['cat_subcat']))))));
			}
			return false;	
	}
//---------------------------------------------------------------------------------------------------------------------------------//			  
	function getAllAdvertiserCategoryById($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$myCategory = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.cat_subcat'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			if(isset($myCategory['AdvertiserProfile']['cat_subcat']) && $myCategory['AdvertiserProfile']['cat_subcat']!='')
			{
				$advSelectedCats=array();
				$advSelectedSampleCats=array();
				
				$allCatsArr=array_values(array_filter(explode('|',$myCategory['AdvertiserProfile']['cat_subcat'])));
				
				foreach($allCatsArr as $allCatsArrr)
				{
					$curCatArr=explode('-',$allCatsArrr);
					$currCat=$curCatArr[0];
					$currSubCat=$curCatArr[1];
					
					if(!in_array($currCat,$advSelectedSampleCats))
					{
						$advSelectedSampleCats[]=$currCat;
						$advSelectedCats[]=$currCat.'***'.$currSubCat;
					}
				}
				return $advSelectedCats;
			}
			return false;	
	}
	
//---------------------------------------------------------------------------------------------------------------------------------//	
	function validateEmail($email) {
		$email = trim($email);
		if($email) {
		App::import('model','FrontUser');
		$this->FrontUser = new FrontUser();	
			
		App::import('model','NewsletterUser');
		$this->NewsletterUser = new NewsletterUser();	
			
		$totalProfile = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.email'=>$email,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")')));
		$totalNewsltr = $this->NewsletterUser->find('count',array('conditions'=>array('NewsletterUser.email'=>$email,'NewsletterUser.status'=>'yes')));
		if($totalProfile>0 || $totalNewsltr>0) {
			return false;
		}
		return true;
		} else {
			return false;
		}
	}
//-----------------------------------------------------------------------------------------------------------------------------------------//
	function todayContest($county) {
			$contest_timstmp = mktime(0,0,0,date('m'),date('d'),date('Y'));
			App::import('model','Contest');
			$this->Contest = new Contest();
			$today_contest = $this->Contest->find('first',array('conditions'=>array('Contest.county_id'=>$county,'Contest.s_date <='.$contest_timstmp,'Contest.e_date >='.$contest_timstmp,'Contest.status'=>'yes')));
			return $today_contest;
	}
//-----------------------------------------------------------------------------------------------------------------------------------------//
	function contestWinner($county) {
			App::import('model','ContestUser');
			$this->ContestUser = new ContestUser();
			$winner_list = $this->ContestUser->find('all',array('fields'=>array('FrontUser.name','ContestUser.created','Contest.prize','Contest.e_date'),'conditions'=>array('Contest.county_id'=>$county,'ContestUser.winner'=>1),'order'=>array('ContestUser.id'=>'DESC'),'limit'=>4));
			return $winner_list;
	}
//-----------------------------------------------------------------------------------------------------------------------------------------//
	function contestLimit($contest_id,$user_id) {
			App::import('model','Contest');
			$this->Contest = new Contest();
			
			App::import('model','ContestUser');
			$this->ContestUser = new ContestUser();
			
			$limit = $this->Contest->find('first',array('fields'=>array('Contest.user_limit'),'conditions'=>array('Contest.id'=>$contest_id)));
			$userCount = $this->ContestUser->find('count',array('conditions'=>array('ContestUser.contest_id'=>$contest_id,'ContestUser.front_user_id'=>$user_id)));
			
			if(($userCount<$limit['Contest']['user_limit']) || !$limit['Contest']['user_limit']) {
				return true;
			}
			return false;
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------//
	function getDealByUnique($uniqueid){
			App::import('model','DailyDeal');
		    $this->DailyDeal = new DailyDeal();
			$full_url = '';	
			$DailyDeal = $this->DailyDeal->find('first',array('fields'=>array('DailyDeal.advertiser_county_id'),'conditions'=>array("DailyDeal.unique"=>$uniqueid)));
			if(isset($DailyDeal['DailyDeal']['advertiser_county_id'])) {
				$county = $DailyDeal['DailyDeal']['advertiser_county_id'];
				return FULL_BASE_URL.router::url('/',false).'state/'.$this->getStateUrl($county).'/'.$this->getCountyUrl($county).'/dailydeal?unique='.$uniqueid;
			} else {
				return FULL_BASE_URL.router::url('/',false).'state/'.$this->getStateUrl($county).'/'.$this->getCountyUrl($county);
			}
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------//
	function getDiscountByUnique($uniqueid){
			App::import('model','DailyDiscount');
		    $this->DailyDiscount = new DailyDiscount();
			$full_url = '';	
			$DailyDiscount = $this->DailyDiscount->find('first',array('fields'=>array('DailyDiscount.advertiser_county_id'),'conditions'=>array("DailyDiscount.unique"=>$uniqueid)));
			if(isset($DailyDiscount['DailyDiscount']['advertiser_county_id'])) {
				$county = $DailyDiscount['DailyDiscount']['advertiser_county_id'];
				return FULL_BASE_URL.router::url('/',false).'state/'.$this->getStateUrl($county).'/'.$this->getCountyUrl($county).'/buyDiscount?unique='.$uniqueid;
			} else {
				return FULL_BASE_URL.router::url('/',false).'state/'.$this->getStateUrl($county).'/'.$this->getCountyUrl($county);
			}
	}	
//--------------------------------------------------------------------------------------------------------------------------------------------//
	function getMapAddress($id) {
			App::import('model','AdvertiserProfile');
			$this->AdvertiserProfile = new AdvertiserProfile();
			$adv_data=$this->AdvertiserProfile->find('first',array('conditions'=>array('AdvertiserProfile.id'=>$id)));
			////////////////////////////////////////
					// find all city	 
					 	$cityList=$this->getAllCity();
					 // find all county	 
						$countyList=$this->getAllCounty();
					// find all state
						$stateList=$this->getAllState();
					// find all country	 
						$countryList=$this->getAllCountry();
					///////////////////map code///////////////////
							$address1 = '';	

								 	if($adv_data['AdvertiserProfile']['id'])
									{
										$add = '';
										$city = '';
										$zip = '';
										$phone = '';
										$showAddress = 0;
										if($adv_data['AdvertiserProfile']['show_address']=='yes') {
											$showAddress = 1;
										 	$add[] = $adv_data['AdvertiserProfile']['address'];
											$city[] = '';
											if(isset($cityList[$adv_data['AdvertiserProfile']['city']])) {
												$city[]= $cityList[$adv_data['AdvertiserProfile']['city']];
											}
											$zip[]= $adv_data['AdvertiserProfile']['zip'];
											$phone[]= $adv_data['AdvertiserProfile']['phoneno'];
										 }										 
										 if($adv_data['AdvertiserProfile']['show_address2']=='yes') {
										 	$showAddress = 1;
											$city[] = '';
										 	$add[] = $adv_data['AdvertiserProfile']['address2'];
											if(isset($cityList[$adv_data['AdvertiserProfile']['city2']])) {
												$city[]= $cityList[$adv_data['AdvertiserProfile']['city2']];
											}
											$zip[]= $adv_data['AdvertiserProfile']['zip2'];
											$phone[]= $adv_data['AdvertiserProfile']['phoneno2'];
										 }										 
										 $county= $countyList[$adv_data['AdvertiserProfile']['county']];
										 
										 $state= $stateList[$adv_data['AdvertiserProfile']['state']];
										 
										 $country= $countryList[$adv_data['AdvertiserProfile']['country']];
										 										 
										 $name= $adv_data['AdvertiserProfile']['name'];
										 
										 $company_name= $adv_data['AdvertiserProfile']['company_name'];
										 
										 $logo= $adv_data['AdvertiserProfile']['logo'];
										 $o = 0;
										 if(is_array($add)) {
											 foreach($add as $add) {	
													$address1[$o][0]=$add.', '.$city[$o].', '.$county.', '.$state.', '.$country;
													$address1[$o][1]='<strong>'.ucwords(strtolower($company_name)).'</strong><br />'.$phone[$o].'<br />'.$add.' '.$city[$o].' '.$county.'<br />'.$state.' '.$country.' '.$zip[$o];												
												$o++;
											 }
										 } else {
										 		$address1[0][0]=$county.', '.$state.', '.$country;
												$address1[0][1]='<strong>'.ucwords(strtolower($company_name)).'</strong><br />'.$county.'<br />'.$state.' '.$country;
										 }
								 }
								 return $address1;
	}
//--------------------------------------------------------------------------------------------------------------------------------------------//
function compnyAddressForDeal($id)
	{
		$address = '';
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$advertiser = $this->AdvertiserProfile->find('first',array('conditions'=>array('AdvertiserProfile.id'=>$id)));
		
		if($advertiser['AdvertiserProfile']['show_address']=='yes') {
			$address .= $advertiser['AdvertiserProfile']['address'].', ';
			if($this->getCityName($advertiser['AdvertiserProfile']['city'])!='') {
				$address .= $this->getCityName($advertiser['AdvertiserProfile']['city']).', ';
			}	
			$address .= $this->getStateName($advertiser['AdvertiserProfile']['state']);
			if($advertiser['AdvertiserProfile']['zip']!='') {
				$address .= ', '.$advertiser['AdvertiserProfile']['zip'];
			}
		}
		if($advertiser['AdvertiserProfile']['show_address2']=='yes') {
			$address .= '<br />'.$advertiser['AdvertiserProfile']['address2'].', ';
			if($this->getCityName($advertiser['AdvertiserProfile']['city2'])!='') {
				$address .= $this->getCityName($advertiser['AdvertiserProfile']['city2']).', ';
			}	
			$address .= $this->getStateName($advertiser['AdvertiserProfile']['state']);
			if($advertiser['AdvertiserProfile']['zip2']!='') {
				$address .= ', '.$advertiser['AdvertiserProfile']['zip2'];
			}
		}
		return $address.'<br />';
	}
//--------------------------------------------------------------------------------------------------------------------------------------------//
function compnyPhoneForDeal($id)
	{
		$address = '';
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$advertiser = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.phoneno','AdvertiserProfile.phoneno2'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
		$add = '';
		if($advertiser['AdvertiserProfile']['phoneno']!='') {
			$add[] .= $advertiser['AdvertiserProfile']['phoneno'];
		}
		if($advertiser['AdvertiserProfile']['phoneno2']!='') {
			$add[] .= $advertiser['AdvertiserProfile']['phoneno2'];
		}
		$address .= implode(', ',$add);
		return $address;
	}
//--------------------------------------------------------------------------------------------------------------------------------------------//
function compnyWebForDeal($id)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$advertiser = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.website'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
		return $advertiser['AdvertiserProfile']['website'];
	}
//--------------------------------------------------------coder:keshav------------------------------------------------------------------------------------//
function splitString($string='',$charactor=10)
	{
		if(strlen($string)<=$charactor) {
			return $string;
		} else {
			$parts = explode(' ',$string);
			$newStr = '';
			foreach($parts as $parts) {
			
				if(strlen($newStr)>=((int)($charactor-3))) {
					break;
				} else if(((int)(strlen($newStr)+strlen($parts)))>((int)$charactor)){
					break;
				} else {	
					$newStr.=$parts.' ';
				}
				
				
			}
			return substr($newStr,0,-1).'...';
		}
	}
//--------------------------------------------------------------------------------------------------------------------------------------------//
function limitString($string='',$charactor=10)
	{
		if(strlen($string)<=$charactor) {
			return $string;
		} else {

			return substr($string,0,($charactor-3)).'...';
		}
	}	
//--------------------------------------------------------------------------------------------------------------------------------------------//
function getAdvertiserProfile($id)
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$advertiser = $this->AdvertiserProfile->find('first',array('fields'=>array('AdvertiserProfile.companyprofile'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
		return $advertiser['AdvertiserProfile']['companyprofile'];
	}
//--------------------------------------------------------------------------------------------------------------------------------------------//
function getAdvanceFooterForFront()
	{
		App::import('model','Setting');
		$this->Setting = new Setting();
		return $this->Setting->find('first',array('fields'=>array('Setting.home_page_footer_text','Setting.home_page_footer_image','Setting.home_page_footer_box_status'),'conditions'=>array('Setting.home_page_footer_box_status'=>1)));
	}
//--------------------------------------------------------------------------------------------------------------------------------------------//	
	function checkCity($cityname,$county_id,$state_id)
	{
		App::import('Model','City');
		$this->City=new City();
		$city=$this->City->find('first',array('fields'=>array('City.id'),'conditions'=>array('City.cityname'=>$cityname,'City.state_id'=>$state_id,'City.county_id'=>$county_id)));
		if(isset($city['City']['id'])) {
			$city_id = $city['City']['id'];
		} else {
			$save = '';
			$save['City']['cityname'] = $cityname;
			$save['City']['front_status'] = 0;
			$save['City']['state_id'] = $state_id;
			$save['City']['county_id'] = $county_id;
			$save['City']['page_url'] = $this->makeAlias($cityname);
			$this->City->save($save,false);
			$city_id = $this->City->getlastinsertid();
		}
		return $city_id;
	}
//----------------------------------------------------------------------------------------------------------------------------//	
function checkEmailWithId($email,$id) {
					App::import('model','FrontUser');
				   	$this->FrontUser=new FrontUser();
					$data = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.email'=>$email,'FrontUser.id !='=>$id,'(FrontUser.user_type="customer" OR FrontUser.user_type="parent")','FrontUser.status'=>'yes')));
					if($data) {
						return false;
					}
					return true;
	}
//----------------------------------------------------------------------------------------------------------------------------//	
function checkEmailAdvertiser($email) {
					App::import('model','AdvertiserProfile');
				   	$this->AdvertiserProfile=new AdvertiserProfile();
					$data = $this->AdvertiserProfile->find('count',array('conditions'=>array('AdvertiserProfile.email'=>$email)));
					if($data) {
						return false;
					}
					return true;
	}
//----------------------------------------------------------------------------------------------------------------------------//	
function checkUserPswd($pswd,$id) {
					App::import('model','FrontUser');
				   	$this->FrontUser=new FrontUser();
					$data = $this->FrontUser->find('count',array('conditions'=>array('FrontUser.password'=>$this->Auth->password($pswd),'FrontUser.id'=>$id)));
					if(!$data) {
						return false;
					}
					return true;
	}	
//---------------------------------------------------------------------------------------------------------------------------------//	
	function order_history($id) {
		App::import('model','Order');
		$this->Order=new Order();
		$allorder = $this->Order->find('all',array('conditions'=>array('Order.front_user_id'=>$id),'order'=>'Order.id DESC'));
		return $allorder;
	}
//---------------------------------------------------------------------------------------------------------------------------------//		
	function proof_message($order_id,$sub,$msg,$type,$to,$to_grp,$form,$b_line,$salse_id) {
		App::import('model','WorkOrder');
		$this->WorkOrder=new WorkOrder();
		$saveWorkArray = array();
		$saveWorkArray['WorkOrder']['advertiser_order_id']   	=  $order_id;
	  	$saveWorkArray['WorkOrder']['read_status']   			=  0;
	  	$saveWorkArray['WorkOrder']['subject']   				=  $sub;
	 	$saveWorkArray['WorkOrder']['message']   				=  $msg;
	  	$saveWorkArray['WorkOrder']['type']   					=  $type;
	  	$saveWorkArray['WorkOrder']['sent_to']   				=  $to;
	  	$saveWorkArray['WorkOrder']['sent_to_group']   			=  $to_grp;
	  	$saveWorkArray['WorkOrder']['from_group']   			=  $form;
	 	$saveWorkArray['WorkOrder']['bottom_line']				=  $b_line;
	  	$saveWorkArray['WorkOrder']['salseperson_id'] 			=  $salse_id;
	 	date_default_timezone_set('US/Eastern');
	  	$saveWorkArray['WorkOrder']['created_date']   			=  date(DATE_FORMAT.' h:i:s A');
	 	$saveWorkArray['WorkOrder']['created']   				=  strtotime(date(DATE_FORMAT.' h:i:s A'));
	 	$this->WorkOrder->save($saveWorkArray);
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
	function advertiserCatCombo($advertiser,$model='SavingOffer',$field='homecat',$default='') {
	
		App::import('model','AdvertiserCategory');
		$this->AdvertiserCategory=new AdvertiserCategory();
		$maincat = array();
		$string = '';
		$string .= '<select name="data['.$model.']['.$field.']" style="width:200px;">';
		
		if($default=='') {
			$string .= '<option value="" selected="selected">Select Category</option>';
		} else {
			$string .= '<option value="">Select Category</option>';
		}
		
		if($advertiser) {
			$cats = $this->AdvertiserCategory->find('all',array('conditions'=>array('AdvertiserCategory.advertiser_profile_id'=>$advertiser),'contain'=>array('CategoriesSubcategory'=>array('Category.publish')),'recursive'=>2));

			foreach($cats as $cats) {
				if($cats['CategoriesSubcategory']['Category']['publish']=='yes' && !in_array($cats['CategoriesSubcategory']['category_id'],$maincat)) {
					$maincat[] = $cats['CategoriesSubcategory']['category_id'];
				}
			}
		}
		if(!empty($maincat)) {
			foreach($maincat as $maincat) {
					if($maincat==$default) {
						$string .= '<option value="'.$maincat.'" selected="selected">'.$this->getCategoryName($maincat).'</option>';
					} else {
						$string .= '<option value="'.$maincat.'">'.$this->getCategoryName($maincat).'</option>';
					}
			}
			
		}
		
		
		$string .= '</select>';
		return $string;
	}
//---------------------------------------------------------------------------------------------------------------------------------//
		function getAllCatoptions_admin($scatname="")
		{
		App::import('model','Category');
		$this->Category = new Category(); 
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		$combo='';
		if($scatname=="")
				 {
				 	$combo.='<option value="0" selected="selected">Select Subcategory</option>';
				 }
		foreach($this->Category->query("select * from categories where publish='yes' ORDER BY `order`,`categoryname` ASC") as $category)
		   	{
                 $combo.='<optgroup label="'.strtoupper($category['categories']['categoryname']).'" >';
				 $id=$category['categories']['id'];
				 
				 $data = $this->CategoriesSubcategory->find('all',array('fields'=>array('Subcategory.categoryname','Subcategory.id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'Subcategory.publish'=>'yes'),'order'=>array('Subcategory.order,Subcategory.categoryname')));
				 
				 
				foreach($data as $subCat)
					{
					if($subCat['Subcategory']['categoryname']!='')
								{
								if($scatname==$id.'/'.$subCat['Subcategory']['id'])
								{
								$combo.='<option value="'.$id.'/'.$subCat['Subcategory']['id'].'" selected="selected"><b>'.$subCat['Subcategory']['categoryname'].'</b></option>';
								}
								else
								{
								$combo.='<option value="'.$id.'/'.$subCat['Subcategory']['id'].'">&nbsp;<b>'.$subCat['Subcategory']['categoryname'].'</b></option>';
								}
								}
					}					
				}
				$combo.='</optgroup>';
				return $combo;
		}
//---------------------------------------------------------------------------------------------------------------------------------//
	function latLong($address='') {
			$address = str_replace(' ','+',$address);
			$url = "https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=United+States";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response);
			$map = '';
			$map['lat'] = $response_a->results[0]->geometry->location->lat;
			$map['long'] = $response_a->results[0]->geometry->location->lng;
			return $map;
	}
//---------------------------------------------------------------------------------------------------------------------------------//	
function newGetCatoptions_byCountyOLD($county="",$scatname="",$catname="")
		{
		
		$combo = '';
		$subCatName = 'What?';
		if($scatname!='') {
			$subCatName = $this->getSubcategoryDetails_url($scatname);
		}
		$combo .= '
		<div class="selectBox">
          <span class="selected" id="selectedCatVal">'.$subCatName.'</span>
          <span class="selectArrow">&#9660 </span>
  <div class="selectOptions"> <span class="selectOption" value="select">'.$subCatName.'</span>';
  
  
  $combo.=' <div><span class="selectOption" value="What?" group="2" style="display:block;" onclick="fillCatValue(\'/\')">What?</span></div>';
  
		App::import('model','CountyCategory');
		$this->CountyCategory = new CountyCategory();
		App::import('model','Subcategory');
		$this->Subcategory = new Subcategory();
		App::import('model','CountiesCategoriesSubcategory');
		$this->CountiesCategoriesSubcategory = new CountiesCategoriesSubcategory();
		
		
		$data = $this->CountyCategory->find('all',array('fields'=>array('DISTINCT Category.id','Category.page_url','Category.categoryname'),'conditions'=>array('CountyCategory.county_id'=>$county,'Category.publish'=>'yes'),'order'=>array('Category.order,Category.categoryname')));		
		foreach($data as $category)
		   	{
				$showSubCat = '';
				if($catname==$category['Category']['page_url']) {
					$showSubCat = ' style="display:block;"';
				}
                $combo.='<div><span class="selectOptionGroup" value="2">&#187; '.$category['Category']['categoryname'].'</span>';
				$id=$category['Category']['id'];
				
				$sucats = array(0);
				$subdata = $this->CountiesCategoriesSubcategory->find('all',array('fields'=>array('DISTINCT CategoriesSubcategory.subcategory_id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'CountiesCategoriesSubcategory.county_id'=>$county)));
				foreach($subdata as $subdata) {
					$sucats[] = $subdata['CategoriesSubcategory']['subcategory_id'];
				}
				$subCat = $this->Subcategory->query("select * from subcategories where publish='yes' and id IN(".implode(',',$sucats).") ORDER BY `categoryname`,`categoryname` ASC");
				foreach($subCat as $subCat)
					{
					if($subCat['subcategories']['categoryname']!='')
								{
								$combo.='<span class="selectOption" value="'.$subCat['subcategories']['categoryname'].'" group="2" onclick="fillCatValue(\''.$category['Category']['page_url'].'/'.$subCat['subcategories']['page_url'].'\')"'.$showSubCat.'>'.$subCat['subcategories']['categoryname'].'</span>';
								}
					}
					$combo.='</div>';
				}
				$combo.='</div></div>';
				return $combo;
		}
//---------------------------------------------------------------------------------------------------------------------------------//	
function newGetCatoptions_byCounty($county="",$scatname="",$catname="")
		{
		
		$combo = '';
		$subCatName = 'What?';
		if($scatname!='') {
			$subCatName = $this->getSubcategoryDetails_url($scatname);
		}
		
		
		$combo .= '<li class="closeMenu"><a href="javascript:void(0)" onClick="fillCatValue(\'What?\',\'\')">What?</a></li>';
  
		App::import('model','CountyCategory');
		$this->CountyCategory = new CountyCategory();
		App::import('model','Subcategory');
		$this->Subcategory = new Subcategory();
		App::import('model','CountiesCategoriesSubcategory');
		$this->CountiesCategoriesSubcategory = new CountiesCategoriesSubcategory();
		
		
		$data = $this->CountyCategory->find('all',array('fields'=>array('DISTINCT Category.id','Category.page_url','Category.categoryname'),'conditions'=>array('CountyCategory.county_id'=>$county,'Category.publish'=>'yes'),'order'=>array('Category.order,Category.categoryname')));		
		foreach($data as $category)
		   	{
				$combo.='<li> <a href="javascript:void(0)">'.$category['Category']['categoryname'].'</a>';
				$id=$category['Category']['id'];
				
				$sucats = array(0);
				$subdata = $this->CountiesCategoriesSubcategory->find('all',array('fields'=>array('DISTINCT CategoriesSubcategory.subcategory_id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'CountiesCategoriesSubcategory.county_id'=>$county)));
				foreach($subdata as $subdata) {
					$sucats[] = $subdata['CategoriesSubcategory']['subcategory_id'];
				}
				$subCat = $this->Subcategory->query("select * from subcategories where publish='yes' and id IN(".implode(',',$sucats).") ORDER BY `categoryname`,`categoryname` ASC");
				 
				 $remainingUL='';
				 if(!empty($subCat))
				 {
				 	$combo.='<ul class="selectOptions">';
					$remainingUL='</ul>';
				 }
				foreach($subCat as $subCat)
					{
					if($subCat['subcategories']['categoryname']!='')
								{
								
								$combo.='<li><a href="javascript:void(0)" onclick="fillCatValue(\''.$subCat['subcategories']['categoryname'].'\',\''.$category['Category']['page_url'].'/'.$subCat['subcategories']['page_url'].'\')">'.$subCat['subcategories']['categoryname'].'</a></li>';
								}
					}
					$combo.=$remainingUL.'</li>';
				}
				return $combo;
		}
//---------------------------------------------------------------------------------------------------------------------------------//			
function allCatWIthActiveSavingOffer($county) {
		App::import('model','Category');
		$this->Category = new Category();
		$cat = $this->Category->query("SELECT COUNT( categories.id ) as Total , categories.id, categories.categoryname, categories.page_url , saving_offers.homecat
											FROM categories
										RIGHT JOIN county_categories ON categories.id = county_categories.category_id
										AND county_categories.county_id =".$county."
										LEFT JOIN saving_offers ON categories.id = saving_offers.homecat
											AND saving_offers.current_saving_offer = 1 
											AND  saving_offers.status = 'yes' 
											AND  saving_offers.advertiser_status = 'yes' 
											AND  saving_offers.show_at_home = 1
											AND  saving_offers.advertiser_county_id = ".$county."
											AND  FROM_UNIXTIME(`offer_start_date`) < CURDATE() 
											AND  FROM_UNIXTIME(`offer_expiry_date`) > CURDATE() 
										WHERE categories.publish='yes' 
										GROUP BY categories.id
										ORDER BY categories.order");
		return $cat;
	}
//---------------------------------------------------------------------------------------------------------------------------------//			
function getCurrentOffer($county=0,$cat=0,$offset=0) {
		App::import('model','SavingOffer');
		$this->SavingOffer = new SavingOffer();
		$Offer = $this->SavingOffer->find('first',array('fields'=>array('SavingOffer.advertiser_profile_id','SavingOffer.title','SavingOffer.off_unit','SavingOffer.off_text','SavingOffer.off','AdvertiserProfile.city','AdvertiserProfile.page_url','AdvertiserProfile.main_image','AdvertiserProfile.main_image_type','AdvertiserProfile.logo','AdvertiserProfile.company_name'),'conditions'=>array('AdvertiserProfile.publish="yes" AND SavingOffer.current_saving_offer=1 AND SavingOffer.status = "yes" AND  SavingOffer.show_at_home = 1 AND  FROM_UNIXTIME(`offer_start_date`) < CURDATE() AND FROM_UNIXTIME(`offer_expiry_date`) > CURDATE() AND  homecat ='.$cat.' AND AdvertiserProfile.county='.$county),'order' =>'SavingOffer.id ASC','limit' => 1,'offset'=>$offset));
		return $Offer;
	}
//--------------------------------------------------get advertiser offer image ----------------------------------------------------------------//			  
	function getAdvertiserOfferImagebyId($id) {
			App::import('model','AdvertiserProfile');
		    $this->AdvertiserProfile = new AdvertiserProfile();			
			$company = $this->AdvertiserProfile->find('first',array('fields'=>('AdvertiserProfile.offer_image'),'conditions'=>array('AdvertiserProfile.id'=>$id)));
			return $company['AdvertiserProfile']['offer_image'];	
	}
//--------------------------------------------------get advertiser offer image ----------------------------------------------------------------//		
	function getAllCompanyUrl()
	{
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		$page_details=$this->AdvertiserProfile->find('all',array('fields'=>array('AdvertiserProfile.page_url'),'conditions'=>array('AdvertiserProfile.publish'=>'yes'),'recursive'=>-1));
		return $page_details;
	}
//--------------------------------------------------get advertiser offer image ----------------------------------------------------------------//		
	function updateNewTables()
	{
		set_time_limit(0);
		App::import('model','Subcategory');
		$this->Subcategory = new Subcategory();
		
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		
		App::import('model','CountiesCategoriesSubcategory');
		$this->CountiesCategoriesSubcategory = new CountiesCategoriesSubcategory();
		
		$subcat = $this->Subcategory->find('all');
		
		foreach($subcat as $subcat) {
			$cats = '';
			$county = '';
			$cats = array_values(array_filter(explode(',',$subcat['Subcategory']['category_id'])));
			if(is_array($cats) && !empty($cats)) {
				foreach($cats as $cats) {
					$save = '';
					$save['CategoriesSubcategory']['id'] = '';
					$save['CategoriesSubcategory']['category_id'] = $cats;
					$save['CategoriesSubcategory']['subcategory_id'] = $subcat['Subcategory']['id'];
					$this->CategoriesSubcategory->save($save,false);
					$county = array_values(array_filter(explode(',',$subcat['Subcategory']['county'])));
					if(is_array($county) && !empty($county)) {
						$lastid = $this->CategoriesSubcategory->getlastinsertid();
						foreach($county as $county) {
							$save2 = '';
							$save2['CountiesCategoriesSubcategory']['id'] = '';
							$save2['CountiesCategoriesSubcategory']['county_id'] = $county;
							$save2['CountiesCategoriesSubcategory']['categories_subcategory_id'] = $lastid;
							$this->CountiesCategoriesSubcategory->save($save2,false);
						}	
					}
				}
			}
		}
	}
//--------------------------------------------------get advertiser offer image ----------------------------------------------------------------//		
	function updateNewTable()
	{
		set_time_limit(0);
		App::import('model','Category');
		$this->Category = new Category();
		
		App::import('model','CountyCategory');
		$this->CountyCategory = new CountyCategory();
		
		$cat = $this->Category->find('all');
		
		foreach($cat as $cat) {
					$county = '';
					$county = array_values(array_filter(explode(',',$cat['Category']['county'])));
					if(is_array($county) && !empty($county)) {
						foreach($county as $county) {
							$save2 = '';
							$save2['CountyCategory']['id'] = '';
							$save2['CountyCategory']['county_id'] = $county;
							$save2['CountyCategory']['category_id'] = $cat['Category']['id'];
							$this->CountyCategory->save($save2,false);
						}	
					}
				}
			}
//--------------------------------------------------get advertiser offer image ----------------------------------------------------------------//		
	function updateAdvertiserCats()
	{
		set_time_limit(0);
		App::import('model','AdvertiserProfile');
		$this->AdvertiserProfile = new AdvertiserProfile();
		
		App::import('model','AdvertiserCategory');
		$this->AdvertiserCategory = new AdvertiserCategory();
		
		
		$data = $this->AdvertiserProfile->find('all',array('fields'=>array('AdvertiserProfile.id','AdvertiserProfile.cat_subcat')));
		
		foreach($data as $data) {
			
			$advertiser_id_is = $data['AdvertiserProfile']['id'];
			$cats = array_values(array_filter(explode('|',$data['AdvertiserProfile']['cat_subcat'])));
			
			
			foreach($cats as $pair) {
				$break = explode('-',$pair);
				$catSubcat = $this->returnCatSubcatId($break[0],$break[1]);
				if($catSubcat) {
					$save = '';
					$save['AdvertiserCategory']['id'] = '';
					$save['AdvertiserCategory']['advertiser_profile_id'] = $advertiser_id_is;
					$save['AdvertiserCategory']['categories_subcategory_id'] = $catSubcat;
					$this->AdvertiserCategory->save($save,false);
				}
			}
		}	
	}			
//-------------------------------------------------- get advertiser offer image ----------------------------------------------------------------//		
	function returnCatSubcatId($cat=0,$subcat=0)
	{
		App::import('model','CategoriesSubcategory');
		$this->CategoriesSubcategory = new CategoriesSubcategory();
		
		$id = 0;
		$cat = $this->CategoriesSubcategory->find('first',array('fields'=>'CategoriesSubcategory.id','conditions'=>array('CategoriesSubcategory.category_id'=>$cat,'CategoriesSubcategory.subcategory_id'=>$subcat)));
		if(isset($cat['CategoriesSubcategory']['id'])) {
			$id = $cat['CategoriesSubcategory']['id'];
		}
		return $id;
	}
//-------------------------------------------------- get advertiser offer image ----------------------------------------------------------------//		
	function advertiserByCat($cat=0)
	{
		App::import('model','AdvertiserCategory');
		$this->AdvertiserCategory = new AdvertiserCategory();
		$advertiser = array(0);
		$data = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT AdvertiserCategory.advertiser_profile_id','conditions'=>array('CategoriesSubcategory.category_id'=>$cat)));
		if(!empty($data)) {
			foreach($data as $data) {
				$advertiser[] = $data['AdvertiserCategory']['advertiser_profile_id'];
			}
		}
		return $advertiser;
	}
//-------------------------------------------------- get advertiser offer image ----------------------------------------------------------------//		
	function advertiserByCatSubcat($cat=0,$subcat=0)
	{
		App::import('model','AdvertiserCategory');
		$this->AdvertiserCategory = new AdvertiserCategory();
		$advertiser = array(0);
		$data = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT AdvertiserCategory.advertiser_profile_id','conditions'=>array('CategoriesSubcategory.category_id'=>$cat,'CategoriesSubcategory.subcategory_id'=>$subcat)));
		if(!empty($data)) {
			foreach($data as $data) {
				$advertiser[] = $data['AdvertiserCategory']['advertiser_profile_id'];
			}
		}
		return $advertiser;
	}
//-------------------------------------------------- get advertiser offer image ----------------------------------------------------------------//		
	function allCatByAdvertiser($advertiser=0)
	{
		App::import('model','AdvertiserCategory');
		$this->AdvertiserCategory = new AdvertiserCategory();
		$cats = array(0);
		$data = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT CategoriesSubcategory.category_id','conditions'=>array('AdvertiserCategory.advertiser_profile_id'=>$advertiser)));
		if(!empty($data)) {
			foreach($data as $data) {
				$cats[] = $data['CategoriesSubcategory']['category_id'];
			}
		}
		return $cats;
	}
//-------------------------------------------------- get advertiser offer image ----------------------------------------------------------------//		
	function allSubCatByAdvertiser($advertiser=0)
	{
		App::import('model','AdvertiserCategory');
		$this->AdvertiserCategory = new AdvertiserCategory();
		$subcats = array(0);
		$data = $this->AdvertiserCategory->find('all',array('fields'=>'DISTINCT CategoriesSubcategory.subcategory_id','conditions'=>array('AdvertiserCategory.advertiser_profile_id'=>$advertiser)));
		if(!empty($data)) {
			foreach($data as $data) {
				$subcats[] = $data['CategoriesSubcategory']['subcategory_id'];
			}
		}
		return $subcats;
	}		
//-------------------------------------------------- get advertiser offer image ----------------------------------------------------------------//		
	function allCatSubcatPairByAdvertiser($advertiser=0)
	{
		App::import('model','AdvertiserCategory');
		$this->AdvertiserCategory = new AdvertiserCategory();
		$data = $this->AdvertiserCategory->find('first',array('fields'=>array('CategoriesSubcategory.category_id','CategoriesSubcategory.subcategory_id'),'conditions'=>array('AdvertiserCategory.advertiser_profile_id'=>$advertiser)));
		return $data;
	}
//------------------------------------------------mobile search bar category combo-----------------------------------------------//
function newGetCatoptions_byCountyMobile($county="",$scatname="")
		{
		App::import('model','CountyCategory');
		$this->CountyCategory = new CountyCategory();
		App::import('model','Subcategory');
		$this->Subcategory = new Subcategory();
		App::import('model','CountiesCategoriesSubcategory');
		$this->CountiesCategoriesSubcategory = new CountiesCategoriesSubcategory();
		$combo='';
		if($scatname=="")
				 {
				 $combo.='<option value="0" selected="selected">What?</option>';
				 }else{
				 $combo.='<option value="0">What?</option>';
				 }
$data = $this->CountyCategory->find('all',array('fields'=>array('DISTINCT Category.id','Category.page_url','Category.categoryname'),'conditions'=>array('CountyCategory.county_id'=>$county,'Category.publish'=>'yes'),'order'=>array('Category.order,Category.categoryname')));		
		foreach($data as $category)
		   	{
				$combo.='<optgroup label="'.strtoupper($category['Category']['categoryname']).'" >';
				$id=$category['Category']['id'];
				
				$sucats = array(0);
				$subdata = $this->CountiesCategoriesSubcategory->find('all',array('fields'=>array('DISTINCT CategoriesSubcategory.subcategory_id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$id,'CountiesCategoriesSubcategory.county_id'=>$county)));
				foreach($subdata as $subdata) {
					$sucats[] = $subdata['CategoriesSubcategory']['subcategory_id'];
				}
				$subCat = $this->Subcategory->query("select * from subcategories where publish='yes' and id IN(".implode(',',$sucats).") ORDER BY `categoryname`,`categoryname` ASC");
				foreach($subCat as $subCat)
					{

								if($subCat['subcategories']['categoryname']!='')
								{	
									if(strtolower($scatname)==strtolower($subCat['subcategories']['page_url']))
									{
										$combo.='<option value="'.$category['Category']['page_url'].'/'.$subCat['subcategories']['page_url'].'" selected="selected"><b>'.$subCat['subcategories']['categoryname'].'</b></option>';
									}
									else
									{
										$combo.='<option value="'.$category['Category']['page_url'].'/'.$subCat['subcategories']['page_url'].'">&nbsp;<b>'.$subCat['subcategories']['categoryname'].'</b></option>';
									}
								}			
					}
				
				
				$combo.='</optgroup>';
			}


				return $combo;
		}
}
?>