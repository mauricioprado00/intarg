<?
class Granguia_Model_Menu extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'texto',
			'title',
			'id_nodo',
			'url_externa',
			'activo',
			'screenblock',
			'orden',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'gg_menu';
	}
	private $_nodo;
	public function getNodo(){
		if(!$this->hasIdNodo()){
//			echo "no hay nodo: no hay id de nodo ".__LINE__;
			return null;
		}
		if(!($id_nodo = $this->getIdNodo())){
//			echo "no hay nodo: id vacio ".__LINE__;
			return null;
		}
		if(!isset($this->_nodo)||$this->_nodo->getId()!=$id_nodo){
			$nodo = new Granguia_Model_Nodo();
			$nodo->setId($id_nodo);
			if(!$nodo->load()){
//				echo "no hay nodo: id vacio ".__LINE__;
				return null;
			}
			$this->_nodo = $nodo;
		}
		return $this->_nodo;
	}
	public function esDescargable(){
		if($nodo = $this->getNodo()){
			if($tipo_nodo = $nodo->getTipoNodo()){
				return $tipo_nodo->getNombre()=='Archivo';
			}
		}
		return false;
	}
	public function canBeAjax(){
		if($nodo = $this->getNodo()){
			if($tipo_nodo = $nodo->getTipoNodo()){
				return $tipo_nodo->getNombre()!='Archivo';
			}
		}
		return false;
	}
	public function getInternalUrl(){
		if($nodo = $this->getNodo()){
			return $nodo->getPathUrl();
		}
		return false;
	}
}
?>