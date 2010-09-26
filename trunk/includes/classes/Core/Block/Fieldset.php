<?
class Core_Block_Fieldset extends Core_Block_Abstract{
	public function __construct(){
		parent::__construct();
		$this
			->setTagname('fieldset')
		;
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
		$caption = '';
		if($this->hasCaption()){
			$caption.='<h3>'.htmlentities($this->getCaption()).'</h3>';
		}
		if($this->hasLegend()){
			$caption .= '<legend>'.$this->getLegend().'</legend>';
		}
		return '<'.$this->getTagname().$arr_attrs.'>'.$caption.'<div>'.$this->getChildHtml('', true, true).'</div>'.'</'.$this->getTagname().'>'."\n";
	}
}
?>