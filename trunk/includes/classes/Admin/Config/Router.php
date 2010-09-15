<?
class Admin_Config_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'tools','backgrounds',
			'regenerar_cache_busqueda'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_config');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_config=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Config()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Configuraciones.');
		}
		else{
			if(isset($id_config)){
				Admin_Config_Helper::actionEliminarConfig($id_config);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_config=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Config()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Configuraciones.');
			//$mensajes[] = 'No tiene permitido editar configes.';
			$this->listar();
			//return;
		}
		else{
			if(Core_Http_Post::hasParameters()){
					$post = Core_Http_Post::getParameters('Core_Object');
					$nombre_imagen = $post->getValor();
					if(Core_Http_Files::countParameters()){
						if(strpos($post->getNombre(), 'file_')!==false)
							$post->setValor(Admin_Config_Helper::actionSubirNuevoArchivo());
						if(strpos($post->getNombre(), 'img_')!==false)
							$post->setValor(Admin_Config_Helper::actionSubirNuevaImagen());
					}
					if($post->hasValor())
					$guardado = 
						Admin_Config_Helper::actionAgregarEditarConfig($post)?true:false;
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_config_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_config');
				if(isset($id_config)){
					$add_edit_form = Core_App::getLoadedLayout()
							->getBlock('config_add_edit_form');
					if($add_edit_form){
						$add_edit_form->setIdToEdit($id_config);
					}
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_config');
		$this->cambiarUrlAjax('administrator/config/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_config');
	}
	protected function tools(){
		Core_App::getLayout()->addActions('config_tools');
	}
	protected function backgrounds(){
		if(Core_Http_Post::hasParameters()){
			$post_data = Core_Http_Post::getParameters('core_object');
			$archivos_subidos = null;
			if(Core_Http_Files::hasParameters())
				$archivos_subidos = Core_Http_Files::getParameters('core_object');

			//var_dump(Core_Http_Files::getParameters('core_object'));
//			var_dump(Core_Http_Post::getParameters('core_object'));
			$vars = array('color'=>'cpkr_', 'imagen'=>'img_', 'repetir_imagen'=>'chk_');
			foreach($post_data->getBackground() as $prefijo=>$data){
				foreach($vars as $var=>$varpref){
					switch($var){
						case 'imagen':{
							if(isset($archivos_subidos, $archivos_subidos['background'])){
								$names = $archivos_subidos['background']->getName();
								$tmp_names = $archivos_subidos['background']->getTmpName();
								if(isset($names[$prefijo]['nueva_imagen'])&&!empty($names[$prefijo]['nueva_imagen'])){
									$name = $names[$prefijo]['nueva_imagen'];
									$tmp_name = $tmp_names[$prefijo]['nueva_imagen'];
									
									$data[$var] = $new_value = Admin_Config_Helper::moveNewFile2($name, $tmp_name, 'img', false);
								}
							}
							break;
						}
					}
					if(isset($data[$var])){
						$varname = 'background/'.$prefijo.'/'.$varpref.$var;
						$value = $data[$var];
						$config = Inta_Model_Config::findConfig($varname);
						if(!isset($config)){
							$config = new Inta_Model_Config();
							$config->setNombre($varname);
						}
						$config->setValor($value);
						$config->replace();
					}
				}
			}
			$this->listar();
			return true;
		}
		else{
			Core_App::getLayout()->addActions('config_backgrounds');
			$vars = array('color'=>'cpkr_', 'imagen'=>'img_', 'repetir_imagen'=>'chk_');
			$posiciones = array('abajo', 'derecha');
			
			foreach($posiciones as $posicion){
				$background_abajo = Core_App::getLoadedLayout()
						->getBlock('background_'.$posicion);
				if($background_abajo){
					$varname_prefix = 'background/'.$posicion.'/';
					$imagen = Inta_Model_Config::findConfigValue($varname_prefix.'img_imagen');
					$color = Inta_Model_Config::findConfigValue($varname_prefix.'cpkr_color');
					$repetir = Inta_Model_Config::findConfigValue($varname_prefix.'chk_repetir_imagen');
					
					$x = new Core_Object();
					$x
						->setColorFondo($color)
						->setImagenFondo($imagen)
						->setRepetirImagen($repetir)
					;
					$background_abajo
						->setObjectToEdit($x)
						->setPrefijo($posicion)
					;
				}
			}
			
//			$background_derecha = Core_App::getLoadedLayout()
//					->getBlock('background_derecha');
//			if($background_derecha){
//				$background_derecha->setPrefijo('derecha');
//			}
		}
	}
	protected function regenerar_cache_busqueda(){
		$nodo = new Inta_Model_Nodo();
		$nodo->setEsActiva(1);
		$nodo->setWhere(Db_Helper::equal('es_activa'));
		$nodos = $nodo->search(null, 'ASC', null, 0, 'Inta_Model_Nodo');
		if($nodos){
			foreach($nodos as $nodo){
				$nodo->replace();
			}
		}
		echo utf8_encode("Generación de cache terminada");
		die();
	}
	protected function dispatchNode(){
		return;
	}
}
?>