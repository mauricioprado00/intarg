<?
class Granguia_Novedad_Block_View extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("novedad/view.phtml")
		;
	}
	public function getNovedad(){
		if($novedad = parent::getNovedad()){
			return($novedad);
		}
		$novedad = new Granguia_Model_Novedad();
		$novedad->setId($this->getIdNovedad());
		$novedad->setActivo(1);
		if(!$novedad->load())
			return(null);
		$this->setNovedad($novedad);
		return($novedad);
	}
}
?>