<?php
namespace PhpNestedSets;

/**
 * 
 */
class Tree extends TreeNode
{
    protected $globalUid = 1;
    protected $nodes     = array();
        
    /**
     * 
     * @param type $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
        $this->parent = null;
        $this->lft    = 1;
        $this->rgt    = 2;
        $this->depth  = 0;
        $this->nodes  = array();
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