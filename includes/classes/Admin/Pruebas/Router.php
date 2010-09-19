<?
class Admin_Pruebas_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'wsdl','ws','callws','handwsdl','customwsdl'
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
	public function handwsdl(){
		$class = 'MiClaseServidora';
		$uri = Core_App::getUrlModel()->getUrl('pruebas/ws');
		$wsdl = new Zend_Soap_Wsdl($class, $uri, true);
		$reflection = new Zend_Server_Reflection();
        $wsdl->addSchemaTypeSection();

        $port = $wsdl->addPortType($class . 'Port');
        $binding = $wsdl->addBinding($class . 'Binding', 'tns:' .$class. 'Port');

        $wsdl->addSoapBinding($binding, $this->_bindingStyle['style'], $this->_bindingStyle['transport']);
        $wsdl->addService($class . 'Service', $class . 'Port', 'tns:' . $class . 'Binding', $uri);
        
        foreach ($reflection->reflectClass($class)->getMethods() as $method) {
            $this->_addFunctionToWsdl($method, $wsdl, $port, $binding);
        }
        $wsdl->setUri($uri);
        $wsdl->dump();

		die();
	}
	public function wsdl(){
		$class = 'MiClaseServidora';
		$uri = Core_App::getUrlModel()->getUrl('pruebas/ws');
		$wsdl = new Zend_Soap_AutoDiscover('Zend_Soap_Wsdl_Strategy_ArrayOfTypeComplex');
		$wsdl->setClass($class);
		$wsdl->setUri($uri);
		$wsdl->setBindingStyle(array('style'=>'document'));
		//$wsdl
//		$strategy = new 
		//$wsdl->setComplexTypeStrategy();
		$wsdl->handle();
		die();
	}
	public function customwsdl(){
		$class = 'MiClaseServidora';
		$uri = Core_App::getUrlModel()->getUrl('pruebas/ws');
		$wsdl = new Zend_Soap_AutoDiscover('MiEstrategia');
		$wsdl->setClass($class);
		$wsdl->setUri($uri);
		$wsdl->setBindingStyle(array('style'=>'document'));
		//$wsdl
//		$strategy = new 
		//$wsdl->setComplexTypeStrategy();
		$wsdl->handle();
		die();
	}
	
	public function ws(){
		if(isset($_GET['wsdl'])){
			$this->wsdl();
		}
		$wsdl_cache = Core_App::getUrlModel()->getUrl('pruebas/wsdl');
        $options = array(
            'soap_version' => SOAP_1_1,
//            'actor' => 'http://framework.zend.com/Zend_Soap_ServerTest.php',
//            'classmap' => array(
//                'TestData1' => 'Zend_Soap_Server_TestData1',
//                'TestData2' => 'Zend_Soap_Server_TestData2',
//            ),
            'encoding' => 'ISO-8859-1',
//            'uri' => Core_App::getUrlModel()->getUrl('pruebas/ws'),
        );
		ini_set('soap.wsdl_cache_enabled','0');
		ini_set('soap.wsdl_cache','0');
		$server = new Zend_Soap_Server(null, $options);
		$server->setWsdl($wsdl_cache);
		$server->setClass('MiClaseServidora');
		$server->setObject(new MiClaseServidora());
		$server->handle();
		die();

	}
	public function callws(){
        $options = array(
            'soap_version' => SOAP_1_1,
//            'actor' => 'http://framework.zend.com/Zend_Soap_ServerTest.php',
//            'classmap' => array(
//                'TestData1' => 'Zend_Soap_Server_TestData1',
//                'TestData2' => 'Zend_Soap_Server_TestData2',
//            ),
//            'encoding' => 'ISO-8859-1',
//            'uri' => ,
        );
		ini_set('soap.wsdl_cache_enabled','0');
		ini_set('soap.wsdl_cache','0');
		$wsdl_cache = Core_App::getUrlModel()->getUrl('pruebas/wsdl');
		$client = new Zend_Soap_Client($wsdl_cache, $options);
		//var_dump((object)self::getParameters());die();
		Core_Http_Header::ContentType('text/plain');
		try{
			$response = $client->method1("a", 'b');
		var_dump($response);
			$response = $client->method2("a", 'b');
		var_dump($response);
			$response = $client->otroMetodo("a", 'b');
		var_dump($response);
		}
		catch(Exception $e){
			var_dump($e);
			die("error en la llamada a WS");
		}
		die();
	}
	protected function localDispatch(){
		die("nada que probar");
		return;
	}
	protected function dispatchNode(){
		die("nada que probar");
		return;
	}
}
/**
 * Retorno
 * @package inta
 * @author 
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class Retorno{
/**
 * @var integer
 */
	var $id;
/**
 * @var string
 */
	var $nombre;
	    public function __call($method, $args)
    {
    	}
    	/**
 * @param string
 */
    	public function kradkk($yeye){
			
		}

}
class OtroRetorno{
/**
 * @var integer
 */
	var $id;
/**
 * @var string
 */
	var $name;
/**
 * @var Retorno[] minOccurs=1
 */
	var $retorno;
}
class MiClaseServidora {
	
