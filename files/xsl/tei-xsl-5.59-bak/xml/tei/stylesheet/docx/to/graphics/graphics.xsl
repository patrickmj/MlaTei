<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"
                xmlns:cals="http://www.oasis-open.org/specs/tm9901"
                xmlns:contypes="http://schemas.openxmlformats.org/package/2006/content-types"
                xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:dcmitype="http://purl.org/dc/dcmitype/"
                xmlns:dcterms="http://purl.org/dc/terms/"
                xmlns:html="http://www.w3.org/1999/xhtml"
                xmlns:iso="http://www.iso.org/ns/1.0"
                xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
                xmlns:mml="http://www.w3.org/1998/Math/MathML"
                xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture"
                xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
		xmlns:rel="http://schemas.openxmlformats.org/package/2006/relationships"
                xmlns:tbx="http://www.lisa.org/TBX-Specification.33.0.html"
                xmlns:tei="http://www.tei-c.org/ns/1.0"
                xmlns:teidocx="http://www.tei-c.org/ns/teidocx/1.0"
                xmlns:teix="http://www.tei-c.org/ns/Examples"
                xmlns:v="urn:schemas-microsoft-com:vml"
                xmlns:fn="http://www.w3.org/2005/02/xpath-functions"
                xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006"
                xmlns:w10="urn:schemas-microsoft-com:office:word"
                xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
                xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
                xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
                
                xmlns:xs="http://www.w3.org/2001/XMLSchema"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                version="2.0"
                exclude-result-prefixes="cp ve o r m v wp w10 w wne
					 mml tbx iso  tei a xs
					 pic fn xsi dc dcterms
					 dcmitype rel contypes teidocx teix html cals">
    

    <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet" type="stylesheet">
      <desc>
         <p> TEI stylesheet for making Word docx files from TEI XML
         </p>
         <p> This library is free software; you can redistribute it and/or
            modify it under the terms of the GNU Lesser General Public License as
            published by the Free Software Foundation; either version 2.1 of the
            License, or (at your option) any later version. This library is
            distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
            without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
            PARTICULAR PURPOSE. See the GNU Lesser General Public License for more
            details. You should have received a copy of the GNU Lesser General Public
            License along with this library; if not, write to the Free Software
            Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA </p>
         <p>Author: See AUTHORS</p>
         <p>Id: $Id: graphics.xsl 9379 2011-09-24 14:30:52Z rahtz $</p>
         <p>Copyright: 2008, TEI Consortium</p>
      </desc>
   </doc>


    <xsl:key name="W" match="image" use="@url"/>
    <xsl:key name="H" match="image" use="@url"/>

    <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>
        Guides the identity transformation for graphics
    </desc>
   </doc>
    <xsl:template match="a:blip" mode="iden">
        <xsl:variable name="me" select="generate-id()"/>
        <a:blip>
            <xsl:variable name="rId">
                <xsl:for-each select="key('BLIP',1)">
                    <xsl:if test="generate-id()=$me">
                        <xsl:value-of select="concat('rId', string(200 + position()))"/>
                    </xsl:if>
                </xsl:for-each>
            </xsl:variable>
            <xsl:choose>
                <xsl:when test="@r:embed">
                    <xsl:attribute name="r:embed" select="$rId"/>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:attribute name="r:link" select="$rId"/>
                </xsl:otherwise>
            </xsl:choose>
        </a:blip>
    </xsl:template>
    
    <!-- 
        Handle figures 
    -->
    
    <xsl:template match="tei:figure">
      <xsl:choose>
	<xsl:when test="@rend='inline' or @place='inline'">
	  <xsl:apply-templates/>
	</xsl:when>
	<xsl:otherwise>
	  <xsl:call-template name="block-element">
            <xsl:with-param name="pPr">
	      <w:pPr>
		<w:spacing w:before="240"/>
		<w:jc w:val="{$alignFigures}"/>
	      </w:pPr>
            </xsl:with-param>
	  </xsl:call-template>
	</xsl:otherwise>
      </xsl:choose>
    </xsl:template>

    <xsl:template match="tei:figure/tei:figDesc"/>
    
    <xsl:template match="tei:figure/tei:head">
      <xsl:variable name="number">
	<xsl:number level="any"/>
      </xsl:variable>
      <xsl:choose>
	<xsl:when test="../@xml:id">
	    <!-- we want a bookmark for referencing this figure -->
	  <xsl:call-template name="block-element">
            <xsl:with-param name="style">
	      <xsl:choose>
		<xsl:when test="ancestor::tei:back">Figuretitleannex</xsl:when>
		<xsl:otherwise>Figuretitle</xsl:otherwise>
	      </xsl:choose>
	    </xsl:with-param>
	    <xsl:with-param name="bookmark-id">
	      <xsl:value-of select="1000+$number"/>
	    </xsl:with-param>
	    <xsl:with-param name="bookmark-name">
	      <xsl:text>_</xsl:text>
	      <xsl:value-of select="../@xml:id"/>
	    </xsl:with-param>
	  </xsl:call-template>
	</xsl:when>
	<xsl:otherwise>  
	  <xsl:call-template name="block-element">
            <xsl:with-param name="style">
	      <xsl:choose>
		<xsl:when test="ancestor::tei:back">Figuretitleannex</xsl:when>
		<xsl:otherwise>Figuretitle</xsl:otherwise>
	      </xsl:choose>
	    </xsl:with-param>
	  </xsl:call-template>
	</xsl:otherwise>
      </xsl:choose>
    </xsl:template>
    
    <xsl:template match="tei:graphic">
        <!-- perform some tests on the graphic -->
	<xsl:variable name="maxWidth" select="number(number($pageWidth)*100) cast as xs:integer"/>
	<xsl:variable name="maxHeight"
		      select="number(number($pageHeight)*100) cast as
			      xs:integer"/>
	<xsl:variable name="filename">
	  <xsl:text>media/resource</xsl:text>
	  <xsl:number level="any"/>
	  <xsl:text>.</xsl:text>
	  <xsl:value-of select="tokenize(@url,'\.')[last()]"/>
	</xsl:variable>
	<xsl:variable name="origheight">
	  <xsl:choose>
	    <xsl:when test="@teidocx:height">
	      <xsl:value-of select="@teidocx:height"/>
	    </xsl:when>
	    <xsl:when test="doc-available(concat($wordDirectory,'/image-size-info.xml'))">
		<xsl:for-each select="document(concat($wordDirectory,'/image-size-info.xml'))">
		  <xsl:value-of select="(number(key('H',$filename)/height) div 72) * 9144"/>
		</xsl:for-each>
	    </xsl:when>
	    <xsl:otherwise>0</xsl:otherwise>
	  </xsl:choose>
	</xsl:variable>

	<xsl:variable name="origwidth">
	  <xsl:choose>
	    <xsl:when test="@teidocx:width">
	      <xsl:value-of select="@teidocx:width"/>
	    </xsl:when>
	    <xsl:when test="doc-available(concat($wordDirectory,'/image-size-info.xml'))">
		<xsl:for-each select="document(concat($wordDirectory,'/image-size-info.xml'))">
		  <xsl:value-of select="(number(key('W',$filename)/width) div 72) * 9144"/>
		</xsl:for-each>
	    </xsl:when>
	    <xsl:otherwise>0</xsl:otherwise>
	  </xsl:choose>
	</xsl:variable>
	<xsl:choose>
	  <xsl:when test="$filename and  ( ($origwidth &gt; 0 and $origheight &gt; 0) or (@width and @height))">
            
            <!--
                
                is there a number present?
                
                not(number(substring(@width,0,string-length(@width)-1))=NAN) and 
                not(number(substring(@height,0,string-length(@height)-1))=NAN)">
                
            -->
            
            <xsl:variable name="Width">
                <xsl:choose>
                    <xsl:when test="contains(@width,'%')">
                        <xsl:value-of select="number($pageWidth * number(substring-before(@width,'%'))) cast as xs:integer"/>
                    </xsl:when>
                    <xsl:when test="@width">
                        <xsl:value-of select="tei:convert-dim-emu(@width)"/>
                    </xsl:when>
                    <xsl:when test="@scale and $origwidth">
                        <xsl:value-of select="($origwidth *  number(@scale)) cast as xs:integer"/>
                    </xsl:when>
                    <xsl:when test="@height and $origheight and $origwidth">
		      <xsl:variable name="h">
			<xsl:choose>
			  <xsl:when test="contains(@height,'%')">
			    <xsl:value-of select="number($pageHeight * (number(substring-before(@height,'%')))) cast as xs:integer"/>
			  </xsl:when>
			  <xsl:otherwise>
			    <xsl:value-of
				select="tei:convert-dim-emu(@height)"/>
			  </xsl:otherwise>
			</xsl:choose>
		      </xsl:variable>
		      <xsl:value-of select="number(($h * $origwidth) div $origheight)    cast as xs:integer"/>
                    </xsl:when>
                    <xsl:when test="$origwidth">
                        <xsl:value-of select="$origwidth"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:message terminate="yes">no way to work out image width for
                            <xsl:value-of select="$filename"/>
                        </xsl:message>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:variable>
            
            <xsl:variable name="Height">
                <xsl:choose>
                    <xsl:when test="contains(@height,'%')">
                        <xsl:value-of select="number($pageHeight * (number(substring-before(@height,'%')))) cast as xs:integer"/>
                    </xsl:when>
                    <xsl:when test="@height">
                        <xsl:value-of select="tei:convert-dim-emu(@height)"/>
                    </xsl:when>
                    <xsl:when test="@scale and $origheight">
                        <xsl:value-of select="($origheight * number(@scale)) cast as xs:integer"/>
                    </xsl:when>
                    <xsl:when test="@width and $origheight and $origwidth">
                        <xsl:value-of select="number(  ($Width *  $origheight) div $origwidth) cast as xs:integer"/>
                    </xsl:when>
                    <xsl:when test="$origheight">
                        <xsl:value-of select="$origheight"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:message terminate="yes">no way to work out image height for
                            <xsl:value-of select="$filename"/>
                        </xsl:message>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:variable>
            
	    <!-- check for sense -->
            <xsl:variable name="imageWidth">
	      <xsl:choose>
		<xsl:when test="$Width = -1">
		  <xsl:value-of select="$maxWidth"/>
		</xsl:when>
		<xsl:when test="$Width &gt; $maxWidth">
		  <xsl:value-of select="$maxWidth"/>
		</xsl:when>
		<xsl:otherwise>
		  <xsl:value-of select="$Width"/>
		</xsl:otherwise>
	      </xsl:choose>
	    </xsl:variable>
            <xsl:variable name="imageHeight">
	      <xsl:choose>
		<xsl:when test="$Height = -1">
		  <xsl:value-of select="$maxHeight"/>
		</xsl:when>
		<xsl:when test="$Height &gt; $maxHeight">
		  <xsl:value-of select="$maxHeight"/>
		</xsl:when>
		<xsl:when test="$Width &gt; $maxWidth">
		  <xsl:value-of select="($Height * ($maxWidth div
					$Width) ) cast as xs:integer"/>
		</xsl:when>
		<xsl:otherwise>
		  <xsl:value-of select="$Height"/>
		</xsl:otherwise>
	      </xsl:choose>
	    </xsl:variable>
	    <!--
		
		<xsl:message>
		========================
		<xsl:for-each select="@*">
		- @<xsl:value-of select="name(.)"/>: <xsl:value-of select="."/>
		</xsl:for-each>
		
		- maxWidth: <xsl:value-of select="$maxWidth"/>
		- maxHeight: <xsl:value-of select="$maxHeight"/>
		- Width: <xsl:value-of select="$Width"/>
		- Height: <xsl:value-of select="$Height"/>
		* imageWidth: <xsl:value-of select="$imageWidth"/>
		* imageHeight: <xsl:value-of select="$imageHeight"/>
		</xsl:message>
	    -->
	    <!-- prepare actual graphic -->
	    <xsl:variable name="generatedID">
	      <xsl:number level="any"/>
	    <!--
		<xsl:choose>
		<xsl:when test="@n">
		  <xsl:value-of select="@n"/>
		</xsl:when>
		<xsl:otherwise>
		  <xsl:number level="any"/>
		</xsl:otherwise>
	      </xsl:choose>
	      -->
	    </xsl:variable>

            <xsl:variable name="graphic-element">
                <a:graphic>
                    <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
                        <pic:pic>
                            <pic:nvPicPr>
                                <pic:cNvPr name="{tokenize($filename, '/')[last()]}">
                                    <xsl:attribute name="id">
                                        <xsl:number level="any"/>
                                    </xsl:attribute>
                                </pic:cNvPr>
                                <pic:cNvPicPr/>
                            </pic:nvPicPr>
                            <pic:blipFill>
                                <a:blip>
				  <xsl:attribute name="r:embed">
				    <xsl:choose>
				      <xsl:when test="$isofreestanding='true'">
					<xsl:text>rId</xsl:text>
					<xsl:value-of
					    select="number($generatedID)
						    + 300"/>
				      </xsl:when>
				      <xsl:otherwise>
					<xsl:value-of select="document(concat($wordDirectory,'/word/_rels/document.xml.rels'))//rel:Relationship[@Target=$filename]/@Id"/>
				      </xsl:otherwise>
				    </xsl:choose>
				  </xsl:attribute>
				</a:blip>
				<a:stretch>
				  <a:fillRect/>
                                </a:stretch>
                            </pic:blipFill>
                            <pic:spPr>
                                <a:xfrm>
                                    <a:off x="0" y="0"/>
                                    <a:ext cx="{$imageWidth}00" cy="{$imageHeight}00"/>
                                </a:xfrm>
                                <a:prstGeom prst="rect">
                                    <a:avLst/>
                                </a:prstGeom>
				<xsl:if test="$shadowGraphics='true'
					      and parent::tei:figure">
				  <a:effectLst>
				    <a:outerShdw blurRad="50800" dist="88900" dir="2700000" algn="tl" rotWithShape="0">
				      <a:schemeClr val="tx1">
					<a:alpha val="49000"/>
				      </a:schemeClr>
				    </a:outerShdw>
				  </a:effectLst>
				</xsl:if>
                            </pic:spPr>
                        </pic:pic>
                    </a:graphicData>
                </a:graphic>
            </xsl:variable>
            <!-- end graphic element -->
            
            <w:r>
                <w:drawing>
                    <!-- choose between inline and block -->
                    <xsl:choose>
		      <xsl:when test="parent::tei:figure[@place='left'
				      or @place='centre' or @place='right' or @place='center']">
			<wp:anchor simplePos="0" relativeHeight="10" behindDoc="0" locked="0" layoutInCell="1"
				   allowOverlap="1">
			  <wp:simplePos x="0" y="0"/>
			  <wp:positionH relativeFrom="margin">
			    <wp:align>
			      <xsl:value-of select="parent::tei:figure/@place"/>
			    </wp:align>
			  </wp:positionH>
			  <wp:positionV relativeFrom="paragraph">
			    <wp:align>center</wp:align>
			  </wp:positionV>
			  <wp:extent cx="{$imageWidth}00" cy="{$imageHeight}00"/>
			  <xsl:if test="$shadowGraphics='true'">
			    <wp:effectExtent l="50800" t="25400" r="101600" b="63500"/>
			  </xsl:if>
			  <wp:wrapSquare wrapText="bothSides"/>
			  <wp:docPr  name="{tokenize($filename, '/')[last()]}">
			    <xsl:attribute name="id">
			      <xsl:value-of select="$generatedID"/>
			    </xsl:attribute>
			  </wp:docPr>                                
			  <xsl:if test="$shadowGraphics='true'">
			    <wp:cNvGraphicFramePr/>
			  </xsl:if>
			  <xsl:copy-of select="$graphic-element"/>
			</wp:anchor>
		      </xsl:when>
		      <xsl:otherwise>
			<wp:inline distT="0" distB="0" distL="0" distR="0">
			  <wp:extent cx="{$imageWidth}00" cy="{$imageHeight}00"/>
			  <xsl:if test="$shadowGraphics='true'">
			    <wp:effectExtent l="50800" t="25400" r="101600" b="63500"/>
			  </xsl:if>
			  <wp:docPr  name="{tokenize($filename, '/')[last()]}">
			    <xsl:attribute name="id">
			      <xsl:value-of select="$generatedID"/>
			    </xsl:attribute>
			  </wp:docPr>                                
			  <xsl:if test="$shadowGraphics='true'">
			    <wp:cNvGraphicFramePr/>
			  </xsl:if>
			  <xsl:copy-of select="$graphic-element"/>
			</wp:inline>
		      </xsl:otherwise>
                    </xsl:choose>
                </w:drawing>
            </w:r>
	  </xsl:when>
	  <xsl:otherwise>
	    <xsl:message terminate="yes">ERROR. no image size info for  <xsl:value-of select="$filename"/>, cannot proceed</xsl:message>

	  </xsl:otherwise>
	</xsl:choose>
    </xsl:template>
    
</xsl:stylesheet>
