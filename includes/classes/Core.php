<?
class Core extends Core_Singleton{
	public function create($class){
		return new $class;
	}
	public function getObject($o){
		return $o;
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}
?>