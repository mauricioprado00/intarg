<?php
/**
 *@referencia Agencia(id_agencia) Inta_Model_Agencia(id)
*/
class Inta_Collection_Grouped_Reporte_ByAgencia extends Core_Collection_Grouped{
	protected function getXmlEntityTagname(){
		return 'inta_actividad_byagencia';
		//return str_replace('inta_', '', $this->getDbTableName());
	}
	public function getAgencia(){
		if(!$this->hasIdAgencia())
			return null;
		//todo: mejorar, esto deberia hacerse con el docbloc de "referencia", pero eso funciona solo para Model_abstract habria que hacerlo para todos los objetos, previo comprobar performance
		$agencia = new Inta_Model_Agencia();
		$agencia->setId($this->getIdAgencia());
		if($agencia->load()){
			return $agencia;
		}
		return null;
	}
	public function getAudienciasPriorizadas(){
		$audiencias_priorizadas = array();
		foreach($this->getItems() as $byobjetivo){
			$objetivo = $byobjetivo->getObjetivo();
			$problemas = $objetivo->getListProblema();
			if($problemas)
				foreach($problemas as $relacion_problema){
					$problema = $relacion_problema->getProblema();
					if($audiencia = $problema->getAudiencia()){
						if(!isset($audiencias_priorizadas[$audiencia->getId()]))
							$audiencias_priorizadas[$audiencia->getId()] = $audiencia;
					}
				}
		}
		if(!count($audiencias_priorizadas))
			return null;
		return new Core_Collection($audiencias_priorizadas);
	}

}
?>