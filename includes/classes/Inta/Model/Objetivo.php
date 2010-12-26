<?
/**
 *@referencia Agencia(id_agencia) Inta_Model_Agencia(id)
 *@listar ResultadoEsperado Inta_Model_ResultadoEsperado
 *@listar Problema Inta_Model_ObjetivoProblema
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
//	public function asignarProblemasDeprecated($ids_problemas){
//		if(!$this->getId())
//			return;
//		$this->getDb()->open();
//		$sql = strtr(
//			'UPDATE %tabla SET id_objetivo = 0 WHERE id_objetivo=%id_objetivo ;', array(
//				'%id_objetivo'=>$this->getDb()->valueToString($this->getId()),
//				'%tabla'=>$this->getDb()->nameToString('inta_problema')
//			)
//		);
//		$this->getDb()->exec($sql);
//		if($ids_problemas){
//			foreach($ids_problemas as &$id_problema){
//				$id_problema = $this->getDb()->valueToString($id_problema);
//			}unset($id_problema);
//			$ids_problemas = implode(',', $ids_problemas);
//			$sql = strtr(
//				'UPDATE %tabla SET id_objetivo = %id_objetivo WHERE id IN (%ids_problemas);', array(
//					'%ids_problemas'=>$ids_problemas,
//					'%id_objetivo'=>$this->getDb()->valueToString($this->getId()),
//					'%tabla'=>$this->getDb()->nameToString('inta_problema')
//				)
//			);
//			$this->getDb()->exec($sql);
//		}
//		$this->getDb()->close();
//	}
	public function asignarProblemas($ids_problemas){
		if(!$this->getId())
			return;
		//si no hay problemas nuevos de todas maneras hay que eliminar los existentes
		if(!$ids_problemas)
			$ids_problemas = array();
		//obtener problemas actuales
		$relaciones_objetivo_problema = $this->getListProblema();
		if(!$relaciones_objetivo_problema)
			$relaciones_objetivo_problema = array();
		//obtener ids de problemas actuales
		$col_relaciones_objetivo_problema = new Core_Collection($relaciones_objetivo_problema);
		$arr_id_problema_existente = array_unique($col_relaciones_objetivo_problema->VerticalSliceSingleArray('id_problema'));
		//filtrar ids de los problemas nuevos
		$arr_id_nuevo = array_diff($ids_problemas, $arr_id_problema_existente);
		//crear relaciones nuevas
		foreach($arr_id_nuevo as $id_problema){
			$relacion_objetivo_problema = 
				c(new Inta_Model_ObjetivoProblema())
					->setIdObjetivo($this->getId())
					->setIdProblema($id_problema)
					->insert()
			;
		}
		//filtrar los problemas eliminados
		$col_relaciones_objetivo_problema_eliminadas = $col_relaciones_objetivo_problema->addFilterNotIn('id_problema', $ids_problemas);
		//eliminar relaciones viejas
		if($col_relaciones_objetivo_problema_eliminadas){
			foreach($col_relaciones_objetivo_problema_eliminadas as $relacion_objetivo_problema){
				$relacion_objetivo_problema->delete();
			}
		}
	}
//	public function getListProblema($relaciones=true){
//		$arr_relacion_objetivo_problema = parent::getListProblema();
//		if($relaciones){
//			return $arr_relacion_objetivo_problema;
//		}
//		$result = array();
//		foreach($arr_relacion_objetivo_problema as $relacion_objetivo_problema){
//			$result[] = $relacion_objetivo_problema->getProblema();
//		}
//		return $result;
//	}
	
	public static function crearFiltroAgencia($id_agencia=null){
		if(!isset($id_agencia))
			$id_agencia = Admin_Helper::getInstance()->getIdAgenciaSeleccionada();
		return Db_Helper::equal('id_agencia', $id_agencia);
	}
	public function getDbTableName() 
	{
		return 'inta_objetivo';
	}
	
}
?>