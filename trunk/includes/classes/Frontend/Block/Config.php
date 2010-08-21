<?
class Frontend_Block_Config extends Core_Block_Abstract{
	public function getHtml(){
		return($this->_toHtml());
	}
	public function _toHtml(){
		if(!$this->hasVariable())
			return '';
		$nombre_variable = $this->getVariable();
		if(!$nombre_variable)
			return '';
		$valor = Granguia_Model_Config::findConfigValue($nombre_variable);
		if(!isset($valor))
			return '';
		return $valor;
	}
}
?>