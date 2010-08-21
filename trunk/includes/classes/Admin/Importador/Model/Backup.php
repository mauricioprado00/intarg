<?
class Admin_Importador_Model_Backup extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$this->setId(null);
	}
	public function getDbTableName() 
	{
		return 'bm_backup';
	}
}
?>