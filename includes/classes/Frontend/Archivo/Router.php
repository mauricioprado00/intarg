<?php
class Frontend_Archivo_Router extends Core_Router_Abstract{
	protected function initialize(){
	}
	private function downloadArchivo(){
		$archivo = $this->getArchivo();
		$nombre = CFG_PATH_ROOT.'/'.$archivo->getPathArchivo();
		Core_Http_Header::OutputFile($nombre, $archivo->getNombre(), true);
		die();
		ECHO mime_content_type($nombre);
		VAR_DUMP($nombre);
		DIE();
		var_dump($archivo->getPathArchivo());
		
		header('Content-type: application/pdf');
		
		// It will be called downloaded.pdf
		header('Content-Disposition: attachment; filename="downloaded.pdf"');
		die();
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
		$archivo = new Granguia_Model_Archivo();
		$archivo = $this->getInstancia()->getTypeInstance();
		if(!$archivo){//nodo generico de archivo, no tiene instancia de archivo asociada
			$id_nodo = array_shift($request_path);//consumimos el id de nodo del archivo del request_path (puede continuar o no el nombre de categor�a)
			$nodo = new Granguia_Model_Nodo();
			$nodo->setId($id_nodo);
			if(!$nodo->load()){
				return false;
			}
			$tipo_nodo = $nodo->getTipoNodo();
			if(!$tipo_nodo->esArchivo()){
				return false;
			}
			$archivo = $nodo->getTypeInstance();
			$this->setInstancia($nodo);
			Core_App::getInstance()->setNodo($nodo);
		}
		Core_App::getInstance()->setArchivo($archivo);
		$this->setArchivo($archivo);
		$this->downloadArchivo();
		return true;
	}
	protected function dispatchNode(){
		return $this->dispatchAll();
	}
}
?>