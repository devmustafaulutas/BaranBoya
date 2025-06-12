<?php
// /vogue/dashboard/index.php
require __DIR__ . '/init.php';

if (empty($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';

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
    $labels[] = date('d M', strtotime($day));    
    $data[]   = $raw_visits[$day] ?? 0;
}

$contactRes = mysqli_query($con,
  "SELECT id, name, email, message, created_at
   FROM contact_messages
   ORDER BY created_at DESC
   LIMIT 3"
);
if (!$contactRes) {
    die("SQL Error (contact_messages): " . mysqli_error($con));
}
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="row mb-4">
        <div class="col">
          <h4 class="fw-bold">Dashboard</h4>
        </div>
      </div>

      <!-- Stats cards -->
      <div class="row g-3">
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
                <h4 class="mb-0"><?= getCount($con, 'service') ?></h4>
              </div>
            </div>
          </div>
        </div>

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
                <h4 class="mb-0"><?= getCount($con, 'blog') ?></h4>
              </div>
            </div>
          </div>
        </div>

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
                <h4 class="mb-0"><?= getCount($con, 'urunler') ?></h4>
              </div>
            </div>
          </div>
        </div>

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
                <h4 class="mb-0"><?= getCount($con, 'kategoriler') ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mt-4 align-items-stretch">
        <div class="col-lg-2 d-flex">
           <div class="card flex-fill h-100">
            <div class="card-body text-center">
              <h5 class="card-title mb-3">Toplam<br>İletişim Mesajı</h5>
              <h2><?= getCount($con, 'contact_messages') ?></h2>
            </div>
          </div>
        </div>
        <div class="col-lg-10 d-flex">
          <div class="row g-3 flex-fill align-items-stretch">
            <?php while ($m = mysqli_fetch_assoc($contactRes)): ?>
              <div class="col-lg-4 d-flex">
                <div class="card flex-fill shadow-sm h-100">
                  <div class="card-body">
                    <h6 class="card-title">
                      <?= htmlspecialchars($m['name'], ENT_QUOTES) ?>
                      <small class="text-muted d-block">
                        <?= date('d M Y, H:i', strtotime($m['created_at'])) ?>
                      </small>
                    </h6>
                    <p class="card-text text-truncate" style="max-height:3em; overflow:hidden;">
                      <?= htmlspecialchars($m['email'], ENT_QUOTES) ?>
                    </p>
                    <p class="card-text text-truncate" style="max-height:3em; overflow:hidden;">
                      <?= htmlspecialchars($m['message'], ENT_QUOTES) ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      </div>

      <!-- Weekly visits chart -->
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

<!-- ApexCharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  new ApexCharts(
    document.querySelector("#visits-chart"),
    {
      chart: { type: 'line', height: 300 },
      series: [{ name: 'Ziyaretler', data: <?= json_encode($data) ?> }],
      xaxis:  { 
        categories: <?= json_encode($labels) ?>,
        labels: { rotate: -45 }
      },
      stroke: { curve: 'smooth' },
      tooltip: {
        x: { formatter: val => val }
      }
    }
  ).render();
});
</script>

<?php include __DIR__ . '/footer.php'; ?>
