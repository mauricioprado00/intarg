<?
class Granguia_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function getUrlContactoCliente(){
		return('contacto');
	}
	public static function contactoCliente(){
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			if(
				$post->hasData('nombre') && $post->getData('nombre') &&
				$post->hasData('apellido') && $post->getData('apellido') &&
				$post->hasData('localidad') && $post->getData('localidad') &&
				$post->hasData('provincia') && $post->getData('provincia') &&
				$post->hasData('pais') && $post->getData('pais') &&
				$post->hasData('email') && $post->getData('email') &&
				$post->hasData('consulta') && $post->getData('consulta')
			){
				$errores = array();
				if(!Core_Helper::emailValido($post->getEmail())){
					$errores[] = ("El email no es válido");
				}
				if(!Core_Capcha_Helper::validarPost()){
					$errores[] = ("El código no es válido");
				}
				if(count($errores)){
					$errores = implode('<br />', $errores);
					Core_Registro::getInstance()->setMensajeErrorPaginaDeContacto($errores);
				}
				else if(self::sendMailContactoCliente($post)){
					Core_Registro::getInstance()->setMensajeErrorPaginaDeContacto("Su mensaje ha sido enviado");
				}
			}
		}
		Core_App::getLayout()->addActions('pagina_contacto');
	}
	private static function sendMailContactoCliente($post){
		$email_layout = new Core_Email('email_contacto_cliente');
		$email_template = 
			$email_layout
				->loadDom()
				->getBlock('contacto_cliente');
		$email_template
			->setInfo($post)
		;
		return($email_layout->recibir(
			$post->getEmail(), 
			$post->getNombre().' '.$post->getApellido(), 
			'Contacto de cliente'
		));
	}
}
?>