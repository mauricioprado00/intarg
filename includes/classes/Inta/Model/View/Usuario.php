<?
class Inta_Model_View_Usuario extends Inta_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Inta_Db_Model_View_Generic();
		$view
			->addTable('inta_usuario',null,'u',array(
	            'usuario_id'=>'u.id',
	            'usuario_id_agencia'=>'u.id_agencia',
	            'usuario_activo'=>'u.activo',
	            'usuario_username'=>'u.username',
	            'usuario_password'=>'u.password',
	            'usuario_nombre'=>'u.nombre',
	            'usuario_apellido'=>'u.apellido',
	            'usuario_email'=>'u.email',
	            'usuario_privilegios'=>'u.privilegios',
	            'usuario_ultimo_acceso'=>'u.ultimo_acceso',
			))
			->addTable('inta_agencia', 'a.id= u.id_agencia', 'a', array(
				'agencia_id'=>'a.id',
				'agencia_nombre'=>'a.nombre',
				'agencia_id_localidad'=>'a.id_localidad',
				'agencia_direccion'=>'a.direccion',
				'agencia_telefono'=>'a.telefono',
				'agencia_email'=>'a.email',
			))
			//->addColumn('IF('.$this->getDb()->nameToString('pi.importar').'=\'1\',\'si\',\'no\')', 'importar')
			//->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			//->addColumn('count(pi.id)', 'cantidad_productos_importados')
			//->setGroupBy('i.id')
		;	
		$this->addView($view, 'actividad', array(
	            'id'=>'usuario_id',
	            'id_agencia'=>'usuario_id_agencia',
	            'activo'=>'usuario_activo',
	            'username'=>'usuario_username',
	            'password'=>'usuario_password',
	            'nombre'=>'usuario_nombre',
	            'apellido'=>'usuario_apellido',
	            'email'=>'usuario_email',
	            'privilegios'=>'usuario_privilegios',
	            'ultimo_acceso'=>'usuario_ultimo_acceso',
				'agencia_id',
				'agencia_nombre',
				'agencia_id_localidad',
				'agencia_direccion',
				'agencia_telefono',
				'agencia_email',
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
//	inta_usuario u 
//	inner join inta_actividad as a on a.id_responsable = u.id
//	WHERE u.id_agencia={%s})', $id_agencia);
//	}
}
?>