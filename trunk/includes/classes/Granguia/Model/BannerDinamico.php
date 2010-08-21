<?
class Granguia_Model_BannerDinamico extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			"id",
			"nombre",
			"contenido",
			"cantidad_espacios_vertical",
			"cantidad_espacios_horizontal",
			"links",
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
//		$this->addAutofilterFieldInput('contenido', array($this, 'capturarLinks'));
//		$this->addAutofilterFieldOutput('contenido', array($this, 'insertarLinks'));
	}
	public function linksMode($real=true){
		$this->addAutofilterFieldInput('contenido', array($this, 'capturarLinks'));
		if($real)
			$this->addAutofilterFieldOutput('contenido', array($this, 'insertarLinks'));
		else
			$this->addAutofilterFieldOutput('contenido', array($this, 'insertarPseudolinks'));
	}
	public function fixLinksFilters(){
		$this->resetAutofilterFieldsInput('links');
		$this->resetAutofilterFieldsOutput('links');
		$this->addAutofilterFieldInput('links', 'serialize');
		$this->addAutofilterFieldOutput('links', 'unserialize');
	}
	public function capturarLinks($contenido){
		$reg_exp = '(href="(?<urls>[^"{}]+)")';
		if(!preg_match_all($reg_exp, $contenido, $matches))
			return $contenido;
		$this->fixLinksFilters();
		$this->setLinks($matches['urls']);
		foreach($this->getLinks() as $idx=>$link){
			$contenido = str_replace('href="'.$link.'"','href="{!banner_link_url}/'.$idx.'"', $contenido);
		}
		return $contenido;
	}
	public function insertarLinks($contenido){
		$this->fixLinksFilters();
		foreach($this->getLinks() as $idx=>$link){
			$contenido = str_replace('href="{!banner_link_url}/'.$idx.'"','href="'.$link.'"', $contenido);
		}
		return $contenido;
		//return strtr($contenido, array('{!banner_link_url}'=>'{!base_url}link/'.$this->getId().'/'));
	}
	private static $_tipo_nodo = null;
	private static function getTipoNodo(){
		if(!isset(self::$_tipo_nodo)){
			$tipo_nodo = new Granguia_Model_TipoNodo();
			$tipo_nodo->setNombre('BannerDinamico');
			if($tipo_nodo->load()){
				self::$_tipo_nodo = $tipo_nodo;
			}
		}
		return self::$_tipo_nodo;
	}
	private static $_nodo = null;
	private static function getNodo(){
		if(!isset(self::$_nodo)){
			$tipo_nodo = self::getTipoNodo();
			if(isset($tipo_nodo)){
				$nodo = new Granguia_Model_Nodo();
				$nodo->setIdTipoNodo($tipo_nodo->getId());
				if($nodo->load()){
					self::$_nodo = $nodo;
				}
			}
		}
		return self::$_nodo;
	}
	public function insertarPseudolinks($contenido){
		$nodo = $this->getNodo();
		$link = isset($nodo)?$nodo->getPathUrl():'link';
		return strtr($contenido, array('{!banner_link_url}'=>'{!base_url}'.$link.'/'.$this->getId()));
	}
	public function getDbTableName() 
	{
		return 'gg_banner_dinamico';
	}
}
?>