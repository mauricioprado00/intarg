<?
class Db_Compare_In extends Db_Compare_Abstract{
	function __construct ( $field, $in=true, $in_values = null ){
		$this->setField($field);
		$this->setIn($in);
		$this->setInValues($in_values);
	}
	function toString(){return(false);}
}
?>