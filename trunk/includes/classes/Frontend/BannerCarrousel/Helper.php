<?php

class Frontend_BannerCarrousel_Helper extends Core_Singleton{
	private $_nodo = null;
	public function getNodo(){
		if(!isset($this->_nodo)){
			if($tipo_nodo = Granguia_Model_TipoNodo::getTipoNodoByName('BannerCarrousel')){
				$nodo = new Granguia_Model_Nodo();
				$nodo->setIdTipoNodo($tipo_nodo->getId());
				if($nodo->load()){
					$this->_nodo = $nodo;
				}
			}
		}
		return ($this->_nodo);
	}
	public function getSearchLinkUrl($idBannerCarrousel = null){
		if(!$nodo = $this->getNodo())
			return '';
                $retv = ($idBannerCarrousel) ? $nodo->getPathUrl()."/".$idBannerCarrousel : $nodo->getPathUrl();
                return $retv;
	}
        public function getInstance(){
               return(self::getInstanceOf(__CLASS__));
        }
}

?>