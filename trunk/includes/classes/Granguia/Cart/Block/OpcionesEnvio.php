<?
class Granguia_Cart_Block_OpcionesEnvio extends Core_Block_Template{
	public function __construct(){
		$this->setTemplate("cart/opciones_envio.phtml");
	}
	public function listarPaises(){
		$obj = new Granguia_Model_Pais();
		return($obj->search('nombre'));
	}
	public function listarProvincias($id_pais){
		if(!$id_pais){
			return(false);
		}
		$obj = new Granguia_Model_Provincia();
		$obj->setIdPais($id_pais);
		$obj->setWhere(Db_Helper::equal('id_pais'));
		return($obj->search('nombre'));
	}
	public function listarCiudades(){
		$obj = new Granguia_Model_Ciudad();
		return($obj->search('nombre'));
	}
	public function listarMetodosEnvio(){
		$obj = new Granguia_Model_MetodoEnvio();
		return($obj->search());
	}
	public function listarFormasPago(){
		$obj = new Granguia_Model_FormaPago();
		return($obj->search());
	}
	public function listarCodigosPostales($id_ciudad){
		if(!$id_ciudad){
			return(false);
		}
		$obj = new Granguia_Model_CiudadCodigoPostal();
		$obj->setIdCiudad($id_ciudad);
		$obj->setWhere(Db_Helper::equal('id_ciudad'));
		return($obj->search());
	}
}
?>