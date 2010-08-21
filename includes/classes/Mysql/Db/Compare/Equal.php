<?
class Mysql_Db_Compare_Equal extends Db_Compare_Equal{
	function __construct(Db_Compare_Equal $compare){
		$this->setField($compare->getField());
		$this->setValue($compare->getValue());
		$this->setEqual($compare->getEqual());
	}
	function toString($asociative_array_field_and_values){
		if(!$this->hasField())
			return(false);
		if(!$this->hasValue()){
			if($asociative_array_field_and_values[$this->getField()]!==null){
				$value = $asociative_array_field_and_values[$this->getField()];
			}
			else{
				return($this->getField().' IS'.($this->getEqual()?'':' NOT').' NULL');
			}
		}
		else $value = $this->getValue();
		$comparador = $this->getEqual()?' = ':' != ';
		return(Mysql_Db::nameToString($this->getWhere()->getFieldName($this->getField())).$comparador.Mysql_Db::valueToString($value).'');
	}
}
?>