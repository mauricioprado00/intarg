<?
class Admin_Block_Messages extends Core_Block_Template{
	const MENSAJE_SUCCESS = 'success';
	const MENSAJE_INFORMACION = 'info';
	const MENSAJE_WARNING = 'warning';
	const MENSAJE_ERROR = 'error';
	
	function __construct(){
		$this->setTemplate('page/messages.phtml');
	}
	function addMessage($message){
		return($this->addInfoMessage($message));
	}
	public function addWarningMessage($message){
		return($this->_addMessage($message,'info'));
	}
	public function addInfoMessage($message){
		return($this->_addMessage($message,'warning'));
	}
	public function addSuccessMessage($message){
		return($this->_addMessage($message,'success'));
	}
	public function addErrorMessage($message){
		return($this->_addMessage($message,'error'));
	}
	private function _addMessage($message, $tipo){
		$this->appMessages(array($tipo,$message));
		return($this);
	}
	protected function getAllMessages(){
		return($this->getMessages());
	}
	protected function getErrorMessages(){
		return($this->getMessages('error'));
	}
	protected function getSuccessMessages(){
		return($this->getMessages('success'));
	}
	protected function getWarningMessages(){
		return($this->getMessages('warning'));
	}
	protected function getInfoMessages(){
		return($this->getMessages('info'));
	}
	private function getMessages($tipo=null){
		if(!$this->hasMessages())
			return(null);
		$ret = array();
		$messages = $this->getData('messages');
		foreach($messages as $message){
			if($tipo==null||$message[0]==$tipo){
				$m = new Core_Object();
				$m->setMessage($message[1]);
				$m->setTipo($message[0]);
				$m->setIsError($message[0]=='error');
				$m->setIsInfo($message[0]=='info');
				$m->setIsSuccess($message[0]=='info');
				$m->setIsWarning($message[0]=='info');
				$ret[] = $m;
			}
		}
		return($ret);
	}
}
?>