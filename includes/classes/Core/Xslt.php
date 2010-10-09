<?php
class Core_Xslt extends Core_Singleton{
	public function getInstance(){
		return parent::getInstanceOf(__CLASS__);
	}
  /**
   * Core_Xslt::Template()
   * @param DomNode[] $arr_nodes
   * @param string $template
   * @return void
   */
	public static function Template($arr_nodes, $template){
		return self::getInstance()->TemplateNode($arr_nodes, $template);
	}
	private function TemplateNode($arr_nodes, $template){
		$layout = Core_Helper::getInstance()->LookUpLayout();// $this->lookupLayout();
		if(!$layout)
			return '';
		$template_block = c(new Core_Block_Template())
			->setLayout($layout)
			->setTemplate($template)
		;
		$arr_simple = array();
		foreach($arr_nodes as $node){
			$arr_simple[] = $snode = simplexml_import_dom($node);
		}
		$template_block
			->setDomNodes($arr_nodes)
			->setSimpleNodes($arr_simple);
		;
		return $template_block->toHtml();
	}
}
?>