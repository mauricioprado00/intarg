<?
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
}
?>