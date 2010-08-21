<?
class Granguia_Model_CiudadCodigoPostal extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"id_ciudad",
			"codigo_postal",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'bm_ciudad_codigo_postal';
	}
}
?>