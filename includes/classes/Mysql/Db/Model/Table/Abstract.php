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
			case 1451:{
				return $this->translateForeignKeyFails($error);
				break;
			}
		}
		return $error->getCode().'- '.$error->getDescription();
	}
	protected function translateForeignKeyFails($error){
		$re = '/Table \'(?P<nombre_tabla>[^\']+)\' doesn\'t exist/';
		$re = '/.*a foreign key constraint fails [(][`](?P<nombre_tabla_destino>.*?)[`], CONSTRAINT [`](?P<nombre_de_la_fk>.*?)[`] FOREIGN KEY (?P<campos_referenciantes>[(].*?[)]) REFERENCES (?P<tabla_referenciada>[`].*?[`]) [(](?P<campos_referenciados>.*?)[)]/';
//			  'w: a foreign key constraint fails  (  `  app_inta/inta_usuario        ` , CONSTRAINT  `  fk_usuario_agencia      `  FOREIGN KEY (`id_agencia`) REFERENCES `inta_agencia` (`id`))';
		$return = 'No de puede completar la operación, existen entidades asociadas';
//		echo Core_Helper::DebugVars($error->getDescription());
		if(preg_match($re, $error->getDescription(), $matches)){
			//$return = $this->translateInexistentTableName($matches['nombre_tabla']);
			//echo Core_Helper::DebugVars($matches);
			$return = 'No se puede completar la operacion sobre la tabla '.$matches['tabla_referenciada'].' existen entidades asociadas en '.$matches['nombre_tabla_destino'];
		}
		return $return;
	}
	protected function translateInexistentTable($error){
		$re = '/Table \'(?P<nombre_tabla>[^\']+)\' doesn\'t exist/';
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
		$re = '/Duplicate entry \'(?P<valor>[^\']+)\' for key (?P<nro_campo>[0-9]+)/';
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