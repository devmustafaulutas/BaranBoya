<?php
require __DIR__ . '/init.php';

$alt_kategori_id = isset($_GET['alt_kategori_id']) ? (int)$_GET['alt_kategori_id'] : 0;

$query = "SELECT id, isim FROM alt_kategoriler_alt WHERE alt_kategori_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $alt_kategori_id);
$stmt->execute();
$stmt->bind_result($id, $isim);

$alt_alt_kategoriler = [];
while ($stmt->fetch()) {
    $alt_alt_kategoriler[] = ['id' => $id, 'isim' => $isim];
}

$stmt->close();

header('Content-Type: application/json');
echo json_encode($alt_alt_kategoriler);
?>