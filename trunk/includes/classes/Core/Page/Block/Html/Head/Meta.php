<?
class Core_Page_Block_Html_Head_Meta extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('page/html/head/meta.phtml')
		;
	}
}
?>