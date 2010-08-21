<?
class Granguia_Model_TipoNodo extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'nombre',
			'clase_controladora',
			'tabla',
			//'default_url_prefix',//este esta deprecated, esta info va en el nodo generico
			'clase_modelo',
			'id_nodo',
			'prioridad_seo',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function esBarrio(){
		return $this->hasClaseModelo()&&$this->getClaseModelo()=='Granguia_Model_Barrio';
	}
	public function esPagina(){
		return $this->hasClaseModelo()&&$this->getClaseModelo()=='Granguia_Model_Pagina';
	}
	public function esArchivo(){
		return $this->hasClaseModelo()&&$this->getClaseModelo()=='Granguia_Model_Archivo';
	}
	public function esAnuncio(){
		return $this->hasClaseModelo()&&$this->getClaseModelo()=='Granguia_Model_Anuncio';
	}
	public function esCategoria(){
		return $this->hasClaseModelo()&&$this->getClaseModelo()=='Granguia_Model_Categoria';
	}
	private static $_tipos_nodos = array();
	public static function getTipoNodoByName($nombre){
		if(in_array($nombre, array_keys(self::$_tipos_nodos)))
			return self::$_tipos_nodos[$nombre];
		$tipo_nodo = new Granguia_Model_TipoNodo();
		$tipo_nodo->setNombre($nombre);
		if(!$tipo_nodo->load())
			$tipo_nodo = null;
		return self::$_tipos_nodos[$nombre] = $tipo_nodo;
	}
	private static $_nodos_genericos = array();
	public static function getTipoNodoGenericoByName($nombre){
		if(!in_array($nombre, array_keys(self::$_nodos_genericos))){
			if($tipo_nodo = Granguia_Model_TipoNodo::getTipoNodoByName($nombre)){
				if($nodo_generico = $tipo_nodo->getNodoGenerico()){
					self::$_nodos_genericos[$nombre] = $nodo_generico;
				}
			}
		}
		return isset(self::$_nodos_genericos[$nombre])?self::$_nodos_genericos[$nombre]:null;
	}
	public static function getTipoNodoGenericoUrlByName($nombre){
		if(!($nodo = self::getTipoNodoGenericoByName($nombre)))
			return null;
		return $nodo->getPathUrl();
	}
	private function getNodoGenerico(){
		if(!c($nodo = new Granguia_Model_Nodo)->setId($this->getIdNodo())->load())
			return null;
		return $nodo;
	}
	private function deprecated_getNodoGenerico(){
		$nodo = new Granguia_Model_Nodo();
		$wheres = array();
		$wheres[] = Db_Helper::equal('id_tipo_nodo', $this->getId());
		if($this->getTabla()){
			$wheres[] = Db_Helper::custom('{@id} not in (SELECT {@id_nodo} FROM {@'.$this->getTabla().'} WHERE NOT {@id_nodo} IS NULL)');
		}
		$nodo->setWhereByArray($wheres);
		$nodos = $nodo->search();
		if(!$nodos)
			return null;
		if(count($nodos)>1&&false){
			echo ('Se encontro mas de un nodo de tipo '.$this->getNombre().' sin instancia asociada');
			echo $nodo->searchGetSql()."\n";
			$bt = debug_backtrace();
			foreach($bt as $info){
				echo $info['file'].'('.$info['line'].')'."\n";
			}
			die();
		}
		return array_shift($nodos);
	}
	public function getDbTableName() 
	{
		return 'gg_tipo_nodo';
	}
}
?>