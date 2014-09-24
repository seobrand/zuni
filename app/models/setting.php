<?php 
	class Setting extends AppModel { 
	        var $name = 'Setting';
			 
		/*
		This function is getting setting data from database table based on setting id.
		 we have only one record in setting table so it will be always 1.
		*/
		function settingEditDetail($id=null){
			$this->id = $id;
			$Setting = $this->read();
			return $Setting;
	      }	
		  
		/*
		This function is getting Advertiser Email Data data like subject and body from database table
		*/
		function getAdvertiserEmailData(){
			$advertiserEmailQuery = $this->query("select new_advertiser_subject,new_advertiser_body from settings where id = 1");
			return $advertiserEmailQuery;
	      }	
		  
		/*
		This function is getting consumer Email Data data like subject and body from database table
		*/
		function getConsumerEmailData(){
			$advertiserEmailQuery = $this->query("select new_consumer_subject,new_consumer_body from settings where id = 1");
			return $advertiserEmailQuery;
	      }	
		/*
		This function is getting Friend Email Data data like subject and body from database table
		*/
		function getFriendEmailData(){
			$friendEmailQuery = $this->query("select send_to_friend_subject,send_to_frient_body from settings where id = 1");
			return $friendEmailQuery;
	      }	
		
		/*
		This function is getting business Email Data data like subject and body from database table
		*/
		function getBusinessEmailData(){
			$friendEmailQuery = $this->query("select new_business_subject,new_business_body from settings where id = 1");
			return $friendEmailQuery;
	      }	
		/*
		This function is getting sent proof Email Data data like subject and body from database table
		*/
		function getSentProofEmailData(){
			$friendEmailQuery = $this->query("select new_sent_proof_subject,new_sent_proof_body from settings where id = 1");
			return $friendEmailQuery;
	      }	
		/*
		This function is getting accept proof Email Data data like subject and body from database table
		*/
		function getAcceptProofEmailData(){
			$friendEmailQuery = $this->query("select new_accept_proof_subject,new_accept_proof_body from settings where id = 1");
			return $friendEmailQuery;
	      }	
										
		/*
		This function is replacing all placemarkers from email body like advertiser name , company , order total etc. 
		*/
		function replaceMarkers($bodyText,$name,$package_name,$company_name,$package_price,$order_number){
				$data = str_replace('[advertiser_name]',$name,$bodyText);
				$data = str_replace('[package_name]',$package_name,$data);
				$data = str_replace('[company_name]',$company_name,$data);
				$data = str_replace('[package_price]','$'.$package_price,$data);
				$data = str_replace('[order_number]',$order_number,$data);
				return $data;
	      }
		/*  
		This function is replacing all placemarkers from email body like advertiser name , company , order total and password etc. 
		*/
		function replaceUserMarkers($bodyText,$name,$package_name,$company_name,$package_price,$order_number,$password,$signature){
				$data = str_replace('[advertiser_name]',$name,$bodyText);
				$data = str_replace('[package_name]',$package_name,$data);
				$data = str_replace('[company_name]',$company_name,$data);
				$data = str_replace('[package_price]','$'.$package_price,$data);
				$data = str_replace('[order_number]',$order_number,$data);
				$data = str_replace('[password]',$password,$data);
				$data = str_replace('[signature]',$signature,$data);
				return $data;
	      }
		/*
		This function is replacing all placemarkers from email body like consumer name etc. 
		*/
		function replaceMarkersConsumer($bodyText,$name){
				$data = str_replace('[consumer_name]',$name,$bodyText);
				return $data;

	      }		
	/*
		This function is replacing all placemarkers from email body like friend name , link , message & from etc. 
		*/
		function replaceMarkersFriend($bodyText,$name,$link,$msg,$from){
				$data = str_replace('[friend_name]',$name,$bodyText);
				$data = str_replace('[link]',$link,$data);
				$data = str_replace('[message]',$msg,$data);
				$data = str_replace('[from]',$from,$data);
				return $data;

	      }		
	/*
		This function is replacing all placemarkers from email body like friend name, link , message and from etc. 
		*/
		function replaceMarkersBusiness($bodyText,$name,$link,$msg,$from){
				$data = str_replace('[name]',$name,$bodyText);
				$data = str_replace('[link]',$link,$data);
				$data = str_replace('[business_details]',$msg,$data);
				$data = str_replace('[from]',$from,$data);
				return $data;

	      }		
	/*
		This function is replacing all placemarkers from email body like name, link , message and from etc. 
		*/
		function replaceMarkersSentProof($bodyText,$name,$link){
				$data = str_replace('[name]',$name,$bodyText);
				$data = str_replace('[link]',$link,$data);
				
				return $data;

	      }	
	/*
		This function is replacing all placemarkers from email body like name, link , message and from etc. 
		*/
		function replaceMarkersAcceptProof($bodyText,$name,$link){
				$data = str_replace('[name]',$name,$bodyText);
				$data = str_replace('[password]',$link,$data);
				
				return $data;

	      }						  				  		  		  	  
	} 
?>