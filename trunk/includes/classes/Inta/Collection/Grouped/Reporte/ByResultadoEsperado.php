<?php
/**
 *@referencia ResultadoEsperado(id_resultado_esperado) Inta_Model_ResultadoEsperado(id)
*/
class Inta_Collection_Grouped_Reporte_ByResultadoEsperado extends Core_Collection_Grouped{
	protected function getXmlEntityTagname(){
		return 'inta_actividad_byresultado_esperado';
		//return str_replace('inta_', '', $this->getDbTableName());
	}
	public function getResultadoEsperado(){
		if(!$this->hasIdResultadoEsperado())
			return null;
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