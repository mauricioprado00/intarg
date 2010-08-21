<?php
class Granguia_Cronjob_Estadistica_AccesoCategoria extends Granguia_Cronjob_Base{
	private $_clicks;
	function Verificar(){
		$this->setCantidadRegistrosAGuardar(10);
		$this->setRegistraSalida(true);
		if(!parent::Verificar())//verifica el tiempo mediante parametros configurables
			return false;
		$x = new Granguia_Model_View_ContadorAccesoCategoriaDia();
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
		$cache_categorias = array();
		foreach($this->_clicks as $data){
			if(isset($cache_categorias[$data->getIdCategoria()])){
				$categoria = $cache_categorias[$data->getIdCategoria()];
			}
			else{
				$categoria = new Granguia_Model_Categoria();
				$categoria->setId($data->getIdCategoria());
				if(!$categoria->load())
					continue;
			}
			$res = Granguia_Model_Estadistica_AccesoCategoria::Registrar(
				$data->getCantidad(),
				$categoria->getId(),
				$categoria->getNombre(),
				Mysql_Helper::filterDateOutput($data->getDia())
			);
			if($categoria_padre = $categoria->getCategoriaPadre()){
				$res = Granguia_Model_Estadistica_AccesoCategoria::Registrar(
					$data->getCantidad(),
					$categoria_padre->getId(),
					$categoria_padre->getNombre(),
					Mysql_Helper::filterDateOutput($data->getDia()),
					'1'
				);
			}
			if($res)
				$maxs[] = $data->getMaxId();
		}
		$max = max($maxs);
		Granguia_Model_Contador::AccesoCategoriaMarcarProcesado($max);
		return "items procesados: ".count($maxs);
	}
}
?>