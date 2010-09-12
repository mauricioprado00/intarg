<?
class Admin_UsuarioActividad_Block_Listado extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('usuario_actividad/listado.phtml')
		;
	} 
	private $_usuario_actividads = null;
	private $_id_actividad = null;
	public function getUsuarioActividads(){
		if(isset($this->_usuario_actividads)){
			if($this->_id_actividad!=$this->getIdActividad()){
				$this->_usuario_actividads = null;
			}
		}
		if(!isset($this->_usuario_actividads)){
			$this->_usuario_actividads = c($usuario_actividad = new Inta_Model_UsuarioActividad())
				->setIdActividad($this->getIdActividad())
				->setWhere(Db_Helper::equal('id_actividad'))
				->search(null, null, nlll, null, get_class($usuario_actividad))
			;
			//echo Core_Helper::DebugVars($this->getData());
			$this->_id_actividad = $this->getIdActividad();
			if(!$this->_usuario_actividads)
				$this->_usuario_actividads = null;
		}
		return $this->_usuario_actividads;
	}
}
?>