<?
class Granguia_Model_View_ContadorInicioSesionDia extends Granguia_Db_Model_View_Generic{
	public function _construct(){
		parent::_construct();
		$tipo_contador = Granguia_Model_TipoContador::getTipoContadorByName('INICIO_SESION');
//		var_dump($this->getDb()->getLastErrors());
		$view = new Granguia_Db_Model_View_Generic();
		$view
			->addTable('gg_contador',null,'c',array('id_tipo'))
			->addColumn('max(id)', 'max_id')
			->addColumn('count(*)', 'cantidad')
			->addColumn('date(fecha)', 'dia')
			->setIdTipo($tipo_contador->getId())
			->setWhere(Db_Helper::equal('procesado','0'),Db_Helper::equal('id_tipo'))
			->setGroupBy('dia')
		;	
		//echo "\n".$view->searchGetSql();
//		die();
		$this->addView($view, 'c', array('id_tipo','max_id', 'cantidad', 'dia'));
	}
}
?>