<?php
$pageTitle = "Appendix Search Results";
head(array('title'=>$pageTitle,'bodyid'=>'mla-tei-results','bodyclass' => 'browse'));

?>


<div id="primary" class="browse">
<h1><?php echo $count; ?> results in appendix for "<?php echo $search; ?>"</h1>
<div id="pagination-top" class="pagination"><?php echo pagination_links(); ?></div>
<div style="clear:both;"></div>
<!--  Appendix Ps -->
    <div id='mla-appendix-ps'>
        <?php foreach($discussions as $discussion) : ?>
   
            <div class='mlatei-discussion-wrap' id='<?php echo $discussion->xml_id; ?>'>
                <div class='mlatei-discussion-references'>
                    <ul class='mlatei-discussion-nav'>
                        <li id='mlatei-discussion-bib-<?php echo $discussion->id; ?>-nav' class='mla-reveal-open' >Note Bibliography</li>
                        <li id='mlatei-discussion-passages-<?php echo $discussion->id; ?>-nav' class='mla-reveal-open' >Passages Mentioned</li>                    
                    </ul>
                </div>     
            
            <h3><?php echo $discussion->label ;?></h3>
                
                <?php $tags = $discussion->getTags();
               
                ?>
                <div class='tags'>
                    <?php echo tag_string($tags,  WEB_ROOT . '/items/browse/type/Commentator/sort_field/Dublin+Core,Title/tag/'); ?>
                </div>
                <div class='mlatei-discussion-content-wrap'>
                    <?php echo $discussion->html; ?>
                </div>
                <?php $secondaryHTML .= "<!-- Bibliography for discussion -->
                                <div id='mlatei-discussion-bib-{$discussion->id}-wrap' class='mlatei-discussion-bib-wrap' >
                                 <h3>Note Bibliography</h3>
                                 ";
                        
                        $discBib = mla_get_bibliography_for_discussion($discussion);
                        $editions = mla_get_editions_for_discussion($discussion);
                        $bib = array_merge($discBib, $editions);
                        
                        foreach($bib as $entry) {
                            $secondaryHTML .= "<div class='mla-bib-entry'>";
                            $secondaryHTML .= $entry->html;
                            $secondaryHTML .= "</div>";
                        }
                        $secondaryHTML .= "</div>"; 
            
                ?>
                <?php $secondaryHTML .= "<!-- Passages referred to  -->
                            <div id='mlatei-discussion-passages-{$discussion->id}-wrap' class='mlatei-discussion-passages-wrap' >
                                <h3>Passages</h3>
                            "; 
                        $passages = mla_get_passages_for_discussion($discussion);
                        foreach($passages as $passage) {
                            $secondaryHTML .= "
                                    <div class='mla-passage'>
                                        <p class='mla-passage-line'>
                                        {$passage->n}
                                        </p>
                                        <div class='mla-passage-html'>
                                        {$passage->html}
                                        </div>   
                                    </div>                                         
                            ";
            
                        }
                        $secondaryHTML .= "</div>";
                ?>
                
                
            </div>
        <?php endforeach; ?>
    </div>

<br/>
<div id="pagination-bottom" class="pagination"><?php echo pagination_links(); ?></div>
<br/><br/>
</div>
<div id="secondary">

<?php echo $secondaryHTML; ?>
    



</div><!-- end secondary -->


<?php foot(); ?>