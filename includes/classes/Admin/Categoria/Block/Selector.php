<?
class Admin_Categoria_Block_Selector extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('categoria/selector.phtml');
	}
}
?>