<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:="http://www.-c.org/ns/1.0"
		xmlns:xs="http://www.w3.org/2001/XMLSchema"                
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                exclude-result-prefixes=" xs"
                version="1.0">
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet" type="stylesheet">
      <desc>
         <p>  stylesheet dealing with elements from the linking module. </p>
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
         <p>Id: $Id: linking.xsl 9490 2011-10-10 19:43:23Z sbauman $</p>
         <p>Copyright: 2011,  Consortium</p>
      </desc>
   </doc>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element  in xref mode</desc>
   </doc>
  <xsl:template match="" mode="xref">
      <xsl:apply-templates select="Header/fileDesc/titleStmt/title[1]"/>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process elements text in xref mode</desc>
   </doc>
  <xsl:template match="text" mode="xref">
   <xsl:choose>
     <xsl:when test="head">
       <xsl:apply-templates select="head" mode="plain"/>
     </xsl:when>
     <xsl:when test="body/head">
       <xsl:apply-templates select="body/head" mode="plain"/>
     </xsl:when>
     <xsl:when test="front//titlePart/title">
       <xsl:apply-templates select="front//titlePart/titl" mode="plain"/>
     </xsl:when>
     <xsl:otherwise>
       <xsl:text>Text </xsl:text>
       <xsl:number/>
     </xsl:otherwise>
   </xsl:choose>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element anchor|p in xref mode</desc>
   </doc>
  <xsl:template match="anchor|p" mode="xref">
      <xsl:text>here</xsl:text>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element bibl</desc>
   </doc>
  <xsl:template match="bibl" mode="xref">
      <xsl:text>[</xsl:text>
      <xsl:number/>
      <xsl:text>]</xsl:text>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element s
      div|div1|div2|div3|div4|div5|div6 in
      xref mode<param name="minimal">whether to make a link with just numbers or with
      text too</param>
      </desc>
   </doc>
  <xsl:template match="div|div1|div2|div3|div4|div5|div6"
                 mode="xref">
      <xsl:param name="minimal">false</xsl:param>
      <xsl:call-template name="header">
         <xsl:with-param name="minimal" select="$minimal"/>
         <xsl:with-param name="display">plain</xsl:with-param>
      </xsl:call-template>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element ptr in xref mode</desc>
   </doc>
  <xsl:template match="ptr">
      <xsl:call-template name="makeLink">
         <xsl:with-param name="ptr" select="true()"/>
      </xsl:call-template>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element ref</desc>
   </doc>
  <xsl:template match="ref">
      <xsl:call-template name="makeLink">
         <xsl:with-param name="ptr" select="false()"/>
      </xsl:call-template>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>[common] Making a heading for something
         <param name="minimal">whether to display headings</param>
         <param name="toc">whether this is making a TOC entry</param>
         <param name="display">detail of display (full, simple, plain), ie
    whether markup is followed</param>
      </desc>
   </doc>
  <xsl:template name="header">
      <xsl:param name="minimal">false</xsl:param>
      <xsl:param name="toc"/>
      <xsl:param name="display">full</xsl:param>
      <xsl:variable name="depth">
         <xsl:apply-templates mode="depth" select="."/>
      </xsl:variable>
      <xsl:call-template name="formatHeadingNumber">
         <xsl:with-param name="toc">
	   <xsl:value-of select="$toc"/>
         </xsl:with-param>
         <xsl:with-param name="text">
            <xsl:choose>
               <xsl:when test="local-name(.) = ''"/>
               <xsl:when test="$depth &gt; $numberHeadingsDepth"> </xsl:when>
               <xsl:when test="self::text">
		 <xsl:number/>
		 <xsl:call-template name="headingNumberSuffix"/>
	       </xsl:when>
               <xsl:when test="ancestor::back">
                  <xsl:if test="$numberBackHeadings='true'">
                     <xsl:call-template name="i18n">
                        <xsl:with-param name="word">appendixWords</xsl:with-param>
                     </xsl:call-template>
                     <xsl:text> </xsl:text>
                     <xsl:call-template name="numberBackDiv"/>
                     <xsl:if test="$minimal='false'">
                        <xsl:value-of select="$numberSpacer"/>
                     </xsl:if>
                  </xsl:if>
               </xsl:when>
               <xsl:when test="ancestor::front">
                  <xsl:if test="$numberFrontHeadings='true'">
                     <xsl:call-template name="numberFrontDiv">
		       <xsl:with-param name="minimal">
			 <xsl:value-of select="$minimal"/>
		       </xsl:with-param>
		     </xsl:call-template>
                  </xsl:if>
               </xsl:when>
               <xsl:when test="$numberHeadings ='true'">
                  <xsl:choose>
                     <xsl:when test="$prenumberedHeadings='true'">
                        <xsl:value-of select="@n"/>
                     </xsl:when>
                     <xsl:otherwise>
                        <xsl:call-template name="numberBodyDiv"/>
                     </xsl:otherwise>
                  </xsl:choose>
                  <xsl:if test="$minimal='false'">
                     <xsl:call-template name="headingNumberSuffix"/>
                  </xsl:if>
               </xsl:when>
            </xsl:choose>
         </xsl:with-param>
      </xsl:call-template>
      <xsl:if test="$minimal='false'">
         <xsl:choose>
            <xsl:when test="local-name(.) = ''">
               <xsl:apply-templates select="Header/fileDesc/titleStmt/title[1]"/>
            </xsl:when>
            <xsl:when test="not($toc='')">
	      <xsl:call-template name="makeInternalLink">
		<xsl:with-param name="dest">
		  <xsl:value-of select="$toc"/>
		</xsl:with-param>
		<xsl:with-param name="class">
		  <xsl:value-of select="$class_toc"/>
		  <xsl:text> </xsl:text>
		  <xsl:value-of select="concat($class_toc,'_',$depth)"/>
		</xsl:with-param>
		<xsl:with-param name="body">
		  <xsl:choose>
		    <xsl:when test="not(head) and body/head">
			<xsl:apply-templates mode="plain" select="body/head"/>
		    </xsl:when>	
		    <xsl:when test="not(head) and front//titlePart/title">
			<xsl:apply-templates mode="plain" select="front//titlePart/title"/>
		    </xsl:when>	
		    <xsl:when test="head">
			<xsl:apply-templates mode="plain" select="head"/>
		    </xsl:when>
		    <xsl:when test="$autoHead='true'">
		      <xsl:call-template name="autoMakeHead">
			<xsl:with-param name="display" select="$display"/>
		      </xsl:call-template>
		    </xsl:when>
		    <xsl:otherwise>
		      <xsl:number/>
		    </xsl:otherwise>
		  </xsl:choose>
		</xsl:with-param>
	      </xsl:call-template>
            </xsl:when>
            <xsl:when test="$autoHead='true'">
	      <xsl:choose>
		<xsl:when test="$outputTarget='epub' and
				not(head)"/>
		<xsl:otherwise>
		  <xsl:call-template name="autoMakeHead">
		    <xsl:with-param name="display" select="$display"/>
		  </xsl:call-template>
		</xsl:otherwise>
	      </xsl:choose>
            </xsl:when>
            <xsl:when test="$display='plain'">
               <xsl:for-each select="head">
		 <xsl:apply-templates mode="plain"/>
	       </xsl:for-each>
            </xsl:when>
            <xsl:when test="$display='simple'">
               <xsl:for-each select="head">
		 <xsl:apply-templates mode="plain"/>
	       </xsl:for-each>
            </xsl:when>
	    <xsl:when test="self::index">
	      <xsl:value-of select="substring(term,1,10)"/>
	      <xsl:text>…</xsl:text>
	    </xsl:when>
	    <xsl:when test="not(head)">
	      <xsl:value-of select="substring(normalize-space(.),1,10)"/>
	      <xsl:text>…</xsl:text>
	    </xsl:when>
            <xsl:otherwise>
               <xsl:apply-templates select="head" mode="makeheading"/>
            </xsl:otherwise>
         </xsl:choose>
      </xsl:if>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>
         <p>[common] processing the number portion of a heading</p>
         <p>By default, the text is printed as is. You may
    wish to colour it, align it, etc</p>
         <param name="text"/>
      </desc>
   </doc>
  <xsl:template name="formatHeadingNumber">
      <xsl:param name="text"/>
      <xsl:param name="toc"/>
      <xsl:copy-of select="$text"/>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>
         <p>[common] Making a heading for something, and making sure it has
      contents</p>
         <p>This is a wrapper around the "header" template which ensures that
      some text is returned; if all else fails, the element name is
    used.</p>
         <param name="minimal">false</param>
      </desc>
   </doc>
  <xsl:template name="headerLink">
      <xsl:param name="minimal">false</xsl:param>
      <xsl:variable name="Text">
         <xsl:call-template name="header">
            <xsl:with-param name="minimal" select="$minimalCrossRef"/>
	           <xsl:with-param name="display">plain</xsl:with-param>
         </xsl:call-template>
      </xsl:variable>
      <xsl:choose>
         <xsl:when test="$Text=''">
            <xsl:text>&lt;</xsl:text>
            <xsl:value-of select="local-name(.)"/>
            <xsl:text>&gt;</xsl:text>
         </xsl:when>
         <xsl:otherwise>
            <xsl:copy-of select="$Text"/>
         </xsl:otherwise>
      </xsl:choose>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>
         <p>[common] Make a hypertext link</p>
         <p>cross-referencing </p>
         <param name="ptr">ptr</param>
      </desc>
   </doc>
  <xsl:template name="makeLink">
      <xsl:param name="ptr" as="xs:boolean" select="false()"/>
      <!-- is this a ptr or a ref? -->
    <xsl:choose>
      <!-- If there is a target attribute starting with #, it is always a local reference -->
      <xsl:when test="@target and starts-with(@target,'#')">
            <xsl:call-template name="makeInternalLink">
               <xsl:with-param name="target" select="substring(@target,2)"/>
               <xsl:with-param name="ptr" select="$ptr"/>
               <xsl:with-param name="dest">
                  <xsl:call-template name="generateEndLink">
                     <xsl:with-param name="where">
                        <xsl:value-of select="substring(@target,2)"/>
                     </xsl:with-param>
                  </xsl:call-template>
               </xsl:with-param>
            </xsl:call-template>
         </xsl:when>
         <!-- if we are doing  P4, all targets are local -->
      <xsl:when test="@target and $P4Compat='true'">
            <xsl:call-template name="makeInternalLink">
               <xsl:with-param name="target" select="@target"/>
               <xsl:with-param name="ptr" select="$ptr"/>
               <xsl:with-param name="dest">
                  <xsl:call-template name="generateEndLink">
                     <xsl:with-param name="where">
                        <xsl:value-of select="@target"/>
                     </xsl:with-param>
                  </xsl:call-template>
               </xsl:with-param>
            </xsl:call-template>
         </xsl:when>
         <!-- other uses of target means it is external -->
      <xsl:when test="@target">
            <xsl:call-template name="makeExternalLink">
               <xsl:with-param name="ptr" select="$ptr"/>
               <xsl:with-param name="dest">
                  <xsl:value-of select="@target"/>
               </xsl:with-param>
            </xsl:call-template>
         </xsl:when>
         <!-- If there is a url attribute starting with #, it is a local
       reference -->
      <xsl:when test="@url and starts-with(@url,'#')">
            <xsl:call-template name="makeInternalLink">
               <xsl:with-param name="target" select="substring(@url,2)"/>
               <xsl:with-param name="ptr" select="$ptr"/>
               <xsl:with-param name="dest">
                  <xsl:call-template name="generateEndLink">
                     <xsl:with-param name="where">
                        <xsl:value-of select="substring(@url,2)"/>
                     </xsl:with-param>
                  </xsl:call-template>
               </xsl:with-param>
            </xsl:call-template>
         </xsl:when>
         <!-- otherwise it is an external URL -->
      <xsl:when test="@url">
            <xsl:call-template name="makeExternalLink">
               <xsl:with-param name="ptr" select="$ptr"/>
               <xsl:with-param name="dest">
                  <xsl:value-of select="@url"/>
               </xsl:with-param>
            </xsl:call-template>
         </xsl:when>
         <!-- A doc attribute means an external reference -->
      <xsl:when test="@doc">
            <xsl:call-template name="makeExternalLink">
               <xsl:with-param name="ptr" select="$ptr"/>
               <xsl:with-param name="dest">
                  <xsl:value-of select="unparsed-entity-uri(@doc)"/>
               </xsl:with-param>
            </xsl:call-template>
         </xsl:when>
         <xsl:otherwise>
        <!--
      <xsl:for-each select="@*">
	[[markup error: <xsl:value-of select="name(.)"/>=<xsl:value-of select="."/>]]
      </xsl:for-each>
-->
        <xsl:apply-templates/>
         </xsl:otherwise>
      </xsl:choose>
  </xsl:template>
</xsl:stylesheet>
