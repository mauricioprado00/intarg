<?
/**
 *@referencia Agencia(id_agencia) Inta_Model_Agencia(id)
*/
class Inta_Model_View_ResultadoEsperado extends Inta_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Inta_Db_Model_View_Generic();
		$view
			->addTable('inta_resultado_esperado',null,'r',array(
				'resultado_esperado_id'=>'r.id',
				'resultado_esperado_id_objetivo'=>'r.id_objetivo',
				'resultado_esperado_descripcion'=>'r.descripcion',
			))
			->addTable('inta_objetivo', 'o.id= r.id_objetivo', 'o', array(
				'objetivo_id'=>'o.id',
				'objetivo_id_agencia'=>'o.id_agencia',
				'objetivo_nombre'=>'o.nombre',
//				'objetivo_descripcion'=>'r.descripcion',
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
		$this->addView($view, 'resultado_esperado', array(
				'id'=>'resultado_esperado_id',
				'id_objetivo'=>'resultado_esperado_id_objetivo',
				'descripcion'=>'resultado_esperado_descripcion',
				'objetivo_id',
				'objetivo_id_agencia',
				'objetivo_nombre',
//				'objetivo_descripcion',
				'agencia_id',
				'agencia_nombre',
//				'agencia_id_localidad',
//				'agencia_direccion',
//				'agencia_telefono',
//				'agencia_email',
		));
	}
	private $resultado_esperado;
	public function getResultadoEsperado(){
		if(!isset($this->resultado_esperado)||$this->getId()!=$this->resultado_esperado->getId()){
			$resultado_esperado = new Inta_Model_ResultadoEsperado();
			$resultado_esperado->setId($this->getId());
			if($resultado_esperado->load()){
				$this->resultado_esperado = $resultado_esperado;
			}
		}
		return $this->resultado_esperado;
	}
	
//	public static function crearFiltroAgencia2($id_agencia=null){
//		if(!isset($id_agencia))
//			$id_agencia = Admin_Helper::getInstance()->getIdAgencia();
//		return Db_Helper::custom('actividad_id IN (select distinct(rea.id_actividad)
//from inta_resultado_esperado as o
//    inner join inta_resultado_esperado as re on re.id_resultado_esperado = o.id
//    inner join inta_resultado_esperado_actividad as rea on rea.id_resultado_esperado = re.id
//	where o.id_agencia={%s})', $id_agencia);
//	}
//	public static function crearFiltroAgencia($id_agencia=null){
//		if(!isset($id_agencia))
//			$id_agencia = Admin_Helper::getInstance()->getIdAgencia();
//		return Db_Helper::custom('actividad_id IN (select distinct(a.id)
//from 
//	inta_resultado_esperado u 
//	inner join inta_actividad as a on a.id_responsable = u.id
//	WHERE u.id_agencia={%s})', $id_agencia);
//	}
}
?>