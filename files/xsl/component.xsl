<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet [
	<!ENTITY hellip     " &#x2E;&#xA0;&#x2E;&#xA0;&#x2E; ">
	<!ENTITY inked     "&#x2759;">
	<!ENTITY caret     "&#x2038;">
	<!ENTITY minus     "&#x2212;">
	<!ENTITY plus     "&#x002B;">
	<!ENTITY shy     "&#x00AD;">
	<!ENTITY sigrange     "&#x002D;">
	<!ENTITY swdash     "&#x2002;&#x007E;&#x2002;">
	<!ENTITY verbar     "&#x2002;&#x007C;&#x2002;">
	<!ENTITY cmacr     "&#x63;&#x304;">
]>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns="http://www.w3.org/1999/xhtml">

	<!--
      Copyright (c) 2008, Regents of the University of California
      All rights reserved.
      
      Redistribution and use in source and binary forms, with or without 
      modification, are permitted provided that the following conditions are 
      met:
      
      - Redistributions of source code must retain the above copyright notice, 
      this list of conditions and the following disclaimer.
      - Redistributions in binary form must reproduce the above copyright 
      notice, this list of conditions and the following disclaimer in the 
      documentation and/or other materials provided with the distribution.
      - Neither the name of the University of California nor the names of its
      contributors may be used to endorse or promote products derived from 
      this software without specific prior written permission.
      
      THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
      AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
      IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
      ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE 
      LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
      CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
      SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
      INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
      CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
      ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
      POSSIBILITY OF SUCH DAMAGE.
   -->


