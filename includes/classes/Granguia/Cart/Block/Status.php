<?
class Granguia_Cart_Block_Status extends Core_Block_Template{
	public function __construct(){
		$this->setTemplate("cart/status.phtml");
	}
	public function countItems(){
		return(Granguia_Cart_Helper::countItemsCarro());
	}
}
?>