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
	private function isExport(){
		//hay que determinar por algun parametro post si hay que exportar
		return Core_Http_Post::getParameters('Core_Object')->hasXmlListExportTo();
		//return true;
	}
	private function getExportHandlerClass(){
		return Core_Http_Post::getParameters('Core_Object')->getXmlListExportTo();
		return 'Jqgrid_XmlList_ExportHandler_Xlst';
	}
	private function getXmlExportHandler(){
		if($this->isExport()){
			if(($class = $this->getExportHandlerClass())&&class_exists($class)){
				if($o = new $class){
					$o
						->setXmlList($this)
					;
					return $o;
				}
			}
		}
	}
	private function handleExport(){
		if($export_handler = $this->getXmlExportHandler()){
			return $export_handler->ExportXml();
		}
		return false;
	}
//	public function toXml(){
//		return parent::_toHtml();
//	}
	public function _toHtml(){
		if($r = $this->handleExport()){
			return $r;
		}
		return parent::_toHtml();
	}
	protected function _beforeToHtml(){
		$this->FindFieldsRenderer();
	}
	private $_field_renderer = array();
	private function FindFieldsRenderer(){
		$lf = $this->getListFields();
		$vf = $this->getVirtualFields();
		$arr_fieldname = array_merge($lf, $vf);
		if(false){//estrategia 1, busco los hijos y me fijo el campo que renderizan (no funciona para mas de un campo)
			foreach($this->getChild() as $child){
				if($child instanceof Jqgrid_Block_Field_Renderer){
					if($fieldname = $child->getFieldName()){
						if(in_array($fieldname, $arr_fieldname)){
							$this->_field_renderer[$fieldname] = $child;
						}
					}
				}
			}
		}
		else{//estratgia 2, miro mis campos y le pregunto a todos mis hijos si lo saben renderizar, mas flexible
			foreach($arr_fieldname as $fieldname){
				foreach($this->getChild() as $child){
					if($child instanceof Jqgrid_Block_Field_Renderer){
						if($child->canRender($fieldname)){
							$this->_field_renderer[$fieldname] = $child;
						}
					}
				}
			}
		}
	}
	protected function renderValue($item, $fieldname, $value){
		if(isset($this->_field_renderer[$fieldname])){
			$renderer = $this->_field_renderer[$fieldname];
			$html = $renderer
				->setObject($item)
				->setFieldName($fieldname)
				->setValue($value)
				->toHtml()
			;
			return $html;
		}
		return $value;
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
	private $parameters = null;
	public function setParameters($p){
		$this->parameters = $p;
		return $this;
	}
	public function getParameters(){
		if(!isset($this->parameters)){
			$this->parameters = Core_Http_Get::getParameters('object');
		}
		return $this->parameters;
	}
	public function getDatos(){
		//$p = Core_Http_Get::getParameters('object');
		$p = $this->getParameters();
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

		if(!$post->searchField||$post->_search==='false'||$post->_search===false)
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