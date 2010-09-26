<?
class Admin_Block_Menu extends Core_Block_Template{
	public function __construct(){
		$this
			->setTemplate('page/menu.phtml')
		;
		
	}
	public function addItem($link_url, $text, $title, $name=null, $template=null){
		$new = new Core_Block_Template();
		if($template===null)
			$template = 'page/menu_item.phtml';
		if(Admin_App::getInstance()->getModoAjax())
			$link_url = 'administrator/ajax/'.$link_url;
		else $link_url = 'administrator/'.$link_url;
		$new->setTemplate($template);
		$new->setLinkUrl($link_url);
		$new->setText($this->__t($text));
		$new->setTitle($this->__t($title));
		$new->setIsSubmenu(true);
		if($name!==null){
			$this->getLayout()->setBlock(
				$name, 
				$new
					->setName($name)
					->setNameInLayout($name)
			);
		}
		$this->insert($new);
	}
}
?>