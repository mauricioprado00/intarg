<?
class Admin_Anuncio_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_anuncio');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_anuncio=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Anuncio()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Anuncio.');
		}
		else{
			if(isset($id_anuncio)){
				Admin_Anuncio_Helper::actionEliminarAnuncio($id_anuncio);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_anuncio=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Anuncio()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Anuncio.');
			//$mensajes[] = 'No tiene permitido editar anuncioes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_anuncio = $post&&$post->hasAnuncio()?$post->GetAnuncio(true):null;
			$post_horario_anuncio = $post&&$post->hasHorario()?$post->GetHorario(true):null;
			$post_nodo = $post&&$post->hasNodo()?$post->GetNodo(true):null;
			if($post_nodo){
				//insertar el nodo
			}
			else{
				$id_nodo = null;
			}
			$horario_anuncio = new Granguia_Model_HorarioAnuncio();
			$horario_anuncio->addAutofilterOutput('utf8_decode');
			$anuncio = new Granguia_Model_Anuncio();
			Core_App::getUrlModel()->autofilterFieldInput($anuncio, 'minisitio');
			$anuncio->addAutofilterOutput('utf8_decode');
			Core_App::getUrlModel()->autofilterFieldOutput($anuncio, 'minisitio');
			$nodo = Granguia_Model_Anuncio::NewNodo();//new Granguia_Model_Nodo();
			//$nodo = new Granguia_Model_Nodo();
			if(isset($post_anuncio)){
				$guardado_nodo = true;
				$anuncio->loadFromArray($post->GetAnuncio(true)->getData());
				if(isset($post_nodo)){
					$nodo->loadFromArray($post_nodo->getData());
					//$id_nodo = $nodo->getId();//para permitir el setTypeInstance (edicion)
					//$anuncio->setIdNodo($id_nodo);//para permitir el setTypeInstance (edicion)
					$nodo->setTypeInstance($anuncio, true);
					$guardado_nodo = Admin_Nodo_Helper::actionAgregarEditarNodo($nodo, $post_nodo);
					if($guardado_nodo){
						//$id_nodo = $nodo->getId();//el nodo es nuevo (alta)
						//$anuncio->setIdNodo($id_nodo);//se hace en afterReplace de nodo
						//$anuncio->setNodo($nodo);//se hace en afterReplace de nodo
					}
				}
//				if(isset($id_nodo))
//					$post->setIdNodo($id_nodo);
				$guardado_anuncio = Admin_Anuncio_Helper::actionAgregarEditarAnuncio($anuncio)?true:false;
				$horario_anuncio->loadFromArray($post->GetHorario(true)->getData());
				if($guardado_anuncio){
					$horario_anuncio->setIdAnuncio($anuncio->getId());
					$guardado_horario = Admin_Anuncio_Helper::actionAgregarEditarHorarioAnuncio($horario_anuncio)?true:false;
				}
				else $guardado_horario = false;
				$guardado = $guardado_anuncio && $guardado_nodo && $guardado_horario;
//				$guardado = false;
//				Core_App::getInstance()->appendLastErrorMessages('No quiero darte el ok');
			}
			else{
				if(isset($id_anuncio)){
					$anuncio->setId($id_anuncio);
					$anuncio->load();
					if($horario = $anuncio->getHorarioAnuncio()){
						$horario_anuncio->loadFromArray($horario->getData());
						foreach($horario_anuncio->getData() as $key=>$value){
							$horario_anuncio->setData($key, substr($value, 0, 5));
						}
					}
					if($anuncio->hasIdNodo()&&($id_nodo = $anuncio->getIdNodo())){
						$nodo->setId($id_nodo);
						if(!$nodo->load()){
							$nodo = Granguia_Model_Anuncio::NewNodo();
							Admin_App::getInstance()->addWarningMessage('Error durante la carga del nodo asociado');
							echo Core_Helper::DebugVars($nodo);
						}
					}
					else{
						Admin_App::getInstance()->addInfoMessage('El anuncio actualmente no posee nodo.');
					}
				}
			}
			//echo Core_Helper::DebugVars($nodo->getData());
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_anuncio_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_anuncio');
				$layout = Core_App::getLoadedLayout();
				
//				$x = new Granguia_Model_Anuncio();
//				if(isset($id_anuncio)){
//					$x->setId($id_anuncio);
//					$x->load();
//				}
//				else{
//					if($post){//si ya hizo post (cuando no se agrego/modifico y vuelve con errores)
//						$x->loadFromArray($post->getAnuncio());
//					}
//				}
				//$anuncio->addAutofilterOutput('utf8_decode');
				$tipo = new Granguia_Model_TipoContacto();
				$tipos = $tipo->search();
				$opciones = Granguia_Model_Anuncio::OpcionesMinisitio();
				$barrio = new Granguia_Model_Barrio();
				$barrios = $barrio->search('nombre');
				$tipo_punto = new Granguia_Model_TipoPunto();
				$tipos_puntos = $tipo_punto->search('nombre');
				$estados = Granguia_Model_Anuncio::OpcionesEstado();

				foreach($layout->getBlocks('anuncio_add_edit_form') as $block){
					$block->setIdToEdit($anuncio->getId());
					if($tipos)
						$block->setTiposContactos($tipos);
					if($opciones)
						$block->setOpcionesMinisitio($opciones);
					if($barrios)
						$block->setBarrios($barrios);
					if($tipos_puntos)
						$block->setTiposPuntos($tipos_puntos);
					if($estados)
						$block->setOpcionesEstado($estados);
					$block->setObjectToEdit($anuncio);
				}

				$nodo->addAutofilterOutput('utf8_decode');
				
				$tipo_nodo = new Granguia_Model_TipoNodo();
				$tipos_nodos = $tipo_nodo->search('nombre','DESC');
				foreach($layout->getBlocks('nodo_add_edit_form') as $block){
					if($nodo&&$nodo->hasId())
						$block->setIdToEdit($nodo->getId());
					//if(isset($id_nodo))
//						$block->setIdToEdit($id_nodo);
					if($tipos_nodos)
						$block->setTiposNodos($tipos_nodos);
					$block->setObjectToEdit($nodo);
				}
				
				$horario = $horario_anuncio;
				if(!isset($horario)){
					$horario = new Core_Object();
				}
				foreach($layout->getBlocks('horario_anuncio_add_edit_form') as $block){
					$block->setObjectToEdit($horario);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_anuncio');
		$this->cambiarUrlAjax('administrator/anuncio/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_anuncio');
	}
	protected function dispatchNode(){
		return;
	}
}
?>