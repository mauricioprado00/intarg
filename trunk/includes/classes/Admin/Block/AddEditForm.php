<?
class Admin_Block_AddEditForm extends Core_Block_Template{
	public function __construct(){
		$this
			->setTemplate('page/add_edit_form.phtml')
			->setAjaxTarjet('.contenedor_main')
			->setBooleanData('ajax_replace_with')
			->setAjaxMethod('ajaxSubmit')
		;
	}
	public function getUrl($link_url){
		if(Admin_App::getInstance()->getModoAjax())
			$link_url = 'administrator/ajax/'.$link_url;
		else $link_url = 'administrator/'.$link_url;
		return(parent::getUrl($link_url));
	}
}
?>