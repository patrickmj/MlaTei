<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns="http://www.w3.org/1999/xhtml"
                xmlns:tbx="http://www.lisa.org/TBX-Specification.33.0.html"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:iso="http://www.iso.org/ns/1.0"
		xmlns:cals="http://www.oasis-open.org/specs/tm9901"
                xmlns:html="http://www.w3.org/1999/xhtml"
                xmlns:teix="http://www.tei-c.org/ns/Examples"
                xmlns:s="http://www.ascc.net/xml/schematron"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:tei="http://www.tei-c.org/ns/1.0"
                xmlns:t="http://www.thaiopensource.com/ns/annotations"
                xmlns:a="http://relaxng.org/ns/compatibility/annotations/1.0"
                xmlns:rng="http://relaxng.org/ns/structure/1.0"
                exclude-result-prefixes="tei html t a rng s iso tbx
					 cals teix dc"
                version="2.0">
    <xsl:import href="../../../epub/tei-to-epub.xsl"/>

    <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet" type="stylesheet">
      <desc>
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
         <p>Id: $Id: to.xsl 9410 2011-09-27 22:01:12Z rahtz $</p>
         <p>Copyright: 2008, TEI Consortium</p>
      </desc>
   </doc>

    <xsl:param name="publisher">Oxford Text Archive, Oxford University</xsl:param>
    <xsl:param name="numberHeadings">false</xsl:param>
    <xsl:param name="numberHeadingsDepth">-1</xsl:param>
    <xsl:param name="numberBackHeadings"></xsl:param>
    <xsl:param name="numberFrontHeadings"></xsl:param>
    <xsl:param name="numberFigures">false</xsl:param>
    <xsl:param name="numberTables">false</xsl:param>
    <xsl:param name="autoToc">true</xsl:param>
    <xsl:param name="footnoteBackLink">true</xsl:param>
    <xsl:param name="cssFile">../profiles/ecco/epub/ecco.css</xsl:param>
    <xsl:param name="subject">Oxford Text Archive</xsl:param>
    <xsl:param name="pagebreakStyle">none</xsl:param>

    <xsl:template match="tei:title[@type='main']/text()">
      <xsl:value-of select="replace(.,' \[Electronic resource\]','')"/>
    </xsl:template>

    <!--
      <div class="pagebreak">
	<xsl:text>✁[</xsl:text>
	<xsl:text> Page </xsl:text>
	<xsl:value-of select="@n"/>
	<xsl:text>]✁</xsl:text>
      </div>
      -->
	
    <xsl:template match="tei:w[@type and @lemma]">
      <span class="wordtype{@type}">
	<xsl:apply-templates/>
      </span>
    </xsl:template>

  <xsl:template match="tei:sp">
      <xsl:choose>
	<xsl:when test="tei:ab and tei:speaker">
	  <div class="spProse">
	    <xsl:for-each select="tei:speaker">
	      <span class="speaker">
		<xsl:apply-templates/>
	      </span>
	    </xsl:for-each>
	    <xsl:text> </xsl:text>
	    <xsl:for-each select="tei:ab">
	      <xsl:apply-templates/>
	    </xsl:for-each>
	  </div>
	</xsl:when>
	<xsl:otherwise>
	  <div class="sp">
	    <xsl:apply-templates/>
	  </div>
	</xsl:otherwise>
      </xsl:choose>
  </xsl:template>

  <xsl:template match="tei:cell/tei:lb"/>

  <xsl:template match="tei:body/tei:lb"/>

  <xsl:template match="tei:div/tei:lb"/>

   <xsl:template match="tei:titlePart" mode="simple">
      <xsl:if test="preceding-sibling::tei:titlePart">
         <br/>
      </xsl:if>
      <xsl:value-of select="."/>
   </xsl:template>

  <xsl:template name="generateSubjectHook">
    <dc:subject>ECCO</dc:subject>
    <dc:subject>Oxford Text Archive</dc:subject>
  </xsl:template>

</xsl:stylesheet>