<!-- Modifications from version distributed in TeiDisplay Omeka plugin -->

	<!-- ====================================================================== -->
	<!-- Heads                                                                  -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='head']">

		<xsl:variable name="type" select="parent::*/@type"/>

		<xsl:variable name="class">
			<xsl:choose>
				<xsl:when test="@rend">
					<xsl:value-of select="@rend"/>
				</xsl:when>
				<xsl:otherwise>normal</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>

		<xsl:choose>
			<xsl:when test="@type='sub' or @type='subtitle'">
				<!-- Needs more choices here -->
				<h3 class="{$class}">
					<xsl:apply-templates/>
				</h3>
			</xsl:when>
			<xsl:when test="$type='fmsec'">
				<h2 class="{$class}">
					<xsl:apply-templates/>
				</h2>
			</xsl:when>
			<xsl:when test="$type='volume'">
				<h1 class="{$class}">
					<xsl:if test="parent::*/@n">
						<xsl:value-of select="parent::*/@n"/>
						<xsl:text>. </xsl:text>
					</xsl:if>
					<xsl:apply-templates/>
				</h1>
			</xsl:when>
			<xsl:when test="$type='part'">
				<h1 class="{$class}">
					<xsl:if test="parent::*/@n">
						<xsl:value-of select="parent::*/@n"/>
						<xsl:text>. </xsl:text>
					</xsl:if>
					<xsl:apply-templates/>
				</h1>
			</xsl:when>
			<xsl:when test="$type='chapter'">
				<h2 class="{$class}">
					<xsl:if test="parent::*/@n">
						<xsl:value-of select="parent::*/@n"/>
						<xsl:text>. </xsl:text>
					</xsl:if>
					<xsl:apply-templates/>
				</h2>
			</xsl:when>
			<xsl:when test="$type='ss1'">
				<h3 class="{$class}">
					<xsl:if test="parent::*/@n">
						<xsl:value-of select="parent::*/@n"/>
						<xsl:text>. </xsl:text>
					</xsl:if>
					<xsl:apply-templates/>
				</h3>
			</xsl:when>
			<xsl:when test="$type='ss2'">
				<h3 class="{$class}">
					<xsl:apply-templates/>
				</h3>
			</xsl:when>
			<xsl:when test="$type='ss3'">
				<h3 class="{$class}">
					<xsl:apply-templates/>
				</h3>
			</xsl:when>
			<xsl:when test="$type='ss4'">
				<h4 class="{$class}">
					<xsl:apply-templates/>
				</h4>
			</xsl:when>
			<xsl:when test="$type='ss5'">
				<h4 class="{$class}">
					<xsl:apply-templates/>
				</h4>
			</xsl:when>
			<xsl:when test="$type='bmsec'">
				<h2 class="{$class}">
					<xsl:apply-templates/>
				</h2>
			</xsl:when>
			<xsl:when test="$type='appendix'">
				<h2 class="{$class}">
					<xsl:if test="parent::*/@n">
						<xsl:value-of select="parent::*/@n"/>
						<xsl:text>. </xsl:text>
					</xsl:if>
					<xsl:apply-templates/>
				</h2>
			</xsl:when>
			<xsl:when test="$type='endnotes'">
				<h3 class="{$class}">
					<xsl:apply-templates/>
				</h3>
			</xsl:when>
			<xsl:when test="$type='bibliography'">
				<h2 class="{$class}">
					<xsl:apply-templates/>
				</h2>
			</xsl:when>
			<xsl:when test="$type='glossary'">
				<h2 class="{$class}">
					<xsl:apply-templates/>
				</h2>
			</xsl:when>
			<xsl:when test="$type='index'">
				<h2 class="{$class}">
					<xsl:apply-templates/>
				</h2>
			</xsl:when>
			<xsl:otherwise>
				<h4 class="{$class}">
					<xsl:apply-templates/>
				</h4>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="*[local-name()='docAuthor']">
		<h4>
			<xsl:apply-templates/>
		</h4>
	</xsl:template>

	<!-- ====================================================================== -->
	<!-- Verse                                                                  -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='lg']">
		<div class="linegroup">
			<xsl:apply-templates/>
		</div>
	</xsl:template>

	<xsl:template match="*[local-name()='l']">
		<xsl:choose>
			<xsl:when test="parent::lg">
				<tr>
					<td width="30">
						<xsl:choose>
							<xsl:when test="@n">
								<span class="run-head">
									<xsl:value-of select="@n"/>
								</span>
							</xsl:when>
							<xsl:otherwise>
								<xsl:text> </xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</td>
					<td>
						<xsl:apply-templates/>
					</td>
				</tr>
			</xsl:when>
			<xsl:otherwise>
				<xsl:if test="@n">
					<span class="run-head">
						<xsl:value-of select="@n"/>
					</span>
				</xsl:if>
				<xsl:apply-templates/>
				<br/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="*[local-name()='seg']">
		<xsl:if test="position() > 1">
			<xsl:text>&#160;&#160;&#160;&#160;</xsl:text>
		</xsl:if>
		<xsl:apply-templates/>
		<br/>
	</xsl:template>

	<!-- ====================================================================== -->
	<!-- Speech                                                                 -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='sp']">
		<xsl:apply-templates/>
		<br/>
	</xsl:template>

	<xsl:template match="*[local-name()='speaker']">
		<b>
			<xsl:apply-templates/>
		</b>
	</xsl:template>

	<xsl:template match="*[local-name()='sp']/*[local-name()='p']">
		<p class="noindent">
			<xsl:apply-templates/>
		</p>
	</xsl:template>

	<!-- ====================================================================== -->
	<!-- Lists                                                                  -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='list']">
		<xsl:choose>
			<xsl:when test="@type='gloss'">
				<dl>
					<xsl:apply-templates/>
				</dl>
			</xsl:when>
			<xsl:when test="@type='simple'">
				<ul class="nobull">
					<xsl:apply-templates/>
				</ul>
			</xsl:when>
			<xsl:when test="@type='ordered'">
				<xsl:choose>
					<xsl:when test="@rend='alpha'">
						<ol class="alpha">
							<xsl:apply-templates/>
						</ol>
					</xsl:when>
					<xsl:otherwise>
						<ol>
							<xsl:apply-templates/>
						</ol>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:when>
			<xsl:when test="@type='unordered'">
				<ul>
					<xsl:apply-templates/>
				</ul>
			</xsl:when>
			<xsl:when test="@type='bulleted'">
				<xsl:choose>
					<xsl:when test="@rend='dash'">
						<ul class="nobull">
							<xsl:text>- </xsl:text>
							<xsl:apply-templates/>
						</ul>
					</xsl:when>
					<xsl:otherwise>
						<ul>
							<xsl:apply-templates/>
						</ul>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:when>
			<xsl:when test="@type='bibliographic'">
				<ol>
					<xsl:apply-templates/>
				</ol>
			</xsl:when>
			<xsl:when test="@type='special'">
				<ul>
					<xsl:apply-templates/>
				</ul>
			</xsl:when>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="*[local-name()='item']">
		<xsl:choose>
			<xsl:when test="parent::list[@type='gloss']">
				<dd>
					<xsl:apply-templates/>
				</dd>
			</xsl:when>
			<xsl:otherwise>
				<li>
					<xsl:apply-templates/>
				</li>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="*[local-name()='label']">
		<h4>
			<xsl:apply-templates/>
		</h4>
	</xsl:template>

	<xsl:template match="*[local-name()='name']">
		<xsl:apply-templates/>
	</xsl:template>

	<!-- ====================================================================== -->
	<!-- Notes                                                                  -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='note']">
		<div class="note">
			<xsl:apply-templates/>
		</div>
	</xsl:template>

	<xsl:template match="*[local-name()='p'][ancestor::note[@type='footnote' or @place='foot']]">

		<xsl:variable name="n" select="parent::note/@n"/>

		<p>
			<xsl:if test="position()=1">
				<xsl:if test="$n != ''">
					<xsl:text>[</xsl:text>
					<xsl:value-of select="$n"/>
					<xsl:text>] </xsl:text>
				</xsl:if>
			</xsl:if>
			<xsl:apply-templates/>
		</p>

	</xsl:template>

	<xsl:template match="*[local-name()='p'][ancestor::note[@type='endnote' or @place='end']]">

		<xsl:variable name="n" select="parent::note/@n"/>

		<xsl:variable name="class">
			<xsl:choose>
				<xsl:when test="position()=1">noindent</xsl:when>
				<xsl:otherwise>indent</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>

		<p class="{$class}">
			<xsl:apply-templates/>
		</p>
	</xsl:template>

	<!-- ====================================================================== -->
	<!-- Paragraphs                                                             -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='p'][not(ancestor::note[@type='endnote' or @place='end'])]">

		<xsl:choose>
			<xsl:when test="@rend='center'">
				<p class="center">
					<xsl:apply-templates/>
				</p>
			</xsl:when>
			<xsl:when test="name(preceding-sibling::node()[1])='pb'">
				<p class="noindent">
					<xsl:apply-templates/>
				</p>
			</xsl:when>
			<xsl:when test="parent::td">
				<p>
					<xsl:apply-templates/>
				</p>
			</xsl:when>
			<xsl:when test="contains(@rend, 'IndentHanging')">
				<p class="{@rend}">
					<xsl:apply-templates/>
				</p>
			</xsl:when>
			<xsl:when test="not(preceding-sibling::p)">
				<xsl:choose>
					<xsl:when test="@rend='hang'">
						<p class="hang">
							<xsl:apply-templates/>
						</p>
					</xsl:when>
					<xsl:when test="@rend='indent'">
						<p class="indent">
							<xsl:apply-templates/>
						</p>
					</xsl:when>
					<xsl:when test="@rend='noindent'">
						<p class="noindent">
							<xsl:apply-templates/>
						</p>
					</xsl:when>
					<xsl:otherwise>
						<p class="noindent">
							<xsl:apply-templates/>
						</p>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:when>
			<xsl:when test="not(following-sibling::p)">
				<xsl:choose>
					<xsl:when test="@rend='hang'">
						<p class="hang">
							<xsl:apply-templates/>
						</p>
					</xsl:when>
					<xsl:when test="@rend='indent'">
						<p class="indent">
							<xsl:apply-templates/>
						</p>
					</xsl:when>
					<xsl:when test="@rend='noindent'">
						<p class="noindent">
							<xsl:apply-templates/>
						</p>
					</xsl:when>
					<xsl:otherwise>
						<p class="padded">
							<xsl:apply-templates/>
						</p>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:when>
			<xsl:otherwise>
				<xsl:choose>
					<xsl:when test="@rend">
						<p class="{@rend}">
							<xsl:apply-templates/>
						</p>
					</xsl:when>
					<xsl:otherwise>
						<p class="normal">
							<xsl:apply-templates/>
						</p>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:otherwise>
		</xsl:choose>

	</xsl:template>

	<!-- ====================================================================== -->
	<!-- Other Text Blocks                                                      -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='epigraph']">
		<blockquote>
			<xsl:apply-templates/>
		</blockquote>
		<br/>
	</xsl:template>

	<xsl:template match="*[local-name() = 'closer']">
		<blockquote>
			<xsl:apply-templates/>
		</blockquote>
	</xsl:template>

	<xsl:template match="*[local-name()='epigraph']/*[local-name()='bibl']">
		<p class="right">
			<xsl:apply-templates/>
		</p>
	</xsl:template>

	<xsl:template match="*[local-name()='byline']">
		<p class="right">
			<xsl:apply-templates/>
		</p>
	</xsl:template>

	<xsl:template match="*[local-name()='cit']">
		<xsl:apply-templates/>
	</xsl:template>

	<xsl:template match="*[local-name()='cit']/*[local-name()='bibl']">
		<p class="right">
			<xsl:apply-templates/>
		</p>
	</xsl:template>

	<xsl:template match="*[local-name()='quote']">
		<xsl:apply-templates/>
	</xsl:template>

	<xsl:template match="*[local-name()='q']">
		<blockquote>
			<xsl:apply-templates/>
		</blockquote>
	</xsl:template>

	<xsl:template match="*[local-name()='date']">
		<xsl:apply-templates/>
	</xsl:template>

	<xsl:template match="*[local-name()='foreign']">
		<i>
			<xsl:apply-templates/>
		</i>
	</xsl:template>

	<xsl:template match="*[local-name()='address']">
		<xsl:apply-templates/>
	</xsl:template>

	<xsl:template match="*[local-name()='addrLine']">
		<xsl:apply-templates/>
		<br/>
	</xsl:template>

	<!-- ====================================================================== -->
	<!-- Bibliographies                                                         -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='listBibl']">
		<xsl:apply-templates/>
	</xsl:template>

	<xsl:template match="*[local-name()='bibl']">
		<xsl:choose>
			<xsl:when test="parent::listBibl">
				<p class="hang">
					<xsl:apply-templates/>
				</p>
			</xsl:when>
			<xsl:otherwise>
				<xsl:apply-templates/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<!-- Because of order in the following, "rend" takes precedence over "level" -->

	<xsl:template match="*[local-name()='title']">
		<xsl:choose>
			<xsl:when test="@rend='italic'">
				<i>
					<xsl:apply-templates/>
				</i>
			</xsl:when>
			<xsl:when test="@level='m'">
				<i>
					<xsl:apply-templates/>
				</i>
			</xsl:when>
			<xsl:when test="@level='a'"> &#x201C;<xsl:apply-templates/>&#x201D; </xsl:when>
			<xsl:when test="@level='j'">
				<i>
					<xsl:apply-templates/>
				</i>
			</xsl:when>
			<xsl:when test="@level='s'">
				<i>
					<xsl:apply-templates/>
				</i>
			</xsl:when>
			<xsl:when test="@level='u'">
				<i>
					<xsl:apply-templates/>
				</i>
			</xsl:when>
			<xsl:otherwise>
				<xsl:apply-templates/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="*[local-name()='author']">
		<xsl:choose>
			<xsl:when test="@rend='hide'">
				<xsl:text>&#x2014;&#x2014;&#x2014;</xsl:text>
			</xsl:when>
			<xsl:otherwise>
				<xsl:apply-templates/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<!-- ====================================================================== -->
	<!-- Formatting                                                             -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='hi']">
		<xsl:choose>
			<xsl:when test="@rend='bold'">
				<b>
					<xsl:apply-templates/>
				</b>
			</xsl:when>
			<xsl:when test="@rend='italic'">
				<i>
					<xsl:apply-templates/>
				</i>
			</xsl:when>
			<xsl:when test="@rend='mono'">
				<code>
					<xsl:apply-templates/>
				</code>
			</xsl:when>
			<xsl:when test="@rend='roman'">
				<span class="normal">
					<xsl:apply-templates/>
				</span>
			</xsl:when>
			<xsl:when test="@rend='smallcaps'">
				<span class="sc">
					<xsl:apply-templates/>
				</span>
			</xsl:when>
			<xsl:when test="@rend='sub' or @rend='subscript'">
				<sub>
					<xsl:apply-templates/>
				</sub>
			</xsl:when>
			<xsl:when test="@rend='sup' or @rend='superscript'">
				<sup>
					<xsl:apply-templates/>
				</sup>
			</xsl:when>
			<xsl:when test="@rend='underline'">
				<u>
					<xsl:apply-templates/>
				</u>
			</xsl:when>
			<xsl:otherwise>
				<i>
					<xsl:apply-templates/>
				</i>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="*[local-name()='lb']">
		<br/>
	</xsl:template>

	<!-- ====================================================================== -->
	<!-- References                                                             -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='ref']">
		<!-- process refs -->
		<xsl:choose>
			<xsl:when test="string(@target)">
				<a href="{@target}" class="ref {@targType}">					
					<xsl:apply-templates/>
				</a>
			</xsl:when>
			<xsl:otherwise>
				<xsl:apply-templates/>
			</xsl:otherwise>
		</xsl:choose>

	</xsl:template>

	<xsl:template match="*[local-name()='xref']">
		<a>
			<xsl:attribute name="href">
				<xsl:value-of select="@to"/>
			</xsl:attribute>
			<xsl:attribute name="target">_top</xsl:attribute>
			<xsl:apply-templates/>
		</a>
	</xsl:template>

	<!-- ====================================================================== -->
	<!-- Figures                                                                -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='figure']">

		<xsl:variable name="img_src">
			<xsl:value-of select="@entity"/>
		</xsl:variable>

		<xsl:choose>
			<xsl:when test="@rend='hide'">
				<div class="illgrp">
					<p>Image Withheld</p>
					<!-- for figDesc -->
					<xsl:apply-templates/>
				</div>
			</xsl:when>
			<xsl:when test="@rend='inline'">
				<img src="{$img_src}" alt="inline image"/>
			</xsl:when>
			<xsl:when test="@rend='block'">
				<div class="illgrp">
					<img src="{$img_src}" width="400" alt="block image"/>
					<!-- for figDesc -->
					<xsl:apply-templates/>
				</div>
			</xsl:when>
			<xsl:when test="contains(@rend, 'popup(')">
				<div class="illgrp">
					<img src="{$img_src}" alt="figure"/>
					<!-- for figDesc -->
					<xsl:apply-templates/>
				</div>
			</xsl:when>
			<xsl:otherwise>
				<div class="illgrp">
					<img src="{$img_src}" width="400" alt="image"/>
					<!-- for figDesc -->
					<xsl:apply-templates/>
				</div>
			</xsl:otherwise>
		</xsl:choose>

	</xsl:template>

	<xsl:template match="*[local-name()='figDesc']">
		<br/>
		<span class="down1">
			<xsl:if test="@n"><xsl:value-of select="@n"/>. </xsl:if>
			<xsl:apply-templates/>
		</span>
	</xsl:template>

	<!-- ====================================================================== -->
	<!-- Milestones                                                             -->
	<!-- ====================================================================== -->

	<xsl:template match="*[local-name()='pb']">
		<hr class="pb"/>
		<div align="center">&#x2015; <span class="run-head"><xsl:value-of select="@n"/></span>
		&#x2015;</div>
		<xsl:if test="@facs">
			<img src="{@facs}"/>
		</xsl:if>
	</xsl:template>

	<xsl:template match="*[local-name()='milestone']">
		<xsl:if test="@rend='ornament' or @rend='ornamental_break'">
			<div align="center">
				<table border="0" width="40%">
					<tr align="center">
						<td>&#x2022;</td>
						<td>&#x2022;</td>
						<td>&#x2022;</td>
					</tr>
				</table>
			</div>
		</xsl:if>

	</xsl:template>

	<xsl:template match="*[local-name()='lem']">
		<span class='lem'>
			<xsl:apply-templates></xsl:apply-templates>
		</span>
	</xsl:template>

	<xsl:template match="*[local-name() = 'space']">
		<xsl:text> </xsl:text>
	</xsl:template>


<xsl:template match="*">
	<xsl:message><xsl:value-of select="local-name()"/></xsl:message>
	<xsl:apply-templates></xsl:apply-templates>
</xsl:template>


</xsl:stylesheet>
