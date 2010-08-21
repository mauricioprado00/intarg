<?
class Granguia_Catalog_Router_Catalog extends Core_Router_Abstract{
	private static $config;
	protected function initialize(){
		$this->addActions(
			'buscar',
			'producto',
			'completa_tu_moto',
			'protege_tu_cuerpo',
			'premium',
			'marca',
			'AdvancedSearch',
			'autocomplete',
			'continuar',
			'ver_talles'
		);
		self::$config = new Core_Object();
	}
	protected function ver_talles($search_key=''){
		$l = Core_App::getLayout(); 
		$l->setActions();
		$l->addActions('component','ver_talles');

		$producto = Granguia_Catalog_Model_Producto::findBySearchKey($search_key);
		if($producto){if(0)$producto= new Granguia_Catalog_Model_Producto();
			$talle_key = 'img_detalle_rubro_producto:'.$producto->getRubro();
			$talle = Granguia_Model_Config::findConfigValue($talle_key);
			if($talle){
				$vista_talles = Core_App::getInstance()
						->loadLayoutDom()
						->getLayout()
						->getBlock('vista_talles');
				$vista_talles->setImagenLink('img/'.$talle);
			}
		}


	}
	public static function esCompletaTuMoto(){return(self::$config && self::$config->getEsCompletaTuMoto());}
	public static function esProtegeTuCuerpo(){return(self::$config && self::$config->getEsProtegeTuCuerpo());}
	public static function esPremium(){return(self::$config && self::$config->getEsPremium());}
	public static function getCampoFiltro(){return(self::$config?self::$config->getCampoFiltro():false);}
	public static function getTextoFiltro(){return(self::$config?self::$config->getTextoFiltro():false);}
	public static function getEsFiltroDuro(){return(self::$config?self::$config->getHardFiltro():false);}
	
	public function continuar(){
		Granguia_Catalog_Helper::returnToLastSearch();
//		return;
//		Granguia_Catalog_Helper::restoreSearchParams();
//		$ret = $this->routeDelegate($this,$this->arr_request_path);
//		return($ret);
	}
	public static function getCurrentTabLinkUrl(){
		if(self::esPremium())
			return('catalogo/premium');
		elseif(self::esProtegeTuCuerpo())
			return('catalogo/protege_tu_cuerpo');
		elseif(self::esCompletaTuMoto())
			return('catalogo/completa_tu_moto');
	}
	private function searchPoint(){
		Granguia_Catalog_Helper::searchPoint($this->request_path);
	}
	protected function marca($texto_filtro=''){
		$this->searchPoint();
		if($texto_filtro)
			$this->setFiltro('marca', $texto_filtro, false);
		Core_App::getLayout()
			//->addActions('advanced_search_result')
			->addActions('navigation_search_result')
		;
	}
	protected function completa_tu_moto($campo_filtro='', $texto_filtro=''){
		$this->searchPoint();
		//$rubros = Granguia_Model_Config::findConfigValue('menu_rubros_completa_tu_moto');
		$rubros = Granguia_Model_Config::findConfigTextFiltered('text_menu_rubros_completa_tu_moto');
		$show_productos = Granguia_Model_Config::findConfigValue('chk_rubros_completa_tu_moto_show_productos');
		$this->setFiltro($campo_filtro, $texto_filtro);
		$this->showRubros($rubros, $show_productos?'producto':'marca');
		self::$config->setEsCompletaTuMoto(true);
		Core_App::getLayout()
			->addActions('tab_completa_tu_moto','navigation_controls')
		;
	}
	protected function protege_tu_cuerpo($campo_filtro='', $texto_filtro=''){
		$this->searchPoint();
		//$rubros = Granguia_Model_Config::findConfigValue('menu_rubros_protege_tu_cuerpo');
		$rubros = Granguia_Model_Config::findConfigTextFiltered('text_menu_rubros_protege_tu_cuerpo');
		$show_productos = Granguia_Model_Config::findConfigValue('chk_rubros_protege_tu_cuerpo_show_productos');
		$this->setFiltro($campo_filtro, $texto_filtro);
		$this->showRubros($rubros, $show_productos?'producto':'marca');
		self::$config->setEsProtegeTuCuerpo(true);
		Core_App::getLayout()
			->addActions('tab_protege_tu_cuerpo','navigation_controls')
		;
//		Core_App::getInstance()
//			->loadLayoutDom()
//		;
//		echo '<!-- '.Core_App::getLayout()->dump().' -->';
	}
	protected function premium($campo_filtro='', $texto_filtro=''){
		$this->searchPoint();
//		var_dump($post = Core_Http_Post::getParameters('array'));
//		die();
		//$rubros = Granguia_Model_Config::findConfigValue('menu_rubros_premium');
		$rubros = Granguia_Model_Config::findConfigTextFiltered('text_menu_rubros_premium');
		$show_productos = Granguia_Model_Config::findConfigValue('chk_rubros_premium_show_productos');
		$this->setFiltro($campo_filtro, $texto_filtro);
		$this->showRubros($rubros, $show_productos?'producto':'marca');
		self::$config->setEsPremium(true);
		Core_App::getLayout()
			->addActions('tab_premium','product_list_item_simple','navigation_controls')
		;
//		echo Core_App::getInstance()
//				->loadLayoutDom()->getLayout()->dump();
	}
	private function setFiltro($campo_filtro, $texto_filtro, $hard=true){
		if(!in_array($campo_filtro, array('marca')))
			return;
		self::$config->setCampoFiltro(urldecode($campo_filtro));
		self::$config->setTextoFiltro(urldecode($texto_filtro));
		self::$config->setHardFiltro($hard?true:false);
	}
	private function showRubros($rubros, $by='marca'){
		if(is_string($rubros)){
			$rubros = strtolower($rubros);
			$rubros = explode(',', $rubros);
			$new_rubros = array();
			foreach($rubros as $idx=>$rubro){
				if(trim($rubro))
					$new_rubros[] = trim($rubro);
			}
			$rubros = $new_rubros;
		}
		$by = self::$config->hasCampoFiltro() && self::$config->hasTextoFiltro()?'producto':$by;
		//if($by=='marca'&&Granguia_Catalog_Block_Search_AdvancedForm::getFiltros())
		if($by=='marca'&&Granguia_Catalog_Block_Navigation::getFiltros())
			$by = 'producto';
		if(count($rubros)){
			self::setRubros($rubros);
			if($by=='marca')
				Core_App::getLayout()
					->addActions('marcas_search_result')
				;
			else
				Core_App::getLayout()
					//->addActions('advanced_search_result')
					->addActions('navigation_search_result')
				;
		}
//		var_dump(Granguia_Catalog_Block_Search_AdvancedForm::getFiltros());
//		var_dump(Core_Http_Post::getParameters('array'));
//		echo Core_App::getInstance()
//				->loadLayoutDom()->getLayout()->dump();
	}
	private static $rubros = null;
	private static function setRubros($rubros){
		self::$rubros = $rubros;
	}
	public static function getRubros(){
		return(self::$rubros);
	}
	
