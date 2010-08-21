<?

class OO_NamingHelper{
	public static function lcfirst($string){
		$string[0] = strtolower($string[0]);
		return($string);
	}
	public static function UnderScoreToCamelCase($string){
		 return(
		 	self::lcfirst(implode('', explode(' ', ucwords(implode(' ', explode('_', $string))))))
		 );
	}
}