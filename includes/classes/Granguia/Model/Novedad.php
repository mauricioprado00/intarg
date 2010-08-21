<?
class Granguia_Model_Novedad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"nombre",
			"descripcion",
			"desarrollo",
			"imagen",
			"activo",
			"fecha"
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'bm_novedad';
	}
}
?>