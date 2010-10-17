<?
class Admin_MedioVerificacion_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		
		$this
			->setTextField('nombre')
			//->setTextFormat('%s, %s')
		;
		$this->addAutofilterFieldInput('id_indicador', array($this, '_filter_setIdIndicador'));
	}
	protected function _filter_setIdIndicador($id_id_indicador){
		$entity = $this->getEntityToList();
		if(isset($id_id_indicador)){
			$entity->setWhere(Db_Helper::equal('id_indicador', $id_id_indicador));
		}
		else{
			$tipo_audiencia = new Inta_Model_MedioVerificacion();
	    	$this
				->setEntityToList($tipo_audiencia)
			;
		} 
		return $id_id_indicador;
	}
    protected function _prepareLayout()
    {
		$tipo_audiencia = new Inta_Model_MedioVerificacion();
    	$this
			->setEntityToList($tipo_audiencia)
		;
        return $this;
    }
}
?>