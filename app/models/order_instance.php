<?php 
	class OrderInstance extends AppModel { 
	        var $name = 'OrderInstance';
			var $belongsTo = array('Package','AdvertiserProfile','AdvertiserOrder');
			
			//Validation for users
		   var $validate =  '';	
		
		function advertiserOrderEditDetail($id=null){
			$this->id = $id;
			$AdvertiserOrder = $this->read();
			return $AdvertiserOrder;
	      }	
	
	    function packageDetail($advertiserId){
			$packageQuery = $this->query("select order_id from advertiser_profiles where id = '".$advertiserId."'");
			$packageIdQuery = $this->query("select package_id from advertiser_orders where id = '".$packageQuery[0]['advertiser_profiles']['order_id']."'");
			return $packageIdQuery[0]['advertiser_orders']['package_id'];
	      }	
		  
	    function salesPersonDetail($advertiserId){
			$salesPersonQuery = $this->query("select order_id from advertiser_profiles where id = '".$advertiserId."'");
			$salesPersonIdQuery = $this->query("select salesperson from advertiser_orders where id = '".$salesPersonQuery[0]['advertiser_profiles']['order_id']."'");
			if(isset($salesPersonIdQuery[0]['advertiser_orders']['salesperson'])) {
				return $salesPersonIdQuery[0]['advertiser_orders']['salesperson'];
			} else {
				return 0;
			}
	      }		  
	} 
?>