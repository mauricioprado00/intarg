<?php /*es útf8*/
class Frontend_Cronjob_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addActions('minisitio','contacto','contar');
	}
	protected function localDispatch(){
		return $this->dispatchAll();
	}
	private function dispatchAll(){
		error_reporting(E_ALL);
		chdir(dirname(__FILE__));
		
		$maximo_tiempo_seg = (60/*minuto*/)*60;//minutos
		$maximo_tiempo_seg+= 5;
		$maximo_tiempo_seg = 0;
		ini_set("max_execution_time",$maximo_tiempo_seg);
		Core_Http_Header::ContentType('text/plain');
		//Helpers::HeaderContentType();
		//este archivo debe ser alimentado todos los minutos
		$db = Granguia_Db::getInstance();
		//para evitar que se ejecute mas de un cronjob a la vez
		//$db->exec('lock tables gg_cronjobs_results write');
//		$cronjob_result = new Granguia_Model_CronjobResult();
//		$cronjob_result->lockWrite();
		$cronjob = Core_Cronjob::getInstance();
		$cronjob->Init();
		$cronjob->AppendCronjobs(
			'Granguia_Cronjob_Estadistica_ClickAnuncio', 
			'Granguia_Cronjob_Estadistica_InicioSesion',
			'Granguia_Cronjob_Estadistica_ClickMinisitio',
			'Granguia_Cronjob_Estadistica_AccesoSesion',
			'Granguia_Cronjob_Estadistica_ClickBannerDinamico',
			'Granguia_Cronjob_Estadistica_ClickBannerCarrousel',
			'Granguia_Cronjob_Estadistica_AccesoCategoria',
			'Granguia_Cronjob_Estadistica_Cleaner'
		);
		$cronjob->Polling();
//		$db->unlockTables();
		exit();
	}
	protected function dispatchNode(){
		return $this->dispatchAll();
	}
}
?>