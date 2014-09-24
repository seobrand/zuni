<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/*
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
//exit;
/*if(isset($_SERVER['REDIRECT_QUERY_STRING']) && strpos($_SERVER['REDIRECT_QUERY_STRING'],'switchdesktop')) {
	$_SESSION['switch'] = 'hello';
}*/
include_once(WWW_ROOT.'Mobile_Detect.php');
$detect_root = new Mobile_Detect();
$device = ($detect_root->isMobile() ? ($detect_root->isTablet() ? 'tablet' : 'mobile') : 'computer');

Router::connect('/florida/marion',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/monmouth',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/monmouth/new-jersey',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/new-jersey/monmouth',array('controller' => 'pages', 'action' => 'formerpage'));

Router::connect('/marion',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/marion/florida',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/business',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/topten_business',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/marion/florida/',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/1/marion/florida',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/marion/florida/1',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/marion/florida/',array('controller' => 'pages', 'action' => 'formerpage'));

// static pages routing
/*Router::connect('/state/florida/marion/anthony/food-and-dining/catering',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/marion-oaks/home-and-garden/pressure-washing',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/FREE',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/anthony/food-and-dining/coffee-shop',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/anthony/food-and-dining/grocery-stores-supermarkets',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/anthony/shopping/wedding',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/anthony/travel-transportation-lodging/hotels',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/belleview/food-and-dining/grocery-stores-supermarkets',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/belleview/travel-transportation-lodging/hotels',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/dunnellon/food-and-dining/catering',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/dunnellon/food-and-dining/coffee-shop',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/dunnellon/food-and-dining/grocery-stores-supermarkets',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/dunnellon/shopping/wedding',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/dunnellon/travel-transportation-lodging/hotels',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/hernando/food-and-dining/catering',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/hernando/food-and-dining/coffee-shop',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/hernando/food-and-dining/grocery-stores-supermarkets',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/hernando/shopping/wedding',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/hernando/travel-transportation-lodging/hotels',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocala/food-and-dining/grocery-stores-supermarkets',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocala/food-and-dining/ice-cream-and-yogurt',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocala/food-and-dining/pizza',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocala/home-and-garden/pool-spa',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocala/medical-and-dental/dentists',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocala/nightlife/bars',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocala/nightlife/night-clubs',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocala/shopping/antiques',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocala/shopping/furniture',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocala/travel-transportation-lodging/hotels',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocklawaha/food-and-dining/grocery-stores-supermarkets',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/ocklawaha/travel-transportation-lodging/hotels',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/silversprings/food-and-dining/catering',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/silversprings/food-and-dining/coffee-shop',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/silversprings/food-and-dining/grocery-stores-supermarkets',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/silversprings/shopping/wedding',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/silversprings/travel-transportation-lodging/hotels',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/sparr/food-and-dining/catering',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/sparr/food-and-dining/coffee-shop',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/sparr/food-and-dining/grocery-stores-supermarkets',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/sparr/shopping/wedding',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/sparr/travel-transportation-lodging/hotels',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/test/food-and-dining/catering',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/test/food-and-dining/coffee-shop',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/test/food-and-dining/grocery-stores-supermarkets',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/test/shopping/wedding',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/test/travel-transportation-lodging/hotels',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/wildwood/food-and-dining/catering',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/wildwood/food-and-dining/coffee-shop',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/wildwood/food-and-dining/grocery-stores-supermarkets',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/wildwood/shopping/wedding',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/state/florida/marion/wildwood/travel-transportation-lodging/hotels',array('controller' => 'pages', 'action' => 'formerpage'));

Router::connect('/category/state/florida/marion/ocala/shopping',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/category/state/florida/marion/ocala/food-and-dining',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/category/state/florida/marion/ocala/medical-and-dental',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/category/state/florida/marion/anthony',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/author/admin/feed',array('controller' => 'pages', 'action' => 'formerpage'));*/
Router::connect('/page/2',array('controller' => 'pages', 'action' => 'formerpage'));
Router::connect('/admin', array('controller' => 'admins', 'action' => 'login'));

Router::connect('/category/state/florida/marion',array('controller' => 'pages', 'action' => 'formerpage'));

Router::connect('/category/state/florida/marion/ocala',array('controller' => 'pages', 'action' => 'formerpage'));

Router::connect('/category/state/florida/marion/ocala/page/2',array('controller' => 'pages', 'action' => 'formerpage'));

Router::connect('/category/state/florida/marion/ocala/travel-transportation-lodging',array('controller' => 'pages', 'action' => 'formerpage'));

Router::connect('/category/state/florida/marion/page/2',array('controller' => 'pages', 'action' => 'formerpage'));

Router::connect('/category/state/florida/marion/ocala/home-and-garden',array('controller' => 'pages', 'action' => 'formerpage'));


if($device=='mobile') {
//if($_SERVER['REMOTE_ADDR']=='192.168.100.44') {
//if($device!='mobile' && $_SERVER['REMOTE_ADDR']!='192.168.100.45'){
	Router::connect('/merchants/*',array('controller' => 'mobiles', 'action' => 'merchants'));
	Router::connect('/userSignup',array('controller' => 'mobile_users', 'action' => 'userSignup'));
	Router::connect('/merchantEmail',array('controller' => 'mobile_users', 'action' => 'merchantEmail'));
	
	Router::connect('/fbSignup',array('controller' => 'mobile_users', 'action' => 'fbSignup'));
	Router::connect('/browseCounty',array('controller' => 'mobile_users', 'action' => 'browseCounty'));
	Router::connect('/changecategory',array('controller' => 'mobile_users', 'action' => 'changecategory'));

	Router::connect('/state/*',array('controller' => 'mobiles', 'action' => 'home'));
	Router::connect('/pages/page/*', array('controller' => 'mobiles', 'action' => 'staticpage'));
	Router::connect('/pages/*', array('controller' => 'mobiles', 'action' => 'display'));
	
	Router::connect('/referral', array('controller' => 'mobiles', 'action' => 'referral'));
	Router::connect('/', array('controller' => 'mobiles', 'action' => 'display'));
	Router::connect('/contact', array('controller' => 'contact_leads', 'action' => 'contactMobile'));
	Router::connect('/contact/success', array('controller' => 'contact_leads', 'action' => 'contactMobile'));
	Router::connect('/consumer_login',array('controller' => 'mobile_users', 'action' => 'consumerLogin'));
	
	Router::connect('/advertiser_login',array('controller' => 'mobile_users', 'action' => 'advertiserLogin'));
	
	Router::connect('/userPage',array('controller' => 'mobile_users', 'action' => 'userPage'));
	
	Router::connect('/careers',array('controller' => 'careers', 'action' => 'mobile_index'));
	Router::connect('/careers/index',array('controller' => 'careers', 'action' => 'mobile_index'));
	Router::connect('/careers/details/*',array('controller' => 'careers', 'action' => 'mobile_details'));
	Router::connect('/careers/jobs/*',array('controller' => 'careers', 'action' => 'mobile_jobs'));
	Router::connect('/careers/apply/*',array('controller' => 'careers', 'action' => 'mobile_apply'));
} else {
	Router::connect('/merchants/*',array('controller' => 'pages', 'action' => 'merchants'));
	Router::connect('/userSignup',array('controller' => 'front_users', 'action' => 'userSignup'));
	Router::connect('/merchantEmail',array('controller' => 'front_users', 'action' => 'merchantEmail'));
	
	Router::connect('/fbSignup',array('controller' => 'front_users', 'action' => 'fbSignup'));
	Router::connect('/browseCounty',array('controller' => 'front_users', 'action' => 'browseCounty'));
	Router::connect('/changecategory',array('controller' => 'front_users', 'action' => 'changecategory'));

	Router::connect('/state/*',array('controller' => 'pages', 'action' => 'home'));
	Router::connect('/pages/page/*', array('controller' => 'pages', 'action' => 'staticpage'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	
	Router::connect('/referral', array('controller' => 'pages', 'action' => 'referral'));
	Router::connect('/', array('controller' => 'pages', 'action' => 'display'));
	Router::connect('/contact', array('controller' => 'contact_leads', 'action' => 'contact'));
	Router::connect('/contact/success', array('controller' => 'contact_leads', 'action' => 'contact'));
	
	Router::connect('/consumer_login',array('controller' => 'front_users', 'action' => 'consumerLogin'));
	
	//Router::connect('/advetiser_login',array('controller' => 'front_users', 'action' => 'advetiserLogin'));
	
	Router::connect('/userPage',array('controller' => 'front_users', 'action' => 'userPage'));
}
Router::connect('/contact/success', array('controller' => 'contact_leads', 'action' => 'contact'));