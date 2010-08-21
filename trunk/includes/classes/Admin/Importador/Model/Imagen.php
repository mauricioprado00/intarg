<?
class Admin_Importador_Model_Imagen extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"id_importacion",
			"name",
			"importar"
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function switch_importar(){
		if($this->load()){
			$this->setImportar( $this->getImportar()=='1'?'0':'1' );
			$this->replace();
		}
		return($this);
	}
	public function getDbTableName() 
	{
		return 'bm_imagen_importacion';
	}
}
?>