<?
abstract class Db_Model_Abstract extends Core_Object{
	private $__db_model_abstract_where;
	private $__db_model_abstract_db;
	abstract protected function getFrom();
	protected function lockWrite(){}
	abstract protected function lockRead();
	public function __construct(){
		parent::__construct();
		$this->init();
	}
	protected function init(){;}
	protected function setDb(Db_Abstract $db){
		$this->__db_model_abstract_db = $db;
	}
	protected function getDb(){
		return($this->__db_model_abstract_db);
	}
	public function formatDateOut($format, $field){
		return($this->getDb()->formatDate($format, $this->getData($field)));
	}
	protected function __data_to_str_list($data, $separator=',', $use_null_values=false){
		$sets = array();
		foreach($data as $key=>$value){
			if($value===null){
				if($use_null_values){
					if($use_null_values===true || (is_array($use_null_values)&&in_array($key, $use_null_values))){
						$sets[] = '`'.$key.'`=NULL';
					}
				}
				continue;
			}
			$sets[] = '`'.$key.'`=\''.mysql_real_escape_string($value).'\'';
		}
		$sets = implode(' '.$separator.' ', $sets);
		if(!$sets)
			return(false);
		return($sets);
	}
	public function exists($data=null){
		$this->getDb()->open();
		if($data===null){
			$data = $this->getData();
		}
		if(!($tablename=$this->getFrom()) || !$wheres = $this->__data_to_str_list($data,'AND'))
			return(false);
		$sql = 'SELECT count(*) as cantidad FROM `'.$tablename.'` WHERE '.$wheres.';';
		$ret = false;
		$this->lockRead(false);
		$r = $this->getDb()->exec($sql);
		if($r){
			if($fila = $this->getDb()->fetchAssociative($r)){
				$ret = $fila['cantidad']>0;
			}
		}
		$this->getDb()->close();
		return($ret);
	}
	public function load($data=null, $only_if_unique=false){
		$arr_where = array();
		foreach($this->getData() as $key=>$value)
			if($value!==null)
				$arr_where[] = Db_Helper::equal($key, $value);
		call_user_method_array('setWhere',$this, $arr_where);
		$this->setWhereByArray($arr_where)->setWhereLogicalOperator('AND');
		//var_dump($this->searchGetSql());
		$l = $this->search();
		if($l && count($l) && (!$only_if_unique || ($only_if_unique&&count($l)==1))){
			$this->loadFromArray($l[0]->getData());
			$this->resetWhere();
			return(true);
		}
		$this->resetData();
		return(false);
	}
	public function resetWhere(){
		$this->__db_model_abstract_where = null;
	}
	public function setWhereByArray($arr){
		$this->__db_model_abstract_where = new Db_Where($this->getDb());
		if(count($arr))
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
	abstract protected function getColumnSelect();
	public function searchCount($limit=null, $start=0){
		$this->getDb()->open();
		if($this->getWhere())
			//$str_where = $this->getWhere()->toString($this->getData());
			$str_where = $this->getWhere()->toString($this->getData(),$this->getAttrToFieldTranslationTable());
		$str_where = $str_where?' WHERE '.$str_where:'';
		$sql = 'SELECT count(*) as cantidad FROM '.$this->getFrom().$str_where/*.$order*/.$limit;
		$this->lockRead(false);
		$r = $this->getDb()->exec($sql);
		$ret = null;
		if($r){
			$ret = array();
			while($fila = $this->getDb()->fetchAssociative($r)){
				$ret = $fila["cantidad"];
			}
		}
		$this->getDb()->close();
		return($ret);
	}
	protected function setGroupBy(){
		echo "group by no soportado por la clase ".get_class($this)."\n";
		die();
	}
	protected function getGroupBy(){
		return("");
	}
	protected function columnsToSelect($columns){
		foreach($columns as $alias=>$column){
			if(is_int($alias))
				$columns[$alias] = $this->getDb()->nameToString($column);
			else//tiene un alias
				$columns[$alias] = $this->getDb()->nameToString($column).' as '.$this->getDb()->nameToString($alias);
		}
		return(implode(', ', $columns));
	}
	protected function getAttrToFieldTranslationTable(){
		return(null);
	}
/* <nuevo para vistas> */

    public function searchGetSql($order_by=null, $order_dir='ASC', $limit=null, $start=0, $as_objects=true, $columns=null){
        return($this->search($order_by, $order_dir, $limit, $start, $as_objects, $columns, true));
    }
	public function search($order_by=null, $order_dir='ASC', $limit=null, $start=0, $as_objects=true, $columns=null){
        $args = func_get_args();
        $get_sql = isset($args[6])&&$args[6];
/* </nuevo para vistas> */
		$ret = null;
		$this->getDb()->open();
		if(!$this->getWhere())
			$this->setWhere()->getWhere()->setArrToFieldTranslationTable($this->getAttrToFieldTranslationTable());
		if($this->getWhere()){
			$str_where = $this->getWhere()->toString($this->getData(),$this->getAttrToFieldTranslationTable());
		}
		//$order = $order_by?' order by '.($this->db->valueToString($order_by)):'';
		$order_by = $order_by!==null?($this->getWhere()->getFieldName($order_by)):null;//traduce el campo de un alias a el verdaro campo
		$order_by = $order_by!==null?' ORDER BY '.$this->getDb()->nameToString($order_by).($order_dir&&in_array(trim(strtoupper($order_dir)),array('ASC','DESC'))?' '.$order_dir:''):'';
		$group_by = $this->getGroupBy();
		//$limit = $limit!==null&&$start!==null&&$start>0?(' limit '.$this->getDb()->valueToString($start).', '.$this->getDb()->valueToString($limit)):'';
		$limit = $limit!==null&&$start!==null&&$start>=0&&$limit>0?(' limit '.($start+0).', '.($limit+0)):'';
		$str_where = $str_where?' WHERE '.$str_where:'';
		if($columns!==null && is_array($columns) && count($columns)){
//			foreach($columns as $idx=>$column)
//				$columns[$idx] = $this->getDb()->nameToString($column);
//			$columns = implode(', ', $columns);
			$columns = $this->columnsToSelect($columns);
		}
		else $columns = $this->getColumnSelect();
		if($columns){
			$sql = 'SELECT '.$columns.' FROM '.$this->getFrom().$str_where.$group_by.$order_by.$limit;
//			if(get_class($this)=='Admin_Importador_Model_View_MatchingGrouped'){
//				var_dump($sql);
//				die();
//			}
/* <nuevo para vistas> */
            if($get_sql){
                $ret = $sql;
            }
/* </nuevo para vistas> */
            else{
//    			echo ($sql."\nddsfdsf");
//                die();
    //			var_dump($this->getAttrToFieldTranslationTable());
    			$this->lockRead(false);
                $r = $this->getDb()->exec($sql);
                if($r){
                    $ret = array();
                    if($as_objects){
                        //if($as_objects===true||!class_exists($as_objects)){
                        if($as_objects!==true && class_exists($as_objects) && is_a(new $as_objects, 'Db_Model_Abstract')){
                            $class = $as_objects;
                            while($fila = $this->getDb()->fetchAssociative($r)){
                                $new = new $class();
                                //$new->loadFromArray($this->getData(), false);
                                $ret[] = $new->loadFromArray($fila, false);
                            }
                        }
                        else{
                            $class = $as_objects;//soporte para otros tipos de clases, es decir llenar los datos en objetos de otras clases que deriven de Core_Object
                            if($class===true||!is_a(new $class, 'Core_Object'))
                                $class = 'Core_Object';
                            while($fila = $this->getDb()->fetchAssociative($r)){
                                $new = new $class();
                                foreach($fila as $alias=>$value)
                                    $new->setData($alias, $value);
                                //$new->loadFromArray($this->getData(), false);
                                $ret[] = $new;
                            }
                        }
                        //$fields = array_keys($this->getData());
                    }
                    else{
                        while($fila = $this->getDb()->fetchAssociative($r)){
                            $ret[] = $fila;
                        }
                    }
                }
            }
		}
		$this->getDb()->close();
		return($ret);
	}
	public function fillFieldFromDb($key, $value){
		return(false);
	}
	public function loadFromArrayWithFilters($arr, $check_properties=true){
		return $this->loadFromArray($arr, $check_properties, true);
	}
	public function loadFromArray($arr, $check_properties=true, $use_filters=false){
		if($check_properties===null){
			$check_properties = count($this->getData())!=0;
		}
		$filters = !$use_filters?false:null;
		if($check_properties){
			foreach($arr as $var=>$value){
				//var_dump(key_exists($var, $this->getData()), $var, $this->getData());
				if(key_exists($var, $this->getData())){
					if(!$this->fillFieldFromDb($var, $value))
						$this->setData($var, $value, $filters);
				}
			}
		}
		else{
			foreach($arr as $var=>$value){
				if(!$this->fillFieldFromDb($var, $value))
					$this->setData($var, $value, $filters);
			}
		}
		return($this);
	}
	public function resetData(){
		foreach($this->getData() as $key=>$value){
			$this->setData($key);
		}
		return($this);
	}
	public function getLastErrors($cant=null){
		return $this->getDb()->getLastErrors($cant);
	}
	public function getTranslatedErrors($cant=null){
		$errors = array();
		foreach($this->getLastErrors($cant) as $error){
			$translated = $this->translateError($error);
			$error->setTranslatedDescription($translated);
			$errors[] = $error;
		}
		return $errors;
	}
	protected function translateError($error){
		return $error->getCode().'- '.$error->getDescription();
	}
	private static $_relation_data = array();
	private function _getClassRelationsData($class=null){
		if(!isset($class))
			$class = get_class($this);
		if(!isset(self::$_relation_data[$class])){
			$parent_class = get_parent_class($class);
			if($parent_class!='Core_Model_Abstract'){
				$relation_data = $this->_getClassRelationsData($parent_class);//herencia de informacion
			}
			else $relation_data = array();
			
			$reflection = new Zend_Server_Reflection();
			
			if($doc_comment = $reflection->reflectClass($class)->getDocComment()){
				$re = '/@referencia\s+(?P<relation>[A-Za-z0-9_]+)\s*\(\s*(?P<fk>[A-Za-z0-9_]+)\s*\)\s+(?P<class>[A-Za-z0-9_]+)\s*\(\s*(?P<pk>[A-Za-z0-9_]+)\s*\)/';
				if(preg_match_all($re, $doc_comment, $matches)){
					foreach($matches[0] as $idx=>$match){
						$relation_data[$relation = $matches['relation'][$idx]] = array(
							'relation'=>$relation,
							'fk'=>$matches['fk'][$idx],
							'class'=>$matches['class'][$idx],
							'pk'=>$matches['pk'][$idx],
						);
					}
					//var_dump($matches);
				}
				//var_dump($relation_data);
			}
			self::$_relation_data[$class] = $relation_data;
		}
		return self::$_relation_data[$class];
	}
	private function _hasClassRelation($relation){
		if($data = $this->_getClassRelationsData()){
			return isset($data[$relation]);
		}
		return false;
	}
	private function _getClassRelation($relation){
		if($data = $this->_getClassRelationsData()){
			if(isset($data[$relation])){
				//var_dump($data[$relation]);
				$related_object = $this->_getRelatedObject(
					$data[$relation]['relation'],
					$data[$relation]['fk'],
					$data[$relation]['class'],
					$data[$relation]['pk']
				);
				return $related_object;
//				var_dump($related_object);
//				die(__FILE__.__LINE__);
			}
		}
		return null;
	}
	private $_related_objects = array();
	private function _getRelatedObject($relation, $fk, $class, $pk){
		if(!$this->hasData($fk)||!($fk_value = $this->getData($fk)))
			return null;
		if(!isset($this->_related_objects[$relation])||$this->_related_objects[$relation]->getData($pk)!=$fk_value){
//		echo "buscando\n";
//		var_dump($relation, $fk, $class, $pk);
			$object = new $class();
			$object->setData($pk, $fk_value);
			if(!$object->load())
				$object = null;
//			var_dump(Inta_Db::getInstance()->getLastQuery());
//			var_dump(get_class($object));
			
			$this->_related_objects[$relation] = $object;
		}
		return $this->_related_objects[$relation];
	}
	private static $_list_type_data = array();
	private function _getListTypeData($list_type){
		$class = get_class($this);
		if(!isset(self::$_list_type_data[$class])){
			$parent_class = get_parent_class($class);
			if($parent_class!='Core_Model_Abstract'){
				$list_type_data = $this->_getListTypeData($parent_class);//herencia de informacion
			}
			else $list_type_data = array();

			//$list_type_data = array();
			$reflection = new Zend_Server_Reflection();
			if($doc_comment = $reflection->reflectClass($class)->getDocComment()){
				$re = '/@listar\s+(?P<list_type>[A-Za-z0-9_]+)\s+(?P<class>[A-Za-z0-9_]+)/';
				$re = '/@listar\s+(?P<list_type>[A-Za-z0-9_]+)(\s*\(\s*(?P<pk>[A-Za-z0-9_]+)\s*\))?\s+(?P<class>[A-Za-z0-9_]+)(\s*\(\s*(?P<fk>[A-Za-z0-9_]+)\s*\))?/';
				if(preg_match_all($re, $doc_comment, $matches)){
					foreach($matches[0] as $idx=>$match){
						$list_type = $matches['list_type'][$idx];
						$data = array(
							'list_type'=>$list_type,
							'fk'=>$matches['fk'][$idx],
							'class'=>$matches['class'][$idx],
							'pk'=>$matches['pk'][$idx],
						);
						if(!class_exists($data['class']))
							continue;
						if(empty($data['pk'])||empty($data['fk'])){
							//Intento completar la información faltante con la información de la clase que referencia a la actual
							if($other_data = $this->_getClassRelationsData($data['class'])){
//								var_dump($other_data);
								$col = new Core_Collection($other_data);
								$filcol = $col->addFilterEq('class', get_class($this));
								if(!count($filcol))
									continue;
								$item = $filcol->getFirst()->getData();
								if(empty($data['pk']))
									$data['pk'] = $item['pk'];
								if(empty($data['fk']))
									$data['fk'] = $item['fk'];
//								var_dump($item);
								//die(__FILE__.__LINE__);
							}
							else continue;
						}
						$list_type_data[$list_type] = $data;
					}
					//var_dump($matches);
				}
				//var_dump($list_type_data);
			}
			self::$_list_type_data[$class] = $list_type_data;
		}
		return self::$_list_type_data[$class];
	}
	private function _hasListType($list_type){
		if($data = $this->_getListTypeData($list_type)){
			return isset($data[$list_type]);
		}
		return false;
	}
	private function _getListType($list_type){
		if($data = $this->_getListTypeData($list_type)){
			//return isset($data[$list_type]);
			if(isset($data[$list_type])){
//				var_dump($data[$list_type]);
				return $this->_getListTypeObjects(
					$data[$list_type]['list_type'], 
					$data[$list_type]['fk'], 
					$data[$list_type]['class'], 
					$data[$list_type]['pk']
				);
				//die("aca".__FILE__.__LINE__);
			}
		}
		return null;
	}
	private $_list_type_objects = array();
	public function _getListTypeObjects($list_type, $fk, $class, $pk){
		if(!$this->hasData($pk)||!$this->getData($pk))
			return null;
		if(!isset($this->_list_type_objects[$list_type])){
			$o = new $class();
			$o
				->setData($fk, $this->getData($pk))
				->setWhere(Db_Helper::equal($fk))
			;
//			var_dump(Inta_Db::getInstance()->getLastQuery());
//
			if($listado = $o->search(null, null, null, null, get_class($o))){
				$this->_list_type_objects[$list_type] = $listado;
			}
//			var_dump($listado);
//						die("aca".__FILE__.__LINE__);
		}
		return $this->_list_type_objects[$list_type];
	}

	
    public function __call($method, $args)
    {
    	switch (substr($method, 0, 3)) {
            case 'get' :
            	if(strpos($method, 'getList')===0&& $this->_hasListType($list_type = substr($method, 7))){
            		$listado = $this->_getListType($list_type);
            		return $listado;
//            		var_dump($listado);
//					die("aca".__FILE__.__LINE__);
				}
            	elseif($this->_hasClassRelation($relation = substr($method, 3))){
//            		echo "\n";
            		$related_object = $this->_getClassRelation($relation);
//	            	if($related_object)
//	            		echo "encontrado \n";
//	            	else echo "no encontrado \n";
//	            	var_dump($method);
					return $related_object;
	            	//die(__FILE__.__LINE__);
	            }
                //Varien_Profiler::start('GETTER: '.get_class($this).'::'.$method);
//                $key = $this->_underscore(substr($method,3));
//                $data = $this->getData($key, isset($args[0]) ? $args[0] : null);
//                //Varien_Profiler::stop('GETTER: '.get_class($this).'::'.$method);
//                return $data;
        }
    	return parent::__call($method, $args);
    	die("ok".$method);
    }

}
?>