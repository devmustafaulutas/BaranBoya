<?php
require __DIR__ . '/init.php';

if (empty($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

include  __DIR__ .  '/header.php';
include  __DIR__ . '/sidebar.php';

function getCount(mysqli $con, string $table): int {
    $sql = "SELECT COUNT(*) FROM `$table`";
    $res = mysqli_query($con, $sql);
    if (!$res) {
        die("SQL Error ({$table}): " . mysqli_error($con));
    }
    $row = mysqli_fetch_row($res);
    return (int)$row[0];
}

$visitsRes = mysqli_query($con,
  "SELECT DATE(visited_at) AS dt, COUNT(*) AS cnt
   FROM page_visits
   WHERE visited_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
   GROUP BY DATE(visited_at)"
);
if (!$visitsRes) {
    die("SQL Error (page_visits): " . mysqli_error($con));
}
$raw_visits = [];
while ($r = mysqli_fetch_assoc($visitsRes)) {
    $raw_visits[$r['dt']] = (int)$r['cnt'];
}
$labels = $data = [];
for ($i = 6; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-{$i} days"));
    $labels[] = $day;
    $data[]   = $raw_visits[$day] ?? 0;
}
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="row mb-4">
        <div class="col"><h4 class="fw-bold">Dashboard</h4></div>
      </div>

      <div class="row g-3">
        <?php $cnt = getCount($con, 'service'); ?>
        <div class="col-lg-3 col-md-6 d-flex">
          <div class="card flex-fill">
            <div class="card-body d-flex align-items-center">
              <div class="avatar-sm flex-shrink-0">
                <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                  <i class="ri-git-merge-fill"></i>
                </span>
              </div>
              <div class="flex-grow-1 ms-3">
                <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Toplam Servisler</p>
                <h4 class="mb-0"><?= $cnt ?></h4>
              </div>
            </div>
          </div>
        </div>

        <?php $cnt = getCount($con, 'blog'); ?>
        <div class="col-lg-3 col-md-6 d-flex">
          <div class="card flex-fill">
            <div class="card-body d-flex align-items-center">
              <div class="avatar-sm flex-shrink-0">
                <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                  <i class="ri-pages-line"></i>
                </span>
              </div>
              <div class="flex-grow-1 ms-3">
                <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Toplam Blog</p>
                <h4 class="mb-0"><?= $cnt ?></h4>
              </div>
            </div>
          </div>
        </div>

        <?php $cnt = getCount($con, 'urunler'); ?>
        <div class="col-lg-3 col-md-6 d-flex">
          <div class="card flex-fill">
            <div class="card-body d-flex align-items-center">
              <div class="avatar-sm flex-shrink-0">
                <span class="avatar-title bg-light text-success rounded-circle fs-3">
                  <i class="ri-shopping-bag-3-line"></i>
                </span>
              </div>
              <div class="flex-grow-1 ms-3">
                <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Toplam Ürünler</p>
                <h4 class="mb-0"><?= $cnt ?></h4>
              </div>
            </div>
          </div>
        </div>

        <?php $cnt = getCount($con, 'kategoriler'); ?>
        <div class="col-lg-3 col-md-6 d-flex">
          <div class="card flex-fill">
            <div class="card-body d-flex align-items-center">
              <div class="avatar-sm flex-shrink-0">
                <span class="avatar-title bg-light text-warning rounded-circle fs-3">
                  <i class="ri-list-unordered"></i>
                </span>
              </div>
              <div class="flex-grow-1 ms-3">
                <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Toplam Kategoriler</p>
                <h4 class="mb-0"><?= $cnt ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mt-4">
        <div class="col-lg-6 d-flex">
          <div class="card flex-fill">
            <div class="card-body">
              <h5 class="card-title mb-3">Toplam İletişim Mesajı</h5>
              <h2><?= getCount($con, 'contact_messages') ?></h2>
            </div>
          </div>
        </div>
        <div class="col-lg-6 d-flex">
          <div class="card flex-fill">
            <div class="card-body">
              <h5 class="card-title mb-3">Son 5 İletişim Mesajı</h5>
              <div class="table-responsive">
                <table class="table table-striped mb-0">
                  <thead><tr><th>Ad Soyad</th><th>Email</th><th>Tarih</th></tr></thead>
                  <tbody>
                    <?php
                      $res = mysqli_query($con,
                        "SELECT name, email, created_at
                         FROM contact_messages
                         ORDER BY created_at DESC
                         LIMIT 5"
                      );
                      if (!$res) die("SQL Error (contact_messages): ".mysqli_error($con));
                      while($m = mysqli_fetch_assoc($res)):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($m['name']) ?></td>
                      <td><?= htmlspecialchars($m['email']) ?></td>
                      <td><?= $m['created_at'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mt-4">
        <div class="col-12 d-flex">
          <div class="card flex-fill">
            <div class="card-body">
              <h5 class="card-title mb-3">Haftalık Sayfa Ziyaretleri</h5>
              <div id="visits-chart" style="height:300px;"></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="assets/libs/apexcharts/apexcharts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  new ApexCharts(
    document.querySelector("#visits-chart"),
    {
      chart: { type: 'line', height: 300 },
      series: [{ name: 'Ziyaretler', data: <?= json_encode($data) ?> }],
      xaxis:  { categories: <?= json_encode($labels) ?> },
      stroke: { curve: 'smooth' },
      tooltip:{ x: { format: 'yyyy-MM-dd' } }
    }
  ).render();
});
</script>

<?php include "footer.php"; ?>