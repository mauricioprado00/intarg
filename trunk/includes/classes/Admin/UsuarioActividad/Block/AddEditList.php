<?
class Admin_UsuarioActividad_Block_AddEditList extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('usuario_actividad/add_edit_list.phtml')
		;
	}
	private $_listado_usuario_actividads = null;
	private function crearBloqueListadoUsuarioActividads($name=null){
		if(!$this->getIdActividad()){
			return;
		}
		$name = !isset($name)?$this->generateRandomId():$name;
		$this->_listado_usuario_actividads = 
			$block = $this->appendBlock('<listado_usuario_actividad name="'.$name.'" />', '', $this);
		$block->setIdActividad($this->getIdActividad());
		$block->setShowEmptyMessage(true);
		return $block;
	}
	protected function _prepareLayout(){
		$this->crearBloqueListadoUsuarioActividads();
	}
	private function getListadoUsuarioActividads(){
		return $this->_listado_usuario_actividads;
	}
	protected function getListadoUsuarioActividadsToHtml(){
		if(isset($this->_listado_usuario_actividads)){
			return $this->_listado_usuario_actividads->toHtml();
		}
		else{
			$block = $this->crearBloqueListadoUsuarioActividads();
			if($block){
				return $block->toHtml();
			}
		}
		return '';
	}
	private $_id_actividad = null;
	private $_tipo_actividad = null;
}
?>