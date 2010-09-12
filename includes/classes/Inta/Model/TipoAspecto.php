<?
class Inta_Model_TipoAspecto extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'nombre',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'inta_tipo_aspecto';
	}
}
?>