<?
class Admin_Block_AddEditForm extends Core_Block_Template{
	public function __construct(){
		$this
			->setTemplate('page/add_edit_form.phtml')
			->setAjaxTarjet('.contenedor_main')
			->setBooleanData('ajax_replace_with')
			->setAjaxMethod('ajaxSubmit')
			->setPostTargetVariable('admin_addeditform')
		;
	}
	public function onAfterLayoutLoad(){
		$this->setTranslateContext($this->getActionUrl());
	}
	public function getUrl($link_url){
		if(Admin_App::getInstance()->getModoAjax())
			$link_url = 'administrator/ajax/'.$link_url;
		else $link_url = 'administrator/'.$link_url;
		return(parent::getUrl($link_url));
	}
	public function getAjaxTarget(){
		$ajax_target = $this->getData('ajax_target');
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			$var = $this->getPostTargetVariable();
			
			if($post->hasData($var)){
				$data = $post->getData($var);
				if(is_array($data)&&isset($data['ajax_target'])){
					return $data['ajax_target'];
				}
				return $data;
			}
		}
		return $ajax_target;
	}
}
?>