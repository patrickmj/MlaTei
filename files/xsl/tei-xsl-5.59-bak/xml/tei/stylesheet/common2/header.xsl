<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:="http://www.-c.org/ns/1.0"
                
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                exclude-result-prefixes=""
                version="1.0">
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet" type="stylesheet">
      <desc>
         <p>  stylesheet dealing with elements from the header module. </p>
         <p> This library is free software; you can redistribute it and/or modify it under the terms of the
      GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of
      the License, or (at your option) any later version. This library is distributed in the hope that it will
      be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
      A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details. You should have
      received a copy of the GNU Lesser General Public License along with this library; if not, write to the
      Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA </p>
         <p>Author: See AUTHORS</p>
         <p>Id: $Id: header.xsl 9494 2011-10-12 22:25:12Z sbauman $</p>
         <p>Copyright: 2011,  Consortium</p>
      </desc>
   </doc>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>[common] Find a plausible editor name</desc>
   </doc>
  <xsl:template name="generateEditor">
    <xsl:choose>
      <xsl:when test="ancestor-or-self::/Header/fileDesc/titleStmt/editor">
        <xsl:for-each
          select="ancestor-or-self::/Header/fileDesc/titleStmt/editor">
          <xsl:apply-templates/>
          <xsl:choose>
            <xsl:when test="count(following-sibling::editor)=1">
              <xsl:if test="count(preceding-sibling::editor)>=1">
                <xsl:text>,</xsl:text>
              </xsl:if>
              <xsl:call-template name="i18n">
                <xsl:with-param name="word">and</xsl:with-param>
              </xsl:call-template>
            </xsl:when>
            <xsl:when test="following-sibling::editor">, </xsl:when>
          </xsl:choose>
        </xsl:for-each>
      </xsl:when>
      <xsl:when
        test="ancestor-or-self::/Header/revisionDesc/change/respStmt[resp='editor']">
        <xsl:apply-templates
          select="ancestor-or-self::/Header/revisionDesc/change/respStmt[resp='editor'][1]/name"
        />
      </xsl:when>
    </xsl:choose>
  </xsl:template>
  
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>[common] Find a plausible main author name</desc>
   </doc>
  <xsl:template name="generateAuthor">
      <xsl:choose>
         <xsl:when test="$useHeaderFrontMatter='true' and ancestor-or-self::/text/front//docAuthor">
            <xsl:apply-templates mode="author"
                                 select="ancestor-or-self::/text/front//docAuthor"/>
         </xsl:when>
         <xsl:when test="ancestor-or-self::/Header/fileDesc/titleStmt/author">
            <xsl:for-each select="ancestor-or-self::/Header/fileDesc/titleStmt/author">
               <xsl:apply-templates/>
               <xsl:choose>
            <xsl:when test="count(following-sibling::author)=1">
              <xsl:if test="count(preceding-sibling::author)>1">
                <xsl:text>,</xsl:text>
              </xsl:if>
              <xsl:call-template name="i18n">
                <xsl:with-param name="word">and</xsl:with-param>
              </xsl:call-template>
            </xsl:when>
                  <xsl:when test="following-sibling::author">, </xsl:when>
               </xsl:choose>
            </xsl:for-each>
         </xsl:when>
         <xsl:when test="ancestor-or-self::/Header/revisionDesc/change/respStmt[resp='author']">
            <xsl:apply-templates select="ancestor-or-self::/Header/revisionDesc/change/respStmt[resp='author'][1]/name"/>
         </xsl:when>
         <xsl:when test="ancestor-or-self::/text/front//docAuthor">
            <xsl:apply-templates mode="author"
                                 select="ancestor-or-self::/text/front//docAuthor"/>
         </xsl:when>
      </xsl:choose>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>[common] Find a plausible name of person responsible for current revision</desc>
   </doc>
  <xsl:template name="generateRevAuthor">
      <xsl:variable name="who">
         <xsl:choose>
            <xsl:when test="ancestor-or-self::/Header/revisionDesc/@vcwho">
               <xsl:apply-templates select="ancestor-or-self::/Header/revisionDesc/@vcwho"/>
            </xsl:when>
            <xsl:when test="ancestor-or-self::/Header/revisionDesc/change[1]/respStmt/name">
               <xsl:value-of select="ancestor-or-self::/Header/revisionDesc/change[1]/respStmt/name/text()"/>
            </xsl:when>
         </xsl:choose>
      </xsl:variable>
      <xsl:choose>
         <xsl:when test="normalize-space($who)=concat('$Author', '$')"/>
         <xsl:when test="starts-with($who,'$Author')">
        <!-- it's RCS -->
        <xsl:value-of select="normalize-space(substring-before(substring-after($who,'Author'),'$'))"/>
         </xsl:when>
         <xsl:when test="starts-with($who,'$LastChangedBy')">
        <!-- it's Subversion -->
        <xsl:value-of select="normalize-space(substring-before(substring-after($who,'LastChangedBy:'),'$'))"/>
         </xsl:when>
         <xsl:otherwise>
            <xsl:value-of select="$who"/>
         </xsl:otherwise>
      </xsl:choose>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>[common] </desc>
   </doc>
  <xsl:template name="generateAuthorList">
      <xsl:variable name="realauthor">
         <xsl:call-template name="generateAuthor"/>
      </xsl:variable>
      <xsl:variable name="revauthor">
         <xsl:call-template name="generateRevAuthor"/>
      </xsl:variable>
      <xsl:variable name="editor">
        <xsl:call-template name="generateEditor"/>
      </xsl:variable>
      <xsl:if test="not($realauthor = '')">
        <p xmlns="http://www.w3.org/1999/xhtml" class="mainAuthor">
         <xsl:text> </xsl:text>
         <xsl:call-template name="i18n">
            <xsl:with-param name="word">authorWord</xsl:with-param>
         </xsl:call-template>
          <xsl:text>: </xsl:text>
         <xsl:copy-of select="$realauthor"/>
        </p>
      </xsl:if>
      <xsl:if test="not($revauthor = '')">
      <p class="mainRevAuthor" xmlns="http://www.w3.org/1999/xhtml">
         <xsl:text> (</xsl:text>
         <xsl:call-template name="i18n">
            <xsl:with-param name="word">revisedWord</xsl:with-param>
         </xsl:call-template>
         <xsl:text> </xsl:text>
         <xsl:copy-of select="$revauthor"/>
         <xsl:text>)</xsl:text>
      </p>
    </xsl:if>
    <xsl:if test="not($editor = '')">
      <p class="mainEditor" xmlns="http://www.w3.org/1999/xhtml">
         <xsl:call-template name="i18n">
            <xsl:with-param name="word">editorWord</xsl:with-param>
         </xsl:call-template>
        <xsl:text>: </xsl:text>
        <xsl:copy-of select="$editor"/>
      </p>
      </xsl:if>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>[common] Work out the last revision date of the document </desc>
   </doc>
  <xsl:template name="generateRevDate">
      <xsl:variable name="when">
         <xsl:choose>
            <xsl:when test="ancestor-or-self::/Header/revisionDesc/@vcdate">
               <xsl:apply-templates select="ancestor-or-self::/Header/revisionDesc/@vcdate"/>
            </xsl:when>
            <xsl:when test="ancestor-or-self::/Header/revisionDesc/descendant::date">
               <xsl:value-of select="ancestor-or-self::/Header/revisionDesc/descendant::date[1]"/>
            </xsl:when>
            <xsl:when
		test="ancestor-or-self::/Header/fileDesc/descendant::date">
	      <xsl:value-of select="ancestor-or-self::/Header/fileDesc/descendant::date"/>
	    </xsl:when>	    
         </xsl:choose>
      </xsl:variable>
      <xsl:choose>
         <xsl:when test="starts-with($when,'$Date')">
        <!-- it's RCS -->
        <xsl:value-of select="substring($when,16,2)"/>
            <xsl:text>/</xsl:text>
            <xsl:value-of select="substring($when,13,2)"/>
            <xsl:text>/</xsl:text>
            <xsl:value-of select="substring($when,8,4)"/>
         </xsl:when>
         <xsl:when test="starts-with($when,'$LastChangedDate')">
        <!-- it's Subversion-->
        <xsl:value-of select="substring-before(substring-after($when,'('),')')"/>
         </xsl:when>
         <xsl:otherwise>
            <xsl:value-of select="$when"/>
         </xsl:otherwise>
      </xsl:choose>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>[common] Work out the publish date of the document </desc>
   </doc>
  <xsl:template name="generateDate">
      <xsl:choose>
	 <xsl:when test="$useFixedDate='true'">1970-01-01</xsl:when>
         <xsl:when test="$useHeaderFrontMatter='true' and ancestor-or-self::/text/front//docDate">
            <xsl:apply-templates mode="date" select="ancestor-or-self::/text/front//docDate"/>
         </xsl:when>
         <xsl:when
	     test="ancestor-or-self::/Header/fileDesc/editionStmt/descendant::date[@when]">
            <xsl:value-of select="ancestor-or-self::/Header/fileDesc/editionStmt/descendant::date[@when][1]/@when"/>
         </xsl:when>
         <xsl:when test="ancestor-or-self::/Header/fileDesc/editionStmt/descendant::date">
            <xsl:value-of select="ancestor-or-self::/Header/fileDesc/editionStmt/descendant::date[1]"/>
         </xsl:when>
         <xsl:when test="ancestor-or-self::/Header/fileDesc/publicationStmt/date">
            <xsl:value-of select="ancestor-or-self::/Header/fileDesc/publicationStmt/date"/>
         </xsl:when>
         <xsl:when test="ancestor-or-self::/Header/fileDesc/editionStmt/edition">
            <xsl:apply-templates select="ancestor-or-self::/Header/fileDesc/editionStmt/edition"/>
         </xsl:when>
	 <xsl:when
	     test="ancestor-or-self::/Header/revisionDesc/change[@when
		   or date]">
            <xsl:for-each
		select="ancestor-or-self::/Header/revisionDesc/change[1]">
	      <xsl:choose>
		<xsl:when test="@when">
		  <xsl:value-of select="@when"/>
		</xsl:when>
		<xsl:when test="date/@when">
		  <xsl:value-of select="date/@when"/>
		</xsl:when>
		<xsl:when test="date">
		  <xsl:value-of select="date"/>
		</xsl:when>
		<xsl:otherwise>
		  <xsl:value-of select="format-dateTime(current-dateTime(),'[Y]-[M02]-[D02]')"/>
		</xsl:otherwise>
	      </xsl:choose>
	    </xsl:for-each>
	 </xsl:when>
	 <xsl:otherwise>
	   <xsl:value-of select="format-dateTime(current-dateTime(),'[Y]-[M02]-[D02]')"/>
	 </xsl:otherwise>
      </xsl:choose>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>[common] Generate a title</desc>
   </doc>
  <xsl:template name="generateTitle">
      <xsl:choose>
         <xsl:when test="$useHeaderFrontMatter='true' and ancestor-or-self::/text/front//docTitle">
            <xsl:apply-templates select="ancestor-or-self::/text/front//docTitle/titlePart"/>
         </xsl:when>

         <xsl:when test="$useHeaderFrontMatter='true' and ancestor-or-self::Corpus/text/front//docTitle">
            <xsl:apply-templates select="ancestor-or-self::Corpus/text/front//docTitle/titlePart"/>
         </xsl:when>

         <xsl:when test="self::Corpus">	
            <xsl:apply-templates select="Header/fileDesc/titleStmt/title[not(@type='subordinate')]"/>
         </xsl:when>

         <xsl:otherwise>
            <xsl:for-each
		select="ancestor-or-self::/Header/fileDesc/titleStmt">
	      <xsl:choose>
		<xsl:when test="title[@type='main']">
		  <xsl:apply-templates select="title[@type='main']"/>
		</xsl:when>
		<xsl:otherwise>
		  <xsl:apply-templates select="title"/>
		</xsl:otherwise>
	      </xsl:choose>
	    </xsl:for-each>
         </xsl:otherwise>
      </xsl:choose>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>
         <p>[common] </p>
         <p>Generate simple title with no markup</p>
      </desc>
   </doc>
  <xsl:template name="generateSimpleTitle">
      <xsl:choose>
         <xsl:when test="$useHeaderFrontMatter='true' and ancestor-or-self::/text/front//docTitle">
            <xsl:apply-templates select="ancestor-or-self::/text/front//docTitle"
                                 mode="simple"/>
         </xsl:when>
         <xsl:otherwise>
            <xsl:for-each
		select="ancestor-or-self::/Header/fileDesc/titleStmt">
	      <xsl:choose>
		<xsl:when test="title[@type='main']">
		  <xsl:apply-templates
		      select="title[@type='main']" mode="simple"/>
		</xsl:when>
		<xsl:otherwise>
		  <xsl:apply-templates select="title[1]" mode="simple"/>
		</xsl:otherwise>
	      </xsl:choose>
	    </xsl:for-each>
         </xsl:otherwise>
      </xsl:choose>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>[common] Generate sub title </desc>
   </doc>
  <xsl:template name="generateSubTitle">
      <xsl:choose>
         <xsl:when test="$useHeaderFrontMatter='true' and ancestor-or-self::/text/front//docTitle">
            <xsl:apply-templates select="ancestor-or-self::/text/front//docTitle"/>
         </xsl:when>
         <xsl:when test="$useHeaderFrontMatter='true' and ancestor-or-self::Corpus/text/front//docTitle">
            <xsl:apply-templates select="ancestor-or-self::Corpus/text/front//docTitle"/>
         </xsl:when>
         <xsl:otherwise>
            <xsl:for-each select="ancestor-or-self::|ancestor-or-self::Corpus">
               <xsl:apply-templates select="Header/fileDesc/titleStmt/title[@type='subordinate']"/>
            </xsl:for-each>
         </xsl:otherwise>
      </xsl:choose>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc/>
   </doc>
  <xsl:template match="div/docAuthor"/>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc> Omit docAuthor found outside front matter</desc>
   </doc>
  <xsl:template match="div/docDate"/>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc> Omit docDate if found outside front matter</desc>
   </doc>
  <xsl:template match="div/docTitle"/>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Ignore docTitle in a div</desc>
   </doc>
  <xsl:template match="docAuthor" mode="heading">
      <xsl:if test="preceding-sibling::docAuthor">
         <xsl:choose>
            <xsl:when test="not(following-sibling::docAuthor)">
               <xsl:text> and </xsl:text>
            </xsl:when>
            <xsl:otherwise>
               <xsl:text>, </xsl:text>
            </xsl:otherwise>
         </xsl:choose>
      </xsl:if>
      <xsl:apply-templates/>
  </xsl:template>

   <xsl:template match="idno[@type='url']">
      <xsl:text> &lt;</xsl:text>
      <xsl:call-template name="makeExternalLink">
         <xsl:with-param name="ptr" select="true()"/>
         <xsl:with-param name="dest">
            <xsl:value-of select="normalize-space(.)"/>
         </xsl:with-param>
      </xsl:call-template>
      <xsl:text>&gt;.</xsl:text>
   </xsl:template>


   <xsl:template match="idno">
      <xsl:text> </xsl:text>
      <xsl:apply-templates/>
   </xsl:template>

   <xsl:template match="idno[@type='doi']"/>

  <xsl:template name="generateEdition">
    <p xmlns="http://www.w3.org/1999/xhtml" class="editionStmt">
      <xsl:apply-templates select="/(Corpus|)/Header/fileDesc/editionStmt"/>
    </p>
  </xsl:template>

</xsl:stylesheet>
