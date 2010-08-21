<?php
class Frontend_Buscador_Helper extends Core_Singleton{
	private $_nodo = null;
	public function getNodo(){
		if(!isset($this->_nodo)){
			if($tipo_nodo = Granguia_Model_TipoNodo::getTipoNodoByName('Buscador')){
				$nodo = new Granguia_Model_Nodo();
				$nodo->setIdTipoNodo($tipo_nodo->getId());
				if($nodo->load()){
					$this->_nodo = $nodo;
				}
			}
		}
		return ($this->_nodo);
	}
	public function getSearchLinkUrl(){
		if(!$nodo = $this->getNodo())
			return '';
		return $nodo->getPathUrl();
	}
	private $query = null;
	public function getQuery(){
		if(!isset($this->query)){
			$get = Core_Http_Get::getParameters('Core_Object');
			$this->query = $get->hasQ()?$get->getQ():'';
		}
		return $this->query;
	}
	private $pagina = null;
	public function getPagina(){
		if(!isset($this->pagina)){
			$get = Core_Http_Get::getParameters('Core_Object');
			$this->pagina = $get->getP()+0;
		}
		return $this->pagina;
	}
	public function getSearchLinkUrlAnuncios($query=null,$pagina=null){
		if(!($base_link = $this->getSearchLinkUrl()))
			return '';
		$link = $base_link . '/anuncios';
		if(isset($query)){
			if($query===true){
				$query = $this->getQuery();
			}
			$link .= '?q='.urlencode($query);
			if(isset($pagina)){
				$link .= '&p='.urlencode($pagina+0);
			}
		}
		return $link;
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
}
?>