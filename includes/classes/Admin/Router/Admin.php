<?
class Admin_Router_Admin extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions('ajax');
		$this->addActions('noajax');
		$this->addActions('onajax');
	}

	protected function noajax(){//
		Admin_App::getInstance()->setModoAjax(false);
		$this->onThrought(true);
		$ret = $this->routeDelegate($this,$this->arr_request_path);
		return($ret);
	}
	protected function onajax(){//
		Admin_App::getInstance()->setModoAjax(true);
		$this->onThrought(true);
		$ret = $this->routeDelegate($this,$this->arr_request_path);
		return($ret);
	}
	protected function ajax(){
		//var_dump($this->arr_request_path);
		Core_App::getLayout()
			->setModo('ajax')
			->addDesignPaths('ajax', 'admin/default/');
		$ret = $this->routeDelegate($this,$this->arr_request_path);
		return($ret);
	}
	protected function restrictDispatch(){
		/*Core_App::getLayout()
			->setActions('other');*/
		$r = new Admin_User_Router_User;
		$res = $r->route('ValidarUsuario');
		if($res===true)
			return(true);
		return;
//		$args = $this->arr_request_path;
//		if(count($args)==1&&$args[0]=='user'){
//			//$this->arr_request_path = array('user', 'login.php');
//			$r = new Admin_User_Router_User;
//			$res = $r->route('login.php');
//			if($res===true)
//				return(true);
//		}
	}
	public function onThrought($force=false){
		static $called;if($called&&!$force)return($this);$called=true;
		if($user = Admin_User_Model_User::getLogedUser()){
			if($user->esSuperadmin()){
				Core_App::getLayout()
					->addActions('superadmin');
			}	
		}
		if(Admin_App::getInstance()->getModoAjax()){
			Core_App::getLayout()
				->addActions('modo_ajax');
		}
		else{
			Core_App::getLayout()
				->removeActions('modo_ajax');
		}
		Core_App::getLayout()
			->setModo('admin')
			->addDesignPaths('admin', 'admin/default/');
	}
	public function localDispatch(){
		parent::localDispatch();
	}

//	protected function dispatchNode($node){
//		/*aca podriamos consultar a la base de datos para ver como esta asignado el nodo si es que existe*/
//		switch($node){
//			case 'caquita':{
//				echo "yyyeee";
//				return(true);
//				break;
//			}
//		}
//	}
}
?>