<?
/**
 *@listar Objetivo Inta_Model_ObjetivoProblema
 *@referencia Audiencia(id_audiencia) Inta_Model_Audiencia(id)
*/
class Inta_Model_Problema extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			//'id_objetivo',
			'id_audiencia',
			'nombre',
			'importancia_economica',
			'impacto_ambiental',
			'importancia_social',
			'familias_implicadas',
			'valor_agregado_potencial',
			'impacto_desarrollo',
			'prioridad',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'inta_problema';
	}
	public static function crearFiltroAgencia($id_agencia=null){
		if(!isset($id_agencia))
			$id_agencia = Admin_Helper::getInstance()->getIdAgenciaSeleccionada();
		return Db_Helper::custom('id_audiencia IN (
		SELECT DISTINCT id 
		FROM inta_audiencia 
		WHERE id_agencia = {%s})', $id_agencia);
	}
}
?>