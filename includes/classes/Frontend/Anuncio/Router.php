<?php /*es útf8*/
class Frontend_Anuncio_Router extends Core_Router_Abstract{
	protected function initialize(){
		$this->addActions('minisitio','contacto','contar');
	}
	protected function contar(){
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			if($post->hasIdAnuncio()){
				Granguia_Model_Contador::ContarClickAnuncio($post->getIdAnuncio());
			}
		}
		die();
	}
	protected function minisitio(){
		$anuncio = Core_App::getInstance()->getNodo()->getTypeInstance();
		if($anuncio->sitioExternoHabilitado()){
			Granguia_Model_Contador::ContarClickMinisitio($anuncio->getId(), $anuncio->getUrlMinisitio());
			Core_Http_Header::Redirect($anuncio->getUrlMinisitio());
			die();
		}
		if(!$anuncio->minisitioHabilitado())
			return false;
		Granguia_Model_Contador::ContarClickMinisitio($anuncio->getId());
		Core_App::getLayout()->setModo('simple')->addActions('anuncio_minisitio');
		//Core_App::getLayout()->setActions('simple', 'anuncio_minisitio');
		//Core_App::getLayout()->setActions('simple');
		Core_App::getInstance()->setAnuncio($anuncio);
		//echo $anuncio->getMinisitio();
		return true;
	}
	private function loadBarrio(){
		Core_App::getLayout()->addActions('barrio');
		if($c=Core_App::getLoadedLayout()->getBlock('mainmenu'))
			$c->setSelectedItem('btn_inicio');
		$block_mapa = Core_App::getLoadedLayout()->getBlock('mapa');
		if($block_mapa){
			$block_mapa
				->setBarrio(Core_App::getInstance()->getBarrio())
				->setCategoria(Core_App::getInstance()->getCategoria())
				//->setAnuncioDestacado(Core_App::getInstance()->getAnuncio())
			;
		}
		Core_App::getInstance()->setAnuncioDestacado(Core_App::getInstance()->getAnuncio());
	}
	protected function localDispatch(){
		return $this->dispatchAll();
	}
	private function dispatchAll(){//esto no es de Core_Router_Abstract, es un invento para procesar todo lo que no coincide en un solo lado
		static $count = 0;
		if($count++>0)
			return false;
		$request_path = $this->arr_request_path;
		$anuncio = new Granguia_Model_Anuncio();
		$anuncio = $this->getInstancia()->getTypeInstance();
		if(!$anuncio){//nodo generico de anuncio, no tiene instancia de anuncio asociada
			$id_nodo = array_shift($request_path);//consumimos el id de nodo del anuncio del request_path (puede continuar o no el nombre de categor�a)
			$nodo = new Granguia_Model_Nodo();
			$nodo->setId($id_nodo);
			if(!$nodo->load()){
				return false;
			}
			$tipo_nodo = $nodo->getTipoNodo();
			if(!$tipo_nodo->esAnuncio()){
				return false;
			}
			$anuncio = $nodo->getTypeInstance();
			$this->setInstancia($nodo);
			Core_App::getInstance()->setNodo($nodo);
		}
		if(!empty($request_path)){
			return $this->route(implode('/', $request_path));
		}
//		var_dump($anuncio->getIdBarrio());
//		var_dump($anuncio->getIdCategoria());
//		var_dump($anuncio->getId());
		$barrio = $anuncio->getBarrio();
		$categoria = $anuncio->getCategoria();
//		var_dump(get_class($barrio), get_class($categoria));
//		die();
		Core_App::getInstance()
			->setAnuncio($anuncio)
			->setBarrio($barrio)
			->setCategoria($categoria)
		;
		$this->loadBarrio();
		return true;
	}
	protected function dispatchNode(){
		return $this->dispatchAll();
	}
	private function showFormularioContacto(){
		$anuncio = Core_App::getInstance()->getAnuncio($anuncio);
		$contacto_action = $anuncio->esContactoBoliche()?'anuncio_contacto_boliche':'anuncio_contacto_normal';
		Core_App::getLayout()
			->addActions('anuncio_contacto', $contacto_action)
		;
		$layout = Core_App::getLoadedLayout();
		if($contacto_form = $layout->getBlock('contacto_form')){
			$contacto_form->setUrlAction($anuncio->getUrlContacto());
		}
		if($campos_form = $layout->getBlock('campos_form')){
			$strtr = array(
				'{!TEXTO_CONTACTO_ANUNCIO}'=>$anuncio->getTextoContacto()
			);
			$campos_form->setStrtr($strtr);
		}
	}
	protected function contacto(){
		$nodo = Core_App::getInstance()->getNodo();
		$anuncio = $nodo->getTypeInstance();
		Core_App::getInstance()
			->setAnuncio($anuncio)
		;
		$this->atenderPeticion();
		$this->showFormularioContacto();
		return true;
	}
	private function atenderPeticion(){
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			$mensaje = 'La firma es incorrecta';
			$fields = array();
			foreach($post->getData() as $key=>$value){
				if($key=='firma'){
					$fields = implode('-', $fields);
					$digest = $fields.Frontend_Contacto_Helper::getInstance()->getClaveEncriptacion();
					$firma = sha1($digest);
					if($firma==$post->getFirma()){
						$valido = Core_Capcha_Helper::validarPost('capcha_code');
						if($valido){
							$post = Core_Http_Post::getParameters('Core_Object');
							if(!$post->hasEmail() || Core_Helper::emailValido($post->getEmail())){
								Frontend_Anuncio_Helper::getInstance()->enviarEmailContacto($post);
								$mensaje = 'Gracias por comunicarse con nosotros.';
							}
							else{
								$mensaje = 'El email ingresado no es un email válido. Corrijalo.';
							}
						}
						else{
							$mensaje = 'El codigo es inválido';
						}
					}
					break;
				}
				$fields[] = $key;
			}
			echo json_encode(array('resultado'=>$valido,'mensaje'=>$mensaje));
			die();
		}
	}
}
?>