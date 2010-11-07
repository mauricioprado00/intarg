<?php
class Inta_Model_View_ReporteActividad extends Inta_Db_Model_View_Abstract{

	protected function init(){
		parent::init($filtro_agencia = null, $filtro_audiencia = null, $filtro_resultado_esperado = null, $filtro_objetivos = null, $filtro_responsable = null, $filtro_proyecto = null, $filtro_usuarios_asociados = null);
		$view = new Inta_Db_Model_View_Generic();
                $view
                        ->addTable('inta_actividad',null,'a',array(
                                        'id_actividad'=>'a.id',
                                        'nombre_actividad'=>'a.nombre',
                                        'estado' => 'a.estado',
                                        'ano' => 'a.ano',
                                        'mes_enero' => 'a.mes_enero',
                                        'mes_febrero' => 'a.mes_febrero',
                                        'mes_marzo' => 'a.mes_marzo',
                                        'mes_abril' => 'a.mes_abril',
                                        'mes_mayo' => 'a.mes_mayo',
                                        'mes_junio' => 'a.mes_junio',
                                        'mes_julio' => 'a.mes_julio',
                                        'mes_agosto' => 'a.mes_agosto',
                                        'mes_septiembre' => 'a.mes_septiembre',
                                        'mes_octubre' => 'a.mes_octubre',
                                        'mes_noviembre' => 'a.mes_noviembre',
                                        'mes_diciembre' => 'a.mes_diciembre',
                                        'fecha_inicio' => 'a.fecha_inicio',
                                        'fecha_fin' => 'a.fecha_fin',
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
                        ))
                        ->addTable('inta_resultado_esperado_actividad','a.id = rea.id_actividad', 'rea', array())
                        ->addTable('inta_resultado_esperado','rea.id_resultado_esperado = re.id', 're', array(
                            'resultado_esperado' => 're.descripcion',
                        ))
                        ->addTable('inta_objetivo','re.id_objetivo = o.id', 'o', array(
                            'objetivo' => 'o.nombre',
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