<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:php="http://php.net/xsl">

<xsl:template match="/entity">
	<!-- esto llama a la funcion de php 
	<xsl:value-of select="php:function('Core_Xslt::Template',./actividad,'test/template_nodes.phtml')" />
	-->
	<w:document xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml">
	  <w:body>
		<!-- titulo "Reporte por agencia" -->
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
			<w:t>Reporte por agencia</w:t>
		  </w:r>
		</w:p>
		<!-- linea horizontal mas espacio en blanco -->
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
		<!-- espacio en blanco -->
		<w:p w:rsidR="00E1640C" w:rsidRDefault="00E1640C">
		  <w:pPr>
			<w:jc w:val="both"/>
			<w:rPr>
			  <w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/>
			  <w:sz w:val="22"/>
			  <w:szCs w:val="22"/>
			  <w:lang w:val="es-ES"/>

			</w:rPr>
		  </w:pPr>
		</w:p>
		<!-- espacio en blanco -->
		<w:p w:rsidR="00E1640C" w:rsidRDefault="00E1640C">
		  <w:pPr>
			<w:tabs>
			  <w:tab w:val="left" w:pos="1985"/>
			</w:tabs>
			<w:jc w:val="center"/>

			<w:rPr>
			  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
			  <w:i/>
			  <w:color w:val="000000"/>
			  <w:sz w:val="28"/>
			  <w:szCs w:val="28"/>
			  <w:lang w:val="es-ES"/>
			</w:rPr>
		  </w:pPr>

		</w:p>
		<!-- espacio en blanco -->
		<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432" w:rsidP="00E6534C">
		  <w:pPr>
			<w:jc w:val="both"/>
			<w:rPr>
			  <w:lang w:val="es-ES_tradnl"/>
			</w:rPr>
		  </w:pPr>
		  <w:r>

			<w:rPr>
			  <w:sz w:val="22"/>
			  <w:szCs w:val="22"/>
			  <w:lang w:val="es-ES"/>
			</w:rPr>
			<w:t> </w:t>
		  </w:r>
		</w:p>

		<xsl:for-each select="./inta_actividad_byagencia">
		<agencia>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:jc w:val="center"/>
				<w:rPr>
				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>

				  <w:color w:val="000000"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t> </w:t>
			  </w:r>
			</w:p>
			<!-- Titulo de agencia -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00570EB4">
			  <w:pPr>

				<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
				<w:jc w:val="both"/>
				<w:rPr>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:b/>
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

				<w:t><xsl:value-of select="./actividad_agencia/agencia/nombre" /></w:t>
			  </w:r>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:rPr>
				  <w:sz w:val="20"/>
				  <w:szCs w:val="20"/>
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
			<!-- Descripcion de agencia -->
			<w:p w:rsidR="00E6534C" w:rsidRDefault="00E6534C" w:rsidP="00E6534C">
			  <w:pPr>
				<w:rPr>
				  <w:rStyle w:val="apple-style-span"/>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:color w:val="000000"/>
				  <w:sz w:val="17"/>
				  <w:szCs w:val="17"/>

				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:rStyle w:val="apple-style-span"/>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:color w:val="000000"/>
				  <w:sz w:val="17"/>
				  <w:szCs w:val="17"/>

				</w:rPr>
				<w:t>Direccion: <xsl:value-of select="./agencia_actividad/agencia/direccion" /></w:t>
			  </w:r>
			</w:p>
			<w:p w:rsidR="00E6534C" w:rsidRDefault="00E6534C" w:rsidP="00E6534C">
			  <w:pPr>
				<w:rPr>
				  <w:rStyle w:val="apple-style-span"/>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:color w:val="000000"/>
				  <w:sz w:val="17"/>
				  <w:szCs w:val="17"/>

				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:rStyle w:val="apple-style-span"/>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:color w:val="000000"/>
				  <w:sz w:val="17"/>
				  <w:szCs w:val="17"/>

				</w:rPr>
				<w:t>Teléfono: <xsl:value-of select="./agencia_actividad/agencia/telefono" /></w:t>
			  </w:r>
			</w:p>
			<w:p w:rsidR="00E6534C" w:rsidRDefault="00E6534C" w:rsidP="00E6534C">
			  <w:pPr>
				<w:rPr>
				  <w:rStyle w:val="apple-style-span"/>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:color w:val="000000"/>
				  <w:sz w:val="17"/>
				  <w:szCs w:val="17"/>

				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:rStyle w:val="apple-style-span"/>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:color w:val="000000"/>
				  <w:sz w:val="17"/>
				  <w:szCs w:val="17"/>

				</w:rPr>
				<w:t>Email: <xsl:value-of select="./agencia_actividad/agencia/email" /></w:t>
			  </w:r>
			</w:p>
			<w:p w:rsidR="00E6534C" w:rsidRDefault="00E6534C" w:rsidP="00E6534C">
			  <w:pPr>
				<w:rPr>
				  <w:rStyle w:val="apple-style-span"/>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:color w:val="000000"/>
				  <w:sz w:val="17"/>
				  <w:szCs w:val="17"/>

				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:rStyle w:val="apple-style-span"/>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:color w:val="000000"/>
				  <w:sz w:val="17"/>
				  <w:szCs w:val="17"/>

				</w:rPr>
				<w:t>Agentes:</w:t>
			  </w:r>
			</w:p>
			<xsl:for-each select="./actividad_agencia/agencia/agencia_usuario/usuario">
				<w:p w:rsidR="004471D1" w:rsidRPr="005F47AC" w:rsidRDefault="005F47AC" w:rsidP="005F47AC">
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
					<w:r w:rsidRPr="005F47AC">
					  <w:rPr>
						<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
						<w:i/>
						<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
						<w:sz w:val="16"/>
						<w:szCs w:val="16"/>
						<w:lang w:val="es-ES"/>

					  </w:rPr>
					  <w:t xml:space="preserve">
						<xsl:value-of select="./apellido" />, <xsl:value-of select="./nombre" />
					  </w:t>
					</w:r>
				</w:p>
			</xsl:for-each>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:jc w:val="center"/>
				<w:rPr>

				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:color w:val="000000"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t> </w:t>

			  </w:r>
			</w:p>
			<!-- Titulo de caracterización de la zona -->
			<w:p w:rsidR="00E6534C" w:rsidRDefault="00E6534C" w:rsidP="00E6534C">
			  <w:pPr>
				<w:pStyle w:val="Ttulo1"/>
				<w:pBdr>
				  <w:top w:val="single" w:sz="6" w:space="0" w:color="CCCCCC"/>
				  <w:bottom w:val="single" w:sz="6" w:space="0" w:color="CCCCCC"/>
				</w:pBdr>

				<w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/>
				<w:spacing w:before="240" w:beforeAutospacing="0" w:after="240" w:afterAutospacing="0"/>
				<w:rPr>
				  <w:rFonts w:ascii="Trebuchet MS" w:hAnsi="Trebuchet MS"/>
				  <w:color w:val="000000"/>
				  <w:sz w:val="23"/>
				  <w:szCs w:val="23"/>
				</w:rPr>
			  </w:pPr>

			  <w:r>
				<w:rPr>
				  <w:rFonts w:ascii="Trebuchet MS" w:hAnsi="Trebuchet MS"/>
				  <w:color w:val="000000"/>
				  <w:sz w:val="23"/>
				  <w:szCs w:val="23"/>
				</w:rPr>
				<w:t xml:space="preserve">Caracterización de la zona </w:t>

			  </w:r>
			</w:p>
			<!-- "insertar documento word" -->
			<w:p w:rsidR="00E6534C" w:rsidRDefault="00E6534C" w:rsidP="00E6534C">
			  <w:r>
				<w:t xml:space="preserve">Insertar el documento Word correspondiente que está anexado en la base </w:t>
			  </w:r>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">

			  <w:pPr>
				<w:jc w:val="center"/>
				<w:rPr>
				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:color w:val="000000"/>

				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t> </w:t>
			  </w:r>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:jc w:val="center"/>

				<w:rPr>
				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:color w:val="000000"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>

				<w:t>  </w:t>
			  </w:r>
			</w:p>
			<!-- Titulo de audiencias priorizadas y problemas asociados -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="004471D1">
			  <w:pPr>
				<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
				<w:jc w:val="both"/>
				<w:rPr>

				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:b/>
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
				<w:t xml:space="preserve">Audiencias </w:t>
			  </w:r>

			  <w:r w:rsidR="00E6534C">
				<w:rPr>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:b/>
				  <w:sz w:val="26"/>
				  <w:szCs w:val="26"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t xml:space="preserve">priorizadas </w:t>

			  </w:r>
			  <w:r>
				<w:rPr>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:b/>
				  <w:sz w:val="26"/>
				  <w:szCs w:val="26"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>

				<w:t>y Problemas asociados</w:t>
			  </w:r>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:jc w:val="both"/>
				<w:rPr>
				  <w:lang w:val="es-ES"/>

				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:sz w:val="22"/>
				  <w:szCs w:val="22"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>

				<w:t> </w:t>
			  </w:r>
			</w:p>
			<!-- Tabla de audiencias priorizadas y problemas asociados -->
			<w:tbl>
			  <!-- definicion de tabla -->
			  <w:tblPr>
				<w:tblW w:w="10833" w:type="dxa"/>
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
				<w:gridCol w:w="2470"/>
				<w:gridCol w:w="4536"/>
				<w:gridCol w:w="3827"/>
			  </w:tblGrid>
			  <!-- Fila cabecera -->
			  <w:tr w:rsidR="004471D1" w:rsidTr="00E6534C">
				<w:tc>
				  <w:tcPr>
					<w:tcW w:w="2470" w:type="dxa"/>
					<w:tcBorders>
					  <w:top w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
					  <w:left w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
					  <w:bottom w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
					  <w:right w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
					</w:tcBorders>

					<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
					<w:hideMark/>
				  </w:tcPr>
				  <w:p w:rsidR="004471D1" w:rsidRDefault="004471D1" w:rsidP="004471D1">
					<w:pPr>
					  <w:jc w:val="center"/>
					</w:pPr>
					<w:r>
					  <w:rPr>

						<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
						<w:sz w:val="16"/>
						<w:szCs w:val="16"/>
						<w:lang w:val="es-ES"/>
					  </w:rPr>
					  <w:t>Tipo audiencia</w:t>
					</w:r>
				  </w:p>

				</w:tc>
				<w:tc>
				  <w:tcPr>
					<w:tcW w:w="4536" w:type="dxa"/>
					<w:tcBorders>
					  <w:top w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
					  <w:left w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
					  <w:bottom w:val="outset" w:sz="6" w:space="0" w:color="auto"/>
					  <w:right w:val="outset" w:sz="6" w:space="0" w:color="auto"/>

					</w:tcBorders>
					<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
					<w:hideMark/>
				  </w:tcPr>
				  <w:p w:rsidR="004471D1" w:rsidRDefault="004471D1">
					<w:pPr>
					  <w:jc w:val="center"/>
					</w:pPr>
					<w:r>

					  <w:t>Nombre (se llamará Descripción cuando cambie los títulos)</w:t>
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
				  <w:p w:rsidR="004471D1" w:rsidRDefault="004471D1">
					<w:pPr>

					  <w:jc w:val="center"/>
					</w:pPr>
					<w:r>
					  <w:t>Problemas Asociados</w:t>
					</w:r>
				  </w:p>
				</w:tc>
			  </w:tr>
				<xsl:for-each select="./audiencias_priorizadas/entity/audiencia">
				<audiencia_priorizada>

				  <!-- Fila de tipo audiencia/audiencia/problemas -->
				  <w:tr w:rsidR="004471D1" w:rsidRPr="005F47AC" w:rsidTr="00E6534C">
					<!-- celda de tipo de audiencia -->
					<w:tc>
					  <w:tcPr>
						<w:tcW w:w="2470" w:type="dxa"/>
						<w:tcBorders>
						  <w:top w:val="single" w:sz="8" w:space="0" w:color="auto"/>
						  <w:left w:val="single" w:sz="8" w:space="0" w:color="auto"/>
						  <w:bottom w:val="single" w:sz="8" w:space="0" w:color="auto"/>
						  <w:right w:val="single" w:sz="8" w:space="0" w:color="auto"/>

						</w:tcBorders>
						<w:tcMar>
						  <w:top w:w="0" w:type="dxa"/>
						  <w:left w:w="108" w:type="dxa"/>
						  <w:bottom w:w="0" w:type="dxa"/>
						  <w:right w:w="108" w:type="dxa"/>
						</w:tcMar>
						<w:hideMark/>
					  </w:tcPr>

					  <w:p w:rsidR="004471D1" w:rsidRPr="005F47AC" w:rsidRDefault="004471D1" w:rsidP="002D58A0">
						<w:pPr>
						  <w:jc w:val="both"/>
						  <w:rPr>
							<w:i/>
							<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
						  </w:rPr>
						</w:pPr>
						<w:r w:rsidRPr="005F47AC">

						  <w:rPr>
							<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
							<w:i/>
							<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
							<w:sz w:val="16"/>
							<w:szCs w:val="16"/>
							<w:lang w:val="es-ES"/>
						  </w:rPr>
						  <w:t xml:space="preserve"><xsl:value-of select="./audiencia_tipo_audiencia/tipo_audiencia/nombre" /></w:t>
						</w:r>
					  </w:p>
					</w:tc>
					<!-- celda de audiencia -->
					<w:tc>
					  <w:tcPr>
						<w:tcW w:w="4536" w:type="dxa"/>

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
					  <w:p w:rsidR="004471D1" w:rsidRPr="005F47AC" w:rsidRDefault="00FF5CA2" w:rsidP="005F47AC">
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
					<!-- celda de problemas de agencia -->
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
						<xsl:for-each select="./audiencia_problemas/entity">
						  <w:p w:rsidR="004471D1" w:rsidRPr="005F47AC" w:rsidRDefault="005F47AC" w:rsidP="005F47AC">
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
							<w:r w:rsidRPr="005F47AC">
							  <w:rPr>
								<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
								<w:i/>
								<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
								<w:sz w:val="16"/>
								<w:szCs w:val="16"/>
								<w:lang w:val="es-ES"/>

							  </w:rPr>
							  <w:t xml:space="preserve"><xsl:value-of select="./nombre" /></w:t>
							</w:r>
						  </w:p>
						</xsl:for-each>
					</w:tc>
				  </w:tr>
				</audiencia_priorizada>
				</xsl:for-each>
			</w:tbl>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:jc w:val="center"/>
				<w:rPr>
				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>

				  <w:color w:val="000000"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t> </w:t>
			  </w:r>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>

				<w:jc w:val="center"/>
				<w:rPr>
				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:color w:val="000000"/>
				  <w:lang w:val="es-ES"/>

				</w:rPr>
				<w:t> </w:t>
			  </w:r>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:jc w:val="center"/>
				<w:rPr>

				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:color w:val="000000"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t> </w:t>

			  </w:r>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:jc w:val="center"/>
				<w:rPr>
				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>

			  <w:r>
				<w:rPr>
				  <w:color w:val="000000"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t> </w:t>
			  </w:r>
			</w:p>

			<!-- Titulo de objetivo genera de la agencia -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
				<w:jc w:val="both"/>
				<w:rPr>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:b/>
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
				<w:t>Objetivo General</w:t>
			  </w:r>
			  <w:r w:rsidR="004471D1">
				<w:rPr>
				  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
				  <w:b/>

				  <w:sz w:val="26"/>
				  <w:szCs w:val="26"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t xml:space="preserve"> de la Agencia</w:t>
			  </w:r>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">

			  <w:pPr>
				<w:rPr>
				  <w:sz w:val="20"/>
				  <w:szCs w:val="20"/>
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
			
			<!-- Descripcion de objetivo general de la agencia -->
			<w:tbl>

			  <w:tblPr>
				<w:tblW w:w="9000" w:type="dxa"/>
				<w:tblCellSpacing w:w="15" w:type="dxa"/>
				<w:tblCellMar>
				  <w:top w:w="15" w:type="dxa"/>
				  <w:left w:w="15" w:type="dxa"/>
				  <w:bottom w:w="15" w:type="dxa"/>
				  <w:right w:w="15" w:type="dxa"/>
				</w:tblCellMar>

				<w:tblLook w:val="04A0"/>
			  </w:tblPr>
			  <w:tblGrid>
				<w:gridCol w:w="9000"/>
			  </w:tblGrid>
			  <w:tr w:rsidR="00E1640C" w:rsidRPr="005F47AC">
				<w:trPr>
				  <w:tblCellSpacing w:w="15" w:type="dxa"/>
				</w:trPr>

				<w:tc>
				  <w:tcPr>
					<w:tcW w:w="0" w:type="auto"/>
					<w:vAlign w:val="center"/>
					<w:hideMark/>
				  </w:tcPr>
				  <w:p w:rsidR="00E1640C" w:rsidRPr="005F47AC" w:rsidRDefault="00C40432">
					<w:pPr>
					  <w:rPr>

						<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
						<w:i/>
						<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
						<w:sz w:val="22"/>
						<w:szCs w:val="22"/>
						<w:lang w:val="es-ES"/>
					  </w:rPr>
					</w:pPr>
					<w:r w:rsidRPr="005F47AC">

					  <w:rPr>
						<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
						<w:i/>
						<w:color w:val="548DD4" w:themeColor="text2" w:themeTint="99"/>
						<w:sz w:val="22"/>
						<w:szCs w:val="22"/>
						<w:lang w:val="es-ES"/>
					  </w:rPr>
					  <w:t xml:space="preserve"><xsl:value-of select="php:function('strip_tags',string(./actividad_agencia/agencia/descripcion))" /></w:t>
					</w:r>
				  </w:p>
				</w:tc>
			  </w:tr>
			</w:tbl>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:rPr>
				  <w:sz w:val="20"/>

				  <w:szCs w:val="20"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:sz w:val="20"/>
				  <w:szCs w:val="20"/>
				  <w:lang w:val="es-ES"/>

				</w:rPr>
				<w:lastRenderedPageBreak/>
				<w:t> </w:t>
			  </w:r>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00E1640C">
			  <w:pPr>
				<w:jc w:val="center"/>

				<w:rPr>
				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:jc w:val="center"/>
				<w:rPr>

				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>
			  <w:r>
				<w:rPr>
				  <w:color w:val="000000"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t> </w:t>

			  </w:r>
			</w:p>
			<!-- espacio en blanco -->
			<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">
			  <w:pPr>
				<w:jc w:val="center"/>
				<w:rPr>
				  <w:lang w:val="es-ES_tradnl"/>
				</w:rPr>
			  </w:pPr>

			  <w:r>
				<w:rPr>
				  <w:color w:val="000000"/>
				  <w:lang w:val="es-ES"/>
				</w:rPr>
				<w:t> </w:t>
			  </w:r>
			</w:p>
			<xsl:for-each select="./inta_actividad_byobjetivo">
			<objetivo>
				<!-- Titulo objetivo específico -->
				<w:p w:rsidR="00E1640C" w:rsidRDefault="00FF5CA2">
				  <w:pPr>
					<w:shd w:val="clear" w:color="auto" w:fill="E6E6E6"/>
					<w:jc w:val="both"/>
					<w:rPr>
					  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
					  <w:b/>
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
					<w:t>Objetivo</w:t>
				  </w:r>
				  <w:r w:rsidR="00C40432">
					<w:rPr>
					  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
					  <w:b/>

					  <w:sz w:val="26"/>
					  <w:szCs w:val="26"/>
					  <w:lang w:val="es-ES"/>
					</w:rPr>
					<w:t xml:space="preserve"> Específico</w:t>
				  </w:r>
				  <w:r>
					<w:rPr>

					  <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
					  <w:b/>
					  <w:sz w:val="26"/>
					  <w:szCs w:val="26"/>
					  <w:lang w:val="es-ES"/>
					</w:rPr>
					<w:t xml:space="preserve"> NOBJE</w:t>
				  </w:r>

				</w:p>
				<!-- espacio en blanco -->
				<w:p w:rsidR="009A5A92" w:rsidRDefault="009A5A92">
				  <w:pPr>
					<w:rPr>
					  <w:sz w:val="20"/>
					  <w:szCs w:val="20"/>
					  <w:lang w:val="es-ES"/>
					</w:rPr>
				  </w:pPr>

				</w:p>
				<!-- Descripciond el objetivo específico -->
				<w:p w:rsidR="00B6704D" w:rsidRPr="00C545A1" w:rsidRDefault="00B6704D" w:rsidP="00FF5CA2">
				  <w:pPr>
					<w:rPr>
					  <w:lang w:eastAsia="es-ES"/>
					</w:rPr>
				  </w:pPr>
				  <w:r w:rsidRPr="00FF5CA2">
					<w:rPr>

					  <w:b/>
					  <w:bCs/>
					  <w:color w:val="0000FF"/>
					  <w:lang w:eastAsia="es-ES"/>
					</w:rPr>
					<w:t><xsl:value-of select="php:function('strip_tags',string(./actividad_objetivo/objetivo/descripcion))" /></w:t>
				  </w:r>
				</w:p>
				<xsl:for-each select="./inta_actividad_byresultado_esperado">
				<resultado_esperado>
					<!-- espacio en blanco -->
					<w:p w:rsidR="00B6704D" w:rsidRPr="00B6704D" w:rsidRDefault="00B6704D">
					  <w:pPr>
						<w:rPr>
						  <w:sz w:val="20"/>
						  <w:szCs w:val="20"/>
						</w:rPr>
					  </w:pPr>
					</w:p>
					<!-- tabla de resultados esperados del objetivo -->
					<w:tbl>
						<!-- Definicion de la tabla -->
					  <w:tblPr>
						<w:tblW w:w="12453" w:type="dxa"/>
						<w:tblLayout w:type="fixed"/>
						<w:tblCellMar>
						  <w:top w:w="15" w:type="dxa"/>
						  <w:left w:w="15" w:type="dxa"/>
						  <w:bottom w:w="15" w:type="dxa"/>
						  <w:right w:w="15" w:type="dxa"/>
						</w:tblCellMar>

						<w:tblLook w:val="04A0"/>
					  </w:tblPr>
					  <w:tblGrid>
						<w:gridCol w:w="1620"/>
						<w:gridCol w:w="10833"/>
					  </w:tblGrid>
					  <!-- Fila de resultado esperado -->
					  <w:tr w:rsidR="009A5A92" w:rsidRPr="001E407A" w:rsidTr="00B6704D">
						<!-- Celda del resultado esperado -->
						<w:tc>
						  <w:tcPr>

							<w:tcW w:w="1620" w:type="dxa"/>
							<w:tcBorders>
							  <w:top w:val="single" w:sz="2" w:space="0" w:color="000000"/>
							  <w:left w:val="single" w:sz="2" w:space="0" w:color="000000"/>
							  <w:bottom w:val="single" w:sz="2" w:space="0" w:color="000000"/>
							  <w:right w:val="single" w:sz="2" w:space="0" w:color="000000"/>
							</w:tcBorders>
							<w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
							<w:tcMar>

							  <w:top w:w="120" w:type="dxa"/>
							  <w:left w:w="120" w:type="dxa"/>
							  <w:bottom w:w="120" w:type="dxa"/>
							  <w:right w:w="120" w:type="dxa"/>
							</w:tcMar>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="009A5A92" w:rsidRPr="00C545A1" w:rsidRDefault="009A5A92" w:rsidP="009A5A92">

							<w:pPr>
							  <w:rPr>
								<w:lang w:eastAsia="es-ES"/>
							  </w:rPr>
							</w:pPr>
							<w:r w:rsidRPr="00C545A1">
							  <w:rPr>
								<w:b/>
								<w:bCs/>

								<w:lang w:eastAsia="es-ES"/>
							  </w:rPr>
							  <w:t>Resultado Esperado</w:t>
							</w:r>
							<w:r>
							  <w:rPr>
								<w:b/>
								<w:bCs/>

								<w:lang w:eastAsia="es-ES"/>
							  </w:rPr>
							  <w:t xml:space="preserve"> NDRE</w:t>
							</w:r>
							<w:r w:rsidRPr="00C545A1">
							  <w:rPr>
								<w:b/>
								<w:bCs/>

								<w:lang w:eastAsia="es-ES"/>
							  </w:rPr>
							  <w:t>:</w:t>
							</w:r>
						  </w:p>
						</w:tc>
						<!-- celda de Descripcion del resultado esperado + tabla de indicadores y medios de verificacion -->
						<w:tc>
						  <!-- definicion de la tabla -->
						  <w:tcPr>

							<w:tcW w:w="10833" w:type="dxa"/>
							<w:tcBorders>
							  <w:top w:val="single" w:sz="2" w:space="0" w:color="000000"/>
							  <w:left w:val="single" w:sz="2" w:space="0" w:color="000000"/>
							  <w:bottom w:val="single" w:sz="2" w:space="0" w:color="000000"/>
							  <w:right w:val="single" w:sz="2" w:space="0" w:color="000000"/>
							</w:tcBorders>
							<w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
							<w:tcMar>

							  <w:top w:w="120" w:type="dxa"/>
							  <w:left w:w="120" w:type="dxa"/>
							  <w:bottom w:w="120" w:type="dxa"/>
							  <w:right w:w="120" w:type="dxa"/>
							</w:tcMar>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <!-- tabla de indicadores y medios de verificacion -->
						  <w:tbl>
							<!-- definicion de la tabla -->
							<w:tblPr>
							  <w:tblpPr w:leftFromText="141" w:rightFromText="141" w:vertAnchor="page" w:horzAnchor="margin" w:tblpXSpec="right" w:tblpY="241"/>
							  <w:tblOverlap w:val="never"/>
							  <w:tblW w:w="3536" w:type="dxa"/>
							  <w:tblLayout w:type="fixed"/>
							  <w:tblCellMar>
								<w:top w:w="15" w:type="dxa"/>
								<w:left w:w="15" w:type="dxa"/>
								<w:bottom w:w="15" w:type="dxa"/>

								<w:right w:w="15" w:type="dxa"/>
							  </w:tblCellMar>
							  <w:tblLook w:val="04A0"/>
							</w:tblPr>
							<w:tblGrid>
							  <w:gridCol w:w="2260"/>
							  <w:gridCol w:w="1276"/>
							</w:tblGrid>
							<!-- fila cabecera de indicador de resultado/medio de verificacion -->
							<w:tr w:rsidR="009A5A92" w:rsidRPr="001E407A" w:rsidTr="00A47FB3">
							  <w:trPr>
								<w:trHeight w:val="436"/>
							  </w:trPr>
							  <w:tc>
								<w:tcPr>
								  <w:tcW w:w="2260" w:type="dxa"/>
								  <w:tcBorders>
									<w:top w:val="single" w:sz="6" w:space="0" w:color="000000"/>
									<w:left w:val="single" w:sz="6" w:space="0" w:color="000000"/>

									<w:bottom w:val="single" w:sz="6" w:space="0" w:color="000000"/>
									<w:right w:val="single" w:sz="6" w:space="0" w:color="000000"/>
								  </w:tcBorders>
								  <w:shd w:val="clear" w:color="auto" w:fill="CCCCCC"/>
								  <w:noWrap/>
								  <w:tcMar>
									<w:top w:w="120" w:type="dxa"/>
									<w:left w:w="120" w:type="dxa"/>
									<w:bottom w:w="120" w:type="dxa"/>

									<w:right w:w="120" w:type="dxa"/>
								  </w:tcMar>
								  <w:vAlign w:val="center"/>
								  <w:hideMark/>
								</w:tcPr>
								<w:p w:rsidR="009A5A92" w:rsidRPr="00A52939" w:rsidRDefault="009A5A92" w:rsidP="009A5A92">
								  <w:pPr>
									<w:jc w:val="center"/>
									<w:rPr>

									  <w:b/>
									  <w:bCs/>
									  <w:color w:val="FFFFFF"/>
									  <w:sz w:val="20"/>
									  <w:szCs w:val="20"/>
									  <w:lang w:eastAsia="es-ES"/>
									</w:rPr>
								  </w:pPr>
								  <w:r w:rsidRPr="00A52939">

									<w:rPr>
									  <w:b/>
									  <w:bCs/>
									  <w:color w:val="000000"/>
									  <w:sz w:val="20"/>
									  <w:szCs w:val="20"/>
									  <w:lang w:eastAsia="es-ES"/>
									</w:rPr>
									<w:t>Indicador de resultado</w:t>

								  </w:r>
								</w:p>
							  </w:tc>
							  <w:tc>
								<w:tcPr>
								  <w:tcW w:w="1276" w:type="dxa"/>
								  <w:tcBorders>
									<w:top w:val="single" w:sz="6" w:space="0" w:color="000000"/>
									<w:left w:val="single" w:sz="6" w:space="0" w:color="000000"/>

									<w:bottom w:val="single" w:sz="6" w:space="0" w:color="000000"/>
									<w:right w:val="single" w:sz="6" w:space="0" w:color="000000"/>
								  </w:tcBorders>
								  <w:shd w:val="clear" w:color="auto" w:fill="CCCCCC"/>
								  <w:noWrap/>
								  <w:tcMar>
									<w:top w:w="120" w:type="dxa"/>
									<w:left w:w="120" w:type="dxa"/>
									<w:bottom w:w="120" w:type="dxa"/>

									<w:right w:w="120" w:type="dxa"/>
								  </w:tcMar>
								  <w:vAlign w:val="center"/>
								  <w:hideMark/>
								</w:tcPr>
								<w:p w:rsidR="009A5A92" w:rsidRPr="00A52939" w:rsidRDefault="009A5A92" w:rsidP="009A5A92">
								  <w:pPr>
									<w:jc w:val="center"/>
									<w:rPr>

									  <w:b/>
									  <w:bCs/>
									  <w:color w:val="FFFFFF"/>
									  <w:sz w:val="20"/>
									  <w:szCs w:val="20"/>
									  <w:lang w:eastAsia="es-ES"/>
									</w:rPr>
								  </w:pPr>
								  <w:r w:rsidRPr="00A52939">

									<w:rPr>
									  <w:b/>
									  <w:bCs/>
									  <w:color w:val="000000"/>
									  <w:sz w:val="20"/>
									  <w:szCs w:val="20"/>
									  <w:lang w:eastAsia="es-ES"/>
									</w:rPr>
									<w:t>Medio de verificación</w:t>

								  </w:r>
								</w:p>
							  </w:tc>
							</w:tr>
							
							<xsl:for-each select="./actividad_resultado_esperado/resultado_esperado/resultado_esperado_indicador/indicador_resultado">
							<indicador>
								<!-- fila de detalle de indicador / resultado esperado -->
								<w:tr w:rsidR="009A5A92" w:rsidRPr="001E407A" w:rsidTr="00A47FB3">
								  <!-- celda de inicador -->
								  <w:tc>
									<w:tcPr>
									  <w:tcW w:w="2260" w:type="dxa"/>
									  <w:tcBorders>

										<w:top w:val="single" w:sz="6" w:space="0" w:color="000000"/>
										<w:left w:val="single" w:sz="6" w:space="0" w:color="000000"/>
										<w:bottom w:val="single" w:sz="6" w:space="0" w:color="000000"/>
										<w:right w:val="single" w:sz="6" w:space="0" w:color="000000"/>
									  </w:tcBorders>
									  <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
									  <w:tcMar>
										<w:top w:w="120" w:type="dxa"/>
										<w:left w:w="120" w:type="dxa"/>

										<w:bottom w:w="120" w:type="dxa"/>
										<w:right w:w="120" w:type="dxa"/>
									  </w:tcMar>
									  <w:vAlign w:val="center"/>
									  <w:hideMark/>
									</w:tcPr>
									<w:p w:rsidR="009A5A92" w:rsidRPr="00C545A1" w:rsidRDefault="009A5A92" w:rsidP="009A5A92">
									  <w:pPr>
										<w:rPr>

										  <w:lang w:eastAsia="es-ES"/>
										</w:rPr>
									  </w:pPr>
									  <w:proofErr w:type="spellStart"/>
									  <w:r w:rsidRPr="00C545A1">
										<w:rPr>
										  <w:lang w:eastAsia="es-ES"/>
										</w:rPr>
										<w:t><xsl:value-of select="./resultado_esperado_indicador_instancia/indicador/nombre" /></w:t>
									  </w:r>
									</w:p>
								  </w:tc>
								  <!-- celda de resultado medio de verificacion -->
								  <w:tc>
									<w:tcPr>
									  <w:tcW w:w="1276" w:type="dxa"/>
									  <w:tcBorders>
										<w:top w:val="single" w:sz="6" w:space="0" w:color="000000"/>
										<w:left w:val="single" w:sz="6" w:space="0" w:color="000000"/>
										<w:bottom w:val="single" w:sz="6" w:space="0" w:color="000000"/>

										<w:right w:val="single" w:sz="6" w:space="0" w:color="000000"/>
									  </w:tcBorders>
									  <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
									  <w:tcMar>
										<w:top w:w="120" w:type="dxa"/>
										<w:left w:w="120" w:type="dxa"/>
										<w:bottom w:w="120" w:type="dxa"/>
										<w:right w:w="120" w:type="dxa"/>
									  </w:tcMar>

									  <w:vAlign w:val="center"/>
									  <w:hideMark/>
									</w:tcPr>
									<xsl:for-each select="./resultado_esperado_indicador_medios_verificacion/medio_verificacion_indicador_resultado/medio_verificacion_instancia/medio_verificacion">
									<medio_verificacion>
										<w:p w:rsidR="009A5A92" w:rsidRPr="00C545A1" w:rsidRDefault="009A5A92" w:rsidP="009A5A92">
										  <w:pPr>
											<w:rPr>
											  <w:lang w:eastAsia="es-ES"/>
											</w:rPr>
										  </w:pPr>

										  <w:r>
											<w:rPr>
											  <w:lang w:eastAsia="es-ES"/>
											</w:rPr>
											<w:t><xsl:value-of select="./nombre" /></w:t>
										  </w:r>
										</w:p>
									</medio_verificacion>
									</xsl:for-each>
								  </w:tc>
								</w:tr>
							</indicador>
							</xsl:for-each>
						  </w:tbl>
						  <!-- descripcion de el resultado esperado -->
						  <w:p w:rsidR="009A5A92" w:rsidRPr="00C545A1" w:rsidRDefault="009A5A92" w:rsidP="009A5A92">
							<w:pPr>
							  <w:rPr>
								<w:lang w:eastAsia="es-ES"/>
							  </w:rPr>
							</w:pPr>
							<w:r w:rsidRPr="00C545A1">

							  <w:rPr>
								<w:lang w:eastAsia="es-ES"/>
							  </w:rPr>
							  <w:t><xsl:value-of select="./actividad_resultado_esperado/resultado_esperado/descripcion"/></w:t>
							</w:r>
						  </w:p>
						</w:tc>
					  </w:tr>
					</w:tbl>
					<!-- espacio en blanco -->
					<w:p w:rsidR="00E1640C" w:rsidRDefault="00C40432">

					  <w:pPr>
						<w:rPr>
						  <w:sz w:val="20"/>
						  <w:szCs w:val="20"/>
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
					<!-- tabla vacia??? -->
					<w:tbl>
					  <w:tblPr>
						<w:tblW w:w="9000" w:type="dxa"/>
						<w:tblCellSpacing w:w="15" w:type="dxa"/>
						<w:tblCellMar>
						  <w:top w:w="15" w:type="dxa"/>
						  <w:left w:w="15" w:type="dxa"/>
						  <w:bottom w:w="15" w:type="dxa"/>
						  <w:right w:w="15" w:type="dxa"/>
						</w:tblCellMar>

						<w:tblLook w:val="04A0"/>
					  </w:tblPr>
					  <w:tblGrid>
						<w:gridCol w:w="9000"/>
					  </w:tblGrid>
					  <!-- fila vacia -->
					  <w:tr w:rsidR="00E1640C">
						<w:trPr>
						  <w:tblCellSpacing w:w="15" w:type="dxa"/>
						</w:trPr>

						<w:tc>
						  <w:tcPr>
							<w:tcW w:w="0" w:type="auto"/>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="00E1640C" w:rsidRDefault="00E1640C">
							<w:pPr>
							  <w:rPr>

								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							</w:pPr>
						  </w:p>
						</w:tc>
					  </w:tr>
					  <!-- fila vacia -->
					  <w:tr w:rsidR="00E1640C">
						<w:trPr>

						  <w:tblCellSpacing w:w="15" w:type="dxa"/>
						</w:trPr>
						<w:tc>
						  <w:tcPr>
							<w:tcW w:w="0" w:type="auto"/>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="005307E7" w:rsidRPr="004471D1" w:rsidRDefault="005307E7" w:rsidP="004471D1">

							<w:pPr>
							  <w:rPr>
								<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
								<w:b/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
								<w:lang w:val="es-ES"/>
							  </w:rPr>
							</w:pPr>

						  </w:p>
						</w:tc>
					  </w:tr>
					</w:tbl>
					
					<!-- espacio en blanco -->
					<w:p w:rsidR="009A5A92" w:rsidRDefault="00C40432">
					  <w:pPr>
						<w:rPr>
						  <w:sz w:val="20"/>
						  <w:szCs w:val="20"/>

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
					<!-- tabla de actividades que aportan al resultado esperado -->
					<w:tbl>
					  <!-- definicion de la tabla -->
					  <w:tblPr>
						<w:tblW w:w="12348" w:type="dxa"/>
						<w:tblLayout w:type="fixed"/>
						<w:tblCellMar>

						  <w:left w:w="70" w:type="dxa"/>
						  <w:right w:w="70" w:type="dxa"/>
						</w:tblCellMar>
						<w:tblLook w:val="04A0"/>
					  </w:tblPr>
					  <w:tblGrid>
						<w:gridCol w:w="2006"/>
						<w:gridCol w:w="3593"/>
						<w:gridCol w:w="1190"/>

						<w:gridCol w:w="884"/>
						<w:gridCol w:w="1701"/>
						<w:gridCol w:w="848"/>
						<w:gridCol w:w="2126"/>
					  </w:tblGrid>
					  <!-- fila de cabeceras de la tabla (actividades/cronograma/resposable/presupuesto/programa o proyecto/% de tiempo/tipo de actividad (estrategia) -->
					  <w:tr w:rsidR="009A5A92" w:rsidRPr="009A5A92" w:rsidTr="00B6704D">
						<w:trPr>
						  <w:trHeight w:val="405"/>
						</w:trPr>

						<!-- celda Actividad -->
						<w:tc>
						  <w:tcPr>
							<w:tcW w:w="2006" w:type="dxa"/>
							<w:tcBorders>
							  <w:top w:val="nil"/>
							  <w:left w:val="nil"/>
							  <w:bottom w:val="single" w:sz="12" w:space="0" w:color="4F81BD"/>
							  <w:right w:val="nil"/>
							</w:tcBorders>

							<w:shd w:val="clear" w:color="000000" w:fill="FFFF00"/>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
							<w:pPr>
							  <w:jc w:val="center"/>
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>

								<w:bCs/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							</w:pPr>
							<w:r w:rsidRPr="00B546D6">
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>
								<w:bCs/>

								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							  <w:t>Actividad</w:t>
							</w:r>
							<w:r>
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>

								<w:bCs/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							  <w:t>es</w:t>
							</w:r>
						  </w:p>
						</w:tc>

						<!-- celda vacia -->
						<w:tc>
						  <w:tcPr>
							<w:tcW w:w="3593" w:type="dxa"/>
							<w:tcBorders>
							  <w:top w:val="nil"/>
							  <w:left w:val="nil"/>
							  <w:bottom w:val="single" w:sz="12" w:space="0" w:color="4F81BD"/>
							  <w:right w:val="nil"/>
							</w:tcBorders>

							<w:shd w:val="clear" w:color="000000" w:fill="FFFF00"/>
							<w:noWrap/>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
							<w:pPr>
							  <w:jc w:val="center"/>
							  <w:rPr>

								<w:rFonts w:cs="Calibri"/>
								<w:bCs/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							</w:pPr>
						  </w:p>
						</w:tc>
						<!-- celda Responsable -->
						<w:tc>

						  <w:tcPr>
							<w:tcW w:w="1190" w:type="dxa"/>
							<w:tcBorders>
							  <w:top w:val="nil"/>
							  <w:left w:val="nil"/>
							  <w:bottom w:val="single" w:sz="12" w:space="0" w:color="4F81BD"/>
							  <w:right w:val="nil"/>
							</w:tcBorders>
							<w:shd w:val="clear" w:color="000000" w:fill="FFFF00"/>

							<w:noWrap/>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
							<w:pPr>
							  <w:jc w:val="center"/>
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>

								<w:bCs/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							</w:pPr>
							<w:r w:rsidRPr="00B546D6">
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>
								<w:bCs/>

								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							  <w:t>Responsable</w:t>
							</w:r>
						  </w:p>
						</w:tc>
						<!-- celda Presupuesto -->
						<w:tc>

						  <w:tcPr>
							<w:tcW w:w="884" w:type="dxa"/>
							<w:tcBorders>
							  <w:top w:val="nil"/>
							  <w:left w:val="nil"/>
							  <w:bottom w:val="single" w:sz="12" w:space="0" w:color="4F81BD"/>
							  <w:right w:val="nil"/>
							</w:tcBorders>
							<w:shd w:val="clear" w:color="000000" w:fill="FFFF00"/>

							<w:noWrap/>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
							<w:pPr>
							  <w:jc w:val="center"/>
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>

								<w:bCs/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							</w:pPr>
							<w:r w:rsidRPr="00B546D6">
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>
								<w:bCs/>

								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							  <w:t>Presupuesto</w:t>
							</w:r>
						  </w:p>
						</w:tc>
						<!-- Programa/proyecto -->
						<w:tc>

						  <w:tcPr>
							<w:tcW w:w="1701" w:type="dxa"/>
							<w:tcBorders>
							  <w:top w:val="nil"/>
							  <w:left w:val="nil"/>
							  <w:bottom w:val="single" w:sz="12" w:space="0" w:color="4F81BD"/>
							  <w:right w:val="nil"/>
							</w:tcBorders>
							<w:shd w:val="clear" w:color="000000" w:fill="FFFF00"/>

							<w:noWrap/>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
							<w:pPr>
							  <w:jc w:val="center"/>
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>

								<w:bCs/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							</w:pPr>
							<w:r w:rsidRPr="00B546D6">
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>
								<w:bCs/>

								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							  <w:t>Programa/proyecto</w:t>
							</w:r>
						  </w:p>
						</w:tc>
						<!-- % de tiempo -->
						<w:tc>

						  <w:tcPr>
							<w:tcW w:w="848" w:type="dxa"/>
							<w:tcBorders>
							  <w:top w:val="nil"/>
							  <w:left w:val="nil"/>
							  <w:bottom w:val="single" w:sz="12" w:space="0" w:color="4F81BD"/>
							  <w:right w:val="nil"/>
							</w:tcBorders>
							<w:shd w:val="clear" w:color="000000" w:fill="FFFF00"/>

							<w:noWrap/>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
							<w:pPr>
							  <w:jc w:val="center"/>
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>

								<w:bCs/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							</w:pPr>
							<w:r w:rsidRPr="00B546D6">
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>
								<w:bCs/>

								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							  <w:t>% de tiempo</w:t>
							</w:r>
						  </w:p>
						</w:tc>
						<!-- Tipo de actividad (estrategia) -->
						<w:tc>

						  <w:tcPr>
							<w:tcW w:w="2126" w:type="dxa"/>
							<w:tcBorders>
							  <w:top w:val="nil"/>
							  <w:left w:val="nil"/>
							  <w:bottom w:val="single" w:sz="12" w:space="0" w:color="4F81BD"/>
							  <w:right w:val="nil"/>
							</w:tcBorders>
							<w:shd w:val="clear" w:color="000000" w:fill="FFFF00"/>

							<w:noWrap/>
							<w:vAlign w:val="center"/>
							<w:hideMark/>
						  </w:tcPr>
						  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A721AB">
							<w:pPr>
							  <w:tabs>
								<w:tab w:val="left" w:pos="869"/>
							  </w:tabs>

							  <w:ind w:right="726"/>
							  <w:jc w:val="center"/>
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>
								<w:bCs/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							</w:pPr>

							<w:r w:rsidRPr="00B546D6">
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>
								<w:bCs/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							  <w:t> </w:t>

							</w:r>
							<w:r w:rsidR="00A721AB">
							  <w:rPr>
								<w:rFonts w:cs="Calibri"/>
								<w:bCs/>
								<w:sz w:val="20"/>
								<w:szCs w:val="20"/>
							  </w:rPr>
							  <w:t>Tipo de actividad (estrategia)</w:t>

							</w:r>
						  </w:p>
						</w:tc>
					  </w:tr>
						<xsl:for-each select="./inta_actividad/actividad_instancia/actividad">
						<actividad>
							<!-- fila de actividad que aporta al resultado esperado -->
							<w:tr w:rsidR="009A5A92" w:rsidRPr="009A5A92" w:rsidTr="00B6704D">
								<!-- definicion de la fila -->
								<w:trPr>
								  <w:trHeight w:val="885"/>
								</w:trPr>
								<!-- celda de nombre de "actividad" -->
								<w:tc>

								  <w:tcPr>
									<w:tcW w:w="2006" w:type="dxa"/>
									<w:tcBorders>
									  <w:top w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:left w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:bottom w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:right w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									</w:tcBorders>
									<w:shd w:val="clear" w:color="000000" w:fill="F2F2F2"/>

									<w:hideMark/>
								  </w:tcPr>
								  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
									<w:pPr>
									  <w:rPr>
										<w:rFonts w:cs="Calibri"/>
										<w:bCs/>
										<w:color w:val="3F3F3F"/>
										<w:sz w:val="20"/>

										<w:szCs w:val="20"/>
									  </w:rPr>
									</w:pPr>
									<w:r w:rsidRPr="00B546D6">
									  <w:rPr>
										<w:rFonts w:cs="Calibri"/>
										<w:bCs/>
										<w:color w:val="3F3F3F"/>
										<w:sz w:val="20"/>

										<w:szCs w:val="20"/>
									  </w:rPr>
									  <w:t><xsl:value-of select="./nombre" /></w:t>
									</w:r>
								  </w:p>
								</w:tc>
								<!-- celda de "cronograma" -->
								<w:tc>
								  <w:tcPr>

									<w:tcW w:w="3593" w:type="dxa"/>
									<w:tcBorders>
									  <w:top w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:left w:val="nil"/>
									  <w:bottom w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:right w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									</w:tcBorders>
									<w:shd w:val="clear" w:color="000000" w:fill="F2F2F2"/>
									<w:noWrap/>

									<w:vAlign w:val="bottom"/>
									<w:hideMark/>
								  </w:tcPr>
								  <w:p w:rsidR="009A5A92" w:rsidRPr="003E1ACB" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
									<w:pPr>
									  <w:rPr>
										<w:rFonts w:cs="Calibri"/>
										<w:b/>
										<w:bCs/>

										<w:color w:val="FF0000"/>
										<w:sz w:val="20"/>
										<w:szCs w:val="20"/>
									  </w:rPr>
									</w:pPr>
									<w:r w:rsidRPr="003E1ACB">
									  <w:rPr>
										<w:rFonts w:cs="Calibri"/>
										<w:b/>

										<w:bCs/>
										<w:color w:val="FF0000"/>
										<w:sz w:val="20"/>
										<w:szCs w:val="20"/>
									  </w:rPr>
									  <w:t>ACRO</w:t>
									</w:r>
								  </w:p>

								</w:tc>
								<!-- celda de "responsable" -->
								<w:tc>
								  <w:tcPr>
									<w:tcW w:w="1190" w:type="dxa"/>
									<w:tcBorders>
									  <w:top w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:left w:val="nil"/>
									  <w:bottom w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:right w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>

									</w:tcBorders>
									<w:shd w:val="clear" w:color="000000" w:fill="F2F2F2"/>
									<w:noWrap/>
									<w:vAlign w:val="bottom"/>
									<w:hideMark/>
								  </w:tcPr>
								  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
									<w:pPr>
									  <w:rPr>

										<w:rFonts w:cs="Calibri"/>
										<w:bCs/>
										<w:color w:val="333333"/>
										<w:sz w:val="20"/>
										<w:szCs w:val="20"/>
									  </w:rPr>
									</w:pPr>
									<w:r w:rsidRPr="00B546D6">
									  <w:rPr>

										<w:rFonts w:cs="Calibri"/>
										<w:bCs/>
										<w:color w:val="333333"/>
										<w:sz w:val="20"/>
										<w:szCs w:val="20"/>
									  </w:rPr>
									  <w:t><xsl:value-of select="./actividad_responsable/usuario/apellido" />, <xsl:value-of select="./actividad_responsable/usuario/nombre" /> (<xsl:value-of select="./actividad_responsable/usuario/email" />)</w:t>
									</w:r>

								  </w:p>
								</w:tc>
								<!-- celda de "presupuesto" -->
								<w:tc>
								  <w:tcPr>
									<w:tcW w:w="884" w:type="dxa"/>
									<w:tcBorders>
									  <w:top w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:left w:val="nil"/>
									  <w:bottom w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>

									  <w:right w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									</w:tcBorders>
									<w:shd w:val="clear" w:color="000000" w:fill="F2F2F2"/>
									<w:noWrap/>
									<w:vAlign w:val="bottom"/>
									<w:hideMark/>
								  </w:tcPr>
								  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
									<w:pPr>

									  <w:rPr>
										<w:rFonts w:cs="Calibri"/>
										<w:bCs/>
										<w:color w:val="3F3F3F"/>
										<w:sz w:val="20"/>
										<w:szCs w:val="20"/>
									  </w:rPr>
									</w:pPr>
									<w:r w:rsidRPr="00B546D6">

									  <w:rPr>
										<w:rFonts w:cs="Calibri"/>
										<w:bCs/>
										<w:color w:val="3F3F3F"/>
										<w:sz w:val="20"/>
										<w:szCs w:val="20"/>
									  </w:rPr>
									  <w:t><xsl:value-of select="sum(./actividad_proyectos/proyecto_actividad/monto)" /></w:t>
									</w:r>
								  </w:p>
								</w:tc>
								<!-- celda de "proyecto" -->
								<w:tc>
								  <w:tcPr>
									<w:tcW w:w="1701" w:type="dxa"/>
									<w:tcBorders>
									  <w:top w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:left w:val="nil"/>

									  <w:bottom w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:right w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									</w:tcBorders>
									<w:shd w:val="clear" w:color="000000" w:fill="F2F2F2"/>
									<w:hideMark/>
								  </w:tcPr>
								  <xsl:for-each select="./actividad_proyectos/proyecto_actividad/proyecto_instancia/proyecto">
									  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
										<w:pPr>
										  <w:rPr>
											<w:rFonts w:cs="Calibri"/>
											<w:bCs/>
											<w:color w:val="333333"/>
											<w:sz w:val="20"/>
											<w:szCs w:val="20"/>
										  </w:rPr>
										</w:pPr>
										<w:r w:rsidRPr="00B546D6">
										  <w:rPr>
											<w:rFonts w:cs="Calibri"/>
											<w:bCs/>
											<w:color w:val="333333"/>
											<w:sz w:val="20"/>
											<w:szCs w:val="20"/>
										  </w:rPr>
										  <w:t><xsl:value-of select="./nombre" /></w:t>
										</w:r>
									  </w:p>
								  </xsl:for-each>
								</w:tc>
								<!-- celda de porcentaje de tiempo -->
								<w:tc>
								  <w:tcPr>
									<w:tcW w:w="848" w:type="dxa"/>
									<w:tcBorders>
									  <w:top w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:left w:val="nil"/>
									  <w:bottom w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>

									  <w:right w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									</w:tcBorders>
									<w:shd w:val="clear" w:color="000000" w:fill="F2F2F2"/>
									<w:noWrap/>
									<w:vAlign w:val="bottom"/>
									<w:hideMark/>
								  </w:tcPr>
								  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">
									<w:pPr>
									  <w:rPr>
										<w:rFonts w:cs="Calibri"/>
										<w:bCs/>
										<w:color w:val="3F3F3F"/>
										<w:sz w:val="20"/>
										<w:szCs w:val="20"/>
									  </w:rPr>
									</w:pPr>
									<w:r w:rsidRPr="00B546D6">
									  <w:rPr>
										<w:rFonts w:cs="Calibri"/>
										<w:bCs/>
										<w:color w:val="3F3F3F"/>
										<w:sz w:val="20"/>
										<w:szCs w:val="20"/>
									  </w:rPr>
									  <w:t><xsl:value-of select="./porcentaje_tiempo" /></w:t>
									</w:r>
								  </w:p>
								</w:tc>
								<!-- celda de estrategia -->
								<w:tc>
								  <w:tcPr>
									<w:tcW w:w="2126" w:type="dxa"/>
									<w:tcBorders>
									  <w:top w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:left w:val="nil"/>

									  <w:bottom w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									  <w:right w:val="single" w:sz="4" w:space="0" w:color="3F3F3F"/>
									</w:tcBorders>
									<w:shd w:val="clear" w:color="000000" w:fill="F2F2F2"/>
									<w:noWrap/>
									<w:vAlign w:val="bottom"/>
									<w:hideMark/>
								  </w:tcPr>
								  <xsl:for-each select="./actividad_estrategias/estrategia_actividad/estrategia_instancia/estrategia">
									  <w:p w:rsidR="009A5A92" w:rsidRPr="00B546D6" w:rsidRDefault="009A5A92" w:rsidP="00A47FB3">

										<w:pPr>
										  <w:rPr>
											<w:rFonts w:cs="Calibri"/>
											<w:bCs/>
											<w:color w:val="3F3F3F"/>
											<w:sz w:val="20"/>
											<w:szCs w:val="20"/>
										  </w:rPr>
										</w:pPr>

										<w:r w:rsidRPr="00B546D6">
										  <w:rPr>
											<w:rFonts w:cs="Calibri"/>
											<w:bCs/>
											<w:color w:val="3F3F3F"/>
											<w:sz w:val="20"/>
											<w:szCs w:val="20"/>
										  </w:rPr>
										  <w:t> </w:t>

										</w:r>
										<w:r w:rsidRPr="009A5A92">
										  <w:rPr>
											<w:rFonts w:cs="Calibri"/>
											<w:bCs/>
											<w:color w:val="3F3F3F"/>
											<w:sz w:val="20"/>
											<w:szCs w:val="20"/>
										  </w:rPr>

										  <w:t><xsl:value-of select="./nombre" /></w:t>
										</w:r>
									  </w:p>
								  </xsl:for-each>
								</w:tc>
							</w:tr>
						</actividad>
						</xsl:for-each>
					</w:tbl>
				</resultado_esperado>
				</xsl:for-each>
			</objetivo>
			</xsl:for-each>
		</agencia>
		</xsl:for-each>
			<!-- espacio en blanco -->
		<w:p w:rsidR="005C523F" w:rsidRDefault="005C523F">
			<w:pPr>
				<w:rPr>
				  <w:sz w:val="20"/>
				  <w:szCs w:val="20"/>
				  <w:lang w:val="es-ES"/>

				</w:rPr>
			</w:pPr>
		</w:p>

		<!-- definicion del cuerpo del documento -->
		<w:sectPr w:rsidR="00CD25F9" w:rsidRPr="00FF5CA2" w:rsidSect="00B6704D">
		  <w:pgSz w:w="16838" w:h="11906" w:orient="landscape"/>
		  <w:pgMar w:top="851" w:right="2946" w:bottom="1701" w:left="1417" w:header="708" w:footer="708" w:gutter="0"/>
		  <w:cols w:space="708"/>
		  <w:docGrid w:linePitch="360"/>

		</w:sectPr>
	  </w:body>
	</w:document>

</xsl:template>
</xsl:stylesheet>