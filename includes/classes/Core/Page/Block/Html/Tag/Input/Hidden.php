<?
class Core_Page_Block_Html_Tag_Input_Hidden extends Core_Page_Block_Html_Tag_Input{ 
	public function __construct(){
		parent::__construct();
		$this->setData('type','hidden');
	}
}

?>