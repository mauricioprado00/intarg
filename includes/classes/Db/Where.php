<?
class Db_Where{
	private $where;
	private $db;
	private $logical_operator;
	function __construct($db){
		$this->db = $db;
		$this->logical_operator = "AND";
	}
	protected function getDb(){
		return($this->db);
	}
	public function setLogicalOperator($op){
		$this->logical_operator = in_array(trim(strtolower($op)), array('or','and'))?$op:'';
		return($this);
	}
	function set(){
		$args = func_get_args();
		$this->where = array();
		foreach($args as $idx=>$arg){
			//var_dump($arg,'is_string:'.is_string($arg));
			if(is_string($arg))
				$this->where[] = $arg;
			if(is_a($arg,'Db_Compare_Abstract')){
				$this->where[] = $this->db->getCompare($arg)->setWhere($this);
			}
		}
		return($this);
	}
	private $attr_to_field_translation_table = null;
	public function setArrToFieldTranslationTable($attr_to_field_translation_table){
		$this->attr_to_field_translation_table = $attr_to_field_translation_table;
		return($this);
	}
	function toString($asociative_array_field_and_values, $attr_to_field_translation_table=null){
		$this->attr_to_field_translation_table = $attr_to_field_translation_table;
		$assemble_where = array();
		$last_was_Db_Compare_Abstract = null;
		if($this->where)
			foreach($this->where as $where){
				if(is_string($where)){
					$assemble_where[] = $where;
					$last_was_Db_Compare_Abstract = false;
				}
				else if(is_a($where, 'Db_Compare_Abstract')){
					if($this->logical_operator&&$last_was_Db_Compare_Abstract===true)
						$assemble_where[] = $this->logical_operator;
					$assemble_where[] = $where->toString($asociative_array_field_and_values);
					$last_was_Db_Compare_Abstract = true;
				}
			}
		return(implode(' ', $assemble_where));
	}
	public function getFieldName($attr){
		if($this->attr_to_field_translation_table===null||!isset($this->attr_to_field_translation_table[$attr]))
			return($attr);
		return($this->attr_to_field_translation_table[$attr]);
	}
}
?>