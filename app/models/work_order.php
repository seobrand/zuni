<?php 
	class WorkOrder extends AppModel { 
	        var $name = 'WorkOrder';
			
			 var $belongsTo = array('AdvertiserOrder');  

		function workOrderEditDetail($id=null){
		
		$msg_details=$this->query("select * from work_orders where work_orders.id= '".$id."'");
		if(isset($msg_details[0]['work_orders']['from_group']) && ($msg_details[0]['work_orders']['type']=='imageuploaded' || $msg_details[0]['work_orders']['from_group']=='Consumer' || ($msg_details[0]['work_orders']['from_group']=='Advertiser' && ($msg_details[0]['work_orders']['type']=='videoApproval' || $msg_details[0]['work_orders']['type']=='imageApproval')))) {
			return $msg_details;
		} else if(isset($msg_details[0]['work_orders']['from_group']) && $msg_details[0]['work_orders']['from_group']=='Feedback') {
			return $msg_details;
		} else {
		
		$adv_front=$this->query("select * from work_orders,advertiser_profiles where work_orders.id= '".$id."' and work_orders.advertiser_order_id = advertiser_profiles.id");
		//pr($adv_front);
		if(isset($adv_front[0]['work_orders']['from_group']) && ($adv_front[0]['work_orders']['from_group']=='Advertiser' || $adv_front[0]['work_orders']['from_group']=='Feedback')){
				return $adv_front;
			}
		else
			{
				$allData = $this->query("select * from advertiser_orders,advertiser_profiles,work_orders where work_orders.id= '".$id."' and advertiser_orders.id = advertiser_profiles.order_id and advertiser_orders.id = work_orders.advertiser_order_id");
				//pr($allData);
				return $allData;			
			}
		}			    
	}		  
		function updateReadStatus($id=null){
				$allData = $this->query("update work_orders set read_status ='1' where id= '".$id."'");	
				return $allData;
				exit;
	      }	
	} 
?>