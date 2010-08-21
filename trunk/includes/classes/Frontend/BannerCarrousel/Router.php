<?php
class Frontend_BannerCarrousel_Router extends Core_Router_Abstract{
	protected function initialize(){
	}

	private function dispatchAll(){//esto no es de Core_Router_Abstract, es un invento para procesar todo lo que no coincide en un solo lado
		$request_path = $this->arr_request_path;
		$banner_carrousel = new Granguia_Model_BannerCarrousel();
		$banner_carrousel->setId(array_shift($request_path));
		if(!$banner_carrousel->load())
			return false;
//		$banner_carrousel->metodoQueGuardaLosLinks();
		Granguia_Model_Contador::ContarClickBannerCarrousel($banner_carrousel->getId(), $banner_carrousel->getUrlClick());
		header('location:'.$banner_carrousel->getUrlClick());
		die();
	}
	protected function dispatchNode(){
		return $this->dispatchAll();
	}
}
?>