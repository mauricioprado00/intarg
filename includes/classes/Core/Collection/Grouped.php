<?php
class Core_Collection_Grouped extends Core_Collection implements ArrayAccess{
	private $_join_class = 'Core_Collection';
	public function offsetSet($offset, $value) {
		die('offsetSet no implementado en Core_Collection_Grouped');
        $this->container[$offset] = $value;
    }
    public function offsetExists($offset) {
    	$_items = $this->getItems();
    	return isset($_items[$offset]);
    }
    public function offsetUnset($offset) {
    	die('offsetUnset no implementado en Core_Collection_Grouped');
        unset($this->container[$offset]);
    }
    public function offsetGet($offset) {
    	$_items = $this->getItems();
    	return isset($_items[$offset])?$_items[$offset]:null;
    }
	protected function setJoinClass($class){
		$this->_join_class = $class;
		return $this;
	}
	private function getJoinClass(){
		return $this->_join_class;
	}
	public function __construct(){
		parent::__construct();
		$this->setAllowedClasses('Core_Collection');
	}
	public function join($use_items_class=true){
		$join_class = $this->getJoinClass();
		if($use_items_class){
			foreach($this as $collection){
				$join_class = get_class($collection);
				break;
			}
		}
		$joined = new $join_class();
		foreach($this as $collection){
			foreach($collection as $item)
				$joined->addItem($item);
		}
		return $joined;
	}
}
?>