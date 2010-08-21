<?
class Granguia_Catalog_Model_Producto extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"codigo",
			"nombre",
			"descripcion",
			"marca",
			"categoria",
			"rubro",
			"stock",
			"precio",
			"imagen",
			"fecha",
			"modelos",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	private static $campos_ordenables = array(
			"Nombre"=>"nombre",
			"Descripción"=>"descripcion",
			"Código"=>"codigo",
			"Marca"=>"marca",
			//"Categoria"=>"categoria",
			"Rubro"=>"rubro",
			"Precio"=>"precio",
	);
	public static function isCampoOrdenable($campo){
		return(in_array($campo, self::$campos_ordenables));
	}
	private static function transformCamposOrdenables(){
		$ret = array();
		foreach(self::$campos_ordenables as $label=>$campo){
			$ret[] = array($campo, $label);
		}
		return($ret);
	}
	public static function getCamposOrdenables(){
		$arr = self::transformCamposOrdenables();
		$ret = array();
		foreach($arr as $item){
			$obj = new Core_Object();
			$ret[] = $obj
				->setNombre($item[0])
				->setLabel($item[1])
			;
		}
		return($ret);
	}
	private static $campos_buscables = array(
			//"Nombre"=>"nombre",
			"Código"=>"codigo",
			"Descripción"=>"descripcion",
			"Marca"=>"marca",
			//"Categoria"=>"categoria",
			"Rubro"=>"rubro",
			"Modelos"=>"modelos",
			//"Precio"=>"precio",
	);
	public static function isCampoBuscable($campo){
		return(in_array($campo, self::$campos_buscables));
	}
	private static function transformCamposBuscables(){
		$ret = array();
		foreach(self::$campos_buscables as $label=>$campo){
			$ret[] = array($campo, $label);
		}
		return($ret);
	}
	public static function getCamposBuscables(){
		//$arr = $this->campos_buscables;
		$arr = self::transformCamposBuscables();
		$ret = array();
		foreach($arr as $item){
			$obj = new Core_Object();
			$ret[] = $obj
				->setNombre($item[0])
				->setLabel($item[1])
			;
		}
		return($ret);
	}
	public static function getCategorias(){
		$categoria = new Granguia_Catalog_Model_View_Categoria();
		return($categoria->search());
	}
	public static function getRubros(){
		$rubro = new Granguia_Catalog_Model_View_Rubro();
		return($rubro->search());
	}
	public function getSrcImagen($idx=null){
		//var_dump($this->getImagen());
		if(!$this->getImagen())
			return(null);
		$arr_img_src = explode(',', $this->getImagen());
		//var_dump($arr_img_src);
		if(!$arr_img_src)
			return(null);
		if($idx!==null){
			$idx = isset($arr_img_src[$idx])?$idx:0;
			$img = isset($arr_img_src[$idx])?($arr_img_src[$idx]):'';
			return(('img/'.$img));
		}
		foreach($arr_img_src as $idx=>$img_src)
			$arr_img_src[$idx] = 'img/'.$img_src;
		return($arr_img_src);
	}
	public function getSrcImagenSearchResult(){
		if($this->getSrcImagen(0))
			return($this->getSrcImagen(0));
		return('img/'.Granguia_Model_Config::findConfigValue('img_producto_sin_imagen'));
	}
	public function getLinkImagenUrl($idx=0, $max_width=null,$max_height=null,$use_cache=true){
		$imagen = $this->getSrcImagen($idx);
		if(!$imagen){
			$imagen = Granguia_Model_Config::findConfigValue('img_producto_sin_imagen');
			if(!$imagen)
				return('');
			$imagen = 'img/'.$imagen;
		}
		if(!$imagen)
			return('');
		if(!$use_cache)
			return(Admin_MarcaImagen_Helper::getLinkImagenUrl($imagen));
		$imagepath = CFG_PATH_ROOT.'/'.$imagen;// Admin_MarcaImagen_Helper::getImagePath($imagen);
		$image_cache = Core_Image_Cache::create($imagepath, $max_width, $max_height);
		return($image_cache->getLinkUrl('jpg'));
	}

	public function getSearchKey(){
		return($this->getId().'-'.$this->getCodigo());
	}
	public function getLinkUrlVer($popup=false){
		return('catalogo/producto/'.$this->getSearchKey().($popup?'/1':''));
	}
	public function getLinkUrlAgregarACarro($cantidad=null){
		return(Granguia_Cart_Helper::getUrlAgregarACarro($this->getSearchKey(), $cantidad));
	}
	public function getDbTableName() 
	{
		return 'bm_producto';
	}
	public static function findBySearchKey($search_key){
		$search_key = explode('-', $search_key);
		$id = array_shift($search_key);
		$codigo = implode('-', $search_key);
		$producto = new self();
		$producto->setCodigo($codigo);
		$producto->setWhere(Db_Helper::equal('codigo'));
		$productos = $producto->search();
		//var_dump($producto->search(null, 'ASC', null, 0, true, null, true));
		if(!$productos){
			$producto->resetData();
			$producto->setId($id);
			$producto->setWhere(Db_Helper::equal('id'));
			$productos = $producto->search();
			if(!$productos)
				return(null);
		}
		$producto->resetData();
		$producto->loadFromArray($productos[0]->getData());
		return($producto);
		
	}
}
?>