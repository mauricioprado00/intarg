<?
class Granguia_Novedad_Router extends Core_Router_Abstract{
	protected function initialize(){
		$this->addActions(
			'ver'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('novedades_page');
	}
	public function ver($id_novedad=0){
		Core_App::getLayout()->addActions('novedad_ver_contenido');
		$contenido_view = 
			Core_App::getInstance()
				->loadLayoutDom()
				->getLayout()
				->getBlock('contenido_novedad');
		$contenido_view->setIdNovedad($id_novedad);
	}
}
?>