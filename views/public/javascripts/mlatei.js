
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
        
        targetId = '#' + this.id.substring(0, this.id.length - 4) + '-wrap';
        
        target = jQuery(targetId);
        offset = jQuery(this.parentNode).offset();
        jQuery('#secondary').offset({top: offset.top});
        jQuery('#secondary > div').not(target).hide();
        target.toggle();
        content = jQuery('#content');
        content.height(content[0].scrollHeight);
    },
    
    refClick: function(event) {
        target = jQuery(event.target);
        href = target.attr('href');
        console.log(href);
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
                console.log(refClass);
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
        console.log('--------------');
        refClasses.forEach(function(ref) {
            
            if((ref != 'mla-convos') && (ref != '')) {
               jQuery('#' + ref).show();
               console.log(ref);   
           } 
        });
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
}); 