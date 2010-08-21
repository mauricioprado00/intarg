<?
class Granguia_Model_BannerCarrousel extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"nombre",
			"title",
			"imagen_banner",
			"url_click",
			"es_activa",
			"orden",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function resetOrden(){
		$this->getDb()->open();
		$nombre_tabla = $this->getDb()->nameToString($this->getDbTableName());
		$nombre_campo = $this->getDb()->nameToString('orden');
		$valor = $this->getDb()->valueToString(3000);
		$sql = 'update '.$nombre_tabla.' set '.$nombre_campo.'='.$valor.';';
		$this->getDb()->exec($sql);
		$this->getDb()->close();
	}
	public function getDbTableName() 
	{
		return 'gg_banner_carrousel';
	}
}
?>