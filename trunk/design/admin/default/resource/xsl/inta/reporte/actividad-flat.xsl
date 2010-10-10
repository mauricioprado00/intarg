<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:php="http://php.net/xsl">

<xsl:template match="/entity">
	<!-- esto llama a la funcion de php -->
	<entity>
		<!-- <xsl:for-each select="./actividad"> -->
		<xsl:if test="./results/records > 0">
		<xsl:for-each select="php:function('Jqgrid_Block_XmlServer::groupByTag',./*)">
			<xsl:for-each select="./*">
				<resultado_actividad>	
				<xsl:copy-of select="./id" />
				<xsl:copy-of select="./id_usuario_logeado" />
				
				<agencia_id><xsl:value-of select="./id_agencia" /></agencia_id>
				<agencia_nombre><xsl:value-of select="./nombre_agencia" /></agencia_nombre>
				<agencia_direccion><xsl:value-of select="./agencia_actividad//direccion" /></agencia_direccion>
				<agencia_telefono><xsl:value-of select="./agencia_actividad//telefono" /></agencia_telefono>
				<agencia_email><xsl:value-of select="./agencia_actividad//email" /></agencia_email>

				<actividad_id><xsl:value-of select="./id_actividad" /></actividad_id>
				<actividad_nombre><xsl:value-of select="./nombre_actividad" /></actividad_nombre>
				<actividad_ano><xsl:value-of select="./actividad_resultado//ano" /></actividad_ano>
				<actividad_porcentaje_cumplimiento><xsl:value-of select="./actividad_resultado//porcentaje_cumplimiento" /></actividad_porcentaje_cumplimiento>
				<actividad_porcentaje_tiempo><xsl:value-of select="./actividad_resultado//porcentaje_tiempo" /></actividad_porcentaje_tiempo>
				<actividad_presupuesto_estimado><xsl:value-of select="./actividad_resultado//presupuesto_estimado" /></actividad_presupuesto_estimado>
				<actividad_mes_enero><xsl:value-of select="./actividad_resultado//mes_enero" /></actividad_mes_enero>
				<actividad_mes_febrero><xsl:value-of select="./actividad_resultado//mes_febrero" /></actividad_mes_febrero>
				<actividad_mes_marzo><xsl:value-of select="./actividad_resultado//mes_marzo" /></actividad_mes_marzo>
				<actividad_mes_abril><xsl:value-of select="./actividad_resultado//mes_abril" /></actividad_mes_abril>
				<actividad_mes_mayo><xsl:value-of select="./actividad_resultado//mes_mayo" /></actividad_mes_mayo>
				<actividad_mes_junio><xsl:value-of select="./actividad_resultado//mes_junio" /></actividad_mes_junio>
				<actividad_mes_julio><xsl:value-of select="./actividad_resultado//mes_julio" /></actividad_mes_julio>
				<actividad_mes_agosto><xsl:value-of select="./actividad_resultado//mes_agosto" /></actividad_mes_agosto>
				<actividad_mes_septiembre><xsl:value-of select="./actividad_resultado//mes_septiembre" /></actividad_mes_septiembre>
				<actividad_mes_octubre><xsl:value-of select="./actividad_resultado//mes_octubre" /></actividad_mes_octubre>
				<actividad_mes_noviembre><xsl:value-of select="./actividad_resultado//mes_noviembre" /></actividad_mes_noviembre>
				<actividad_mes_diciembre><xsl:value-of select="./actividad_resultado//mes_diciembre" /></actividad_mes_diciembre>
				<actividad_observaciones><xsl:value-of select="./actividad_resultado//observaciones" /></actividad_observaciones>
				<actividad_comentario><xsl:value-of select="./actividad_resultado//comentario" /></actividad_comentario>
				<actividad_motivo_atrasado><xsl:value-of select="./actividad_resultado//motivo_atrasado" /></actividad_motivo_atrasado>
				<actividad_motivo_cancelado><xsl:value-of select="./actividad_resultado//motivo_cancelado" /></actividad_motivo_cancelado>
				<actividad_estado><xsl:value-of select="./actividad_resultado//estado" /></actividad_estado>
				
				<responsable_id><xsl:value-of select="./id_responsable" /></responsable_id>
				<responsable_nombre_completo><xsl:value-of select="./nombre_responsable" /></responsable_nombre_completo>
				<responsable_activo><xsl:value-of select="./responsable_actividad//activo" /></responsable_activo>
				<responsable_nombre><xsl:value-of select="./responsable_actividad//nombre" /></responsable_nombre>
				<responsable_apellido><xsl:value-of select="./responsable_actividad//apellido" /></responsable_apellido>
				<responsable_email><xsl:value-of select="./responsable_actividad//email" /></responsable_email>
				<xsl:copy-of select="./responsable_email" />
				
				<xsl:for-each select="./actividad_resultado/actividad/list_aspecto_actividad/aspecto_actividad">
					<aspecto_actividad>
						<aspecto_id><xsl:value-of select="./id_aspecto" /></aspecto_id>
						<aspecto_tipo_nombre><xsl:value-of select=".//tipo_aspecto/nombre" /></aspecto_tipo_nombre>
						<aspecto_nombre><xsl:value-of select=".//aspecto/nombre" /></aspecto_nombre>
						<aspecto_nombre_extendido><xsl:value-of select="./nombre_extendido" /></aspecto_nombre_extendido>
					</aspecto_actividad>
				</xsl:for-each>

				<xsl:for-each select="./actividad_resultado/actividad/list_estrategia_actividad/estrategia_actividad">
					<estrategia_actividad>
						<estrategia_id><xsl:value-of select="./id_estrategia" /></estrategia_id>
						<estrategia_nombre><xsl:value-of select=".//estrategia/nombre" /></estrategia_nombre>
					</estrategia_actividad>
				</xsl:for-each>

				<xsl:for-each select="./actividad_resultado/actividad/list_proyecto_actividad/proyecto_actividad">
					<proyecto_actividad>
						<proyecto_id><xsl:value-of select="./id_proyecto" /></proyecto_id>
						<proyecto_nombre><xsl:value-of select=".//proyecto/nombre" /></proyecto_nombre>
						<proyecto_monto><xsl:value-of select="./monto" /></proyecto_monto>
					</proyecto_actividad>
				</xsl:for-each>
				<proyecto_presupuesto_total>
					<xsl:value-of select="sum(./actividad_resultado/actividad/list_proyecto_actividad/proyecto_actividad//monto)" />
				</proyecto_presupuesto_total>

				<xsl:for-each select="./actividad_resultado/actividad/list_resultado_esperado_actividad/resultado_esperado_actividad">
					<resultado_esperado_actividad>
						<resultado_esperado_id><xsl:value-of select="./id_resultado_esperado" /></resultado_esperado_id>
						<resultado_esperado_descripcion><xsl:value-of select=".//resultado_esperado/descripcion" /></resultado_esperado_descripcion>
						<resultado_esperado_objetivo_id><xsl:value-of select=".//id_objetivo" /></resultado_esperado_objetivo_id>
						<resultado_esperado_objetivo_nombre><xsl:value-of select=".//objetivo/nombre" /></resultado_esperado_objetivo_nombre>
						<resultado_esperado_objetivo_descripcion><xsl:value-of select=".//objetivo/descripcion" /></resultado_esperado_objetivo_descripcion>
					</resultado_esperado_actividad>
				</xsl:for-each>

				<xsl:for-each select="./actividad_resultado/actividad/list_audiencia_actividad/audiencia_actividad">
					<audiencia_actividad>
						<audiencia_id><xsl:value-of select="./id_audiencia" /></audiencia_id>
						<audiencia_asistencia><xsl:value-of select="./asistencia" /></audiencia_asistencia>
						<audiencia_cantidad_esperada><xsl:value-of select="./cantidad_esperada" /></audiencia_cantidad_esperada>
						<audiencia_nombre><xsl:value-of select=".//audiencia/nombre" /></audiencia_nombre>
						<audiencia_tipo_nombre><xsl:value-of select=".//tipo_audiencia/nombre" /></audiencia_tipo_nombre>
					</audiencia_actividad>
				</xsl:for-each>
				</resultado_actividad>
			</xsl:for-each>
		</xsl:for-each>
		</xsl:if>
		
		<xsl:copy-of select="./*" />
	</entity>
</xsl:template>
</xsl:stylesheet>