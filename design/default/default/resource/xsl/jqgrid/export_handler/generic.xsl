<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- 
Este xsl genera un html de todos los campos de un xml cuyas "cell" no sean @special, que serian las de ui
-->
<xsl:template match="/">
  <html>
  <head>
  <style>
  <![CDATA[
  table{border:1px solid black;; border-collapse:collapse;}
  ]]>
  </style>
  
  </head>
  <body>
  <center>
  <h2>
  	<xsl:value-of select="//grid/caption" />
    <xsl:if test="count(//rows/row)&lt;10">
    	- pagina <xsl:value-of select="//rows/page" />
    </xsl:if>
  </h2>
    <table border="1">
    	<thead>
      <tr bgcolor="#9acd32">
      	<xsl:for-each select="//columns/column">
        <th><xsl:value-of select="title" /></th>
        </xsl:for-each>
      </tr>
      </thead>
      <tfoot>
      <tr bgcolor="#9acd32">
      	<th>
			<xsl:attribute name="colspan">
				<xsl:value-of select="count(//columns/column)" />
			</xsl:attribute>
		    <xsl:if test="count(//rows/row) &lt; number(//rows/records)">
		    	<span><xsl:value-of select="count(//rows/row)" /> de <xsl:value-of select="//rows/records" /> items</span>
		    </xsl:if>
		    <xsl:if test="count(//rows/row) &gt;= number(//rows/records)">
		    	<span><xsl:value-of select="//rows/records" /> items</span>
		    </xsl:if>
		  </th>
      </tr>
      </tfoot>
      <xsl:for-each select="//rows/row">
      <tr>
      	<xsl:for-each select="./cell">
      	<xsl:if test="not(@special = 'ui')">
		<td>
			<xsl:if test="not(count(./*))">
				<xsl:value-of select="." />
			</xsl:if>
			<xsl:if test="count(./*)">
				<xsl:copy-of select="./*" />
			</xsl:if>
		</td>
      	</xsl:if>
      	</xsl:for-each>
      </tr>
      </xsl:for-each>
    </table>
    </center>
  </body>
  </html>
</xsl:template>
</xsl:stylesheet>