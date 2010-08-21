<?
class Granguia_Model_Pais extends Core_Model_Abstract{
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
		return 'bm_pais';
	}
	public function listarProvincias(){
		$c = new Granguia_Model_Provincia();
		$c->setIdPais($this->getId());
		$c->setWhere(Db_Helper::equal('id_pais'));
		if($cp->searchCount()){
			return($cp->search());
		}
		return(null);
	}
}
?>