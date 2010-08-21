<?php
class Frontend_Barrio_Router extends Core_Router_Abstract{
	protected function initialize(){
	}
	private function loadBarrio(){
		Core_App::getLayout()->addActions('barrio');
		if($c=Core_App::getLoadedLayout()->getBlock('mainmenu'))
			$c->setSelectedItem('btn_inicio');
		$block_mapa = Core_App::getLoadedLayout()->getBlock('mapa');
		if($block_mapa){
			$block_mapa
				->setBarrio($this->getBarrio())
				->setCategoria($this->getCategoria())
			;
		}
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
//		//aca entra por coincidencia completa de url, lo cual no asegura 
//		//var_dump($this->getInstancia());
//		$barrio = new Granguia_Model_Barrio();
//		$barrio->setIdNodo($this->getInstancia()->getId());
//		//var_dump($this->getInstancia()->getId());die('muerte');
//		$barrio->setWhere(Db_Helper::equal('id_nodo'));
//		$aBarrios = $barrio->search(null,'ASC',null,'0','Granguia_Model_Barrio');
//		var_dump($aBarrios);
//		if(!$aBarrios){
//			return false;
//		}
//		$barrio = array_shift($aBarrios);
//		$this->setBarrio($barrio);
//		die('localdis');
	}
	private function dispatchAll(){//esto no es de Core_Router_Abstract, es un invento para procesar todo lo que no coincide en un solo lado
		$request_path = $this->arr_request_path;
		$barrio = new Granguia_Model_Barrio();
		$barrio = $this->getInstancia()->getTypeInstance();
		if(!$barrio){//nodo generico de barrio, no tiene instancia de barrio asociada
			$id_nodo = array_shift($request_path);//consumimos el id de nodo del barrio del request_path (puede continuar o no el nombre de categor�a)
			$nodo = new Granguia_Model_Nodo();
			$nodo->setId($id_nodo);
			if(!$nodo->load()){
				return false;
			}
			$tipo_nodo = $nodo->getTipoNodo();
			if(!$tipo_nodo->esBarrio()){
				return false;
			}
			$barrio = $nodo->getTypeInstance();
			$this->setInstancia($nodo);
			Core_App::getInstance()->setNodo($nodo);
		}
//		$barrio->setIdNodo($this->getInstancia()->getId());
//		$barrio->setWhere(Db_Helper::equal('id_nodo'));
//		$barrios = $barrio->search(null, 'ASC', null, 0, 'Granguia_Model_Barrio');
//		if(!$barrios){//es un nodo gen�rico barrio/15, etc... pero de un barrio que tiene un path_node fancy, sino el nodo estar�a asociado al barrio
//			$id_nodo = array_shift($request_path);//consumimos el id de barrio del request_path (puede continuar o no el nombre de categor�a)
//			$barrio->setIdNodo($id_nodo);
//			$barrios = $barrio->search(null, 'ASC', null, 0, 'Granguia_Model_Barrio');
//			if(!$barrios){//no se encuentra, volvemos
//				//die("no se encuentra el barrio con el id_nodo $id_nodo");
//				return false;
//			}
////			var_dump($this->getInstancia()->getTipoNodo()->getData());
////			$nodo = $barrios->getNodo();
////			var_dump($nodo);
////			die();
////			Core_App::setNodo($nodo);//el nodo 
//		}
		//hasta aca independientemente si entro con el alias o con barrio/id_nodo en el request_path queda el resto de la misma manera
		//y la variable $barrio esta cargada con el barrio asociado al nodo
//		$barrio = array_shift($barrios);
		Core_App::getInstance()->setBarrio($barrio);
//		$div = new Core_Html_Tag_Custom('div');
//		echo $div->setInnerHtml( $barrio->getNombre() )->getHtml();

		$this->setBarrio($barrio);
		$categoria = Granguia_Model_Categoria::getCategoriaFromComponenteUrl(implode('/', $request_path));
		if(!$categoria){
			$categoria = Granguia_Model_Categoria::getCategoriaDefault();
		}
		if($categoria){
			$this->setCategoria($categoria);
        	Core_App::getInstance()->setCategoria($categoria);
        	$anuncio_destacado = Granguia_Model_Anuncio::getAnuncioDestacado($barrio->getId(), $categoria->getId());
        	//var_dump($anuncio_destacado->getData());
//        	var_dump($barrio->getId(), $categoria->getId());
//        	die();
//echo $div->setInnerHtml( 'hay anuncio destacado: '.($anuncio_destacado?'Si':'No') )->getHtml();
        	if($anuncio_destacado)
        		Core_App::getInstance()->setAnuncioDestacado($anuncio_destacado);
        }
		$this->loadBarrio();
//		var_dump($this->getBarrio());
//		var_dump($this->getCategoria());
//		die();
		//var_dump($barrio,$categoria);
		//die();
		return true;
	}
	protected function dispatchNode(){
		return $this->dispatchAll();
//		var_dump($this->arr_request_path);
////		var_dump($this->request_path);
//		var_dump($this->getInstancia());
//		$idBarrio = array_shift($this->arr_request_path);
//		$barrio = new Granguia_Model_Barrio();
//		$barrio->setId($idBarrio);
//		//var_dump($barrio);die('kradesuperkk'); 
//		if(!$barrio->load()){
//			die("no encuentro el barrio");
//			return false;
//		}
//		$nodo = new Granguia_Model_Nodo();
//		$nodo->setId($barrio->getIdNodo());
//		if(!$nodo->load()){
//			die("barrio no publicado");
//			return false;
//		}
//		$this->setInstancia($nodo);
//		$this->setBarrio($barrio);
//		die('toy en el dispatchnode');
	}
//	
//	private function isGeneric(){
//		if($this->getInstancia() !== null && strpos($this->getInstancia()->getPathUrl()) != -1){
//			return true;
//		}
//		return false;
//	}
	 
}
?>