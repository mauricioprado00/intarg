<?
class Admin_Reporte_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarReporte($reporte){
		if(!is_a($reporte,'Inta_Model_Reporte_Actividad')){
			$reporte = new Inta_Model_Reporte_Actividad($reporte->getData());
		}
		if(!$reporte->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $reporte->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Reporte a�adida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Reporte, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $reporte->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Reporte actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Reporte, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarReporte($id_reporte){
		if(self::eliminarReporte($id_reporte)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Reporte Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Reporte'));
		}
	}
	public static function eliminarReporte($id_reporte){
		$reporte = new Inta_Model_Reporte();
		return($reporte->setId($id_reporte)->delete());
	}
        public static function buscarActividadReporte($data){
            echo "<pre>";
            var_dump($data);
            echo "</pre>";die();
//            $a = new Inta_Model_Actividad();
//                  ["id_agencia"]=>
//                  ["audiencia"]=>
//                  ["resultado_esperado"]=>
//                  ["objetivo"]=>
//                  ["id_responsable"]=>
//                  ["id_usuario"]=>

            $a = new Inta_Model_View_ReporteActividad();

            $aWheres = array();

//            if(!empty($data['id_responsable']))
//                array_push($aWheres,Db_Helper::equal('id_responsable',$data['id_responsable']));
//            if(!empty($data['id_agencia']))
//                array_push($aWheres,Db_Helper::equal('agencia_id',$data['id_agencia']));
//            if(!empty($data['audiencia']))
//                array_push($aWheres,Db_Helper::like('audiencia','%'.$data['audiencia'].'%'));
//            if(!empty($data['resultado_esperado']))
//                array_push($aWheres,Db_Helper::like('resultado_esperado','%'.$data['resultado_esperado'].'%'));
//            if(!empty($data['objetivo']))
//                array_push($aWheres,Db_Helper::like('objetivo','%'.$data['objetivo'].'%'));
//            var_dump($data[id_usuario]);die();
//            if(!empty($data['id_usuario'])){
//                array_push($aWheres,Db_Helper::in('id_usuario_involucrado',true,$data['id_usuario']));
//            }
            if(!empty($data['estado'])&&$data['estado'] != ''){
                array_push($aWheres,Db_Helper::equal('estado',true,$data['estado']));
            }else{
                array_push($aWheres,Db_Helper::equal('estado',true,'planificado'));
            }
            if(!empty($data['fecha_desde'])){
                array_push($aWheres,Db_Helper::equal('fecha_inicio',true, $data['fecha_desde']));
            }
            if(!empty($data['fecha_hasta'])){
                array_push($aWheres,Db_Helper::equal('fecha_fin',true, $this->$data['fecha_hasta']));
            }
            if(!empty($data['mes_enero'])){
                array_push($aWheres,Db_Helper::equal('mes_enero',true,1));
            }
            if(!empty($data['mes_febrero'])){
                array_push($aWheres,Db_Helper::equal('mes_febrero',true,1));
            }
            if(!empty($data['mes_marzo'])){
                array_push($aWheres,Db_Helper::equal('mes_marzo',true,1));
            }
            if(!empty($data['mes_abril'])){
                array_push($aWheres,Db_Helper::equal('mes_abril',true,1));
            }
            if(!empty($data['mes_mayo'])){
                array_push($aWheres,Db_Helper::equal('mes_mayo',true,1));
            }
            if(!empty($data['mes_junio'])){
                array_push($aWheres,Db_Helper::equal('mes_junio',true,1));
            }
            if(!empty($data['mes_julio'])){
                array_push($aWheres,Db_Helper::equal('mes_julio',true,1));
            }
            if(!empty($data['mes_agosto'])){
                array_push($aWheres,Db_Helper::equal('mes_agosto',true,1));
            }
            if(!empty($data['mes_septiembre'])){
                array_push($aWheres,Db_Helper::equal('mes_septiembre',true,1));
            }
            if(!empty($data['mes_octubre'])){
                array_push($aWheres,Db_Helper::equal('mes_octubre',true,1));
            }
            if(!empty($data['mes_noviembre'])){
                array_push($aWheres,Db_Helper::equal('mes_noviembre',true,1));
            }
            if(!empty($data['mes_diciembre'])){
                array_push($aWheres,Db_Helper::equal('mes_diciembre',true,1));
            }
            $a->setWhereByArray($aWheres);
            $r = $a->searchGetSql();
            var_dump($r);die();
            $arr = $a->search(null, 'ASC',null, 0, false);
//            $arr = $a->search();
//            var_dump($arr[0]);die();
//            $c = new Core_Collection($a->search());
//
//            $c =$c->groupBy('actividad_id');
//            $q = $a->searchGetSql();
//            var_dump($c);
//            die();

            $resultadoActividad = new Inta_Model_Reporte_Actividad();
//            $resultadoActividad->insert($arr[0]);
//            var_dump($xc = $resultadoActividad->insert($arr[0],false,true));
//            die();
            $resultadoActividad ->loadFromArray($arr[0]);
//            var_dump($resultadoActividad);die();
            $resultadoActividad->replace();
            return;
	}
	public static function agregarActividades($ids_actividades){
		if(!$ids_actividades || !is_array($ids_actividades) || !count($ids_actividades)){
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo agregar actividades'));
			return false;
		}
		$usuario = Admin_User_Model_User::getLogedUser();
		$agencia = Admin_Helper::getInstance()->getAgencia();
		$resultado = new Core_Object();//aca usar la clase de verdad
		$resultado
			->setIdAgencia($agencia->getId())
			->setNombreAgencia($agencia->getNombre())
			->setIdUsuarioLogeado($usuario->getId())
		;
		
		foreach($ids_actividades as $id_actividad){
			$actividad = new Inta_Model_Actividad();
			if(!$actividad->load(array('id'=>$id_actividad)))
				continue;
			if(!($responsable = $actividad->getResponsable())){
				continue;
			}
			$resultado
				->setIdActividad($id_actividad)
				->setNombreActividad($actividad->getNombre())
				->setIdResponsable($responsable->getId())
				->setNombreResponsable($responsable->getNombre())
			;
			//$resultado->insert();
		}
		Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Se agregaron {!cantidad} actividades al reporte', (array('cantidad'=>count($ids_actividades)))));
		return true;
	}
}
?>