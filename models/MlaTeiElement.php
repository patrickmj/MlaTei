<?php

abstract class MlaTeiElement extends Omeka_Record
{
    public $id;
    public $item_id;
    public $xml;
    public $xml_id;
    public $html;

 
    
    protected $_related = array('Tags'=>'getTags');    
    
    protected function _initializeMixins()
    {
        $this->_mixins[] = new Taggable($this);
    }
            
}