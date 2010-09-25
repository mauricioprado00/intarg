<?
class Admin_Pruebas_Router_Reflection extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'RecorrerClase','TestRelation'
//			'wsdl','ws','callws','handwsdl','customwsdl'
//			'addEdit','delete','listar','datalist',
//			'ordenar','setorden'
		);
	}
	
	public function log($cont, $prefix=''){
        file_put_contents(dirname(__FILE__).'/'.$prefix.time().'.log', $cont);
	}
	public function logVar($var, $prefix=''){
		ob_start();
		var_dump($var);
		$c = ob_get_contents();
		ob_end_clean();
		$this->log($c, $prefix);
	}
	//Zend_Server_Reflection_Method
	//Zend_Server_Reflection_Prototype
	public function TestRelation(){
		Core_Http_Header::ContentType('text/plain');
		$x = new Inta_Model_AspectoActividadTestInherit();
		$rels = $x->search(null, 'ASC', null, 0, get_class($x));
		foreach($rels as $rel){
			//var_dump(get_class($rel));
			$actividad = $rel->getActividad();
			var_dump(get_class($actividad));
			$aspecto = $rel->getOtracosa();
			var_dump(get_class($aspecto));
			$aspecto = $rel->getAlgomas();
			var_dump(get_class($aspecto));
		}
//		$x->setId(1);
//		var_dump($x->getId());
//		$x->setAlgo("ye");
//		var_dump($x->getActividad());
		die();
	}
	public function RecorrerClase($clase='Inta_Model_AspectoActividadTest'){
		$reflection = new Zend_Server_Reflection();
		//$x = new Inta_Model_AspectoActividadTest();
		Core_Http_Header::ContentType('text/plain');
		var_dump($reflection->reflectClass($clase)->getDocComment());
		foreach ($reflection->reflectClass($clase)->getMethods() as $method) {
			var_dump($method->getDocComment());
			var_dump($method->getName());
			var_dump($method->getDeclaringClass()->getName());
//        foreach ($method->getPrototypes() as $tmpPrototype) {
//            $numParams = count($tmpPrototype->getParameters());
//				ECHO "\n";
//			foreach ($tmpPrototype->getParameters() as $param) {
//				var_dump($param->getName(), $param->getType());
//            }
//        }
//			$class = $method->getDeclaringClass();
//			var_dump($class->name, $class->class);
//			$method->getName();
			echo <<<EOF
			
{$method->name}
{$method->class}
----------------------------

EOF;
			//var_dump(get_class($method));
		}
		die("ok");
	}
	protected function localDispatch(){
		die("nada que probar".__FILE__.__LINE__);
		return;
	}
	protected function dispatchNode(){
		die("nada que probar".__FILE__.__LINE__);
		return;
	}
}





