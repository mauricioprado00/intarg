<?
abstract class Core_Block_List_Abstract extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			//->setTemplate("catalog/product/list.phtml")
			->setMaxItems(3)
			->setCurrentRelativeIndex(null)
			->setCurrentEntity(null)
			->addCustomBlockType('button', 'Core_Block_List_Button')
		;
	}
	protected function _beforeToHtml(){
		$this->setEntityes($this->search());
		
	}
	protected function resetIterator(){
		$this->setCurrentRelativeIndex(null);
		return($this);
	}
	protected function moveNext(){
		$new_index = 0;
		if($this->getCurrentRelativeIndex()!==null){
			$new_index = $this->getCurrentRelativeIndex()+1;
		}
		if($new_index>=count($this->getEntityes()))
			return(false);
		$this->setCurrentRelativeIndex($new_index);
		return(true);
	}
	protected function getCurrentEntity(){
		$entitys = $this->getEntityes();
		$keys = array_keys($entitys);
		return($entitys[$keys[$this->getCurrentRelativeIndex()]]);
	}
	protected function count(){
		$entityes = $this->getEntityes();
		return($entityes?count($entityes):0);
	}
	protected function entityToHtml(){
		$item_template = $this->getChild('list_entity_template');
		$item_template->setEntity($this->getCurrentEntity());
		return($item_template->getHtml());
	}
	abstract protected function search(); 
	abstract protected function searchCount(); 
	protected function getCantidadPaginas(){
		if($this->getMaxItems()>0)
			return(ceil($this->searchCount() / $this->getMaxItems()));
		else return(1);
	}
	protected function getPaginationInfo($pagina_actual, $cant_paginas, $padding=6, $margin=3){
		$paginas = $cant_paginas;
		$pagina = $pagina_actual;
		$pagination_info = array();
		
		$desde = $pagina-$padding;
		$hasta = $pagina+$padding;
		$desde = $desde<0?0:$desde;
		$hasta = $hasta>$paginas?$paginas:$hasta;
		//var_dump($desde,$hasta,$paginas,$pagina);
		$cantidad_ss = $margin;
		for($i=0;$i<$cantidad_ss&&$i<$paginas;$i++){
			$obj = new Core_Object();
			if($i!=$pagina)
				$pagination_info[] = $obj->setLabel(($i+1).'')->setPagina($i);
			else $pagination_info[] = $obj->setLabel(($i+1).'');
		}
		//echo "<hr>";
		
		if($i<$desde&&$i<$paginas){
			$obj = new Core_Object();
			$i = $desde;
			$pagination_info[] = $obj->setLabel('...')->setPagina(floor($desde/2));
		}
		//echo "<hr>";
		for(;$i<$hasta&&$i<$paginas;$i++){
			$obj = new Core_Object();
			if($i!=$pagina)
				$pagination_info[] = $obj->setLabel(($i+1).'')->setPagina($i);
			else $pagination_info[] = $obj->setLabel(($i+1).'');
		}
		//echo "<hr>$i-$hasta";
		if($i<($paginas-$cantidad_ss)&&$i<$paginas){
			$obj = new Core_Object();
			$i = $paginas-$cantidad_ss;
			$obj->setLabel('...')->setPagina(floor(($hasta+$paginas)/2));
			$pagination_info[] = $obj;
		}
		
		//echo "<hr>";
		for(;$i<$paginas;$i++){
			$obj = new Core_Object();
			if($i!=$pagina)
				$pagination_info[] = $obj->setLabel(($i+1).'')->setPagina($i);
			else $pagination_info[] = $obj->setLabel(($i+1).'');
		}
		//echo "<hr>";
		return($pagination_info);
	}
}
?>