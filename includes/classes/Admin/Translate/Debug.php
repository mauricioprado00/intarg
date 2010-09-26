<?php

class Admin_Translate_Debug extends Core_Translate_Singleton{
	public function __t($texto, $vars, $explicacion, $contexto){
		if(!trim($explicacion)&&$vars){
			$explicacion = 'variables: '.implode(', ', array_keys($vars));
		}
		$traduccion = Inta_Model_Traduccion::getMatch($texto, $explicacion, $contexto);
		Core_App::getInstance()->getMessageContainerTranslate()->addItem(
			$traduccion
		);
		//Inta_Model_Traduccion::Traducir($texto, $explicacion, $contexto);
	}
	public function isEnabled(){
		return Core_App::getSession()->getVar('enable_translate_debug', __CLASS__);
	}
	public function EnableDebug(){
		Core_App::getSession()->setVar('enable_translate_debug', true, __CLASS__);
		return $this;
	}
	public function DisableDebug(){
		Core_App::getSession()->setVar('enable_translate_debug', null, __CLASS__);
		return $this;
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}

?>