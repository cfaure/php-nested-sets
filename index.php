<?php

/**
 * 
 */
class GacTree
{
    protected $roots     = array();
    protected $nodes     = array();
    protected $globalUid = 1;
    
    public function __construct()
    {
    }
    
    /**
     * 
     * @param GacTreeNode $root
     */
    public function addRoot(GacTreeNode $root)
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
     * @param GacTreeNode $parent
     * @param GacTreeNode $child
     */
    public function addNode(GacTreeNode $parent, GacTreeNode $child)
    {
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
        foreach ($nodes as $uid => $node) {
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
     * @return GacTreeNode
     */
    public function getRoot($uid)
    {
        return $this->roots[$uid];
    }
    
    /**
     * 
     */
    public function __toString()
    {
        foreach ($this->roots as $rootUid => $rootNode)
        {
            echo "Root [0, {$rootNode->getLft()}, {$rootNode->getRgt()}]\n";
            foreach ($this->nodes[$rootUid] as $node) {
                echo "Node [{$node->getLft()}, {$node->getLft()}, {$node->getRgt()}]\n";    
            }
        }
    }
}

class GacTreeNode
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


$tree = new GacTree();
$root = $tree->addRoot(new GacTreeNode(array('prop1' => 1, 'prop2' => 2)));
$tree->addNode($root, new GacTreeNode(array('prop1' => 11, 'prop2' => 22)));

echo $tree;