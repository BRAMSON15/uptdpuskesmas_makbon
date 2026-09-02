<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $nama = $_POST['nama'] ?? 'Nama Pegawai';
    $jabatan = $_POST['jabatan'] ?? 'Jabatan Baru';
    
    $stmt = $pdo->prepare("INSERT INTO struktur_organisasi (parent_id, nama, jabatan, urutan) VALUES (?, ?, ?, 0)");
    $stmt->execute([$parent_id, $nama, $jabatan]);
    echo json_encode(['status' => 'success', 'id' => $pdo->lastInsertId()]);
    exit;
}

if ($action === 'edit') {
    $id = (int)$_POST['id'];
    $nama = $_POST['nama'] ?? '';
    $jabatan = $_POST['jabatan'] ?? '';
    
    $stmt = $pdo->prepare("UPDATE struktur_organisasi SET nama=?, jabatan=? WHERE id=?");
    $stmt->execute([$nama, $jabatan, $id]);
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'delete') {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM struktur_organisasi WHERE id=?");
    $stmt->execute([$id]);
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'get') {
    $data = $pdo->query("SELECT * FROM struktur_organisasi ORDER BY urutan ASC, id ASC")->fetchAll();
    
    function buildHierarchy($elements, $parentId = null) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = buildHierarchy($elements, $element['id']);
                $node = [
                    'id' => $element['id'],
                    'title' => $element['jabatan'],
                    'name' => $element['nama']
                ];
                if ($children) {
                    $node['children'] = $children;
                }
                $branch[] = $node;
            }
        }
        return $branch;
    }
    
    $tree = buildHierarchy($data);
    if (count($tree) > 0) {
        echo json_encode($tree[0]);
    } else {
        echo json_encode(null);
    }
    exit;
}
