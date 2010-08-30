<?php

/**
 * Description of Actividad
 *
 * @author mati
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
    }
    public function getDbTableName()
    {
        return 'inta_actividad';
    }
}
?>