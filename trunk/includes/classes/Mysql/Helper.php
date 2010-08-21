<?
class Mysql_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function nameToString($name){
		return preg_replace('/[A-Za-z0-9_]+/','`$0`', $name);
		$name = explode('.', $name);
		foreach($name as $idx=>$name_part){
			$name[$idx] = '`'.$name_part.'`';//$this->getDb()->nameToString($column_part);
		}
		$name = implode('.', $name);
		return($name);
	}
	public static function valueToString($value){
		//aca agregar soporte para DateTime object convertirlo a formato db
		return('\''.mysql_real_escape_string($value).'\'');
	}
	public static function filterDateInput($date){
		return implode('-', array_reverse(explode('/',trim($date))));
	}
	public static function filterDateOutput($date){
		return implode('/', array_reverse(explode('-',trim($date))));
	}
	public static function filterTimestampInput($timestamp){
		return date('Y-m-d H:i:s', $timestamp);
	}
	public static function filterTimestampOutput($timestamp){
		return strtotime($timestamp);
	}
}
?>