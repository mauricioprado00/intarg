<?
class Core_Session extends Base_Singleton{
//	public static function getConfig(){
//		$args = func_get_args();
//		return(call_user_method('_getConfig', self::getInstance(), $x = $args));
//	}
//	private function _getConfig(){
//		return($this->config);
//	}

  /**
   * Core_Session::getVar($varname,$context='global',...mas contextos)
   *
   * @param mixed $varname nombre de la variable
   * @param mixed $context contexto en el que se llama
   * @return valor de la variable (null si no existe o si estan mal los parametros)
   */
	public static function listContextVars($context='global'){
		$contexts = func_get_args();
		if(count($contexts)==1&&is_array($contexts[0]))
			$contexts = $contexts[0];
		$values = &$_SESSION;
		foreach($contexts as $context){
			if(!isset($values[$context]))
				return(null);
			$values = &$values[$context];
		}
		return(array_keys($values));
	}
	public static function listContextValues($modo='array', $context='global'){
		$contexts = func_get_args();
		$contexts = array_slice($contexts, 1);
		if(count($contexts)==1&&is_array($contexts[0]))
			$contexts = $contexts[0];
		$values = &$_SESSION;
		foreach($contexts as $context){
			if(!isset($values[$context])){
				return(null);
			}
			$values = &$values[$context];
		}
		switch($modo){
			case 'array':{
				$ret = array();
				foreach($values as $varname=>$value){
					$ret[] = array(
						'varname'=>$varname,
						'value'=>self::getVarMulticontext($varname, $contexts)
					);
				}
				break;
			}
			case 'Core_Object':{
				$ret = array();
				foreach($values as $varname=>$value){
					$oret = new Core_Object();
					$oret->setVarname($varname);
					$oret->setValue(self::getVarMulticontext($varname, $contexts));
					//$oret->setData($varname, self::getVarMulticontext($varname, $contexts));
					$ret[] = $oret;
				}
			}
		}
		return($ret);
	}
	public static function getVar($varname, $context='global'){
		if(isset($context)&&is_string($context)){
			$args = func_get_args();
			$contexts = array_slice($args, 1);
			return(self::getVarMulticontext($varname, $contexts));
		}
		return(null);
	}
	public static function getVarMulticontext($varname, $contexts=array()){
		if(!is_array($contexts)||!count($contexts))
			return(null);
		if($varname!=null)
			$contexts = array_merge($contexts, array($varname));
		$values = &$_SESSION;
		foreach($contexts as $context){
			if(!isset($values[$context]))
				return(null);
			$values = &$values[$context];
		}
		if($values===null)
			return($values);
		return(unserialize($values));
	}
  /**
   * Core_Session::setVar()
   *
   * @param mixed $varname nombre de la variable
   * @param mixed $value valor a setear
   * @param string $context contexto en el que se llama
   * @return true si esta bien, false si no
   */
	public static function setVar($varname, $value, $context='global'){
		if(isset($context)&&is_string($context)){
			/*if(is_object($value))*/
			$args = func_get_args();
			$contexts = array_slice($args, 2);
			return(self::setVarMulticontext($varname, $value, $contexts));
		}
		return(false);
	}
	public static function setVarMulticontext($varname, $value, $contexts=array()){
		if(!is_array($contexts)||!count($contexts))
			return(null);
		if($varname!=null)
			$contexts = array_merge($contexts, array($varname));
		$parent_context = null;
		$values = &$_SESSION;
		//var_dump($contexts);
		foreach($contexts as $context){
			$parent_context = &$values;
			$values = &$values[$context];
		}
		if($value===null){
			//$values = null;
			$find = array_search($context, array_keys($parent_context));
			if($find!==null && $find!==false){
				$values = array_values($parent_context);
				$keys = array_keys($parent_context);
				array_splice($keys, $find, 1 );
				array_splice($values, $find, 1 );
				$parent_context = array();
				foreach($keys as $idx=>$key)
					$parent_context[$key] = $values[$idx];
			}
		}
		else
			$values = serialize($value);
		return(true);
	}
	public function getId(){
		return session_id();
	}

	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}
?>