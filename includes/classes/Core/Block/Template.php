<?
class Core_Block_Template extends Core_Block_Abstract{
	private $template;
	public function getHtml(){
		return($this->_toHtml());
	}
	public function _toHtml(){
		return($this->getTemplateHtml());
//		$c = $this->getTemplateHtml(); 
//		return("\n<template>$c\n</template>");
	}
	protected function _dump_extra(){
		return("\t\t\t\t\t".'->template:'.$this->getTemplate());
	}
	protected function getTemplateHtml($template=null){
		$template = $template==null?$this->getTemplate():$template;
		$file = $this->getLayout()->getDesignFilePath(CONF_SUBPATH_TEMPLATE.$template);
		if(file_exists($file)){
			ob_start();
			include($file);
			$c = ob_get_contents();
			ob_end_clean();
		}
		else{
			$c = 'el archivo '.$template.' no existe';
		}
		return($c);
	}
}
?>