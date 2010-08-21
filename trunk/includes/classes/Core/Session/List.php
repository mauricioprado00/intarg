<?php 
class Core_Session_List extends Core_Singleton{
	public static function createList($id=null, $type='default', $context = array('Core_Session_List')){
		if(!isset($id)){
			$id = self::createId($type, $context);
		}
		$lista = array();
		$varname = $id;
		if(!$type)
			$type = 'default';
		$context[] = $type;
		Core_Session::setVarMulticontext($varname, $lista, $context);
		return $id;
	}
	public static function deleteList($id, $type='default', $context = array('Core_Session_List')){
		$varname = $id;
		if(!$type)
			$type = 'default';
		$context[] = $type;
		Core_Session::setVarMulticontext($varname, null, $context);
	}
	private static function createId($type='default', $context = array('Core_Session_List')){
		$ids = self::listarIds($type, $context);
		$i = 1;
		if($ids)
			while(in_array($i, $ids)){
				$i++;
			}
		return $i;
	}
	public static function clearLists($type='default', $context = array('Core_Session_List')){
		Core_Session::setVarMulticontext($type, null, $context);
	}
	private static function listarIds($type='default', $context = array('Core_Session_List')){
		if(!$type)
			$type = 'default';
		$context[] = $type;
		return Core_Session::listContextVars($context);
	}
	public static function addToList($key=null, $elemento, $id, $type='default', $context = array('Core_Session_List')){
		$varname = $id;
		if(!$type)
			$type = 'default';
		$context[] = $type;
		$lista = Core_Session::getVarMulticontext($varname, $context);
		if(!isset($key)){
			$keys = array_keys($lista);
			$lista[] = $elemento;
			$nuevo_key = array_diff(array_keys($lista), $keys);
			$key = array_pop($nuevo_key);
		}
		else{
			$lista[$key] = $elemento;
		}
		Core_Session::setVarMulticontext($varname, $lista, $context);
		return $key;	
	}
	public static function removeFromList($key, $id, $type='default', $context = array('Core_Session_List')){
		$varname = $id;
		if(!$type)
			$type = 'default';
		$context[] = $type;
		$lista = Core_Session::getVarMulticontext($varname, $context);
		$keys = array_keys($lista);
		$find = array_search($key, $keys);
		if($find!==null && $find!==false){
			$values = array_values($lista);
			array_splice($keys, $find, 1 );
			array_splice($values, $find, 1 );
			$lista = array();
			foreach($keys as $idx=>$key)
				$lista[$key] = $values[$idx];
			Core_Session::setVarMulticontext($varname, $lista, $context);
			return true;
		}
		return false;
	}
	public static function getList($id, $type='default', $context = array('Core_Session_List')){
		$varname = $id;
		if(!$type)
			$type = 'default';
		$context[] = $type;
		$lista = Core_Session::getVarMulticontext($varname, $context);
		return $lista;
	}
	public static function setList($new_list, $id, $type='default', $context = array('Core_Session_List')){
		if(!is_array($new_list))
			return false;
		$varname = $id;
		if(!$type)
			$type = 'default';
		$context[] = $type;
		Core_Session::setVarMulticontext($varname, $new_list, $context);
		return true;
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}
?>