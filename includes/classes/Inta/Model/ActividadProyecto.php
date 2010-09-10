<?php

/**
 * Description of ActividadProyecto
 *
 * @author mati
 */
class Inta_Model_ActividadProyecto extends Core_Model_Abstract{
    public function init(){
        parent::init();
        $datafields = array(
            'id',
            'id_actividad',
            'id_proyecto',
            'monto',
        );
        foreach($datafields as $datafield)
            $this->setData($datafield);
    }
    public function getDbTableName()
    {
        return 'inta_actividad_proyecto';
    }
}
?>