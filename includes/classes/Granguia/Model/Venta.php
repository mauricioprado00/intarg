<?
class Granguia_Model_Venta extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"id_usuario",
			"ciudad",
			"codigo_postal",
			"metodo_envio",
			"forma_pago",
			"estado",
			"fecha",
			"es_dineromail",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'bm_venta';
	}
}
?>