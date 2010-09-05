<?
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
}
?>