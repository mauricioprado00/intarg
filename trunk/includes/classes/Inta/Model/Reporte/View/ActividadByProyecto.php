<?php
class Inta_Model_Reporte_View_ActividadByProyecto extends Inta_Db_Model_View_Abstract{
	//protected function init($valor=null){
	public function Inta_Model_Reporte_View_ActividadByProyecto($cohibir_repeticiones=true){
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
		;
		if($cohibir_repeticiones){
			/** sin items repetidos **/
			/**/
			$view->addTable('inta_proyecto_actividad','pa.id = (select pa2.id from inta_proyecto_actividad pa2 where pa2.id_actividad = r.id_actividad limit 1)','pa',array(
				'monto_proyecto'=>'pa.monto',
			));
			/**/
		}
		else{
			/** con items repetidos **/
			/**/
			$view->addTable('inta_proyecto_actividad','pa.id_actividad = r.id_actividad','pa',array(
				'monto_proyecto'=>'pa.monto',
			));
			/**/
			
		}
		$view	
			->addTable('inta_proyecto','p.id = pa.id_proyecto','p',array(
				'id_proyecto'=>'p.id',
				'nombre_proyecto'=>'p.nombre',
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
				'monto_proyecto',
				'nombre_proyecto',
				'id_proyecto',
		));
	}
	protected function getXmlEntityTagname(){
		return 'resultado_actividad';
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