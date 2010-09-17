<?php 
class Admin_Chained_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function getUrlChainControl($o){
		return 'administrator/chained/'.get_class($o);
	}
}

?>