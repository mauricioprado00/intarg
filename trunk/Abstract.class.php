<?
abstract class Db_Model_Abstract extends Core_Object{
	private $__db_model_abstract_where;
	private $__db_model_abstract_db;
	abstract function getDbTableName();
	public function __construct(){
		$this->init();
	}
	protected function init(){;}
	protected function setDb(Db_Abstract $db){
		$this->__db_model_abstract_db = $db;
	}
	protected function getDb(){
		return($this->__db_model_abstract_db);
	}
	private function __data_to_str_list($data, $separator=','){
		$sets = array();
		foreach($data as $key=>$value){
			if($value===null)
				continue;
			$sets[] = '`'.$key.'`=\''.mysql_real_escape_string($value).'\'';
		}
		$sets = implode(' '.$separator.' ', $sets);
		if(!$sets)
			return(false);
		return($sets);
	}
	protected function afterReplace($data){;}
	public function replace($data=null){
		if($data===null){
			$data = $this->getData();
		}
		$this->getDb()->open();
		if(!($tablename=$this->getDbTableName()) || !$sets = $this->__data_to_str_list($data))
			return(false);
		$sql = 'REPLACE INTO `'.$tablename.'` SET '.$sets.';';
//		return($this->query($sql));
		$ret = $this->getDb()->exec($sql);
		$this->afterReplace($data);
		$this->getDb()->close();
		return($ret);
	}
	public function delete($data=null){
		$this->getDb()->open();
		if($data===null){
			$data = $this->getData();
		}
		if(!($tablename=$this->getDbTableName()) || !$wheres = $this->__data_to_str_list($data,'AND'))
			return(false);
		$sql = 'DELETE FROM `'.$tablename.'` WHERE '.$wheres.';';
		$r = $this->getDb()->exec($sql);
		$this->getDb()->close();
		return($r);
	}
	public function exists($data=null){
		$this->getDb()->open();
		if($data===null){
			$data = $this->getData();
		}
		if(!($tablename=$this->getDbTableName()) || !$wheres = $this->__data_to_str_list($data,'AND'))
			return(false);
		$sql = 'SELECT count(*) as cantidad FROM `'.$tablename.'` WHERE '.$wheres.';';
		$ret = false;
		$r = $this->getDb()->exec($sql);
		if($r){
			if($fila = $this->getDb()->fetchAssociative($r)){
				$ret = $fila['cantidad']>0;
			}
		}
		$this->getDb()->close();
		return($ret);
	}
	public function load($data=null){
		$arr_where = array();
		foreach($this->getData() as $key=>$value)
			if($value!==null)
				$arr_where[] = Db_Helper::equal($key, $value);
		call_user_method_array('setWhere',$this, $arr_where);
		$this->setWhereByArray($arr_where)->setWhereLogicalOperator('AND');
		$l = $this->search();
		if($l && count($l)){
			$this->loadFromArray($l[0]->getData());
			return(true);
		}
		$this->unsetData();
		return(false);
	}
	public function setWhereByArray($arr){
		$this->__db_model_abstract_where = new Db_Where($this->getDb());
		call_user_method_array('set',$this->__db_model_abstract_where, $arr);
		return($this);
	}
	public function setWhere(){
		$x = func_get_args();
		$this->setWhereByArray($x);
		return($this);
	}
	public function setWhereLogicalOperator($op){
		if(!isset($this->__db_model_abstract_where))
			return(false);
		$this->__db_model_abstract_where->setLogicalOperator($op);
		return(true);
	}
	protected function getWhere(){
		return($this->__db_model_abstract_where);
	}
	public function searchCount($limit=null, $start=0){
		$this->getDb()->open();
		if($this->getWhere())
			$str_where = $this->getWhere()->toString($this->getData());
		$str_where = $str_where?' WHERE '.$str_where:'';
		if($columns!==null && is_array($columns) && count($columns)){
			foreach($columns as $idx=>$column)
				$columns[$idx] = $this->getDb()->nameToString($column);
			$columns = implode(', ', $columns);
		}
		else $columns = '*';
		$sql = 'SELECT count(*) as cantidad FROM '.$this->getDb()->nameToString($this->getDbTableName()).$str_where.$order.$limit;
		$r = $this->getDb()->exec($sql);
		if($r){
			$ret = array();
			while($fila = $this->getDb()->fetchAssociative($r)){
				$ret = $fila["cantidad"];
			}
		}
		$this->getDb()->close();
		return($ret);
	}
	public function search($order_by=null, $order_dir='ASC', $limit=null, $start=0, $as_objects=true, $columns=null){
		$this->getDb()->open();
		if($this->getWhere())
			$str_where = $this->getWhere()->toString($this->getData());
		//$order = $order_by?' order by '.($this->db->valueToString($order_by)):'';
		$order_by = $order_by!==null?' ORDER BY '.$order_by.($order_dir&&in_array(trim(strtoupper($order_dir)),array('ASC','DESC'))?' '.$order_dir:''):'';
		//$limit = $limit!==null&&$start!==null&&$start>0?(' limit '.$this->getDb()->valueToString($start).', '.$this->getDb()->valueToString($limit)):'';
		$limit = $limit!==null&&$start!==null&&$start>=0&&$limit>0?(' limit '.($start+0).', '.($limit+0)):'';
		$str_where = $str_where?' WHERE '.$str_where:'';
		if($columns!==null && is_array($columns) && count($columns)){
			foreach($columns as $idx=>$column)
				$columns[$idx] = $this->getDb()->nameToString($column);
			$columns = implode(', ', $columns);
		}
		else $columns = '*';
		$sql = 'SELECT '.$columns.' FROM '.$this->getDb()->nameToString($this->getDbTableName()).$str_where.$order.$limit;
		$r = $this->getDb()->exec($sql);
		if($r){
			if($as_objects){
				$fields = array_keys($this->getData());
				$class = get_class($this);
				$ret = array();
				while($fila = $this->getDb()->fetchAssociative($r)){
					$new = new $class();
					$ret[] = $new->loadFromArray($fila);
				}
			}
			else{
				$ret = array();
				while($fila = $this->getDb()->fetchAssociative($r)){
					$ret[] = $fila;
				}
			}
		}
		$this->getDb()->close();
		return($ret);
	}
	public function fillFieldFromDb($key, $value){
		return(false);
	}
	public function loadFromArray($arr, $check_properties=true){
		if($check_properties===null){
			$check_properties = count($this->getData())!=0;
		}
		if($check_properties){
			foreach($arr as $var=>$value){
				//var_dump(key_exists($var, $this->getData()), $var, $this->getData());
				if(key_exists($var, $this->getData())){
					if(!$this->fillFieldFromDb($var, $value))
						$this->setData($var, $value);
				}
			}
		}
		else{
			foreach($arr as $var=>$value){
				if(!$this->fillFieldFromDb($var, $value))
					$this->setData($var, $value);
			}
		}
		return($this);
	}

}
?>