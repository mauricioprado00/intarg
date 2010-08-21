<?php
class Granguia_Cronjob_Estadistica_ClickBannerDinamico extends Granguia_Cronjob_Base{
	private $_clicks;
	function Verificar(){
		$this->setCantidadRegistrosAGuardar(10);
		$this->setRegistraSalida(true);
		if(!parent::Verificar())//verifica el tiempo mediante parametros configurables
			return false;
		$x = new Granguia_Model_View_ContadorClickBannerDinamicoDia();
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
		$cache_banners_dinamicos = array();
		foreach($this->_clicks as $data){
			if(isset($cache_banners_dinamicos[$data->getIdBannerDinamico()])){
				$banner_dinamico = $cache_banners_dinamicos[$data->getIdBannerDinamico()];
			}
			else{
				$banner_dinamico = new Granguia_Model_BannerDinamico();
				$banner_dinamico->setId($data->getIdBannerDinamico());
				if(!$banner_dinamico->load())
					continue;
			}
			$res = Granguia_Model_Estadistica_ClickBannerDinamico::Registrar(
				$data->getCantidad(),
				$banner_dinamico->getId(),
				$banner_dinamico->getNombre(),
				$data->getUrl(),
				Mysql_Helper::filterDateOutput($data->getDia())
			);
			if($res)
				$maxs[] = $data->getMaxId();
		}
		$max = max($maxs);
		Granguia_Model_Contador::ClickBannerDinamicoMarcarProcesado($max);
		return "items procesados: ".count($maxs);
	}
}
?>