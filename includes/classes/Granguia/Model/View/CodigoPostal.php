<?
class Granguia_Model_View_CodigoPostal extends Granguia_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Granguia_Db_Model_View_Generic();
		$view
			->addTable('bm_ciudad_codigo_postal',null,'cp',array(
				'id'=>'cp.id','id_ciudad'=>'cp.id_ciudad','codigo_postal'=>'cp.codigo_postal'))
			->addTable('bm_ciudad', 'cp.id_ciudad = c.id', 'c', array('ciudad'=>'c.nombre'))
			//->addColumn('IF('.$this->getDb()->nameToString('pi.importar').'=\'1\',\'si\',\'no\')', 'importar')
			//->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			//->addColumn('count(pi.id)', 'cantidad_productos_importados')
			//->setGroupBy('i.id')
		;	
		$this->addView($view, 'vcp', array(
			'id','codigo_postal','ciudad'));
	}
}
?>