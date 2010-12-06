<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:php="http://php.net/xsl">

<xsl:template match="/entity">
	<!-- esto llama a la funcion de php 
	<xsl:value-of select="php:function('Core_Xslt::Template',./actividad,'test/template_nodes.phtml')" />
	-->
	<entity>
		<xsl:for-each select="./inta_actividad_byestrategia">
			<tipo_actividad>
				<xsl:value-of select="./estrategia_actividad/estrategia/nombre" />
			</tipo_actividad>
			<xsl:for-each select="./inta_actividad_byagencia">
				<agencia>
					<xsl:value-of select="./agencia_actividad/agencia/nombre" />
				</agencia>
				<xsl:for-each select="./inta_actividad/reporte_actividad/actividad">
					<actividad>
						<xsl:copy-of select="./nombre" />
						<xsl:copy-of select="./observaciones" />
						<xsl:copy-of select="./porcentaje_cumplimiento" />
					</actividad>
				</xsl:for-each>
			</xsl:for-each>
			<!--
			<actividad_nombre>
				<xsl:value-of select="php:function('Core_Xslt::Template',.,'reporte/xmlserver/html/detalle.phtml')" />
			</actividad_nombre>
			<xsl:copy-of select="./responsable_nombre_completo" />
			-->
		</xsl:for-each>
	</entity>
</xsl:template>
</xsl:stylesheet>