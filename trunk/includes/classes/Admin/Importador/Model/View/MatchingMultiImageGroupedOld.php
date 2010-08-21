<?
class Admin_Importador_Model_View_MatchingMultiImageGroupedOld extends Granguia_Db_Model_View_Abstract{
	public function Admin_Importador_Model_View_MatchingMultiImageGroupedOld($id_importacion_productos, $arr_ids_importacion_imagenes){
		parent::__construct();
		$view = new Admin_Importador_Model_View_MatchingMultiImageOld($id_importacion_productos, $arr_ids_importacion_imagenes);
		$view->setGroupBy('id_producto_importacion', 'id_imagen_importacion');
		$this
			->addView($view, 'inf')
			->addColumn('count(if(name is null and imagen!=\'\', 1 , null))', 'cantidad_imagenes_faltantes')
		;
		
	}
}
?>