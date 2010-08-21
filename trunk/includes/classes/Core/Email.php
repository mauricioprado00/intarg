<?
class Core_Email extends Base_Layout{
	public function __construct($action,$design_path='frontendv3/default/'){
		$this
			->setModo('email')
			->addDesignPaths('email',$design_path)
			->addActions($action);
		Core_App::getInstance()->loadLayoutUpdates($this);
	}
	public function getEmailVentas($idx=0){
		if($idx==0)
			return array_shift(explode(';', Granguia_Model_Config::findConfigValue('core_email/email_notificacion_ventas')));
		$arr = explode(';', Granguia_Model_Config::findConfigValue('core_email/email_notificacion_ventas'));
		if(count($arr)<=$idx)
			return null;
		return array_shift(array_slice($arr, $idx, 1));
	}
	public function getEmailVentasName($idx=0){
		if($idx==0)
			return array_shift(explode(';', Granguia_Model_Config::findConfigValue('core_email/email_notificacion_ventas_nombre')));
		$arr = explode(';', Granguia_Model_Config::findConfigValue('core_email/email_notificacion_ventas_nombre'));
		if(count($arr)<=$idx)
			return null;
		return array_shift(array_slice($arr, $idx, 1));
	}
	public function getEmailsVentasAll($email_from_name = null){
		$mail_to = array();
		$consultar_nombre = $email_from_name===null;
		while($email_from = $this->getEmailVentas($idx)){
			if($consultar_nombre){
				$nuevo_nombre = $this->getEmailVentasName($idx);
				if($nuevo_nombre)
					$email_from_name = $nuevo_nombre;
				//$email_from_name = Granguia_Model_Config::findConfigValue('core_email/email_notificacion_ventas_nombre');
			}
			$mail_to[] = array($email_from, $email_from_name);
			$idx++;
		}
		return $mail_to;
	}
	public function enviar($email, $nombre, $subject='', $altbody='', $email_from = null, $email_from_name = null, $cco = true){
//		$x = func_get_args();
//		file_put_contents(dirname(__FILE__).'/'.basename(__FILE__).'.log.'.__FUNCTION__, var_export($x, true));
		$mailer = new Core_Mailer();
		if($email_from === null)
			//$email_from = Granguia_Model_Config::findConfigValue('core_email/email_notificacion_ventas');
			$email_from = $this->getEmailVentas();
		if($email_from_name === null){
			//$email_from_name = Granguia_Model_Config::findConfigValue('core_email/email_notificacion_ventas_nombre');
			$email_from_name = $this->getEmailVentasName();
			if($email_from_name)
				$email_from_name = utf8_decode($email_from_name);
		}
		if($cco)
			return(
				$mailer
					->enviar2(
						array($email_from, $this->filter($email_from_name)),
						array(
							array($email, $this->filter($nombre))
						),
						//array(
//							array($email_from, $this->filter($email_from_name))
//						)
						$this->getEmailsVentasAll(),
						$this->filter($this->renderOutput(false)),
						$this->filter($subject),$this->filter($altbody)
					)
			);
		return(
			$mailer
				->enviar(
					array($email_from, $this->filter($email_from_name)),
					array(
						array($email, $this->filter($nombre))
					),
					$this->filter($this->renderOutput(false)),
					$this->filter($subject),$this->filter($altbody)
				)
		);
	}
	private $_output_filters = null;
	private function outputFilters($filters){
		$this->_output_filters = $filters;
	}
	private function filter($txt){
		if($this->_output_filters)
			foreach($this->_output_filters as $filter){
				$txt = call_user_func($filter, $txt);
			}
		return($txt);
	}
	public function addUtf8DecodeFilter(){
		$this->_output_filters[] = 'utf8_decode';
		return($this);
	}
	public function addUtf8EncodeFilter(){
		$this->_output_filters[] = 'utf8_encode';
		return($this);
	}
//	public function renderOutput($to_output=true){
//		$ret = parent::renderOutput($to_output);
//		return($this->filter($ret));
//	}
	public function recibir($email, $nombre, $subject='', $altbody='', $email_from = null, $email_from_name = null){
		$mail_to = array();
		if($email_from!==null){
			if($email_from === null)
				$email_from = Granguia_Model_Config::findConfigValue('core_email/email_notificacion_ventas');
			if($email_from_name === null)
				$email_from_name = Granguia_Model_Config::findConfigValue('core_email/email_notificacion_ventas_nombre');
			$mail_to[] = array($email_from, $email_from_name);
		}
		else{
			$idx = 0;
			$consultar_nombre = $email_from_name === NULL;
			while($email_from = $this->getEmailVentas($idx)){
				if($consultar_nombre){
					$nuevo_nombre = $this->getEmailVentasName($idx);
					if($nuevo_nombre)
						$email_from_name = $nuevo_nombre;
					//$email_from_name = Granguia_Model_Config::findConfigValue('core_email/email_notificacion_ventas_nombre');
				}
				$mail_to[] = array($email_from, $email_from_name);
				$idx++;
			}
		}
		$ret = true;
		foreach($mail_to as $to){
			$ret .= $this->enviar($to[0], $to[1], $subject, $altbody, $email, $nombre, false);
		}
		return($ret);
	}
}
?>