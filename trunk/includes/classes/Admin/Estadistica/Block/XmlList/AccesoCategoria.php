<?
class Admin_Estadistica_Block_XmlList_AccesoCategoria extends Jqgrid_Block_XmlList{
	protected function loadData($page, $rows, $sidx, $sord, $comparator){
		/*hay que hacer que consulte la base de datos
			sidx = columna de ordenacion
			sord = orden de ordenacion (asc/desc)
		*/
		//$estadistica = new Granguia_Model_Estadistica();
		$estadistica = new Granguia_Model_Estadistica_AccesoCategoria();
		if($comparator!=null){
			if($comparator instanceof Db_Compare_Between){
				$estadistica->setData($comparator->getField(), $comparator);
			}
			$estadistica->setWhere($comparator);
		}
		$datos = array();
		$total_items = $estadistica->searchCount();
		$cantidad_paginas = ceil($total_items/$rows);
		
		//aca consultariamos la base de datos
//		$left = $total_items - ($page-1) * $rows;
//		$left = $left>$rows?$rows:$left;
//		for($i=0;$i<$left;$i++){
//			$obj = new Core_Object();
//			$obj->setId($i+($page-1)*$rows)
//				->setUsername('nombre de usuario')
//				->setNombre('nombre')
//				->setApellido('apellido')
//				->setActivo(true)
//				->setPrivilegios(rand(0,100)<50?'Total':'Vista')
//				->setUltimoAcceso('22/12/2008')
//			;
//			$datos[] = $obj;
//		}
		
		//$datos = $estadistica->search(null,'ASC',null,0,true,array('id', 'username', 'nombre', 'apellido', 'activo', 'privilegios', 'ultimo_acceso')); 
		$datos = $estadistica->search($sidx,$sord,$rows,$rows*($page-1),'Core_Object');
		//aca termina la consulta a la base
//var_dump($estadistica->searchGetSql());
		$this->setPage($page);
		$this->setRecords($total_items);
		$this->setTotal($cantidad_paginas);
		//$this->setDataResult($datos);
		return($datos);
	}
}
?>