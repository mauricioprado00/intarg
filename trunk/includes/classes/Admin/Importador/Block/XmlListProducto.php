<?
class Admin_Importador_Block_XmlListProducto extends Jqgrid_Block_XmlList{
	protected function loadData($page, $rows, $sidx, $sord, $comparator){
		/*hay que hacer que consulte la base de datos
			sidx = columna de ordenacion
			sord = orden de ordenacion (asc/desc)
		*/
		//$admin_importacion_producto = new Admin_Importador_Model_Producto();
		$admin_importacion_producto = new Admin_Importador_Model_View_Producto();
		$admin_importacion_producto->setIdImportacion($this->getIdImportacion());
		$arr_where = array(Db_Helper::equal('id_importacion'));
		if($comparator!=null){
			$arr_where[] = $comparator;
		}
		//$admin_importacion_producto->setWhere(Db_Helper::equal('id_importacion'));
		$admin_importacion_producto->setWhereByArray($arr_where);
		$datos = array();
		$total_items = $admin_importacion_producto->searchCount();
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
		//$datos = $admin_importacion_producto->search(null,'ASC',null,0,true,array('id', 'username', 'nombre', 'apellido', 'activo', 'privilegios', 'ultimo_acceso')); 
		$datos = $admin_importacion_producto->search($sidx,$sord,$rows,$rows*($page-1));
		//aca termina la consulta a la base
		
		
		$this->setPage($page);
		$this->setRecords($total_items);
		$this->setTotal($cantidad_paginas);
		//$this->setDataResult($datos);
		return($datos);
	}
}
?>