<?
class Admin_Importador_Model_View_Producto extends Granguia_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Granguia_Db_Model_View_Generic();
		$view
			->addTable('bm_producto_importacion',null,'pi',array(
				'id'=>'pi.id','id_importacion'=>'pi.id_importacion','codigo'=>'pi.codigo',
				'nombre'=>'pi.nombre','descripcion'=>'pi.descripcion','marca'=>'pi.marca',
				'categoria'=>'pi.categoria','rubro'=>'pi.rubro','stock'=>'pi.stock',
				'precio'=>'pi.precio','imagen'=>'pi.imagen','modelos'=>'pi.modelos'))
			->addColumn('IF('.$this->getDb()->nameToString('pi.importar').'=\'1\',\'si\',\'no\')', 'importar')
			//->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			//->addColumn('count(pi.id)', 'cantidad_productos_importados')
			//->setGroupBy('i.id')
		;	
		$this->addView($view, 'pi', array(
			'id','id_importacion','codigo',
			'nombre','descripcion','marca',
			'categoria','rubro','stock',
			'precio','imagen',
			'importar','modelos'));
	}
}
?>