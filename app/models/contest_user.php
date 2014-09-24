<?php 
	class ContestUser extends AppModel { 
	        var $name = 'ContestUser';
			var $belongsTo = array('Contest','FrontUser');
	}
?>