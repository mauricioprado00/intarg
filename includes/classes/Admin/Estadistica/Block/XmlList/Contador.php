<?
class Admin_Estadistica_Block_XmlList_Contador extends Jqgrid_Block_XmlList{
	protected function loadData($page, $rows, $sidx, $sord, $comparator){
		/*hay que hacer que consulte la base de datos
			sidx = columna de ordenacion
			sord = orden de ordenacion (asc/desc)
		*/
		//$estadistica = new Granguia_Model_Estadistica();
		$estadistica = new Granguia_Model_View_Contador();
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