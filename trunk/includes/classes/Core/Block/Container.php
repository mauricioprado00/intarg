<?
class Core_Block_Container extends Core_Block_Abstract{
	public function getHtml(){
		
	}
	public function _toHtml(){
		//echo "<cont>";
		$c = $this->getChildHtml('', true, true);
		//echo "</cont>";
		return($c);
	}
}
?>