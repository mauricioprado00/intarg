<?
class Granguia_User_Block_Form_Register extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this
			->setTemplate('user/form_registro.phtml')
			->setActionUrl(Granguia_User_Helper::getUrlRegisterForm())
		;
	}
}
?>