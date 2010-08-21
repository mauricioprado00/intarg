<?
abstract class Granguia_Db_Model_Abstract extends Mysql_Db_Model_Table_Abstract{
	protected function init(){
		parent::init();
		$this->setDB(Granguia_Db::getInstance());
	}
	protected function afterReplace($data){
		if(!$this->hasId()){
			$this->setId($this->getDb()->insertId());
		}
	}
}
?>