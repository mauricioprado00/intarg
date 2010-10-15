<?php
//die(__FILE__); 
class Core_Block_Inline extends Core_Block_Abstract{
	public function __construct(){
		parent::__construct();
		$this->setInlineContent(true);
	}
	public function getHtml(){
		return $this->_toHtml();
	}
	public function _toHtml(){
		return $this->getInlineContent();
	}
}
?>