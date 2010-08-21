<?
class Granguia_Cart_Router_Cart extends Core_Router_Abstract{
	protected function initialize(){
		$this->addActions(
			'agregar',
			'ver',
			'codigos_postales',
			'compra',
			'ciudadesByProvincia',
			'provinciasByPais',
//			'generarPedido',
			'dineromail'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('catalogo_default');
	}
	public function agregar($product_search_key, $cantidad=1){
//		var_dump(Core_Http_Post::getParameters('Core_Object'));
//		die("kradkk");
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			if($post->hasCantidad()){
				$cantidad = $post->getCantidad()+0;
				$cantidad = $cantidad>0?$cantidad:1;
			}
		}
		$x = new Granguia_User_Model_User();
//		if(!$x->isLoged()){
//			Core_Http_Header::Redirect(Core_App::getUrlModel()->getUrl(''));
//		}
		$cantidad_agregada = Granguia_Cart_Helper::actionAgregarProductoACarro($product_search_key, $cantidad);
		$this->ver();
		$mensaje = null;
		if($cantidad_agregada>0||Granguia_Cart_Helper::getCantidadActual($product_search_key)){
			$producto = Granguia_Catalog_Model_Producto::findBySearchKey($product_search_key);
			if($cantidad_agregada!=$cantidad)
				$mensaje = 'Stock insuficiente de <i style="white-space:nowrap;">'.$producto->getNombre().'</i> para '.$cantidad.' unidades adicionales.';
		}
		else{
			$producto = Granguia_Catalog_Model_Producto::findBySearchKey($product_search_key);
			$mensaje = 'Producto <i style="white-space:nowrap;">'.$producto->getNombre().'</i> fuera de stock';
		}
		if($mensaje!==null){
			$contenido_carro =
				Core_App::getInstance()
					->loadLayoutDom()
					->getLayout()
					->getBlock('contenido_carro');
			$contenido_carro->setMensaje($mensaje);
		}
	}
	public function ver($pagina=0){
		if(Core_Http_Post::hasParameters()){
			if(!$this->processActions())
				return;
		}
		Core_App::getLayout()->addActions('cart_ver_contenido','cart_content_list');
		
		$contenido_carro = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('contenido_carro');
		$contenido_carro->setPagina($pagina);
	}
	private function processActions(){
		$params = Core_Http_Post::getParameters('Core_Object');

		switch($params->getAction()){
			case 'sumar_a_carro':{
				Granguia_Cart_Helper::actionSumarACarro($params);
				break;
			}
			case 'clear':{
				Granguia_Cart_Helper::actionClear();
				Granguia_Catalog_Helper::returnToLastSearch();
				break;
			}
			case 'actualizar':{
				Granguia_Cart_Helper::actionActualizar($params);
				break;
			}
			case 'continuar':{
				$this->opciones_envio();
				return(false);
				break;
			}
		}
		return(true);
	}
	private function opciones_envio(){
		Core_App::getLayout()->addActions('cart_opciones_envio');
	}
	protected function codigos_postales(){
		if($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
			if(Core_Http_Post::hasParameters()){
				$post = Core_Http_Post::getParameters('Core_Object');
				if($post->hasIdCiudad()){
					$codigo_postal = new Granguia_Model_CiudadCodigoPostal();
					$codigo_postal->setIdCiudad($post->getIdCiudad());
					$codigo_postal->setWhere(Db_Helper::equal('id_ciudad'));
					$cps = $codigo_postal->search('codigo_postal');
					/*if($cps)*/{
						$data = array();
						foreach($cps as $codigo_postal){
							$data[] = array(
								'id'=>$codigo_postal->getId(),
								'codigo_postal'=>$codigo_postal->getCodigoPostal()
							);
						}
						echo json_encode($data);
					}
				}
			}
		}
		die();
	}
	/*Mat, esto definitivamente no va aca*/
	protected function ciudadesByProvincia(){
		if($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
			if(Core_Http_Post::hasParameters()){
				$post = Core_Http_Post::getParameters('Core_Object');
				if($post->hasIdProvincia()){
					$ciudad = new Granguia_Model_Ciudad();
					$ciudad->setIdProvincia($post->getIdProvincia());
					$ciudad->setWhere(Db_Helper::equal('id_provincia'));
					$ciudades = $ciudad->search('nombre');
					/*if($cps)*/{
						$data = array();
						foreach($ciudades as $city){
							$data[] = array(
								'id'=>$city->getId(),
								'nombre'=>$city->getNombre()
							);
						}
						echo json_encode($data);
					}
				}
			}
		}
		die();
	}
	protected function provinciasByPais(){
		if($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
			if(Core_Http_Post::hasParameters()){
				$post = Core_Http_Post::getParameters('Core_Object');
				if($post->hasIdPais()){
					$provincia = new Granguia_Model_Provincia();
					$provincia->setIdPais($post->getIdPais());
					$provincia->setWhere(Db_Helper::equal('id_pais'));
					$provincias = $provincia->search('nombre');
					/*if($cps)*/{
						$data = array();
						foreach($provincias as $prov){
							$data[] = array(
								'id'=>$prov->getId(),
								'nombre'=>$prov->getNombre()
							);
						}
						echo json_encode($data);
					}
				}
			}
		}
		die();
	}
	/*Mat, esto definitivamente no va aca end*/
	protected function compra($option=null){
		if(Core_Http_Post::hasParameters()){
			Granguia_Cart_Helper::setOpcionesEnvio(Core_Http_Post::getParameters('Core_Object'));
		}
		if(Granguia_Cart_Helper::checkOpcionesEnvio()){
			$this->_compra($option);
			return;
		}
		$this->opciones_envio();
	}
	private function _compra($option){
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			if($post->hasAction()){
				switch($post->getAction()){
					case 'registrarme':{
						Core_Http_Header::Redirect(Core_App::getUrlModel()->getUrl('usuario/registrar'));
						die();
					}
					case 'pedido':{
						$this->_generarPedido($post);
						return;
						break;
					}
					case 'guardar_opciones_envio':{
						Granguia_Cart_Helper::guardarPedido();
					}
					case 'volver':{
						$this->opciones_envio();
						return;
						break;
					}
				}
			}
		}
		//este es el case "continuar"
		Core_App::getLayout()->addActions('cart_pre_buy','cart_content_list');
	}
	private function _generarPedido($post){
		if($post->hasCantidad()){
			$cantidad = $post->getCantidad();
			if(is_array($cantidad) && count($cantidad)){
				if($post->hasQuitar() && is_array($post->getQuitar()))
					foreach($post->getQuitar() as $search_key=>$uno){
						array_splice($cantidad, array_search($search_key, array_keys($cantidad)), 1);
					}
				if($cantidad && count($cantidad)){
					if($venta = Granguia_Cart_Helper::generarPedido($cantidad)){
						Granguia_Cart_Helper::clearCarro();
						Core_App::getLayout()->addActions('cart_venta_ok');
						$cart_venta_ok = 
							Core_App::getInstance()
								->loadLayoutDom()
								->getLayout()
								->getBlock('cart_venta_ok');
						$cart_venta_ok->setVenta($venta);
						return;
					}
				}
			}
		}
		Core_App::getLayout()->addActions('cart_pre_buy','cart_content_list');
	}
	protected function dineromail($id_venta, $param_token){
		Core_App::getLayout()
			->setActions('popup','cart_pago_dineromail');
		$real_token = Granguia_Cart_Helper::getIdVentaToken($id_venta);
		$venta = new Granguia_Model_Venta();
		$venta->setId($id_venta);
		if(!$venta->load() || ($real_token!=$param_token)){
			$venta = null;
		}
		$cart_pago_dineromail = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('cart_pago_dineromail');
		$cart_pago_dineromail->setVenta($venta);
	}
	protected function generarPedido($id_pedido){
		$venta = new Granguia_Model_Venta();
		$venta->setId($id_pedido);
		if($venta->load()){
			Core_App::getLayout()->addActions('cart_venta_ok');
			$cart_venta_ok = 
				Core_App::getInstance()
					->loadLayoutDom()
					->getLayout()
					->getBlock('cart_venta_ok');
			$cart_venta_ok->setVenta($venta);
		}
	}
}
?>