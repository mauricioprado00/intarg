<?php

class Test_AutofilterFieldOutput extends Granguia_Model_Menu{
	function _construct(){
		parent::_construct();
		//$this->addAutofiltersFieldOutput('orden', __CLASS__.'::filterOrden?prametroadicional');
		$this->addAutofilterFieldOutput('orden', __CLASS__.'::filterOrden?2st_parameter');
		$this->addAutofilterFieldOutput('orden', 'md5');
		$this->
		/**
			<action method="addAutofilterFieldOutput">
				<fieldname>orden</fieldname>
				<filter>Admin_Menu_Model_Menu::otrofiltro</filter>
				<param1>orden</param1>
				<param1>{</param1>
				<param1>}</param1>
			</action>
		*/
		addAutofilterFieldOutput('orden', 'Admin_Menu_Model_Menu', 'orden', '{', '}');
	}
	public static function filterOrden($orden, $key){
		return 'el orden de '.$key.' es '.$orden;
	}
	public static function otrofiltro($orden, $key, $comienzo='[', $fin=']'){
		return $comienzo.'otro filtro ('.$orden.')'.$fin;
	}
	// la salida seria: {otro filtro (70e9e2674cab864be4df90738030dedd)}
}
?>