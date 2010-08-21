<?
class Admin_Block_MessageContainer extends Core_Block_Template{
	function __construct(){
		$this->setTemplate('page/message_container.phtml');
	}
	public function requestMessageContainerTo($class, $method){
		$this->setRequestMessagesTo(array($class, $method));
		return $this;
	}
	public function getMessages(){
		$callback = $this->getRequestMessagesTo();
		if(!$callback)
			return null;
		$message_container = call_user_func($callback);
		if(!$message_container)
			return null;
		return $message_container->getMessages();
	}
}
?>