/**
 * Varien Object
 *
 * @category   Varien
 * @package    Varien_Object
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class EsaClaseRara
{

    /**
     * Object attributes
     *
     * @var array
     */
    protected $_data = array();

    /**
    * Original data that was loaded
    *
    * @var array
    */
    protected $_origData;

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = null;

    /**
     * Setter/Getter underscore transformation cache
     *
     * @var array
     */
    protected static $_underscoreCache = array();

    protected static $_camelizeCache = array();

    /**
     * Enter description here...
     *
     * @var boolean
     */
    protected $_isDeleted = false;

    /**
     * Constructor
     *
     * By default is looking for first argument as array and assignes it as object attributes
     * This behaviour may change in child classes
     *
     */
    public function __construct()
    {
        $args = func_get_args();
        if (empty($args[0])) {
            $args[0] = array();
        }
        $this->_data = $args[0];

        $this->_construct();
    }

    /**
     * Enter description here...
     *
     */
    protected function _construct()
    {

    }

    /**
     * Enter description here...
     *
     * @param boolean $isDeleted
     * @return boolean
     */
    public function isDeleted($isDeleted=null)
    {
        $result = $this->_isDeleted;
        if (!is_null($isDeleted)) {
            $this->_isDeleted = $isDeleted;
        }
        return $result;
    }

    /**
     * set name of object id field
     *
     * @param   string $name
     * @return  Core_Object
     */
    public function setIdFieldName($name)
    {
        $this->_idFieldName = $name;
        return $this;
    }

    /**
     * Retrieve name of object id field
     *
     * @return  Core_Object
     */
    public function getIdFieldName()
    {
        return $this->_idFieldName;
    }

    /**
     * Retrieve object id
     *
     * @return mixed
     */
    public function getId()
    {
        if ($this->getIdFieldName()) {
            return $this->getData($this->getIdFieldName());
        }
        return $this->getData('id');
    }

    /**
     * Set object id field value
     *
     * @param   mixed $value
     * @return  Core_Object
     */
    public function setId($value)
    {
        if ($this->getIdFieldName()) {
            $this->setData($this->getIdFieldName(), $value);
        }
        else {
            $this->setData('id', $value);
        }
        return $this;
    }

    /**
     * Add data to the object.
     *
     * Retains previous data in the object.
     *
     * @param array $arr
     * @return Core_Object
     */
    public function addData(array $arr)
    {
        foreach($arr as $index=>$value) {
            $this->setData($index, $value);
        }
        return $this;
    }

    /**
     * Overwrite data in the object.
     *
     * $key can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     *
     * If $key is an array, it will overwrite all the data in the object.
     *
     * $isChanged will specify if the object needs to be saved after an update.
     *
     * @param string|array $key
     * @param mixed $value
     * @param boolean $isChanged
     * @return Core_Object
     */
    private $__array_boolean_vars = array();/*esto es para soporte de tipos de datos cargados desde los atributos xml*/
    private $__array_array_vars = array();
    protected function setBooleanData(){
		$args = func_get_args();
		$this->__array_boolean_vars = $args;
		return($this);
	}
    protected function addArrayData(){
		$args = func_get_args();
		$this->__array_array_vars = array_merge($this->__array_array_vars, $args);
		return($this);
	}
    public function setData($key, $value=null, $filter=null)
    {
    	if(in_array($key, $this->__array_boolean_vars )){
			$value = $value&&$value!=='false'?true:false;
		}
		elseif(is_string($value)&&in_array($key, $this->__array_array_vars)){
			$value = explode(',', $value);
			$new = array();
			foreach($value as $item)
				$new[] = trim($item);
			$value = $new;
		}
        if(is_array($key)) {
            $this->_data = $key;
        } else {
	        if(!isset($filter)){
				$filter = $this->AutofilterFieldInput($key);
			}
            if(isset($filter)){
            	$value = isset($value)?$this->_filter($value, $key, $filter):null;
            }
            $this->_data[$key] = $value;
        }
        return $this;
    }

    /**
     * Unset data from the object.
     *
     * $key can be a string only. Array will be ignored.
     *
     * $isChanged will specify if the object needs to be saved after an update.
     *
     * @param string $key
     * @return Core_Object
     */
    public function unsetData($key=null)
    {
        if (is_null($key)) {
            $this->_data = array();
        } else {
            unset($this->_data[$key]);
        }
        return $this;
    }
    private $_use_auto_filters = true;
    public function UseAutoFilter($use=true){
    	if($use==null)
    		return $this->_use_auto_filters;
		$this->_use_auto_filters = $use?true:false;
		return $this;
	}
	private $_fields_filters_output = array();
	private $_fields_filters_input = array();
  /**
   * Core_Object::addAutofilterFieldOutput()
   * puede tener muchos parametros 
   * @param mixed $key addAutofilterFieldOutput($key, $filter, $parametro1, $parametro2, $parametro3, ...)
   * @param mixed $filter
   * @return
   */
	public function addAutofilterFieldOutput($key, $filter){
		$params = array_slice($args = func_get_args(), 2);
		$filter = array_merge($this->_translateFilter($filter), $params);
		return $this->addAutofiltersFieldOutput($key, $filter);
	}
	public function addAutofilterFieldInput($key, $filter){
		$params = array_slice($args = func_get_args(), 2);
		$filter = array_merge($this->_translateFilter($filter), $params);
		return $this->addAutofiltersFieldInput($key, $filter);
	}
	public function addAutofilterOutput($filter){
		foreach(array_keys($this->getData()) as $key){
			$this->addAutofilterFieldOutput($key, $filter);
		}
	}
	public function addAutofilterInput($filter){
		foreach(array_keys($this->getData()) as $key){
			$this->addAutofilterFieldInput($key, $filter);
		}
	}
	private function _translateFilter($filter){
		if(is_array($filter)){
			if(count($filter)==1)
				$filter = $this->_translateFilter(array_shift($filter));
			else{
				return $filter;
			}
		}

		if(!is_string($filter)){
			die("filtro incorrecto ".__FILE__.__LINE__);
			return;
		}
		if(preg_match_all('/^(?<class>[a-zA-Z_]+)::(?<method>[a-zA-Z0-9_]+)([?](?<more>.*))?$/', $filter, $matches)){
			if(!count($matches['class']) || !count($matches['method'])){
				die("filtro incorrecto ".__FILE__.__LINE__);
				return;
			}
			$class = array_shift($matches['class']);
			$method = array_shift($matches['method']);
			$filter = array($class,$method);
			if(count($matches['more'])&&$more=array_shift($matches['more'])){
				$filter[] = $more;
			}
		}
		elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $filter)){
			var_dump($filter);
			die("filtro incorrecto ".__FILE__.__LINE__);
			return;
		}
		else{
			return array(null, $filter);
		}
		
		return $filter;
	}
  /**
   * Core_Object::addAutofilterFieldOutput()
   *
   * @param mixed $key
   * @param mixed $filter
   * example: 	$obj->addAutofilterFieldOutput('orden', array(__CLASS__, 'filterOrden'));
   * 			$obj->addAutofilterFieldOutput('orden', __CLASS__.'::filterOrden'));
   * 			$obj->addAutofilterFieldOutput('orden', 'utf8_encode'));
   * 			$obj->addAutofilterFieldOutput('orden', 'utf8_decode', 'htmlentities'));
   * 			$obj->addAutofilterFieldOutput('orden', 'md5'));
   * 			$obj->addAutofilterFieldOutput('orden', array(null, 'md5')));
   *			$obj->addAutofilterFieldOutput('orden', __CLASS__.'::filterOrden?2st_parameter');
   */
	public function addAutofiltersFieldOutput($key, $filter){
		$filters = array_slice($args = func_get_args(), 1);
		if(!count($filters))
			return;
		if(!isset($this->_fields_filters_output[$key]))
			$this->_fields_filters_output[$key] = array();
		foreach($filters as $filter){
			$filter = $this->_translateFilter($filter);
			$this->_fields_filters_output[$key][] = $filter;
		}
	}
