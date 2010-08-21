<?php
include_once(dirname(__FILE__).'/external_library/phpmailer/phpmailer.php');
class Core_Mailer2 extends PHPMailer{

	public function __construct(){
		parent::__construct();
		$this->SetLanguage("es",CFG_PATH_ROOT.CONF_PATH_CORE."external_library/phpmailer/language/");
		$this->PluginDir = CFG_PATH_ROOT.CONF_PATH_CORE."external_library/phpmailer/";
		$this->Mailer = "smtp";
		$this->Timeout=30;
		$parametros = $this->getParametros();
		$this->Host = $parametros->host;
		$this->Port = $parametros->port;
		$this->Username = $parametros->username;
		$this->Password = $parametros->password;
		$this->SMTPAuth = true;
	}
	
	private $_parametros = null;
	function getParametros(){
		if(!isset($this->_parametros)){
			$this->_parametros = ((object)
				array(
					'host'=>Granguia_Model_Config::findConfigValue('core_mailer2/email_server_host','smtp.example.com.ar'),
					'port'=>Granguia_Model_Config::findConfigValue('core_mailer2/email_server_port','25'),
					'username'=>Granguia_Model_Config::findConfigValue('core_mailer2/email_server_username','username@example.com.ar'),
					'password'=>Granguia_Model_Config::findConfigValue('core_mailer2/email_server_password','password_example'),
				)
			);
		}
		return $this->_parametros;
	}
	public function AddAddresses($emails, $tobbc=false, $tocc=false){
		if(!$emails)
			return false;
		$emails = explode(',', $emails);
		foreach($emails as $email){
			$email = trim($email);
			$re = '((?<primera_parte>[^<]*)([<](?<email>[^<]+)[>])?)';
			if(preg_match($re, $email, $matches)){
				if($matches['email']){
					$nombre = $matches['primera_parte'];
					$email = $matches['email'];
				}
				else{
					$nombre = '';
					$email = $matches['primera_parte'];
				}
				ob_start();
				if($tobbc)
					$this->AddBCC($email, $nombre);
				elseif($tocc)
					$this->AddCC($email, $nombre);
				else
					$this->AddAddress($email, $nombre);
				ob_end_clean();
			}
		}
		return count($emails)?true:false;
	}
	
	public function Send($body=null,$subject=null,$altBody=null) {
		if(isset($body))
			$this->Body = $body;
		if(isset($subject))
			$this->Subject = $subject;
		if(isset($altBody))
			$this->AltBody = $altBody==""?"Este correo posee un mensaje en formato HTML que no puede ser visualizado en su lector de correo.":$altBody;
		if(!isset($this->Body))
			return false;
		return(parent::Send());
	}	
}
?>