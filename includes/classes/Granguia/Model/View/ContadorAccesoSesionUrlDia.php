<?
class Granguia_Model_View_ContadorAccesoSesionUrlDia extends Granguia_Db_Model_View_Generic{
	public function Granguia_Model_View_ContadorAccesoSesionUrlDia($max_id){
		parent::__construct();
		$tipo_contador = Granguia_Model_TipoContador::getTipoContadorByName('ACCESO_SESION');
//		var_dump($this->getDb()->getLastErrors());
		$view = new Granguia_Db_Model_View_Generic();
		
		$view
			->addTable('gg_contador',null,'c',array('id_tipo', 'ip'=>'info1', 'url'=>'info2'))
			->addColumn('max(id)', 'max_id')
			->addColumn('count(*)', 'cantidad')
			->addColumn('date(fecha)', 'dia')
			->setIdTipo($tipo_contador->getId())
			->setWhere(Db_Helper::equal('procesado','0'),Db_Helper::equal('id_tipo'),Db_Helper::between('c.id', null, $max_id))
			->setGroupBy('url,dia')
//			->addTable('bm_usuario', 'v.id_usuario = c.id', 'c', array('nombre'=>'c.nombre','apellido'=>'c.apellido','email'=>'c.email','username'=>'username','telefono_cod_area','telefono_numero','domicilio','localidad','provincia','pais'))
//			->addColumn('CONCAT('.($this->getDb()->nameToString('c.apellido')).', \', \', '.($this->getDb()->nameToString('c.nombre')).')', 'nombre_completo')
			//->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			//->addColumn('count(pi.id)', 'cantidad_productos_importados')
			//->setGroupBy('i.id')
		;	
		//echo "\n".$view->searchGetSql();
//		die();
		$this->addView($view, 'c', array('id_tipo','url', 'max_id', 'cantidad', 'dia'));
	}
}
?>