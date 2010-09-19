<?php
/**
 *@referencia IndicadorResultado(id_indicador_resultado) Inta_Model_IndicadorResultado(id)
 *@referencia MedioVerificacion(id_medio_verificacion) Inta_Model_MedioVerificacion(id)
*/
class Inta_Model_MedioVerificacionIndicadorResultado extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_indicador_resultado',
			'id_medio_verificacion',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getIndicador(){
		if($indicador_resultado = $this->getIndicadorResultado()){
			if($indicador = $indicador_resultado->getIndicador()){
				return $indicador;
			}
		}
		return null;
	}
	public function getResultadoEsperado(){
		if($indicador_resultado = $this->getIndicadorResultado()){
			if($resultado_esperado = $indicador_resultado->getResultadoEsperado()){
				return $resultado_esperado;
			}
		}
		return null;
	}
	public function getDbTableName() 
	{
		return 'inta_medio_verificacion_indicador_resultado';
	}
}
//	private $_indicador_resultado = null;
//	public function getIndicadorResultado(){
//		if(!$this->hasIdIndicadorResultado()||!($id_indicador_resultado = $this->getIdIndicadorResultado()))
//			return null;
//		if(!isset($this->_indicador_resultado)||$this->_indicador_resultado->getId()!=$id_indicador_resultado){
//			$indicador_resultado = new Inta_Model_IndicadorResultado();
//			$indicador_resultado->setId($id_indicador_resultado);
//			if(!$indicador_resultado->load())
//				$indicador_resultado = null;
//			$this->_indicador_resultado = $indicador_resultado;
//		}
//		return $this->_indicador_resultado;
//	}
//	private $_medio_verificacion = null;
//	public function getMedioVerificacion(){
//		if(!$this->hasIdMedioVerificacion()||!($id_medio_verificacion = $this->getIdMedioVerificacion()))
//			return null;
//		if(!isset($this->_medio_verificacion)||$this->_medio_verificacion->getId()!=$id_medio_verificacion){
//			$medio_verificacion = new Inta_Model_MedioVerificacion();
//			$medio_verificacion->setId($id_medio_verificacion);
//			if(!$medio_verificacion->load())
//				$medio_verificacion = null;
//			$this->_medio_verificacion = $medio_verificacion;
//		}
//		return $this->_medio_verificacion;
//	}
//
?>