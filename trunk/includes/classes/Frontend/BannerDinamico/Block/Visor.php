<?
class Frontend_BannerDinamico_Block_Visor extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('banner_dinamico/visor.phtml');
	}
	public function getBannersDinamicos(){
		if(!$this->hasPosicion()||!($posicion = $this->getPosicion()))
			return null;
		if($categoria = Core_App::getInstance()->getCategoria()){
			if(!($nodo = $categoria->getNodo()))
				return null;
			$banners_dinamicos = $nodo->getBannersDinamicos($posicion);
			if($banners_dinamicos)//si hay banners en la categoria los devolvemos
				return $banners_dinamicos;
			$categoria_padre = $categoria->getCategoriaPadre();
			if($categoria_padre){
				if($nodo = $categoria_padre->getNodo()){
					if($banners_dinamicos = $nodo->getBannersDinamicos($posicion)){
						return $banners_dinamicos;//cargamos los banners de la categoria padre
					}
				}
			}
			//sino devolvemos los banners del nodo de barrio (si este tiene banners dinamicos abajo)
		}
		if(!($nodo = Core_App::getInstance()->getNodo()))
			return null;
//		if(!($tipo_nodo = $nodo->getTipoNodo()))
//			return null;
		return $nodo->getBannersDinamicos($posicion);
	}
}
?>