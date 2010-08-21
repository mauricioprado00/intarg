<?
class Admin_Categoria_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function getLinkUrlChangeParent(){
		return('administrator/categoria/change');
	}
	public static function getLinkUrlAdd(){
		return('administrator/categoria/addEdit');
	}
	public static function getLinkUrlEdit($id){
		return('administrator/categoria/addEdit/'.$id);
	}
	public static function getLinkUrlDelete($id){
		return('administrator/categoria/delete/'.$id);
	}
	public static function actionAgregarEditarCategoria($categoria){
		if(!is_a($categoria,'Granguia_Model_Categoria')){
			$categoria = new Granguia_Model_Categoria($categoria->getData());
		}
		if(!$categoria->hasId()){/** aca hay que agregar a la base de datos*/
			try{
				if(!$categoria->hasOrden()||!$categoria->getOrden())
					$categoria->setOrden(2000);
				$resultado = $categoria->replace()?true:false;
			}catch(Exception $e){
				Admin_App::getInstance()->addErrorMessage($e->description);
			}
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Categoria añadido correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar el Categoria, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $categoria->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Categoria actualizado correctamente');
			}
			else{
				foreach($categoria->getTranslatedErrors() as $error)
					Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar el Categoria, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarCategoria($id_categoria){
		if(self::eliminarCategoria($id_categoria)){
			Admin_App::getInstance()->addSuccessMessage('Categoria Eliminado Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar el Categoria');
		}
	}
	public static function eliminarCategoria($id_categoria){
		$categoria = new Granguia_Model_Categoria();
		$categoria->setId($id_categoria);
		if(!$categoria->load())
			return false;
		if($categoria->hasIdNodo())
			if(!Admin_Nodo_Helper::eliminarNodo($categoria->getIdNodo()))
				return false;
		return($categoria->delete(array('id'=>$id_categoria)));
	}
	public static function getTree($root_name=null){
		return Granguia_Model_Categoria::getTree($root_name);
	}
}
?>