<?
class Db_Compare_Null extends Db_Compare_Abstract{
	function __construct ( $field, $null = true ){
		$this->setField($field);
		$this->setNull($null?true:false);
	}
	function toString(){return(false);}
}
?>