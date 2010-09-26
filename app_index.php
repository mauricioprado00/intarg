<?
//time_nanosleep(0, 500000000);
//ini_set('display_errors','on');
//error_reporting(E_ALL);
//var_dump(strtotime('12:30'), time());
//die();
include_once(dirname(__FILE__).'/basic.php');
//Core_Http_Header::ContentType('text/plain');
//var_duMP($GLOBALS);
//die();
//die();
//header("content-type: text/plain;");
//Core_Http_Header::ContentType('text/plain');
//Core_Http_Header::ContentType('text/plain');
//$db_connection = new COM("ADODB.Connection", NULL, 1251);
//if(Core_Http_Header::isAjaxRequest()){
//	echo "hola";
//	die();
//}
//var_dump(Core_Http_Header::getRequest('X_REQUESTED_WITH'));
//var_dump($_SERVER);
////var_dump($GLOBALS);
//die();
$app = Core_App::getInstance();
$app
	->setDbHost(DB_HOST)
	->setDbUser(DB_USER)
	->setDbPassword(DB_PASS)
	->setDbModel(DB_DATABASE);
//$x = Inta_Model_Traduccion::Traducir('alguna cosa', 'x: kradkk', 'Core');
//var_dump($x);
//$x = Inta_Model_Traduccion::Traducir('alguna cosa', 'x: kradkk', 'Core_kradkk');
////echo Inta_Db::getInstance()->getLastQuery();
//var_dump($x);
//die(); 
if(1){
	$app->run();
	return;
}
//Core_Server::regenerarClase();
//die();
if(true){
	
	if(true){
		var_dump(
		Granguia_Model_Contador::ContarAccesoCategoria(1,2),
		Granguia_Model_Contador::ContarAccesoSesion('192.168.1.215','/kradkk/15'),
		Granguia_Model_Contador::ContarClickAnuncio(5),
		Granguia_Model_Contador::ContarClickBannerDinamico(5,'http://www.kradkk.com/'),
		Granguia_Model_Contador::ContarClickMinisitio(6),
		Granguia_Model_Contador::ContarInicioSesion('192.168.1.215')
		);
		
		die();
	}
	if(true){
		var_dump(Core_Http_Header::isAjaxRequest());
		foreach($_SERVER as $var=>$value){
			if(strpos($var, 'HTTP_')===false)
				continue;
			var_dump(array($var=>$value));
		}
		die();
	}
	if(true){
		Core_Http_Header::ContentType('text/plain');
		if($nodo_generico = Granguia_Model_TipoNodo::getTipoNodoGenericoUrlByName('Barrio')){
			//$nodo_generico = $tipo_nodo->getNodoGenerico();
			var_dump($nodo_generico);
		}
		die();
	}
	if(true){
		$mailer = new Core_Mailer2();
		$mailer->AddAddress('kradkk007@yopmail.com', 'alguien');
		var_dump($mailer->Send('hola', 'yey'));
		die();
	}
	if(false){
		Core_Http_Header::ContentType('text/plain');
		var_dump(Granguia_Model_Barrio::TipoNodo());
		var_dump(Granguia_Model_Pagina::TipoNodo());
		die();
	}
	if(false){
		$pagina = new Core_Object();
		$pagina->addAutofilterFieldInput('contenido_html', array('Core_UrlModel','urlToVar'));
		$pagina->setContenidoHtml('http://192.168.1.215/granguia/administrator#pagina/listar');
		var_dump($pagina->getData());
		die("termino");
	}
	if(true){
		//$ws = realpath(dirname(__FILE__).'/../ws/priceWS.wsdl');
		Core_Http_Header::ContentType('text/plain');
//		echo file_get_contents('http://192.168.35.220:8080/B2BFluidra/services/PriceWS?wsdl');
//		echo str_repeat('-',200)."\n";
//		echo file_get_contents('http://192.168.35.220:8080/B2BFluidra/services/PriceWS?wsdl');
		function test_prices(){
			$client = new Zend_Soap_Client('http://192.168.35.220:8080/B2BFluidra/services/PriceWS?wsdl');
			//var_dump($client);
			$r = new stdclass();
			$r->idCompany = '014';
			$r->idClient = '0001';
			$r->articlesQuantities = array(
				array('skuArticle'=>'00250##','qty'=>1),
				//array('skuArticle'=>'07876##','qty'=>5),
			);
			$r->currency = 'EUR';
			$r->claseTarifa = '96';
			$ret = $client->getPrices($r);
			var_dump($ret);
		}
		function test_prices_no_client(){
			$client = new Zend_Soap_Client('http://192.168.35.220:8080/B2BFluidra/services/PriceWS?wsdl');
			//var_dump($client);
			$r = new stdclass();
			$r->idCompany = '119';
			$r->idClient = null;
			$r->articlesQuantities = array(
				array('skuArticle'=>'00612##','qty'=>1),
				array('skuArticle'=>'00250##','qty'=>1),
				array('skuArticle'=>'07876##','qty'=>1),
				//array('skuArticle'=>'07876##','qty'=>5),
			);
			$r->currency = null;
			$r->claseTarifa = null;
			$ret = $client->getPrices($r);
			var_dump($ret);
		}
		/*
		yo he probado con los productos 00370##, 00250##, y 07876##, y para dos de ellos devolvía precio
[14:48:21] David: para el otro no, pero no me acuerdo ahroa de cuá
[14:48:23] David: l
[14:48:28] David: espera que pruebo y te digo
[14:49:57] David: no devuelve precio para el último, 07876##
[14:50:13] David: para los otros dos sí devuelve precio en una moneda extraña (FT.)
[14:50:36] mauricio.insis: esto ya con el ws nuevo no?
		*/
		//test_prices();
		test_prices_no_client();
		//test_prices_no_client();
		function test_stock(){
			$client = new Zend_Soap_Client('http://192.168.35.220:8080/B2BFluidra/services/StockWS?wsdl');
			//var_dump($client);
			$r = new stdclass();
			$r->idCompany = '014';
			$r->articleSku = '00250##';
			$r->quantity = '200';
			$ret = $client->getStocks($r);
			var_dump($ret);
		}
		//test_stock();
		
		


//		$r->idCompany = '014';
//		$r->idClient = '0001';
//		$r->articlesSku = array('00370');
//		$r->quantity = 1;
//		$r->currency = 'EUR';
//		$r->claseTarifa = '97';
		//var_dump($client->getPrices($r));
		//var_dump($prices = $client->getPrices((object)self::getParameters()));
		die();
	}
	if(false){
		//$ws = realpath(dirname(__FILE__).'/../ws/priceWS.wsdl');
		Core_Http_Header::ContentType('text/plain');
		$client = new Zend_Soap_Client('http://192.168.35.220:8080/B2BFluidraWS/services/PriceWS?wsdl');
		//var_dump($client);
		$r = new stdclass();
		$r->idCompany = '014';
		$r->idClient = '0002';
		$r->articlesQuantities = array(
			array('idArticle'=>'07876','qty'=>1),
			array('idArticle'=>'07876##','qty'=>5),
		);
		$r->currency = 'EUR';
		$r->claseTarifa = '96';
		$ret = $client->getPrices($r);
		
		var_dump($ret);


//		$r->idCompany = '014';
//		$r->idClient = '0001';
//		$r->articlesSku = array('00370');
//		$r->quantity = 1;
//		$r->currency = 'EUR';
//		$r->claseTarifa = '97';
		//var_dump($client->getPrices($r));
		//var_dump($prices = $client->getPrices((object)self::getParameters()));
		die();
	}
	if(true){
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		Core_Http_Header::ContentType('text/plain');
		$file = 'F:\\desktop\\Escritorio\\bigmotorcicle\\stkstockvalorizado1_modificado.xls';
		Admin_Importador_Helper_Importador::getInstance()->importarArchivo($file);
		die();
		$x = new Admin_Importador_Model_Importacion();
		$x->setArchivo('.xls');
		$x->setWhere(Db_Helper::like('archivo','%'));
		$res = $x->search('id','desc', 1);
		if($res&&count($res)){
			$x = array_shift($res);
			echo 'importando: '. $x->getArchivo()."\n";
			Admin_Importador_Helper_Importador::getInstance()->importarArchivo($x->getArchivo());
		}
		die();
		
		Core_Helper::max_execution_time(null,1);
		$new_file = ("F:\\desktop\\Escritorio\\bigmotorcicle\\download\\backup_100127_040953\\beta\\files\\importados\\reporte web_20090901_213757_000000.xls");
		if(1){
			Admin_Importador_Helper_Importador::getInstance()->importarArchivo($new_file);
			return;
		}
		$data = new Spreadsheet_Excel_Reader();
		$data->read($new_file);
		var_dump($data->sheets);
		return;
	}

	if(false){
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		$usuario = new Granguia_User_Model_User();
		$usuario->load(array('id'=>1));
		var_dump($usuario);
		return;
	}
	if(false){
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		var_dump(Granguia_Model_Config::findConfigValue('email_server_host'));
		die();
	}
	if(false){
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		$nuevos_metodos_envio = array(
			'correo argentino',
			'el burro veloz',
			'oca',
		);
		$nuevas_formas_pago = array(
			'tarjeta débito', 
			'tarjeta crédito',
			'transferencia bancaria',
			'contrareembolso',
		);
		foreach($nuevos_metodos_envio as $nombre){
			$fp = new Granguia_Model_MetodoEnvio();
			$fp->setNombre(utf8_encode($nombre));
			$fp->setActivo(1);
			$fp->replace();
		}
		foreach($nuevas_formas_pago as $nombre){
			$fp = new Granguia_Model_FormaPago();
			$fp->setNombre(utf8_encode($nombre));
			$fp->setActivo(1);
			$fp->replace();
		}
	}
	if(false){
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		$nuevas_ciudades = array(
			'Córdoba Capital'=>array(
				1531,
				1235,
				4568
			),
			'Villa Carlos Paz'=>array(
				5152
			),
			'Villa Allende'=>array(
				5153
			),
		);
		foreach($nuevas_ciudades as $nombre_ciudad=>$codigos_postales){
			$x = new Granguia_Model_Ciudad();
			$x->setNombre(utf8_encode($nombre_ciudad));
			$x->replace();
			foreach($codigos_postales as $codigo_postal){
				$x->addCodigoPostal($codigo_postal);
			}
		}
	}
	
	if(false){
		error_reporting(E_ALL);
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		Core_Http_Header::ContentType('text/plain');
		Admin_Importador_Helper_Importador::makeBackupImageFiles(1);
		return;
	}
	if(false){
		error_reporting(E_ALL);
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		Core_Http_Header::ContentType('text/plain');
		Admin_Importador_Helper_Importador::makeBackup(true);
		return;
	}
	if(false){
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		$matching = new Admin_Importador_Model_View_MatchingGrouped(2,6);
		var_dump($matching->search());
		return;
	}
	if(false){
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		Core_Helper::max_execution_time(null,1);
		$new_file = ("H:\\importacion\\x.xls");
		Admin_Importador_Helper_Importador::getInstance()->importarArchivo($new_file);
		return;
	}
	if(false){
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		Core_Helper::max_execution_time(null,1);
		$new_file = ("c:\\rars\\productos.zip");
		Admin_Importador_Helper_Importador::getInstance()->importarArchivo($new_file);
		return;
		
			$zip = zip_open("c:\\rars\\productos.zip");
			@mkdir("c:\\rars\\productos", 0777, true);
			if ($zip) {
			    while ($zip_entry = zip_read($zip)) {
			        echo "Name:               " . zip_entry_name($zip_entry) . "\n";
			        echo "Actual Filesize:    " . zip_entry_filesize($zip_entry) . "\n";
			        echo "Compressed Size:    " . zip_entry_compressedsize($zip_entry) . "\n";
			        echo "Compression Method: " . zip_entry_compressionmethod($zip_entry) . "\n";
			        if (zip_entry_open($zip, $zip_entry, "r")) {
			            echo "File Contents to: $zip_entry\n";
			            $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			            file_put_contents("c:\\rars\productos\\".zip_entry_name($zip_entry), $buf);
			            //echo "$buf\n";
			            zip_entry_close($zip_entry);
			        }
			        echo "\n";
			    }
			    zip_close($zip);
			}
	}
	if(false/*test de model view*/){
        Library::import('Core.*, Db.*, Mysql.*, Granguia.*');
        class Producto_x_Importacion2 extends Granguia_Db_Model_View_Abstract{
			protected function init(){
				parent::init();
                $view = new Granguia_Db_Model_View_Generic();
				$view
					->addTable('bm_importacion',null,'i',array('id_importacion'=>'i.id','archivo'=>'i.archivo'))
					->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi',array('pi.id','codigo'=>'pi.codigo','texto'=>'pi.descripcion'))
                    ->addColumn('count(pi.id_importacion)', 'cantidad')
				;
                
                $this->addView($view, 'vpi', array('id_importacion','archivo','cant'=>'cantidad'), null);
			}
        }
        
        $x = new Producto_x_Importacion2();
        $x->setCantidad(610);
        $x->setWhere(Db_Helper::equal('cantidad'));
        var_dump($x->search());
        return;
		class Admin_Importador_Model_Importacion extends Core_Model_Abstract{
			public function init(){
				parent::init();
				$this->setId(null)
					->setArchivo(null);
			}
			public function getDbTableName() 
			{
				return 'bm_importacion';
			}
		}
		class Producto_x_Importacion extends Granguia_Db_Model_View_Abstract{
			protected function init(){
				parent::init();
				$this
					->addTable('bm_importacion',null,'i',array('id_importacion'=>'i.id','archivo'=>'i.archivo'))
					->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi',array('pi.id','codigo'=>'pi.codigo','texto'=>'pi.descripcion'))
				;	
			}
		}
		class VImportacion extends Granguia_Db_Model_View_Abstract{
			protected function init(){
				parent::init();
				$this
					->addTable('bm_importacion',null,'i',array('id_importacion'=>'i.id','archivo'=>'i.archivo'))
					->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi')
					->addColumn('count(pi.id)', 'cantidad')
					->setGroupBy('i.id')
				;	
			}
		}
//		$x = new Admin_Importador_Model_Importacion();
//		//$x->setArchivo("c:\\archivito.xls");
////		$x->replace();
//		$x->setId(2);
//		$x->setWhere(Db_Helper::equal('id'));
//		//$x->delete();
//		var_dump($x->search());
//die();
		if(false){
			$x = new Granguia_Db_Model_View_Generic();
			$x->addTable('bm_importacion',null,'i',array('id_importacion'=>'i.id','archivo'=>'i.archivo'));
			$x->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi',array('id_producto_importacion'=>'pi.id','codigo'=>'pi.codigo'));
			$x->setIdProductoImportacion("601");
			var_dump($x->searchCount());
			$x->setWhere(Db_Helper::equal('id_producto_importacion'));
			//$x->setWhere(Db_Helper::null('id_producto_importacion', false));
			var_dump($x->search());
		}
		if(false){
			$x = new Granguia_Db_Model_View_Generic();
			$x->addTable('bm_importacion',null,'i',array('id_importacion'=>'i.id','archivo'=>'i.archivo'));
			$x->addTable('bm_producto_importacion','i.id = pi.id_importacion','pi',array('pi.id','codigo'=>'pi.codigo'));
			$x->setPiId("601");
			$x->setWhere(Db_Helper::equal('pi_id'));
			var_dump($x->search(null,'ASC',null,0,'Granguia_Db_Model_View_Generic'));
		}
		if(true){
			$x = new VImportacion();
			/**
				invalido, no se puede hacer un where con una columna calculada
			*/
			$x->setCantidad(610);
			$x->setWhere(Db_Helper::equal('cantidad'));
			$r = $x->search('cantidad');
			var_dump($r);
			/**
				ok, sise puede ordenar por una columna calculada
			*/
			$x->resetWhere();
			$r = $x->search('cantidad');
			var_dump($r);
		}
		if(false){
			$x = new Producto_x_Importacion();
	//		$x->setPiId("601");
	//		$x->setWhere(Db_Helper::equal('pi_id'));
			$r = $x->search('texto','ASC',null,0,'Granguia_Db_Model_View_Generic');
			foreach($r as $o)
				var_dump($o->getTexto());
					//var_dump($o->getArchivo().' '.$o->getTexto()."\n");
			$x->delete();
		}
	}
	if(false/*test de import*/){
		Library::import('Core.*, Db.*, Mysql.*, Granguia.*, Admin.*');
		Core_Helper::max_execution_time(null,1);
		$new_file = "c:\\xls\\articulos_mati.xls";
		Admin_Importador_Helper_Importador::getInstance()->importarArchivo($new_file);
		die();
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
				echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
			}
			echo "\n";
		}
	}
	return;
	;
	if(0):
	$GLOBALS['STOREPLACE'] = array();
	function set_var($varname, $value, $context='global'){
		$args = func_get_args();
		$contexts = array_merge(array_slice($args, 2), array($varname));
		$values = &$GLOBALS['STOREPLACE'];
		foreach($contexts as $context){
			$values = &$values[$context];
		}
		$values = $value;
	}
	function get_var($varname, $contexts='global'){
		$args = func_get_args();
		$contexts = array_merge(array_slice($args, 1), array($varname));
		$values = &$GLOBALS['STOREPLACE'];
		foreach($contexts as $context){
			if(!isset($values[$context]))
				return(null);
			$values = &$values[$context];
		}
		return($values);
	}
	set_var('minombre', 15, 'a', 'b', 'c');
	set_var('minombre', 16, 'a', 'b');
	var_dump($GLOBALS['STOREPLACE']);
	set_var('minombre', null, 'a', 'b', 'c');
	var_dump($GLOBALS['STOREPLACE']);
	var_dump(get_var('minombre','a','b'));
	var_dump(get_var('minombre','a','b','c','d'));
	var_dump(get_var('b','a'));
	var_dump($GLOBALS['STOREPLACE']);
	die();
	endif;
	//Library::import('Db.*, Mysql.*, Granguia.*, Admin.*');
}
//include_once(dirname(__FILE__).'/test/test_de_herencia_de_modelo.php');
//var_dump($_SERVER);
////Library::_include('includes/otro.php');
//Library::_class('DBEntity');
//Library::_class('RegExp');
////Library::import("sdlfkjsd.* sdf sdf sdf sdf klljkl, paquete.x.%");
//Library::import("DBx.%, DBx.xx.%");

