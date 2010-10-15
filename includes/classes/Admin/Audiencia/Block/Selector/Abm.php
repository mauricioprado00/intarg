<?php
class Admin_Audiencia_Block_Selector_Abm extends Admin_Audiencia_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this->setTemplate('audiencia/selector/abm.phtml');
	}
}
?>