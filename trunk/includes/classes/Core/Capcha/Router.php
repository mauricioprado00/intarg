<?
class Core_Capcha_Router extends Core_Router_Abstract{
	protected function initialize(){
		$this->addActions(
			'show'
		);
	}
	public function show($id_novedad=0){
		$img = new Core_Capcha_SecurImage();
		$img->show();
		die();
	}
}
?>