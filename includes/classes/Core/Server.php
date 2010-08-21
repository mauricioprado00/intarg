<?php
class Core_Server{
	public static function getRedirectStatus(){
		return $_SERVER['REDIRECT_STATUS'];
	}
	public static function getHttpHost(){
		return $_SERVER['HTTP_HOST'];
	}
	public static function getHttpUserAgent(){
		return $_SERVER['HTTP_USER_AGENT'];
	}
	public static function getHttpAccept(){
		return $_SERVER['HTTP_ACCEPT'];
	}
	public static function getHttpAcceptLanguage(){
		return $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	}
	public static function getHttpAcceptEncoding(){
		return $_SERVER['HTTP_ACCEPT_ENCODING'];
	}
	public static function getHttpAcceptCharset(){
		return $_SERVER['HTTP_ACCEPT_CHARSET'];
	}
	public static function getHttpKeepAlive(){
		return $_SERVER['HTTP_KEEP_ALIVE'];
	}
	public static function getHttpConnection(){
		return $_SERVER['HTTP_CONNECTION'];
	}
	public static function getHttpCookie(){
		return $_SERVER['HTTP_COOKIE'];
	}
	public static function getPath(){
		return $_SERVER['PATH'];
	}
	public static function getServerSignature(){
		return $_SERVER['SERVER_SIGNATURE'];
	}
	public static function getServerSoftware(){
		return $_SERVER['SERVER_SOFTWARE'];
	}
	public static function getServerName(){
		return $_SERVER['SERVER_NAME'];
	}
	public static function getServerAddr(){
		return $_SERVER['SERVER_ADDR'];
	}
	public static function getServerPort(){
		return $_SERVER['SERVER_PORT'];
	}
	public static function getRemoteAddr(){
		return $_SERVER['REMOTE_ADDR'];
	}
	public static function getDocumentRoot(){
		return $_SERVER['DOCUMENT_ROOT'];
	}
	public static function getServerAdmin(){
		return $_SERVER['SERVER_ADMIN'];
	}
	public static function getScriptFilename(){
		return $_SERVER['SCRIPT_FILENAME'];
	}
	public static function getRemotePort(){
		return $_SERVER['REMOTE_PORT'];
	}
	public static function getRedirectUrl(){
		return $_SERVER['REDIRECT_URL'];
	}
	public static function getGatewayInterface(){
		return $_SERVER['GATEWAY_INTERFACE'];
	}
	public static function getServerProtocol(){
		return $_SERVER['SERVER_PROTOCOL'];
	}
	public static function getRequestMethod(){
		return $_SERVER['REQUEST_METHOD'];
	}
	public static function getQueryString(){
		return $_SERVER['QUERY_STRING'];
	}
	public static function getRequestUri(){
		return $_SERVER['REQUEST_URI'];
	}
	public static function getScriptName(){
		return $_SERVER['SCRIPT_NAME'];
	}
	public static function getPhpSelf(){
		return $_SERVER['PHP_SELF'];
	}
	public static function getRequestTime(){
		return $_SERVER['REQUEST_TIME'];
	}
	public static function getArgv(){
		return $_SERVER['argv'];
	}
	public static function getArgc(){
		return $_SERVER['argc'];
	}
##METODO_CREADOR
	public static function regenerarClase(){
Core_Http_Header::ContentType('text/plain');
echo "<?php\nclass Core_Server{\n";
foreach($_SERVER as $key=>$value){
	$method_name = 'get'.Core_Object::_camelize(ucwords(strtolower(str_replace('_',' ',$key))));
$method = <<<metodo
	public static function $method_name(){
		return \$_SERVER['$key'];
	}

metodo;
echo $method;
}
$c = file_get_contents(__FILE__);
$label = '#'.'#METODO_CREADOR';
$partes = explode($label, $c);
echo $label.$partes[1]."\n".$label."\n";
echo "}\n?>";
	}

##METODO_CREADOR
}
?>