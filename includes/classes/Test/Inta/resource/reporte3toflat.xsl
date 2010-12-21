<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:php="http://php.net/xsl">
<!-- 

-->
<xsl:param name="aaa" select="ddd"/>

<xsl:template match="/entity">
	<!-- esto llama a la funcion de php -->
	<entity>
		<xsl:value-of select="php:function('header','content-type:text/xml')" />
		<xsl:for-each select="./resultado_actividad">
			<actividad>
				<xsl:copy-of select="./id_actividad" />
				<xsl:copy-of select="./nombre_agencia" />
				<xsl:copy-of select="./nombre_actividad" />
				<xsl:copy-of select="./nombre_responsable" />
				<xsl:copy-of select="./actividad_instancia/actividad/ano" />
				<xsl:copy-of select="./actividad_instancia/actividad/porcentaje_cumplimiento" />
				<xsl:copy-of select="./actividad_instancia/actividad/porcentaje_tiempo" />
				<xsl:copy-of select="./actividad_instancia/actividad/presupuesto_proyecto" />
				<observaciones><xsl:value-of select="php:function('strip_tags',string(./actividad_instancia/actividad/observaciones))" /></observaciones>
				<xsl:copy-of select="./actividad_instancia/actividad/estado" />
			</actividad>
		</xsl:for-each>
		<xsl:copy-of select="./params" />
		<results>
			<records><xsl:value-of select="count(./resultado_actividad)" /></records>
		</results>
	</entity>
</xsl:template>
</xsl:stylesheet>