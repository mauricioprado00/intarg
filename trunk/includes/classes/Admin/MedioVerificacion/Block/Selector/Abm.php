<?php
class Admin_MedioVerificacion_Block_Selector_Abm extends Admin_MedioVerificacion_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this->setTemplate('medio_verificacion/selector/abm.phtml');
		//$this->addAutofilterFieldInput('id_tipo_indicador', array($this, '_filter_setIdTipoIndicador'));
	}

}
?>