<?
abstract class Mysql_Db_Model_Abstract extends Db_Model_Abstract{
	function Mysql_Db_Model_Abstract(){
		$this->setDb(new Mysql_Db());
	}
}
?>