<?php
require './vendor/autoload.php';

use PhpNestedSets\Tree;
use PhpNestedSets\TreeNode;

$root = new Tree('Root');
$a    = $root->addChild(new TreeNode('A'));
$b1   = $a->addChild(new TreeNode('B1'));
$b2   = $a->addChild(new TreeNode('B2'));
$c    = $root->addChild(new TreeNode('C'));
$d1   = $c->addChild(new TreeNode('D1'));

echo $root->save();
var_dump($root->getNumNodes());