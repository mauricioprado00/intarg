<?
class Admin_Importador_Model_View_Importacion extends Granguia_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$this
			->addTable('bm_importacion',null,'i',array('id'=>'i.id','archivo'=>'i.archivo'))
			->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			->addColumn('count(pi.id)', 'cantidad_productos_importados')
			->setGroupBy('i.id')
		;	
	}
}
?>