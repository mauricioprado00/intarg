<?
class Admin_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function getAgencia(){
		$usuario = Admin_User_Model_User::getLogedUser();
		if(!$usuario)
			return null;
		return $usuario->getAgencia();
//		$agencia = new Inta_Model_Agencia();
//		$agencia->setId(1);
//		if(!$agencia->load())
//			return false;
//		return $agencia;
	}
	public function getIdAgencia(){
		$agencia = $this->getAgencia();
		if(!$agencia)
			return null;
		return $agencia->getId();
	}
}
?>