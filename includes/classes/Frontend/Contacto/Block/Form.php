<?php
class Frontend_Contacto_Block_Form extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('contacto/form.phtml');
	}
	private $_formulario = null;
	protected function getFormulario(){
		if(!isset($this->_formulario)){
			$this->_formulario = $this->getChildHtml('campos_form');
		}
		return $this->_formulario;
	}
	private $_firma = null;
	protected function getFirma(){
		if(!isset($this->_firma)){
			$firma = '';
			$reg = '(name="(?<campos>[^"]+)")';
			if(preg_match_all($reg, $this->getFormulario(), $matches)){
				$campos = implode('-',$matches['campos']).Frontend_Contacto_Helper::getInstance()->getClaveEncriptacion();
				$firma = sha1($campos);
			}
			$this->_firma = $firma;
		}
		return $this->_firma;
	}
	protected function _beforeToHtml(){

	}
	public function getUrlAction(){
		if(!$this->hasUrlAction()){
			$url = Frontend_Contacto_Helper::getInstance()->getLinkForm();
			$this->setUrlAction($url);
		}
		return parent::getData('url_action');
	}
}
?>