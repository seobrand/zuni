<?php 
/*
   Coder	:	 Manoj pandit
   Date  	: 	 4 Oct 2011
*/ 


class FrontCategoriesController extends AppController{
 var $name = 'FrontCategories'; 
 var $helpers = array('Html', 'Form','User','Javascript','Text', 'Image','Paginator'); 
 var $components = array('Auth','common','Session','Cookie','Email');
 var $layout = 'admin';
 /***-----------------------This function is the Index function i.e. call by default---------------------------------------------------------------------------------*/
	function index(){
		$this->set('title_for_layout','Hot Button Manager');
		$this->set('categoryList',$this->common->getAllCategory());
		$this->paginate = array('limit' => PER_PAGE_RECORD,'order' => array('FrontCategory.id' => 'asc'));
		$data = $this->paginate('FrontCategory');
		$this->set('frontcategories', $data);
	}
/**------------------------------------------------------------------------------------------------------------------------------------------------------------------*/	
	
	
/***-----------------------This function Add new front category combination------------------------------------------------------------------------------------------*/
	function addFrontCategory(){
	/*-------check the categories before add------*/
		$cat_count=$this->FrontCategory->find('all');
		if(count($cat_count)<8)
		{
		if(isset($this->data))
				{
				  $this->FrontCategory->set($this->data['front_category']);

				  if($this->FrontCategory->validates())
				  {	
				  			  
					/*------------------------validation code for category selection-------------------------------------*/
					
				  	if(empty($this->data['category_selected'][0]))
					{
						$this->Session->setFlash('Please select atleast one category');
						return false;

					}
					/*--------------------------------------------------------------------------------------------------*/				  			 
					$cat_str='';
					$cat_str=implode(',',$this->data['category_selected']);
					$cat_str=','.$cat_str.',';
					$saveArray = array();
					/*----------------category order validation for front end view-----------------*/
					  if(!preg_match('/^[0-9]{1,}/', $this->data['front_category']['order']) || $this->data['front_category']['order'] == ""){
						     $findOrderQuery = $this->FrontCategory->query("SELECT MAX(front_categories.order) as orderMax FROM `front_categories`");
						     $maxOrder  =  $findOrderQuery[0][0];
						     $saveArray['FrontCategory']['order'] = (int)$maxOrder['orderMax'] + 1;
					  } 
					  else
					  {
					  		$saveArray['FrontCategory']['order']    =  $this->data['front_category']['order'];
					  }
					$saveArray['FrontCategory']['title']   					=  $this->data['front_category']['title'];
					$saveArray['FrontCategory']['categories_selected']   	=  $cat_str;
					$saveArray['FrontCategory']['publish']   				=  $this->data['front_category']['status'];
					$saveArray['FrontCategory']['page_url'] = $this->common->makeAlias(trim($this->data['front_category']['title']));
					$this->FrontCategory->save($saveArray);
					$this->Session->setFlash('Hot Botton Successfully Saved');
					$this->redirect(array('action' => 'index')); 
				  }
				  else
				  {				
						$errors = $this->FrontCategory->invalidFields();
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
		}
		else
		{
						$this->Session->setFlash('You are Already Have 8 Hot Bottons, So delete anyone to add another');
						$this->redirect(array('action' => 'index'));  		
		}
	}



/***----------------------This function Edit Existing front category combination------------------------------------------------------------------------------------*/
	function editFrontCategory($id=null){
	 	$this->set('categoryList',$this->common->getAllCategory()); //  List categories
		$this->set('data',$this->FrontCategory->findbyId($id));		// 	find the category details	
		if(isset($this->data))
				{
				  $this->FrontCategory->set($this->data['front_category']);
				  
				  $this->FrontCategory->id =$this->data['front_category']['cid'];			  
				  
				  if($this->FrontCategory->validates())
				  {
					/*------------------------validation code for title and category selection-------------------------------------*/

				  	if(empty($this->data['category_selected'][0]))
					{
						$error_cat[]='Please select atleast one category';

					}
					/*------------------------------------------------------------------------------------------------------------*/
									  				 
					$cat_str='';
					$cat_str=implode(',',$this->data['category_selected']);
					$cat_str=','.$cat_str.',';
					
					$saveArray = array();
					/*----------------category order validation for front end view-----------------*/
					  if(!preg_match('/^[0-9]{1,}/', $this->data['front_category']['order']) || $this->data['front_category']['order'] == ""){
						     $findOrderQuery = $this->FrontCategory->query("SELECT MAX(front_categories.order) as orderMax FROM `front_categories`");
						     $maxOrder  =  $findOrderQuery[0][0];
						     $saveArray['FrontCategory']['order'] = (int)$maxOrder['orderMax'] + 1;
					  } 
					  else
					  {
					  		$saveArray['FrontCategory']['order']    =  $this->data['front_category']['order'];
					  } 

					$saveArray['FrontCategory']['id']   					=  $this->data['front_category']['cid'];
					$saveArray['FrontCategory']['title']   					=  $this->data['front_category']['title'];
					$saveArray['FrontCategory']['categories_selected']   	=  $cat_str;
					$saveArray['FrontCategory']['publish']   				=  $this->data['front_category']['status'];
					$saveArray['FrontCategory']['page_url'] = $this->common->makeAlias(trim($this->data['front_category']['title']));					
					$this->FrontCategory->save($saveArray);
					$this->Session->setFlash('Hot Botton with id: '.$this->data['front_category']['cid'].' has been Updated Successfully');
					$this->redirect(array('action' => 'index')); 
				  }
				  else
				  {				
						$errors = $this->FrontCategory->invalidFields();
						$this->Session->setFlash(implode('<br>', $errors));  
						return false;
				  }	  
			}
			
				
	}


/***-----------------------This function Delete the  front category combination-----------------------------------------------------------------------------------*/
	
	function deleteFrontCategory($id=null){
								
					$this->FrontCategory->delete($id);
					$this->Session->setFlash('Hot Botton with id: '.$this->data['front_category']['cid'].' has been Deleted Successfully ');
					$this->redirect(array('action' => 'index')); 					
	}
	
	
/***-----------------------This function Set the Css for Particlar Theme Selection---------------------------------------------------------------------------------*/
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
					
/*
    this function is checking username and password in database
	and if true then redirect to home page
	*/

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

}
?>