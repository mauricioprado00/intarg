<?
class Granguia_User_Router_User extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		//$this->addActions('ValidarUsuario');
		//$this->addActions('cerrar_sesion');
		//$this->addActions('addEdit');
		$this->addActions(
			'registrar',
			'login',
			'logout',
			'recover_password'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo_usuario');
	}
	public function login(){
		$errors = array();
		if(Core_Http_Post::hasParameters()){
			$data = Core_Http_Post::getParameters('Core_Object');
			$usuario = new Granguia_User_Model_User();
			$login_ok = 
				$usuario
					->login($data->getUsername(), $data->getPassword())
				;
		}
		Core_App::getLayout()->addActions('resultado_login_usuario');
	}
	public function logout(){
		$x = new Granguia_User_Model_User();
		$x->logout();
		Core_Http_Header::Redirect(Core_App::getUrlModel()->getUrl(''));
	}
	public function registrar(){
		$errors = array();
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('array');
			if(isset($post['email'])&&!Core_Helper::emailValido($post['email'])){
				$errors[] = utf8_encode("El email no es válido");
			}
			if(!Core_Capcha_Helper::validarPost()){
				$errors[] = utf8_encode("Ingrese el código correctamente.");
			}
			if(Granguia_User_Helper::existeMail($post['email'])){
				$errors[] = utf8_encode("El email ya está en uso.");
			}

			if(!count($errors)){
				$usuario = Granguia_User_Model_User::actionRegistrar($post);
				if($usuario){
					$enviado = $usuario->actionEnviarCorreoRegistro($post['password']);
					if(!$enviado){
						$errors[] = "No se pudo enviar correo de confirmaci&oacute;n";
					}
					$logeado = $usuario->login($usuario->getUsername(), $usuario->getPassword());
					if(!$logeado){
						$errors[] = "No se pudo iniciar sesi&oacute;n";
					}
					Core_App::getLayout()->addActions('registro_usuario_ok');
					$registro_usuario_ok =
						Core_App::getInstance()
							->loadLayoutDom()
							->getLayout()
							->getBlock('registro_usuario_ok');
					if($errors){
						$registro_usuario_ok->setErrorMessages($errors);
					}
					return;
				}
				else{
					$errors[] = "El nombre de usuario ya existe, elija otro";
				}
			}
		}
		Core_App::getLayout()->addActions('registro_usuario', 'kradkk');
		$user_form_register =
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('user_form_register');
		if(Core_Http_Post::hasParameters()){
				$user_form_register
					->setDatos(Core_Http_Post::getParameters('Core_Object'));
		}
		if($errors){
			$user_form_register->setErrorMessages($errors);
		}
	}
	protected function recover_password($id_usuario='',$vencimiento='',$firma=''){
		Core_App::getInstance()->clearLastErrorMessages();
		if($id_usuario && $vencimiento){
			if(Granguia_User_Helper::validatePasswordRecovery($id_usuario, $vencimiento, $firma)){
				if(Core_Http_Post::hasParameters()){
					$post = Core_Http_Post::getParameters('Core_Object');
					if($post->hasPassword()){
						if(Granguia_User_Helper::validarNuevoPassword($post->getPassword())){
							if(Granguia_User_Helper::actionPasswordChange($id_usuario, $post->getPassword())){
								if(Granguia_User_Helper::actionUserLogin($id_usuario)){
									Granguia_User_Helper::sendNewPassword($id_usuario, $post->getPassword());
									Core_App::getLayout()->addActions('resultado_login_usuario');
									return;
								}
							}
							Core_App::getInstance()->appendLastErrorMessages('Ha ocurrido un error, vuelta a intentarlo.');
						}
					}
				}
				Core_App::getLayout()->addActions('form_recover_password_usuario');
				return;
			}
			Core_App::getInstance()->appendLastErrorMessages('El link no es válido o a caducado, vuelva a soliciar un nuevo link.');
		}
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			if($post->hasEmail()){
				$errores = array();
				if(!Core_Helper::emailValido(trim($post->getEmail())))
					$errores[] = 'El email "'.$post->getEmail().'" no es un email válido.';
				if(!Core_Capcha_Helper::validarPost()){
					$errores[] = "Ingrese el código correctamente.";
				}

				if(!count($errores)){
					if(Granguia_User_Helper::actionRecuperarPassword($post->getEmail())){
						Core_App::getLayout()->addActions('recover_password_usuario_enviado');
						$usuario = Granguia_User_Helper::getUserByEmail(trim($post->getEmail()));
//						Core_Http_Header::ContentType('text/plain');
//						var_dump($usuario);
//						die();
						if($usuario){
							$recuperar_password_enviado =
								Core_App::getInstance()
									->loadLayoutDom()
									->getLayout()
									->getBlock('recuperar_password_enviado');
							if($recuperar_password_enviado)
								$recuperar_password_enviado->setUsuario($usuario);
						}
						return;	
					}
				}
				else{
					foreach($errores as $error)					
						Core_App::getInstance()->appendLastErrorMessages($error);
				}
			}
		}
		Core_App::getLayout()->addActions('recover_password_usuario');
	}
	
	
}
?>