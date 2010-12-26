<?
/**
 *@referencia Agencia(id_agencia) Inta_Model_Agencia(id)
*/
class Inta_Model_View_Objetivo extends Inta_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Inta_Db_Model_View_Generic();
		$view
			->addTable('inta_objetivo',null,'o',array(
				'objetivo_id'=>'o.id',
				'objetivo_id_agencia'=>'o.id_agencia',
				'objetivo_nombre'=>'o.nombre',
				'objetivo_descripcion'=>'o.descripcion',
			))
			->addTable('inta_agencia', 'a.id= o.id_agencia', 'a', array(
				'agencia_id'=>'a.id',
				'agencia_nombre'=>'a.nombre',
//				'agencia_id_localidad'=>'a.id_localidad',
//				'agencia_direccion'=>'a.direccion',
//				'agencia_telefono'=>'a.telefono',
//				'agencia_email'=>'a.email',
			))
		;	
		$this->addView($view, 'objetivo', array(
				'id'=>'objetivo_id',
				'id_agencia'=>'objetivo_id_agencia',
				'nombre'=>'objetivo_nombre',
				'descripcion'=>'objetivo_descripcion',
				'agencia_id',
				'agencia_nombre',
//				'agencia_id_localidad',
//				'agencia_direccion',
//				'agencia_telefono',
//				'agencia_email',
		));
	}
	private $objetivo;
	public function getObjetivo(){
		if(!isset($this->objetivo)||$this->getId()!=$this->objetivo->getId()){
			$objetivo = new Inta_Model_Objetivo();
			$objetivo->setId($this->getId());
			if($objetivo->load()){
				$this->objetivo = $objetivo;
			}
		}
		return $this->objetivo;
	}
	public static function crearFiltroAgencia($id_agencia=null){
		if(!isset($id_agencia))
			$id_agencia = Admin_Helper::getInstance()->getIdAgenciaSeleccionada();
		return Db_Helper::equal('objetivo_id_agencia', $id_agencia);
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
//	inta_objetivo u 
//	inner join inta_actividad as a on a.id_responsable = u.id
//	WHERE u.id_agencia={%s})', $id_agencia);
//	}
}
?>