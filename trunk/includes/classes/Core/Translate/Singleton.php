<?php

class Core_Translate_Singleton extends Core_Singleton{
	private $col = null;
	public function __construct(){
		$this->col = new Core_Collection();
	}
	public function initialize($data){
		foreach($data as $weight=>$data){
			foreach($data as $class){
				if(method_exists($class, 'getInstance')){
					$o = (call_user_func(array($class, 'getInstance')));
				}
				else $o = new $class;
				if($o instanceof Core_Translate_Singleton){
					//var_dump(get_class($o));
					$this->col->addItem($o);
				}
				//var_dump(get_class($o));

				
			}
		}
		//var_dump($data);
		//die();
	}
	public function addTranslator($trans){
		if($trans instanceof Core_Translate_Singleton){
			$this->col->addItem($trans);
		}
	}
	public function translate($texto, $vars=null, $explicacion=null, $contexto=null){
		foreach($this->col as $item){
			//var_dump(get_class($item));
			if(($t = $item->__cache($texto, $vars, $explicacion, $contexto))){
				break;
			}
		}
		if(!isset($t))
			$t = $this->__cache($texto, $vars, $explicacion, $contexto);
		return $this->addVars($t, $vars);
	}
	protected function addVars($texto, $vars){
		return c(new Core_Object($vars))->DataStrtr(null, $texto);
	}
	private $cache = array();
	private final function __cache($texto, $vars, $explicacion, $contexto){
		if(!isset($this->cache[$texto])){
			if(($t = $this->__t($texto, $vars, $explicacion, $contexto))!==null){
				$this->cache[$texto] = $t;
			}
		}
		return $this->cache[$texto];
	}
	public function __t($texto, $vars, $explicacion, $contexto){
		return '__t: '.$texto;
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}

?>