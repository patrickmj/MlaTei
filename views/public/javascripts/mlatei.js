
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
    
    toggleDiscussionDetail: function(el) {
        
        if(el instanceof jQuery) {
           var id = el.attr('id');
           if(el.hasClass('mla-reveal-close')) {               ;
               var isOpen = true;
           }
        } else {
            el = jQuery(this);
            var id = this.id;
            var isOpen = false;
        }

        var otherNav = jQuery("ul.mlatei-discussion-nav > li").not(el);
        var targetId = '#' + id.substring(0, id.length - 4) + '-wrap';        
        var target = jQuery(targetId);
        var offset = el.parent().offset();
        var content = jQuery('#content');
        
        if(!isOpen) { 
            el.toggleClass('mla-reveal-open');
            el.toggleClass('mla-reveal-close');        
            otherNav.removeClass('mla-reveal-close');
            otherNav.addClass('mla-reveal-open');            
            jQuery('#secondary > div').not(target).hide();
            target.toggle();                
        }

        jQuery('#secondary').offset({top: offset.top});
        content.height(content[0].scrollHeight - 56);
    },
    
    refClick: function(event) {
        var target = jQuery(event.target);
        var discussionWrap = target.closest('.mlatei-discussion-wrap');
        var href = target.attr('href');
        //do some branching based on the ref type, which is second part of class
        var refClasses = target.attr('class').split(' ');
        var ref = null;
        switch(refClasses[1]) {

            //@TODO: possible danger of same line id showing up on pages
            case 'lb':
                ref = jQuery(href);
                var navEl = jQuery('ul.mlatei-discussion-nav > li.mla-discussion-nav-lb', discussionWrap);
                break;                
                
            //in xsl pmj_mod, bibls are given a class for what should be an id.
            //this lets me have the bibl entry show up multiple times on commentator page
            //and get selected via class
            
            case 'bibl':
                refClass = href.substr(1);
                ref = jQuery('.' + refClass);
                var navEl = jQuery('ul.mlatei-discussion-nav > li.mla-discussion-nav-bibl', discussionWrap);
                break;
                
            default:
                return;
        
        }      
        if(ref) {
            if(ref.attr('style')) {
                ref.attr('style', '');
            } else {
                ref.attr('style', 'color: red');    
            }         
            
            //if the parent secondary html section isn't shown, show it by digging up the relevant element
            MlaTei.toggleDiscussionDetail(navEl);
            
            
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