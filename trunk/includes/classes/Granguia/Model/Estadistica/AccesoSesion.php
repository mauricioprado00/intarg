<?php
class Granguia_Model_Estadistica_AccesoSesion extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'cantidad',
			'ip',
			'dia',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
		$this->addAutofilterFieldInput('dia', array('Mysql_Helper', 'filterDateInput'));
		$this->addAutofilterFieldOutput('dia', array('Mysql_Helper', 'filterDateOutput'));
	}
	public static function Registrar($cantidad,$ip, $dia){
		$e = new self();
		$e
			->setCantidad($cantidad)
			->setIp($ip)
			->setDia($dia)
		;
		$e->setWhere(Db_Helper::equal('dia'),Db_Helper::equal('ip'));
		if($e->searchCount()){
			$es = $e->search(null, null, null, null, __CLASS__);
			$e = array_shift($es);
			$e->setCantidad($cantidad + $e->getCantidad());
			return $e->update();
		}
		return $e->insert();
	}
	public function getDbTableName(){
		return 'gg_estadistica_acceso_sesion';
	}
	//Core_Model_Abstract
}
?>