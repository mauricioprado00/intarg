<?
class Inta_Model_View_MedioVerificacion extends Inta_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Inta_Db_Model_View_Generic();
		$view
			->addTable('inta_medio_verificacion',null,'m',array(
				'medio_verificacion_id'=>'m.id',
				'medio_verificacion_id_indicador'=>'m.id_indicador',
				'medio_verificacion_nombre'=>'m.nombre',
			))
			->addTable('inta_indicador', 'i.id= m.id_indicador', 'i', array(
				'indicador_id'=>'i.id',
				'indicador_nombre'=>'i.nombre',
				'indicador_tipo_indicador'=>'i.tipo_indicador',
			))
			//->addColumn('IF('.$this->getDb()->nameToString('pi.importar').'=\'1\',\'si\',\'no\')', 'importar')
			//->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			//->addColumn('count(pi.id)', 'cantidad_productos_importados')
			//->setGroupBy('i.id')
		;	
		$this->addView($view, 'actividad', array(
				'id'=>'medio_verificacion_id',
				'id_indicador'=>'medio_verificacion_id_indicador',
				'nombre'=>'medio_verificacion_nombre',
				'indicador_id',
				'indicador_nombre',
				'indicador_tipo_indicador',

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
//	inta_medio_verificacion u 
//	inner join inta_actividad as a on a.id_responsable = u.id
//	WHERE u.id_agencia={%s})', $id_agencia);
//	}
}
?>