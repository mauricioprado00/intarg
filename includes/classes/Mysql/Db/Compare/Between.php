<?
class Mysql_Db_Compare_Between extends Db_Compare_Between{
	function __construct(Db_Compare_Between $compare){
		$this->setField($compare->getField());
		$this->setMax($compare->getMax());
		$this->setMin($compare->getMin());
		$this->setUseEqual($compare->getUseEqual());
	}
	function toString($asociative_array_field_and_values){
		if(!$this->hasField() || !($this->hasMin()||$this->hasMax()))
			return(false);
		
		if(!$this->hasMax()){
			return(
				Mysql_Db::nameToString($this->getWhere()->getFieldName($this->getField())).
				($this->getUseEqual()?'>=':'>').(Mysql_Db::valueToString($this->getMin()))
			);
		}
		elseif(!$this->hasMin()){
			return(
				Mysql_Db::nameToString($this->getWhere()->getFieldName($this->getField())).
				($this->getUseEqual()?'<=':'<').(Mysql_Db::valueToString($this->getMax()))
			);
		}
		return(
			Mysql_Db::nameToString($this->getWhere()->getFieldName($this->getField())).' BETWEEN '.Mysql_Db::valueToString($this->getMin()).
			' AND '.Mysql_Db::valueToString($this->getMax()));
	}
}
?>