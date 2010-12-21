<?php
class Admin_Reporte_Helper_Export_Format1 extends Core_Singleton{
	const USE_SHELL_EXEC = true;
	const DOWNLOAD_FILE_NAME = 'Actividades_x_Tipo_x_Agencia.docx';
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function export(){
		$document_xml = $this->getXmlString();
		if($dirname = $this->saveToFile($document_xml)){
			$zipname = $this->createZip($dirname);
			Core_Http_Header::ContentType('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			Core_Http_Header::ContentDisposition(self::DOWNLOAD_FILE_NAME);
			readfile($zipname);
			unlink($zipname);
			Core_File_Helper::getInstance()->remDir($dirname);
		}
		die();
	}
	private function useShellExec(){
		return self::USE_SHELL_EXEC || !class_exists('ZipArchive');
	}
	private function createZip($dirname){
		$zipname = realpath($dirname).'.zip';
		if(file_exists($zipname))
			unlink($zipname);
		if($this->useShellExec()){
			chdir($dirname);
			//`zip -R $zipname $dirname`;
			`zip -r $zipname .`;
		}
		else{
			// create object
			$zip = new ZipArchive();
			
			// open archive 
			if ($zip->open($zipname, ZIPARCHIVE::CREATE) !== TRUE) {
			    die ("Could not open archive");
			}
			
			// list of files to add
			// list of files to add
			Core_File_Helper::getInstance()->listDir($dirname, $fileList);
			
			
			// add files
			foreach ($fileList as $f) {
				$ln = str_replace($dirname.'/', '',$f);
				$ln = str_replace($dirname, '',$ln);
			    $zip->addFile($f, $ln) or die ("ERROR: Could not add file: $f. ".__FILE__.__LINE__);
			}
			    
			// close and save archive
			$zip->close();
		}
		//echo "Archive created successfully.".$zipname;
		return $zipname;
	}
	private function saveToFile($xml){
		$dirname = Core_File_Helper::getInstance()->tempnam();
		//$dirname = tempnam(dirname(__FILE__).'/tmp/', '');
		if(file_exists($dirname))
			unlink($dirname);
		mkdir($dirname);
		Core_File_Helper::getInstance()->copyAll(dirname(__FILE__).'/resource/docx/format1', $dirname);
		file_put_contents($dirname.'/word/document.xml', $xml);
		return $dirname;
		//var_dump($dirname, scandir($dirname));
	}
	private function getXmlString(){
		$reporte = new Inta_Model_Reporte_View_Actividad();
		$reporte->setWhere(Db_Helper::equal('id_usuario_logeado', Admin_User_Model_User::getLogedUser()->getId()));
		$actividades = $reporte->search(null, 'ASC', null, 0, 'Inta_Model_Reporte_View_Actividad');
		$c = new Core_Collection($actividades);
		$g = $c->groupByAs(array('id_estrategia', 'nombre_estrategia', 'id_agencia', 'nombre_agencia'), 'Inta_Collection_Reporte_ByAgencia');
		$g = $g->groupByAs(array('id_estrategia', 'nombre_estrategia'), 'Inta_Collection_Grouped_Reporte_ByEstrategia');
		$datamodel = ('
			<model for="entity">
				<model for="inta_actividad_byestrategia">
					<model for="inta_actividad_byagencia">
					<!--
						<model for="inta_actividad">
							<field name="id" />
							
						</model>
						-->
						<!--
						<method name="agencia_actividad" method="getAgencia" multiplicity="single" >
						</method>
						-->
						<method name="agencia_actividad" method="getAgencia" multiplicity="single" >
						</method>
						<model for="inta_actividad">
							<field name="id" />
							<!--
							<field name="id_usuario_logeado" />
							<field name="id_agencia" />
							<field name="nombre_agencia" />
							<field name="id_actividad" />
							<field name="nombre_actividad" />
							<field name="nombre_estrategia" />
							<field name="id_estrategia" />
							-->
							<field name="id_responsable" />
							<field name="nombre_responsable" />
							<method name="reporte_actividad" method="getActividad" multiplicity="single" >
							
							</method>
						</model>
					</model>
					<method name="estrategia_actividad" method="getEstrategia" multiplicity="single" >
					</method>
				</model>
			</model>'
		);
		$xs = new Core_Xslt_Server();
		$xs->setSource($g, $datamodel);
		$xs->appendStyle(dirname(__FILE__).'/resource/reporte1todoc.v3.xsl');
		//$xs->appendStyle(dirname(__FILE__).'/resource/reporte1todoc.xsl');
		//Core_Http_Header::ContentType('text/xml');
		//echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ? >';
		return $xs->toXmlString();
	}
}
?>