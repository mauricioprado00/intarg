<?
abstract class Core_Router_Abstract extends Core_Object{
	private static $request_url;
	private $routers;
	protected $request_path;
	protected $arr_request_path;
	private $route_data;
	private $actions;
	static private $full_get_request;
	private $parent_router;
	
	public static function getFullGetRequest(){
		return(self::$full_get_request);
	}
	function __construct(){
		$this->request_path = '';
		$this->arr_request_path = array();
		$this->clearActions();
		$this->clearRouters();
		$this->parent_router = null;
	}
	abstract protected function initialize();
	protected function clearRouters(){
		$this->routers = array();
	}
	protected function clearActions(){
		$this->actions = array();
	}
	protected function addActions(){
		$actions = func_get_args();
		foreach($actions as $action)
			$this->actions[] = $action;
	}
	protected function hasAction($action){
		return(in_array($action, $this->actions));
	}
	protected function addRouter($subpath, $router_name){
		$this->routers[$subpath] = array('router'=>$router_name);
	}
	public function setRouteData($route_data){
		$this->route_data = $route_data;
		foreach($route_data as $subpath=>$value){
			if($key=='@classname')
				continue;
			if(!isset($route_data[$subpath]['@classname']))
				continue;
			$router_name = $route_data[$subpath]['@classname'];
			//var_dump($subpath, $router_name);
			$this->addRouter($subpath, $router_name);
		}
		return($this);
	}
	private function setRouteInfo($routers){
		$this->routers = $routers;
	}
	public function route($request_path=null,$debug=false){
		//var_dump($request_path);
		$this->initialize();
		if(!isset($request_path)){
			if(Core_App::getFancyUrlEnabled()){
				//self::$request_url = $_SERVER['REQUEST_URI'];
				self::$request_url = $_SERVER['REDIRECT_URL'];
				//$request_path = str_replace(CONF_PATH_APP, '', self::$request_url);
				$request_path = explode(CONF_SUBURL_APP, self::$request_url);;//str_replace(CONF_SUBURL_APP, '', self::$request_url);
				array_shift($request_path);
				$request_path = implode(CONF_SUBURL_APP, $request_path);
				if(!isset(self::$main_request_path))
					self::$full_get_request = $request_path;
			}
			else{
				self::$full_get_request = Core_App::getUrlModel()->getUrl($_SERVER['PATH_INFO']);
				//var_dump($_SERVER['PATH_INFO'], self::$full_get_request);
				//die();
				$request_path = $_SERVER['PATH_INFO'];
			}
			
			$request_path = array_shift(explode('?', $request_path));
		}
		$this->request_path = $request_path;
		if($this->request_path)
			$this->arr_request_path = explode('/',$this->request_path);
		else{$this->arr_request_path = array();}
		return($this->dispatch());
		
	}
	protected function dispatchNode($node){
		return(null);
	}
	protected function onThrought(){
		return(null);
	}
	protected function restrictDispatch(){
		return(null);
	}
	protected function getParentRouter(){
		return($this->parent_router);
	}
  /**
   * Core_Router_Abstract::routeDelegate()
   *
   * @param mixed $router (solo puede ser un router que tenga route_data, es decir que solo se puede volver)
   * @param mixed $arr_request_path
   * @param integer $left_slice_count
   * @return
   */
	protected function routeDelegate($router, $arr_request_path, $left_slice_count=1){
		if($left_slice_count){
			$arr_request_path = array_slice($this->arr_request_path, $left_slice_count);
		}
		return($router->route(implode('/', $arr_request_path),true));
	}
	private function dispatch(){
		//var_dump($this->arr_request_path,isset($this->arr_request_path[0]),$this->arr_request_path[0],isset($this->routers[$this->arr_request_path[0]]));
//		var_dump(($this->routers));
//		var_dump(($this->route_data));
		/* primero vemos si no hay un router para despachar la peticion*/
		$this->onThrought();
		if(true){
			$ret = call_user_method('restrictDispatch',$this);
			if($ret==true)
				return;
			//var_dump($this->arr_request_path);
		}
		if(isset($this->arr_request_path[0], $this->routers[$this->arr_request_path[0]])){
			$class = implode('_',explode('.', $this->routers[$this->arr_request_path[0]]['router']));
			$router = new $class;
			$router->parent_router = $this;
			if(isset($this->route_data[$this->arr_request_path[0]]))
				$router->setRouteData($this->route_data[$this->arr_request_path[0]]);
			//var_dump($router);
			//echo "creando router ".$class.(implode('/', array_slice($this->arr_request_path, 1)))."\n";
			return($router->route(implode('/', array_slice($this->arr_request_path, 1))));
			
		}
		if(count($this->arr_request_path)>0){
			$action = array_shift($x = $this->arr_request_path);
			/* segundo verificamos si no esiste un metodo para despachar la peticion*/
			if($this->hasAction($action) /*method_exists($this,$action)*/){
				//echo "$action action\n";
				return(call_user_method_array($action, $this, array_slice($this->arr_request_path, 1)));
			}
			/* tercero vemos si no existe un metodo dinamico para despachar la peticion */
			elseif(($ret = call_user_method_array('dispatchNode', $this, $this->arr_request_path))!==null){
				//echo "$action dispatchNode\n"; 
				return($ret);
			}
		}
		/* por ultimo vamos al dispatch por defecto aca podriamos ir al 404 */
		//var_dump(get_class($this));
		//echo "$action localDispatch\n";
		return($this->localDispatch());
	}
	protected function localDispatch(){
		//echo "<!-- Despachando ".$this->request_path." desde ".get_class($this)." -->\n";
	}
}
/*
Buena idea:
que el subpath del addrouter sea en vez de "path" "pathx/otropath",
asi primero podemos agregar por ejemplo
$router->addRouter('admin', 'Routers.RouterAdmin')
y despues $router->addRouter('admin/products', 'Routers.RouterAdminProducts')
o directamente 
$router->addRouter('admin/products','Routers.RouterAdmin/Routers.RouterAdminProducts')
el array quedaria algo asi:
$this->routers = array(
	'admin'=>array(
		'class'=>'Routers.RouterAdmin'//lo que antes era router
		'routers'=>array(
			'products'=>array(
				'class'=>'Routers.RouterAdminProducts'
			)
		)
	)
) 
de esta manera al llamar a router el pasariamos como parametros los routers
			$router = new $class;
			$router->route($path_to_route, $routers);

con esto ganamos que podemos predefinir todos los routers en un archivo, ej:
routers.init.php
donde esten todas las directivas
$router->addRouter('admin', 'Routers.RouterAdmin')
$router->addRouter('admin/products', 'Routers.RouterAdminProducts')
... etc


*/
?>