
var MlaTei = {
    
    
    toggleSection: function() {        
        jQuery('#secondary > div').hide();
        target = this.id.substring(0, this.id.length - 4);
        jQuery('#' + target).toggle();
        actionSpan = jQuery('.action', this);
        if(jQuery('#' + target).css('display') == 'none') {
            actionSpan.html("Show");
        } else {
            actionSpan.html("Hide");
        }
    },
    
    toggleDiscussionDetail: function() {
        var el = jQuery(this);

        el.toggleClass('mla-reveal-open');
        el.toggleClass('mla-reveal-close');
        var otherNav = jQuery("ul.mlatei-discussion-nav > li").not(el);
        otherNav.removeClass('mla-reveal-close');
        otherNav.addClass('mla-reveal-open');
        var targetId = '#' + this.id.substring(0, this.id.length - 4) + '-wrap';
        
        var target = jQuery(targetId);
        var offset = jQuery(this.parentNode).offset();
        jQuery('#secondary').offset({top: offset.top});
        jQuery('#secondary > div').not(target).hide();
        target.toggle();
        content = jQuery('#content');
        content.height(content[0].scrollHeight - 56);
    },
    
    refClick: function(event) {
        target = jQuery(event.target);
        href = target.attr('href');
        //do some branching based on the ref type, which is second part of class
        refClasses = target.attr('class').split(' ');
        ref = null;
        switch(refClasses[1]) {

            //@TODO: possible danger of same line id showing up on pages
            case 'lb':
                ref = jQuery(href);
                break;

                
                
            //in xsl pmj_mod, bibls are given a class for what should be an id.
            //this lets me have the bibl entry show up multiple times on commentator page
            //and get selected via class
            
            default:
                refClass = href.substr(1);
                ref = jQuery('.' + refClass);                
                break;
        
        }      
        if(ref) {
            if(ref.attr('style')) {
                ref.attr('style', '');
            } else {
                ref.attr('style', 'color: red');    
            }            
        }

        event.preventDefault();
    },
    
    convoClick: function(event) {
        target = jQuery(event.target);
        jQuery('.mlatei-discussion-wrap').hide();
        refClasses = target.attr('class').split(' ');        
        jQuery('li.mla-in-convo').not(target.parent()).hide();
        refClasses.forEach(function(ref) {            
            if((ref != 'mla-convos') && (ref != '')) {
               jQuery('#' + ref).show();
           } 
        });
    },
    
    /**
     * toggleDetails
     * assumes that the details in question are the next sibling div to the parent element
     */
    
    toggleDetails: function() {
        var el = jQuery(this);
        el.toggleClass('mla-reveal-open');
        el.toggleClass('mla-reveal-close');
        
        el.parent().next('div.mla-details').toggle();
    }
    
    
    
    
    
};

jQuery(document).ready(function() {
    jQuery('.mla-ref').click(MlaTei.toggleSection);
    jQuery('ul.mlatei-discussion-nav > li').click(MlaTei.toggleDiscussionDetail);
    jQuery('.ref').click(function(event) {
        MlaTei.refClick(event);
    });
    jQuery('.mla-convos').click(function(event) {
        MlaTei.convoClick(event);
    });
    jQuery('#convo-reset').click(function(event) {
        jQuery('.mlatei-discussion-wrap').show(); 
        jQuery('li.mla-in-convo').show();
    });
    jQuery('a.mla-toggle-details').click(MlaTei.toggleDetails);
}); 