<?
class Granguia_Model_MetodoEnvio extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"nombre",
			"activo",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'bm_metodo_envio';
	}
}
?>