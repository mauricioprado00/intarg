<?
class Jqgrid_Block_XmlList extends Core_Block_XmlTemplate{
	public function __construct(){
		parent::__construct();
		$this
		->setTemplate('jqgrid/datalist.phtml')
		->setListFields(array())
		->setVirtualFields(array())
		->addArrayData('output_filters')
		->setOutputFilters(array());
	} 
	protected function _beforeToHtml(){
		
	}
	protected function outputFilter($value){
		foreach($this->getOutputFilters() as $filter){
			$value = call_user_func($filter, $value);
		}
		return($value);
	}
	private $_field_filter_output = array();
	public function addAutofilterFieldOutput($key, $filter1, $param1=null, $param2=null){
//		$filters = array_slice($args = func_get_args(), 1);
//		if(!$filters)
//			return;
		//$this->_field_filter_output[$key] = $filters;
		$this->_field_filter_output[$key][] = ($args = func_get_args());
	}
	public function getDatos(){
		$p = Core_Http_Get::getParameters('object');
		$comparator = $this->assembleComparator($p);
		$datos = $this->loadData($p->page, $p->rows, $p->sidx, $p->sord, $comparator);
		foreach($datos as $dato)
			foreach($this->_field_filter_output as $key=>$filters){
				foreach($filters as $filter)
					call_user_func_array(array($dato, 'addAutofilterFieldOutput'), $filter);
				//$dato->addAutofilterFieldOutput($key, $filter);
			}
		return($datos);
	}
	private function assembleComparator($post){
		if(!$post->searchField||$post->_search=='false')
			return(null);
		switch($post->searchOper){
			case 'bw':{
				return(Db_Helper::like($post->searchField,'%',$post->searchString));
				break;
			}
			case 'cn':{
				return(Db_Helper::like($post->searchField,'%',$post->searchString,'%'));
				break;
			}
			case 'ew':{
				return(Db_Helper::like($post->searchField,null,$post->searchString,'%'));
				break;
			}
			case 'eq':{
				return(Db_Helper::equal($post->searchField,$post->searchString));
				break;
			}
			case 'lt':{
				return(Db_Helper::between($post->searchField, null, $post->searchString));
				break;
			}
			case 'gt':{
				return(Db_Helper::between($post->searchField, $post->searchString));
				break;
			}
		}
		return(null);
	}
	protected function loadData($page, $rows, $sidx, $sord, $comparator){
		return;
	}
}
?>