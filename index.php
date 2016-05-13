<?php
require './autoload.php';

use Psi\NestedSets\Tree as Tree;
use Psi\NestedSets\Node as Node;

$tree = new Tree();
$root = $tree->addRoot(new Node(array('prop1' => 1, 'prop2' => 2)));
$tree->addNode($root, new Node(array('prop1' => 11, 'prop2' => 22)));

echo $tree;