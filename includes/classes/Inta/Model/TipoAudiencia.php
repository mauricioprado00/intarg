<?php

/**
 * Description of TipoAudiencia
 *
 * @author mati
 */
class Inta_Model_TipoAudiencia extends Core_Model_Abstract{
    public function init(){
        parent::init();
        $datafields = array(
            'id',
            'nombre',
        );
        foreach($datafields as $datafield)
            $this->setData($datafield);
    }
    public function getDbTableName()
    {
        return 'inta_tipo_audiencia';
    }
}
?>