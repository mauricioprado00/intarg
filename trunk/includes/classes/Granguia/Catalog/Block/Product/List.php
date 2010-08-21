<?
class Granguia_Catalog_Block_Product_List extends Core_Block_List_Abstract{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("catalog/product/list/default.phtml")
			->setMaxItems(3)
		;
	}
	protected function search(){
		$producto = new Granguia_Catalog_Model_Producto();
		return($producto->search(null,null,10));
	} 
	protected function searchCount(){
		$producto = new Granguia_Catalog_Model_Producto();
		return($producto->searchCount());
	}
}
?>