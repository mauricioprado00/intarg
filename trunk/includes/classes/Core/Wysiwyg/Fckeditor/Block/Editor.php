<?
class Core_Wysiwyg_Fckeditor_Block_Editor extends Core_Wysiwyg_Block_Editor_Abstract{
	public function __construct(){
		parent::__construct();
		
		$this
			->setTemplate('fckeditor/editor.phtml')
			->setHtmlId($this->generateRandomId())
			//->setTemplate("catalog/product/list.phtml")
//			->setMaxItems(3)
//			->setCurrentRelativeIndex(null)
//			->setCurrentEntity(null)
//			->addCustomBlockType('button', 'Core_Block_List_Button')
		;
	}
	protected function _allwaysBeforeToHtml(){
		if(!$_ready){
			$this->tryOnAddJsReady(array('global_document_ready_bottom', 'global_document_ready'));
		}
	}
	public function onAfterLayoutLoad(){
		if(!$_ready){
			$this->tryOnAddJsReady(array('global_document_ready', 'global_document_ready_bottom'));
		}
	}
	private $_ready = false;
	private function tryOnAddJsReady($targets){
		$xml = '<document_ready template="fckeditor/init.phjs"></document_ready>';
		foreach($targets as $target){
			$x = $this->appendBlock($xml, '', $target);
			if($x){
				$_ready = true;
				$x->setEditor($this);
				return(true);
			}
		}
		return (false);
	}
}
?>