<?
class Admin_User_Router_User extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions('ValidarUsuario');
		$this->addActions('cerrar_sesion');
		$this->addActions('addEdit');
		$this->addActions('delete');
	}
	public function cerrar_sesion(){
		Admin_User_Model_User::getLogedUser()->logout();
		Core_Http_Header::Redirect(Core_App::getUrlModel()->getUrl('administrator'));
	}
	public function ValidarUsuario(){
		$user = Admin_User_Model_User::getLogedUser();
		if(!$user||$user->isLoged()==false){
			if($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
				die('<script>document.location.href="'.Core_App::getUrlModel()->getUrl('administrator').'";</script>');
			}
			$p = Core_Http_Post::getParameters('object');
			if(!isset($p->username,$p->password)){
				Core_App::addMessage('Identifiquese','loginerror');
				Core_App::getLayout()
					->setActions('simple', 'login_page')
				;
				return(true);//evito que continue "routeando"
			}
			else{
				$user = new Admin_User_Model_User();
				$user->login($p->username, $p->password);
				if(!$user->isLoged()){
					Core_App::addMessage('datos no validos','loginerror');
					Core_App::getLayout()
						->setActions('simple', 'login_page')
					;
					return(true);//evito que continue "routeando"
				}
			}
		}
		//logeado
	}
	protected function onThrought(){
		if(isset($this->arr_request_path[0])&&$this->arr_request_path[0]=='ValidarUsuario')
			return;
		Core_App::getLayout()->addActions('modulo','modulo_admin_users');
	}
	protected function delete(){
		$args = func_get_args();
		if(count($args)){
			$actions = array('entity_delete_action', 'delete_admin_user_action');
			call_user_method_array('addActions', Core_App::getLayout(),$actions);
			Core_App::getLayout()->addActions('entity_list', 'list_admin_user');//para que muestre la lista nueva;
			$helper_url_ajax = Core_App::getLoadedLayout()
					->getBlock('helper_url_ajax');
			if($helper_url_ajax){
				$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl('administrator/user/list'));
			}
			Admin_User_Model_User::actionDelete($args[0]);
		}
	}
	protected function addEdit(){
		Core_App::getInstance()->clearLastErrorMessages();
		if(count(Core_Http_Post::getParameters('array'))
			&& Admin_User_Model_User::actionAddEdit(Core_Http_Post::getParameters('object'))
			){//ya cargo el formulario
			$actions = array('entity_addedit_action', 'addedit_admin_user_action');
			call_user_method_array('addActions', Core_App::getLayout(),$actions);
			Core_App::getLayout()->addActions('entity_list', 'list_admin_user');//para que muestre la lista nueva;
			$helper_url_ajax = Core_App::getLoadedLayout()
					->getBlock('helper_url_ajax');
			if($helper_url_ajax){
				$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl('administrator/user/list'));
			}
		}
		else{
			$actions = array('entity_addedit', 'addedit_admin_user');
			call_user_method_array('addActions', Core_App::getLayout(),$actions);
			$args = func_get_args();
			if(count($args)){
				$add_edit_form = Core_App::getLoadedLayout()
						->getBlock('user_add_edit_form');
				if($add_edit_form){
					$add_edit_form->setIdToEdit($args[0]);
				}
			}
		}
		return(true);
	}
	protected function dispatchNode(){
		$actions = array(
			'list'=>array(
				'reset'=>false,
				'actions'=>array('entity_list', 'list_admin_user'),
			),
			'datalist'=>array(
				'reset'=>true,
				'actions'=>array('datalist', 'datalist_admin_user'),
			),
		);
		$args = func_get_args();
		if(!count($args)||!isset($actions[$args[0]]))
			return;
		$d = $actions[$args[0]];
		if($d['reset']){
			//var_dump($d);
			Core_App::getLayout()->setActions(array());
		}
		call_user_method_array('addActions', Core_App::getLayout(),$d['actions']);
		return(true);
	}
}
?>