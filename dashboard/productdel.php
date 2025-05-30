<?php
include __DIR__ ."../z_db.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);

    // Geçerli bir ürün ID'si olup olmadığını kontrol et
    if (is_numeric($product_id) && $product_id > 0) {
        // Ürünün resmini al
        $resim_query = "SELECT resim FROM urunler WHERE id = ?";
        $stmt_resim = $con->prepare($resim_query);
        $stmt_resim->bind_param("i", $product_id);
        $stmt_resim->execute();
        $stmt_resim->bind_result($resim);
        $stmt_resim->fetch();
        $stmt_resim->close();

        // Silme sorgusu
        $delete_query = "DELETE FROM urunler WHERE id = ?";
        $stmt_delete = $con->prepare($delete_query);
        $stmt_delete->bind_param("i", $product_id);
        if ($stmt_delete->execute()) {
            // Resmi sil
            if ($resim && file_exists("../" . $resim)) {
                unlink("../" . $resim);
            }
            header("Location: products.php?msg=deleted");
            exit();
        } else {
            header("Location: products.php?msg=error");
            exit();
        }
    } else {
        // Geçersiz ürün ID'si
        header("Location: products.php?msg=error");
        exit();
    }
} else {
    header("Location: products.php");
    exit();
}
?>