<?php
class Granguia_Cronjob_Estadistica_InicioSesion extends Granguia_Cronjob_Base{
	private $_counts;
	function Verificar(){
		$this->setCantidadRegistrosAGuardar(10);
		$this->setRegistraSalida(true);
		if(!parent::Verificar())//verifica el tiempo mediante parametros configurables
			return false;
		$x = new Granguia_Model_View_ContadorInicioSesionDia();
		$this->_counts = $x->search();
		return true;
	}
	function Ejecutar(){
		if(!$this->_counts)
			return 'empty';
		$max_id = 0;
		$maxs = array();
		foreach($this->_counts as $data){
			$res = Granguia_Model_Estadistica_InicioSesion::Registrar(
				$data->getCantidad(),
				Mysql_Helper::filterDateOutput($data->getDia())
			);
			if($res)
				$maxs[] = $data->getMaxId();
		}
		$max = max($maxs);
		Granguia_Model_Contador::InicioSesionMarcarProcesado($max);
		return "items procesados: ".count($maxs);
	}
}
?>