<?
class Admin_User_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function getUrlSeleccionAgencia(){
		return 'administrator/user/seleccion_agencia';
	}
}
?>