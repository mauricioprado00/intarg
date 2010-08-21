<?
class Base_Errors{
	const CFG_SHOW_ERRORS = 0;
	const CFG_SHOW_ERRORS_MENSAJE = 1;
	const CFG_SHOW_ERRORS_FULL_INFO = 1;
	private static $debug = true;
	private static $errors = array();
	public static function Add($mensaje,$informacion, $code=null){
		if(self::CFG_SHOW_ERRORS){
			if(self::CFG_SHOW_ERRORS_FULL_INFO){
				if(self::CFG_SHOW_ERRORS_MENSAJE)
					echo $mensaje;
				var_dump($informacion);
			}
			else echo $mensaje;
		}
		self::$errors[] = new Base_Error($mensaje,$informacion,$code);
	}
	public static function getLastError($backward_index=0,$truncate=null){
		return(self::getError(count(self::$errors)-$backward_index-1,$truncate));
	}
	public static function clear(){
		self::$errors = array();
	}
	public static function count(){
		return(count(self::$errors));
	}
	public static function getError($idx=0,$truncate=null){
		Base_Parameters::validate(array('idx'=>'integer','truncate'=>'integer||null||boolean'),1,2);
		if($idx>=count(self::$errors)||$idx<0){
			return(false);
		}
		$ret = self::$errors[$idx];
		if($truncate!==null&&$idx===0){
			if($truncate===true)
				self::truncate(0,$truncate);
		}
		return($ret);
	}
	public static function getErrors($idx){
		if($idx>=count(self::$errors))
			return(false);
		return(array_slice(self::$errors,$idx));
	}
	public static function truncate($idx,$cant=null){
		Base_Parameters::validate(array('idx'=>'integer','cant'=>'integer||null||boolean'),1,2);
		if($idx>=count(self::$errors))
			return(false);
		self::$errors = array_slice(self::$errors, 0, -$idx); 
	}

	public static function triggerError($mensaje,$backwards=1,$error = E_USER_ERROR){
		$msgs = array(
			E_USER_ERROR=>array('msg'=>'Fatal error','asoc'=>E_ERROR),
			E_USER_NOTICE=>array('msg'=>'Notice','asoc'=>E_NOTICE),
			E_USER_WARNING=>array('msg'=>'Warning','asoc'=>E_WARNING),
		);
		$types = array_keys($msgs);
		if(!in_array($error,$types)){
			$mensaje = "Invalid Error Type ".$error;
			$backwards = 1;
			$error = E_USER_ERROR;
		}
		extract($msgs[$error]);
		if($show_error = error_reporting()&$asoc){//si esta seteado mostrar errores de este tipo
			$data = debug_backtrace();
			$data = $data[$backwards];
			echo "<br />\n".'<b>'.$msg.'</b>:  '.$mensaje.' in <b>'.$data['file'].'</b> on line <b>'.$data['line'].'</b><br />'."\n";
		}
		if($error==E_USER_ERROR)
			die();
	}
}
?>