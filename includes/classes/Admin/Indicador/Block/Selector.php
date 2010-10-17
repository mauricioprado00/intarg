<?
class Admin_Indicador_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		
		$this
			->setTextField('nombre')
			//->setTextFormat('%s, %s')
		;
		$this->addAutofilterFieldInput('tipo_indicador', array($this, '_filter_setIdTipoIndicador'));
	}
	protected function _filter_setIdTipoIndicador($id_tipo_indicador){
		$entity = $this->getEntityToList();
		if(isset($id_tipo_indicador)){
			$entity->setWhere(Db_Helper::equal('tipo_indicador', $id_tipo_indicador));
		}
		else{
			$tipo_audiencia = new Inta_Model_Indicador();
	    	$this
				->setEntityToList($tipo_audiencia)
			;
		} 
		return $id_tipo_indicador;
	}
    protected function _prepareLayout()
    {
		$tipo_audiencia = new Inta_Model_Indicador();
    	$this
			->setEntityToList($tipo_audiencia)
		;
        return $this;
    }
}
?>