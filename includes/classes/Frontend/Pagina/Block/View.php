<?php
class Frontend_Pagina_Block_View extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('pagina/view.phtml');
	}
	protected function cargarPagina(){
		if($this->hasPagina())
			return true;
		$pagina = new Granguia_Model_Pagina();
		$pagina->loadFromArray($this->getData());//cargo las propiedades asignadas al bloque (en el layout)
		$continuar = false;
		foreach($pagina->getData() as $key=>$value)
			if(isset($value)){
				$continuar = true;
				break;
			}
		if(!$continuar)
			return false;
		if($pagina->load(null, true)){
			$this->setPagina($pagina);
			return true;
		}
		return false;
	} 
	protected function prepararPagina(){
		if($this->cargarPagina()){
			Core_App::getUrlModel()->autofilterFieldOutput($this->getPagina(), 'contenido_html');
		}
	}
	protected function _beforeToHtml(){
		$this->prepararPagina();
	}
}
?>