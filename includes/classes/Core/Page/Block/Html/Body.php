<?
class Core_Page_Block_Html_Body extends Core_Block_Wrapper{
	public function __construct(){
		parent::__construct();
		$this->setTagname('body');
	}
}
//class Core_Page_Block_Html_Body extends Core_Block_Template{
//	function __construct(){
//		$this->setTemplate('page/html/body.phtml');
//	}
//}
?>