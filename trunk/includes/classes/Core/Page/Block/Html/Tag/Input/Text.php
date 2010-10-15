<?
class Core_Page_Block_Html_Tag_Input_Text extends Core_Page_Block_Html_Tag_Input{ 
	public function __construct(){
		parent::__construct();
		$this->setData('type','text');
	}
}

?>