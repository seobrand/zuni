<?php
class JobresumesController extends AppController { 
        var $name    = 'Jobresumes';
        var $helpers = array('Html', 'Form','User', 'Javascript','Text' , 'Image','Paginator','Ajax');  
        var $layout  = 'admin';            
        var $components = array('Auth','common','Cookie','RequestHandler','Session');
        var $uses = array(); 
        
        function index(){
            //echo "helllo";exit;
            $condition='';
            $this->set('CountyList',$this->common->getAllCounty());
            $this->set('countySearch', 'County');
            $this->set('title', 'Title');
            $this->set('publish', 'Publish');
            	
            App::import('model','Career');
            $this->Career = new Career();
            $this->paginate = array( 'limit' => PER_PAGE_RECORD,'order' => array( 'Career.id' => 'DESC' ));
            
            if((!empty($this->data['jobresumes']['countySearch']) && $this->data['jobresumes']['countySearch']!='County')){
                $cond['Jobresume.county'] =  $this->data['jobresumes']['countySearch'];
                (empty($this->params['named'])) ? $this->set('countySearch', $this->data['jobresumes']['countySearch']) :$this->set('countySearch', $this->data['named']['countySearch']) ; 
            }
            if((!empty($this->data['jobresumes']['title']) && $this->data['jobresumes']['title']!='Title')){
                $cond[] =  'Jobresume.title LIKE "%'.$this->data['jobresumes']['title'].'%"';
                (empty($this->params['named'])) ? $this->set('title', $this->data['jobresumes']['title']) :$this->set('title', $this->data['named']['title']) ; 
            }
            if((!empty($this->data['jobresumes']['publish']) && $this->data['jobresumes']['publish']!='Publish')){
                $cond['Jobresume.status'] =  $this->data['jobresumes']['publish'];
                (empty($this->params['named'])) ? $this->set('publish', $this->data['jobresumes']['publish']) :$this->set('publish', $this->data['named']['publish']) ; 
            }
            
            if(!empty($this->params['named'])){
                if(isset($this->params['named']['countySearch'] )){
                    $cond['Jobresume.county'] = $this->params['named']['countySearch'] ;
                    $this->set('countySearch', $this->params['named']['countySearch']);
                }

                if(isset($this->params['named']['title'] )){
                    $cond[] =  'Jobresume.title LIKE "%'.$this->params['named']['title'].'%"';
                    $this->set('title', $this->params['named']['title']);
                }

                if(isset($this->params['named']['publish'] )){
                    $cond['Jobresume.status'] =  $this->params['named']['publish'];
                    $this->set('publish', $this->params['named']['publish']);
                }
            }
            
            $cond = array();
            //If condition array is greater then 1 then combine by AND tag
            if(is_array($condition) && count($condition) > 1) {
                $condition['AND'] = $cond;
            } else {
                $condition  = $cond;
            }
            //pr($this->data);
            
            $resumes = $this->paginate('Career', $condition);
            $this->set('resumes', $resumes);             
        }  
        function view($resumeid = null){
            if(isset($resumeid)){
                App::import('model','Career');
                $this->Career = new Career();
                $this->Career->id = $resumeid;
		$this->set('data',$this->Career->read());
            }
        }
        function download ($filename) {
            $this->autoRender = false;
            $this->view = 'Media';
            $params = array(
                'id' => $filename,
                'name' => 'example',
                'extension' => 'docx',
                'mimeType' => array('docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'), // extends internal list of mimeTypes
                'path' => APP.WEBROOT_DIR . '/resumes' . DS.$filename
            );            
            $this->set($params);
            $this->autoLayout = false;
        }
        
        
 //-------------------------------------------------delete  job resume  data in database-----------------------------------------------------------------------//

	   function jobDelete($id) {
				  	$this->autoRender = false;
						
				   	App::import('model','Career');
					$this->Career = new Career();
					
					$this->Career->id=$id;
					
					$my_resume_file=$this->Career->field('resume');
					
					if($my_resume_file!='' && file_exists(WWW_ROOT.'resumes/'.$my_resume_file))
					{
						@chmod(WWW_ROOT.'resumes/',0777);
						@unlink(WWW_ROOT.'resumes/'.$my_resume_file);
					}
					
					$this->Career->delete($id);
					 
					$this->Session->setFlash('The Resume detail has been deleted.');
				
					$this->redirect(array('action'=>'index'));
	   }      
        
        /*
        this function is checking username and pasword in database
        and if true then redirect to home page
        */
        function beforeFilter() { 
            //$this->Auth->allow('index');
            $this->Auth->fields = array(
            'username' => 'username', 
            'password' => 'password'
            );
            $this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'home');
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
