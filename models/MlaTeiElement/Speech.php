<?php

class MlaTeiElement_Speech extends MlaTeiElement
{
    public $role_id;
    public $role_xml_id;
    public $first_line_xml_id;
    public $last_n;
    public $last_line_xml_id;
    public $n;
    public $item_id; 
    
    
    public function previousSpeech()
    {
        return $this->neighborSpeech('previous');
    }
    
    public function nextSpeech()
    {
        return $this->neighborSpeech('next');        
    }
    
    private function neighborSpeech($dir)
    {
        $operator = ($dir == 'previous') ? "<" : ">";
        $table = $this->getTable();
        $select = $this->getSelect();
        $select->where("n $operator ?", $this->n);
        $select->limit(1);
        return $table->fetchObject($select);        
    }
    
}