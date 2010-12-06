<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:php="http://php.net/xsl">

<xsl:template match="/entity">
	<!-- esto llama a la funcion de php 
	<xsl:value-of select="php:function('Core_Xslt::Template',./actividad,'test/template_nodes.phtml')" />
	-->
	<entity>
		<!-- <xsl:for-each select="./actividad"> -->
		<xsl:if test="./results/records > 0">
		<xsl:for-each select="php:function('Jqgrid_Block_XmlServer::groupByTag',./*)">
			<xsl:for-each select="./*">
				<resultado_actividad>
				<xsl:copy-of select="./id" />
				<xsl:copy-of select="./agencia_nombre" />
				<actividad_nombre>
					<xsl:value-of select="php:function('Core_Xslt::Template',.,'reporte/xmlserver/html/detalle.phtml')" />
				</actividad_nombre>
				<xsl:copy-of select="./responsable_nombre_completo" />
				</resultado_actividad>
			</xsl:for-each>
		</xsl:for-each>
		</xsl:if>
		
		<xsl:copy-of select="./*" />
	</entity>
</xsl:template>
</xsl:stylesheet>