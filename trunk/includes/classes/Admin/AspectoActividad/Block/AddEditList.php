<?
class Admin_AspectoActividad_Block_AddEditList extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('aspecto_actividad/add_edit_list.phtml')
		;
	}
	private $_listado_aspecto_actividads = null;
	private function crearBloqueListadoAspectoActividads($name=null){
		if(!$this->getIdActividad()){
			return;
		}
		$name = !isset($name)?$this->generateRandomId():$name;
		$this->_listado_aspecto_actividads = 
			$block = $this->appendBlock('<listado_aspecto_actividad name="'.$name.'" />', '', $this);
		$block->setIdActividad($this->getIdActividad());
		$block->setShowEmptyMessage(true);
		return $block;
	}
	protected function _prepareLayout(){
		$this->crearBloqueListadoAspectoActividads();
	}
	private function getListadoAspectoActividads(){
		return $this->_listado_aspecto_actividads;
	}
	protected function getListadoAspectoActividadsToHtml(){
		if(isset($this->_listado_aspecto_actividads)){
			return $this->_listado_aspecto_actividads->toHtml();
		}
		else{
			$block = $this->crearBloqueListadoAspectoActividads();
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