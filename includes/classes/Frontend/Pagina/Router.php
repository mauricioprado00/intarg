<?php
class Frontend_Pagina_Router extends Core_Router_Abstract{
	protected function initialize(){
	}
	private function showPagina(){
		Core_App::getLayout()
			->addActions('nodo_pagina')
		;
		$pagina = Core_App::getInstance()->getPagina();
		if($pagina&&$pagina->getNombreInterno()=='quienes_somos'&&$b=Core_App::getLoadedLayout()->getBlock('mainmenu'))
			$b->setSelectedItem('btn_quienes');
			
		if($pagina_view = Core_App::getLoadedLayout()->getBlock('pagina_view')){
			$pagina_view->setPagina($pagina);
			return true;
		}
		return false;
		//$btm = Core_App::getLoadedLayout()->getBlock('template_mapa');
		//$btm->setCategoria('18');
//esto no hace mas falta, el bloque de menu de barrio consulta solo los barrios existentes
//		$barrio = new Granguia_Model_Barrio();
//		$aBarrios = $barrio->search();
//		if($blockBarrio = Core_App::getLoadedLayout()->getBlock('barrio'))
//			$blockBarrio->setABarrios($aBarrios);
	}
	protected function localDispatch(){
		return $this->dispatchAll();
	}
	private function dispatchAll(){//esto no es de Core_Router_Abstract, es un invento para procesar todo lo que no coincide en un solo lado
		$request_path = $this->arr_request_path;
		$pagina = new Granguia_Model_Pagina();
		$pagina = $this->getInstancia()->getTypeInstance();
		if(!$pagina){//nodo generico de pagina, no tiene instancia de pagina asociada
			$id_nodo = array_shift($request_path);//consumimos el id de nodo del pagina del request_path (puede continuar o no el nombre de categor�a)
			$nodo = new Granguia_Model_Nodo();
			$nodo->setId($id_nodo);
			if(!$nodo->load()){
				return false;
			}
			$tipo_nodo = $nodo->getTipoNodo();
			if(!$tipo_nodo->esPagina()){
				return false;
			}
			$pagina = $nodo->getTypeInstance();
			$this->setInstancia($nodo);
			Core_App::getInstance()->setNodo($nodo);
		}
		Core_App::getInstance()->setPagina($pagina);
		$this->setPagina($pagina);
		$this->showPagina();
		return true;
	}
	protected function dispatchNode(){
		return $this->dispatchAll();
	}
}
?>