<?
class Granguia_User_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function getUrlRegisterForm(){
		return('usuario/registrar');
	}
	public static function getUrlLoginForm(){
		return('usuario/login');
	}
	public static function getUrlCerrarSesion(){
		return('usuario/logout');
	}
	public static function getUrlRecuperarPassword(){
		return('usuario/recover_password');
	}
	public static function existeMail($email){
		$usuario = new Granguia_User_Model_User();
		$usuario->setEmail($email);
		$usuario->setWhere(Db_Helper::equal('email'));
		
		$usuarios = $usuario->searchCount();
		return($usuarios?true:false);
	}
	public static function getUserByEmail($email){
		$usuario = new Granguia_User_Model_User();
		$usuario->setEmail($email);
		$usuario->setWhere(Db_Helper::equal('email'));
		$usuarios = $usuario->search(null, 'ASC', null, 0, 'Granguia_User_Model_User');
		if(!$usuarios||count($usuarios)!=1)
			return(null);
		return($usuarios[0]);
	}
	public static function getUserById($id){
		$usuario = new Granguia_User_Model_User();
		$usuario->setId($id);
		$usuario->setWhere(Db_Helper::equal('id'));
		$usuarios = $usuario->search(null, 'ASC', null, 0, 'Granguia_User_Model_User');
		if(!$usuarios||count($usuarios)!=1)
			return(null);
		return($usuarios[0]);
	}
	public static function actionRecuperarPassword($email){
		$usuario = self::getUserByEmail($email);
		if(!$usuario){
			Core_App::getInstance()->appendLastErrorMessages('El email "'.$email.'" no pertenece a una cuenta en el sitio.');
			return(false);
		}
//		Core_Http_Header::ContentType('text/plain');
//		var_dump($usuarios);
//		die();
		self::enviarMailRecuperacionPassword($usuario);
		return(true);
	}
	public static function enviarMailRecuperacionPassword($usuario){
		$email_layout = new Core_Email('email_recuperacion_password');
		$email_template = 
			$email_layout
				->loadDom()
				->getBlock('recuperacion_password');
		$email_template
			//->setVenta($venta)
			->setUsuario($usuario)
			//->setDetalles($detalles)
		;
		($email_layout->enviar(
			$usuario->getEmail(), 
			$usuario->getNombre().' '.$usuario->getApellido(), 
			'Recuperacion de contraseña'
		));
	}
	public static function makePasswordRecoveryLink($usuario){
		$userid = $usuario->getId();
		$timestamp = ((int)microtime(true)) + 60 * 5;
		$firma = sha1($userid . $timestamp . 'palabrasecretaparaencriptar');
		return(self::getUrlRecuperarPassword().'/'.$userid.'/'.$timestamp.'/'.$firma); 
	}
	public static function validatePasswordRecovery($userid, $timestamp, $firma){
		$timestamp_actual = ((int)microtime(true));
		if($timestamp_actual>$timestamp)
			return(false);
		$firma_posta = sha1($userid . $timestamp . 'palabrasecretaparaencriptar');
		if($firma_posta!=$firma)
			return(false);
		return(true);
	}
	public static function validarNuevoPassword($password){
		$return = true;
		if(strlen($password)<5){
			Core_App::getInstance()->appendLastErrorMessages('El password debe tener al menos 5 letras.');
			$return = false;
		}
		return($return);
	}
	public static function actionPasswordChange($id_usuario, $new_password){
		if(!$new_password)
			return(false);
		$usuario = self::getUserById($id_usuario);
		if(!$usuario)
			return(false);
		$usuario->setPassword($new_password);
		return($usuario->replace()?true:false);
	}
	public static function sendNewPassword($id_usuario, $new_password){
		if(!$new_password)
			return(false);
		$usuario = self::getUserById($id_usuario);
		if(!$usuario)
			return(false);
			
		$email_layout = new Core_Email('email_nuevo_password');
		$email_template = 
			$email_layout
				->loadDom()
				->getBlock('nuevo_password');
		$email_template
			//->setVenta($venta)
			->setUsuario($usuario)
			->setPassword($new_password)
			//->setDetalles($detalles)
		;
		($email_layout->enviar(
			$usuario->getEmail(), 
			$usuario->getNombre().' '.$usuario->getApellido(), 
			'Recuperacion de contraseña'
		));
	}
	public static function actionUserLogin($id_usuario){
		$usuario = self::getUserById($id_usuario);
		if(!$usuario)
			return(false);
		//$usuario = new Granguia_User_Model_User();
		$login_ok = 
			$usuario
				->login($usuario->getUsername(), $usuario->getPassword())
			;
		return($login_ok?true:false);
	}
	
}
?>