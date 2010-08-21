<?php
class Granguia_Cronjob_Estadistica_ClickMinisitio extends Granguia_Cronjob_Base{
	private $_clicks;
	function Verificar(){
		$this->setCantidadRegistrosAGuardar(10);
		$this->setRegistraSalida(true);
		if(!parent::Verificar())//verifica el tiempo mediante parametros configurables
			return false;
		$x = new Granguia_Model_View_ContadorClickMinisitioDia();
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
		foreach($this->_clicks as $data){
			$anuncio = new Granguia_Model_Anuncio();
			$anuncio->setId($data->getIdAnuncio());
			if(!$anuncio->load())
				continue;
			$res = Granguia_Model_Estadistica_ClickMinisitio::Registrar(
				$data->getCantidad(),
				$anuncio->getId(),
				$anuncio->getNombre(),
				$data->getUrl(),
				Mysql_Helper::filterDateOutput($data->getDia())
			);
			if($res)
				$maxs[] = $data->getMaxId();
		}
		$max = max($maxs);
		Granguia_Model_Contador::ClickMinisitioMarcarProcesado($max);
		return "items procesados: ".count($maxs);
	}
}
?>