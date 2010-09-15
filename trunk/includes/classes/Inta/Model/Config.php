<?
//echo '<div class="contenedor_main">ok</div>';
//die("ok");
class Inta_Model_Config extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"nombre",
			"valor",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'inta_configuracion';
	}
	public static function findConfig($nombre){
		$config = new self();
		$config->setNombre($nombre);
		$config->setWhere(Db_Helper::equal('nombre'));
		$config = $config->search(null, null, null, null, __CLASS__);
		if(!$config || !is_array($config) || !count($config))
			return(null);
		$config = array_shift($config);
		return($config);
	}
	public static function findConfigValue($nombre, $default=null){
		$config = self::findConfig($nombre);
		if(!$config){
			if(isset($default)){
				$config = new self();
				$config->setNombre($nombre)
					->setValor($default)
					->replace()
				;
				return $default;
			}
			return(null);
		}
		return($config->getValor());
	}
	public function filterValue(){
		$configured = $this->getValor();
		$valores = array();
		foreach(explode("\n", $configured) as $valor){
			$valor = trim($valor);
			if($valor==='')
				continue;
			if($valor[0]=='#')
				continue;
			$valores[] = $valor;
		}
		if(count($valores))
			return($valores);
		return null;
	}
	public static function findConfigTextFiltered($nombre, $default=null){
		$configured = self::findConfig($nombre, $default);
		if(!$configured)
			return null;
		$arr = $configured->getData();
		$config = new self();
		$config->loadFromArray($arr);
		return $config->filterValue();
	}
}
?>