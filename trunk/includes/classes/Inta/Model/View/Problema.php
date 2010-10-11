<?
class Inta_Model_View_Problema extends Inta_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Inta_Db_Model_View_Generic();
		$view
			->addTable('inta_problema',null,'p',array(
				'problema_id'=>'p.id',
				'problema_id_objetivo'=>'p.id_objetivo',
				'problema_id_audiencia'=>'p.id_audiencia',
				'problema_nombre'=>'p.nombre',
				'problema_importancia_economica'=>'p.importancia_economica',
				'problema_impacto_ambiental'=>'p.impacto_ambiental',
				'problema_importancia_social'=>'p.importancia_social',
				'problema_familias_implicadas'=>'p.familias_implicadas',
				'problema_valor_agregado_potencial'=>'p.valor_agregado_potencial',
				'problema_impacto_desarrollo'=>'p.impacto_desarrollo',
				'problema_prioridad'=>'p.prioridad',
			))
			->addTable('inta_audiencia', 'a.id= p.id_audiencia', 'a', array(
				'audiencia_id'=>'a.id',
//				'audiencia_id_agencia'=>'a.id_agencia',
//				'audiencia_id_tipo_audiencia'=>'a.id_tipo_audiencia',
				'audiencia_nombre'=>'a.nombre',
			))
			->addTable('inta_agencia', 'ag.id= a.id_agencia', 'ag', array(
				'agencia_id'=>'ag.id',
				'agencia_nombre'=>'ag.nombre',
//				'agencia_id_localidad'=>'au.id_localidad',
//				'agencia_direccion'=>'au.direccion',
//				'agencia_telefono'=>'au.telefono',
//				'agencia_email'=>'au.email',
			))
			//->addColumn('IF('.$this->getDb()->nameToString('pi.importar').'=\'1\',\'si\',\'no\')', 'importar')
			//->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			//->addColumn('count(pi.id)', 'cantidad_productos_importados')
			//->setGroupBy('i.id')
		;	
		$this->addView($view, 'problema', array(
				'id'=>'problema_id',
				'id_objetivo'=>'problema_id_objetivo',
				'id_audiencia'=>'problema_id_audiencia',
				'nombre'=>'problema_nombre',
				'importancia_economica'=>'problema_importancia_economica',
				'impacto_ambiental'=>'problema_impacto_ambiental',
				'importancia_social'=>'problema_importancia_social',
				'familias_implicadas'=>'problema_familias_implicadas',
				'valor_agregado_potencial'=>'problema_valor_agregado_potencial',
				'impacto_desarrollo'=>'problema_impacto_desarrollo',
				'prioridad'=>'problema_prioridad',
				'audiencia_id',
//				'audiencia_id_agencia',
//				'audiencia_id_tipo_audiencia',
				'audiencia_nombre',
				'agencia_id',
				'agencia_nombre',
//				'agencia_id_localidad',
//				'agencia_direccion',
//				'agencia_telefono',
//				'agencia_email',
		));
	}
//	public static function crearFiltroAgencia2($id_agencia=null){
//		if(!isset($id_agencia))
//			$id_agencia = Admin_Helper::getInstance()->getIdAgencia();
//		return Db_Helper::custom('actividad_id IN (select distinct(rea.id_actividad)
//from inta_objetivo as o
//    inner join inta_resultado_esperado as re on re.id_objetivo = o.id
//    inner join inta_resultado_esperado_actividad as rea on rea.id_resultado_esperado = re.id
//	where o.id_agencia={%s})', $id_agencia);
//	}
//	public static function crearFiltroAgencia($id_agencia=null){
//		if(!isset($id_agencia))
//			$id_agencia = Admin_Helper::getInstance()->getIdAgencia();
//		return Db_Helper::custom('actividad_id IN (select distinct(a.id)
//from 
//	inta_problema u 
//	inner join inta_actividad as a on a.id_responsable = u.id
//	WHERE u.id_agencia={%s})', $id_agencia);
//	}
}
?>