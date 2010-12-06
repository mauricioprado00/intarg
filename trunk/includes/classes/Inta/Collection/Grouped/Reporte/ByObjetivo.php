<?php
/**
 *@referencia Objetivo(id_objetivo) Inta_Model_Objetivo(id)
*/
class Inta_Collection_Grouped_Reporte_ByObjetivo extends Core_Collection_Grouped{
	protected function getXmlEntityTagname(){
		return 'inta_actividad_byobjetivo';
		//return str_replace('inta_', '', $this->getDbTableName());
	}
	public function getObjetivo(){
		if(!$this->hasIdObjetivo())
			return null;
		//todo: mejorar, esto deberia hacerse con el docbloc de "referencia", pero eso funciona solo para Model_abstract habria que hacerlo para todos los objetos, previo comprobar performance
		$objetivo = new Inta_Model_Objetivo();
		$objetivo->setId($this->getIdObjetivo());
		if($objetivo->load()){
			return $objetivo;
		}
		return null;
	}
}
?>