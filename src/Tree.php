<?php
namespace PhpNestedSets;

/**
 * 
 */
class Tree extends TreeNode
{
    protected $roots     = array();
    protected $nodes     = array();
    protected $globalUid = 1;
    
//    public function __construct($node)
//    {
//        $this = $node;
//        $this->parent = null;
//        //$uid = $this->autoinc();
//        //$root->setNodeProperties(0, 1, 2, $uid, true);
//        //$this->setRoot($uid);
//        //$this->roots[$uid] = $root;
//        //$this->nodes[$uid] = array();
//        
//        return $this;
//    }
    
    /**
     * 
     * @param type $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
        $this->parent = null;
        $this->lft   = 1;
        $this->rgt   = 2;
        $this->level = 0;
        $this->nodes = array();
        $this->setRoot($this);
        $this->addNode($this);
    }
    
    /**
     * 
     * @return int
     */
    public function autoinc()
    {
        return $this->globalUid++;
    }
    
    /**
     * Array containing all tree nodes.
     * @param \PhpNestedSets\TreeNode $node
     */
    protected function addNode(TreeNode $node)
    {
        $this->nodes[] = $node;
    }
    
    /**
     * 
     * @return int
     */
    public function getNumNodes()
    {
        return count($this->nodes);
    }
}