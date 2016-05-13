<?php
namespace Psi\NestedSets;

/**
 * 
 */
class Node
{
    protected $uid;
    protected $lft;
    protected $rgt;
    protected $level;
    protected $values;
    
    protected $children;
    protected $isRoot = false;
    protected $rootUid;
    
    /**
     * 
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }
    
    /**
     * 
     * @param int $level
     * @param int $lft
     * @param int $rgt
     * @param int $uid
     */
    public function setNodeProperties($level, $lft, $rgt, $uid, $isRoot = false)
    {
        $this->level  = $level;
        $this->lft    = $lft;
        $this->rgt    = $rgt;
        $this->uid    = $uid;
        $this->isRoot = $isRoot;
    }
    
    /**
     * 
     * @return type
     */
    public function getUid()
    {
        return $this->uid;
    }
    
    /**
     * 
     * @return type
     */
    public function isRoot()
    {
        return $this->isRoot;
    }
    
    public function getLevel()  { return $this->level; }
    
    public function getRoot()     { return $this->rootUid; }
    public function setRoot($uid) { $this->rootUid = $uid; }
    
    public function getLft()     { return $this->lft; }
    public function setLft($lft) { $this->lft = $lft; }
    
    public function getRgt()     { return $this->rgt; }
    public function setRgt($rgt) { $this->rgt = $rgt; }
}