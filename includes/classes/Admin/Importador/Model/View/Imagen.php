<?
class Admin_Importador_Model_View_Imagen extends Granguia_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Granguia_Db_Model_View_Generic();
		$view
			->addTable('bm_imagen_importacion',null,'ii',array('id'=>'ii.id','id_importacion'=>'ii.id_importacion','name'=>'ii.name'))
			->addColumn('IF('.$this->getDb()->nameToString('ii.importar').'=\'1\',\'si\',\'no\')', 'importar')
			//->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			//->addColumn('count(pi.id)', 'cantidad_productos_importados')
			//->setGroupBy('i.id')
		;	
		$this->addView($view, 'ii', array('id', 'id_importacion', 'name', 'importar'));
	}
}
?>