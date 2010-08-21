<?
class Granguia_Catalog_Block_Search_Form extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this->setTemplate("catalog/search/form.phtml");
	}
	protected function _beforeToHtml(){
		$data = null;
		if(Core_Http_Post::hasParameters()){
			$data = Core_Http_Post::getParameters('Core_Object');
			Core_Session::setVar('last_search', $data, __CLASS__);
		}
		else{
			$data = Core_Session::getVar('last_search', __CLASS__);
		}
		$this->setSearchInformation($data);
	}
}
?>