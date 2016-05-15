<?php
namespace PhpNestedSets;

/**
 * 
 */
class TreeNode
{
    protected $uid;
    protected $lft;
    protected $rgt;
    protected $level;
    
    public $data;
    protected $parent;   // TreeNode
    protected $children; // TreeNodeList : Array<TreeNode>
    protected $root;     // TreeNode
        
    protected $rootUid;
    
    /**
     * 
     * @param array $values
     */
    public function __construct($data)
    {
        $this->data     = $data;
        $this->children = array();
    }
    
    /**
     * 
     * @param Node $parent
     * @param Node $child
     */
    public function addChild(TreeNode $child)
    {
        $root = $this->getRoot();
        
        $root->addNode($this);
        $child->setParent($this);
        $child->setRoot($root);
        $this->children[] = $child;
        
        return $child;
    }
    
    /**
     * 
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * 
     * @return TreeNode
     */
    public function getRoot()
    {
        if ($this->root instanceof Tree) {
            return $this->root;    
        }
        throw new \Exception('Invalid root node');
    }
    
    /**
     * Set reference to "root" node. It must be an instance of Tree.
     * 
     */
    public function setRoot(Tree $node)
    {
        $this->root = $node;
    }
    
    /**
     * 
     * @param \Psi\NestedSets\TreeNode $node
     */
    public function setParent(TreeNode $node)
    {
        $this->parent = $node;
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
    
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * 
     * @return int
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * 
     * @param int $lft
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    /**
     * 
     * @return int
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * 
     * @param type $rgt
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }

    /**
     * Recursively sets lft and rgt values for each node.
     */
    public function save()
    {
        $children     = $this->getChildren();
        $isFirstChild = true;
        foreach ($children as $child) {
            if ($isFirstChild) {
                $child->lft   = $this->lft + 1;
                $child->rgt   = $this->lft + 2;
                $isFirstChild = false;
            } else {
                $child->lft = $this->rgt;
                $child->rgt = $this->rgt + 1;
            }
            $child->save();
            $this->rgt = $child->rgt + 1;
        }
        echo "{$this->data}: {$this->lft} {$this->rgt}<br/>";
    }
}