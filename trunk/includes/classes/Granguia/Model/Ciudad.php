<?
class Granguia_Model_Ciudad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"id_provincia",
			"nombre",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function addCodigoPostal($codigo_postal){
		$cp = new Granguia_Model_CiudadCodigoPostal();
		$cp->setCodigoPostal($codigo_postal);
		$cp->setIdCiudad($this->getId());
		$cp->setWhere(Db_Helper::equal('codigo_postal'), Db_Helper::equal('id_ciudad'));
		if(!$cp->searchCount()){
			$cp->replace();
			return($cp);
		}
		return(null);
	}
	public function getDbTableName() 
	{
		return 'bm_ciudad';
	}
	public function listarCodigosPostales(){
		$cp = new Granguia_Model_CiudadCodigoPostal();
		$cp->setIdCiudad($this->getId());
		$cp->setWhere(Db_Helper::equal('id_ciudad'));
		if($cp->searchCount()){
			return($cp->search());
		}
		return(null);
	}
}
?>