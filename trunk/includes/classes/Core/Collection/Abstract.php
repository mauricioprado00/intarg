<?php 
abstract class Core_Collection_Abstract extends Core_Object implements IteratorAggregate{
	public function __construct($items=null){
		parent::__construct();
		if(func_num_args()){
			$items = func_get_args();
			if(count($items)==1){
				$items = array_pop($items);
			}
			if(is_array($items)){
				$items = array_values($items);
				if(is_array($items[0])){
					foreach($items as &$item){
						$item = new Core_Object($item);
					}
					unset($item);
				}
			}
			$this->setItems($items);
		}
	}
	/* metodos genericos (prototipo para objeto collection)*/
	private $_items = array();
	private $_allowed_classes = null;
    public function getIterator() {
        return new Core_Iterator($this->_items);
    }
	protected function setAllowedClasses(){
		$allowed_classes = func_get_args();
		$this->_allowed_classes = $allowed_classes;
		return $this;
	}
	private function is_allowed($item){
		if(!isset($this->_allowed_classes))
			return $item instanceof Core_Object;
		foreach($this->_allowed_classes as $class)
			if($item instanceof $class)
				return true;
		return false;
	}
	public function setItems($items){
		$this->_items = array();
		if(isset($items)&&is_array($items)&&count($items)){
			foreach($items as $item){
				if($this->is_allowed($item)){
					$this->_items[] = $item;
				}
			}
		}
		return $this;
	}
	public function addItem($item, $key=null){
		if($this->is_allowed($item)){
			if(!isset($key))
				$this->_items[] = $item;
			else $this->_items[$key] = $item;
		}	
		return $this;
	}
	public function getItems(){
		return $this->_items;
	}
	protected function _getMin($field, $ignore_null=true){
		$values = array();
		foreach($this->_items as $item){
			if(!$item->hasData($field)){
				if($ignore_null)
					continue;
				return null;
			}
			$values[] = $item->getData($field);
		}
		return min($values);
	}
	protected function _add($field, $value, $create_if_not_exists=true){
		foreach($this->_items as $item){
			$old_value = $item->getData($field);
			if(!isset($old_value)){
				if(!$create_if_not_exists)
					continue;
				else $old_value = 0;
			}
			$item->setData($field, $old_value + $value);
		}
		return $this;
	}
	protected function _sum($field, $abs=false, $if_not_null=false){
		$suma = 0;
		foreach($this->_items as $item){
			if($if_not_null&&!$item->hasData($field))
				continue;
			$old_value = $item->getData($field);
			if($abs)
				$suma += abs($old_value);
			else $suma += $old_value;
		}
		return $suma;
	}
	public function count($field=null, $if_not_null=true){
		if(isset($field)){
			if($if_not_null){
				$count = 0;
				foreach($this->_items as $item){
					if(!$this->_items->hasData($field))
						continue;
					$count++;
				}
			}
		}
		return count($this->_items);
	}
	protected function _avg($field, $decimales=null, $abs=false, $if_not_null=false){
		$suma = $this->_sum($field, false, $if_not_null);
		$count = $this->count($field, $if_not_null);
		if(!isset($decimales))
			return $suma/$count;
		$avg = $suma/$count;
		return round($avg, $decimales);
	}
	public function _getDataEqualData($field){
		$value = null;
		foreach($this->_items as $item){
			$item_value = $item->getData($field);
			if(!isset($item_value))
				continue;
			if(isset($value)){
				if($item_value!=$value){
					$value = null;
					break;
				}
			}
			else $value = $item_value;
		}
		return $value;
	}
	public function collectionFirstData($field){
		if(!count($this->_items))
			return null;
		$item = $this->_items[0];
		if(!$item->hasData($field))
			return null;
		return $item->getData($field);
	}
	protected function getEq($idx){
		$keys = array_keys($this->_items);
		return $this->_items[$keys[$idx]];
	}
	public function getFirst(){
		if(!$this->count())
			return null;
		return $this->getEq(0);
	}
	public function groupBy(){
		$args = array(
			'campos'=>func_get_args(),
			'include_nulls'=>true
		);
		return call_user_func_array(array($this, '_groupBy'), $args);
	}
	public function groupByWithoutNulls(){
		$args = array(
			'campos'=>func_get_args(),
			'include_nulls'=>false
		);
		return call_user_func_array(array($this, '_groupBy'), $args);
	}
	private function _groupBy($campos, $include_nulls=true){
//		if(is_array($campos))
//			$campo = array_shift($campos);
//		else $campo = $campos;
		$grouped = new Core_Collection_Grouped();
		foreach($this->getItems() as $item){
			$grouped_filtered = $grouped;
			foreach($campos as $campo){
				if(!$include_nulls && !$item->hasData($campo)){
					$grouped_filtered = null;
					break;
				}
				$value = $item->getData($campo);
				$grouped_filtered = $grouped_filtered->_filterEq($campo, $value);
				//var_dump(array($campo, $value, $grouped_filtered->count()));
			}
			if(!isset($grouped_filtered))
				continue;
			//$grouped_filtered = $grouped->_filterEq($campo, $value);
			if(!$grouped_filtered->count()){
				$class = get_class($this);
				$collection = new $class;
				$key = array();
				foreach($campos as $campo){
					$value = $item->getData($campo);
					$key[] = $value;
					$collection->setData($campo, $value);
				}
				$grouped->addItem($collection, implode(',',$key));//una coleccion de colecciones (grouped)
				//var_dump($grouped->count());
			}
			else $collection = $grouped_filtered->getFirst();
			//$collection = $collections[$value];
			//var_dump(get_class($item),get_class($collection));
			$collection->addItem($item);
			//var_dump(array($collection->count(), $collection->getData(), $grouped->count()));
		}
		$grouped->setGroupFields($campos);
		return $grouped;
	}
	
