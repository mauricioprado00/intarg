<?
class Granguia_Catalog_Model_View_Marca extends Granguia_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		//$view = new Granguia_Db_Model_View_Generic();
		$this
			->addTable('bm_producto',null,'p')
			->addColumn('Distinct p.marca', 'nombre')
			//->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			//->addColumn('count(pi.id)', 'cantidad_productos_importados')
			//->setGroupBy('i.id')
		;	
		//$this->addView($view, 'pi', array('id','id_importacion','codigo','sinonimo','descripcion','rubro','unidad','existencia','importar'));
	}
}
?>