<?
class Admin_EstrategiaActividad_Block_AddEditList extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('estrategia_actividad/add_edit_list.phtml')
		;
	}
	private $_listado_estrategia_actividads = null;
	private function crearBloqueListadoEstrategiaActividads($name=null){
		if(!$this->getIdActividad()){
			return;
		}
		$name = !isset($name)?$this->generateRandomId():$name;
		$this->_listado_estrategia_actividads = 
			$block = $this->appendBlock('<listado_estrategia_actividad name="'.$name.'" />', '', $this);
		$block->setIdActividad($this->getIdActividad());
		$block->setShowEmptyMessage(true);
		return $block;
	}
	protected function _prepareLayout(){
		$this->crearBloqueListadoEstrategiaActividads();
	}
	private function getListadoEstrategiaActividads(){
		return $this->_listado_estrategia_actividads;
	}
	protected function getListadoEstrategiaActividadsToHtml(){
		if(isset($this->_listado_estrategia_actividads)){
			return $this->_listado_estrategia_actividads->toHtml();
		}
		else{
			$block = $this->crearBloqueListadoEstrategiaActividads();
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