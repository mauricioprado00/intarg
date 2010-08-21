<?
class Mysql_Db_Compare_Null extends Db_Compare_Null{
	function __construct(Db_Compare_Null $compare){
		$this->setField($compare->getField());
		$this->setNull($compare->getNull());
	}
	function toString($asociative_array_field_and_values){
		if(!$this->hasField())
			return(false);
		return(Mysql_Db::nameToString($this->getWhere()->getFieldName($this->getField())).' IS '.($this->getNull()?'':'NOT ').'NULL ');
	}
}
?>