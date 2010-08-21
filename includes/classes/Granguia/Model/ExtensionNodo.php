<?
abstract class Granguia_Model_ExtensionNodo extends Core_Model_Abstract{
	abstract public static function TipoNodo();
	abstract public static function IdTipoNodo();
	abstract public static function NewNodo();
	private $_nodo;
	public function getNodo(){
		if(!$this->hasIdNodo()){
			echo "no hay nodo: no hay id de nodo";
			return null;
		}
		if(!($id_nodo = $this->getIdNodo())){
			echo "no hay nodo: id vacio";
			return null;
		}
		if(!isset($this->_nodo)||$this->_nodo->getId()!=$id_nodo){
			$nodo = new Granguia_Model_Nodo();
			$nodo->setId($id_nodo);
			if(!$nodo->load()){
				echo "no hay nodo: id vacio";
				return null;
			}
			$nodo->setTypeInstance($this);
			$this->_nodo = $nodo;
		}
		return $this->_nodo;
	}
	public function setNodo($nodo){
		if(!$this->hasIdNodo()){
			echo "no hay nodo: no hay id de nodo";
			return null;
		}
		if(!($id_nodo = $this->getIdNodo())){
			echo "no hay nodo: id vacio";
			return null;
		}
		if(isset($nodo)&&($nodo instanceof Granguia_Model_Nodo)&&$nodo->getId()==$id_nodo){
			$this->_nodo = $nodo;
		}
		return $this;
	}
	public function getNodoTagsArray(){
		$nodo = $this->getNodo();
		if(!$nodo){
			return array();
		}
		$tags = explode(',', $nodo->getTags());
		return $tags;
	}
	public function getDataIndex($tags, $path_url){//esta funcion la llama la clase Nodo antes de guardar, para generar el campo fulltext de busqueda
		return $tags . ' ' . $path_url;
	}
	public function getMetaTitle($default){
		return $default;
	}
	public function getMetaDescription($default){
		return $default;
	}
	public function getBusquedaTitulo(){
		return 'titulo por defecto';
	}
	public function getBusquedaTituloLink(){
		return $this->getNodo()->getPathUrl();
	}
	public function getBusquedaDescripcion(){
		return 'descripcion por defecto';
	}
	public function getBusquedaEnlaces(){
		return null;
	}
}
?>