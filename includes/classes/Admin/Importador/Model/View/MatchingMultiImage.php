<?
class Admin_Importador_Model_View_MatchingMultiImage extends Granguia_Db_Model_View_Abstract{
	public function Admin_Importador_Model_View_MatchingMultiImage($id_importacion_productos, $arr_ids_importacion_imagenes){
		parent::__construct();
		$view = new Granguia_Db_Model_View_Generic();
		$view
			->addTable('bm_producto_importacion',null,'pi',array('id_producto_importacion'=>'pi.id','codigo'=>'pi.codigo','nombre'=>'pi.nombre','descripcion'=>'pi.descripcion','marca'=>'pi.marca','categoria'=>'pi.categoria','rubro'=>'pi.rubro','stock'=>'pi.stock','precio'=>'pi.precio','imagen'=>'pi.imagen','modelos'=>'pi.modelos'))
			->setWhere(
				Db_Helper::equal('id_importacion',$id_importacion_productos),
				Db_Helper::equal('importar','1'))
		;
		$this->addView($view, 'vpi', array(), null);
		$view = new Granguia_Db_Model_View_Generic();
		$view
			->addTable('bm_imagen_importacion',null,'ii',array('id_imagen_importacion'=>'ii.id','name'=>'ii.name'))
			->setWhere(
				Db_Helper::in('id_importacion',true,$arr_ids_importacion_imagenes),
				Db_Helper::equal('importar','1'))
		;
		$this
			->addView($view, 'vii', array(), '(
				imagen != \'\' AND(instr(lcase(imagen), lcase(name))>0)
			)')
			//->addColumn('count(name is null)', 'cantidad_imagenes_faltantes')
		;
		
	}
}
?>