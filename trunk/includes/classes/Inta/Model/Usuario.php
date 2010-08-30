<?php

/**
 * Description of Usuario
 *
 * @author mati
 */
class Inta_Model_Usuario extends Core_Model_Abstract{
    public function init(){
        parent::init();
        $datafields = array(
            'id',
            'id_agencia',
            'activo',
            'username',
            'password',
            'nombre',
            'apellido',
            'email',
            'privilegios',
            'ultimo_acceso',
        );
        foreach($datafields as $datafield)
            $this->setData($datafield);
    }
    public function getDbTableName()
    {
        return 'inta_usuario';
    }
}
?>