//	public function changeAutofilterFieldOutput($key, $filters=null){
//		$this->_fields_filters_output[$key] = $filters;
//	}
	public function AutofilterFieldOutput($key){
		if(isset($this->_fields_filters_output[$key]) && ($filter = $this->_fields_filters_output[$key])){
			return $filter;
		}
		return null;
	}
  /**
   * Core_Object::addAutofilterFieldInput()
   *
   * @param mixed $key
   * @param mixed $filter
   * example: 	$obj->addAutofilterFieldInput('orden', array(__CLASS__, 'filterOrden'));
   * 			$obj->addAutofilterFieldInput('orden', __CLASS__.'::filterOrden'));
   * 			$obj->addAutofilterFieldInput('orden', 'utf8_encode'));
   * 			$obj->addAutofilterFieldInput('orden', 'utf8_decode', 'htmlentities'));
   * 			$obj->addAutofilterFieldInput('orden', 'md5'));
   * 			$obj->addAutofilterFieldInput('orden', array(null, 'md5')));
   *			$obj->addAutofilterFieldInput('orden', __CLASS__.'::filterOrden?2st_parameter');
   */
	public function addAutofiltersFieldInput($key, $filter){
		$filters = array_slice($args = func_get_args(), 1);
		if(!count($filters))
			return;
		if(!isset($this->_fields_filters_input[$key]))
			$this->_fields_filters_input[$key] = array();
		foreach($filters as $filter){
			$filter = $this->_translateFilter($filter);
			$this->_fields_filters_input[$key][] = $filter;
		}
	}
