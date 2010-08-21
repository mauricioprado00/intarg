<?
class Core_String_Helper extends Core_Singleton{
	public function concat(){
		$ret = '';
		foreach($args=func_get_args() as $arg){
			if(is_string($arg))
				$ret .= $arg;
		}
		return $ret;
	}
	public function prepend($string, $val){
		return $val.$string;
	}
	private static $_memorize;
	public function memorize($string){
		self::$_memorize = $string;
		return '';
	}
	public function remember($nada, $format,$cantidad){
		$args = array();
		while($cantidad--)$args[] = self::$_memorize;
		return call_user_func_array('sprintf', array_merge(array($format), $args));
	}
/* ejemplo de uso
			<action method="addAutofilterFieldOutput">
				<fieldname>orden</fieldname>
				<filter>Core_String_Helper::memorize</filter>
			</action>
			<action method="addAutofilterFieldOutput">
				<fieldname>orden</fieldname>
				<filter>Core_String_Helper::remember</filter>
				<param><![CDATA[<a href="#menu/listar/kradkk/%s">cambiar: %s</a>]]></param>
				<param>2</param>
			</action>
*/
	public function swap($idx1, $idx2){
		$args = array_slice($args = func_get_args(), 2);
		$val1 = $args[$idx1+0];
		$args[$idx1+0] = $args[$idx2+0];
		$args[$idx2+0] = $val1;
		return $args;
	}
	public function sinAcentos($string){
		$sinenes = array(
			'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U',
			'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u','À'=>'A','È'=>'E','Ì'=>'I','Ò'=>'O','Ù'=>'U',
			'ã'=>'a','õ'=>'o','ñ'=>'n','Ã'=>'A','Õ'=>'O','Ñ'=>'N',
		);
		return strtr($string, $sinenes);
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}
?>