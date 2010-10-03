<?
/**
 *@referencia Agencia(id_agencia) Inta_Model_Agencia(id)
 *@referencia Actividad(id_actividad) Inta_Model_Actividad(id)
 *@referencia Responsable(id_responsable) Inta_Model_Usuario(id)
 *@referencia UsuarioLogeado(id_usuario_logeado) Inta_Model_Usuario(id)
*/
class Inta_Model_Reporte_Actividad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_usuario_logeado',
			'id_agencia',
			'nombre_agencia',
			'id_actividad',
			'nombre_actividad',
			'id_responsable',
			'nombre_responsable',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'inta_resultado_actividad';
	}
}

?>