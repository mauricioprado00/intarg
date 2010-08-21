<?
abstract class Db_Model_Table_Abstract extends Db_Model_Abstract{
	abstract function getDbTableName();
	protected function getFrom(){
		if(!$this->getDbTableName())
			return(null);
		return($this->getDb()->nameToString($this->getDbTableName()));
	}
	
	protected function init(){
		parent::init();
	}
	protected function getColumnSelect(){
		$columns = array_keys($this->getData());
		return($this->columnsToSelect($columns));
	}
	protected function beforeReplace(&$data){;}
	protected function afterReplace($data,$resultado=null){;}
	protected function beforeChange(&$data){;}
	protected function afterChange($data,$resultado=null){;}
	protected function beforeUpdate(&$data){;}
	protected function afterUpdate($data,$resultado=null){;}
	//{
//		$arr_where = array();
//		foreach($this->getData() as $key=>$value)
//			if($value!==null)
//				$arr_where[] = Db_Helper::equal($key, $value);
//		call_user_method_array('setWhere',$this, $arr_where);
//		$this->setWhereByArray($arr_where)->setWhereLogicalOperator('AND');
//		$l = $this->search();
//		if($l && count($l)){
//			$this->loadFromArray($l[0]->getData());
//			return(true);
//		}
//		$this->resetData();
//		return(false);
//	}
	public function update($data=null, $use_null_values=false, $match_fields=array('id')){
		if($data===null){
			$data = $this->getData();
		}
		$this->beforeChange($data);
		$this->beforeUpdate($data);
		$arr_where = array();
		foreach($match_fields as $field){
			$value = $this->getData($field);
			//if($value!==null)
			$arr_where[] = Db_Helper::equal($field, $value);
		}
		call_user_method_array('setWhere',$this, $arr_where);
		$this->setWhereByArray($arr_where)->setWhereLogicalOperator('AND');

		$this->getDb()->open();
		if(!($tablename=$this->getDbTableName()) || !$sets = $this->__data_to_str_list($data,',',$use_null_values))
			return(false);
		if($this->getWhere())
			//$str_where = $this->getWhere()->toString($this->getData());
			$str_where = $this->getWhere()->toString($this->getData(),$this->getAttrToFieldTranslationTable());
		$str_where = $str_where?' WHERE '.$str_where:'';

		$sql = 'UPDATE `'.$tablename.'` SET '.$sets.$str_where;
		//var_dump($sql);
//		return($this->query($sql));
		$this->lockWrite(false);
		$ret = $this->getDb()->exec($sql);
		$this->afterUpdate($data, $ret);
		$this->afterChange($data, $ret);
		//$this->afterReplace($data);
		$this->getDb()->close();
		return($ret);
	}
	public function insert($data=null,$use_null_values=false, $get_sql=false){
		if($data===null){
			$data = $this->getData();
		}
		$this->beforeChange($data);
		$this->beforeReplace($data);
		$this->getDb()->open();
		if(!($tablename=$this->getDbTableName()) || !$sets = $this->__data_to_str_list($data,',',$use_null_values))
			return(false);
		$sql = 'INSERT INTO `'.$tablename.'` SET '.$sets.';';
		if($get_sql)
			return($sql);
//		return($this->query($sql));
		$this->lockWrite(false);
		$ret = $this->getDb()->exec($sql);
		$this->afterReplace($data, $ret);
		$this->afterChange($data, $ret);
		$this->getDb()->close();
		return($ret);
	}
	public function replace($data=null,$use_null_values=false, $get_sql=false){
		if($data===null){
			$data = $this->getData();
		}
		$this->beforeChange($data);
		$this->beforeReplace($data);
		$this->getDb()->open();
		if(!($tablename=$this->getDbTableName()) || !$sets = $this->__data_to_str_list($data,',',$use_null_values))
			return(false);
		$sql = 'REPLACE INTO `'.$tablename.'` SET '.$sets.';';
		if($get_sql)
			return($sql);
//		return($this->query($sql));
		$this->lockWrite(false);
		$ret = $this->getDb()->exec($sql);
		$this->afterReplace($data, $ret);
		$this->afterChange($data, $ret);
		$this->getDb()->close();
		return($ret);
	}
	public function truncate(){
		$this->getDb()->open();
		if(!($tablename=$this->getDbTableName()))
			return(false);
		$sql = 'TRUNCATE TABLE `'.$tablename.'`;';
		$this->lockWrite(false);
		$ret = $this->getDb()->exec($sql);
		$this->getDb()->close();
		return($ret);
	}
	protected function beforeDelete($data){;}
	public function delete($data=null){
		if($data===null){
			$data = $this->getData();
		}
		$ret = $this->beforeDelete($data);
		if($ret===false){
			return(false);
		}
		$this->getDb()->open();
		if(!($tablename=$this->getDbTableName()) || !$wheres = $this->__data_to_str_list($data,'AND'))
			return(false);
		$sql = 'DELETE FROM `'.$tablename.'` WHERE '.$wheres.';';
		$this->lockWrite(false);
		$r = $this->getDb()->exec($sql);
		$this->getDb()->close();
		return($r);
	}
	public function lockWrite($force=true, $alias=null){
		return $this->getDb()->lockWrite($this->getDbTableName(), $force, $alias);
//		echo ('lock tables '.$this->getDbTableName().' WRITE')."\n";
//		$this->getDb()->exec('lock tables '.$this->getDbTableName().' WRITE');
	}
	public function lockRead($force=true, $alias=null){
		return $this->getDb()->lockRead($this->getDbTableName(), $force, $alias);
//		echo ('lock tables '.$this->getDbTableName().' READ')."\n";
//		$this->getDb()->exec('lock tables '.$this->getDbTableName().' READ');
	}
}
?>