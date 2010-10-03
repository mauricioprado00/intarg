<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- 
Este xsl genera un html de todos los campos de un xml cuyas "cell" no sean @special, que serian las de ui
-->
<xsl:template match="/">
    <xsl:processing-instruction name="mso-application">
      <xsl:text>progid="Excel.Sheet"</xsl:text>
    </xsl:processing-instruction>
	<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
	 xmlns:o="urn:schemas-microsoft-com:office:office"
	 xmlns:x="urn:schemas-microsoft-com:office:excel"
	 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
	 xmlns:c="urn:schemas-microsoft-com:office:component:spreadsheet"
	 xmlns:html="http://www.w3.org/TR/REC-html40"
	 xmlns:x2="http://schemas.microsoft.com/office/excel/2003/xml"
	 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
	  <Version>12.00</Version>
	 </DocumentProperties>
	 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
	  <WindowHeight>10005</WindowHeight>
	  <WindowWidth>10005</WindowWidth>
	  <WindowTopX>120</WindowTopX>
	  <WindowTopY>135</WindowTopY>
	  <ProtectStructure>False</ProtectStructure>
	  <ProtectWindows>False</ProtectWindows>
	 </ExcelWorkbook>
	 <Styles>
	  <Style ss:ID="Default" ss:Name="Normal">
	   <Alignment ss:Vertical="Bottom"/>
	   <Borders/>
	   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
	   <Interior/>
	   <NumberFormat/>
	   <Protection/>
	  </Style>
	  <Style ss:ID="m63810848">
	   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
	   <Borders>
	    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
	    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
	    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
	    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
	   </Borders>
	   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="24" ss:Color="#000000"/>
	   <Interior ss:Color="#D8D8D8" ss:Pattern="Solid"/>
	  </Style>
	  <Style ss:ID="s62">
	   <Borders>
	    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
	    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
	   </Borders>
	  </Style>
	  <Style ss:ID="s71">
	   <Borders>
	    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
	    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
	    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
	    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
	   </Borders>
	   <Interior ss:Color="#EEEEEE" ss:Pattern="Solid"/>
	  </Style>
	 </Styles>
	 <Worksheet ss:Name="datos.xml">
	  <Table ss:ExpandedColumnCount="10" ss:ExpandedRowCount="22" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60" ss:DefaultRowHeight="15">
	   <Column ss:StyleID="s62" ss:AutoFitWidth="0" ss:Width="97.5" ss:Span="1"/>
	   <Column ss:Index="3" ss:StyleID="s62" ss:AutoFitWidth="0" ss:Width="105.75"/>
	   <Column ss:StyleID="s62" ss:AutoFitWidth="0" ss:Width="97.5" ss:Span="6"/>
	   <Row ss:AutoFitHeight="0" ss:Height="31.5">
	   <Cell ss:StyleID="m63810848">
	   <xsl:attribute name="ss:MergeAcross">
	   <xsl:value-of select="count(//columns/column)-1" />
	   </xsl:attribute>
	   <Data ss:Type="String">
		  	<xsl:value-of select="//grid/caption" />
		    <xsl:if test="count(//rows/row)&lt;10">
		    	- pagina <xsl:value-of select="//rows/page" />
		    </xsl:if>
		</Data>
		</Cell>
	   </Row>
	   <Row ss:AutoFitHeight="0">
      	<xsl:for-each select="//columns/column">
	    <Cell ss:StyleID="s71"><Data ss:Type="String"><xsl:value-of select="title" /></Data></Cell>
        </xsl:for-each>
	   </Row>
	   <xsl:for-each select="//rows/row">
	   <Row ss:AutoFitHeight="0">
	        <xsl:for-each select="./cell">
	        <xsl:if test="not(@special = 'ui')">
			<Cell>
				<Data ss:Type="String"><xsl:if test="not(count(./*))"><xsl:value-of select="." /></xsl:if><xsl:if test="count(./*)"><xsl:copy-of select="./*" /></xsl:if></Data>
			</Cell>
	        </xsl:if>
	        </xsl:for-each>
	   </Row>
	   </xsl:for-each>
	  </Table>
	  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
	   <Unsynced/>
	   <Print>
	    <ValidPrinterInfo/>
	    <PaperSizeIndex>9</PaperSizeIndex>
	    <HorizontalResolution>600</HorizontalResolution>
	    <VerticalResolution>600</VerticalResolution>
	   </Print>
	   <Selected/>
	   <Panes>
	    <Pane>
	     <Number>3</Number>
	     <ActiveRow>12</ActiveRow>
	     <ActiveCol>3</ActiveCol>
	    </Pane>
	   </Panes>
	   <ProtectObjects>False</ProtectObjects>
	   <ProtectScenarios>False</ProtectScenarios>
	  </WorksheetOptions>
	 </Worksheet>
	 <x:ExcelWorkbook></x:ExcelWorkbook>
	</Workbook>

</xsl:template>
</xsl:stylesheet>