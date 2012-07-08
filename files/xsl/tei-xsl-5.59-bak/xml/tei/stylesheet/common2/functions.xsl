<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:cals="http://www.oasis-open.org/specs/tm9901" xmlns:="http://www.-c.org/ns/1.0" xmlns:iso="http://www.iso.org/ns/1.0" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:fn="http://www.w3.org/2005/02/xpath-functions" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml" xmlns:mml="http://www.w3.org/1998/Math/MathML" xmlns:tbx="http://www.lisa.org/TBX-Specification.33.0.html" xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture" xmlns:docx="http://www.-c.org/ns/docx/1.0" version="1.0" exclude-result-prefixes="cals ve o r m v wp w10 w wne mml tbx iso  a xs pic fn">
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl" scope="stylesheet" type="stylesheet">
    <desc>
      <p>  Utility stylesheet defining functions for use in all
	 output formats.</p>
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
      <p>Id: $Id: functions.xsl 9480 2011-10-09 22:56:09Z rahtz $</p>
      <p>Copyright: 2008,  Consortium</p>
    </desc>
  </doc>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
    <desc>Whether a section is "identifiable"</desc>
  </doc>
  <xsl:function name="is-identifiable" as="xs:boolean">
    <xsl:param name="element"/>
    <xsl:for-each select="$element">
      <xsl:choose>
        <xsl:when test="self::div">true</xsl:when>
        <xsl:when test="self::div1">true</xsl:when>
        <xsl:when test="self::div2">true</xsl:when>
        <xsl:when test="self::div3">true</xsl:when>
        <xsl:when test="self::div4">true</xsl:when>
        <xsl:when test="self::div5">true</xsl:when>
        <xsl:when test="self::div6">true</xsl:when>
        <xsl:when test="self::p[@xml:id]">true</xsl:when>
        <xsl:when test="self::index[@xml:id]">true</xsl:when>
        <xsl:otherwise>false</xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </xsl:function>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
    <desc>Whether a section is "transcribable"</desc>
  </doc>
  <xsl:function name="is-transcribable" as="xs:boolean">
    <xsl:param name="element"/>
    <xsl:for-each select="$element">
      <xsl:choose>
	<xsl:when test="self::p and parent::sp">true</xsl:when>
        <xsl:when test="self::l">true</xsl:when>
        <xsl:otherwise>false</xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </xsl:function>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
    <desc>Whether to render text in small caps.</desc>
  </doc>
  <xsl:function name="render-smallcaps" as="xs:boolean">
    <xsl:param name="element"/>
    <xsl:for-each select="$element">
      <xsl:choose>
        <xsl:when test="contains(@rend,'smallcaps')">true</xsl:when>
        <xsl:when test="@rend='sc'">true</xsl:when>
        <xsl:otherwise>false</xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </xsl:function>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
    <desc>Whether to render text in smart quotes.</desc>
  </doc>
  <xsl:function name="render-quotes" as="xs:boolean">
    <xsl:param name="element"/>
    <xsl:for-each select="$element">
      <xsl:choose>
        <xsl:when test="self::soCalled">true</xsl:when>
        <xsl:when test="contains(@rend,'quotes')">true</xsl:when>
        <xsl:otherwise>false</xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </xsl:function>


  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
    <desc>Whether to render text in bold.</desc>
  </doc>
  <xsl:function name="render-bold" as="xs:boolean">
    <xsl:param name="element"/>
    <xsl:for-each select="$element">
      <xsl:choose>
        <xsl:when test="parent::hi[starts-with(@rend,'specList-')]">true</xsl:when>
        <xsl:when test="parent::hi[@rend='bold']">true</xsl:when>
        <xsl:when test="contains(@rend,'bold')">true</xsl:when>
        <xsl:when test="@rend='label'">true</xsl:when>
        <xsl:when test="ancestor-or-self::cell[@role='label']">true</xsl:when>
        <xsl:when test="ancestor-or-self::cell[@rend='wovenodd-col1']">true</xsl:when>
        <xsl:when test="self::cell and parent::row[@role='label']">true</xsl:when>
        <xsl:when test="self::label[following-sibling::item]">true</xsl:when>
        <xsl:when test="self::term">true</xsl:when>
        <xsl:when test="self::unclear">true</xsl:when>
        <xsl:otherwise>false</xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </xsl:function>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
    <desc>Whether to render something in italic.</desc>
  </doc>
  <xsl:function name="render-italic" as="xs:boolean">
    <xsl:param name="element"/>
    <xsl:for-each select="$element">
      <xsl:choose>
        <xsl:when test="self::ref and render-italic(..)">true</xsl:when>
        <xsl:when test="contains(@rend,'italic')">true</xsl:when>
        <xsl:when test="self::emph">true</xsl:when>
        <xsl:when test="self::hi[not(@rend)]">true</xsl:when>
        <xsl:when test="self::tbx:hi[@style='italics']">true</xsl:when>
        <xsl:when test="@rend='ital'">true</xsl:when>
        <xsl:when test="@rend='it'">true</xsl:when>
        <xsl:when test="@rend='i'">true</xsl:when>
        <xsl:when test="@rend='att'">true</xsl:when>
        <xsl:when test="self::att">true</xsl:when>
        <xsl:when test="self::speaker">true</xsl:when>
        <xsl:when test="self::gloss">true</xsl:when>
        <xsl:when test="self::title">true</xsl:when>
        <xsl:when test="self::name">true</xsl:when>
        <xsl:otherwise>false</xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </xsl:function>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
    <desc>Whether to render something in typewriter-like code.</desc>
  </doc>
  <xsl:function name="render-typewriter" as="xs:boolean">
    <xsl:param name="element"/>
    <xsl:for-each select="$element">
      <xsl:choose>
        <xsl:when test="self::gi">true</xsl:when>
        <xsl:when test="self::val">true</xsl:when>
        <xsl:when test="self::code">true</xsl:when>
        <xsl:when test="self::ident">true</xsl:when>
        <xsl:otherwise>false</xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </xsl:function>
  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
    <desc>Is given an element and defines whether or not this element is to be rendered inline.</desc>
  </doc>
  <xsl:function name="is-inline" as="xs:boolean">
    <xsl:param name="element"/>
    <xsl:for-each select="$element">
      <xsl:choose>
        <xsl:when test="self::mml:math">true</xsl:when>
        <xsl:when test="self::abbr">true</xsl:when>
        <xsl:when test="self::affiliation">true</xsl:when>
        <xsl:when test="self::altIdentifier">true</xsl:when>
        <xsl:when test="self::analytic">true</xsl:when>
        <xsl:when test="self::add">true</xsl:when>
        <xsl:when test="self::am">true</xsl:when>
        <xsl:when test="self::att">true</xsl:when>
        <xsl:when test="self::author">true</xsl:when>
        <xsl:when test="self::bibl and is-inline($element/..)">true</xsl:when>
        <xsl:when test="self::biblScope">true</xsl:when>
        <xsl:when test="self::br">true</xsl:when>
        <xsl:when test="self::byline">true</xsl:when>
        <xsl:when test="self::c">true</xsl:when>
        <xsl:when test="self::caesura">true</xsl:when>
        <xsl:when test="self::choice">true</xsl:when>
        <xsl:when test="self::code">true</xsl:when>
        <xsl:when test="self::collection">true</xsl:when>
        <xsl:when test="self::country">true</xsl:when>
        <xsl:when test="self::damage">true</xsl:when>
        <xsl:when test="self::date">true</xsl:when>
        <xsl:when test="self::del">true</xsl:when>
        <xsl:when test="self::depth">true</xsl:when>
        <xsl:when test="self::dim">true</xsl:when>
        <xsl:when test="self::dimensions">true</xsl:when>
        <xsl:when test="self::editor">true</xsl:when>
        <xsl:when test="self::editionStmt">true</xsl:when>
        <xsl:when test="self::emph">true</xsl:when>
        <xsl:when test="self::ex">true</xsl:when>
        <xsl:when test="self::expan">true</xsl:when>
        <xsl:when test="self::figure[@place='inline']">true</xsl:when>
        <xsl:when test="self::foreign">true</xsl:when>
        <xsl:when test="self::forename">true</xsl:when>
        <xsl:when test="self::gap">true</xsl:when>
        <xsl:when test="self::genName">true</xsl:when>
        <xsl:when test="self::geogName">true</xsl:when>
        <xsl:when test="self::gi">true</xsl:when>
        <xsl:when test="self::gloss">true</xsl:when>
        <xsl:when test="self::graphic">true</xsl:when>
        <xsl:when test="self::height">true</xsl:when>
        <xsl:when test="self::hi[not(w:*)]">true</xsl:when>
        <xsl:when test="self::ident">true</xsl:when>
        <xsl:when test="self::idno">true</xsl:when>
        <xsl:when test="self::imprint">true</xsl:when>
        <xsl:when test="self::institution">true</xsl:when>
        <xsl:when test="self::lb">true</xsl:when>
        <xsl:when test="self::locus">true</xsl:when>
        <xsl:when test="self::mentioned">true</xsl:when>
        <xsl:when test="self::monogr">true</xsl:when>
        <xsl:when test="self::series">true</xsl:when>
        <xsl:when test="self::msName">true</xsl:when>
        <xsl:when test="self::name">true</xsl:when>
        <xsl:when test="self::note[@place='margin']">false</xsl:when>
        <xsl:when test="self::note[@place='bottom']">true</xsl:when>
        <xsl:when test="self::note[@place='comment']">true</xsl:when>
        <xsl:when test="self::note[@place='end']">true</xsl:when>
        <xsl:when test="self::note[@place='foot']">true</xsl:when>
        <xsl:when test="self::note[@place='inline']">true</xsl:when>
        <xsl:when test="self::note[parent::biblStruct]">true</xsl:when>
        <xsl:when test="self::note[parent::bibl]">true</xsl:when>
        <xsl:when test="self::num">true</xsl:when>
        <xsl:when test="self::orgName">true</xsl:when>
        <xsl:when test="self::orig">true</xsl:when>
        <xsl:when test="self::origDate">true</xsl:when>
        <xsl:when test="self::origPlace">true</xsl:when>
        <xsl:when test="self::origPlace">true</xsl:when>
        <xsl:when test="self::pb">true</xsl:when>
        <xsl:when test="self::persName">true</xsl:when>
        <xsl:when test="self::placeName">true</xsl:when>
        <xsl:when test="self::ptr">true</xsl:when>
        <xsl:when test="self::publisher">true</xsl:when>
        <xsl:when test="self::pubPlace">true</xsl:when>
        <xsl:when test="self::q[*]">false</xsl:when>
        <xsl:when test="self::q">true</xsl:when>
        <xsl:when test="self::said">true</xsl:when>
        <xsl:when test="self::ref">true</xsl:when>
        <xsl:when test="self::region">true</xsl:when>
        <xsl:when test="self::repository">true</xsl:when>
        <xsl:when test="self::roleName">true</xsl:when>
        <xsl:when test="self::rubric">true</xsl:when>
        <xsl:when test="self::seg">true</xsl:when>
        <xsl:when test="self::sic">true</xsl:when>
        <xsl:when test="self::settlement">true</xsl:when>
        <xsl:when test="self::soCalled">true</xsl:when>
        <xsl:when test="self::summary">true</xsl:when>
        <xsl:when test="self::supplied">true</xsl:when>
        <xsl:when test="self::surname">true</xsl:when>
        <xsl:when test="self::term">true</xsl:when>
        <xsl:when test="self::textLang">true</xsl:when>
        <xsl:when test="self::title">true</xsl:when>
        <xsl:when test="self::unclear">true</xsl:when>
        <xsl:when test="self::val">true</xsl:when>
        <xsl:when test="self::width">true</xsl:when>
        <xsl:when test="self::dynamicContent">true</xsl:when>
        <xsl:when test="self::w:drawing">true</xsl:when>
        <xsl:when test="self::m:oMath">true</xsl:when>
        <xsl:otherwise>
          <xsl:choose>
            <xsl:when test="empty($element/..)">false</xsl:when>
            <xsl:when test="is-inline($element/..)">true</xsl:when>
            <xsl:otherwise>false</xsl:otherwise>
          </xsl:choose>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </xsl:function>

  <doc xmlns="http://www.oxygenxml.com/ns/doc/xsl">
    <desc>Is given an element and says whether the context is at the
    level of a block</desc>
  </doc>
  <xsl:function name="blockContext" as="xs:boolean">
    <xsl:param name="element"/>
    <xsl:for-each select="$element">
      <xsl:choose>
	<xsl:when test="parent::note[@place='foot'] and self::gap">false</xsl:when>
	<xsl:when test="parent::note[@place='foot' or @place='bottom']">true</xsl:when>
	<xsl:when test="parent::body">true</xsl:when>
	<xsl:when test="parent::div">true</xsl:when>
	<xsl:when test="parent::titlePage">true</xsl:when>
	<xsl:otherwise>false</xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </xsl:function>


</xsl:stylesheet>
