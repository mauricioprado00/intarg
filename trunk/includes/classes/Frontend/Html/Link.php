<?php
class Frontend_Html_Link extends Core_Object{
	private $no_ajax = false;
	public function setNeverAjax(){
		$this->no_ajax = true;
		return $this;
	}
	private $descargable = false;
	public function setDescargable($descargable){
		$this->descargable = $descargable?true:false;
		return $this;
	}
	private $screenblock = false;
	public function setScreenBlock($screenblock){
		$this->screenblock = $screenblock?true:false;
		return $this;
	}
	function getHtml($begin=null, $html=null){
		$wrapped = new Core_Html_Tag_Custom('a');
		$wrapped->setData($this->getData());
		$wrapped->setTagname('a');
		$extra_info = array();
		if($this->descargable)
			$extra_info['descargable'] = 1;
		if($this->no_ajax){
			$extra_info['no_ajax'] = 1;
		}
		if($this->screenblock){
			$extra_info['screenblock'] = 1;
		}
		elseif(!$this->no_ajax){
			if(Core_Http_Header::isAjaxRequest())
				if($this->hasHref() && strpos($this->getHref(), ($base_url=Core_App::getUrlModel()->getUrl()))===0){
					$href = '#'.array_pop(explode($base_url, $this->getHref()));
					$href = $href=='#'?'#~':$href;
					$wrapped->setHref($href);
				}
		}
		if(count($extra_info))
			$wrapped->setData('data-extrainfo', json_encode($extra_info));
		return $wrapped->getHtml($begin, $html);
	}
}
?>