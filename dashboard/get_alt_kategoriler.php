<?php
require __DIR__ . '/init.php';

$kategori_id = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : 0;

$query = "SELECT id, isim FROM alt_kategoriler WHERE kategori_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $kategori_id);
$stmt->execute();
$stmt->bind_result($id, $isim);

$alt_kategoriler = [];
while ($stmt->fetch()) {
    $alt_kategoriler[] = ['id' => $id, 'isim' => $isim];
}

$stmt->close();

header('Content-Type: application/json');
echo json_encode($alt_kategoriler);
?>