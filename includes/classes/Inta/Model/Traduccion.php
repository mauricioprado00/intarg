<?
class Inta_Model_Traduccion extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'texto',
			'contexto',
			'traduccion',
			'explicacion',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public static function getMatch($texto, $explicacion, $contexto){
		$traduccion = new self();
		$wheres = array();
		$wheres[] = Db_Helper::equal('texto');
		
		if($contexto){
			$wheres[] = 'AND (';
			$wheres[] = Db_Helper::equal('contexto');//{@contexto} like concat({#contexto},{%s})', '/%');
			$wheres[] = 'OR';
//			$wheres[] = Db_Helper::custom('{@contexto} like concat({#contexto},{%s})', '%');
			$wheres[] = Db_Helper::custom('{#contexto} like concat({@contexto},{%s})', '%');
			$wheres[] = ')';
		}
		$traduccion->setWhereByArray($wheres);
		$traduccion
			->setTexto($texto)
			->setContexto($contexto)
		;
		if($traduccion->searchCount()){
			$arr_traduccion = $traduccion->search('contexto', 'desc', null, null, __CLASS__);
			if($arr_traduccion){
				$traduccion = $arr_traduccion[0];
				return $traduccion;
			}
		}
//		var_dump(Inta_Db::getInstance()->getLastQuery());
//		var_dump($traduccion->getData());
//		die();
		$traduccion
			->setExplicacion($explicacion)
			->setTraduccion($texto)
			->insert()
		;
		return $traduccion;
	}
	public static function Traducir($texto, $explicacion, $contexto){
		$traduccion = self::getMatch($texto, $explicacion, $contexto);
		return $traduccion->getTraduccion();
		$traduccion = new self();
		$wheres = array();
		$wheres[] = Db_Helper::equal('texto');
		
		if($contexto){
			$wheres[] = 'AND (';
			$wheres[] = Db_Helper::equal('contexto');//{@contexto} like concat({#contexto},{%s})', '/%');
			$wheres[] = 'OR';
//			$wheres[] = Db_Helper::custom('{@contexto} like concat({#contexto},{%s})', '%');
			$wheres[] = Db_Helper::custom('{#contexto} like concat({@contexto},{%s})', '%');
			$wheres[] = ')';
		}
		$traduccion->setWhereByArray($wheres);
		$traduccion
			->setTexto($texto)
			->setContexto($contexto)
		;
		if($traduccion->searchCount()){
			$arr_traduccion = $traduccion->search('contexto', 'desc');
			if($arr_traduccion){
				$traduccion = $arr_traduccion[0];
				return 'db:'.$traduccion->getTraduccion();	
			}
		}
//		var_dump(Inta_Db::getInstance()->getLastQuery());
//		var_dump($traduccion->getData());
//		die();
		$traduccion
			->setExplicacion($explicacion)
			->setTraduccion($texto)
			->insert()
		;
		return null;
	}
	public function getDbTableName() 
	{
		return 'inta_traduccion';
	}
}
?>