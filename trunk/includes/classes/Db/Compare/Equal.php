<?
class Db_Compare_Equal extends Db_Compare_Abstract{
	function __construct ( $field, $value = null, $equal=true){
		$this->setField($field);
		$this->setValue($value);
		$this->setEqual($equal?true:false);
	}
	function toString(){return(false);}
}
?>