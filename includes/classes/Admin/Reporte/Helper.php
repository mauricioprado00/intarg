<?
//Para hacer filtros personalizados, meto en el __construct:
//$this->addAutofilterFieldInput('fecha_desde', array($this,'mifiltropersonalizadodeentrada'));
//Después defino un método mifiltropersonalizadodeentrada en la clase y listo, esto es porque lo que va en el array pasa derecho al call_user_func

class Admin_Reporte_Helper extends Core_Singleton{
//        public function  __construct() {
//            parent::__construct();
//            $coreInstance->addAutofilterFieldInput('fecha_desde', array('Mysql_Helper','filterDateInput'));
//            $coreInstance->addAutofilterFieldInput('fecha_hasta', array('Mysql_Helper','filterDateInput'));
//        }

	public function getUrlExportarFormato1(){
		return Core_App::getUrlModel()->getUrl('administrator/reporte/formato1');
	}
	public function getUrlExportarFormato2(){
		return Core_App::getUrlModel()->getUrl('administrator/reporte/formato2');
	}
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
		$reporte = new Inta_Model_Reporte_Actividad();
		return($reporte->setId($id_reporte)->delete());
	}
        public static function buscarActividadReporte($data){
            $a = new Inta_Model_View_ReporteActividad();

            $aWheres = array();

            if(!empty($data['id_responsable']))
                array_push($aWheres,Db_Helper::equal('id_responsable',$data['id_responsable']));
            if(!empty($data['id_agencia']))
                array_push($aWheres,Db_Helper::equal('id_agencia',$data['id_agencia']));
            if(!empty($data['audiencia']))
                array_push($aWheres,Db_Helper::like('audiencia','%',$data['audiencia'],'%'));
            if(!empty($data['resultado_esperado']))
                array_push($aWheres,Db_Helper::like('resultado_esperado','%',$data['resultado_esperado'],'%'));
            if(!empty($data['objetivo']))
                array_push($aWheres,Db_Helper::like('objetivo','%',$data['objetivo'],'%'));
            if(!empty($data['id_usuario'])){
                array_push($aWheres,Db_Helper::in('id_usuario_involucrado',true,$data['id_usuario']));
            }
            if(!empty($data['estado'])&&$data['estado'] !== ''){
                array_push($aWheres,Db_Helper::equal('estado',$data['estado']));
            }else{
                //array_push($aWheres,Db_Helper::equal('estado','planificado'));
            }
            if(!empty($data['ano'])){
                array_push($aWheres,Db_Helper::equal('ano',$data['ano']));
            }
            if (!empty($data['fecha_desde'])) {
                $coreInstance = self::getInstance();
                $coreInstance->addAutofilterFieldInput('fecha_desde', array('Mysql_Helper','filterDateInput'));
                $coreInstance->setFechaDesde($data['fecha_desde']);
                array_push($aWheres, Db_Helper::custom('fecha_inicio >= "'.$coreInstance->getFechaDesde().'"' ));
//                array_push($aWheres, Db_Helper::lt('fecha_inicio', $coreInstance->getFechaDesde()));
            }
            if(!empty($data['fecha_hasta'])){
                $coreInstance = empty($coreInstance)?self::getInstance():$coreInstance;
                $coreInstance->addAutofilterFieldInput('fecha_hasta', array('Mysql_Helper','filterDateInput'));
                $coreInstance->setFechaHasta($data['fecha_hasta']);
                array_push($aWheres,Db_Helper::custom('fecha_fin <= "'.$coreInstance->getFechaHasta().'"'));
//                array_push($aWheres,Db_Helper::equal('fecha_fin',$coreInstance->getFechaHasta()));
            }
            if(!empty($data['custom_keyword'])){
                array_push($aWheres, ' AND (');
                array_push($aWheres,Db_Helper::like('objetivo','%',$data['custom_keyword'],'%'));
                array_push($aWheres, 'OR');
                array_push($aWheres,Db_Helper::like('nombre_actividad','%',$data['custom_keyword'],'%'));
                array_push($aWheres, 'OR');
                array_push($aWheres,Db_Helper::like('nombre_responsable','%',$data['custom_keyword'],'%'));
                array_push($aWheres, 'OR');
                array_push($aWheres,Db_Helper::like('nombre_agencia','%',$data['custom_keyword'],'%'));
                array_push($aWheres, 'OR');
                array_push($aWheres,Db_Helper::like('resultado_esperado','%',$data['custom_keyword'],'%'));
                array_push($aWheres,')');
            }
//            var_dump($aWheres);

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
            $debug = false;

            $a->setWhereByArray($aWheres);
            if($debug){
				echo Core_Helper::DebugVars($a->searchGetSql());
//                $r = $a->searchGetSql();
//                var_dump($r);die();
            }
            $arr = $a->search(null, 'ASC',null, 0, false);
            return($arr);
	}
	public static function agregarActividades($ids_actividades){
		if(!$ids_actividades || !is_array($ids_actividades) || !count($ids_actividades)){
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo agregar actividades'));
			return false;
		}
		$usuario = Admin_User_Model_User::getLogedUser();
		$agencia = Admin_Helper::getInstance()->getAgencia();
		$resultado = new Inta_Model_Reporte_Actividad();
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
			//echo Core_Helper::DebugVars(get_class($resultado));
			$resultado->insert();
		}
		Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Se agregaron {!cantidad} actividades al reporte', (array('cantidad'=>count($ids_actividades)))));
		return true;
	}
	public function clearReportesDeUsuario($id_usuario = null){
		if(!isset($id_usuario)){
			$id_usuario = Admin_User_Model_User::getLogedUser()->getId();
		}
		if(!$id_usuario){
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo limpiar los resultados de búsqueda'));
		}
		
			
		$reporte = new Inta_Model_Reporte_Actividad();
		$reporte->setIdUsuarioLogeado($id_usuario);
		if($cantidad = $reporte->searchCount()){
			if($reporte->delete()){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Se eliminaron {!cantidad} de resultados', array('cantidad'=>$cantidad)));
			}
			else{
				Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudieron eliminar {!cantidad} resultados', array('cantidad'=>$cantidad)));
			} 
		}
		else{
			Admin_App::getInstance()->addInfoMessage(self::getInstance()->__t('No hay resultados resultados de busqueda a eliminar'));
		}
	}
}
?>
