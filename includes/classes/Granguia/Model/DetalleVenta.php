<?
class Granguia_Model_DetalleVenta extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"id_venta",
			"codigo",
			"nombre",
			"descripcion",
			"marca",
			"categoria",
			"rubro",
			"cantidad",
			"precio",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'bm_detalle_venta';
	}
}
?>