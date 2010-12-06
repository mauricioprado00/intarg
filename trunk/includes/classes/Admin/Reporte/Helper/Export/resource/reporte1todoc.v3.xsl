<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:php="http://php.net/xsl">

<xsl:template match="/entity">
	<!-- esto llama a la funcion de php 
	<xsl:value-of select="php:function('Core_Xslt::Template',./actividad,'test/template_nodes.phtml')" />
	-->
	<w:document xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml">
	  <w:body>
		<!-- Titulo -->
		<w:p w:rsidR="00E1640C" w:rsidRDefault="00F7226D">
		  <w:pPr>
			<w:jc w:val="center"/>
			<w:rPr>
			  <w:rFonts w:ascii="Courier" w:hAnsi="Courier"/>
			  <w:sz w:val="18"/>
			  <w:szCs w:val="18"/>

			  <w:lang w:val="es-ES"/>
			</w:rPr>
		  </w:pPr>
		  <w:r>
			<w:rPr>
			  <w:rFonts w:ascii="Courier" w:hAnsi="Courier"/>
			  <w:sz w:val="18"/>
			  <w:szCs w:val="18"/>
			  <w:lang w:val="es-ES"/>

			</w:rPr>
			<w:t xml:space="preserve">Reporte por tipo de actividad y por agencia</w:t>
		  </w:r>
		</w:p>
		<!-- espacio en blanco -->
		<w:p w:rsidR="00E1640C" w:rsidRDefault="00E1640C">
		  <w:pPr>

			<w:pBdr>
			  <w:bottom w:val="single" w:sz="4" w:space="1" w:color="auto"/>
			</w:pBdr>
			<w:jc w:val="both"/>
			<w:rPr>
			  <w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/>
			  <w:sz w:val="22"/>
			  <w:szCs w:val="22"/>
			  <w:lang w:val="es-ES"/>

			</w:rPr>
		  </w:pPr>
		</w:p>
		
		<xsl:for-each select="./inta_actividad_byestrategia">
			<!-- espacio en blanco -->
			<w:p w:rsidR="00931570" w:rsidRDefault="00931570" w:rsidP="00931570">
			  <w:pPr>
				<w:rPr>
				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>

			</w:p>
			<!-- comienza tipo de actividad -->
			<w:p w:rsidR="00931570" w:rsidRPr="0008153E" w:rsidRDefault="00931570" w:rsidP="00931570">
			  <w:pPr>
				<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
				<w:jc w:val="both"/>
				<w:rPr>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:b/>
				  <w:i/>

				  <w:sz w:val="26"/>
				  <w:szCs w:val="26"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:b/>

				  <w:sz w:val="26"/>
				  <w:szCs w:val="26"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t xml:space="preserve">Tipo de actividad: </w:t>
			  </w:r>
			  <w:r w:rsidRPr="0026421A">
				<w:rPr>

				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:b/>
				  <w:i/>
				  <w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
				  <w:sz w:val="36"/>
				  <w:szCs w:val="36"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<xsl:if test="count(./estrategia_actividad/estrategia/nombre) > 0">
				<w:t><xsl:value-of select="./estrategia_actividad/estrategia/nombre" /></w:t>
				</xsl:if>
				<xsl:if test="count(./estrategia_actividad/estrategia/nombre) = 0">
				<w:t>[planificada]</w:t>
				</xsl:if>
			  </w:r>
			  <w:r>
				<w:rPr>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:b/>
				  <w:i/>
				  <w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
				  <w:sz w:val="26"/>
				  <w:szCs w:val="26"/>

				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t xml:space="preserve">                                 </w:t>
			  </w:r>
			</w:p>
			<xsl:for-each select="./inta_actividad_byagencia">
				<!-- espacio en blanco -->
				<w:p w:rsidR="00931570" w:rsidRDefault="00931570" w:rsidP="00931570">
				  <w:pPr>
					<w:rPr>

					  <w:lang w:val="es-ES"/>
					</w:rPr>
				  </w:pPr>
				  <w:r>
					<w:rPr>
					  <w:sz w:val="20"/>
					  <w:szCs w:val="20"/>
					  <w:lang w:val="es-ES"/>
					</w:rPr>

					<w:t> </w:t>
				  </w:r>
				</w:p>
				<!-- comienza agencia -->
				<w:p w:rsidR="00931570" w:rsidRPr="002359A8" w:rsidRDefault="00931570" w:rsidP="00931570">
				  <w:pPr>
					<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
					<w:jc w:val="both"/>
					<w:rPr>

					  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
					  <w:b/>
					  <w:sz w:val="22"/>
					  <w:szCs w:val="22"/>
					  <w:lang w:val="es-ES"/>
					</w:rPr>
				  </w:pPr>
				  <w:r>
					<w:rPr>

					  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
					  <w:b/>
					  <w:sz w:val="22"/>
					  <w:szCs w:val="22"/>
					  <w:lang w:val="es-ES"/>
					</w:rPr>
					<w:t xml:space="preserve">AER: </w:t>
				  </w:r>

				  <w:r w:rsidRPr="0008153E">
					<w:rPr>
					  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
					  <w:b/>
					  <w:i/>
					  <w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
					  <w:sz w:val="22"/>
					  <w:szCs w:val="22"/>
					  <w:lang w:val="es-ES"/>

					</w:rPr>
					<w:t xml:space="preserve"><xsl:value-of select="./agencia_actividad/agencia/nombre" /></w:t>
				  </w:r>

				  <w:proofErr w:type="spellEnd"/>
				</w:p>
				<!-- espacio en blanco -->
				<w:p w:rsidR="00931570" w:rsidRDefault="00931570" w:rsidP="00931570">
				  <w:pPr>
					<w:jc w:val="center"/>
					<w:rPr>
					  <w:lang w:val="es-ES"/>
					</w:rPr>
				  </w:pPr>

				</w:p>
				<!-- tabla de actividades de agencia -->
				<w:tbl>
					
				  <w:tblPr>
					<w:tblpPr w:leftFromText="141" w:rightFromText="141" w:vertAnchor="text" w:horzAnchor="margin" w:tblpY="-47"/>
					<w:tblW w:w="13952" w:type="dxa"/>
					<w:tblBorders>
					  <w:top w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
					  <w:left w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
					  <w:bottom w:val="outset" w:sz="6" w:space="0" w:color="auto"/>

					  <w:right w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
					</w:tblBorders>
					<w:tblCellMar>
					  <w:top w:w="60" w:type="dxa"/>
					  <w:left w:w="60" w:type="dxa"/>
					  <w:bottom w:w="60" w:type="dxa"/>
					  <w:right w:w="60" w:type="dxa"/>
					</w:tblCellMar>
					<w:tblLook w:val="04A0"/>

				  </w:tblPr>
				  <w:tblGrid>
					<w:gridCol w:w="4253"/>
					<w:gridCol w:w="3827"/>
					<w:gridCol w:w="5872"/>
				  </w:tblGrid>
				  <!-- comienza fila cabecera de actividades -->
				  <w:tr w:rsidR="00931570" w:rsidTr="0003703D">
					<w:tc>
					  <w:tcPr>

						<w:tcW w:w="4253" w:type="dxa"/>
						<w:tcBorders>
						  <w:top w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
						  <w:left w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
						  <w:bottom w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
						  <w:right w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
						</w:tcBorders>
						<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
						<w:hideMark/>

					  </w:tcPr>
					  <w:p w:rsidR="00931570" w:rsidRPr="00517F6E" w:rsidRDefault="00931570" w:rsidP="0003703D">
						<w:pPr>
						  <w:jc w:val="center"/>
						  <w:rPr>
							<w:b/>
						  </w:rPr>
						</w:pPr>
						<w:r w:rsidRPr="00517F6E">

						  <w:rPr>
							<w:rFonts w:ascii="Trebuchet MS" w:hAnsi="Trebuchet MS"/>
							<w:b/>
							<w:color w:val="000000"/>
							<w:sz w:val="23"/>
							<w:szCs w:val="23"/>
						  </w:rPr>
						  <w:t>Actividades realizadas</w:t>

						</w:r>
					  </w:p>
					</w:tc>
					<w:tc>
					  <w:tcPr>
						<w:tcW w:w="3827" w:type="dxa"/>
						<w:tcBorders>
						  <w:top w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
						  <w:left w:val="outset" w:sz="6" w:space="0" w:color="auto"/>

						  <w:bottom w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
						  <w:right w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
						</w:tcBorders>
						<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
					  </w:tcPr>
					  <w:p w:rsidR="00931570" w:rsidRPr="00517F6E" w:rsidRDefault="00931570" w:rsidP="0003703D">
						<w:pPr>
						  <w:jc w:val="center"/>
						  <w:rPr>

							<w:b/>
						  </w:rPr>
						</w:pPr>
						<w:r w:rsidRPr="00517F6E">
						  <w:rPr>
							<w:b/>
						  </w:rPr>
						  <w:t>Observaciones</w:t>

						</w:r>
					  </w:p>
					</w:tc>
					<w:tc>
					  <w:tcPr>
						<w:tcW w:w="5872" w:type="dxa"/>
						<w:tcBorders>
						  <w:top w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
						  <w:left w:val="outset" w:sz="6" w:space="0" w:color="auto"/>

						  <w:bottom w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
						  <w:right w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
						</w:tcBorders>
						<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
					  </w:tcPr>
					  <w:p w:rsidR="00931570" w:rsidRPr="00517F6E" w:rsidRDefault="00931570" w:rsidP="0003703D">
						<w:pPr>
						  <w:jc w:val="center"/>
						  <w:rPr>

							<w:b/>
						  </w:rPr>
						</w:pPr>
						<w:r w:rsidRPr="00517F6E">
						  <w:rPr>
							<w:b/>
						  </w:rPr>
						  <w:t>% de realización</w:t>

						</w:r>
					  </w:p>
					</w:tc>
				  </w:tr>
				  <!-- fin fila cabecera de actividades -->
					<xsl:for-each select="./inta_actividad/reporte_actividad/actividad">

					  <!-- comienza fila de actividad -->
					  <w:tr w:rsidR="00931570" w:rsidRPr="005F47AC" w:rsidTr="0003703D">
						<w:tc>
						  <w:tcPr>
							<w:tcW w:w="4253" w:type="dxa"/>
							<w:tcBorders>

							  <w:top w:val="single" w:sz="8" w:space="0" w:color="auto"/>
							  <w:left w:val="nil"/>
							  <w:bottom w:val="single" w:sz="8" w:space="0" w:color="auto"/>
							  <w:right w:val="single" w:sz="8" w:space="0" w:color="auto"/>
							</w:tcBorders>
							<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
							<w:tcMar>
							  <w:top w:w="0" w:type="dxa"/>
							  <w:left w:w="108" w:type="dxa"/>

							  <w:bottom w:w="0" w:type="dxa"/>
							  <w:right w:w="108" w:type="dxa"/>
							</w:tcMar>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="00931570" w:rsidRPr="005F47AC" w:rsidRDefault="00931570" w:rsidP="0003703D">
							<w:pPr>
							  <w:jc w:val="both"/>
							  <w:rPr>

								<w:i/>
								<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
							  </w:rPr>
							</w:pPr>
							<w:r>
							  <w:rPr>
								<w:i/>
								<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
							  </w:rPr>

							  <w:t><xsl:value-of select="./nombre" /></w:t>
							</w:r>
						  </w:p>
						</w:tc>
						<w:tc>
						  <w:tcPr>
							<w:tcW w:w="3827" w:type="dxa"/>
							<w:tcBorders>

							  <w:top w:val="single" w:sz="8" w:space="0" w:color="auto"/>
							  <w:left w:val="nil"/>
							  <w:bottom w:val="single" w:sz="8" w:space="0" w:color="auto"/>
							  <w:right w:val="single" w:sz="8" w:space="0" w:color="auto"/>
							</w:tcBorders>
						  </w:tcPr>
						  <w:p w:rsidR="00931570" w:rsidRPr="005F47AC" w:rsidRDefault="00931570" w:rsidP="0003703D">
							<w:pPr>
							  <w:jc w:val="both"/>

							  <w:rPr>
								<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
								<w:i/>
								<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
								<w:sz w:val="16"/>
								<w:szCs w:val="16"/>
								<w:lang w:val="es-ES"/>
							  </w:rPr>
							</w:pPr>

							<w:r>
							  <w:rPr>
								<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
								<w:i/>
								<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
								<w:sz w:val="16"/>
								<w:szCs w:val="16"/>
								<w:lang w:val="es-ES"/>
							  </w:rPr>
							  <w:t><xsl:value-of select="php:function('strip_tags',string(./observaciones))" /></w:t>
							</w:r>
						  </w:p>
						</w:tc>
						<w:tc>
						  <w:tcPr>
							<w:tcW w:w="5872" w:type="dxa"/>
							<w:tcBorders>

							  <w:top w:val="single" w:sz="8" w:space="0" w:color="auto"/>
							  <w:left w:val="nil"/>
							  <w:bottom w:val="single" w:sz="8" w:space="0" w:color="auto"/>
							  <w:right w:val="single" w:sz="8" w:space="0" w:color="auto"/>
							</w:tcBorders>
						  </w:tcPr>
						  <w:p w:rsidR="00931570" w:rsidRPr="005F47AC" w:rsidRDefault="00931570" w:rsidP="0003703D">
							<w:pPr>
							  <w:jc w:val="both"/>

							  <w:rPr>
								<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
								<w:i/>
								<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
								<w:sz w:val="16"/>
								<w:szCs w:val="16"/>
								<w:lang w:val="es-ES"/>
							  </w:rPr>
							</w:pPr>

							<w:r>
							  <w:rPr>
								<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
								<w:i/>
								<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
								<w:sz w:val="16"/>
								<w:szCs w:val="16"/>
								<w:lang w:val="es-ES"/>
							  </w:rPr>

							  <w:t><xsl:value-of select="./porcentaje_cumplimiento" />%</w:t>
							</w:r>
						  </w:p>
						</w:tc>
					  </w:tr>
					  <!-- fin fila de actividad -->
					</xsl:for-each>

				
				</w:tbl>
			</xsl:for-each>
			<!--
			<actividad_nombre>
				<xsl:value-of select="php:function('Core_Xslt::Template',.,'reporte/xmlserver/html/detalle.phtml')" />
			</actividad_nombre>
			<xsl:copy-of select="./responsable_nombre_completo" />
			-->
		</xsl:for-each>
	<!-- espacio en blanco -->
		<w:sectPr w:rsidR="00D548FD" w:rsidRPr="00B05D70" w:rsidSect="00CB1C8F">
		  <w:pgSz w:w="16838" w:h="11906" w:orient="landscape"/>
		  <w:pgMar w:top="851" w:right="1529" w:bottom="1701" w:left="1417" w:header="708" w:footer="708" w:gutter="0"/>
		  <w:cols w:space="708"/>
		  <w:docGrid w:linePitch="360"/>
		</w:sectPr>
	  </w:body>
	</w:document>
</xsl:template>
</xsl:stylesheet>