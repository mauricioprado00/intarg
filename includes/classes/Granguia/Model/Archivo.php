<?
	class Granguia_Model_Archivo extends Granguia_Model_ExtensionNodo{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'nombre',
			'path_archivo',
			'id_nodo',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public static function TipoNodo(){
		static $tipo_nodo = null;
		if(!isset($tipo_nodo)){
			$tipo_nodo = new Granguia_Model_TipoNodo();
			$tipo_nodo->setNombre('Archivo');
			$tipo_nodo->load();
		}
		return($tipo_nodo);
	}
	public static function IdTipoNodo(){
		return self::TipoNodo()->getId(); 
	}
	public static function NewNodo(){
		$nodo = new Granguia_Model_Nodo();
		$nodo->setIdTipoNodo(self::IdTipoNodo());
		return $nodo;
	}
	public function getDataIndex($tags, $path_url){//esta funcion la llama la clase Nodo antes de guardar, para generar el campo fulltext de busqueda
		$ret = parent::getDataIndex($tags, $path_url);
		$dob = new Core_Object($this->getData());//de esta manera obtengo los valores internod del objeto evitando los filtros, pero sin modificar la configuración de filtros
		return 
				$dob->getNombre() . ' ' .
				$dob->getPathArchivo() . ' ' . 
				$ret;
	}
	public function getDbTableName() 
	{
		return 'gg_archivo';
	}
}
?>