<?
class Granguia_Cart_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function getUrlAgregarACarro($product_search_key, $cantidad=null){
		return('carro/agregar/'.$product_search_key.($cantidad!==null?'/'.$cantidad:''));
	}
	public static function getUrlVerCarro($pagina=''){
		return('carro/ver/'.$pagina);
	}
	public static function getUrlCompra(){
		return('carro/compra');
	}
	public static function getUrlPagoDineromail($id_venta){
		return('carro/dineromail/'.$id_venta.'/'.self::getIdVentaToken($id_venta));
	}
	public static function getIdVentaToken($id_venta){
		return sha1('EstoEsunaPassParaevitarEstafas'.$id_venta);
	}
	private static function getCartUserSessionContext(){
		return(array('cart','producto'));
	}
	private static function getCartShopSessionContext(){
		return(array('cart', 'shop'));
	}
	public static function countItemsCarro(){
		$items = self::listarCarroSearchKeys();
		return($items?count($items):0);
	}
	public static function setIdVenta($id_venta){
		$x = new Granguia_User_Model_User();
		$x->setSessionVar('id_venta', $id_venta, self::getCartShopSessionContext());
	}
	public static function getIdVenta(){
		$x = new Granguia_User_Model_User();
		return($x->getSessionVar('id_venta', self::getCartShopSessionContext()));
	}
	public static function setOpcionesEnvio($post, $delete_saved=true){
		if($post->hasIdCiudad() && $post->hasIdMetodoEnvio() && $post->hasIdFormaPago()){
			$x = new Granguia_User_Model_User();
			$x->setSessionVar('id_ciudad', $post->getIdCiudad(), self::getCartShopSessionContext());
			if($post->hasIdCodigoPostal())
				$x->setSessionVar('id_codigo_postal', $post->getIdCodigoPostal(), self::getCartShopSessionContext());
			if($post->hasCodigoPostal())
				$x->setSessionVar('codigo_postal', $post->getCodigoPostal(), self::getCartShopSessionContext());
			$x->setSessionVar('id_metodo_envio', $post->getIdMetodoEnvio(), self::getCartShopSessionContext());
			$x->setSessionVar('id_forma_pago', $post->getIdFormaPago(), self::getCartShopSessionContext());
			if($delete_saved)
				self::deleteSaved();
			return(true);
		}
		return(false);
	}
	public static function checkOpcionesEnvio(){
		$x = new Granguia_User_Model_User();
		return(
			$x->getSessionVar('id_ciudad', self::getCartShopSessionContext()) && 
			$x->getSessionVar('id_metodo_envio', self::getCartShopSessionContext()) &&
			$x->getSessionVar('id_forma_pago', self::getCartShopSessionContext())  
		);
	}
	public static function getOpcionesEnvioIdCiudad(){
		$x = new Granguia_User_Model_User();
		$id_ciudad = $x->getSessionVar('id_ciudad', self::getCartShopSessionContext());
		$id_ciudad = $id_ciudad?$id_ciudad:16869;
		return($id_ciudad);
	}
	public static function getOpcionesEnvioIdCiudadCodigoPostal(){
		$x = new Granguia_User_Model_User();
		return($id_codigo_postal = $x->getSessionVar('id_codigo_postal', self::getCartShopSessionContext()));
	}
	public static function getOpcionesEnvioCodigoPostal(){
		$x = new Granguia_User_Model_User();
		return($codigo_postal = $x->getSessionVar('codigo_postal', self::getCartShopSessionContext()));
	}
	public static function getOpcionesEnvioIdMetodoEnvio(){
		$x = new Granguia_User_Model_User();
		return($id_metodo_envio = $x->getSessionVar('id_metodo_envio', self::getCartShopSessionContext()));
	}
	public static function getOpcionesEnvioIdFormaPago(){
		$x = new Granguia_User_Model_User();
		return($id_forma_pago = $x->getSessionVar('id_forma_pago', self::getCartShopSessionContext()));
	}
	public static function getOpcionesEnvioCiudad(){
		$id_entidad = self::getOpcionesEnvioIdCiudad();
		if($id_entidad){
			$entidad = new Granguia_Model_Ciudad();
			$entidad->setId($id_entidad);
			if($entidad->load()){
				return($entidad);
			}
		}
		return(null);
	}
	public static function getOpcionesEnvioCiudadCodigoPostal(){
		$id_entidad = self::getOpcionesEnvioIdCiudadCodigoPostal();
		if($id_entidad){
			$entidad = new Granguia_Model_CiudadCodigoPostal();
			$entidad->setId($id_entidad);
			if($entidad->load()){
				return($entidad);
			}
		}
		return(null);
	}
	public static function getOpcionesEnvioMetodoEnvio(){
		$id_entidad = self::getOpcionesEnvioIdMetodoEnvio();
		if($id_entidad){
			$entidad = new Granguia_Model_MetodoEnvio();
			$entidad->setId($id_entidad);
			if($entidad->load()){
				return($entidad);
			}
		}
		return(null);
	}
	public static function getOpcionesEnvioFormaPago(){
		$id_entidad = self::getOpcionesEnvioIdFormaPago();
		if($id_entidad){
			$entidad = new Granguia_Model_FormaPago();
			$entidad->setId($id_entidad);
			if($entidad->load()){
				return($entidad);
			}
		}
		return(null);
	}
	public static function getOpcionesEnvio(){
		$opciones_envio = new Core_Object();
		if($ciudad = self::getOpcionesEnvioCiudad()){
			$opciones_envio->setCiudad($ciudad);
		}
		if($codigo_postal = self::getOpcionesEnvioCodigoPostal()){
			$object = new Core_Object();
			$object->setCodigoPostal($codigo_postal);
			$opciones_envio->setCodigoPostal($object);
		}
		elseif($codigo_postal = self::getOpcionesEnvioCiudadCodigoPostal()){
			$opciones_envio->setCodigoPostal($codigo_postal);
		}
		if($metodo_envio = self::getOpcionesEnvioMetodoEnvio()){
			$opciones_envio->setMetodoEnvio($metodo_envio);
		}
		if($forma_pago = self::getOpcionesEnvioFormaPago()){
			$opciones_envio->setFormaPago($forma_pago);
		}
		if(count($opciones_envio->getData()))
			return($opciones_envio);
		return(null);
	}
	public static function getCantidades($start=0, $max=null){
		$carro = self::listarCarroSearchKeys($start, $max);
		if(!$carro)
			return(null);
		$cantidades = array();
		foreach($carro as $item)
			$cantidades[$item->getVarname()] = $item->getValue();
		if(!$cantidades)
			return(null);
		return($cantidades);
	}
	public static function listarCarroSearchKeys($start=0, $max=null){
		$max = $max>0?$max:null;
		$x = new Granguia_User_Model_User();
		//$ret = $x->listSessionVars(self::getCartUserSessionContext());
		$ret = $x->listSessionValues('Core_Object', self::getCartUserSessionContext());
		if($start>0){
			if(count($ret)<$start){
				return(null);
			}
			$ret = array_slice($ret, $start);
		}
		if($max){
			if(count($ret)<=$max){
				return($ret);
			}
			$ret = array_slice($ret, 0, $max);
		}
		return($ret);
	}
	private static function actionActualizarProducto($product_search_key, $cantidad=1){
		$x = new Granguia_User_Model_User();
		$x->setSessionVar($product_search_key, $cantidad, self::getCartUserSessionContext());
		self::deleteSaved();
	}
	private static function actionQuitarProducto($product_search_key){
		$x = new Granguia_User_Model_User();
		$x->setSessionVar($product_search_key, null, self::getCartUserSessionContext());
		self::deleteSaved();
	}
	public static function actionAgregarProductoACarro($product_search_key, $cantidad=1, $delete_saved=true){
		$x = new Granguia_User_Model_User();
		$cantidad_previa = $x->getSessionVar($product_search_key, self::getCartUserSessionContext());
		$cantidad_previa = $cantidad_previa===null?0:$cantidad_previa;

		$producto = Granguia_Catalog_Model_Producto::findBySearchKey($product_search_key);
		$stock = $producto->getStock();
		if($stock==$cantidad_previa)
			return 0;
		$dif = $stock - $cantidad_previa;
		$cantidad = $dif<0?$cantidad+$dif:$cantidad;
		
		$cantidad_nueva = $cantidad_previa+$cantidad;
		$x->setSessionVar($product_search_key, $cantidad_nueva, self::getCartUserSessionContext());
		if($delete_saved)
			self::deleteSaved();
		return($cantidad);
	}
	public static function getCantidadActual($product_search_key){
		$x = new Granguia_User_Model_User();
		$cantidad_previa = $x->getSessionVar($product_search_key, self::getCartUserSessionContext());
		$cantidad_previa = $cantidad_previa===null?0:$cantidad_previa;
		return($cantidad_previa);
	}
	public static function actionClear(){
		$x = new Granguia_User_Model_User();
		$x->setSessionVar('cart', null, array());
		self::deleteSaved();
	}
	public static function actionActualizar($params){
		if($params->hasCantidad()){
			foreach($params->getCantidad() as $search_key=>$cantidad){
				self::actionActualizarProducto($search_key, $cantidad);
			}
			self::deleteSaved();
		}
		if($params->hasQuitar()){
			foreach($params->getQuitar() as $search_key=>$quitar){
				//var_dump($search_key);
				self::actionQuitarProducto($search_key);
			}
			self::deleteSaved();
		}
	}
	public static function actionSumarACarro($params){
		
	}
	public static function calcularTotalCarro(){
		$carro = self::listarCarroSearchKeys();
		$total = 0;
		foreach($carro as $item){
			$producto = Granguia_Catalog_Model_Producto::findBySearchKey($item->getVarname());
			if($producto){
				$cantidad = $item->getValue();
				$total += $producto->getPrecio() * $cantidad;
			}
		}
		return($total);
	}
	public static function getUrlLinkListCodigosPostales(){
		return('carro/codigos_postales');
	}
	public static function generarPedido($cantidades, $estado='pendiente'){
		if(!$cantidades || !is_array($cantidades))
			return(false);
		$user = Granguia_User_Model_User::getLogedUser();
		if(!$user)
			return(false);
		$opciones_envio = self::getOpcionesEnvio();
		//var_dump(array_keys($opciones_envio->getData()));
		if(	!$opciones_envio->hasCiudad() || 
			!$opciones_envio->hasCodigoPostal() || 
			!$opciones_envio->hasMetodoEnvio() || 
			!$opciones_envio->hasFormaPago())
				return(false);
		
		$estado= strtolower($estado);
		if($estado&&$estado=='guardado'){
			self::deleteSaved();
		}
		$venta = new Granguia_Model_Venta();
		$venta->setIdUsuario($user->getId());
		$venta->setCiudad($opciones_envio->getCiudad()->getNombre());
		$venta->setCodigoPostal($opciones_envio->getCodigoPostal()->getCodigoPostal());
		$venta->setMetodoEnvio($opciones_envio->getMetodoEnvio()->getNombre());
		$forma_pago = $opciones_envio->getFormaPago();
		$venta->setFormaPago($forma_pago->getNombre());
		$venta->setEsDineromail($forma_pago->getEsDineromail());

		if($estado)
			$venta->setEstado($estado);
		$ret = $venta->replace();
		if(!$ret)
			return(false);
		$cant_detalles = 0;
		$detalles = array();
		foreach($cantidades as $search_key=>$cantidad){
			$producto = Granguia_Catalog_Model_Producto::findBySearchKey($search_key);
			if(!$producto)
				continue;
			$detalle_venta = new Granguia_Model_DetalleVenta();
			$detalle_venta
				->setIdVenta($venta->getId())
				->setCodigo($producto->getCodigo())
				->setNombre($producto->getNombre())
				->setDescripcion($producto->getDescripcion())
				->setMarca($producto->getMarca())
				->setCategoria($producto->getCategoria())
				->setRubro($producto->getRubro())
				->setPrecio($producto->getPrecio())
				->setCantidad($cantidad)
			;
			if(!$detalle_venta->replace())
				continue;
			$detalles[] = $detalle_venta;
			$cant_detalles++;
			
		}
		if(!$cant_detalles)
			return(false);
		self::notificarVenta($user, $venta, $detalles);
		return($venta);
	}
	private static function notificarVenta($usuario, $venta, $detalles){
		$email_layout = new Core_Email('email_venta_notificacion_cliente');
		$email_template = 
			$email_layout
				->loadDom()
				->addUtf8DecodeFilter()
				->getBlock('venta_notificacion_cliente');
		$email_template
			->setVenta($venta)
			->setUsuario($usuario)
			->setDetalles($detalles)
		;
		($email_layout->enviar(
			$usuario->getEmail(), 
			$usuario->getNombre().' '.$usuario->getApellido(), 
			'Notificacion de venta'
		));
	}
	public static function guardarPedido(){
		if(!Granguia_User_Model_User::getLogedUser() || self::getIdVenta())
			return(false);
		
		$venta = self::generarPedido(self::getCantidades(), 'guardado');
		if(!$venta)
			return(false);
		
		self::setIdVenta($venta->getId());
		return($venta);
	}
	public static function canSave(){
		return(Granguia_User_Model_User::getLogedUser() && !self::getIdVenta());
	}
	public static function deleteSaved(){
		$user = Granguia_User_Model_User::getLogedUser();
		if(!$user)
			return;
		self::setIdVenta(null);
		$delventa = new Granguia_Model_Venta();
		$delventa->setIdUsuario($user->getId());
		$delventa->setEstado('guardado');
		$delventa->delete();
	}
	public static function onLogin(){
		self::loadSaved();
	}
	public static function loadSaved(){
		$user = Granguia_User_Model_User::getLogedUser();
		if(!$user)
			return;
		$venta = new Granguia_Model_Venta();
		$venta->setIdUsuario($user->getId());
		$venta->setEstado('guardado');
		$venta->setWhere(Db_Helper::equal('id_usuario'), Db_Helper::equal('estado'));
		if($venta->searchCount()==1){
			$venta = array_pop($venta->search());
			
			$post = new Core_Object();
			$ciudad = new Granguia_Model_Ciudad();
			$ciudad->setNombre($venta->getCiudad());
		//var_dump($ciudad->getData());

			if(!$ciudad->load())
				return(false);
			
			$codigo_postal = new Granguia_Model_CiudadCodigoPostal();
			$codigo_postal->setIdCiudad($ciudad->getId());
			$codigo_postal->setCodigoPostal($venta->getCodigoPostal());
			if(!$codigo_postal->load()){
				$post->setCodigoPostal($venta->getCodigoPostal());
			}
			else{
				$post->setIdCodigoPostal($codigo_postal->getId());
			}
			
			$metodo_envio = new Granguia_Model_MetodoEnvio();
			$metodo_envio->setNombre($venta->getMetodoEnvio());
			if(!$metodo_envio->load())
				return(false);
			
			$forma_pago = new Granguia_Model_FormaPago();
			$forma_pago->setNombre($venta->getFormaPago());
			if(!$forma_pago->load())
				return(false);

			self::clearCarro();
			$post->setIdCiudad($ciudad->getId());
			
			$post->setIdMetodoEnvio($metodo_envio->getId());
			$post->setIdFormaPago($forma_pago->getId());
			
			
			//var_dump($ciudad->getData(), $codigo_postal->getData(), $metodo_envio->getData(), $forma_pago->getData());
			if(self::setOpcionesEnvio($post, false)){
				$detalle_venta = new Granguia_Model_DetalleVenta();
				$detalle_venta->setIdVenta($venta->getId());
				$detalle_venta->setWhere(Db_Helper::equal('id_venta'));
				$detalles = $detalle_venta->search();
				$cantidades = array();
				if($detalles){
					foreach($detalles as $detalle_venta){
						$producto = Granguia_Catalog_Model_Producto::findBySearchKey('-'.$detalle_venta->getCodigo());
						if(!$producto)
							continue;
						self::actionAgregarProductoACarro($producto->getSearchKey(), $detalle_venta->getCantidad(), false);
					}
				}
				self::setIdVenta($venta->getId());
			}
			return(true);
		}
		return(false);
	}
	public static function clearCarro(){
		self::setIdVenta(null);
		$cantidades = self::getCantidades();
		$x = new Granguia_User_Model_User();
		if($cantidades){
			foreach($cantidades as $search_key=>$cantidad)
				$x->setSessionVar($search_key, null, self::getCartUserSessionContext());
		}
		$x->setSessionVar('id_ciudad', null, self::getCartShopSessionContext());
		$x->setSessionVar('id_codigo_postal', null, self::getCartShopSessionContext());
		$x->setSessionVar('id_metodo_envio', null, self::getCartShopSessionContext());
		$x->setSessionVar('id_forma_pago', null, self::getCartShopSessionContext());
	}
	public static function getPaisProvinciaSelected($id_ciudad)
	{
		$c = new Granguia_Model_Ciudad();
		$c->setId($id_ciudad);
		$ciudad = $c->load();
		$pr = new Granguia_Model_Provincia();		
		$pr->setId($c->getIdProvincia());
		$pr->load();
		$p = new Granguia_Model_Pais();
		$p->setId($pr->getIdPais());
		$p->load();
		$aRet = array(
						'IdCiudadSelected' => $c->getId(),
						'IdProvinciaSelected' => $pr->getId(),
						'IdPaisSelected' => $p->getId()
		);
		return $aRet;
	}
}
?>