<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:php="http://php.net/xsl">

<xsl:template match="/entity">
	<!-- esto llama a la funcion de php 
	<xsl:value-of select="php:function('Core_Xslt::Template',./actividad,'test/template_nodes.phtml')" />
	-->
	<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
		<Relationship Id="rId8" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="theme/theme1.xml"/>
		<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/settings" Target="settings.xml"/>
		<Relationship Id="rId7" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/fontTable" Target="fontTable.xml"/>
		<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
		<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/numbering" Target="numbering.xml"/>
		<xsl:for-each select="./agencia/documento_caracterizacion/entity">
			<Relationship Id="rId6" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/package">
				<xsl:attribute name="Id">
					<xsl:value-of select="./id" />
				</xsl:attribute>
				<xsl:attribute name="Target">
					<xsl:value-of select="concat('embeddings/',string(./token))" />
				</xsl:attribute>
			</Relationship>
		</xsl:for-each>
		<Relationship Id="rId5" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image1.png"/>
		<Relationship Id="rId4" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/webSettings" Target="webSettings.xml"/>
	</Relationships>
	

</xsl:template>
</xsl:stylesheet>