<?
class Db_Compare_Between extends Db_Compare_Abstract{
	function __construct ( $field, $min = null, $max = null, $use_equal=true){
		$this->setField($field);
		$this->setMin($min);
		$this->setMax($max);
		$this->setUseEqual($use_equal?true:false);
	}
	function toString(){return(false);}
}
?>