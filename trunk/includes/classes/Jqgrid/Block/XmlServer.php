<?
class Jqgrid_Block_XmlServer extends Core_Block_Abstract{
	private $xs = null;
	private $params = null;
	public function getHtml(){
		return $this->toHtml();
	}
	public static function groupByTag($xml,$exclude_tags=null){
		//var_dump($x = func_get_args());//		var_dump($xml);//		echo $xml->asXML();
		if(!$xml||!isset($xml[0])||!($xml[0] instanceof domnode))
			return new DOMNode;
		$name = $xml[0]->nodeName;
		if($exclude_tags){
			$exclude_tags = explode(',', $exclude_tags);
			if(in_array($name, $exclude_tags)){
				return new DOMNode;
			}
		}
		
		$root = $xml[0]->ownerDocument->createElement('group');
		$return = array();
		foreach($xml as $node){
			if($name == $node->nodeName)
				$root->appendChild($node);
		}
		return $root;
		//	//$newDom = new DOMDocument('1.0');//$root = $newDom->createElement('root');//$root = $newDom->appendChild($root);//foreach($xml as $item){//	$domNode = $newDom->importNode($item, true);//	$root->appendChild($domNode);//}//$xPath = new DOMXPath($newDom);//return $xPath->query('./*');//echo $newDom->saveXML();//echo $xml[0]->asXML();//		return $xml[0];//		return array($xml);//		die("ok llamando a ble");
	}
	public static function xmlTextToNodes($xml){
		$doc = new DOMDocument();
		$html_encoded =  $xml[0]->ownerDocument->saveXML($xml[0]->childNodes->item(0));
		$html = html_entity_decode($html_encoded);
		$doc->loadXML('<root>'.$html.'</root>');
		return $doc->childNodes->item(0);
		foreach($doc->childNodes as $child){
			echo $child->nodeName;
		}
		die();
		$root = $xml[0]->ownerDocument->createElement('group');
		foreach($xml as $node){
			if($name == $node->nodeName){
				$root->appendChild($node);
			}
		}
		return $root;
		var_dump($xml[0]->textContent);
		die("ok creando nodos");
	}
	public function __construct(){
		parent::__construct();
		$this->xs = new Core_Xslt_Server();
		$this->params = new Core_Object();
		$this->params->setXmlEntityTagname('params');
	}
	public function setParamsXmlEntityTagname($name){
		$this->params->setXmlEntityTagname($name);
		
	}
	public function addParam($name,$value){
		if(!is_object($name)){
			$this->_addParam($name, $value);
			return;
		}
		if(!($name instanceof SimpleXMLElement))
			return;
		$args = func_get_args();
		foreach($args as $arg){
			
		}
		
//		var_dump($this->params->getData());
//		die();	
		//$this->params[$name] = $value;
	}
	private function _addParam($name, $value){
		$this->params->setData($name, $value);
	}
	public function appendStyle($file, $weight=0){
		if(isset($_GET['nostyles']))
			return;
		$file = $this->getLayout()->getDesignFilePath($file);
		if(!$file)
			return;
		$this->xs->appendStyle($file, $weight);
	}
	private $entity_classname = null;
	public function setEntityClassname($classname){
		$this->entity_classname = $classname;
		return $this;
	}
	private function getEntityClassname(){
		return $this->entity_classname;
	}
	protected function getSource(){
		$datos = $this->_getDatos();
		if(is_array($datos)){
			return new Core_Collection($datos); 
		}
		elseif(is_object($datos)){
			if($datos instanceof Core_Object)
				return $datos;
		}
		return null;
	}
	private $data_model = null;
	public function setDataModel($data_model){
		$this->data_model = $data_model;
	}
	protected function getDataModel(){
		return $this->data_model;
	}
	protected function getResultsMetadata(){
		$params = $this->getQueryParameters();
		$r = new Core_Object();
		$r->setXmlEntityTagname('results');
		$r
			->setPage($params->page)
			//->setRecords($this->CountResults())
			->setRecords($this->contarDatos())
			->setTotal($this->getPageCount())
		;	
		return $r;
		
	}
//	public function hasParams(){
//		return $this->params->hasData();
//	}
	protected function setHardParams($params){
		if(Core_Http_Post::hasParameters()){
			$post = Core_Http_Post::getParameters('object');
			if($post->rango=='todo'){
				$params->setRango('todo');
			}
			else{
				$params->setRango('page');
			}
		}
		if($config = $this->getConfig()){
			$columnas = array();
			foreach($config->grid->colModel as $idx=>$columna){
				if($columna->hideinexport)
					continue;
				$columna->title = $config->grid->colNames[$idx];
				$columnas[] = $columna;
			}
			$params->setColumnas($columnas);
		}
	}
	protected function getParams(){
		$this->setHardParams($this->params);
		return $this->params;
	}
	public function _toHtml(){
		$source = $this->getSource();
		if(!($source instanceof Core_Collection)){
			$s = new Core_Collection();
			$s->addItem($source);
			$source = $s;
		}
		$params = $this->getResultsMetadata();
		if($this->getParams())
			$source->addItem($this->getParams());
		$source->addItem($params);
		$this->xs->setSource($source, $this->getDataModel());
		//$datos->toXmlString();
		//$this->xs->setParameter('aaa','bbb');
		return $this->xs->toXmlString();
		//return($this->getTemplateHtml());
	}
	public function setParameters($p){
		$this->parameters = $p;
		return $this;
	}
	protected function getConfig(){
		if(!isset($this->_config)){
			$json_config = Core_Http_Post::getParameters('Core_Object')->getData('json_config');
			if($config = @json_decode($json_config)){
				$this->_config = $config;
			}
		}
		return $this->_config;
	}
	protected function getParameterFromConfig(){
		$config = $this->getConfig();
		if($config){
			return $config->grid->postData;;
		}
	}
	public function getQueryParameters(){
		if(!isset($this->parameters)){
			if(!($this->parameters = $this->getParameterFromConfig()))
				$this->parameters = Core_Http_Get::getParameters('object');
			$post = Core_Http_Post::getParameters('object');
			if($post->rango=='todo'){
				$this->parameters->rows = -1;
				$this->parameters->page = 1;
			}
			if(!$post->utilizar_filtros){
				$this->parameters->_search = false;
				$this->parameters->searchField = '';
				$this->parameters->searchString = '';
				$this->parameters->searchOper = '';
			}
		}
		return $this->parameters;
	}
	private function contarDatos(){
		$this->_getDatos();
		return count($this->_datos);
	}
	private $_datos;
	private function _getDatos(){
		if(!isset($this->_datos)){
			$this->_datos = $this->getDatos();
		}
		return $this->_datos;
	}
	public function getDatos(){
		//$p = Core_Http_Get::getParameters('object');
		$p = $this->getQueryParameters();
		$comparator = $this->assembleComparator($p);
		$datos = $this->loadData($p->page, $p->rows, $p->sidx, $p->sord, $comparator);
		return($datos);
	}
	public function getPageSize(){
		return $this->getQueryParameters()->rows;
	}
	public function getPageCount(){
		if(!$this->getPageSize())
			return 1;
		return intval(ceil($this->CountAll()/$this->getPageSize()));
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
	private function canLoadDataFromEntity(){
		return $this->_getEntityToList();
	}
	protected function getEntityToList(){
		$class = $this->getEntityClassname();
		return new $class;
	}
	private $entity_to_list = null;
	private function _getEntityToList(){
		if(!isset($this->entity_to_list)){
			$this->entity_to_list = $this->getEntityToList();
		}
		return $this->entity_to_list;
	}
	private $_hard_filtros = null;
	public function setHardFiltros($wheres){
		$this->_hard_filtros = $wheres;
	}
	protected function getWheres($comparator){
		if(!$this->_hard_filtros)
			return array($comparator);
		$filtros = $this->_hard_filtros;
		$filtros[] = $comparator;
		return $filtros;
	}
	protected function prepareEntityToList($entity, $page, $rows, $sidx, $sord, $comparator){
		$entity->setWhereByArray($this->getWheres($comparator));
	}
	protected function loadDataFromEntity($page, $rows, $sidx, $sord, $comparator){
		$classname = $this->getEntityClassname();
		$entity = new $classname();
		$this->prepareEntityToList($entity, $page, $rows, $sidx, $sord, $comparator);
//		var_dump($page, $rows, $sidx, $sord, $comparator);
//		var_dump($sidx,$sord,1,intval($rows*($page-1)),get_class($entity));
		//var_dump($entity->searchGetSql($sidx,$sord,1,intval($rows*($page-1)),get_class($entity)));
//		var_dump($entity->searchGetSql($sidx,$sord,$rows,intval($rows*($page-1)),get_class($entity)));
//		die();
		return $entity->search($sidx,$sord,$rows,intval($rows*($page-1)),get_class($entity));
	} 
	protected function loadData($page, $rows, $sidx, $sord, $comparator){
		if($this->canLoadDataFromEntity())
			return $this->loadDataFromEntity($page, $rows, $sidx, $sord, $comparator);
		return null;
	}

	/*conteo de todos de los resultados*/
	private $count_results = null;
	protected function setCountResults($count_results){
		$this->count_results = $count_results;
	}
	protected function CountResults(){
		if(isset($this->count_results))
			return $this->count_results;
		if(($res = $this->CountResultsAlternative())!==null){
			return $res;
		}
		if($this->canLoadDataFromEntity()){
			return $this->getEntityToList()->searchCount();
		}
		return null;
	}
	protected function CountResultsAlternative(){
		return null;
	}

	/*conteo de todos los items*/
	private $count_all = null;
	protected function setCountAll($count_results){
		$this->count_results = $count_results;
	}
	protected function CountAll(){
		if(isset($this->count_all))
			return $this->count_all;
		if(($res = $this->CountAllAlternative())!==null){
			return $res;
		}
		if($this->canLoadDataFromEntity()){
			$class = get_class($this->getEntityToList());
			$ent = new $class();
			return $ent->searchCount();
		}
		return null;
	}
	protected function CountAllAlternative(){
		return null;
	}
}
?>