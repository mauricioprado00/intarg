<?php
class Test_Inta_Reporte extends Core_Singleton{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	function testXmlOutputAgrupado(){
//		$reporte = new Inta_Model_Reporte_Actividad();
//		$resultados = $reporte->search();
		Core_Http_Header::ContentType('text/xml');
		$reporte = new Inta_Model_Reporte_View_Actividad();
		//var_dump($reporte->searchGetSql());
		$actividades = $reporte->search(null, 'ASC', null, 0, 'Inta_Model_Reporte_View_Actividad');
//		foreach($actividades as $actividad){
//			var_dump($actividad->getData());
//		}
		$c = new Core_Collection($actividades);
		//$c = Core_Helper::getInstance()->Cast($c, 'Inta_Collection_Grouped_Reporte_ByAgencia');
		//var_dump($c);
//		die();
		//$g = $c->groupBy('id_estrategia', 'nombre_estrategia', 'id_agencia', 'nombre_agencia');
		$g = $c->groupByAs(array('id_estrategia', 'nombre_estrategia', 'id_agencia', 'nombre_agencia'), 'Inta_Collection_Reporte_ByAgencia');
//		$g = Core_Helper::getInstance()->Cast($g, 'Inta_Collection_Grouped_Reporte_ByEstrategia');
//		$g = $g->groupBy('id_estrategia', 'nombre_estrategia');
		$g = $g->groupByAs(array('id_estrategia', 'nombre_estrategia'), 'Inta_Collection_Grouped_Reporte_ByEstrategia');
		//echo $c->toXmlString();
//		var_dump($g);
//		
//		die();
		echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		echo $g->toXmlString('
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
				</model>
				<method name="estrategia_actividad" method="getEstrategia" multiplicity="single" >
				</method>
			</model>
		</model>');
		die();
		echo $g->toXmlString();
		die();
		//die("hola ");
	}
	function testDocxOutputAgrupado(){
		$reporte = new Inta_Model_Reporte_View_Actividad();
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
		Core_Http_Header::ContentType('text/xml');
		//echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ? >';
		echo $xs->toXmlString();
		die();
	}
	function testXmlOutputAgrupado2(){
//		$reporte = new Inta_Model_Reporte_Actividad();
//		$resultados = $reporte->search();
		Core_Http_Header::ContentType('text/xml');
		$reporte = new Inta_Model_Reporte_View_ActividadByObjetivo();
//		var_dump($reporte->searchGetSql());
//		die();
		$reporte->setWhere(Db_Helper::equal('id_usuario_logeado', 4));
		//$actividades = $reporte->search(null, 'ASC', null, 0, 'Inta_Model_Reporte_View_Actividad');
		$actividades = $reporte->search(null, 'ASC', null, 0, 'Inta_Model_Reporte_View_Actividad');
//		foreach($actividades as $actividad){
//			var_dump($actividad->getData());
//		}
		$c = new Core_Collection($actividades);
		//$c = Core_Helper::getInstance()->Cast($c, 'Inta_Collection_Grouped_Reporte_ByAgencia');
		//var_dump($c);
//		die();
		//$g = $c->groupBy('id_estrategia', 'nombre_estrategia', 'id_agencia', 'nombre_agencia');
		$g = $c->groupByAs(array('id_agencia', 'id_objetivo', 'id_resultado_esperado'), 'Inta_Collection_Reporte_ByResultadoEsperado');

		$g = $g->groupByAs(array('id_agencia', 'id_objetivo'), 'Inta_Collection_Grouped_Reporte_ByObjetivo');

		$g = $g->groupByAs(array('id_agencia'), 'Inta_Collection_Grouped_Reporte_ByAgencia');

//		echo $g->toXmlString();
//		die();
		
		
//		$g = $c->groupByAs(array('id_estrategia', 'nombre_estrategia', 'id_agencia', 'nombre_agencia'), 'Inta_Collection_Reporte_ByAgencia');
////		$g = Core_Helper::getInstance()->Cast($g, 'Inta_Collection_Grouped_Reporte_ByEstrategia');
////		$g = $g->groupBy('id_estrategia', 'nombre_estrategia');
//		$g = $g->groupByAs(array('id_estrategia', 'nombre_estrategia'), 'Inta_Collection_Grouped_Reporte_ByEstrategia');
//		//echo $c->toXmlString();
//		echo $g->toXmlString();
//		//var_dump($g);
////		
//		die();
		if(false)echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
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
		//echo $g->toXmlString($datamodel);die();
		$xs = new Core_Xslt_Server();
		$xs->setSource($g, $datamodel);
		$xs->appendStyle(dirname(__FILE__).'/resource/reporte2todoc.v5.xsl');
		//$xs->appendStyle(dirname(__FILE__).'/resource/reporte1todoc.xsl');
		Core_Http_Header::ContentType('text/xml');
		//echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ? >';
		echo $xs->toXmlString();
		die();

//		die();
//		echo $g->toXmlString();
//		die();
		//die("hola ");
	}

}
?>