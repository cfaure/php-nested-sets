<?php
namespace Psi\NestedSets;

/**
 * 
 */
class Tree
{
    protected $roots     = array();
    protected $nodes     = array();
    protected $globalUid = 1;
    
    public function __construct()
    {
    }
    
    /**
     * 
     * @param Node $root
     */
    public function addRoot(Node $root)
    {
        $uid = $this->autoinc();
        $root->setNodeProperties(0, 1, 2, $uid, true);
        $this->setRoot($uid);
        $this->roots[$uid] = $root;
        $this->nodes[$uid] = array();
        
        return $root;
    }
    
    /**
     * 
     * @param Node $parent
     * @param Node $child
     */
    public function addNode(Node $parent, Node $child)
    {
        $lft = 0; // FIXME:
        $rgt = 0; // FIXME: 
        $rootUid = $parent->getRoot();
        $child->setRoot($rootUid);
        $child->setNodeProperties($parent->getLevel() + 1, $parent->getLft() + 1, $parent->getLft() + 2, $this->autoinc());
        $this->nodes[$rootUid][$child->getUid()] = &$child;
        $this->updateNodes($rootUid, $lft, $rgt);
    }
    
    /**
     * 
     * @param type $rootUid
     * @param type $lft
     * @param type $rgt
     */
    protected function updateNodes($rootUid, $lft)
    {
        $nodes = $this->nodes[$rootUid];
        foreach ($nodes as $node) {
            if ($node->getRgt() >= $lft) {
                $node->setRgt($node->getRgt() + 2);
            }
            if ($node->getLft() > $lft) {
                $node->setLft($node->getLft() + 2);
            }
        }
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
     * 
     * @param int $uid
     * @return Node
     */
    public function getRoot($uid)
    {
        return $this->roots[$uid];
    }
    
    /**
     * 
     * @param type $uid
     */
    public function setRoot($uid)
    {
        $this->roots[$uid] = [];
    }
    
    /**
     * 
     */
    public function __toString()
    {
        foreach ($this->roots as $rootUid => $rootNode)
        {
            return "Root [0, {$rootNode->getLft()}, {$rootNode->getRgt()}]\n";
            foreach ($this->nodes[$rootUid] as $node) {
                return "Node [{$node->getLft()}, {$node->getLft()}, {$node->getRgt()}]\n";    
            }
        }
    }
}