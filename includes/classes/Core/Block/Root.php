<?
class Core_Block_Root extends Core_Block_Abstract{
	public function getHtml(){
		
	}
	public function _toHtml(){
		if(false){
			ob_start();?> 
		<root><?="\n".$this->getChildHtml()?> 
		</root><?
			$c = ob_get_contents();
			ob_end_clean();
		}
		else $c = $this->getChildHtml();
		return($c);
	}
}
?>