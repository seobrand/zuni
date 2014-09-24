<?php 
	class Offer extends AppModel { 
	        var $name = 'Offer';
		
				 
		function offerEditDetail($id=null){
			   $Offer = $this->query("SELECT * FROM saving_offers WHERE id ='".$id."'");
			   return $Offer;
	      }	
		 
	} 
?>