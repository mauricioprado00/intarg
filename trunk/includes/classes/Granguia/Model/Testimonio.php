<?
class Granguia_Model_Testimonio extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"descripcion",
			"relator",
			"localidad",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'bm_testimonio';
	}
}
?>