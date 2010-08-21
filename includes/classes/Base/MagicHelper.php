<?php
class Base_MagicHelper{
	static $Browser_Name="";
	static $Browser_Version="";
	static $Browser_Platform="";
	public static function PathResolve($path){
		//$debug = $path === 'C:\AppServ\www\MundoAceite\includes\package\../../includes/configuraciones.php'&&false;
		$args = func_get_args();
		$debug = count($args)>1&&$args[1]?true:false;
		$equivalencias_canonicas = array(
			array('([\\\\])'=>'/')							//		CAMBIA \ POR /
			,
			array('(([/])([/]))'=>'$1')						//		CAMBIA // POR /
			,
			array('([a-zA-Z0-9_-]+([/][.][.][/]))'=>'')		//		CAMBIA DIRECTORIO/.. POR ./
			,
			array('([a-zA-Z0-9_-]+([/][.][.]))'=>'')		//		CAMBIA DIRECTORIO/.. POR ./
			,
			array('((/)([.])([/])([.]))'=>'/.')			//		CAMBIA ./. POR .
			,
			array('(([^.])([/])([.])([/]))'=>'$1/')			//		CAMBIA /./ POR /
			,
			array('(([/])([/]))'=>'/')						//		CAMBIA // POR /
		);
		//$path = implode("/",explode("\\",$path));
		//var_dump($equivalencias_canonicas);
		$seReemplazo=true;
		while($seReemplazo){
			$seReemplazo = false;
			foreach($equivalencias_canonicas as $equivalencia_canonica)
			foreach($equivalencia_canonica as $pattern=>$replacement){
				while(true)
				{
					//echo "buscando $pattern en $path<br>";
					if(!preg_match($pattern, $path))
						break;
					$seReemplazo = true;
					$path = preg_replace($pattern, $replacement, $path);
					if($debug)
						var_dump('							'.$path.'  '.$pattern.'=>'.$replacement);
				}
			}
			if(!$seReemplazo)
				break;
		}
		return($path);
	}
	public static function GetFileContent($f){
		$c = filesize($f);
		$f = fopen($f,"r");
		$c = fread($f,$c);
		fclose($f);
		return($c);
	}
	public static function PrintLineNumber($where,$line='',$prev_lines=0,$post_lines=0){
		if($line!==''){
			$regexp = MagicParser::Linea($line,$cual,$prev_lines,$post_lines);
			//var_dump(array($regexp,$cual));
			return(preg_replace('('.$regexp.')',$cual,$where));
		}
		$regexp = MagicParser::Bloque('//[dontprint]','//[/dontprint]');
		$reemplazos = array(
			array($regexp=>'')
		);
		foreach($reemplazos as $equivalencia_canonica)
		foreach($equivalencia_canonica as $pattern=>$replacement){
			while(true)
			{
				$pattern = '('.$pattern.')';
				if(!preg_match($pattern, $where))
					break;
				$where = preg_replace($pattern, $replacement, $where);
			}
		}
		return($where);
	}
	public static function GetThatFileName($backoffset=0){
		$bt = debug_backtrace();
		return($bt[$backoffset]['file']);
	}
	public static function GetThatPath($backoffset=0){
		$fileName = self::GetThatFileName($backoffset); 
		return(dirname($fileName));
	}
	public static function PrintFile($filename,$line='',$prev_lines=0,$post_lines=0){
		return(
			Magichelper::PrintLineNumber(self::GetFileContent(
				$filename
			),$line,$prev_lines,$post_lines)
		);
	}
	public static function PrintThisFile($line='',$prev_lines=0,$post_lines=0){
		return(
			self::PrintFile(self::GetThatFileName(1),
				$line,$prev_lines,$post_lines
			)
		);
	}
	public static function Arrobeado(){
		$x = error_reporting(0);
		error_reporting($x);
		return($x===0);
	}
	private function NavigatorInfo(){
		static $Browser_info = false;
		if($Browser_info)
			return;
		/* Get the name the browser calls itself and what version */
		$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
		self::$Browser_Name = strtok($HTTP_USER_AGENT, "/");
		self::$Browser_Version = strtok( " ");
		
		/* MSIE lies about its name */
		if(ereg( "MSIE", $HTTP_USER_AGENT))
		{
			self::$Browser_Name = "MSIE";
			self::$Browser_Version = strtok( "MSIE");
			self::$Browser_Version = strtok( " ");
			self::$Browser_Version = strtok( ";");
		}
		
		/* try to figure out what platform, windows or mac */
		self::$Browser_Platform = "unknown";
		if(ereg( "Windows",$HTTP_USER_AGENT) ||
			ereg( "WinNT",$HTTP_USER_AGENT) ||
			ereg( "Win95",$HTTP_USER_AGENT))
		{
			self::$Browser_Platform = "Windows";
		}
		
		if(ereg( "Mac", $HTTP_USER_AGENT))
		{
			self::$Browser_Platform = "Macintosh";
		}
	}
	public static function isIE7minor(){
		self::NavigatorInfo();
		return(self::$Browser_Name=="MSIE"&&self::$Browser_Version<7);
	}
	public static function isWindowsServer2003(){
		return(self::getOS()=='Windows Server 2003');
	}
	public static function getOS(){
		static $result = null;
		if($result!=null)
			return($result);
		$OSList = array ( 
			'Windows 3.11' => 'Win16', 
			'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)', 
			'Windows 98' => '(Windows 98)|(Win98)|(Windows NT 4.10.2222)', 
			'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)|(Windows NT 5.0.3700.6690)', 
			'Windows XP' => '(Windows NT 5.1)|(Windows XP)', 
			'Windows XP 64-bit Edition 2003' => '(Windows NT 5.2.3790)', 
			'Windows Server 2003' => '(Windows NT 5.2)|(Windows NT 5.2.3790)', 
			'Windows Vista' => '(Windows NT 6.0)|(Windows NT6.0.6000)', 
			'Windows 7' => '(Windows NT 7.0)', 
			'Windows Home Server' => '(Windows NT 5.2.4500)', 
			'Windows Server 2008' => '(Windows NT 6.0.6001)|(Windows NT 6.0+)', 
			'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)', 
			'Windows ME' => '(Windows ME)|(NT 4.90.3000)', 
			'Windows Millenium' => '(Windows NT 4.90)', 
			'Open BSD' => 'OpenBSD', 
			'Sun OS' => 'SunOS', 
			'Sun OS' => 'SunOS', 
			'Linux' => '(Linux)|(X11)', 
			'Mac OS' => '(Mac_PowerPC)|(Macintosh)', 
			'QNX' => 'QNX', 'BeOS' => 'BeOS', 'OS/2' => 'OS/2', 
			'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Sl urp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)' 
		); 
		foreach($OSList as $CurrOS=>$Match){ 
			if (eregi($Match, $_SERVER['HTTP_USER_AGENT'])){ 
				break;
			}
		}
		return($result=$CurrOS);
	} 
	public static function getMiliseconds($divisor=false){
		
		$milisec = microtime();
		$milisec = explode(" ",$milisec);
		$milisec = $milisec[0]*1000 + $milisec[1]*1000;
		if($divisor!=false){
			$milisec = $milisec/$divisor;
			$milisec = explode(".",$milisec);
			$milisec = $milisec[0];
		}
		return($milisec);
	} 
	public static function isAWindowsServer(){
		return($_ENV['OS']=='Windows_NT');
	}
	private static $real_check = true;
	private static function setEnabledRealCheck($enabled){
		self::$real_check = (boolean)$enabled;
	}
	public function doesReallyFileExists($file){
		if(!file_exists($file))
			return(false);
		$file = self::PathResolve($file);
		$real_file = self::PathResolve(realpath($file));
		return($file===$real_file);
		
		//esto revolvia el directorio donde estaba y obtenia los archivos y comparaba con el archivo a incluir
		//pero no verificaba que todos los directorios fueran case sensitive, solo del archivo.
		//aparte hacia a el magichelper dependiente de otra clase, lo cual no es muy conveniente
		//$ = array_pop(explode('/',$file));
		if(self::$real_check==false){//para que no sea infinito
			return(true);
		}
		self::setEnabledRealCheck(false);
		
		$x = new Base_FileFilterRetriever();
		$x->Start(dirname($file),0);
		$existe = false;
		$base = basename($file);
		foreach($x->getFilteredFiles() as $file_in_dir)
			if($base==basename($file_in_dir)){
				$existe = true;
				break;
			}
		self::setEnabledRealCheck(true);
		//var_dump($file);
		return($existe);
	}
}
/*function ObtenerNavegador() { \$navegadores = array( 
 'Opera' => 'Opera',
 'Mozilla Firefox'=> '(Firebird)|(Firefox)', 
 'Galeon' => 'Galeon', 
 'Safari'=>'saf', 
  'MyIE'=>'MyIE', 
  'konqueror'=>'konq', 
  'netpositive'=>'netp', 
  'K-meleon'=>'K-Meleon', 
   'Lynx' => 'Lynx', 
   'Netscape'=>'(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)', 
   'Konqueror'=>'Konqueror', 
    'Internet Explorer 7' => '(MSIE 7\.[0-9]+)', 
	'Internet Explorer 6' => '(MSIE 6\.[0-9]+)', 
	'Internet Explorer 5' => '(MSIE 5\.[0-9]+)', 
	 'Internet Explorer 4' => '(MSIE 4\.[0-9]+)', 
	  'amaya'=>'amaya', 
	  'AOL'=>'AOL', 
	  'elinks'=>'elinks', 
	  'links'=>'links', 
	  'w3m'=>'w3m', 
	   'dillo'=>'dillo', 
	    ' Googlebot'=>'google' 
		 ); 
		  foreach($navegadores as \$navegador=>\$pattern){ 
		  	if (eregi(\$pattern, \$_SERVER['HTTP_USER_AGENT'])) 
			   return \$navegador; 
			    } 
				 return 'Desconocido'; 
				  } */
?>