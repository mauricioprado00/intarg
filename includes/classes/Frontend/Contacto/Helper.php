<?php
class Frontend_Contacto_Helper extends Core_Singleton{
	public function getLinkForm(){
		if($tipo_nodo = Granguia_Model_TipoNodo::getTipoNodoByName('Contacto')){
			$nodo = new Granguia_Model_Nodo();
			$nodo->setIdTipoNodo($tipo_nodo->getId());
			if($nodo->load()){
				return $nodo->getPathUrl();
			}
		}
		return '';
	}
	public function getClaveEncriptacion(){
		return Granguia_Model_Config::findConfigValue('frontend_contacto/text_clave_encriptacion_contacto_form','Este es un texto aleatorio para generar una clave de encriptacion');
	}
	public function enviarEmailContacto($post){
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
		$email_template
			->setInfo($info)
			//->setVenta($venta)
			//->setUsuario($usuario)
			//->setDetalles($detalles)
		;
		$email_layout->agregarNotificacion();
		if($post->hasEmail()){
			$email = $post->getEmail();
			$email_layout->setFrom($email, $post->getNombre());
		}
		//$email_layout->AddAddresses('Mr. kradkk<kradkk007@yopmail.com>');
		return $email_layout->enviar('Contacto','');
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}
?>