//	public function changeAutofilterFieldInput($key, $filters=null){
//		$this->_fields_filters_input[$key] = $filters;
//	}
	public function AutofilterFieldInput($key){
		if(isset($this->_fields_filters_input[$key]) && ($filter = $this->_fields_filters_input[$key])){
			return $filter;
		}
		return null;
	}
	public function DataStrtr($value, $format, $prefix='{!', $sufix='}'){
		$new_data = array();
		foreach($this->getData() as $idx=>$value){
			if(!is_object($idx)&&!is_array($value)){
				$new_data[$prefix.$idx.$sufix] = $value;
			}
		}
		return strtr($format, $new_data);
	}
	public function resetAutofilterFieldsOutput($key=null){
		if(!isset($key))
			$this->_fields_filters_output = null;
		$this->_fields_filters_output[$key] = null;
	}
	public function resetAutofilterFieldsInput($key=null){
		if(!isset($key))
			$this->_fields_filters_input = null;
		$this->_fields_filters_input[$key] = null;
	}
    private function _filter($value, $key, $filters){
    	if($filters===false){
    		return $value;
    	}
		foreach($filters as $filter){
			//echo "\n---------------------\n";
			//var_dump($filter);
			if(count($filter)>2){
				$value = array_merge(array($value), array_slice($filter, 2));
				$filter = array_slice($filter, 0, 2);
			}
			else $value = array($value);
			if(!isset($filter[0]))
				$filter = array_pop($filter);
			//var_export(array($filter, $value));
			//echo "\n---------------------\n";
			$value = call_user_func_array($filter, $value);
			//var_dump($filter, $value);
		}
		return $value;
	}

    /**
     * Retrieves data from the object
     *
     * If $key is empty will return all the data as an array
     * Otherwise it will return value of the attribute specified by $key
     *
     * If $index is specified it will assume that attribute data is an array
     * and retrieve corresponding member.
     *
     * @param string $key
     * @param string|int $index
     * @param mixed $default
     * @return mixed
     */
    public final function getData($key='', $index=null, $filter=null)
    {
        if (''===$key) {
            return $this->_data;
        }

        $default = null;
        
        if(!isset($filter)){
			$filter = $this->AutofilterFieldOutput($key);
		}

        // accept a/b/c as ['a']['b']['c']
        if (strpos($key,'/')) {
            $keyArr = explode('/', $key);
            $data = $this->_data;
            foreach ($keyArr as $i=>$k) {
                if ($k==='') {
                    return $default;
                }
                if (is_array($data)) {
                    if (!isset($data[$k])) {
                        return $default;
                    }
                    $data = $data[$k];
                } elseif ($data instanceof Core_Object) {
                    $data = $data->getData($k);
                } else {
                    return $default;
                }
            }
            return $data;
        }

        // legacy functionality for $index
        if (isset($this->_data[$key])) {
            if (is_null($index)) {
				$val = $this->_data[$key];
                if(isset($filter))
                	$val = isset($val)?$this->_filter($val, $key, $filter):null;
                return $val;
            }

            $value = $this->_data[$key];
            if (is_array($value)) {
                //if (isset($value[$index]) && (!empty($value[$index]) || strlen($value[$index]) > 0)) {
                /**
                 * If we have any data, even if it empty - we should use it, anyway
                 */
                if (isset($value[$index])) {
                	$val = $value[$index];
	                if(isset($filter))
	                	$val = isset($val)?$this->_filter($val, $index, $filter):null;
                    return $val;
                }
                return null;
            } elseif (is_string($value)) {
                $arr = explode("\n", $value);
                $val = (isset($arr[$index]) && (!empty($arr[$index]) || strlen($arr[$index]) > 0)) ? $arr[$index] : null;
                if(isset($filter))
                	$val = isset($val)?$this->_filter($val, $index, $filter):null;
                return $val;
            } elseif ($value instanceof Core_Object) {
                return $value->getData($index, null, $filter);
            }
            return $default;
        }
        return $default;
    }
    public function getDataAsStdClass(){
		$data = $this->getData();
		$odata = new StdClass();
		foreach($data as $key=>$value){
			$odata->$key = $value;
		}
		return($odata);
	}

    /**
     * Get value from _data array without parse key
     *
     * @param   string $key
     * @return  mixed
     */
    protected function _getData($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function setDataUsingMethod($key, $args=array())
    {
        $method = 'set'.$this->_camelize($key);
        $this->$method($args);
        return $this;
    }

    public function getDataUsingMethod($key, $args=null)
    {
        $method = 'get'.$this->_camelize($key);
        return $this->$method($args);
    }

    /**
     * Fast get data or set default if value is not available
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getDataSetDefault($key, $default)
    {
        if (!isset($this->_data[$key])) {
            $this->_data[$key] = $default;
        }
        return $this->_data[$key];
    }

    /**
     * If $key is empty, checks whether there's any data in the object
     * Otherwise checks if the specified attribute is set.
     *
     * @param string $key
     * @return boolean
     */
    public function hasData($key='')
    {
        if (empty($key) || !is_string($key)) {
            return !empty($this->_data);
        }
        return array_key_exists($key, $this->_data);
    }

    /**
     * Convert object attributes to array
     *
     * @param  array $arrAttributes array of required attributes
     * @return array
     */
    public function __toArray(array $arrAttributes = array())
    {
        if (empty($arrAttributes)) {
            return $this->_data;
        }

        $arrRes = array();
        foreach ($arrAttributes as $attribute) {
            if (isset($this->_data[$attribute])) {
                $arrRes[$attribute] = $this->_data[$attribute];
            }
            else {
                $arrRes[$attribute] = null;
            }
        }
        return $arrRes;
    }

    /**
     * Public wrapper for __toArray
     *
     * @param array $arrAttributes
     * @return array
     */
    public function toArray(array $arrAttributes = array())
    {
        return $this->__toArray($arrAttributes);
    }

    /**
     * Set required array elements
     *
     * @param   array $arr
     * @param   array $elements
     * @return  array
     */
    protected function _prepareArray(&$arr, array $elements=array())
    {
        foreach ($elements as $element) {
            if (!isset($arr[$element])) {
                $arr[$element] = null;
            }
        }
        return $arr;
    }

    /**
     * Convert object attributes to XML
     *
     * @param  array $arrAttributes array of required attributes
     * @param string $rootName name of the root element
     * @param boolean $addOpenTag
     * @param boolean $addCdata
     * @return string
     */
    protected function __toXml(array $arrAttributes = array(), $rootName = 'item', $addOpenTag=false, $addCdata=true)
    {
        $xml = '';
        if ($addOpenTag) {
            $xml.= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        }
        if (!empty($rootName)) {
            $xml.= '<'.$rootName.'>'."\n";
        }
        $xmlModel = new Varien_Simplexml_Element('<node></node>');
        $arrData = $this->toArray($arrAttributes);
        foreach ($arrData as $fieldName => $fieldValue) {
            if ($addCdata === true) {
                $fieldValue = "<![CDATA[$fieldValue]]>";
            } else {
                $fieldValue = $xmlModel->xmlentities($fieldValue);
            }
            $xml.= "<$fieldName>$fieldValue</$fieldName>"."\n";
        }
        if (!empty($rootName)) {
            $xml.= '</'.$rootName.'>'."\n";
        }
        return $xml;
    }

    /**
     * Public wrapper for __toXml
     *
     * @param array $arrAttributes
     * @param string $rootName
     * @param boolean $addOpenTag
     * @param boolean $addCdata
     * @return string
     */
    public function toXml(array $arrAttributes = array(), $rootName = 'item', $addOpenTag=false, $addCdata=true)
    {
        return $this->__toXml($arrAttributes, $rootName, $addOpenTag, $addCdata);
    }

    /**
     * Convert object attributes to JSON
     *
     * @param  array $arrAttributes array of required attributes
     * @return string
     */
    protected function __toJson(array $arrAttributes = array())
    {
        $arrData = $this->toArray($arrAttributes);
        $json = Zend_Json::encode($arrData);
        return $json;
    }

    /**
     * Public wrapper for __toJson
     *
     * @param array $arrAttributes
     * @return string
     */
    public function toJson(array $arrAttributes = array())
    {
        return $this->__toJson($arrAttributes);
    }

    /**
     * Convert object attributes to string
     *
     * @param  array  $arrAttributes array of required attributes
     * @param  string $valueSeparator
     * @return string
     */
    public function __toString(array $arrAttributes = array(), $valueSeparator=',')
    {
        $arrData = $this->toArray($arrAttributes);
        return implode($valueSeparator, $arrData);
    }

    /**
     * Public wrapper for __toString
     *
     * Will use $format as an template and substitute {{key}} for attributes
     *
     * @param string $format
     * @return string
     */
    public function toString($format='')
    {
        if (empty($format)) {
            $str = implode(', ', $this->getData());
        } else {
            preg_match_all('/\{\{([a-z0-9_]+)\}\}/is', $format, $matches);
            foreach ($matches[1] as $var) {
                $format = str_replace('{{'.$var.'}}', $this->getData($var), $format);
            }
            $str = $format;
        }
        return $str;
    }

    /**
     * Set/Get attribute wrapper
     *
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
        	case 'app':{
        		if(count($args)){
					$key = $this->_underscore(substr($method,3));
					$data = $this->getData($key, isset($arg) ? $arg : null);
					if($data === null){
						$data = array();
					}
					foreach($args as $arg){
						array_push($data, $arg);
					}
					$result = $this->setData($key, $data);
				}
				return($this);
			}
			case 'pre':{
				if(count($args)){
					$key = $this->_underscore(substr($method,3));
					$data = $this->getData($key, isset($arg) ? $arg : null);
					if($data === null){
						$data = array();
					}
					foreach($args as $arg){
						array_unshift($data, $arg);
					}
					$result = $this->setData($key, $data);
				}
				return($this);
			}
            case 'get' :
                //Varien_Profiler::start('GETTER: '.get_class($this).'::'.$method);
                $key = $this->_underscore(substr($method,3));
                $data = $this->getData($key, isset($args[0]) ? $args[0] : null);
                //Varien_Profiler::stop('GETTER: '.get_class($this).'::'.$method);
                return $data;
            case 'Get' :
                //Varien_Profiler::start('GETTER: '.get_class($this).'::'.$method);
                $key = $this->_underscore(substr($method,3));
                $data = $this->getData($key, isset($args[1]) ? $args[1] : null);
                $tipo = !count($args)||!isset($args[0])||$args[0]===false||!in_array(gettype($args[0]),array('string','boolean'))?null:$args[0];
               	$tipo = !is_array($data)?null:$tipo;
                if(isset($tipo)){
                	if($tipo=='Core_Object'||$tipo===true)
                		$clase = 'Core_Object';
                	else $clase = $tipo;
//	                if($key=='anuncio')
//	                	echo Core_Helper::DebugVars(array('data'=>$data,'count(args)'=>count($args),'tipo'=>$tipo,'clase'=>$clase));
                	$data = new $clase($data);
				}
                //Varien_Profiler::stop('GETTER: '.get_class($this).'::'.$method);
                return $data;

            case 'set' :
                //Varien_Profiler::start('SETTER: '.get_class($this).'::'.$method);
                $key = $this->_underscore(substr($method,3));
                $result = $this->setData($key, isset($args[0]) ? $args[0] : null);
                //Varien_Profiler::stop('SETTER: '.get_class($this).'::'.$method);
                return $result;

            case 'uns' :
                //Varien_Profiler::start('UNS: '.get_class($this).'::'.$method);
                $key = $this->_underscore(substr($method,3));
                $result = $this->unsetData($key);
                //Varien_Profiler::stop('UNS: '.get_class($this).'::'.$method);
                return $result;

            case 'has' :
                //Varien_Profiler::start('HAS: '.get_class($this).'::'.$method);
                $key = $this->_underscore(substr($method,3));
                //Varien_Profiler::stop('HAS: '.get_class($this).'::'.$method);
                return isset($this->_data[$key]);
        }
        echo ("Invalid method ".get_class($this)."::".$method."(".print_r($args,1).")");
        throw new Varien_Exception("INVALID METHOD ".GET_CLASS($THIS)."::".$METHOD."(".PRINT_R($ARGS,1).")");
    }

    /**
     * Attribute getter (deprecated)
     *
     * @param string $var
     * @return mixed
     */

    public function __get($var)
    {
        $var = $this->_underscore($var);
        return $this->getData($var);
    }

    /**
     * Attribute setter (deprecated)
     *
     * @param string $var
     * @param mixed $value
     */
    public function __set($var, $value)
    {
        $this->_isChanged = true;
        $var = $this->_underscore($var);
        $this->setData($var, $value);
    }

    /**
     * checks whether the object is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        if(empty($this->_data)) {
            return true;
        }
        return false;
    }

    /**
     * Converts field names for setters and geters
     *
     * $this->setMyField($value) === $this->setData('my_field', $value)
     * Uses cache to eliminate unneccessary preg_replace
     *
     * @param string $name
     * @return string
     */
    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }
        #Varien_Profiler::start('underscore');
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
        #Varien_Profiler::stop('underscore');
        self::$_underscoreCache[$name] = $result;
        return $result;
    }
	private static function uc_words($str, $destSep='_', $srcSep='_')
	{
	    return str_replace(' ', $destSep, ucwords(str_replace($srcSep, ' ', $str)));
	}
    public function _camelize($name)
    {
        return self::uc_words($name, '');
    }

    /**
     * serialize object attributes
     *
     * @param   array $attributes
     * @param   string $valueSeparator
     * @param   string $fieldSeparator
     * @param   string $quote
     * @return  string
     */
    public function serialize($attributes = array(), $valueSeparator='=', $fieldSeparator=' ', $quote='"')
    {
        $res  = '';
        $data = array();
        if (empty($attributes)) {
            $attributes = array_keys($this->_data);
        }

        foreach ($this->_data as $key => $value) {
            if (in_array($key, $attributes)) {
                $data[] = $key.$valueSeparator.$quote.$value.$quote;
            }
        }
        $res = implode($fieldSeparator, $data);
        return $res;
    }

    /**
     * Enter description here...
     *
     * @param string $key
     * @return mixed
     */
    public function getOrigData($key=null)
    {
        if (is_null($key)) {
            return $this->_origData;
        }
        return isset($this->_origData[$key]) ? $this->_origData[$key] : null;
    }

    /**
     * Enter description here...
     *
     * @param string $key
     * @param mixed $data
     * @return Core_Object
     */
    public function setOrigData($key=null, $data=null)
    {
        if (is_null($key)) {
            $this->_origData = $this->_data;
        } else {
            $this->_origData[$key] = $data;
        }
        return $this;
    }

    /**
     * Enter description here...
     *
     * @param string $field
     * @return boolean
     */
    public function dataHasChangedFor($field)
    {
        $newData = $this->getData($field);
        $origData = $this->getOrigData($field);
        return $newData!=$origData;
    }

    /**
     * Enter description here...
     *
     * @param string $field
     * @return boolean
     */
    public function isDirty($field=null)
    {
        if (empty($this->_dirty)) {
            return false;
        }
        if (is_null($field)) {
            return true;
        }
        return isset($this->_dirty[$field]);
    }

    /**
     * Enter description here...
     *
     * @param string $field
     * @param boolean $flag
     * @return Core_Object
     */
    public function flagDirty($field, $flag=true)
    {
        if (is_null($field)) {
            foreach ($this->getData() as $field=>$value) {
                $this->flagDirty($field, $flag);
            }
        } else {
            if ($flag) {
                $this->_dirty[$field] = true;
            } else {
                unset($this->_dirty[$field]);
            }
        }
        return $this;
    }
/*
    public function __sleep()
    {
        return array('_data', '_idFieldName');
    }

    public function __wakeup()
    {
        $this->_construct();
    }
*/
    public function debug($data=null, &$objects=array())
    {
        if (is_null($data)) {
            $hash = spl_object_hash($this);
            if (!empty($objects[$hash])) {
                return '*** RECURSION ***';
            }
            $objects[$hash] = true;
            $data = $this->getData();
        }
        $debug = array();
        foreach ($data as $key=>$value) {
            if (is_scalar($value)) {
                $debug[$key] = $value;
            } elseif (is_array($value)) {
                $debug[$key] = $this->debug($value, $objects);
            } elseif ($value instanceof Core_Object) {
                $debug[$key.' ('.get_class($value).')'] = $value->debug(null, $objects);
            }
        }
        return $debug;
    }
}

?>