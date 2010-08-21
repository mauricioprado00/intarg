<?
//abstract class Core_Model_User extends Core_Persistant{
abstract class Core_Model_User extends Core_Model_Abstract{
	private $_loged;
	private $_username;
	private $_password;
	protected function onStartSession($username){}
	protected function onLogedOk(){}
	protected function onEndSession(){}
	protected function onBadTry(){}
	
	abstract protected function validate($username, $password);

	public function init(){
		parent::init();
		$this->_loged = null;
	}
	public function login($username, $password){
		if($this->validate($username, $password)){
			$this->_loged = true;
			$this->_username = $username;
			$this->onStartSession($username);
			$this->__saveInSession();
			$this->onLogedOk();
			return($this);
		}
		$this->_loged = false;
		$this->onBadTry();
		return($this);
	}
	public function isLoged(){
		if($this->_loged===null&&!$this->__loadFromSession())
			return(false);
		return($this->_loged);
	}
	private function getSessionContext(){
		return('_class'.get_class($this));
	}
	public function logout(){
		//Core_App::getSession()->setVar('login', null, $this->getSessionContext());
		$this->onEndSession();
		Core_App::getSession()->setVar(null, null, $this->getSessionContext());
	}
	public function setSessionVar($varname, $value, $contexts=array('default')){
		$contexts = array_merge(array($this->getSessionContext()), $contexts);
		return(Core_App::getSession()->setVarMulticontext($varname, $value, $contexts));
	}
	public function getSessionVar($varname, $contexts=array('default')){
		$contexts = array_merge(array($this->getSessionContext()), $contexts);
		return(Core_App::getSession()->getVarMulticontext($varname, $contexts));
	}
	public function listSessionVars(){
		$contexts = func_get_args();
		if(count($contexts)==1&&is_array($contexts[0]))
			$contexts = $contexts[0];
		$contexts = array_merge(array($this->getSessionContext()), $contexts);
		return(call_user_method_array('listContextVars', Core_App::getSession(), $contexts));
	}
	public function listSessionValues($modo='array'){
		$contexts = func_get_args();
		$contexts = array_slice($contexts,1);
		if(count($contexts)==1&&is_array($contexts[0]))
			$contexts = $contexts[0];
		$contexts = array_merge(array($this->getSessionContext()), $contexts);
		$params = array_merge(array($modo), $contexts);
		return(call_user_method_array('listContextValues', Core_App::getSession(), $params));
	}
	private function __saveInSession(){
		Core_App::getSession()->setVar('login', $this, $this->getSessionContext());
		return($this);
	}
	private function __loadFromSession(){
		$loged =  Core_App::getSession()->getVar('login', $this->getSessionContext());
		if($loged === null)
			return(false);
		$this->_loged = $loged->_loged;
		$this->_username = $loged->_username;
		$this->onStartSession($loged->_username);
		return(true);
	}
}
?>