<?
class Admin_Documento_Block_AddEditList extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('documento/add_edit_list.phtml')
		;
	}
	private $_listado_documentos = null;
	private function crearBloqueListadoDocumentos($name=null){
		if(!$this->getTipoEntidad()||!$this->getIdEntidad()){
			return;
		}
		$name = !isset($name)?$this->generateRandomId():$name;
		$this->_listado_documentos = 
			$block = $this->appendBlock('<listado_documentos name="'.$name.'" />', '', $this);
		$block->setTipoEntidad($this->getTipoEntidad());
		$block->setIdEntidad($this->getIdEntidad());
		$block->setShowEmptyMessage(true);
		return $block;
	}
	protected function _prepareLayout(){
		$this->crearBloqueListadoDocumentos();
	}
	private function getListadoDocumentos(){
		return $this->_listado_documentos;
	}
	protected function getListadoDocumentosToHtml(){
		if(isset($this->_listado_documentos)){
			return $this->_listado_documentos->toHtml();
		}
		else{
			$block = $this->crearBloqueListadoDocumentos();
			if($block){
				return $block->toHtml();
			}
		}
		return '';
	}
	private $_id_entidad = null;
	private $_tipo_entidad = null;
}
?>