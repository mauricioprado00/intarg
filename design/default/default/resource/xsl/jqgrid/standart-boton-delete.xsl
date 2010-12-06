<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:php="http://php.net/xsl">
<!-- 

-->
<xsl:param name="aaa" select="ddd"/>

<xsl:template match="/entity">
	<!-- esto llama a la funcion de php -->
	<xsl:value-of select="php:function('header','content-type:text/xml')" />
	<rows>
		<xsl:copy-of select="./results/page" />
		<xsl:copy-of select="./results/total" />
		<xsl:copy-of select="./results/records" />
		<!-- esto habría que arrastrarlo siempre -->
		<xsl:copy-of select="./params" />
		
		
		<!-- <xsl:for-each select="./actividad"> -->
		<xsl:if test="./results/records > 0">
		<xsl:for-each select="php:function('Jqgrid_Block_XmlServer::groupByTag',./*)">
			<xsl:for-each select="./*">
			<row>
				<xsl:attribute name="id">
					<xsl:value-of select="id" />
				</xsl:attribute>
				<cell special="ui"><![CDATA[
					<a href="#]]><xsl:value-of select="//params/delete_url" /><xsl:value-of select="id" /><![CDATA[" onclick="getGrid(this).setSelection(']]><xsl:value-of select="id" /><![CDATA['); return confirm(']]><xsl:value-of select="//params/mensaje_eliminacion" /><![CDATA[')"><div class="lstde"></div></a>
					
				]]></cell>
				<xsl:for-each select="./*">
					<cell><xsl:value-of select="." /></cell>
				</xsl:for-each>
			</row>
			</xsl:for-each>
		</xsl:for-each>
		</xsl:if>
		
		<xsl:copy-of select="//params/extra" />
	</rows>
</xsl:template>
</xsl:stylesheet>