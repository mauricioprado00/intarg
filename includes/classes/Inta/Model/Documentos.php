<?php

/**
 * Description of Documentos
 *
 * @author mati
 */
class Inta_Model_Documentos extends Core_Model_Abstract{
    public function init(){
        parent::init();
        $datafields = array(
            'id',
            'id_entidad',
            'tipo_entidad',
            'path',
        );
        foreach($datafields as $datafield)
            $this->setData($datafield);
    }
    public function getDbTableName()
    {
        return 'inta_documentos';
    }
}
?>