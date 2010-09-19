<?
/**
 *@referencia TipoAspecto(id_tipo_aspecto) Inta_Model_TipoAspecto(id)
*/
class Inta_Model_Aspecto extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_tipo_aspecto',
			'nombre',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'inta_aspecto';
	}
}
?>