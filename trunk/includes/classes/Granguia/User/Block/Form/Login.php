<?
class Granguia_User_Block_Form_Login extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this
			->setTemplate('user/login_box.phtml')
			->setActionUrl(Granguia_User_Helper::getUrlLoginForm())
		;
	}
}
?>