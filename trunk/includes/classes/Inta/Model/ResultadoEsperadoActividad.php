<?php

/**
 * Description of ResultadoEsperadoActividad
 *
 * @author mati
 */
class Inta_Model_ResultadoEsperadoActividad extends Core_Model_Abstract{
    public function init(){
        parent::init();
        $datafields = array(
            'id',
            'id_actividad',
            'id_resultado_esperado',
        );
        foreach($datafields as $datafield)
            $this->setData($datafield);
    }
    public function getDbTableName()
    {
        return 'inta_resultado_esperado_actividad';
    }
}
?>