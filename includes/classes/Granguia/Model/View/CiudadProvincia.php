<?
class Granguia_Model_View_CiudadProvincia extends Granguia_Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$view = new Granguia_Db_Model_View_Generic();
		$view
			->addTable('bm_ciudad',null,'c',array(
				'id'=>'c.id','id_provincia'=>'c.id_provincia','nombre'=>'c.nombre'))
			->addTable('bm_provincia', 'c.id_provincia = p.id', 'p', array('provincia'=>'p.nombre'))
		;	
		$this->addView($view, 'vcp', array(
			'id','provincia','nombre'));
	}
}
?>