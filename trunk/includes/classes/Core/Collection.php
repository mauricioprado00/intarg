<?php 
class Core_Collection extends Core_Collection_Abstract{
	public function addFilterEq($field, $value, $match_null=false, $reverse=false){
		return parent::_filterEq($field, $value, $match_null, $reverse);
	}
	public function addFilterIn($field, array $values){
		return parent::_filterIn($field, $values);
	}
	public function addFilterNotIn($field, array $values){
		return parent::_filterIn($field, $values, true);
	}
	protected function addFilterGt($field, $value, $match_null=false){
		return parent::_filterGt($field, $value, $match_null);
	}
	protected function addFilterGte($field, $value, $match_null=false){
		return parent::_filterGte($field, $value, $match_null);
	}
	protected function addFilterLt($field, $value, $match_null=false){
		return parent::_filterLt($field, $value, $match_null);
	}
	protected function addFilterLte($field, $value, $match_null=false){
		return parent::_filterLte($field, $value, $match_null);
	}
	public function VerticalSliceArray(){
		$columns = func_get_args();
		return $this->_vertical_slice($columns, 'array');
	}
	public function VerticalSliceObject(){
		$columns = func_get_args();
		return $this->_vertical_slice($columns, 'object');
	}
	public function VerticalSlice(){
		$columns = func_get_args();
		return $this->_vertical_slice($columns, 'Core_Object');
	}
	public function VerticalSliceSingleArray($column){
		return $this->_vertical_slice_single($column);
	}
}
?>