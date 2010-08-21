<?
class Granguia_Catalog_Block_Search_AdvancedForm extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("catalog/search/advanced_form.phtml")
			->setMaxFiltros(3)
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
	protected function getMaxAutocompleteOptions(){
		$value = Granguia_Model_Config::findConfigValue('int_opciones_autocomplete_cantidad');
		return($value?$value:6);
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
	private static function _getFiltrosFromPost(){
		if(!Core_Http_Post::hasParameters())
			return(null);
		$post = Core_Http_Post::getParameters('Core_Object');
		if(!$post->hasFiltros())
			return(null);
		$filtros = $post->getFiltros();
		if(!count($filtros))
			return(null);
		return($filtros);
	}
	private static function _getNuevoFiltro(){
		if(!Core_Http_Post::hasParameters())
			return(null);
		$post = Core_Http_Post::getParameters('Core_Object');
		if(!$post->hasTextoBusqueda()||!$post->hasCampoBusqueda())
			return(null);
		$texto_busqueda = trim($post->getTextoBusqueda());
		$campo_busqueda = $post->getCampoBusqueda();
		//var_dump(self::isCampoBusquedaValido($campo_busqueda));
		if(!$texto_busqueda||!self::isCampoBusquedaValido($campo_busqueda))
			return(null);
		return(
			array(
				'campo_busqueda'=>$campo_busqueda, 
				'texto_busqueda'=>$texto_busqueda
			)
		);
		
	}
	public static function getFiltroAdicional(){
		if(
			($campo_filtro = Granguia_Catalog_Router_Catalog::getCampoFiltro()) &&
			($texto_filtro = Granguia_Catalog_Router_Catalog::getTextoFiltro())
			){
			return(
				array(
					'campo_busqueda'=>$campo_filtro, 
					'texto_busqueda'=>$texto_filtro
				)
			);
		}
		return(false);
	}
	public static function getFiltros(){
		$fuente_filtros = array(
			self::_getFiltrosFromPost(),
			array(self::_getNuevoFiltro()),
		);
		if(!Granguia_Catalog_Router_Catalog::getEsFiltroDuro()){
			$filtro_adicional = self::getFiltroAdicional();
			if($filtro_adicional)
				$fuente_filtros[] = array($filtro_adicional);
		}

		
//		Core_Http_Header::ContentType('text/plain');
//		var_dump($fuente_filtros);
//		die();
		
		if(!isset($fuente_filtros)||!count($fuente_filtros))
			return(null);
		$resultado = array();
		foreach($fuente_filtros as $fuente_filtro){
			if(!isset($fuente_filtro)||!is_array($fuente_filtro)||!count($fuente_filtro))
				continue;
			$fuente_filtro = array_values($fuente_filtro);
			if(!$fuente_filtro[0])
				continue;
			$resultado = array_merge($resultado, $fuente_filtro);
		}
		if(!count($resultado))
			return(null);
		return($resultado);
	}
	public static function isCampoBuscableAutocomplete($campo){
		$default_values = array(
			'codigo'=>false,
			'descripcion'=>false,
			'marca'=>true,
			'categoria'=>true,
			'rubro'=>true,
			'modelos'=>true,
		);
		$nombre_variable = 'chk_buscador_autocompletar_campo_'.$campo;
		$valor_variable = Granguia_Model_Config::findConfigValue($nombre_variable);
		if($valor_variable!=null){
			return($valor_variable?true:false);
		}
		if(!isset($default_values[$campo]))
			return(false);
		return($default_values[$campo]?true:false);
		
	}
}
?>