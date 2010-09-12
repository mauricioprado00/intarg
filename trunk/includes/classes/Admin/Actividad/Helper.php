<?
class Admin_Actividad_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
        public static function adapterActividad($post){
            
        }
        public static function actionAgregarEditarActividad($actividad, $aActividadProyecto, $aResultadoEsperadoActividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		if(!$actividad->getIdNodo()){
			$actividad->setIdNodo(null);
		}
		if(!$actividad->hasId()){/** aca hay que agregar a la base de datos*/
//                    die("soy un kradekk");
			$resultado = $actividad->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Actividad añadida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Actividad, error en la operación");
			}
                        //Mat, meto el link actividad proyecto
                        $resultadoActividadProyecto = true;
                        foreach($aActividadProyecto As $actividad_proyecto){
                            $actividad_proyecto->setIdActividad($actividad->getId());
                            if(!$actividad_proyecto->replace())
                                $resultadoActividadProyecto = false;
                        }

                        //Mat, meto el link actividad resultado esperado
                        $resultadoActividadResultadoEsperado = true;
                        foreach($aResultadoEsperadoActividad As $resultado_esperado_actividad){
                            $resultado_esperado_actividad->setIdActividad($actividad->getId());
                            if(!$resultado_esperado_actividad->replace())
                                $resultadoActividadResultadoEsperado = false;
                        }
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $actividad->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Actividad actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Actividad, error en la operaci�n");
			}
//                        return($resultado);

//                        $actividad_proyecto = new Inta_Model_ActividadProyecto();
//                        $actividad_proyecto->delete(array('id_actividad'=>$id_actividad));
                         //Mat, meto el link actividad proyecto
                        $resultadoActividadProyecto = true;
                        foreach($aActividadProyecto As $actividad_proyecto){
                            $actividad_proyecto->setIdActividad($actividad->getId());
                            if(!$actividad_proyecto->replace())
                                $resultadoActividadProyecto = false;
                        }

                         //Mat, meto el link actividad resultado esperado
                        $resultadoActividadResultadoEsperado = true;
                        foreach($aResultadoEsperadoActividad As $resultado_esperado_actividad){
                            $resultado_esperado_actividad->setIdActividad($actividad->getId());
                            if(!$resultado_esperado_actividad->replace())
                                $resultadoActividadResultadoEsperado = false;
                        }

//                        $actividad_proyecto->setIdActividad($actividad->getId());
//                        echo "<br>IDACTIVIDAD: " . $actividad->getId();
//                        $contador = 0;
//                        foreach($actividad['id_proyecto'] As $var_id_proyecto){
//                            $actividad_proyecto->setIdProyecto($var_id_proyecto);
//                            $actividad_proyecto->setMonto(isset($actividad['monto['.$contador.']']) ? $actividad['monto'] : 0);
//                            echo "<br>vd_actividad_proyecto: " . var_dump($actividad_proyecto);
//                            $resultado_proyecto = $actividad_proyecto->replace()?true:false;
//                            if($resultado_proyecto){
//                             Admin_App::getInstance()->addSuccessMessage('Actividad añadida correctamente');
//                            }
//                            else{
//                                    Admin_App::getInstance()->addErrorMessage("No se pudo relacionar con el Proyecto, error en la operación");
//                            }
//                            $contador ++;
//                        }
 		}
//                $resultado = $resultado&&$resultado_proyecto?true:false;
		return($resultado);
	}
	public static function actionEliminarActividad($id_actividad){
		if(self::eliminarActividad($id_actividad)){
			Admin_App::getInstance()->addSuccessMessage('Actividad Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Actividad');
		}
	}
	public static function eliminarActividad($id_actividad){
		$actividad = new Inta_Model_Actividad();
		return($actividad->setId($id_actividad)->delete());
	}
}
?>