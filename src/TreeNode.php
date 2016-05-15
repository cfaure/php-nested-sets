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
    protected $depth;
    
    public $data;
    protected $parent;   // TreeNode
    protected $children; // TreeNodeList : Array<TreeNode>
    protected $root;     // Tree
    
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
        $child->setDepth($this->depth + 1);
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
     * @return type
     */
    public function getUid()
    {
        return $this->uid;
    }
    
    /**
     * 
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }
    
    /**
     * 
     * @param int $depth
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
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
     * 
     * @return string
     */
    public function __toString()
    {
        ob_start();
        $this->toString();
        $output = ob_get_clean();
        
        return $output;
    }
    
    /**
     * 
     */
    protected function toString()
    {
        $spaces = str_repeat('-', $this->depth);
        echo "$spaces{$this->data} [{$this->depth}]: {$this->lft} {$this->rgt}<br/>\n";
        foreach ($this->children as $child) {
            $child->toString();
        }
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
    }
}