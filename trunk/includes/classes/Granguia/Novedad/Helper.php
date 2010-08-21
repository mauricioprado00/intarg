<?
class Granguia_Novedad_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function getUrlNovedad($id_novedad){
		return('novedad/ver/'.$id_novedad);
	}


}
?>