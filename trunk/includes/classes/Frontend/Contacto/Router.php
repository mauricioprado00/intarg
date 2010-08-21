<?php
class Frontend_Contacto_Router extends Core_Router_Abstract{
	protected function initialize(){
	}
	private function showContacto(){
		Core_App::getLayout()
			->addActions('nodo_contacto')
		;
		if($contacto_view = Core_App::getLoadedLayout()->getBlock('contacto_view')){
			$contacto_view->setContacto(Core_App::getInstance()->getContacto());
			return true;
		}
		return false;
	}
	private function showFormularioContacto(){
		Core_App::getLayout()
			->addActions('pagina_contacto')
		;
		
		if($c=Core_App::getLoadedLayout()->getBlock('mainmenu'))
			$c->setSelectedItem('btn_contactos');
		//var_dump(Core_App::getLayout()->getActions());
		if($contacto_form = Core_App::getLoadedLayout()->getBlock('contacto_form')){
			die("ok");
			//$contacto_form->set()
			return true;
		}
		return false;
	}
	protected function localDispatch(){
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			$mensaje = 'La firma es incorrecta';
			$fields = array();
			foreach($post->getData() as $key=>$value){
				if($key=='firma'){
					$fields = implode('-', $fields);
					$digest = $fields.Frontend_Contacto_Helper::getInstance()->getClaveEncriptacion();
					$firma = sha1($digest);
					if($firma==$post->getFirma()){
						$valido = Core_Capcha_Helper::validarPost('capcha_code');
						if($valido){
							$post = Core_Http_Post::getParameters('Core_Object');
							if(!$post->hasEmail() || Core_Helper::emailValido($post->getEmail())){
								Frontend_Contacto_Helper::getInstance()->enviarEmailContacto($post);
								$mensaje = 'Gracias por comunicarse con nosotros.';
							}
							else{
								$mensaje = 'El email ingresado no es un email válido. Corrijalo.';
							}
						}
						else{
							$mensaje = 'El codigo es inválido';
						}
					}
					break;
				}
				$fields[] = $key;
			}
			echo json_encode(array('resultado'=>$valido,'mensaje'=>utf8_encode($mensaje)));
			die();
		}
		$this->showFormularioContacto();
		return true;
	}
//	protected function dispatchNode(){
//		return false;
//	}
}
?>