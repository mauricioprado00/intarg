<?
class Granguia_Model_Provincia extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"id_pais",
			"nombre",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'bm_provincia';
	}
	public function listarCiudades(){
		$c = new Granguia_Model_Ciudad();
		$c->setIdProvincia($this->getId());
		$c->setWhere(Db_Helper::equal('id_provincia'));
		if($cp->searchCount()){
			return($cp->search());
		}
		return(null);
	}
}
?>