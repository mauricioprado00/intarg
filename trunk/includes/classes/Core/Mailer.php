<?php
include_once(dirname(__FILE__).'/external_library/phpmailer/phpmailer.php');
class Core_Mailer{
	var $host;
	var $port;
	var $username;
	var $password;
	var $mail;


	function getParameters(){
		$this->host = Granguia_Model_Config::findConfigValue('core_mailer/email_server_host');//"mail.insis-sa.com.ar";
		$this->port = Granguia_Model_Config::findConfigValue('core_mailer/email_server_port');//"25";
		$this->username = Granguia_Model_Config::findConfigValue('core_mailer/email_server_username');//"mprado@insis-sa.com.ar";
		$this->password = Granguia_Model_Config::findConfigValue('core_mailer/email_server_password');//"mauricioinsis";
	}
	
	function Core_Mailer(){
		$this->mail = $this->mail = new PHPMailer();
		$this->getParameters();
		$this->mail->SetLanguage("es",CFG_PATH_ROOT.CONF_PATH_CORE."external_library/phpmailer/language/");
		$this->mail->PluginDir = CFG_PATH_ROOT.CONF_PATH_CORE."external_library/phpmailer/";
		$this->mail->Mailer = "smtp";
		$this->mail->Timeout=30;
	}
  /**
   * Core_Mailer::enviar()
   * Envia un email a uno o mas destinos
   * @param mixed $arr_from, un array de 2 elementos array('from@email','nombre')
   * @param mixed $arr_to, un array con arrays de recipientes array(array('to1@email','nombre1'),array('to2@email','nombre2'))
   * @param mixed $body
   * @param string $subject
   * @param string $altBody
   * @return resultado del envio
   */
	function enviar($arr_from,$arr_to,$body,$subject="",$altBody=""){
//		$x = func_get_args();
//		file_put_contents(dirname(__FILE__).'/'.basename(__FILE__).'.log.'.__FUNCTION__, var_export($x, true));
		if(	gettype($arr_from)!="array"
			||gettype($arr_to)!="array"
			||count($arr_from)<1||count($arr_from)>2
			||count($arr_to)<1
			||$body==""
			)
			return(false);
		$this->mail->Host = $this->host;
		$this->mail->Port = $this->port;
		$this->mail->Username = $this->username;
		$this->mail->Password = $this->password;
		$this->mail->SMTPAuth = true;
		//var_dumpre(debug_backtrace(),'de aca pa tras para enviar un mail');
		$this->mail->From = $arr_from[0];
		if(count($arr_from)==2)
			$this->mail->FromName = $arr_from[1];
		foreach($arr_to as $to){
			$nombre="";
			if(gettype($to)=="array"){
				if(count($to)>2||count($to)<1)
					return(false);
				$address = $to[0];
				$nombre = count($to)==2?$to[1]:""; 
			}
			else $address = $to; 
			$this->mail->AddAddress($address,$nombre);
		}
		$this->mail->Subject = $subject;
		$this->mail->Body = $body;
		$this->mail->AltBody = $altBody==""?"Este correo posee un mensaje en formato HTML que no puede ser visualizado en su lector de correo.":$altBody;
		return($this->mail->Send());
	}
	
  /**
   * Core_Mailer::enviar2()
   * Envia un email a uno o mas destinos y una o mas copias oculatas
   * @param mixed $arr_from, un array de 2 elementos array('from@email','nombre')
   * @param mixed $arr_to, un array con arrays de recipientes array(array('to1@email','nombre1'),array('to2@email','nombre2'))
   * @param mixed $arr_to_co, un array con arrays de recipientes array(array('cco1@email','nombre1'),array('cco2@email','nombre2'))
   * @param mixed $body
   * @param string $subject
   * @param string $altBody
   * @return resultado del envio
   */
	function enviar2($arr_from,$arr_to,$arr_to_co,$body,$subject="",$altBody=""){
		
//		$x = func_get_args();
//		file_put_contents(dirname(__FILE__).'/'.basename(__FILE__).'.log.'.__FUNCTION__, var_export($x, true));
		if(	gettype($arr_from)!="array"
			||gettype($arr_to)!="array"
			||gettype($arr_to_co)!="array"
			||count($arr_from)<1||count($arr_from)>2
			||count($arr_to)<1
			||$body==""
			)
			return(false);
//		$x = func_get_args();
//		file_put_contents(dirname(__FILE__).'/log.log', var_export($x, true));
		$this->mail->Host = $this->host;
		$this->mail->Port = $this->port;
		$this->mail->Username = $this->username;
		$this->mail->Password = $this->password;
		$this->mail->SMTPAuth = true;
		
		$this->mail->From = $arr_from[0];
		if(count($arr_from)==2)
			$this->mail->FromName = $arr_from[1];
		foreach($arr_to as $to){
			$nombre="";
			if(gettype($to)=="array"){
				if(count($to)>2||count($to)<1)
					return(false);
				$address = $to[0];
				$nombre = count($to)==2?$to[1]:""; 
			}
			else $address = $to; 
			$this->mail->AddAddress($address,$nombre);
		}
		
		foreach($arr_to_co as $to){
			$nombre="";
			if(gettype($to)=="array"){
				if(count($to)>2||count($to)<1)
					return(false);
				$address = $to[0];
				$nombre = count($to)==2?$to[1]:""; 
			}
			else $address = $to;
			$this->mail->AddBCC($address,$nombre);
		}
		$this->mail->Subject = $subject;
		$this->mail->Body = $body;
		$this->mail->AltBody = $altBody==""?"Este correo posee un mensaje en formato HTML que no puede ser visualizado en su lector de correo.":$altBody;
		return($this->mail->Send());
	}
}
?>