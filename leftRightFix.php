<?php

/*$fullPaths = [
    'A'  => 'A',
    'B'  => 'A / B',
    'C1' => 'A / B / C',
    'D'  => 'A / B / D',
    'C2' => 'A / C',
    'E'  => 'E',
    'F'  => 'E / F',
    'G'  => 'G'
];*/

$fullPaths = [];
$dbh  = new \PDO('mysql:host=localhost;dbname=veolia', 'root', '') or die('Cnx impossible');
$stmt = $dbh->prepare('select entity_id, full_label from entity where client_id = 103 order by parent_id ASC, full_label ASC');
$stmt->execute();

while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $fullPaths[$res['entity_id']] = $res['full_label'];
}


function getNextChild(array &$tree, $key, $path)
{
    $nodes = explode(' / ', $path, 2);
    $node  = $nodes[0];
    if (!isset($tree[$node])) {
        $tree[$node] = [
            'key'      => null,
            'children' => []
        ];
    } else {

    }
    if (count($nodes) > 1) {
        getNextChild($tree[$node]['children'], $key, $nodes[1]);
    } else {
        // La clé correspond toujours au dernier niveau du full_label
        $tree[$node]['key'] = $key;
        //echo "setting up key: $key\n";
    }
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
foreach ($fullPaths as $key => $fullPath) {
    //echo "cur path: $fullPath\n";
    getNextChild($rootTrees, $key, $fullPath);
    //echo "---\n";
}

// pour tous les "roots", mise à jour des lft/rgt
foreach ($rootTrees as &$rootTree) {
    $lft = 1;
    updateNextNode($rootTree, $lft);
}
