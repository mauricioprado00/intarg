<?
class Granguia_Db_Model_View_Generic extends Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$this->setDB(Granguia_Db::getInstance());
	}
}
?>