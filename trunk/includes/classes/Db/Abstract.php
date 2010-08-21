<?
abstract class Db_Abstract extends Core_Object{
	abstract public function open();
	abstract public function close();
	abstract public function exec($sql);
	public function transactionStart(){}
	//public function transactionCommit(){}//si se hace transaction end sin haber llamado rollback dentro, entonces es commit
	public function transactionRollback(){}
	public function transtactionIsActive(){return true;}//siempre y cuando no se haya rollbackeado es activa
	public function transactionEnd(){}
	//metodos normales para conectar / desconectar
	abstract function getCompare(Db_Compare_Abstract $compare);
	public function nameToString($name){return($name);}
	public function valueToString($value){return($value);}
	public function formatDate($format, $value){return(null);}
	
	private $_locks = null;
	public function lockRead($table, $force=true, $alias=null){
		if(!$this->_locks&&!$force){
			return;
		}
		$this->lockTables($table, 'READ', $alias);
	}
	public function lockWrite($table, $force=true, $alias=null){
		if(!$this->_locks&&!$force){
			return;
		}
		$this->lockTables($table, 'WRITE', $alias);
	}
	public function unlockTables(){
		$this->_locks = null;
		$this->_unlockTables();
	}
	private function lockTables($table, $type='READ', $alias=null){
		$type = strtolower($type);
		if(!in_array($type, array('read','write')))
			$type = 'write';
		$tables = array();
		if(is_string($table)){
			if(isset($alias))
				$tables[$alias] = $table;
			else $tables = array($table);
		}	
		else $tables = $table;
		foreach($tables as $alias=>&$table){
			if(isset($this->_locks['write'][$table]))//si tiene el maximo tipo de lock entonces no tiene sentido volver a lockearla
				continue;
			$this->_lockTable($table, $type, !is_int($alias)?$alias:null);
			$this->_locks[$type][$table] = true;
		}unset($table);
	}
	abstract protected function _lockTable($table, $type='READ', $alias=null);
	abstract protected function _unlockTables();
}
?>