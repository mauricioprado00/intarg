<?
class Mysql_Db_Compare_Like extends Db_Compare_Like{
	function __construct(Db_Compare_Like $compare){
		$this->setField($compare->getField());
		$this->setValue($compare->getValue());
		$this->setPreval($compare->getPreval());
		$this->setPostval($compare->getPostval());
		$this->setReverse($compare->getReverse());
	}
	function toString($asociative_array_field_and_values){
		if(!$this->hasField())
			return(false);
		$value = array();
		if($this->hasPreval())
			$value[] = $this->getPreval();
		if(!$this->hasValue()){
			if(isset($asociative_array_field_and_values[$this->getField()])){
				$value[] = $asociative_array_field_and_values[$this->getField()];
			}
//			else{
//				return($this->getField().' IS NULL');
//			}
		}
		else $value[] = $this->getValue();
		if($this->hasPostval())
			$value[] = $this->getPostval();
		$value = implode('',$value);
		$operators = array(
			Mysql_Db::nameToString($this->getWhere()->getFieldName($this->getField())),
			Mysql_Db::valueToString($value)
		);
		if($this->getReverse())
			$operators = array_reverse($operators);
		return(implode(' LIKE ', $operators));
	}
}
?>