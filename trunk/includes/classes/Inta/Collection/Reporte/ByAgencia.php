<?php
class Inta_Collection_Reporte_ByAgencia extends Core_Collection{
	protected function getXmlEntityTagname(){
		return 'inta_actividad_byagencia';
		//return str_replace('inta_', '', $this->getDbTableName());
	}
	public function getAgencia(){
		//todo: mejorar, esto deberia hacerse con el docbloc de "referencia", pero eso funciona solo para Model_abstract habria que hacerlo para todos los objetos, previo comprobar performance
		$agencia = new Inta_Model_Agencia();
		$agencia->setId($this->getIdAgencia());
		if($agencia->load()){
			return $agencia;
		}
		return null;
	}
}
?>