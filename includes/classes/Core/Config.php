<?
class Core_Config{
	private $global;
	function __construct(){
		$this->global = array(
			'routers'=>array(),
			'modules'=>array(),
			'modules_library'=>array(),
			'updates'=>array(),
			'layout_updates'=>array(),
			'block_types'=>array(),
			'update_to_modulo'=>array(),
			'layout_updates_to_modulo'=>array(),
		);
	}
	private $updates = array();
	private function beginUpdate(){
		$this->updates[] = array();
	}
	private function getModuloOfConfigUpdate($file){
		$modulo = $this->global['update_to_modulo'][$file];
		$modulo = $modulo===null?'Core':$modulo;
		return($modulo);
	}
	private function addUpdate($config_file, $modulo=null){
		$this->global['update_to_modulo'][$config_file] = $modulo;
		$this->global['updates'] = $config_file;
		$this->updates[count($this->updates)-1][] = $config_file;
	}
	private function getCurrentUpdates(){
		return($this->updates[count($this->updates)-1]);
	}
	private function endUpdate(){
		$this->updates = array_slice($this->updates, 0, -1);
	}
	private $current_config_file = null;
	private function getCurrentConfigFile(){
		return($this->current_config_file);
	}
	private function getCurrentModulo(){
		return($this->getModuloOfConfigUpdate($this->getCurrentConfigFile()));
	}
	private function addLayoutToConfig($file, $modo){
		$this->global['layout_updates'][] = array(
			'file'=>$file, 
			'modo'=>$modo
		);
		$this->global['layout_updates_to_modulo'][$file] = $this->getCurrentModulo();
	}
	public function getLayoutModulo($file){
		return($this->global['layout_updates_to_modulo'][$file]);
	}
	private function addBlockTypeToConfig($type, $class, $modulo=null){
		$modulo = $modulo===null?$this->getCurrentModulo():$modulo;
		$this->global['block_types']['by_modulo'][$modulo][$type][] = $class;
		$this->global['block_types']['global'][$type][] = $class;
	}
	private function addModuleToConfig($module_name, $module_library){
		$this->global['modules_library'][$module_name] = $module_library;
	}
	public function getModules(){
		return($this->global['modules_library']);
	}
	public function getBlockTypes(){
		$block_types = array();
		foreach($this->global['block_types']['global'] as $type=>$data){
			$block_types[] = $type;
		}
		return($block_types);
	}
	public function getBlockTypeClass($type, $modulo=null){
		$class = null;
		if($modulo!==null){
			if(!isset($this->global['block_types']['by_modulo'][$modulo])){
				$modulo = null;
			}
			else{
				$class = $this->global['block_types']['by_modulo'][$modulo][$type];
				if($class!==null && count($class)){
					return($class[0]);
				}
				else{
					$modulo = null;
				};
			}
		}
		$class = $this->global['block_types']['global'][$type];
		if($class!==null && count($class)){
			return($class[0]);
		}
		echo "no existe el tipo de bloque $type\n";
		die();
	}
	private function loadFromCache(){
		$file = CFG_PATH_ROOT.'/cache/config/cache_config.serialized';
		if(!file_exists($file))
			return(null);
		$cont = file_get_contents($file);
		if(!$cont)
			return(false);
		$arr_data = unserialize($cont);
		foreach($arr_data as $key=>$value)
			$this->$key = $value;
		return(true);
	}
	private function saveToCache(){
		$file = CFG_PATH_ROOT.'/cache/config/cache_config.serialized';
		$arr_data = array();
		foreach($this as $key=>$data){
			$arr_data[$key] = $data;
		}
		if(!file_exists(dirname($file)))
			Base_FM::mkdir(dirname($file), 0777, true);
		Base_FM::file_put_contents($file, serialize($arr_data));
		//chmod($file, 0777);
	}
	function load($file, $cache=false){
		$this->current_config_file = $file;
		static $ident = 0;
		if($ident===0){
			if($cache){
				if($this->loadFromCache()===true){
					return;
				}
			}
		}
		$ident++;
		$this->beginUpdate();
		//var_dump($file);
		if($xml = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA)){
			$globals = $xml->xpath("/global");
			foreach($globals as $key => $global) {
	        	$this->addGlobal($global);
	        }
	        //$updates = $this->global['updates'];
	        //$this->global['updates'] = array();
	        //if($ident===1){
	        $updates = $this->getCurrentUpdates();
				foreach($updates as $config_file){
					//echo "child $config_file of $file\n";
					$this->load($config_file);
				}
			//}
			//$this->global['updates'] = array_merge($updates, $this->global['updates']);
			//var_dump($this->global['updates']);
			$ident--;
			if($ident===0){
				if($cache){
					$this->saveToCache();
				}
			}
	        return true;   
	    }
	    else{
			echo "no se puede cargar configuracion $file";die();
		}
		$this->endUpdate();
		return(false);
	}
	private function addGlobal($global){
        foreach($global as $key=>$element) {
            //$ret[$i][$key] = (string)$element;
            switch($key){
				case 'routers':{
					$this->addRouters($element);
					break;
				}
				case 'modules':{
					$this->addModules($element);
					break;
				}
				case 'layouts':{
					$this->addLayouts($element);
					break;
				}
				case 'block':{
					$this->addBlockType($element);
					break;
				}
			}
            $i++;
        }
	}
	private function addBlockType($element){
		$type = $element->xpath('./type');
		if(!$type)
			return;
		$type = (string)array_pop($type);		
		$class = $element->xpath('./class');
		if(!$class)
			return;
		$class = (string)array_pop($class);	
		$this->addBlockTypeToConfig($type, $class);
		//var_dump($element, $type, $class, $this->getCurrentModulo());
	}
	private function addLayouts($layouts){
		//echo "agreando layouts\n";
		foreach($layouts->children() as $modo=>$value){
			//echo "layout: ".(string)$value."\n";
//			Core_App::getLayout()
//				->addLayout((string)$value,$modo);
			$this->addLayoutToConfig((string)$value, $modo);
//			$this->global['layout_updates'][] = array(
//				'file'=>(string)$value, 
//				'modo'=>$modo
//			);
		}
	}
	private function addRouters($routers){
		//echo "agregar routers ";
		//var_dump($routers);
		foreach($routers->children() as $classname=>$value){
			$path = ((string)$value);
			$arr_path = explode('/', $path);
			$r = &$this->global['routers'];
			$c = count($subpath);
			foreach($arr_path as $i=>$subpath){
				if(!isset($r[$subpath]))
					$r[$subpath] = array();
				$r = &$r[$subpath];
			}
			$r['@classname'] = $classname;
		}
		//var_dump($routers->admin);
	}
	private function addModules($modules){
		//echo "agregar modulos";
        foreach($modules as $module_name=>$element) {
			$module_name = explode('_', $module_name);
			foreach($module_name as $idx=>$arr_el)
				$module_name[$idx] = ucfirst(($arr_el));
			//var_dump($arr);
			$module_path = implode('/', $module_name);
			$module_library = implode('.', $module_name);

        	//$module_name = (ucfirst(strtolower($module_name)));
        	$module_name = implode('_', $module_name);
            //$ret[$i][$key] = (string)$element;
            if(!isset($this->global['modules'][$module_name]))
            	$this->global['modules'][$module_name] = array();
            foreach($element as $subkey=>$subelement){
            	switch($subkey){
					case 'global':{
						$this->addGlobal($subelement);
						break;
					}
				}
			}
			
			$this->addModuleToConfig($module_name, $module_library);
			$config_file = (CFG_PATH_ROOT.CONF_PATH_CLASSES.$module_path.'/etc/config.xml');
			//var_dump($config_file);
			if(file_exists($config_file)){
				//$this->global['updates'][] = $config_file;
				$this->addUpdate($config_file, $module_library);
			}
        }
	}
	public function getRouteData(){
		return($this->global['routers']);
	}
	public function getLayoutUpdates(){
		return($this->global['layout_updates']);
	}
}
?>