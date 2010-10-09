<?php

class Test_XsltServer extends Core_Singleton{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public function testAll(){
		header('content-type:text/xml');
//		$root = new SimpleXMLElement('<alltests></alltests>');
//		$no_formating = new SimpleXMLElement('<no_formating></no_formating>');
//		$no_formating->addChild(simplexml_load_string(Test_XsltServer::getInstance()->testNoFormating()));
//		$root->addChild($no_formating);
//		echo $root->asXML();
//		return;
		
		$proc = '<?xml version="1.0"?>';
		echo $proc;
		echo '<alltests>';
		echo '<no_formating>';
		echo Test_XsltServer::getInstance()->testNoFormating();
		echo '</no_formating>';
		echo '<single_formating>';
		echo Test_XsltServer::getInstance()->testSingleFormating(false);
		echo '</single_formating>';
		echo '<multipe_formating>';
		echo Test_XsltServer::getInstance()->testMultipleFormating(false);
		echo '</multipe_formating>';
		echo '</alltests>';
	}
	public function testNoFormating($use_header=true){
		$c = new Core_Collection(c($agencia = new Inta_Model_Agencia())->search(null, null, null, null, 'Inta_Model_Agencia'));
		$xs = new Core_Xslt_Server();
		$xs->setUseHeader($use_header);
		$xs->setSource($c);
		return $xs->toXmlString();
	}
	public function testSingleFormating($use_header=true){
		Core_App::getInstance()->initialize();
		$c = new Core_Collection(c($agencia = new Inta_Model_Agencia())->search(null, null, null, null, 'Inta_Model_Agencia'));
		$file = Core_App::getInstance()->getLayout()->getDesignFilePath('resource/xsl/test/agencia.xsl');
		$xs = new Core_Xslt_Server();
		$xs->setUseHeader($use_header);
		$xs->setSource($c);
		$xs->appendStyle($file);
		return $xs->toXmlString();
	}
	public function testMultipleFormating($use_header=true){
		Core_App::getInstance()->initialize();
		$c = new Core_Collection(c($agencia = new Inta_Model_Agencia())->search(null, null, null, null, 'Inta_Model_Agencia'));
		$file = Core_App::getInstance()->getLayout()->getDesignFilePath('resource/xsl/test/agencia.xsl');
		$file2 = Core_App::getInstance()->getLayout()->getDesignFilePath('resource/xsl/test/agencia2.xsl');
		$xs = new Core_Xslt_Server();
		$xs->setUseHeader($use_header);
		$xs->setSource($c);
		$xs->appendStyle($file);
		$xs->appendStyle($file2);
		return $xs->toXmlString();
	}
}
?>