<?php
// productdel.php

// 1) doğru path ile db bağlantısını include et
include __DIR__ . '/../z_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // 2) gelen product_id değerini sanitize et
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);

    if (is_numeric($product_id) && $product_id > 0) {
        // 3) önce resmi al
        $resim_query = "SELECT resim FROM urunler WHERE id = ?";
        $stmt_resim = $con->prepare($resim_query);
        $stmt_resim->bind_param("i", $product_id);
        $stmt_resim->execute();
        $stmt_resim->bind_result($resim);
        $stmt_resim->fetch();
        $stmt_resim->close();

        // 4) satırı sil
        $delete_query = "DELETE FROM urunler WHERE id = ?";
        $stmt_delete = $con->prepare($delete_query);
        $stmt_delete->bind_param("i", $product_id);
        if ($stmt_delete->execute()) {
            // 5) resim dosyasını da kaldır
            $resim_path = __DIR__ . '/../' . $resim;
            if ($resim && file_exists($resim_path)) {
                unlink($resim_path);
            }
            header("Location: products.php?msg=deleted");
            exit();
        } else {
            // DB hatası varsa detayını gör
            error_log("Ürün silme hatası: " . $stmt_delete->error);
            header("Location: products.php?msg=error");
            exit();
        }
    } else {
        header("Location: products.php?msg=error");
        exit();
    }
} else {
    header("Location: products.php");
    exit();
}
