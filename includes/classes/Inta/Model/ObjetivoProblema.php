<?
/**
 *@referencia Objetivo(id_objetivo) Inta_Model_Objetivo(id)
 *@referencia Problema(id_problema) Inta_Model_Problema(id)
*/
class Inta_Model_ObjetivoProblema extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_objetivo',
			'id_problema',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}

	public function getDbTableName() 
	{
		return 'inta_objetivo_problema';
	}
}

?>