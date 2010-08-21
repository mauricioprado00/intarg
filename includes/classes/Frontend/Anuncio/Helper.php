<?php
class Frontend_Anuncio_Helper extends Core_Singleton{
	public function enviarEmailContacto($post){
		$anuncio = Core_App::getInstance()->getAnuncio($anuncio);
		
		$email_layout = new Granguia_Email('email_contacto','granguia/default/');
		$email_template = 
			$email_layout
				->loadDom()
				->getBlock('datos_contacto');
		$post->addAutofilterOutput('utf8_decode');
		$data = array();
		foreach($post->getData() as $key=>$value){
			if($key=='firma')
				break;
			$data[$key] = $post->getData($key);//la filtra
		}
		$info = new Core_Object($data);
		if($anuncio){
			$info
				->setData('Id Anuncio', $anuncio->getId())
			;
		}
		$email_template
			->setInfo($info)
		;
		$email_layout->agregarNotificacion();
		if($post->hasEmail()){
			$email = $post->getEmail();
			$email_layout->setFrom($email, $post->getNombre());
		}
		if($anuncio){
			if($anuncio->getEmailContacto()){
				$email_layout->AddAddress($anuncio->getEmailContacto(), utf8_decode($anuncio->getNombre()));
			}
		}
		//$email_layout->AddAddresses('Mr. kradkk<kradkk007@yopmail.com>');
		return $email_layout->enviar('Contacto','');
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}
?>