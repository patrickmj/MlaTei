<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:="http://www.-c.org/ns/1.0"
                
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                exclude-result-prefixes=""
                version="1.0">
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet" type="stylesheet">
      <desc>
         <p>  stylesheet dealing with elements from the textstructure
      module. </p>
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
         <p>Id: $Id: textstructure.xsl 8703 2011-02-26 20:08:20Z rahtz $</p>
         <p>Copyright: 2011,  Consortium</p>
      </desc>
   </doc>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Establish nesting depth of sections</desc>
   </doc>
  <xsl:template match="text" mode="depth">
    <xsl:value-of select="count(ancestor::text)-1"/>
  </xsl:template>
  <xsl:template match="div|div1|div2|div3|div4|div5|div6"
                 mode="depth">
      <xsl:choose>
	<xsl:when test="ancestor::text/parent::group and
			self::div">
	   <xsl:value-of select="count(ancestor::div) + 1"/>
	</xsl:when>
         <xsl:when test="local-name(.) = 'div'">
            <xsl:value-of select="count(ancestor::div)"/>
         </xsl:when>
	<xsl:when test="ancestor::text/parent::group">
	   <xsl:value-of select="number(substring-after(local-name(.),'div')) "/>
	</xsl:when>
	<xsl:when test="ancestor::text/parent::group">
	  <xsl:value-of select="number(substring-after(local-name(.),'div'))"/>
	</xsl:when>
	<xsl:otherwise>
	  <xsl:value-of select="number(substring-after(local-name(.),'div')) - 1"/>
         </xsl:otherwise>
      </xsl:choose>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Generate revision description</desc>
  </doc>
  <xsl:template match="divGen[@type='revHist']">
    <xsl:variable name="r">
      <div xmlns="http://www.-c.org/ns/1.0" rend='nonumber'>
	<head>Revision history</head>
	<table rend="rules" >
	  <xsl:for-each
	      select="ancestor-or-self::/Header/revisionDesc/change">
	    <row>
	      <cell><xsl:value-of select="@when"/></cell>
	      <cell><xsl:value-of select="@who"/></cell>
	      <cell><xsl:value-of select="."/></cell>
	    </row>
	  </xsl:for-each>
	</table>
      </div>
    </xsl:variable>
    <xsl:for-each select="$r">
      <xsl:apply-templates/>
    </xsl:for-each>
  </xsl:template>



</xsl:stylesheet>