<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- 

-->
<xsl:template match="/entity">
	<rows>
		<xsl:copy-of select="./results/page" />
		<xsl:copy-of select="./results/total" />
		<xsl:copy-of select="./results/records" />
		<xsl:for-each select="./actividad">
			<row>
				<xsl:for-each select="./*">
					<cell><xsl:value-of select="." /></cell>
				</xsl:for-each>
			</row>
		</xsl:for-each>
	</rows>
</xsl:template>
</xsl:stylesheet>