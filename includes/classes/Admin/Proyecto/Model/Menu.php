<?php

class Admin_Proyecto_Model_Proyecto extends Inta_Model_Proyecto{
	function _construct(){
		parent::_construct();
		//$this->addAutofiltersFieldOutput('orden', __CLASS__.'::filterOrden?prametroadicional');
		//$this->addAutofilterFieldOutput('orden', __CLASS__.'::filterOrden?2st_parameter');
		//$this->addAutofilterFieldOutput('orden', 'md5');
	}
	public static function filterOrden($orden, $key){
		return 'el orden de '.$key.' es '.$orden;
	}
	public static function otrofiltro($orden, $key, $comienzo='[', $fin=']'){
		return $comienzo.'otro filtro ('.$orden.')'.$fin;
	}
}
?>