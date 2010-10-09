<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- 
Este xsl genera un html de todos los campos de un xml cuyas "cell" no sean @special, que serian las de ui
-->
<xsl:template match="/entity">
	<agencias>
		<xsl:for-each select="./agencia">
			<agencia>
				<nombre><xsl:value-of select="//agencia/nombre" /></nombre>
				<email><xsl:value-of select="//agencia/nombre" /></email>
			</agencia>
		</xsl:for-each>
	</agencias>
</xsl:template>
</xsl:stylesheet>