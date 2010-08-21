<?php
class Base_XmlW extends XMLWriter
{
    protected $Encoding;
    /**
     * Constructor.
     * @param string $prm_rootElementName A root element's name of a current xml document
     * @param string $prm_xsltFilePath Path of a XSLT file.
     * @access public
     * @param null
     */
    public function __construct($prm_rootElementName, $encoding = 'UTF-8', $prm_xsltFilePath = ''){

        $this->Encoding = $encoding;
        $this->openMemory();
        $this->setIndent(true);
        $this->setIndentString(' ');
        $this->startDocument('1.0', $this->Encoding);

        if($prm_xsltFilePath){
            $this->writePi('xml-stylesheet', 'type="text/xsl" href="'.$prm_xsltFilePath.'"');
        }

        $this->startElement($prm_rootElementName);
    }

    /**
     * Set an element with a text to a current xml document.
     * @access public
     * @param string $prm_elementName An element's name
     * @param string $prm_ElementText An element's text
     * @return null
     */
    public function setElement($prm_elementName, $prm_ElementText){
        //$prm_ElementText = preg_replace('/[^!-%\x27-;=?-~<>&\x09\x0a\x0d\x0B ]/e', '"&#".ord("$0").chr(59)', $prm_ElementText);
        //$prm_ElementText = preg_replace('/&#195;&#([0-9]+);/e', '"&#".((int) \\1 + 64).";"', $prm_ElementText);
        if($this->Encoding == 'UTF-8')
            $prm_ElementText = utf8_encode($prm_ElementText);
            
        $this->startElement($prm_elementName);
        $this->text($prm_ElementText);
        $this->endElement();
    }


    public function setCData($prm_elementName, $prm_ElementText){
        //$prm_ElementText = preg_replace('/[^!-%\x27-;=?-~<>&\x09\x0a\x0d\x0B ]/e', '"&#".ord("$0").chr(59)', $prm_ElementText);
        //$prm_ElementText = preg_replace('/&#195;&#([0-9]+);/e', '"&#".((int) \\1 + 64).";"', $prm_ElementText);
        if($this->Encoding == 'UTF-8')
            $prm_ElementText = utf8_encode($prm_ElementText);

        $this->startElement($prm_elementName);
        $this->writeCdata($prm_ElementText);
        $this->endElement();
    }

    /**
     * Construct elements and texts from an array.
     * The array should contain an attribute's name in index part
     * and a attribute's text in value part.
     * @access public
     * @param array $prm_array Contains attributes and texts
     * @return null
     */
    public function fromArray($prm_array){
      if(is_array($prm_array)){
        foreach ($prm_array as $index => $element){
          if(is_array($element)){
            $this->startElement($index);
            $this->fromArray($element);
            $this->endElement();
          }
          else
            $this->setElement($index, $element);

        }
      }
    }

    /**
     * Return the content of a current xml document.
     * @access public
     * @param null
     * @return string Xml document
     */
    public function getDocument(){
        $this->endElement();
        $this->endDocument();
        return $this->outputMemory();
    }

    /**
     * Output the content of a current xml document.
     * @access public
     * @param null
     */
    public function output(){
        header('Content-type: text/xml');
        echo $this->getDocument();
    }


}


class XML {
//put your code here


    static public function fromArray($arr) {
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('root');

        foreach($data as $key => $value)
        {
            if(is_array($value))
            {
                $xml->startElement($key);
                write($xml, $value);
                $xml->endElement();
                continue;
            }
            $xml->writeElement($key, $value);
        }

        $xml->endElement();
        return $xml->outputMemory(true);
    }
    public static function noResultError($xml,$msg){
    	/*$xml = new XmlW('NoResultError');
    	$xml->startElement('Failure');
    		$xml->setCData('Msg',$msg);
    	$xml->endElement();
    	return $xml;*/
    	$xml->startElement('Failure');
    	$xml->writeAttribute('ErrorCode','NoResult');
    		$xml->setCData('Msg',$msg);
    	$xml->endElement();
    	return $xml;
    }
 	public static function xmlError($msg, $xml = false, $aAttributes = false){
 		if(!$xml)
 			$xml = new XmlW('GAL_Failure');
    	$xml->startElement('Failure');
    	if(is_array($aAttributes))
    	{
    		foreach ($aAttributes As $key => $value)
    			$xml->writeAttribute($key,$value);
    	}
			$xml->setCData('Msg',$msg);
    	$xml->endElement();
    	return $xml;
    }
    public static function xmlSuccess($msg,$xml = false,$aAttributes = false){
    	if(!$xml)
    		$xml = new XmlW('GAL_Success');
    	$xml->startElement('Success');
    	if(is_array($aAttributes))
    	{
    		foreach ($aAttributes As $key => $value)
    			$xml->writeAttribute($key,$value);    		
//    			$xml->writeAttribute(utf8_encode($key),utf8_encode($value));    		
    	}
    		$xml->setCData('Msg',$msg);
    	$xml->endElement();
    	return $xml;
    }    
}
?>