//Library::_class('FileFilterIncluder');

//Library::_class('HtmlDirectoryList');

/** TO DO
*@todo los paths estan bien ahora
*@todo hacer los Library::_new('DB.funca',x,y,z)
*@todo estaria bueno hacer:   Library::import("DB.%txt, DB.xx.%html|php ); para indicar extensiones
/**/

/** TO DO
*@todo para poder independizar el nucleo:
*@todo 1) crear el htacces que redirecciona recursos accesibles publicamente en el directorio de proyecto especifico
*@todo 2) modificar el metodo de inclusion de clases escaneando no solo el direcotio includes/classes sino cualquiera que se agregen en el/los subproyectos
*@todo 3) modificar el solucionador de recursos (getSkinUrl) para que resuelva bustando en los paths tanto del proyecto como del nucleo (se deben poder agregar paths de skin)
*@todo 4) crear un archivo xml, y parseador generico de configuraciones, que lea toda estos sets del subproyecto
*@todo 5) indicar en el archivo xml la configuracion del path con el que se accede al nucleo (si esta desviado) por un htacces o si se accede directamente por otra url
*@todo 6) que se pueda indicar las demas configuraciones en el xml (conexion a la base, suburl, etc) 
*@todo 7) configuraciones de suburl dependientes del hostname. Ej:
*@todo <virtualhost>
*@todo 	<http_host>*</http_localhost>
*@todo 	<config>
*@todo 		<database>
*@todo 			<host>127.0.0.1</host>
*@todo 			<user>root</user>
*@todo 			<password>elNuev0</host>
*@todo 			<schema>app_bigmotorcicle</schema>
*@todo 		</database>
*@todo  	<paths>
*@todo  		<root_path>h:\AppServ\www\BigMotorcicle</root_path>
*@todo  		<subpath_layout>layout/</subpath_layout>
*@todo  		<subpath_design>design/</subpath_design>
*@todo  		<subpath_skin>skin/</subpath_skin>
*@todo  		<subpath_layout>layout/</subpath_layout>
*@todo  		<subpath_layout>layout/</subpath_layout>
*@todo  	</paths>
*@todo 	</config>
*@todo </virtualhost>
*@todo <virtualhost>
*@todo 	<http_host>156.%</http_localhost>
*@todo 	<config>
*@todo 		<database>
*@todo 			<host>xxxxxx</host>
*@todo 			<user>xxxxx</user>
*@todo 			<password>xxxxxx</host>
*@todo 			<schema>xxxxxxxx</schema>
*@todo 		</database>
*@todo  	<paths>
*@todo  		<root_path>h:\AppServ\www\xxxxxxxx</root_path>
*@todo  	</paths>
*@todo 	</config>
*@todo </virtualhost>
*@todo 8) crear un proyecto instalador, que configure el xml y los paths y demas y cree en el directorio de destino
*/
?>