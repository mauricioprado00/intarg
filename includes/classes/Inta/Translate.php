<?php

class Inta_Translate extends Core_Translate_Singleton{
	public function __t($texto, $vars, $explicacion, $contexto){
		if(!trim($explicacion)&&$vars){
			$explicacion = 'variables: '.implode(', ', array_keys($vars));
		}
		return Inta_Model_Traduccion::Traducir($texto, $explicacion, $contexto);
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}

?>