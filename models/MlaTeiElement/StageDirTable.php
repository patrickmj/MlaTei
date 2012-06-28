<?php

class MlaTeiElement_StageDirTable extends MlaTeiElementTable
{
    

    public function findSurroundingStageDir($lineNum)
    {
        $select = $this->getSelect();
        $select->where("n <= ?", $lineNum);
        $select->where("last_n >= ?", $lineNum);
        $select->limit(1);
        return $this->fetchObject($select);
    }    
    
    
}