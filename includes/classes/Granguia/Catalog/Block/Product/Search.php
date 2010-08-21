<?
class Granguia_Catalog_Block_Product_Search extends Granguia_Catalog_Block_Product_List{
//	protected function searchProducto(){
//		$producto = new Granguia_Catalog_Model_Producto();
//		return($producto->search());
//	} 
	private $search_object = null;
	private function initializeSearchObject(){
		if($this->search_object===null){
			$this->search_object = new Granguia_Catalog_Model_Producto();
			$wheres = array();
			if(Core_Http_Post::hasParameters()){
				$post = Core_Http_Post::getParameters('Core_Object');
				
				$categoria = $post->getCategoria();
				if($categoria!==''){
					$wheres[] = Db_Helper::equal('categoria', $categoria);
				}
				$rubro = $post->getRubro();
				if($rubro!='')
					$wheres[] = Db_Helper::equal('rubro', $rubro);
				$texto_busqueda = $post->getTextoBusqueda();
				$campo_busqueda = $post->getCampoBusqueda();
				if($texto_busqueda!==''){
					if($campo_busqueda==='' || !Granguia_Catalog_Model_Producto::isCampoBuscable($campo_busqueda)){
						$campos = Granguia_Catalog_Model_Producto::getCamposBuscables();
						$wheres[] = (count($wheres)?' AND ':'').'(';
						$idx = 0;
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
			$rubros = Granguia_Catalog_Router_Catalog::getRubros();
			if($rubros){
				$this->search_object->setWhere(Db_Helper::in('rubro', true, $rubros));
				if($wheres)
					$wheres[] = ' AND ';
				$wheres[] = Db_Helper::in('rubro', true, $rubros);
			}
			if($wheres)
				$this->search_object->setWhereByArray($wheres);
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
				//echo $this->getSearchObject()->search($campo_orden,'ASC', $max_items, $start_item, true, null, true);
				return($this->getSearchObject()->search($campo_orden,'ASC', $max_items, $start_item));
			}
		}
//echo '<div style="display:none;" class="consultasql">';
//		echo $this->getSearchObject()->search(null,'ASC', $max_items, $start_item, true, null, true);
//echo '</div>';
		return($this->getSearchObject()->search(null, 'ASC', $max_items, $start_item));
	}
}
?>