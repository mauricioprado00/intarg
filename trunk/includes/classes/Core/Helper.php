<?
class Core_Helper extends Core_Singleton{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public function LookUpLayout(){
		$bt = debug_backtrace();
		foreach($bt as $bti){
			if($bti['object'] instanceof Base_Layout)
				return $bti['object'];
		}
		return Core_App::getInstance()->getLayout();
	}
	public static function max_execution_time($seg=null,$min=null,$horas=null){
		$min = $min + $horas * 60;
		$seg = $seg + $min * 60;
		return(ini_set('max_execution_time', $seg));
	}
    /**
    Validate an email address.
    Provide email address (raw input)
    Returns true if the email address has the email
    address format and the domain exists.
    */
    public static function emailValido($email){//}, $linuxServer = false) {
    	$windows = (isset($_ENV["OS"])&&preg_match('(windows)',strtolower($_ENV["OS"])));
    	$linuxServer = !$windows;
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        }
        else {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
            // local part length exceeded
                $isValid = false;
            }
            else if ($domainLen < 1 || $domainLen > 255) {
                // domain part length exceeded
                    $isValid = false;
                }
                else if ($local[0] == '.' || $local[$localLen-1] == '.') {
                    // local part starts or ends with '.'
                        $isValid = false;
                    }
                    else if (preg_match('/\\.\\./', $local)) {
                        // local part has two consecutive dots
                            $isValid = false;
                        }
                        else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                            // character not valid in domain part
                                $isValid = false;
                            }
                            else if (preg_match('/\\.\\./', $domain)) {
                                // domain part has two consecutive dots
                                    $isValid = false;
                                }
                                else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
                                    // character not valid in local part unless
                                    // local part is quoted
                                        if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
                                            $isValid = false;
                                        }
                                    }

            if ($linuxServer && $isValid && !(checkdnsrr($domain,"MX") ||  checkdnsrr($domain,"A"))) {
            // domain not found in DNS
                $isValid = false;
            }
        }
        return $isValid;
    }
    public static function DebugVars(){
		$args = func_get_args();
		$pre = new Core_Html_Tag_Custom('pre');
		$pre
			->setStyle('margin-left: 257px; background-color: white;')
		;
		ob_start();
		if(count($args)==1)$args=array_pop($args);
		var_dump($args);
		$c = ob_get_contents();
		ob_end_clean();
		$c = htmlentities($c);
		$boton = new Core_Html_Tag_Custom('input');
		$boton->setType('button')->setValue('quitar')->setOnclick('javascript:jQuery(this).parents("pre:first").remove();');
		$c = $boton->getHtml() . $c .$boton->getHtml();
		$pre->setInnerHtml($c);
		return $pre->getHtml();
	}

}
?>