<?
class Admin_AudienciaActividad_Block_AddEditList extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('audiencia_actividad/add_edit_list.phtml')
		;
	}
	private $_listado_audiencia_actividads = null;
	private function crearBloqueListadoAudienciaActividads($name=null){
		if(!$this->getIdActividad()){
			return;
		}
		$name = !isset($name)?$this->generateRandomId():$name;
		$this->_listado_audiencia_actividads = 
			$block = $this->appendBlock('<listado_audiencia_actividad name="'.$name.'" />', '', $this);
		$block->setIdActividad($this->getIdActividad());
		$block->setShowEmptyMessage(true);
		return $block;
	}
	protected function _prepareLayout(){
		$this->crearBloqueListadoAudienciaActividads();
	}
	private function getListadoAudienciaActividads(){
		return $this->_listado_audiencia_actividads;
	}
	protected function getListadoAudienciaActividadsToHtml(){
		if(isset($this->_listado_audiencia_actividads)){
			return $this->_listado_audiencia_actividads->toHtml();
		}
		else{
			$block = $this->crearBloqueListadoAudienciaActividads();
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