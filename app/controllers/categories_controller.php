<?php
   class CategoriesController extends AppController { 
	    var $name    = 'Categories';
	    var $helpers = array('Html', 'Form','User', 'Javascript','Text', 'Image','Paginator','Ajax');  
	    var $layout  = 'admin';
		//var $hasMany = 'Subcategory';
		//variable for admin layout
	    var $components = array('Auth','common','Cookie','Session','RequestHandler'); 
		//component to check authentication . this component file is exists in app/controllers/components

	   // index page of category for listing
	 
	   function index(){
	   
	          //variable for display number of state name per page
			  	
				$this->set('countyList',$this->common->getAllCounty()); //get All counties
				
	            $condition='';
				
				if(isset($this->params['named']['message']))
		           {
			          if($this->params['named']['message']=='success')
			          {
				        $this->set('success','success');
			          }else{
			            $this->set('error','error');
			          }
		          }
	            $this->set('search_text', 'category name');
				$this->set('county', '');
				
			    $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'Category.modified' => 'desc' ));
				
			    if((!empty($this->data['categories']['search_text'] ))&&($this->data['categories']['search_text']!="category name"))  {
                           $this->set('search_text', $this->data['categories']['search_text']); 
				           $condition[] =   array('Category.categoryname LIKE' => '%' . $this->data['categories']['search_text'] . '%');
						   
		        }
				if((!empty($this->data['categories']['county'] ))&&($this->data['categories']['county']!=""))  {
							$this->loadModel('CountyCategory');
							$data = $this->CountyCategory->find('all',array('fields'=>'category_id','conditions'=>array('county_id'=>$this->data['categories']['county'])));
							$cat = '';
							if(!empty($data)) {
								foreach($data as $data) {
									$cat[] = $data['CountyCategory']['category_id'];
								}
							}
							if(is_array($cat)) {
								$cat_ids = implode(',',array_values(array_filter($cat)));
							} else {
								$cat_ids = 0;
							}

                           $this->set('county', $this->data['categories']['county']); 
				           $condition[] =   array('Category.id IN ('.$cat_ids.')');
						   
		        }
	           	if((isset($this->params['named']['search_text']))&&($this->params['named']['search_text']!="category name")){
                          $this->set('search_text', $this->params['named']['search_text']);
				          $condition[] =   array('Category.categoryname LIKE' => '%' . $this->params['named']['search_text'] . '%');
						  
		       	}
				if((isset($this->params['named']['county']))&&($this->params['named']['county']!="")){
                          $this->set('county', $this->params['named']['county']);
						  $condition[] =   array('Category.county LIKE ' => '%,'.$this->params['named']['county'].',%');
						  
		        }
			  $data = $this->paginate('Category', $condition);
			  //pr($data);
		      $this->set('categories', $data);
	   }
	  
	  
/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocompleteCategory($string='') {

			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			 /*App::import('model', 'County');
			$this->County = new County;*/
			$name = $this->Category->query("SELECT Category.categoryname FROM categories AS Category WHERE Category.categoryname LIKE '$string%'");
			foreach($name as $name) {
				$arr[] = $name['Category']['categoryname'];
			}
			echo json_encode($arr);
			}
	}	

