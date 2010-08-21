<?
class Granguia_Model_TipoPunto extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"nombre",
			"imagen_deseleccionado",
			"imagen_seleccionado",
			"imagen_over",
			"imagen_deseleccionado_hotspot_x",
			"imagen_deseleccionado_hotspot_y",
			"imagen_seleccionado_hotspot_x",
			"imagen_seleccionado_hotspot_y",
			"imagen_over_hotspot_x",
			"imagen_over_hotspot_y",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getMapInformation(){
		/**
		@todo: falta guardar los tamaños de las imagenes para evitar tener que consultarlos constantemente
		*/
		$info = array('id', 'nombre', 'imagen_deseleccionado', 'imagen_seleccionado', 'imagen_over',
			'imagen_deseleccionado_hotspot_x',
			'imagen_deseleccionado_hotspot_y',
			'imagen_seleccionado_hotspot_x',
			'imagen_seleccionado_hotspot_y',
			'imagen_over_hotspot_x',
			'imagen_over_hotspot_y',
		);
		$object = new Core_Object();
		foreach($this->getData() as $key=>$value)
			if(in_array($key, $info))
				$object->setData($key, $value);
		list($ancho, $alto) = $this->getIconSize();
		$object->setWidth($ancho);
		$object->setHeight($alto);
		return $object;
	}
	public function getIconSize(){
		$file = CFG_PATH_ROOT.'/'.$this->getImagenDeseleccionado();
		if(!file_exists($file))
			return array(50, 50);
		//list($ancho, $alto) = getimagesize(CFG_PATH_ROOT);
		return getimagesize($file);
	}
	
	public function getDbTableName() 
	{
		return 'gg_tipo_punto';
	}
}
?>