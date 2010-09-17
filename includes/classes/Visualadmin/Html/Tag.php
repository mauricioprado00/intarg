<?
class Visualadmin_Html_Tag extends Core_Html_Tag_Custom{
//	public function __construct($tagname){
//		parent::__construct();
//		$this->setTagname($tagname);
//	}
//	public function appendInnerHtml($content){
//		$prev_content = '';
//		if($this->hasInnerHtml()){
//			$prev_content = $this->getInnerHtml();
//		}
//		if(is_a($content, 'Core_IHtmlRenderer'))
//			$content = $content->getHtml();
//		$prev_content .= $content;
//		$this->setInnerHtml($prev_content);
//		return $this;
//	}
//	public function getInnerHtml(){
//		$innerHtml = $this->getData('inner_html');
//		if($innerHtml instanceof Core_Html_Tag_Custom)
//			return $innerHtml->getHtml();
//		return $innerHtml;
//	}
	public function getHtml($begin=null, $html=null){
		switch($this->getTagname()){
			case 'input':{
				switch($this->getType()){	
					case 'submit':
					case 'text':{
						if($this->hasClass()){
							$this->setClass($this->getType().' '.$this->getClass());
						}
						else{
							$this->setClass($this->getType());
						}
						break;
					}
					case 'button':{
						if($this->hasClass()){
							$this->setClass('submit '.$this->getClass());
						}
						else{
							$this->setClass('submit');
						}
						break;
					}
				}
				break;
			}
			case 'select':{
				if($this->hasClass()){
					$this->setClass($this->getTagname().' '.$this->getClass());
				}
				else{
					$this->setClass($this->getTagname());
				}
			}
			
		}
		return parent::getHtml($begin, $html);
	}
//	public function getHtml($begin=null, $html=null){
//		if($this->isSingleTag()){
//			return $this->getBeginHtml();
//		}
//		if(isset($html)){
//			return $this->getBeginHtml().$html.$this->getEndHtml();
//		}
//		if(!isset($begin)){
//			return $this->getBeginHtml().$this->getInnerHtml().$this->getEndHtml();
//		}
//		if($begin)
//			return $this->getBeginHtml();
//		return $this->getEndHtml();
//	}
//	public function getEndHtml(){
//		if($this->isSingleTag())
//			return '';
//		return '</'.$this->getTagname().'>';
//	}
//	public function getBeginHtml(){
//		$attrs = $this->getData();
//		$arr_attrs = array();
//		foreach($attrs as $key=>&$attr){
//			if(in_array($key, array('tagname','inner_html')))
//				continue;
//			$arr_attrs[] = $key.'="'.htmlentities($attr).'"';
//		}unset($attrs);
//		$arr_attrs = implode(' ', $arr_attrs);
//		$arr_attrs = $arr_attrs?' '.$arr_attrs:'';
//		$end = $this->isSingleTag()?'/>':'>';
//		return '<'.$this->getTagname().$arr_attrs.$end;
//	}
//	public function isSingleTag(){
//		return in_array(strtolower($this->getTagname()), array('input','img','br','meta'));
//	}
}
?>