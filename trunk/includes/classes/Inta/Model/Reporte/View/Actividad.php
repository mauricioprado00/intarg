<?php
class Inta_Model_Reporte_View_Actividad extends Inta_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Inta_Db_Model_View_Generic();
		$view
			->addTable('inta_resultado_actividad',null,'r',array(
				'id'=>'r.id',
				'id_usuario_logeado'=>'r.id_usuario_logeado',
				'id_agencia'=>'r.id_agencia',
				'nombre_agencia'=>'r.nombre_agencia',
				'id_actividad'=>'r.id_actividad',
				'nombre_actividad'=>'r.nombre_actividad',
				'id_responsable'=>'r.id_responsable',
				'nombre_responsable'=>'r.nombre_responsable',
			))
			->addTable('inta_estrategia_actividad','ea.id = (select ea2.id from inta_estrategia_actividad ea2 where ea2.id_actividad = r.id_actividad limit 1)','ea',array()/*, 'inner'*/)
			->addTable('inta_estrategia','e.id = ea.id_estrategia','e',array(
				'id_estrategia'=>'e.id',
				'nombre_estrategia'=>'e.nombre',
			)/*, 'inner'*/)
		;
		$this->addView($view, 'resultado', array(
				'id'=>'id',
				'id_usuario_logeado',
				'id_agencia',
				'nombre_agencia',
				'id_actividad',
				'nombre_actividad',
				'id_responsable',
				'nombre_responsable',
				'nombre_estrategia',
				'id_estrategia',
		));
	}
	protected function getXmlEntityTagname(){
		return 'inta_actividad';
		//return str_replace('inta_', '', $this->getDbTableName());
	}
	public function getActividad(){
		$actividad = new Inta_Model_Actividad();
		$actividad->setId($this->getData('id_actividad'));
		if(!$actividad->load())
			return null;
		return $actividad;
	}
}
?>