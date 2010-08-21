<?
class Core_Page_Block_Html_Tag extends Core_Block_Abstract{
	public function getHtml(){
		return("\n\n\n".'yeeeeeeeeeeeeee'."\n\n\n");
	}
	public function _toHtml(){
		$tagname = $this->getTagname();
		if(!$tagname){
			var_dump($this->getData());
			return 'fuuuu';
		}
		$x = new Core_Html_Tag_Custom($tagname);
		$x->setData($this->getData());
		$content = null;
		if($x->hasInlineContent())
			$content = $x->getInlineContent();
		$content = $content?$content:'';
		if($content)
			$x->setInnerHtml($content);
		return($x->getHtml());
	}
//<block type="Core_Page/Html_Tag" tagname="textarea" inline_content="1">ok funca de lujo</block>
//<block type="Core_Page/Html_Tag" tagname="textarea" />

}
?>