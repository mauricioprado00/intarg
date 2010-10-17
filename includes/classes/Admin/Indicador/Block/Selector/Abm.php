<?php
class Admin_Indicador_Block_Selector_Abm extends Admin_Indicador_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this->setTemplate('indicador/selector/abm.phtml');
		//$this->addAutofilterFieldInput('id_tipo_indicador', array($this, '_filter_setIdTipoIndicador'));
	}

}
?>