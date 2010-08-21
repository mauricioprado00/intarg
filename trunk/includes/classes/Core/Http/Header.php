<?
class Core_Http_Header{
	public static function ContentType($mime_type=null){
		if($mime_type!=null){
			header('content-type: '.$mime_type.';');
			//var_dump('content-type: '.$mime_type.';');
		}
	}
	public static function ContentEncoding($type='gzip'){
		header('Content-Encoding: '.$type);
	}
	public static function ContentDisposition($filename, $disposition = 'attachment'){
		if($disposition){
			header('content-disposition: '.$disposition.'; '.($filename?'filename="'.$filename.'"':''));
		}
	}
	public static function OutputFile($filepath, $filename='', $add_extension = false){
		if($filepath && file_exists($filepath)){
			if(!$filename)
				$filename = basename($filepath);
			elseif($add_extension && strpos($filepath, '.')!==false)
				$filename .= '.'.array_pop(explode('.', $filepath));
			if($mime = mime_content_type($filepath)){
				self::ContentType($mime);
			}
//			var_dump($add_extension, utf8_decode($filename), $add_extension && strpos($filepath, '.')!==false);
//			die();
			self::ContentDisposition($filename);
			readfile($filepath);
		}
	}
	public static function Error($http_error_code){
		$errors = array(
			'404'=>'HTTP/1.0 404 Not Found',
		);
		$error_codes = array_keys($errors);
		if(in_array($http_error_code, $error_codes)){
			header($errors[$http_error_code]);
			//echo $errors[$http_error_code];
		}
	}
	public static function Redirect($url, $app_url=false){
		if($app_url){
			$url = Core_App::getUrlModel()->getUrl($url);
		}
		header('location: '.$url);
		die();
	}
	public static function canonicalHeaderName($header_name){
		return strtoupper(preg_replace('([^A-Za-z0-9_])', '', $header_name));
	}
	public static function getRequest($header_name){
		$header_name = self::canonicalHeaderName($header_name);
		return isset($_SERVER['HTTP_' . $header_name])?$_SERVER['HTTP_' . $header_name]:null;
	}
	public static function isAjaxRequest(){
		return self::getRequest('X_REQUESTED_WITH')=='XMLHttpRequest';
		//return isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&$_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest';
	}
	public static function setResponse($header_name, $value){
		header($header_name.': '.$value);
	}
	public static function avoidCache(){
		header ("Expires: Thu, 27 Mar 1980 23:59:00 GMT"); //la pagina expira en una fecha pasada
		header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
		header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
		header ("Pragma: no-cache");
	}
}
?>