	public function onThrought($force=false){
		static $called;if($called&&!$force)return($this);$called=true;
			Core_App::getLayout()
				->addActions('catalogo_default');
	}
	public function buscar(){
		Core_App::getLayout()
			->addActions('search_result');
	}
	public function producto($search_key,$popup=0){
		if($popup){
			Core_App::getLayout()
				->setActions('popup','catalogo_ver_producto');
		}
		else{
			Core_App::getLayout()
				->addActions('catalogo_ver_producto');
		}
		$product_view = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('product_view');
		if($product_view){
			$product_view
				->setSearchKey($search_key)
				->setIsPopup($popup?true:false)
			;
		}
	}
	public function AdvancedSearch(){
		$this->searchPoint();
		Core_App::getLayout()
			->addActions('catalogo_advanced')
		;
		if(Core_Http_Post::hasParameters()){
			Core_App::getLayout()
				->addActions('advanced_search_result')
			;
		}
	}
	public function autocomplete(){
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			if($post->hasCampo()&&$post->hasTexto()){
				$result = array();
				switch($post->getCampo()){
					case 'marca':{
						$marca = new Granguia_Catalog_Model_View_Marca();
						$marca->setWhere(Db_Helper::like('marca','%',$post->getTexto(),'%'));
						$marcas = $marca->search(null,'ASC',20);
						$valores_texto = array();
						foreach($marcas as $marca){
							$valores_texto[] = $marca->getNombre();
						}
						if(isset($valores_texto)&&is_array($valores_texto)&&count($valores_texto)){
							$result = $valores_texto;
						}
						break;
					}
					case 'categoria':{
						$categoria = new Granguia_Catalog_Model_View_Categoria();
						$categoria->setWhere(Db_Helper::like('categoria','%',$post->getTexto(),'%'));
						$categorias = $categoria->search(null,'ASC',20);
						$valores_texto = array();
						foreach($categorias as $categoria){
							$valores_texto[] = $categoria->getNombre();
						}
						if(isset($valores_texto)&&is_array($valores_texto)&&count($valores_texto)){
							$result = $valores_texto;
						}
						break;
					}
					case 'rubro':{
						$rubro = new Granguia_Catalog_Model_View_Rubro();
						$rubro->setWhere(Db_Helper::like('rubro','%',$post->getTexto(),'%'));
						$rubros = $rubro->search(null,'ASC',20);
						$valores_texto = array();
						foreach($rubros as $rubro){
							$valores_texto[] = $rubro->getNombre();
						}
						if(isset($valores_texto)&&is_array($valores_texto)&&count($valores_texto)){
							$result = $valores_texto;
						}
						break;
					}
					case 'modelos':{
						$producto = new Granguia_Catalog_Model_Producto();
						$producto->setWhere(Db_Helper::like('modelos','%',$post->getTexto(),'%'));
						$productos = $producto->search(null,'ASC',20);
						$valores_texto = array();
						foreach($productos as $producto){
							$modelos = explode(',', $producto->getModelos());
							foreach($modelos as $modelo)
								if(stripos($modelo, $post->getTexto())!==false && !in_array($modelo, $valores_texto))
									$valores_texto[] = $modelo;
							if(count($valores_texto)>=20)
								break;
						}
						if(isset($valores_texto)&&is_array($valores_texto)&&count($valores_texto)){
							$result = $valores_texto;
						}
						break;
					}
				}
				//$result = array('a'=>'sdfsdf','b'=>'sdfsdff');
				echo json_encode($result);
			}
		}
		die();
	}
	public function localDispatch(){
		//$this->listadoMarcas();
		$this->selectorTab();
		parent::localDispatch();
	}
	public function selectorTab(){
		Core_App::getLayout()
			->addActions('selector_tab')
		;
	}
	public function listadoMarcas(){
		Core_App::getLayout()
			->addActions('marcas_search_result')
		;
	}
	protected function dispatchNode($node){
		die($node);
		/*aca podriamos consultar a la base de datos para ver como esta asignado el nodo si es que existe*/
		switch($node){
			case 'caquita':{
				echo "yyyeee";
				return(true);
				break;
			}
		}
	}
}
?>