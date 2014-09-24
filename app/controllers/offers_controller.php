<?php 
/*
   Coder: anoop sharma
   Date  : 20 April 2011
*/ 

class OffersController extends AppController { 
      var $name = 'Offers';
	  var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator');  
	  var $layout = 'admin'; //variable for admin layout
	  var $components = array('common','Cookie','Auth','Email','Session');
	  
	  function index()
	  {
			#Getting detail of current logged in user
			$this->set('currentAdmin', $this->Auth->user());
			#Getting advertiser id from url and then setting that value in a variable 'adv_profile_id'
			$this->set('adv_profile_id', $this->params['pass'][0]);
			#Getting id , name, comnay name of a perticualr advertiser and than setting these 3 values
			#in three different variables for use in ctp file
			$adverName = $this->Offer->query("SELECT id,name,company_name FROM advertiser_profiles WHERE id ='".$this->params['pass'][0]."'");
			$this->set('adverName', $adverName[0]['advertiser_profiles']['name']);
			$this->set('adverCompany', $adverName[0]['advertiser_profiles']['company_name']);
			$this->set('adverId', $adverName[0]['advertiser_profiles']['id']);
			#making condition to filter data and getting data with pagging and assigning returning data array in a variable
			$sOffers = $this->Offer->query("SELECT * FROM saving_offers WHERE advertiser_profile_id ='".$this->params['pass'][0]."' order by id asc");
			#counting array length to get number of records to show or hide add new saving offer link
			#admin or sales person can insert max 5 saving offers after that link will be hide automatically.
			$this->set('totalSavingOffers', count($sOffers));
		    $this->set('SavingOffer', $sOffers);
			
			$vOffers = $this->Offer->query("SELECT * FROM vip_offers WHERE advertiser_profile_id ='".$this->params['pass'][0]."' order by id asc");
			#counting array length to get number of records to show or hide add new saving offer link
			#admin or sales person can insert max 5 saving offers after that link will be hide automatically.
			$this->set('totalVipOffers', count($vOffers));
		    $this->set('VipOffer', $vOffers);
			
			
	  }
	  
	 /*
		 we have admin theme in 5 different colors so this function is checking which color user 
		 wants and then assigning that color in cookie for further use
	 */
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
	 
		
		
	 
	 #------------------------------Function to Delete saving Offer------------------------------------
	function savingOfferDelete($id) {

			$imageOld = $this->Offer->query("SELECT offer_image_small,offer_image_big FROM saving_offers WHERE id =".$id.";");
			if($imageOld[0]['saving_offers']['offer_image_small']!=''){
				@unlink(APP.'webroot/img/offer/soffers/'.$imageOld[0]['saving_offers']['offer_image_small']);
			}
			if($imageOld[0]['saving_offers']['offer_image_big']!=''){
				@unlink(APP.'webroot/img/offer/soffers/'.$imageOld[0]['saving_offers']['offer_image_big']);
			}
			
			$this->Offer->query("delete from saving_offers where id ='".$id."'");
			$this->Session->setFlash('Saving Offer with id: '.$id.' has been deleted.');
			$this->redirect(array('action'=>'index/'.$this->params['pass'][1]));
		}
		
		
		
		function vipOfferDelete($id) {

			$imageOld = $this->Offer->query("SELECT offer_image_small FROM vip_offers WHERE id =".$id.";");
			if($imageOld[0]['vip_offers']['offer_image_small']!=''){
				@unlink(APP.'webroot/img/offer/voffers/'.$imageOld[0]['vip_offers']['offer_image_small']);
			}
			
			$this->Offer->query("delete from vip_offers where id ='".$id."'");
			$this->Session->setFlash('Vip Offer with id: '.$id.' has been deleted.');
			$this->redirect(array('action'=>'index/'.$this->params['pass'][1]));
		}	
			
	

	function beforeFilter() { 

        $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );

			$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
   	}
	

	/* This function is setting all info about current logged in user in 
		currentAdmin array so we can use it anywhere in ctp file.Also setting 
		cssname for current theme and usergroup detail
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