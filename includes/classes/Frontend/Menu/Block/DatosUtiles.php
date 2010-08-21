<?
class Frontend_Menu_Block_DatosUtiles extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('datos_utiles/datos_utiles.phtml');
	}
	public function getItemsMenu(){
		$datosUtiles = new Granguia_Model_Menu();
		$datosUtiles->setWhere(
			Db_Helper::equal('activo','1'), ' AND (',Db_Helper::equal('url_externa','',false),' or ',Db_Helper::equal('id_nodo',null,false),')'
		);
		return $datosUtiles->search('orden','asc',null,0,'Granguia_Model_Menu');
	}        
}
?>