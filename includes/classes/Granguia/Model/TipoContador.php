<?
class Granguia_Model_TipoContador extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'nombre',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	private static $_tipos_by_name = array();
	public static function getTipoContadorByName($nombre){
		if(!isset(self::$_tipos_by_name[$nombre])){
			$tipo = new self();
			$tipo->setNombre($nombre);
			if(!$tipo->load())
				$tipo = null;
			self::$_tipos_by_name[$nombre] = $tipo;
		}
		return self::$_tipos_by_name[$nombre];
	}
	public function getDbTableName() 
	{
		return 'gg_tipo_contador';
	}
}
?>