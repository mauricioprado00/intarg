<?
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
	private $_indicadores = null;
	public function getIndicadores(){
		if(!$this->hasId()||!$this->getId())
			return null;
		if(!isset($this->_indicadores)){
			$indicador_resultado = new Inta_Model_IndicadorResultado();
			$indicador_resultado
				->setIdResultadoEsperado($this->getId())
				->setWhere(Db_Helper::equal('id_resultado_esperado'))
			;
			if($indicadores = $indicador_resultado->search()){
				$this->_indicadores = $indicadores;
			}
		}
		return $this->_indicadores;
	}
	public function getDbTableName() 
	{
		return 'inta_resultado_esperado';
	}
}
?>