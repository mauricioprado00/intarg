<?
class Admin_Documento_Block_Listado extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('documento/listado.phtml')
		;
	} 
	private $_documentos = null;
	private $_id_entidad = null;
	private $_tipo_entidad = null;
	public function getDocumentos(){
		if(isset($this->_documentos)){
			if($this->_id_entidad!=$this->getIdEntidad()||$this->_tipo_entidad!=$this->getTipoEntidad()){
				$this->_documentos = null;
			}
		}
		if(!isset($this->_documentos)){
			$this->_documentos = c($documento = new Inta_Model_Documento())
				->setIdEntidad($this->getIdEntidad())
				->setTipoEntidad($this->getTipoEntidad())
				->setWhere(Db_Helper::equal('id_entidad'), Db_Helper::equal('tipo_entidad'))
				->search()
			;
			//echo $documento->searchGetSql();
			//echo Core_Helper::DebugVars($this->getData());
			$this->_id_entidad = $this->getIdEntidad();
			$this->_tipo_entidad = $this->getTipoEntidad();
			if(!$this->_documentos)
				$this->_documentos = null;
		}
		return $this->_documentos;
	}
}
?>