<?
/**
 *@listar Usuario Inta_Model_Usuario
*/
class Inta_Model_Agencia extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'nombre',
			'id_localidad',
			'direccion',
			'telefono',
			'email',
			'agentes',
			'descripcion',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'inta_agencia';
	}
	public function getListProblema(){
		$problema = new Inta_Model_Problema();
		$problema->setWhere(Inta_Model_Problema::crearFiltroAgencia($this->getId()));
		return $problema->search(null, 'ASC', null, 0, get_class($problema));
	}
	public function getListDocumento(){
		$documento = new Inta_Model_Documento();
		$documento
			->setTipoEntidad('agencia')
			->setIdEntidad($this->getId())
		;
		$documento->setWhere(Db_Helper::like('tipo_entidad'), Db_Helper::like('id_entidad'));
		return $documento->search(null, 'ASC', null, 0, 'Inta_Model_Documento');
	}
	public function getDocumentoCaracterizacion(){
		$documento = new Inta_Model_Documento();
		$documento
			->setTipoEntidad('agencia')
			->setIdEntidad($this->getId())
			->setPath('caractz%')
		;
		$wheres = array(
			Db_Helper::equal('tipo_entidad'), 
			Db_Helper::equal('id_entidad')
		);
		
		$search_wheres = $wheres;
		$search_wheres[] = Db_Helper::like('path','%',null,'.docx');
		$documento->setWhereByArray($search_wheres);
		if(!$documento->searchCount()){
			$documento->setPath(null);
			if(!$documento->searchCount())
				return null;
		}
		$res = $documento->search(null, 'ASC', null, 0, 'Inta_Model_Documento');
		if(!$res)
			return null;
		return array_shift($res);
	}
	public function getDocumentoCaracterizacionToken(){
		$doc = $this->getDocumentoCaracterizacion();
		if(!$doc)
			return null;
		return new Core_Object(array('token'=>md5($doc->getPath()).'.docx','id'=>'rCDocId'.$doc->getId()));
	}
}
?>