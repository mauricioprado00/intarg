<?php
class Inta_Collection_Reporte_ByResultadoEsperado extends Core_Collection{
	protected function getXmlEntityTagname(){
		return 'inta_actividad_byresultado_esperado';
		//return str_replace('inta_', '', $this->getDbTableName());
	}
	public function getResultadoEsperado(){
		//todo: mejorar, esto deberia hacerse con el docbloc de "referencia", pero eso funciona solo para Model_abstract habria que hacerlo para todos los objetos, previo comprobar performance
		$resultado_esperado = new Inta_Model_ResultadoEsperado();
		$resultado_esperado->setId($this->getIdResultadoEsperado());
		if($resultado_esperado->load()){
			return $resultado_esperado;
		}
		return null;
	}
}
?>