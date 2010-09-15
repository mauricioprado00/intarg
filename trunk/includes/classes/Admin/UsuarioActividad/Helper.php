<?
class Admin_UsuarioActividad_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarUsuarioActividad($usuario_actividad){
		if(!is_a($usuario_actividad,'Inta_Model_UsuarioActividad')){
			$usuario_actividad = new Inta_Model_UsuarioActividad($usuario_actividad->getData());
		}
		$existente = $usuario_actividad->setWhere(Db_Helper::equal('id_actividad'),Db_Helper::equal('id_usuario'))->search();
		if(!$usuario_actividad->hasId()){/** aca hay que agregar a la base de datos*/
			if($existente){
				Admin_App::getInstance()->addInfoMessage("El UsuarioActividad ya estaba agregado");
				$resultado = true;
			}
			else{
				$resultado = $usuario_actividad->replace()?true:false;
				//$insertada = true;// insertarEnLaBase()
				if($resultado){
					Admin_App::getInstance()->addSuccessMessage('UsuarioActividad añadida correctamente');
				}
				else{
					Admin_App::getInstance()->addErrorMessage("No se pudo agregar la UsuarioActividad, error en la operación");
					echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			if($existente&&$existente[0]->getId()!=$usuario_actividad->getId()){
				$usuario_actividad->delete(array('id'=>$usuario_actividad->getId()));
				Admin_App::getInstance()->addInfoMessage("El UsuarioActividad ya estaba agregado");
				$resultado = true;
			}
			else{
				$resultado = $usuario_actividad->update(null)?true:false;
				if($resultado){
					Admin_App::getInstance()->addSuccessMessage('UsuarioActividad actualizada correctamente');
				}
				else{
					Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la UsuarioActividad, error en la operación");
				}
			}
		}
		return($resultado);
	}
	public static function actionEliminarUsuarioActividad($id_usuario_actividad){
		if(self::eliminarUsuarioActividad($id_usuario_actividad)){
			Admin_App::getInstance()->addSuccessMessage('UsuarioActividad Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la UsuarioActividad');
		}
	}
	public static function eliminarUsuarioActividad($id_usuario_actividad){
		$usuario_actividad = new Inta_Model_UsuarioActividad();
		return($usuario_actividad->setId($id_usuario_actividad)->delete());
	}
}
?>