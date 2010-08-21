<?
class Mysql_Db_Compare_Custom extends Db_Compare_Custom{
	function __construct(Db_Compare_Custom $compare){
		$this->setString($compare->getString());
		$this->setValues($compare->getValues());
	}
	function toString($asociative_array_field_and_values){
		if(!$this->hasString())
			return false;
		//var_dump($this->getString());
		$re_name = '/\{\@(?<campo>[a-zA-Z_0-9]+)\}/';
		$re_value = '/\{\#(?<campo>[a-zA-Z_0-9]+)\}/';
		//$re_param = '/\{\(%s)\}/';
		$string = $this->getString();
		if(preg_match_all($re_name, $string, $matches)){//usa nombres de campos en el string
			if(isset($matches['campo'])){
				//var_dump($matches['campo']);
				foreach($matches['campo'] as $campo){
					$search = '{@'.$campo.'}';
					$replace = Mysql_Db::nameToString($campo);
					$string = str_replace($search, $replace, $string);
				}
			}
		}
		if(preg_match_all($re_value, $string, $matches)){//usa nombres de campos en el string
			if(isset($matches['campo'])){
				//var_dump($matches['campo']);
				foreach($matches['campo'] as $campo){
					$valor = isset($asociative_array_field_and_values[$campo])?$asociative_array_field_and_values[$campo]:'';
					$search = '{#'.$campo.'}';
					$replace = Mysql_Db::valueToString($valor);
					$string = str_replace($search, $replace, $string);
				}
			}
		}
		$params = $this->getValues();
		$search = '{%s}';
		while(($pos = strpos($string, $search))!==false){
			if(!$params){
				return false;
			}
			$param = array_shift($params);
			$replace = Mysql_Db::valueToString($param);
			$string = substr_replace($string, $replace, $pos, strlen($search));
		}
		return ($string);
	}
}
?>