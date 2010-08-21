<?
class Core_Router_Main extends Core_Router_Abstract{
//	public function onThrought(){
////		Core_App::getLayout()
////			->setModo('frontend')
////			->addDesignPaths('frontend', 'frontendv3/default/');
////		if(Granguia_User_Model_User::getLogedUser())
////			Core_App::getLayout()->addActions('loged_user');
//	}
	protected function initialize(){
		Core_App::getLayout()
			->setModo(Core_Http_Header::isAjaxRequest()&&!Core_Http_Header::getRequest('EMULATENOAJAX')?'ajax_mode':'granguia')
			->addDesignPaths('granguia', 'granguia/default/')
			->addDesignPaths('simple', 'granguia/default/')
			->addDesignPaths('ajax_mode', 'granguia/default/');
			
		//var_dump();
		//$route_data = Core_App::getInstance()->getConfig()->getRouteData();
		//$this->setRouteData($route_data);
		//$this->addActions('skin','contacto');
		//$this->addRouter('admin','Router.Admin');
	}
//	protected function contacto(){
//		Granguia_Helper::contactoCliente();
//	}
	protected function localDispatch(){//esto es la HOME
		/*
		el home con el loader cuando no es ajax
		*/
		if(!Core_Http_Header::isAjaxRequest()){
			Core_App::getLayout()->setActions('pageloader');
			return true;
		}
		
		static $count = 0;
		if($count++)
			return false;
		$nodo = Granguia_Model_Nodo::getHomeNodo();
		$path_url = $nodo->getPathUrl();//obtengo al redireccion interna al home
		$tipo_nodo = $nodo->getTipoNodo();
		if($tipo_nodo->esBarrio()){
			if($barrio = $nodo->getTypeInstance()){
				if($categoria_default = Granguia_Model_Categoria::getCategoriaDefault()){
					$path_url = $categoria_default->getLinkUrl($barrio);//url de barrio con la categoria pegada
				}
			}
		}
		return $this->route($path_url);
	}
	protected function dispatchNode(){
//		$nodo = new Granguia_Model_Nodo();
//		$nodo->setWhere(Db_Helper::like('path_url',null,null,null,true));
//		$nodo->setPathUrl($this->request_path);
//		$nodos = $nodo->search(null,'ASC',null,0,'Granguia_Model_Nodo');
//		if(!$nodos){
//			die("no encuentro nada en la linea: ".__LINE__);
//		}
//		$nodo = array_shift($nodos);
		$nodo = Granguia_Model_Nodo::getNodoFromPathUrl($this->request_path);
		if($nodo&&$nodo->getEsActiva()){
			if(Core_Http_Header::isAjaxRequest()){
				//sleep(6);
				if($pagina_anterior = Core_Http_Header::getRequest('pagina_anterior')){
					if(strpos($pagina_anterior, '#')!==false){
						$nodo_anterior = null;
						$link_url = array_pop( explode('#', $pagina_anterior) );
						if(!$link_url)
							$nodo_anterior = Granguia_Model_Nodo::getHomeNodo();
						else $nodo_anterior = Granguia_Model_Nodo::getNodoFromPathUrl($link_url);
						if($nodo_anterior){
							if($tipo_anterior = $nodo_anterior->getTipoNodo()){
								if($tipo_anterior = $tipo_anterior->getNombre()){
									$tipo_anterior = Core_String_Helper::getInstance()->sinAcentos(utf8_decode($tipo_anterior));
									$tipo_anterior = str_replace(' ', '_', strtoupper($tipo_anterior));
									//ECHO '//'.$tipo_anterior.'_TIPO_NODO_ANTERIOR';
									Core_App::getLayout()->addActions($tipo_anterior.'_TIPO_NODO_ANTERIOR');
								}
							}
						}
					}
				}
				$gg_click_counter = Core_Http_Header::getRequest('gg_click_counter');
				if(isset($gg_click_counter)){
					Core_App::getLayout()->addActions('GG_CLICK_COUNTER_EQ_'.($gg_click_counter+0));
				}
				if(Core_Http_Header::getRequest('screenblock')){
					Core_App::getLayout()->addActions('screenblock');
				}
			}
			Core_App::getInstance()->setNodo($nodo);
			$tipo = $nodo->getTipoNodo();
	//		Core_Http_Header::ContentType('text/plain');
			false&&$tipo = new Granguia_Model_TipoNodo();
			$clase = $tipo->getClaseControladora();
			$controller = new $clase;
			$controller->setInstancia($nodo);
			$coincidencia = array_shift($x = explode('%', $nodo->getPathUrl()));
			//var_dump($coincidencia,$this->request_path);die('conid'); 
			$resto = array_pop($x = explode($coincidencia, $this->request_path));
			$aResto = explode('/',$resto);
			while(isset($aResto[0])&&$aResto[0]=='')// me como los slashs path_url////algomas
				array_shift($aResto);
			$resto = implode('/', $aResto);
			$r = $controller->route($resto);
			if(!$r){
				Core_Http_Header::Redirect('', true);
				die("no rute2");
				//algo al home...
			}
		}
		else{
			Core_Http_Header::Redirect('', true);
			die("no rute3");
			//algo al home...
		}
		/* hasta aca llega al router, si continua es que paso el router */
		
		/*<estadisticas>*/
		Granguia_Model_Contador::ContarAccesoSesion(Core_Server::getRemoteAddr(), $this->request_path);
		if(Core_Session::getVarMulticontext('session_contada',array('contador_estadisticas'))==null){
			Core_Session::setVarMulticontext('session_contada','1',array('contador_estadisticas'));
			Granguia_Model_Contador::ContarInicioSesion(Core_Server::getRemoteAddr());	
		}
		if(in_array('barrio',Core_App::getLayout()->getActions())){
			$id_barrio = null;
			if($barrio = Core_App::getInstance()->getBarrio())
				$id_barrio = $barrio->getId();
			$id_categoria = null;
			if($categoria = Core_App::getInstance()->getCategoria())
				$id_categoria = $categoria->getId();
			Granguia_Model_Contador::ContarAccesoCategoria($id_categoria, $id_barrio);
		}
		/*</estadisticas>*/
		
		$nodo = Core_App::getInstance()->getNodo();//el nodo puede cambiar de generico a específico en el router
		$head = Core_App::getLoadedLayout()->getBlock('html_head');
		if($head){
			$titulo = $nodo->getMetaTitle($head->getTitle());
			$favicon = Granguia_Model_Config::findConfigValue('pagina/file_favicon');
			if(isset($favicon)){
				$head->setFavicon($favicon);
			}
			if($categoria = Core_App::getInstance()->getCategoria()){
				$titulo = $categoria->getNodo()->getMetaTitle($titulo);
			}
			$head->setTitle($titulo);
			$meta_description = Core_App::getLayout()->appendBlock(utf8_decode('<meta html_name="Description" before="" />'),$head);
			$meta_description
				->setHtmlContent($nodo->getMetaDescription('Guía de comercios de la ciudad de Córdoba'))
			;
			$meta_description = Core_App::getLayout()->appendBlock(utf8_decode('<meta html_name="keywords" before="" />'),$head);
			if($categoria){
				$meta_description
					->setHtmlContent($categoria->getNodo()->getMetaKeywords('ubicaciones de comercios, mapa comercios, comercios córdoba, listado comercios'))
				;
			}
			else{
				$meta_description
					->setHtmlContent($nodo->getMetaKeywords(''))
				;
			}
		}
		return true;
	}
	protected function skin(){
die();
		$c = func_get_args();
		$file = CFG_PATH_ROOT.CONF_PATH_APP.CONF_SUBPATH_SKIN.implode('/', $c);
		if(file_exists($file)){
			Core_Http_Header::ContentType(Core_File_Info::getMimeType($file));
			echo file_get_contents($file);
			die();
		}
		else{
			Core_Http_Header::Error(404);
//			Core_App::getLayout()
//				->setMainLayout('Page404.xml','error404')
//				->setModo('error404');
			$layout = new Base_Layout();
			$layout
				->addLayout('Page404.xml','error404')
				->setModo('error404');
			$layout->renderOutput();
			die();
		}
		//die();
	}
}
?>