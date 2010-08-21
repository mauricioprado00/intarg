<?
class Admin_App extends Core_Singleton{
	public function __construct(){
		$ajaxon = Core_App::getSession()->getVar('ajaxon', __CLASS__);
		if($ajaxon===null){
			$ajaxon = true;
		}
		self::setData('modo_ajax', $ajaxon);
		self::setData('message_container', new Core_MessageContainer());
	}
	public static function getMessageContainer(){
		$inst = self::getInstance();
		return $inst->getData('message_container');
	}
	public function addWarningMessage($message){
		$contenedor = $this->getMessageContainer();
		return($contenedor->addWarningMessage(utf8_encode($message),'warning'));
	}
	public function addInfoMessage($message){
		$contenedor = $this->getMessageContainer();
		return($contenedor->addInfoMessage(utf8_encode($message),'info'));
	}
	public function addSuccessMessage($message){
		$contenedor = $this->getMessageContainer();
		return($contenedor->addSuccessMessage(utf8_encode($message),'success'));
	}
	public function addErrorMessage($message){
		$contenedor = $this->getMessageContainer();
		return($contenedor->addErrorMessage(utf8_encode($message),'error'));
	}
	public function addLightMessage($message){
		$contenedor = $this->getMessageContainer();
		return($contenedor->addLightMessage(utf8_encode($message),'light'));		
	}
	public function addShieldMessage($message){
		$contenedor = $this->getMessageContainer();
		return($contenedor->addShieldMessage(utf8_encode($message),'shield'));		
	}
	public function addMessageMessage($message){
		$contenedor = $this->getMessageContainer();
		return($contenedor->addMessageMessage(utf8_encode($message),'message'));		
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function setModoAjax($set){
		Core_App::getSession()->setVar('ajaxon',$set?true:false, __CLASS__);
		$this->setData('modo_ajax', $set?true:false);
		return($this);
	}
}
?>