	protected function _filterByGeneric($function, $field, $params){
		$class = get_class($this);
		$filtered = new $class();
		foreach($this->getItems() as $item){
			$args = array(
				'value'=>$item->getData($field),
				'params'=>$params
			);
			$res = call_user_func_array($function, $args);
			if($res)
				$filtered->addItem($item);
		}
		return $filtered;
	}
	private function _function_filter_eq($value, $params){
		return (!isset($value)&&($params['match_null']||!isset($params['value'])))||(isset($value)&&$value==$params['value']);
	}
	protected function _filterEq($field, $value, $match_null=false){
		return $this->_filterByGeneric(array(__CLASS__,'_function_filter_eq'), $field, array('value'=>$value,'match_null'=>$match_null));
	}
	private function _function_filter_gt($value, $params){
		return ($params['match_null']&&!isset($value))||(isset($value)&&$value>$params['value']);
	}
	protected function _filterGt($field, $value, $match_null=false){
		return $this->_filterByGeneric(array(__CLASS__,'_function_filter_gt'), $field, array('value'=>$value,'match_null'=>$match_null));
	}
	private function _function_filter_gte($value, $params){
		return ($params['match_null']&&!isset($value))||(isset($value)&&$value>=$params['value']);
	}
	protected function _filterGte($field, $value, $match_null=false){
		return $this->_filterByGeneric(array(__CLASS__,'_function_filter_gte'), $field, array('value'=>$value,'match_null'=>$match_null));
	}
	private function _function_filter_lt($value, $params){
		return ($params['match_null']&&!isset($value))||(isset($value)&&$value<$params['value']);
	}
	protected function _filterLt($field, $value, $match_null=false){
		return $this->_filterByGeneric(array(__CLASS__,'_function_filter_lt'), $field, array('value'=>$value,'match_null'=>$match_null));
	}
	private function _function_filter_lte($value, $params){
		return ($params['match_null']&&!isset($value))||(isset($value)&&$value<=$params['value']);
	}
	protected function _filterLte($field, $value, $match_null=false){
		return $this->_filterByGeneric(array(__CLASS__,'_function_filter_lte'), $field, array('value'=>$value,'match_null'=>$match_null));
	}
	protected function _vertical_slice($columns, $as='Core_Object'){
		$return = array();
		foreach($this->getItems() as $item){
			$fila = array();
			foreach($columns as $column){
				$fila[$column] = $item->getData($column);
			}
			if($as=='object'){
				$fila = ((object)$fila);
			}
			elseif($as!='array'){
				$fila = new $as($fila); 
			}
			$return[] = $fila;
		}
		return $return;
	}
	protected function _vertical_slice_single($column){
		$return = array();
		foreach($this->getItems() as $item){
			$val = $item->getData($column);
			$return[] = $val;
		}
		return $return;
	}
}
?>