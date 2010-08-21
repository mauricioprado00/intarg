<?php
class Frontend_Categoria_Block_Menu extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('categoria/menu.phtml');
	}
	public function getCategorias(){
		return Granguia_Model_Categoria::getTree(null, 'Granguia_Model_Categoria');
//		
//		if(!parent::hasData('categorias')){
//			$categoria = new Granguia_Model_Categoria();
//			parent::setData('categorias', $categoria->search());
//		}
//		return parent::getData('categorias');
	}
}
?>