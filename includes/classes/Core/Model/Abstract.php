<?
//abstract class Core_Model_Abstract extends Granguia_Db_Model_Abstract{}
abstract class Core_Model_Abstract extends Inta_Db_Model_Abstract{}
/*
abstract class Core_Model_Abstract extends Core_Object{
	abstract public function getTableName();
	private function __data_to_set_list($data){
		$sets = array();
		foreach($data as $key=>$value){
			if($value===null)
				continue;
			$sets[] = '`'.$key.'`=\''.mysql_real_escape_string($value).'\'';
		}
		$sets = implode(', ', $sets);
		if(!$sets)
			return(false);
		return($sets);
	}
	private function query($sql){
		file_put_contents(CFG_PATH_ROOT.'/queryes.log', var_export(array($sql,$this->__getDBHandler()),true), FILE_APPEND);
		if(!$result = mysql_query($sql, $this->__getDBHandler()))
			return(null);
		return $result;
	}
	private function fetchAssociative($res){
		
	}
	public function replace($data=null){
		if($data===null){
			$data = $this->getData();
		}
		$this->connect();
		if(!($tablename=$this->getTableName()) || !$sets = $this->__data_to_set_list($data))
			return(false);
		$sql = 'REPLACE INTO `'.$tablename.'` SET '.$sets.';';
//		return($this->query($sql));
		$ret = $this->query($sql);
		$this->disconnect();
		return($ret);
		
	}
	public function delete($data=null){
		if($data===null){
			$data = $this->getData();
		}
		if(!($tablename=$this->getTableName()) || !$wheres = $this->__data_to_set_list($data))
			return(false);
		$sql = 'DELETE FROM `'.$tablename.'` WHERE '.$wheres.';';
		return($this->query($sql));
	}
	public function search($where=null, $orderBy = null, $orderDir = 'ASC', $limit = null, $start = 0){
		if(!($tablename=$this->getTableName()))
			return(false);
		$where = $where!==null?' WHERE '.$where:'';
		$order = $orderBy!==null?' ORDER BY '.$orderBy.($orderDir?' '.$orderDir:''):'';
		$limit = $limit!==null?$start.', '.$limit:'';
		$sql = "SELECT * FROM ";
		$r = $this->query($sql);
		$ret = array();
		while($arr = $this->fetchAssociative($res)){
			$class = get_class($this);
			$x = new $class();
			$ret[] = $x->loadFromArray($arr);
		}
		if(!$ret)
			return(null);
		return($ret);
	}
	public function loadFromArray($arr, $check_properties=true){
		if($check_properties===null){
			$check_properties = count($this->getData())==0;
		}
		if($check_properties){
			foreach($arr as $var=>$value)
				if(key_exists($var, $this->getData()))
					$this->setData($var, $value);
		}
		else{
			foreach($arr as $var=>$value){
				$this->setData($var, $value);
			}
		}
		return($this);
	}
	//Mat
	private static $____db_handler = null;
	public static function connect($host = null, $user = null, $password = null, $db = null)
	{
		$host = $host !== null ? $host : DB_HOST;
		$user = $user !== null ? $user :DB_USER;
		$password = $password !== null ? $password : DB_PASS;
		$db = $db !== null ? $db : DB_DATABASE;
//		echo $host ," ",$user," ",$password," ",$db;
		$handler = mysql_connect($host,$user,$password);
		if(!mysql_select_db($db,$handler))
			return(null);
		self::$____db_handler = $handler; 
		return ($this);
	}
	private function __getDBHandler(){
		return(self::$____db_handler);
	}
	public static function disconnect($handler = null)
	{
		mysql_close(self::$____db_handler);
	}
}
*/
?>