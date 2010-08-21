<?
class Admin_Estadistica_Block_XmlList_ClickMinisitio extends Jqgrid_Block_XmlList{
	protected function loadData($page, $rows, $sidx, $sord, $comparator){
		$estadistica = new Granguia_Model_Estadistica_ClickMinisitio();
		if($comparator!=null){
			if($comparator instanceof Db_Compare_Between){
				$estadistica->setData($comparator->getField(), $comparator);
			}
			$estadistica->setWhere($comparator);
		}
		$datos = array();
		$total_items = $estadistica->searchCount();
		$cantidad_paginas = ceil($total_items/$rows);
		
		//$datos = $estadistica->search(null,'ASC',null,0,true,array('id', 'username', 'nombre', 'apellido', 'activo', 'privilegios', 'ultimo_acceso')); 
		$datos = $estadistica->search($sidx,$sord,$rows,$rows*($page-1),'Core_Object');

		$this->setPage($page);
		$this->setRecords($total_items);
		$this->setTotal($cantidad_paginas);

		return($datos);
	}
}
?>