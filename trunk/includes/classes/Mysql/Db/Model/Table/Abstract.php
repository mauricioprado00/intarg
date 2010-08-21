<?
abstract class Mysql_Db_Model_Table_Abstract extends Db_Model_Table_Abstract{
	protected function translateError($error){
		switch($error->getCode()){
			case 1062:{
				return $this->translateDuplicatedKey($error);
				break;
			}
			case 1146:{
				return $this->translateInexistentTable($error);
				break;
			}
		}
		return $error->getCode().'- '.$error->getDescription();
	}
	protected function translateInexistentTable($error){
		$re = '/Table \'(?<nombre_tabla>[^\']+)\' doesn\'t exist/';
		$return = 'Tabla inexistente';
		if(preg_match($re, $error->getDescription(), $matches)){
			$return = $this->translateInexistentTableName($matches['nombre_tabla']);
			if(!$return)
				$return = 'La tabla '.$this->getDb()->nameToString($matches['nombre_tabla']).' no existe';
		}
		return $return;
	}
	protected function translateInexistentTableName($tablename){
		return '';
	}
	
	protected function translateDuplicatedKey($error){
		$re = '/Duplicate entry \'(?<valor>[^\']+)\' for key (?<nro_campo>[0-9]+)/';
		$return = 'campo duplicado';
		if(preg_match($re, $error->getDescription(), $matches)){
			$return = $this->translateDuplicatedKeyNumber($matches['nro_campo'], $matches['valor']);
			if(!$return)
				$return = 'El campo nro Nº'.$matches['nro_campo'].' con el valor \''.$matches['valor'].'\' esta duplicado';
		}
		return $return;
	}
	protected function translateDuplicatedKeyNumber($number,$valor){
		return '';
	}
}
?>