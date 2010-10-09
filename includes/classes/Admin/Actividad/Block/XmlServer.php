<?php
class Admin_Actividad_Block_XmlServer extends Jqgrid_Block_XmlServer{
	public function __construct(){
		parent::__construct();
		$this->setEntityClassname('Admin_Actividad_Block_XmlList_View');
		$post = Core_Http_Post::getParameters('Core_Object');
		if($post->hasCaption()){
			//var_dump(get_class(simplexml_load_string('<title>'.$post->getCaption().'</title>')));
			$this->addParam('title','sdf');
			//$params->setTitle($post->getCaption());
		}
		
	}
	protected function getParams(){
		$params = parent::getParams();
		$post = Core_Http_Post::getParameters('Core_Object');
		if($post->hasCaption())
			$params->setTitle($post->getCaption());
		return $params;
	}
}
?>