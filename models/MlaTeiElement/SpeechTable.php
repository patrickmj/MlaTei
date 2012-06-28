<?php

class MlaTeiElement_SpeechTable extends MlaTeiElementTable
{
    
    public function findSurroundingSpeech($lineNum)
    {
        $select = $this->getSelect();
        $select->where("n <= ?", $lineNum);
        $select->where("last_n >= ?", $lineNum);
        $select->limit(1);
        return $this->fetchObject($select);        
    }
}