<?php
class Granguia_Model_Estadistica_ClickAnuncioCategoria extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'cantidad',
			'id_categoria',
			'nombre_categoria',
			'dia',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
		$this->addAutofilterFieldInput('dia', array('Mysql_Helper', 'filterDateInput'));
		$this->addAutofilterFieldOutput('dia', array('Mysql_Helper', 'filterDateOutput'));
	}
	public static function Registrar($cantidad, $id_categoria, $nombre_categoria, $dia){
		$e = new self();
		$e
			->setCantidad($cantidad)
			->setIdCategoria($id_categoria)
			->setNombreCategoria($nombre_categoria)
			->setDia($dia)
		;
		$e->setWhere(Db_Helper::equal('dia'), Db_Helper::equal('id_categoria'));
		if($e->searchCount()){
			$es = $e->search(null, null, null, null, __CLASS__);
			$e = array_shift($es);
			$e->setCantidad($cantidad + $e->getCantidad());
			return $e->update();
		}
		return $e->insert();
	}
	public function getDbTableName(){
		return 'gg_estadistica_click_anuncio_categoria';
	}
	//Core_Model_Abstract
}
?>