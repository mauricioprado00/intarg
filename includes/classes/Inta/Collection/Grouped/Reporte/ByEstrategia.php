<?php
/**
 *@referencia Estrategia(id_estrategia) Inta_Model_Estrategia(id)
*/
class Inta_Collection_Grouped_Reporte_ByEstrategia extends Core_Collection_Grouped{
	protected function getXmlEntityTagname(){
		return 'inta_actividad_byestrategia';
		//return str_replace('inta_', '', $this->getDbTableName());
	}
	public function getEstrategia(){
		if(!$this->hasIdEstrategia())
			return null;
		//todo: mejorar, esto deberia hacerse con el docbloc de "referencia", pero eso funciona solo para Model_abstract habria que hacerlo para todos los objetos, previo comprobar performance
		$estrategia = new Inta_Model_Estrategia();
		$estrategia->setId($this->getIdEstrategia());
		if($estrategia->load()){
			return $estrategia;
		}
		return null;
	}
}
?>