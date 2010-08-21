<?
class Db_Compare_Like extends Db_Compare_Abstract{
	function __construct ( $field, $preval, $value = null, $postval = null, $reverse=false ){
		$this->setField($field);
		$this->setPreval($preval);
		$this->setValue($value);
		$this->setPostval($postval);
		$this->setReverse($reverse?true:false);
	}
	function toString(){return(false);}
}
?>