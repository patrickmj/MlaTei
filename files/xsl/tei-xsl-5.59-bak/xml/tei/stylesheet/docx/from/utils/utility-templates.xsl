<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet 
                xmlns:xs="http://www.w3.org/2001/XMLSchema"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:prop="http://schemas.openxmlformats.org/officeDocument/2006/custom-properties"
                xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"
                xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:dcterms="http://purl.org/dc/terms/"
                xmlns:dcmitype="http://purl.org/dc/dcmitype/"
                xmlns:iso="http://www.iso.org/ns/1.0"
                xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                xmlns:mml="http://www.w3.org/1998/Math/MathML"
                xmlns:mo="http://schemas.microsoft.com/office/mac/office/2008/main"
                xmlns:mv="urn:schemas-microsoft-com:mac:vml"
                xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture"
                xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
                xmlns:rel="http://schemas.openxmlformats.org/package/2006/relationships"
                xmlns:tbx="http://www.lisa.org/TBX-Specification.33.0.html"
                xmlns:tei="http://www.tei-c.org/ns/1.0"
                xmlns:teidocx="http://www.tei-c.org/ns/teidocx/1.0"
                xmlns:v="urn:schemas-microsoft-com:vml"
                xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006"
                xmlns:w10="urn:schemas-microsoft-com:office:word"
                xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                
                xmlns="http://www.tei-c.org/ns/1.0"
                version="2.0"
                exclude-result-prefixes="a cp dc dcterms dcmitype prop     iso m mml mo mv o pic r rel     tbx tei teidocx v xs ve w10 w wne wp ">


    <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet" type="stylesheet">
      <desc>
         <p> TEI stylesheet for converting Word docx files to TEI </p>
         <p> This library is free software; you can redistribute it and/or modify it under
            the terms of the GNU Lesser General Public License as published by the Free Software
            Foundation; either version 2.1 of the License, or (at your option) any later version.
            This library is distributed in the hope that it will be useful, but WITHOUT ANY
            WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
            PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details. You
            should have received a copy of the GNU Lesser General Public License along with this
            library; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite
            330, Boston, MA 02111-1307 USA </p>
         <p>Author: See AUTHORS</p>
         <p>Id: $Id: utility-templates.xsl 9043 2011-07-03 22:14:48Z rahtz $</p>
         <p>Copyright: 2008, TEI Consortium</p>
      </desc>
   </doc>

    <xsl:template name="generateAppInfo">
      <appInfo>
	        <application ident="TEI_fromDOCX" version="2.15.0">
	           <label>DOCX to TEI</label>
	        </application>
	        <xsl:if test="doc-available(concat($wordDirectory,'/docProps/custom.xml'))">
	           <xsl:for-each select="document(concat($wordDirectory,'/docProps/custom.xml'))/prop:Properties">
	              <xsl:for-each select="prop:property">
	                 <xsl:choose>
		                   <xsl:when test="@name='TEI_fromDOCX'"/>
		                   <xsl:when test="contains(@name,'TEI')">
		                      <application ident="{@name}" version="{.}">
		                         <label>
		                            <xsl:value-of select="@name"/>
		                         </label>
		                      </application>
		                   </xsl:when>
	                 </xsl:choose>
	              </xsl:for-each>
		      <xsl:if test="prop:property[@name='WordTemplateURI']">
			<application ident="WordTemplate" version="{prop:property[@name='WordTemplate']}">
			  <label>Word template file</label>
			  <ptr target="{prop:property[@name='WordTemplateURI']}"/>
			</application>
		      </xsl:if>
	           </xsl:for-each>
	        </xsl:if>
      </appInfo>
    </xsl:template>

    <xsl:template name="getDocTitle">
      <xsl:value-of select="$docProps/cp:coreProperties/dc:title"/>
    </xsl:template>

    <xsl:template name="getDocAuthor">
      <xsl:value-of select="$docProps/cp:coreProperties/dc:creator"/>
    </xsl:template>

    <xsl:template name="getDocDate">
      <xsl:value-of select="substring-before($docProps/cp:coreProperties/dcterms:created,'T')"/>
    </xsl:template>

    <xsl:template name="identifyChange">
      <xsl:param name="who"/>
      <xsl:attribute name="resp">
	<xsl:text>#</xsl:text>
	<xsl:value-of select="translate($who,' ','_')"/>
      </xsl:attribute>
    </xsl:template>


</xsl:stylesheet>
