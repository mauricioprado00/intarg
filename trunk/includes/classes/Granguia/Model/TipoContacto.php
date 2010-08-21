<?
class Granguia_Model_TipoContacto extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"nombre",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'gg_tipo_contacto';
	}
}
?>