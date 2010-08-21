<?
class Granguia_Model_View_ContadorClickAnuncioBarrioDia extends Granguia_Db_Model_View_Generic{
	public function Granguia_Model_View_ContadorClickAnuncioBarrioDia($max_id){
		parent::__construct();
		$tipo_contador = Granguia_Model_TipoContador::getTipoContadorByName('CLICK_ANUNCIO');
//		var_dump($this->getDb()->getLastErrors());
		$view = new Granguia_Db_Model_View_Generic();
		
		
		{
			$view_click_anuncio = new Granguia_Db_Model_View_Generic();
			$view_click_anuncio 
				->addTable('gg_contador', null,'c',array('id_tipo', 'id_anuncio'=>'info1'))
				->addTable('gg_anuncio', 'a.id=c.info1', 'a', array('id_barrio'=>'a.id_barrio'))
				//->addColumn('max(id)', 'max_id')
				->addColumn('count(*)', 'cantidad')
				->addColumn('date(fecha)', 'dia')
				->setIdTipo($tipo_contador->getId())
				->setWhere(Db_Helper::equal('procesado','0'),Db_Helper::equal('id_tipo'),Db_Helper::between('c.id', null, $max_id))
				->setGroupBy('id_anuncio,dia')
			;
		}
		
		$view
			->addView($view_click_anuncio,'ca', array('id_tipo'=>'id_tipo','dia'=>'ca.dia'))
			->addTable('gg_barrio', 'c.id = ca.id_barrio', 'c', array('id_barrio'=>'c.id', 'nombre_barrio'=>'c.nombre'))
			->addColumn('sum(ca.cantidad)', 'cantidad')
			->setGroupBy('c.id,ca.dia')
		;	
//		echo "\n".$view->searchGetSql();
//		die();
		$this->addView($view, 'c', array('id_tipo','id_barrio', 'cantidad', 'dia'));
//		var_dump(get_class($this->getDb()));
//		var_dump(get_class($this->getDb()));
//		echo "\n".$this->searchGetSql();
//		die();
	}
}
?>