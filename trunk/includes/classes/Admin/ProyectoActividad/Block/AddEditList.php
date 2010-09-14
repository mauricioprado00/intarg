<?
class Admin_ProyectoActividad_Block_AddEditList extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('proyecto_actividad/add_edit_list.phtml')
		;
	}
	private $_listado_proyecto_actividads = null;
	private function crearBloqueListadoProyectoActividads($name=null){
		if(!$this->getIdActividad()){
			return;
		}
		$name = !isset($name)?$this->generateRandomId():$name;
		$this->_listado_proyecto_actividads = 
			$block = $this->appendBlock('<listado_proyecto_actividad name="'.$name.'" />', '', $this);
		$block->setIdActividad($this->getIdActividad());
		$block->setShowEmptyMessage(true);
		return $block;
	}
	protected function _prepareLayout(){
		$this->crearBloqueListadoProyectoActividads();
	}
	private function getListadoProyectoActividads(){
		return $this->_listado_proyecto_actividads;
	}
	protected function getListadoProyectoActividadsToHtml(){
		if(isset($this->_listado_proyecto_actividads)){
			return $this->_listado_proyecto_actividads->toHtml();
		}
		else{
			$block = $this->crearBloqueListadoProyectoActividads();
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