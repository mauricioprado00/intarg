<?php
class Granguia_Cronjob_Estadistica_ClickAnuncio extends Granguia_Cronjob_Base{
	private $_clicks;
	function Verificar(){
		$this->setCantidadRegistrosAGuardar(10);
		$this->setRegistraSalida(true);
		if(!parent::Verificar())//verifica el tiempo mediante parametros configurables
			return false;
		$x = new Granguia_Model_View_ContadorClickAnuncioDia();
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
		$c = 0;
		foreach($this->_clicks as $data){
			//var_dump($data->getData());
			$anuncio = new Granguia_Model_Anuncio();
			$anuncio->setId($data->getIdAnuncio());
			if(!$anuncio->load())
				continue;
			if(isset($cache_categorias[$anuncio->getIdCategoria()]))
				$categoria = $cache_categorias[$anuncio->getIdCategoria()];
			else
				$categoria = $anuncio->getCategoria();
			if(isset($cache_barrios[$anuncio->getIdBarrio()]))
				$barrio = $cache_barrios[$anuncio->getIdBarrio()];
			else
				$barrio = $anuncio->getBarrio();
			$res = Granguia_Model_Estadistica_ClickAnuncio::Registrar(
				$data->getCantidad(),
				$anuncio->getId(),
				$anuncio->getNombre(),
				$categoria->getId(),
				$categoria->getNombre(),
				$barrio->getId(),
				$barrio->getNombre(),
				Mysql_Helper::filterDateOutput($data->getDia())
			);
			if($res)
				$maxs[] = $data->getMaxId();
		}
		$max = max($maxs);
		$return = "click anuncios procesados: ".count($maxs);

		$contador = new Granguia_Model_View_ContadorClickAnuncioCategoriaDia($max);
		$clicks_categoria = $contador->search();
		
		//echo $contador->searchGetSql();
		$c = 0;
		foreach($clicks_categoria as $data){
			//var_dump($data->getData());
			if(isset($cache_categorias[$data->getIdCategoria()]))
				$categoria = $cache_categorias[$data->getIdCategoria()];
			else{
				$categoria = new Granguia_Model_Categoria();
				$categoria->setId($data->getIdCategoria());
				if(!$categoria->load())
					continue;
				
			}
			$res = Granguia_Model_Estadistica_ClickAnuncioCategoria::Registrar(
				$data->getCantidad(),
				$categoria->getId(),
				$categoria->getNombre(),
				Mysql_Helper::filterDateOutput($data->getDia())
			);
			if($res)
				$c++;
		}
		$return .= "\nclick anuncio por categoria procesados: ".$c;

		
		$contador = new Granguia_Model_View_ContadorClickAnuncioBarrioDia($max);
		$clicks_barrio = $contador->search();
		//echo $contador->searchGetSql();
		$c = 0;
		foreach($clicks_barrio as $data){
			//var_dump($data->getData());
			if(isset($cache_barrios[$data->getIdBarrio()]))
				$barrio = $cache_barrios[$data->getIdBarrio()];
			else{
				$barrio = new Granguia_Model_Barrio();
				$barrio->setId($data->getIdBarrio());
				if(!$barrio->load())
					continue;
			}
			$res = Granguia_Model_Estadistica_ClickAnuncioBarrio::Registrar(
				$data->getCantidad(),
				$barrio->getId(),
				$barrio->getNombre(),
				Mysql_Helper::filterDateOutput($data->getDia())
			);
			if($res)
				$c++;
		}
		$return .= "\nclick anuncio por barrio procesados: ".$c;
		Granguia_Model_Contador::ClickAnuncioMarcarProcesado($max);
		//var_dump(count($clicks_categoria));
		//var_dumP(Granguia_Db::getInstance()->getLastErrors());
		return $return;
	}
}
?>