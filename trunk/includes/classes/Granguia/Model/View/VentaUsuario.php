<?
class Granguia_Model_View_VentaUsuario extends Granguia_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Granguia_Db_Model_View_Generic();
			
		$view
			->addTable('bm_venta',null,'v',array('id'=>'v.id',
			'id_usuario'=>'v.id_usuario',
			'ciudad'=>'v.ciudad',
			'codigo_postal'=>'v.codigo_postal',
			'metodo_envio'=>'v.metodo_envio',
			'forma_pago'=>'v.forma_pago',
			'estado'=>'v.estado',
			'fecha'=>'v.fecha'))
			->addTable('bm_usuario', 'v.id_usuario = c.id', 'c', array('nombre'=>'c.nombre','apellido'=>'c.apellido','email'=>'c.email','username'=>'username','telefono_cod_area','telefono_numero','domicilio','localidad','provincia','pais'))
			->addColumn('CONCAT('.($this->getDb()->nameToString('c.apellido')).', \', \', '.($this->getDb()->nameToString('c.nombre')).')', 'nombre_completo')
			//->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
			//->addColumn('count(pi.id)', 'cantidad_productos_importados')
			//->setGroupBy('i.id')
		;	
		$this->addView($view, 'vvu', array(
			'id','id_usuario','ciudad','codigo_postal','metodo_envio','forma_pago','estado','fecha','nombre','apellido','email','nombre_completo','username','telefono_cod_area','telefono_numero','domicilio','localidad','provincia','pais'));
	}
}
?>