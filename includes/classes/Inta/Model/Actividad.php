<?php

/**
 *@referencia Responsable(id_responsable) Inta_Model_Usuario(id)
 *@listar Audiencia Inta_Model_AudienciaActividad
 *@listar Aspecto Inta_Model_AspectoActividad
 *@listar Estrategia Inta_Model_EstrategiaActividad
 *@listar Proyecto Inta_Model_ProyectoActividad
 *@listar ResultadoEsperado Inta_Model_ResultadoEsperadoActividad
*/
class Inta_Model_Actividad extends Core_Model_Abstract{
	public function getListAudiencias(){
		die("ok".__FILE__.__LINE__);
		$result = $this->getListAudiencia();
		$ltd = $this->_getListTypeData("Audiencia");
		var_dump($result);
		die("ok");
	}
    public function init(){
        parent::init();
        $datafields = array(
            'id',
            'id_responsable',
            'nombre',
            'ano',
            'porcentaje_cumplimiento',
            'porcentaje_tiempo',
            'presupuesto_estimado',
//            'fecha_cancelado',
//            'fecha_creacion',
//            'fecha_inicio',
//            'fecha_fin',
//estan pero no los pongo porque se administran solos con los triggers
            'mes_enero',
            'mes_febrero',
            'mes_marzo',
            'mes_abril',
            'mes_mayo',
            'mes_junio',
            'mes_julio',
            'mes_agosto',
            'mes_septiembre',
            'mes_octubre',
            'mes_noviembre',
            'mes_diciembre',
            'observaciones',
            'comentario',
            'motivo_atrasado',
            'motivo_cancelado',
            'estado',

        );
        foreach($datafields as $datafield)
            $this->setData($datafield);
//		$this->addAutofilterFieldInput('fecha_inicio', array('Mysql_Helper','filterDateInput'));
//		$this->addAutofilterFieldInput('fecha_fin', array('Mysql_Helper','filterDateInput'));
//		$this->addAutofilterFieldOutput('fecha_inicio', array('Mysql_Helper','filterDateOutput'));
//		$this->addAutofilterFieldOutput('fecha_fin', array('Mysql_Helper','filterDateOutput'));
    }
    public function esPlanificada(){
		return !$this->getEstado()||$this->getEstado()=='planificado';
	}
    public function esParcial(){
		return $this->getEstado()=='parcial';
	}
    public function esCancelada(){
		return $this->getEstado()=='cancelado';
	}
    public function esAtrasada(){
		return $this->getEstado()=='atrasado';
	}
    public function esRealizada(){
		return $this->getEstado()=='realizado';
	}
    public function getOpcionesEstadosPosiblesDeCambio(){
    	//todo: los que se crean solo se puedan crear como planificados, hay que hacer que los iniciados no puedan pasarse a planificados, que los cancelados no puedan pasarse a ningun otro estado, que los iniciados soo puedan pasarse a terminados o realizados,etc.
    	//return array('planificado' => 'Planificado','parcial' => 'Realizada Parcialmente','cancelado' => 'Cancelada', 'realizado' => 'Realizada');
    	if($this->esPlanificada()){
			return array('planificado' => 'Planificado','parcial' => 'Realizada Parcialmente','cancelado' => 'Cancelada');
		}
		elseif($this->esParcial()){
			return array('parcial' => 'Realizada Parcialmente','atrasado' => 'Atrasado','cancelado' => 'Cancelada', 'realizado' => 'Realizada');
		}
		elseif($this->esAtrasada()){
			return array('parcial' => 'Realizada Parcialmente','atrasado' => 'Atrasado','cancelado' => 'Cancelada', 'realizado' => 'Realizada');
		}
		elseif($this->esCancelada()){
			return array('cancelado' => 'Cancelada');
		}
		elseif($this->esRealizada()){
			return array('realizado' => 'Realizada');
		}
		return array('planificado' => 'Planificado','parcial' => 'Realizada Parcialmente','cancelado' => 'Cancelada', 'realizado' => 'Realizada');
	}
	public function canEditPorcentajeCumplimiento(){
		return $this->getEstado()&&$this->getEstado()=='parcial';
	}
	public function canEditCronograma(){
		return $this->esPlanificada();
	}
	public function canEditPorcentajeTiempo(){
		return $this->esPlanificada();
	}
	public function canEditPresupuestoEstimado(){
		return $this->esPlanificada();
	}
	public function canEditNombre(){
		return $this->esPlanificada();
	}
	public function canEditIdResponsable(){
		return $this->esPlanificada();
	}
	public function canEditAno(){
		return $this->esPlanificada();
	}
	public function canViewMotivoCancelado(){
		return $this->esCancelada();
	}
	public function canViewMotivoAtrasado(){
		return $this->esAtrasada();
	}
	public static function crearFiltroAgencia($id_agencia=null){
		if(!isset($id_agencia))
			$id_agencia = Admin_Helper::getInstance()->getIdAgenciaSeleccionada();
		return Db_Helper::custom('id IN (select distinct(rea.id_actividad)
from inta_objetivo as o
    inner join inta_resultado_esperado as re on re.id_objetivo = o.id
    inner join inta_resultado_esperado_actividad as rea on rea.id_resultado_esperado = re.id
	where o.id_agencia={%s})', $id_agencia);
	}
	protected function afterReplace($data){
		parent::afterReplace($data);
		if($this->hasId()){
			Inta_Model_ResultadoEsperadoActividad::flushSessionParaActividad($this->getId());
		}
	}
    public function getDbTableName()
    {
        return 'inta_actividad';
    }
    public function getPresupuestoProyectos(){
    	$presupuesto = 0;
		if($proyectos = $this->getListProyecto()){
			foreach($proyectos as $proyecto)
				$presupuesto += $proyecto->getMonto();
		}
		return $presupuesto;
	}
	public function getNombresProyectos(){
    	$nombres = array();
		if($proyectos = $this->getListProyecto()){
			foreach($proyectos as $proyecto_actividad)
				if($proyecto = $proyecto_actividad->getProyecto())
					$nombres[] = $proyecto->getNombre();
		}
		return implode(', ', $nombres);
	}
}
?>