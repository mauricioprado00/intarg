<?php
class Granguia_Cronjob_Estadistica_AccesoSesion extends Granguia_Cronjob_Base{
	private $_clicks;
	function Verificar(){
		$this->setCantidadRegistrosAGuardar(10);
		$this->setRegistraSalida(true);
		if(!parent::Verificar())//verifica el tiempo mediante parametros configurables
			return false;
		$x = new Granguia_Model_View_ContadorAccesoSesionDia();
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
			//var_dump($data->getData());
			$res = Granguia_Model_Estadistica_AccesoSesion::Registrar(
				$data->getCantidad(),
				$data->getIp(),
				Mysql_Helper::filterDateOutput($data->getDia())
			);
			if($res)
				$maxs[] = $data->getMaxId();
		}
		$max = max($maxs);
		$return = "accesos ip procesadas: ".count($maxs);

		$urls = new Granguia_Model_View_ContadorAccesoSesionUrlDia($max);
		//echo $urls->searchGetSql();
		$acceso_url = $urls->search();
		$c = 0;
		foreach($acceso_url as $data){
			//var_dump($data->getData());
			$res = Granguia_Model_Estadistica_AccesoSesionUrl::Registrar(
				$data->getCantidad(),
				$data->getUrl(),
				Mysql_Helper::filterDateOutput($data->getDia())
			);
			if($res)
				$c++;
		}
		Granguia_Model_Contador::AccesoSesionMarcarProcesado($max);
		$return .= "\naccesos url procesadas: ".$c;
		return $return;
	}
}
?>