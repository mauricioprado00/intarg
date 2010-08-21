<?






















class Granguia_Email extends Core_Email2{
	public function agregarNotificacion($tobbc=true, $tocc=false){
		$emails = Granguia_Model_Config::findConfigValue('granguia_email/bbc_emails','mauricio<mauricioprado00@hotmail.com>');
		return $this->AddAddresses($emails, $tobbc, $tocc);
	}
	public function fromSelf(){
		$fromemail = Granguia_Model_Config::findConfigValue('granguia_email/email_send_from_email','info@granguia.com.ar');
		$fromname = Granguia_Model_Config::findConfigValue('granguia_email/email_send_from_name','GranGuia');
		return $this->AddAddresses($emails, true);
	}
	public function enviar($subject=null, $altBody=null){
		return $this->Send($this->renderOutput(false), $subject, $altBody);
	}
}
?>