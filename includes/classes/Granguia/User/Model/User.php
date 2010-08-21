<?
class Granguia_User_Model_User extends Core_Model_User{
	public function init(){
		parent::init();
		$this->setId(null)
			->setActivo(null)
			->setUsername(null)
			->setPassword(null)
			->setNombre(null)
			->setApellido(null)
			->setEmail(null)
			->setTelefonoCodArea(null)
			->setTelefonoNumero(null)
			->setDomicilio(null)
			->setLocalidad(null)
			->setProvincia(null)
			->setPais(null)
			->setMoto(null)
			->setMotoMarca(null)
			->setMotoModelo(null)
			->setMotoAnio(null)
			->setMarcaFavorita1(null)
			->setMarcaFavorita2(null)
			->setMarcaFavorita3(null)
			->setNewsletter(null)
			->setUltimoAcceso(null);
	}
	protected function onStartSession($username){
		$this->resetData();
		$this->setUsername($username);
		$this->load();
	}
	function validate($username, $password){
		/*consultar la base de datos*/
		//echo "validando $username, $password\n";
		$x = new self();
		$x->setUsername($username);
		$x->setPassword($password);
		$x->setWhere(Db_Helper::equal('username'), Db_Helper::equal('password'));
		$cant = $x->searchCount();
		return($cant==1);
	}
//	public function login($username=null, $password=null){
//		if(!@$this&&$username===null){
//			return(false);
//		}
//		if($username!=null){
//			return(parent::login($username, $password));
//		}
//		return(parent::login($this->username, $this->password));
//	}
	public static function getLogedUser(){
		$_this = new self();
		//echo "loged user ".$_this->isLoged()."\n";
		if(!$_this->isLoged())
			return(null);
		return($_this);
	}
	public function actionDelete($id){
		/** aca deberiamos llamar a la case padre para eliminar la fila*/
		$x = new Admin_User_Model_User();
		$x->setId($id)->delete();
		//$id = $this->getId();
		$mensajes = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('contenedor_mensajes');
		$eliminada = true;//deleteEnLaBase()
		if($eliminada){
			if($mensajes)
				$mensajes->addSuccessMessage(htmlentities('Usuario eliminado correctamente'));
		}
		else{
			if($mensajes)
				$mensajes->addErrorMessage(htmlentities("No se pudo eliminar usuario, error en la operación"));
		}
		return($eliminada);
	}
	public static function actionRegistrar($data){
		$resultado = false;
		$x = new self();
		$x->setUsername($data['username']);
		$x->setWhere(Db_Helper::equal('username'));
		$l = $x->searchCount();
		if($l){
			return(null);
		}
		$resultado = $x->
				loadFromArray($data)
				->setId(null)
				->setActivo(1)
				->setUltimoAcceso(null)
				->replace()?true:false;
		return($resultado?$x:null);
	}
	public function actionEnviarCorreoRegistro($password){
		$email_layout = new Core_Email('email_registracion_usuario');
		$email_template = 
			$email_layout
				->loadDom()
				->getBlock('registracion_usuario');
		$email_template
			->setUsuario($this)
			->setPassword($password)
		;
		($sent_flag = $email_layout->enviar(
			$this->getEmail(), 
			$this->getNombre().' '.$this->getApellido(), 
			'Registacion de Usuario'
		));
		return($sent_flag?true:false);
	}
	public static function actionAddEdit($data){
		/** aca deberiamos llamar a la case padre*/
		//Mat
//		parent::replace($data);<br>
		$resultado = false;
		$x = new self();
		$resultado = $x->loadFromArray($data)->replace()?true:false;
		if(!$data->id){/** aca hay que agregar a la base de datos*/
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Core_App::getInstance()->appendLastErrorMessages('Usuario añadido correctamente');
			}
			else{
				Core_App::getInstance()->appendLastErrorMessages("No se pudo agregar usuario, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			if($resultado){
				Core_App::getInstance()->appendLastErrorMessages('Usuario actualizado correctamente');
			}
			else{
				Core_App::getInstance()->appendLastErrorMessages("No se pudo actualizar usuario, error en la operación");
			}
		}
		return($resultado);
	}
	public function getDbTableName() 
	{
		return 'bm_usuario';
	}
	protected function onLogedOk(){
		Granguia_Cart_Helper::onLogin();
	}
}
?>