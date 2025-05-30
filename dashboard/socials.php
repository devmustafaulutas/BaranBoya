<?php
require __DIR__ . '/init.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delId = intval($_POST['delete_id']);
    mysqli_query($con, "DELETE FROM social WHERE id={$delId}");
    header('Location: socials.php');
    exit;
}
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $fa = mysqli_real_escape_string($con, $_POST['fa']);
    $socialLink = mysqli_real_escape_string($con, $_POST['social_link']);
    $stmt = $con->prepare("INSERT INTO social (name, fa, social_link) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $name, $fa, $socialLink);
    if ($stmt->execute()) {
        header('Location: socials.php');
        exit;
    } else {
        $msg = 'Ekleme sırasında hata oluştu.';
    }
}
if ($action === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $fa = mysqli_real_escape_string($con, $_POST['fa']);
        $socialLink = mysqli_real_escape_string($con, $_POST['social_link']);
        $stmt = $con->prepare("UPDATE social SET name=?, fa=?, social_link=? WHERE id=?");
        $stmt->bind_param('sssi', $name, $fa, $socialLink, $id);
        if ($stmt->execute()) {
            header('Location: socials.php');
            exit;
        } else {
            $msg = 'Güncelleme sırasında hata oluştu.';
        }
    }
    $stmt = $con->prepare("SELECT * FROM social WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resEdit = $stmt->get_result()->fetch_assoc();
    $selectedFa = $resEdit['fa'];
}
$resList = mysqli_query($con, "SELECT * FROM social ORDER BY id DESC");
include  __DIR__ .  '/header.php';
include  __DIR__ . '/sidebar.php';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card-header text-white d-flex justify-content-between align-items-center">
                        <h4 class="page-title">Sosyal Medya</h4>
                        <div class="text-end">
                            <a href="socials.php?action=create" class="btn btn-success">
                                <i class="ri-add-line"></i> Yeni Ekle
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($msg): ?>
                <div class="alert alert-danger"><?= $msg ?></div><?php endif; ?>
            <?php if ($action === 'create' || ($action === 'edit' && isset($resEdit))): ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <?= $action === 'create' ? 'Yeni Sosyal Medya Ekle' : 'Sosyal Medya Düzenle' ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="post"
                                    action="socials.php?action=<?= $action ?><?= $action === 'edit' ? '&id=' . $id : '' ?>">
                                    <div class="mb-3">
                                        <label class="form-label">İsim</label>
                                        <input type="text" name="name" class="form-control" required
                                            value="<?= $action === 'edit' ? htmlspecialchars($resEdit['name'], ENT_QUOTES) : '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">İkon Seç</label>
                                        <div>
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#faModal">
                                                <i id="faPreview"
                                                    class="<?= isset($selectedFa) ? $selectedFa : 'fab fa-facebook-f' ?> fa-2x me-2"></i>
                                                <span><?= isset($selectedFa) ? $selectedFa : 'Facebook' ?></span>
                                            </button>
                                        </div>
                                        <input type="hidden" name="fa" id="faInput"
                                            value="<?= isset($selectedFa) ? htmlspecialchars($selectedFa, ENT_QUOTES) : 'fab fa-facebook-f' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sosyal Link</label>
                                        <input type="url" name="social_link" class="form-control" required
                                            value="<?= $action === 'edit' ? htmlspecialchars($resEdit['social_link'], ENT_QUOTES) : '' ?>">
                                    </div>
                                    <div class="text-end">
                                        <button type="submit"
                                            class="btn btn-warning"><?= $action === 'create' ? 'Ekle' : 'Güncelle' ?></button>
                                        <a href="socials.php" class="btn btn-secondary">İptal</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="faModal" tabindex="-1" aria-labelledby="faModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="faModalLabel">Sosyal İkon Seç</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <?php
                                    $icons = [
                                        'fab fa-facebook-f' => 'Facebook',
                                        'fab fa-instagram' => 'Instagram',
                                        'fab fa-twitter' => 'Twitter',
                                        'fab fa-linkedin-in' => 'LinkedIn',
                                        'fab fa-youtube' => 'YouTube',
                                        'fab fa-pinterest' => 'Pinterest',
                                        'fab fa-github' => 'GitHub',
                                        'fab fa-telegram' => 'Telegram',
                                    ];
                                    foreach ($icons as $class => $label): ?>
                                        <div class="col-3 text-center">
                                            <button type="button" class="btn btn-light icon-btn p-2" data-icon="<?= $class ?>"
                                                data-label="<?= $label ?>">
                                                <i class="<?= $class ?> fa-2x"></i>
                                                <div class="small mt-1"><?= $label ?></div>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.querySelectorAll('.icon-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const cls = btn.getAttribute('data-icon');
                            const label = btn.getAttribute('data-label');
                            document.getElementById('faPreview').className = cls + ' fa-2x me-2';
                            document.querySelector('#faPreview + span').textContent = label;
                            document.getElementById('faInput').value = cls;
                            bootstrap.Modal.getInstance(document.getElementById('faModal')).hide();
                        });
                    });
                </script>
            <?php else: ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>İsim</th>
                                            <th>İkon</th>
                                            <th>Link</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        while ($row = mysqli_fetch_assoc($resList)): ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></td>
                                                <td><i class="<?= htmlspecialchars($row['fa'], ENT_QUOTES) ?> fa-2x me-1"></i>
                                                    <?= htmlspecialchars($row['fa'], ENT_QUOTES) ?></td>
                                                <td><a href="<?= htmlspecialchars($row['social_link'], ENT_QUOTES) ?>"
                                                        target="_blank">Link</a></td>
                                                <td>
                                                    <a href="socials.php?action=edit&id=<?= $row['id'] ?>"
                                                        class="btn btn-sm btn-warning">Düzenle</a>
                                                    <form method="post" action="socials.php" style="display:inline-block;">
                                                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                        <?php if ($i === 1): ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Kayıt bulunamadı.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

  <?php include "footer.php"; ?>
