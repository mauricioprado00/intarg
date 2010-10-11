<?
/**
 *@referencia Agencia(id_agencia) Inta_Model_Agencia(id)
 *@listar ResultadoEsperado Inta_Model_ResultadoEsperado
*/
class Inta_Model_Objetivo extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_agencia',
			'nombre',
			'descripcion',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function asignarProblemas($ids_problemas){
		if(!$this->getId())
			return;
		$this->getDb()->open();
		$sql = strtr(
			'UPDATE %tabla SET id_objetivo = 0 WHERE id_objetivo=%id_objetivo ;', array(
				'%id_objetivo'=>$this->getDb()->valueToString($this->getId()),
				'%tabla'=>$this->getDb()->nameToString('inta_problema')
			)
		);
		$this->getDb()->exec($sql);
		if($ids_problemas){
			foreach($ids_problemas as &$id_problema){
				$id_problema = $this->getDb()->valueToString($id_problema);
			}unset($id_problema);
			$ids_problemas = implode(',', $ids_problemas);
			$sql = strtr(
				'UPDATE %tabla SET id_objetivo = %id_objetivo WHERE id IN (%ids_problemas);', array(
					'%ids_problemas'=>$ids_problemas,
					'%id_objetivo'=>$this->getDb()->valueToString($this->getId()),
					'%tabla'=>$this->getDb()->nameToString('inta_problema')
				)
			);
			$this->getDb()->exec($sql);
		}
		$this->getDb()->close();
	}

	public function getDbTableName() 
	{
		return 'inta_objetivo';
	}
}
?>