/*------------------------------------------------------------------------------------------------------------------------*/ 	  
	  
	  // adding new state in database
	  
	    function addNewCategory(){
				$this->set('countyList',$this->common->getAllCounty()); //get All counties	
	              if(isset($this->data)){
	    	               $this->Category->set($this->data['categories']);
				   
			               if (empty($this->data)){
                          		   $this->data = $this->Category->find(array('Category.id' => $id));
                             }
			               if($this->data['categories']!=''){

					                 if ($this->Category->validates()) {
									      //making data array so we can pass in save mathod
									      $saveArray 							 = array();
									      $saveArray['Category']['categoryname'] =  $this->data['categories']['categoryname'];
										  $saveArray['Category']['publish']      =  $this->data['categories']['publish'];
										  $saveArray['Category']['county'] = '';
										  $saveArray['Category']['page_url'] =  $this->common->makeAlias(trim($this->data['categories']['categoryname']));	
										  if($this->data['categories']['order'] != ""){
										      $saveArray['Category']['order']    =  $this->data['categories']['order'];
										  } else {
										       $findOrderQuery = $this->Category->query("SELECT MAX(categories.order) as orderMax FROM `categories`");
											   $maxOrder  =  $findOrderQuery[0][0];
											   $saveArray['Category']['order'] = (int)$maxOrder['orderMax'] + 1;
											   $this->data['categories']['order'] = $saveArray['Category']['order'];
										  }
									      $this->Category->save($saveArray,false);
										  if(!empty($this->data['categories']['county'])) {
										  $id = $this->Category->getlastinsertid();
										  $this->loadModel('CountyCategory');
										  	foreach($this->data['categories']['county'] as $county) {
												$save = '';
												$save['CountyCategory']['id'] = '';
												$save['CountyCategory']['county_id'] = $county;
												$save['CountyCategory']['category_id'] = $id;
												$this->CountyCategory->save($save,false);
											}
										 }
									      $this->Session->setFlash('Your data has been submitted successfully.');
									      $this->redirect(array('action' => "index"  , 'message'=>'success'));
						             } else {
									      /*setting error message if validation fails*/
									       $errors = $this->Category->invalidFields();
									       $this->Session->setFlash(implode('<br>', $errors));
									       //$this->redirect(array('action' => "userGroup", 'message'=>'error'));
						             }
				            }
	              }
        
	     }
		 
		 
		 // show data in edit state form
	   function categoryEditDetail($id=null){
	         $this->set('Category',$this->Category->categoryEditDetail($id));
			 $this->set('countyList',$this->common->getAllCounty()); //get All counties
	    }
		
		//edit state data
	   function categoryEdit($id=null){
	 // pr($this->data);exit;
	         $this->Category->set($this->data['categories']);	

			 if ($this->Category->validates()) {
                            
							//making data array so we can pass in save mathod
							
							//$countyString=(!empty($this->data['categories']['county']))?implode(',',$this->data['categories']['county']):'';
							
							$saveArray = array();
							$saveArray['Category']['county']      ='';
							
							$categoryId 						   = $this->data['categories']['id'];
							$saveArray['Category']['id']           = $categoryId;
			                $categoryDetailArr 					   = $this->Category->find("id = $categoryId");
							$saveArray['Category']['categoryname'] = $this->data['categories']['categoryname'];
							//$saveArray['Category']['county'] =  ','.$countyString.',';
							$saveArray['Category']['page_url'] =  $this->common->makeAlias(trim($saveArray['Category']['categoryname']));
							
							if($this->data['categories']['order']!=""){
							   $saveArray['Category']['order']     = $this->data['categories']['order'];
							} else {
							   $saveArray['Category']['order']     = $categoryDetailArr['Category']['order'];
							   $this->data['categories']['order']  = $saveArray['Category']['order'];
							}
							
							$saveArray['Category']['publish']      = $this->data['categories']['publish'];
							$this->Category->save($saveArray);
							
							$this->loadModel('CountyCategory');
							$this->CountyCategory->deleteAll(array('CountyCategory.category_id'=>$categoryId));
							foreach($this->data['categories']['county'] as $county) {
									$save = '';
									$save['CountyCategory']['id'] = '';
									$save['CountyCategory']['county_id'] = $county;
									$save['CountyCategory']['category_id'] = $categoryId;
									$this->CountyCategory->save($save,false);
								}
										 
							$this->Session->setFlash('Your data has been updated successfully.');  
							$this->redirect(array('action' => "index" , 'message'=>'success'));

			  } else {  

							/*setting error message if validation fails*/
							$errors = $this->Category->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "categoryEditDetail/".$this->data['categories']['id'])); 
							
			  }
			  
	    }
	  
	  //delete category data in database
	   function categoryDelete($id) {
	    
		 
			  $result       		   = $this->Category->query("SELECT * FROM subcategories where category_id like "."'%".",".$id.","."%'");
			  
			  $resultLink   		   = $this->Category->query("SELECT * FROM links where category_id like "."'%".",".$id.","."%'");
			  
			  $resultAdvertiserProfile = $this->Category->query("SELECT * FROM advertiser_profiles where category like "."'%".",".$id.","."%'");
			  
			  $resultBanner            = $this->Category->query("SELECT * FROM banners where category_id like "."'%".",".$id.","."%'");
			  
			  $resultTopTen            = $this->Category->query("SELECT * FROM top_ten_businesses where category like "."'%".",".$id.","."%'");

			  if((!empty($result))||(!empty($resultLink))||(!empty($resultAdvertiserProfile))||(!empty($resultTopTen))||(!empty($resultBanner))){
			     
				  if(!empty($result))
			      $delete['result'] = 'This category contain sub-categories.You have to delete first sub-categories of this category.';
				  
				  if(!empty($resultLink))
			      $delete['resultLink'] = 'This category contain Link Manager.You have to delete first Link Manager of this category.';
				  
				  if(!empty($resultAdvertiserProfile))
			      $delete['resultAdvertiserProfile'] = 'This category contain Advertiser Profile.You have to delete first Advertiser Profile of this category.';
				  
				  if(!empty($resultTopTen))
			      $delete['resultTopTen'] = 'This category contain Top Ten Bussiness Detail.You have to delete first Ten Bussiness Detail of this category.';
				  
				  if(!empty($resultBanner))
			      $delete['resultBanner'] = 'This category contain Bannner.You have to delete first Banner Detail of this category.';
				  
				  $this->Session->setFlash(implode('<br>', $delete));
				  
				  $this->redirect(array('action' => "index" , 'message'=>'error'));
				   
			  } else {
								
			     $this->Category->delete($id);
				 
			     $this->Session->setFlash('The Parent Category with id: '.$id.' has been deleted.');
			
			     $this->redirect(array('action'=>'index' , 'message'=>'success'));
			  
			  }
			
	   }
	   
	   
	   // index page of category for listing
	 
	  function subcategory() {
	  
			 $this->set('countyList',$this->common->getAllCounty()); //get All counties
			 
			 $condition='';
			 
			 if(isset($this->params['named']['message']))
			 {
			          if($this->params['named']['message']=='success') {
				        $this->set('success','success');
			          } else {
			            $this->set('error','error');
			          }
		     }
			 
			 $this->set('Categorys', $this->common->getFullCategory());
			 
			 App::import('model','Subcategory'); // importing Article (pages) model
			 
		     $this->Subcategory = new Subcategory();
			 
			 $this->set('search_text', 'sub category name'); 
			 
			 $this->set('published', '');
			 
			 $this->set('categoryParentName','');
			 
			 $this->set('categorySearch', 'Category');
			 $this->set('county', '');
			 
			 $this->paginate = array(

				'limit' => PER_PAGE_RECORD,

				'order' => array('Subcategory.modified' => 'DESC'),
				
				'recursive' => 2,
				
				'contain' => array('CategoriesSubcategory'=>array('Category.categoryname','Category.publish'))

			  );
			  
			 #setting diff condition in paginate function according to search criteria
			 if(isset($this->data)) {
			
			if((!empty($this->data['categories']['search_text']) && $this->data['categories']['search_text']!='sub category name')){
				 $this->set('search_text', $this->data['categories']['search_text']); 
				 $condition[] =   array('Subcategory.categoryname LIKE' => '%' . $this->data['categories']['search_text'] . '%');
			 }
			 
			 if((!empty($this->data['categories']['county'] ))&&($this->data['categories']['county']!=""))  {
                 $this->set('county', $this->data['categories']['county']); 
				  $this->loadModel('CountiesCategoriesSubcategory');
					$data = $this->CountiesCategoriesSubcategory->find('all',array('fields'=>array('DISTINCT CategoriesSubcategory.subcategory_id'),'conditions'=>array('CountiesCategoriesSubcategory.county_id'=>$this->data['categories']['county'])));
					$cat = '';
					if(!empty($data)) {
						foreach($data as $data) {
							$cat[] = $data['CategoriesSubcategory']['subcategory_id'];
						}
					}
					if(is_array($cat)) {
						$cat_ids = implode(',',array_values(array_filter($cat)));
					} else {
						$cat_ids = 0;
					}
				   $condition[] =   array('Subcategory.id IN ('.$cat_ids.')');
		     }
			
			if(isset($this->data['categories']['categorySearch']) && $this->data['categories']['categorySearch'] != ""){
				 $this->set('categorySearch', $this->data['categories']['categorySearch']);
				 $this->loadModel('CategoriesSubcategory');
					$data = $this->CategoriesSubcategory->find('all',array('fields'=>array('CategoriesSubcategory.subcategory_id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$this->data['categories']['categorySearch'])));
					$cat = '';
					if(!empty($data)) {
						foreach($data as $data) {
							$cat[] = $data['CategoriesSubcategory']['subcategory_id'];
						}
					}
					if(is_array($cat)) {
						$cat_ids = implode(',',array_values(array_filter($cat)));
					} else {
						$cat_ids = 0;
					}
				   $condition[] =   array('Subcategory.id IN ('.$cat_ids.')');
			 }
			 } else if(!empty($this->params['named'])) {
			
			if((!empty($this->params['named']['search_text']) && $this->params['named']['search_text']!='sub category name')){
				 $this->set('search_text', $this->params['named']['search_text']); 
				 $condition[] =   array('Subcategory.categoryname LIKE' => '%' . $this->params['named']['search_text'] . '%');
			 }
			 
			 if((isset($this->params['named']['county']))&&($this->params['named']['county']!="")){
                 $this->set('county', $this->params['named']['county']); 
				  $this->loadModel('CountiesCategoriesSubcategory');
					$data = $this->CountiesCategoriesSubcategory->find('all',array('fields'=>array('DISTINCT CategoriesSubcategory.subcategory_id'),'conditions'=>array('CountiesCategoriesSubcategory.county_id'=>$this->params['named']['county'])));
					$cat = '';
					if(!empty($data)) {
						foreach($data as $data) {
							$cat[] = $data['CategoriesSubcategory']['subcategory_id'];
						}
					}
					if(is_array($cat)) {
						$cat_ids = implode(',',array_values(array_filter($cat)));
					} else {
						$cat_ids = 0;
					}
				   $condition[] =   array('Subcategory.id IN ('.$cat_ids.')');
		     }
			
			if(isset($this->params['named']['categorySearch']) && $this->params['named']['categorySearch'] != ""){
				 $this->set('categorySearch', $this->params['named']['categorySearch']);
				 $this->loadModel('CategoriesSubcategory');
					$data = $this->CategoriesSubcategory->find('all',array('fields'=>array('CategoriesSubcategory.subcategory_id'),'conditions'=>array('CategoriesSubcategory.category_id'=>$this->params['named']['categorySearch'])));
					$cat = '';
					if(!empty($data)) {
						foreach($data as $data) {
							$cat[] = $data['CategoriesSubcategory']['subcategory_id'];
						}
					}
					if(is_array($cat)) {
						$cat_ids = implode(',',array_values(array_filter($cat)));
					} else {
						$cat_ids = 0;
					}
				   $condition[] =   array('Subcategory.id IN ('.$cat_ids.')');
			 }
			 }
			 //----------------------------------At the time of sorting Filteration on basis of these fields------------------------------
			 
			 $data = $this->paginate('Subcategory', $condition);
			// pr($data);
		     $this->set('subcategorys', $data); 
	
	}
	
	/*------------------------------function to Add New Subcategory------------------------------------*/
	
	function addNewSubcategory(){
	         $this->set('Categorys', $this->common->getAllCategory()); //get All categories
			 $this->set('countyList',$this->common->getAllCounty()); //get All counties
			 
			 App::import('model','Subcategory');
		     $this->Subcategory = new Subcategory();
			
			 if($this->data){
			 
			 
				 $this->Subcategory->set($this->data['categories']);
				 //pr($this->data);
				 if($this->data['categories']!=''){
					 if ($this->Subcategory->validates()) {
					        if($this->data['categories']['category_id']!=""){
									//making data array so we can pass in save mathod
									$saveArray = array();
									$saveArray['Subcategory']['categoryname']       =  $this->data['categories']['categoryname'];
									$saveArray['Subcategory']['publish']    	    =  $this->data['categories']['publish'];
									$saveArray['Subcategory']['category_id']        =  '';
									$saveArray['Subcategory']['county']				=  '';
									$saveArray['Subcategory']['page_url'] =  $this->common->makeAlias(trim($this->data['categories']['categoryname']));
									
									$saveArray['Subcategory']['meta_keyword']    	=  $this->data['categories']['meta_keyword'];
									
									if(trim($this->data['categories']['meta_title'])!="")
									{
									 	$saveArray['Subcategory']['meta_title'] = $this->data['categories']['meta_title'];
									} else {
									 	$saveArray['Subcategory']['meta_title'] = $this->data['categories']['categoryname'];
									}

									$saveArray['Subcategory']['meta_description']   =  $this->data['categories']['meta_description'];
									if($this->Subcategory->save($saveArray,false))
									{
										$subcat = $this->Subcategory->getlastinsertid();
										$this->loadModel('CategoriesSubcategory');
										$this->loadModel('CountiesCategoriesSubcategory');
										
										foreach($this->data['categories']['category_id'] as $cats) {
											$save = '';
											$save['CategoriesSubcategory']['id'] = '';
											$save['CategoriesSubcategory']['category_id'] = $cats;
											$save['CategoriesSubcategory']['subcategory_id'] = $subcat;
											$this->CategoriesSubcategory->save($save,false);
												$county = $this->data['categories']['county'];
												$lastid = $this->CategoriesSubcategory->getlastinsertid();
												foreach($county as $county) {
													$save2 = '';
													$save2['CountiesCategoriesSubcategory']['id'] = '';
													$save2['CountiesCategoriesSubcategory']['county_id'] = $county;
													$save2['CountiesCategoriesSubcategory']['categories_subcategory_id'] = $lastid;
													$this->CountiesCategoriesSubcategory->save($save2,false);
												}	
										}
										$this->Session->setFlash('Your data has been submitted successfully.');
										$this->redirect(array('action' => "subcategory" , 'message'=>'success'));
									} else {
										$this->Session->setFlash('Data Save Problem, Please try later.');
										$this->redirect(array('action' => "subcategory" , 'message'=>'error'));
									}
						     } else {
							        /*setting error message if validation fails*/
							        $errors = "Please select the parent category of subcategory";
									$this->Session->setFlash($errors);
							 }
						}else{
									/*setting error message if validation fails*/
									$errors = $this->Subcategory->invalidFields();
									$this->Session->setFlash(implode('<br>', $errors));
						}
				 }
		    }
	}
	
	// show data in edit state form
	   function subcategoryEditDetail($id=null){
	   
	         $this->set('Categorys', $this->common->getAllCategory()); //get All parent categories
	         $this->set('countyList',$this->common->getAllCounty()); //get All counties
		     
			 App::import('model','Subcategory'); // importing Article model
			 $this->Subcategory = new Subcategory();
	
	         $this->set('Subcategory',$this->Subcategory->subcategoryEditDetail($id));
	    }
		
		//edit county data
	   function subcategoryEdit($id=null){
	         
			 $this->set('Categorys', $this->common->getAllCategory()); //get All parent categories
			 $this->set('countyList',$this->common->getAllCounty()); //get All counties
			 
	         App::import('model','Subcategory'); // importing Subcategory model
			 $this->Subcategory = new Subcategory();
	  
	         $this->Subcategory->set($this->data['categories']);

			 if ($this->Subcategory->validates()) {
			 	 if($this->data['categories']['category_id']!=""){
			              //making data array so we can pass in save mathod
						    $saveArray = array();
							$subcategoryId 						       =  $this->data['categories']['id'];
							$saveArray['Subcategory']['id']            =  $subcategoryId;
			                $categoryDetailArr 					       =  $this->Subcategory->find("id = $subcategoryId");
							$saveArray['Subcategory']['categoryname']  =  $this->data['categories']['categoryname'];
							$saveArray['Subcategory']['category_id']   =  '';
							$saveArray['Subcategory']['county']        =  '';
							$saveArray['Subcategory']['page_url'] 	   =  $this->common->makeAlias(trim($saveArray['Subcategory']['categoryname']));
							$saveArray['Category']['publish']          = $this->data['categories']['publish'];
									
									$saveArray['Subcategory']['meta_keyword']    	=  $this->data['categories']['meta_keyword'];
									if(trim($this->data['categories']['meta_title'])!="")
									{
									 	$saveArray['Subcategory']['meta_title'] = $this->data['categories']['meta_title'];	
									}else{
									 	$saveArray['Subcategory']['meta_title'] = $this->data['categories']['categoryname'];	
									}

									$saveArray['Subcategory']['meta_description']   =  $this->data['categories']['meta_description'];
									
							$this->Subcategory->save($saveArray,false);
								
										$subcat = $subcategoryId;
										$this->loadModel('CategoriesSubcategory');
										$this->loadModel('CountiesCategoriesSubcategory');
										$deleteids = $this->CategoriesSubcategory->find('all',array('fields'=>'CategoriesSubcategory.id','conditions'=>array('CategoriesSubcategory.subcategory_id'=>$subcat)));
										foreach($deleteids as $deleteids) {
											$this->CountiesCategoriesSubcategory->deleteAll(array('CountiesCategoriesSubcategory.categories_subcategory_id'=>$deleteids['CategoriesSubcategory']['id']));
										}
										$this->CategoriesSubcategory->deleteAll(array('CategoriesSubcategory.subcategory_id'=>$subcat));
										
										foreach($this->data['categories']['category_id'] as $cats) {
											$save = '';
											$save['CategoriesSubcategory']['id'] = '';
											$save['CategoriesSubcategory']['category_id'] = $cats;
											$save['CategoriesSubcategory']['subcategory_id'] = $subcat;
											$this->CategoriesSubcategory->save($save,false);
												$county = $this->data['categories']['county'];
												$lastid = $this->CategoriesSubcategory->getlastinsertid();
												foreach($county as $county) {
													$save2 = '';
													$save2['CountiesCategoriesSubcategory']['id'] = '';
													$save2['CountiesCategoriesSubcategory']['county_id'] = $county;
													$save2['CountiesCategoriesSubcategory']['categories_subcategory_id'] = $lastid;
													$this->CountiesCategoriesSubcategory->save($save2,false);
												}	
										}
							$this->Session->setFlash('Your data has been updated successfully.');  
							$this->redirect(array('action' => "subcategory" , 'message'=>'success'));
							
								} else {
										/*setting error message if validation fails*/
										$errors = "Please select the parent category of subcategory";
										$this->Session->setFlash($errors);
										$this->redirect($this->referer()); 
								 }
							
							} else{  

							/*setting error message if validation fails*/
							$errors = $this->Subcategory->invalidFields();	
							$this->Session->setFlash(implode('<br>', $errors));  
							$this->redirect(array('action' => "subcategoryEditDetail/".$this->data['categories']['id'])); 
							
			  }
	    }
		
		//delete category data in database
	   function subcategoryDelete($id) {
	    
		         App::import('model','Subcategory'); // importing Subcategory model
				
			     $this->Subcategory     = new Subcategory();
			 
			     $categoryDetailArr 	= $this->Subcategory->find("id = $id");
				 
				 $resultLink   		    = $this->Subcategory->query("SELECT * FROM links where category_id like "."'%".",".$id.","."%'");
			  
			     $resultAdvertiserProfile = $this->Subcategory->query("SELECT * FROM advertiser_profiles where category like "."'%".",".$id.","."%'");
				 
				 $resultTopTen            = $this->Subcategory->query("SELECT * FROM top_ten_businesses where category like "."'%".",".$id.","."%'");
				 
				 
				 if((!empty($resultLink))||(!empty($resultAdvertiserProfile))||(!empty($resultTopTen))){
				 
				   if(!empty($resultLink))
			          $delete['resultLink'] = 'This sub-category contain Link Manager.You have to delete first Link Manager of this sub-category.';
				  
				   if(!empty($resultAdvertiserProfile))
			          $delete['resultAdvertiserProfile'] = 'This sub-category contain Advertiser Profile.You have to delete first Advertiser Profile of this sub-category.';
				  
				   if(!empty($resultTopTen))
			         $delete['resultTopTen'] = 'This sub-category contain Top Ten Bussiness Detail.You have to delete first Ten Bussiness Detail of this sub-category.';
					 
				     $this->Session->setFlash(implode('<br>', $delete));
				  
				     $this->redirect(array('action' => "subcategory" , 'message'=>'error'));
					 
				 } else {
								
			        $this->Subcategory->delete($id);
				 
			        $this->Session->setFlash('The Sub Category with id: '.$id.' has been deleted.');
			
			       $this->redirect(array('action'=>'subcategory' , 'message'=>'success'));
				 
				}
			
	   }

/*---------------------------it is used to autocomplete the search box-----------------------------------------------------*/
	function autocompleteSubcategory($string='') {

			$this->autoRender = false;
			if($string!=''){
			$arr = '';
			 App::import('model', 'Subcategory');
			$this->Subcategory = new Subcategory;
			$name = $this->Subcategory->query("SELECT Subcategory.categoryname FROM subcategories AS Subcategory WHERE Subcategory.categoryname LIKE '$string%' order by categoryname asc");
			foreach($name as $name) {
				$arr[] = $name['Subcategory']['categoryname'];
			}
			echo json_encode($arr);
			}
	}	

	/*----to create ajax category list-----------*/		
	function selectedCatList($county_list='',$sel_cat=''){
		$this->layout = false;

		if(isset($county_list) && $county_list!='')
				$this->set('county_list',$county_list);
		else
				$this->set('county_list','');

		if(isset($sel_cat) && $sel_cat!='')
			$this->set('sel_cat',explode('-',$sel_cat));
		else
			$this->set('sel_cat','');

	}
	/*----to create ajax category list-----------*/		
	function catForOrder($county=0,$platform='computer'){
		$this->layout = false;
		$this->set('county',$county);
		$this->set('platform',$platform);
	}
	/*----to create ajax category list-----------*/		
	function catForProfile($county=0){
		$this->layout = false;
		$this->set('county',$county);
	}	
	/*----to create ajax category list-----------*/		
	function catForDiscount($county=0){
		$this->layout = false;
		$this->set('county',$county);
	}
	/*----to create ajax category list-----------*/		
	function catForDeal($county=0){
		$this->layout = false;
		$this->set('county',$county);
	}
/*------------------------------------------------------------------------------------------------------------------------*/
	   
      /*    destroy all current sessions for a particular SuperAdmins
	       and redirect to login page automatically
	 */
	    function logout() {
   		         $this->redirect($this->Auth->logout());
        }


     /*

	    this function is checking username and pasword in database
	    and if true then redirect to home page
	*/
	   function beforeFilter() { 
	 
             $this->Auth->fields = array(
             'username' => 'username', 
             'password' => 'password'
            );
			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
			
   	    }
		
		
		
	 //Set css
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
		/* This function is setting all info about current SuperAdmins in 
	currentAdmin array so we can use it anywhere lie name id etc.
	*/

	 function beforeRender(){
	 
		    $this->set('currentAdmin', $this->Auth->user());
			$this->set('cssName',$this->Cookie->read('css_name'));
			$this->set('groupDetail',$this->common->adminDetails());
			$this->set('common',$this->common);
			//$this->Ssl->force();

	  } 
}//end class