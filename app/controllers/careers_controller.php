<?php
class CareersController extends AppController { 
        var $name    = 'Careers';
        var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax');  
        var $layout  = 'career';            
        var $components = array('Auth','common','Cookie','RequestHandler','Session');
        
		//---------------------------------------------------------------------------------------------------------------------------------------------------//
		
        function index(){
			if(!$this->Session->read('county_data')){
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
		   }
            $this->layout='staticpage';
            
			$this->set('title_for_layout','Careers');
            $this->set('state_list',$this->common->getAllState());
            $this->set('county_list','');
			$state='';$county='';
            App::import('model','Job');
            $this->Job = new Job();
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));           
			$matchingJobs = $this->Job->find('all',array('conditions'=>array('Job.start_date <= '=>$today,'Job.end_date >= '=>$today,'status'=>'yes')));
			$this->set('matchingJobs',$matchingJobs);
            $this->set('state',$state);
            $this->set('county',$county);
        }
        
		//---------------------------------------------------------------------------------------------------------------------------------------------------//
		
        function jobs($state='',$county=''){
             if(!$this->Session->read('county_data')){
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
		    }
			 $this->layout='staticpage';
            $pageStateId = $this->common->getStateIdByUrl($state);
            $pageCountyId = $this->common->getCountyIdByUrl($county);
            App::import('model','Job');
            $this->Job = new Job();
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $matchingJobs = $this->Job->find('all',array('conditions'=>array('Job.start_date <= '=>$today,'Job.end_date >= '=>$today,'status'=>'yes')));
            $this->set('matchingJobs',$matchingJobs);
            $this->set('state',$state);
            $this->set('county',$county);
        }
		
		//---------------------------------------------------------------------------------------------------------------------------------------------------//
		
        function details($jobid = null){
            if(!$this->Session->read('county_data')){
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
		   }
			if(isset($jobid)){
                $this->set('common',$this->common);
               $this->layout='staticpage';
                App::import('model','Job');
                $this->Job = new Job();
				
				$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
				
                $jobDetails = $this->Job->find('first',array('conditions'=>array('Job.start_date <= '=>$today,'Job.end_date >= '=>$today,'status'=>'yes','Job.id'=>$jobid)));
				if(!empty($jobDetails))
                	$this->set('jobDetails',$jobDetails);
				else
					 $this->redirect(array('controller'=>'careers','action'=>'index'));
            }else{
                $this->redirect(array('controller'=>'careers','action'=>'index'));
            }            
        }
		
		//---------------------------------------------------------------------------------------------------------------------------------------------------//
		
        function apply($jobid = null){
            if(!$this->Session->read('county_data')){
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
		   }
			$this->set('common',$this->common);
             $this->layout='staticpage';
			$state='';
			$this->set('state',$state);
            $this->set('state_list',$this->common->getAllState());
			
            if(isset($this->data)){
                $this->Career->set($this->data);
				
                 if($this->Career->validates()){
                    $imageName='';
                    if($this->data['Career']['resume']['name']!=''){
                        $this->data['Career']['resume']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','_',$this->data['Career']['resume']['name']);
                        @chmod(APP.'webroot/resumes',0777);
                        $docDestination = APP.'webroot/resumes/'.$this->data['Career']['resume']['name']; 
                       if(move_uploaded_file($this->data['Career']['resume']['tmp_name'], $docDestination))
					   {
                       		$this->data['Career']['resume']=$this->data['Career']['resume']['name']; 
					   }else{
					   		$this->Session->setFlash('File uploading problem, please try again.');
							 $this->set('job_id',$jobid);
							return false;
					   }
                    }
					
					/*if(isset($this->data['Career']['fname']) && $this->data['Career']['fname']=='First Name:'){$this->data['Career']['fname']==''}
					if(isset($this->data['Career']['lname']) && $this->data['Career']['lname']=='Last Name:'){$this->data['Career']['lname']==''}
					if(isset($this->data['Career']['address']) && $this->data['Career']['address']=='Address:'){$this->data['Career']['address']==''}
					if(isset($this->data['Career']['phone']) && $this->data['Career']['phone']=='Phone number:'){$this->data['Career']['phone']==''}
					if(isset($this->data['Career']['alt_phone']) && $this->data['Career']['alt_phone']=='Alternate Phone number:'){$this->data['Career']['alt_phone']==''}
					if(isset($this->data['Career']['city']) && $this->data['Career']['city']=='City:'){$this->data['Career']['city']==''}
					if(isset($this->data['Career']['zip']) && $this->data['Career']['zip']=='Zip:'){$this->data['Career']['zip']==''}
					if(isset($this->data['Career']['email']) && $this->data['Career']['email']=='Email:'){$this->data['Career']['email']==''}
					if(isset($this->data['Career']['date_available']) && $this->data['Career']['date_available']=='Date Available :'){$this->data['Career']['date_available']==''}
					if(isset($this->data['Career']['last_pay']) && $this->data['Career']['last_pay']=='Least acceptable rate of pay :'){$this->data['Career']['last_pay']==''}*/
					
					$wrking_shift=array('');
					$wrking_shift[] = ($this->data['Career']['working_shift_day']) ? 'Days':'';
					$wrking_shift[] = ($this->data['Career']['working_shift_night']) ? 'Nights':'';
					$wrking_shift[] = ($this->data['Career']['working_shift_noon']) ? 'Afternoon':'';
					$wrking_shift[] = ($this->data['Career']['working_shift_Weekend']) ? 'Weekends':'';
					$this->data['Career']['working_shift'] = implode(', ',array_filter($wrking_shift));
					
					$this->data['Career']['education'] =$this->data['Career']['education1'].'***'.$this->data['Career']['education2'].'***'.$this->data['Career']['education3'].'***'.$this->data['Career']['education4'];
					
                    if($this->Career->save($this->data)){ 
                            $this->Session->setFlash('<span style="color:#006600; font:15px \'OpenSansRegular\';">Job Application Sent Successfully.</span><br /><br />');
                            $this->redirect(FULL_BASE_URL.router::url('/',false).'careers');                            
                    }else{
                            $this->Session->setFlash('Data Save Problem, Please try later.');
                    }
                 }else{
                    $errors = $this->Career->invalidFields();
                    $this->Session->setFlash(implode('<br>', $errors));
					$this->set('state',$this->data['Career']['state']);
                    $this->set('job_id',$jobid);
                }
            }else{
                $this->set('job_id',$jobid);
            }            
        }
 
 /*------------------------------------------------------------------Mobile Section start----------------------------------------------------------------------------*/
         function mobile_index(){
			if(!$this->Session->read('state') || !$this->Session->read('county')){
				$this->Session->write('login_referer','careers');
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
		   }
            $this->layout='staticpage_mobile';
            
			$this->set('title_for_layout','Careers');
            $this->set('state_list',$this->common->getAllState());
            $this->set('county_list','');
			$state='';$county='';
            App::import('model','Job');
            $this->Job = new Job();
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));           
			$matchingJobs = $this->Job->find('all',array('conditions'=>array('Job.start_date <= '=>$today,'Job.end_date >= '=>$today,'status'=>'yes')));
			$this->set('matchingJobs',$matchingJobs);
            $this->set('state',$state);
            $this->set('county',$county);
        }
        //---------------------------------------------------------------------------------------------------------------------------------------------------//
        function mobile_jobs($state='',$county=''){
			if(!$this->Session->read('state') || !$this->Session->read('county')){
				$this->Session->write('login_referer','careers/jobs');
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
		    }
			 $this->layout='staticpage_mobile';
			 $this->set('title_for_layout','Careers');
            $pageStateId = $this->common->getStateIdByUrl($state);
            $pageCountyId = $this->common->getCountyIdByUrl($county);
            App::import('model','Job');
            $this->Job = new Job();
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $matchingJobs = $this->Job->find('all',array('conditions'=>array('Job.start_date <= '=>$today,'Job.end_date >= '=>$today,'status'=>'yes')));
            $this->set('matchingJobs',$matchingJobs);
            $this->set('state',$state);
            $this->set('county',$county);
        }
		//---------------------------------------------------------------------------------------------------------------------------------------------------//
        function mobile_details($jobid = null){
			if(!$this->Session->read('state') || !$this->Session->read('county')){
				$this->Session->write('login_referer','careers/details/'.$jobid);
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
		   }
		   $this->set('title_for_layout','Careers');
			if(isset($jobid)){
                $this->set('common',$this->common);
               $this->layout='staticpage_mobile';
                App::import('model','Job');
                $this->Job = new Job();
				
				$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
				
                $jobDetails = $this->Job->find('first',array('conditions'=>array('Job.start_date <= '=>$today,'Job.end_date >= '=>$today,'status'=>'yes','Job.id'=>$jobid)));
				if(!empty($jobDetails))
                	$this->set('jobDetails',$jobDetails);
				else
					 $this->redirect(array('controller'=>'careers','action'=>'index'));
            }else{
                $this->redirect(array('controller'=>'careers','action'=>'index'));
            }            
        }
		
		//---------------------------------------------------------------------------------------------------------------------------------------------------//
        
		function mobile_apply($jobid = null){
			if(!$this->Session->read('state') || !$this->Session->read('county')){
				$this->Session->write('login_referer','careers/apply/'.$jobid);
		   		$this->redirect(FULL_BASE_URL.router::url('/',false));
		   }
		   $this->set('title_for_layout','Careers');
			$this->set('common',$this->common);
             $this->layout='staticpage_mobile';
			$state='';
			$this->set('state',$state);
            $this->set('state_list',$this->common->getAllState());
			
            if(isset($this->data)){
                $this->Career->set($this->data);
				
                 if($this->Career->validates()){
                    $imageName='';
                    if($this->data['Career']['resume']['name']!=''){
                        $this->data['Career']['resume']['name'] = $this->common->getTimeStamp()."_".str_replace(' ','_',$this->data['Career']['resume']['name']);
                        @chmod(APP.'webroot/resumes',0777);
                        $docDestination = APP.'webroot/resumes/'.$this->data['Career']['resume']['name']; 
                       if(move_uploaded_file($this->data['Career']['resume']['tmp_name'], $docDestination))
					   {
                       		$this->data['Career']['resume']=$this->data['Career']['resume']['name']; 
					   }else{
					   		$this->Session->setFlash('File uploading problem, please try again.');
							 $this->set('job_id',$jobid);
							return false;
					   }
                    }
					
					$wrking_shift=array('');
					$wrking_shift[] = ($this->data['Career']['working_shift_day']) ? 'Days':'';
					$wrking_shift[] = ($this->data['Career']['working_shift_night']) ? 'Nights':'';
					$wrking_shift[] = ($this->data['Career']['working_shift_noon']) ? 'Afternoon':'';
					$wrking_shift[] = ($this->data['Career']['working_shift_Weekend']) ? 'Weekends':'';
					$this->data['Career']['working_shift'] = implode(', ',array_filter($wrking_shift));
					
					$this->data['Career']['education'] =$this->data['Career']['education1'].'***'.$this->data['Career']['education2'].'***'.$this->data['Career']['education3'].'***'.$this->data['Career']['education4'];
					
                    if($this->Career->save($this->data)){ 
                            $this->Session->setFlash('<span style="color:#006600; font:15px \'OpenSansRegular\';">Job Application Sent Successfully.</span><br /><br />');
                            $this->redirect(FULL_BASE_URL.router::url('/',false).'careers');                            
                    }else{
                            $this->Session->setFlash('Data Save Problem, Please try later.');
                    }
                 }else{
                    $errors = $this->Career->invalidFields();
                    $this->Session->setFlash(implode('<br>', $errors));
					$this->set('state',$this->data['Career']['state']);
                    $this->set('job_id',$jobid);
                }
            }else{
                $this->set('job_id',$jobid);
            }            
        }
 
 /*------------------------------------------------Mobile Section end--------------------------------------------------------*/       
        /*
        this function is checking username and pasword in database
        and if true then redirect to home page
        */
        function beforeFilter() { 		
		parent::beforeFilter();        
		    $this->Auth->allow('*');
            $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );
            $this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
			
			//find daily discount for today
			$this->loadModel('DailyDeal');
			$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$daily_deal = $this->DailyDeal->find('all',array('conditions'=>array("DailyDeal.status='yes' AND DailyDeal.s_date<=$today AND DailyDeal.e_date>=$today AND DailyDeal.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDeal.show_on_home_page=1"),'order'=>array('RAND()')));
			$this->set('daily_deal',$daily_deal);

			//find daily discount for today
			$this->loadModel('DailyDiscount');
			$today1 = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$today2 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			$daily_disc = $this->DailyDiscount->find('all',array('conditions'=>array("DailyDiscount.status='yes' AND DailyDiscount.s_date<=$today2 AND DailyDiscount.e_date>=$today2 AND DailyDiscount.advertiser_county_id=".$this->Session->read('county_data.id')." AND DailyDiscount.show_on_home_page=1"),'order'=>array('RAND()')));
			$this->set('daily_disc',$daily_disc);
			
		$this->set('myCookie', $this->Cookie);			
					
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
}
?>
