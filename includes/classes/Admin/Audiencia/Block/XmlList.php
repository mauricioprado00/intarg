<?
class Admin_Audiencia_Block_XmlList extends Jqgrid_Block_XmlList{
	protected function loadData($page, $rows, $sidx, $sord, $comparator){
		/*hay que hacer que consulte la base de datos
			sidx = columna de ordenacion
			sord = orden de ordenacion (asc/desc)
		*/
		//$audiencia = new Inta_Model_Audiencia();
		$audiencia = new Inta_Model_Audiencia();
		$wheres = array();
		$wheres[] = $audiencia->crearFiltroAgencia();
		if($comparator!=null){
			//$audiencia->setWhere($comparator);
			$wheres[] = $comparator;
		}
		$audiencia->setWhereByArray($wheres);
		$datos = array();
		$total_items = $audiencia->searchCount();
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
		
		//$datos = $audiencia->search(null,'ASC',null,0,true,array('id', 'username', 'nombre', 'apellido', 'activo', 'privilegios', 'ultimo_acceso'));
		$datos = $audiencia->search($sidx,$sord,$rows,$rows*($page-1),get_class($audiencia));
		//aca termina la consulta a la base
//		echo $audiencia->searchGetSql($sidx,$sord,$rows,$rows*($page-1),get_class($audiencia));
//		die();
		
		
		$this->setPage($page);
		$this->setRecords($total_items);
		$this->setTotal($cantidad_paginas);
		//$this->setDataResult($datos);
		return($datos);
	}
}
?>