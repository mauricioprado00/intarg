<?
class Granguia_Catalog_Block_Navigation extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("catalog/search/navigation.phtml")
		;
	}
	protected function _beforeToHtml(){
		$data = null;
		if(Core_Http_Post::hasParameters()){
			$data = Core_Http_Post::getParameters('Core_Object');
			Core_Session::setVar('last_search', $data, __CLASS__);
		}
		else{
			$data = Core_Session::getVar('last_search', __CLASS__);
		}
		$this->setSearchInformation($data);
	}
	protected static function getCamposOrdenables(){
		return(Granguia_Catalog_Model_Producto::getCamposOrdenables());
	}
	protected static function getCamposBuscables(){
		return(Granguia_Catalog_Model_Producto::getCamposBuscables());
	}
	protected static function isCampoBusquedaValido($campo){
		$campos_validos = array('');
		$campos_buscables = self::getCamposBuscables();
		if(isset($campos_buscables) && $campos_buscables) 
			foreach($campos_buscables as $campo_buscable)
				if(!in_array($campo_buscable->getNombre(), $campos_validos))
					$campos_validos[] = $campo_buscable->getNombre();
		return(isset($campo)&&in_array($campo, $campos_validos));
	}
	protected static function getArrayRubros(){
		$rubros = Granguia_Catalog_Router_Catalog::getRubros();
		if($rubros==null){
			$rubro = new Granguia_Catalog_Model_View_Rubro();
			$rubro->setWhere(Db_Helper::distinct('rubro',''));
			$ret = $rubro->search('nombre');
			if(!$ret)
				return null;
			$rubros = array();
			foreach($ret as $rubro){
				$rubros[] = $rubro->getNombre();
			}
		}
		return $rubros;
	}
	protected static function getArrayMarcas(){
		$marca = new Granguia_Catalog_Model_View_Marca();
		$rubros = Granguia_Catalog_Router_Catalog::getRubros();
		$where = array();
		if($rubros){
			$where[] = Db_Helper::in('rubro', true, $rubros);
		}
		$where[] = Db_Helper::distinct('marca','');
		$marca->setWhereByArray($where);
		$ret = $marca->search('nombre');
		if(!$ret)
			return null;
		$marcas = array();
		foreach($ret as $marca){
			$marcas[] = $marca->getNombre();
		}
		return $marcas;
	}
	protected static function getSelectedMarca(){
		if(!Core_Http_Post::hasParameters())
			return null;
		$post = Core_Http_Post::getParameters('Core_Object');
		if(!$post->hasMarca()){
			return null;
		}
		return($post->getMarca());
	}
	protected static function getSelectedRubro(){
		if(!Core_Http_Post::hasParameters())
			return null;
		$post = Core_Http_Post::getParameters('Core_Object');
		if(!$post->hasRubro()){
			return null;
		}
		return($post->getRubro());
	}
	protected static function getTextoBusqueda(){
		if(!Core_Http_Post::hasParameters())
			return null;
		$post = Core_Http_Post::getParameters('Core_Object');
		if(!$post->hasTextoBusqueda()){
			return null;
		}
		return($post->getTextoBusqueda());
	}
	public static function getFiltros(){
		$filtros = array();
		$marca = self::getSelectedMarca();
		if($marca){
			$filtros[] = array('campo_busqueda'=>'marca','texto_busqueda'=>$marca);
		}
		$rubro = self::getSelectedRubro();
		if($rubro){
			$filtros[] = array('campo_busqueda'=>'rubro','texto_busqueda'=>$rubro);
		}
		if($texto_busqueda = self::getTextoBusqueda()){
			$filtros[] = array('campo_busqueda'=>'nombre|descripcion','texto_busqueda'=>$texto_busqueda);
		}
		return($filtros);
	}
	
}
?>