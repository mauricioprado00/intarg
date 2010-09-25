<?php

/**
 *@referencia Responsable(id_responsable) Inta_Model_Usuario(id)
 *@listar Audiencia Inta_Model_AudienciaActividad
*/
class Inta_Model_Actividad extends Core_Model_Abstract{
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
            'fecha_inicio',
            'fecha_fin',
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
            'estado',

        );
        foreach($datafields as $datafield)
            $this->setData($datafield);
		$this->addAutofilterFieldInput('fecha_inicio', array('Mysql_Helper','filterDateInput'));
		$this->addAutofilterFieldInput('fecha_fin', array('Mysql_Helper','filterDateInput'));
		$this->addAutofilterFieldOutput('fecha_inicio', array('Mysql_Helper','filterDateOutput'));
		$this->addAutofilterFieldOutput('fecha_fin', array('Mysql_Helper','filterDateOutput'));
    }
    public function getDbTableName()
    {
        return 'inta_actividad';
    }
}
?>