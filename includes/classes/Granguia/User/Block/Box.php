<?
class Granguia_User_Block_Box extends Core_Block_Container{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('user/box.phtml');
	}
	protected function _allwaysBeforeToHtml(){
		$x = new Granguia_User_Model_User();
		if($x->isLoged()){
			$this->unsetChild('user_box_not_loged');
		}
		else{
			$this->unsetChild('user_box_loged');
		}
	}
}
?>