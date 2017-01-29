<?php
/**
 *
 */
class FixEntities
{
    protected $dbh;

    /**
     * [__construct description]
     * @param [type] $dsn  [description]
     * @param [type] $user [description]
     * @param [type] $pass [description]
     */
    public function __construct($dsn, $user, $pass)
    {
        try {
            $this->dbh = new \PDO($dsn, $user, $pass);
        } catch (Exception $e) {
            die("Connexion bdd impossible.\n");
        }
    }

    /**
     * Construction de l'arbre en stockage récursif
     * @param  array  $tree [description]
     * @param  [type] $key  [description]
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    protected function getNextChild(array &$tree, $key, $path)
    {
        $nodes = explode(' / ', $path, 2);
        $node  = $nodes[0];
        if (!isset($tree[$node])) {
            $tree[$node] = [
                'key'      => null,
                'children' => []
            ];
        }
        if (count($nodes) > 1) {
            $this->getNextChild($tree[$node]['children'], $key, $nodes[1]);
        } else {
            // La clé correspond toujours au dernier niveau du full_label
            $tree[$node]['key'] = $key;
        }
    }

    /**
     * Mise à jour des lft/rgt à partir du stockage récursif
     * @param  array  $node [description]
     * @param  [type] $lft  [description]
     * @param  [type] $stmt [description]
     * @return [type]       [description]
     */
    protected function updateNextNode(array &$node, &$lft, PDOStatement $stmt)
    {
        $node['lft'] = $lft;
        foreach ($node['children'] as &$child) {
            $lft = $lft + 1;
            $this->updateNextNode($child, $lft, $stmt);
        }
        $lft         = $lft + 1;
        $node['rgt'] = $lft;
        $stmt->execute([
            'lft' => $node['lft'],
            'rgt' => $node['rgt'],
            'entityId'  => $node['key']
        ]);
    }

    /**
     * Run fix entity script.
     * @param  int $clientId  [description]
     * @param  int $updatedBy [description]
     * @param  int $parentId  [description]
     * @return void
     */
    public function run($clientId, $updatedBy, $parentId = null)
    {
        $parentIdFilter = $parentId !== null
            ? ' AND parent_id = '.(int)$parentId
            : '';
        $stmt = $this->dbh->prepare('
            SELECT entity_id, full_label
            FROM entity
            WHERE client_id = '.(int)$clientId.'
            '.$parentIdFilter.'
            ORDER BY parent_id ASC, full_label ASC, lft ASC
        ');
        $stmt->execute();
        $revFullPaths = [];
        $fullPaths    = [];
        while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (array_key_exists($res['full_label'], $revFullPaths)) {
                echo "ERROR (should stop) duplicate full_label: ".$res['full_label']."\n";
                continue;
            }
            $revFullPaths[$res['full_label']] = $res['entity_id'];
            $fullPaths[$res['entity_id']]     = $res['full_label'];
        }

        $rootTrees = [];
        foreach ($fullPaths as $key => $fullPath) {
            $this->getNextChild($rootTrees, $key, $fullPath);
        }

        $updateStmt = $this->dbh->prepare('
            UPDATE entity
            SET lft = :lft, rgt = :rgt, updated_by = '.(int)$updatedBy.', updated_at = NOW()
            WHERE
                client_id = '.(int)$clientId.'
                AND entity_id = :entityId
                AND (lft != :lft OR rgt != :rgt)
        ');
        foreach ($rootTrees as &$rootTree) {
            $lft = 1;
            $this->updateNextNode($rootTree, $lft, $updateStmt);
        }
    }
}

$fix = new FixEntities('mysql:host=localhost;dbname=entity', 'root', '');
$fix->run(103, 7719, 178546);
