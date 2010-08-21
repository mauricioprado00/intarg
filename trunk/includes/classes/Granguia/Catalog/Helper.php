<?
class Granguia_Catalog_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function getLinkUrlTabCompletaTuMoto(){
		return('catalogo/completa_tu_moto');
	}
	public static function getLinkUrlTabProtegeTuCuerpo(){
		return('catalogo/protege_tu_cuerpo');
	}
	public static function getLinkUrlTabPremium(){
		return('catalogo/premium');
	}
	public static function getUrlVista360(){
		$url = Granguia_Model_Config::findConfigValue('url_vista_360');
		if(ereg('^/(.*)', $url, $reqs)){
			$url = Core_UrlModel::getUrl($reqs[1]);
		}
		return($url);
	}
	public static function getLinkUrlContinuarComprando(){
		return('catalogo/continuar');
	}
	public static function getFiltrarProductosVacios(){
		//return(false);	
		$filtrar = Granguia_Model_Config::findConfigValue('chk_listado_productos_filtrar_vacios');
		return(isset($filtrar)&&$filtrar?true:false);
	}
	public static function getAutocompleteOptions($campo){
		$return = null;
		$valores_texto = array();
		for($i=0; $i<10; $i++)
			$valores_texto['a'.$i] = utf8_encode('valor para "\'' . $campo. '\' nº' . $i);
		$return = $valores_texto;
		$varname = 'text_opciones_autocomplete_'.$campo;
		$configured = Granguia_Model_Config::findConfigValue($varname);
		if($configured){
			$valores = array();
			foreach(explode("\n", $configured) as $valor){
				$valor = trim($valor);
				if($valor==='')
					continue;
				if($valor[0]=='#')
					continue;
				$valores[] = $valor;
			}
//			Core_Http_Header::ContentType('text/plain');
//			var_dump($configured,$valores);
//			die();
			if(count($valores))
				return($valores);
		}
		switch($campo){
			case 'marca':{
				$marca = new Granguia_Catalog_Model_View_Marca();
				$marcas = $marca->search();
				$valores_texto = array();
				foreach($marcas as $marca){
					$valores_texto[] = $marca->getNombre();
				}
				if(isset($valores_texto)&&is_array($valores_texto)&&count($valores_texto)){
					$return = $valores_texto;
				}
				break;
			}
			case 'categoria':{
				$categoria = new Granguia_Catalog_Model_View_Categoria();
				$categorias = $categoria->search();
				$valores_texto = array();
				foreach($categorias as $categoria){
					$valores_texto[] = $categoria->getNombre();
				}
				if(isset($valores_texto)&&is_array($valores_texto)&&count($valores_texto)){
					$return = $valores_texto;
				}
				break;
			}
			case 'rubro':{
				$rubro = new Granguia_Catalog_Model_View_Rubro();
				$rubros = $rubro->search();
				$valores_texto = array();
				foreach($rubros as $rubro){
					$valores_texto[] = $rubro->getNombre();
				}
				if(isset($valores_texto)&&is_array($valores_texto)&&count($valores_texto)){
					$return = $valores_texto;
				}
				break;
			}
			case 'modelos':{
				$producto = new Granguia_Catalog_Model_Producto();
				$producto->setModelos('');
				$producto->setWhere(Db_Helper::distinct('modelos'));
				$productos = $producto->search();
				$valores_texto = array();
				foreach($productos as $producto){
					$arr_modelos = explode(',', $producto->getModelos());
					foreach($arr_modelos as $modelo){
						$modelo = trim($modelo);
						if(!in_array($modelo, $valores_texto))
							$valores_texto[] = $modelo;
					} 
					if(count($valores_texto)>5)
						break;
				}
				if(isset($valores_texto)&&is_array($valores_texto)&&count($valores_texto)){
					$return = $valores_texto;
				}
				break;
			}
		}
		return($return);
	}
	private static function getSessionContext(){
		return(array('catalogo'));
	}
	public static function searchPoint($path){
		if(self::getRestoreSearchParams()){
			self::restoreSearchParams();
		}
		else{
			self::saveSearch($path);
		}
	}
	public static function saveSearch($path){
		//guardo el path
		Core_Session::setVarMulticontext('last_search', $path, self::getSessionContext());
		//guardo el post
		$post = Core_Http_Post::getParameters('Core_Object');
		Core_Session::setVarMulticontext('last_search_params', $post, self::getSessionContext());
	}
	public static function restoreSearchParams(){
		$arr = Core_Session::getVarMulticontext('last_search_params', self::getSessionContext());
		Core_Http_Post::replaceParameters($arr);
	}
	public static function getLastSearch(){
		//solo del path
		return Core_Session::getVarMulticontext('last_search', self::getSessionContext());
	}
	public static function setRestoreSearchParams($bool=true){
		Core_Session::setVarMulticontext('restore_search_params', $bool?true:false, self::getSessionContext());
	}
	public static function getRestoreSearchParams($down=true){
		$val = Core_Session::getVarMulticontext('restore_search_params', self::getSessionContext());
		if($val&&$down)
			self::setRestoreSearchParams(false);
		return $val;
	}
	public static function returnToLastSearch(){
		self::setRestoreSearchParams();
		$last_search = self::getLastSearch();
		if(isset($last_search)){
			$url = 'catalogo/'.$last_search;
		}
		else $url = 'catalogo';
		Core_Http_Header::Redirect(Core_App::getUrlModel()->getUrl($url));
		die();
	}
	public static function returnToLastSearch2(){
		$last_search = self::getLastSearch();
		if(isset($last_search)){
			$url = 'catalogo/continuar/'.$last_search;
		}
		else $url = 'catalogo/continuar';
		Core_Http_Header::Redirect(Core_App::getUrlModel()->getUrl($url));
		die();
	}
}
?>