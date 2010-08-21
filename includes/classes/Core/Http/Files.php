<?
abstract class Core_Http_Files{
	public static function countParameters(){
		return(self::hasParameters()?count($_FILES):0);
	}

	public static function hasParameters(){
		return(isset($_FILES) && is_array($_FILES) && count($_FILES));
	}

	public static function getParameters($modo=null){
		switch(strtolower($modo)){
//			case 'array':{
//				$arr_parameters = explode('&', $ret);
//				$ret = $_POST;
//				break;
//			}
//			case 'object':{
//				$arr_parameters = explode('&', $ret);
//				$ret = new StdClass;
//				foreach($_POST as $var=>$value){
//					$ret->$var = $value;
//				}
//				break;
//			}
			case 'core_object':{
				$arr_parameters = explode('&', $ret);
				$rets = array();
				foreach($_FILES as $filename=>$info){
					$ret = new Core_Object;
					foreach($info as $var=>$value)
						$ret->setData($var, $value);
					$rets[$filename] = $ret;
				}
				break;
			}
		}
		return($rets);
	}
}
?>