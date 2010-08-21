<?

class Admin_Importador_Router_Importador extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		//$this->addActions('ValidarUsuario');
		//$this->addActions('cerrar_sesion');
		//$this->addActions('addEdit');
		$this->addActions(
			'delete',
			'seleccionar', 'procesar', 
			'list_productos', 'datalist_productos', 'switch_importar_producto', 'editar_producto', 
			'listar', 
			'list_imagenes', 'datalist_imagenes', 'switch_importar_imagen', 
			'list_contenido',
			'utilizar','noutilizar',
			'paso1', 'paso2',
			'list_imagenes_json',
			'dump_image'
			);
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	public function procesar(){
		Admin_Importador_Helper_Importador::getInstance()->importarArchivos();
		Core_App::getLayout()->addActions('entity_list', 'list_admin_importacion');
		$helper_url_ajax = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl('administrator/importar/listar'));
		}
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_importador');
	}
	protected function seleccionar(){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Admin_Importador_Model_Importacion()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('show_message');
			$mensajes = 
				Core_App::getInstance()
					->loadLayoutDom()
					->getLayout()
					->getBlock('contenedor_mensajes');
			Core_App::getLayout()->addActions('security_restriction');
			$mensajes->addSuccessMessage(htmlentities('No tiene permitido esta operación'));
			return;
		}
		Core_App::getLayout()->addActions('entity_addedit', 'select_upload_import_file');
		
		
	}
	protected function delete($id_importacion=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Admin_Importador_Model_Importacion()), 'd');
		$mensajes = array();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			$mensajes[] = 'No tiene permitido borrar archivos importados.';
		}
		else{
			if(isset($id_importacion)){
				if(	$borrado = Admin_Importador_Helper_Importador::eliminarArchivoImportado($id_importacion) )
					$mensajes[] = "Archivo eliminado correctamente";
				else $mensajes[] = "No se pudo eliminar el archivo importado";
			}
		}
		$this->listar();
	}
	protected function editar_producto($id_producto=null, $id_importacion=null, $pagina=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Admin_Importador_Model_Importacion()), 'w');
		$mensajes = array();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			$mensajes[] = 'No tiene permitido modificar los datos de importación.';
			$this->list_productos($id_importacion, $pagina);
		}
		else{
			$this->_editar_producto($id_producto, $id_importacion, $pagina);
		}
	}
	protected function _editar_producto($id_producto=null, $id_importacion=null, $pagina=null){
		if(isset(Core_Http_Post::getParameters('object')->id)){//ya cargo el formulario
			$post = Core_Http_Post::getParameters('Core_Object');
			Admin_Importador_Helper_Importador::actionEditarProducto($post);
			if($post->hasIdImportacionRequired()&&$post->getIdImportacionRequired()){
				$this->_utilizar($post->getIdImportacionRequired());
			}
			$this->list_productos($post->getIdImportacion(), $post->getPagina());
		}
		else{
			$actions = array('entity_addedit', 'addedit_admin_importacion_producto');
			call_user_method_array('addActions', Core_App::getLayout(),$actions);
			//$args = func_get_args();
			if(isset($id_producto)){
				$add_edit_form = 
					Core_App::getInstance()
						->loadLayoutDom()
						->getLayout()
						->getBlock('producto_importacion_add_edit_form');
				if($add_edit_form){
					if(isset($id_producto))
						$add_edit_form->setIdToEdit($id_producto);
					if(isset($id_importacion))
						$add_edit_form->setIdImportacion($id_importacion);
					if(isset($pagina))
						$add_edit_form->setPagina($pagina);
				}
			}
		}
	}
	protected function addEdit(){
		if(isset(Core_Http_Post::getParameters('object')->id)){//ya cargo el formulario
			$actions = array('entity_addedit_action', 'addedit_admin_user_action');
			call_user_method_array('addActions', Core_App::getLayout(),$actions);
			Core_App::getLayout()->addActions('entity_list', 'list_admin_user');//para que muestre la lista nueva;
			$helper_url_ajax = 
				Core_App::getInstance()
					->loadLayoutDom()
					->getLayout()
					->getBlock('helper_url_ajax');
			if($helper_url_ajax){
				$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl('administrator/user/list'));
			}
			Admin_User_Model_User::addEdit(Core_Http_Post::getParameters('object'));
		}
		else{
			$actions = array('entity_addedit', 'addedit_admin_user');
			call_user_method_array('addActions', Core_App::getLayout(),$actions);
			$args = func_get_args();
			if(count($args)){
				$add_edit_form = 
					Core_App::getInstance()
						->loadLayoutDom()
						->getLayout()
						->getBlock('user_add_edit_form');
				if($add_edit_form){
					$add_edit_form->setIdToEdit($args[0]);
				}
			}
		}
		return(true);
	}
	public function list_productos($id_importacion, $pagina=1){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_importacion_producto');
		$grilla = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('listado_datos_grid');
		$grilla
			->setIdImportacion($id_importacion)
			->setSource($grilla->getSource().'/'.$id_importacion)
			->setPage($pagina)
		;
		$helper_url_ajax = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl('administrator/importar/list_productos/'.$id_importacion));
		}
	}
	public function datalist_productos($id_importacion){
		Core_App::getLayout()->setActions(array());
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_importacion_producto');
		$xml_data = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('xml_data_admin_importador_producto');
		$xml_data->setIdImportacion($id_importacion);
	}
	public function list_contenido($id_importacion){
		$importacion = new Admin_Importador_Model_View_Importacion2();
		$importacion->setId($id_importacion);
		if($importacion->load()){
			if($importacion->getData("cantidad_imagenes_importadas")>0){
				$this->list_imagenes($id_importacion);
			}
			if($importacion->getData('cantidad_productos_importados')>0){
				$this->list_productos($id_importacion);
			}
		}
	}
	public function list_imagenes($id_importacion, $pagina=1){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_importacion_imagen');
		$grilla = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('listado_datos_grid');
		$grilla
			->setIdImportacion($id_importacion)
			->setSource($grilla->getSource().'/'.$id_importacion)
			->setPage($pagina)
		;
		$helper_url_ajax = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl('administrator/importar/list_imagenes/'.$id_importacion));
		}

	}
	public function datalist_imagenes($id_importacion){
		Core_App::getLayout()->setActions(array());
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_importacion_imagen');
		$xml_data = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('xml_data_admin_importador_imagen');
		$xml_data->setIdImportacion($id_importacion);
	}
	public function switch_importar_producto($id_producto, $id_importacion=0, $pagina=1){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Admin_Importador_Model_Importacion()), 'w');
		$mensajes = array();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido modificar los datos de importación.');
		}
		else{
			$producto = new Admin_Importador_Model_Producto();
			$producto->setId($id_producto);
			$producto->switch_importar();
		}
		$this->list_productos($id_importacion, $pagina);
	}
	public function switch_importar_imagen($id_imagen, $id_importacion=0, $pagina=1){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Admin_Importador_Model_Importacion()), 'w');
		$mensajes = array();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido modificar los datos de importación.');
		}
		else{
			$imagen = new Admin_Importador_Model_Imagen();
			$imagen->setId($id_imagen);
			$imagen->switch_importar();
		}
		$this->list_imagenes($id_importacion, $pagina);
	}
	protected function listar($pagina=1){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_importacion');
		$grilla = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('listado_datos_grid');
		$grilla->setPage($pagina);
		$helper_url_ajax = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl('administrator/importar/listar'));
		}
	}
	private function addImportacionImagenesDesde($id_importacion){
		$desde = $this->getImportacionImagenesDesde();
		if(!$desde)
			$desde = array();
		if(!in_array($id_importacion, $desde)){
			$desde[] = $id_importacion;
			$this->setImportacionImagenesDesde($desde);
		}
	}
	private function removeImportacionImagenesDesde($id_importacion){
		$desde = $this->getImportacionImagenesDesde();
		if(!$desde)
			return;
		if(!in_array($id_importacion, $desde))
			return;
		array_splice($desde, array_search($id_importacion, $desde), 1);
		$this->setImportacionImagenesDesde($desde);
	}
	private function setImportacionImagenesDesde($arr_ids_importacion){
		Core_Session::setVar('importacion_imagenes_desde', $arr_ids_importacion, 'variable_de_clase_'.__CLASS__);
	}
	private function getImportacionImagenesDesde(){
		return(Core_Session::getVar('importacion_imagenes_desde', 'variable_de_clase_'.__CLASS__));
	}
	private function removeImportacionProductosDesde($id_importacion){
		if($this->getImportacionProductosDesde()==$id_importacion){
			$this->setImportacionProductosDesde(null);
		}
	}
	private function setImportacionProductosDesde($id_importacion){
		Core_Session::setVar('importacion_productos_desde', $id_importacion, 'variable_de_clase_'.__CLASS__);
	}
	private function getImportacionProductosDesde(){
		return(Core_Session::getVar('importacion_productos_desde', 'variable_de_clase_'.__CLASS__));
	}
	public function utilizar($id_importacion){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Admin_Importador_Model_Importacion()), 'w');
		$mensajes = array();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido realizar el proceso de importacion');
			$this->list_contenido($id_importacion);
		}
		else{
			if($this->_utilizar($id_importacion))
				$this->paso1();
		}
	}
	private function _utilizar($id_importacion){
		$importacion = new Admin_Importador_Model_View_Importacion2();
		$importacion->setId($id_importacion);
		if($importacion->load()){
			if($importacion->getData("cantidad_imagenes_importadas")>0){
				$this->addImportacionImagenesDesde($id_importacion);
			}
			if($importacion->getData('cantidad_productos_importados')>0){
				$this->setImportacionProductosDesde($id_importacion);
			}
			return(true);
		}
		return(false);
	}
	public function noutilizar($id_importacion){
		$importacion = new Admin_Importador_Model_View_Importacion2();
		$importacion->setId($id_importacion);
		if($importacion->load()){
			if($importacion->getData("cantidad_imagenes_importadas")>0){
				$this->removeImportacionImagenesDesde($id_importacion);
			}
			if($importacion->getData('cantidad_productos_importados')>0){
				$this->removeImportacionProductosDesde($id_importacion);
			}
			$this->paso1();
		}
	}
	public function paso1(){
		Core_App::getLayout()->addActions('importador_paso1');
		$importador_paso1 = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('importador_paso1');
		$importador_paso1->setImportacionProductosDesde($this->getImportacionProductosDesde());
		$importador_paso1->setImportacionImagenesDesde($this->getImportacionImagenesDesde());
		$this->cambiarUrlAjax('administrator/importar/paso1');
	}
	public function paso2(){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Admin_Importador_Model_Importacion()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido realizar el proceso de importación');
			return;
		}

		$maximo_tiempo_seg = 60 * 60 * 3;//3 horas 
		ini_set("max_execution_time",$maximo_tiempo_seg+5);

		Core_App::getLayout()->addActions('importador_paso2');
		$post = Core_Http_Post::getParameters('Core_Object');
		$omitir_ids = array();
		$omitir_producto_invalido = $post->getOmitirProductoInvalido();
		if(isset($omitir_producto_invalido)&&is_array($omitir_producto_invalido)&&count($omitir_producto_invalido)){
			foreach($omitir_producto_invalido as $id_producto=>$on)
				$omitir_ids[] = $id_producto;
		}
		
		$ret = Admin_Importador_Helper_Importador::importacionAProduccion(
			$post->getIdImportacionImagenes(), 
			$post->getIdImportacionProductos(), 
			$post->getMakeBackup(), 
			$omitir_ids
		);
		if($ret){
			$this->setImportacionImagenesDesde(null);
			$this->setImportacionProductosDesde(null);
		}
		$importador_paso2 = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('importador_paso2')
				->setResultadoImportacion($ret);
		$this->cambiarUrlAjax('administrator/importar/paso2');
	}
	protected function dispatchNode(){
		$actions = array(
			'list'=>array(
				'reset'=>false,
				'actions'=>array('entity_list', 'list_admin_importacion'),
			),
			'datalist'=>array(
				'reset'=>true,
				'actions'=>array('datalist', 'datalist_admin_importacion'),
			),
		);
		$args = func_get_args();
		if(!count($args)||!isset($actions[$args[0]]))
			return;
		$d = $actions[$args[0]];
		if($d['reset']){
			//var_dump($d);
			Core_App::getLayout()->setActions(array());
		}
		call_user_method_array('addActions', Core_App::getLayout(),$d['actions']);
		return(true);
	}
	protected function list_imagenes_json($id_importacion){
		$imagen = new Admin_Importador_Model_Imagen();
		$imagen->setIdImportacion($id_importacion);
		$imagen->setWhere(Db_Helper::equal('id_importacion'));
		echo json_encode($imagen->search(null, 'ASC', null, 0, false,array('id','name')));
		die();
	}
	protected function dump_image($id){
		Admin_Importador_Helper_Importador::dumpImage($id);
		die();
	}
}
?>