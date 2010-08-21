<?
class Inta_Db extends Mysql_Db{
	private static $_instancia = null;
	private function Inta_Db(){// lo hago singleton
		parent::__construct();
	}
	public function getInstance(){
		if(!isset(self::$_instancia)){
			self::$_instancia = new self();
			self::$_instancia->modoSingleton(true);
			self::$_instancia->open();
		}
		return self::$_instancia;
	}
	public function exec($sql){
		$this->setLastQuery($sql);
		return parent::exec($sql);
	}
	function open(){
		if(!$this->hasHost())
			$this->setHost(Core_App::getInstance()->getDbHost());
		if(!$this->hasUser())
			$this->setUser(Core_App::getInstance()->getDbUser());
		if(!$this->hasPassword())
			$this->setPassword(Core_App::getInstance()->getDbPassword());
		if(!$this->hasModel())
			$this->setModel(Core_App::getInstance()->getDbModel());
		return(parent::open());
	}
}
?>