<?php
class Granguia_Model_Estadistica_ClickAnuncio extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'cantidad',
			'id_anuncio',
			'nombre_anuncio',
			'id_categoria',
			'nombre_categoria',
			'id_barrio',
			'nombre_barrio',
			'dia',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
		$this->addAutofilterFieldInput('dia', array('Mysql_Helper', 'filterDateInput'));
		$this->addAutofilterFieldOutput('dia', array('Mysql_Helper', 'filterDateOutput'));
	}
	public static function Registrar($cantidad,$id_anuncio, $nombre_anuncio, $id_categoria, $nombre_categoria, $id_barrio, $nombre_barrio, $dia){
		$e = new self();
		$e
			->setCantidad($cantidad)
			->setIdAnuncio($id_anuncio)
			->setNombreAnuncio($nombre_anuncio)
			->setIdCategoria($id_categoria)
			->setNombreCategoria($nombre_categoria)
			->setIdBarrio($id_barrio)
			->setNombreBarrio($nombre_barrio)
			->setDia($dia)
		;
		$e->setWhere(Db_Helper::equal('dia'),Db_Helper::equal('id_anuncio'), Db_Helper::equal('id_categoria'), Db_Helper::equal('id_barrio'));
		if($e->searchCount()){
			$es = $e->search(null, null, null, null, __CLASS__);
			$e = array_shift($es);
			$e->setCantidad($cantidad + $e->getCantidad());
			return $e->update();
		}
		return $e->insert();
	}
	public function getDbTableName(){
		return 'gg_estadistica_click_anuncio';
	}
	//Core_Model_Abstract
}
?>