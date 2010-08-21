<?
class Frontend_Barrio_Block_Menu extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('barrio/submenu.phtml');
	}
        private $aBarrios = null;
	public function getBarrios(){
            if(!isset($this->aBarrios)) {
                $tipoNodo = Granguia_Model_TipoNodo::getTipoNodoByName('Barrio');
                if(!$tipoNodo)
                {
                    return false;
		}
                $nodo = new Granguia_Model_Nodo();
                $nodo->setIdTipoNodo($tipoNodo->getId());
                $nodo->setEsActiva(1);
                $nodo->setWhere(Db_Helper::equal('id_tipo_nodo'),Db_Helper::equal('es_activa'));
                $aNodo = $nodo->search(null,'asc',null,0,'Granguia_Model_Nodo');
                $aBarrios = array();
                if($aNodo)
                    foreach($aNodo as $oNodo) {
                        $instance = $oNodo->getTypeInstance();
                        if($instance)
                            $this->aBarrios[] = $instance;
                    }

         
            }
            return $this->aBarrios;
	}
}
?>