<?php

class Core_Block_Excel extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('excel/list.phtml');
	}
	private $campos = array();
	private $titulos = array();
	public function agregarColumna($campo, $titulo){
		$this->campos[] = $campo;
		$this->titulos[] = $titulo;
	}
	public function getTitulos(){
		return $this->titulos;
	}
	public function getCampos(){
		return $this->campos;
	}
}

?>