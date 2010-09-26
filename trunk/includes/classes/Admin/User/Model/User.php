<?
class Admin_User_Model_User extends Core_Model_User{
	public function init(){
		parent::init();
		$this->setId(null)
			->setIdAgencia(null)
			->setActivo(null)
			->setUsername(null)
			->setPassword(null)
			->setNombre(null)
			->setApellido(null)
			->setPrivilegios(null)
			->setUltimoAcceso(null);
	}
//	public function setData($key, $value=null){
//		switch($key){
//			case 'password':{
//				$value = md5($value);
//				break;
//			}
//		}
//		return(parent::setData($key, $value));
//	}
//	public function load($field, $value){
//		/** aca deberiamos llamar a la case padre para cargar la fila*/
//		return(parent::load)
//		;
//		return(
//		$this->setId('1')
//			->setUsername('nombre de usuario')
//			->setNombre('nombre')
//			->setApellido('apellido')
//			->setActivo('1')
//			->setPrivilegios('todos')
//			->setUltimoAcceso('2009/07/04')
//			->setData($field, $value));
//	}
	protected function onStartSession($username){
		$this->resetData();
		$this->setUsername($username);
		$this->load();
		Core_Session::setVar('CKFINDER_ALLOWED', '1', 'CKFINDER');
		//Core_Session::setVar('BASEURL', Core_App::getUrlModel()->getUrl('skin/uploads'), 'CKFINDER');
		$baseUrl = trim(Core_App::getUrlModel()->getUrl(CONF_SUBPATH_UPLOADS),'/');
		//$baseUrl = Core_App::getUrlModel()->cleanUrl($baseUrl);
		Core_Session::setVar('BASEURL', $baseUrl, 'CKFINDER');
		//Core_Session::setVar('BASEURL', $baseUrl='skin/uploads', 'CKFINDER');
		Core_Session::setVar('BASEDIR', $baseDir=CFG_PATH_ROOT.CONF_PATH_UPLOADS, 'CKFINDER');
		$ResourceType = Core_Session::getVar('RESOURCETYPES', 'CKFINDER');
		if(!$ResourceType)
			$ResourceType = array();
		$ResourceType['Images'] = Array(
				'name' => 'Images',
				'url' => $baseUrl.'/Imagenes',// . 'image',
				'directory' => $baseDir.'Imagenes/',// . 'image',
				'maxSize' => 0,
				'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
				'deniedExtensions' => ''
		);
		$ResourceType['Flash'] = Array(
				'name' => 'Flash',
				'url' => $baseUrl.'/Flash',// . 'image',
				'directory' => $baseDir.'Flash/',// . 'image',
				'maxSize' => 0,
				'allowedExtensions' => 'swf',
				'deniedExtensions' => ''
		);
		$ResourceType['TipoPunto'] = Array(
				'name' => 'TipoPunto',
				'url' => $baseUrl.'/TipoPunto',// . 'image',
				'directory' => $baseDir.'TipoPunto/',
				'maxSize' => 0,
				'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
				'deniedExtensions' => ''
		);
		$ResourceType['Barrio'] = Array(
				'name' => 'Barrio',
				'url' => $baseUrl.'/Barrio',// . 'image',
				'directory' => $baseDir.'Barrio/',
				'maxSize' => 0,
				'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
				'deniedExtensions' => ''
		);
		$ResourceType['LogoAnuncio'] = Array(
				'name' => 'LogoAnuncio',
				'url' => $baseUrl.'/LogoAnuncio',// . 'image',
				'directory' => $baseDir.'LogoAnuncio/',
				'maxSize' => 0,
				'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
				'deniedExtensions' => ''
		);
		$ResourceType['Archivo'] = Array(
				'name' => 'Archivo',
				'url' => $baseUrl.'/Archivo',// . 'image',
				'directory' => $baseDir.'Archivo/',
				'maxSize' => 0,
				'allowedExtensions' => 'rar,ppt,xls,doc,txt,pdf,xls,bmp,png,gif,tga,jpg,jpeg,zip,tar,gz,tgz,bz2,tbz,7z,avi,mpg,mpeg,flv',
				'deniedExtensions' => ''
		);
		$ResourceType['BannerCarrousel'] = Array(
				'name' => 'BannerCarrousel',
				'url' => $baseUrl.'/BannerCarrousel',// . 'image',
				'directory' => $baseDir.'BannerCarrousel/',
				'maxSize' => 0,
				'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
				'deniedExtensions' => ''
		);
		Core_Session::setVar('RESOURCETYPES',$ResourceType, 'CKFINDER');

	}
	protected function onEndSession(){
		Core_Session::setVar(null, null, 'CKFINDER');
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
	public static function getLogedUser(){
		$_this = new self();
		//echo "loged user ".$_this->isLoged()."\n";
		if(!$_this->isLoged())
			return(null);
		return($_this);
	}
	public function actionDelete($id){
		/** aca deberiamos llamar a la case padre para eliminar la fila*/
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class($this), 'd');
		if($permisos){
			$x = new Admin_User_Model_User();
			$x->setId($id)->delete();
			//$id = $this->getId();
			$eliminada = true;//deleteEnLaBase()
			if($eliminada){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Usuario eliminado correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo eliminar usuario, error en la operación");
			}
			return($eliminada);
		}
		Core_App::getLayout()->addActions('security_restriction');
		Admin_App::getInstance()->addShieldMessage('Usted no posee los permisos necesarios para eliminar el usuario');
		return(false);
	}
	public static function actionAddEdit($data){
		/** aca deberiamos llamar a la case padre*/
		//Mat
//		parent::replace($data);<br>
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class($this), 'w');
		if($permisos){
			$x = new self();
			$x->loadFromArray($data);
			if($x->getPassword()=='')
				$x->setData('password');
			if(!$data->id){/** aca hay que agregar a la base de datos*/
				$resultado = $x->replace()?true:false;
				//$insertada = true;// insertarEnLaBase()
				if($resultado){
					Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Usuario añadido correctamente'));
				}
				else{
					Admin_App::getInstance()->addErrorMessage("No se pudo agregar usuario, error en la operación");
				}
			}
			else{/** aca hay que actualizar el registro*/
				//$actualizada = true;// actualizarEnLaBase()
				$resultado = $x->update()?true:false;
				if($resultado){
					Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Usuario actualizado correctamente'));
				}
				else{
					Admin_App::getInstance()->addErrorMessage("No se pudo actualizar usuario, error en la operación");
				}
			}
			return($resultado);
		}
		Core_App::getLayout()->addActions('security_restriction');
		Admin_App::getInstance()->addShieldMessage('Usted no posee los permisos necesarios para modificar/agregar el usuario');
		return(false);
	}
	public function getDbTableName() 
	{
		return 'inta_usuario';
	}
	public function listarPrivilegios(){/*aca habria que hacer un search en otro objeto que maneje los privilegios desde la base*/
		$privilegios = array(
			array('id'=>'444','nombre'=>'Solo Vista'),
			array('id'=>'777','nombre'=>'Todos'),
		);
		$ret = array();
		foreach($privilegios as $privilegio){
			$o = new Core_Object();
			$o->setId($privilegio['id'])
			->setNombre($privilegio['nombre']);
			$ret[] = $o;	
		}
		return($ret);
	}
	public function checkPrivilegio($privilegio, $modo='r'){
		switch($modo){
			case 'r':{
				return(true);
				return($this->getPrivilegios()=='444');
				break;
			}
			case 'w':{
				switch($this->getPrivilegios()){
					case '777':{
						return(true);
					}
					case '444':{
						return(false);
					}
				}
				break;
			}
			case 'd':{
				switch($this->getPrivilegios()){
					case '777':{
						return(true);
					}
					default:
						return(false);
				}
			}
		}
	}
	public function esSuperadmin(){
		return($this->getPrivilegios()=='777');
	}
	private $_agencia = null;
	public function getAgencia(){
		if(!$this->hasIdAgencia()||!$this->getIdAgencia())
			return null;
		if(!isset($this->_agencia)){
			$agencia = new Inta_Model_Agencia();
			$agencia->setId($this->getIdAgencia());
			if($agencia->load()){
				$this->_agencia = $agencia;
			}
		}
		return $this->_agencia;
	}
}
?>