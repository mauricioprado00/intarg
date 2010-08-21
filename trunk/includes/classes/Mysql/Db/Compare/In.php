<?
class Mysql_Db_Compare_In extends Db_Compare_In{
	function __construct(Db_Compare_In $compare){
		$this->setField($compare->getField());
		$this->setIn($compare->getIn());
		$this->setInValues($compare->getInValues());
	}
	function toString($asociative_array_field_and_values){
		if(!$this->hasField())
			return(false);
		if(!$this->hasInValues()){
			if($asociative_array_field_and_values[$this->getField()]){
				$in_values = $asociative_array_field_and_values[$this->getField()];
			}
			else{
				return($this->getField().' IS'.($this->getIn()?'':' NOT').' NULL');
			}
		}
		else $in_values = $this->getInValues();
		$in_values = is_array($in_values)?$in_values:array($in_values);
		foreach($in_values as $idx=>$value)
			$in_values[$idx] = Mysql_Db::valueToString($value);
		$in_values = implode(', ', $in_values);
		return(Mysql_Db::nameToString($this->getWhere()->getFieldName($this->getField())).($this->getIn()?'':' NOT').' IN ('.$in_values.')');
	}
}
?>