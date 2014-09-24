<?php 
class Article extends AppModel {
	var $name="Article";
	/*This is validation rule for ppc add form*/
	var $validate =  array(
				 'title'=>array(
				 			'title-1' =>array(
								'rule'=> 'notEmpty',
        		 				'message' => 'Please insert page title.'),
							'title-2' =>array(
								'rule'=> 'isUnique',
        		 				'message' => 'Page Title already in use, Please try another.')
								),
								
				 'description' => array(
        						'rule' => 'notEmpty',
        						'message' => 'Please insert description.')
				 );
				 
	
	/*this function is fetching page detail from article 
		table for a particular page id 
	*/
	function pageEditDetail($id=null){
			$this->id = $id;
			$Article = $this->read();
			return $Article;
	}	
	
/*	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		if(empty($order)){
			// great fix!
			$order = array($extra['passit']['sort'] => $extra['passit']['direction']);
		}
		//$group = $extra['group'];
		return $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group'));
	}*/	 
}
?>