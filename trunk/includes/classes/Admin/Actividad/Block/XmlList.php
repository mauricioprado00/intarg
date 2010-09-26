<?
class Admin_Actividad_Block_XmlList extends Jqgrid_Block_XmlList{
	protected function loadData($page, $rows, $sidx, $sord, $comparator){
		/*hay que hacer que consulte la base de datos
			sidx = columna de ordenacion
			sord = orden de ordenacion (asc/desc)
		*/
		//$actividad = new Inta_Model_Actividad();
		$actividad = new Inta_Model_Actividad();
		$wheres = array();
		if($comparator!=null){
			$wheres[] = $comparator;
			//$actividad->setWhere($comparator);
		}
		if($this->hasHardFiltros()){
			foreach($this->getHardFiltros() as $fieldname=>$value){
				if($fieldname=='id_agencia'){
					$wheres[] = $actividad->crearFiltroAgencia($value);
				}
				else{
					$wheres[] = Db_Helper::equal($fieldname, $value);
				}
			}
		}
		if(count($wheres))
			$actividad->setWhereByArray($wheres);
		$datos = array();
		$total_items = $actividad->searchCount();
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
		
		//$datos = $actividad->search(null,'ASC',null,0,true,array('id', 'username', 'nombre', 'apellido', 'activo', 'privilegios', 'ultimo_acceso'));
		$datos = $actividad->search($sidx,$sord,$rows,$rows*($page-1),get_class($actividad));
		//aca termina la consulta a la base
		//echo Inta_Db::getInstance()->getLastQuery();
		
		$this->setPage($page);
		$this->setRecords($total_items);
		$this->setTotal($cantidad_paginas);
		//$this->setDataResult($datos);
		return($datos);
	}
}
?>