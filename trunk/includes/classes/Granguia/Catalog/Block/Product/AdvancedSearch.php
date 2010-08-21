<?
class Granguia_Catalog_Block_Product_AdvancedSearch extends Granguia_Catalog_Block_Product_List{
//	protected function searchProducto(){
//		$producto = new Granguia_Catalog_Model_Producto();
//		return($producto->search());
//	} 
	private $search_object = null;
	private function initializeSearchObject(){
		if($this->search_object===null){
			$this->search_object = new Granguia_Catalog_Model_Producto();
			$wheres = array();
			$filtros = Granguia_Catalog_Block_Search_AdvancedForm::getFiltros();
			$filtro_adicional = Granguia_Catalog_Block_Search_AdvancedForm::getFiltroAdicional();
			if($filtro_adicional){
				if($filtros)
					$filtros = array_merge(array($filtro_adicional), $filtros);// array_unshift(, $filtros);
				else $filtros = array($filtro_adicional);
			}
			
			if(Granguia_Catalog_Helper::getFiltrarProductosVacios()){
				$wheres[] = Db_Helper::distinct('descripcion', '');
				$wheres[] = Db_Helper::between('precio',0,null,false);
			}

			if($filtros){
//							Core_Http_Header::ContentType('text/plain');
//							var_dump($filtros);
//							die();
				/**
				array(2) {
				  [0]=>
				  array(2) {
				    ["campo_busqueda"]=>
				    string(9) "categoria"
				    ["texto_busqueda"]=>
				    string(4) "juyh"
				  }
				  [1]=>
				  array(2) {
				    ["campo_busqueda"]=>
				    string(5) "marca"
				    ["texto_busqueda"]=>
				    string(4) "bleh"
				  }
				}
				*/
				$default = array('campo_busqueda'=>null,'texto_busqueda'=>null);
				foreach($filtros as $filtro){
					extract($default);extract($filtro);
//					$texto_busqueda = $post->getTextoBusqueda();
//					$campo_busqueda = $post->getCampoBusqueda();
					if($texto_busqueda!==''){
						if(count($wheres))
							$wheres[] = ' AND ';
						if($campo_busqueda==='' || !Granguia_Catalog_Model_Producto::isCampoBuscable($campo_busqueda)){
							$campos = Granguia_Catalog_Model_Producto::getCamposBuscables();
							$idx = 0;
							$wheres[] = '(';
							foreach($campos as $campo){
								if($idx++)
									$wheres[] = 'OR';
								$wheres[] = Db_Helper::like($campo->getNombre(), '%', $texto_busqueda, '%');
							}
							$wheres[] = ')';
						}
						else{
							$wheres[] = Db_Helper::like($campo_busqueda, '%', $texto_busqueda, '%');
						}
					}
				}
			}
			$rubros = Granguia_Catalog_Router_Catalog::getRubros();
			if($rubros){
				$this->search_object->setWhere(Db_Helper::in('rubro', true, $rubros));
				if($wheres)
					$wheres[] = ' AND ';
				$wheres[] = Db_Helper::in('rubro', true, $rubros);
			}
			if($wheres)
				$this->search_object->setWhereByArray($wheres);
//			Core_Http_Header::ContentType('text/plain');
//			var_dump($this->search_object->search(null,'ASC',null,0,true,null,true));
//			die();
		}
	}
	public function getSearchObject(){
		$this->initializeSearchObject();
		return($this->search_object);
	}
	protected function searchCount(){
		static $count = null;
		if($count!==null){
			return($count);
		}
		$count = $this->search_object->searchCount();
		return($count);
	}
	protected function getPagina(){
		if(Core_Http_Post::hasParameters()){
			return(Core_Http_Post::getParameters('Core_Object')->getPagina()+0);
		}
		return(0);
	}
	protected function search(){
		$start_item = 0;
		$max_items = $this->getMaxItems();
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('Core_Object');
			$start_item = $post->getPagina() * $max_items;
			$campo_orden = $post->getCampoOrden();
			if($campo_orden!=='' && Granguia_Catalog_Model_Producto::isCampoOrdenable($campo_orden)){
//		Core_Http_Header::ContentType('text/plain');
//		var_dump($this->getSearchObject()->search($campo_orden,'ASC', $max_items, $start_item, true, null, true));
//		die();
//				echo $this->getSearchObject()->search($campo_orden,'ASC', $max_items, $start_item, true, null, true);
				return($this->getSearchObject()->search($campo_orden,'ASC', $max_items, $start_item));
			}
		}
		//echo $this->getSearchObject()->search(null,'ASC', $max_items, $start_item, true, null, true);
		$default_order = Granguia_Catalog_Model_Producto::getCamposOrdenables();
		$default_order = $default_order?array_shift($default_order):null;
		$default_order = $default_order?$default_order->getNombre():null;
//		Core_Http_Header::ContentType('text/plain');
//echo '<div style="display:none;" class="consultasql">';
//		var_dump($this->getSearchObject()->search($default_order, 'ASC', $max_items, $start_item, true, null, true));
//echo '</div>';
//		die();
		return($this->getSearchObject()->search($default_order, 'ASC', $max_items, $start_item));
	}
}
?>