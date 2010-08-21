<?
class Granguia_Model_Anuncio extends Granguia_Model_ExtensionNodo{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'estado',
			'nombre',
			'imagen_logo',
			'telefono',
			'direccion',
			'email',
			'id_categoria',
			'id_barrio',
			'id_tipo_punto',
			'posicion_x',
			'posicion_y',
			'id_tipo_contacto',
			'texto_contacto',
			'label_contacto',
			'email_contacto',
			'tiene_minisitio',
			'texto_minisitio',
			'minisitio',
			'url_minisitio',
			'id_nodo',
			'activo',
			'destacado'
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public static function OpcionesMinisitio(){
		$ret = array();
		$ret[] = new Core_Object(array('id'=>0,'nombre'=>'Deshabilitado'));
		$ret[] = new Core_Object(array('id'=>1,'nombre'=>'Interno'));
		$ret[] = new Core_Object(array('id'=>2,'nombre'=>'Externo'));
		return $ret;
	}
	public static function OpcionesEstado(){
		$ret = array();
		$ret[] = new Core_Object(array('id'=>1,'nombre'=>'Habilitado'));
		$ret[] = new Core_Object(array('id'=>0,'nombre'=>'Deshabilitado'));
		return $ret;
	}
	public static function TipoNodo(){
		static $tipo_nodo = null;
		if(!isset($tipo_nodo)){
			$tipo_nodo = new Granguia_Model_TipoNodo();
			$tipo_nodo->setNombre('Anuncio');
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
	public function getTipoPunto($use_cache=true){
		static $tipos_puntos = array();
		if(!$this->hasIdTipoPunto()||!$this->getIdTipoPunto())
			return null;
		if($use_cache){
			$ids = array_keys($tipos_puntos);
			if(in_array($this->getIdTipoPunto(), $ids))
				return $tipos_puntos[$this->getIdTipoPunto()];
		}
		$tipo_punto = new Granguia_Model_TipoPunto();
		$tipo_punto->setId($this->getIdTipoPunto());
		$tipo_punto->setWhere(Db_Helper::equal('id'));
		$resultados = $tipo_punto->search(null, 'ASC', null, 0, 'Granguia_Model_TipoPunto');
		if(!$resultados){
			$tipos_puntos[$this->getIdTipoPunto()] = null;
		}
		else{
			$tipos_puntos[$this->getIdTipoPunto()] = array_shift($resultados);
		}
		return $tipos_puntos[$this->getIdTipoPunto()];
	}
	private function usarUrlInternas(){
		/* para poder registrar los accesos hacemos que siempre sean url internas */
		return true;
	}
	public function getAbiertoAhora(){
		if(!($horario = $this->getHorarioAnuncio())){
			return false;
		}
		return $horario->getAbiertoAhora();
	}
	public function getMapInformation(){
		$info = array('id','nombre', 'imagen_logo', 'id_tipo_punto', 'posicion_x', 'posicion_y', 'telefono', 'tiene_minisitio', 'url_minisitio', 'id_tipo_contacto', 'label_contacto', 'texto_minisitio');
		$object = new Core_Object();
		foreach($this->getData() as $key=>$value)
			if(in_array($key, $info))
				$object->setData($key, trim($value));
		if($this->usarUrlInternas())
			if($object->getTieneMinisitio())
				$object->setTieneMinisitio(1);
		if($object->getTieneMinisitio()==1)
			$object->setUrlMinisitio($this->crearUrlInternaMinisitio());
		if($this->tieneContacto()){
			$object->setUrlContacto($this->getUrlContacto());
		}
		$object->setAbierto($this->getAbiertoAhora());
		return $object;
	}
	public function getEsMinisitioInterno(){
		return $this->getTieneMinisitio()==1?true:false;
	}
	public static function getUrlContadorClicks(){
		return $url_generica = Granguia_Model_TipoNodo::getTipoNodoGenericoUrlByName('anuncio').'/contar';
	}
	public function crearUrlInterna($param=null, $usar_generica=false){
//		$bt = debug_backtrace();
//		foreach($bt as $info){
//			var_dump($info['file'], $info['line']);
//		}
		if(!($nodo = $this->getNodo()))
			return null;
		$id = $nodo->getId();
		$url = $nodo->getPathUrl();
		if($usar_generica){
			if($url_generica = Granguia_Model_TipoNodo::getTipoNodoGenericoUrlByName('anuncio')){
				$url = $url_generica.'/'.$id;
			}
		}
		return $url.'/'.(isset($param)?$param:'');
		//return 'anuncio/'.$this->getId().'/';
	}
	public function crearUrlInternaMinisitio(){
		return $this->crearUrlInterna('minisitio');
	}
	public function tieneContacto(){
		return $this->hasIdTipoContacto() && $this->getIdTipoContacto()!=1;
	}
	public function esContactoComun(){
		return $this->hasIdTipoContacto() && $this->getIdTipoContacto()==2;
	}
	public function esContactoBoliche(){
		return $this->hasIdTipoContacto() && $this->getIdTipoContacto()==3;
	}
	public function getUrlContacto(){
		return $this->crearUrlInterna().'contacto';
	}
	private $_barrio = null;
	public function getBarrio(){
		if(!$this->hasIdBarrio()||!$this->getIdBarrio())
			return null;
		if(!isset($this->_barrio)){
			$barrio = new Granguia_Model_Barrio();
			$barrio->setId($this->getIdBarrio());
			if($barrio->load()){
				$this->_barrio = $barrio;
			}
		}
		return $this->_barrio;
	}
	private $_categoria = null;
	public function getCategoria(){
		if(!$this->hasIdCategoria()||!$this->getIdCategoria())
			return null;
		if(!isset($this->_categoria)){
			$categoria = new Granguia_Model_Categoria();
			$categoria->setId($this->getIdCategoria());
			if($categoria->load()){
				$this->_categoria = $categoria;
			}
		}
		return $this->_categoria;
	}
	public function resetOtherDestacados(){
		if(!$this->getDestacado()||!$this->getIdBarrio()||!$this->getIdCategoria()||$this->getEstado()==0)
			return;
		$this->getDb()->open();
		$sql = strtr(
			'UPDATE %tabla SET destacado = 0 WHERE id!=%id and id_barrio=%id_barrio and id_categoria=%id_categoria;', array(
				'%id'=>$this->getDb()->valueToString($this->getId()),
				'%id_barrio'=>$this->getDb()->valueToString($this->getIdBarrio()),
				'%id_categoria'=>$this->getDb()->valueToString($this->getIdCategoria()),
				'%tabla'=>$this->getDb()->nameToString($this->getDbTableName())
			)
		);
		$this->getDb()->exec($sql);
		$this->getDb()->close();
	}
	private $_horario_anuncio = null;
	public function getHorarioAnuncio(){
		if(!$this->hasId()||!$this->getId())
			return null;
		if(!isset($this->_horario_anuncio)){
			$horario_anuncio = new Granguia_Model_HorarioAnuncio();
			$horario_anuncio->setIdAnuncio($this->getId());
			if($horario_anuncio->load()){
				$this->_horario_anuncio = $horario_anuncio;
			}
		}
		return $this->_horario_anuncio;
	}
	protected function beforeDelete($data){
		$this->getDb()->open();
		$sql = strtr(
			'DELETE FROM %tabla WHERE id_anuncio!=%id_anuncio ;', array(
				'%id_anuncio'=>$this->getDb()->valueToString($data->getId()),
				'%tabla'=>$this->getDb()->nameToString('gg_horario')
			)
		);
		$this->getDb()->exec($sql);
		$this->getDb()->close();
	}
	protected function afterChange($data, $resultado){
		parent::afterChange($data, $resultado);
		if(!$resultado)
			return;
		$this->resetOtherDestacados();
	}
	public function getDataIndex($tags, $path_url){//esta funcion la llama la clase Nodo antes de guardar, para generar el campo fulltext de busqueda
		$ret = parent::getDataIndex($tags, $path_url);
		$dob = new Core_Object($this->getData());//de esta manera obtengo los valores internod del objeto evitando los filtros, pero sin modificar la configuración de filtros
		$parent_dataindex = '';
		if($categoria = $this->getCategoria()){
			$parent_dataindex .= ' ' . $categoria->getDataIndex('', '');
		}
		if($barrio = $this->getBarrio()){
			$parent_dataindex .= ' ' . $barrio->getDataIndex('', '');
		}
		return 
				$dob->getNombre() . ' ' . 
				$dob->getTelefono() . ' ' . 
				$dob->getDireccion() . ' ' . 
				$dob->getEmail() . '                  ' . 
				$dob->getUrlMinisitio() . ' ' . 
				$ret . ' ' . 
				$parent_dataindex;
	}
	public function getBusquedaTitulo(){
		return $this->getData('nombre', null, false);
	}
	public function getBusquedaDescripcion(){
		$barrio = $this->getBarrio();
		if(!$barrio){
			return 'error en busqueda de barrio';
		}
		return $barrio->getData('nombre', null, false) . ' - ' . $this->getData('direccion', null, false) . ' - ' . $this->getData('telefono', null, false);
	}
	public function getBusquedaEnlaces(){
		$enlaces = array();
		$enlaces[] = 
			c(new Core_Object())
				->setLabel('Ver Mapa')
				->setLink($this->getNodo()->getPathUrl())
		;
		return $enlaces;
	}
	public function getBusquedaTituloLink(){
		if(!$this->getTieneMinisitio())
			return parent::getBusquedaTituloLink();
		if($this->getTieneMinisitio()==1)
			return $this->crearUrlInternaMinisitio();
		return $this->getUrlMinisitio();
	}
	public static function getAnuncioDestacado($id_barrio, $id_categoria){
		$anuncio = new self();
		$anuncio
			->setIdBarrio($id_barrio)
			->setIdCategoria($id_categoria)
			->setDestacado(1)
			->setEstado(1)
		;
		if(!$anuncio->load())
			return null;
		$an2 = new self();
		$an2->loadFromArray($anuncio->getData());
		return $an2;
	}
	public function minisitioHabilitado(){
		return $this->getEstado()==1&&$this->getTieneMinisitio()==1;
	}
	public function sitioExternoHabilitado(){
		return $this->getEstado()==1&&$this->getTieneMinisitio()==2;
	}
	public function getMetaTitle($default){
		return $this->getNombre() . ' - ' . $default;
	}
	public function getDbTableName() 
	{
		return 'gg_anuncio';
	}
}
?>