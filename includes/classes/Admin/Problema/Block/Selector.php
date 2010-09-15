<?
class Admin_Problema_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this->setEntityToList(new Inta_Model_Problema());
	}
}
?>