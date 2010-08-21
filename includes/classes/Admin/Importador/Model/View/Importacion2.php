<?
class Admin_Importador_Model_View_Importacion2 extends Granguia_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
        $view = new Granguia_Db_Model_View_Generic();
		$view
			->addTable('bm_importacion',null,'i',array('id'=>'i.id','archivo'=>'i.archivo'))
			->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			->addTable('bm_imagen_importacion','i.id = ii.id_importacion','ii')
			->addColumn('count(pi.id)', 'cantidad_productos_importados')
			->addColumn('count(IF(pi.importar, 1, null))', 'cantidad_productos_a_importar')
			->addColumn('count(ii.id)', 'cantidad_imagenes_importadas')
			->addColumn('count(IF(ii.importar, 1, null))', 'cantidad_imagenes_a_importar')
			->setGroupBy('i.id')
		;
		$this->addView($view, 'vpi', array('id','archivo','cantidad_productos_importados', 'cantidad_imagenes_importadas','cantidad_productos_a_importar','cantidad_imagenes_a_importar'), null);
	}
}
?>