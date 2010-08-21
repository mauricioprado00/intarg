<?
class Db_Compare_Custom extends Db_Compare_Abstract{
	function __construct ( $string, $values=null ){
		$this->setString($string);
		$this->setValues($values);
	}
	function toString(){return(false);}
}
?>