<?php

/**
 * Description of UsuarioActividad
 *
 * @author mati
 */
class Inta_Model_UsuarioActividad extends Core_Model_Abstract{
    public function init(){
        parent::init();
        $datafields = array(
            'id',
            'id_usuario',
            'id_actividad',
        );
        foreach($datafields as $datafield)
            $this->setData($datafield);
    }
    public function getDbTableName()
    {
        return 'inta_usuario_actividad';
    }
}
?>