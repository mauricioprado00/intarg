<?php

		class Test_Wheres_Testee extends Inta_Db_Model_Abstract{
			public function init(){
				parent::init();
				$datafields = array(
					'id',
					'id_agencia',
					'estado',
					'ano',
					'objetivo',
					'nombre_actividad',
					'nombre_responsable',
					'nombre_agencia',
					'resultado_esperado',
				);
				foreach($datafields as $datafield)
					$this->setData($datafield);
			}
			public function getDbTableName() 
			{
				return 'inta_algo';
			}
		}

class Test_Wheres extends Core_Singleton{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function ComplexConditions(){
		$x = new Test_Wheres_Testee();
		$x->setWhere(
			Db_Helper::equal('id_agencia', '1'),
			Db_Helper::equal('estado', 'planificado'),
			Db_Helper::equal('ano', '2010'),
			'AND (',
				Db_Helper::like('objetivo', '%', 'de prueba', '%'),'OR',
				Db_Helper::like('nombre_actividad', '%', 'de prueba', '%'),'OR',
				Db_Helper::like('nombre_responsable', '%', 'de prueba', '%'),'OR',
				Db_Helper::like('nombre_agencia', '%', 'de prueba', '%'),'OR',
				Db_Helper::like('resultado_esperado', '%', 'de prueba', '%'),
			')'
		);
		echo $x->searchGetSql();

	}
}
?>