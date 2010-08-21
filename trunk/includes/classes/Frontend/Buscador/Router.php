<?php
class Frontend_Buscador_Router extends Core_Router_Abstract{
	protected function initialize(){
		$this->addActions('anuncios');
	}
	protected function anuncios(){//buscador de anuncio
		$tipo_nodo_anuncio = Granguia_Model_TipoNodo::getTipoNodoByName('Anuncio');
		if(!$tipo_nodo_anuncio)
			return false;
		Core_App::getLayout()->addActions('busqueda');
		$query = Frontend_Buscador_Helper::getInstance()->getQuery();
		$pagina = Frontend_Buscador_Helper::getInstance()->getPagina();
		$nodo = new Granguia_Model_Nodo();
		$nodo->setData(array('id'=>null,'path_url'=>null,'busqueda_titulo'=>null,'busqueda_descripcion'=>null));
		$nodo->setIdTipoNodo($tipo_nodo_anuncio->getId());
		$nodo->setEsActiva(1);
		$nodo->setWhere(
			Db_Helper::equal('id_tipo_nodo'),
			Db_Helper::equal('es_activa'),
			Db_Helper::custom('match({@data_index}) against({%s})', $query)
		);
		$resultado_busqueda = Core_App::getLoadedLayout()->getBlock('resultado_busqueda');
		$resultado_busqueda
			->setSearchObject($nodo)
			->setTerminoBusqueda($query)
			->setPagina($pagina)
		;
		return true;
	}
//	protected function localDispatch(){
//		die(__FILE__.__LINE__);
//		return false;
//	}
//	private function dispatchAll(){//esto no es de Core_Router_Abstract, es un invento para procesar todo lo que no coincide en un solo lado
//		$request_path = $this->arr_request_path;
//		die(__FILE__.__LINE__);
//	}
//	protected function dispatchNode(){
//		die(__FILE__.__LINE__);
//		return $this->dispatchAll();
//	}
}
?>