<?
class Granguia_Model_Pagina extends Granguia_Model_ExtensionNodo{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'titulo',
			'contenido_html',
			'nombre_interno',
			'id_nodo',
			'tiene_nodo',
			'titulo_interno'
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public static function TipoNodo(){
		static $tipo_nodo = null;
		if(!isset($tipo_nodo)){
			$tipo_nodo = new Granguia_Model_TipoNodo();
			$tipo_nodo->setNombre('Pagina');
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
	public function getMetaTitle($default){
		return $this->getTitulo() . ' - ' . $default;
	}
	private static $_paginas_internas = array();
	public static function getByNombreInterno($nombre_interno){
		if(isset(self::$_paginas_internas[$nombre_interno]))
			return self::$_paginas_internas[$nombre_interno];
		$pagina = new self();
		$pagina->setNombreInterno($nombre_interno);
		if($pagina->load())
			return self::$_paginas_internas[$nombre_interno] = $pagina;
		return null;
	}
	public static function getUrlByNombreInterno($nombre_interno){
		return c(self::getByNombreInterno($nombre_interno))->getNodo()->getPathUrl();
	}
	public function getDbTableName() 
	{
		return 'gg_pagina';
	}
}
?>