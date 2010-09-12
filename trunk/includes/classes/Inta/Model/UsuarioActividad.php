<?
class Inta_Model_UsuarioActividad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_actividad',
			'id_usuario',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	private $_actividad = null;
	public function getActividad(){
		if(!$this->hasIdActividad()||!($id_actividad = $this->getIdActividad()))
			return null;
		if(!isset($this->_actividad)||$this->_actividad->getId()!=$id_actividad){
			$actividad = new Inta_Model_Actividad();
			$actividad->setId($id_actividad);
			if(!$actividad->load())
				$actividad = null;
			$this->_actividad = $actividad;
		}
		return $this->_actividad;
	}
	private $_usuario = null;
	public function getUsuario(){
		if(!$this->hasIdUsuario()||!($id_usuario = $this->getIdUsuario()))
			return null;
		if(!isset($this->_usuario)||$this->_usuario->getId()!=$id_usuario){
			$usuario = new Inta_Model_Usuario();
			$usuario->setId($id_usuario);
			if(!$usuario->load())
				$usuario = null;
			$this->_usuario = $usuario;
		}
		return $this->_usuario;
	}

	public function getDbTableName() 
	{
		return 'inta_usuario_actividad';
	}
}
?>