<?
class Core_Email2 extends Base_Layout{
	private $mailer = null;
	public function __construct($action,$design_path='frontendv3/default/'){
		$this->mailer = new Core_Mailer2();
		$this
			->setModo('email')
			->addDesignPaths('email',$design_path)
			->addActions($action);
		Core_App::getInstance()->loadLayoutUpdates($this);
	}
	public function setFrom($from, $fromname=null){
		$this->mailer->From = $from;
		if(isset($fromname)){
			$this->mailer->FromName = $fromname;
		}
	}
	public function __call($method, $args){
		if(method_exists($this->mailer, $method)){
			return call_user_func_array(array($this->mailer, $method), $args);
		}
		$this->mailer->AddAddresses('kradkk');
		echo("error, no existe la funcion $method en la clase ".__CLASS__);
		die();
	}
}
?>