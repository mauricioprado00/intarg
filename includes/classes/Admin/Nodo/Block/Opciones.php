<?
class Admin_Nodo_Block_Opciones extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('nodo/add.phtml');
	}
	public function getValidationLinkUrl(){
		if(!$this->hasValidationLinkUrl()){
			$this->setValidationLinkUrl(Admin_Nodo_Helper::getValidationLinkUrl());
		}
		return parent::getData('validation_link_url');
	}
}
?>