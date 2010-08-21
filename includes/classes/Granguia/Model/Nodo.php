<?
class Granguia_Model_Nodo extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"path_url",
			"tags",
			"id_tipo_nodo",
			"es_home",
			"es_activa",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDefaultPathUrl(){
		if($tipo_nodo = $this->getTipoNodo()){
			//if($prefijo = $tipo_nodo->getDefaultUrlPrefix()){
			if($prefijo = $tipo_nodo->getTipoNodoGenericoUrlByName($tipo_nodo->getNombre())){
				return $prefijo.'/'.$this->getId();
			}
		}
		return null;
	}
	public function validateUrl(){
		$in_conflict = $this->pathInConflict();
		if($in_conflict){
			$nodo = $this->getNodeInConflict();
			if($nodo){
				$tipo_nodo = $nodo->getTipoNodo();
				if($tipo_nodo){
					$url = Core_App::getUrlModel()->getUrl($nodo->getPathUrl());
					$nombre = $tipo_nodo->getNombre();
					return sprintf(utf8_encode('La <b>Direccion</b> genera conflictos con %s (<b>%s</b>) .'), $url, $nombre);
				}
			}
			return utf8_encode('La <b>Direccion</b> genera conflictos, modifiquela.');
		}
		return null;
	}
	public function esNodoGenerico(){
		if(!($tipo_nodo = $this->getTipoNodo()))
			return null;
		if($tipo_nodo->getIdNodo()!=$this->getId())
			return false;
		return true;
	}
	public function pathInConflict(){
		$path = $this->getPathUrl();
		$path = self::canonicalPathUrl($path);
		//var_dump($this->getDefaultPathUrl(), $path);
		if($path==$this->getDefaultPathUrl())
			return false;
		$r = new self();
		$r->setPathUrl($path);
		$r->setIdTipoNodo($this->getIdTipoNodo());
		$es_nodo_generico = $this->esNodoGenerico();
		$wheres = array();
		$wheres[] = '(';
		$wheres[] = Db_Helper::like('path_url', null, null, null, false);
		$wheres[] = 'OR';
		$wheres[] = Db_Helper::like('path_url', null, null, null, true);
		$wheres[] = 'OR';
		if($es_nodo_generico){
			$wheres[] = '(';
			$wheres[] = Db_Helper::custom('{@path_url} like concat({#path_url},{%s})', '/%');
			$wheres[] = Db_Helper::equal('id_tipo_nodo', null, false);
			$wheres[] = ')';
		}
		else
			$wheres[] = Db_Helper::custom('{@path_url} like concat({#path_url},{%s})', '/%');
		$wheres[] = 'OR';
		$wheres[] = Db_Helper::custom('{#path_url} like concat({@path_url},{%s})', '/%');
		$wheres[] = ') AND ';
		$wheres[] = Db_Helper::equal('es_activa', 1);
		if($this->hasId()&&$id=$this->getId()){
			$r->setId($id);
			$wheres[] = Db_Helper::equal('id', null, false);
		}
		$r->setWhereByArray($wheres);
		//echo $r->searchGetSql();
		//echo $r->searchCount();
		if($r->searchCount()>0)
			return true;
		return false;
	}
	public function getNodeInConflict(){
		$path = $this->getPathUrl();
		$path = self::canonicalPathUrl($path);
		//var_dump($this->getDefaultPathUrl(), $path);
		if($path==$this->getDefaultPathUrl())
			return null;
		$r = new self();
		$r->setPathUrl($path);
		$wheres = array();
		$wheres[] = '(';
		$wheres[] = Db_Helper::like('path_url', null, null, null, false);
		$wheres[] = 'OR';
		$wheres[] = Db_Helper::like('path_url', null, null, null, true);
		$wheres[] = 'OR';
		$wheres[] = Db_Helper::custom('{@path_url} like concat({#path_url},{%s})', '/%');
		$wheres[] = 'OR';
		$wheres[] = Db_Helper::custom('{#path_url} like concat({@path_url},{%s})', '/%');
		$wheres[] = ') AND ';
		$wheres[] = Db_Helper::equal('es_activa', 1);
		if($this->hasId()&&$id=$this->getId()){
			$r->setId($id);
			$wheres[] = Db_Helper::equal('id', null, false);
		}
		$r->setWhereByArray($wheres);
		//echo $r->searchGetSql();
		if($r->searchCount()>0){
			$nodes = $r->search(null, 'ASC', 1, 0, __CLASS__);
			if(isset($nodes)&&is_array($nodes)&&count($nodes)){
				return array_shift($nodes);
			}
		}
		return null;
	}
	private $_tipo_nodo = null;
	public function getTipoNodo(){
		if(!$this->hasIdTipoNodo())
			return null;
		if(!isset($this->_tipo_nodo)||$this->_tipo_nodo->getId()!=$this->getIdTipoNodo()){
			$id_tipo_nodo = $this->getIdTipoNodo();
			$tipo_nodo = new Granguia_Model_TipoNodo();
			$tipo_nodo->setId($id_tipo_nodo);
			if(!$tipo_nodo->load())
				$tipo_nodo = null;
			$this->_tipo_nodo = $tipo_nodo;
		}
		return $this->_tipo_nodo;
	}
	private $_type_instance = null;
	public function getTypeInstance(){
		if(!$this->hasId()||!$this->getId())
			return null;
		if(!isset($this->_type_instance)||$this->_type_instance->getIdNodo()!=$this->getId()){
			$this->_type_instance = null;
			$tipo_nodo = $this->getTipoNodo();
			if($tipo_nodo){
				if($tipo_nodo->hasClaseModelo() && ($clase_modelo = $tipo_nodo->getClaseModelo())){
					$instancia = new $clase_modelo();
					$instancia->setIdNodo($this->getId());
					if($instancia->load()){
						$instancia->setNodo($this);
						$this->_type_instance = $instancia;
					}
				}
			}
		}
		return $this->_type_instance;
	}
	public function setTypeInstance($instancia, $force=false){
		if((!$this->hasId()||!$this->getId()) && !$force)
			return null;
		if(isset($instancia)&&($instancia instanceof Granguia_Model_ExtensionNodo)){
			if($instancia->getIdNodo()!=$this->getId()){
				if(!$force)
					return null;
				elseif(!$instancia->hasIdNodo()){
					$instancia->setIdNodo($this->getId());
				}
				elseif(!$this->hasId()){
					$this->setId($instancia->getIdNodo());
				}
			}
			$tipo_nodo = $this->getTipoNodo();
			if($tipo_nodo){
				if($tipo_nodo->hasClaseModelo() && ($clase_modelo = $tipo_nodo->getClaseModelo())){
					if($clase_modelo == get_class($instancia)){
						$this->_type_instance = $instancia;
					}
				}
			}
		}
		return $this;
	}
	public static function getHomeNodo(){
		$nodo = new Granguia_Model_Nodo();
		$nodo->setEsHome(1);
		if(!$nodo->load(null, true))
			return false;
		return $nodo;
	}
	public function resetOtherHomes(){
		if(!$this->getEsHome())
			return;
		$this->getDb()->open();
		$sql = strtr(
			'UPDATE %tabla SET es_home = 0 WHERE id!=%id;', array(
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
		$this->resetOtherHomes();
		$this->fixPathUrl();
		if(isset($this->_type_instance)){
			$this->_type_instance
				->setIdNodo($this->getId())
				->setNodo($this)
			;
		}
	}
	private function canonicalPathUrl($path_url){
		return preg_replace('/\/+/','/', rtrim($path_url,'/'));
	}
	protected function beforeChange(&$data){
		parent::beforeChange($data);
		if(isset($data['path_url']))
			$data['path_url'] = self::canonicalPathUrl($data['path_url']);
		$este = c(new self())->loadFromArray($data);
		if(
			$este->getId()&&//estamos modificando
			$este->esNodoGenerico()&&//un nodo generico
			c($nodo_viejo = new self())->setId($este->getId())->load()&&//existente
			$nodo_viejo->getPathUrl()!=$este->getPathUrl()//y cambiamos la url
		){//tenemos que actualizar todos los nodos hijos con url generica
			$db = $this->getDb();
			$db->open();
			$db->exec(array('debug'=>
				'UPDATE '.$db->nameToString($este->getDbTableName()).
				' SET path_url = concat('.$db->valueToString($este->getPathUrl()).', '.$db->valueToString('/').', id)'.
				' WHERE id_tipo_nodo = '.$db->valueToString($este->getIdTipoNodo()).' AND'.
				' id!='.$db->valueToString($este->getId()).' AND'.
				' path_url LIKE '.$db->valueToString($nodo_viejo->getPathUrl().'/%')
			));
			$db->close();
		}
		if($type_instance = $this->getTypeInstance()){
			$o = new Core_Object($data);
			if($data_index = $type_instance->getDataIndex($o->getTags(), $o->getPathUrl())){
				$data_index = preg_replace('([ ][ ]+)', ' ', $data_index);
				$data['data_index'] = $data_index;
			}
			if($busqueda_titulo = $type_instance->getBusquedaTitulo()){
				$data['busqueda_titulo'] = $busqueda_titulo;
			}
			if($busqueda_descripcion = $type_instance->getBusquedaDescripcion()){
				$data['busqueda_descripcion'] = $busqueda_descripcion;
			}
		}
	}
	private function fixPathUrl(){
		$nodo = $this;
		if(trim($nodo->getPathUrl())=='' && $nodo->getEsActiva()){
			$nodo = new self();
			$nodo->setId($this->getId());
			$nodo->load();
			if($default_path_url = $nodo->getDefaultPathUrl()){
//			if($tipo_nodo = $nodo->getTipoNodo()){
//				if($prefijo = $tipo_nodo->getDefaultUrlPrefix()){
					$updater = new Granguia_Model_Nodo();
					$updater->setData(array(
						'id'=>$nodo->getId(),'path_url'=>$default_path_url
					));
					$updater->update();
//				}
//			}
			}
		}
	}
	public static function getNodoFromPathUrl($path_url){
		$nodo = new self();
		$nodo->setPathUrl($path_url);
		$nodo->setWhere(Db_Helper::equal('path_url'));
		$nodos = $nodo->search(null,'ASC',null,0,__CLASS__);//si encuentra el que matchea perfecto mejor(nodo específico), 
		if(!$nodos){//sino le damos con likes (nodo genérico)
			$wheres = array();
			$wheres[] = '(';
			$wheres[] = Db_Helper::like('path_url', null, null, null, true);
			$wheres[] = 'OR';
			$wheres[] = Db_Helper::custom('{#path_url} like concat({@path_url},{%s})', '/%');
			$wheres[] = ') AND ';
			$wheres[] = Db_Helper::equal('es_activa', 1);
	
			//$nodo->setWhere(Db_Helper::like('path_url',null,null,null,true));
			$nodo->setWhereByArray($wheres);
			//echo $nodo->searchGetSql();
			//die();
			//$nodo->setPathUrl($this->request_path);
			$nodos = $nodo->search('path_url','DESC',null,0,__CLASS__);
			//EL ORDEN ES 100% IMPORTANTE ej si machea:
			// 'barrio' y 'barrio/18' vamos a preferir el más específico ( la cadena mas larga, por lo tanto mas pesada en ordenacion)
			//'barrio/18'-->primera (por el array_shift)
			//'barrio'
			if(!$nodos){
				return null;
				//die("no encuentro nada en la linea: ".__LINE__);
			}
		}
		$nodo = array_shift($nodos);
		return $nodo;
	}
	public function getBannersDinamicosDerecha(){
		return $this->getBannersDinamicos('derecha');
	}
	public function getBannersDinamicosAbajo(){
		return $this->getBannersDinamicos('abajo');
	}
	public function getNodoBannersDinamicosDerecha(){
		return $this->getBannersDinamicos('derecha', true);
	}
	public function getNodoBannersDinamicosAbajo(){
		return $this->getBannersDinamicos('abajo', true);
	}
	public function getBannersNoUsados(){
		$nodo_banner_dinamico = new Granguia_Model_NodoBannerDinamico();
		$nodo_banner_dinamico
			->setIdNodo($this->getId())
		;
		return $nodo_banner_dinamico->getBannersNoUsadosEnNodo();
	}
	public function getBannersDinamicos($posicion=null, $relacion=false){
		$nodo_banner_dinamico = new Granguia_Model_NodoBannerDinamico();
		$nodo_banner_dinamico
			->setIdNodo($this->getId())
			->setPosicion($posicion)
		;
		return $nodo_banner_dinamico->getBannersDinamicos($relacion);
		//return $this->NodoBannersDinamicos_to_BannersDinamicos( $this->getNodoBannersDinamicos($posicion) );
	}
	public function getMetaTitle($default){
		if(!($type_instance = $this->getTypeInstance()))
			return $default;
		$titulo = $type_instance->getMetaTitle($default);
		return $titulo;
	}
	public function getMetaDescription($default){
		if(!($type_instance = $this->getTypeInstance()))
			return $default;
		$description = $type_instance->getMetaDescription($default);
		return $description;
	}
	public function getMetaKeywords($default){
		if(!($tags = $this->getTags())){
			return $default;
		}
		return $tags;
	}
	public function getDbTableName() 
	{
		return 'gg_nodo';
	}
}
?>