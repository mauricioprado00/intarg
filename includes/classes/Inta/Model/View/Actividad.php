<?
class Inta_Model_View_Actividad extends Inta_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Inta_Db_Model_View_Generic();
		$view
			->addTable('inta_actividad',null,'a',array(
				'actividad_id'=>'a.id',
				'actividad_nombre'=>'a.nombre',
				'actividad_ano'=>'a.ano',
				'actividad_porcentaje_cumplimiento'=>'a.porcentaje_cumplimiento',
				'actividad_porcentaje_tiempo'=>'a.porcentaje_tiempo',
				'actividad_presupuesto_estimado'=>'a.presupuesto_estimado',
				'actividad_observaciones'=>'a.observaciones',
				'actividad_estado'=>'a.estado',
			))
			->addTable('inta_usuario', 'a.id_responsable= r.id', 'r', array(
				'responsable_id_agencia'=>'r.id_agencia',
				'responsable_id'=>'r.id',
				'responsable_nombre'=>'r.nombre',
				'responsable_apellido'=>'r.apellido',
			))
			->addTable('inta_agencia', 'ag.id= r.id_agencia', 'ag', array(
				'agencia_id'=>'ag.id',
				'agencia_nombre'=>'ag.nombre',
//				'agencia_id_localidad'=>'ag.id_localidad',
//				'agencia_direccion'=>'ag.direccion',
//				'agencia_telefono'=>'ag.telefono',
//				'agencia_email'=>'ag.email',
			))
			->addTable('inta_proyecto_actividad', 'a.id=p.id_actividad', 'p', array(
				'proyecto_monto'=>'p.monto',
			))
			->addColumn('SUM(p.monto)', 'actividad_presupuesto_sum')
			->setGroupBy('actividad_id')
			//->addColumn('IF('.$this->getDb()->nameToString('pi.importar').'=\'1\',\'si\',\'no\')', 'importar')
			//->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			//->addColumn('count(pi.id)', 'cantidad_productos_importados')
			//->setGroupBy('i.id')
		;	
		$this->addView($view, 'actividad', array(
				'id'=>'actividad_id',
				'actividad_id',
				'actividad_nombre',
				'actividad_ano',
				'actividad_porcentaje_cumplimiento',
				'actividad_porcentaje_tiempo',
				'actividad_presupuesto_estimado',
				'actividad_observaciones',
				'actividad_estado',
				'responsable_id',
				'responsable_nombre',
				'responsable_apellido',
				'actividad_presupuesto_sum',
				'responsable_id_agencia',
				'agencia_id',
				'agencia_nombre',
//				'agencia_id_localidad',
//				'agencia_direccion',
//				'agencia_telefono',
//				'agencia_email',
		));
	}
	public static function crearFiltroAgencia2($id_agencia=null){
		if(!isset($id_agencia))
			$id_agencia = Admin_Helper::getInstance()->getIdAgenciaSeleccionada();
		return Db_Helper::custom('actividad_id IN (select distinct(rea.id_actividad)
from inta_objetivo as o
    inner join inta_resultado_esperado as re on re.id_objetivo = o.id
    inner join inta_resultado_esperado_actividad as rea on rea.id_resultado_esperado = re.id
	where o.id_agencia={%s})', $id_agencia);
	}
	public static function crearFiltroAgencia($id_agencia=null){
		if(!isset($id_agencia))
			$id_agencia = Admin_Helper::getInstance()->getIdAgenciaSeleccionada();
		return Db_Helper::custom('actividad_id IN (select distinct(a.id)
from 
	inta_usuario u 
	inner join inta_actividad as a on a.id_responsable = u.id
	WHERE u.id_agencia={%s})', $id_agencia);
	}
}
?>