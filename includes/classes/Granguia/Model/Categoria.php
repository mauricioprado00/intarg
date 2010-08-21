<?
class Granguia_Model_Categoria extends Granguia_Model_ExtensionNodo{
	public function init(){
		parent::init();
		$datafields = array(
//			"id",
//			"parent_id",
//			"title",
//			"description",
			'id',
			'id_categoria_padre',
			'nombre',
			'orden',
			'es_default',
			'componente_url',
			'id_nodo',
			'id_banner_c',
			'id_banner_d',
			'id_banner_e',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'gg_categoria';
	}
	public function Posicionar($orden){
		$id_categoria_padre = $this->getIdCategoriaPadre();
		$search = new self();
		$search->setWhere(Db_Helper::equal('id_categoria_padre'), Db_Helper::between('orden', $orden), Db_Helper::equal('id', null, false));
		$search->setId($this->getId());
		$search->setIdCategoriaPadre($id_categoria_padre);
		$brothers = $search->search('orden', 'ASC', null, 0, get_class($this));
		//var_dump($search->searchGetSql());
		if(!$brothers)
			return null;
		$mas_orden = $orden;
		foreach($brothers as $brother){
			$n = ++$mas_orden;
			//var_dump($n);
			$brother->setOrden($n);
			$brother->replace();
		}
		//var_dump($orden);
		$this->setOrden($orden);
		$this->replace();
	}
	public static function getTree($root_name=null, $as_objects=true){
		$categoria = new self();
		$categoria->setWhere(Db_Helper::equal('id_categoria_padre',0));
		$categorias = $categoria->search('orden', 'ASC', null, 0, $as_objects);
		$root_item = new Core_Object;
		$root_item->setId(0);
		if(!isset($root_name))
			$root_name = 'root';
		$root_item->setNombre($root_name);
		//$root_item->setDescription('');
		
		$ret = array(
			'item'=>$root_item,
			'childs'=>self::makeTree($categorias, $as_objects)
		);
		return(array($ret));
	}
	private static function makeTree($objs, $as_objects=true){
		foreach($objs as &$obj){
			if(is_a($obj, __CLASS__))
				$childs = $obj->getChilds(null, $as_objects);//para que los guarde en su cache interna
			else
				$childs = self::getChilds($obj->getId(), $as_objects);
			$obj = array(
				'item'=>$obj,
				//'childs'=>self::makeTree($childs),
			);
			if($childs){
				$obj['childs'] = self::makeTree($childs, $as_objects);
			}
		}unset($obj);
		return($objs);
	}
	private $_childs = null;
	private function getChilds($id=null, $as_objects=true){
		if(!isset($this) || !isset($this->_childs)){
			if($id===null){
				if(isset($this)){
					$id = $this->getId();
				}
				else{
//					echo "\n no puedo buscar hijos($id)\n";
//					var_dump(array_keys($this->getData()));
					return(null);
				}
			}
//			echo "\nbusco hijos($id)\n";
			$categoria = new self();
			$categoria->setWhere(Db_Helper::equal('id_categoria_padre', $id));
			$childs = $categoria->search('orden', 'ASC', null, 0, $as_objects);
			if(isset($this))
				$this->_childs = $childs;
		}
		else $childs = $this->_childs;
		return($childs);
	}
	private function canonicalComponenteUrl($componente_url){
		$componente_url = str_replace(' ', '-', $componente_url);
		$componente_url = str_replace('_', '-', $componente_url);
		$componente_url = preg_replace('/[^A-Za-z0-9-]/', '', $componente_url);
		return preg_replace('/\/+/','/', trim($componente_url,'/'));
	}
	protected function beforeChange(&$data){
		parent::beforeChange($data);
		if(!isset($data['componente_url']) || trim($data['componente_url'])==''){
			$data['componente_url'] = $data['nombre'];
		}
		$data['componente_url'] = self::canonicalComponenteUrl($data['componente_url']);
	}
	public function resetOtherDefault(){
		if(!$this->getEsDefault())
			return;
		$this->getDb()->open();
		$sql = strtr(
			'UPDATE %tabla SET es_default = 0 WHERE id!=%id;', array(
				'%id'=>$this->getDb()->valueToString($this->getId()),
				'%tabla'=>$this->getDb()->nameToString($this->getDbTableName())
			)
		);
		$this->getDb()->exec($sql);
		$this->getDb()->close();
	}
	protected function afterChange($data, $resultado){
		parent::afterChange($data, $resultado);
		if(!$resultado)
			return;
		$this->resetOtherDefault();
	}
	public static function getCategoriaFromComponenteUrl($componente_url){
		$categoria = new self();
		$categoria->setComponenteUrl('/'.self::canonicalComponenteUrl($componente_url));
		$categoria->setWhere(Db_Helper::equal('componente_url'));
		$categorias = $categoria->search(null,'ASC',null,0,__CLASS__);//si encuentra el que matchea perfecto mejor(nodo específico), 
		if(!$nodos){//sino le damos con likes (nodo genérico)
			$wheres = array();
//			$wheres[] = '(';
//			$wheres[] = Db_Helper::like('path_url', null, null, null, true);
//			$wheres[] = 'OR';
			$wheres[] = Db_Helper::custom('{#componente_url} like concat({%s},{@componente_url})', '%/');
//			$wheres[] = ') AND ';
//			$wheres[] = Db_Helper::equal('es_activa', 1);
	
			//$nodo->setWhere(Db_Helper::like('path_url',null,null,null,true));
			$categoria->setWhereByArray($wheres);
			//echo $nodo->searchGetSql();die();
			$categorias = $categoria->search('componente_url','DESC',null,0,__CLASS__);
			if(!$categorias){
				return null;
				//die("no encuentro nada en la linea: ".__LINE__);
			}
		}
		$categoria = array_shift($categorias);
		return $categoria;
	}
	public static function getCategoriaDefault(){
		$categoria = new self();
		$categoria->setEsDefault(1);
		if(!$categoria->load(null, true))
			return false;
		return $categoria;
	}
	public static function TipoNodo(){
		static $tipo_nodo = null;
		if(!isset($tipo_nodo)){
			$tipo_nodo = new Granguia_Model_TipoNodo();
			$tipo_nodo->setNombre('Categoria');
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
	protected function translateDuplicatedKeyNumber($number, $valor){
		if($number == 2){
			return 'Cambie el valor \''.$valor.'\' del campo "Componente Url", el mismo ya se encuentra en uso.';
		}
	}
	private $_terminos_buscables;
	public function getTerminosBuscables($join=null){
		if(!isset($this->_terminos_buscables)){
			$terminos_buscables = array();
			$childs = $this->getChilds(null, __CLASS__);
			$terminos_buscables = $this->getNodoTagsArray();
			if($childs)
				foreach($childs as $subcategoria){
					//var_dump($subcategoria->getTerminosBuscables());
					$terminos_buscables = array_merge($terminos_buscables, $subcategoria->getTerminosBuscables());
				}
			$this->_terminos_buscables = array_unique($terminos_buscables);
		}
		if(isset($join))
			return implode($join, $this->_terminos_buscables);
		return $this->_terminos_buscables;
	}
	public function getLinkUrl($barrio=null){
		$link_url = array($this->getComponenteUrl());
		if(isset($barrio)&&is_object($barrio)&&$barrio instanceof Granguia_Model_Barrio){
			if($nodo = $barrio->getNodo()){
				array_unshift($link_url, $nodo->getPathUrl());
			}
		}
		return implode('/', $link_url);
	}
	private $_categoria_padre = null;
	public function getCategoriaPadre(){
		if(!$this->hasIdCategoriaPadre()||!($id_categoria_padre = $this->getIdCategoriaPadre()))
			return null;
		if(!isset($this->_categoria_padre)||$this->_categoria_padre->getId()!=$id_categoria_padre){
			$categoria = new self();
			$categoria->setId($id_categoria_padre);
			if(!$categoria->load())
				$categoria = null;
			$this->_categoria_padre = $categoria;
			
		}
		return $this->_categoria_padre;
	}
	public function getDataIndex($tags, $path_url){//esta funcion la llama la clase Nodo antes de guardar, para generar el campo fulltext de busqueda
		$ret = parent::getDataIndex($tags, $path_url);
		$dob = new Core_Object($this->getData());//de esta manera obtengo los valores internod del objeto evitando los filtros, pero sin modificar la configuración de filtros
		$parents_dataindex = '';
		if($categoria_padre = $this->getCategoriaPadre()){
			$parents_dataindex .= ' ' . $categoria_padre->getDataIndex('','');
		}
		return 
				$dob->getNombre() . ' ' . 
				$dob->getComponenteUrl() . ' ' . 
				$ret . ' ' .
				$parents_dataindex;
	}
	public function getMetaTitle($default){
		return $this->getNombre() . ' - ' . $default;
	}
}
?>