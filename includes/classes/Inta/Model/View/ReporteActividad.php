<?php
class Inta_Model_View_ReporteActividad extends Inta_Db_Model_View_Abstract{

	protected function init(){
		parent::init($filtro_agencia = null, $filtro_audiencia = null, $filtro_resultado_esperado = null, $filtro_objetivos = null, $filtro_responsable = null, $filtro_proyecto = null, $filtro_usuarios_asociados = null);
		$view = new Inta_Db_Model_View_Generic();
                $view
                        ->addTable('inta_actividad',null,'a',array(
                                        'id_actividad'=>'a.id',
                                        'nombre_actividad'=>'a.nombre',
                        ))
                        ->addTable('inta_usuario','a.id_responsable = r.id', 'r',array(
                            'id_responsable' => 'r.id',
                            'nombre_responsable'=>'r.nombre',
                            'apellido_responsable'=>'r.apellido',
                        ))
                        ->addTable('inta_usuario_actividad','ua.id_actividad = a.id', 'ua',array())
                        ->addTable('inta_usuario','ua.id_usuario = ui.id', 'ui',array(
                            'id_usuario_involucrado' => 'ui.id',
                        ))
                        ->addTable('inta_agencia','r.id_agencia = ag.id', 'ag', array(
                            'id_agencia' => 'ag.id',
                            'nombre_agencia' => 'ag.nombre',
                        ))
                        ->addTable('inta_audiencia_actividad','a.id = aa.id_actividad', 'aa', array(
                            'id_audiencia' => 'aa.id',
                        ));
                        $this->addView($view, 'reporte_actividad', array(
                                'id_actividad',
                                'nombre_actividad',
                                'id_agencia',
                                'nombre_agencia',
                                'id_responsable',
                                'nombre_responsable',
                ));
	}
        protected function getColumnSelect(){
            $columnas = parent::getColumnSelect();
            return 'DISTINCT '.$columnas;
        }
}
?>