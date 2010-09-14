<?
class Admin_ResultadoEsperadoActividad_Block_AddEditList extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('resultado_esperado_actividad/add_edit_list.phtml')
		;
	}
	private $_listado_resultado_esperado_actividads = null;
	private function crearBloqueListadoResultadoEsperadoActividads($name=null){
		if(!$this->getIdActividad()){
			return;
		}
		$name = !isset($name)?$this->generateRandomId():$name;
		$this->_listado_resultado_esperado_actividads = 
			$block = $this->appendBlock('<listado_resultado_esperado_actividad name="'.$name.'" />', '', $this);
		$block->setIdActividad($this->getIdActividad());
		$block->setShowEmptyMessage(true);
		return $block;
	}
	protected function _prepareLayout(){
		$this->crearBloqueListadoResultadoEsperadoActividads();
	}
	private function getListadoResultadoEsperadoActividads(){
		return $this->_listado_resultado_esperado_actividads;
	}
	protected function getListadoResultadoEsperadoActividadsToHtml(){
		if(isset($this->_listado_resultado_esperado_actividads)){
			return $this->_listado_resultado_esperado_actividads->toHtml();
		}
		else{
			$block = $this->crearBloqueListadoResultadoEsperadoActividads();
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