<?php
class Inta_Model_Reporte_View_ActividadByObjetivo extends Inta_Db_Model_View_Abstract{
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
			->addTable('inta_resultado_esperado_actividad','rea.id_actividad = r.id_actividad','rea',array(), 'inner')
			->addTable('inta_resultado_esperado','re.id = rea.id_resultado_esperado','re',array(
				'id_resultado_esperado'=>'re.id',
//				'descripcion_resultado_esperado'=>'re.descripcion',
			), 'inner')
			//->addTable('inta_resultado_esperado_actividad','rea.id_actividad = r.id_actividad','rea',array(), 'inner')
			->addTable('inta_objetivo','o.id = re.id_objetivo','o',array(
				'id_objetivo'=>'o.id',
//				'nombre_objetivo'=>'o.nombre',
//				'descripcion_objetivo'=>'o.descripcion',
//				'id_agencia'=>'o.id_agencia',
			), 'inner')
		;
		$this->addView($view, 'resultado', array(
				//r
				'id'=>'id',
				'id_usuario_logeado',
				'id_agencia',
				'nombre_agencia',
				'id_actividad',
				'nombre_actividad',
				'id_responsable',
				'nombre_responsable',
				
				//re
				'id_resultado_esperado',
				//'descripcion_resultado_esperado',
				
				//o
				'id_objetivo',
//				'nombre_objetivo',
//				'descripcion_objetivo',
//				'id_agencia',
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