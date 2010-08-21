<?
class Db_Helper{
	public static function equal ( $field, $value = null, $equal=true ){
		return(new Db_Compare_Equal($field, $value, $equal));
	}
	public static function distinct ( $field, $value = null, $distinct=true ){
		return(new Db_Compare_Equal($field, $value, $distinct?false:true));
	}
	public static function between ( $field, $min=null, $max=null, $use_equal=true){
		return(new Db_Compare_Between($field, $min, $max, $use_equal));
	}
	public static function like ( $field, $preval=null, $value=null, $postval=null, $reverse=false){
		return(new Db_Compare_Like($field,$preval , $value, $postval, $reverse));
	}
	public static function null ( $field, $isnull=true){
		return(new Db_Compare_Null($field,$isnull));
	}
	public static function in ( $field, $in=true, $in_values = null ){
		return(new Db_Compare_In( $field, $in, $in_values ));
	}
	public static function custom ( $string, $values = null ){
		$args = func_get_args();
		return(new Db_Compare_Custom( $string, $values = array_slice($args, 1) ));
	}
	//... so on
}
?>