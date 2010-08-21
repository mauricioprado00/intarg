<?
abstract class Core_Http_Get{
	private static $parameters;
	private static $request;
	private static $path;
	
	public static function getParameters($modo=null){
		if(true||Core_App::getFancyUrlEnabled()){
			self::initialize();
			$ret = self::$parameters;
			$arr_parameters = explode('&', $ret);
			
			$ret = array();
			$x = new stdclass;
			foreach($arr_parameters as $str_parameter){
				$arr_parameter = explode('=', $str_parameter);
				if(!$arr_parameter[0])
					continue;
				$value = count($arr_parameter)>1?$arr_parameter[1]:true;
				$ret[urldecode($arr_parameter[0])] = urldecode($value);
			}
			$arr_get = $ret;
		}
		else{
			$arr_parameters = $_GET;
			$arr_get = $_GET;
			
		}
		switch(strtolower($modo)){
			case 'array':{
				$ret = $arr_get;
				break;
			}
			case 'object':{
				$ret = new StdClass;
				foreach($arr_get as $idx=>$value){
					$ret->$idx = strlen($value)?$value:true;
				}
				break;
				foreach($arr_parameters as $str_parameter){
					$arr_parameter = explode('=', $str_parameter);
					if(!$arr_parameter[0])
						continue;
					$value = count($arr_parameter)>1?$arr_parameter[1]:true;
					$ret->$arr_parameter[0] = urldecode($value);
				}
				break;
			}
			case 'core_object':{
				//$arr_parameters = explode('&', $ret);
				$ret = new Core_Object;
				foreach($arr_get as $var=>$value){
					$ret->setData($var, $value);
				}
				break;
			}
		}
		return($ret);
	}
	public static function getRequest(){
		self::initialize();
		return(self::$request);
	}
	public static function getPath(){
		self::initialize();
		return(self::$path);
	}
	private static function initialize(){
		static $initialized = false;
		if($initialized)
			return;
		if(true||Core_App::getFancyUrlEnabled()){
			self::$request = str_replace(CONF_PATH_APP, '', $_SERVER['REQUEST_URI']);
		}
		else{
			self::$request = $_SERVER['PATH_INFO'];
		}
		$arr_request = explode('?', self::$request);
		if(count($arr_request)>1){
			self::$parameters = array_pop($arr_request);
		}
		self::$path = array_pop($arr_request);
	}
}
?>