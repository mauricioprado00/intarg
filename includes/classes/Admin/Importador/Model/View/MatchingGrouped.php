<?
class Admin_Importador_Model_View_MatchingGrouped extends Granguia_Db_Model_View_Abstract{
	public function Admin_Importador_Model_View_MatchingGrouped($id_importacion_productos, $id_importacion_imagenes){
		parent::__construct();
		$view = new Admin_Importador_Model_View_Matching($id_importacion_productos, $id_importacion_imagenes);
		$view->setGroupBy('id_producto_importacion', 'id_imagen_importacion');
		$this
			->addView($view, 'inf')
			->addColumn('count(if(name is null, 1 , null))', 'cantidad_imagenes_faltantes')
		;
		
	}
}
?>