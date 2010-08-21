<?
class Admin_Importador_Model_BackupProducto extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"id_backup",
			"codigo",
			"nombre",
			"descripcion",
			"marca",
			"categoria",
			"rubro",
			"stock",
			"precio",
			"imagen",
			"modelos",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'bm_producto_backup';
	}
	
}
?>