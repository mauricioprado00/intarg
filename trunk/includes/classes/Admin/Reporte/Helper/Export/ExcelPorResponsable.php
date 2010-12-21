<?php
class Admin_Reporte_Helper_Export_ExcelPorResponsable extends Core_Singleton{
	const USE_SHELL_EXEC = true;
	const DOWNLOAD_FILE_NAME = 'Actividades_x_Responsable.xml';
	const TITULO = 'Listado de Actividad por Responsable';
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function export(){
		$collection = $this->getCollection();
		$document_xml = $this->getXmlString($collection);
		Core_Http_Header::ContentType('application/vnd.ms-excel');
		Core_Http_Header::ContentDisposition(self::DOWNLOAD_FILE_NAME);
		echo $document_xml;
		die();
	}
	private $_collection = null;
	public function getCollection(){
		if(!isset($this->_collection)){
			$reporte = new Inta_Model_Reporte_Actividad();
			$reporte->setWhere(Db_Helper::equal('id_usuario_logeado', 4));
			$actividades = $reporte->search('nombre_responsable', 'ASC', null, 0, 'Inta_Model_Reporte_Actividad');
//			echo $reporte->searchGetSql('nombre_responsable', 'ASC', null, 0, 'Inta_Model_Reporte_Actividad');
//			die();
			$c = new Core_Collection($actividades);
			$this->_collection = $c;
		}
		return $this->_collection;
	}
	private function getXmlString($g){
		
//		foreach($g as $g_agencia){
//			$agencia = $g_agencia->getAgencia();
////			$documentos = $agencia->getListDocumento();
////			foreach($documentos as $doc)
//			if($res = $agencia->getDocumentoCaracterizacion()){
//				
//			}
//			
//			var_dump($res->getData(), $res->getFullPath());
//		}
//		die();

		$datamodel = ('
		<model for="entity">
			<model for="resultado_actividad">
				<field name="id_usuario_logeado" />
				<field name="id_agencia" />
				<field name="nombre_agencia" />
				<field name="id_actividad" />
				<field name="nombre_actividad" />
				<field name="id_responsable" />
				<field name="nombre_responsable" />
				<field name="nombre_estrategia" />
				<field name="id_estrategia" />
				<method name="actividad_instancia" method="getActividad" multiplicity="single">
					<model for="actividad">
						<field name="ano" />
						<field name="porcentaje_cumplimiento" />
						<field name="observaciones" />
						<!--
						<field name="presupuesto_estimado" />
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
						<method name="presupuesto_proyecto" method="getPresupuestoProyectos" multiplicity="single">
						</method>
					</model>
				</method>
				<!-- 
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
				-->
			</model>
		</model>');
		$ncol = 0;
		$params = new Core_Object(array(
			'title'=>self::TITULO,
			'columnas'=>array(
				'item_'.$ncol++=>array(
					'title'=>'Inv No',
					'width'=>'40',
				),
				'item_'.$ncol++=>array(
					'title'=>'Agencia',
					'width'=>'114',
				),
				'item_'.$ncol++=>array(
					'title'=>'Nombre',
					'width'=>'329',
				),
				'item_'.$ncol++=>array(
					'title'=>'Responsable',
					'width'=>'99',
				),
				'item_'.$ncol++=>array(
					'title'=>utf8_encode('Año'),
					'width'=>'55',
				),
				'item_'.$ncol++=>array(
					'title'=>'Cumplimiento',
					'width'=>'55',
				),
				'item_'.$ncol++=>array(
					'title'=>'Tiempo',
					'width'=>'55',
				),
				'item_'.$ncol++=>array(
					'title'=>'Presupuesto',
					'width'=>'55',
				),
				'item_'.$ncol++=>array(
					'title'=>'Observaciones',
					'width'=>'187',
				),
				'item_'.$ncol++=>array(
					'title'=>'Estado',
					'width'=>'97',
				),
			)
		));
		$params->setXmlEntityTagname('params');
		
		$g->addItem($params);


		$xs = new Core_Xslt_Server();
		$xs->setSource($g, $datamodel);
		$xs->appendStyle(dirname(__FILE__).'/resource/reporte3toflat.xsl');
		$xsl_standart_botones = 'resource/xsl/inta/actividad/standart-botones.xsl';
		$path_xsl_standart_botones = c($layout = Core_App::getInstance()->getLayout())
			->getDesignFilePath($xsl_standart_botones)
		;
		$xsl_excel = 'resource/xsl/jqgrid/export/excel.xsl';
		$path_xsl_excel = $layout->getDesignFilePath($xsl_excel);

		$xs->appendStyle($path_xsl_standart_botones);
		$xs->appendStyle($path_xsl_excel);
		
		return $xs->toXmlString();
	}
}
?>