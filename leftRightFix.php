<?php

$fullPaths = [
    'A',
    'A / B',
    'A / B / C',
    'A / B / D',
    'A / C',
    'E',
    'E / F',
    'G'
];

function getNextChild(array &$tree, $path)
{
    $nodes = explode(' / ', $path, 2);
    $node  = $nodes[0];
    if (!isset($tree[$node])) {
        $tree[$node] = [
            'children' => []
        ];
    }
    
    if (count($nodes) > 1) {
        getNextChild($tree[$node]['children'], $nodes[1]);
    }
    echo "retour sur: $node\n";
}

function updateNextNode(array &$node, &$lft)
{
    $node['lft'] = $lft;
    foreach ($node['children'] as &$child) {
        $lft = $lft + 1;
        updateNextNode($child, $lft);
    }
    $lft         = $lft + 1;
    $node['rgt'] = $lft;
}

// construction de l'arbre, stockage dans $tree
$rootTrees = [];
foreach ($fullPaths as $fullPath) {
    echo "cur path: $fullPath\n";
    getNextChild($rootTrees, $fullPath);
    echo "---\n";
}

// pour tous les "roots", mise à jour des lft/rgt
foreach ($rootTrees as &$rootTree) {
    $lft = 1;
    updateNextNode($rootTree, $lft);
}
print_r($rootTrees);
