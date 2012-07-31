<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    

    <xsl:template match="stage">
            <span class="stage">
                <xsl:apply-templates/>
            </span>        
    </xsl:template>

    <xsl:template match="sp">
        <dl>
            <dt>
                <xsl:apply-templates select="speaker"/>
            </dt>
            
            <dd>

                <xsl:apply-templates
                    select="p | l | lg | seg | ab | stage | lb"/>
            </dd>
        </dl>
    </xsl:template>    
    
    <xsl:template match="sp/p">
        <p>
            <a class='lb'>
                <xsl:attribute name="xml:id"><xsl:value-of select="preceding::lb[position() = '1']/@xml:id"/></xsl:attribute>
            </a>            
            <xsl:apply-templates></xsl:apply-templates>
        </p>
    </xsl:template>
    
    <xsl:template match="milestone"></xsl:template>
    <xsl:template match="hi"></xsl:template>
    
    <xsl:template match="lb">
        <xsl:choose>
            <xsl:when test="preceding-sibling::speaker">
                
                
            </xsl:when>
            <xsl:otherwise>                
                <a class='lb'>
                    <xsl:attribute name="xml:id"><xsl:value-of select="@xml:id"/></xsl:attribute>
                </a>                
            </xsl:otherwise>
        </xsl:choose>

    </xsl:template>
        
    <xsl:template match="bibl">        
        <xsl:choose>
            <xsl:when test="parent::cit">
                <div class="citbibl">
                    <xsl:text>(</xsl:text>
                    <xsl:apply-templates/>
                    <xsl:text>)</xsl:text>
                </div>
            </xsl:when>
            <xsl:otherwise>
                <span class="bibl">
                    <xsl:attribute name="class"><xsl:value-of select="@xml:id"/></xsl:attribute>
                    <xsl:apply-templates/>
                </span>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>    
    
    <xsl:template match="bibl//quote">
        <xsl:apply-templates></xsl:apply-templates>
    </xsl:template>        
        
</xsl:stylesheet>