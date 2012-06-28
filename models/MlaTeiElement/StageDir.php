<?php
class MlaTeiElement_StageDir extends MlaTeiElement
{
    public $first_line_xml_id;
    public $last_n;
    public $last_line_xml_id;
    public $n;

    
    
    protected $_related = array('ContainingSpeech'=>'getContainingSpeech');
    
    public function getContainingSpeech()
    {
        return get_db()->getTable('MlaTeiElement_Speech')->findContainingSpeech(($this->n));
        
    }
    
    
}

