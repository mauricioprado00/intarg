<?
/**
 *@referencia Objetivo(id_objetivo) Inta_Model_Objetivo(id)
 *@listar Indicador Inta_Model_IndicadorResultado
*/
class Inta_Model_ResultadoEsperado extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_objetivo',
			'descripcion',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'inta_resultado_esperado';
	}
}
//	private $_indicadores = null;
//	public function getIndicadores(){
//		if(!$this->hasId()||!$this->getId())
//			return null;
//		if(!isset($this->_indicadores)){
//			$indicador_resultado = new Inta_Model_IndicadorResultado();
//			$indicador_resultado
//				->setIdResultadoEsperado($this->getId())
//				->setWhere(Db_Helper::equal('id_resultado_esperado'))
//			;
//			if($indicadores = $indicador_resultado->search(null, null, null, null, get_class($indicador_resultado))){
//				$this->_indicadores = $indicadores;
//			}
//		}
//		return $this->_indicadores;
//	}

?>