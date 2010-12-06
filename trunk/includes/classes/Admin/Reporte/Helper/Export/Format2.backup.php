<?php
class Admin_Reporte_Helper_Export_Format2 extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function export(){
		$document_xml = $this->getXmlString();
		if($dirname = $this->saveToFile($document_xml)){
			$zipname = $this->createZip($dirname);
			Core_Http_Header::ContentType('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			Core_Http_Header::ContentDisposition('reporte_x_Agencia.docx');
			readfile($zipname);
			unlink($zipname);
			Core_File_Helper::getInstance()->remDir($dirname);
		}
		die();
	}
	private function createZip($dirname){
		$zipname = realpath($dirname).'.zip';
		if(file_exists($zipname))
			unlink($zipname);
		if(!class_exists('ZipArchive')){
			`zip -R $zipname $dirname`;
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
		$dirname = tempnam(dirname(__FILE__).'/tmp/', '');
		unlink($dirname);
		mkdir($dirname);
		Core_File_Helper::getInstance()->copyAll(dirname(__FILE__).'/resource/docx/format2', $dirname);
		file_put_contents($dirname.'/word/document.xml', $xml);
		return $dirname;
		//var_dump($dirname, scandir($dirname));
	}
	private function getXmlString(){
		$reporte = new Inta_Model_Reporte_View_ActividadByObjetivo();
		//$reporte->setWhere(Db_Helper::equal('id_usuario_logeado', 4));
		$reporte->setWhere(Db_Helper::equal('id_usuario_logeado', Admin_User_Model_User::getLogedUser()->getId()));
		$actividades = $reporte->search(null, 'ASC', null, 0, 'Inta_Model_Reporte_View_Actividad');
		$c = new Core_Collection($actividades);

		$g = $c->groupByAs(array('id_agencia', 'id_objetivo', 'id_resultado_esperado'), 'Inta_Collection_Reporte_ByResultadoEsperado');
		$g = $g->groupByAs(array('id_agencia', 'id_objetivo'), 'Inta_Collection_Grouped_Reporte_ByObjetivo');
		$g = $g->groupByAs(array('id_agencia'), 'Inta_Collection_Grouped_Reporte_ByAgencia');
		
		foreach($g as $g_agencia){
			$agencia = $g_agencia->getAgencia();
//			$documentos = $agencia->getListDocumento();
//			foreach($documentos as $doc)
			if($res = $agencia->getDocumentoCaracterizacion()){
				
			}
			var_dump($res->getData());
		}

		$datamodel = ('
		<model for="entity">
			<model for="inta_actividad_byagencia">
				<model for="inta_actividad_byobjetivo">
					<model for="inta_actividad_byresultado_esperado">
						<method name="actividad_resultado_esperado" method="getResultadoEsperado" multiplicity="single" >
							<model for="resultado_esperado">
								<!--
								<field name="id" />
								-->
								<field name="descripcion" />
								<method name="resultado_esperado_indicador" method="getListIndicador" multiplicity="multiple">
									<model for="indicador_resultado">
										<field name="adecuado" />
										<field name="descripcion" />
										<method name="resultado_esperado_indicador_instancia" method="getIndicador" multiplicity="single">
										</method>
										<method name="resultado_esperado_indicador_medios_verificacion" method="getListMedioVerificacion" multiplicity="multiple">
											<model for="medio_verificacion_indicador_resultado">
												<method name="medio_verificacion_instancia" method="getMedioVerificacion" multiplicity="single">
												</method>
											</model>
										</method>
									</model>
								</method>
							</model>
						</method>
						<model for="inta_actividad">
							<method name="actividad_instancia" method="getActividad" multiplicity="single">
								<model for="actividad">
									<!--
									<field name="porcentaje_cumplimiento" />
									<field name="presupuesto_estimado" />
									<field name="ano" />
									<field name="observaciones" />
									<field name="comentario" />
									<field name="motivo_atrasado" />
									<field name="motivo_cancelado" />
									-->
									<field name="nombre" />
									<field name="porcentaje_tiempo" />
									<field name="mes_enero" />
									<field name="mes_febrero" />
									<field name="mes_marzo" />
									<field name="mes_abril" />
									<field name="mes_mayo" />
									<field name="mes_junio" />
									<field name="mes_julio" />
									<field name="mes_agosto" />
									<field name="mes_septiembre" />
									<field name="mes_octubre" />
									<field name="mes_noviembre" />
									<field name="mes_diciembre" />
									<field name="estado" />
									<method name="actividad_responsable" method="getResponsable" multiplicity="single">
										<model for="usuario">
											<!--
											<field name="activo" />
											<field name="username" />
											<field name="password" />
											<field name="privilegios" />
											<field name="ultimo_acceso" />
											-->
											<field name="nombre" />
											<field name="apellido" />
											<field name="email" />
										</model>
									</method>
									<method name="actividad_proyectos" method="getListProyecto" multiplicity="multiple">
										<model for="proyecto_actividad">
											<field name="monto" />
											<method name="proyecto_instancia" method="getProyecto" multiplicity="single">
												<model for="proyecto">
													<field name="nombre" />
												</model>
											</method>
										</model>
									</method>
									<method name="actividad_estrategias" method="getListEstrategia" multiplicity="multiple">
										<model for="estrategia_actividad">
											<method name="estrategia_instancia" method="getEstrategia" multiplicity="single">
												<model for="estrategia">
													<field name="nombre" />
												</model>
											</method> 
										</model>
									</method>
								</model>
							</method>
						</model>
					</model>
					<method name="actividad_objetivo" method="getObjetivo" multiplicity="single" >
						<model for="objetivo">
							<field name="id" />
							<field name="descripcion" />
							<method name="actividad_objetivo_problemas" method="getListProblema" multiplicity="multiple">
								<model for="objetivo_problema">
									<method name="actividad_objetivo_problema_instancia" method="getProblema" multiplicity="simple">
										<model for="problema">
											<field name="id" />
											<field name="nombre" />
											<field name="importancia_economica" />
											<field name="impacto_ambiental" />
											<field name="importancia_social" />
											<field name="familias_implicadas" />
											<field name="valor_agregado_potencial" />
											<field name="impacto_desarrollo" />
											<field name="prioridad" />
											<method name="problema_audiencia" method="getAudiencia" multiplicity="simple">
											</method>
										</model>
									</method>
								</model>
							</method>
						</model>
					</method>
				</model>
				<method name="actividad_agencia" method="getAgencia" multiplicity="single" >
					<model for="agencia">
						<field name="nombre" />
						<field name="id_localidad" />
						<field name="direccion" />
						<field name="telefono" />
						<field name="email" />
						<field name="agentes" />
						<field name="descripcion" />
						<method name="agencia_usuario" method="getListUsuario" multiplicity="multiple">
						</method>
					</model>
				</method>
				<method name="audiencias_priorizadas" method="getAudienciasPriorizadas" multiplicity="single">
					<model for="entity">
						<model for="audiencia">
							<field name="nombre"/>
							<field name="id"/>
							<method name="audiencia_tipo_audiencia" method="getTipoAudiencia" multiplicity="single">
							</method>
							<method name="audiencia_problemas" method="getListProblema" multiplicity="multiple">
							</method>
						</model>
					</model>
				</method>
			</model>
		</model>');
		$xs = new Core_Xslt_Server();
		$xs->setSource($g, $datamodel);
		$xs->appendStyle(dirname(__FILE__).'/resource/reporte2todoc.v5.xsl');
		//Core_Http_Header::ContentType('text/xml');
		//echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ? >';
		return $xs->toXmlString();
	}
}
?>