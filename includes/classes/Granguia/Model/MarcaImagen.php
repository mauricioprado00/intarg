<?
class Granguia_Model_MarcaImagen extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"marca",
			"imagen"
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'bm_marca_imagen';
	}
}
?>