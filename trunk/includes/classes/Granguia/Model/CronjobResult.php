<?
class Granguia_Model_CronjobResult extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'name',
			'inicio',
			'fin',
			'resultado',
			'salida',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
		$this->addAutofilterFieldInput('inicio', array('Mysql_Helper', 'filterTimestampInput'));
		$this->addAutofilterFieldOutput('inicio', array('Mysql_Helper', 'filterTimestampOutput'));
		$this->addAutofilterFieldInput('fin', array('Mysql_Helper', 'filterTimestampInput'));
		$this->addAutofilterFieldOutput('fin',array('Mysql_Helper', 'filterTimestampOutput'));
	}
	public static function Registrar($name, $resultado=null, $salida=null){
		$res = new self();
		$res
			->setName($name)
			->setInicio(time())
//			->setFin($fin)
			->setResultado($resultado)
			->setSalida($salida)
		;
		$r = $res->insert()?true:false;
		return $r;
	}
	public static function Actualizar($name, $resultado, $salida){
		$cronjob = new self();
		$cronjob->setName($name);
		$cronjob->setWhere(Db_Helper::equal('name'));
		$cronjobs = $cronjob->search('id', 'DESC', 1);
		if(!$cronjobs){
			return false;
		}
		$cronjob = new self();
		$cronjob
			->setData($cronjobs[0]->getData())
			->setResultado($resultado)
			->setSalida($salida)
			->setFin(time())
		;
		return $cronjob
			->update()?true:false;
	}
	public static function EliminarViejos($name, $cantidad_max){
		$cantidad_max = abs($cantidad_max);
		if(!$cantidad_max)// si no se quiere nada hay que desactivar el logeo no borrar siempre.
			return false;

		$cronjob = new self();
		$cronjob->setName($name);
		$wheres = array(Db_Helper::equal('name'));
		$cronjob->setWhereByArray($wheres);
		if(!($cronjobs = $cronjob->search('id', 'desc', $cantidad_max))){
			return false;
		}
		$c = new Core_Collection($cronjobs);
		$ids = $c->VerticalSliceSingleArray('id');
		
		
		$cronjob->getDb()->open();
		$tabla = $cronjob->getDb()->nameToString($cronjob->getDbTableName());
		$name = $cronjob->getDb()->valueToString($cronjob->getName());
		$in_list = array();
		foreach($ids as $id){
			$in_list[] = $cronjob->getDb()->valueToString($id);
		}
		$in_list = implode(', ', $in_list);
		$sql = 'DELETE FROM '.$tabla.' WHERE name='.$name.' AND id not in ('.$in_list.');';
		$res = $cronjob->getDb()->exec($sql);
		$cronjob->getDb()->close();
		return $res;
	}
	public function getDbTableName() 
	{
		return 'gg_cronjobs_results';
	}
}
?>