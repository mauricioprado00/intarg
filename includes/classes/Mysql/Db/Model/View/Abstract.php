<?
abstract class Mysql_Db_Model_View_Abstract extends Db_Model_View_Abstract{
	function init(){
		parent::init();
		$this->setDb(new Mysql_Db());
	}
}
?>