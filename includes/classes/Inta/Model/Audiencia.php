<?
/**
 *@referencia TipoAudiencia(id_tipo_audiencia) Inta_Model_TipoAudiencia(id)
 *@referencia Agencia(id_agencia) Inta_Model_Agencia(id)
*/
class Inta_Model_Audiencia extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_agencia',
			'id_tipo_audiencia',
			'nombre',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'inta_audiencia';
	}
	public function getListProblema(){
		$problema = new Inta_Model_Problema();
		$problema->setIdAudiencia($this->getId());
		$problema->setWhere(Db_Helper::equal('id_audiencia'));
		return $problema->search();
	}
	public static function crearFiltroAgencia($id_agencia=null){
		if(!isset($id_agencia))
			$id_agencia = Admin_Helper::getInstance()->getIdAgenciaSeleccionada();
		return Db_Helper::equal('id_agencia', $id_agencia);
		return Db_Helper::custom('id_agencia = {%s}', $id_agencia);
	}

}
?>