  /**
   * MiClaseServidora::method1()
   * @param string $inputParam
   * @return Retorno
   */
    public function method1($inputParam) {
    	return ((object)array(
    		'id'=>time(),
    		'nombre'=>'funciona '.$inputParam.__FILE__.__LINE__
		));
    }
  /**
   * MiClaseServidora::method2()
   *
   * @param integer $inputParam1
   * @return Retorno[]
   */
    public function method2($inputParam1) {
    	return array(((object)array(
    		'id'=>time(),
    		'nombre'=>'funciona '.$inputParam.__FILE__.__LINE__
		)),
		((object)array(
    		'id'=>time(),
    		'nombre'=>'funciona '.$inputParam.__FILE__.__LINE__
		))
		);
    }

  /**
   * MiClaseServidora::otroMetodo()
   *
   * @param integer $inputParam1
   * @return OtroRetorno
   */
    public function otroMetodo($inputParam1) {
    	$x = new OtroRetorno();
    	$x->id = time();
    	$x->name = 'hola';
		 
    	$x->retorno[] = $retorno = new Retorno();
    	$retorno->id = time();
    	$retorno->nombre = 'hola';
    	$x->retorno[] = $retorno = new Retorno();
    	$retorno->id = time();
    	$retorno->nombre = 'hola2';
    	return $x;
    }
  /**
   * MiClaseServidora::method3()
   *
   * @param integer $inputParam1
   * @param string $inputParam2
   * @return string
   */
    public function method3($inputParam1, $inputParam2) {
    	return 'funciona '.__FILE__.__LINE__;
    }
}
class MiEstrategia extends Zend_Soap_Wsdl_Strategy_ArrayOfTypeComplex
{
    /**
     * Add an ArrayOfType based on the xsd:complexType syntax if type[] is detected in return value doc comment.
     *
     * @param string $type
     * @return string tns:xsd-type
     */
    public function addComplexType($type)
    {
        if(in_array($type, $this->_inProcess)) {
            require_once "Zend/Soap/Wsdl/Exception.php";
            throw new Zend_Soap_Wsdl_Exception("Infinite recursion, cannot nest '".$type."' into itself.");
        }
        $this->_inProcess[$type] = $type;

        $nestingLevel = $this->_getNestedCount($type);

        if($nestingLevel > 1) {
            require_once "Zend/Soap/Wsdl/Exception.php";
            throw new Zend_Soap_Wsdl_Exception(
                "ArrayOfTypeComplex cannot return nested ArrayOfObject deeper than ".
                "one level. Use array object properties to return deep nested data.
            ");
        }

        $singularType = $this->_getSingularPhpType($type);

        if(!class_exists($singularType)) {
            require_once "Zend/Soap/Wsdl/Exception.php";
            throw new Zend_Soap_Wsdl_Exception(sprintf(
                "Cannot add a complex type %s that is not an object or where ".
                "class could not be found in 'DefaultComplexType' strategy.", $type
            ));
        }

        if($nestingLevel == 1) {
            // The following blocks define the Array of Object structure
            $xsdComplexTypeName = $this->_addArrayOfComplexType($singularType, $type);
        } else {
            $xsdComplexTypeName = $singularType;
        }

        // The array for the objects has been created, now build the object definition:
        if(!in_array($singularType, $this->getContext()->getTypes())) {
        	/*<mauricio>* /
            parent::addComplexType($singularType);
            /*</mauricio>*/
            //Zend_Soap_Wsdl_Strategy_DefaultComplexType::addComplexType($singularType);
            $this->Zend_Soap_Wsdl_Strategy_DefaultComplexType_addComplexType($singularType);
        }

        unset($this->_inProcess[$type]);
        return "tns:".$xsdComplexTypeName;
    }
    /**
     * Add a complex type by recursivly using all the class properties fetched via Reflection.
     *
     * @param  string $type Name of the class to be specified
     * @return string XSD Type for the given PHP type
     */
    public function Zend_Soap_Wsdl_Strategy_DefaultComplexType_addComplexType($type)
    {
        if(!class_exists($type)) {
            require_once "Zend/Soap/Wsdl/Exception.php";
            throw new Zend_Soap_Wsdl_Exception(sprintf(
                "Cannot add a complex type %s that is not an object or where ".
                "class could not be found in 'DefaultComplexType' strategy.", $type
            ));
        }

        $dom = $this->getContext()->toDomDocument();
        $class = new ReflectionClass($type);

        $complexType = $dom->createElement('xsd:complexType');
        $complexType->setAttribute('name', $type);

        $all = $dom->createElement('xsd:all');

        foreach ($class->getProperties() as $property) {
            if ($property->isPublic() && preg_match_all('/@var\s+([^\s]+)/m', $property->getDocComment(), $matches)) {

                /**
                 * @todo check if 'xsd:element' must be used here (it may not be compatible with using 'complexType'
                 * node for describing other classes used as attribute types for current class
                 */


                $element = $dom->createElement('xsd:element');
                $element->setAttribute('name', $property->getName());
                $element->setAttribute('type', $this->getContext()->getType(trim($matches[1][0])));
				/*<patrick>*/
                $tempo = $property->getDocComment();
                if (preg_match('/minOccurs\s*=\s*(\d+|unbounded)/',$tempo,$matches)) {
					$element->setAttribute('minOccurs', $matches[1]);
                }
                if (preg_match('/maxOccurs\s*=\s*(\d+|unbounded)/',$tempo,$matches)) {
					$element->setAttribute('maxOccurs', $matches[1]);
				}
				/*<patrick>*/
                $all->appendChild($element);
            }
        }

        $complexType->appendChild($all);
        $this->getContext()->getSchema()->appendChild($complexType);
        $this->getContext()->addType($type);

        return "tns:$type";
    }
    
    

}

?>