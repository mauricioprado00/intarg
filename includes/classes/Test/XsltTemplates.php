<?php
class Test_XsltTemplates_XmlServer extends Jqgrid_Block_XmlServer{
	public function __construct(){
		parent::__construct();
		$this->setEntityClassname('Inta_Model_Actividad');
	}	
}

class Test_XsltTemplates extends Core_Singleton{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
  /**
   * Test_XsltTemplates::test1()
   * Testeo de la clase Test_XsltTemplates_XmlServer junto con la clase
   * Core_Xslt, esta se testea dentro de un xsl llamando al metodo registrado Template de la misma
   * que permite instanciar bloques de template para formatear los nodos con archivos phtml
   * @see design/default/default/resource/xsl/test/template.xsl
   * @see design/default/default/template/test/template_nodes.phtml
   * @see Core_Xslt::Template
   * @return
   */
	public function test1(){
		function mensajeeliminacion(){
			//Desea continuar eliminando el elemento seleccionado
			return 'desea eliminar';
		}
		function testfunc($param,$param2=null){//esto se llama desde el xsl asi: <xsl:value-of select="php:function('testfunc',.,.)" />
			//var_dump(gettype($param));
			var_dump($param[0]);
			$node = $param[0]->ownerDocument->createElement("para");
			$attr = $param[0]->ownerDocument->createAttribute('algo');
			$attr->value="mivalor";
			$node->appendChild($attr);
			var_dump($param[0]->ownerDocument->saveXML($param[0]));
			//var_dump($param[0]->nodeName);
			$newnode = $param[0]->appendChild($node);
			return '';
			return $param[0];
			return $param[0]->ownerDocument->saveXML($param[0]);
		}
		header('content-type:text/plain');
		//$c = new Core_Collection(c($agencia = new Inta_Model_Agencia())->search(null, null, null, null, 'Inta_Model_Agencia'));
		$x = new Test_XsltTemplates_XmlServer;
		$data_model = '
		<model for="entity">
			<model for="actividad">
				<field name="id" />
				<field name="id_responsable" />
				<field name="nombre" />
				<field name="porcentaje_cumplimiento" />
				<field name="porcentaje_tiempo" />
				<field name="presupuesto_estimado" />
			</model>
		</model>
		';
		//$data_model = null;
		$x->setDataModel($data_model);
		$x->setLayout(Core_Helper::getInstance()->LookUpLayout());
		//$x->appendStyle('resource/xsl/jqgrid/standart.xsl');
		//$x->appendStyle('resource/xsl/jqgrid/standart-botones.xsl');
		$x->appendStyle('resource/xsl/test/template.xsl');
		//esto va a estar en un onclick, asi que hay que convertirlo en json y además en htmlentities
		$x->addParam('mensaje_eliminacion', htmlentities(json_encode(utf8_encode('¿Desea continuar eliminando la actividad?'))));
		
		echo $x->toHtml();

	}
}?>