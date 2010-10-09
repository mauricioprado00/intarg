header('content-type:text/plain');
function showNiceXml($xml_string){
	$doc = new domDocument();
	$doc->loadXML($xml_string);
	$doc->formatOutput = true;
	return $doc->saveXML();
}
$x = new Inta_Model_Reporte_Actividad();
$otro_objeto = new Inta_Model_Actividad(array('nombre'=>'hola'));
$res = $x->search(null, null, null, null, 'Inta_Model_Reporte_Actividad');
array_unshift($res, $otro_objeto);
$res[] = new Inta_Model_Actividad(array('nombre'=>'hola'));

$c = new Core_Collection($res);
//echo showNiceXml($c->toXmlString());
//die();
$g = $c->groupBy('<!classname>');
echo showNiceXml($g->toXmlString());
die();
echo $g->toXmlStringLegacy();
die();

//$g = $c->groupBy('id_agencia','id_responsable');
foreach($g as $c){
	foreach($c as $x){
		echo $x->toXmlString()."\n";
	//	if($i++==10)
	//		die();
	}
	
}