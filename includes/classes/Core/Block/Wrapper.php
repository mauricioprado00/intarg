<?
class Core_Block_Wrapper extends Core_Block_Abstract{
	public function __construct(){
		parent::__construct();
		$this->setTagname('div');
	}
	public function getHtml(){
		
	}
	public function _toHtml(){
		$attrs = $this->getData();
		$arr_attrs = array();
		foreach($attrs as $key=>&$attr){
			if(in_array($key, array('tagname'/*,'inner_html'*/)))
				continue;
			if(strpos($key, 'html_')!==0)
				continue;
			$key = substr($key, 5);
			$arr_attrs[] = $key.'="'.htmlentities($attr).'"';
		}unset($attrs);
		$arr_attrs = implode(' ', $arr_attrs);
		$arr_attrs = $arr_attrs?' '.$arr_attrs:'';
		//$end = $this->isSingleTag()?'/>':'>';
		return '<'.$this->getTagname().$arr_attrs.'>'.$this->getChildHtml('', true, true).'</'.$this->getTagname().'>'."\n";
	}
}
?>