<?php
class Admin_Actividad_Block_XmlList_View extends Inta_Model_View_Actividad{
	public function __construct(){
		parent::__construct();
		$this->setData('responsable_nombre_completo','a');
		$this->addAutofilterFieldOutput('actividad_porcentaje_cumplimiento', array($this, 'outputPorcentaje'));
		$this->addAutofilterFieldOutput('actividad_porcentaje_tiempo', array($this, 'outputPorcentaje'));
		$this->addAutofilterFieldOutput('actividad_presupuesto_sum', array($this, 'moneda'));
		$this->addAutofilterFieldOutput('responsable_apellido', array($this, 'nombre_completo'));
		//$this->addAutofilterFieldOutput('actividad_presupuesto_estimado', array($this, 'outputPorcentaje'));
		$this->setXmlEntityTagname('actividad');
	}
	public function outputPorcentaje($value){
		return $value.'%';
	}
	public function moneda($value){
		return '$'.$value;
	}
	public function nombre_completo($value){
		//return implode(', ', array_keys($this->getData()));
		return $this->DataStrtr('', '{!responsable_apellido}, {!responsable_nombre}');
		//return $this->getData('responsable_apellido').', '.$this->getData('responsable_nombre'); 
	}
}
?>