<?php 
/*
@todo: relaciones/mapeo a metodos
	<relation class="Inta_Model_AudienciaActividad" relation_type="referencee" pk_field="id" fk_field="id_agencia" />
	<relation name="Actividad" multiplicity="single" /> <!-- esto se deberia completar de la clase de modelo -->

*/
class Test_DataModel extends Core_Singleton{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	function showNiceXml($xml_string){
		$doc = new domDocument();
		$doc->loadXML($xml_string);
		$doc->formatOutput = true;
		//$doc->childNodes->item(0)->childNodes->item(0)
		return $doc->saveXML();
	}
	function pruebaXmlOutputEntity(){// prueba de el output xml de una entidad con subentidades
		$resultado_actividad_data_model = <<<MODELOPARAENTIDAD
			<model for="resultado_actividad">
				<field name="id" />
				<field name="id_usuario_logeado" />
				<field name="id_agencia" />
				<field name="nombre_agencia" />
				<field name="id_actividad" />
				<field name="nombre_actividad" />
				<field name="otro_resultado">
					<model>
						<field name="nombre_agencia" />
						<field name="nombre_actividad" />
						<field name="last_child">
							<model>
								<field name="nombre_responsable" />
							</model>
						</field>
						<field name="nombre_responsable" />
					</model>
				</field>
				<field name="id_responsable" />
				<field name="nombre_responsable" />
			</model>
MODELOPARAENTIDAD;
		//<method relation="Inta_Model_AudienciaActividad" relation_type="referencee" pk_field="id" fk_field="id_agencia" />
		c($reporte1 = new Inta_Model_Reporte_Actividad())->load(1);
		c($reporte2 = new Inta_Model_Reporte_Actividad())->load(2);
		c($reporte3 = new Inta_Model_Reporte_Actividad())->load(3);
		$reporte1->setData('otro_resultado', $reporte2);
		$reporte2->setData('last_child', $reporte3);
		echo $this->showNiceXml($reporte1->toXmlString());
		echo $this->showNiceXml($reporte1->toXmlString($resultado_actividad_data_model));
	}
	function testXmlOutputCollectionHomogenea(){//prueba de una collection homogenea
		$collection_data_model = <<<MODELOPARACOLLECTION
		<model for="entity">
			<model for="resultado_actividad">
				<field name="id" />
				<!-- 
				<field name="id_usuario_logeado" />
				<field name="id_agencia" /> 
				<field name="id_actividad" />
				<field name="id_responsable" />
				-->
				<field name="nombre_agencia" />
				<field name="nombre_actividad" />
				<field name="nombre_responsable" />
			</model>
		</model>
MODELOPARACOLLECTION;
		//<method relation="Inta_Model_AudienciaActividad" relation_type="referencee" pk_field="id" fk_field="id_agencia" />
		
		$x = new Inta_Model_Reporte_Actividad();
		$res = $x->search(null, null, null, null, 'Inta_Model_Reporte_Actividad');
		array_unshift($res, $otro_objeto);
		
		$c = new Core_Collection($res);
		$cf = $c->addFilterEq('id_agencia', 2);
		$cf = $cf->addFilterEq('id_responsable', 11);
		
		echo $this->showNiceXml($cf->toXmlString($collection_data_model));
	}
	function testXmlOutputCollectionHeterogenea(){//prueba de una collection heterogenea
		$collection_data_model = <<<MODELOPARACOLLECTION
		<model for="mixeddata">
			<model for="resultado_actividad">
				<field name="id" />
				<field name="nombre_agencia" />
				<field name="nombre_actividad" />
				<field name="nombre_responsable" />
			</model>
			<model for="actividad">
				<field name="nombre" />
			</model>
		</model>
MODELOPARACOLLECTION;
		//<method relation="Inta_Model_AudienciaActividad" relation_type="referencee" pk_field="id" fk_field="id_agencia" />
		
		$x = new Inta_Model_Reporte_Actividad();
		c($otro_objeto = new Inta_Model_Actividad())->setNombre('algun nombre de ejemplo');
		$x->setWhere(Db_Helper::equal('id_agencia', 2), Db_Helper::equal('id_responsable', 11));
		$res = $x->search(null, null, null, null, 'Inta_Model_Reporte_Actividad');
		array_unshift($res, $otro_objeto);
		$res[] = c($otro_objeto = new Inta_Model_Actividad())->setNombre('algun nombre de ejemplo XXXXX');
		
		$c = new Core_Collection($res);
		$c->setXmlEntityTagname('mixeddata');
		echo $this->showNiceXml($c->toXmlString($collection_data_model));
	}
	function textXmlOutputGroupedCollectionAndMethodSingle(){//prueba de una collection agrupada y un metodo de multiplicidad single
		$grouped_collection_data_model = <<<MODELOPARACOLLECTION
		<model for="entity">
			<model for="resultado_actividad_list">
				<model for="resultado_actividad">
					<field name="id" />
					<field name="nombre_agencia" />
					<field name="nombre_actividad" />
					<field name="nombre_responsable" />
					<method name="relacion_actividad" method="getActividad" multiplicity="single">
						<params><!-- no implementado solo estaba flipando -->
							<param>xxx</param>
							<param>xxx</param>
						</params>
						<model for="actividad">
							<field name="id" />
							<field name="nombre" />
						</model>
					</method>
				</model>
			</model>
			<model for="actividad_list">
				<model for="actividad">
					<field name="nombre" />
				</model>
			</model>
		</model>
MODELOPARACOLLECTION;
//					<relation class="Inta_Model_AudienciaActividad" relation_type="referencee" pk_field="id" fk_field="id_agencia" />
//					<relation name="Actividad" multiplicity="single" /> <!-- esto se deberia completar de la clase de modelo -->
//
		$x = new Inta_Model_Reporte_Actividad();
		c($otro_objeto = new Inta_Model_Actividad())->setNombre('algun nombre de ejemplo');
		$x->setWhere(Db_Helper::equal('id_agencia', 2), Db_Helper::equal('id_responsable', 11));
		$res = $x->search(null, null, null, null, 'Inta_Model_Reporte_Actividad');
		array_unshift($res, $otro_objeto);
		$res[] = c($otro_objeto = new Inta_Model_Actividad())->setNombre('algun nombre de ejemplo XXXXX');
		
		$c = new Core_Collection($res);
		//echo showNiceXml($c->toXmlString($grouped_collection_data_model));
		$g = $c->groupBy('<!classname>');
		echo $this->showNiceXml($g->toXmlString($grouped_collection_data_model));
		echo $g->toXmlStringLegacy();
	}
	function testXmlOutputGroupeCollectionAndMethodMultiple(){//prueba de una collection agrupada y un metodo de multiplicidad multiple y rendudancia redundante :P
		$grouped_collection_data_model = <<<MODELOPARACOLLECTION
		<model for="entity">
			<model for="actividad_list">
				<model for="actividad">
					<field name="id" />
					<field name="nombre" />
					<field name="estado" />
					<method name="audiencias" method="getListAudiencia" multiplicity="multiple">
						<model for="audiencia_actividad">
							<field name="asistencia" />
							<field name="id" />
							<method name="relacion_audiencia" method="getAudiencia" multiplicity="single" >
								<model for="audiencia">
									<field name="id" />
									<field name="nombre" />
								</model>
							</method>
							<field name="cantidad_esperada" />
						</model>
					</method>
				</model>
			</model>
		</model>
MODELOPARACOLLECTION;
		
		$x = new Inta_Model_Actividad();
		//$x->setWhere(Db_Helper::equal('id_agencia', 2), Db_Helper::equal('id_responsable', 11));
		$res = $x->search(null, null, null, null, 'Inta_Model_Actividad');
		
		$c = new Core_Collection($res);
		//echo showNiceXml($c->toXmlString($grouped_collection_data_model));
		$g = $c->groupBy('<!classname>');
		echo $this->showNiceXml($g->toXmlString($grouped_collection_data_model));
		echo $g->toXmlStringLegacy();
	}

}

?>