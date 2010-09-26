<?
class Core_App extends Core_Singleton{
	private static $app;
	private $router;
	private $config;
	private $layout;
	private $url_model;
	private $session;
	private $messages;
	private $errors;
	private $use_config_cache = false;
	private $fancy_url_enabled = true;
	public static function getLoadedLayout(){
		return self::getInstance()
			->loadLayoutDom()
			->getLayout();
	}
	public function clearLastErrorMessages(){
		$this->errors = null;
	}
	public function setLastErrorMessages(){
		$args = func_get_args();
		$this->errors = $args;
		return($this);
	}
	public function appendLastErrorMessages(){
		$args = func_get_args();
		$this->errors = array_merge($this->errors!==null?$this->errors:array(), $args);
		return($this);
	}
	public function getLastErrorMessages(){
		return($this->errors);
	}
	public function __construct(){
		$this->messages = array();
	}
	public static function run(){
		$args = func_get_args();
		return(call_user_func_array(array(self::getInstance(),'_run'), $args));
	}
	private function _run(){
		header('X-UA-Compatible:IE=8');
		session_start();
		$start = microtime(true);
		$this->setVersion('1.2.0.1.2');
		$this->config = new Core_Config();
//		$nodo = new Granguia_Model_Nodo();
//		$nodo->setWhere(Db_Helper::like('path_url',null,null,true));
//		$nodo->setPathUrl($_SERVER['REDIRECT_URL']);
//		$nodos = $nodo->search();
//		if(!$nodos){
//			
//		}
//		$nodo = array_shift($nodos);
//		$tipo = $nodo->getTipoNodo();
//		false&&$tipo = new Granguia_Model_TipoNodo();
//		$clase = $tipo->getClaseControladora();
//		
//		$controller = new $clase;
//		$controller->setInstancia($nodo);
//		$coincidencia = array_shift($x = explode('%', $nodo->getPathUrl()));
//		$resto = array_pop($x = explode($coincidencia, $_SERVER['REDIRECT_URL']));
//		$clase->dispatch($resto);
		
		$this->router = new Core_Router_Main();
		//$layout = Base_Layout::getInstance();
		$this->layout = Base_Layout::getInstance();
		$this->session = new Core_Session();
		$this->url_model = Core_UrlModel::getInstance();
		if($_SERVER['SCRIPT_NAME']==CONF_SUBURL_APP.'index.php'){
			if($_SERVER['PATH_INFO']===null)
				$_SERVER['PATH_INFO'] = '/';
		}
		if($_SERVER['PATH_INFO']!==null){
			$_SERVER['PATH_INFO'] = substr($_SERVER['PATH_INFO'], 1);
			$this->setFancyUrlEnabled(false);
		}
		$this->config->load(CFG_PATH_ROOT.CONF_PATH_CORE.'etc/config.xml', $this->use_config_cache);
		foreach($this->config->getModules() as $module_name=>$module_library){
			//Base_Library::import($module_library.'.*');
		}
		
		;
		$enable_translate_debug = Admin_Translate_Debug::getInstance()->isEnabled();
		if($enable_translate_debug){
			$enable_translate_debug = true;
			//Core_Translate_Singleton::getInstance()->addTranslator(new Admin_Translate_Debug());
			Core_App::getConfig()->addTranslator('Admin_Translate_Debug', 0);
			//Core_Translate_Singleton::getInstance()->translate('algo');
			self::setData('message_container_translate', new Core_Collection());
		}
		
		$trans = $this->config->getTranslate();
		asort($trans);
		Core_Translate_Singleton::getInstance()->initialize($trans);
		//var_dump($this->config);
		$this->router
			->setRouteData(
				Core_App::getInstance()->getConfig()->getRouteData()
			)
			->route()
		;
		//var_dump($this->config->getLayoutUpdates());
		$this->loadLayoutUpdates();
		
		if(Core_Http_Header::isAjaxRequest()&&!headers_sent()){
			Core_Http_Header::ContentEncoding('gzip');
			$this->layout->renderOutput(true, true);
		}
		else
			$this->layout->renderOutput();
		/*$usuario = new Granguia_User_Model_User();
		$usuario->load(array('id'=>1));
		$usuario->actionEnviarCorreoRegistro();*/
		
//		var_dump($usuario);

		
	}
	public function loadLayoutUpdates($layout_object=null){
		static $called;
		if($called&&$layout_object===null)
			return($this);
		$called=$layout_object===null;
		$layout_object = $layout_object===null?$this->layout:$layout_object;
		foreach($this->config->getLayoutUpdates() as $layout_update){
			$layout_object->addLayout($layout_update['file'], $layout_update['modo']);
		}
		return($this);
	}
	public function loadLayoutDom(){
		static $called;if($called)return($this);$called=true;
		$this->loadLayoutUpdates();
		$this->layout->loadDom();
		return($this);
	}
	public static function getConfig(){
		$args = func_get_args();
		return(call_user_func(array(self::getInstance(), '_getConfig'), $x = $args));
	}
	private function _getConfig(){
		return($this->config);
	}
	public static function getFancyUrlEnabled(){
		$args = func_get_args();
		return(call_user_func(array(self::getInstance(), '_getFancyUrlEnabled'), $x = $args));
	}
	private function _getFancyUrlEnabled(){
		return($this->fancy_url_enabled);
	}
	public static function setFancyUrlEnabled($enabled){
		$args = func_get_args();
		return(call_user_func_array(array(self::getInstance(), '_setFancyUrlEnabled'), $x = $args));
	}
	private function _setFancyUrlEnabled($enabled=true){
		$this->fancy_url_enabled = $enabled?true:false;
		return($this);
	}
	public static function getSession(){
		$args = func_get_args();
		return(call_user_func(array(self::getInstance(), '_getSession'), $x = $args));
	}
	private function _getSession(){
		return($this->session);
	}
	public static function getUrlModel(){
		$args = func_get_args();
		return(call_user_func(array(self::getInstance(), '_getUrlModel'), $x = $args));
	}
	private function _getUrlModel(){
		return($this->url_model);
	}
	public static function getLayout(){
		$args = func_get_args();
		return(call_user_func(array(self::getInstance(), '_getLayout'), $x = $args));
	}
	private function _getLayout(){
		return($this->layout);
	}
	public static function getRouter(){
		$args = func_get_args();
		return(call_user_func(array(self::getInstance(), '_getRouter'), $x = $args));
	}
	private function _getRouter(){
		return($this->router);
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	
	public function addMessage($message,$messagename='default'){
		$args = func_get_args();
		return(call_user_func_array(array(self::getInstance(), '_addMessage'), $x = $args));
	}
	private function _addMessage($message,$messagename=null){
		if(!isset($messagename))
			return($this);
		$this->messages[$messagename][] = $message;
		return($this);
	}
	public function getMessages($messagename=null){
		$args = func_get_args();
		return(call_user_func_array(array(self::getInstance(), '_getMessages'), $x = $args));
	}
	private function _getMessages($messagename=null){
		if(!isset($messagename)||!isset($this->messages[$messagename]))
			return(null);
		return($this->messages[$messagename]);
	}
}
?>