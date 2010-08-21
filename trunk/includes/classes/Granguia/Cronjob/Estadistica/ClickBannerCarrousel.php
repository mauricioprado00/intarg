<?php
class Granguia_Cronjob_Estadistica_ClickBannerCarrousel extends Granguia_Cronjob_Base{
	private $_clicks;
	function Verificar(){
		$this->setCantidadRegistrosAGuardar(10);
		$this->setRegistraSalida(true);
		if(!parent::Verificar())//verifica el tiempo mediante parametros configurables
			return false;
		$x = new Granguia_Model_View_ContadorClickBannerCarrouselDia();
		$this->_clicks = $x->search();
		return true;
	}
	function Ejecutar(){
		if(!$this->_clicks)
			return 'empty';
		$cache_categorias = array();
		$cache_barrios = array();
		$max_id = 0;
		$maxs = array();
		$cache_banners_carrousels = array();
		foreach($this->_clicks as $data){
			if(isset($cache_banners_carrousels[$data->getIdBannerCarrousel()])){
				$banner_carrousel = $cache_banners_carrousels[$data->getIdBannerCarrousel()];
			}
			else{
				$banner_carrousel = new Granguia_Model_BannerCarrousel();
				$banner_carrousel->setId($data->getIdBannerCarrousel());
				if(!$banner_carrousel->load())
					continue;
			}
			$res = Granguia_Model_Estadistica_ClickBannerCarrousel::Registrar(
				$data->getCantidad(),
				$banner_carrousel->getId(),
				$banner_carrousel->getNombre(),
				$data->getUrl(),
				Mysql_Helper::filterDateOutput($data->getDia())
			);
			if($res)
				$maxs[] = $data->getMaxId();
		}
		$max = max($maxs);
		Granguia_Model_Contador::ClickBannerCarrouselMarcarProcesado($max);
		return "items procesados: ".count($maxs);
	}
}
?>