<?
class Granguia_Cart_Block_List extends Core_Block_List_Abstract{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("cart/list.phtml")
			->setMaxItems(2)
			->setPagina(0)
		;
	}
	private $botones = null;
	protected function getButtons(){
		if(!$this->botones || !count($this->botones)){
			$but1 = new Core_Object();
			$but1->setTexto("Actualizar")->setAccion("actualizar");
			$but2 = new Core_Object();
			$but2->setTexto("Continuar")->setAccion("continuar");
			$botones[] = array(
				$but1,
				$but2,
			);
			
		}
		else{
			$botones = $this->botones;
		}
		return($botones);
	}
	public function onBeforeInsertChild($block){
		if(get_class($block)=='Core_Block_List_Button'){
			$new = new Core_Object();
			$new
				->setTexto($block->getTexto())
				->setAccion($block->getAccion())
			;
			$this->botones[] = $new;
			return(null);
		}
		return($block);
	}
	protected function search(){
		return(Granguia_Cart_Helper::listarCarroSearchKeys(
			$this->getPagina()*$this->getMaxItems(),
			$this->getMaxItems()
		));
	}
	protected function searchCount(){
		return(count(Granguia_Cart_Helper::listarCarroSearchKeys()));
	}
	protected function calcularTotal(){
		return(Granguia_Cart_Helper::calcularTotalCarro());
		return($total);
	}
	protected function getFormUrl(){
		if(!$this->hasFormUrl()){
			return($this->getUrl(Granguia_Cart_Helper::getUrlVerCarro()).$this->getPagina());
		}
		else return($this->getUrl($this->getData('form_url')));
	}
}
?>