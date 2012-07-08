<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns="http://www.w3.org/1999/xhtml" 
                xmlns:a="http://relaxng.org/ns/compatibility/annotations/1.0"
                xmlns:fo="http://www.w3.org/1999/XSL/Format"
                xmlns:html="http://www.w3.org/1999/xhtml"
                xmlns:rng="http://relaxng.org/ns/structure/1.0"
                xmlns:tei="http://www.tei-c.org/ns/1.0"
                xmlns:teix="http://www.tei-c.org/ns/Examples"                
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                exclude-result-prefixes=" a fo rng tei teix html"
                version="1.0">
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet" type="stylesheet">
      <desc>
         <p> TEI stylesheet dealing with elements from the drama module,
      making HTML output. </p>
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
         <p>Id: $Id: drama.xsl 9395 2011-09-26 18:37:32Z rahtz $</p>
         <p>Copyright: 2011, TEI Consortium</p>
      </desc>
   </doc>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element actor</desc>
   </doc>
  <xsl:template match="actor">
      <span class="actor">
         <xsl:apply-templates/>
      </span>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element camera</desc>
   </doc>
  <xsl:template match="camera">
      <span class="camera">
         <xsl:apply-templates/>
      </span>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element caption</desc>
   </doc>
  <xsl:template match="caption">
      <span class="caption">
         <xsl:apply-templates/>
      </span>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element castGroup</desc>
   </doc>
  <xsl:template match="castGroup">
      <ul>
         <xsl:apply-templates/>
      </ul>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element castItem</desc>
   </doc>
  <xsl:template match="castItem">
      <li>
         <xsl:apply-templates/>
      </li>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element castList</desc>
   </doc>
  <xsl:template match="castList">
      <xsl:if test="head">
         <p>
            <em>
               <xsl:for-each select="head">
                  <xsl:apply-templates/>
               </xsl:for-each>
            </em>
         </p>
      </xsl:if>
      <ul>
         <xsl:apply-templates/>
      </ul>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element castList/head</desc>
   </doc>
  <xsl:template match="castList/head"/>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element role</desc>
   </doc>
  <xsl:template match="role">
      <span class="role">
         <xsl:apply-templates/>
      </span>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element roleDesc</desc>
   </doc>
  <xsl:template match="roleDesc">
      <blockquote>
	        <xsl:choose>
	           <xsl:when test="@rend">
	              <xsl:attribute name="class">
		                <xsl:value-of select="@rend"/>
	              </xsl:attribute>
	           </xsl:when>
	           <xsl:when test="@rendition">
		             <xsl:call-template name="applyRendition"/>
	           </xsl:when>
	           <xsl:otherwise>
	              <xsl:attribute name="class">
		                <xsl:text>roleDesc</xsl:text>
	              </xsl:attribute>
	           </xsl:otherwise>
	        </xsl:choose>
         <xsl:choose>
            <xsl:when test="p">
               <xsl:apply-templates/>
            </xsl:when>
            <xsl:otherwise>
               <p>
                  <xsl:apply-templates/>
               </p>
            </xsl:otherwise>
         </xsl:choose>
      </blockquote>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element set</desc>
   </doc>
  <xsl:template match="set">
      <span class="set">
         <xsl:apply-templates/>
      </span>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element sound</desc>
   </doc>
  <xsl:template match="sound">
      <span class="sound">
         <xsl:apply-templates/>
      </span>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>
         <p>Process element sp</p>
         <p>
            <p xmlns="http://www.w3.org/1999/xhtml"> elaborated by Nick Nicholas &lt;nicholas@uci.edu&gt;, March
        2001 </p>
         </p>
      </desc>
   </doc>
  <xsl:template match="sp">
    <div class="speaker">
      <xsl:call-template name="makeAnchor"/>
      <xsl:apply-templates select="speaker"/>
    </div>
    <xsl:apply-templates select="*[not(self::speaker)]"/>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element sp/p</desc>
   </doc>
  <xsl:template match="sp/p">
    <xsl:choose>
      <xsl:when test="$filePerPage='true'">
	<xsl:for-each-group select="node()" group-starting-with="pb">
	  <xsl:choose>
	    <xsl:when test="self::pb">
	      <xsl:apply-templates select="."/>
	      <div>
		<xsl:for-each select="..">
		  <xsl:call-template name="rendToClass">      
		    <xsl:with-param name="default">p-in-sp</xsl:with-param>
		    <xsl:with-param name="id">
		      <xsl:choose>
			<xsl:when test="@xml:id">
			  <xsl:value-of select="@xml:id"/>
			  <xsl:text>continued</xsl:text>
			</xsl:when>
			<xsl:otherwise>
			  <xsl:text>false</xsl:text>
			</xsl:otherwise>
		      </xsl:choose>
		    </xsl:with-param>
		  </xsl:call-template>
		</xsl:for-each>
		<xsl:apply-templates select="current-group() except ."/>
	      </div>
	    </xsl:when>
	    <xsl:otherwise>
	      <div>
		<xsl:for-each select="..">
		  <xsl:call-template name="rendToClass">      
		    <xsl:with-param name="default">p-in-sp</xsl:with-param>
		  </xsl:call-template>
		</xsl:for-each>
		<xsl:apply-templates select="current-group()"/>
	      </div>
	    </xsl:otherwise>
	  </xsl:choose>
	</xsl:for-each-group>
      </xsl:when>
      <xsl:otherwise>
	<div>
	  <xsl:call-template name="rendToClass">      
	    <xsl:with-param name="default">p-in-sp</xsl:with-param>
	  </xsl:call-template>
	  <xsl:apply-templates/>
	</div>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element stage</desc>
   </doc>
  <xsl:template match="stage">
    <xsl:element name="{if (blockContext(.) or *[not(is-inline(.))]) then 'div' else 'span' }">
      <xsl:call-template name="rendToClass">
	<xsl:with-param name="default">
	  <xsl:choose>
	    <xsl:when
		test="ancestor::text/@rend='firstfolio'">stage</xsl:when>
	    <xsl:otherwise>stage it</xsl:otherwise>
	  </xsl:choose>
	</xsl:with-param>
      </xsl:call-template>
      <xsl:apply-templates/>
    </xsl:element>
  </xsl:template>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element tech</desc>
   </doc>
  <xsl:template match="tech">
      <span class="tech">
         <xsl:apply-templates/>
      </span>
  </xsl:template>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
      <desc>Process element view</desc>
   </doc>
  <xsl:template match="view">
      <span class="view">
         <xsl:apply-templates/>
      </span>
  </xsl:template>
</xsl:stylesheet>