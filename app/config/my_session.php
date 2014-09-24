<?php 

ini_restore('session.referer_check');
ini_set('session.use_trans_sid', 0);
ini_set('session.name', Configure::read('Session.cookie'));
ini_set('session.cookie_lifetime', 0);
// Cookie path is now '/' even if you app is within a sub 
// directory on the domain
$this->path = '/';
ini_set('session.cookie_path', $this->path);
ini_set('session.cookie_domain', env('HTTP_BASE'));

?>