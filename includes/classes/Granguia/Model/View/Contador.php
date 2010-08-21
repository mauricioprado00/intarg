<?
class Granguia_Model_View_Contador extends Granguia_Db_Model_View_Generic{
	private $_view_contador = null;
	private $_view_tipo_contador = null; 
	public function _construct(){
		parent::_construct();
		$view = new Granguia_Db_Model_View_Generic();
		//id, id_tipo, info1, info2, fecha, uk, procesado
		$view
			->addTable('gg_contador',null,'c',array('id'=>'c.id', 'info1'=>'c.info1', 'info2'=>'c.info2', 'uk'=>'c.uk', 'procesado'=>'c.procesado'))
			->addColumn('date(fecha)','fecha')
			->addTable('gg_tipo_contador','c.id_tipo=tc.id', 'tc', array('id_tipo'=>'tc.id','nombre_tipo'=>'tc.nombre'))
		;	
		$this->addView($view, 'c', array('id', 'id_tipo', 'nombre_tipo', 'info1', 'info2', 'fecha'));
	}
}
?>