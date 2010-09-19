<?
/**
 *@referencia ResultadoEsperado(id_resultado_esperado) Inta_Model_ResultadoEsperado(id)
 *@referencia Indicador(id_indicador) Inta_Model_Indicador(id)
 *@listar MedioVerificacion Inta_Model_MedioVerificacionIndicadorResultado
 *listar MedioVerificaciones(id) Inta_Model_MedioVerificacionIndicadorResultado(id_indicador_resultado)
 *esta es una forma alternativa, si la clase destino no define las claves de la relacion
*/
class Inta_Model_IndicadorResultado extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_resultado_esperado',
			'id_indicador',
			//'id_medio_verificacion',
			'adecuado',
			'descripcion',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'inta_indicador_resultado';
	}
}
//	private $_medio_verificacions = null;
//	public function getMediosVerificacion(){
//		if(!$this->hasId()||!$this->getId())
//			return null;
//		if(!isset($this->_medio_verificacions)){
//			$medio_verificacion_indicador_resultado = new Inta_Model_MedioVerificacionIndicadorResultado();
//			$medio_verificacion_indicador_resultado
//				->setIdIndicadorResultado($this->getId())
//				->setWhere(Db_Helper::equal('id_indicador_resultado'))
//			;
//			if($medio_verificacions = $medio_verificacion_indicador_resultado->search(null, null, null, null, get_class($medio_verificacion_indicador_resultado))){
//				$this->_medio_verificacions = $medio_verificacions;
//			}
//		}
//		return $this->_medio_verificacions;
//	}
//
//	private $_indicador = null;
//	public function getIndicador(){
//		if(!$this->hasIdIndicador()||!($id_indicador = $this->getIdIndicador()))
//			return null;
//		if(!isset($this->_indicador)||$this->_indicador->getId()!=$id_indicador){
//			$indicador = new Inta_Model_Indicador();
//			$indicador->setId($id_indicador);
//			if(!$indicador->load())
//				$indicador = null;
//			$this->_indicador = $indicador;
//		}
//		return $this->_indicador;
//	}
//	private $_resultado_esperado = null;
//	public function getResultadoEsperado(){
//		if(!$this->hasIdResultadoEsperado()||!($id_resultado_esperado = $this->getIdResultadoEsperado()))
//			return null;
//		if(!isset($this->_resultado_esperado)||$this->_resultado_esperado->getId()!=$id_resultado_esperado){
//			$resultado_esperado = new Inta_Model_ResultadoEsperado();
//			$resultado_esperado->setId($id_resultado_esperado);
//			if(!$resultado_esperado->load())
//				$resultado_esperado = null;
//			$this->_resultado_esperado = $resultado_esperado;
//		}
//		return $this->_resultado_esperado;
//	}
//
?>