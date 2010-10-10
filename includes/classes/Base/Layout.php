<?
class Base_Layout extends Base_Singleton{
	//var $main_layout = 'Frontend.xml';
	//var $layouts = array('Frontend.xml');
	var $mode_layouts = array();
	var $current_design_path = null;
	var $default_design_path = 'default/default/';
	var $design_paths = array();
	var $arr_object_dom = array();
	var $actions = array('default');
	var $by_name = array();
	var $modo = 'default';
	var $use_compression = false;
	public function setUseCompression($use){
		$this->use_compression = $use?true:false;
		return $this;
	}
	public function clearDesignPaths($modo){
		$args = func_get_args();
		return(call_user_method_array('_clearDesignPaths', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _clearDesignPaths($modo=null){
		if($modo===null)
			$this->design_paths = array();
		else
			$this->design_paths[$modo] = array();
		return($this);
	}
  /**
   * Base_Layout::addDesignPaths($modo, $design_path, $other_designpath, ...)
   *
   * @param mixed $modo
   * @return
   */
	public function addDesignPaths($modo){
		$args = func_get_args();
		return(call_user_method_array('_addDesignPaths', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _addDesignPaths($modo){
		$args = func_get_args();
		if(count($args)<2)
			return($this);
		$args = array_slice($args = func_get_args(), 1);
		foreach($args as $design_path){
			$this->design_paths[$modo][] = $design_path;
		}
		return($this);
	}
	public function setModo($modo){
		$args = func_get_args();
		return(call_user_method_array('_setModo', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _setModo($modo){
		//echo "nuevo modo $modo\n";var_dump(array_slice($x = debug_backtrace(),2,1));
		$this->modo = $modo;
		return($this);
//		if(isset($this->mode_layouts[$modo])){
//			$this->modo = $modo;
//			//echo "modo cargado $modo\n";
//		}
//		else{
//			echo "no existen layouts para el modo que se intenta setear '$modo'\n";
//			var_dump(debug_backtrace());
//			die();
//		}
//		return($this);
	}
	public function setMainLayout($layout,$modo=null){
		$args = func_get_args();
		return(call_user_method_array('_setMainLayout', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _setMainLayout($layout,$modo=null){
		$this->mode_layouts[$modo][0] = $layout;
		return($this);
	}
	public function setActions(){
		$args = func_get_args();
		//var_dump($this);
		return(call_user_method_array('_setActions', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _setActions(){
		$actions = func_get_args();
		if(count($actions))
			$this->actions = $actions;
		return($this);
	}
	public function getActions(){
		$args = func_get_args();
		return(call_user_method_array('_getActions', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _getActions(){
		return($this->actions);
	}
	public function addActions(){
		$args = func_get_args();
		return(call_user_method_array('_addActions', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _addActions(){
		$actions = func_get_args();
		if(count($actions))
			foreach($actions as $action)
				if(!in_array($action, $this->actions))
					$this->actions[] = $action;
		return($this);
	}
	public function removeActions(){
		$args = func_get_args();
		return(call_user_method_array('_removeActions', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _removeActions(){
		$actions = func_get_args();
		if(count($actions))
			foreach($actions as $action)
				if(($key = array_search($action, $this->actions))!==false){
					$new = array_slice($this->actions, 0, $key);
					if(isset($this->actions[$key+1]))
						$new = array_merge($new, array_slice($this->actions,$key+1));
					$this->actions = $new;
				}
		return($this);
	}
	public function addAction($action){
		$args = func_get_args();
		return(call_user_method_array('_addAction', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _addAction($action){
		$this->actions[] = $action;
		return($this);
	}
	public function getPrimaryAction(){
		$args = func_get_args();
		return(call_user_method_array('_getPrimaryAction', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _getPrimaryAction(){
		return(array_shift($x = $this->actions));
	}
	public function getSkinUrl($file){
		$args = func_get_args();
		return(call_user_method_array('_getSkinUrl', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _getSkinUrl($file){
		/**
			deberia recorrer todos los paths hasta encontrar un archivo que exista
		*/
		//echo "skinning $file\n";
		//$paths = $this->design_paths;
		$paths = array($this->default_design_path);
		if(isset($this->design_paths[$this->modo]))
			$paths = array_merge($paths, $this->design_paths[$this->modo]);

		$cant = count($paths);
		for($i=0; $i<$cant; $i++){
			$relative_file = CONF_SUBPATH_SKIN.$paths[$cant-1-$i].$file;
			//echo "relative ".CFG_PATH_ROOT.CONF_PATH_APP."$relative_file\n";
			if(file_exists(CFG_PATH_ROOT.CONF_PATH_APP.$relative_file)){
				return(CONF_URL_APP.$relative_file);
			}
		}
		return(null);
	}
	public function getSkinPath($file){
		$args = func_get_args();
		return(call_user_method_array('_getSkinPath', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _getSkinPath($file){
		/**
			deberia recorrer todos los paths hasta encontrar un archivo que exista
		*/
		//echo "skinning $file\n";
		//$paths = $this->design_paths;
		$paths = array($this->default_design_path);
		if(isset($this->design_paths[$this->modo]))
			$paths = array_merge($paths, $this->design_paths[$this->modo]);

		$cant = count($paths);
		for($i=0; $i<$cant; $i++){
			$relative_file = CONF_SUBPATH_SKIN.$paths[$cant-1-$i].$file;
			//echo "relative ".CFG_PATH_ROOT.CONF_PATH_APP."$relative_file\n";
			if(file_exists(CFG_PATH_ROOT.CONF_PATH_APP.$relative_file)){
				return(CFG_PATH_ROOT.CONF_PATH_APP.$relative_file);
			}
		}
		return(null);
	}

	public function getDesignFilePath($file){
		$args = func_get_args();
		return(call_user_method_array('_getDesignFilePath', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function x_getDesignFilePath($file){
		/**
			deberia recorrer todos los paths hasta encontrar un archivo que exista
		*/
		if(!isset($this->current_design_path)){
			$this->current_design_path = array_pop($x = $this->design_paths);
		}
		return(CFG_PATH_ROOT.CONF_PATH_DESIGN.$this->current_design_path.$file);
	}
	private function _getDesignFilePath($file){
		/**
			deberia recorrer todos los paths hasta encontrar un archivo que exista
		*/
		//echo "skinning $file\n";
		//array_merge(array($this->default_design_path), $this->design_paths[$this->modo])var_dump(array_merge(array($this->default_design_path), $this->design_paths[$this->modo]), $this->modo);die();
		//$paths = $this->design_paths;
		//var_dump($this->design_paths);
		//var_dump($this->modo);
		$paths = array($this->default_design_path);
		if(isset($this->design_paths[$this->modo]))
			$paths = array_merge($paths, $this->design_paths[$this->modo]);
			
		$cant = count($paths);
		for($i=0; $i<$cant; $i++){
			$relative_file = CONF_SUBPATH_DESIGN.$paths[$cant-1-$i].$file;
			//var_dump($relative_file);
			//echo "relative ".CFG_PATH_ROOT.CONF_PATH_APP."$relative_file\n";
			if(file_exists(CFG_PATH_ROOT.CONF_PATH_APP.$relative_file)){
				return(CFG_PATH_ROOT.CONF_PATH_APP.$relative_file);
			}
		}
		return(null);
	}
//	public function setPrimaryLayout($file){
//		$this->layouts = array($file);
//	}
	public function addLayout($file,$modo=null){
		$args = func_get_args();
		return(call_user_method_array('_addLayout', ($obj=$this?$this:self::getInstance()),$args));
	}
	private function _addLayout($file,$modo=null){
		$modo = $modo===null?'default':$modo;
		//echo "agregando layout $modo\n";
		$this->mode_layouts[$modo][] = $file;
		//$this->layouts[] = $file;
		return($this);
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function loadDom(){
		$args = func_get_args();
		return(call_user_method_array('_loadDom', ($obj=$this?$this:self::getInstance()), $args));
	}
	private $modulo;
	public function setCurrentModulo($modulo){
		$this->modulo = $modulo;
	}
	public function getCurrentModulo(){
		return($this->modulo);
	}
	public function dump(){
		if(!$this->arr_object_dom || !count($this->arr_object_dom))
			return(null);
		return($this->arr_object_dom[0]->dump());
	}
	private function _loadDom(){
		//if($called->called)return($this);$called=true;
		if(count($this->arr_object_dom))
			return;
		$this->arr_object_dom = array(new Core_Block_Root());
		//var_dump($this->mode_layouts,$this->modo);
		//var_dump($this->mode_layouts[$this->modo]);
		//var_dump($this->mode_layouts);
		//var_dump($this->modo);
		if(isset($this->mode_layouts[$this->modo])){
			foreach($this->mode_layouts[$this->modo] as $layout_file_name){
				//var_dump($layout_file_name);
				//var_dump($layout_file_name, Core_App::getConfig()->getLayoutModulo($layout_file_name));
				$this->setCurrentModulo(Core_App::getConfig()->getLayoutModulo($layout_file_name));
				if(!$this->_loadLayoutFile($this->getDesignFilePath(CONF_SUBPATH_LAYOUT.$layout_file_name))){
					echo "no existe layout $layout_file_name\n";
					echo $this->getDesignFilePath('');
					var_dump($this->design_paths);
				};
			}
		}
		else{echo "<!-- no hay layouts -->";}
		return($this);
	} 
	public function renderOutput($to_output=true,$compressed=null){
		$args = func_get_args();
		return(call_user_method_array('_renderOutput', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _renderOutput($to_output=true,$compressed=null){
		if(!isset($compressed))
			$compressed = $this->use_compression;
		$this->_loadDom();
		$output =  $this->arr_object_dom[0]->toHtml();
		if($compressed)
			$output = gzencode($output);
		if($to_output){
			echo $output;
			return($this);
		}
		return($output);
		//var_dump($this->arr_object_dom);
		return($this);
	}
	public static function getSimpleXmlFromString($xml)
	{
		return($xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
	}
	public static function getSimpleXmlFromFile($file)
	{
		return($xml = simplexml_load_file($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
	}
	private function _loadLayoutFile($full_file_path){
		//echo "cargando archivo de layout $full_file_path\n";
		if(file_exists($full_file_path)){
			//var_dump($this->getPrimaryAction());
			if($xml = simplexml_load_file($full_file_path, 'SimpleXMLElement', LIBXML_NOCDATA)){
				//var_dump($this->actions);
				foreach($this->actions as $action){
					$this->_loadXmlAction($xml, $action);
				}
			}
			return(true);
		}
		return(false);		
		{
			echo "no existe layout $full_file_path\n";
		}
		return($this);
	}
	private function _shouldLoad($xml){
		static $arr_checks = null;
		$attr = $xml->attributes();
		$load = true;
//		if(!$attr['in'])
//			return(true);
		if(isset($attr['in'])){//acciones
			$continue = false;
			foreach(explode(' ', (string)$attr['in']) as $action_or){
				$action_or = explode('|', $action_or);
				$res = false;
				foreach($action_or as $action){
					$bool = false;
					if($action[0]=='!'){
						$action = substr($action, 1);
						$bool = true;
					}
					if(in_array($action, $this->actions)){
						$bool = !$bool;
						//break;
						//echo "no se esta ejecutando la accion $action\n";
					}
					$res = $bool;
				}
				if($res===false){
					$continue = true;
					break;
				}
			}
			$load = !$continue;
			//return(!$continue);
		}
		if($load && isset($attr['on'])){//modos
			$continue = false;
			foreach(explode(' ', (string)$attr['on']) as $modo_or){
				$modo_or = explode('|', $modo_or);
				$res = false;
				foreach($modo_or as $modo){
					$bool = false;
					if($modo[0]=='!'){
						$modo = substr($modo, 1);
						$bool = true;
					}
					if($modo==$this->modo){
						$bool = !$bool;
						//break;
						//echo "no se esta ejecutando la accion $modo\n";
					}
					$res = $bool;
				}
				if($res===false){
					$continue = true;
					break;
				}
			}
			$load = !$continue;
		}
		if($load && isset($attr['when'])){//modos
//			$re = '(^((accion|modo)[(]([a-zA-Z0-9_]+)(,[a-zA-Z0-9_]+)*[)])+$)';
			$when = (string)$attr['when'];
			//estaria buena esta sintaxis
			//!accion(xx,xx,xx) ->ninguna
			//accion(xx,xx,xx) ->alguna
			//~accion(xxx,xx,xx) ->alguna no
			//&accion(xxx,xx,xx) ->todas
			$when = preg_replace('/[\\s]*([(),|!])[\\s]*/','$1', $when);
//			if(!preg_match($re, $when)){
//				echo "la expresion when:\"$when\", es incorrecta";
//				die();
//			}
			$re = '((?P<denied>!)?(?P<qualifier>accion|modo|get|req)[(](?P<params>([a-zA-Z0-9_]+)([,][a-zA-Z0-9_]+)*)[)])';
			if(preg_match_all($re, $when, $matches)){
				//var_dump($matches);
				foreach($matches[0] as $idx=>$full_string){
					$denied = $matches['denied'][$idx];
					$qualifier = $matches['qualifier'][$idx];
					$params = $matches['params'][$idx];
					$reemplazo = preg_replace('([a-zA-Z0-9_]+)', $denied.$qualifier.'_$0', $params);
					$reemplazo = str_replace(',',$denied?'&':'|', $reemplazo);
					$when = str_replace($full_string, '('.$reemplazo.')', $when);
					//var_dump($reemplazo);
				}
			}
			//var_dump($when);
			$when = preg_replace('/([A-Za-z0-9_]+)/', '\\$$1', $when);
			$when = preg_replace('/([|]+)/', '||', $when);
			$when = preg_replace('/([&]+)/', '&&', $when);
			if(!isset($arr_checks)){
				foreach($this->actions as $action){
					$arr_checks['accion_'.$action] = true;
				}
				if(Core_Http_Get::hasParameters())
				foreach(Core_Http_Get::getParameters() as $name=>$value){
					$arr_checks['get_'.$name] = true;
					$arr_checks['get_'.$name.'_eq_'.$value] = true;
					$arr_checks['req_'.$name] = true;
					$arr_checks['req_'.$name.'_eq_'.$value] = true;
				}
				if(Core_Http_Post::hasParameters())
				foreach(Core_Http_Post::getParameters('array') as $name=>$value){
					$arr_checks['post_'.$name] = true;
					$arr_checks['post_'.$name.'_eq_'.$value] = true;
					$arr_checks['req_'.$name] = true;
					$arr_checks['req_'.$name.'_eq_'.$value] = true;
				}
				$arr_checks['modo_'.$this->modo] = true;
			}

			//var_dump($arr_checks);
			if(preg_match_all('([$](?P<varnames>[A-Za-z0-9_]+))', $when, $matches)){
				foreach($matches['varnames'] as $varname){
					if(!isset($arr_checks[$varname])){
						//echo 'set false '. $varname."\n";
						$$varname = false;
					}
					$when = preg_replace('/\\$'.$varname.'\\b/', '\\$arr_checks["'.$varname.'"]', $when);
				}
			}
			//var_dump($when);
			$when = '$load = '.$when.';';
			//var_dump($when,$arr_checks);
			//$load = 'kradkk';
			try{
				ob_start();
				eval($when);
				/*eval('?><?$kradkk = "50";?>');*/
				$ejecuta_mal = ob_get_contents();
				ob_end_clean();
			}catch(Exception $e){
				
			}
			if($ejecuta_mal){
				$load = true;
			}
		}
		return $load;
	}
	private function _loadXmlAction($xml, $action){
		//echo "cargando accion $action\n";
		$xml_action_blocks = $xml->xpath("./".$action);
		//var_dump($action, $xml_action_blocks);
		//echo count($xml_action_blocks)."\n";
		foreach($xml_action_blocks as $xml_action_block){
			if(!$this->_shouldLoad($xml_action_block))
				continue;
			$this->_loadBlocks($xml_action_block, $this->arr_object_dom[0]);
			$this->_loadReferences($xml_action_block);
		}
		return($this);
	}
	public function appendXmlReferences($xml){
		$_this = $this?$this:self::getInstance();
		$args = func_get_args();
		if(!($args[0] = self::_getSimpleXmlFrom('<bluff>'.$xml.'</bluff>'))){
			return(false);
		}
		return(call_user_method_array('_loadReferences', $_this, $args));
	}
	private function _loadReferences($xml){
		$xml_references = $xml->xpath("./reference");
		foreach($xml_references as $xml_reference){
			if(!$this->_shouldLoad($xml_reference))
				continue;
			$reference_name = null;
			$atributos = array();
			foreach($xml_reference->attributes() as $attr_name=>$value){
				switch($attr_name){
					case 'name':{
						$reference_name = ((string)$value); 
						break;
					}
					default:{
						$atributos[$attr_name] = (string)$value;
					}
				}
			}
			$block = null;
			if(isset($reference_name)){
				$block = $this->getBlock($reference_name);
				if($block)
				foreach($atributos as $attr_name=>$value){
					//echo "$attr_name\n $value\n";
					$block->setData($attr_name, $value);
				}
				//echo "XXXexiste: ".(self::getBlock($reference_name)).", tipo: ".get_class(self::getBlock($reference_name))."\n";
				
				//echo "referenced class name: ".get_class(self::getBlock($reference_name))."\n";
				
			}
			else $block = $this->arr_object_dom[0];
			if($block!==null)
				$this->_loadBlock($xml_reference, $block);
			else{
				echo "bloque no encontrado: '$reference_name'\n";
				var_dump(array_keys($this->by_name));
			}
			/*
				buena idea: si no encuentra un objeto, entonces que lo agrege a un listado y que haga un pooling luego de cargar todos los layouts
				
			*/
		}
		return($this);
	}
	private function getBlockTypes(){
		static $block_types = null;
		if($block_types!==null){
			return($block_types);
		}
		$block_types = array('block'=>'');
		foreach(Core_App::getConfig()->getBlockTypes() as $block_type){
			//var_dump($block_type, Core_App::getConfig()->getBlockTypeClass($block_type, $this->getCurrentModulo()));
			$block_types[$block_type] = Core_App::getConfig()->getBlockTypeClass($block_type, $this->getCurrentModulo());
		}
		return($block_types);
	}
	private function _loadBlocksOld($xml, &$parent_block){
		//echo "cargando bloques\n";
		$block_types = $this->getBlockTypes();
		foreach($parent_block->getCustomBlockTypes() as $block_type){
			if(in_array($block_type, array('block', 'action', 'reference'))){
				echo "fatal error, custom_block_type $type cant be block, action or reference those are reserved types\n";
				die(); 
			}
			$classname = $parent_block->getCustomBlockTypeClassname($block_type);
			if($classname===null)
				continue;
			$block_types[$block_type] = $classname;
		}
		foreach($block_types as $block_type=>$classname){
			$xml_blocks = $xml->xpath("./".$block_type);
			foreach($xml_blocks as $xml_block){
				if(!$this->_shouldLoad($xml_block))
					continue;
				//var_dump($xml_block);
				$block = $this->_createBlock($xml_block, $parent_block, $classname);
				if($block===null)
					continue;
	//			$parent_block->insert($block);
				$this->_loadBlock($xml_block, $block);
			}
		}
		return($this);
	}
	private function _getBlockTypes($parent_block=null){
		$block_types = $this->getBlockTypes();
		if($parent_block!=null)
			foreach($parent_block->getCustomBlockTypes() as $block_type){
				if(in_array($block_type, array('block', 'action', 'reference'))){
					echo "fatal error, custom_block_type $type cant be `block`, `action` or `reference` those are reserved types\n";
					die(); 
				}
				$classname = $parent_block->getCustomBlockTypeClassname($block_type);
				if($classname===null)
					continue;
				$block_types[$block_type] = $classname;
			}
		return($block_types);
	}
	public function appendXmlBlocks($xml, &$parent_block){
		$_this = $this?$this:self::getInstance();
		$args = func_get_args();
		if(!($args[0] = self::_getSimpleXmlFrom('<bluff>'.$xml.'</bluff>'))){
			return(false);
		}
		return(call_user_method_array('_loadBlocks', $_this, $args));
	}
	private function _loadBlocks($xml, &$parent_block){
		//echo "cargando bloques\n";
		$block_types = $this->_getBlockTypes($parent_block);
		$block_types_names = array_keys($block_types);
		foreach($xml->children() as $block_type=>$xml_block){
			if(!in_array($block_type, $block_types_names))
				continue;
			$classname = $block_types[$block_type];
			if(!$this->_shouldLoad($xml_block))
				continue;
			//var_dump($xml_block);
			$block = $this->_createBlock($xml_block, $classname);
			if($block===null)
				continue;
			$this->_appendBlock($block, $parent_block);
//			$parent_block->insert($block);
			$this->_loadBlock($xml_block, $block);
			$this->_loadReferences($xml_block);
		}
		return($this);
	}
	private function _loadBlock($xml_block, &$block){
		/**
		para soportar referencias anidadas tendriamos que agregar aca un 
		$this->_loadBlocks($xml_block, $block);
		*/
			$this->_loadBlocks($xml_block, $block);
			$this->_loadActions($xml_block, $block);
	}
	private function _loadActions($xml, &$block){
		//echo "cargando acciones\n";
		$xml_actions = $xml->xpath("./action");
		foreach($xml_actions as $xml_action){
			//var_dump($xml_action);
			if(!$this->_shouldLoad($xml_action))
				continue;
			$attrs = $xml_action->attributes();
			$method = null;
			$by_name = false;
			foreach($attrs as $attr_name=>$attr){
				switch($attr_name){
					case 'method':{
						$method = (string)$attr;
						break;
					}
					case 'named_args':{
						$val = (string)$attr;
						$val = strtolower($val)==='false'?false:$val;
						$by_name = ($val?true:false);
						break;
					}
				}
			}
			$method_named_args = $method.'_named_args';
			$by_name = $by_name&&method_exists($block, $method_named_args);
			$argumentos = array();
			if($by_name){
				$method = $method_named_args;
				foreach($xml_action as $param_name=>$value){
					if(isset($argumentos[$param_name])){
						if(!is_array($argumentos[$param_name]))
							$argumentos[$param_name] = array($argumentos[$param_name]);
						$argumentos[$param_name][] = (string)$value;
					}
					 else
					 	$argumentos[$param_name] = (string)$value;
				}
				$argumentos = array($argumentos);//leva un solo argumento que incluye todos
			}
			elseif(method_exists($block, $method)||method_exists($block, '__call')){
				foreach($xml_action as $param_name=>$value){
					if(isset($value->attributes()->as_xml))
						$argumentos[] = $value;
					else
						$argumentos[] = (string)$value;
				}
			}
			else{
				echo "no existe el metodo $method\n para ".get_class($block);die();
			}
//			var_dump(method_exists($block, $method));
//			var_dump(method_exists($block, $method_named_args));
//			var_dump($by_name);
//			var_dump($method);
//			var_dump($argumentos);
			call_user_method_array($method, $block, $argumentos);
		}
		return($this);
	}
	private function _createBlock($xml_block, $classname = ''){
		$attributes = $xml_block->attributes();
		$attr = array(
			
		);
		foreach($attributes as $name=>$xml_value){
			$attr[$name] = (string)$xml_value;
		}
//		if(!){
//			echo "no tiene atributo type (deberia haber un tipo de bloque por defecto que muestre siempre los hijos)\n";
//			var_dump($xml_block);
//			return(null);
//		}
		if(isset($attr['type'])&&$attr['type']){
			$value = $attr['type'];
			$value = explode('/', $value);
			$modulo = 'Core';
			$cant = count($value);
			if($cant>2)
				return(null);
			elseif($cant==2)
				$modulo = array_shift($value);
			
			$classname = $modulo.'_Block_'.$value[0];
			if(class_exists('Core_'.$classname)){
				$classname = 'Core_'.$classname;
			}
			if(!class_exists($classname)){
				echo "no existe clase $classname\n";
				return(null);
			}
			//echo "si existe clase $classname\n";
		}
		if(!$classname){
			$classname = 'Core_Block_Container';
			//var_dump($xml_block);
		}
		$obj = new $classname;
		foreach($attr as $name=>$value){
			switch($name){
				case 'type':{
					$obj->setType($value);
					break;
				}
				default:{
					$obj->setData($name, $value);
					//$obj->setData('caca',"kkk");
				}
			}
			//var_dump($name);
		}
//		if($obj->hasTranslate()){
//			$arr_translate = explode(',', $obj->hasTranslate());
//			$attr['translate'] = Core_App::getInstance()->__t($attr['translate'], null, null, $classname);
//			die($obj->getTranslate());
//		}
		if($obj->hasInlineContent()&&strtolower($obj->getInlineContent())==true){
		//if($classname == 'Core_Page_Block_Html_Head_Script'){
			$obj->setInlineContent((string)$xml_block);
		//}
		}
		return($obj);
	}
	private function _getSimpleXmlFrom($xml){
		if(is_string($xml))
			$xml = $this->getSimpleXmlFromString($xml);
		//if(!$xml||!is_object($xml)||!is_a($xml, 'SimpleXMLElement'))//!$xml devuelve true cuando un objeto SimpleXMLElement esta vacio
		if(!isset($xml)||$xml===false||!is_object($xml)||!is_a($xml, 'SimpleXMLElement'))
			return null;
		return($xml);
	}
	public function getBlockFrom($block, $parent_block=null, $classname=''){
		return $this->_getBlockFrom($bloc, $parent_block, $classname);
	}
	private function _getBlockFrom($block, $parent_block=null, $classname=''){
		if(is_object($block)){
			if(is_a($block, 'Core_Block_Abstract')){
				return($block);
			}
			return null;
		}
		$xml_block = self::_getSimpleXmlFrom($block);
		if($xml_block==null)
			return(null);

		if(!isset($classname) || empty($classname)){
			$block_types = $this->_getBlockTypes($parent_block);
			$block_types_names = array_keys($block_types);
			$block_type = $xml_block->getName();
			if(!in_array($block_type, $block_types_names))
				return null;
			$classname = $block_types[$block_type];
		}

		$block = $this->_createBlock($xml_block, $classname);
		
		return($block?$block:null);
	}
	public function appendBlock($obj, &$parent_block, $classname=''){
		$_this = $this?$this:self::getInstance();
		if(!$parent_block||!is_object($parent_block)||!is_a($parent_block,'Core_Block_Abstract')){
			if(!is_string($parent_block))
				return null;
			if(!($parent_block = $_this->getBlock($parent_block)))
				return null;
		}
		$args = func_get_args();
		if(!($args[0] = $_this->_getBlockFrom($obj, $parent_block, $classname))){
			return(null);
		}
		return(call_user_method_array('_appendBlock', $_this, $args));
	}
	private function _appendBlock($obj, $parent_block){
		$obj = $parent_block->onBeforeInsertChild($obj);
		if($obj===null)
			return(null);
		$obj->setLayout($this);
		//var_dump($obj->getName());
		if($obj->hasName()){
			$obj->setName($obj->getData('name'));
			$this->addByName($obj);
			$obj->setNameInLayout($obj->getName());
			//var_dump($obj->getNameInLayout());
		}
		else{
			$obj->setIsAnonymous(true);
		}
		$siblingName = '';
		$after = true;
		if($obj->hasAfter()){
			$siblingName = $obj->getAfter();
			$after = true;
		}
		elseif($obj->hasBefore()){
			$siblingName = $obj->getBefore();
			$after = false;
		}
		$alias = '';
		if($obj->hasAs()){
			$alias = $obj->getAs();
			$obj->setIsAnonymous(false);
		}
		$parent_block->insert($obj, $siblingName, $after, $alias);
		$obj->onAfterLayoutLoad();
		return $obj;
	}
	
	private function addByName($block){
//		echo "agregando por nombre ".(get_class($block))."-".($block->getName())."\n";
		return($this->setBlock($block->getName(), $block));
	}
	public function setBlock($name, $block){
		$args = func_get_args();
		return(call_user_method_array('_setBlock', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _setBlock($name, $block){
//		echo "agregando por nombre ".(get_class($block))."-".($name)."\n";
		if(isset($this->by_name[$name])){
			if(!is_array($this->by_name[$name])){
				$this->by_name[$name] = array($this->by_name[$name]);
			}
			$this->by_name[$name][] = &$block;
		}
		else $this->by_name[$name] = &$block;
//		echo "setBlock->existe: ".(self::getBlock($name)).", tipo: ".gettype(self::getBlock($name))."\n";
		return($this);
	}
	public function unsetBlock($name){
		$args = func_get_args();
		return(call_user_method_array('_unsetBlock', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _unsetBlock($name){
		if(isset($this->by_name[$name])){
			$block = &$this->by_name[$name];
			if($block->getParentBlock()){
				$block->getParentBlock()->unsetChild($name);
			}
		}
		return($this);
	}
	public function getBlock($name){
		$args = func_get_args();
		return(call_user_method_array('_getBlock', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _getBlock($name){
//		echo 'cantidad: '.count($this->by_name)."\n";
//		foreach($this->by_name as $name=>$block){
//			echo 'list name: '.$name."\n";
//		}
//		echo "corroborar set: ".gettype($this->by_name[$name])."\n";
		if(isset($this->by_name[$name])){
			if(!is_array($this->by_name[$name]))
				return($this->by_name[$name]);
			return array_pop(array_slice($this->by_name[$name], -1));		
		}
		return(null);
	}
	public function getBlocks($name){
		$args = func_get_args();
		return(call_user_method_array('_getBlocks', ($obj=$this?$this:self::getInstance()), $args));
	}
	private function _getBlocks($name){
//		echo 'cantidad: '.count($this->by_name)."\n";
//		foreach($this->by_name as $name=>$block){
//			echo 'list name: '.$name."\n";
//		}
//		echo "corroborar set: ".gettype($this->by_name[$name])."\n";
		if(isset($this->by_name[$name])){
			if(!is_array($this->by_name[$name]))
				return(array($this->by_name[$name]));
			return $this->by_name[$name];		
		}
		return(null);
	}
}
?>