<?php
class Granguia_Cronjob_Estadistica_Cleaner extends Granguia_Cronjob_Base{
	private $_clicks;
	function Verificar(){
		$this->setCantidadRegistrosAGuardar(10);
		$this->setRegistraSalida(true);
		if(!parent::Verificar())//verifica el tiempo mediante parametros configurables
			return false;
		return true;
	}
	function Ejecutar(){
		$limpiar_fecha = date('d/m/Y', time() - 60 * 60 * 24);
		Granguia_Model_Contador::Limpiar($limpiar_fecha);
		return "Tabla de contador limpiada para la fecha ".$limpiar_fecha;
	}
}
?>